<?php

namespace App\Models;

use App\Models\BaseModel;

// PKS & Contract (menu Activation > PKS & Contract): dokumen Perjanjian Kerja Sama & kontrak
class PksContractModel extends BaseModel
{
    protected $table      = 'd_pks_contract';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'no_pks',
        'tanggal_pks',
        'perusahaan',
        'kepada',
        'nama_file',
        'file_simpan',
        'format',
        'ukuran',
        'hash_sha1',
        'diupload_oleh',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    // tidak ada updated_at: dokumen hanya diupload / dihapus, tidak diedit (sama seperti SPK)
    protected $updatedField  = '';
}
