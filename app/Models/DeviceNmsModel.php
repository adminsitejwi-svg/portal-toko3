<?php

namespace App\Models;

use App\Libraries\SnmpKlien;

// NMS / Network Management System (menu Data Teknis NETMON > NMS), mengikuti netmon/nms.php:
// tiap device Device Monitoring di-poll -> ping ICMP (latency, packet loss) + CPU/RAM lewat SNMP
// -> satu baris di d_device_nms_metric. Dipanggil dari DeviceMonitoringModel::poll().
// Model biasa (bukan BaseModel): hasil polling tiap menit tidak dicatat di Change Log.
class DeviceNmsModel extends \CodeIgniter\Model
{
    protected $table      = 'd_device_nms_metric';
    protected $primaryKey = 'id';

    /** Riwayat metrik disimpan sekian hari. */
    public const RETENSI_HARI = 30;

    /** Pilihan periode history: kode => [label, detik]. */
    public const PERIODE = [
        '1h'  => ['1 Jam', 3600],
        '6h'  => ['6 Jam', 21600],
        '12h' => ['12 Jam', 43200],
        '24h' => ['24 Jam', 86400],
        '7d'  => ['7 Hari', 604800],
        '30d' => ['30 Hari', 2592000],
    ];

    /** HOST-RESOURCES-MIB (MikroTik, Linux, Windows, banyak switch/OLT). */
    private const OID_HR_CPU     = ['load' => '1.3.6.1.2.1.25.3.3.1.2'];                  // hrProcessorLoad
    private const OID_HR_STORAGE = [
        'type' => '1.3.6.1.2.1.25.2.3.1.2', // hrStorageType
        'unit' => '1.3.6.1.2.1.25.2.3.1.4', // hrStorageAllocationUnits
        'size' => '1.3.6.1.2.1.25.2.3.1.5', // hrStorageSize
        'used' => '1.3.6.1.2.1.25.2.3.1.6', // hrStorageUsed
    ];
    private const HR_RAM = '1.3.6.1.2.1.25.2.1.2'; // hrStorageRam

    /** Cisco: cpmCPUTotal5minRev + ciscoMemoryPoolUsed/Free. */
    private const OID_CISCO_CPU = ['load' => '1.3.6.1.4.1.9.9.109.1.1.1.1.8'];
    private const OID_CISCO_MEM = ['used' => '1.3.6.1.4.1.9.9.48.1.1.1.5', 'free' => '1.3.6.1.4.1.9.9.48.1.1.1.6'];

    /** Juniper: jnxOperatingCPU / jnxOperatingBuffer (persen per komponen, diambil yang terbesar). */
    private const OID_JUNIPER = ['cpu' => '1.3.6.1.4.1.2636.3.1.13.1.8', 'mem' => '1.3.6.1.4.1.2636.3.1.13.1.11'];

    private static ?bool $siap = null;

    /** Tabel metrik sudah dibuat (database/d_device_nms.sql). */
    public static function siap(): bool
    {
        if (self::$siap === null) {
            self::$siap = \Config\Database::connect()->tableExists('d_device_nms_metric');
        }

        return self::$siap;
    }

    /** Server bisa menjalankan perintah ping (exec aktif). */
    public static function bisaPing(): bool
    {
        static $bisa = null;
        if ($bisa === null) {
            $mati = array_map('trim', explode(',', (string) ini_get('disable_functions')));
            $bisa = function_exists('exec') && ! in_array('exec', $mati, true);
        }

        return $bisa;
    }

