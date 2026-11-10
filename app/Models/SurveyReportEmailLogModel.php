<?php

namespace App\Models;

use App\Models\BaseModel;

// Riwayat kirim email Survey Report (sama dengan RFOEmailLogModel)
class SurveyReportEmailLogModel extends BaseModel
{
    protected $table      = 'd_survey_report_email_log';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'survey_report_id',
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
