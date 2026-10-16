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
            'tanggal_report' => 'required|valid_date[Y-m-d]',
            'nama'           => 'required|max_length[255]',
            'id_pelanggan'   => 'required|max_length[100]',
            'nama_pelanggan' => 'required|max_length[255]',
            'alamat'         => 'required',
            'odp_port'       => 'required|max_length[255]',
            'waktu_kendala'  => 'required|valid_date[Y-m-d H:i:s]',
            'kendala'        => 'required',
            'no_tiket'       => 'required|max_length[100]',
            'action'         => 'required',
            'status'         => 'required|in_list[' . implode(',', array_keys(MaintenanceReportModel::STATUS)) . ']',
        ];
    }

    private function collectFields(): array
    {
        $post = fn (string $k) => trim((string) $this->request->getPost($k));

        // Input datetime-local (2026-10-15T14:30) disimpan sebagai datetime MySQL; nilai tidak valid dibiarkan agar gagal validasi
        $waktuKendala = \DateTime::createFromFormat('!Y-m-d\TH:i', $post('waktu_kendala'));

        // Waktu Resolved diisi otomatis oleh form dari jam komputer pengguna saat status diubah ke Resolved
        // (2026-10-15T14:30:05). Jika kosong/tidak valid memakai jam server; status selain Resolved dikosongkan.
        $waktuResolved = null;
        if ($post('status') === 'Resolved') {
            $wr = \DateTime::createFromFormat('!Y-m-d\TH:i:s', $post('waktu_resolved'))
                ?: \DateTime::createFromFormat('!Y-m-d\TH:i', $post('waktu_resolved'));
            $waktuResolved = ($wr ?: new \DateTime())->format('Y-m-d H:i:s');
        }

        return [
            'tanggal_report'        => $post('tanggal_report'),
            'nama'                  => $post('nama'),
            'id_pelanggan'          => $post('id_pelanggan'),
            'nama_pelanggan'        => $post('nama_pelanggan'),
            'alamat'                => $post('alamat'),
            'odp_port'              => $post('odp_port'),
            'waktu_kendala'         => $waktuKendala ? $waktuKendala->format('Y-m-d H:i:s') : $post('waktu_kendala'),
            'maintenance'           => $this->request->getPost('maintenance') ? 1 : 0,
            'perpindahan_perangkat' => $this->request->getPost('perpindahan_perangkat') ? 1 : 0,
            'gangguan'              => $this->request->getPost('gangguan') ? 1 : 0,
            'kendala'               => $post('kendala'),
            'no_tiket'              => $post('no_tiket'),
            'action'                => $post('action'),
            'status'                => $post('status'),
            'waktu_resolved'        => $waktuResolved,
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
