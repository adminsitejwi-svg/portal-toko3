<?php

namespace App\Models;

use App\Models\BaseModel;

// Data NetMon (menu All NOC > NetMon): judul, link website, keterangan
class NetMonModel extends BaseModel
{
    protected $table      = 'md_netmon';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'judul',
        'link',
        'keterangan',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
