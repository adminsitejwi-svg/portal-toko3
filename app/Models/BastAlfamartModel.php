<?php

namespace App\Models;

use App\Models\BaseModel;

// BAST Alfamart (menu Activation > BAST > Alfamart): dokumen BAST toko Alfamart
class BastAlfamartModel extends BaseModel
{
    protected $table      = 'd_bast_alfamart';
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
