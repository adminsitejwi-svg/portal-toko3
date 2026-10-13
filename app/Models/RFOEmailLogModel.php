<?php

namespace App\Models;

use App\Models\BaseModel;

class RFOEmailLogModel extends BaseModel
{
    protected $table            = 'd_rfo_email_log';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'rfo_id',
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
