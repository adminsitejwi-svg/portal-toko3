<?php

namespace App\Libraries;

use RuntimeException;

/**
 * Alat jaringan halaman Terminal (Informasi > Terminal): ping, tracert, nslookup, port, ip.
 *
 * Hanya perintah di daftar ini yang bisa jalan. Program dijalankan tanpa shell (proc_open dengan array),
 * jadi teks dari pengguna tidak pernah ditafsirkan sebagai perintah sistem operasi.
 */
class TerminalAlat
{
    public const BANTUAN = [
        'ping <host> [jumlah]'            => 'uji koneksi, jumlah 1-20 (bawaan 4)',
        'tracert <host>'                  => 'jalur ke host (maksimal 20 hop)',
        'nslookup <domain> [jenis]'       => 'DNS: A, AAAA, MX, NS, TXT, CNAME, SOA, PTR (bawaan A)',
        'port <host> <port>'              => 'tes port TCP terbuka atau tidak',
        'ip'                              => 'alamat IP server portal',
        'ssh <user>@<host> [-p port]'     => 'login SSH ke MikroTik / OLT / switch',
        'exit'                            => 'putuskan SSH',
        'clear'                           => 'bersihkan layar',
    ];

    private const DNS = [
        'A' => DNS_A, 'AAAA' => DNS_AAAA, 'MX' => DNS_MX, 'NS' => DNS_NS,
        'TXT' => DNS_TXT, 'CNAME' => DNS_CNAME, 'SOA' => DNS_SOA, 'PTR' => DNS_PTR,
    ];

    /** Jalankan satu baris perintah; mengembalikan teks hasil. */
    public function jalankan(string $baris): string
    {
        $arg    = preg_split('/\s+/', trim($baris)) ?: [];
        $nama   = strtolower((string) array_shift($arg));

        switch ($nama) {
            case 'ping':
                return $this->ping($arg);
            case 'tracert':
            case 'traceroute':
                return $this->tracert($arg);
            case 'nslookup':
            case 'dig':
                return $this->nslookup($arg);
            case 'port':
            case 'telnet':
                return $this->port($arg);
            case 'ip':
            case 'ipconfig':
                return $this->ipServer();
            default:
                throw new RuntimeException("'" . $nama . "' tidak tersedia. Ketik help untuk daftar perintah.");
        }
    }

    // ================= PERINTAH =================

    private function ping(array $arg): string
    {
        // ping host | ping host 10 | ping -n 10 host | ping -c 10 host
        $jumlah = 4;
        $host   = '';
        for ($i = 0; $i < count($arg); $i++) {
            if (in_array(strtolower($arg[$i]), ['-n', '-c'], true)) {
                $jumlah = (int) ($arg[++$i] ?? 4);
            } elseif ($host === '') {
                $host = $arg[$i];
            } else {
                $jumlah = (int) $arg[$i];
            }
        }
        $jumlah = max(1, min(20, $jumlah));
        $ip     = self::ipTujuan($host);
        $v6     = str_contains($ip, ':');

        $cmd = self::windows()
            ? ['ping', $v6 ? '-6' : '-4', '-n', (string) $jumlah, '-w', '1000', $ip]
            : ['ping', $v6 ? '-6' : '-4', '-c', (string) $jumlah, '-W', '1', $ip];
        $hasil = $this->proses([$cmd, array_values(array_filter($cmd, static fn ($a) => $a !== '-4'))], 45);

        return ($host !== $ip ? $host . ' = ' . $ip . "\n" : '') . $hasil;
    }

    private function tracert(array $arg): string
    {
        $host = (string) ($arg[0] ?? '');
        $ip   = self::ipTujuan($host);
        $v6   = str_contains($ip, ':');

        if (self::windows()) {
            $pilihan = [['tracert', $v6 ? '-6' : '-4', '-d', '-h', '20', '-w', '700', $ip]];
        } else {
            $a       = [$v6 ? '-6' : '-4', '-n', '-q', '1', '-w', '1', '-m', '20', $ip];
            $b       = ['-n', '-m', '20', $ip];
            $pilihan = [
                ['traceroute', ...$a], ['/usr/sbin/traceroute', ...$a], ['/usr/bin/traceroute', ...$a],
                ['tracepath', ...$b], ['/usr/sbin/tracepath', ...$b], ['/usr/bin/tracepath', ...$b],
            ];
        }

        return $this->proses($pilihan, 60);
    }

