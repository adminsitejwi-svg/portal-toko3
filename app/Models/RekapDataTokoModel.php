<?php

namespace App\Models;

use CodeIgniter\Model;

class RekapDataTokoModel extends Model
{
    protected $table            = 'rekap_data_toko';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'nama_pengisi',
        'perusahaan', // <-- Tambahkan ini
        'tanggal',
        'toko',
        'branch',
        'kode_toko',
        'nama_toko',
        'foto_sn_fortigate',
        'id_pelanggan_fo',
        'sn_ont_fo',
        'tipe_modem_gsm_1',
        'foto_imei_modem_1',
        'msisdn_modem_1',
        'iccid_modem_1',
        'tipe_modem_gsm_2',
        'msisdn_modem_2',
        'iccid_modem_2',
        'sharelock_tikor_alamat',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
