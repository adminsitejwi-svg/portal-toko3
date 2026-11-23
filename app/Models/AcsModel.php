<?php

namespace App\Models;

use App\Libraries\AcsData;

// ACS JWI Group (TR-069): data Settings, ONT, data ONT, antrean & log (tabel d_acs_*, database/d_acs.sql).
// Dipakai halaman Devices, Settings, Log Koneksi (Controllers/AcsDevice, AcsSetting, AcsLog) dan server TR-069 (Controllers/AcsCwmp).
// Model biasa (bukan BaseModel): Inform ONT tiap beberapa menit tidak dicatat di Change Log.
class AcsModel extends \CodeIgniter\Model
{
    protected $table      = 'd_acs_device';
    protected $primaryKey = 'id';

    /**
     * Data yang dibaca dari ONT (TR-098): grup => [objek, dibaca ulang paling cepat sekian detik sekali].
     * Dikirim terpisah agar satu yang ditolak ONT tidak menggagalkan yang lain. 'optik' & 'mac': objeknya sesuai merek (AcsData).
     */
    public const BACA = [
        'wan'   => ['InternetGatewayDevice.WANDevice.1.WANConnectionDevice.', 600],
        'optik' => [null, 1800],
        'host'  => ['InternetGatewayDevice.LANDevice.1.Hosts.', 1800],
        'wifi'  => ['InternetGatewayDevice.LANDevice.1.WLANConfiguration.', 3600],
        'info'  => ['InternetGatewayDevice.DeviceInfo.', 3600],
        'mac'   => [null, 3600],
        'acs'   => ['InternetGatewayDevice.ManagementServer.PeriodicInformInterval', 3600],
    ];

    /** Grup yang ditolak ONT (Fault) tidak dicoba lagi selama sekian detik. */
    private const JEDA_DITOLAK = 86400;

    private const MAKS_PARAMETER = 3000;

    /** Perintah yang mengubah ONT dibatalkan kalau belum diambil ONT selama sekian detik. */
    private const UMUR_TULIS = 1800;
    private const MAKS_PERCOBAAN = 3;
    /** Log & antrean disimpan sekian hari. */
    public const RETENSI_HARI = 30;

    /** Interval Inform (detik) sebelum nilai aslinya terbaca dari ONT. */
    private const INTERVAL_BAWAAN = 600;

    /** Kejadian di log saat username / password ONT salah. */
    public const LOGIN_DITOLAK = 'Login ONT ditolak';

    private static ?bool $siap = null;

    public static function siap(): bool
    {
        if (self::$siap === null) {
            $db         = \Config\Database::connect();
            self::$siap = true;
            foreach (['d_acs_setting', 'd_acs_device', 'd_acs_wan', 'd_acs_task', 'd_acs_event'] as $t) {
                self::$siap = self::$siap && $db->tableExists($t);
            }
        }

        return self::$siap;
    }

    /** Tabel data detail ONT sudah dibuat (d_acs.sql terbaru). */
    public static function dataSiap(): bool
    {
        static $siap = null;

        return $siap ??= \Config\Database::connect()->tableExists('d_acs_data');
    }

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

    private static function sekarang(): string
    {
        return date('Y-m-d H:i:s');
    }

    private static function potong(?string $s, int $maks = 255): ?string
    {
        $s = $s === null ? '' : trim(mb_convert_encoding($s, 'UTF-8', 'UTF-8'));

        return $s === '' ? null : mb_substr($s, 0, $maks);
    }

    // ================= SETTINGS =================

    public function setting(): array
    {
        $s = $this->db->table('d_acs_setting')->where('id', 1)->get()->getRowArray()
            ?? ['id' => 1, 'acs_url' => 'dev.jwi.id', 'acs_username' => 'acsjwi', 'acs_password' => null, 'updated_by' => null, 'updated_at' => null];
        $s['acs_url'] = self::domain($s['acs_url']);

        return $s;
    }

    /** Ambil domain (dan port) saja: https://dev.jwi.id/acs/cwmp -> dev.jwi.id */
    public static function domain(string $url): string
    {
        return strtolower((string) preg_replace('~^[a-z]+://|[/?#].*$~i', '', trim($url)));
    }

