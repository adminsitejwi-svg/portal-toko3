<?php

namespace App\Models;

use CodeIgniter\Model;

class JadwalModel extends Model
{
    protected $table         = 'jadwal_noc';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;   // isi created_at / updated_at otomatis

    protected $allowedFields = [
        'tanggal',
        'shift',
        'nama',
        'warna',
        'keterangan',
    ];

    // Ambil jadwal dalam rentang tanggal (dipakai FullCalendar)
    public function getByRange(string $start, string $end): array
    {
        return $this->where('tanggal >=', $start)
            ->where('tanggal <=', $end)
            ->orderBy('tanggal', 'ASC')
            ->orderBy('shift', 'ASC')
            ->findAll();
    }
}
