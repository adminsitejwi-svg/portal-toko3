<?php

namespace App\Models;

use App\Models\BaseModel;

class VPNModel extends BaseModel
{
    protected $table            = 'md_vpn';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'kode_tujuan_koneksi',
        'tujuan_koneksi',
        'ip_address_tujuan',
        'status',
        'keterangan',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';
}
