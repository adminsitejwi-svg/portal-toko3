<?php

namespace App\Models;

use App\Models\BaseModel;

class RipotRetailModel extends BaseModel
{
    protected $table            = 'repot_noc';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'shift',
        'client',
        'start_time',
        'finish_time',
        'duration_hour',
        'duration_minute',
        'jenis_layanan',
        'problem',
        'action',
        'note',
        'solver',
        'backup_start_time',
        'backup_finish_time',
        'backup_duration_hour',
        'backup_duration_minute',
        'daily',
        'regards',
        'status'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';
}
