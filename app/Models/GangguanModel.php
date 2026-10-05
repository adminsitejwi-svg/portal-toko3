<?php

namespace App\Models;

use App\Models\BaseModel;

class GangguanModel extends BaseModel
{
    protected $table            = 'd_gangguan';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'report_id',
        'grup',
        'sub_grup',
        'customer_site',
        'cid_ticket',
        'status',
        'priority',
        'gangguan',
        'tindakan',
        'next_action',
        'pic',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';

    public const GRUP     = ['RETAIL', 'CORPORATE', 'RNET GROUP', 'BACKBONE'];
    public const STATUS   = ['Resolved', 'On Progress', 'Monitoring', 'Down', 'Pending'];
    public const PRIORITY = ['High', 'Medium', 'Low'];
}
