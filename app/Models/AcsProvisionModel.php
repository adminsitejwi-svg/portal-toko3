<?php

namespace App\Models;

use App\Libraries\AcsData;
use Config\Database;

// Provisioning ONT (ACS JWI Group), mengikuti Provisioning Profiles + Add ONT speednet_acs:
// profil berisi daftar parameter TR-069, diterapkan sekali ke SN yang didaftarkan saat ONT itu mengirim Inform.
class AcsProvisionModel
{
    public const TIPE = ['xsd:string', 'xsd:boolean', 'xsd:int', 'xsd:unsignedInt'];

    public const MAKS_ATURAN = 40;

    /** Parameter yang bisa memutus ONT dari ACS: tidak boleh ada di profil. */
    public const POLA_DILARANG = '/\.ManagementServer\.(URL|Username|Password|EnableCWMP)$/i';

    /** @var \CodeIgniter\Database\BaseConnection */
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public static function siap(): bool
    {
        static $siap = null;

        return $siap ??= Database::connect()->tableExists('d_acs_profile') && Database::connect()->tableExists('d_acs_provision');
    }

    /** SN disamakan bentuknya: Huawei mengirim 48575443XXXXXXXX, di stiker tertulis HWTCXXXXXXXX. */
    public static function kunciSn(string $sn): string
    {
        $sn = strtoupper((string) preg_replace('/\s+/', '', $sn));
        if (preg_match('/^((?:4[1-9A-F]|5[0-9A]){4})([0-9A-F]{8})$/', $sn, $m)) {
            return hex2bin($m[1]) . $m[2];
        }

        return $sn;
    }

    private static function sekarang(): string
    {
        return date('Y-m-d H:i:s');
    }

    // ================= PROFIL =================

    private static function buka(array $p): array
    {
        $p['aturan'] = json_decode((string) $p['aturan'], true) ?: [];

        return $p;
    }

    public function semuaProfil(): array
    {
        return array_map(
            [self::class, 'buka'],
            $this->db->table('d_acs_profile p')->select('p.*')
                ->select('(SELECT COUNT(*) FROM d_acs_provision o WHERE o.profil_id = p.id) AS jumlah_ont', false)
                ->orderBy('p.nama', 'ASC')->get()->getResultArray()
        );
    }

    public function profil(int $id): ?array
    {
        $p = $this->db->table('d_acs_profile')->where('id', $id)->get()->getRowArray();

        return $p ? self::buka($p) : null;
    }

    public function namaDipakai(string $nama, int $kecuali): bool
    {
        return $this->db->table('d_acs_profile')->where('nama', $nama)->where('id !=', $kecuali)->countAllResults() > 0;
    }

