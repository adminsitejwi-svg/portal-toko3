<?php

namespace App\Models;

use App\Models\BaseModel;

// Data IP Public (menu Data Teknis NOC > Data IP Public): tabel d_ip_public_subnet
class IpPublicSubnetModel extends BaseModel
{
    protected $table      = 'd_ip_public_subnet';
    protected $primaryKey = 'id';

    protected $allowedFields = ['judul_id', 'subjudul_id', 'subnet', 'deskripsi'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
