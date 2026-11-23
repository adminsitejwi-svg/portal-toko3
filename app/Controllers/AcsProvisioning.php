<?php

namespace App\Controllers;

use App\Libraries\AcsData;
use App\Models\AcsModel;
use App\Models\AcsProvisionModel;

// Halaman Provisioning (menu ACS JWI Group > Provisioning), mengikuti Provisioning Profiles + Add ONT speednet_acs.
// Penerapan ke ONT dilakukan AcsCwmp saat Inform (AcsProvisionModel::terapkan).
class AcsProvisioning extends BaseController
{
    private const TAMPIL = ['menunggu', 'berhasil', 'gagal'];

    /** Batas SN sekali daftar. */
    private const MAKS_SN = 200;

    /** @var AcsProvisionModel */
    protected $model;

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta'); // waktu lokal (WIB), sama dengan halaman lain
        $this->model = new AcsProvisionModel();
    }

    private function siap(): bool
    {
        return AcsModel::siap() && AcsModel::dataSiap() && AcsProvisionModel::siap();
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

    private function tolak(int $kode, string $pesan)
    {
        return $this->response->setStatusCode($kode)->setJSON(['ok' => false, 'pesan' => $pesan]);
    }

    /** Pesan tampil di bar hijau "Berhasil" setelah halaman dimuat ulang, sama seperti halaman lain. */
    private function berhasil(string $pesan)
    {
        session()->setFlashdata('success', esc($pesan));

        return $this->response->setJSON(['ok' => true, 'pesan' => $pesan]);
    }

    /** null = boleh lanjut. */
    private function periksa()
    {
        $t = (string) session('acs_token');
        if (! $this->siap() || $t === '' || ! hash_equals($t, (string) $this->request->getPost('token'))) {
            return $this->tolak(403, 'Token keamanan tidak valid, muat ulang halaman.');
        }

        return null;
    }

    private function teks(string $k): string
    {
        $v = $this->request->getPost($k);

        return is_string($v) ? trim($v) : '';
    }

    private function log(string $aksi, string $tabel, $id, array $isi): void
    {
        helper('activity');
        log_activity($aksi, $tabel, $id, null, null, $isi);
    }

    // Halaman utama (view AcsProvisioning/index.php); ?lihat=profil, ?tampil=menunggu|berhasil|gagal
    public function index()
    {
        if (! $this->siap()) {
            return view('AcsProvisioning/index', ['tabelSiap' => false]);
        }
        $tampil = $this->request->getGet('tampil');
        $ont    = $this->model->semuaOnt();
        $profil = array_map(static function ($p) {
            foreach ($p['aturan'] as &$x) { // nilai rahasia tidak dikirim ke browser
                if (! empty($x['enk'])) {
                    $x['v'] = '';
                }
            }

            return $p;
        }, $this->model->semuaProfil());

        return view('AcsProvisioning/index', [
            'tabelSiap' => true,
            'lihat'     => $this->request->getGet('lihat') === 'profil' ? 'profil' : 'ont',
            'tampil'    => in_array($tampil, self::TAMPIL, true) ? $tampil : '',
            'ont'       => $ont,
            'profil'    => $profil,
            'token'     => $this->token(),
        ]);
    }

    // GET AcsProvisioning/parameter (JSON): saran nama parameter dari ONT yang pernah terbaca
    public function parameter()
    {
        session_write_close();

        return $this->siap() ? $this->response->setJSON(['ok' => true, 'nama' => $this->model->saranParameter()]) : $this->tolak(503, 'Tabel belum ada.');
    }

    // POST AcsProvisioning/profil: id (0 = baru), nama, merek, model, aktif, aturan (JSON [{n, v, t, tetap}])
    public function profil()
    {
        if ($x = $this->periksa()) {
            return $x;
        }
        $id   = (int) $this->teks('id');
        $lama = $id ? $this->model->profil($id) : null;
        if ($id && ! $lama) {
            return $this->tolak(404, 'Profil tidak ditemukan.');
        }
        $nama = $this->teks('nama');
        foreach (['Nama profil' => $nama, 'Merek' => $this->teks('merek'), 'Model' => $this->teks('model')] as $l => $v) {
            if (mb_strlen($v) > 128) {
                return $this->tolak(422, $l . ' maksimal 128 karakter.');
            }
        }
        if ($nama === '') {
            return $this->tolak(422, 'Nama profil wajib diisi.');
        }
        if ($this->model->namaDipakai($nama, $id)) {
            return $this->tolak(422, 'Nama profil sudah dipakai.');
        }
        $aturan = $this->aturan(json_decode($this->teks('aturan'), true), $lama['aturan'] ?? []);
        if (is_string($aturan)) {
            return $this->tolak(422, $aturan);
        }
        $id = $this->model->simpanProfil($id, [
            'nama'       => $nama,
            'merek'      => $this->teks('merek') ?: null,
            'model'      => $this->teks('model') ?: null,
            'aktif'      => $this->teks('aktif') === '0' ? 0 : 1,
            'aturan'     => $aturan,
            'updated_by' => mb_substr((string) session('username'), 0, 100),
        ]);
        $this->log($lama ? 'update' : 'create', 'd_acs_profile', $id, ['nama' => $nama, 'parameter' => implode(', ', array_column($aturan, 'n'))]);

        return $this->berhasil('Profil provisioning berhasil ' . ($lama ? 'diperbarui.' : 'ditambahkan.'));
    }

    /** @return array|string daftar aturan siap simpan, atau pesan kesalahan */
    private function aturan($isi, array $lama)
    {
        if (! is_array($isi) || ! $isi || count($isi) > AcsProvisionModel::MAKS_ATURAN) {
            return 'Isi 1 sampai ' . AcsProvisionModel::MAKS_ATURAN . ' parameter.';
        }
        $rahasiaLama = array_column(array_filter($lama, static fn ($x) => ! empty($x['enk'])), 'v', 'n');
        $hasil       = [];
        foreach (array_values($isi) as $i => $x) {
            $no = 'Parameter nomor ' . ($i + 1) . ': ';
            $n  = is_string($x['n'] ?? null) ? trim($x['n']) : '';
            $v  = is_string($x['v'] ?? null) ? $x['v'] : '';
            $t  = in_array($x['t'] ?? null, AcsProvisionModel::TIPE, true) ? $x['t'] : 'xsd:string';
            if (strlen($n) > 255 || ! preg_match('/^(InternetGatewayDevice|Device)(\.[A-Za-z0-9_-]+)+$/', $n)) {
                return $no . 'path harus lengkap, contoh InternetGatewayDevice.LANDevice.1.WLANConfiguration.1.SSID';
            }
            if (preg_match(AcsProvisionModel::POLA_DILARANG, $n)) {
                return $no . 'alamat & akun ACS tidak boleh diubah lewat profil karena bisa memutus ONT dari ACS.';
            }
            if (isset($hasil[$n])) {
                return $no . 'parameter ini sudah ada di profil.';
            }
            $rahasia = (bool) preg_match(AcsData::POLA_RAHASIA, $n);
            if ($rahasia && $v === '' && ! empty($x['tetap']) && isset($rahasiaLama[$n])) {
                $hasil[$n] = ['n' => $n, 'v' => $rahasiaLama[$n], 't' => $t, 'enk' => true];

                continue;
            }
            if (mb_strlen($v) > 256 || preg_match('/[\x00-\x1F\x7F]/', $v) || ! mb_check_encoding($v, 'UTF-8')) {
                return $no . 'nilai maksimal 256 karakter.';
            }
            $pola = ['xsd:boolean' => '/^(0|1|true|false)$/i', 'xsd:int' => '/^-?\d{1,10}$/', 'xsd:unsignedInt' => '/^\d{1,10}$/'][$t] ?? null;
            if ($pola && ! preg_match($pola, $v)) {
                return $no . 'nilai tidak sesuai tipe ' . substr($t, 4) . '.';
            }
            if ($rahasia && $v === '') {
                return $no . 'password / kunci wajib diisi.';
            }
            $hasil[$n] = $rahasia ? ['n' => $n, 'v' => AcsModel::enkripsi($v), 't' => $t, 'enk' => true] : ['n' => $n, 'v' => $v, 't' => $t];
        }

        return array_values($hasil);
    }

    // POST AcsProvisioning/hapusProfil/{id}
    public function hapusProfil($id)
    {
        if ($x = $this->periksa()) {
            return $x;
        }
        $p = $this->model->profil((int) $id);
        if (! $p) {
            return $this->tolak(404, 'Profil tidak ditemukan.');
        }
        if (! $this->model->hapusProfil((int) $id)) {
            return $this->tolak(409, 'Profil masih dipakai ONT yang belum selesai. Hapus ONT itu dulu, atau nonaktifkan profilnya.');
        }
        $this->log('delete', 'd_acs_profile', (int) $id, ['nama' => $p['nama']]);

        return $this->berhasil('Profil provisioning berhasil dihapus.');
    }

    // POST AcsProvisioning/ont: sn (satu per baris), profil
    public function ont()
    {
        if ($x = $this->periksa()) {
            return $x;
        }
        $p = $this->model->profil((int) $this->teks('profil'));
        if (! $p || ! (int) $p['aktif']) {
            return $this->tolak(422, 'Pilih profil provisioning yang aktif.');
        }
        $sn = array_values(array_unique(array_filter(array_map('trim', preg_split('/[\r\n,;]+/', $this->teks('sn'))), static fn ($s) => $s !== '')));
        if (! $sn || count($sn) > self::MAKS_SN) {
            return $this->tolak(422, 'Isi 1 sampai ' . self::MAKS_SN . ' serial number.');
        }
        foreach ($sn as $s) {
            if (! preg_match('/^[A-Za-z0-9._:-]{4,64}$/', $s)) {
                return $this->tolak(422, 'Serial number tidak valid: ' . mb_substr($s, 0, 40));
            }
        }
        $jumlah = ['baru' => 0, 'ulang' => 0, '' => 0];
        foreach ($sn as $s) {
            $jumlah[(string) $this->model->daftarkan($s, (int) $p['id'], (string) session('username'))]++;
        }
        $this->log('create', 'd_acs_provision', null, ['profil' => $p['nama'], 'serial_number' => mb_substr(implode(', ', $sn), 0, 1000)]);

        return $this->berhasil($jumlah['baru'] . ' ONT berhasil ditambahkan'
            . ($jumlah['ulang'] ? ', ' . $jumlah['ulang'] . ' ONT lama akan diterapkan ulang' : '')
            . ($jumlah[''] ? ', ' . $jumlah[''] . ' dilewati karena sedang diproses' : '') . '. Profil diterapkan saat ONT mengirim Inform.');
    }

    // POST AcsProvisioning/ubahOnt/{id}: sn, profil
    public function ubahOnt($id)
    {
        if ($x = $this->periksa()) {
            return $x;
        }
        $o = $this->model->ont((int) $id);
        if (! $o) {
            return $this->tolak(404, 'ONT tidak ditemukan.');
        }
        $sn = $this->teks('sn');
        $p  = $this->model->profil((int) $this->teks('profil'));
        if (! preg_match('/^[A-Za-z0-9._:-]{4,64}$/', $sn)) {
            return $this->tolak(422, 'Serial number tidak valid.');
        }
        if (! $p || ! (int) $p['aktif']) {
            return $this->tolak(422, 'Pilih profil provisioning yang aktif.');
        }
        if ($sn === $o['serial_number'] && (int) $p['id'] === (int) $o['profil_id']) {
            return $this->tolak(422, 'Tidak ada yang diubah.');
        }
        if ($salah = $this->model->ubahOnt($o, $sn, (int) $p['id'], (string) session('username'))) {
            return $this->tolak(409, $salah);
        }
        $this->log('update', 'd_acs_provision', (int) $id, ['serial_number' => $sn, 'profil' => $p['nama']]);

        return $this->berhasil('Data ONT berhasil diperbarui. Profil diterapkan saat ONT mengirim Inform.');
    }

    // POST AcsProvisioning/ulangi/{id}
    public function ulangi($id)
    {
        if ($x = $this->periksa()) {
            return $x;
        }
        $o = $this->model->ont((int) $id);
        $p = $o ? $this->model->profil((int) $o['profil_id']) : null;
        if (! $o || ! $p) {
            return $this->tolak(404, $o ? 'Profilnya sudah dihapus. Daftarkan ulang ONT dengan profil lain.' : 'ONT tidak ditemukan.');
        }
        if ($this->model->daftarkan($o['serial_number'], (int) $o['profil_id'], (string) session('username')) === null) {
            return $this->tolak(409, 'ONT ini sedang diproses.');
        }
        $this->log('update', 'd_acs_provision', (int) $id, ['serial_number' => $o['serial_number'], 'profil' => $p['nama'], 'aksi' => 'terapkan ulang']);

        return $this->berhasil('Profil diterapkan ulang saat ONT mengirim Inform.');
    }

    // POST AcsProvisioning/hapusOnt/{id}: hanya dari daftar, konfigurasi di ONT tidak berubah
    public function hapusOnt($id)
    {
        if ($x = $this->periksa()) {
            return $x;
        }
        $o = $this->model->ont((int) $id);
        if (! $o) {
            return $this->tolak(404, 'ONT tidak ditemukan.');
        }
        if (! $this->model->hapusOnt($o, new AcsModel(), (string) session('username'))) {
            return $this->tolak(409, 'Perintah sudah terkirim ke ONT, tunggu hasilnya dulu.');
        }
        $this->log('delete', 'd_acs_provision', (int) $id, ['serial_number' => $o['serial_number']]);

        return $this->berhasil('ONT berhasil dihapus dari daftar provisioning.');
    }
}
