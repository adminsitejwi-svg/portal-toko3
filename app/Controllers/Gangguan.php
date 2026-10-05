<?php

namespace App\Controllers;

use App\Models\AktivasiModel;
use App\Models\CatatanModel;
use App\Models\FollowUpModel;
use App\Models\GangguanModel;
use App\Models\HistoryReportModel;
use App\Models\MaintenanceModel;
use App\Models\PengirimanModel;

// Data gangguan per History Report (dibuka dari tombol Settings di halaman History Report)
class Gangguan extends BaseController
{
    protected $gangguanModel;
    protected $reportModel;

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->gangguanModel = new GangguanModel();
        $this->reportModel   = new HistoryReportModel();
    }

    // Halaman gangguan milik satu report (view di folder HistoryReport/gangguan.php)
    public function index($reportId)
    {
        $report = $this->reportModel->find($reportId);
        if (! $report) {
            return redirect()->to(site_url('HistoryReport'))->with('error', 'Data report tidak ditemukan.');
        }

        $data = [
            'report'   => $report,
            'gangguan' => $this->gangguanModel
                ->where('report_id', $reportId)
                ->orderBy('id', 'DESC')
                ->findAll(),
            'followUp' => (new FollowUpModel())
                ->where('report_id', $reportId)
                ->orderBy('id', 'DESC')
                ->findAll(),
            'pengiriman' => (new PengirimanModel())
                ->where('report_id', $reportId)
                ->orderBy('id', 'DESC')
                ->findAll(),
            'maintenance' => (new MaintenanceModel())
                ->where('report_id', $reportId)
                ->orderBy('id', 'DESC')
                ->findAll(),
            'catatan' => (new CatatanModel())
                ->where('report_id', $reportId)
                ->orderBy('id', 'DESC')
                ->findAll(),
            'aktivasi' => (new AktivasiModel())
                ->where('report_id', $reportId)
                ->orderBy('id', 'DESC')
                ->findAll(),
            // Tab yang dibuka: ?tab=followup / pengiriman / maintenance / aktivasi / catatan setelah simpan/hapus
            'activeTab'       => in_array($this->request->getGet('tab'), ['followup', 'pengiriman', 'maintenance', 'aktivasi', 'catatan'], true)
                ? $this->request->getGet('tab')
                : 'gangguan',
            'pengirimanStatusOptions'  => PengirimanModel::STATUS,
            'maintenanceStatusOptions' => MaintenanceModel::STATUS,
            'catatanGrupOptions'       => CatatanModel::GRUP,
            'catatanKategoriOptions'   => CatatanModel::KATEGORI,
            'catatanStatusOptions'     => CatatanModel::STATUS,
            'grupOptions'     => GangguanModel::GRUP,
            'statusOptions'   => GangguanModel::STATUS,
            'priorityOptions' => GangguanModel::PRIORITY,
        ];

        return view('HistoryReport/gangguan', $data);
    }

    private function rules(): array
    {
        return [
            'grup'          => 'required|in_list[' . implode(',', GangguanModel::GRUP) . ']',
            'sub_grup'      => 'permit_empty|max_length[255]',
            'customer_site' => 'required|max_length[255]',
            'cid_ticket'    => 'permit_empty|max_length[255]',
            'status'        => 'required|in_list[' . implode(',', GangguanModel::STATUS) . ']',
            'priority'      => 'required|in_list[' . implode(',', GangguanModel::PRIORITY) . ']',
            'gangguan'      => 'required',
            'pic'           => 'required|max_length[255]',
        ];
    }

    private function collectFields(): array
    {
        $post = fn ($k) => trim((string) $this->request->getPost($k));

        return [
            'grup'          => $post('grup'),
            'sub_grup'      => $post('sub_grup'),
            'customer_site' => $post('customer_site'),
            'cid_ticket'    => $post('cid_ticket'),
            'status'        => $post('status'),
            'priority'      => $post('priority'),
            'gangguan'      => $post('gangguan'),
            'tindakan'      => $post('tindakan'),
            'next_action'   => $post('next_action'),
            'pic'           => $post('pic'),
        ];
    }

    private function backToList($reportId, string $msg)
    {
        return redirect()->to(site_url('HistoryReport/gangguan/' . $reportId))->with('success', $msg);
    }

    // Simpan data baru (POST HistoryReport/gangguan/save)
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

        $this->gangguanModel->insert($fields + ['report_id' => $reportId]);

        return $this->backToList($reportId, 'Data gangguan berhasil ditambahkan.');
    }

    // Update data (POST HistoryReport/gangguan/update)
    public function update()
    {
        $id  = $this->request->getPost('id');
        $row = $this->gangguanModel->find($id);

        if (! $row) {
            return redirect()->to(site_url('HistoryReport'))->with('error', 'Data gangguan tidak ditemukan.');
        }

        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->gangguanModel->update($id, $fields);

        return $this->backToList($row['report_id'], 'Data gangguan berhasil diperbarui.');
    }

    // Hapus data
    public function delete($id)
    {
        $row = $this->gangguanModel->find($id);

        if (! $row) {
            return redirect()->to(site_url('HistoryReport'))->with('error', 'Data gangguan tidak ditemukan.');
        }

        $this->gangguanModel->delete($id);

        return $this->backToList($row['report_id'], 'Data gangguan berhasil dihapus.');
    }
}
