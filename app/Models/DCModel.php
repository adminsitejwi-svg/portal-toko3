<?php

namespace App\Models;

use App\Models\BaseModel;

class DCModel extends BaseModel
{
    protected $table = 'md_dc';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'kode_dc',
        'nama_dc',
        'alamat_dc',
        'status',
        'keterangan'
    ];
}
