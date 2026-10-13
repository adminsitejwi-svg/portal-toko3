<?php

namespace App\Controllers;

use App\Libraries\RfoPdf;
use App\Models\JadwalModel;
use App\Models\RFOEmailLogModel;
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
        // Status kirim email terakhir per RFO (d_rfo.id => baris log terbaru)
        $logModel = new RFOEmailLogModel();
        $terakhir = $logModel->builder()->select('MAX(id)', false)->groupBy('rfo_id')->getCompiledSelect();
        $statusEmail = array_column(
            $logModel->where('id IN (' . $terakhir . ')', null, false)->findAll(),
            null,
            'rfo_id'
        );

        $data = [
            'rfo'         => $this->rfoModel->orderBy('id', 'DESC')->findAll(),
            'perusahaan'  => array_keys(RFOModel::PERUSAHAAN), // opsi filter
            'statusEmail' => $statusEmail,
            'akunEmail'   => config('RfoEmail')->akun, // dropdown "Dari" pada form kirim email
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

        return view('RFO/ViewRFO', $this->suratData($rfo));
    }

    // Data surat RFO yang dipakai halaman View dan email
    private function suratData(array $rfo): array
    {
        return [
            'rfo'     => $rfo,
            'namaTtd' => RFOModel::NAMA_TTD[$rfo['perusahaan']] ?? $rfo['perusahaan'],
            'warnaKop' => RFOModel::WARNA_KOP[$rfo['perusahaan']] ?? RFOModel::WARNA_KOP['PT JURAGAN WIFI INDONESIA'],
            // Nama petugas: pilihan pada form; RFO lama (kosong) memakai data shift pada tanggal gangguan
            'petugas' => ! empty($rfo['petugas']) ? [$rfo['petugas']] : array_column(
                (new JadwalModel())->where('tanggal', date('Y-m-d', strtotime($rfo['waktu_gangguan'])))
                    ->where('nama !=', '')
                    ->orderBy('shift', 'ASC')
                    ->findAll(),
                'nama'
            ),
            // Kontak NOC sesuai perusahaan (dipakai juga di body email)
            'nomorWa'  => RFOModel::NOMOR_WA[$rfo['perusahaan']] ?? RFOModel::NOMOR_WA['PT JURAGAN WIFI INDONESIA'],
            'emailNoc' => RFOModel::EMAIL_NOC[$rfo['perusahaan']] ?? RFOModel::EMAIL_NOC['PT JURAGAN WIFI INDONESIA'],
            'pembuka' => RFOModel::SURAT_PEMBUKA,
            'penutup' => sprintf(
                RFOModel::SURAT_PENUTUP,
                RFOModel::NOMOR_WA[$rfo['perusahaan']] ?? RFOModel::NOMOR_WA['PT JURAGAN WIFI INDONESIA'],
                RFOModel::EMAIL_NOC[$rfo['perusahaan']] ?? RFOModel::EMAIL_NOC['PT JURAGAN WIFI INDONESIA']
            ),
        ];
    }

    /**
     * Pecah daftar e-mail (pisah koma / titik koma / baris baru).
     * Menerima "nama@domain.com" maupun "Nama <nama@domain.com>" (hasil copy dari webmail),
     * serta membuang spasi tak terlihat hasil copy-paste.
     * Hasil: ['valid' => [...alamat], 'invalid' => [...teks yang bukan e-mail]].
     */
    private function parseEmails(string $raw): array
    {
        // Spasi non-breaking / zero-width -> spasi biasa
        $raw    = preg_replace('/[\x{00A0}\x{200B}-\x{200D}\x{2060}\x{FEFF}]/u', ' ', $raw) ?? $raw;
        $hasil  = ['valid' => [], 'invalid' => []];

        foreach (preg_split('/[,;\r\n]+/', $raw) as $bagian) {
            $bagian = trim($bagian, " \t\"'");
            if ($bagian === '') {
                continue;
            }

            // Format "Nama <alamat>": ambil alamat di dalam < >
            if (preg_match('/<([^>]+)>/', $bagian, $m)) {
                $bagian = trim($m[1]);
            }

            // Beberapa alamat dipisah spasi dalam satu bagian
            foreach (preg_split('/\s+/', $bagian) as $addr) {
                if (filter_var($addr, FILTER_VALIDATE_EMAIL)) {
                    $hasil['valid'][] = strtolower($addr);
                } else {
                    $hasil['invalid'][] = $addr;
                }
            }
        }

        $hasil['valid'] = array_values(array_unique($hasil['valid']));

        return $hasil;
    }

    // Halaman riwayat pengiriman email RFO
    // View: app/Views/RFO/EmailLog.php
    public function emailLog()
    {
        // Kolom body (HTML) tidak ikut dimuat; dibuka per baris lewat RFO/email-log/body/{id}
        $log = (new RFOEmailLogModel())
            ->select('l.id, l.rfo_id, l.dari, l.kepada, l.cc, l.subjek, l.lampiran, l.status, l.pesan_error, l.dikirim_oleh, l.created_at, d_rfo.rfo_id AS kode_rfo, d_rfo.perusahaan')
            ->from('d_rfo_email_log l', true)
            ->join('d_rfo', 'd_rfo.id = l.rfo_id', 'left')
            ->orderBy('l.id', 'DESC')
            ->findAll();

        return view('RFO/EmailLog', ['log' => $log]);
    }

    // Body email yang tersimpan di riwayat (GET RFO/email-log/body/{id}); hanya tampilan, tidak bisa diubah.
    // Riwayat lama (sebelum body disimpan) memakai body yang dibuat ulang dari data RFO saat ini.
    public function emailLogBody($id)
    {
        $log  = (new RFOEmailLogModel())->find($id);
        $body = $log['body'] ?? null;

        if ($log && empty($body) && ($rfo = $this->rfoModel->find($log['rfo_id']))) {
            $body = view('RFO/EmailRFO', $this->suratData($rfo));
        }

        if (empty($body)) {
            return $this->response->setStatusCode(404)->setBody('Body email tidak tersedia.');
        }

        // CSP sandbox: body ditampilkan tanpa menjalankan script
        return $this->response->setHeader('Content-Security-Policy', 'sandbox')->setBody($body);
    }

    // PDF surat RFO (GET RFO/pdf/{id}) untuk dicek sebelum dikirim; tampil di browser, terkunci (tidak bisa diubah)
    public function pdf($id)
    {
        $rfo = $this->rfoModel->find($id);

        if (! $rfo) {
            return redirect()->to(site_url('RFO'))->with('error', 'Data RFO tidak ditemukan.');
        }

        return $this->response
            ->setContentType('application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . RfoPdf::namaFile($rfo) . '"')
            ->setBody((new RfoPdf())->buat($this->suratData($rfo)));
    }

    // Preview body email RFO (GET RFO/email-preview/{id}) untuk form kirim email; hanya tampilan, tidak bisa diubah
    public function emailPreview($id)
    {
        $rfo = $this->rfoModel->find($id);

        if (! $rfo) {
            return $this->response->setStatusCode(404)->setBody('Data RFO tidak ditemukan.');
        }

        return view('RFO/EmailRFO', $this->suratData($rfo));
    }

    // Kirim surat RFO lewat email (POST RFO/email); lampiran = PDF surat RFO yang dibuat otomatis.
    // Setiap RFO dikirim sebagai email terpisah lewat akun SMTP di .env (noc@jwi.id).
    public function email()
    {
        $ids    = array_filter(array_map('intval', (array) $this->request->getPost('ids')));
        $kepada = $this->parseEmails((string) $this->request->getPost('kepada'));
        $cc     = $this->parseEmails((string) $this->request->getPost('cc'));
        $subjek = trim((string) $this->request->getPost('subjek'));
        $dari   = (string) $this->request->getPost('dari');

        // Akun pengirim harus salah satu dari dropdown dan sudah punya password di .env
        $akunEmail = config('RfoEmail');
        $passDari  = isset($akunEmail->akun[$dari]) ? $akunEmail->password($dari) : null;
        if ($passDari === null) {
            return redirect()->to(site_url('RFO'))->with('error', 'Akun pengirim (Dari) tidak valid atau belum diatur.');
        }

        if ($ids === []) {
            return redirect()->to(site_url('RFO'))->with('error', 'Pilih minimal satu data RFO untuk dikirim.');
        }
        if ($kepada['valid'] === []) {
            return redirect()->to(site_url('RFO'))->with('error', 'Alamat e-mail Kepada wajib diisi dengan format nama@domain.com.');
        }
        $salah = array_merge($kepada['invalid'], $cc['invalid']);
        if ($salah !== []) {
            return redirect()->to(site_url('RFO'))->with('error', 'Alamat e-mail tidak valid: ' . esc(implode(', ', $salah)) . '. Gunakan format nama@domain.com.');
        }
        $kepada = $kepada['valid'];
        $cc     = $cc['valid'];

        $rfoList  = $this->rfoModel->whereIn('id', $ids)->orderBy('id', 'ASC')->findAll();
        $logModel = new RFOEmailLogModel();
        // Mailer dengan akun pengirim terpilih (server SMTP sama, hanya login yang berbeda)
        $mailer = service('email', array_merge(get_object_vars(config('Email')), [
            'fromEmail' => $dari,
            'fromName'  => $akunEmail->akun[$dari],
            'SMTPUser'  => $dari,
            'SMTPPass'  => $passDari,
        ]), false);
        $terkirim = [];
        $gagal    = [];

        foreach ($rfoList as $rfo) {
            // Subjek kosong: otomatis per RFO
            $judul = $subjek !== '' ? $subjek : 'RFO ' . $rfo['rfo_id'] . ' - ' . (RFOModel::NAMA_TTD[$rfo['perusahaan']] ?? $rfo['perusahaan']);

            $mailer->clear(true);
            $mailer->setFrom($dari, $akunEmail->akun[$dari]);
            $mailer->setTo($kepada);
            if ($cc !== []) {
                $mailer->setCC($cc);
            }
            $mailer->setSubject($judul);

            // Lampiran: PDF surat RFO dari data yang sama dengan halaman View
            // (attach() dengan $mime terisi menerima ISI file, bukan path)
            $data     = $this->suratData($rfo);
            $namaPdf  = RfoPdf::namaFile($rfo);
            $mailer->attach((new RfoPdf())->buat($data), 'attachment', $namaPdf, 'application/pdf');
            $body = view('RFO/EmailRFO', $data);
            $mailer->setMessage($body);

            $ok = $mailer->send(false);

            $logModel->insert([
                'rfo_id'       => $rfo['id'],
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
                $terkirim[] = $rfo['rfo_id'];
            } else {
                $gagal[] = $rfo['rfo_id'];
            }
        }

        if ($gagal !== []) {
            return redirect()->to(site_url('RFO'))->with('error', 'Gagal mengirim email RFO ' . esc(implode(', ', $gagal)) . '.'
                . ($terkirim !== [] ? ' Terkirim: ' . esc(implode(', ', $terkirim)) . '.' : '')
                . ' Detail di Riwayat Email.');
        }

        return redirect()->to(site_url('RFO'))
            ->with('success', 'Email RFO ' . esc(implode(', ', $terkirim)) . ' berhasil dikirim ke ' . esc(implode(', ', $kepada)) . '.');
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
