<?php

namespace App\Controllers;

use App\Models\JadwalModel;
use App\Models\ReportSatModel;

// Halaman Tiket FO Loss (menu Daily Report SAT Group di sidebar)
class ReportSat extends BaseController
{
    protected $reportModel;

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->reportModel = new ReportSatModel();
    }

    // Halaman utama (view di folder ReportSat/index.php)
    public function index()
    {
        return view('ReportSat/index', [
            'report'          => $this->reportModel->orderBy('tanggal_report', 'DESC')->orderBy('id', 'DESC')->findAll(),
            // Pilihan filter Shift & Kategori Toko
            'shiftOptions'    => ReportSatModel::SHIFT,
            'kategoriOptions' => ReportSatModel::KATEGORI_TOKO,
        ]);
    }

    // Pilihan dropdown di form tambah/edit
    private function formData(): array
    {
        return [
            'nocOptions'      => $this->nocOptions(),
            'shiftOptions'    => ReportSatModel::SHIFT,
            'kategoriOptions' => ReportSatModel::KATEGORI_TOKO,
        ];
    }

    // Halaman form tambah (view ReportSat/FormReportSat.php)
    public function create()
    {
        return view('ReportSat/FormReportSat', $this->formData());
    }

    // Halaman form edit (view ReportSat/EditFormReportSat.php)
    public function edit($id)
    {
        $row = $this->reportModel->find($id);

        if (! $row) {
            return redirect()->to(site_url('ReportSat'))->with('error', 'Data Tiket FO Loss tidak ditemukan.');
        }

        return view('ReportSat/EditFormReportSat', $this->formData() + ['row' => $row]);
    }

    // Pilihan dropdown NOC On Duty: nama dari data shift (jadwal_noc), sama seperti Daily Report
    private function nocOptions(): array
    {
        return (new JadwalModel())->distinct()->select('nama')
            ->where('nama !=', '')
            ->orderBy('nama', 'ASC')
            ->findColumn('nama') ?? [];
    }

    private function rules(): array
    {
        return [
            'noc_on_duty'         => 'required|max_length[255]',
            'shift'               => 'required|in_list[' . implode(',', ReportSatModel::SHIFT) . ']',
            'kategori_toko'       => 'required|in_list[' . implode(',', ReportSatModel::KATEGORI_TOKO) . ']',
            'kode_toko'           => 'required|max_length[100]',
            'nama_toko'           => 'required|max_length[255]',
            'alamat'              => 'required',
            'deskripsi_pekerjaan' => 'required',
            'resi_pengiriman'     => 'permit_empty|max_length[255]',
            'kendala'             => 'required',
            'action'              => 'required',
            'tanggal_report'      => 'required|valid_date[Y-m-d]',
            'status'              => 'required|max_length[100]',
            'noted'               => 'permit_empty',
        ];
    }

    private function collectFields(): array
    {
        $post = fn (string $k) => trim((string) $this->request->getPost($k));

        return [
            'noc_on_duty'         => $post('noc_on_duty'),
            'shift'               => $post('shift'),
            'kategori_toko'       => $post('kategori_toko'),
            'kode_toko'           => $post('kode_toko'),
            'nama_toko'           => $post('nama_toko'),
            'alamat'              => $post('alamat'),
            'deskripsi_pekerjaan' => $post('deskripsi_pekerjaan'),
            'resi_pengiriman'     => $post('resi_pengiriman') ?: null,
            'kendala'             => $post('kendala'),
            'action'              => $post('action'),
            'tanggal_report'      => $post('tanggal_report'),
            'status'              => $post('status'),
            'noted'               => $post('noted') ?: null,
        ];
    }

    // Simpan data baru (POST ReportSat/save)
    public function save()
    {
        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->reportModel->insert($fields);

        return redirect()->to(site_url('ReportSat'))
            ->with('success', 'Data Tiket FO Loss berhasil ditambahkan.');
    }

    // Update data (POST ReportSat/update)
    public function update()
    {
        $id = $this->request->getPost('id');

        if (! $this->reportModel->find($id)) {
            return redirect()->to(site_url('ReportSat'))->with('error', 'Data Tiket FO Loss tidak ditemukan.');
        }

        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->reportModel->update($id, $fields);

        return redirect()->to(site_url('ReportSat'))
            ->with('success', 'Data Tiket FO Loss berhasil diperbarui.');
    }

    // Hapus data
    public function delete($id)
    {
        $this->reportModel->delete($id);

        return redirect()->to(site_url('ReportSat'))
            ->with('success', 'Data Tiket FO Loss berhasil dihapus.');
    }
}
