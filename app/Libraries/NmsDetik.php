<?php

namespace App\Libraries;

use App\Models\DeviceMonitoringModel;
use App\Models\DeviceNmsModel;
use App\Models\DeviceTrafficModel;

/**
 * Grafik "5 Menit" & "10 Menit" per detik (Traffic Monitoring, NMS Management: Live + History, Screen).
 *
 * Selama halaman dengan rentang 10 Menit terbuka, browser memanggil endpoint .../detik tiap 1 detik. Tiap panggilan:
 *   - satu SNMP GET kecil: counter octets interface + OID CPU/RAM (OID-nya dicari sekali lalu disimpan di cache),
 *   - satu ping (metrik device),
 *   - hasilnya masuk penyangga file writable/cache/nms_detik (hanya 10 menit terakhir, tanpa tabel database).
 * Banyak pengguna membuka grafik yang sama = perangkat tetap dibaca paling sering sekali per detik (file kunci).
 * Polling biasa, riwayat & alert (d_device_*) tidak berubah.
 */
class NmsDetik
{
    /** Lebar jendela grafik (detik). */
    public const JENDELA = 600;

    /** Jarak minimal antar sampel (detik). */
    private const JEDA = 0.7;

    /** Batas tunggu balasan SNMP (detik), tanpa pengulangan: sampel berikutnya sudah menyusul. */
    private const TIMEOUT = 1.0;

    /** Patokan counter lebih tua dari ini (detik) tidak dipakai: grafik baru dibuka lagi. */
    private const BASI = 15;

    /** Perangkat yang baru memperbarui counter tiap beberapa detik: rate terakhir dipertahankan paling lama sekian detik. */
    private const TAHAN = 10;

    /**
     * Ambil sampel baru (kalau sudah waktunya) lalu kembalikan titik yang lebih baru dari $sejak (ms).
     *
     * @param array      $d      baris d_device_monitoring
     * @param array|null $i      baris d_device_interface (null = tanpa traffic)
     * @param bool       $metrik ikut ambil latency, packet loss, CPU & RAM
     *
     * @return array{sekarang: int, traffic: list<array>, metrik: list<array>, sumber: string|null, pesan: string|null}
     */
    public static function ambil(array $d, ?array $i, bool $metrik, int $sejak = 0): array
    {
        $pesan = null;
        if (! (int) $d['monitoring_enabled'] || ! DeviceMonitoringModel::bisaPoll($d)) {
            $pesan = 'Monitoring / SNMP v2c device ini tidak aktif.';
        } elseif (! self::siapkanFolder()) {
            $pesan = 'Folder writable/cache tidak bisa ditulis.';
        } else {
            $pesan = self::sampel($d, $i, $metrik);
        }

        $isiMetrik = $metrik ? self::baca('dev', (int) $d['id']) : [];

        return [
            'sekarang' => self::ms(),
            'traffic'  => $i ? self::titik(self::baca('if', (int) $i['id']), $sejak, $sejak > 0 ? [] : self::awalTraffic((int) $i['id'])) : [],
            'metrik'   => $metrik ? self::titik($isiMetrik, $sejak, $sejak > 0 ? [] : self::awalMetrik((int) $d['id'])) : [],
            'sumber'   => $isiMetrik['sumber'] ?? null,
            'pesan'    => $pesan,
        ];
    }

    /** Perangkat yang tidak membalas dicoba lagi setelah sekian detik (halaman Screen: jangan menahan grafik perangkat lain). */
    private const TUNDA_GAGAL = 5;

    /** Batas waktu satu putaran pembacaan banyak perangkat (detik). */
    private const BATAS_PUTARAN = 3.0;

