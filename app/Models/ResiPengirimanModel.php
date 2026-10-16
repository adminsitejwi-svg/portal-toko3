<?php

namespace App\Models;

use App\Models\BaseModel;

// Data Resi Pengiriman SAT Group (menu Daily Report SAT Group > tab Resi Pengiriman)
class ResiPengirimanModel extends BaseModel
{
    protected $table            = 'd_resi_pengiriman';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'noc_on_duty',
        'shift',
        'kategori_toko',
        'kode_toko',
        'nama_toko',
        'alamat',
        'deskripsi_pekerjaan',
        'resi_pengiriman',
        'kendala',
        'tanggal_report',
        'status',
        'noted',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';
}
