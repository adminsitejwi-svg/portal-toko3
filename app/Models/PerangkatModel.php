<?php

namespace App\Models;

use App\Models\BaseModel;

class PerangkatModel extends BaseModel
{
    protected $table            = 'md_merek_perangkat';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'merk_perangkat',
        'status',
        'keterangan',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';
}
