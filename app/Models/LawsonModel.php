<?php

namespace App\Models;

use App\Models\BaseModel;

class LawsonModel extends BaseModel
{
    protected $table      = 'd_lawson';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        // relasi (Diambil Dari Master Data) -> simpan id
        'pemilik_projek_id',
        'nama_dc_id',
        'media_koneksi_id',
        'vendor_id',
        'layanan_vendor_id',
        'jenis_perangkat_id',
        'merk_perangkat_id',
        'backup_media_koneksi',

        // isi manual
        'nama_lawson',
        'kode_toko',
        'alamat_lawson',
        'titik_koor_toko',
        'map_toko',
        'pic_toko',
        'nomor_hp_pic',
        'tanggal_installasi',
        'tanggal_aktivasi',
        'kapasitas_bandwidth',
        'ip_address',
        'type_perangkat',
        'serial_number_perangkat',
        'status',
        'keterangan',
        'upload_lampiran',
        'created_at',
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';
}
