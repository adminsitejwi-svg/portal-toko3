<?php

namespace App\Models;

use App\Models\BaseModel;

// Data Center pada halaman Data Rak DC (beda dengan DataCenterModel / menu Data Center)
class DataCenterDcModel extends BaseModel
{
    protected $table            = 'md_data_center_dc';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'kode_dc',
        'nama_dc',
        'alamat',
        'gedung',
        'lantai',
        'pic',
        'kontak',
        'status',
        'keterangan',
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    protected $updatedField  = 'updated_at';

    // Data center + jumlah rak & perangkat di dalamnya, urut dari yang pertama diinput
    public function withJumlah(): array
    {
        return $this->select('md_data_center_dc.*, COUNT(DISTINCT r.id) AS jumlah_rak, COUNT(p.id) AS jumlah_perangkat')
            ->join('md_data_rak r', 'r.data_center_id = md_data_center_dc.id', 'left')
            ->join('md_data_perangkat p', 'p.data_rak_id = r.id', 'left')
            ->groupBy('md_data_center_dc.id')
            ->orderBy('md_data_center_dc.id', 'ASC')
            ->findAll();
    }
}
