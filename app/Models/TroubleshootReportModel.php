<?php

namespace App\Models;

use App\Models\BaseModel;

// Troubleshoot Report (menu Report NOC > Troubleshoot Report); tampilan & fitur sama dengan Survey Report.
// Struktur mengikuti "Troubleshooting Report Form Yesnet (1).docx". Perusahaan & logo memakai data RFO (RFOModel).
class TroubleshootReportModel extends BaseModel
{
    protected $table      = 'd_troubleshoot_report';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'trouble_id',
        'perusahaan',
        'logo',
        'nama_pelanggan',
        'tipe_bisnis',
        'alamat',
        'telepon',
        'email_pelanggan',
        'tgl_spk',
        'no_spk',
        'tanggal_pelaksanaan',
        'pelaksana',
        'jenis_layanan',
        'catatan',
        'trouble',
        'action',
        'resume',
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

    /** Folder foto lampiran. */
    public static function folderFoto(): string
    {
        return WRITEPATH . 'uploads/troubleshoot-report/';
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
     * Troubleshoot ID berikutnya untuk tanggal hari ini: TRB-dd/mm/yyyy-NNN (contoh TRB-10/11/2026-001).
     * Nomor urut lanjut terus antar hari dan reset ke 001 setiap ganti bulan (sama dengan Survey ID).
     */
    public function generateTroubleId(): string
    {
        $last = $this->builder()
            ->select("MAX(CAST(SUBSTRING_INDEX(trouble_id, '-', -1) AS UNSIGNED)) AS seq", false)
            ->where("trouble_id LIKE 'TRB-__/" . date('m/Y') . "-%'", null, false)
            ->get()
            ->getRowArray();

        $next = ((int) ($last['seq'] ?? 0)) + 1;

        return 'TRB-' . date('d/m/Y') . '-' . str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }
}
