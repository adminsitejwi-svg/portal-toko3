<?php

namespace App\Controllers;

use App\Models\DataCenterModel;
use App\Models\InterkoneksiModel;

// Simpan / ubah / hapus Data Center. Tampilannya digabung di halaman Data Interkoneksi:
// daftar Data Center ada di kartu kiri, form tambah/edit berupa pop up.
class DataCenter extends BaseController
{
    protected $dcModel;

    public function __construct()
    {
        $this->dcModel = new DataCenterModel();
    }

    // Halaman lama Data Center & form-nya sekarang ada di halaman Data Interkoneksi
    public function index()
    {
        return redirect()->to(site_url('Interkoneksi'));
    }

    public function create()
    {
        return redirect()->to(site_url('Interkoneksi'));
    }

    public function edit($id)
    {
        return redirect()->to(site_url('Interkoneksi'));
    }

    private function rules(): array
    {
        return [
            'nama_dc'    => 'required|max_length[255]',
            'alamat_dc'  => 'required',
            'lokasi_rak' => 'required|max_length[255]',
        ];
    }

    private function collectFields(): array
    {
        $post = fn (string $k) => trim((string) $this->request->getPost($k));

        return [
            'nama_dc'    => $post('nama_dc'),
            'alamat_dc'  => $post('alamat_dc'),
            'lokasi_rak' => $post('lokasi_rak'),
        ];
    }

    // Gagal validasi: kembali ke halaman dan buka lagi pop up Data Center dengan isian sebelumnya
    private function gagal(string $pesan)
    {
        return redirect()->to(site_url('Interkoneksi'))
            ->withInput()
            ->with('popup', 'dc')
            ->with('error', $pesan);
    }

    // Simpan data baru (POST DataCenter/save)
    public function save()
    {
        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules())) {
            return $this->gagal(implode(' ', $this->validator->getErrors()));
        }

        $this->dcModel->insert($fields);

        return redirect()->to(site_url('Interkoneksi'))
            ->with('success', 'Data Center berhasil ditambahkan.');
    }

    // Update data (POST DataCenter/update)
    public function update()
    {
        $id = $this->request->getPost('id');

        if (! $this->dcModel->find($id)) {
            return redirect()->to(site_url('Interkoneksi'))->with('error', 'Data Center tidak ditemukan.');
        }

        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules())) {
            return $this->gagal(implode(' ', $this->validator->getErrors()));
        }

        $this->dcModel->update($id, $fields);

        return redirect()->to(site_url('Interkoneksi'))
            ->with('success', 'Data Center berhasil diperbarui.');
    }

    // Hapus data; ditolak bila masih dipakai Data Interkoneksi (agar datanya tidak kehilangan Data Center)
    public function delete($id)
    {
        $dipakai = (new InterkoneksiModel())->where('data_center_id', $id)->countAllResults();

        if ($dipakai > 0) {
            return redirect()->to(site_url('Interkoneksi'))
                ->with('error', 'Data Center masih dipakai ' . $dipakai . ' data interkoneksi. Pindahkan atau hapus data interkoneksinya dulu.');
        }

        $this->dcModel->delete($id);

        return redirect()->to(site_url('Interkoneksi'))
            ->with('success', 'Data Center berhasil dihapus.');
    }
}
