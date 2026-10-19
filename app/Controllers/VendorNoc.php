<?php

namespace App\Controllers;

use App\Models\VendorNocModel;

// Halaman Data Vendor (menu All NOC > Data Vendor di sidebar, beda dengan menu Vendor Non Celullar)
class VendorNoc extends BaseController
{
    protected $vendorModel;

    public function __construct()
    {
        $this->vendorModel = new VendorNocModel();
    }

    // Halaman utama (view di folder VendorNoc/index.php)
    public function index()
    {
        return view('VendorNoc/index', [
            'vendors' => $this->vendorModel->orderBy('nama_vendor', 'ASC')->findAll(),
        ]);
    }

    // Halaman form tambah (view VendorNoc/FormVendorNoc.php)
    public function create()
    {
        return view('VendorNoc/FormVendorNoc');
    }

    // Halaman form edit (view VendorNoc/EditFormVendorNoc.php)
    public function edit($id)
    {
        $row = $this->vendorModel->find($id);

        if (! $row) {
            return redirect()->to(site_url('VendorNoc'))->with('error', 'Data Vendor tidak ditemukan.');
        }

        return view('VendorNoc/EditFormVendorNoc', ['row' => $row]);
    }

    private function rules(): array
    {
        return [
            'nama_vendor' => 'required|max_length[255]',
            'alamat'      => 'permit_empty',
            'nama_pic'    => 'required|max_length[255]',
            'telp_pic'    => 'required|max_length[50]',
            'email_pic'   => 'permit_empty|max_length[255]|valid_email',
        ];
    }

    private function messages(): array
    {
        return [
            'email_pic' => ['valid_email' => 'Format Email PIC tidak valid.'],
        ];
    }

    private function collectFields(): array
    {
        $post = fn (string $k) => trim((string) $this->request->getPost($k));

        return [
            'nama_vendor' => $post('nama_vendor'),
            'alamat'      => $post('alamat') ?: null,
            'nama_pic'    => $post('nama_pic'),
            'telp_pic'    => $post('telp_pic'),
            'email_pic'   => $post('email_pic') ?: null,
        ];
    }

    // Simpan data baru (POST VendorNoc/save)
    public function save()
    {
        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules(), $this->messages())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->vendorModel->insert($fields);

        return redirect()->to(site_url('VendorNoc'))
            ->with('success', 'Data Vendor berhasil ditambahkan.');
    }

    // Update data (POST VendorNoc/update)
    public function update()
    {
        $id = $this->request->getPost('id');

        if (! $this->vendorModel->find($id)) {
            return redirect()->to(site_url('VendorNoc'))->with('error', 'Data Vendor tidak ditemukan.');
        }

        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules(), $this->messages())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->vendorModel->update($id, $fields);

        return redirect()->to(site_url('VendorNoc'))
            ->with('success', 'Data Vendor berhasil diperbarui.');
    }

    // Hapus data
    public function delete($id)
    {
        $this->vendorModel->delete($id);

        return redirect()->to(site_url('VendorNoc'))
            ->with('success', 'Data Vendor berhasil dihapus.');
    }
}
