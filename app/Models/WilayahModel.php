<?php

namespace App\Models;

use App\Models\BaseModel;

// Wilayah / area jaringan untuk Data ODC & ODP (menu Data Teknis NOC > Data ODC & ODP)
class WilayahModel extends BaseModel
{
    protected $table      = 'md_wilayah';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'kode_wilayah',
        'nama_wilayah',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
