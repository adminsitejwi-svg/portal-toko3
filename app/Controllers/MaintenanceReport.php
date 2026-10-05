<?php

namespace App\Controllers;

use App\Models\JadwalModel;
use App\Models\MaintenanceReportModel;

class MaintenanceReport extends BaseController
{
    protected $reportModel;

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->reportModel = new MaintenanceReportModel();
    }

    // Halaman utama (view di folder MaintenanceReport/index.php)
    public function index()
    {
        return view('MaintenanceReport/index', [
            'report'      => $this->reportModel->orderBy('id', 'DESC')->findAll(),
            'namaOptions' => $this->namaOptions(),
            'statusList'  => MaintenanceReportModel::STATUS,
        ]);
    }

    // Pilihan dropdown Nama: dari data shift (jadwal_noc), sama seperti Daily Report
    private function namaOptions(): array
    {
        return (new JadwalModel())->distinct()->select('nama')
            ->where('nama !=', '')
            ->orderBy('nama', 'ASC')
            ->findColumn('nama') ?? [];
    }

    private function rules(): array
    {
        return [
            'nama'           => 'required|max_length[255]',
            'id_pelanggan'   => 'required|max_length[100]',
            'nama_pelanggan' => 'required|max_length[255]',
            'alamat'         => 'required',
            'odp_port'       => 'required|max_length[255]',
            'kendala'        => 'required',
            'no_tiket'       => 'required|max_length[100]',
            'action'         => 'required',
            'status'         => 'required|in_list[' . implode(',', array_keys(MaintenanceReportModel::STATUS)) . ']',
        ];
    }

    private function collectFields(): array
    {
        $post = fn (string $k) => trim((string) $this->request->getPost($k));

        return [
            'nama'                  => $post('nama'),
            'id_pelanggan'          => $post('id_pelanggan'),
            'nama_pelanggan'        => $post('nama_pelanggan'),
            'alamat'                => $post('alamat'),
            'odp_port'              => $post('odp_port'),
            'maintenance'           => $this->request->getPost('maintenance') ? 1 : 0,
            'perpindahan_perangkat' => $this->request->getPost('perpindahan_perangkat') ? 1 : 0,
            'kendala'               => $post('kendala'),
            'no_tiket'              => $post('no_tiket'),
            'action'                => $post('action'),
            'status'                => $post('status'),
        ];
    }

    // Simpan data baru (POST MaintenanceReport/save)
    public function save()
    {
        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->reportModel->insert($fields);

        return redirect()->to(site_url('MaintenanceReport'))
            ->with('success', 'Data maintenance report berhasil ditambahkan.');
    }

    // Update data (POST MaintenanceReport/update)
    public function update()
    {
        $id = $this->request->getPost('id');

        if (! $this->reportModel->find($id)) {
            return redirect()->to(site_url('MaintenanceReport'))->with('error', 'Data maintenance report tidak ditemukan.');
        }

        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->reportModel->update($id, $fields);

        return redirect()->to(site_url('MaintenanceReport'))
            ->with('success', 'Data maintenance report berhasil diperbarui.');
    }

    // Hapus data
    public function delete($id)
    {
        $this->reportModel->delete($id);

        return redirect()->to(site_url('MaintenanceReport'))
            ->with('success', 'Data maintenance report berhasil dihapus.');
    }
}
