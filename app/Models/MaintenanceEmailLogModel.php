<?php

namespace App\Models;

use App\Models\BaseModel;

class MaintenanceEmailLogModel extends BaseModel
{
    protected $table            = 'md_maintenance_email_log';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'mt_id',
        'dari',
        'kepada',
        'cc',
        'subjek',
        'lampiran',
        'body',
        'status',
        'pesan_error',
        'dikirim_oleh',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';
}
