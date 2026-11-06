<?php

namespace App\Models;

use App\Models\BaseModel;

// Data Rak pada halaman Data Rak DC
class DataRakModel extends BaseModel
{
    protected $table            = 'md_data_rak';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'data_center_id',
        'kode_rak',
        'nama_rak',
        'ukuran_u',
        'tinggi_cm',
        'lebar_cm',
        'kedalaman_cm',
        'lokasi_rak',
        'kapasitas_listrik',
        'status',
        'keterangan',
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    protected $updatedField  = 'updated_at';

    // Rak + data center + jumlah perangkat & U terpakai (perangkat tanpa posisi tidak memakai U)
    public function withRelasi(?int $dcId = null): array
    {
        $this->select('md_data_rak.*, dc.kode_dc, dc.nama_dc, COUNT(p.id) AS jumlah_perangkat, COALESCE(SUM(CASE WHEN p.posisi_u IS NOT NULL THEN p.ukuran_u END), 0) AS terpakai_u')
            ->join('md_data_center_dc dc', 'dc.id = md_data_rak.data_center_id')
            ->join('md_data_perangkat p', 'p.data_rak_id = md_data_rak.id', 'left');

        if ($dcId) {
            $this->where('md_data_rak.data_center_id', $dcId);
        }

        return $this->groupBy('md_data_rak.id')
            ->orderBy('dc.kode_dc', 'ASC')
            ->orderBy('md_data_rak.kode_rak', 'ASC')
            ->findAll();
    }
}
