<?php

namespace App\Models;

use App\Models\BaseModel;

class DataSIModel extends BaseModel
{
    protected $table      = 'd_simcard';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'quota_simcard_id',
        'nomor_msisdn',
        'nomor_imei',
        'kategori_pelanggan_id',
        'pelanggan_id',
        'toko_id',
        'status',
        'keterangan',
        'created_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
