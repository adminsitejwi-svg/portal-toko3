<?php

namespace App\Controllers;

use App\Models\VPNModel;

class VPN extends BaseController
{
    public function index(): string
    {
        $model = new VPNModel();

        $data['MD_vpn'] = $model->orderBy('id', 'DESC')->findAll();

        return view('VPN/index', $data);
    }
    public function create(): string
    {
        return view('VPN/FormVPN');
    }
    public function save()
    {
        date_default_timezone_set('Asia/Jakarta');
        $model = new VPNModel();

        $kode = $this->request->getPost('kode_tujuan_koneksi');

        // Cek duplikat dulu
        $exists = $model->where('kode_tujuan_koneksi', $kode)->first();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Kode Tujuan Koneksi "' . $kode . '" sudah terdaftar.');
        }

        $model->insert([
            'kode_tujuan_koneksi' => $kode,
            'tujuan_koneksi'      => $this->request->getPost('tujuan_koneksi'),
            'ip_address_tujuan'   => $this->request->getPost('ip_address_tujuan'),
            'status'              => $this->request->getPost('status'),
            'keterangan'          => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to(site_url('VPN'))->with('success', 'Data VPN berhasil ditambahkan.');
    }
    public function delete($id)
    {
        $model = new \App\Models\VPNModel();

        $data = $model->find($id);

        if (!$data) {
            return redirect()->back()
                ->with('error', 'Data tidak ditemukan.');
        }

        $model->delete($id);

        return redirect()->to('/VPN')
            ->with('success', 'Data berhasil dihapus.');
    }
    public function update()
    {
        $model = new VPNModel();

        $id   = $this->request->getPost('id');
        $kode = $this->request->getPost('kode_tujuan_koneksi');

        if (!$model->find($id)) {
            return redirect()->to('/VPN')->with('error', 'Data tidak ditemukan.');
        }

        // Cek duplikat, kecuali baris yang sedang diedit
        $exists = $model->where('kode_tujuan_koneksi', $kode)
            ->where('id !=', $id)
            ->first();

        if ($exists) {
            return redirect()->to('/VPN')
                ->with('error', 'Kode Tujuan Koneksi "' . $kode . '" sudah terdaftar.');
        }

        $model->update($id, [
            'kode_tujuan_koneksi' => $kode,
            'tujuan_koneksi'      => $this->request->getPost('tujuan_koneksi'),
            'ip_address_tujuan'   => $this->request->getPost('ip_address_tujuan'),
            'status'              => $this->request->getPost('status'),
            'keterangan'          => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('/VPN')->with('success', 'Data VPN berhasil diupdate.');
    }
    public function show($id)
    {
        $model = new VPNModel();

        $data = $model->find($id);

        if (!$data) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data'    => $data
        ]);
    }
}
