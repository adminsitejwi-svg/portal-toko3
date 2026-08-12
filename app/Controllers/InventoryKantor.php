<?php

namespace App\Controllers;

use App\Models\BarangModel;

class InventoryKantor extends BaseController
{
    protected $barangModel;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
    }

    // Halaman utama (view di folder InventoryKantor/index.php)
    public function index()
    {
        $data = [
            'inventory' => $this->barangModel->orderBy('id', 'DESC')->findAll(),
        ];

        return view('InventoryKantor/index', $data);
    }

    // Halaman form tambah barang (terpisah)
    // View: app/Views/InventoryKantor/FormInventoryKantor.php
    public function create()
    {
        return view('InventoryKantor/FormInventoryKantor');
    }

    // Simpan data baru (dari modal Tambah -> POST InventoryKantor/save)
    public function save()
    {
        date_default_timezone_set('Asia/Jakarta');
        $rules = [
            'nama_barang'    => 'required',
            'jumlah'         => 'required|integer',
            'sn_kode_barang' => 'required',
            'kondisi_barang' => 'required|in_list[Baik,Rusak,Hilang,Perlu Service]',
            'keterangan'     => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->barangModel->save([
            'nama_barang'    => $this->request->getPost('nama_barang'),
            'jumlah'         => $this->request->getPost('jumlah'),
            'sn_kode_barang' => $this->request->getPost('sn_kode_barang'),
            'kondisi_barang' => $this->request->getPost('kondisi_barang'),
            'keterangan'     => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to(site_url('InventoryKantor'))
            ->with('success', 'Data barang berhasil ditambahkan.');
    }

    // Update data (dari modal Edit -> POST InventoryKantor/update)
    public function update()
    {
        $id = $this->request->getPost('id');

        $rules = [
            'nama_barang'    => 'required',
            'jumlah'         => 'required|integer',
            'sn_kode_barang' => 'required',
            'kondisi_barang' => 'required|in_list[Baik,Rusak,Hilang,Perlu Service]',
            'keterangan'     => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->barangModel->update($id, [
            'nama_barang'    => $this->request->getPost('nama_barang'),
            'jumlah'         => $this->request->getPost('jumlah'),
            'sn_kode_barang' => $this->request->getPost('sn_kode_barang'),
            'kondisi_barang' => $this->request->getPost('kondisi_barang'),
            'keterangan'     => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to(site_url('InventoryKantor'))
            ->with('success', 'Data barang berhasil diperbarui.');
    }

    // Hapus data
    public function delete($id)
    {
        $this->barangModel->delete($id);

        return redirect()->to(site_url('InventoryKantor'))
            ->with('success', 'Data barang berhasil dihapus.');
    }

    // Detail (dipanggil via fetch untuk modal Detail) -> JSON
    public function show($id)
    {
        $data = $this->barangModel->find($id);

        if (! $data) {
            return $this->response
                ->setStatusCode(404)
                ->setJSON(['error' => 'Data tidak ditemukan']);
        }

        return $this->response->setJSON(['data' => $data]);
    }
}