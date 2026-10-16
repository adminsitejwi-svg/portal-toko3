<?php

namespace App\Controllers;

use App\Models\JadwalModel;
use App\Models\ReportSatModel;
use App\Models\ResiPengirimanModel;

// Halaman Resi Pengiriman (menu Daily Report SAT Group di sidebar, tab Resi Pengiriman)
class ResiPengiriman extends BaseController
{
    protected $resiModel;

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->resiModel = new ResiPengirimanModel();
    }

    // Halaman utama (view di folder ResiPengiriman/index.php)
    public function index()
    {
        // Pilihan Shift & Kategori Toko sama dengan Tiket FO Loss
        return view('ResiPengiriman/index', [
            'report'          => $this->resiModel->orderBy('tanggal_report', 'DESC')->orderBy('id', 'DESC')->findAll(),
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

    // Halaman form tambah (view ResiPengiriman/FormResiPengiriman.php)
    public function create()
    {
        return view('ResiPengiriman/FormResiPengiriman', $this->formData());
    }

    // Halaman form edit (view ResiPengiriman/EditFormResiPengiriman.php)
    public function edit($id)
    {
        $row = $this->resiModel->find($id);

        if (! $row) {
            return redirect()->to(site_url('ResiPengiriman'))->with('error', 'Data Resi Pengiriman tidak ditemukan.');
        }

        return view('ResiPengiriman/EditFormResiPengiriman', $this->formData() + ['row' => $row]);
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
            'resi_pengiriman'     => 'required|max_length[255]',
            'kendala'             => 'required',
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
            'resi_pengiriman'     => $post('resi_pengiriman'),
            'kendala'             => $post('kendala'),
            'tanggal_report'      => $post('tanggal_report'),
            'status'              => $post('status'),
            'noted'               => $post('noted') ?: null,
        ];
    }

    // Simpan data baru (POST ResiPengiriman/save)
    public function save()
    {
        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->resiModel->insert($fields);

        return redirect()->to(site_url('ResiPengiriman'))
            ->with('success', 'Data Resi Pengiriman berhasil ditambahkan.');
    }

    // Update data (POST ResiPengiriman/update)
    public function update()
    {
        $id = $this->request->getPost('id');

        if (! $this->resiModel->find($id)) {
            return redirect()->to(site_url('ResiPengiriman'))->with('error', 'Data Resi Pengiriman tidak ditemukan.');
        }

        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->resiModel->update($id, $fields);

        return redirect()->to(site_url('ResiPengiriman'))
            ->with('success', 'Data Resi Pengiriman berhasil diperbarui.');
    }

    // Hapus data
    public function delete($id)
    {
        $this->resiModel->delete($id);

        return redirect()->to(site_url('ResiPengiriman'))
            ->with('success', 'Data Resi Pengiriman berhasil dihapus.');
    }
}
