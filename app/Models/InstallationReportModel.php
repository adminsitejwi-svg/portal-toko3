<?php

namespace App\Models;

use App\Models\BaseModel;

// Installation Report (menu Report NOC > Installation Report); tampilan & fitur sama dengan Survey Report.
// Struktur mengikuti "Installation Report Form Yesnet (1).docx". Perusahaan & logo memakai data RFO (RFOModel).
class InstallationReportModel extends BaseModel
{
    protected $table      = 'd_installation_report';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'install_id',
        'perusahaan',
        'logo',
        'nama_pelanggan',
        'tipe_bisnis',
        'alamat',
        'pic_hp',
        'email_pelanggan',
        'tgl_spk',
        'no_spk',
        'tanggal_pelaksanaan',
        'pelaksana',
        'jenis_layanan',
        'layanan',
        'catatan',
        'nama_perangkat',
        'dibuat_oleh',
        'diketahui_oleh',
        'lampiran',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /** Pilihan Tipe Bisnis, urutan sama dengan docx (selain ini = isian bebas dari pilihan "Dll"). */
    public const TIPE_BISNIS = ['Corporate', 'Personal', 'Government'];

    /** Pilihan Jenis Layanan (selain ini = isian bebas dari pilihan "Dll"). */
    public const JENIS_LAYANAN = ['FO', 'Wireless', 'VSAT'];

    /** Folder foto lampiran testlink. */
    public static function folderFoto(): string
    {
        return WRITEPATH . 'uploads/installation-report/';
    }

    /** Foto lampiran beserta keterangannya: [['file' => nama file, 'ket' => keterangan], ...]. */
    public static function lampiran(?array $ir): array
    {
        return SurveyReportModel::lampiran($ir);
    }

    /** Daftar nama file foto lampiran saja. */
    public static function foto(?array $ir): array
    {
        return array_column(self::lampiran($ir), 'file');
    }

    /**
     * Installation ID berikutnya untuk tanggal hari ini: INS-dd/mm/yyyy-NNN (contoh INS-10/11/2026-001).
     * Nomor urut lanjut terus antar hari dan reset ke 001 setiap ganti bulan (sama dengan Survey ID).
     */
    public function generateInstallId(): string
    {
        $last = $this->builder()
            ->select("MAX(CAST(SUBSTRING_INDEX(install_id, '-', -1) AS UNSIGNED)) AS seq", false)
            ->where("install_id LIKE 'INS-__/" . date('m/Y') . "-%'", null, false)
            ->get()
            ->getRowArray();

        $next = ((int) ($last['seq'] ?? 0)) + 1;

        return 'INS-' . date('d/m/Y') . '-' . str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }
}
