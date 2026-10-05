<?php

namespace App\Controllers;

use App\Models\GangguanModel;
use App\Models\HistoryReportModel;
use App\Models\PengirimanModel;

// Data pengiriman per History Report (tab Pengiriman di halaman HistoryReport/gangguan/{id})
class Pengiriman extends BaseController
{
    protected $pengirimanModel;
    protected $reportModel;

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->pengirimanModel = new PengirimanModel();
        $this->reportModel     = new HistoryReportModel();
    }

    private function rules(): array
    {
        // Pilihan Group sama dengan data gangguan
        return [
            'grup'          => 'required|in_list[' . implode(',', GangguanModel::GRUP) . ']',
            'customer'      => 'required|max_length[255]',
            'device'        => 'required|max_length[255]',
            'tracking_resi' => 'permit_empty|max_length[255]',
            'status'        => 'required|in_list[' . implode(',', PengirimanModel::STATUS) . ']',
            'eta'           => 'permit_empty|valid_date[Y-m-d]',
            'pic'           => 'required|max_length[255]',
        ];
    }

    private function collectFields(): array
    {
        $post = fn ($k) => trim((string) $this->request->getPost($k));

        return [
            'grup'          => $post('grup'),
            'customer'      => $post('customer'),
            'device'        => $post('device'),
            'tracking_resi' => $post('tracking_resi'),
            'status'        => $post('status'),
            'eta'           => $post('eta') ?: null,
            'pic'           => $post('pic'),
        ];
    }

    private function backToList($reportId, string $msg)
    {
        return redirect()->to(site_url('HistoryReport/gangguan/' . $reportId) . '?tab=pengiriman')->with('success', $msg);
    }

    // Simpan data baru (POST HistoryReport/pengiriman/save)
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

        $this->pengirimanModel->insert($fields + ['report_id' => $reportId]);

        return $this->backToList($reportId, 'Data pengiriman berhasil ditambahkan.');
    }

    // Update data (POST HistoryReport/pengiriman/update)
    public function update()
    {
        $id  = $this->request->getPost('id');
        $row = $this->pengirimanModel->find($id);

        if (! $row) {
            return redirect()->to(site_url('HistoryReport'))->with('error', 'Data pengiriman tidak ditemukan.');
        }

        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->pengirimanModel->update($id, $fields);

        return $this->backToList($row['report_id'], 'Data pengiriman berhasil diperbarui.');
    }

    // Hapus data
    public function delete($id)
    {
        $row = $this->pengirimanModel->find($id);

        if (! $row) {
            return redirect()->to(site_url('HistoryReport'))->with('error', 'Data pengiriman tidak ditemukan.');
        }

        $this->pengirimanModel->delete($id);

        return $this->backToList($row['report_id'], 'Data pengiriman berhasil dihapus.');
    }
}
