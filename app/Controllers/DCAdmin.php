<?php

namespace App\Controllers;


class DCAdmin extends BaseController
{
    public function index()
    {
        $model = new \App\Models\DCModel();

        $data['MD_dc'] = $model
            ->orderBy('id', 'DESC')
            ->findAll();

        return view('DCAdmin/index', $data);
    }
    public function create()
    {
        return view('DCAdmin/FormDC');
    }

    public function save()
    {
        $rules = [
            'kode_dc'    => 'required',
            'nama_dc'    => 'required',
            'alamat_dc'  => 'required',
            'status'     => 'required',
            'keterangan' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()
                ->with('error', 'Semua field wajib diisi.');
        }

        $model = new \App\Models\DCModel();

        $kode = trim((string) $this->request->getPost('kode_dc'));
        $nama = trim((string) $this->request->getPost('nama_dc'));

        // ── Cegah kode ganda ──
        if ($kode !== '' && $model->where('kode_dc', $kode)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Kode "' . $kode . '" sudah digunakan. Kode tidak boleh ganda.');
        }

        // ── Cegah nama ganda ──
        if ($nama !== '' && $model->where('nama_dc', $nama)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Data DC "' . $nama . '" sudah ada. Data tidak boleh ganda.');
        }

        $model->save([
            'kode_dc'    => $kode,
            'nama_dc'    => $nama,
            'alamat_dc'  => $this->request->getPost('alamat_dc'),
            'status'     => $this->request->getPost('status'),
            'keterangan' => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('/DCAdmin')
            ->with('success', 'Data DC berhasil disimpan');
    }
    public function delete($id)
    {
        $model = new \App\Models\DCModel();

        $data = $model->find($id);

        if (!$data) {
            return redirect()->back()
                ->with('error', 'Data tidak ditemukan.');
        }

        $model->delete($id);

        return redirect()->to('/DCAdmin')
            ->with('success', 'Data berhasil dihapus.');
    }
    public function update()
    {
        $id    = $this->request->getPost('id');
        $model = new \App\Models\DCModel();

        $kode = trim((string) $this->request->getPost('kode_dc'));
        $nama = trim((string) $this->request->getPost('nama_dc'));

        // ── Cegah kode ganda (kecuali baris ini) ──
        if ($kode !== '' && $model->where('kode_dc', $kode)->where('id !=', $id)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Kode "' . $kode . '" sudah digunakan. Kode tidak boleh ganda.');
        }

        // ── Cegah nama ganda (kecuali baris ini) ──
        if ($nama !== '' && $model->where('nama_dc', $nama)->where('id !=', $id)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Data DC "' . $nama . '" sudah ada. Data tidak boleh ganda.');
        }

        $model->update($id, [
            'kode_dc'    => $kode,
            'nama_dc'    => $nama,
            'alamat_dc'  => $this->request->getPost('alamat_dc'),
            'status'     => $this->request->getPost('status'),
            'keterangan' => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('/DCAdmin')
            ->with('success', 'Data berhasil diperbarui.');
    }
}
