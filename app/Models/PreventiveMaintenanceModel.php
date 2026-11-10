<?php

namespace App\Models;

use App\Models\BaseModel;

// Preventive Maintenance (menu Report NOC > Preventive Maintenance); tampilan & fitur sama dengan Survey Report.
// Struktur mengikuti "Maintenance Report Form Yesnet.docx". Perusahaan & logo memakai data RFO (RFOModel).
class PreventiveMaintenanceModel extends BaseModel
{
    protected $table      = 'd_preventive_maintenance';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'pm_id',
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
        'layanan',
        'catatan',
        'nama_perangkat',
        'mac_address',
        'type_perangkat',
        'tx',
        'rx',
        'resume',
        'dibuat_oleh',
        'diketahui_oleh',
        'lampiran',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /** Pilihan Tipe Bisnis, urutan sama dengan docx (selain ini = isian bebas dari pilihan "Dll"). */
    public const TIPE_BISNIS = ['Personal', 'Corporate', 'Government'];

    /** Pilihan Jenis Layanan (selain ini = isian bebas dari pilihan "Dll"). */
    public const JENIS_LAYANAN = ['FO', 'Wireless', 'VSAT'];

    /** Folder foto lampiran. */
    public static function folderFoto(): string
    {
        return WRITEPATH . 'uploads/preventive-maintenance/';
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
     * PM ID berikutnya untuk tanggal hari ini: PM-dd/mm/yyyy-NNN (contoh PM-10/11/2026-001).
     * Nomor urut lanjut terus antar hari dan reset ke 001 setiap ganti bulan (sama dengan Survey ID).
     */
    public function generatePmId(): string
    {
        $last = $this->builder()
            ->select("MAX(CAST(SUBSTRING_INDEX(pm_id, '-', -1) AS UNSIGNED)) AS seq", false)
            ->where("pm_id LIKE 'PM-__/" . date('m/Y') . "-%'", null, false)
            ->get()
            ->getRowArray();

        $next = ((int) ($last['seq'] ?? 0)) + 1;

        return 'PM-' . date('d/m/Y') . '-' . str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }
}
