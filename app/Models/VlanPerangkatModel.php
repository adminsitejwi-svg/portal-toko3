<?php

namespace App\Models;

use App\Models\BaseModel;

// Mapping perangkat per VLAN (md_vlan_perangkat.md_vlan_id -> md_vlan.id, ikut terhapus bersama VLAN-nya)
class VlanPerangkatModel extends BaseModel
{
    protected $table      = 'md_vlan_perangkat';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'md_vlan_id',
        'nama_perangkat',
        'vendor',
        'interface_port',
        'mode_port',
        'keterangan',
        'urutan',
    ];

    protected $useTimestamps = false;
}