    /**
     * Traffic banyak interface dari banyak perangkat sekaligus (halaman Screen). Satu SNMP GET per perangkat.
     *
     * @param list<array>     $daftar baris d_device_interface
     * @param array<int, int> $sejak  id interface => waktu titik terakhir yang sudah dimiliki browser (ms)
     *
     * @return array{sekarang: int, traffic: array<int, list<array>>, pesan: array<int, string>} pesan per id interface
     */
    public static function ambilBanyak(array $daftar, array $sejak): array
    {
        $pesan = [];
        $kelompok = [];
        foreach ($daftar as $i) {
            $kelompok[(int) $i['device_id']][(int) $i['id']] = $i;
        }

        $device = $kelompok
            ? array_column((new DeviceMonitoringModel())->whereIn('id', array_keys($kelompok))->findAll(), null, 'id')
            : [];
        if (self::siapkanFolder()) {
            // perangkat yang paling lama belum dibaca didahulukan
            uasort($kelompok, static function ($a, $b) {
                $lama = static fn (array $x) => min(array_map(static fn ($id) => (int) (self::baca('if', $id)['diambil'] ?? 0), array_keys($x)));

                return $lama($a) <=> $lama($b);
            });
            $mulai = microtime(true);
            foreach ($kelompok as $deviceId => $isi) {
                $d     = $device[$deviceId] ?? null;
                $tidak = ! $d || ! (int) $d['monitoring_enabled'] || ! DeviceMonitoringModel::bisaPoll($d);
                $info  = $tidak ? 'Monitoring / SNMP v2c device ini tidak aktif.' : null;
                if (! $tidak && microtime(true) - $mulai < self::BATAS_PUTARAN) {
                    $info = self::sampelBanyak($d, $isi);
                }
                if ($info !== null) {
                    $pesan += array_fill_keys(array_keys($isi), $info);
                }
            }
        }

        $traffic = [];
        foreach ($daftar as $i) {
            $id           = (int) $i['id'];
            $s            = (int) ($sejak[$id] ?? 0);
            $traffic[$id] = self::titik(self::baca('if', $id), $s, $s > 0 ? [] : self::awalTraffic($id));
        }

        return ['sekarang' => self::ms(), 'traffic' => $traffic, 'pesan' => $pesan];
    }

    /** Baca counter beberapa interface satu perangkat dengan satu SNMP GET; kembalikan pesan kalau tidak membalas. */
    private static function sampelBanyak(array $d, array $daftar): ?string
    {
        $kunci = $isi = $oid = [];

        try {
            foreach ($daftar as $id => $i) {
                $k = self::kunci('if', $id);
                if (! $k) {
                    continue; // sedang dibaca pengguna lain
                }
                $kunci[$id] = $k;
                $x          = self::baca('if', $id);
                if (! empty($x['gagal']) && self::ms() - (int) $x['gagal'] < self::TUNDA_GAGAL * 1000) {
                    $tunda = true;

                    continue;
                }
                if (self::ms() - (int) ($x['diambil'] ?? 0) < self::JEDA * 1000) {
                    continue;
                }
                $isi[$id] = $x;
                foreach ((int) $i['counter_bit'] === 32 ? DeviceTrafficModel::OID_32 : DeviceTrafficModel::OID_HC as $arah => $o) {
                    $oid[$arah . '_' . $id] = $o . '.' . (int) $i['if_index'];
                }
            }
            if (! $isi) {
                return isset($tunda) ? 'Perangkat tidak membalas SNMP.' : null;
            }

            $klien = new SnmpKlien($d['ip_address'], DeviceMonitoringModel::dekripsi($d['snmp_community']), (int) $d['snmp_port'], self::TIMEOUT, 0);
            $mulai = microtime(true);
            $snmp  = $klien->get($oid);
            $t     = (int) round(($mulai + microtime(true)) / 2 * 1000);

            foreach ($isi as $id => $x) {
                $x['diambil'] = (int) round($mulai * 1000);
                unset($x['gagal']);
                if ($snmp['ok']) {
                    $nilai = ['in' => $snmp['nilai']['in_' . $id] ?? null, 'out' => $snmp['nilai']['out_' . $id] ?? null];
                    $x     = self::hitungTraffic($x, $nilai, (int) $daftar[$id]['counter_bit'] === 32 ? 32 : 64, $t, (int) $daftar[$id]['speed_mbps']);
                } else {
                    $x['gagal'] = self::ms();
                }
                self::tulis('if', $id, $x);
            }

            return $snmp['ok'] ? null : 'Perangkat tidak membalas SNMP.';
        } finally {
            foreach ($kunci as $k) {
                flock($k, LOCK_UN);
                fclose($k);
            }
        }
    }

