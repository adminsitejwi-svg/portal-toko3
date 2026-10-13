<?php

namespace App\Models;

use App\Models\BaseModel;

class MDMaintenanceModel extends BaseModel
{
    protected $table            = 'md_maintenance';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'maintenance_id',
        'perusahaan',
        'logo',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai',
        'estimasi',
        'kegiatan',
        'impact',
        'nama',
        'email',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';

    /** Perusahaan, logo, nama tanda tangan, kontak NOC, warna kop & alamat kantor sama dengan RFO. */
    public const PERUSAHAAN    = RFOModel::PERUSAHAAN;
    public const NAMA_TTD      = RFOModel::NAMA_TTD;
    public const EMAIL_NOC     = RFOModel::EMAIL_NOC;
    public const NOMOR_WA      = RFOModel::NOMOR_WA;
    public const WARNA_KOP     = RFOModel::WARNA_KOP;
    public const ALAMAT_KANTOR = RFOModel::ALAMAT_KANTOR;

    /** Kalimat pembuka surat Maintenance (halaman View / Print). */
    public const SURAT_PEMBUKA = "Terima kasih atas kepercayaanya menggunakan jasa layanan kami,\n"
        . "Berikut kami informasikan detail maintenance yang akan dilakukan guna menjaga kualitas dan kestabilan network kami.";

    /** Kalimat penutup surat Maintenance; %1$s = nomor WA Support, %2$s = e-mail NOC. */
    public const SURAT_PENUTUP = "Atas ketidaknyamanan yang ditimbulkan atas maintenance yang kami lakukan kami sampaikan permohonan maaf.\n"
        . "Sekian informasi yang dapat kami sampaikan. Untuk informasi lebih lanjut, silahkan hubungi\n"
        . "Hotline Support kami di 021-5011-2225 / %1\$s (WA Support) yang beroperasi 24/7\n"
        . "atau e-mail ke %2\$s\n"
        . "\n"
        . "Demikian informasi yang kami sampaikan, Atas perhatian dan kerjasamanya kami ucapkan terima\n"
        . "kasih.";

    /**
     * Maintenance ID berikutnya untuk tanggal hari ini: MT-dd/mm/yyyy-NNN (contoh MT-13/10/2026-001),
     * sama dengan format RFO ID. Nomor urut lanjut terus antar hari dan reset ke 001 setiap ganti bulan.
     */
    public function generateMaintenanceId(): string
    {
        $prefix = 'MT-' . date('d/m/Y') . '-';

        // Cari nomor urut terbesar di bulan ini, tanggal berapa pun:
        // format sekarang MT-__/mm/yyyy-NNN serta format lama MT-__mmyyyy-NNN
        $last = $this->builder()
            ->select("MAX(CAST(SUBSTRING_INDEX(maintenance_id, '-', -1) AS UNSIGNED)) AS seq", false)
            ->groupStart()
                ->where("maintenance_id LIKE 'MT-__/" . date('m/Y') . "-%'", null, false)
                ->orWhere("maintenance_id LIKE 'MT-__" . date('mY') . "-%'", null, false)
            ->groupEnd()
            ->get()
            ->getRowArray();

        $next = ((int) ($last['seq'] ?? 0)) + 1;

        return $prefix . str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }
}
