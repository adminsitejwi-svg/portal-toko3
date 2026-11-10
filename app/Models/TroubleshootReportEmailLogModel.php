<?php

namespace App\Models;

use App\Models\BaseModel;

// Riwayat kirim email Troubleshoot Report (sama dengan SurveyReportEmailLogModel)
class TroubleshootReportEmailLogModel extends BaseModel
{
    protected $table      = 'd_troubleshoot_report_email_log';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'troubleshoot_report_id',
        'dari',
        'kepada',
        'cc',
        'subjek',
        'lampiran',
        'body',
        'status',
        'pesan_error',
        'dikirim_oleh',
        'created_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField = '';
}
