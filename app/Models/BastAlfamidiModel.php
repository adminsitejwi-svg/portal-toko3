<?php

namespace App\Models;

use App\Models\BaseModel;

// BAST Alfamidi (menu Activation > BAST > Alfamidi): dokumen BAST toko Alfamidi
class BastAlfamidiModel extends BaseModel
{
    protected $table      = 'd_bast_alfamidi';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'no_bast',
        'tanggal_bast',
        'perusahaan',
        'layanan',
        'nama_file',
        'file_simpan',
        'format',
        'ukuran',
        'hash_sha1',
        'diupload_oleh',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    // tidak ada updated_at: dokumen hanya diupload / dihapus, tidak diedit (sama seperti KMZ)
    protected $updatedField  = '';
}
