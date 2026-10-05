<?php

namespace App\Models;

use App\Models\BaseModel;

class PengirimanModel extends BaseModel
{
    protected $table            = 'd_pengiriman';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'report_id',
        'grup',
        'customer',
        'device',
        'tracking_resi',
        'status',
        'eta',
        'pic',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';

    public const STATUS = ['Requested', 'Shipped', 'In Transit', 'Received', 'Installed', 'Closed'];
}
