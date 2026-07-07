<?php

namespace App\Controllers;


class TypePerangkat extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $data['MD_type_perangkat'] = $db->table('md_type_perangkat tp')
            ->select('tp.*, jp.jenis_perangkat, mp.merk_perangkat')
            ->join('md_jenis_perangkat jp', 'jp.id = tp.jenis_perangkat_id', 'left')
            ->join('md_merek_perangkat mp', 'mp.id = tp.merek_perangkat_id', 'left')
            ->orderBy('tp.id', 'DESC')
            ->get()
            ->getResultArray();

        // ── data untuk dropdown di modal edit ──
        $jenisModel = new \App\Models\JenisPerangkatModel();
        $merkModel  = new \App\Models\PerangkatModel();

        $data['md_jenis_perangkat'] = $jenisModel->findAll();
        $data['md_merek_perangkat'] = $merkModel->findAll();

        return view('Perangkat/TypePerangkat', $data);
    }
    public function create()
    {
        $jenisModel = new \App\Models\JenisPerangkatModel();
        $merkModel  = new \App\Models\PerangkatModel();

        $data['MD_jenis_perangkat'] = $jenisModel->findAll();
        $data['MD_merek_perangkat'] = $merkModel->findAll();

        return view('Perangkat/FormTypePerangkat', $data);
    }
    public function save()
    {
        date_default_timezone_set('Asia/Jakarta');

        $model = new \App\Models\TypePerangkatModel();

        // Ambil dari nama field di form
        $jenisId = $this->request->getPost('jenis_perangkat_id');
        $merekId = $this->request->getPost('merk_perangkat_id');
        $type    = trim((string) $this->request->getPost('type_perangkat'));

        // Cegah data ganda (kombinasi jenis + merek + type lebih masuk akal)
        $dup = $model->where('jenis_perangkat_id', $jenisId)
            ->where('merek_perangkat_id', $merekId)
            ->where('type_perangkat', $type)
            ->first();

        if ($dup) {
            return redirect()->back()->withInput()
                ->with('error', 'Data Type Perangkat "' . $type . '" sudah ada. Data tidak boleh ganda.');
        }

        $model->save([
            'jenis_perangkat_id' => $jenisId,
            'merek_perangkat_id' => $merekId,
            'type_perangkat'     => $type,
            'status'             => $this->request->getPost('status'),
            'keterangan'         => $this->request->getPost('keterangan'),
            'created_at'         => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/TypePerangkat')
            ->with('success', 'Data Type Perangkat berhasil disimpan');
    }
    public function delete($id)
    {
        $model = new \App\Models\TypePerangkatModel();

        $data = $model->find($id);

        if (!$data) {
            return redirect()->back()
                ->with('error', 'Data tidak ditemukan.');
        }

        $model->delete($id);

        return redirect()->to('/TypePerangkat')
            ->with('success', 'Data berhasil dihapus.');
    }
    public function update()
    {
        $id    = $this->request->getPost('id');
        $model = new \App\Models\TypePerangkatModel();

        $jenisId = $this->request->getPost('jenis_perangkat_id');
        $merekId = $this->request->getPost('merk_perangkat_id');

        // ── Cegah data ganda (kecuali baris ini sendiri) ──
        $cekDup = trim((string) $this->request->getPost('jenis_perangkat_id'));
        if ($cekDup !== '' && $model->where('jenis_perangkat_id', $cekDup)->where('id !=', $id)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Data Type Perangkat "' . $cekDup . '" sudah ada. Data tidak boleh ganda.');
        }

        $model->update($id, [
            'jenis_perangkat_id' => $jenisId,
            'merek_perangkat_id' => $merekId,
            'type_perangkat'     => $this->request->getPost('type_perangkat'),
            'status'             => $this->request->getPost('status'),
            'keterangan'         => $this->request->getPost('keterangan'),
            // created_at biasanya tidak diubah saat update
        ]);

        return redirect()->to('/TypePerangkat')
            ->with('success', 'Data berhasil diperbarui.');
    }
}
