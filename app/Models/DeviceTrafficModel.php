<?php

namespace App\Models;

use App\Libraries\SnmpKlien;

// Traffic Monitoring (menu NMS JWI Group > Traffic Monitoring): interface & traffic RX/TX per device
// dari d_device_monitoring. Alurnya sama dengan netmon/workers/poll_traffic.php:
//   walk IF-MIB -> simpan counter octets terakhir di d_device_interface
//   -> rate = selisih counter x 8 / selisih detik -> riwayat di d_device_interface_traffic.
// Dipanggil dari DeviceMonitoringModel::poll() setiap device di-poll dan membalas SNMP (Up).
// Model biasa (bukan BaseModel): hasil polling tiap menit tidak dicatat di Change Log.
class DeviceTrafficModel extends \CodeIgniter\Model
{
    protected $table      = 'd_device_interface';
    protected $primaryKey = 'id';

    public const TABEL_TRAFFIC = 'd_device_interface_traffic';

    /** Riwayat traffic disimpan sekian hari. */
    public const RETENSI_HARI = 7;

    /** Selisih waktu minimal antar sampel (detik) agar rate tidak melonjak karena interval terlalu pendek. */
    public const JEDA_MIN = 10;

    /** Kolom IF-MIB yang di-walk (nama => OID kolom). */
    public const OID_INFO = [
        'name'  => '1.3.6.1.2.1.31.1.1.1.1',  // ifName
        'descr' => '1.3.6.1.2.1.2.2.1.2',     // ifDescr
        'alias' => '1.3.6.1.2.1.31.1.1.1.18', // ifAlias (comment interface)
        'speed' => '1.3.6.1.2.1.31.1.1.1.15', // ifHighSpeed (Mbps)
        'admin' => '1.3.6.1.2.1.2.2.1.7',     // ifAdminStatus
        'oper'  => '1.3.6.1.2.1.2.2.1.8',     // ifOperStatus
    ];

    /** Counter 64-bit (ifHCInOctets / ifHCOutOctets). */
    public const OID_HC = [
        'in'  => '1.3.6.1.2.1.31.1.1.1.6',
        'out' => '1.3.6.1.2.1.31.1.1.1.10',
    ];

    /** Counter 32-bit (ifInOctets / ifOutOctets), cadangan kalau perangkat tidak membalas counter 64-bit. */
    public const OID_32 = [
        'in'  => '1.3.6.1.2.1.2.2.1.10',
        'out' => '1.3.6.1.2.1.2.2.1.16',
    ];

    /** Perangkat yang gagal counter 64-bit tidak dicoba lagi selama sekian detik (langsung pakai 32-bit). */
    private const HC_GAGAL_TTL = 3600;

    private static ?bool $siap = null;

    /** Tabel traffic sudah dibuat (database/d_device_traffic.sql). */
    public static function siap(): bool
    {
        if (self::$siap === null) {
            $db         = \Config\Database::connect();
            self::$siap = $db->tableExists('d_device_interface') && $db->tableExists(self::TABEL_TRAFFIC);
        }

        return self::$siap;
    }

    /**
     * Status interface untuk tampilan: Up / Down / Disabled / Not Present / Unknown.
     * Device yang sedang tidak membalas SNMP = Unknown (data interface terakhir sudah tidak pasti).
     */
    public static function statusTampil(array $i, ?string $statusDevice = 'Up'): string
    {
        if ($statusDevice !== 'Up') {
            return 'Unknown';
        }
        if ((int) $i['admin_status'] === 2) {
            return 'Disabled';
        }

        return match ((int) $i['oper_status']) {
            1       => 'Up',
            2, 7    => 'Down',
            6       => 'Not Present',
            default => 'Unknown',
        };
    }

