<?php

namespace App\Controllers;

use App\Models\FollowUpModel;
use App\Models\GangguanModel;
use App\Models\HistoryReportModel;

// Data follow up per History Report (tab Follow Up di halaman HistoryReport/gangguan/{id})
class FollowUp extends BaseController
{
    protected $followUpModel;
    protected $reportModel;

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->followUpModel = new FollowUpModel();
        $this->reportModel   = new HistoryReportModel();
    }

    private function rules(): array
    {
        // Pilihan Group & Priority sama dengan data gangguan
        return [
            'grup'          => 'required|in_list[' . implode(',', GangguanModel::GRUP) . ']',
            'customer_site' => 'required|max_length[255]',
            'priority'      => 'required|in_list[' . implode(',', GangguanModel::PRIORITY) . ']',
            'due_date'      => 'permit_empty|valid_date[Y-m-d]',
            'pic'           => 'required|max_length[255]',
            'issue'         => 'required',
        ];
    }

    private function collectFields(): array
    {
        $post = fn ($k) => trim((string) $this->request->getPost($k));

        return [
            'grup'          => $post('grup'),
            'customer_site' => $post('customer_site'),
            'priority'      => $post('priority'),
            'due_date'      => $post('due_date') ?: null,
            'pic'           => $post('pic'),
            'issue'         => $post('issue'),
            'action'        => $post('action'),
        ];
    }

    private function backToList($reportId, string $msg)
    {
        return redirect()->to(site_url('HistoryReport/gangguan/' . $reportId) . '?tab=followup')->with('success', $msg);
    }

    // Simpan data baru (POST HistoryReport/followup/save)
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

        $this->followUpModel->insert($fields + ['report_id' => $reportId]);

        return $this->backToList($reportId, 'Data follow up berhasil ditambahkan.');
    }

    // Update data (POST HistoryReport/followup/update)
    public function update()
    {
        $id  = $this->request->getPost('id');
        $row = $this->followUpModel->find($id);

        if (! $row) {
            return redirect()->to(site_url('HistoryReport'))->with('error', 'Data follow up tidak ditemukan.');
        }

        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->followUpModel->update($id, $fields);

        return $this->backToList($row['report_id'], 'Data follow up berhasil diperbarui.');
    }

    // Hapus data
    public function delete($id)
    {
        $row = $this->followUpModel->find($id);

        if (! $row) {
            return redirect()->to(site_url('HistoryReport'))->with('error', 'Data follow up tidak ditemukan.');
        }

        $this->followUpModel->delete($id);

        return $this->backToList($row['report_id'], 'Data follow up berhasil dihapus.');
    }
}
