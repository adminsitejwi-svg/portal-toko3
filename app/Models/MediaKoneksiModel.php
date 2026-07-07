<?php

namespace App\Models;

use App\Models\BaseModel;

class MediaKoneksiModel extends BaseModel
{
    protected $table            = 'md_media_koneksi';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'kode_media_koneksi',
        'status_link',
        'media_koneksi',
        'status',
        'keterangan',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';
}
