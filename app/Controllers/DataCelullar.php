<?php

namespace App\Controllers;


class DataCelullar extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $data['MD_data_celullar'] = $db->table('md_data_celullar dc')
            ->select('dc.*, v.nama_vendor')
            ->join('md_vendor v', 'v.id = dc.vendor_id', 'left')
            ->orderBy('dc.id', 'DESC')
            ->get()
            ->getResultArray();

        $vendorModel = new \App\Models\VendorModel();

        $data['md_vendor'] = $vendorModel->findAll();

        return view('DataCelullar/index', $data);
    }
    public function create()
    {
        $vendorModel = new \App\Models\VendorModel();


        $data['MD_vendor'] = $vendorModel->findAll();

        return view('DataCelullar/FormDataCelullar', $data);
    }
    public function save()
    {
        date_default_timezone_set('Asia/Jakarta');

        $model = new \App\Models\DataCelullarModel();

        $vendorId      = $this->request->getPost('vendor_id');
        $namaPaketData = trim((string) $this->request->getPost('nama_paket_data'));

        // Cek data ganda berdasarkan vendor dan nama paket
        $dup = $model->where('vendor_id', $vendorId)
            ->where('nama_paket_data', $namaPaketData)
            ->first();

        if ($dup) {
            return redirect()->back()->withInput()
                ->with(
                    'error',
                    'Paket Data "' . $namaPaketData . '" pada vendor tersebut sudah ada.'
                );
        }

        $model->save([
            'vendor_id'       => $vendorId,
            'nama_paket_data' => $namaPaketData,
            'status'          => $this->request->getPost('status'),
            'keterangan'      => $this->request->getPost('keterangan'),
            'created_at'      => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/DataCelullar')
            ->with('success', 'Data Paket Data berhasil disimpan');
    }
    public function delete($id)
    {
        $model = new \App\Models\DataCelullarModel();

        $data = $model->find($id);

        if (!$data) {
            return redirect()->back()
                ->with('error', 'Data tidak ditemukan.');
        }

        $model->delete($id);

        return redirect()->to('/DataCelullar')
            ->with('success', 'Data berhasil dihapus.');
    }
    public function update()
    {
        $id    = $this->request->getPost('id');
        $model = new \App\Models\DataCelullarModel();

        $vendorId      = $this->request->getPost('vendor_id');
        $namaPaketData = trim((string) $this->request->getPost('nama_paket_data'));

        // Cegah data ganda (vendor + paket data)
        $cekDup = $model
            ->where('vendor_id', $vendorId)
            ->where('nama_paket_data', $namaPaketData)
            ->where('id !=', $id)
            ->first();

        if ($cekDup) {
            return redirect()->back()->withInput()
                ->with(
                    'error',
                    'Paket Data "' . $namaPaketData . '" pada vendor tersebut sudah ada.'
                );
        }

        $model->update($id, [
            'vendor_id'       => $vendorId,
            'nama_paket_data' => $namaPaketData,
            'status'          => $this->request->getPost('status'),
            'keterangan'      => $this->request->getPost('keterangan'),
            'updated_at'      => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/DataCelullar')
            ->with('success', 'Data Paket Data berhasil diperbarui.');
    }
}
