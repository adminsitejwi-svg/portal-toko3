<?php

namespace App\Models;

use App\Models\BaseModel;

class CatatanModel extends BaseModel
{
    protected $table            = 'd_catatan';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'report_id',
        'grup',
        'kategori',
        'priority',
        'status',
        'judul',
        'catatan',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';

    // Group sama dengan data gangguan ditambah UMUM
    public const GRUP     = ['RETAIL', 'CORPORATE', 'RNET GROUP', 'BACKBONE', 'UMUM'];
    public const KATEGORI = ['Info', 'Note', 'Recurring'];
    public const STATUS   = ['Open', 'Done'];

    /** Kolom catatan (JSON array) => daftar poin catatan. */
    public static function poin(?string $json): array
    {
        $list = json_decode((string) $json, true);

        return is_array($list) ? $list : array_filter([(string) $json]);
    }
}
