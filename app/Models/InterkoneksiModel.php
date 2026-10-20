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
        'file_dokumen',
        'file_dokumen_asli',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';

    // Data interkoneksi + nama data center & lokasi rak (diambil dari md_data_center)
    public function withDataCenter(): array
    {
        return $this->select('d_interkoneksi.*, dc.nama_dc, dc.lokasi_rak')
            ->join('md_data_center dc', 'dc.id = d_interkoneksi.data_center_id', 'left')
            ->orderBy('d_interkoneksi.kode_interkoneksi', 'ASC')
            ->findAll();
    }
}