    /** Baca perangkat sekali; kembalikan pesan kalau perangkat tidak membalas SNMP. */
    private static function sampel(array $d, ?array $i, bool $metrik): ?string
    {
        // masing-masing punya kunci sendiri: pengguna lain yang sedang membaca hal yang sama tidak diulang
        $kunciIf  = $i ? self::kunci('if', (int) $i['id']) : null;
        $kunciDev = $metrik ? self::kunci('dev', (int) $d['id']) : null;

        try {
            $isiIf  = $kunciIf ? self::baca('if', (int) $i['id']) : null;
            $isiDev = $kunciDev ? self::baca('dev', (int) $d['id']) : null;
            $baru   = static fn (?array $x) => $x !== null && (self::ms() - (int) ($x['diambil'] ?? 0)) < self::JEDA * 1000;
            if ($baru($isiIf)) {
                $isiIf = null;
            }
            if ($baru($isiDev)) {
                $isiDev = null;
            }
            if ($isiIf === null && $isiDev === null) {
                return null;
            }

            $oid = ['sysUpTime' => SnmpKlien::OID_SISTEM['sysUpTime']];
            $bit = 64;
            if ($isiIf !== null) {
                $bit = (int) $i['counter_bit'] === 32 ? 32 : 64;
                foreach ($bit === 64 ? DeviceTrafficModel::OID_HC : DeviceTrafficModel::OID_32 as $arah => $o) {
                    $oid[$arah] = $o . '.' . (int) $i['if_index'];
                }
            }
            $spek = null;
            if ($isiDev !== null) {
                $spek = cache(self::kunciSpek($d)); // null = belum dicari, dicari setelah perangkat terbukti membalas
                $oid += $spek['oid'] ?? [];
            }

            $klien = new SnmpKlien($d['ip_address'], DeviceMonitoringModel::dekripsi($d['snmp_community']), (int) $d['snmp_port'], self::TIMEOUT, 0);
            $mulai = microtime(true);
            $snmp  = $klien->get($oid);
            $akhir = microtime(true);
            $t     = (int) round(($mulai + $akhir) / 2 * 1000);

            if ($isiIf !== null) {
                $isiIf['diambil'] = (int) round($mulai * 1000);
                if ($snmp['ok']) {
                    $isiIf = self::hitungTraffic($isiIf, $snmp['nilai'], $bit, $t, (int) $i['speed_mbps']);
                }
                self::tulis('if', (int) $i['id'], $isiIf);
            }

            if ($isiDev !== null) {
                $ping   = DeviceNmsModel::ping($d['ip_address'], 1);
                $sumber = 'icmp';
                if ($ping === null) {
                    $sumber = 'snmp'; // server tidak bisa ping: waktu respon SNMP dipakai sebagai latency
                    $ping   = ['ms' => $snmp['ok'] ? round(($akhir - $mulai) * 1000, 2) : null, 'loss' => $snmp['ok'] ? 0.0 : 100.0];
                }
                [$cpu, $mem] = $snmp['ok'] && $spek ? self::nilaiCpuRam($spek, $snmp['nilai']) : [null, null];

                $isiDev['diambil'] = (int) round($mulai * 1000);
                $isiDev['sumber']  = $sumber;
                $isiDev['d'][]     = [$t, $ping['ms'], $ping['loss'], $cpu, $mem];
                self::tulis('dev', (int) $d['id'], $isiDev);
                if ($snmp['ok'] && ! is_array($spek)) {
                    self::cariSpek($d, $klien);
                }
            }

            return $snmp['ok'] ? null : 'Perangkat tidak membalas SNMP.';
        } finally {
            foreach ([$kunciIf, $kunciDev] as $k) {
                if ($k) {
                    flock($k, LOCK_UN);
                    fclose($k);
                }
            }
        }
    }

