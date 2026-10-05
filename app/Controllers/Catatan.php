<?php

namespace App\Controllers;

use App\Models\CatatanModel;
use App\Models\GangguanModel;
use App\Models\HistoryReportModel;

// Data catatan per History Report (tab Catatan di halaman HistoryReport/gangguan/{id})
class Catatan extends BaseController
{
    protected $catatanModel;
    protected $reportModel;

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->catatanModel = new CatatanModel();
        $this->reportModel  = new HistoryReportModel();
    }

    private function rules(): array
    {
        // Priority sama dengan data gangguan
        return [
            'grup'     => 'required|in_list[' . implode(',', CatatanModel::GRUP) . ']',
            'kategori' => 'required|in_list[' . implode(',', CatatanModel::KATEGORI) . ']',
            'priority' => 'required|in_list[' . implode(',', GangguanModel::PRIORITY) . ']',
            'status'   => 'required|in_list[' . implode(',', CatatanModel::STATUS) . ']',
            'judul'    => 'required|max_length[255]',
            'catatan'  => 'required',
        ];
    }

    private function collectFields(): array
    {
        $post = fn ($k) => trim((string) $this->request->getPost($k));

        // Poin catatan dari input catatan[] (tombol "+ Tambah Catatan"), yang kosong dibuang
        $poin = array_values(array_filter(
            array_map('trim', (array) $this->request->getPost('catatan')),
            static fn ($v) => $v !== ''
        ));

        return [
            'grup'     => $post('grup'),
            'kategori' => $post('kategori'),
            'priority' => $post('priority'),
            'status'   => $post('status'),
            'judul'    => $post('judul'),
            'catatan'  => $poin ? json_encode($poin, JSON_UNESCAPED_UNICODE) : '',
        ];
    }

    private function backToList($reportId, string $msg)
    {
        return redirect()->to(site_url('HistoryReport/gangguan/' . $reportId) . '?tab=catatan')->with('success', $msg);
    }

    // Simpan data baru (POST HistoryReport/catatan/save)
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

        $this->catatanModel->insert($fields + ['report_id' => $reportId]);

        return $this->backToList($reportId, 'Data catatan berhasil ditambahkan.');
    }

    // Update data (POST HistoryReport/catatan/update)
    public function update()
    {
        $id  = $this->request->getPost('id');
        $row = $this->catatanModel->find($id);

        if (! $row) {
            return redirect()->to(site_url('HistoryReport'))->with('error', 'Data catatan tidak ditemukan.');
        }

        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->catatanModel->update($id, $fields);

        return $this->backToList($row['report_id'], 'Data catatan berhasil diperbarui.');
    }

    // Hapus data
    public function delete($id)
    {
        $row = $this->catatanModel->find($id);

        if (! $row) {
            return redirect()->to(site_url('HistoryReport'))->with('error', 'Data catatan tidak ditemukan.');
        }

        $this->catatanModel->delete($id);

        return $this->backToList($row['report_id'], 'Data catatan berhasil dihapus.');
    }
}
