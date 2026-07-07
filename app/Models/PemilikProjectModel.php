<?php

namespace App\Models;

use App\Models\BaseModel;

class PemilikProjectModel extends BaseModel
{
    protected $table            = 'md_pemilik_projek';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'kode_pemilik_projek',
        'nama_pemilik',
        'alamat_lengkap',
        'pic_projek',
        'nomor_hp_pic',
        'status',
        'keterangan',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';
}
