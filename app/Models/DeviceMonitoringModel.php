<?php

namespace App\Models;

use App\Libraries\SnmpKlien;

// Device Monitoring (menu Data Teknis NETMON > Device Monitoring): data perangkat + konfigurasi SNMP.
// Status Up/Down diisi polling SNMP v2c (SnmpKlien); perubahan status dicatat di d_device_monitoring_log.
class DeviceMonitoringModel extends BaseModel
{
    protected $table      = 'd_device_monitoring';
    protected $primaryKey = 'id';

    // Kolom hasil polling (status, sys_*, last_*) tidak ada di sini: diubah lewat simpanHasilPoll()
    // tanpa model, agar tiap polling tidak tercatat di Change Log.
    protected $allowedFields = [
        'hostname',
        'ip_address',
        'vendor',
        'model',
        'device_group',
        'lokasi',
        'deskripsi',
        'snmp_version',
        'snmp_port',
        'snmp_community',
        'polling_interval',
        'snmp_enabled',
        'monitoring_enabled',
        'status',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /** Pilihan Vendor (selain ini = isian manual dari pilihan "Other"). */
    public const VENDOR = ['Cisco', 'Juniper', 'Mikrotik'];

    /** Pilihan Device Group (selain ini = isian manual dari pilihan "Other"). */
    public const DEVICE_GROUP = ['Backbone', 'Firewall', 'OLT', 'Router', 'Server', 'Switch'];

    /** Versi SNMP: kode => label. */
    public const SNMP_VERSION = [
        'v2c' => 'SNMP v2c',
        'v3'  => 'SNMP v3 (credentials setup pending)',
    ];

    /** Community bawaan untuk device baru. */
    public const COMMUNITY_DEFAULT = 'JWI5758';

    /** Batas polling interval (detik). */
    public const INTERVAL_MIN = 10;
    public const INTERVAL_MAX = 86400;

    public static function enkripsi(string $plain): string
    {
        return base64_encode(service('encrypter')->encrypt($plain));
    }

    public static function dekripsi(?string $simpan): string
    {
        if ($simpan === null || $simpan === '') {
            return '';
        }
        try {
            return service('encrypter')->decrypt(base64_decode($simpan, true) ?: '');
        } catch (\Throwable $e) {
            return '';
        }
    }

    /**
     * Status yang ditampilkan: Up / Down / Unknown, atau alasan tidak di-polling
     * (Monitoring Off, SNMP Off, v3 Pending).
     */
    public static function statusTampil(array $d): string
    {
        if (! (int) $d['monitoring_enabled']) {
            return 'Monitoring Off';
        }
        if (! (int) $d['snmp_enabled']) {
            return 'SNMP Off';
        }
        if ($d['snmp_version'] !== 'v2c') {
            return 'v3 Pending';
        }

        return in_array($d['status'], ['Up', 'Down'], true) ? $d['status'] : 'Unknown';
    }

    /** Device bisa di-polling SNMP (manual maupun otomatis). */
    public static function bisaPoll(array $d): bool
    {
        return (int) $d['snmp_enabled'] && $d['snmp_version'] === 'v2c';
    }

    /** Jalankan SNMP GET ke perangkat (tanpa menyimpan). */
    public static function cekSnmp(string $ip, int $port, string $community, float $timeout = 2.0): array
    {
        return (new SnmpKlien($ip, $community, $port, $timeout, 1))->get(SnmpKlien::OID_SISTEM);
    }

    /**
     * Polling satu device lalu simpan hasilnya; kembalikan baris terbaru.
     * Device yang membalas (Up) sekalian di-poll traffic interface-nya (Traffic Monitoring),
     * kalau tabel traffic sudah ada.
     */
    public function poll(array $d, float $timeout = 2.0): array
    {
        $community = self::dekripsi($d['snmp_community']);
        $hasil     = self::cekSnmp($d['ip_address'], (int) $d['snmp_port'], $community, $timeout);
        $this->simpanHasilPoll($d, $hasil);

        // NMS: ping ICMP + CPU/RAM (tetap dicatat walau SNMP tidak membalas, agar packet loss terlihat)
        $metrikBaru = false;
        if (DeviceNmsModel::siap()) {
            try {
                (new DeviceNmsModel())->pollDevice($d, $hasil, $community, max(2.0, $timeout));
                $metrikBaru = true;
            } catch (\Throwable $e) {
                log_message('error', 'NMS poll ' . $d['hostname'] . ': ' . $e->getMessage());
            }
        }

        if ($hasil['ok'] && DeviceTrafficModel::siap()) {
            try {
                (new DeviceTrafficModel())->pollDevice($d, $community, max(2.0, $timeout));
            } catch (\Throwable $e) {
                log_message('error', 'Traffic Monitoring poll ' . $d['hostname'] . ': ' . $e->getMessage());
            }
        }

        // Alert & notifikasi dari hasil polling di atas (halaman Alert)
        if (DeviceAlertModel::siap()) {
            try {
                (new DeviceAlertModel())->periksa($d, $metrikBaru);
            } catch (\Throwable $e) {
                log_message('error', 'Alert ' . $d['hostname'] . ': ' . $e->getMessage());
            }
        }

        return $this->find($d['id']) ?? $d;
    }

    /**
     * Polling device yang sudah waktunya (monitoring & SNMP aktif, v2c, last_poll + interval <= sekarang).
     * $batasDetik membatasi lama proses agar request web tidak terlalu lama.
     * Hanya satu proses yang berjalan dalam satu waktu (banyak halaman & lonceng notifikasi memanggil ini);
     * kalau sedang berjalan di proses lain, langsung kembali 0.
     *
     * @return int jumlah device yang di-polling
     */
    public function pollJatuhTempo(float $batasDetik = 8.0): int
    {
        $kunci = 'dm_poll_' . md5($this->db->getDatabase());
        $lock  = $this->db->query('SELECT GET_LOCK(?, 0) AS ok', [$kunci])->getRowArray();
        if ((int) ($lock['ok'] ?? 0) !== 1) {
            return 0;
        }
        try {
            return $this->pollAntrean($batasDetik);
        } finally {
            $this->db->query('SELECT RELEASE_LOCK(?)', [$kunci]);
        }
    }

    private function pollAntrean(float $batasDetik): int
    {
        $mulai = microtime(true);
        $list  = $this->where('monitoring_enabled', 1)
            ->where('snmp_enabled', 1)
            ->where('snmp_version', 'v2c')
            ->groupStart()
            ->where('last_poll', null)
            ->orWhere('TIMESTAMPADD(SECOND, polling_interval, last_poll) <= ' . $this->db->escape(date('Y-m-d H:i:s')), null, false)
            ->groupEnd()
            ->orderBy('last_poll IS NULL', 'DESC', false)
            ->orderBy('last_poll', 'ASC')
            ->findAll();

        $n = 0;
        foreach ($list as $d) {
            if (microtime(true) - $mulai > $batasDetik) {
                break;
            }
            $this->poll($d, 1.5);
            $n++;
        }

        return $n;
    }

    /** Simpan hasil polling + catat perubahan status (tanpa Change Log). */
    public function simpanHasilPoll(array $d, array $hasil): void
    {
        $sekarang = date('Y-m-d H:i:s');
        $status   = $hasil['ok'] ? 'Up' : 'Down';
        $v        = $hasil['nilai'];
        $teks     = static fn ($x, int $maks = 255) => is_string($x) && $x !== '' ? mb_substr(mb_convert_encoding($x, 'UTF-8', 'UTF-8'), 0, $maks) : null;

        $data = [
            'status'      => $status,
            'response_ms' => $hasil['ms'],
            'last_poll'   => $sekarang,
            'last_error'  => $hasil['ok'] ? null : mb_substr((string) $hasil['error'], 0, 255),
        ];
        if ($hasil['ok']) {
            $data += [
                'last_up'      => $sekarang,
                'sys_name'     => $teks($v['sysName'] ?? null),
                'sys_descr'    => $teks($v['sysDescr'] ?? null, 2000),
                'sys_uptime'   => is_int($v['sysUpTime'] ?? null) ? $v['sysUpTime'] : null,
                'sys_contact'  => $teks($v['sysContact'] ?? null),
                'sys_location' => $teks($v['sysLocation'] ?? null),
                'if_number'    => is_int($v['ifNumber'] ?? null) ? $v['ifNumber'] : null,
            ];
        }

        $this->db->table($this->table)->where('id', $d['id'])->update($data);

        if ($d['status'] !== $status) {
            $this->db->table('d_device_monitoring_log')->insert([
                'device_id'  => $d['id'],
                'status'     => $status,
                'pesan'      => $hasil['ok'] ? 'SNMP membalas (' . $hasil['ms'] . ' ms)' : mb_substr((string) $hasil['error'], 0, 255),
                'created_at' => $sekarang,
            ]);
        }
    }

    /** Riwayat perubahan status satu device (terbaru dulu). */
    public function riwayat(int $id, int $batas = 50): array
    {
        return $this->db->table('d_device_monitoring_log')
            ->where('device_id', $id)
            ->orderBy('id', 'DESC')
            ->limit($batas)
            ->get()
            ->getResultArray();
    }
}
