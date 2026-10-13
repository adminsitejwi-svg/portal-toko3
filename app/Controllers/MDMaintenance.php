<?php

namespace App\Controllers;

use App\Libraries\DaftarEmail;
use App\Libraries\RfoPdf;
use App\Models\MaintenanceEmailLogModel;
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
        // Status kirim email terakhir per Maintenance (md_maintenance.id => baris log terbaru)
        $logModel = new MaintenanceEmailLogModel();
        $terakhir = $logModel->builder()->select('MAX(id)', false)->groupBy('mt_id')->getCompiledSelect();
        $statusEmail = array_column(
            $logModel->where('id IN (' . $terakhir . ')', null, false)->findAll(),
            null,
            'mt_id'
        );

        return view('MDMaintenance/index', [
            'maintenance' => $this->mtModel->orderBy('id', 'DESC')->findAll(),
            'perusahaan'  => array_keys(MDMaintenanceModel::PERUSAHAAN), // opsi filter
            'statusEmail' => $statusEmail,
            'akunEmail'   => config('RfoEmail')->akun, // dropdown "Dari" pada form kirim email
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

        return view('MDMaintenance/ViewMaintenance', $this->suratData($mt));
    }

    // Data surat Maintenance yang dipakai halaman View, PDF dan email
    private function suratData(array $mt): array
    {
        $default  = 'PT JURAGAN WIFI INDONESIA';
        $nomorWa  = MDMaintenanceModel::NOMOR_WA[$mt['perusahaan']] ?? MDMaintenanceModel::NOMOR_WA[$default];
        $emailNoc = MDMaintenanceModel::EMAIL_NOC[$mt['perusahaan']] ?? MDMaintenanceModel::EMAIL_NOC[$default];

        return [
            'mt'       => $mt,
            'namaTtd'  => MDMaintenanceModel::NAMA_TTD[$mt['perusahaan']] ?? $mt['perusahaan'],
            'warnaKop' => MDMaintenanceModel::WARNA_KOP[$mt['perusahaan']] ?? MDMaintenanceModel::WARNA_KOP[$default],
            'petugas'  => ! empty($mt['nama']) ? [$mt['nama']] : [],
            // Kontak NOC sesuai perusahaan (dipakai juga di body email)
            'nomorWa'  => $nomorWa,
            'emailNoc' => $emailNoc,
            'pembuka'  => MDMaintenanceModel::SURAT_PEMBUKA,
            'penutup'  => sprintf(MDMaintenanceModel::SURAT_PENUTUP, $nomorWa, $emailNoc),
        ];
    }

    // Halaman riwayat pengiriman email Maintenance
    // View: app/Views/MDMaintenance/EmailLog.php
    public function emailLog()
    {
        // Kolom body (HTML) tidak ikut dimuat; dibuka per baris lewat MDMaintenance/email-log/body/{id}
        $log = (new MaintenanceEmailLogModel())
            ->select('l.id, l.mt_id, l.dari, l.kepada, l.cc, l.subjek, l.lampiran, l.status, l.pesan_error, l.dikirim_oleh, l.created_at, m.maintenance_id AS kode_mt, m.perusahaan')
            ->from('md_maintenance_email_log l', true)
            ->join('md_maintenance m', 'm.id = l.mt_id', 'left')
            ->orderBy('l.id', 'DESC')
            ->findAll();

        return view('MDMaintenance/EmailLog', ['log' => $log]);
    }

    // Body email yang tersimpan di riwayat (GET MDMaintenance/email-log/body/{id}); hanya tampilan, tidak bisa diubah
    public function emailLogBody($id)
    {
        $log  = (new MaintenanceEmailLogModel())->find($id);
        $body = $log['body'] ?? null;

        if ($log && empty($body) && ($mt = $this->mtModel->find($log['mt_id']))) {
            $body = view('MDMaintenance/EmailMaintenance', $this->suratData($mt));
        }

        if (empty($body)) {
            return $this->response->setStatusCode(404)->setBody('Body email tidak tersedia.');
        }

        // CSP sandbox: body ditampilkan tanpa menjalankan script
        return $this->response->setHeader('Content-Security-Policy', 'sandbox')->setBody($body);
    }

    // PDF surat Maintenance (GET MDMaintenance/pdf/{id}) untuk dicek sebelum dikirim; terkunci (tidak bisa diubah)
    public function pdf($id)
    {
        $mt = $this->mtModel->find($id);

        if (! $mt) {
            return redirect()->to(site_url('MDMaintenance'))->with('error', 'Data Maintenance tidak ditemukan.');
        }

        return $this->response
            ->setContentType('application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . RfoPdf::namaFileId($mt['maintenance_id']) . '"')
            ->setBody((new RfoPdf())->buat($this->suratData($mt), 'MDMaintenance/PdfMaintenance'));
    }

    // Preview body email (GET MDMaintenance/email-preview/{id}) untuk form kirim email; hanya tampilan, tidak bisa diubah
    public function emailPreview($id)
    {
        $mt = $this->mtModel->find($id);

        if (! $mt) {
            return $this->response->setStatusCode(404)->setBody('Data Maintenance tidak ditemukan.');
        }

        return view('MDMaintenance/EmailMaintenance', $this->suratData($mt));
    }

    // Kirim surat Maintenance lewat email (POST MDMaintenance/email); lampiran = PDF surat yang dibuat otomatis
    public function email()
    {
        $ids    = array_filter(array_map('intval', (array) $this->request->getPost('ids')));
        $kepada = DaftarEmail::parse((string) $this->request->getPost('kepada'));
        $cc     = DaftarEmail::parse((string) $this->request->getPost('cc'));
        $subjek = trim((string) $this->request->getPost('subjek'));
        $dari   = (string) $this->request->getPost('dari');
        $balik  = redirect()->to(site_url('MDMaintenance'));

        // Akun pengirim harus salah satu dari dropdown dan sudah punya password di .env
        $akunEmail = config('RfoEmail');
        $passDari  = isset($akunEmail->akun[$dari]) ? $akunEmail->password($dari) : null;
        if ($passDari === null) {
            return $balik->with('error', 'Akun pengirim (Dari) tidak valid atau belum diatur.');
        }

        if ($ids === []) {
            return $balik->with('error', 'Pilih minimal satu data Maintenance untuk dikirim.');
        }
        if ($kepada['valid'] === []) {
            return $balik->with('error', 'Alamat e-mail Kepada wajib diisi dengan format nama@domain.com.');
        }
        $salah = array_merge($kepada['invalid'], $cc['invalid']);
        if ($salah !== []) {
            return $balik->with('error', 'Alamat e-mail tidak valid: ' . esc(implode(', ', $salah)) . '. Gunakan format nama@domain.com.');
        }
        $kepada = $kepada['valid'];
        $cc     = $cc['valid'];

        $mtList   = $this->mtModel->whereIn('id', $ids)->orderBy('id', 'ASC')->findAll();
        $logModel = new MaintenanceEmailLogModel();
        // Mailer dengan akun pengirim terpilih (server SMTP sama, hanya login yang berbeda)
        $mailer = service('email', array_merge(get_object_vars(config('Email')), [
            'fromEmail' => $dari,
            'fromName'  => $akunEmail->akun[$dari],
            'SMTPUser'  => $dari,
            'SMTPPass'  => $passDari,
        ]), false);
        $terkirim = [];
        $gagal    = [];

        foreach ($mtList as $mt) {
            // Subjek kosong: otomatis per Maintenance
            $judul = $subjek !== '' ? $subjek : 'Maintenance ' . $mt['maintenance_id'] . ' - ' . (MDMaintenanceModel::NAMA_TTD[$mt['perusahaan']] ?? $mt['perusahaan']);

            $mailer->clear(true);
            $mailer->setFrom($dari, $akunEmail->akun[$dari]);
            $mailer->setTo($kepada);
            if ($cc !== []) {
                $mailer->setCC($cc);
            }
            $mailer->setSubject($judul);

            // Lampiran: PDF surat Maintenance dari data yang sama dengan halaman View
            // (attach() dengan $mime terisi menerima ISI file, bukan path)
            $data    = $this->suratData($mt);
            $namaPdf = RfoPdf::namaFileId($mt['maintenance_id']);
            $mailer->attach((new RfoPdf())->buat($data, 'MDMaintenance/PdfMaintenance'), 'attachment', $namaPdf, 'application/pdf');
            $body = view('MDMaintenance/EmailMaintenance', $data);
            $mailer->setMessage($body);

            $ok = $mailer->send(false);

            $logModel->insert([
                'mt_id'        => $mt['id'],
                'dari'         => $dari,
                'kepada'       => implode(', ', $kepada),
                'cc'           => $cc !== [] ? implode(', ', $cc) : null,
                'subjek'       => $judul,
                'lampiran'     => $namaPdf,
                'body'         => $body,
                'status'       => $ok ? 'Terkirim' : 'Gagal',
                'pesan_error'  => $ok ? null : strip_tags($mailer->printDebugger(['headers'])),
                'dikirim_oleh' => session()->get('username'),
            ]);

            if ($ok) {
                $terkirim[] = $mt['maintenance_id'];
            } else {
                $gagal[] = $mt['maintenance_id'];
            }
        }

        if ($gagal !== []) {
            return $balik->with('error', 'Gagal mengirim email Maintenance ' . esc(implode(', ', $gagal)) . '.'
                . ($terkirim !== [] ? ' Terkirim: ' . esc(implode(', ', $terkirim)) . '.' : '')
                . ' Detail di Riwayat Email.');
        }

        return $balik->with('success', 'Email Maintenance ' . esc(implode(', ', $terkirim)) . ' berhasil dikirim ke ' . esc(implode(', ', $kepada)) . '.');
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
