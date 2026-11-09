<?php

namespace App\Models;

use App\Models\BaseModel;

// Data Switch (menu Data Teknis NOC > Data Switch)
class SwitchModel extends BaseModel
{
    protected $table      = 'd_switch';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nama_switch',
        'ip_switch',
        'type',
        'seri_switch',
        'sn',
        'versi_switch',
        'lokasi_pemasangan',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
