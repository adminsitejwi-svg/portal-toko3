<?php

namespace App\Controllers;

use App\Models\JadwalModel;
use App\Models\RFOModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class RFO extends BaseController
{
    protected $rfoModel;

    public const STATUS_LIST = ['Resolved', 'Monitoring', 'On Progress', 'Pending'];

    public function __construct()
    {
        // RFO ID memakai tanggal lokal (WIB), bukan UTC
        date_default_timezone_set('Asia/Jakarta');
        $this->rfoModel = new RFOModel();
    }

    // Halaman utama (view di folder RFO/index.php)
    public function index()
    {
        $data = [
            'rfo'        => $this->rfoModel->orderBy('id', 'DESC')->findAll(),
            'perusahaan' => array_keys(RFOModel::PERUSAHAAN), // opsi filter
        ];

        return view('RFO/index', $data);
    }

    // Halaman form tambah RFO
    // View: app/Views/RFO/FormRFO.php
    public function create()
    {
        return view('RFO/FormRFO', [
            'nextRfoId'  => $this->rfoModel->generateRfoId(),
            'perusahaan' => RFOModel::PERUSAHAAN,
            'petugas'    => $this->rfoModel->namaPetugas(),
        ]);
    }

    // Halaman form edit (tampilan & fitur sama dengan form tambah)
    // View: app/Views/RFO/EditFormRFO.php
    public function edit($id)
    {
        $rfo = $this->rfoModel->find($id);

        if (! $rfo) {
            return redirect()->to(site_url('RFO'))->with('error', 'Data RFO tidak ditemukan.');
        }

        return view('RFO/EditFormRFO', [
            'rfo'        => $rfo,
            'perusahaan' => RFOModel::PERUSAHAAN,
            'petugas'    => $this->rfoModel->namaPetugas(),
        ]);
    }

    // Halaman lihat: surat RFO siap cetak
    // View: app/Views/RFO/ViewRFO.php
    public function view($id)
    {
        $rfo = $this->rfoModel->find($id);

        if (! $rfo) {
            return redirect()->to(site_url('RFO'))->with('error', 'Data RFO tidak ditemukan.');
        }

        return view('RFO/ViewRFO', [
            'rfo'     => $rfo,
            'namaTtd' => RFOModel::NAMA_TTD[$rfo['perusahaan']] ?? $rfo['perusahaan'],
            // Nama petugas: pilihan pada form; RFO lama (kosong) memakai data shift pada tanggal gangguan
            'petugas' => ! empty($rfo['petugas']) ? [$rfo['petugas']] : array_column(
                (new JadwalModel())->where('tanggal', date('Y-m-d', strtotime($rfo['waktu_gangguan'])))
                    ->where('nama !=', '')
                    ->orderBy('shift', 'ASC')
                    ->findAll(),
                'nama'
            ),
            'pembuka' => RFOModel::SURAT_PEMBUKA,
            'penutup' => sprintf(
                RFOModel::SURAT_PENUTUP,
                RFOModel::NOMOR_WA[$rfo['perusahaan']] ?? RFOModel::NOMOR_WA['PT JURAGAN WIFI INDONESIA'],
                RFOModel::EMAIL_NOC[$rfo['perusahaan']] ?? RFOModel::EMAIL_NOC['PT JURAGAN WIFI INDONESIA']
            ),
        ]);
    }

    // Aturan validasi bersama untuk save & update
    private function rules(): array
    {
        return [
            'perusahaan'     => 'required|in_list[' . implode(',', array_keys(RFOModel::PERUSAHAAN)) . ']',
            'waktu_gangguan' => 'required|valid_date[Y-m-d\TH:i]',
            'waktu_selesai'  => 'permit_empty|valid_date[Y-m-d\TH:i]',
            'penyebab'       => 'required',
            'impact'         => 'required',
            'action'         => 'required',
            'status'         => 'required|in_list[' . implode(',', self::STATUS_LIST) . ']',
            'petugas'        => 'required|in_list[' . implode(',', $this->rfoModel->namaPetugas()) . ']',
        ];
    }

    // Ambil field dari request, format datetime-local -> Y-m-d H:i:s
    private function collectFields(): array
    {
        $toDb = static function ($v) {
            $v = trim((string) $v);
            return $v === '' ? null : date('Y-m-d H:i:s', strtotime($v));
        };

        $perusahaan = $this->request->getPost('perusahaan');

        return [
            'perusahaan'     => $perusahaan,
            // Logo selalu mengikuti logo bawaan perusahaan yang dipilih
            'logo'           => RFOModel::PERUSAHAAN[$perusahaan] ?? null,
            'waktu_gangguan' => $toDb($this->request->getPost('waktu_gangguan')),
            'waktu_selesai'  => $toDb($this->request->getPost('waktu_selesai')),
            'penyebab'       => $this->request->getPost('penyebab'),
            'impact'         => $this->request->getPost('impact'),
            'action'         => $this->request->getPost('action'),
            'status'         => $this->request->getPost('status'),
            'petugas'        => $this->request->getPost('petugas'),
        ];
    }

    // Waktu selesai tidak boleh lebih awal dari waktu gangguan
    private function invalidRange(array $fields): bool
    {
        return $fields['waktu_selesai'] !== null
            && strtotime($fields['waktu_selesai']) < strtotime($fields['waktu_gangguan']);
    }

    // Simpan data baru (POST RFO/save)
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
                ->with('error', 'Waktu selesai gangguan tidak boleh lebih awal dari waktu gangguan.');
        }

        // RFO ID dibuat di server; ulangi bila bentrok dengan input bersamaan
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $fields['rfo_id'] = $this->rfoModel->generateRfoId();
            try {
                $this->rfoModel->insert($fields);
                break;
            } catch (DatabaseException $e) {
                if ($attempt === 2 || stripos($e->getMessage(), 'Duplicate') === false) {
                    throw $e;
                }
            }
        }

        return redirect()->to(site_url('RFO'))
            ->with('success', 'Data RFO ' . $fields['rfo_id'] . ' berhasil ditambahkan.');
    }

    // Update data (dari modal Edit -> POST RFO/update)
    public function update()
    {
        $id  = $this->request->getPost('id');
        $old = $this->rfoModel->find($id);

        if (! $old) {
            return redirect()->to(site_url('RFO'))->with('error', 'Data RFO tidak ditemukan.');
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
                ->with('error', 'Waktu selesai gangguan tidak boleh lebih awal dari waktu gangguan.');
        }

        $this->rfoModel->update($id, $fields);

        return redirect()->to(site_url('RFO'))
            ->with('success', 'Data RFO ' . $old['rfo_id'] . ' berhasil diperbarui.');
    }

    // Hapus data
    public function delete($id)
    {
        $this->rfoModel->delete($id);

        return redirect()->to(site_url('RFO'))
            ->with('success', 'Data RFO berhasil dihapus.');
    }
}
