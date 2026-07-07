<?php

namespace App\Models;

use App\Models\BaseModel;

class VendorModel extends BaseModel
{
    protected $table            = 'md_vendor';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'kode_layanan_vendor',
        'nama_vendor',
        'alamat_vendor',
        'status',
        'keterangan',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';
}
