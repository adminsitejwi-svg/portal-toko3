<?php

namespace App\Controllers;

use App\Libraries\HakAkses;
use App\Libraries\TerminalAlat;
use App\Libraries\TerminalSsh;
use App\Models\FtpAkunModel;
use CodeIgniter\HTTP\ResponseInterface;
use RuntimeException;

/**
 * Halaman Terminal (Informasi > Terminal), khusus Administrator.
 *
 * - Tanpa SSH : hanya alat jaringan di App\Libraries\TerminalAlat (bukan CMD bebas di server)
 * - Dengan SSH: perintah bebas di perangkat (MikroTik / OLT / switch) sesuai hak akun perangkat
 *
 * Akun SSH hanya disimpan di sesi (password terenkripsi) dan habis setelah 15 menit tidak dipakai.
 * Setiap perintah dicatat di Change Log.
 */
class Terminal extends BaseController
{
    private const SESI       = 'terminal_ssh';
    private const SESI_TOKEN = 'terminal_token';
    private const HABIS      = 900;
    private const FILE_KUNCI = WRITEPATH . 'terminal_hostkey.json';

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        helper('activity');
    }

    public function index()
    {
        if (! $this->admin()) {
            return redirect()->to(site_url('dashboard-manager'))->with('error', 'Terminal hanya untuk Administrator.');
        }
        $token = (string) session(self::SESI_TOKEN);
        if ($token === '') {
            $token = bin2hex(random_bytes(32));
            session()->set(self::SESI_TOKEN, $token);
        }
        $sesi = $this->sesi();

        return view('Terminal/index', [
            'token'   => $token,
            'bantuan' => TerminalAlat::BANTUAN,
            'mode'    => TerminalSsh::MODE,
            'ssh'     => $sesi ? ['label' => $this->label($sesi), 'mode' => $sesi['mode']] : null,
        ]);
    }

    /** POST Terminal/jalankan (perintah) */
    public function jalankan()
    {
        if ($tolak = $this->tolak()) {
            return $tolak;
        }
        $perintah = trim(str_replace("\0", '', $this->teks('perintah')));
        if ($perintah === '' || strlen($perintah) > 4000) {
            return $this->gagal('Perintah kosong atau lebih dari 4000 karakter.');
        }
        $tujuan = $this->teks('tujuan'); // 'ssh' = layar sedang terhubung ke perangkat
        $sesi   = $this->sesi();

        if ($tujuan === 'ssh' && ! $sesi) {
            return $this->gagal('Sesi SSH sudah berakhir. Login lagi dengan perintah ssh.', ['putus' => true]);
        }

        if ($tujuan === 'ssh') {
            $sesi['waktu'] = time();
            session()->set(self::SESI, $sesi);
        }
        $this->catat(($tujuan === 'ssh' ? 'SSH ' . $this->label($sesi) : 'Alat jaringan') . ': ' . $perintah);
        session()->close(); // perintah bisa lama; halaman lain tidak ikut menunggu
        set_time_limit(120);

        try {
            if ($tujuan === 'ssh') {
                $ssh = new TerminalSsh($sesi['ip'], $sesi['port'], $sesi['user'], FtpAkunModel::dekripsi($sesi['password']));
                $ssh->login($sesi['sidik']);
                $hasil = $ssh->jalankan($perintah, $sesi['mode']);
                $ssh->tutup();
            } else {
                $hasil = (new TerminalAlat())->jalankan($perintah);
            }
        } catch (RuntimeException $e) {
            return $this->gagal($e->getMessage());
        }

        return $this->response->setJSON(['ok' => true, 'hasil' => $hasil]);
    }

    /** POST Terminal/ssh (user, host, port, password, mode, sidik) */
    public function ssh()
    {
        if ($tolak = $this->tolak()) {
            return $tolak;
        }
        if (! service('throttler')->check('terminal-ssh-' . (int) session('user_id'), 10, MINUTE)) {
            return $this->gagal('Terlalu banyak percobaan login. Tunggu 1 menit.');
        }
        $user = $this->teks('user');
        $port = (int) $this->teks('port');
        $mode = $this->teks('mode');
        $sandi = (string) $this->request->getPost('password');
        if (! preg_match('/^[^\s@\x00-\x1f]{1,100}$/', $user) || $port < 1 || $port > 65535 || ! isset(TerminalSsh::MODE[$mode])) {
            return $this->gagal('Username, port, atau jenis perangkat tidak valid.');
        }

        set_time_limit(60);
        try {
            $ip    = TerminalAlat::ipTujuan($this->teks('host'));
            $ssh   = new TerminalSsh($ip, $port, $user, $sandi);
            $kunci = $ip . ':' . $port;
            $tahu  = $this->kunciTersimpan()[$kunci] ?? '';
            $kini  = $ssh->sidik();

            // kunci baru / berubah harus disetujui dulu; password belum dikirim ke perangkat
            if ($tahu !== $kini && ! hash_equals($kini, $this->teks('sidik'))) {
                return $this->response->setJSON(['ok' => false, 'setuju' => true, 'sidik' => $kini, 'berubah' => $tahu !== '', 'ip' => $kunci]);
            }
            $ssh->tutup();
            $ssh->login($kini);
            $ssh->tutup();
        } catch (RuntimeException $e) {
            $this->catat('Login SSH gagal ke ' . $user . '@' . $this->teks('host') . ':' . $port);

            return $this->gagal($e->getMessage());
        }

        if ($tahu !== $kini) {
            $this->simpanKunci($kunci, $kini);
        }
        $sesi = [
            'ip'       => $ip,
            'port'     => $port,
            'user'     => $user,
            'password' => FtpAkunModel::enkripsi($sandi),
            'mode'     => $mode,
            'sidik'    => $kini,
            'waktu'    => time(),
            'user_id'  => (int) session('user_id'),
        ];
        session()->set(self::SESI, $sesi);
        $this->catat('Login SSH ke ' . $this->label($sesi));

        return $this->response->setJSON(['ok' => true, 'label' => $this->label($sesi), 'mode' => $mode]);
    }

    /** POST Terminal/putus */
    public function putus()
    {
        if ($tolak = $this->tolak()) {
            return $tolak;
        }
        if ($sesi = $this->sesi()) {
            session()->remove(self::SESI);
            $this->catat('Putus SSH dari ' . $this->label($sesi));
        }

        return $this->response->setJSON(['ok' => true]);
    }

    // ================= BANTU =================

    /** Administrator sungguhan: kalau tabel hak akses tidak terbaca, Terminal tetap tertutup. */
    private function admin(): bool
    {
        $akses = HakAkses::instance();

        return $akses->tersedia() && $akses->isAdmin();
    }

    private function tolak(): ?ResponseInterface
    {
        $token = (string) session(self::SESI_TOKEN);
        if (! $this->admin()) {
            return $this->gagal('Terminal hanya untuk Administrator.', [], 403);
        }
        if ($token === '' || ! hash_equals($token, $this->request->getHeaderLine('X-Terminal-Token'))) {
            return $this->gagal('Sesi halaman sudah berakhir. Muat ulang halaman Terminal.', [], 403);
        }

        return null;
    }

    private function sesi(): ?array
    {
        $s = session(self::SESI);
        if (! is_array($s)) {
            return null;
        }
        if (time() - (int) ($s['waktu'] ?? 0) > self::HABIS || (int) ($s['user_id'] ?? 0) !== (int) session('user_id')) {
            session()->remove(self::SESI);

            return null;
        }

        return $s;
    }

    private function label(array $s): string
    {
        return $s['user'] . '@' . $s['ip'] . ((int) $s['port'] !== 22 ? ':' . $s['port'] : '');
    }

    private function teks(string $k): string
    {
        $v = $this->request->getPost($k);

        return is_string($v) ? trim($v) : '';
    }

    private function gagal(string $pesan, array $lain = [], int $kode = 200): ResponseInterface
    {
        return $this->response->setStatusCode($kode)->setJSON(['ok' => false, 'pesan' => $pesan] + $lain);
    }

    /** Catat di Change Log; nilai rahasia di dalam perintah disamarkan. */
    private function catat(string $teks): void
    {
        $teks = preg_replace('/([\w-]*(?:password|passwd|secret|passphrase|pre-shared-key|private-key|community|authentication-key|encryption-key)[\w-]*)(\s*=\s*|\s+(?:(?:cipher|simple|plain|irreversible-cipher|encrypted|\d)\s+)?)("(?:[^"\\\\]|\\\\.)*"|\S+)/i', '$1$2***', $teks) ?? 'perintah tidak terbaca';
        log_activity('terminal', 'terminal', null, 'Terminal - ' . mb_substr(TerminalAlat::utf8($teks), 0, 1000));
    }

    private function kunciTersimpan(): array
    {
        return is_file(self::FILE_KUNCI) ? (json_decode((string) file_get_contents(self::FILE_KUNCI), true) ?: []) : [];
    }

    private function simpanKunci(string $alamat, string $sidik): void
    {
        $semua          = $this->kunciTersimpan();
        $semua[$alamat] = $sidik;
        @file_put_contents(self::FILE_KUNCI, json_encode($semua, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);
    }
}
