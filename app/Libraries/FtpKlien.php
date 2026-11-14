<?php

namespace App\Libraries;

use RuntimeException;

/**
 * Koneksi FTP Client (menu Data Teknis > FTP Client).
 * Protokol: ftp (biasa), ftps (FTP + SSL/TLS eksplisit), sftp (SSH, butuh phpseclib/phpseclib).
 * Semua path memakai "/" dan selalu absolut, mis. /backup/2026.
 */
class FtpKlien
{
    public const PROTOKOL = [
        'ftp'  => 'FTP',
        'ftps' => 'FTPS (FTP + SSL)',
        'sftp' => 'SFTP (SSH)',
    ];

    public const PORT_BAWAAN = ['ftp' => 21, 'ftps' => 21, 'sftp' => 22];

    private string $protokol;
    private string $host;
    private int $port;
    private string $username;
    private string $password;
    private bool $pasif;

    /** @var resource|\FTP\Connection|\phpseclib3\Net\SFTP|null */
    private $conn = null;

    public function __construct(array $cfg)
    {
        $this->protokol = isset(self::PROTOKOL[$cfg['protokol'] ?? '']) ? $cfg['protokol'] : 'ftp';
        $this->host     = trim((string) ($cfg['host'] ?? ''));
        $this->port     = (int) ($cfg['port'] ?? 0) ?: self::PORT_BAWAAN[$this->protokol];
        $this->username = (string) ($cfg['username'] ?? '');
        $this->password = (string) ($cfg['password'] ?? '');
        $this->pasif    = (bool) ($cfg['pasif'] ?? true);
    }

    public static function sftpTersedia(): bool
    {
        return class_exists(\phpseclib3\Net\SFTP::class);
    }

    public static function ftpTersedia(): bool
    {
        return function_exists('ftp_connect');
    }

    private function isSftp(): bool
    {
        return $this->protokol === 'sftp';
    }

    /** Normalisasi path: selalu diawali "/", tanpa "//", "." atau "..". */
    public static function path(string $path): string
    {
        $hasil = [];
        foreach (explode('/', str_replace('\\', '/', $path)) as $bagian) {
            if ($bagian === '' || $bagian === '.') {
                continue;
            }
            if ($bagian === '..') {
                array_pop($hasil);
                continue;
            }
            $hasil[] = $bagian;
        }

        return '/' . implode('/', $hasil);
    }

    public static function gabung(string $dir, string $nama): string
    {
        return self::path(rtrim($dir, '/') . '/' . $nama);
    }

    public function connect(): void
    {
        if ($this->host === '') {
            throw new RuntimeException('IP Address / Hostname wajib diisi.');
        }

        if ($this->isSftp()) {
            if (! self::sftpTersedia()) {
                throw new RuntimeException('SFTP belum tersedia di server (library phpseclib belum terpasang).');
            }
            $sftp = new \phpseclib3\Net\SFTP($this->host, $this->port, 15);
            try {
                $ok = $sftp->login($this->username, $this->password);
            } catch (\Throwable $e) {
                throw new RuntimeException('Tidak dapat terhubung ke ' . $this->host . ':' . $this->port . ' (' . $e->getMessage() . ').');
            }
            if (! $ok) {
                throw new RuntimeException('Login SFTP gagal: username atau password salah.');
            }
            $this->conn = $sftp;

            return;
        }

        if (! self::ftpTersedia()) {
            throw new RuntimeException('Ekstensi FTP PHP belum aktif di server (aktifkan extension=ftp di php.ini lalu restart).');
        }

        $conn = $this->protokol === 'ftps'
            ? @ftp_ssl_connect($this->host, $this->port, 15)
            : @ftp_connect($this->host, $this->port, 15);

        if (! $conn) {
            throw new RuntimeException('Tidak dapat terhubung ke ' . $this->host . ':' . $this->port . '. Periksa IP/hostname, port & jaringan.');
        }
        if (! @ftp_login($conn, $this->username, $this->password)) {
            @ftp_close($conn);
            throw new RuntimeException('Login FTP gagal: username atau password salah' . ($this->protokol === 'ftps' ? ', atau server tidak mendukung SSL.' : '.'));
        }
        @ftp_pasv($conn, $this->pasif);
        $this->conn = $conn;
    }

    public function close(): void
    {
        if ($this->conn === null) {
            return;
        }
        if ($this->isSftp()) {
            $this->conn->disconnect();
        } else {
            @ftp_close($this->conn);
        }
        $this->conn = null;
    }