    /** Simpan Settings; password null = tidak diubah. */
    public function simpanSetting(array $data, ?string $password, string $oleh): void
    {
        $data += ['updated_by' => $oleh, 'updated_at' => self::sekarang()];
        if ($password !== null) {
            $data['acs_password'] = self::enkripsi($password);
        }
        if ($this->db->table('d_acs_setting')->where('id', 1)->countAllResults()) {
            $this->db->table('d_acs_setting')->where('id', 1)->update($data);
        } else {
            $this->db->table('d_acs_setting')->insert($data + ['id' => 1]);
        }
    }

    // ================= LOG =================

    public function catat(string $jenis, string $kejadian, string $detail, ?string $ip, ?int $deviceId = null): void
    {
        $this->db->table('d_acs_event')->insert([
            'device_id'  => $deviceId,
            'jenis'      => $jenis,
            'kejadian'   => self::potong($kejadian, 128) ?? '-',
            'detail'     => self::potong($detail),
            'ip_address' => $ip,
            'created_at' => self::sekarang(),
        ]);

        if (! cache('acs_bersih')) {
            cache()->save('acs_bersih', 1, 3600);
            $lama = date('Y-m-d H:i:s', strtotime('-' . self::RETENSI_HARI . ' days'));
            $this->db->table('d_acs_event')->where('created_at <', $lama)->delete();
            $this->db->table('d_acs_task')->where('created_at <', $lama)->delete();
        }
    }

    private function kueriLog(array $f)
    {
        $q = $this->db->table('d_acs_event e')->join('d_acs_device d', 'd.id = e.device_id', 'left');
        if ($f['tampil'] === 'ok') {
            $q->where('e.jenis', 'ok');
        } elseif ($f['tampil'] === 'login') {
            $q->where('e.kejadian', self::LOGIN_DITOLAK);
        } elseif ($f['tampil'] === 'error') {
            $q->where('e.jenis', 'gagal')->where('e.kejadian !=', self::LOGIN_DITOLAK);
        }
        if ($f['jenis'] !== '') {
            $q->where('e.jenis', $f['jenis']);
        }
        if ($f['kejadian'] !== '') {
            $q->like('e.kejadian', $f['kejadian']);
        }
        if ($f['q'] !== '') {
            $q->groupStart()->like('d.serial_number', $f['q'])->orLike('e.ip_address', $f['q'])
                ->orLike('e.kejadian', $f['q'])->orLike('e.detail', $f['q'])->groupEnd();
        }
        if ($f['dari'] !== '') {
            $q->where('e.created_at >=', $f['dari'] . ' 00:00:00');
        }
        if ($f['sampai'] !== '') {
            $q->where('e.created_at <=', $f['sampai'] . ' 23:59:59');
        }

        return $q;
    }

    public function jumlahLog(array $f): int
    {
        return $this->kueriLog($f)->countAllResults();
    }

    /** Satu halaman log; $kolom = nomor kolom tabel Log Koneksi. */
    public function daftarLog(array $f, int $per, int $mulai, int $kolom = 1, bool $naik = false): array
    {
        $urut = [2 => 'e.jenis', 3 => 'e.kejadian', 4 => 'd.serial_number', 5 => 'e.ip_address'][$kolom] ?? null;
        $q    = $this->kueriLog($f)->select('e.*, d.serial_number');
        if ($urut !== null) {
            $q->orderBy($urut, $naik ? 'ASC' : 'DESC');
        }

        return $q->orderBy('e.id', $urut === null && $naik ? 'ASC' : 'DESC')->limit($per, $mulai)->get()->getResultArray();
    }

    /** Jumlah log untuk kartu: total, berhasil, error ONT, login ditolak. */
    public function ringkasLog(): array
    {
        $r = $this->db->query(
            "SELECT COUNT(*) AS total, COALESCE(SUM(jenis = 'ok'), 0) AS ok, COALESCE(SUM(kejadian = ?), 0) AS login FROM d_acs_event",
            [self::LOGIN_DITOLAK]
        )->getRowArray();

        return ['total' => (int) $r['total'], 'ok' => (int) $r['ok'], 'login' => (int) $r['login'], 'error' => (int) $r['total'] - (int) $r['ok'] - (int) $r['login']];
    }

    // ================= ONT =================

