<?php

namespace App\Controllers;

use App\Libraries\ShiftReportText;
use App\Models\AktivasiModel;
use App\Models\CatatanModel;
use App\Models\FollowUpModel;
use App\Models\GangguanModel;
use App\Models\HistoryReportModel;
use App\Models\JadwalModel;
use App\Models\MaintenanceModel;
use App\Models\PengirimanModel;

class HistoryReport extends BaseController
{
    protected $reportModel;

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->reportModel = new HistoryReportModel();
    }

    // Halaman utama (view di folder HistoryReport/index.php)
    public function index()
    {
        $report = $this->reportModel
            ->orderBy('tanggal', 'DESC')
            ->orderBy('jam_mulai', 'DESC')
            ->findAll();

        $data = [
            'report' => $report,
            // Teks tombol Copy (format WhatsApp) per report
            'copyText'   => $this->copyTexts($report),
            // Form tambah & edit berupa pop up di halaman ini
            'picOptions' => $this->picOptions(),
            'grupCount'  => $this->grupCount(),
            'grupList'   => GangguanModel::GRUP,
            'kategoriOptions' => HistoryReportModel::KATEGORI,
            'dataCount'  => $this->dataCount(),
        ];

        return view('HistoryReport/index', $data);
    }

    // Halaman Detail (Shift Laporan) satu report, hanya baca (view di folder HistoryReport/laporan.php)
    public function laporan($id)
    {
        $report = $this->reportModel->find($id);
        if (! $report) {
            return redirect()->to(site_url('HistoryReport'))->with('error', 'Data report tidak ditemukan.');
        }

        $anak = $this->handoverData([$report['id']])[$report['id']] ?? [];

        return view('HistoryReport/laporan', [
            'report'   => $report,
            'anak'     => $anak,
            'copyText' => ShiftReportText::build($report, $anak),
        ]);
    }

    /**
     * Data Shift Handover (gangguan, follow up, pengiriman, maintenance, catatan)
     * dikelompokkan per report_id, urut sesuai waktu input.
     *
     * @return array<int, array<string, array>> [report_id => [jenis => rows]]
     */
    private function handoverData(array $ids): array
    {
        $db     = db_connect();
        $tables = [
            'gangguan'    => 'd_gangguan',
            'followup'    => 'd_follow_up',
            'pengiriman'  => 'd_pengiriman',
            'maintenance' => 'd_maintenance',
            'catatan'     => 'd_catatan',
            'aktivasi'    => 'd_aktivasi',
        ];

        $anak = [];
        foreach ($tables as $key => $table) {
            $rows = $db->table($table)->whereIn('report_id', $ids)->orderBy('id', 'ASC')->get()->getResultArray();
            foreach ($rows as $r) {
                $anak[$r['report_id']][$key][] = $r;
            }
        }

        return $anak;
    }

    /**
     * Teks "NOC SHIFT REPORT" (format WhatsApp) untuk setiap report, lengkap dengan
     * data gangguan, follow up, pengiriman, maintenance & catatan-nya.
     *
     * @return array<int, string> [report_id => teks]
     */
    private function copyTexts(array $reports): array
    {
        if (! $reports) {
            return [];
        }

        $anak  = $this->handoverData(array_column($reports, 'id'));
        $texts = [];
        foreach ($reports as $r) {
            $texts[$r['id']] = ShiftReportText::build($r, $anak[$r['id']] ?? []);
        }

        return $texts;
    }

    /**
     * Isi card rekap: jumlah data per jenis (gangguan, follow up, pengiriman, maintenance),
     * dihitung langsung dari database.
     *
     * @return array<string, int>
     */
    private function dataCount(): array
    {
        $db     = db_connect();
        $tables = [
            'gangguan'    => 'd_gangguan',
            'followup'    => 'd_follow_up',
            'pengiriman'  => 'd_pengiriman',
            'maintenance' => 'd_maintenance',
        ];

        $count = [];
        foreach ($tables as $key => $table) {
            $count[$key] = $db->table($table)->countAllResults();
        }

        return $count;
    }

    /**
     * Jumlah data Shift Handover per report & group, dihitung langsung dari database
     * (gangguan, follow up, pengiriman, maintenance, catatan) sehingga selalu ikut
     * bertambah/berkurang setiap data berhasil disimpan atau dihapus.
     *
     * @return array<int, array<string, int>> [report_id => [grup => jumlah]]
     */
    private function grupCount(): array
    {
        $db    = db_connect();
        $parts = [];
        foreach (['d_gangguan', 'd_follow_up', 'd_pengiriman', 'd_maintenance', 'd_catatan'] as $table) {
            $parts[] = $db->table($table)->select('report_id, grup')->getCompiledSelect();
        }

        $rows = $db->query(
            'SELECT report_id, grup, COUNT(*) AS jumlah FROM (' . implode(' UNION ALL ', $parts) . ') t GROUP BY report_id, grup'
        )->getResultArray();

        $count = [];
        foreach ($rows as $r) {
            $count[(int) $r['report_id']][$r['grup']] = (int) $r['jumlah'];
        }

        return $count;
    }

    // Pilihan dropdown PIC Shift: nama dari data shift (jadwal_noc), sama seperti Jadwal Piket
    private function picOptions(): array
    {
        return (new JadwalModel())->distinct()->select('nama')
            ->where('nama !=', '')
            ->orderBy('nama', 'ASC')
            ->findColumn('nama') ?? [];
    }

    private function rules(): array
    {
        return [
            'tanggal'     => 'required|valid_date[Y-m-d]',
            'shift'       => 'required|in_list[' . implode(',', array_keys(HistoryReportModel::SHIFT)) . ']',
            'kategori'    => 'required|in_list[' . implode(',', HistoryReportModel::KATEGORI) . ']',
            'pic_shift'   => 'required|max_length[255]',
            'jam_mulai'   => 'required|regex_match[/^\d{2}:\d{2}(:\d{2})?$/]',
            'jam_selesai' => 'required|regex_match[/^\d{2}:\d{2}(:\d{2})?$/]',
            'ringkasan'   => 'required',
        ];
    }

    private function collectFields(): array
    {
        // PIC: pilihan dropdown, atau teks "Isi Manual" bila opsi manual dipilih
        $pic = (string) $this->request->getPost('pic_shift');
        if ($pic === '__manual__') {
            $pic = (string) $this->request->getPost('pic_manual');
        }

        return [
            'tanggal'     => $this->request->getPost('tanggal'),
            'shift'       => $this->request->getPost('shift'),
            'kategori'    => $this->request->getPost('kategori'),
            'pic_shift'   => trim($pic),
            'jam_mulai'   => $this->request->getPost('jam_mulai'),
            'jam_selesai' => $this->request->getPost('jam_selesai'),
            'ringkasan'   => $this->request->getPost('ringkasan'),
        ];
    }

    // Simpan data baru (POST HistoryReport/save)
    public function save()
    {
        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        // Duplikat: report asal yang data Shift Handover-nya ikut disalin
        $sumberId = (int) $this->request->getPost('duplikat_dari');
        if ($sumberId && ! $this->reportModel->find($sumberId)) {
            return redirect()->to(site_url('HistoryReport'))->with('error', 'Data report asal duplikat tidak ditemukan.');
        }

        $db = db_connect();
        $db->transStart();

        $newId = $this->reportModel->insert($fields);
        if ($sumberId && $newId) {
            $this->duplikatHandover($sumberId, (int) $newId);
        }

        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Data report gagal disimpan.');
        }

        return redirect()->to(site_url('HistoryReport'))
            ->with('success', $sumberId
                ? 'Data report berhasil diduplikat beserta gangguan, follow up, pengiriman, maintenance & catatan.'
                : 'Data report berhasil ditambahkan.');
    }

    /**
     * Salin semua data Shift Handover (gangguan, follow up, pengiriman, maintenance, catatan)
     * milik report $dariId ke report baru $keId. Lewat model agar tetap tercatat di history.
     */
    private function duplikatHandover(int $dariId, int $keId): void
    {
        $models = [
            new GangguanModel(),
            new FollowUpModel(),
            new PengirimanModel(),
            new MaintenanceModel(),
            new CatatanModel(),
            new AktivasiModel(),
        ];

        foreach ($models as $model) {
            $rows = $model->where('report_id', $dariId)->orderBy('id', 'ASC')->findAll();
            foreach ($rows as $row) {
                unset($row['id'], $row['created_at']);
                $row['report_id'] = $keId;
                $model->insert($row);
            }
        }
    }

    // Update data (POST HistoryReport/update)
    public function update()
    {
        $id = $this->request->getPost('id');

        if (! $this->reportModel->find($id)) {
            return redirect()->to(site_url('HistoryReport'))->with('error', 'Data report tidak ditemukan.');
        }

        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->reportModel->update($id, $fields);

        return redirect()->to(site_url('HistoryReport'))
            ->with('success', 'Data report berhasil diperbarui.');
    }

    // Hapus data
    public function delete($id)
    {
        $this->reportModel->delete($id);

        return redirect()->to(site_url('HistoryReport'))
            ->with('success', 'Data report berhasil dihapus.');
    }
}