    private function nslookup(array $arg): string
    {
        $host  = rtrim((string) ($arg[0] ?? ''), '.');
        $jenis = strtoupper((string) ($arg[1] ?? 'A'));
        if (! isset(self::DNS[$jenis])) {
            throw new RuntimeException('Jenis DNS harus salah satu: ' . implode(', ', array_keys(self::DNS)) . '.');
        }
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            $nama = @gethostbyaddr($host);

            return $host . ' -> ' . ($nama && $nama !== $host ? $nama : 'tidak punya nama (PTR)');
        }
        if (! self::namaHost($host)) {
            throw new RuntimeException('Domain tidak valid. Contoh: nslookup dev.jwi.id MX');
        }
        $data = @dns_get_record($host, self::DNS[$jenis]);
        if (! $data) {
            return 'Tidak ada data ' . $jenis . ' untuk ' . $host . '.';
        }
        $baris = [];
        foreach ($data as $r) {
            $nilai = match ($r['type'] ?? '') {
                'A'     => $r['ip'],
                'AAAA'  => $r['ipv6'],
                'MX'    => $r['pri'] . ' ' . $r['target'],
                'TXT'   => '"' . $r['txt'] . '"',
                'SOA'   => $r['mname'] . ' ' . $r['rname'] . ' ' . $r['serial'],
                default => $r['target'] ?? '',
            };
            $baris[] = sprintf('%-30s %-6s %-6s %s', $r['host'] ?? $host, $r['ttl'] ?? '', $r['type'] ?? '', $nilai);
        }

