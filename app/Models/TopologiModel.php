<?php

namespace App\Models;

use CodeIgniter\Model;

// Data Topologi (NOC Operation > Data Topologi): diagram draw.io + gambar PNG.
// Sengaja tidak memakai BaseModel supaya isi XML diagram (bisa besar) tidak ikut tersimpan
// di history setiap kali disimpan; history dicatat manual di controller.
class TopologiModel extends Model
{
    protected $table      = 'd_topologi';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'nama',
        'keterangan',
        'xml',
        'gambar',
        'created_by',
        'updated_by',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