    /** Simpan ONT dari Inform, kembalikan barisnya. */
    public function simpanInform(array $i): array
    {
        $sekarang = self::sekarang();
        $data     = [
            'oui'          => self::potong($i['oui'], 16),
            'manufacturer' => self::potong($i['manufacturer'], 128),
            'product_class' => self::potong($i['product_class'], 128),
            'ip_address'   => $i['ip'],
            'last_event'   => self::potong(implode(', ', $i['event'])),
            'last_inform'  => $sekarang,
        ];
        // hanya ditimpa kalau ONT mengirimnya
        foreach (['software_version', 'hardware_version', 'connection_request_url'] as $k) {
            if (self::potong($i[$k]) !== null) {
                $data[$k] = self::potong($i[$k]);
            }
        }

        $serial = mb_substr($i['serial'], 0, 128);
        $this->db->query(
            'INSERT IGNORE INTO d_acs_device (serial_number, first_seen, last_inform) VALUES (?, ?, ?)',
            [$serial, $sekarang, $sekarang]
        );
        $this->db->table('d_acs_device')->where('serial_number', $serial)->set('inform_count', 'inform_count + 1', false)->update($data);

        return $this->db->table('d_acs_device')->where('serial_number', $serial)->get()->getRowArray();
    }

    /** ONT yang baru saja Inform dari IP ini; null kalau tidak ada atau lebih dari satu (NAT). */
    public function tunggalDariIp(string $ip): ?int
    {
        $baris = $this->db->table('d_acs_device')->select('id')->where('ip_address', $ip)
            ->where('last_inform >=', date('Y-m-d H:i:s', time() - 120))->limit(2)->get()->getResultArray();

        return count($baris) === 1 ? (int) $baris[0]['id'] : null;
    }

    /** Syarat SQL "online": Inform terakhir masih dalam 2x interval ONT (+1 menit); interval dibaca dari ONT. */
    private function sqlOnline(): string
    {
        return 'TIMESTAMPDIFF(SECOND, d.last_inform, ' . $this->db->escape(self::sekarang()) . ') <= 2 * COALESCE(d.inform_interval, ' . self::INTERVAL_BAWAAN . ') + 60';
    }

    public function ringkasOnt(): array
    {
        $r = $this->db->query('SELECT COUNT(*) AS total, COALESCE(SUM(' . $this->sqlOnline() . '), 0) AS online, MAX(last_inform) AS akhir FROM d_acs_device d')->getRowArray();

        return ['total' => (int) $r['total'], 'online' => (int) $r['online'], 'akhir' => $r['akhir']];
    }

    /** Nomor log terakhir, titik awal statusKoneksi(). */
    public function logTerakhir(): int
    {
        return (int) ($this->db->table('d_acs_event')->selectMax('id')->get()->getRow()->id ?? 0);
    }

    /**
     * Untuk notifikasi halaman Settings: ONT yang sedang online dan login ONT yang ditolak setelah log $sejak.
     * Login yang ditolak dari browser / bukan ONT tidak ikut.
     */
    public function statusKoneksi(int $sejak): array
    {
        $tolak = $this->db->table('d_acs_event')->select('id, detail')->where('id >', $sejak)->where('kejadian', self::LOGIN_DITOLAK)
            ->notLike('detail', 'bukan ONT')->notLike('detail', 'bukan Inform ONT')->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();

        return [
            'online' => $this->db->table('d_acs_device d')->select('d.id, d.serial_number AS sn, d.product_class AS model')
                ->where($this->sqlOnline(), null, false)->orderBy('d.last_inform', 'DESC')->limit(1000)->get()->getResultArray(),
            'tolak'  => $tolak ? (string) $tolak['detail'] : null,
            'log'    => $this->logTerakhir(),
        ];
    }

