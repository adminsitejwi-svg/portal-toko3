<?php

namespace App\Models;

use App\Models\BaseModel;

// Data Pelanggan (menu All NOC > Data Pelanggan), beda dengan PelangganModel (md_pelanggan)
class PelangganNocModel extends BaseModel
{
    protected $table            = 'd_pelanggan_noc';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'nama_pelanggan',
        'bts_id',
        'file_topologi',
        'file_topologi_asli',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';

    // Data pelanggan + nama BTS + vendor & detail perangkatnya ($id diisi = hanya pelanggan itu)
    // vendor_detail: per vendor berisi data lengkap dari Data Vendor (d_vendor) + IP Address, VLAN & Kapasitas pelanggan;
    // vendors, ip_address, vlan, kapasitas: teks per vendor digabung ", " (urutan sama dengan vendor);
    // perangkat: daftar detail perangkat, perangkat_teks: satu baris per perangkat
    public function withRelasi(?int $id = null): array
    {
        $builder = $this->select('d_pelanggan_noc.*, b.nama_bts')
            ->join('d_bts b', 'b.id = d_pelanggan_noc.bts_id', 'left');
        if ($id !== null) {
            $builder->where('d_pelanggan_noc.id', $id);
        }
        $rows = $builder->orderBy('d_pelanggan_noc.nama_pelanggan', 'ASC')->findAll();

        $vendorModel = (new PelangganNocVendorModel())
            ->select('d_pelanggan_noc_vendor.pelanggan_id, d_pelanggan_noc_vendor.ip_address, d_pelanggan_noc_vendor.vlan_id,
                d_pelanggan_noc_vendor.kapasitas, v.id, v.nama_vendor, v.alamat, v.nama_pic, v.telp_pic, v.email_pic')
            ->join('d_vendor v', 'v.id = d_pelanggan_noc_vendor.vendor_id');
        $perangkatModel = new PelangganNocPerangkatModel();
        if ($id !== null) {
            $vendorModel->where('d_pelanggan_noc_vendor.pelanggan_id', $id);
            $perangkatModel->where('pelanggan_id', $id);
        }

        $vendors = $perangkat = [];
        foreach ($vendorModel->orderBy('d_pelanggan_noc_vendor.id', 'ASC')->findAll() as $r) {
            $vendors[$r['pelanggan_id']][] = $r;
        }
        foreach ($perangkatModel->orderBy('id', 'ASC')->findAll() as $r) {
            $perangkat[$r['pelanggan_id']][] = $r;
        }

        foreach ($rows as &$row) {
            $detail = $vendors[$row['id']] ?? [];

            $row['vendor_detail']  = $detail;
            $row['vendors']        = implode(', ', array_column($detail, 'nama_vendor'));
            $row['ip_address']     = implode(', ', array_column($detail, 'ip_address'));
            $row['vlan']           = implode(', ', array_map(fn ($v) => $v['vlan_id'] ?? 'Tanpa VLAN', $detail));
            $row['kapasitas']      = implode(', ', array_column($detail, 'kapasitas'));
            $row['perangkat']      = $perangkat[$row['id']] ?? [];
            $row['perangkat_teks'] = implode("\n", array_map(
                fn ($p) => implode(' ', array_filter([$p['merk'], $p['jenis'], $p['type']], fn ($s) => (string) $s !== ''))
                    . ($p['sn'] ? ' (SN ' . $p['sn'] . ')' : '') . ' x' . $p['jumlah'],
                $row['perangkat']
            ));
        }

        return $rows;
    }
}
