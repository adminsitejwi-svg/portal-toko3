<?php

namespace App\Controllers;

use App\Models\KmzModel;

// Halaman KMZ (menu Data Teknis NOC > KMZ): upload file peta .kmz / .kml (maks. 30 MB).
// Format, ukuran, tanggal & jam upload, dan pengupload tercatat otomatis.
class Kmz extends BaseController
{
    public const MAKS_BYTE = 30 * 1024 * 1024;   // 30 MB
    private const UPLOAD_DIR = WRITEPATH . 'uploads/kmz/';

    // MIME saat file diunduh
    private const MIME = [
        'KMZ' => 'application/vnd.google-earth.kmz',
        'KML' => 'application/vnd.google-earth.kml+xml',
    ];

    protected $model;

    public function __construct()
    {
        $this->model = new KmzModel();
    }

    // Halaman utama (view Kmz/index.php)
    public function index()
    {
        return view('Kmz/index', [
            'files'    => $this->model->orderBy('created_at', 'DESC')->orderBy('id', 'DESC')->findAll(),
            'maksByte' => self::MAKS_BYTE,
        ]);
    }

    /**
     * Tentukan format dari ISI file (bukan hanya ekstensi):
     * KMZ = arsip ZIP berisi minimal satu file .kml, KML = dokumen XML dengan tag <kml>.
     * Kembalikan 'KMZ' / 'KML', atau null bila bukan file peta yang valid.
     */
    private function deteksiFormat(string $path, string $ext): ?string
    {
        $fh = @fopen($path, 'rb');
        if (! $fh) {
            return null;
        }
        $awal = (string) fread($fh, 4096);
        fclose($fh);

        if ($ext === 'kmz') {
            if (strncmp($awal, "PK\x03\x04", 4) !== 0) {
                return null;
            }
            if (class_exists(\ZipArchive::class)) {
                $zip = new \ZipArchive();
                if ($zip->open($path) !== true) {
                    return null;
                }
                $adaKml = false;
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    if (strtolower(pathinfo((string) $zip->getNameIndex($i), PATHINFO_EXTENSION)) === 'kml') {
                        $adaKml = true;
                        break;
                    }
                }
                $zip->close();

                return $adaKml ? 'KMZ' : null;
            }

            return 'KMZ';
        }

        if ($ext === 'kml') {
            // buang BOM UTF-8 lalu cari tag <kml di bagian awal dokumen
            $awal = preg_replace('/^\xEF\xBB\xBF/', '', $awal);

            return preg_match('/^\s*(<\?xml[^>]*>\s*)?(<!--.*?-->\s*)*<(\w+:)?kml[\s>]/is', $awal) ? 'KML' : null;
        }

        return null;
    }

    // Upload file (POST Kmz/upload)
    public function upload()
    {
        $file = $this->request->getFile('file_kmz');

        if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            return redirect()->to(site_url('Kmz'))->with('error', 'Pilih file KMZ / KML terlebih dahulu.');
        }
        // file lebih besar dari batas server (upload_max_filesize / post_max_size)
        if (in_array($file->getError(), [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true)) {
            return redirect()->to(site_url('Kmz'))->with('error', 'Ukuran file melebihi batas. Maksimal 30 MB.');
        }
        if (! $file->isValid() || $file->hasMoved()) {
            return redirect()->to(site_url('Kmz'))->with('error', 'File gagal diupload, coba lagi.');
        }

        $ext = strtolower($file->getClientExtension());
        if (! in_array($ext, ['kmz', 'kml'], true)) {
            return redirect()->to(site_url('Kmz'))->with('error', 'File harus berformat .kmz atau .kml.');
        }

        $ukuran = (int) $file->getSize();
        if ($ukuran <= 0) {
            return redirect()->to(site_url('Kmz'))->with('error', 'File kosong, tidak bisa diupload.');
        }
        if ($ukuran > self::MAKS_BYTE) {
            return redirect()->to(site_url('Kmz'))->with('error', 'Ukuran file ' . $this->formatUkuran($ukuran) . ' melebihi batas 30 MB.');
        }

        $format = $this->deteksiFormat($file->getTempName(), $ext);
        if ($format === null) {
            return redirect()->to(site_url('Kmz'))->with('error', 'Isi file bukan ' . strtoupper($ext) . ' yang valid. Pastikan file berasal dari Google Earth / aplikasi peta.');
        }

        // file yang sama persis tidak boleh diupload dua kali
        $hash = sha1_file($file->getTempName());
        if ($ada = $this->model->where('hash_sha1', $hash)->first()) {
            return redirect()->to(site_url('Kmz'))->with('error', 'File ini sudah pernah diupload sebagai "' . $ada['nama_file'] . '".');
        }

        if (! is_dir(self::UPLOAD_DIR)) {
            mkdir(self::UPLOAD_DIR, 0755, true);
        }

        $namaAsli   = mb_substr(str_replace(['"', "\r", "\n", '/', '\\'], '', $file->getClientName()), 0, 255);
        $namaSimpan = bin2hex(random_bytes(16)) . '.' . $ext;
        $file->move(self::UPLOAD_DIR, $namaSimpan);

        try {
            $ok = $this->model->insert([
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
            @unlink(self::UPLOAD_DIR . $namaSimpan);

            return redirect()->to(site_url('Kmz'))->with('error', 'File gagal disimpan, kemungkinan file yang sama sudah ada.');
        }

        return redirect()->to(site_url('Kmz'))->with('success', 'File ' . $format . ' "' . $namaAsli . '" (' . $this->formatUkuran($ukuran) . ') berhasil diupload.');
    }

    // Unduh file (GET Kmz/download/{id}) dengan nama asli
    public function download($id)
    {
        $row  = $this->model->find($id);
        $path = $row ? self::UPLOAD_DIR . basename($row['file_simpan']) : null;

        if (! $path || ! is_file($path)) {
            return redirect()->to(site_url('Kmz'))->with('error', 'File tidak ditemukan.');
        }

        return $this->response
            ->setHeader('Content-Type', self::MIME[$row['format']] ?? 'application/octet-stream')
            ->setHeader('Content-Disposition', 'attachment; filename="' . str_replace('"', '', $row['nama_file']) . '"')
            ->setHeader('Content-Length', (string) filesize($path))
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setHeader('Cache-Control', 'no-store, no-cache')
            ->setBody(file_get_contents($path));
    }

    // Hapus data beserta filenya
    public function delete($id)
    {
        $row = $this->model->find($id);

        if ($row) {
            $this->model->delete($id);
            $path = self::UPLOAD_DIR . basename($row['file_simpan']);
            if (is_file($path)) {
                unlink($path);
            }
        }

        return redirect()->to(site_url('Kmz'))->with('success', 'File KMZ berhasil dihapus.');
    }

    private function formatUkuran(int $byte): string
    {
        if ($byte >= 1048576) {
            return number_format($byte / 1048576, 2, ',', '.') . ' MB';
        }

        return number_format($byte / 1024, 1, ',', '.') . ' KB';
    }
}
