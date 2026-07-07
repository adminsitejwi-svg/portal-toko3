<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Model untuk tabel history/audit.
 *
 * PENTING: model ini extends Model biasa (BUKAN BaseModel),
 * supaya proses penyimpanan log tidak ikut tercatat lagi (anti-loop).
 */
class ActivityLogModel extends Model
{
    protected $table         = 'activity_logs';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'user_id',
        'username',
        'action',
        'table_name',
        'record_id',
        'description',
        'old_data',
        'new_data',
        'created_at',
    ];
}
