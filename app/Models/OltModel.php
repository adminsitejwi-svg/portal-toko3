<?php

namespace App\Models;

use App\Models\BaseModel;

// Data OLT (menu Data Teknis NOC > Data OLT)
class OltModel extends BaseModel
{
    protected $table      = 'd_olt';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nama_olt',
        'ip_olt',
        'type',
        'seri_olt',
        'sn',
        'versi_olt',
        'lokasi_pemasangan',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
