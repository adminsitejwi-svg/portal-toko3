<?php

namespace App\Models;

use App\Models\BaseModel;

// Data IP Public (menu Data Teknis NOC > Data IP Public): tabel md_ip_public_judul
class IpPublicJudulModel extends BaseModel
{
    protected $table      = 'md_ip_public_judul';
    protected $primaryKey = 'id';

    protected $allowedFields = ['perusahaan', 'judul'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
