<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RekapDataTokoModel;

class RekapDataMidi extends BaseController
{
    protected $rekapModel;
    protected $uploadPath = 'uploads/rekap_toko/'; // Lokasi di dalam folder public/

    public function __construct()
    {
        $this->rekapModel = new RekapDataTokoModel();
        helper('activity');
    }

    // Mengarah ke app/Views/RekapDataMidi/index.php
    public function index()
    {
        $data = [
            'title'      => 'Rekap Data Toko',
            'rekap_list' => $this->rekapModel->orderBy('id', 'DESC')->findAll(),
        ];

        return view('RekapDataMidi/index', $data);
    }

    // Menampilkan halaman FormTambahRKD.php
    public function create()
    {
        $data = [
            'title' => 'Form Rekap Data Toko',
        ];

        return view('RekapDataMidi/FormTambahRKD', $data);
    }

    // Simpan data baru (POST: RekapDataMidi/save)
    public function save()
    {
        if (!$this->validate($this->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Tangani pilihan dropdown vs isi manual
        $branch = ($this->request->getPost('branch') === 'manual')
            ? $this->request->getPost('branch_manual')
            : $this->request->getPost('branch');

        $modem1 = ($this->request->getPost('tipe_modem_gsm_1') === 'manual')
            ? $this->request->getPost('tipe_modem_gsm_1_manual')
            : $this->request->getPost('tipe_modem_gsm_1');

        $modem2 = ($this->request->getPost('tipe_modem_gsm_2') === 'manual')
            ? $this->request->getPost('tipe_modem_gsm_2_manual')
            : $this->request->getPost('tipe_modem_gsm_2');

        $kodeToko = esc($this->request->getPost('kode_toko'));

        // Upload 3 file dokumen/foto (jpg, png, doc, docx, pdf)
        $fileFortigate = $this->handleUpload($this->request->getFile('foto_sn_fortigate'), $kodeToko . '_fortigate');
        $fileOntFo     = $this->handleUpload($this->request->getFile('sn_ont_fo'), $kodeToko . '_ont_fo');
        $fileImeiModem = $this->handleUpload($this->request->getFile('foto_imei_modem_1'), $kodeToko . '_imei_modem1');

        $dataSimpan = [
            'nama_pengisi'           => $this->request->getPost('nama_pengisi'),
            'perusahaan'             => $this->request->getPost('perusahaan'),
            'tanggal'                => $this->request->getPost('tanggal'),
            'toko'                   => $this->request->getPost('toko'),
            'branch'                 => $branch,
            'kode_toko'              => $kodeToko,
            'nama_toko'              => $this->request->getPost('nama_toko'),
            'foto_sn_fortigate'      => $fileFortigate,
            'id_pelanggan_fo'        => $this->request->getPost('id_pelanggan_fo'),
            'sn_ont_fo'              => $fileOntFo,
            'tipe_modem_gsm_1'       => $modem1,
            'foto_imei_modem_1'      => $fileImeiModem,
            'msisdn_modem_1'         => $this->request->getPost('msisdn_modem_1'),
            'iccid_modem_1'          => $this->request->getPost('iccid_modem_1'),
            'tipe_modem_gsm_2'       => $modem2,
            'msisdn_modem_2'         => $this->request->getPost('msisdn_modem_2'),
            'iccid_modem_2'          => $this->request->getPost('iccid_modem_2'),
            'sharelock_tikor_alamat' => $this->request->getPost('sharelock_tikor_alamat'),
        ];

        $this->rekapModel->save($dataSimpan);

        $newId = $this->rekapModel->getInsertID();
        log_activity('create', 'rekap_data_toko', $newId, null, null, $dataSimpan);

        return redirect()->to('/RekapDataMidi')->with('success', 'Data rekap toko berhasil ditambahkan.');
    }

    // Update data dari form edit (POST: RekapDataMidi/update)
    public function update()
    {
        $id       = $this->request->getPost('id');
        $existing = $this->rekapModel->find($id);

        if (!$existing) {
            return redirect()->to('/RekapDataMidi')->with('error', 'Data tidak ditemukan.');
        }

        if (!$this->validate($this->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $branch = ($this->request->getPost('branch') === 'manual')
            ? $this->request->getPost('branch_manual')
            : $this->request->getPost('branch');

        $modem1 = ($this->request->getPost('tipe_modem_gsm_1') === 'manual')
            ? $this->request->getPost('tipe_modem_gsm_1_manual')
            : $this->request->getPost('tipe_modem_gsm_1');

        $modem2 = ($this->request->getPost('tipe_modem_gsm_2') === 'manual')
            ? $this->request->getPost('tipe_modem_gsm_2_manual')
            : $this->request->getPost('tipe_modem_gsm_2');

        $kodeToko = esc($this->request->getPost('kode_toko'));

        // Cek apakah user mencentang "Hapus file"
        $oldFortigate = $existing['foto_sn_fortigate'];
        if ($this->request->getPost('hapus_foto_sn_fortigate') === '1') {
            $this->deleteFileIfExists($oldFortigate);
            $oldFortigate = null;
        }

        $oldOntFo = $existing['sn_ont_fo'];
        if ($this->request->getPost('hapus_sn_ont_fo') === '1') {
            $this->deleteFileIfExists($oldOntFo);
            $oldOntFo = null;
        }

        $oldImeiModem = $existing['foto_imei_modem_1'];
        if ($this->request->getPost('hapus_foto_imei_modem_1') === '1') {
            $this->deleteFileIfExists($oldImeiModem);
            $oldImeiModem = null;
        }

        // Proses upload file baru jika dipilih, atau gunakan $old...
        $fileFortigate = $this->handleUpload($this->request->getFile('foto_sn_fortigate'), $kodeToko . '_fortigate', $oldFortigate);
        $fileOntFo     = $this->handleUpload($this->request->getFile('sn_ont_fo'), $kodeToko . '_ont_fo', $oldOntFo);
        $fileImeiModem = $this->handleUpload($this->request->getFile('foto_imei_modem_1'), $kodeToko . '_imei_modem1', $oldImeiModem);

        $dataUpdate = [
            'nama_pengisi'           => $this->request->getPost('nama_pengisi'),
            'perusahaan'             => $this->request->getPost('perusahaan'),
            'tanggal'                => $this->request->getPost('tanggal'),
            'toko'                   => $this->request->getPost('toko'),
            'branch'                 => $branch,
            'kode_toko'              => $kodeToko,
            'nama_toko'              => $this->request->getPost('nama_toko'),
            'foto_sn_fortigate'      => $fileFortigate,
            'id_pelanggan_fo'        => $this->request->getPost('id_pelanggan_fo'),
            'sn_ont_fo'              => $fileOntFo,
            'tipe_modem_gsm_1'       => $modem1,
            'foto_imei_modem_1'      => $fileImeiModem,
            'msisdn_modem_1'         => $this->request->getPost('msisdn_modem_1'),
            'iccid_modem_1'          => $this->request->getPost('iccid_modem_1'),
            'tipe_modem_gsm_2'       => $modem2,
            'msisdn_modem_2'         => $this->request->getPost('msisdn_modem_2'),
            'iccid_modem_2'          => $this->request->getPost('iccid_modem_2'),
            'sharelock_tikor_alamat' => $this->request->getPost('sharelock_tikor_alamat'),
        ];

        $this->rekapModel->update($id, $dataUpdate);
        log_activity('update', 'rekap_data_toko', $id, null, $existing, $dataUpdate);

        return redirect()->to('/RekapDataMidi')->with('success', 'Data rekap toko berhasil diperbarui.');
    }

    // Menampilkan halaman FormEditRKD.php
    public function edit($id)
    {
        $row = $this->rekapModel->find($id);
        if (!$row) {
            return redirect()->to('/RekapDataMidi')->with('error', 'Data rekap toko tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Rekap Data Toko',
            'row'   => $row,
        ];

        return view('RekapDataMidi/FormEditRKD', $data);
    }

    // Hapus data (GET: RekapDataMidi/delete/(:num))
    public function delete($id)
    {
        $existing = $this->rekapModel->find($id);
        if ($existing) {
            $this->deleteFileIfExists($existing['foto_sn_fortigate']);
            $this->deleteFileIfExists($existing['sn_ont_fo']);
            $this->deleteFileIfExists($existing['foto_imei_modem_1']);

            $this->rekapModel->delete($id);
            log_activity('delete', 'rekap_data_toko', $id, null, $existing, null);

            return redirect()->to('/RekapDataMidi')->with('success', 'Data rekap toko berhasil dihapus.');
        }

        return redirect()->to('/RekapDataMidi')->with('error', 'Data tidak ditemukan.');
    }

    private function getValidationRules(): array
    {
        return [
            'nama_pengisi'           => 'required|max_length[100]',
            'perusahaan'             => 'required|in_list[PT Genezis Mitra Technology,PT Rtiga Global Media]',
            'tanggal'                => 'required|valid_date',
            'toko'                   => 'required|in_list[Lawson,Midi]',
            'branch'                 => 'required',
            'kode_toko'              => 'required|max_length[50]',
            'nama_toko'              => 'required|max_length[150]',
            'foto_sn_fortigate'      => 'permit_empty|max_size[foto_sn_fortigate,5120]|ext_in[foto_sn_fortigate,jpg,jpeg,png,doc,docx,pdf]',
            'sn_ont_fo'              => 'permit_empty|max_size[sn_ont_fo,5120]|ext_in[sn_ont_fo,jpg,jpeg,png,doc,docx,pdf]',
            'tipe_modem_gsm_1'       => 'required',
            'foto_imei_modem_1'      => 'permit_empty|max_size[foto_imei_modem_1,5120]|ext_in[foto_imei_modem_1,jpg,jpeg,png,doc,docx,pdf]',
            'msisdn_modem_1'         => 'permit_empty|max_length[25]',
            'iccid_modem_1'          => 'permit_empty|max_length[25]',
            'msisdn_modem_2'         => 'permit_empty|max_length[25]',
            'iccid_modem_2'          => 'permit_empty|max_length[25]',
            'sharelock_tikor_alamat' => 'required',
        ];
    }

    private function handleUpload($file, string $prefix, ?string $oldFile = null): ?string
    {
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $this->deleteFileIfExists($oldFile);

            // Buat folder otomatis jika belum ada
            if (!is_dir(FCPATH . $this->uploadPath)) {
                mkdir(FCPATH . $this->uploadPath, 0775, true);
            }

            $newName = time() . '_' . preg_replace('/[^A-Za-z0-9\-]/', '', $prefix) . '.' . $file->getExtension();
            $file->move(FCPATH . $this->uploadPath, $newName);

            return $this->uploadPath . $newName;
        }

        return $oldFile;
    }

    private function deleteFileIfExists(?string $filePath): void
    {
        if (!empty($filePath) && file_exists(FCPATH . $filePath)) {
            unlink(FCPATH . $filePath);
        }
    }
}
