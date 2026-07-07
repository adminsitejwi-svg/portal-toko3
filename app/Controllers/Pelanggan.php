<?php

namespace App\Controllers;


class Pelanggan extends BaseController
{
    public function index()
    {
        $model = new \App\Models\PelangganModel();

        $data['MD_pelanggan'] = $model
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('Pelanggan/index', $data);
    }
    public function create()
    {
        return view('Pelanggan/FormPLG');
    }

    public function save()
    {
        date_default_timezone_set('Asia/Jakarta');

        $model = new \App\Models\PelangganModel();

        // ── Cegah data ganda ──
        $cekDup = trim((string) $this->request->getPost('kategori_pelanggan'));
        if ($cekDup !== '' && $model->where('kategori_pelanggan', $cekDup)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Data Media Koneksi "' . $cekDup . '" sudah ada. Data tidak boleh ganda.');
        }

        $model->save([
            'kategori_pelanggan' => $this->request->getPost('kategori_pelanggan'),
            'status'        => $this->request->getPost('status'),
            'keterangan'    => $this->request->getPost('keterangan'),
            'created_at'    => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/Pelanggan')
            ->with('success', 'Data Media Koneksi berhasil disimpan');
    }
    public function delete($id)
    {
        $model = new \App\Models\PelangganModel();

        $data = $model->find($id);

        if (!$data) {
            return redirect()->back()
                ->with('error', 'Data tidak ditemukan.');
        }

        $model->delete($id);

        return redirect()->to('/Pelanggan')
            ->with('success', 'Data berhasil dihapus.');
    }
    public function update()
    {
        $id = $this->request->getPost('id');

        $model = new \App\Models\PelangganModel();

        // ── Cegah data ganda (kecuali baris ini sendiri) ──
        $cekDup = trim((string) $this->request->getPost('kategori_pelanggan'));
        if ($cekDup !== '' && $model->where('kategori_pelanggan', $cekDup)->where('id !=', $id)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Data Media Koneksi "' . $cekDup . '" sudah ada. Data tidak boleh ganda.');
        }

        $model->update($id, [

            'kategori_pelanggan'    => $this->request->getPost('kategori_pelanggan'),
            'status'     => $this->request->getPost('status'),
            'keterangan' => $this->request->getPost('keterangan'),

        ]);

        return redirect()->to('/Pelanggan')
            ->with('success', 'Data berhasil diperbarui.');
    }
}
