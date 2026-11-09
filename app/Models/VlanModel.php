<?php

namespace App\Models;

use App\Models\BaseModel;

// Data VLAN (menu All NOC > Data VLAN)
class VlanModel extends BaseModel
{
    protected $table      = 'md_vlan';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'vlan_id',
        'nama_vlan',
        'jenis_layanan',
        'lokasi_pop',
        'status',
        'keterangan',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
