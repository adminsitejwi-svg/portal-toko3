<?php

namespace App\Controllers;


class Jns_perangkat extends BaseController
{
    public function index()
    {
        $model = new \App\Models\JenisPerangkatModel();

        $data['MD_jenis_perangkat'] = $model
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('Perangkat/Jns_perangkat', $data);
    }
    public function create()
    {

        return view('Perangkat/FormJanis_Prgkt');
    }

    public function save()
    {
        date_default_timezone_set('Asia/Jakarta');

        $model = new \App\Models\JenisPerangkatModel();

        // ── Cegah data ganda ──
        $cekDup = trim((string) $this->request->getPost('jenis_perangkat'));
        if ($cekDup !== '' && $model->where('jenis_perangkat', $cekDup)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Data Jenis Perangkat "' . $cekDup . '" sudah ada. Data tidak boleh ganda.');
        }

        $model->save([
            'jenis_perangkat'   => $this->request->getPost('jenis_perangkat'),
            'status'            => $this->request->getPost('status'),
            'keterangan'        => $this->request->getPost('keterangan'),
            'created_at'        => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/Jns_perangkat')
            ->with('success', 'Data Vendor berhasil disimpan');
    }
    public function delete($id)
    {
        $model = new \App\Models\JenisPerangkatModel();

        $data = $model->find($id);

        if (!$data) {
            return redirect()->back()
                ->with('error', 'Data tidak ditemukan.');
        }

        $model->delete($id);

        return redirect()->to('/Jns_perangkat')
            ->with('success', 'Data berhasil dihapus.');
    }
    public function update()
    {
        $id = $this->request->getPost('id');

        $model = new \App\Models\JenisPerangkatModel();

        // ── Cegah data ganda (kecuali baris ini sendiri) ──
        $cekDup = trim((string) $this->request->getPost('jenis_perangkat'));
        if ($cekDup !== '' && $model->where('jenis_perangkat', $cekDup)->where('id !=', $id)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Data Jenis Perangkat "' . $cekDup . '" sudah ada. Data tidak boleh ganda.');
        }

        $model->update($id, [

            'jenis_perangkat'   => $this->request->getPost('jenis_perangkat'),

            'status'            => $this->request->getPost('status'),
            'keterangan'        => $this->request->getPost('keterangan'),
            'created_at' => date('Y-m-d H:i:s')

        ]);

        return redirect()->to('/Jns_perangkat')
            ->with('success', 'Data berhasil diperbarui.');
    }
}