    /** Semua ONT + WAN utama (INTERNET diutamakan) + RX power, yang terbaru Inform di atas. */
    public function semuaOnt(): array
    {
        $list = $this->db->table('d_acs_device d')->select('d.*')->select($this->sqlOnline() . ' AS online', false)
            ->orderBy('d.last_inform', 'DESC')->orderBy('d.id', 'DESC')->get()->getResultArray();
        if (! $list) {
            return [];
        }
        $wan = [];
        foreach ($this->db->table('d_acs_wan')->orderBy('path', 'ASC')->get()->getResultArray() as $w) {
            $wan[(int) $w['device_id']][] = self::tanpaPassword($w);
        }
        $optik = [];
        foreach ($this->db->table('d_acs_data')->where('grup', 'optik')->get()->getResultArray() as $b) {
            $optik[(int) $b['device_id']] = self::bukaData($b)['p'];
        }
        foreach ($list as &$d) {
            $milik           = self::urutWan($wan[(int) $d['id']] ?? []);
            $d['online']     = (bool) $d['online'];
            $d['wan']        = $milik[0] ?? null;
            $d['jumlah_wan'] = count($milik);
            $o               = AcsData::optik($optik[(int) $d['id']] ?? []);
            $d['rx']         = $o['rx'];
            $d['rx_nilai']   = $o['status'];
            $d['id_pelanggan'] = self::idPelanggan($milik);
        }
        unset($d);
        $nama = $this->namaPelanggan(array_column($list, 'id_pelanggan'));
        foreach ($list as &$d) {
            $d['nama_pelanggan'] = $nama[mb_strtolower($d['id_pelanggan'])] ?? '';
        }
        unset($d);

        return $list;
    }

    /** Satu ONT + semua WAN-nya. */
    public function detailOnt(int $id): ?array
    {
        $d = $this->db->table('d_acs_device d')->select('d.*')->select($this->sqlOnline() . ' AS online', false)->where('d.id', $id)->get()->getRowArray();
        if (! $d) {
            return null;
        }
        $d['online'] = (bool) $d['online'];
        $d['wan']    = self::urutWan(array_map(
            static fn ($w) => self::tanpaPassword($w),
            $this->db->table('d_acs_wan')->where('device_id', $id)->orderBy('path', 'ASC')->get()->getResultArray()
        ));
        $d['id_pelanggan']   = self::idPelanggan($d['wan']);
        $d['nama_pelanggan'] = $this->namaPelanggan([$d['id_pelanggan']])[mb_strtolower($d['id_pelanggan'])] ?? '';

        return $d;
    }

    /** ID pelanggan yang terbaca dari ONT: username PPPoE sebelum '@' ('' = ONT tidak memakai PPPoE / belum terbaca). */
    private static function idPelanggan(array $wan): string
    {
        foreach ($wan as $w) {
            $id = trim(explode('@', (string) ($w['username'] ?? ''), 2)[0]);
            if ($id !== '') {
                return $id;
            }
        }

        return '';
    }

    /**
     * Nama pelanggan di portal untuk ID tersebut (Data ODC & ODP, lalu Data Aktivasi).
     *
     * @return array<string, string> id huruf kecil => nama
     */
    private function namaPelanggan(array $id): array
    {
        $id   = array_values(array_unique(array_filter($id, static fn ($x) => $x !== '')));
        $nama = [];
        foreach ([['d_aktivasi', 'id_pelanggan'], ['d_pelanggan_odp', 'kode_pelanggan']] as [$tabel, $kolom]) {
            if (! $id || ! $this->db->tableExists($tabel)) {
                continue;
            }
            foreach (array_chunk($id, 500) as $bagian) {
                foreach ($this->db->table($tabel)->select($kolom . ' AS id, nama_pelanggan')->whereIn($kolom, $bagian)->get()->getResultArray() as $r) {
                    $nama[mb_strtolower(trim($r['id']))] = (string) $r['nama_pelanggan'];
                }
            }
        }

        return $nama;
    }

    private static function tanpaPassword(array $w): array
    {
        $w['ada_password'] = $w['password'] !== null;
        unset($w['password']);

        return $w;
    }

    private static function urutWan(array $wan): array
    {
        usort($wan, static fn ($a, $b) => self::bobotWan($b) <=> self::bobotWan($a));

        return $wan;
    }

    private static function bobotWan(array $w): int
    {
        return (stripos((string) $w['layanan'] . ' ' . $w['deskripsi'], 'INTERNET') !== false ? 4 : 0)
            + ($w['jenis'] === 'PPP' ? 2 : 0)
            + (strcasecmp((string) $w['status'], 'Connected') === 0 ? 1 : 0);
    }

    public function passwordWan(int $id): ?string
    {
        $w = $this->db->table('d_acs_wan')->select('password')->where('id', $id)->get()->getRowArray();

        return $w ? self::dekripsi($w['password']) : null;
    }

    // ================= ANTREAN =================

