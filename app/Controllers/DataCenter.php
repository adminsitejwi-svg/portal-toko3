<?php

namespace App\Controllers;

use App\Models\DataCenterModel;

// Halaman Data Center (menu Data Center di sidebar)
class DataCenter extends BaseController
{
    protected $dcModel;

    public function __construct()
    {
        $this->dcModel = new DataCenterModel();
    }

    // Halaman utama (view di folder DataCenter/index.php)
    public function index()
    {
        return view('DataCenter/index', [
            'dataCenter' => $this->dcModel->orderBy('kode_dc', 'ASC')->findAll(),
        ]);
    }

    // Halaman form tambah (view DataCenter/FormDataCenter.php)
    public function create()
    {
        return view('DataCenter/FormDataCenter');
    }

    // Halaman form edit (view DataCenter/EditFormDataCenter.php)
    public function edit($id)
    {
        $row = $this->dcModel->find($id);

        if (! $row) {
            return redirect()->to(site_url('DataCenter'))->with('error', 'Data Center tidak ditemukan.');
        }

        return view('DataCenter/EditFormDataCenter', ['row' => $row]);
    }

    // $id diisi saat update agar kode milik data itu sendiri tidak dianggap duplikat
    private function rules($id = null): array
    {
        return [
            'nama_dc'   => 'required|max_length[255]',
            'kode_dc'   => 'required|max_length[100]|is_unique[md_data_center.kode_dc,id,' . (int) $id . ']',
            'deskripsi' => 'permit_empty',
        ];
    }

    private function messages(): array
    {
        return [
            'kode_dc' => ['is_unique' => 'Kode Data Center sudah dipakai.'],
        ];
    }

    private function collectFields(): array
    {
        $post = fn (string $k) => trim((string) $this->request->getPost($k));

        return [
            'nama_dc'   => $post('nama_dc'),
            'kode_dc'   => $post('kode_dc'),
            'deskripsi' => $post('deskripsi') ?: null,
        ];
    }

    // Simpan data baru (POST DataCenter/save)
    public function save()
    {
        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules(), $this->messages())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->dcModel->insert($fields);

        return redirect()->to(site_url('DataCenter'))
            ->with('success', 'Data Center berhasil ditambahkan.');
    }

    // Update data (POST DataCenter/update)
    public function update()
    {
        $id = $this->request->getPost('id');

        if (! $this->dcModel->find($id)) {
            return redirect()->to(site_url('DataCenter'))->with('error', 'Data Center tidak ditemukan.');
        }

        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules($id), $this->messages())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->dcModel->update($id, $fields);

        return redirect()->to(site_url('DataCenter'))
            ->with('success', 'Data Center berhasil diperbarui.');
    }

    // Hapus data
    public function delete($id)
    {
        $this->dcModel->delete($id);

        return redirect()->to(site_url('DataCenter'))
            ->with('success', 'Data Center berhasil dihapus.');
    }
}
