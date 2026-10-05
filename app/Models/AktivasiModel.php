<?php

namespace App\Models;

use App\Models\BaseModel;

class AktivasiModel extends BaseModel
{
    protected $table            = 'd_aktivasi';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'report_id',
        'id_pelanggan',
        'nama_pelanggan',
        'kapasitas_mbps',
        'sn',
        'tanggal_aktivasi',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';
}