    /** Format bit per detik: 1.25 Mbps, 820 Kbps, dst. */
    public static function formatBps($bps): string
    {
        if ($bps === null || $bps === '') {
            return '-';
        }
        $v = (float) $bps;
        foreach (['Gbps' => 1e9, 'Mbps' => 1e6, 'Kbps' => 1e3] as $satuan => $bagi) {
            if ($v >= $bagi) {
                return number_format($v / $bagi, 2, ',', '.') . ' ' . $satuan;
            }
        }

        return number_format($v, 0, ',', '.') . ' bps';
    }

    /** Format speed interface (Mbps dari ifHighSpeed). */
    public static function formatSpeed($mbps): string
    {
        $m = (int) $mbps;
        if ($m <= 0) {
            return '-';
        }

        return $m >= 1000 ? rtrim(rtrim(number_format($m / 1000, 1, ',', '.'), '0'), ',') . ' Gbps' : $m . ' Mbps';
    }

    /**
     * Polling traffic satu device (community sudah didekripsi), simpan interface + sampel traffic.
     *
     * @return array{ok: bool, interface: int, sampel: int, bit: int|null, error: string|null}
     */
    public function pollDevice(array $d, string $community, float $timeout = 2.0): array
    {
        $klien = new SnmpKlien($d['ip_address'], $community, (int) $d['snmp_port'], $timeout, 1);

        $info = $klien->walkTabel(self::OID_INFO, 10);
        if (! $info['ok']) {
            return ['ok' => false, 'interface' => 0, 'sampel' => 0, 'bit' => null, 'error' => $info['error']];
        }

        // counter 64-bit dulu; tidak dibalas / kosong -> counter 32-bit
        $kunciHc = 'dm_traffic_hc_gagal_' . (int) $d['id'];
        $counter = null;
        $bit     = 64;
        if (! cache($kunciHc)) {
            $hc = $klien->walkTabel(self::OID_HC, 20);
            if ($hc['ok'] && $hc['data']['in']) {
                $counter = $hc['data'];
            } else {
                cache()->save($kunciHc, 1, self::HC_GAGAL_TTL);
            }
        }
        if ($counter === null) {
            $bit = 32;
            $c32 = $klien->walkTabel(self::OID_32, 20);
            if (! $c32['ok']) {
                return ['ok' => false, 'interface' => 0, 'sampel' => 0, 'bit' => null, 'error' => $c32['error']];
            }
            $counter = $c32['data'];
        }

        return $this->simpan($d, $info['data'], $counter, $bit) + ['ok' => true, 'bit' => $bit, 'error' => null];
    }