    /**
     * Ping ICMP dari server (Windows / Linux).
     *
     * @return array{ok: bool, ms: float|null, loss: float|null}|null null = ping tidak bisa dijalankan
     */
    public static function ping(string $ip, int $jumlah = 3): ?array
    {
        if (! self::bisaPing() || ! filter_var($ip, FILTER_VALIDATE_IP)) {
            return null;
        }

        $win = DIRECTORY_SEPARATOR === '\\';
        $v6  = str_contains($ip, ':');
        $cmd = $win
            ? 'ping ' . ($v6 ? '-6 ' : '') . '-n ' . $jumlah . ' -w 1000 ' . escapeshellarg($ip)
            : ($v6 ? 'ping -6' : 'ping') . ' -c ' . $jumlah . ' -i 0.2 -W 1 ' . escapeshellarg($ip);

        $out  = [];
        $kode = 1;
        @exec($cmd . ' 2>&1', $out, $kode);
        $teks = implode("\n", $out);
        if ($teks === '' || preg_match('/not (recognized|found)|tidak dikenali|permission denied|operation not permitted/i', $teks)) {
            return null; // perintah ping tidak tersedia / tidak diizinkan
        }

        // waktu tiap balasan: "time=12ms", "time<1ms", "waktu=12ms", "time=12.3 ms"
        preg_match_all('/(?:time|waktu)\s*([=<])\s*([\d.,]+)\s*ms/i', $teks, $m, PREG_SET_ORDER);
        $waktu = array_map(static fn ($x) => $x[1] === '<' ? 0.5 : (float) str_replace(',', '.', $x[2]), $m);
        $balas = count($waktu);

        // persen loss dari ringkasan ping kalau ada, kalau tidak dihitung dari jumlah balasan.
        // Tanpa balasan "time=" = 100% (Windows menghitung "Destination host unreachable" sebagai balasan).
        if ($balas === 0) {
            $loss = 100.0;
        } elseif (preg_match('/([\d.]+)%\s*(?:packet\s*)?(?:loss|hilang)/i', $teks, $l)) {
            $loss = (float) $l[1];
        } else {
            $loss = round((1 - min($balas, $jumlah) / $jumlah) * 100, 2);
        }

        return [
            'ok'   => $balas > 0,
            'ms'   => $balas ? round(array_sum($waktu) / $balas, 2) : null,
            'loss' => max(0.0, min(100.0, $loss)),
        ];
    }

    /** Rata-rata angka valid (0-100) dari hasil walk; null kalau kosong. */
    private static function rataPersen(array $nilai, bool $maks = false): ?float
    {
        $angka = [];
        foreach ($nilai as $v) {
            if (is_numeric($v)) {
                $angka[] = max(0.0, min(100.0, (float) $v));
            }
        }
        if (! $angka) {
            return null;
        }

        return round($maks ? max($angka) : array_sum($angka) / count($angka), 2);
    }

    /**
     * Ambil CPU & RAM lewat SNMP: HOST-RESOURCES-MIB dulu, lalu MIB Cisco / Juniper sesuai vendor.
     *
     * @return array{cpu: float|null, mem: float|null, used: int|null, total: int|null}
     */
    public static function cpuRam(SnmpKlien $k, string $vendor = ''): array
    {
        $hasil = ['cpu' => null, 'mem' => null, 'used' => null, 'total' => null];

        $cpu = $k->walkTabel(self::OID_HR_CPU, 10, 256);
        if ($cpu['ok']) {
            $hasil['cpu'] = self::rataPersen($cpu['data']['load']);
        }

        $st = $k->walkTabel(self::OID_HR_STORAGE, 10, 256);
        if ($st['ok']) {
            foreach ($st['data']['type'] as $idx => $tipe) {
                if (trim((string) $tipe, '.') !== self::HR_RAM) {
                    continue;
                }
                $unit = (float) ($st['data']['unit'][$idx] ?? 0);
                $size = (float) ($st['data']['size'][$idx] ?? 0);
                $used = (float) ($st['data']['used'][$idx] ?? 0);
                if ($unit > 0 && $size > 0) {
                    $hasil['total'] = (int) round($size * $unit);
                    $hasil['used']  = (int) round($used * $unit);
                    $hasil['mem']   = round(min(100, $used / $size * 100), 2);
                    break;
                }
            }
        }

        $v = strtolower($vendor);
        if (($hasil['cpu'] === null || $hasil['mem'] === null) && str_contains($v, 'cisco')) {
            if ($hasil['cpu'] === null) {
                $c = $k->walkTabel(self::OID_CISCO_CPU, 10, 64);
                $hasil['cpu'] = $c['ok'] ? self::rataPersen($c['data']['load']) : null;
            }
            if ($hasil['mem'] === null) {
                $m = $k->walkTabel(self::OID_CISCO_MEM, 10, 64);
                $u = $m['ok'] ? reset($m['data']['used']) : false;
                $f = $m['ok'] ? reset($m['data']['free']) : false; // pool pertama = Processor
                if (is_numeric($u) && is_numeric($f) && ($u + $f) > 0) {
                    $hasil['used']  = (int) $u;
                    $hasil['total'] = (int) ($u + $f);
                    $hasil['mem']   = round($u / ($u + $f) * 100, 2);
                }
            }
        }
        if (($hasil['cpu'] === null || $hasil['mem'] === null) && str_contains($v, 'juniper')) {
            $j = $k->walkTabel(self::OID_JUNIPER, 10, 128);
            if ($j['ok']) {
                $hasil['cpu'] ??= self::rataPersen($j['data']['cpu'], true);
                $hasil['mem'] ??= self::rataPersen($j['data']['mem'], true);
            }
        }

        return $hasil;
    }

