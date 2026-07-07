<?php

namespace App\Models;

use App\Models\BaseModel;

class NomerInetModel extends BaseModel
{
    protected $table            = 'md_nomer_inet';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'layanan_vendor_id',
        'kecepatan_bandwidth',
        'harga_layanan',
        'nomor_inet',
        'password_inet',
        'status',
        'keterangan',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';
}
