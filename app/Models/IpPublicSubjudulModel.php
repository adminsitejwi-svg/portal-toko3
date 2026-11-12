<?php

namespace App\Models;

use App\Models\BaseModel;

// Data IP Public (menu Data Teknis NOC > Data IP Public): tabel md_ip_public_subjudul
class IpPublicSubjudulModel extends BaseModel
{
    protected $table      = 'md_ip_public_subjudul';
    protected $primaryKey = 'id';

    protected $allowedFields = ['judul_id', 'sub_judul'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
