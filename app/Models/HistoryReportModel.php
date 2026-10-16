<?php

namespace App\Models;

use App\Models\BaseModel;

class HistoryReportModel extends BaseModel
{
    protected $table            = 'd_report';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'tanggal',
        'shift',
        'kategori',
        'pic_shift',
        'jam_mulai',
        'jam_selesai',
        'ringkasan',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';

    /** Pilihan kategori laporan. */
    public const KATEGORI = ['NOC Corp Dan Retail', 'NOC Alfa Grup'];

    /** Kategori tanpa data Pengiriman (card, tab Shift Handover & teks copy tidak menampilkannya). */
    public const TANPA_PENGIRIMAN = ['NOC Corp Dan Retail'];

    public static function adaPengiriman(?string $kategori): bool
    {
        return ! in_array($kategori, self::TANPA_PENGIRIMAN, true);
    }

    /** Shift laporan => nomor shift di tabel jadwal_noc (Jadwal NOC). */
    public const SHIFT = [
        'Pagi'  => 1,
        'Siang' => 2,
        'Malam' => 3,
    ];
}
