<?php

namespace App\Models;

use App\Models\BaseModel;

class QuotaSIMCARDModel extends BaseModel
{
    protected $table            = 'md_quota_simcard';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'kode_quota_simcard',
        'vendor_cellular_id',
        'nama_paket_data',
        'quota_internet',
        'harga_quota',
        'status',
        'keterangan',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';
}
