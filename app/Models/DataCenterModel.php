<?php

namespace App\Models;

use App\Models\BaseModel;

// Data Data Center (menu Data Center di sidebar)
class DataCenterModel extends BaseModel
{
    protected $table            = 'md_data_center';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'nama_dc',
        'kode_dc',
        'deskripsi',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';
}
