<?php

namespace App\Models;

use App\Models\BaseModel;

// Data ODC (menu Data Teknis NOC > Data ODC & ODP). Kode & nama dibuat otomatis per wilayah.
class OdcModel extends BaseModel
{
    protected $table      = 'd_odc';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'wilayah_id',
        'nomor',
        'kode_odc',
        'nama_odc',
        'kapasitas_core',
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