    /**
     * Antrekan pembacaan data ONT. $paksa = semua grup (tombol Baca Ulang / ONT baru menyala),
     * selain itu hanya grup yang sudah waktunya dan tidak baru saja ditolak ONT.
     *
     * @param array $d baris d_acs_device
     */
    public function antreBaca(array $d, bool $paksa, array $hanya = []): void
    {
        $id    = (int) $d['id'];
        $waktu = ['wan' => $d['last_read']];
        if (self::dataSiap()) {
            $waktu += array_column($this->db->table('d_acs_data')->select('grup, updated_at')->where('device_id', $id)->get()->getResultArray(), 'updated_at', 'grup');
        }
        foreach (self::BACA as $grup => [$path, $jeda]) {
            $wajib = $paksa || in_array($grup, $hanya, true);
            if ($grup !== 'wan' && ! self::dataSiap()) {
                continue;
            }
            if (! $wajib && isset($waktu[$grup]) && time() - strtotime($waktu[$grup]) < $jeda) {
                continue;
            }
            $names = match ($grup) {
                'optik' => [AcsData::pathOptik($d['manufacturer'])],
                'mac'   => AcsData::pathMac($d['manufacturer']),
                default => [$path],
            };
            if (! $names) {
                continue; // tidak didukung untuk merek ini
            }
            $payload = json_encode(['grup' => $grup, 'names' => $names], JSON_UNESCAPED_SLASHES);
            $t       = fn () => $this->db->table('d_acs_task')->where('device_id', $id)->where('payload', $payload);
            if ($t()->whereIn('status', ['Pending', 'Sent'])->countAllResults()) {
                continue;
            }
            if (! $wajib && $t()->where('status', 'Failed')->like('hasil', 'Fault', 'after')
                ->where('completed_at >=', date('Y-m-d H:i:s', time() - self::JEDA_DITOLAK))->countAllResults()) {
                continue;
            }
            $this->db->table('d_acs_task')->insert([
                'device_id'  => $id,
                'perintah'   => 'GetParameterValues',
                'payload'    => $payload,
                'kunci'      => 'ACS' . strtoupper(bin2hex(random_bytes(8))),
                'created_at' => self::sekarang(),
            ]);
        }
    }

    /** Antrekan pembacaan satu objek di luar jadwal (mis. hasil ping). */
    public function antreKhusus(int $deviceId, string $grup, array $names): void
    {
        $this->sisip($deviceId, 'GetParameterValues', ['grup' => $grup, 'names' => $names]);
    }

    private function sisip(int $deviceId, string $perintah, array $payload): int
    {
        $this->db->table('d_acs_task')->insert([
            'device_id'  => $deviceId,
            'perintah'   => $perintah,
            'payload'    => json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'kunci'      => 'ACS' . strtoupper(bin2hex(random_bytes(8))),
            'created_at' => self::sekarang(),
        ]);

        return (int) $this->db->insertID();
    }

    /** Antrekan perintah yang mengubah ONT; null kalau masih ada yang menunggu (satu per ONT agar tidak bertabrakan). */
    public function antreTulis(int $deviceId, string $perintah, array $payload): ?int
    {
        $this->kedaluwarsa($deviceId);
        $ada = $this->db->table('d_acs_task')->where('device_id', $deviceId)->where('perintah !=', 'GetParameterValues')
            ->whereIn('status', ['Pending', 'Sent'])->countAllResults();

        return $ada ? null : $this->sisip($deviceId, $perintah, $payload);
    }

    public function batalkanTulis(int $deviceId, int $id, string $oleh): bool
    {
        $this->db->table('d_acs_task')->where('id', $id)->where('device_id', $deviceId)->where('status', 'Pending')->where('perintah !=', 'GetParameterValues')
            ->update(['status' => 'Failed', 'hasil' => self::potong('Dibatalkan oleh ' . $oleh), 'completed_at' => self::sekarang()]);
        $ok = $this->db->affectedRows() > 0;
        if ($ok) {
            $this->samarkan($id);
        }

        return $ok;
    }

    /** Perintah tulis yang terlalu lama menunggu dibatalkan (agar tidak tiba-tiba jalan berjam-jam kemudian). */
    private function kedaluwarsa(int $deviceId): void
    {
        $this->db->table('d_acs_task')->where('device_id', $deviceId)->where('status', 'Pending')->where('perintah !=', 'GetParameterValues')
            ->where('created_at <', date('Y-m-d H:i:s', time() - self::UMUR_TULIS))
            ->update(['status' => 'Failed', 'hasil' => 'Kedaluwarsa: ONT tidak mengambil perintah', 'completed_at' => self::sekarang()]);
    }

