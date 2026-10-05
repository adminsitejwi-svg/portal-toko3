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

    /** Perusahaan, logo, nama tanda tangan & e-mail NOC sama dengan RFO. */
    public const PERUSAHAAN = RFOModel::PERUSAHAAN;
    public const NAMA_TTD   = RFOModel::NAMA_TTD;
    public const EMAIL_NOC  = RFOModel::EMAIL_NOC;

    /** Kalimat pembuka surat Maintenance (halaman View / Print). */
    public const SURAT_PEMBUKA = "Terima kasih atas kepercayaanya menggunakan jasa layanan kami,\n"
        . "Berikut kami informasikan detail maintenance yang akan dilakukan guna menjaga kualitas dan kestabilan network kami.";

    /** Kalimat penutup surat Maintenance; %s = e-mail NOC. */
    public const SURAT_PENUTUP = "Atas ketidaknyamanan yang ditimbulkan atas maintenance yang kami lakukan kami sampaikan permohonan maaf.\n"
        . "Sekian informasi yang dapat kami sampaikan. Untuk informasi lebih lanjut, silahkan hubungi\n"
        . "Hotline Support kami di 021-5011-2225 / +62 811-1370-5508 (WA Support) yang beroperasi 24/7\n"
        . "atau e-mail ke %s\n"
        . "Demikian informasi yang kami sampaikan, Atas perhatian dan kerjasamanya kami ucapkan terima\n"
        . "kasih.";

    /**
     * Maintenance ID berikutnya untuk tanggal hari ini: MT-ddmmyyyy-NNN.
     * Nomor urut dimulai dari 001 dan reset setiap ganti tanggal.
     */
    public function generateMaintenanceId(): string
    {
        $prefix = 'MT-' . date('dmY') . '-';

        $last = $this->select('maintenance_id')
            ->like('maintenance_id', $prefix, 'after')
            ->orderBy('maintenance_id', 'DESC')
            ->first();

        $next = $last ? ((int) substr($last['maintenance_id'], strlen($prefix))) + 1 : 1;

        return $prefix . str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }
}