    /** Simpan hasil walk: upsert interface, hitung rate dari counter sebelumnya, catat sampel. */
    private function simpan(array $d, array $info, array $counter, int $bit): array
    {
        $deviceId = (int) $d['id'];
        $now      = time();
        $sekarang = date('Y-m-d H:i:s', $now);
        $teks     = static fn ($x) => is_string($x) && $x !== '' ? mb_substr(mb_convert_encoding($x, 'UTF-8', 'UTF-8'), 0, 255) : null;
        $angka    = static fn ($x) => is_int($x) ? $x : null;

        $lama = [];
        foreach ($this->db->table($this->table)->where('device_id', $deviceId)->get()->getResultArray() as $r) {
            $lama[(int) $r['if_index']] = $r;
        }

        $indeks = array_unique(array_merge(array_keys($info['name']), array_keys($info['descr']), array_keys($info['oper'])));
        $indeks = array_filter(array_map('intval', $indeks), static fn ($i) => $i > 0);
        sort($indeks, SORT_NUMERIC);

        $jumlah = 0;
        $sampel = [];
        foreach ($indeks as $idx) {
            $nama  = $teks($info['name'][$idx] ?? null) ?? $teks($info['descr'][$idx] ?? null) ?? 'ifIndex-' . $idx;
            $speed = $angka($info['speed'][$idx] ?? null);
            $rxC   = $this->counterValid($counter['in'][$idx] ?? null);
            $txC   = $this->counterValid($counter['out'][$idx] ?? null);

            $baris = [
                'if_name'      => $nama,
                'if_descr'     => $teks($info['descr'][$idx] ?? null),
                'if_alias'     => $teks($info['alias'][$idx] ?? null),
                'speed_mbps'   => $speed !== null && $speed >= 0 ? $speed : null,
                'admin_status' => $angka($info['admin'][$idx] ?? null),
                'oper_status'  => $angka($info['oper'][$idx] ?? null),
                'last_seen'    => $sekarang,
                'updated_at'   => $sekarang,
            ];

            $l          = $lama[$idx] ?? null;
            $simpanBase = true; // counter sekarang jadi patokan polling berikutnya
            if ($rxC !== null && $txC !== null && $l && $l['in_octets'] !== null && $l['out_octets'] !== null
                && $l['counter_at'] && (int) $l['counter_bit'] === $bit) {
                $jeda = $now - strtotime($l['counter_at']);
                if ($jeda < self::JEDA_MIN) {
                    $simpanBase = false; // terlalu dekat: tunggu polling berikutnya, patokan lama dipakai
                } else {
                    $dRx = $this->selisih($rxC, $l['in_octets'], $bit);
                    $dTx = $this->selisih($txC, $l['out_octets'], $bit);
                    if ($dRx !== null && $dTx !== null) {
                        $rx   = (int) round($dRx * 8 / $jeda);
                        $tx   = (int) round($dTx * 8 / $jeda);
                        $maks = $speed ? $speed * 1e6 * 1.5 : null; // lonjakan tidak wajar (> 150% speed) diabaikan
                        if ($maks === null || ($rx <= $maks && $tx <= $maks)) {
                            $baris += ['rx_bps' => $rx, 'tx_bps' => $tx, 'traffic_at' => $sekarang];
                            $sampel[$idx] = [$rx, $tx];
                        }
                    }
                }
            }
            if ($simpanBase) {
                $baris += ['counter_bit' => $bit, 'in_octets' => $rxC, 'out_octets' => $txC, 'counter_at' => $rxC !== null ? $sekarang : null];
            }

            if ($l) {
                $this->db->table($this->table)->where('id', $l['id'])->update($baris);
            } else {
                $this->db->table($this->table)->insert($baris + ['device_id' => $deviceId, 'if_index' => $idx, 'created_at' => $sekarang]);
                $lama[$idx] = ['id' => $this->db->insertID()];
            }
            $jumlah++;
        }

        if ($sampel) {
            $batch = [];
            foreach ($sampel as $idx => [$rx, $tx]) {
                $batch[] = ['device_id' => $deviceId, 'interface_id' => (int) $lama[$idx]['id'], 'rx_bps' => $rx, 'tx_bps' => $tx, 'checked_at' => $sekarang];
            }
            $this->db->table(self::TABEL_TRAFFIC)->insertBatch($batch);
        }

        $this->bersihkan();

        return ['interface' => $jumlah, 'sampel' => count($sampel)];
    }

    /** Counter octets valid = angka bulat >= 0 (int atau string angka untuk Counter64 besar). */
    private function counterValid($v): ?string
    {
        if (is_int($v) && $v >= 0) {
            return (string) $v;
        }

        return is_string($v) && ctype_digit($v) ? $v : null;
    }

    /**
     * Selisih counter (baru - lama). Counter 32-bit yang berputar balik dihitung wrap,
     * counter 64-bit yang turun = reset/reboot (null, tidak dicatat).
     */
    private function selisih(string $baru, string $lama, int $bit): ?float
    {
        $cmp = strlen($baru) <=> strlen($lama) ?: strcmp($baru, $lama);
        if ($cmp >= 0) {
            return strlen($baru) <= 18 ? (float) ((int) $baru - (int) $lama) : (float) $baru - (float) $lama;
        }

        return $bit === 32 ? (float) $baru + 4294967296.0 - (float) $lama : null;
    }