    /** Nilai rahasia (password) di payload dihapus setelah perintah selesai. */
    private function samarkan(int $id): void
    {
        $t = $this->db->table('d_acs_task')->select('payload')->where('id', $id)->get()->getRowArray();
        $p = json_decode((string) ($t['payload'] ?? ''), true);
        if (! is_array($p) || ! array_filter($p['parameter'] ?? [], static fn ($x) => ! empty($x['enk']))) {
            return;
        }
        foreach ($p['parameter'] as &$x) {
            if (! empty($x['enk'])) {
                $x['v'] = '';
            }
        }
        unset($x);
        $this->db->table('d_acs_task')->where('id', $id)->update(['payload' => json_encode($p, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]);
    }

    /** Langkah berikutnya setelah perintah tulis selesai: lanjutan, pembersihan, atau baca ulang data. */
    public function lanjutkan(array $task, bool $ok, string $instance = ''): void
    {
        $this->samarkan((int) $task['id']);
        $id   = (int) $task['device_id'];
        $p    = json_decode((string) $task['payload'], true) ?: [];
        $isi  = static function (array $x) use ($instance): array {
            array_walk_recursive($x, static function (&$v) use ($instance) {
                if (is_string($v) && $instance !== '') {
                    $v = str_replace('{N}', $instance, $v);
                }
            });

            return $x;
        };
        $sisip = function (array $x) use ($id, $p) {
            $perintah = $x['perintah'];
            unset($x['perintah']);
            $this->sisip($id, $perintah, $x + ['label' => ($p['label'] ?? '') . ' (lanjutan)', 'oleh' => $p['oleh'] ?? '']);
        };
        if ($ok) {
            foreach ($p['lanjut'] ?? [] as $x) {
                $sisip($isi($x));
            }
        } elseif (! empty($p['gagal'])) {
            $sisip($isi($p['gagal']));
        }
        if ($ok && ! empty($p['baca']) && ($d = $this->db->table('d_acs_device')->where('id', $id)->get()->getRowArray())) {
            $d['last_read'] ??= self::sekarang();
            $this->antreGrup($d, $p['baca']);
        }
    }

    private function antreGrup(array $d, array $grup): void
    {
        foreach ($grup as $g) {
            $names = match ($g) {
                'optik' => [AcsData::pathOptik($d['manufacturer'])],
                'mac'   => AcsData::pathMac($d['manufacturer']),
                default => [self::BACA[$g][0] ?? ''],
            };
            $this->sisip((int) $d['id'], 'GetParameterValues', ['grup' => $g, 'names' => $names]);
        }
    }

    /** Perintah terbaru untuk halaman detail (tanpa nilai parameter). */
    public function tugasRingkas(int $deviceId, int $batas = 30): array
    {
        $this->kedaluwarsa($deviceId);

        return array_map(static function ($t) {
            $p = json_decode((string) $t['payload'], true) ?: [];

            return [
                'id'     => (int) $t['id'],
                'tulis'  => $t['perintah'] !== 'GetParameterValues',
                'label'  => $p['label'] ?? ('Baca ' . implode(', ', $p['names'] ?? [])),
                'oleh'   => $p['oleh'] ?? '',
                'status' => $t['status'],
                'hasil'  => $t['hasil'],
                'waktu'  => $t['created_at'],
            ];
        }, $this->riwayatBaca($deviceId, $batas));
    }

    public function riwayatBaca(int $deviceId, int $batas = 50): array
    {
        return $this->db->table('d_acs_task')->where('device_id', $deviceId)->orderBy('id', 'DESC')->limit($batas)->get()->getResultArray();
    }

    /**
     * Awal sesi baru: perintah baca yang dulu terkirim tapi tidak dijawab dicoba lagi (maks. 3x).
     * Perintah yang mengubah ONT TIDAK dikirim ulang (bisa jadi sudah dijalankan, mis. reboot).
     */
    public function pulihkanAntrean(int $deviceId): void
    {
        $t     = fn () => $this->db->table('d_acs_task')->where('device_id', $deviceId)->where('status', 'Sent');
        $gagal = ['status' => 'Failed', 'completed_at' => self::sekarang()];
        foreach ($t()->where('perintah !=', 'GetParameterValues')->get()->getResultArray() as $x) {
            $this->db->table('d_acs_task')->where('id', $x['id'])->update($gagal + ['hasil' => 'Terkirim, tetapi ONT tidak menjawab (mungkin sudah dijalankan)']);
            $this->samarkan((int) $x['id']);
        }
        $t()->where('percobaan >=', self::MAKS_PERCOBAAN)->update($gagal + ['hasil' => 'ONT tidak menjawab']);
        $t()->update(['status' => 'Pending']);
        $this->kedaluwarsa($deviceId);
    }

    /** Perintah berikutnya; yang mengubah ONT didahulukan. */
    public function ambilAntrean(int $deviceId): ?array
    {
        $t = $this->db->table('d_acs_task')->where('device_id', $deviceId)->where('status', 'Pending')
            ->orderBy("perintah = 'GetParameterValues'", 'ASC', false)->orderBy('id', 'ASC')->limit(1)->get()->getRowArray();
        if ($t) {
            $this->db->table('d_acs_task')->where('id', $t['id'])->set('percobaan', 'percobaan + 1', false)
                ->update(['status' => 'Sent', 'sent_at' => self::sekarang()]);
        }

        return $t;
    }

    /** Tandai perintah selesai; kembalikan barisnya (null = bukan jawaban perintah yang sedang dikirim). */
    public function selesaikanAntrean(int $deviceId, string $kunci, bool $ok, string $hasil): ?array
    {
        $q = $this->db->table('d_acs_task')->where('device_id', $deviceId)->where('status', 'Sent');
        if ($kunci !== '') {
            $q->where('kunci', $kunci);
        }
        $t = $q->orderBy('sent_at', 'DESC')->limit(1)->get()->getRowArray();
        if ($t) {
            $this->db->table('d_acs_task')->where('id', $t['id'])->update([
                'status'       => $ok ? 'Completed' : 'Failed',
                'hasil'        => self::potong($hasil),
                'completed_at' => self::sekarang(),
            ]);
        }

        return $t;
    }

    // ================= DATA ONT =================

    /** Simpan jawaban GetParameterValues sesuai grupnya. */
    public function simpanNilai(int $deviceId, string $grup, array $nilai): void
    {
        if ($grup === 'wan') {
            $this->simpanWan($deviceId, $nilai); // data mentahnya ikut disimpan di bawah (AddressingType dll.)
        }
        if ($grup === 'acs') {
            foreach ($nilai as $k => $v) {
                if (str_ends_with($k, '.PeriodicInformInterval')) {
                    $this->db->table('d_acs_device')->where('id', $deviceId)->update(['inform_interval' => (int) $v > 0 ? min((int) $v, 4294967295) : null]);
                }
            }
        }
        if (! self::dataSiap()) {
            return;
        }

        $p = $r = [];
        foreach (array_slice($nilai, 0, self::MAKS_PARAMETER, true) as $k => $v) {
            if (preg_match('/\.(DeviceLog|VendorLogFile\.)/', $k)) {
                continue; // log perangkat: panjang & tidak dipakai
            }
            $v = trim(mb_convert_encoding((string) $v, 'UTF-8', 'UTF-8'));
            if (preg_match(AcsData::POLA_RAHASIA, $k)) {
                // nama parameternya tetap dicatat walau nilainya kosong / disamarkan (dipakai saat mengubah password WiFi)
                $r[$k] = $v !== '' && ! preg_match('/^[*\x{2022}]+$/u', $v) ? self::enkripsi($v) : '';
            } else {
                $p[mb_substr($k, 0, 255)] = mb_substr($v, 0, 1000);
            }
        }
        $this->db->table('d_acs_data')->replace([
            'device_id'  => $deviceId,
            'grup'       => $grup,
            'isi'        => json_encode(['p' => $p, 'r' => $r], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_INVALID_UTF8_SUBSTITUTE),
            'updated_at' => self::sekarang(),
        ]);
    }

    /** @return array{p: array<string, string>, r: array<string, string>} */
    private static function bukaData(array $baris): array
    {
        $j = json_decode((string) $baris['isi'], true);

        return ['p' => is_array($j['p'] ?? null) ? $j['p'] : [], 'r' => is_array($j['r'] ?? null) ? $j['r'] : []];
    }

    /** Data semua grup satu ONT: [grup => ['p' => parameter, 'rahasia' => nama parameter rahasia, 'waktu' => dibaca]]. */
    public function dataOnt(int $deviceId): array
    {
        $hasil = [];
        foreach ($this->db->table('d_acs_data')->where('device_id', $deviceId)->get()->getResultArray() as $b) {
            $x                 = self::bukaData($b);
            $hasil[$b['grup']] = ['p' => $x['p'], 'rahasia' => array_keys($x['r']), 'terisi' => array_keys(array_filter($x['r'])), 'waktu' => $b['updated_at']];
        }

        return $hasil;
    }

    public function rahasiaOnt(int $deviceId, string $grup, string $nama): ?string
    {
        $b = $this->db->table('d_acs_data')->where('device_id', $deviceId)->where('grup', $grup)->get()->getRowArray();
        $r = $b ? self::bukaData($b)['r'] : [];

        return ! empty($r[$nama]) ? self::dekripsi($r[$nama]) : null;
    }

    /** Simpan data WAN; WAN yang sudah tidak ada di ONT dihapus. */
    private function simpanWan(int $deviceId, array $nilai): void
    {
        $wan = [];
        foreach ($nilai as $nama => $v) {
            if (preg_match('~^(InternetGatewayDevice\.WANDevice\.\d+\.WANConnectionDevice\.\d+\.WAN(PPP|IP)Connection\.\d+)\.([^.]+)$~', $nama, $m)) {
                $wan[$m[1]]['jenis']      = $m[2];
                $wan[$m[1]]['p'][$m[3]] = trim($v);
            }
        }

        $sekarang = self::sekarang();
        foreach ($wan as $path => $w) {
            $p    = $w['p'];
            // parameter vendor (mis. X_HW_SERVICELIST, X_HW_VLAN) dicari dari akhiran namanya
            $cari = static function (string $pola) use ($p) {
                foreach ($p as $k => $v) {
                    if ($v !== '' && preg_match($pola, $k)) {
                        return $v;
                    }
                }

                return null;
            };
            $kosong0 = static fn ($v) => $v === '0.0.0.0' ? null : $v;
            $data    = [
                'jenis'       => $w['jenis'],
                'deskripsi'   => self::potong($p['Name'] ?? null),
                'layanan'     => self::potong($cari('/SERVICE_?LIST$/i'), 100),
                'vlan'        => self::potong($cari('/(^|_)VLAN(ID)?(Mark)?$/i'), 20),
                'status'      => self::potong($p['ConnectionStatus'] ?? null, 50),
                'mode'        => self::potong($p['ConnectionType'] ?? null, 50),
                'username'    => self::potong($p['Username'] ?? null, 190),
                'mac_address' => self::potong($p['MACAddress'] ?? null, 50),
                'ip_address'  => self::potong($kosong0($p['ExternalIPAddress'] ?? null), 64),
                'gateway'     => self::potong($kosong0($p['DefaultGateway'] ?? null), 64) ?? self::potong($kosong0($p['RemoteIPAddress'] ?? null), 64),
                'dns'         => self::potong($p['DNSServers'] ?? null),
                'updated_at'  => $sekarang,
            ];
            // password hanya disimpan kalau ONT benar-benar mengirimnya (bukan kosong / disamarkan)
            $sandi = $p['Password'] ?? '';
            if ($sandi !== '' && ! preg_match('/^[*\x{2022}]+$/u', $sandi)) {
                $data['password'] = self::enkripsi($sandi);
            }

            $kunci = ['device_id' => $deviceId, 'path' => mb_substr($path, 0, 190)];
            $lama  = $this->db->table('d_acs_wan')->where($kunci)->get()->getRowArray();
            if ($lama) {
                if ($lama['username'] !== $data['username'] && ! isset($data['password'])) {
                    $data['password'] = null; // username berganti: password lama tidak berlaku
                }
                $this->db->table('d_acs_wan')->where('id', $lama['id'])->update($data);
            } else {
                $this->db->table('d_acs_wan')->insert($kunci + $data);
            }
        }

        $hapus = $this->db->table('d_acs_wan')->where('device_id', $deviceId);
        if ($wan) {
            $hapus->whereNotIn('path', array_keys($wan));
        }
        $hapus->delete();
        $this->db->table('d_acs_device')->where('id', $deviceId)->update(['last_read' => $sekarang]);
    }
}
