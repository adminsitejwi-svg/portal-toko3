<?php

namespace App\Models;

use App\Models\BaseModel;

// Data IP Public (menu Data Teknis NOC > Data IP Public): tabel d_ip_public_host (Data Hostname per subnet)
class IpPublicHostModel extends BaseModel
{
    protected $table      = 'd_ip_public_host';
    protected $primaryKey = 'id';

    protected $allowedFields = ['subnet_id', 'ip_address', 'hostname', 'deskripsi'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
