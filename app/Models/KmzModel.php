<?php

namespace App\Models;

use App\Models\BaseModel;

// Data KMZ (menu Data Teknis NOC > KMZ): daftar file peta .kmz / .kml yang diupload
class KmzModel extends BaseModel
{
    protected $table      = 'd_kmz';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nama_file',
        'file_simpan',
        'format',
        'ukuran',
        'hash_sha1',
        'diupload_oleh',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    // tidak ada updated_at: file hanya diupload / dihapus, tidak diedit
    protected $updatedField  = '';
}