    /** Rate RX/TX dari selisih counter; titik baru ditambahkan ke $isi['d']. */
    private static function hitungTraffic(array $isi, array $nilai, int $bit, int $t, int $speed): array
    {
        $in  = DeviceTrafficModel::counterValid($nilai['in'] ?? null);
        $out = DeviceTrafficModel::counterValid($nilai['out'] ?? null);
        if ($in === null || $out === null) {
            return $isi;
        }

        $patokan = ['in' => $in, 'out' => $out, 't' => $t, 'bit' => $bit];
        $base    = $isi['base'] ?? null;
        $detik   = $base ? ($t - (int) $base['t']) / 1000 : 0;
        if (! $base || (int) $base['bit'] !== $bit || $detik <= 0 || $detik > self::BASI) {
            $isi['base'] = $patokan;
            unset($isi['rate']);

            return $isi;
        }

        $dRx = DeviceTrafficModel::selisih($in, (string) $base['in'], $bit);
        $dTx = DeviceTrafficModel::selisih($out, (string) $base['out'], $bit);
        if ($dRx === null || $dTx === null) { // counter reset / perangkat restart
            $isi['base'] = $patokan;
            unset($isi['rate']);

            return $isi;
        }
        // counter belum diperbarui perangkat: pakai rate terakhir, patokan lama tetap dipakai
        if ($dRx == 0.0 && $dTx == 0.0 && $detik < self::TAHAN && isset($isi['rate'])) {
            $isi['d'][] = [$t, $isi['rate'][0], $isi['rate'][1]];

            return $isi;
        }

        $rx   = (int) round($dRx * 8 / $detik);
        $tx   = (int) round($dTx * 8 / $detik);
        $maks = $speed > 0 ? $speed * 1e6 * 1.5 : null; // lonjakan tidak wajar, sama dengan polling biasa
        if ($maks !== null && ($rx > $maks || $tx > $maks)) {
            return $isi; // patokan lama dipertahankan, jadi terhitung rata pada sampel berikutnya
        }

        $isi['base'] = $patokan;
        $isi['rate'] = [$rx, $tx];
        $isi['d'][]  = [$t, $rx, $tx];

        return $isi;
    }

    private static function kunciSpek(array $d): string
    {
        return 'nms_detik_oid_' . (int) $d['id'] . '_' . md5($d['ip_address'] . '|' . $d['snmp_port'] . '|' . $d['vendor']);
    }

    /** Cari OID CPU & RAM perangkat lewat walk, simpan 1 jam (tidak lengkap = dicari lagi 5 menit kemudian). */
    private static function cariSpek(array $d, SnmpKlien $klien): void
    {
        $spek    = DeviceNmsModel::oidCpuRam($klien, (string) $d['vendor']);
        $lengkap = isset($spek['oid']['cpu0']) && $spek['ram'] !== null;
        cache()->save(self::kunciSpek($d), $spek, $lengkap ? 3600 : 300);
    }

    /** @return array{0: float|null, 1: float|null} [cpu %, ram %] */
    private static function nilaiCpuRam(array $spek, array $nilai): array
    {
        $cpu = $ram = [];
        foreach ($nilai as $nama => $v) {
            if (! is_numeric($v)) {
                continue;
            }
            if (str_starts_with((string) $nama, 'cpu')) {
                $cpu[] = max(0.0, min(100.0, (float) $v));
            } elseif (str_starts_with((string) $nama, 'ram')) {
                $ram[$nama] = (float) $v;
            }
        }

        $c = $cpu ? round($spek['cpu_maks'] ? max($cpu) : array_sum($cpu) / count($cpu), 2) : null;
        $m = null;
        if ($spek['ram'] === 'size' && isset($ram['ramUsed'], $ram['ramSize']) && $ram['ramSize'] > 0) {
            $m = $ram['ramUsed'] / $ram['ramSize'] * 100;
        } elseif ($spek['ram'] === 'free' && isset($ram['ramUsed'], $ram['ramFree']) && $ram['ramUsed'] + $ram['ramFree'] > 0) {
            $m = $ram['ramUsed'] / ($ram['ramUsed'] + $ram['ramFree']) * 100;
        } elseif ($spek['ram'] === 'persen' && $ram) {
            $m = max($ram);
        }

        return [$c, $m === null ? null : round(max(0, min(100, $m)), 2)];
    }

