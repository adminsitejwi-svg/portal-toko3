<?php

namespace App\Models;

use App\Models\BaseModel;

class PelangganModel extends BaseModel
{
    protected $table            = 'md_pelanggan';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'kategori_pelanggan',
        'status',
        'keterangan',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';
}
