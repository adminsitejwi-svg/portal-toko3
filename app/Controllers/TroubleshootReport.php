<?php

namespace App\Controllers;

use App\Libraries\DaftarEmail;
use App\Libraries\SurveyReportPdf;
use App\Models\RFOModel;
use App\Models\TroubleshootReportEmailLogModel;
use App\Models\TroubleshootReportModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

// Halaman Troubleshoot Report (menu Report NOC > Troubleshoot Report). Tampilan & fitur sama dengan Survey Report:
// daftar + kartu statistik, form tambah/edit, View dokumen siap cetak, PDF terkunci,
// kirim email (akun "Dari" sama dengan RFO) beserta riwayatnya. Perusahaan & logo memakai data RFO.
// Struktur form: Troubleshooting Report Form Yesnet (1).docx (1. Data Pelanggan, 2. Keterangan Layanan Klien,
// 3. Deskripsi Pekerjaan (Trouble & Action), 4. Resume + Dibuat/Diketahui Oleh, 5. Lampiran).
class TroubleshootReport extends BaseController
{
    // Foto lampiran: maksimal jumlah, ukuran per file, dan tipe yang diterima
    public const MAKS_FOTO   = 10;
    public const MAKS_UKURAN = 5 * 1024 * 1024;
    private const TIPE_FOTO  = ['jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'webp' => 'image/webp'];

    protected $model;

    public function __construct()
    {
        // Troubleshoot ID memakai tanggal lokal (WIB), bukan UTC
        date_default_timezone_set('Asia/Jakarta');
        $this->model = new TroubleshootReportModel();
    }

    // Halaman utama (view TroubleshootReport/index.php)
    public function index()
    {
        // Status kirim email terakhir per troubleshoot report (d_troubleshoot_report.id => baris log terbaru)
        $logModel = new TroubleshootReportEmailLogModel();
        $terakhir = $logModel->builder()->select('MAX(id)', false)->groupBy('troubleshoot_report_id')->getCompiledSelect();
        $statusEmail = array_column(
            $logModel->where('id IN (' . $terakhir . ')', null, false)->findAll(),
            null,
            'troubleshoot_report_id'
        );

        return view('TroubleshootReport/index', [
            'laporan'     => $this->model->orderBy('id', 'DESC')->findAll(),
            'perusahaan'  => array_keys(RFOModel::PERUSAHAAN),
            'statusEmail' => $statusEmail,
            'akunEmail'   => config('RfoEmail')->akun, // dropdown "Dari" pada form kirim email
        ]);
    }

    // Form tambah (view TroubleshootReport/Form.php, dipakai juga untuk edit)
    public function create()
    {
        return view('TroubleshootReport/Form', [
            'sr'         => null,
            'nextId'     => $this->model->generateTroubleId(),
            'perusahaan' => RFOModel::PERUSAHAAN,
            'saran'      => $this->saran(),
        ]);
    }

    // Form edit
    public function edit($id)
    {
        $sr = $this->model->find($id);

        if (! $sr) {
            return redirect()->to(site_url('TroubleshootReport'))->with('error', 'Data Troubleshoot Report tidak ditemukan.');
        }

        return view('TroubleshootReport/Form', [
            'sr'         => $sr,
            'nextId'     => $sr['trouble_id'],
            'perusahaan' => RFOModel::PERUSAHAAN,
            'saran'      => $this->saran(),
        ]);
    }

    // Saran isian (datalist) dari data yang sudah ada
    private function saran(): array
    {
        $kolom = ['pelaksana', 'dibuat_oleh', 'diketahui_oleh'];
        $hasil = [];
        foreach ($kolom as $k) {
            $isi = $this->model->distinct()->select($k)->where($k . ' !=', '')->where($k . ' IS NOT NULL', null, false)->orderBy($k, 'ASC')->findColumn($k) ?? [];
            $hasil[$k] = array_values(array_unique(array_map('trim', $isi)));
        }

        return $hasil;
    }

    // Halaman View: dokumen Troubleshoot Report siap cetak (view TroubleshootReport/View.php)
    public function view($id)
    {
        $sr = $this->model->find($id);

        if (! $sr) {
            return redirect()->to(site_url('TroubleshootReport'))->with('error', 'Data Troubleshoot Report tidak ditemukan.');
        }

        $data = $this->dokumenData($sr);
        // logo dipotong dari ruang kosong (sama dengan PDF) agar tampil besar di kotak logo kop
        $data['logo']    = SurveyReportPdf::logoInfo($sr['logo'] ?? null);
        // foto: alamat gambar, keterangan, dan ukuran asli (untuk tinggi tampilan seperti PDF)
        $data['fotoSrc'] = array_map(function ($l) use ($sr) {
            $ukuran = @getimagesize(TroubleshootReportModel::folderFoto() . $l['file']) ?: [4, 3];

            return [
                'src' => site_url('TroubleshootReport/foto/' . $sr['id'] . '/' . rawurlencode($l['file'])),
                'ket' => $l['ket'],
                'w'   => (int) $ukuran[0],
                'h'   => (int) $ukuran[1],
            ];
        }, $this->fotoAda($sr));

        return view('TroubleshootReport/View', $data);
    }

    // Data dokumen yang dipakai halaman View, PDF, dan body email
    private function dokumenData(array $sr): array
    {
        $p = $sr['perusahaan'];

        return [
            'sr'       => $sr,
            'namaTtd'  => RFOModel::NAMA_TTD[$p] ?? $p,
            'warna'    => RFOModel::WARNA_KOP[$p] ?? RFOModel::WARNA_KOP['PT JURAGAN WIFI INDONESIA'],
            'nomorWa'  => RFOModel::NOMOR_WA[$p] ?? RFOModel::NOMOR_WA['PT JURAGAN WIFI INDONESIA'],
            'emailNoc' => RFOModel::EMAIL_NOC[$p] ?? RFOModel::EMAIL_NOC['PT JURAGAN WIFI INDONESIA'],
        ];
    }

    // PDF Troubleshoot Report (library PDF yang sama dengan Survey Report, view & folder foto sendiri)
    private function buatPdf(array $sr): string
    {
        return (new SurveyReportPdf())->buat($this->dokumenData($sr), $this->fotoAda($sr), 'TroubleshootReport/Pdf', TroubleshootReportModel::folderFoto());
    }

    // Foto lampiran (beserta keterangan) yang file-nya masih ada: [['file', 'ket'], ...]
    private function fotoAda(array $sr): array
    {
        return array_values(array_filter(
            TroubleshootReportModel::lampiran($sr),
            static fn ($l) => is_file(TroubleshootReportModel::folderFoto() . $l['file'])
        ));
    }

    // Tampilkan foto lampiran (GET TroubleshootReport/foto/{id}/{nama}); hanya foto milik data tersebut
    public function foto($id, $nama)
    {
        $sr   = $this->model->find($id);
        $nama = basename(rawurldecode((string) $nama));

        if (! $sr || ! in_array($nama, TroubleshootReportModel::foto($sr), true) || ! is_file(TroubleshootReportModel::folderFoto() . $nama)) {
            return $this->response->setStatusCode(404)->setBody('Foto tidak ditemukan.');
        }

        $ext = strtolower(pathinfo($nama, PATHINFO_EXTENSION));

        return $this->response
            ->setContentType(self::TIPE_FOTO[$ext] ?? 'application/octet-stream')
            ->setHeader('Cache-Control', 'private, max-age=86400')
            ->setBody((string) file_get_contents(TroubleshootReportModel::folderFoto() . $nama));
    }

    // PDF Troubleshoot Report (GET TroubleshootReport/pdf/{id}); tampil di browser, terkunci (tidak bisa diubah)
    public function pdf($id)
    {
        $sr = $this->model->find($id);

        if (! $sr) {
            return redirect()->to(site_url('TroubleshootReport'))->with('error', 'Data Troubleshoot Report tidak ditemukan.');
        }

        return $this->response
            ->setContentType('application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . SurveyReportPdf::namaFileId($sr['trouble_id']) . '"')
            ->setBody($this->buatPdf($sr));
    }

    // Preview body email (GET TroubleshootReport/email-preview/{id}) untuk form kirim email; hanya tampilan
    public function emailPreview($id)
    {
        $sr = $this->model->find($id);

        if (! $sr) {
            return $this->response->setStatusCode(404)->setBody('Data Troubleshoot Report tidak ditemukan.');
        }

        return view('TroubleshootReport/Email', $this->dokumenData($sr));
    }

    // Halaman riwayat pengiriman email (view TroubleshootReport/EmailLog.php)
    public function emailLog()
    {
        // Kolom body (HTML) tidak ikut dimuat; dibuka per baris lewat TroubleshootReport/email-log/body/{id}
        $log = (new TroubleshootReportEmailLogModel())
            ->select('l.id, l.troubleshoot_report_id, l.dari, l.kepada, l.cc, l.subjek, l.lampiran, l.status, l.pesan_error, l.dikirim_oleh, l.created_at, s.trouble_id AS kode_trouble, s.perusahaan, s.nama_pelanggan')
            ->from('d_troubleshoot_report_email_log l', true)
            ->join('d_troubleshoot_report s', 's.id = l.troubleshoot_report_id', 'left')
            ->orderBy('l.id', 'DESC')
            ->findAll();

        return view('TroubleshootReport/EmailLog', ['log' => $log]);
    }

    // Body email yang tersimpan di riwayat (GET TroubleshootReport/email-log/body/{id}); hanya tampilan
    public function emailLogBody($id)
    {
        $log  = (new TroubleshootReportEmailLogModel())->find($id);
        $body = $log['body'] ?? null;

        if (empty($body)) {
            return $this->response->setStatusCode(404)->setBody('Body email tidak tersedia.');
        }

        // CSP sandbox: body ditampilkan tanpa menjalankan script
        return $this->response->setHeader('Content-Security-Policy', 'sandbox')->setBody($body);
    }

    // Kirim Troubleshoot Report lewat email (POST TroubleshootReport/email); lampiran = PDF Troubleshoot Report
    public function email()
    {
        $ids    = array_filter(array_map('intval', (array) $this->request->getPost('ids')));
        $kepada = DaftarEmail::parse((string) $this->request->getPost('kepada'));
        $cc     = DaftarEmail::parse((string) $this->request->getPost('cc'));
        $subjek = trim((string) $this->request->getPost('subjek'));
        $dari   = (string) $this->request->getPost('dari');

        // Akun pengirim harus salah satu dari dropdown dan sudah punya password di .env
        $akunEmail = config('RfoEmail');
        $passDari  = isset($akunEmail->akun[$dari]) ? $akunEmail->password($dari) : null;
        if ($passDari === null) {
            return redirect()->to(site_url('TroubleshootReport'))->with('error', 'Akun pengirim (Dari) tidak valid atau belum diatur.');
        }

        if ($ids === []) {
            return redirect()->to(site_url('TroubleshootReport'))->with('error', 'Pilih minimal satu Troubleshoot Report untuk dikirim.');
        }
        if ($kepada['valid'] === []) {
            return redirect()->to(site_url('TroubleshootReport'))->with('error', 'Alamat e-mail Kepada wajib diisi dengan format nama@domain.com.');
        }
        $salah = array_merge($kepada['invalid'], $cc['invalid']);
        if ($salah !== []) {
            return redirect()->to(site_url('TroubleshootReport'))->with('error', 'Alamat e-mail tidak valid: ' . esc(implode(', ', $salah)) . '. Gunakan format nama@domain.com.');
        }
        $kepada = $kepada['valid'];
        $cc     = $cc['valid'];

        $list     = $this->model->whereIn('id', $ids)->orderBy('id', 'ASC')->findAll();
        $logModel = new TroubleshootReportEmailLogModel();
        // Mailer dengan akun pengirim terpilih (server SMTP sama, hanya login yang berbeda)
        $mailer = service('email', array_merge(get_object_vars(config('Email')), [
            'fromEmail' => $dari,
            'fromName'  => $akunEmail->akun[$dari],
            'SMTPUser'  => $dari,
            'SMTPPass'  => $passDari,
        ]), false);
        $terkirim = [];
        $gagal    = [];

        foreach ($list as $sr) {
            $data  = $this->dokumenData($sr);
            // Subjek kosong: otomatis per Troubleshoot Report
            $judul = $subjek !== '' ? $subjek : 'Troubleshoot Report ' . $sr['trouble_id'] . ' - ' . $sr['nama_pelanggan'];

            $mailer->clear(true);
            $mailer->setFrom($dari, $akunEmail->akun[$dari]);
            $mailer->setTo($kepada);
            if ($cc !== []) {
                $mailer->setCC($cc);
            }
            $mailer->setSubject($judul);

            // Lampiran: PDF Troubleshoot Report (attach() dengan $mime terisi menerima ISI file, bukan path)
            $namaPdf = SurveyReportPdf::namaFileId($sr['trouble_id']);
            $mailer->attach($this->buatPdf($sr), 'attachment', $namaPdf, 'application/pdf');
            $body = view('TroubleshootReport/Email', $data);
            $mailer->setMessage($body);

            $ok = $mailer->send(false);

            $logModel->insert([
                'troubleshoot_report_id' => $sr['id'],
                'dari'             => $dari,
                'kepada'           => implode(', ', $kepada),
                'cc'               => $cc !== [] ? implode(', ', $cc) : null,
                'subjek'           => $judul,
                'lampiran'         => $namaPdf,
                'body'             => $body,
                'status'           => $ok ? 'Terkirim' : 'Gagal',
                'pesan_error'      => $ok ? null : strip_tags($mailer->printDebugger(['headers'])),
                'dikirim_oleh'     => session()->get('username'),
            ]);

            if ($ok) {
                $terkirim[] = $sr['trouble_id'];
            } else {
                $gagal[] = $sr['trouble_id'];
            }
        }

        if ($gagal !== []) {
            return redirect()->to(site_url('TroubleshootReport'))->with('error', 'Gagal mengirim email Troubleshoot Report ' . esc(implode(', ', $gagal)) . '.'
                . ($terkirim !== [] ? ' Terkirim: ' . esc(implode(', ', $terkirim)) . '.' : '')
                . ' Detail di Riwayat Email.');
        }

        return redirect()->to(site_url('TroubleshootReport'))
            ->with('success', 'Email Troubleshoot Report ' . esc(implode(', ', $terkirim)) . ' berhasil dikirim ke ' . esc(implode(', ', $kepada)) . '.');
    }

    // ================= SIMPAN / UBAH / HAPUS =================

    // Pilihan dropdown + "Dll" (isian bebas): kembalikan nilai yang disimpan
    private function pilihan(string $kolom, array $opsi): string
    {
        $pilih = trim((string) $this->request->getPost($kolom));
        if ($pilih === 'Dll') {
            $lain = trim(preg_replace('/\s+/', ' ', (string) $this->request->getPost($kolom . '_lain')));

            return $lain !== '' ? $lain : 'Dll';
        }

        return in_array($pilih, $opsi, true) ? $pilih : '';
    }

    private function collectFields(): array
    {
        $satu = fn (string $k) => trim(preg_replace('/\s+/', ' ', (string) $this->request->getPost($k)));
        $teks = fn (string $k) => trim(preg_replace("/\r\n?/", "\n", (string) $this->request->getPost($k)));
        $tgl  = function (string $k) {
            $v = trim((string) $this->request->getPost($k));
            $d = \DateTime::createFromFormat('!Y-m-d', $v);

            return $d && $d->format('Y-m-d') === $v ? $v : null;
        };
        $opsional = fn ($v) => $v === '' ? null : $v;

        $perusahaan = (string) $this->request->getPost('perusahaan');

        return [
            'perusahaan'          => $perusahaan,
            // Logo selalu mengikuti logo bawaan perusahaan yang dipilih
            'logo'                => RFOModel::PERUSAHAAN[$perusahaan] ?? null,
            'nama_pelanggan'      => $satu('nama_pelanggan'),
            'tipe_bisnis'         => $this->pilihan('tipe_bisnis', TroubleshootReportModel::TIPE_BISNIS),
            'alamat'              => $teks('alamat'),
            'telepon'             => $opsional($satu('telepon')),
            'email_pelanggan'     => $opsional($satu('email_pelanggan')),
            'tgl_spk'             => $tgl('tgl_spk'),
            'no_spk'              => $opsional($satu('no_spk')),
            'tanggal_pelaksanaan' => $tgl('tanggal_pelaksanaan'),
            'pelaksana'           => $satu('pelaksana'),
            'jenis_layanan'       => $this->pilihan('jenis_layanan', TroubleshootReportModel::JENIS_LAYANAN),
            'catatan'             => $opsional($teks('catatan')),
            'trouble'             => $teks('trouble'),
            'action'              => $teks('action'),
            'resume'              => $opsional($teks('resume')),
            'dibuat_oleh'         => $opsional($satu('dibuat_oleh')),
            'diketahui_oleh'      => $opsional($satu('diketahui_oleh')),
        ];
    }


    // Validasi isian; kembalikan pesan error atau null
    private function validasi(array $f): ?string
    {
        $errors = [];

        if (! isset(RFOModel::PERUSAHAAN[$f['perusahaan']])) {
            $errors[] = 'Perusahaan wajib dipilih.';
        }
        $wajib = [
            'nama_pelanggan'      => 'Nama Pelanggan',
            'tipe_bisnis'         => 'Tipe Bisnis',
            'alamat'              => 'Alamat',
            'tanggal_pelaksanaan' => 'Tanggal Pelaksanaan',
            'pelaksana'           => 'Pelaksana',
            'jenis_layanan'       => 'Jenis Layanan',
            'trouble'             => 'Trouble',
            'action'              => 'Action',
        ];
        foreach ($wajib as $k => $label) {
            if ($f[$k] === null || $f[$k] === '') {
                $errors[] = $label . ' wajib diisi.';
            }
        }

        $maks = [
            'nama_pelanggan' => ['Nama Pelanggan', 200], 'tipe_bisnis' => ['Tipe Bisnis', 100], 'telepon' => ['Telepon/HP', 50],
            'email_pelanggan' => ['Email', 255], 'no_spk' => ['No. SPK', 100], 'pelaksana' => ['Pelaksana', 255],
            'jenis_layanan' => ['Jenis Layanan', 100],
            'dibuat_oleh' => ['Dibuat Oleh', 150], 'diketahui_oleh' => ['Diketahui Oleh', 150],
        ];
        foreach ($maks as $k => [$label, $n]) {
            if ($f[$k] !== null && mb_strlen($f[$k]) > $n) {
                $errors[] = $label . ' maksimal ' . $n . ' karakter.';
            }
        }

        if ($f['email_pelanggan'] !== null) {
            $email = DaftarEmail::parse($f['email_pelanggan']);
            if ($email['invalid'] !== [] || $email['valid'] === []) {
                $errors[] = 'Email pelanggan tidak valid. Gunakan format nama@domain.com (lebih dari satu dipisah koma).';
            }
        }
        if ($f['telepon'] !== null && ! preg_match('/^[0-9+()\-\s\/]+$/', $f['telepon'])) {
            $errors[] = 'Telepon/HP hanya boleh berisi angka, spasi, +, -, ( ) dan /.';
        }
        if (trim((string) $this->request->getPost('tgl_spk')) !== '' && $f['tgl_spk'] === null) {
            $errors[] = 'Tgl SPK tidak valid.';
        }

        return $errors ? implode(' ', $errors) : null;
    }

    // Keterangan foto (maks 255 karakter, satu baris)
    private static function ket($v): string
    {
        return mb_substr(trim(preg_replace('/\s+/', ' ', (string) $v)), 0, 255);
    }

    /**
     * Simpan foto baru dari input lampiran[] (keterangan di ket_baru[] dengan urutan sama);
     * kembalikan [[['file', 'ket'], ...], pesan error | null]. $sisa = jumlah foto yang masih boleh ditambah.
     */
    private function uploadFoto(int $sisa): array
    {
        $baru  = [];
        $files = array_filter(
            (array) $this->request->getFileMultiple('lampiran'),
            static fn ($f) => $f && $f->getError() !== UPLOAD_ERR_NO_FILE
        );

        if ($files === []) {
            return [[], null];
        }
        if (count($files) > $sisa) {
            return [[], 'Foto lampiran maksimal ' . self::MAKS_FOTO . ' foto per Troubleshoot Report.'];
        }

        foreach ($files as $file) {
            $ext = strtolower($file->getClientExtension());
            if (! $file->isValid() || ! isset(self::TIPE_FOTO[$ext]) || ! in_array($file->getMimeType(), self::TIPE_FOTO, true)) {
                return [[], 'File "' . esc($file->getClientName()) . '" bukan foto JPG, PNG, atau WEBP.'];
            }
            if ($file->getSize() > self::MAKS_UKURAN) {
                return [[], 'Foto "' . esc($file->getClientName()) . '" lebih dari 5 MB.'];
            }
        }

        if (! is_dir(TroubleshootReportModel::folderFoto())) {
            mkdir(TroubleshootReportModel::folderFoto(), 0755, true);
        }
        $ketBaru = (array) $this->request->getPost('ket_baru');
        foreach ($files as $i => $file) {
            $nama = date('Ymd') . '_' . bin2hex(random_bytes(8)) . '.' . strtolower($file->getClientExtension());
            $file->move(TroubleshootReportModel::folderFoto(), $nama);
            $baru[] = ['file' => $nama, 'ket' => self::ket($ketBaru[$i] ?? '')];
        }

        return [$baru, null];
    }

    private function hapusFileFoto(array $nama): void
    {
        foreach ($nama as $f) {
            $path = TroubleshootReportModel::folderFoto() . basename($f);
            if (is_file($path)) {
                @unlink($path);
            }
        }
    }

    // Simpan data baru (POST TroubleshootReport/save)
    public function save()
    {
        $f = $this->collectFields();

        if ($error = $this->validasi($f)) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        [$foto, $error] = $this->uploadFoto(self::MAKS_FOTO);
        if ($error) {
            return redirect()->back()->withInput()->with('error', $error);
        }
        $f['lampiran'] = $foto ? json_encode($foto, JSON_UNESCAPED_UNICODE) : null;

        // Troubleshoot ID dibuat di server; ulangi bila bentrok dengan input bersamaan
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $f['trouble_id'] = $this->model->generateTroubleId();
            try {
                if ($this->model->insert($f) !== false) {
                    break;
                }
            } catch (DatabaseException $e) {
                if ($attempt < 2 && stripos($e->getMessage(), 'Duplicate') !== false) {
                    continue;
                }
            }
            $this->hapusFileFoto(array_column($foto, 'file'));

            return redirect()->back()->withInput()->with('error', 'Troubleshoot Report gagal disimpan, silakan coba lagi.');
        }

        return redirect()->to(site_url('TroubleshootReport'))
            ->with('success', 'Troubleshoot Report ' . $f['trouble_id'] . ' berhasil ditambahkan.');
    }

    // Update data (POST TroubleshootReport/update)
    public function update()
    {
        $id  = (int) $this->request->getPost('id');
        $old = $id ? $this->model->find($id) : null;

        if (! $old) {
            return redirect()->to(site_url('TroubleshootReport'))->with('error', 'Data Troubleshoot Report tidak ditemukan.');
        }

        $f = $this->collectFields();

        if ($error = $this->validasi($f)) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        // Foto lama yang dicentang "hapus" dibuang, sisanya tetap (keterangannya bisa diubah: ket_lama[nama file]);
        // lalu tambah foto baru
        $lampiranLama = TroubleshootReportModel::lampiran($old);
        $dihapus      = array_values(array_intersect(array_column($lampiranLama, 'file'), (array) $this->request->getPost('hapus_foto')));
        $ketLama      = (array) $this->request->getPost('ket_lama');
        $tetap        = [];
        foreach ($lampiranLama as $l) {
            if (! in_array($l['file'], $dihapus, true)) {
                $tetap[] = ['file' => $l['file'], 'ket' => array_key_exists($l['file'], $ketLama) ? self::ket($ketLama[$l['file']]) : $l['ket']];
            }
        }

        [$baru, $error] = $this->uploadFoto(self::MAKS_FOTO - count($tetap));
        if ($error) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $semua         = array_merge($tetap, $baru);
        $f['lampiran'] = $semua ? json_encode($semua, JSON_UNESCAPED_UNICODE) : null;

        try {
            $ok = $this->model->update($id, $f) !== false;
        } catch (\Throwable $e) {
            $ok = false;
        }
        if (! $ok) {
            $this->hapusFileFoto(array_column($baru, 'file'));

            return redirect()->back()->withInput()->with('error', 'Troubleshoot Report gagal diperbarui.');
        }

        $this->hapusFileFoto($dihapus);

        return redirect()->to(site_url('TroubleshootReport'))
            ->with('success', 'Troubleshoot Report ' . $old['trouble_id'] . ' berhasil diperbarui.');
    }

    // Hapus data beserta foto lampirannya
    public function delete($id)
    {
        $sr = $this->model->find($id);

        if ($sr) {
            $this->model->delete($id);
            $this->hapusFileFoto(TroubleshootReportModel::foto($sr));
        }

        return redirect()->to(site_url('TroubleshootReport'))->with('success', 'Troubleshoot Report berhasil dihapus.');
    }
}
