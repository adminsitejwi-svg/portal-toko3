<?php

namespace App\Models;

use App\Models\BaseModel;

// Data BTS (menu All NOC > BTS)
class BtsModel extends BaseModel
{
    protected $table            = 'd_bts';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'nama_bts',
        'ip_address',
        'kapasitas',
        'vlan_id',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';

    // Data BTS + daftar vendornya (key 'vendors' berisi array nama vendor)
    public function withVendors(): array
    {
        $rows = $this->orderBy('nama_bts', 'ASC')->findAll();

        $vendors = [];
        foreach ((new BtsVendorModel())->orderBy('id', 'ASC')->findAll() as $v) {
            $vendors[$v['bts_id']][] = $v['vendor'];
        }

        foreach ($rows as &$row) {
            $row['vendors'] = $vendors[$row['id']] ?? [];
        }

        return $rows;
    }
}
