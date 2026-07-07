<?php

namespace App\Controllers;


class DataSI extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $data['MD_simcard'] = $db->table('d_simcard s')
            ->select('
        s.*,
        qs.kode_quota_simcard,
        qs.nama_paket_data,
        qs.quota_internet AS isi_quota_internet,
        qs.harga_quota AS harga_quota_internet,
        v.nama_vendor,
        p.kategori_pelanggan
    ')
            ->join('md_quota_simcard qs', 'qs.id = s.quota_simcard_id', 'left')
            ->join('md_vendor_cellular v', 'v.id = qs.vendor_cellular_id', 'left')
            ->join('md_pelanggan p', 'p.id = s.kategori_pelanggan_id', 'left')
            ->orderBy('s.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('DataSI/index', $data);
    }

    public function create()
    {
        $db = \Config\Database::connect();

        // Quota: bawa kode, nama paket, isi quota, harga, vendor untuk auto-isi
        $data['md_quota_simcard'] = $db->table('md_quota_simcard qs')
            ->select('qs.id, qs.kode_quota_simcard, qs.nama_paket_data,
                  qs.quota_internet, qs.harga_quota, v.nama_vendor')
            ->join('md_vendor_cellular v', 'v.id = qs.vendor_cellular_id', 'left')
            ->orderBy('qs.kode_quota_simcard', 'ASC')
            ->get()->getResultArray();

        $data['md_pelanggan'] = $db->table('md_pelanggan')
            ->orderBy('kategori_pelanggan', 'ASC')
            ->get()->getResultArray();

        return view('DataSI/FormDataSI', $data);
    }

    public function edit($id)
    {
        $model = new \App\Models\DataSIModel();
        $data  = $this->loadFormMasters();
        $data['simcard'] = $model->find($id);

        if (!$data['simcard']) {
            return redirect()->to('/DataSI')->with('error', 'Data tidak ditemukan.');
        }
        return view('DataSI/EditFormDataSimcard', $data);
    }

    private function loadFormMasters(): array
    {
        $db = \Config\Database::connect();

        $out['md_quota_simcard'] = $db->table('md_quota_simcard qs')
            ->select('qs.id, qs.kode_quota_simcard, qs.nama_paket_data,
                  qs.quota_internet, qs.harga_quota, v.nama_vendor')
            ->join('md_vendor_cellular v', 'v.id = qs.vendor_cellular_id', 'left')
            ->orderBy('qs.kode_quota_simcard', 'ASC')
            ->get()->getResultArray();

        $out['md_pelanggan'] = $db->table('md_pelanggan')
            ->orderBy('kategori_pelanggan', 'ASC')
            ->get()->getResultArray();

        return $out;
    }
    public function save()
    {
        date_default_timezone_set('Asia/Jakarta');

        $model = new \App\Models\DataSIModel();

        $nomor = trim($this->request->getPost('nomor_msisdn'));

        $cek = $model
            ->where('nomor_msisdn', $nomor)
            ->first();

        if ($cek) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Nomor MSISDN sudah terdaftar.');
        }

        $model->save([
            'quota_simcard_id'      => $this->request->getPost('quota_simcard_id'),
            'nomor_msisdn'          => $nomor,
            'nomor_imei'            => $this->request->getPost('nomor_imei'),
            'kategori_pelanggan_id' => $this->request->getPost('kategori_pelanggan_id'),
            'pelanggan_id'          => $this->request->getPost('pelanggan_id') ?: null,
            'toko_id'               => $this->request->getPost('toko_id') ?: null,
            'status'                => $this->request->getPost('status'),
            'keterangan'            => $this->request->getPost('keterangan'),
            'created_at'            => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/DataSI')
            ->with('success', 'Data berhasil disimpan.');
    }

    public function update()
    {
        $id     = $this->request->getPost('id');
        $model  = new \App\Models\DataSIModel();

        $nomor = trim((string) $this->request->getPost('nomor_msisdn'));

        // Cegah MSISDN ganda kecuali baris sendiri
        $cek = $model->where('nomor_msisdn', $nomor)->where('id !=', $id)->first();
        if ($cek) {
            return redirect()->back()->withInput()
                ->with('error', 'Nomor MSISDN sudah terdaftar.');
        }

        $model->update($id, [
            'quota_simcard_id'      => $this->request->getPost('quota_simcard_id'),
            'nomor_msisdn'          => $nomor,
            'nomor_imei'            => $this->request->getPost('nomor_imei'),
            'kategori_pelanggan_id' => $this->request->getPost('kategori_pelanggan_id'),
            'pelanggan_id'          => $this->request->getPost('id_pelanggan') ?: null,
            'toko_id'               => $this->request->getPost('kode_toko') ?: null,
            'status'                => $this->request->getPost('status'),
            'keterangan'            => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('/DataSI')->with('success', 'Data Simcard berhasil diperbarui');
    }
    public function delete($id)
    {
        $model = new \App\Models\DataSIModel();
        if (!$model->find($id)) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        $model->delete($id);
        return redirect()->to('/DataSI')->with('success', 'Data berhasil dihapus.');
    }
}
