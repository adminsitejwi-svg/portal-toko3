<?php

namespace App\Models;

use App\Models\BaseModel;

class GangguanModel extends BaseModel
{
    use StatusBerantaiTrait;

    // Kolom yang ikut disamakan ke shift sebelumnya (hasil duplikat) saat diubah
    protected array $kolomBerantai = ['status', 'priority'];

    protected $table            = 'd_gangguan';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'report_id',
        'asal_id', // id baris asal (shift sebelumnya) bila hasil duplikat
        'grup',
        'sub_grup',
        'customer_site',
        'cid_ticket',
        'status',
        'priority',
        'gangguan',
        'tindakan',
        'next_action',
        'pic',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';

    public const GRUP     = ['RETAIL', 'CORPORATE', 'RNET GROUP', 'BACKBONE'];
    public const STATUS   = ['Resolved', 'On Progress', 'Monitoring', 'Down', 'Pending'];
    public const PRIORITY = ['High', 'Medium', 'Low'];
}
