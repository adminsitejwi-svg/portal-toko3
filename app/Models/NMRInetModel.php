<?php

namespace App\Models;

use App\Models\BaseModel;

class NMRInetModel extends BaseModel
{
    protected $table      = 'd_nomor_inet';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nomer_inet_id',          // huruf "e" - sesuai kolom asli tabel
        'kategori_pelanggan_id',  // underscore huruf kecil - sesuai kolom asli
        'id_pelanggan',
        'kode_toko',
        'status',
        'keterangan',
        'created_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
