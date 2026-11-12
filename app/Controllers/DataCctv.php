<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DataCctvModel;

class DataCctv extends BaseController
{
    protected $cctvModel;

    public function __construct()
    {
        $this->cctvModel = new DataCctvModel();
        helper(['form', 'url', 'activity']);
    }

    public function index()
    {
        $db = \Config\Database::connect();

        $data = [
            'title'    => 'Data CCTV & NVR',
            'nvrList'  => $this->cctvModel->getNvrWithBts(),
            'cctvList' => $this->cctvModel->getCctvWithNvr(),
            'btsList'  => $db->tableExists('d_bts') ? $db->table('d_bts')->get()->getResultArray() : []
        ];

        return view('DataCctv/index', $data);
    }

    // --- NVR ACTIONS ---
    public function saveNvr()
    {
        $data = [
            'nama_nvr'   => $this->request->getPost('nama_nvr'),
            'ip_nvr'     => $this->request->getPost('ip_nvr'),
            'lokasi_nvr' => $this->request->getPost('lokasi_nvr'),
            'bts_id'     => $this->request->getPost('bts_id') ?: null,
            'keterangan' => $this->request->getPost('keterangan'),
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $this->cctvModel->insertNvr($data);
        log_activity('create', 'd_nvr', null, 'Menambah Data NVR: ' . $data['nama_nvr'], null, $data);

        return redirect()->to(base_url('DataCctv'))->with('success', 'Data NVR berhasil ditambahkan.');
    }

    public function updateNvr()
    {
        $id = $this->request->getPost('id');
        $data = [
            'nama_nvr'   => $this->request->getPost('nama_nvr'),
            'ip_nvr'     => $this->request->getPost('ip_nvr'),
            'lokasi_nvr' => $this->request->getPost('lokasi_nvr'),
            'bts_id'     => $this->request->getPost('bts_id') ?: null,
            'keterangan' => $this->request->getPost('keterangan'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $this->cctvModel->updateNvr($id, $data);
        log_activity('update', 'd_nvr', $id, 'Mengubah Data NVR: ' . $data['nama_nvr'], null, $data);

        return redirect()->to(base_url('DataCctv'))->with('success', 'Data NVR berhasil diperbarui.');
    }

    public function deleteNvr($id)
    {
        $this->cctvModel->deleteNvr($id);
        log_activity('delete', 'd_nvr', $id, 'Menghapus Data NVR ID: ' . $id);

        return redirect()->to(base_url('DataCctv'))->with('success', 'Data NVR berhasil dihapus.');
    }

    // --- CCTV ACTIONS ---
    public function saveCctv()
    {
        $data = [
            'nvr_id'      => $this->request->getPost('nvr_id'),
            'nama_cctv'   => $this->request->getPost('nama_cctv'),
            'ip_cctv'     => $this->request->getPost('ip_cctv'),
            'lokasi_cctv' => $this->request->getPost('lokasi_cctv'),
            'keterangan'  => $this->request->getPost('keterangan'),
            'created_at'  => date('Y-m-d H:i:s'),
        ];

        $this->cctvModel->insertCctv($data);
        log_activity('create', 'd_cctv', null, 'Menambah Data CCTV: ' . $data['nama_cctv'], null, $data);

        return redirect()->to(base_url('DataCctv'))->with('success', 'Data CCTV berhasil ditambahkan.');
    }

    public function updateCctv()
    {
        $id = $this->request->getPost('id');
        $data = [
            'nvr_id'      => $this->request->getPost('nvr_id'),
            'nama_cctv'   => $this->request->getPost('nama_cctv'),
            'ip_cctv'     => $this->request->getPost('ip_cctv'),
            'lokasi_cctv' => $this->request->getPost('lokasi_cctv'),
            'keterangan'  => $this->request->getPost('keterangan'),
            'updated_at'  => date('Y-m-d H:i:s'),
        ];

        $this->cctvModel->updateCctv($id, $data);
        log_activity('update', 'd_cctv', $id, 'Mengubah Data CCTV: ' . $data['nama_cctv'], null, $data);

        return redirect()->to(base_url('DataCctv'))->with('success', 'Data CCTV berhasil diperbarui.');
    }

    public function deleteCctv($id)
    {
        $this->cctvModel->deleteCctv($id);
        log_activity('delete', 'd_cctv', $id, 'Menghapus Data CCTV ID: ' . $id);

        return redirect()->to(base_url('DataCctv'))->with('success', 'Data CCTV berhasil dihapus.');
    }
}