    /**
     * Polling NMS satu device lalu simpan satu baris metrik.
     *
     * @param array $snmp hasil SnmpKlien::get(OID_SISTEM) dari DeviceMonitoringModel::poll()
     */
    public function pollDevice(array $d, array $snmp, string $community, float $timeout = 2.0): void
    {
        $ping   = self::ping($d['ip_address'], PHP_SAPI === 'cli' ? 4 : 3);
        $sumber = 'icmp';
        if ($ping === null) {
            // server tidak bisa ping: pakai waktu respon SNMP sebagai latency
            $sumber = 'snmp';
            $ping   = ['ok' => $snmp['ok'], 'ms' => $snmp['ok'] ? (float) $snmp['ms'] : null, 'loss' => $snmp['ok'] ? 0.0 : 100.0];
        }

        $res = ['cpu' => null, 'mem' => null, 'used' => null, 'total' => null];
        if ($snmp['ok']) {
            $res = self::cpuRam(new SnmpKlien($d['ip_address'], $community, (int) $d['snmp_port'], $timeout, 1), (string) $d['vendor']);
        }

        $uptime = $snmp['nilai']['sysUpTime'] ?? null;
        $this->db->table($this->table)->insert([
            'device_id'    => (int) $d['id'],
            'ping_status'  => $ping['ok'] ? 'Up' : 'Down',
            'ping_source'  => $sumber,
            'latency_ms'   => $ping['ms'],
            'packet_loss'  => $ping['loss'],
            'snmp_ok'      => $snmp['ok'] ? 1 : 0,
            'cpu_usage'    => $res['cpu'],
            'memory_usage' => $res['mem'],
            'memory_used'  => $res['used'],
            'memory_total' => $res['total'],
            'uptime_ticks' => is_int($uptime) ? $uptime : null,
            'checked_at'   => date('Y-m-d H:i:s'),
        ]);

        $this->bersihkan();
    }

    /** Hapus riwayat lebih lama dari RETENSI_HARI (paling sering sekali per jam). */
    private function bersihkan(): void
    {
        if (cache('dm_nms_bersih')) {
            return;
        }
        cache()->save('dm_nms_bersih', 1, 3600);
        $this->db->table($this->table)
            ->where('checked_at <', date('Y-m-d H:i:s', strtotime('-' . self::RETENSI_HARI . ' days')))
            ->delete();
    }

    /** Hapus semua metrik satu device (dipakai saat device dihapus). */
    public function hapusDevice(int $deviceId): void
    {
        $this->db->table($this->table)->where('device_id', $deviceId)->delete();
    }

    /** Metrik terakhir tiap device: [device_id => baris]. */
    public function terakhir(): array
    {
        $baris = $this->db->query(
            'SELECT m.* FROM ' . $this->table . ' m
             JOIN (SELECT device_id, MAX(id) AS id FROM ' . $this->table . ' GROUP BY device_id) t ON t.id = m.id'
        )->getResultArray();

        return array_column($baris, null, 'device_id');
    }

