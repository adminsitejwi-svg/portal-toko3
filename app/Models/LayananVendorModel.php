<?php

namespace App\Models;

use App\Models\BaseModel;

class LayananVendorModel extends BaseModel
{
    protected $table            = 'md_layanan_vendor';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'kode_layanan_vendor',
        'vendor_id',
        'nama_layanan',
        'status',
        'keterangan',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';
}
