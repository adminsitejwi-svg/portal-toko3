<?php

namespace App\Controllers;

use App\Models\AktivasiModel;
use App\Models\HistoryReportModel;

// Data aktivasi per History Report (tab Aktivasi di halaman HistoryReport/gangguan/{id})
class Aktivasi extends BaseController
{
    protected $aktivasiModel;
    protected $reportModel;

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->aktivasiModel = new AktivasiModel();
        $this->reportModel   = new HistoryReportModel();
    }

    private function rules(): array
    {
        return [
            'id_pelanggan'     => 'required|max_length[100]',
            'nama_pelanggan'   => 'required|max_length[255]',
            'kapasitas_mbps'   => 'required|max_length[50]',
            'sn'               => 'required|max_length[255]',
            'tanggal_aktivasi' => 'required|valid_date[Y-m-d]',
        ];
    }

    private function collectFields(): array
    {
        $post = fn ($k) => trim((string) $this->request->getPost($k));

        return [
            'id_pelanggan'     => $post('id_pelanggan'),
            'nama_pelanggan'   => $post('nama_pelanggan'),
            'kapasitas_mbps'   => $post('kapasitas_mbps'),
            'sn'               => $post('sn'),
            'tanggal_aktivasi' => $post('tanggal_aktivasi'),
        ];
    }

    private function backToList($reportId, string $msg)
    {
        return redirect()->to(site_url('HistoryReport/gangguan/' . $reportId) . '?tab=aktivasi')->with('success', $msg);
    }

    // Simpan data baru (POST HistoryReport/aktivasi/save)
    public function save()
    {
        $reportId = $this->request->getPost('report_id');
        if (! $this->reportModel->find($reportId)) {
            return redirect()->to(site_url('HistoryReport'))->with('error', 'Data report tidak ditemukan.');
        }

        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->aktivasiModel->insert($fields + ['report_id' => $reportId]);

        return $this->backToList($reportId, 'Data aktivasi berhasil ditambahkan.');
    }

    // Update data (POST HistoryReport/aktivasi/update)
    public function update()
    {
        $id  = $this->request->getPost('id');
        $row = $this->aktivasiModel->find($id);

        if (! $row) {
            return redirect()->to(site_url('HistoryReport'))->with('error', 'Data aktivasi tidak ditemukan.');
        }

        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->aktivasiModel->update($id, $fields);

        return $this->backToList($row['report_id'], 'Data aktivasi berhasil diperbarui.');
    }

    // Hapus data
    public function delete($id)
    {
        $row = $this->aktivasiModel->find($id);

        if (! $row) {
            return redirect()->to(site_url('HistoryReport'))->with('error', 'Data aktivasi tidak ditemukan.');
        }

        $this->aktivasiModel->delete($id);

        return $this->backToList($row['report_id'], 'Data aktivasi berhasil dihapus.');
    }
}
