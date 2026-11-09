<?php

namespace App\Controllers;

// Halaman SPK (menu Activation > SPK): Surat Perintah Kerja. Fitur & tampilan sama seperti BAST
// (upload, view, download, hapus). Dokumen: PDF / Word / PNG / JPG, maks. 30 MB.
// Nomor SPK, Tanggal SPK, Keperluan & Lokasi dibaca otomatis dari isi dokumen di browser
// (PDF: pdf.js, Word: isi XML / teks, gambar & PDF scan: OCR tesseract.js) lalu dikirim bersama file.
// Yang tidak terbaca bisa diisi manual (boleh kosong). Nomor SPK tidak boleh dobel.
class Spk extends BaseController
{
    public const MAKS_BYTE = 30 * 1024 * 1024;   // 30 MB

    protected string $route    = 'Spk';
    protected string $judul    = 'SPK';
    protected string $subjudul = 'Surat Perintah Kerja untuk pekerjaan aktivasi';
    protected string $ikon     = 'ti-file-certificate';
    protected string $contohNo = 'SPK/NOC/2026/001';

    // ekstensi yang diterima => format
    private const EKSTENSI = ['pdf' => 'PDF', 'doc' => 'DOC', 'docx' => 'DOCX', 'png' => 'PNG', 'jpg' => 'JPG', 'jpeg' => 'JPG'];

    // MIME saat file diunduh / dilihat
    private const MIME = [
        'PDF'  => 'application/pdf',
        'DOC'  => 'application/msword',
        'DOCX' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'PNG'  => 'image/png',
        'JPG'  => 'image/jpeg',
    ];

    // format yang bisa dilihat langsung di browser (pop up View)
    private const BISA_DILIHAT = ['PDF', 'PNG', 'JPG'];

    protected $model;

    public function __construct()
    {
        $this->model = new \App\Models\SpkModel();
    }

    // folder penyimpanan dokumen: writable/uploads/spk/
    private function uploadDir(): string
    {
        return WRITEPATH . 'uploads/spk/';
    }

    // Halaman utama (view bersama Bast/index.php)
    public function index()
    {
        return view('Spk/index', [
            'files'    => $this->model->orderBy('tanggal_spk', 'DESC')->orderBy('id', 'DESC')->findAll(),
            'maksByte' => self::MAKS_BYTE,
            'bast'     => ['route' => $this->route, 'judul' => $this->judul, 'subjudul' => $this->subjudul, 'ikon' => $this->ikon, 'contohNo' => $this->contohNo],
        ]);
    }

    /**
     * Tentukan format dari ISI file (bukan hanya ekstensi). Kembalikan PDF / DOC / DOCX / PNG / JPG,
     * atau null bila isi file tidak cocok dengan ekstensinya.
     */
    private function deteksiFormat(string $path, string $ext): ?string
    {
        $fh = @fopen($path, 'rb');
        if (! $fh) {
            return null;
        }
        $awal = (string) fread($fh, 1024);
        fclose($fh);

        switch (self::EKSTENSI[$ext] ?? null) {
            case 'PDF':
                // tanda "%PDF-" boleh didahului sedikit byte sampah
                return strpos(substr($awal, 0, 1024), '%PDF-') !== false ? 'PDF' : null;

            case 'PNG':
                return strncmp($awal, "\x89PNG\r\n\x1A\n", 8) === 0 ? 'PNG' : null;

            case 'JPG':
                return strncmp($awal, "\xFF\xD8\xFF", 3) === 0 ? 'JPG' : null;

            case 'DOC':
                // Word 97-2003 = berkas OLE (Compound File)
                return strncmp($awal, "\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1", 8) === 0 ? 'DOC' : null;

            case 'DOCX':
                // Word 2007+ = arsip ZIP berisi word/document.xml
                if (strncmp($awal, "PK\x03\x04", 4) !== 0) {
                    return null;
                }
                if (class_exists(\ZipArchive::class)) {
                    $zip = new \ZipArchive();
                    if ($zip->open($path) !== true) {
                        return null;
                    }
                    $ada = $zip->locateName('word/document.xml') !== false;
                    $zip->close();

                    return $ada ? 'DOCX' : null;
                }

                return 'DOCX';
        }

        return null;
    }

    private function gagal(string $pesan)
    {
        return redirect()->to(site_url($this->route))->withInput()->with('error', $pesan);
    }

