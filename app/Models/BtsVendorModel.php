<?php

namespace App\Models;

use App\Models\BaseModel;

// Vendor per BTS (menu All NOC > BTS), satu BTS bisa lebih dari 1 vendor
class BtsVendorModel extends BaseModel
{
    protected $table            = 'd_bts_vendor';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'bts_id',
        'vendor'
    ];
}
