<?php

namespace App\Controllers;


class Vendor extends BaseController
{
    public function index()
    {
        $model = new \App\Models\VendorModel();

        $data['MD_Vendor'] = $model
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('Vendor/index', $data);
    }
    public function create()
    {
        return view('Vendor/FormVendor');
    }

    public function save()
    {
        date_default_timezone_set('Asia/Jakarta');

        $model = new \App\Models\VendorModel();

        $kode = trim((string) $this->request->getPost('kode_layanan_vendor'));
        $nama = trim((string) $this->request->getPost('nama_vendor'));

        // ── Cegah kode ganda ──
        if ($kode !== '' && $model->where('kode_layanan_vendor', $kode)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Kode "' . $kode . '" sudah digunakan. Kode tidak boleh ganda.');
        }

        // ── Cegah nama ganda ──
        if ($nama !== '' && $model->where('nama_vendor', $nama)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Data Vendor "' . $nama . '" sudah ada. Data tidak boleh ganda.');
        }

        $model->save([
            'kode_layanan_vendor' => $kode,
            'nama_vendor'         => $nama,
            'alamat_vendor'       => $this->request->getPost('alamat_vendor'),
            'status'              => $this->request->getPost('status'),
            'keterangan'          => $this->request->getPost('keterangan'),
            'created_at'          => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/Vendor')
            ->with('success', 'Data Vendor berhasil disimpan');
    }
    public function delete($id)
    {
        $model = new \App\Models\VendorModel();

        try {

            $data = $model->find($id);

            if (!$data) {
                return redirect()->to('/Vendor')
                    ->with('error', 'Data tidak ditemukan.');
            }

            $model->delete($id);

            return redirect()->to('/Vendor')
                ->with('success', 'Data Vendor berhasil dihapus.');
        } catch (\Throwable $e) {

            // Tulis error ke log agar bisa dicek jika diperlukan
            log_message('error', 'Vendor Delete Error : ' . $e->getMessage());

            // Jangan tampilkan Whoops
            return redirect()->to('/Vendor')
                ->with(
                    'error',
                    'Data tidak dapat dihapus karena masih digunakan pada data lain.'
                );
        }
    }
    public function update()
    {
        $id    = $this->request->getPost('id');
        $model = new \App\Models\VendorModel();

        $kode = trim((string) $this->request->getPost('kode_layanan_vendor'));
        $nama = trim((string) $this->request->getPost('nama_vendor'));

        // ── Cegah kode ganda (kecuali baris ini) ──
        if ($kode !== '' && $model->where('kode_layanan_vendor', $kode)->where('id !=', $id)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Kode "' . $kode . '" sudah digunakan. Kode tidak boleh ganda.');
        }

        // ── Cegah nama ganda (kecuali baris ini) ──
        if ($nama !== '' && $model->where('nama_vendor', $nama)->where('id !=', $id)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Data Vendor "' . $nama . '" sudah ada. Data tidak boleh ganda.');
        }

        $model->update($id, [
            'kode_layanan_vendor' => $kode,
            'nama_vendor'         => $nama,
            'alamat_vendor'       => $this->request->getPost('alamat_vendor'),
            'status'              => $this->request->getPost('status'),
            'keterangan'          => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('/Vendor')
            ->with('success', 'Data berhasil diperbarui.');
    }
}
