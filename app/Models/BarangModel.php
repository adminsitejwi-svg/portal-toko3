<?php

namespace App\Models;

use App\Models\BaseModel;

class BarangModel extends BaseModel
{
    protected $table            = 'md_barang'; // sesuaikan dengan nama tabel sebenarnya
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'nama_barang',
        'jumlah',
        'sn_kode_barang',
        'kondisi_barang',
        'keterangan',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';
}