    /** Hapus riwayat lebih lama dari RETENSI_HARI (paling sering sekali per jam). */
    private function bersihkan(): void
    {
        if (cache('dm_traffic_bersih')) {
            return;
        }
        cache()->save('dm_traffic_bersih', 1, 3600);
        $this->db->table(self::TABEL_TRAFFIC)
            ->where('checked_at <', date('Y-m-d H:i:s', strtotime('-' . self::RETENSI_HARI . ' days')))
            ->delete();
    }

    /** Hapus semua interface & riwayat traffic satu device (dipakai saat device dihapus). */
    public function hapusDevice(int $deviceId): void
    {
        $this->db->table(self::TABEL_TRAFFIC)->where('device_id', $deviceId)->delete();
        $this->db->table($this->table)->where('device_id', $deviceId)->delete();
    }

    /** Daftar interface (+ data device) untuk halaman; $deviceId 0 = semua device. */
    public function daftar(int $deviceId = 0): array
    {
        $q = $this->db->table($this->table . ' i')
            ->select('i.*, d.hostname, d.ip_address, d.status AS device_status, d.monitoring_enabled, d.snmp_enabled')
            ->join('d_device_monitoring d', 'd.id = i.device_id')
            ->orderBy('d.hostname', 'ASC')
            ->orderBy('i.if_index', 'ASC');
        if ($deviceId > 0) {
            $q->where('i.device_id', $deviceId);
        }

        return $q->get()->getResultArray();
    }

    /** Pilihan rentang grafik: kode => [label, detik]. */
    public const RENTANG = [
        '1h'  => ['1 Jam', 3600],
        '6h'  => ['6 Jam', 21600],
        '24h' => ['24 Jam', 86400],
        '7d'  => ['7 Hari', 604800],
    ];

    /**
     * Data grafik satu interface: rata-rata per bucket (maks. +-360 titik) + ringkasan rentang.
     *
     * @return array{label: list<string>, rx: list<int>, tx: list<int>, ringkas: array}
     */
    public function grafik(int $interfaceId, string $rentang): array
    {
        $detik  = (self::RENTANG[$rentang] ?? self::RENTANG['1h'])[1];
        $bucket = max(60, (int) ceil($detik / 360));
        $dari   = date('Y-m-d H:i:s', time() - $detik);

        $baris = $this->db->query(
            'SELECT FLOOR(UNIX_TIMESTAMP(checked_at) / ?) AS b, MIN(checked_at) AS waktu,
                    ROUND(AVG(rx_bps)) AS rx, ROUND(AVG(tx_bps)) AS tx
             FROM ' . self::TABEL_TRAFFIC . '
             WHERE interface_id = ? AND checked_at >= ?
             GROUP BY b ORDER BY b',
            [$bucket, $interfaceId, $dari]
        )->getResultArray();

        $ringkas = $this->db->query(
            'SELECT COUNT(*) AS n, ROUND(AVG(rx_bps)) AS rx_avg, MAX(rx_bps) AS rx_max,
                    ROUND(AVG(tx_bps)) AS tx_avg, MAX(tx_bps) AS tx_max
             FROM ' . self::TABEL_TRAFFIC . ' WHERE interface_id = ? AND checked_at >= ?',
            [$interfaceId, $dari]
        )->getRowArray();

        $format = $detik > 86400 ? 'd/m H:i' : 'H:i';

        return [
            'label'   => array_map(static fn ($r) => date($format, strtotime($r['waktu'])), $baris),
            'rx'      => array_map(static fn ($r) => (int) $r['rx'], $baris),
            'tx'      => array_map(static fn ($r) => (int) $r['tx'], $baris),
            'ringkas' => [
                'sampel' => (int) ($ringkas['n'] ?? 0),
                'rx_avg' => self::formatBps($ringkas['rx_avg'] ?? null),
                'rx_max' => self::formatBps($ringkas['rx_max'] ?? null),
                'tx_avg' => self::formatBps($ringkas['tx_avg'] ?? null),
                'tx_max' => self::formatBps($ringkas['tx_max'] ?? null),
            ],
        ];
    }
}
