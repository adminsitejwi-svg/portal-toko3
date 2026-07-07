<?php

namespace App\Models;

use App\Models\BaseModel;

class VendorCelulllarModel extends BaseModel
{
    protected $table            = 'md_vendor_cellular';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
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
