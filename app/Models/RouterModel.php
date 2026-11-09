<?php

namespace App\Models;

use App\Models\BaseModel;

// Data Router (menu Data Teknis NOC > Data Router)
class RouterModel extends BaseModel
{
    protected $table      = 'd_router';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nama_router',
        'ip_mainlink',
        'ip_backup',
        'ip_loopback',
        'type',
        'seri_router',
        'sn',
        'versi_router',
        'lokasi_pemasangan',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