    public function simpanProfil(int $id, array $data): int
    {
        $data['aturan']     = json_encode($data['aturan'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $data['updated_at'] = self::sekarang();
        if ($id) {
            $this->db->table('d_acs_profile')->where('id', $id)->update($data);

            return $id;
        }
        $this->db->table('d_acs_profile')->insert($data + ['created_at' => self::sekarang()]);

        return (int) $this->db->insertID();
    }

    /** false = masih dipakai ONT yang belum selesai. */
    public function hapusProfil(int $id): bool
    {
        if ($this->db->table('d_acs_provision')->where('profil_id', $id)->whereIn('status', ['Menunggu', 'Tidak Cocok', 'Antre'])->countAllResults()) {
            return false;
        }
        $this->db->table('d_acs_profile')->where('id', $id)->delete();

        return true;
    }

    /** Nama parameter dari Parameter Master & yang pernah terbaca dari ONT, untuk saran di form profil. */
    public function saranParameter(): array
    {
        $nama = AcsParameterModel::siap() ? array_fill_keys((new AcsParameterModel())->semuaPath(), '') : [];
        foreach ($this->db->table('d_acs_data')->select('isi')->whereIn('grup', ['wifi', 'wan', 'info', 'mac', 'acs'])
            ->orderBy('updated_at', 'DESC')->limit(25)->get()->getResultArray() as $b) {
            $x     = json_decode((string) $b['isi'], true) ?: [];
            $nama += ($x['p'] ?? []) + ($x['r'] ?? []);
        }
        $nama = array_filter(array_map('strval', array_keys($nama)), static fn ($n) => ! preg_match(self::POLA_DILARANG, $n) && ! preg_match('/\.(Stats|AssociatedDevice|Total[A-Z])/', $n));
        natcasesort($nama);

        return array_slice(array_values($nama), 0, 1500);
    }

    // ================= ONT =================

    public function semuaOnt(): array
    {
        $this->sinkron();

        return $this->db->table('d_acs_provision o')
            ->select('o.*, p.nama AS profil, p.aktif AS profil_aktif, d.last_inform, d.manufacturer, d.product_class, t.status AS task_status')
            ->join('d_acs_profile p', 'p.id = o.profil_id', 'left')
            ->join('d_acs_device d', 'd.id = o.device_id', 'left')
            ->join('d_acs_task t', 't.id = o.task_id', 'left')
            ->orderBy('o.id', 'DESC')->get()->getResultArray();
    }

    public function ont(int $id): ?array
    {
        return $this->db->table('d_acs_provision')->where('id', $id)->get()->getRowArray();
    }

    /**
     * Daftarkan SN; yang sudah terdaftar memakai profil baru dan diterapkan ulang.
     *
     * @return string|null 'baru' | 'ulang', null = sedang diproses ONT
     */
    public function daftarkan(string $sn, int $profilId, string $oleh): ?string
    {
        $this->sinkron();
        $kunci = self::kunciSn($sn);
        $data  = ['profil_id' => $profilId, 'status' => 'Menunggu', 'hasil' => null, 'task_id' => null, 'created_by' => mb_substr($oleh, 0, 100), 'provisioned_at' => null];
        $ada   = $this->db->table('d_acs_provision')->where('kunci_sn', $kunci)->get()->getRowArray();
        if (! $ada) {
            $this->db->query('INSERT IGNORE INTO d_acs_provision (serial_number, kunci_sn, profil_id, status, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?)', [
                $sn, $kunci, $profilId, 'Menunggu', $data['created_by'], self::sekarang(),
            ]);

            return 'baru';
        }
        $this->db->table('d_acs_provision')->where('id', $ada['id'])->where('status !=', 'Antre')->update($data);

        return $this->db->affectedRows() > 0 || $ada['status'] === 'Menunggu' ? 'ulang' : null;
    }

    /**
     * Ubah SN / profil; profil baru diterapkan pada Inform berikutnya.
     *
     * @return string|null pesan kesalahan
     */
    public function ubahOnt(array $o, string $sn, int $profilId, string $oleh): ?string
    {
        $kunci = self::kunciSn($sn);
        if ($this->db->table('d_acs_provision')->where('kunci_sn', $kunci)->where('id !=', $o['id'])->countAllResults()) {
            return 'Serial number sudah terdaftar.';
        }
        $data = ['serial_number' => $sn, 'kunci_sn' => $kunci, 'profil_id' => $profilId, 'status' => 'Menunggu', 'hasil' => null,
            'task_id' => null, 'created_by' => mb_substr($oleh, 0, 100), 'provisioned_at' => null];
        if ($kunci !== $o['kunci_sn']) {
            $data['device_id'] = null;
        }
        $this->db->table('d_acs_provision')->where('id', $o['id'])->where('status !=', 'Antre')->update($data);

        return $this->db->table('d_acs_provision')->where('id', $o['id'])->where('status', 'Antre')->countAllResults()
            ? 'ONT ini sedang diproses, tunggu hasilnya dulu.' : null;
    }

    public function hapusOnt(array $o, AcsModel $acs, string $oleh): bool
    {
        // perintah yang sudah terkirim tidak bisa ditarik, tunggu jawabannya
        if ($o['status'] === 'Antre' && $o['task_id'] && ! $acs->batalkanTulis((int) $o['device_id'], (int) $o['task_id'], $oleh)
            && $this->db->table('d_acs_task')->where('id', $o['task_id'])->where('status', 'Sent')->countAllResults()) {
            return false;
        }
        $this->db->table('d_acs_provision')->where('id', $o['id'])->delete();

        return true;
    }

    /** Salin hasil perintah (d_acs_task) yang sudah selesai ke daftar ONT. */
    public function sinkron(): void
    {
        $this->db->query(
            "UPDATE d_acs_provision o LEFT JOIN d_acs_task t ON t.id = o.task_id
             SET o.status = IF(t.status = 'Completed', 'Berhasil', 'Gagal'),
                 o.hasil = IF(t.id IS NULL, 'Perintah tidak ditemukan', t.hasil),
                 o.provisioned_at = IF(t.status = 'Completed', t.completed_at, NULL)
             WHERE o.status = 'Antre' AND o.task_id IS NOT NULL AND (t.id IS NULL OR t.status IN ('Completed', 'Failed'))"
        );
    }

    /** Dipanggil setiap Inform: terapkan profil kalau SN ini terdaftar dan belum diterapkan. */
    public function terapkan(array $dev, AcsModel $acs, string $ip): void
    {
        if (! self::siap()) {
            return;
        }
        $o = $this->db->table('d_acs_provision')->where('kunci_sn', self::kunciSn($dev['serial_number']))->get()->getRowArray();
        if (! $o || in_array($o['status'], ['Berhasil', 'Gagal'], true)) {
            return;
        }
        $id = (int) $dev['id'];
        if ($o['status'] === 'Antre') {
            $this->sinkron();

            return;
        }
        $p     = $this->profil((int) $o['profil_id']);
        $catat = function (string $status, string $hasil) use ($o, $id, $acs, $ip) {
            if ($o['status'] === $status && $o['hasil'] === $hasil) {
                return;
            }
            $this->db->table('d_acs_provision')->where('id', $o['id'])->update(['status' => $status, 'hasil' => $hasil, 'device_id' => $id]);
            $acs->catat('gagal', 'Provisioning dilewati', $hasil, $ip, $id);
        };
        if (! $p || ! (int) $p['aktif']) {
            $catat('Menunggu', $p ? 'Profil nonaktif' : 'Profil tidak ditemukan');

            return;
        }
        if (($p['merek'] && stripos((string) $dev['manufacturer'], $p['merek']) === false)
            || ($p['model'] && strcasecmp((string) $dev['product_class'], $p['model']) !== 0)) {
            $catat('Tidak Cocok', mb_substr('ONT ' . $dev['manufacturer'] . ' ' . $dev['product_class'] . ' tidak sesuai profil', 0, 255));

            return;
        }

        // satu permintaan saja yang boleh memproses baris ini
        $this->db->table('d_acs_provision')->where('id', $o['id'])->whereIn('status', ['Menunggu', 'Tidak Cocok'])
            ->update(['status' => 'Antre', 'hasil' => null, 'task_id' => null, 'device_id' => $id]);
        if ($this->db->affectedRows() < 1) {
            return;
        }
        $baca = [];
        foreach (['wifi' => '.WLANConfiguration.', 'wan' => '.WANConnectionDevice.', 'info' => '.DeviceInfo.', 'mac' => AcsData::MAC, 'acs' => '.ManagementServer.PeriodicInform'] as $g => $pola) {
            foreach ($p['aturan'] as $x) {
                if (str_contains($x['n'], $pola)) {
                    $baca[$g] = $g;
                }
            }
        }
        $task = $acs->antreTulis($id, 'SetParameterValues', [
            'label'     => 'Provisioning: ' . $p['nama'],
            'oleh'      => $o['created_by'],
            'parameter' => $p['aturan'],
            'baca'      => array_values($baca),
        ]);
        if ($task === null) { // masih ada perintah lain, dicoba lagi pada Inform berikutnya
            $this->db->table('d_acs_provision')->where('id', $o['id'])->update(['status' => 'Menunggu', 'hasil' => 'Menunggu perintah lain selesai']);

            return;
        }
        $this->db->table('d_acs_provision')->where('id', $o['id'])->update(['task_id' => $task]);
        $acs->catat('ok', 'Provisioning', 'Profil ' . $p['nama'] . '; ' . count($p['aturan']) . ' parameter masuk antrean', $ip, $id);
    }
}
