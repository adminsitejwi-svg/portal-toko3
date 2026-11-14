<?php

namespace App\Controllers;

use App\Libraries\FtpKlien;
use App\Models\FtpAkunModel;
use CodeIgniter\HTTP\ResponseInterface;
use RuntimeException;

/**
 * FTP Client (menu Data Teknis NOC): connect ke server FTP/FTPS/SFTP, jelajahi folder,
 * import (upload) file / folder / ZIP (diekstrak) untuk backup, download, rename, hapus,
 * buat folder, dan simpan akun FTP (password terenkripsi).
 *
 * Koneksi disimpan di sesi PHP (password terenkripsi); tiap permintaan membuka koneksi baru.
 * Upload dikirim per potongan (8 MB) supaya file besar tidak terbentur upload_max_filesize.
 */
class FtpClient extends BaseController
{
    private const SESI = 'ftp_sesi';

    protected FtpAkunModel $akun;

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        helper('activity');
        $this->akun = new FtpAkunModel();
    }

    // ================= HALAMAN =================

    public function index()
    {
        $sesi = session(self::SESI);

        return view('FtpClient/index', [
            'akun'      => $this->akun->daftarAman(),
            'protokol'  => FtpKlien::PROTOKOL,
            'portBawaan' => FtpKlien::PORT_BAWAAN,
            'sftpAda'   => FtpKlien::sftpTersedia(),
            'ftpAda'    => FtpKlien::ftpTersedia(),
            'sesi'      => $sesi ? array_diff_key($sesi, ['password' => 1]) : null,
            'chunk'     => $this->ukuranPotongan(),
        ]);
    }

    // ================= KONEKSI =================

    /** POST FtpClient/connect */
    public function connect()
    {
        $akunId = (int) $this->request->getPost('akun_id');
        $akun   = $akunId ? $this->akun->find($akunId) : null;

        $cfg = [
            'protokol' => (string) $this->request->getPost('protokol'),
            'host'     => trim((string) $this->request->getPost('host')),
            'port'     => (int) $this->request->getPost('port'),
            'username' => trim((string) $this->request->getPost('username')),
            'password' => (string) $this->request->getPost('password'),
            'pasif'    => (bool) $this->request->getPost('pasif'),
        ];
        $folderAwal = trim((string) $this->request->getPost('folder_awal'));

        // Akun tersimpan: password kosong = pakai password yang tersimpan
        if ($akun && $cfg['password'] === '') {
            $cfg['password'] = FtpAkunModel::dekripsi($akun['password']);
        }
        if (! isset(FtpKlien::PROTOKOL[$cfg['protokol']])) {
            return $this->gagal('Protokol tidak dikenal.');
        }
        if ($cfg['port'] < 1 || $cfg['port'] > 65535) {
            $cfg['port'] = FtpKlien::PORT_BAWAAN[$cfg['protokol']];
        }

        $ftp = new FtpKlien($cfg);
        try {
            $ftp->connect();
            $folder = $folderAwal !== '' ? FtpKlien::path($folderAwal) : $ftp->pwd();
            $isi    = $ftp->daftar($folder);
        } catch (RuntimeException $e) {
            return $this->gagal($e->getMessage());
        }

        // Simpan akun bila dicentang
        $pesanSimpan = '';
        if ($this->request->getPost('simpan')) {
            $nama = trim((string) $this->request->getPost('nama_akun')) ?: ($cfg['username'] . '@' . $cfg['host']);
            $data = [
                'nama'        => mb_substr($nama, 0, 100),
                'protokol'    => $cfg['protokol'],
                'host'        => $cfg['host'],
                'port'        => $cfg['port'],
                'username'    => $cfg['username'],
                'password'    => FtpAkunModel::enkripsi($cfg['password']),
                'folder_awal' => $folderAwal ?: null,
                'pasif'       => $cfg['pasif'] ? 1 : 0,
            ];
            $lama = $akun ?: $this->akun->where('nama', $data['nama'])->first();
            if ($lama) {
                $this->akun->update($lama['id'], $data);
                $akunId = (int) $lama['id'];
            } else {
                $data['dibuat_oleh'] = session('username');
                $akunId              = (int) $this->akun->insert($data);
            }
            $pesanSimpan = ' Akun "' . $data['nama'] . '" disimpan.';
        }

        session()->set(self::SESI, [
            'protokol' => $cfg['protokol'],
            'host'     => $cfg['host'],
            'port'     => $cfg['port'],
            'username' => $cfg['username'],
            'password' => FtpAkunModel::enkripsi($cfg['password']),
            'pasif'    => $cfg['pasif'],
            'folder'   => $folder,
            'akun_id'  => $akunId ?: null,
        ]);

        return $this->response->setJSON([
            'ok'     => true,
            'pesan'  => 'Terhubung ke ' . $cfg['host'] . '.' . $pesanSimpan,
            'folder' => $folder,
            'isi'    => $isi,
            'label'  => strtoupper($cfg['protokol']) . ' · ' . $cfg['username'] . '@' . $cfg['host'] . ':' . $cfg['port'],
            'akun'   => $this->akun->daftarAman(),
        ]);
    }

    /** POST FtpClient/disconnect */
    public function disconnect()
    {
        session()->remove(self::SESI);

        return $this->response->setJSON(['ok' => true, 'pesan' => 'Koneksi FTP diputus.']);
    }

    /** Buka koneksi dari sesi; null bila belum connect. */
    private function klien(): FtpKlien
    {
        $sesi = session(self::SESI);
        if (! $sesi) {
            throw new RuntimeException('Belum terhubung ke FTP Server. Silakan Connect dulu.');
        }
        $sesi['password'] = FtpAkunModel::dekripsi($sesi['password']);
        $ftp              = new FtpKlien($sesi);
        $ftp->connect();

        return $ftp;
    }

    private function label(): string
    {
        $s = session(self::SESI) ?: [];

        return ($s['username'] ?? '') . '@' . ($s['host'] ?? '');
    }

    // ================= FILE EXPLORER =================

    /** GET FtpClient/list?path= */
    public function list()
    {
        try {
            $ftp    = $this->klien();
            $folder = FtpKlien::path((string) $this->request->getGet('path'));
            $isi    = $ftp->daftar($folder);
        } catch (RuntimeException $e) {
            return $this->gagal($e->getMessage());
        }
        $sesi           = session(self::SESI);
        $sesi['folder'] = $folder;
        session()->set(self::SESI, $sesi);

        return $this->response->setJSON(['ok' => true, 'folder' => $folder, 'isi' => $isi]);
    }

    /** POST FtpClient/mkdir (path, nama) */
    public function mkdir()
    {
        $nama = $this->namaAman((string) $this->request->getPost('nama'));
        if ($nama === '') {
            return $this->gagal('Nama folder tidak valid.');
        }
        $tujuan = FtpKlien::gabung((string) $this->request->getPost('path'), $nama);

        try {
            $this->klien()->buatFolder($tujuan);
        } catch (RuntimeException $e) {
            return $this->gagal($e->getMessage());
        }
        log_activity('create', 'ftp_client', null, 'Membuat folder ' . $tujuan . ' di FTP ' . $this->label());

        return $this->response->setJSON(['ok' => true, 'pesan' => 'Folder ' . $nama . ' dibuat.']);
    }

    /** POST FtpClient/rename (path, nama_baru) */
    public function rename()
    {
        $dari = FtpKlien::path((string) $this->request->getPost('path'));
        $nama = $this->namaAman((string) $this->request->getPost('nama_baru'));
        if ($nama === '' || $dari === '/') {
            return $this->gagal('Nama baru tidak valid.');
        }
        $ke = FtpKlien::gabung(dirname($dari), $nama);

        try {
            $this->klien()->rename($dari, $ke);
        } catch (RuntimeException $e) {
            return $this->gagal($e->getMessage());
        }
        log_activity('update', 'ftp_client', null, 'Mengganti nama ' . $dari . ' menjadi ' . $ke . ' di FTP ' . $this->label());

        return $this->response->setJSON(['ok' => true, 'pesan' => 'Nama diganti menjadi ' . $nama . '.']);
    }

    /** POST FtpClient/delete (paths[] , tipe[]) — bisa banyak sekaligus */
    public function delete()
    {
        $paths = (array) $this->request->getPost('paths');
        $tipe  = (array) $this->request->getPost('tipe');
        if (! $paths) {
            return $this->gagal('Tidak ada yang dipilih.');
        }
        set_time_limit(0);

        try {
            $ftp = $this->klien();
            foreach ($paths as $i => $p) {
                $ftp->hapus((string) $p, ($tipe[$i] ?? '') === 'dir');
            }
        } catch (RuntimeException $e) {
            return $this->gagal($e->getMessage());
        }
        log_activity('delete', 'ftp_client', null, 'Menghapus ' . implode(', ', array_map([FtpKlien::class, 'path'], $paths)) . ' di FTP ' . $this->label());

        return $this->response->setJSON(['ok' => true, 'pesan' => count($paths) . ' item dihapus.']);
    }

    /** GET FtpClient/download?path= */
    public function download()
    {
        $remote = FtpKlien::path((string) $this->request->getGet('path'));
        $lokal  = tempnam(sys_get_temp_dir(), 'ftpdl');
        set_time_limit(0);

        try {
            $this->klien()->download($remote, $lokal);
        } catch (RuntimeException $e) {
            @unlink($lokal);

            return redirect()->to(site_url('FtpClient'))->with('error', $e->getMessage());
        }
        register_shutdown_function(static fn () => @unlink($lokal));

        return $this->response->download($lokal, null)->setFileName(basename($remote));
    }

    // ================= UPLOAD (IMPORT) =================

    private function folderTmp(): string
    {
        $dir = WRITEPATH . 'uploads/ftp_tmp/';
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        return $dir;
    }

    /**
     * POST FtpClient/upload — satu potongan file:
     * path (folder tujuan), relpath (nama/relatif dalam folder yang diimport), upload_id,
     * index, total, potongan (file), ekstrak (1 = file ZIP dibongkar ke folder bernama ZIP).
     */
    public function upload()
    {
        $id      = (string) $this->request->getPost('upload_id');
        $index   = (int) $this->request->getPost('index');
        $total   = max(1, (int) $this->request->getPost('total'));
        $relpath = (string) $this->request->getPost('relpath');
        $tujuan  = FtpKlien::path((string) $this->request->getPost('path'));
        $file    = $this->request->getFile('potongan');

        if (! preg_match('/^[a-z0-9]{8,40}$/i', $id)) {
            return $this->gagal('ID upload tidak valid.');
        }
        if (! session(self::SESI)) {
            return $this->gagal('Belum terhubung ke FTP Server. Silakan Connect dulu.');
        }
        if (! $file || ! $file->isValid()) {
            return $this->gagal('Potongan file gagal diterima' . ($file ? ' (' . $file->getErrorString() . ')' : '') . '.');
        }

        // Relatif path: setiap bagian dibersihkan, tidak boleh naik folder
        $bagian = array_values(array_filter(array_map(fn ($n) => $this->namaAman((string) $n), explode('/', str_replace('\\', '/', $relpath))), 'strlen'));
        if (! $bagian) {
            return $this->gagal('Nama file tidak valid.');
        }

        $tmp = $this->folderTmp() . session_id() . '_' . $id . '.part';
        if ($index === 0 && is_file($tmp)) {
            @unlink($tmp);
        }
        $masuk = fopen($file->getTempName(), 'rb');
        $keluar = fopen($tmp, 'ab');
        stream_copy_to_stream($masuk, $keluar);
        fclose($masuk);
        fclose($keluar);

        if ($index < $total - 1) {
            return $this->response->setJSON(['ok' => true, 'lanjut' => true]);
        }

        // Potongan terakhir: kirim ke FTP
        set_time_limit(0);
        $remote = FtpKlien::gabung($tujuan, implode('/', $bagian));
        $ekstrak = $this->request->getPost('ekstrak') && strtolower(pathinfo($remote, PATHINFO_EXTENSION)) === 'zip';

        try {
            $ftp = $this->klien();
            if ($ekstrak) {
                $jumlah = $this->unggahZip($ftp, $tmp, preg_replace('/\.zip$/i', '', $remote));
                $pesan  = 'ZIP diekstrak: ' . $jumlah . ' file diunggah ke ' . preg_replace('/\.zip$/i', '', $remote) . '.';
            } else {
                $ftp->upload($tmp, $remote);
                $pesan = basename($remote) . ' diunggah.';
            }
        } catch (RuntimeException $e) {
            @unlink($tmp);

            return $this->gagal($e->getMessage());
        }
        @unlink($tmp);

        if ($ekstrak) {
            log_activity('create', 'ftp_client', null, 'Import ZIP ' . basename($remote) . ' (diekstrak, ' . $jumlah . ' file) ke ' . dirname($remote) . ' di FTP ' . $this->label());
        }

        return $this->response->setJSON(['ok' => true, 'selesai' => true, 'pesan' => $pesan, 'remote' => $remote]);
    }

    /** Bongkar ZIP lokal lalu unggah isinya ke folder remote; hasil = jumlah file. */
    private function unggahZip(FtpKlien $ftp, string $zipLokal, string $folderRemote): int
    {
        if (! class_exists(\ZipArchive::class)) {
            throw new RuntimeException('Ekstensi ZIP PHP belum aktif di server.');
        }
        $zip = new \ZipArchive();
        if ($zip->open($zipLokal) !== true) {
            throw new RuntimeException('File ZIP rusak atau tidak dapat dibuka.');
        }

        $dirTmp = $this->folderTmp() . 'zip_' . bin2hex(random_bytes(6)) . '/';
        mkdir($dirTmp, 0775, true);
        $jumlah = 0;

        try {
            $ftp->buatFolder($folderRemote);
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $nama   = (string) $zip->getNameIndex($i);
                $bagian = array_values(array_filter(array_map(fn ($n) => $this->namaAman((string) $n), explode('/', str_replace('\\', '/', $nama))), 'strlen'));
                if (! $bagian) {
                    continue;
                }
                $relatif = implode('/', $bagian);
                if (str_ends_with($nama, '/')) {
                    $ftp->buatFolder($folderRemote . '/' . $relatif);
                    continue;
                }
                // Salin satu entri ke file sementara (aman dari path "../" di dalam ZIP)
                $lokal = $dirTmp . 'f' . $i;
                $in    = $zip->getStream($nama);
                if (! $in) {
                    continue;
                }
                $out = fopen($lokal, 'wb');
                stream_copy_to_stream($in, $out);
                fclose($in);
                fclose($out);
                $ftp->upload($lokal, $folderRemote . '/' . $relatif);
                @unlink($lokal);
                $jumlah++;
            }
        } finally {
            $zip->close();
            array_map('unlink', glob($dirTmp . '*') ?: []);
            @rmdir($dirTmp);
        }

        return $jumlah;
    }

    /** POST FtpClient/catat — ringkasan import file/folder ke Change Log (satu baris per import). */
    public function catat()
    {
        $jumlah = (int) $this->request->getPost('jumlah');
        $gagal  = (int) $this->request->getPost('gagal');
        $jenis  = $this->request->getPost('jenis') === 'folder' ? 'folder ' . $this->namaAman((string) $this->request->getPost('nama')) : 'file';
        $tujuan = FtpKlien::path((string) $this->request->getPost('path'));

        if ($jumlah > 0 && session(self::SESI)) {
            log_activity('create', 'ftp_client', null, 'Import ' . $jenis . ' (' . $jumlah . ' file' . ($gagal ? ', ' . $gagal . ' gagal' : '') . ') ke ' . $tujuan . ' di FTP ' . $this->label());
        }

        return $this->response->setJSON(['ok' => true]);
    }

    // ================= AKUN TERSIMPAN =================

    /** POST FtpClient/akun/delete/{id} */
    public function hapusAkun($id)
    {
        $akun = $this->akun->find((int) $id);
        if (! $akun) {
            return $this->gagal('Akun tidak ditemukan.');
        }
        $this->akun->delete($akun['id']);

        return $this->response->setJSON(['ok' => true, 'pesan' => 'Akun "' . $akun['nama'] . '" dihapus.', 'akun' => $this->akun->daftarAman()]);
    }

    // ================= BANTUAN =================

    /** Nama file/folder aman: tanpa / \ : * ? " < > | dan bukan "." atau "..". */
    private function namaAman(string $nama): string
    {
        $nama = trim(preg_replace('/[\/\\\\:*?"<>|\x00-\x1F]/u', '_', $nama));

        return in_array($nama, ['.', '..'], true) ? '' : mb_substr($nama, 0, 200);
    }

    /** Ukuran potongan upload: maks. 8 MB dan di bawah upload_max_filesize & post_max_size server. */
    private function ukuranPotongan(): int
    {
        $byte = static function (string $v): int {
            $v = trim($v);
            $n = (int) $v;
            switch (strtolower(substr($v, -1))) {
                case 'g': return $n * 1024 * 1024 * 1024;
                case 'm': return $n * 1024 * 1024;
                case 'k': return $n * 1024;
            }

            return $n;
        };
        $batas = array_filter([$byte((string) ini_get('upload_max_filesize')), $byte((string) ini_get('post_max_size'))]);

        return max(256 * 1024, min(8 * 1024 * 1024, ($batas ? min($batas) : 2 * 1024 * 1024) - 256 * 1024));
    }

    private function gagal(string $pesan, int $kode = 200): ResponseInterface
    {
        return $this->response->setStatusCode($kode)->setJSON(['ok' => false, 'pesan' => $pesan]);
    }
}
