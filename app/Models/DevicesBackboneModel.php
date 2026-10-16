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
        'data_center_id',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';

    // Data devices + nama data center sebagai lokasi penempatan (diambil dari md_data_center)
    public function withDataCenter(): array
    {
        return $this->select('d_devices_backbone.*, dc.nama_dc')
            ->join('md_data_center dc', 'dc.id = d_devices_backbone.data_center_id', 'left')
            ->orderBy('d_devices_backbone.nama_perangkat', 'ASC')
            ->findAll();
    }
}
