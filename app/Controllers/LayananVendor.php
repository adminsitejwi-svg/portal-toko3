<?php

namespace App\Controllers;


class LayananVendor extends BaseController
{
    public function index()
    {
        $model = new \App\Models\LayananVendorModel();

        // JOIN ke md_vendor agar nama_vendor ikut tampil
        $data['MD_layanan_vendor'] = $model
            ->select('md_layanan_vendor.*, md_vendor.nama_vendor')
            ->join('md_vendor', 'md_vendor.id = md_layanan_vendor.vendor_id', 'left')
            ->orderBy('md_layanan_vendor.id', 'DESC')
            ->findAll();

        // daftar vendor untuk dropdown di modal edit
        $vendorModel = new \App\Models\VendorModel();
        $data['vendorList'] = $vendorModel->orderBy('nama_vendor', 'ASC')->findAll();

        return view('Vendor/LayananVendor', $data);
    }
    public function create()
    {
        // daftar vendor untuk dropdown di form tambah
        $vendorModel = new \App\Models\VendorModel();
        $data['vendorList'] = $vendorModel->orderBy('nama_vendor', 'ASC')->findAll();

        return view('Vendor/FormLV', $data);
    }

    public function save()
    {
        date_default_timezone_set('Asia/Jakarta');

        $model = new \App\Models\LayananVendorModel();

        $kode = trim((string) $this->request->getPost('kode_layanan_vendor'));

        // ── Cegah kode ganda (kode dijadikan index) ──
        if ($kode !== '' && $model->where('kode_layanan_vendor', $kode)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Kode "' . $kode . '" sudah digunakan. Kode tidak boleh ganda.');
        }

        $model->save([
            'kode_layanan_vendor' => $kode,
            'vendor_id'           => $this->request->getPost('vendor_id'),
            'nama_layanan'        => $this->request->getPost('nama_layanan'),
            'status'              => $this->request->getPost('status'),
            'keterangan'          => $this->request->getPost('keterangan'),
            'created_at'          => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/LayananVendor')
            ->with('success', 'Data Layanan Vendor berhasil disimpan');
    }
    public function delete($id)
    {
        $model = new \App\Models\LayananVendorModel();

        $data = $model->find($id);

        if (!$data) {
            return redirect()->back()
                ->with('error', 'Data tidak ditemukan.');
        }

        $model->delete($id);

        return redirect()->to('/LayananVendor')
            ->with('success', 'Data berhasil dihapus.');
    }
    public function update()
    {
        $id    = $this->request->getPost('id');
        $model = new \App\Models\LayananVendorModel();

        $kode = trim((string) $this->request->getPost('kode_layanan_vendor'));

        // ── Cegah kode ganda (kecuali baris ini) ──
        if ($kode !== '' && $model->where('kode_layanan_vendor', $kode)->where('id !=', $id)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Kode "' . $kode . '" sudah digunakan. Kode tidak boleh ganda.');
        }

        $model->update($id, [
            'kode_layanan_vendor' => $kode,
            'vendor_id'           => $this->request->getPost('vendor_id'),
            'nama_layanan'        => $this->request->getPost('nama_layanan'),
            'status'              => $this->request->getPost('status'),
            'keterangan'          => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('/LayananVendor')
            ->with('success', 'Data berhasil diperbarui.');
    }
}
