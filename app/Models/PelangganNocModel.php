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
        'detail_perangkat',
        'devices_backbone_id',
        'file_topologi',
        'file_topologi_asli',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';

    // Data pelanggan + data BTS (IP, kapasitas, vlan, vendors) + data Devices Backbone (dengan lokasi penempatan)
    public function withRelasi(): array
    {
        $rows = $this->select('d_pelanggan_noc.*, b.nama_bts, b.ip_address, b.kapasitas, b.vlan_id,
                d.nama_perangkat, d.type, d.sn, d.jumlah, dc.nama_dc')
            ->join('d_bts b', 'b.id = d_pelanggan_noc.bts_id', 'left')
            ->join('d_devices_backbone d', 'd.id = d_pelanggan_noc.devices_backbone_id', 'left')
            ->join('md_data_center dc', 'dc.id = d.data_center_id', 'left')
            ->orderBy('d_pelanggan_noc.nama_pelanggan', 'ASC')
            ->findAll();

        $vendors = [];
        foreach ((new BtsVendorModel())->orderBy('id', 'ASC')->findAll() as $v) {
            $vendors[$v['bts_id']][] = $v['vendor'];
        }

        foreach ($rows as &$row) {
            $row['vendors'] = $vendors[$row['bts_id']] ?? [];
        }

        return $rows;
    }
}