    /** Ubah periode / tanggal custom jadi [dari, sampai, label, detik]. */
    public static function rentang(string $periode, string $mulai = '', string $akhir = ''): array
    {
        $tgl = static function (string $s) {
            $d = \DateTime::createFromFormat('!Y-m-d', $s);

            return $d && $d->format('Y-m-d') === $s ? $d : null;
        };
        $a = $tgl($mulai);
        $b = $tgl($akhir);
        if ($a && $b && $a <= $b) {
            $dari   = $a->format('Y-m-d 00:00:00');
            $sampai = $b->modify('+1 day')->format('Y-m-d 00:00:00');

            return [$dari, $sampai, date('d-m-Y', strtotime($mulai)) . ' s/d ' . date('d-m-Y', strtotime($akhir)), strtotime($sampai) - strtotime($dari)];
        }

        [$label, $detik] = self::PERIODE[$periode] ?? self::PERIODE['24h'];

        return [date('Y-m-d H:i:s', time() - $detik), date('Y-m-d H:i:s', time() + 60), $label . ' terakhir', $detik];
    }

    /** Ukuran bucket (detik) agar grafik maks. +-400 titik. */
    private static function bucket(int $detik): int
    {
        return max(60, (int) ceil($detik / 400));
    }

    /**
     * Grafik history satu device: latency, packet loss, CPU, RAM (rata-rata per bucket) + ringkasan.
     */
    public function history(int $deviceId, string $dari, string $sampai, int $detik): array
    {
        $bucket = self::bucket($detik);
        $baris  = $this->db->query(
            'SELECT FLOOR(UNIX_TIMESTAMP(checked_at) / ?) AS b, MIN(checked_at) AS waktu,
                    AVG(latency_ms) AS lat, AVG(packet_loss) AS loss, AVG(cpu_usage) AS cpu, AVG(memory_usage) AS mem
             FROM ' . $this->table . '
             WHERE device_id = ? AND checked_at >= ? AND checked_at < ?
             GROUP BY b ORDER BY b',
            [$bucket, $deviceId, $dari, $sampai]
        )->getResultArray();

        $r = $this->db->query(
            'SELECT COUNT(*) AS n, AVG(latency_ms) AS lat_avg, MAX(latency_ms) AS lat_max, AVG(packet_loss) AS loss_avg,
                    AVG(cpu_usage) AS cpu_avg, MAX(cpu_usage) AS cpu_max, AVG(memory_usage) AS mem_avg, MAX(memory_usage) AS mem_max,
                    SUM(ping_status = "Up") AS up
             FROM ' . $this->table . ' WHERE device_id = ? AND checked_at >= ? AND checked_at < ?',
            [$deviceId, $dari, $sampai]
        )->getRowArray();

        $format = $detik > 86400 ? 'd/m H:i' : 'H:i';
        $angka  = static fn ($v, int $d = 2) => $v === null ? null : round((float) $v, $d);
        $n      = (int) ($r['n'] ?? 0);

        return [
            'label'   => array_map(static fn ($x) => date($format, strtotime($x['waktu'])), $baris),
            'latency' => array_map(static fn ($x) => $angka($x['lat']), $baris),
            'loss'    => array_map(static fn ($x) => $angka($x['loss']), $baris),
            'cpu'     => array_map(static fn ($x) => $angka($x['cpu']), $baris),
            'mem'     => array_map(static fn ($x) => $angka($x['mem']), $baris),
            'ringkas' => [
                'sampel'   => $n,
                'lat_avg'  => $angka($r['lat_avg'] ?? null),
                'lat_max'  => $angka($r['lat_max'] ?? null),
                'loss_avg' => $angka($r['loss_avg'] ?? null),
                'cpu_avg'  => $angka($r['cpu_avg'] ?? null, 1),
                'cpu_max'  => $angka($r['cpu_max'] ?? null, 1),
                'mem_avg'  => $angka($r['mem_avg'] ?? null, 1),
                'mem_max'  => $angka($r['mem_max'] ?? null, 1),
                'uptime'   => $n ? round((int) $r['up'] / $n * 100, 2) : null, // availability ping
            ],
        ];
    }

