<?php

namespace App\Controllers;

class NMRInet extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $data['md_nomer_inet'] = $db->table('d_nomor_inet d')
            ->select("
        d.id,
        d.status,
        d.keterangan,
        d.created_at,

        ni.nomor_inet,
        ni.password_inet,
        ni.kecepatan_bandwidth,
        ni.harga_layanan,

        lv.nama_layanan AS nama_paket_layanan,

        v.nama_vendor,
        v.kode_layanan_vendor,

        p.kategori_pelanggan
    ")
            ->join('md_nomer_inet ni', 'ni.id = d.nomer_inet_id', 'left')
            ->join('md_layanan_vendor lv', 'lv.id = ni.layanan_vendor_id', 'left')
            ->join('md_vendor v', 'v.id = lv.vendor_id', 'left')
            ->join('md_pelanggan p', 'p.id = d.kategori_pelanggan_id', 'left')
            ->orderBy('d.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('NMRInet/index', $data);
    }
    public function create()
    {
        $db = \Config\Database::connect();

        $data['md_nomer_inet'] = $db->table('md_nomer_inet ni')
            ->select('
            ni.id,
            ni.nomor_inet,
            ni.password_inet,
            ni.kecepatan_bandwidth,
            ni.harga_layanan,
            lv.nama_layanan AS nama_paket_layanan,
            v.nama_vendor,
            v.kode_layanan_vendor
        ')
            ->join('md_layanan_vendor lv', 'lv.id = ni.layanan_vendor_id', 'left')
            ->join('md_vendor v', 'v.id = lv.vendor_id', 'left')
            ->get()
            ->getResultArray();

        $data['md_pelanggan'] = $db->table('md_pelanggan')->get()->getResultArray();

        return view('NMRInet/FormNMRInet', $data);
    }
    public function save()
    {
        $rules = [
            'nomor_inet_id' => 'required|numeric',
            'pelanggan_id'  => 'required|numeric',
            'status'        => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', $this->validator->listErrors());
        }

        $model = new \App\Models\NMRInetModel();

        $nomerInet   = $this->request->getPost('nomor_inet_id');
        $pelanggan   = $this->request->getPost('pelanggan_id');
        $idPelanggan = trim($this->request->getPost('id_pelanggan'));
        $kodeToko    = trim($this->request->getPost('kode_toko'));



        $cekNomor = $model
            ->where('nomer_inet_id', $nomerInet)
            ->first();

        if ($cekNomor) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Nomor INET tersebut sudah digunakan.');
        }


        try {

            $model->insert([

                'nomer_inet_id'         => $nomerInet,
                'kategori_pelanggan_id' => $pelanggan,
                'id_pelanggan'          => $idPelanggan ?: null,
                'kode_toko'             => $kodeToko ?: null,
                'status'                => $this->request->getPost('status'),
                'keterangan'            => $this->request->getPost('keterangan'),



            ]);
        } catch (\Exception $e) {

            return redirect()->back()
                ->withInput()
                ->with('error', 'Nomor INET sudah terdaftar.');
        }

        return redirect()->to(site_url('NMRInet'))
            ->with('success', 'Data berhasil ditambahkan.');
    }

    public function delete($id)
    {
        $model = new \App\Models\NMRInetModel();

        $data = $model->find($id);

        if (!$data) {
            return redirect()->back()
                ->with('error', 'Data tidak ditemukan.');
        }

        $model->delete($id);

        return redirect()->to('/NMRInet')
            ->with('success', 'Data berhasil dihapus.');
    }
    public function edit($id)
    {
        $db = \Config\Database::connect();

        // Ambil data d_nomor_inet yang mau diedit
        $inet = $db->table('d_nomor_inet')->where('id', $id)->get()->getRowArray();

        if (!$inet) {
            return redirect()->to(site_url('NMRInet'))->with('error', 'Data tidak ditemukan.');
        }

        // Master data untuk dropdown, sama seperti create()
        $data['md_nomer_inet'] = $db->table('md_nomer_inet ni')
            ->select('
            ni.id,
            ni.nomor_inet,
            ni.password_inet,
            ni.kecepatan_bandwidth,
            ni.harga_layanan,
            lv.nama_layanan AS nama_paket_layanan,
            v.nama_vendor,
            v.kode_layanan_vendor
        ')
            ->join('md_layanan_vendor lv', 'lv.id = ni.layanan_vendor_id', 'left')
            ->join('md_vendor v', 'v.id = lv.vendor_id', 'left')
            ->get()
            ->getResultArray();

        $data['md_pelanggan'] = $db->table('md_pelanggan')->get()->getResultArray();
        $data['inet'] = $inet;

        return view('NMRInet/EditFormNMRInet', $data);
    }

    public function update()
    {
        $rules = [
            'nomor_inet_id' => 'required|numeric',
            'pelanggan_id'  => 'required|numeric',
            'status'        => 'required'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('error', $this->validator->listErrors());
        }

        $model = new \App\Models\NMRInetModel();

        $id          = $this->request->getPost('id');
        $nomerInet   = $this->request->getPost('nomor_inet_id');
        $pelanggan   = $this->request->getPost('pelanggan_id');
        $idPelanggan = trim($this->request->getPost('id_pelanggan'));
        $kodeToko    = trim($this->request->getPost('kode_toko'));

        // cek duplikat nomor inet, kecuali baris yang sedang diedit sendiri
        $cekNomor = $model
            ->where('nomer_inet_id', $nomerInet)
            ->where('id !=', $id)
            ->first();

        if ($cekNomor) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Nomor INET tersebut sudah digunakan data lain.');
        }

        try {
            $model->update($id, [
                'nomer_inet_id'         => $nomerInet,
                'kategori_pelanggan_id' => $pelanggan,
                'id_pelanggan'          => $idPelanggan ?: null,
                'kode_toko'             => $kodeToko ?: null,
                'status'                => $this->request->getPost('status'),
                'keterangan'            => $this->request->getPost('keterangan'),
            ]);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data.');
        }

        return redirect()->to(site_url('NMRInet'))
            ->with('success', 'Data berhasil diperbarui.');
    }
}
