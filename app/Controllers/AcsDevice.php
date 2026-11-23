<?php

namespace App\Controllers;

use App\Libraries\AcsData;
use App\Libraries\HakAkses;
use App\Models\AcsModel;

// Halaman Devices (menu ACS JWI Group > Devices): daftar ONT yang terhubung lewat TR-069 dan detailnya
// (info, WAN, optik, WiFi, client, blokir MAC, parameter, riwayat), mengikuti Devices speednet_acs.
// Perintah yang mengubah ONT ada di Controllers/AcsKontrol.
class AcsDevice extends BaseController
{
    /** @var AcsModel */
    protected $model;

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta'); // waktu lokal (WIB), sama dengan halaman lain
        $this->model = new AcsModel();
    }

    private function siap(): bool
    {
        return AcsModel::siap() && AcsModel::dataSiap();
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

    private function tokenValid(): bool
    {
        $t = (string) session('acs_token');

        return $t !== '' && hash_equals($t, (string) $this->request->getPost('token'));
    }

    // Daftar ONT (view AcsDevice/index.php)
    public function index()
    {
        if (! $this->siap()) {
            return view('AcsDevice/index', ['tabelSiap' => false]);
        }

        return view('AcsDevice/index', [
            'tabelSiap' => true,
            'ont'       => $this->model->semuaOnt(),
            'token'     => $this->token(),
        ]);
    }

    // Detail satu ONT (view AcsDevice/detail.php)
    public function detail($id)
    {
        $d = $this->siap() ? $this->model->detailOnt((int) $id) : null;
        if (! $d) {
            return redirect()->to(site_url('AcsDevice'))->with('error', 'ONT tidak ditemukan.');
        }
        $data = $this->model->dataOnt((int) $id);
        $p    = static fn (string $grup): array => $data[$grup]['p'] ?? [];

        $semua = [];
        foreach ($data as $isi) {
            $semua += $isi['p'];
            foreach ($isi['rahasia'] as $n) {
                $semua[$n] = null; // rahasia: hanya ditampilkan lewat tombol di tab WiFi
            }
        }
        ksort($semua, SORT_NATURAL);

        return view('AcsDevice/detail', [
            'd'      => $d,
            'waktu'  => array_map(static fn ($x) => $x['waktu'], $data),
            'info'   => AcsData::info($p('info')),
            'optik'  => AcsData::optik($p('optik')),
            'wifi'   => AcsData::wifi($p('wifi'), $data['wifi']['rahasia'] ?? [], $data['wifi']['terisi'] ?? [], $d['manufacturer']),
            'blokir' => AcsData::blokir($p('mac')),
            'ping'   => AcsData::ping($p('ping')),
            'wanP'   => $p('wan'),
            'kontrol' => HakAkses::instance()->bisa('AcsKontrol'),
            'client' => AcsData::client($p('host'), $p('wifi')),
            'semua'  => $semua,
            'tugas'  => $this->model->tugasRingkas((int) $id),
            'token'  => $this->token(),
        ]);
    }

    // GET AcsDevice/tugas/{id} (JSON): perintah terbaru, untuk memantau hasilnya di halaman detail
    public function tugas($id)
    {
        if (! $this->siap()) {
            return $this->tolak(503, 'Tabel belum ada.');
        }
        session_write_close();

        return $this->response->setHeader('Cache-Control', 'no-store')->setJSON(['ok' => true, 'tugas' => $this->model->tugasRingkas((int) $id, 15)]);
    }

    // POST AcsDevice/baca/{id} (JSON): baca ulang semua data ONT pada Inform berikutnya
    public function baca($id)
    {
        if (! $this->siap() || ! $this->tokenValid()) {
            return $this->tolak(403, 'Token keamanan tidak valid, muat ulang halaman.');
        }
        $d = $this->model->find((int) $id);
        if (! $d) {
            return $this->tolak(404, 'ONT tidak ditemukan.');
        }
        $this->model->antreBaca($d, true);

        return $this->response->setJSON(['ok' => true, 'pesan' => 'Masuk antrean. Data dibaca saat ONT mengirim Inform berikutnya.']);
    }

    // POST AcsDevice/password/{idWan} (JSON): password PPPoE (kalau ONT mengirimnya)
    public function password($id)
    {
        if (! $this->siap() || ! $this->tokenValid()) {
            return $this->tolak(403, 'Token keamanan tidak valid, muat ulang halaman.');
        }
        $p = $this->model->passwordWan((int) $id);

        return $p === null ? $this->tolak(404, 'Data tidak ditemukan.')
            : $this->response->setHeader('Cache-Control', 'no-store')->setJSON(['ok' => true, 'password' => $p]);
    }

    // POST AcsDevice/rahasia/{id} (JSON, nama = parameter WiFi): password WiFi (kalau ONT mengirimnya)
    public function rahasia($id)
    {
        if (! $this->siap() || ! $this->tokenValid()) {
            return $this->tolak(403, 'Token keamanan tidak valid, muat ulang halaman.');
        }
        $p = $this->model->rahasiaOnt((int) $id, 'wifi', (string) $this->request->getPost('nama'));

        return $p === null ? $this->tolak(404, 'Data tidak ditemukan.')
            : $this->response->setHeader('Cache-Control', 'no-store')->setJSON(['ok' => true, 'password' => $p]);
    }
}