    /** Grafik traffic satu interface pada rentang bebas (rata-rata per bucket, bps). */
    public function historyTraffic(int $interfaceId, string $dari, string $sampai, int $detik): array
    {
        $bucket = self::bucket($detik);
        $baris  = $this->db->query(
            'SELECT FLOOR(UNIX_TIMESTAMP(checked_at) / ?) AS b, MIN(checked_at) AS waktu,
                    ROUND(AVG(rx_bps)) AS rx, ROUND(AVG(tx_bps)) AS tx
             FROM ' . DeviceTrafficModel::TABEL_TRAFFIC . '
             WHERE interface_id = ? AND checked_at >= ? AND checked_at < ?
             GROUP BY b ORDER BY b',
            [$bucket, $interfaceId, $dari, $sampai]
        )->getResultArray();

        $r = $this->db->query(
            'SELECT COUNT(*) AS n, ROUND(AVG(rx_bps)) AS rx_avg, MAX(rx_bps) AS rx_max, ROUND(AVG(tx_bps)) AS tx_avg, MAX(tx_bps) AS tx_max
             FROM ' . DeviceTrafficModel::TABEL_TRAFFIC . ' WHERE interface_id = ? AND checked_at >= ? AND checked_at < ?',
            [$interfaceId, $dari, $sampai]
        )->getRowArray();

        $format = $detik > 86400 ? 'd/m H:i' : 'H:i';

        return [
            'label'   => array_map(static fn ($x) => date($format, strtotime($x['waktu'])), $baris),
            'rx'      => array_map(static fn ($x) => (int) $x['rx'], $baris),
            'tx'      => array_map(static fn ($x) => (int) $x['tx'], $baris),
            'ringkas' => [
                'sampel' => (int) ($r['n'] ?? 0),
                'rx_avg' => DeviceTrafficModel::formatBps($r['rx_avg'] ?? null),
                'rx_max' => DeviceTrafficModel::formatBps($r['rx_max'] ?? null),
                'tx_avg' => DeviceTrafficModel::formatBps($r['tx_avg'] ?? null),
                'tx_max' => DeviceTrafficModel::formatBps($r['tx_max'] ?? null),
            ],
        ];
    }

    /**
     * Grafik Network Performance (Dashboard Netmon): CPU & RAM per menit, 60 titik terakhir dalam 24 jam.
     * $deviceId 0 = rata-rata semua device.
     */
    public function performa(int $deviceId = 0, int $titik = 60): array
    {
        $param = [date('Y-m-d H:i:s', time() - 86400)];
        $saring = '';
        if ($deviceId > 0) {
            $saring  = ' AND device_id = ?';
            $param[] = $deviceId;
        }
        $baris = array_reverse($this->db->query(
            'SELECT FLOOR(UNIX_TIMESTAMP(checked_at) / 60) AS b, MIN(checked_at) AS waktu,
                    ROUND(AVG(cpu_usage), 2) AS cpu, ROUND(AVG(memory_usage), 2) AS mem
             FROM ' . $this->table . '
             WHERE checked_at >= ? AND (cpu_usage IS NOT NULL OR memory_usage IS NOT NULL)' . $saring . '
             GROUP BY b ORDER BY b DESC LIMIT ' . $titik,
            $param
        )->getResultArray());
        $angka = static fn ($v) => $v === null ? null : (float) $v;

        return [
            'label' => array_map(static fn ($x) => date('H:i', strtotime($x['waktu'])), $baris),
            'cpu'   => array_map(static fn ($x) => $angka($x['cpu']), $baris),
            'ram'   => array_map(static fn ($x) => $angka($x['mem']), $baris),
        ];
    }

    /** Traffic live satu interface: sampel mentah 1 jam terakhir (maks. 120 titik). */
    public function liveTraffic(int $interfaceId): array
    {
        $baris = array_reverse($this->db->table(DeviceTrafficModel::TABEL_TRAFFIC)
            ->select('checked_at, rx_bps, tx_bps')
            ->where('interface_id', $interfaceId)
            ->where('checked_at >=', date('Y-m-d H:i:s', time() - 3600))
            ->orderBy('checked_at', 'DESC')
            ->limit(120)
            ->get()->getResultArray());

        return [
            'label' => array_map(static fn ($x) => date('H:i:s', strtotime($x['checked_at'])), $baris),
            'rx'    => array_map(static fn ($x) => (int) $x['rx_bps'], $baris),
            'tx'    => array_map(static fn ($x) => (int) $x['tx_bps'], $baris),
        ];
    }
}
