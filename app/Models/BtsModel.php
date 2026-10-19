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
        'kapasitas',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';

    // Data BTS + vendornya ($id diisi = hanya BTS itu)
    // vendor_detail: per vendor berisi data lengkap dari Data Vendor (d_vendor) + IP Address & VLAN-nya;
    // vendors: nama vendor; ip_address & vlan_id: teks gabungan "a, b" (vlan_id NULL = Tanpa VLAN),
    // dipakai tabel BTS & halaman Data Pelanggan
    public function withVendors(?int $id = null): array
    {
        $rows = ($id === null ? $this : $this->where('id', $id))->orderBy('nama_bts', 'ASC')->findAll();

        $vendorModel = (new BtsVendorModel())
            ->select('d_bts_vendor.bts_id, d_bts_vendor.ip_address, d_bts_vendor.vlan_id,
                v.id, v.nama_vendor, v.alamat, v.nama_pic, v.telp_pic, v.email_pic')
            ->join('d_vendor v', 'v.id = d_bts_vendor.vendor_id');
        if ($id !== null) {
            $vendorModel->where('d_bts_vendor.bts_id', $id);
        }

        $vendors = [];
        foreach ($vendorModel->orderBy('d_bts_vendor.id', 'ASC')->findAll() as $r) {
            $vendors[$r['bts_id']][] = $r;
        }

        foreach ($rows as &$row) {
            $detail = $vendors[$row['id']] ?? [];
            $vlans  = array_unique(array_filter(array_column($detail, 'vlan_id'), fn ($v) => $v !== null));

            $row['vendor_detail'] = $detail;
            $row['vendors']       = array_values(array_unique(array_column($detail, 'nama_vendor')));
            $row['ip_address']    = implode(', ', array_unique(array_column($detail, 'ip_address')));
            $row['vlan_id']       = $vlans ? implode(', ', $vlans) : null;
        }

        return $rows;
    }
}