        return implode("\n", $baris);
    }

    private function port(array $arg): string
    {
        // port host 80 | port host:80
        if (count($arg) === 1 && preg_match('/^([^:]+):(\d+)$/', $arg[0], $m)) {
            $arg = [$m[1], $m[2]];
        }
        $host  = (string) ($arg[0] ?? '');
        $nomor = (int) ($arg[1] ?? 0);
        if ($nomor < 1 || $nomor > 65535) {
            throw new RuntimeException('Port harus 1-65535. Contoh: port 192.168.34.1 8291');
        }
        $ip    = self::ipTujuan($host);
        $mulai = microtime(true);
        $fp    = @fsockopen(str_contains($ip, ':') ? '[' . $ip . ']' : $ip, $nomor, $kode, $pesan, 5);
        $ms    = (int) round((microtime(true) - $mulai) * 1000);
        if (! $fp) {
            return $ip . ':' . $nomor . ' TERTUTUP / tidak terjangkau (' . $ms . ' ms)' . ($pesan ? ' - ' . self::utf8(trim($pesan)) : '');
        }
        fclose($fp);

        return $ip . ':' . $nomor . ' TERBUKA (' . $ms . ' ms)';
    }

    private function ipServer(): string
    {
        $alamat = array_values(array_filter(self::ipSendiri(), static fn ($ip) => ! self::loopback($ip) && ! preg_match('/^(169\.254\.|fe80:)/i', $ip)));

        return 'Perintah dijalankan dari server portal: ' . ($alamat ? implode(', ', $alamat) : 'alamat tidak terbaca');
    }

    // ================= ALAMAT =================

    /**
     * Ubah host menjadi IP dan pastikan bukan server portal sendiri.
     * Pemanggil harus memakai IP hasilnya (bukan nama host) supaya tidak bisa dialihkan DNS.
     */
    public static function ipTujuan(string $host): string
    {
        $host = trim($host, " \t[]");
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            $ip = $host;
        } elseif (self::namaHost($host)) {
            $ip = gethostbyname($host);
            if (! filter_var($ip, FILTER_VALIDATE_IP)) {
                throw new RuntimeException('Host ' . $host . ' tidak ditemukan di DNS.');
            }
        } else {
            throw new RuntimeException('Host / IP tidak valid.');
        }
        if (self::terlarang($ip)) {
            throw new RuntimeException('Alamat ' . $ip . ' adalah server portal sendiri atau alamat khusus, tidak boleh dituju dari Terminal.');
        }

        return $ip;
    }

    private static function namaHost(string $host): bool
    {
        // angka saja (mis. 2130706433 / 0x7f.1) bisa dibaca sebagai IP, jadi harus ada huruf & bukan 0x
        return strlen($host) <= 253
            && preg_match('/^(?=.*[a-z])[a-z0-9_]([a-z0-9_-]{0,62})(\.[a-z0-9_]([a-z0-9_-]{0,62}))*$/i', $host) === 1
            && ! preg_match('/^(0x[0-9a-f]+|\d+)(\.(0x[0-9a-f]+|\d+))*$/i', $host);
    }

    private static function loopback(string $ip): bool
    {
        return $ip === '::1' || str_starts_with($ip, '127.');
    }

    private static function terlarang(string $ip): bool
    {
        $biner = inet_pton($ip);
        // IPv4 yang ditulis sebagai IPv6 (::ffff:127.0.0.1 atau ::127.0.0.1)
        if (strlen($biner) === 16 && (str_starts_with($biner, str_repeat("\0", 10) . "\xff\xff") || str_starts_with($biner, str_repeat("\0", 12)))) {
            $biner = substr($biner, 12);
            if ($biner === "\0\0\0\1" || $biner === "\0\0\0\0") {
                return true; // ::1 dan ::
            }
        }
        if (strlen($biner) === 4) {
            [$a, $b] = [ord($biner[0]), ord($biner[1])];
            if ($a === 0 || $a === 127 || $a >= 224 || ($a === 169 && $b === 254)) {
                return true;
            }
        } elseif (ord($biner[0]) === 0xff || (ord($biner[0]) === 0xfe && (ord($biner[1]) & 0xc0) === 0x80)) {
            return true; // multicast & link-local IPv6
        }
        foreach (self::ipSendiri() as $sendiri) {
            if (@inet_pton($sendiri) === $biner) {
                return true;
            }
        }

        return false;
    }

    /** IP server portal ini (antarmuka, nama komputer, dan domain portal). */
    private static function ipSendiri(): array
    {
        static $daftar = null;
        if ($daftar !== null) {
            return $daftar;
        }
        $ip = [(string) ($_SERVER['SERVER_ADDR'] ?? '')];
        foreach ([gethostname(), parse_url(config('App')->baseURL, PHP_URL_HOST)] as $nama) {
            if ($nama) {
                $ip = [...$ip, ...(@gethostbynamel($nama) ?: [])];
            }
        }
        if (function_exists('net_get_interfaces')) {
            foreach (@net_get_interfaces() ?: [] as $if) {
                foreach ($if['unicast'] ?? [] as $u) {
                    $ip[] = (string) ($u['address'] ?? '');
                }
            }
        }

        return $daftar = array_values(array_unique(array_filter($ip, static fn ($x) => (bool) filter_var($x, FILTER_VALIDATE_IP))));
    }

    // ================= PROSES =================

    private static function windows(): bool
    {
        return DIRECTORY_SEPARATOR === '\\';
    }

    /**
     * Jalankan program pertama yang tersedia dari $pilihan (tanpa shell), dengan batas waktu.
     * Keluaran ditampung di file sementara karena pipe di Windows tidak bisa dibaca tanpa menunggu.
     */
    private function proses(array $pilihan, int $detik): string
    {
        $mati = array_map('trim', explode(',', (string) ini_get('disable_functions')));
        if (! function_exists('proc_open') || in_array('proc_open', $mati, true)) {
            throw new RuntimeException('proc_open dimatikan di server, jadi ping & tracert tidak bisa dijalankan. Perintah port, nslookup dan ssh tetap bisa dipakai.');
        }

        foreach ($pilihan as $cmd) {
            $file = tempnam(sys_get_temp_dir(), 'trm');
            try {
                $p = @proc_open($cmd, [0 => ['pipe', 'r'], 1 => ['file', $file, 'w'], 2 => ['file', $file, 'a']], $pipa, null, null, ['bypass_shell' => true]);
                if (! is_resource($p)) {
                    continue;
                }
                fclose($pipa[0]);
                $batas = microtime(true) + $detik;
                $habis = false;
                while (($st = proc_get_status($p))['running']) {
                    if (microtime(true) > $batas) {
                        proc_terminate($p, 9);
                        $habis = true;
                        break;
                    }
                    usleep(100000);
                }
                $kode = $st['exitcode'];
                proc_close($p);
                $teks = self::utf8(trim((string) @file_get_contents($file, false, null, 0, 65536)));
                if ($kode === 127 || (! $habis && $kode !== 0 && preg_match('/invalid option|unrecognized option/i', $teks))) {
                    continue; // program tidak ada / versi lain: coba pilihan berikutnya
                }

                return ($teks !== '' ? $teks : '(tidak ada keluaran)') . ($habis ? "\n[dihentikan setelah " . $detik . ' detik]' : '');
            } finally {
                @unlink($file);
            }
        }

        throw new RuntimeException('Program ' . basename($pilihan[0][0]) . ' tidak tersedia di server.');
    }

    /** Teks aman untuk JSON: UTF-8 valid, tanpa kode warna terminal & karakter kendali. */
    public static function utf8(string $teks): string
    {
        if (! mb_check_encoding($teks, 'UTF-8')) {
            $teks = mb_convert_encoding($teks, 'UTF-8', 'Windows-1252');
        }
        $teks = preg_replace('/\x1b(?:\[[0-?]*[ -\/]*[@-~]|\][^\x07\x1b]*(?:\x07|\x1b\\\\)|[()][0-9A-B]|[=>78DEHMZc])/', '', $teks) ?? $teks;
        $teks = preg_replace('/\r+\n/', "\n", $teks) ?? $teks;
        $teks = preg_replace('/[^\n]*\r(?!\n)(?=[^\n])/', '', $teks) ?? $teks; // baris yang ditimpa
        while (preg_match('/[^\n\x08]\x08/', $teks)) {
            $teks = preg_replace('/[^\n\x08]\x08/', '', $teks);
        }

        return preg_replace('/[\x00-\x08\x0b-\x1f\x7f]/', '', $teks) ?? $teks;
    }
}
