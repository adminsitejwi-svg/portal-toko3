<?php

namespace App\Models;

use App\Models\BaseModel;

class FollowUpModel extends BaseModel
{
    use StatusBerantaiTrait;

    // Kolom yang ikut disamakan ke shift sebelumnya (hasil duplikat) saat diubah
    protected array $kolomBerantai = ['priority'];

    protected $table            = 'd_follow_up';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'report_id',
        'asal_id', // id baris asal (shift sebelumnya) bila hasil duplikat
        'grup',
        'customer_site',
        'priority',
        'due_date',
        'pic',
        'issue',
        'action',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';
}
