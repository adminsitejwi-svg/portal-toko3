<?php

namespace App\Models;

use CodeIgniter\Model;

class DataCctvModel extends Model
{
    protected $table            = 'd_nvr';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['nama_nvr', 'ip_nvr', 'lokasi_nvr', 'bts_id', 'keterangan', 'created_at', 'updated_at'];
    protected $useTimestamps     = true;

    // Ambil daftar NVR beserta informasi BTS-nya
    public function getNvrWithBts()
    {
        return $this->db->table('d_nvr')
            ->select('d_nvr.*, d_bts.nama_bts')
            ->join('d_bts', 'd_bts.id = d_nvr.bts_id', 'left')
            ->orderBy('d_nvr.id', 'DESC')
            ->get()->getResultArray();
    }

    // Ambil daftar CCTV beserta informasi data NVR (Nama NVR, IP NVR, Lokasi NVR, Nama BTS)
    public function getCctvWithNvr()
    {
        return $this->db->table('d_cctv')
            ->select('d_cctv.*, d_nvr.nama_nvr, d_nvr.ip_nvr, d_nvr.lokasi_nvr, d_bts.nama_bts')
            ->join('d_nvr', 'd_nvr.id = d_cctv.nvr_id', 'left')
            ->join('d_bts', 'd_bts.id = d_nvr.bts_id', 'left')
            ->orderBy('d_cctv.id', 'DESC')
            ->get()->getResultArray();
    }

    // CRUD operation untuk NVR
    public function insertNvr($data)
    {
        return $this->db->table('d_nvr')->insert($data);
    }

    public function updateNvr($id, $data)
    {
        return $this->db->table('d_nvr')->where('id', $id)->update($data);
    }

    public function deleteNvr($id)
    {
        return $this->db->table('d_nvr')->where('id', $id)->delete();
    }

    // CRUD operation untuk CCTV
    public function insertCctv($data)
    {
        return $this->db->table('d_cctv')->insert($data);
    }

    public function updateCctv($id, $data)
    {
        return $this->db->table('d_cctv')->where('id', $id)->update($data);
    }

    public function deleteCctv($id)
    {
        return $this->db->table('d_cctv')->where('id', $id)->delete();
    }
}
