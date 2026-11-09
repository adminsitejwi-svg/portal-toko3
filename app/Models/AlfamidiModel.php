<?php

namespace App\Models;

use App\Models\BaseModel;

class AlfamidiModel extends BaseModel
{
    protected $table      = 'd_midi';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        // relasi (Diambil Dari Master Data) -> simpan id
        'pemilik_projek_id',
        'nama_dc_id',
        'nomor_inet_id',
        'simcard_id',
        'nomor_inet_id_backup',   // Vendor Link Backup (opsional): id d_nomor_inet
        'simcard_id_backup',      // Vendor Link Backup (opsional): id d_simcard
        'jenis_perangkat_id',
        'merk_perangkat_id',
        'type_perangkat_id',
        'vpn_id',
        // isi manual
        'nama_alfamidi',
        'kode_toko',
        'alamat_alfamidi',
        'titik_koor_toko',
        'map_toko',
        'pic_toko',
        'nomor_hp_pic',
        'nama_pejabat_toko',
        'telp_pejabat_toko',
        'nama_it_cabang',
        'telp_it_cabang',
        'pic_aktivasi',
        'tanggal_installasi',
        'tanggal_aktivasi',
        'media_koneksi',          // tinyint(1), default 1
        'media_koneksi_backup',   // Vendor Link Backup: NULL = tanpa backup, 0 = Non Cellular, 1 = Cellular
        'kategori_ip_address',    // enum: Static / Dynamic
        'jenis_ip_address',       // enum: Public / Private
        'ip_address_toko',
        'type_koneksi',           // enum: IPSEC / L2TP / PPTP
        'serial_number_perangkat',
        'mac_address',            // AA:BB:CC:DD:EE:FF (opsional)
        'status',                 // tinyint(1), default 0
        'keterangan',
        'upload_lampiran',
        'created_at',
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';
}
