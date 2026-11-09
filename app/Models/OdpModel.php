<?php

namespace App\Models;

use App\Models\BaseModel;

// Data ODP (menu Data Teknis NOC > Data ODC & ODP). Kode & nama dibuat otomatis per wilayah, punya ODC induk.
class OdpModel extends BaseModel
{
    protected $table      = 'd_odp';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'wilayah_id',
        'odc_id',
        'nomor',
        'kode_odp',
        'nama_odp',
        'kapasitas_port',
        'port_terpakai',
        'latitude',
        'longitude',
        'lokasi_area',
        'status',
        'alamat',
        'keterangan',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
