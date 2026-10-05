<?php

namespace App\Controllers;

use App\Models\GangguanModel;
use App\Models\HistoryReportModel;
use App\Models\MaintenanceModel;

// Data maintenance per History Report (tab Maintenance di halaman HistoryReport/gangguan/{id})
class Maintenance extends BaseController
{
    protected $maintenanceModel;
    protected $reportModel;

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->maintenanceModel = new MaintenanceModel();
        $this->reportModel      = new HistoryReportModel();
    }

    private function rules(): array
    {
        // Pilihan Group sama dengan data gangguan
        return [
            'grup'      => 'required|in_list[' . implode(',', GangguanModel::GRUP) . ']',
            'site'      => 'required|max_length[255]',
            'equipment' => 'required|max_length[255]',
            'schedule'  => 'required|valid_date[Y-m-d]',
            'status'    => 'required|in_list[' . implode(',', MaintenanceModel::STATUS) . ']',
            'pic'       => 'required|max_length[255]',
        ];
    }

    private function collectFields(): array
    {
        $post = fn ($k) => trim((string) $this->request->getPost($k));

        return [
            'grup'      => $post('grup'),
            'site'      => $post('site'),
            'equipment' => $post('equipment'),
            'schedule'  => $post('schedule'),
            'status'    => $post('status'),
            'pic'       => $post('pic'),
            'issue'     => $post('issue'),
            'action'    => $post('action'),
        ];
    }

    private function backToList($reportId, string $msg)
    {
        return redirect()->to(site_url('HistoryReport/gangguan/' . $reportId) . '?tab=maintenance')->with('success', $msg);
    }

    // Simpan data baru (POST HistoryReport/maintenance/save)
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

        $this->maintenanceModel->insert($fields + ['report_id' => $reportId]);

        return $this->backToList($reportId, 'Data maintenance berhasil ditambahkan.');
    }

    // Update data (POST HistoryReport/maintenance/update)
    public function update()
    {
        $id  = $this->request->getPost('id');
        $row = $this->maintenanceModel->find($id);

        if (! $row) {
            return redirect()->to(site_url('HistoryReport'))->with('error', 'Data maintenance tidak ditemukan.');
        }

        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->maintenanceModel->update($id, $fields);

        return $this->backToList($row['report_id'], 'Data maintenance berhasil diperbarui.');
    }

    // Hapus data
    public function delete($id)
    {
        $row = $this->maintenanceModel->find($id);

        if (! $row) {
            return redirect()->to(site_url('HistoryReport'))->with('error', 'Data maintenance tidak ditemukan.');
        }

        $this->maintenanceModel->delete($id);

        return $this->backToList($row['report_id'], 'Data maintenance berhasil dihapus.');
    }
}
