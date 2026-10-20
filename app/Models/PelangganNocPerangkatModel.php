<?php

namespace App\Models;

use App\Models\BaseModel;

// Detail perangkat pelanggan di lokasi (menu All NOC > Data Pelanggan), satu pelanggan bisa lebih dari 1 perangkat
class PelangganNocPerangkatModel extends BaseModel
{
    protected $table            = 'd_pelanggan_noc_perangkat';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'pelanggan_id',
        'merk',
        'jenis',
        'type',
        'sn',
        'jumlah',
        'lokasi_penempatan'
    ];
}
