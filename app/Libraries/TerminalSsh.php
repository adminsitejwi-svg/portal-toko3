<?php

namespace App\Libraries;

use phpseclib3\Net\SSH2;
use RuntimeException;

/**
 * Koneksi SSH halaman Terminal (phpseclib). Tiap perintah membuka koneksi baru.
 *
 * Mode:
 *  - exec  : MikroTik / Linux, satu perintah per koneksi
 *  - shell : OLT / switch yang hanya menerima layar interaktif; beberapa baris bisa dikirim sekaligus
 *            (mis. enable, config, display ...) karena tiap kiriman memulai sesi baru
 */
class TerminalSsh
{
    public const MODE = ['exec' => 'MikroTik / Linux', 'shell' => 'OLT / Switch'];

    private const BATAS_DETIK = 30;
    private const BATAS_BYTE  = 262144;

    private ?SSH2 $ssh = null;

    /** @param string $ip IP hasil TerminalAlat::ipTujuan() */
    public function __construct(private string $ip, private int $port, private string $user, private string $password)
    {
        if (! class_exists(SSH2::class)) {
            throw new RuntimeException('SSH belum tersedia di server (library phpseclib belum terpasang).');
        }
    }

    /** Sidik kunci server SSH (dibaca sebelum login, jadi password belum terkirim). */
    public function sidik(): string
    {
        $this->ssh = new SSH2($this->ip, $this->port, 10);
        $this->ssh->setTimeout(15);
        try {
            $kunci = $this->ssh->getServerPublicHostKey();
        } catch (\Throwable $e) {
            throw new RuntimeException('Tidak dapat terhubung ke ' . $this->ip . ':' . $this->port . ' (' . TerminalAlat::utf8($e->getMessage()) . ').');
        }
        $bagian = explode(' ', (string) $kunci);
        if (count($bagian) < 2) {
            throw new RuntimeException('Kunci server SSH tidak terbaca.');
        }

        return 'SHA256:' . rtrim(base64_encode(hash('sha256', (string) base64_decode($bagian[1]), true)), '=');
    }

    /** Login; $sidik = kunci server yang sudah disetujui. */
    public function login(string $sidik): void
    {
        $kini = $this->sidik();
        if (! hash_equals($sidik, $kini)) {
            throw new RuntimeException('Kunci server SSH berubah. Koneksi dibatalkan, password tidak dikirim.');
        }
        try {
            $ok = $this->ssh->login($this->user, $this->password);
        } catch (\Throwable $e) {
            throw new RuntimeException('Login SSH gagal (' . TerminalAlat::utf8($e->getMessage()) . ').');
        }
        if (! $ok) {
            throw new RuntimeException('Login SSH gagal: username atau password salah, atau akun tidak diizinkan SSH.');
        }
    }

    public function jalankan(string $perintah, string $mode): string
    {
        try {
            return $mode === 'shell' ? $this->shell($perintah) : $this->exec($perintah);
        } catch (RuntimeException $e) {
            throw new RuntimeException('Koneksi SSH terputus (' . TerminalAlat::utf8($e->getMessage()) . ').');
        }
    }

    private function exec(string $perintah): string
    {
        $this->ssh->setTimeout(self::BATAS_DETIK);
        $hasil = '';
        $penuh = false;
        $batas = microtime(true) + self::BATAS_DETIK;
        $habis = false;
        $this->ssh->exec($perintah, static function ($data) use (&$hasil, &$penuh, &$habis, $batas) {
            $hasil .= $data;
            $penuh = strlen($hasil) >= self::BATAS_BYTE;
            $habis = microtime(true) > $batas;

            return $penuh || $habis;
        });
        $galat = (string) $this->ssh->getStdError();

        // exec() phpseclib sudah menyertakan stderr dalam hasil kecuali mode senyap
        return $this->rapikan($hasil === '' ? $galat : $hasil, $penuh, $habis || $this->ssh->isTimeout());
    }

    private function shell(string $perintah): string
    {
        $this->ssh->setWindowSize(200, 500);
        $batas = microtime(true) + self::BATAS_DETIK;
        $hasil = $this->bacaSampaiDiam(2.0, $batas);
        foreach (preg_split('/\r\n|\r|\n/', $perintah) as $baris) {
            $this->ssh->write($baris . "\r");
            $hasil .= $this->bacaSampaiDiam(1.5, $batas);
            if (strlen($hasil) >= self::BATAS_BYTE || microtime(true) > $batas) {
                break;
            }
        }

        return $this->rapikan($hasil, strlen($hasil) >= self::BATAS_BYTE, microtime(true) > $batas);
    }

    /** Baca layar sampai perangkat diam; "--More--" dilanjutkan otomatis dengan spasi. */
    private function bacaSampaiDiam(float $diam, float $batas): string
    {
        $hasil = '';
        while (microtime(true) < $batas && strlen($hasil) < self::BATAS_BYTE) {
            $this->ssh->setTimeout($diam);
            $data = $this->ssh->read();
            if (is_string($data)) {
                $hasil .= $data;
            }
            if (preg_match('/(-{2,}\s*\(?more\b[^\n]*|press any key[^\n]*|<---\s*more[^\n]*)$/i', rtrim(TerminalAlat::utf8(substr($hasil, -300))))) {
                $this->ssh->write(' ');
                continue;
            }
            if (! is_string($data) || $data === '' || $this->ssh->isTimeout()) {
                break;
            }
        }

        return $hasil;
    }

    private function rapikan(string $hasil, bool $penuh, bool $habis): string
    {
        $teks = rtrim(TerminalAlat::utf8(substr($hasil, 0, self::BATAS_BYTE)));

        return $teks
            . ($penuh ? "\n[hasil dipotong, lebih dari 256 KB]" : '')
            . ($habis ? "\n[berhenti menunggu setelah " . self::BATAS_DETIK . ' detik; untuk MikroTik tambahkan mis. count=4 atau print without-paging]' : '');
    }

    public function tutup(): void
    {
        if ($this->ssh) {
            try {
                $this->ssh->disconnect();
            } catch (\Throwable $e) {
                // koneksi sudah tertutup
            }
            $this->ssh = null;
        }
    }

    public function __destruct()
    {
        $this->tutup();
    }
}
