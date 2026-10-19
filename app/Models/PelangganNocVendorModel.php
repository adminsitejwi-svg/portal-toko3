<?php

namespace App\Models;

use App\Models\BaseModel;

// Vendor per pelanggan (menu All NOC > Data Pelanggan), satu pelanggan bisa lebih dari 1 vendor
// vendor_id: id dari d_vendor (dipilih dari vendor milik BTS pelanggan);
// ip_address, vlan_id & kapasitas milik pelanggan di vendor itu, vlan_id NULL berarti "Tanpa VLAN"
class PelangganNocVendorModel extends BaseModel
{
    protected $table            = 'd_pelanggan_noc_vendor';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'pelanggan_id',
        'vendor_id',
        'ip_address',
        'vlan_id',
        'kapasitas'
    ];
}
