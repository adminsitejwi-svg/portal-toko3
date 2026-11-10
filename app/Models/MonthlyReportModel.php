<?php

namespace App\Models;

use App\Models\BaseModel;

// Monthly Report (menu Report NOC > Monthly Report)
// durasi_menit & backup_durasi_menit dihitung di controller dari jam mulai - selesai
class MonthlyReportModel extends BaseModel
{
    protected $table      = 'd_monthly_report';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'client',
        'start_time',
        'finish_time',
        'durasi_menit',
        'jenis_layanan',
        'problem',
        'action',
        'note',
        'solver',
        'backup_start',
        'backup_finish',
        'backup_durasi_menit',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
