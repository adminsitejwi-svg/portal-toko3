<?php

namespace App\Controllers;

use App\Models\MDMaintenanceModel;
use App\Models\RFOModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class MDMaintenance extends BaseController
{
    protected $mtModel;

    public function __construct()
    {
        // Maintenance ID memakai tanggal lokal (WIB), bukan UTC
        date_default_timezone_set('Asia/Jakarta');
        $this->mtModel = new MDMaintenanceModel();
    }

    // Halaman utama (View/MDMaintenance/index.php)
    public function index()
    {
        return view('MDMaintenance/index', [
            'maintenance' => $this->mtModel->orderBy('id', 'DESC')->findAll(),
            'perusahaan'  => array_keys(MDMaintenanceModel::PERUSAHAAN), // opsi filter
        ]);
    }

    // Halaman form tambah
    public function create()
    {
        return view('MDMaintenance/FormMaintenance', [
            'nextId'     => $this->mtModel->generateMaintenanceId(),
            'perusahaan' => MDMaintenanceModel::PERUSAHAAN,
            'emailNoc'   => MDMaintenanceModel::EMAIL_NOC,
            'petugas'    => (new RFOModel())->namaPetugas(),
        ]);
    }

    // Halaman form edit (tampilan & fitur sama dengan form tambah)
    public function edit($id)
    {
        $mt = $this->mtModel->find($id);

        if (! $mt) {
            return redirect()->to(site_url('MDMaintenance'))->with('error', 'Data Maintenance tidak ditemukan.');
        }

        return view('MDMaintenance/EditFormMaintenance', [
            'mt'         => $mt,
            'perusahaan' => MDMaintenanceModel::PERUSAHAAN,
            'emailNoc'   => MDMaintenanceModel::EMAIL_NOC,
            'petugas'    => (new RFOModel())->namaPetugas(),
        ]);
    }

    // Halaman lihat: surat Maintenance siap cetak
    public function view($id)
    {
        $mt = $this->mtModel->find($id);

        if (! $mt) {
            return redirect()->to(site_url('MDMaintenance'))->with('error', 'Data Maintenance tidak ditemukan.');
        }

        return view('MDMaintenance/ViewMaintenance', [
            'mt'      => $mt,
            'namaTtd' => MDMaintenanceModel::NAMA_TTD[$mt['perusahaan']] ?? $mt['perusahaan'],
            'pembuka' => MDMaintenanceModel::SURAT_PEMBUKA,
            'penutup' => sprintf(
                MDMaintenanceModel::SURAT_PENUTUP,
                MDMaintenanceModel::EMAIL_NOC[$mt['perusahaan']] ?? MDMaintenanceModel::EMAIL_NOC['PT JURAGAN WIFI INDONESIA']
            ),
        ]);
    }

    // Aturan validasi bersama untuk save & update
    private function rules(): array
    {
        return [
            'perusahaan'    => 'required|in_list[' . implode(',', array_keys(MDMaintenanceModel::PERUSAHAAN)) . ']',
            'tanggal'       => 'required|valid_date[Y-m-d]',
            'waktu_mulai'   => 'required|regex_match[/^\d{2}:\d{2}$/]',
            'waktu_selesai' => 'required|regex_match[/^\d{2}:\d{2}$/]',
            'estimasi'      => 'required|max_length[150]',
            'kegiatan'      => 'required',
            'impact'        => 'required',
            'nama'          => 'required|in_list[' . implode(',', (new RFOModel())->namaPetugas()) . ']',
        ];
    }

    // Ambil field dari request
    private function collectFields(): array
    {
        $perusahaan = $this->request->getPost('perusahaan');

        return [
            'perusahaan'    => $perusahaan,
            // Logo selalu mengikuti logo bawaan perusahaan yang dipilih
            'logo'          => MDMaintenanceModel::PERUSAHAAN[$perusahaan] ?? null,
            'tanggal'       => $this->request->getPost('tanggal'),
            'waktu_mulai'   => $this->request->getPost('waktu_mulai') . ':00',
            'waktu_selesai' => $this->request->getPost('waktu_selesai') . ':00',
            'estimasi'      => trim((string) $this->request->getPost('estimasi')),
            'kegiatan'      => $this->request->getPost('kegiatan'),
            'impact'        => $this->request->getPost('impact'),
            'nama'          => $this->request->getPost('nama'),
            // E-mail otomatis mengikuti perusahaan (sama dengan e-mail NOC pada RFO)
            'email'         => MDMaintenanceModel::EMAIL_NOC[$perusahaan] ?? null,
        ];
    }

    // Waktu selesai tidak boleh lebih awal dari waktu awal
    private function invalidRange(array $fields): bool
    {
        return $fields['waktu_selesai'] < $fields['waktu_mulai'];
    }

    // Simpan data baru (POST MDMaintenance/save)
    public function save()
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $fields = $this->collectFields();

        if ($this->invalidRange($fields)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Waktu selesai maintenance tidak boleh lebih awal dari waktu awal maintenance.');
        }

        // Maintenance ID dibuat di server; ulangi bila bentrok dengan input bersamaan
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $fields['maintenance_id'] = $this->mtModel->generateMaintenanceId();
            try {
                $this->mtModel->insert($fields);
                break;
            } catch (DatabaseException $e) {
                if ($attempt === 2 || stripos($e->getMessage(), 'Duplicate') === false) {
                    throw $e;
                }
            }
        }

        return redirect()->to(site_url('MDMaintenance'))
            ->with('success', 'Data Maintenance ' . $fields['maintenance_id'] . ' berhasil ditambahkan.');
    }

    // Update data (POST MDMaintenance/update)
    public function update()
    {
        $id  = $this->request->getPost('id');
        $old = $this->mtModel->find($id);

        if (! $old) {
            return redirect()->to(site_url('MDMaintenance'))->with('error', 'Data Maintenance tidak ditemukan.');
        }

        if (! $this->validate($this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $fields = $this->collectFields();

        if ($this->invalidRange($fields)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Waktu selesai maintenance tidak boleh lebih awal dari waktu awal maintenance.');
        }

        $this->mtModel->update($id, $fields);

        return redirect()->to(site_url('MDMaintenance'))
            ->with('success', 'Data Maintenance ' . $old['maintenance_id'] . ' berhasil diperbarui.');
    }

    // Hapus data
    public function delete($id)
    {
        $this->mtModel->delete($id);

        return redirect()->to(site_url('MDMaintenance'))
            ->with('success', 'Data Maintenance berhasil dihapus.');
    }
}
