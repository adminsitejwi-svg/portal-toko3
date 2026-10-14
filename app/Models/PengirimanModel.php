<?php

namespace App\Models;

use App\Models\BaseModel;

class PengirimanModel extends BaseModel
{
    use StatusBerantaiTrait;

    // Kolom yang ikut disamakan ke shift sebelumnya (hasil duplikat) saat diubah
    protected array $kolomBerantai = ['status'];

    protected $table            = 'd_pengiriman';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'report_id',
        'asal_id', // id baris asal (shift sebelumnya) bila hasil duplikat
        'grup',
        'customer',
        'device',
        'tracking_resi',
        'status',
        'eta',
        'pic',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';

    public const STATUS = ['Requested', 'Shipped', 'In Transit', 'Received', 'Installed', 'Closed'];
}
