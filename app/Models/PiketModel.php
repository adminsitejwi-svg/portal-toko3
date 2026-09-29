<?php

namespace App\Models;

use CodeIgniter\Model;

class PiketModel extends Model
{
    protected $table         = 'd_piket';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = true;   // isi created_at / updated_at otomatis

    protected $allowedFields = [
        'tanggal',
        'nama',
        'piket',
        'keterangan',
    ];

    // Ambil jadwal piket dalam rentang tanggal (dipakai FullCalendar)
    public function getByRange(string $start, string $end): array
    {
        return $this->where('tanggal >=', $start)
            ->where('tanggal <=', $end)
            ->orderBy('tanggal', 'ASC')
            ->orderBy('piket', 'ASC')
            ->findAll();
    }
}
