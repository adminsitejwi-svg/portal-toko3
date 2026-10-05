<?php

namespace App\Models;

use App\Models\BaseModel;

class FollowUpModel extends BaseModel
{
    protected $table            = 'd_follow_up';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'report_id',
        'grup',
        'customer_site',
        'priority',
        'due_date',
        'pic',
        'issue',
        'action',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';
}