    // Upload dokumen (POST {route}/upload)
    public function upload()
    {
        // hasil baca otomatis dari isi dokumen (boleh kosong bila tidak terbaca); dipotong sesuai panjang kolom
        $bersih = fn (string $k, int $maks) => mb_substr(trim(preg_replace('/\s+/', ' ', (string) $this->request->getPost($k))), 0, $maks) ?: null;
        $noSpk     = $bersih('no_spk', 100);
        $tanggal    = $bersih('tanggal_spk', 10);
        $keperluan = $bersih('keperluan', 255);
        $lokasi    = $bersih('lokasi', 255);

        $tgl = $tanggal ? \DateTime::createFromFormat('!Y-m-d', $tanggal) : false;
        if (! $tgl || $tgl->format('Y-m-d') !== $tanggal) {
            $tanggal = null;
        }

        $err = [];
        if ($noSpk !== null && ($ada = $this->model->where('no_spk', $noSpk)->first())) {
            $err[] = 'Nomor SPK "' . $noSpk . '" sudah dipakai' . ($ada['keperluan'] ? ' (' . $ada['keperluan'] . ')' : '') . '.';
        }

        $file = $this->request->getFile('dokumen');
        if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            $err[] = 'Pilih dokumen SPK terlebih dahulu.';
        } elseif (in_array($file->getError(), [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true)) {
            // file lebih besar dari batas server (upload_max_filesize / post_max_size)
            $err[] = 'Ukuran dokumen melebihi batas. Maksimal 30 MB.';
        } elseif (! $file->isValid() || $file->hasMoved()) {
            $err[] = 'Dokumen gagal diupload, coba lagi.';
        }
        if ($err) {
            return $this->gagal(implode(' ', $err));
        }

        $ext = strtolower($file->getClientExtension());
        if (! isset(self::EKSTENSI[$ext])) {
            return $this->gagal('Dokumen harus berformat PDF, Word (.doc / .docx), PNG, atau JPG.');
        }
        $ukuran = (int) $file->getSize();
        if ($ukuran <= 0) {
            return $this->gagal('Dokumen kosong, tidak bisa diupload.');
        }
        if ($ukuran > self::MAKS_BYTE) {
            return $this->gagal('Ukuran dokumen ' . $this->formatUkuran($ukuran) . ' melebihi batas 30 MB.');
        }

        $format = $this->deteksiFormat($file->getTempName(), $ext);
        if ($format === null) {
            return $this->gagal('Isi dokumen bukan ' . self::EKSTENSI[$ext] . ' yang valid. Pastikan file tidak rusak dan ekstensinya benar.');
        }

        // dokumen yang sama persis tidak boleh diupload dua kali
        $hash = sha1_file($file->getTempName());
        if ($ada = $this->model->where('hash_sha1', $hash)->first()) {
            return $this->gagal('Dokumen ini sudah pernah diupload' . ($ada['no_spk'] ? ' untuk Nomor SPK "' . $ada['no_spk'] . '"' : ' sebagai "' . $ada['nama_file'] . '"') . '.');
        }

        if (! is_dir($this->uploadDir())) {
            mkdir($this->uploadDir(), 0755, true);
        }

        $namaAsli   = mb_substr(str_replace(['"', "\r", "\n", '/', '\\'], '', $file->getClientName()), 0, 255);
        $namaSimpan = bin2hex(random_bytes(16)) . '.' . $ext;
        $file->move($this->uploadDir(), $namaSimpan);

        try {
            $ok = $this->model->insert([
                'no_spk'       => $noSpk,
                'tanggal_spk'  => $tanggal,
                'keperluan'    => $keperluan,
                'lokasi'       => $lokasi,
                'nama_file'     => $namaAsli !== '' ? $namaAsli : $namaSimpan,
                'file_simpan'   => $namaSimpan,
                'format'        => $format,
                'ukuran'        => $ukuran,
                'hash_sha1'     => $hash,
                'diupload_oleh' => session('username') ?: null,
            ]) !== false;
        } catch (\Throwable $e) {
            $ok = false;
        }

        if (! $ok) {
            @unlink($this->uploadDir() . $namaSimpan);

            return $this->gagal('Dokumen gagal disimpan, kemungkinan Nomor SPK / dokumen yang sama sudah ada.');
        }

        return redirect()->to(site_url($this->route))->with('success', 'Dokumen SPK' . ($noSpk ? ' ' . $noSpk : '') . ($keperluan ? ' (' . $keperluan . ')' : '') . ' berhasil diupload.');
    }

    // kirim file: $inline = true untuk dilihat di browser (pop up View), false = diunduh
    private function kirim($id, bool $inline)
    {
        $row  = $this->model->find($id);
        $path = $row ? $this->uploadDir() . basename($row['file_simpan']) : null;

        if (! $path || ! is_file($path)) {
            return redirect()->to(site_url($this->route))->with('error', 'Dokumen tidak ditemukan.');
        }
        if ($inline && ! in_array($row['format'], self::BISA_DILIHAT, true)) {
            $inline = false;   // Word tidak bisa ditampilkan browser -> diunduh
        }

        return $this->response
            ->setHeader('Content-Type', self::MIME[$row['format']] ?? 'application/octet-stream')
            ->setHeader('Content-Disposition', ($inline ? 'inline' : 'attachment') . '; filename="' . str_replace('"', '', $row['nama_file']) . '"')
            ->setHeader('Content-Length', (string) filesize($path))
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setHeader('Cache-Control', 'no-store, no-cache')
            ->setBody(file_get_contents($path));
    }

    // Unduh dokumen dengan nama asli (GET {route}/download/{id})
    public function download($id)
    {
        return $this->kirim($id, false);
    }

    // Tampilkan PDF / gambar di pop up View (GET {route}/lihat/{id})
    public function lihat($id)
    {
        return $this->kirim($id, true);
    }

    // Hapus data beserta filenya
    public function delete($id)
    {
        $row = $this->model->find($id);

        if ($row) {
            $this->model->delete($id);
            $path = $this->uploadDir() . basename($row['file_simpan']);
            if (is_file($path)) {
                unlink($path);
            }
        }

        return redirect()->to(site_url($this->route))->with('success', 'SPK berhasil dihapus.');
    }

    private function formatUkuran(int $byte): string
    {
        if ($byte >= 1048576) {
            return number_format($byte / 1048576, 2, ',', '.') . ' MB';
        }

        return number_format($byte / 1024, 1, ',', '.') . ' KB';
    }
}