    // ===================== RIWAYAT 5 / 10 MENIT (Export PDF) =====================

    /** Rentang (detik) ini memakai data per detik: 5 Menit & 10 Menit. */
    public static function perDetik(int $detik): bool
    {
        return $detik > 0 && $detik <= self::JENDELA;
    }

    /** Bentuknya sama dengan DeviceNmsModel::history(). */
    public static function historyDevice(int $deviceId, int $jendela = self::JENDELA): array
    {
        $p     = self::titik(self::baca('dev', $deviceId), 0, self::awalMetrik($deviceId), $jendela);
        $kolom = static fn (int $k) => array_map(static fn ($x) => $x[$k] === null ? null : (float) $x[$k], $p);
        $ada   = static fn (array $x) => array_filter($x, static fn ($v) => $v !== null);
        $rata  = static fn (array $x, int $n) => ($x = $ada($x)) ? round(array_sum($x) / count($x), $n) : null;
        $maks  = static fn (array $x, int $n) => ($x = $ada($x)) ? round(max($x), $n) : null;

        [$lat, $loss, $cpu, $mem] = [$kolom(1), $kolom(2), $kolom(3), $kolom(4)];
        $n = count($p);

        return [
            'label'   => array_map(static fn ($x) => date('H:i:s', intdiv($x[0], 1000)), $p),
            'latency' => $lat,
            'loss'    => $loss,
            'cpu'     => $cpu,
            'mem'     => $mem,
            'ringkas' => [
                'sampel'   => $n,
                'lat_avg'  => $rata($lat, 2),
                'lat_max'  => $maks($lat, 2),
                'loss_avg' => $rata($loss, 2),
                'cpu_avg'  => $rata($cpu, 1),
                'cpu_max'  => $maks($cpu, 1),
                'mem_avg'  => $rata($mem, 1),
                'mem_max'  => $maks($mem, 1),
                'uptime'   => $n ? round(count($ada($lat)) / $n * 100, 2) : null,
            ],
        ];
    }

    /** Bentuknya sama dengan DeviceNmsModel::historyTraffic() / DeviceTrafficModel::grafik(). */
    public static function historyTraffic(int $interfaceId, int $jendela = self::JENDELA): array
    {
        $p    = self::titik(self::baca('if', $interfaceId), 0, self::awalTraffic($interfaceId), $jendela);
        $rx   = array_map(static fn ($x) => (int) $x[1], $p);
        $tx   = array_map(static fn ($x) => (int) $x[2], $p);
        $rata = static fn (array $x) => DeviceTrafficModel::formatBps($x ? round(array_sum($x) / count($x)) : null);
        $maks = static fn (array $x) => DeviceTrafficModel::formatBps($x ? max($x) : null);

        return [
            'label'   => array_map(static fn ($x) => date('H:i:s', intdiv($x[0], 1000)), $p),
            'rx'      => $rx,
            'tx'      => $tx,
            'ringkas' => ['sampel' => count($p), 'rx_avg' => $rata($rx), 'rx_max' => $maks($rx), 'tx_avg' => $rata($tx), 'tx_max' => $maks($tx)],
        ];
    }

    /** Sampel polling biasa 10 menit terakhir: mengisi grafik sebelum sampel per detik terkumpul. */
    private static function awalTraffic(int $interfaceId): array
    {
        $baris = \Config\Database::connect()->table(DeviceTrafficModel::TABEL_TRAFFIC)
            ->select('checked_at, rx_bps, tx_bps')
            ->where('interface_id', $interfaceId)
            ->where('checked_at >=', date('Y-m-d H:i:s', time() - self::JENDELA))
            ->orderBy('checked_at', 'ASC')->get()->getResultArray();

        return array_map(static fn ($x) => [strtotime($x['checked_at']) * 1000, (int) $x['rx_bps'], (int) $x['tx_bps']], $baris);
    }

