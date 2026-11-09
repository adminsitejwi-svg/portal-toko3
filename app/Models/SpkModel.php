<?php

namespace App\Models;

use App\Models\BaseModel;

// SPK (menu Activation > SPK): dokumen Surat Perintah Kerja
class SpkModel extends BaseModel
{
    protected $table      = 'd_spk';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'no_spk',
        'tanggal_spk',
        'keperluan',
        'lokasi',
        'nama_file',
        'file_simpan',
        'format',
        'ukuran',
        'hash_sha1',
        'diupload_oleh',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    // tidak ada updated_at: dokumen hanya diupload / dihapus, tidak diedit (sama seperti BAST)
    protected $updatedField  = '';
}
