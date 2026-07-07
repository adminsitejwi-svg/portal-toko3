<?php

namespace App\Controllers;


class QuotaSIMCARD extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $data['md_quota_simcard'] = $db->table('md_quota_simcard qs')
            ->select('qs.*, vc.nama_vendor AS nama_vendor_cellular')
            ->join('md_vendor_cellular vc', 'vc.id = qs.vendor_cellular_id', 'left')
            ->orderBy('qs.id', 'DESC')
            ->get()->getResultArray();

        $data['md_vendor_cellular'] = $db->table('md_vendor_cellular')
            ->orderBy('nama_vendor', 'ASC')
            ->get()->getResultArray();

        return view('QuotaSIMCARD/index', $data);
    }

    public function create()
    {
        $db = \Config\Database::connect();

        $data['md_vendor_cellular'] = $db->table('md_vendor_cellular')
            ->orderBy('nama_vendor', 'ASC')
            ->get()->getResultArray();

        return view('QuotaSIMCARD/FormQuotaSIMCARD', $data);
    }

    public function save()
    {
        date_default_timezone_set('Asia/Jakarta');
        $model = new \App\Models\QuotaSIMCARDModel();

        $kode = trim((string) $this->request->getPost('kode_quota_simcard'));

        // ── Cegah kode ganda (kode dijadikan index) ──
        if ($kode !== '' && $model->where('kode_quota_simcard', $kode)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Kode "' . $kode . '" sudah digunakan. Kode tidak boleh ganda.');
        }

        $vendorId  = $this->request->getPost('vendor_cellular_id');
        $namaPaket = trim((string) $this->request->getPost('nama_paket_data'));
        $quota     = trim((string) $this->request->getPost('quota_internet'));

        // ── Cegah quota ganda untuk vendor + paket yang sama ──
        $cek = $model->where('vendor_cellular_id', $vendorId)
            ->where('nama_paket_data', $namaPaket)
            ->where('quota_internet', $quota)
            ->first();
        if ($cek) {
            return redirect()->back()->withInput()
                ->with('error', 'Quota internet tersebut sudah tersedia.');
        }

        $model->save([
            'kode_quota_simcard' => $kode,
            'vendor_cellular_id' => $vendorId,
            'nama_paket_data'    => $namaPaket,
            'quota_internet'     => $quota,
            'harga_quota'        => $this->request->getPost('harga_quota'),
            'status'             => $this->request->getPost('status'),
            'keterangan'         => $this->request->getPost('keterangan'),
            'created_at'         => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/QuotaSIMCARD')
            ->with('success', 'Data quota berhasil disimpan.');
    }

    public function delete($id)
    {

        $model = new \App\Models\QuotaSIMCARDModel();

        $data = $model->find($id);

        if (!$data) {
            return redirect()->back()
                ->with('error', 'Data tidak ditemukan.');
        }

        $model->delete($id);

        return redirect()->to('/QuotaSIMCARD')
            ->with('success', 'Data berhasil dihapus.');
    }
    public function update()
    {
        date_default_timezone_set('Asia/Jakarta');
        $id    = $this->request->getPost('id');
        $model = new \App\Models\QuotaSIMCARDModel();

        $kode = trim((string) $this->request->getPost('kode_quota_simcard'));

        // ── Cegah kode ganda (kecuali baris ini) ──
        if ($kode !== '' && $model->where('kode_quota_simcard', $kode)->where('id !=', $id)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Kode "' . $kode . '" sudah digunakan. Kode tidak boleh ganda.');
        }

        $vendorId  = $this->request->getPost('vendor_cellular_id');
        $namaPaket = trim((string) $this->request->getPost('nama_paket_data'));
        $quota     = trim((string) $this->request->getPost('quota_internet'));

        $cek = $model->where('vendor_cellular_id', $vendorId)
            ->where('nama_paket_data', $namaPaket)
            ->where('quota_internet', $quota)
            ->where('id !=', $id)
            ->first();
        if ($cek) {
            return redirect()->back()->withInput()
                ->with('error', 'Quota internet tersebut sudah tersedia.');
        }

        $model->update($id, [
            'kode_quota_simcard' => $kode,
            'vendor_cellular_id' => $vendorId,
            'nama_paket_data'    => $namaPaket,
            'quota_internet'     => $quota,
            'harga_quota'        => $this->request->getPost('harga_quota'),
            'status'             => $this->request->getPost('status'),
            'keterangan'         => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('/QuotaSIMCARD')
            ->with('success', 'Data quota berhasil diperbarui.');
    }
}
