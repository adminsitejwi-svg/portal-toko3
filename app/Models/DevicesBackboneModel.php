<?php

namespace App\Models;

use App\Models\BaseModel;

// Data Devices Backbone (menu All NOC > Devices Backbone)
class DevicesBackboneModel extends BaseModel
{
    protected $table            = 'd_devices_backbone';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'nama_perangkat',
        'type',
        'sn',
        'jumlah',
        'lokasi_penempatan',
        'rak',
        'u',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';
}