    public function __destruct()
    {
        $this->close();
    }

    /** Folder awal setelah login. */
    public function pwd(): string
    {
        $p = $this->isSftp() ? $this->conn->pwd() : @ftp_pwd($this->conn);

        return self::path((string) ($p ?: '/'));
    }

    /**
     * Isi folder: [['nama','tipe' => dir|file|link,'ukuran','waktu' (timestamp|null),'izin']], folder dulu.
     */
    public function daftar(string $dir): array
    {
        $dir   = self::path($dir);
        $hasil = $this->isSftp() ? $this->daftarSftp($dir) : $this->daftarFtp($dir);

        usort($hasil, static function ($a, $b) {
            if (($a['tipe'] === 'dir') !== ($b['tipe'] === 'dir')) {
                return $a['tipe'] === 'dir' ? -1 : 1;
            }

            return strnatcasecmp($a['nama'], $b['nama']);
        });

        return $hasil;
    }

    private function daftarSftp(string $dir): array
    {
        $isi = $this->conn->rawlist($dir);
        if ($isi === false) {
            throw new RuntimeException('Folder ' . $dir . ' tidak dapat dibuka.');
        }
        $hasil = [];
        foreach ($isi as $nama => $a) {
            if ($nama === '.' || $nama === '..') {
                continue;
            }
            $tipe    = (int) ($a['type'] ?? 1);
            $hasil[] = [
                'nama'   => (string) $nama,
                'tipe'   => $tipe === 2 ? 'dir' : ($tipe === 3 ? 'link' : 'file'),
                'ukuran' => (int) ($a['size'] ?? 0),
                'waktu'  => isset($a['mtime']) ? (int) $a['mtime'] : null,
                'izin'   => isset($a['mode']) ? substr(sprintf('%o', $a['mode']), -4) : '',
            ];
        }

        return $hasil;
    }

    private function daftarFtp(string $dir): array
    {
        // MLSD (format baku) bila server mendukung, selain itu baca LIST gaya Unix/Windows
        $mlsd = function_exists('ftp_mlsd') ? @ftp_mlsd($this->conn, $dir) : false;
        if (is_array($mlsd)) {
            $hasil = [];
            foreach ($mlsd as $a) {
                $type = strtolower($a['type'] ?? '');
                if (in_array($type, ['cdir', 'pdir'], true) || in_array($a['name'] ?? '', ['.', '..'], true)) {
                    continue;
                }
                $waktu = null;
                if (! empty($a['modify']) && preg_match('/^(\d{14})/', $a['modify'], $m)) {
                    $dt    = \DateTime::createFromFormat('YmdHis', $m[1], new \DateTimeZone('UTC'));
                    $waktu = $dt ? $dt->getTimestamp() : null;
                }
                $hasil[] = [
                    'nama'   => (string) $a['name'],
                    'tipe'   => $type === 'dir' ? 'dir' : (str_contains($type, 'link') ? 'link' : 'file'),
                    'ukuran' => (int) ($a['size'] ?? 0),
                    'waktu'  => $waktu,
                    'izin'   => $a['unix.mode'] ?? '',
                ];
            }

            return $hasil;
        }

        $raw = @ftp_rawlist($this->conn, $dir);
        if ($raw === false) {
            throw new RuntimeException('Folder ' . $dir . ' tidak dapat dibuka.');
        }

        $hasil = [];
        foreach ($raw as $baris) {
            // Unix: drwxr-xr-x 2 user group 4096 Nov 14 10:00 nama
            if (preg_match('/^([\-dlbcps])([rwxsStT\-]{9})\S*\s+\d+\s+\S+\s+\S+\s+(\d+)\s+(\w{3}\s+\d{1,2}\s+(?:\d{1,2}:\d{2}|\d{4}))\s+(.+)$/', $baris, $m)) {
                $nama = $m[5];
                if ($m[1] === 'l' && str_contains($nama, ' -> ')) {
                    $nama = explode(' -> ', $nama)[0];
                }
                if ($nama === '.' || $nama === '..') {
                    continue;
                }
                $hasil[] = [
                    'nama'   => $nama,
                    'tipe'   => $m[1] === 'd' ? 'dir' : ($m[1] === 'l' ? 'link' : 'file'),
                    'ukuran' => (int) $m[3],
                    'waktu'  => strtotime($m[4]) ?: null,
                    'izin'   => $m[2],
                ];
                continue;
            }
            // Windows/IIS: 11-14-26  10:00AM  <DIR>  nama   |   11-14-26  10:00AM  1234 nama
            if (preg_match('/^(\d{2}-\d{2}-\d{2,4})\s+(\d{1,2}:\d{2}[AP]M)\s+(<DIR>|\d+)\s+(.+)$/i', $baris, $m)) {
                $hasil[] = [
                    'nama'   => $m[4],
                    'tipe'   => strtoupper($m[3]) === '<DIR>' ? 'dir' : 'file',
                    'ukuran' => strtoupper($m[3]) === '<DIR>' ? 0 : (int) $m[3],
                    'waktu'  => strtotime($m[1] . ' ' . $m[2]) ?: null,
                    'izin'   => '',
                ];
            }
        }

        return $hasil;
    }

