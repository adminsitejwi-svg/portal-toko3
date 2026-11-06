<?php

namespace App\Models;

use App\Models\BaseModel;

// Data Perangkat pada halaman Data Rak DC (beda dengan PerangkatModel / Merek Perangkat)
class DataPerangkatModel extends BaseModel
{
    protected $table            = 'md_data_perangkat';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'data_rak_id',
        'kode_perangkat',
        'nama_perangkat',
        'jenis_perangkat',
        'merk',
        'model',
        'serial_number',
        'ukuran_u',
        'posisi_u',
        'ip_address',
        'mac_address',
        'tanggal_pasang',
        'status',
        'keterangan',
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    protected $updatedField  = 'updated_at';

    // Perangkat + rak + data center-nya
    public function withRelasi(?int $dcId = null, ?int $rakId = null): array
    {
        $this->select('md_data_perangkat.*, r.kode_rak, r.nama_rak, r.data_center_id, dc.kode_dc, dc.nama_dc')
            ->join('md_data_rak r', 'r.id = md_data_perangkat.data_rak_id')
            ->join('md_data_center_dc dc', 'dc.id = r.data_center_id');

        if ($dcId) {
            $this->where('r.data_center_id', $dcId);
        }
        if ($rakId) {
            $this->where('md_data_perangkat.data_rak_id', $rakId);
        }

        return $this->orderBy('dc.kode_dc', 'ASC')
            ->orderBy('r.kode_rak', 'ASC')
            ->orderBy('md_data_perangkat.posisi_u', 'DESC')
            ->findAll();
    }

    // 42.00 -> '42', 0.50 -> '0.5'
    public static function angka($n): string
    {
        return rtrim(rtrim(number_format((float) $n, 2, '.', ''), '0'), '.');
    }

    // Posisi 5 ukuran 2 -> 'U5-U6'; posisi 5 ukuran 1 -> 'U5'; belum ditempatkan -> ''
    public static function labelU($posisi, $ukuran): string
    {
        if ($posisi === null || $posisi === '') {
            return '';
        }

        $atas = (float) $posisi + (float) $ukuran - 1;

        return $atas > (float) $posisi
            ? 'U' . self::angka($posisi) . '-U' . self::angka($atas)
            : 'U' . self::angka($posisi);
    }
}
