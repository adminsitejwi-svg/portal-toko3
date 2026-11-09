<?php

namespace App\Models;

use App\Models\BaseModel;

// Pelanggan ODP (menu Data Teknis NOC > Pelanggan ODP): pelanggan per ODP
class PelangganOdpModel extends BaseModel
{
    protected $table      = 'd_pelanggan_odp';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'odp_id',
        'port',
        'kode_pelanggan',
        'nama_pelanggan',
        'telepon',
        'latitude',
        'longitude',
        'alamat',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