    private static function awalMetrik(int $deviceId): array
    {
        $angka = static fn ($v) => $v === null ? null : (float) $v;
        $baris = \Config\Database::connect()->table('d_device_nms_metric')
            ->select('checked_at, latency_ms, packet_loss, cpu_usage, memory_usage')
            ->where('device_id', $deviceId)
            ->where('checked_at >=', date('Y-m-d H:i:s', time() - self::JENDELA))
            ->orderBy('checked_at', 'ASC')->get()->getResultArray();

        return array_map(static fn ($x) => [
            strtotime($x['checked_at']) * 1000,
            $angka($x['latency_ms']), $angka($x['packet_loss']), $angka($x['cpu_usage']), $angka($x['memory_usage']),
        ], $baris);
    }

    /** Titik dalam jendela & lebih baru dari $sejak; $awal dipakai hanya di waktu yang belum punya sampel per detik. */
    private static function titik(array $isi, int $sejak, array $awal, int $jendela = self::JENDELA): array
    {
        $batas = max($sejak, self::ms() - min($jendela, self::JENDELA) * 1000);
        $detik = array_values(array_filter($isi['d'] ?? [], static fn ($x) => $x[0] > $batas));
        if (! $awal) {
            return $detik;
        }

        $terisi = [];
        foreach ($detik as $x) {
            $terisi[intdiv($x[0], 30000)] = true; // per potongan 30 detik
        }
        foreach ($awal as $x) {
            if ($x[0] > $batas && ! isset($terisi[intdiv($x[0], 30000)])) {
                $detik[] = $x;
            }
        }
        usort($detik, static fn ($a, $b) => $a[0] <=> $b[0]);

        return $detik;
    }

    // ===================== PENYANGGA FILE =====================

    private static function ms(): int
    {
        return (int) round(microtime(true) * 1000);
    }

    private static function folder(): string
    {
        return WRITEPATH . 'cache' . DIRECTORY_SEPARATOR . 'nms_detik' . DIRECTORY_SEPARATOR;
    }

    private static function siapkanFolder(): bool
    {
        $f = self::folder();
        if (! is_dir($f) && ! @mkdir($f, 0775, true) && ! is_dir($f)) {
            return false;
        }
        self::bersihkan();

        return is_writable($f);
    }

    /** Hapus penyangga yang sudah tidak dipakai lebih dari sehari (paling sering sekali per jam). */
    private static function bersihkan(): void
    {
        if (cache('nms_detik_bersih')) {
            return;
        }
        cache()->save('nms_detik_bersih', 1, 3600);
        foreach (['json', 'lock'] as $akhiran) {
            foreach (glob(self::folder() . '*.' . $akhiran) ?: [] as $f) {
                if (@filemtime($f) < time() - 86400) {
                    @unlink($f);
                }
            }
        }
    }

    /** Kunci pengambilan sampel; null = sedang diambil proses lain. */
    private static function kunci(string $jenis, int $id)
    {
        $file = self::folder() . $jenis . '_' . $id . '.lock';
        @touch($file); // waktu file dipakai bersihkan()
        $fp = @fopen($file, 'c');
        if (! $fp) {
            return null;
        }
        if (! flock($fp, LOCK_EX | LOCK_NB)) {
            fclose($fp);

            return null;
        }

        return $fp;
    }

    private static function baca(string $jenis, int $id): array
    {
        $fp = @fopen(self::folder() . $jenis . '_' . $id . '.json', 'rb');
        if (! $fp) {
            return [];
        }
        flock($fp, LOCK_SH);
        $isi = json_decode((string) stream_get_contents($fp), true);
        flock($fp, LOCK_UN);
        fclose($fp);

        return is_array($isi) ? $isi : [];
    }

    private static function tulis(string $jenis, int $id, array $isi): void
    {
        $batas    = self::ms() - self::JENDELA * 1000;
        $isi['d'] = array_values(array_filter($isi['d'] ?? [], static fn ($x) => $x[0] > $batas));
        @file_put_contents(self::folder() . $jenis . '_' . $id . '.json', json_encode($isi), LOCK_EX);
    }
}
