<?php

namespace App\Models;

use App\Models\BaseModel;

// Data Interkoneksi (menu All NOC > Data Interkoneksi)
class InterkoneksiModel extends BaseModel
{
    protected $table            = 'd_interkoneksi';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'kode_interkoneksi',
        'customer',
        'data_center_id',
        'sumber',
        'tujuan',
        'label_kabel',
        'status',
        'note',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';

    // Data interkoneksi + nama & kode data center (diambil dari md_data_center)
    public function withDataCenter(): array
    {
        return $this->select('d_interkoneksi.*, dc.nama_dc, dc.kode_dc')
            ->join('md_data_center dc', 'dc.id = d_interkoneksi.data_center_id', 'left')
            ->orderBy('d_interkoneksi.kode_interkoneksi', 'ASC')
            ->findAll();
    }
}