    private function adaFolder(string $dir): bool
    {
        if ($this->isSftp()) {
            return $this->conn->is_dir($dir);
        }
        $asal = @ftp_pwd($this->conn);
        if (@ftp_chdir($this->conn, $dir)) {
            if ($asal !== false) {
                @ftp_chdir($this->conn, $asal);
            }

            return true;
        }

        return false;
    }

    /** Buat folder beserta folder induknya bila belum ada. */
    public function buatFolder(string $dir): void
    {
        $dir = self::path($dir);
        if ($dir === '/' || $this->adaFolder($dir)) {
            return;
        }

        if ($this->isSftp()) {
            if (! $this->conn->mkdir($dir, -1, true)) {
                throw new RuntimeException('Folder ' . $dir . ' tidak dapat dibuat.');
            }

            return;
        }

        $jalur = '';
        foreach (explode('/', trim($dir, '/')) as $bagian) {
            $jalur .= '/' . $bagian;
            if (! $this->adaFolder($jalur) && @ftp_mkdir($this->conn, $jalur) === false) {
                throw new RuntimeException('Folder ' . $jalur . ' tidak dapat dibuat (cek izin akun FTP).');
            }
        }
    }

    /** Upload file lokal ke path remote (folder induk dibuat otomatis). */
    public function upload(string $lokal, string $remote): void
    {
        $remote = self::path($remote);
        $this->buatFolder(dirname($remote));

        $ok = $this->isSftp()
            ? $this->conn->put($remote, $lokal, \phpseclib3\Net\SFTP::SOURCE_LOCAL_FILE)
            : @ftp_put($this->conn, $remote, $lokal, FTP_BINARY);

        if (! $ok) {
            throw new RuntimeException('Gagal mengunggah ' . basename($remote) . ' (cek izin tulis & ruang disk FTP).');
        }
    }

    /** Download file remote ke file lokal. */
    public function download(string $remote, string $lokal): void
    {
        $remote = self::path($remote);
        $ok     = $this->isSftp()
            ? $this->conn->get($remote, $lokal)
            : @ftp_get($this->conn, $lokal, $remote, FTP_BINARY);

        if ($ok === false) {
            throw new RuntimeException('Gagal mengunduh ' . basename($remote) . '.');
        }
    }

    /** Hapus file, atau folder beserta seluruh isinya. */
    public function hapus(string $path, bool $folder): void
    {
        $path = self::path($path);
        if ($path === '/') {
            throw new RuntimeException('Folder root tidak boleh dihapus.');
        }

        if ($this->isSftp()) {
            if (! $this->conn->delete($path, $folder)) {
                throw new RuntimeException('Gagal menghapus ' . basename($path) . '.');
            }

            return;
        }

        if ($folder) {
            foreach ($this->daftarFtp($path) as $isi) {
                $this->hapus(self::gabung($path, $isi['nama']), $isi['tipe'] === 'dir');
            }
            $ok = @ftp_rmdir($this->conn, $path);
        } else {
            $ok = @ftp_delete($this->conn, $path);
        }
        if (! $ok) {
            throw new RuntimeException('Gagal menghapus ' . basename($path) . ' (cek izin akun FTP).');
        }
    }

    public function rename(string $dari, string $ke): void
    {
        $ok = $this->isSftp()
            ? $this->conn->rename(self::path($dari), self::path($ke))
            : @ftp_rename($this->conn, self::path($dari), self::path($ke));

        if (! $ok) {
            throw new RuntimeException('Gagal mengganti nama (nama mungkin sudah dipakai).');
        }
    }

    public function ukuran(string $remote): int
    {
        if ($this->isSftp()) {
            return (int) ($this->conn->filesize(self::path($remote)) ?: 0);
        }

        return max(0, (int) @ftp_size($this->conn, self::path($remote)));
    }
}
