<?php

namespace App\Models;

use App\Models\BaseModel;

// Data Vendor (menu All NOC > Data Vendor di sidebar)
class VendorNocModel extends BaseModel
{
    protected $table            = 'd_vendor';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'nama_vendor',
        'note',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';
}
