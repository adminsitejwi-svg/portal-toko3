<?php

namespace App\Models;

use App\Models\BaseModel;

// Data Daily Report SAT Group (menu Report NOC > Daily Report SAT Group)
class ReportSatModel extends BaseModel
{
    protected $table            = 'd_report_sat';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'noc_on_duty',
        'shift',
        'kategori_toko',
        'kode_toko',
        'nama_toko',
        'alamat',
        'deskripsi_pekerjaan',
        'resi_pengiriman',
        'kendala',
        'action',
        'tanggal_report',
        'status',
        'noted',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';

    /** Pilihan shift (disimpan 1/2/3, ditampilkan "Shift 1" dst). */
    public const SHIFT = [1, 2, 3];

    /** Pilihan kategori toko. */
    public const KATEGORI_TOKO = ['Alfamidi', 'Alfamart', 'Lawson'];
}
