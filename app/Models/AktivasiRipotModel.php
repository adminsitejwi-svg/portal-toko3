<?php

namespace App\Models;

use App\Models\BaseModel;

class AktivasiRipotModel extends BaseModel
{
    protected $table            = 'aktivasi_ripot';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'id_pelanggan',
        'layanan',
        'status',
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';
}
