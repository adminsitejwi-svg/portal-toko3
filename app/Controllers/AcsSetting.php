<?php

namespace App\Controllers;

use App\Models\AcsModel;

// Halaman ACS Settings (menu ACS JWI Group > Settings): alamat & akun ACS yang diisi di ONT (TR-069), uji koneksi.
// Daftar ONT ada di Controllers/AcsDevice, log koneksi di Controllers/AcsLog, server TR-069-nya ada di Controllers/AcsCwmp.
class AcsSetting extends BaseController
{
    /** @var AcsModel */
    protected $model;

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta'); // waktu lokal (WIB), sama dengan halaman lain
        $this->model = new AcsModel();
    }

    private function token(): string
    {
        $t = (string) session('acs_token');
        if ($t === '') {
            $t = bin2hex(random_bytes(32));
            session()->set('acs_token', $t);
        }

        return $t;
    }

    private function tokenValid(): bool
    {
        $t = (string) session('acs_token');

        return $t !== '' && hash_equals($t, (string) $this->request->getPost('token'));
    }

    private function jsonTolak(int $kode, string $pesan)
    {
        return $this->response->setStatusCode($kode)->setJSON(['ok' => false, 'pesan' => $pesan]);
    }

    // Halaman utama (view AcsSetting/index.php)
    public function index()
    {
        if (! AcsModel::siap()) {
            return view('AcsSetting/index', ['tabelSiap' => false]);
        }
        $s                 = $this->model->setting();
        $s['ada_password'] = AcsModel::dekripsi($s['acs_password']) !== '';
        unset($s['acs_password']);

        return view('AcsSetting/index', [
            'tabelSiap' => true,
            'kunciSiap' => (string) config('Encryption')->key !== '',
            's'         => $s,
            'ringkas'   => $this->model->ringkasOnt(),
            'koneksi'   => $this->model->statusKoneksi($this->model->logTerakhir()),
            'token'     => $this->token(),
        ]);
    }

    // POST AcsSetting/simpan
    public function simpan()
    {
        if (! AcsModel::siap() || ! $this->tokenValid()) {
            return redirect()->to(site_url('AcsSetting'))->with('error', 'Token keamanan tidak valid, muat ulang halaman.');
        }
        if ((string) config('Encryption')->key === '') {
            return redirect()->to(site_url('AcsSetting'))->with('error', 'encryption.key di .env belum diisi.');
        }

        $url      = AcsModel::domain((string) $this->request->getPost('acs_url'));
        $user     = trim((string) $this->request->getPost('acs_username'));
        $password = (string) $this->request->getPost('acs_password');
        $salah    = null;

        if (strlen($url) > 255 || ! preg_match('/^[a-z0-9]([a-z0-9-]*[a-z0-9])?(\.[a-z0-9]([a-z0-9-]*[a-z0-9])?)+(:\d{1,5})?$/', $url)) {
            $salah = 'ACS URL cukup domain atau IP, contoh: dev.jwi.id';
        } elseif (! preg_match('/^[A-Za-z0-9._@-]{3,64}$/', $user)) {
            $salah = 'ACS Username 3-64 karakter: huruf, angka, titik, garis bawah, strip atau @.';
        } elseif ($password !== '' && ! preg_match('/^[\x21-\x7E]{8,64}$/', $password)) {
            $salah = 'ACS Password 8-64 karakter tanpa spasi.';
        }
        if ($salah) {
            return redirect()->to(site_url('AcsSetting'))->with('error', $salah);
        }

        $this->model->simpanSetting([
            'acs_url'      => $url,
            'acs_username' => $user,
        ], $password === '' ? null : $password, (string) session('username'));

        return redirect()->to(site_url('AcsSetting'))->with('success', 'Pengaturan ACS berhasil disimpan. ONT dengan URL, username dan password yang sama langsung terhubung.');
    }

    // GET AcsSetting/status?sejak= (JSON): ONT yang terhubung & login ditolak, untuk notifikasi halaman Settings
    public function status()
    {
        if (! AcsModel::siap()) {
            return $this->jsonTolak(503, 'Tabel belum ada.');
        }
        session_write_close();

        return $this->response->setHeader('Cache-Control', 'no-store')
            ->setJSON(['ok' => true] + $this->model->statusKoneksi(max(0, (int) $this->request->getGet('sejak'))));
    }

    // POST AcsSetting/password (JSON): ACS Password tersimpan, untuk diisi di ONT
    public function password()
    {
        if (! AcsModel::siap() || ! $this->tokenValid()) {
            return $this->jsonTolak(403, 'Token keamanan tidak valid.');
        }

        return $this->response->setHeader('Cache-Control', 'no-store')
            ->setJSON(['ok' => true, 'password' => AcsModel::dekripsi($this->model->setting()['acs_password'])]);
    }

    // POST AcsSetting/uji (JSON): server mencoba ACS URL-nya sendiri (http seperti ONT, lalu https),
    // tanpa login harus 401, dengan login harus 204. Tidak mengetes ONT, jadi pesannya menyebut jumlah ONT online.
    public function uji()
    {
        if (! AcsModel::siap() || ! $this->tokenValid()) {
            return $this->jsonTolak(403, 'Token keamanan tidak valid.');
        }
        session_write_close();
        $s     = $this->model->setting();
        $sandi = AcsModel::dekripsi($s['acs_password']);
        if ($sandi === '') {
            return $this->jsonTolak(422, 'ACS Password belum diisi. Simpan dulu pengaturannya.');
        }
        if (! function_exists('curl_init')) {
            return $this->jsonTolak(500, 'Ekstensi curl PHP tidak aktif.');
        }

        foreach (['http://', 'https://'] as $skema) {
            $url    = $skema . $s['acs_url'] . '/';
            [$kode] = $this->panggil($url, null);
            if ($kode !== 401) {
                continue;
            }
            [$kode] = $this->panggil($url, $s['acs_username'] . ':' . $sandi);
            if ($kode === 204) {
                $online = $this->model->ringkasOnt()['online'];
                if ($online > 0) {
                    $pesan = $online . ' ONT sedang terhubung.';
                } else {
                    $pesan = 'Belum ada ONT yang terhubung. Isi di ONT: ' . $skema . $s['acs_url'] . ', username dan password sama seperti di halaman ini.';
                }
                if ($skema === 'https://') {
                    $pesan .= ' Alamat http:// tidak menjawab sebagai ACS, ONT lama bisa gagal terhubung lewat https://.';
                }

                return $this->response->setJSON(['ok' => true, 'pesan' => $pesan]);
            }

            return $this->jsonTolak(502, 'ACS URL terjangkau, tetapi login gagal. Server tidak meneruskan header Authorization, atau domain mengarah ke server lain.');
        }

        return $this->jsonTolak(502, 'ACS URL tidak menjawab sebagai server ACS. Periksa domainnya, dan pastikan file ACS terbaru sudah diupload ke server itu.');
    }

    /** @return array{0: int, 1: string} */
    private function panggil(string $url, ?string $auth): array
    {
        $c   = curl_init($url);
        $opt = [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => '',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT        => 10,
            CURLOPT_PROTOCOLS      => CURLPROTO_HTTP | CURLPROTO_HTTPS,
            CURLOPT_HTTPHEADER     => ['Content-Type: text/xml', 'X-ACS-Uji: 1'],
        ];
        if ($auth !== null) {
            $opt += [CURLOPT_HTTPAUTH => CURLAUTH_BASIC, CURLOPT_USERPWD => $auth];
        }
        curl_setopt_array($c, $opt);
        curl_exec($c);
        $kode = (int) curl_getinfo($c, CURLINFO_RESPONSE_CODE);
        $err  = curl_error($c);

        return [$kode, $err];
    }
}
