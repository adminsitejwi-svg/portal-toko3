<?php

namespace App\Controllers;


class Perangkat extends BaseController
{
    public function index()
    {
        $model = new \App\Models\PerangkatModel();

        $data['MD_merek_perangkat'] = $model
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('Perangkat/index', $data);
    }
    public function create()
    {
        return view('Perangkat/FormPrgkt');
    }

    public function save()
    {
        date_default_timezone_set('Asia/Jakarta');

        $model = new \App\Models\PerangkatModel();

        // ── Cegah data ganda ──
        $cekDup = trim((string) $this->request->getPost('merk_perangkat'));
        if ($cekDup !== '' && $model->where('merk_perangkat', $cekDup)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Data Merek Perangkat "' . $cekDup . '" sudah ada. Data tidak boleh ganda.');
        }

        $model->save([
            'merk_perangkat' => $this->request->getPost('merk_perangkat'),
            'status' => $this->request->getPost('status'),
            'keterangan' => $this->request->getPost('keterangan'),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/Perangkat')
            ->with('success', 'Data Vendor berhasil disimpan');
    }
    public function delete($id)
    {
        $model = new \App\Models\PerangkatModel();

        $data = $model->find($id);

        if (!$data) {
            return redirect()->back()
                ->with('error', 'Data tidak ditemukan.');
        }

        $model->delete($id);

        return redirect()->to('/Perangkat')
            ->with('success', 'Data berhasil dihapus.');
    }
    public function update()
    {
        $id = $this->request->getPost('id');

        $model = new \App\Models\PerangkatModel();

        // ── Cegah data ganda (kecuali baris ini sendiri) ──
        $cekDup = trim((string) $this->request->getPost('merk_perangkat'));
        if ($cekDup !== '' && $model->where('merk_perangkat', $cekDup)->where('id !=', $id)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Data Merek Perangkat "' . $cekDup . '" sudah ada. Data tidak boleh ganda.');
        }

        $model->update($id, [

            'merk_perangkat' => $this->request->getPost('merk_perangkat'),
            'status' => $this->request->getPost('status'),
            'keterangan' => $this->request->getPost('keterangan'),
            'created_at' => date('Y-m-d H:i:s')

        ]);

        return redirect()->to('/Perangkat')
            ->with('success', 'Data berhasil diperbarui.');
    }
}
