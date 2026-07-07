<?php

namespace App\Models;

use App\Models\BaseModel;

class TypePerangkatModel extends BaseModel
{
    protected $table      = 'md_type_perangkat';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'jenis_perangkat_id',
        'merek_perangkat_id',
        'type_perangkat',
        'status',
        'keterangan',
        'created_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
