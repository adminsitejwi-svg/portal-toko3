<?php

namespace App\Models;

use App\Models\BaseModel;

class MaintenanceModel extends BaseModel
{
    protected $table            = 'd_maintenance';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'report_id',
        'grup',
        'site',
        'equipment',
        'schedule',
        'status',
        'pic',
        'issue',
        'action',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';

    public const STATUS = ['Planned', 'On Progress', 'Done', 'Pending'];
}
