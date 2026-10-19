<?php

namespace App\Models;

use App\Models\BaseModel;

// Vendor per BTS (menu All NOC > BTS), satu BTS bisa lebih dari 1 vendor
// vendor_id: id dari d_vendor (menu All NOC > Data Vendor); tiap vendor punya IP Address & VLAN sendiri
// vlan_id NULL berarti "Tanpa VLAN"
class BtsVendorModel extends BaseModel
{
    protected $table            = 'd_bts_vendor';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'bts_id',
        'vendor_id',
        'ip_address',
        'vlan_id'
    ];
}
