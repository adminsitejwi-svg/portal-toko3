<?php

namespace App\Controllers;

use App\Libraries\AcsData;
use App\Models\AcsModel;

// Perintah yang MENGUBAH ONT lewat TR-069 (tombol di halaman detail Devices): profile client (SSID, password, maksimal
// client), blokir / buka blokir MAC, ping client dan reboot. Hak akses terpisah: 'AcsKontrol'.
// Perintah masuk antrean dan dijalankan saat ONT Inform; kedaluwarsa kalau terlalu lama menunggu, tidak dikirim ulang otomatis.
class AcsKontrol extends BaseController
{
    /** @var AcsModel */
    protected $model;

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta'); // waktu lokal (WIB), sama dengan halaman lain
        $this->model = new AcsModel();
    }

    private function tolak(int $kode, string $pesan)
    {
        return $this->response->setStatusCode($kode)->setJSON(['ok' => false, 'pesan' => $pesan]);
    }

    /** ONT tujuan, atau jawaban penolakan. */
    private function ont($id)
    {
        $t = (string) session('acs_token');
        if ($t === '' || ! hash_equals($t, (string) $this->request->getPost('token'))) {
            return $this->tolak(403, 'Token keamanan tidak valid, muat ulang halaman.');
        }
        $d = AcsModel::siap() && AcsModel::dataSiap() ? $this->model->detailOnt((int) $id) : null;
        if (! $d) {
            return $this->tolak(404, 'ONT tidak ditemukan.');
        }
        if (! $d['online']) {
            return $this->tolak(422, 'ONT sedang offline, perintah tidak bisa dikirim.');
        }

        return $d;
    }

    private function teks(string $k): string
    {
        $v = $this->request->getPost($k);

        return is_string($v) ? $v : '';
    }

    /** Masukkan ke antrean + catat di Change Log (tanpa password). */
    private function antre(array $d, string $label, string $perintah, array $payload)
    {
        $id = $this->model->antreTulis((int) $d['id'], $perintah, ['label' => $label, 'oleh' => (string) session('username')] + $payload);
        if ($id === null) {
            return $this->tolak(409, 'Masih ada perintah yang menunggu untuk ONT ini. Tunggu selesai atau batalkan di tab Riwayat.');
        }
        helper('activity');
        log_activity('update', 'd_acs_device', (int) $d['id'], null, null, ['perintah' => $label, 'serial_number' => $d['serial_number']]);

        return $this->response->setJSON(['ok' => true, 'tugas' => $id, 'pesan' => 'Perintah masuk antrean, dijalankan saat ONT mengirim Inform berikutnya.']);
    }

    private function grup(array $d, string $grup): array
    {
        return $this->model->dataOnt((int) $d['id'])[$grup] ?? ['p' => [], 'rahasia' => [], 'terisi' => []];
    }

    // POST AcsKontrol/wifi/{id}: no, ssid, password (kosong = tetap), maks (kosong = tetap)
    public function wifi($id)
    {
        $d = $this->ont($id);
        if (! is_array($d)) {
            return $d;
        }
        $g = $this->grup($d, 'wifi');
        $w = null;
        foreach (AcsData::wifi($g['p'], $g['rahasia'], $g['terisi'], $d['manufacturer']) as $x) {
            if ($x['no'] === (int) $this->teks('no')) {
                $w = $x;
            }
        }
        if (! $w) {
            return $this->tolak(404, 'WiFi tidak ditemukan. Klik Baca Ulang dulu.');
        }

        $ssid  = trim($this->teks('ssid'));
        $sandi = $this->teks('password');
        $maks  = trim($this->teks('maks'));
        $ubah  = [];
        $apa   = [];
        if ($ssid !== $w['ssid']) {
            if (! preg_match('/^[\x20-\x7E]{1,32}$/', $ssid)) {
                return $this->tolak(422, 'SSID 1-32 karakter huruf, angka atau simbol biasa.');
            }
            $ubah[] = ['n' => $w['dasar'] . 'SSID', 'v' => $ssid, 't' => 'xsd:string'];
            $apa[]  = 'SSID "' . $ssid . '"';
        }
        if ($sandi !== '') {
            if (! $w['param_sandi']) {
                return $this->tolak(422, 'ONT ini tidak menyediakan parameter password WiFi.');
            }
            if (! preg_match('/^[\x21-\x7E]{8,63}$/', $sandi)) {
                return $this->tolak(422, 'Password WiFi 8-63 karakter tanpa spasi.');
            }
            $ubah[] = ['n' => $w['param_sandi'], 'v' => AcsModel::enkripsi($sandi), 't' => 'xsd:string', 'enk' => true];
            $apa[]  = 'password';
        }
        if ($maks !== '' && $maks !== (string) $w['maks']) {
            if (! $w['param_maks']) {
                return $this->tolak(422, 'ONT ini tidak menyediakan pengaturan maksimal client.');
            }
            if (! ctype_digit($maks) || (int) $maks < 1 || (int) $maks > 128) {
                return $this->tolak(422, 'Maksimal client 1-128.');
            }
            $ubah[] = ['n' => $w['param_maks'], 'v' => (string) (int) $maks, 't' => 'xsd:unsignedInt'];
            $apa[]  = 'maksimal client ' . (int) $maks;
        }
        if (! $ubah) {
            return $this->tolak(422, 'Tidak ada yang diubah.');
        }

        return $this->antre($d, 'Ubah WiFi ' . $w['band'] . ' (' . $w['ssid'] . '): ' . implode(', ', $apa), 'SetParameterValues', ['parameter' => $ubah, 'baca' => ['wifi']]);
    }

    // POST AcsKontrol/blokir/{id}: mac
    public function blokir($id)
    {
        $d = $this->ont($id);
        if (! is_array($d)) {
            return $d;
        }
        $mac = strtoupper(str_replace('-', ':', trim($this->teks('mac'))));
        if (! preg_match('/^([0-9A-F]{2}:){5}[0-9A-F]{2}$/', $mac) || hexdec(substr($mac, 0, 2)) & 1) {
            return $this->tolak(422, 'MAC Address tidak valid, contoh: AA:BB:CC:00:11:22');
        }
        $b = AcsData::blokir($this->grup($d, 'mac')['p']);
        if (! $b['didukung']) {
            return $this->tolak(422, 'Pemblokiran MAC belum didukung untuk ONT ini (data filter MAC tidak terbaca).');
        }
        if ($b['putih']) {
            return $this->tolak(422, 'Filter MAC ONT ini memakai mode daftar putih. Menambah MAC justru mengizinkannya, jadi tidak dijalankan.');
        }
        foreach ($b['daftar'] as $x) {
            if ($x['mac'] === $mac && $b['aktif']) {
                return $this->tolak(422, 'MAC ini sudah diblokir.');
            }
        }

        $aktifkan = ['perintah' => 'SetParameterValues', 'parameter' => [['n' => AcsData::MAC . 'MacFilterRight', 'v' => '1', 't' => 'xsd:boolean']], 'baca' => ['mac']];
        foreach ($b['daftar'] as $x) {
            if ($x['mac'] === $mac) { // sudah ada di daftar, filternya saja yang mati
                return $this->antre($d, 'Blokir MAC ' . $mac, 'SetParameterValues', ['parameter' => $aktifkan['parameter'], 'baca' => ['mac']]);
            }
        }

        // buat baris filter -> isi MAC -> aktifkan filter; kalau isi MAC gagal, baris kosongnya dihapus lagi
        return $this->antre($d, 'Blokir MAC ' . $mac, 'AddObject', [
            'objek' => AcsData::MAC . 'MacFilter.',
            'lanjut' => [[
                'perintah'  => 'SetParameterValues',
                'parameter' => [['n' => AcsData::MAC . 'MacFilter.{N}.SourceMACAddress', 'v' => $mac, 't' => 'xsd:string']],
                'gagal'     => ['perintah' => 'DeleteObject', 'objek' => AcsData::MAC . 'MacFilter.{N}.', 'baca' => ['mac']],
                'lanjut'    => $b['aktif'] ? [] : [$aktifkan],
                'baca'      => ['mac'],
            ]],
        ]);
    }

    // POST AcsKontrol/buka/{id}: no (nomor baris filter MAC)
    public function buka($id)
    {
        $d = $this->ont($id);
        if (! is_array($d)) {
            return $d;
        }
        foreach (AcsData::blokir($this->grup($d, 'mac')['p'])['daftar'] as $x) {
            if ($x['no'] === (int) $this->teks('no')) {
                return $this->antre($d, 'Buka blokir MAC ' . $x['mac'], 'DeleteObject', ['objek' => AcsData::MAC . 'MacFilter.' . $x['no'] . '.', 'baca' => ['mac']]);
            }
        }

        return $this->tolak(404, 'Data blokir tidak ditemukan.');
    }

    // POST AcsKontrol/ping/{id}: ip (harus client ONT ini)
    public function ping($id)
    {
        $d = $this->ont($id);
        if (! is_array($d)) {
            return $d;
        }
        $ip = trim($this->teks('ip'));
        if (! filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)
            || ! in_array($ip, array_column(AcsData::client($this->grup($d, 'host')['p'], $this->grup($d, 'wifi')['p']), 'ip'), true)) {
            return $this->tolak(422, 'IP ini bukan client ONT tersebut.');
        }
        $p = 'InternetGatewayDevice.IPPingDiagnostics.';

        return $this->antre($d, 'Ping ' . $ip, 'SetParameterValues', ['parameter' => [
            ['n' => $p . 'Host', 'v' => $ip, 't' => 'xsd:string'],
            ['n' => $p . 'NumberOfRepetitions', 'v' => '4', 't' => 'xsd:unsignedInt'],
            ['n' => $p . 'Timeout', 'v' => '2000', 't' => 'xsd:unsignedInt'],
            ['n' => $p . 'DataBlockSize', 'v' => '56', 't' => 'xsd:unsignedInt'],
            ['n' => $p . 'DiagnosticsState', 'v' => 'Requested', 't' => 'xsd:string'],
        ]]);
    }

    // POST AcsKontrol/reboot/{id}
    public function reboot($id)
    {
        $d = $this->ont($id);

        return is_array($d) ? $this->antre($d, 'Reboot ONT', 'Reboot', []) : $d;
    }

    // POST AcsKontrol/batal/{id}: tugas (hanya yang belum terkirim ke ONT)
    public function batal($id)
    {
        $t = (string) session('acs_token');
        if ($t === '' || ! hash_equals($t, (string) $this->request->getPost('token'))) {
            return $this->tolak(403, 'Token keamanan tidak valid, muat ulang halaman.');
        }

        return $this->model->batalkanTulis((int) $id, (int) $this->teks('tugas'), (string) session('username'))
            ? $this->response->setJSON(['ok' => true, 'pesan' => 'Perintah dibatalkan.'])
            : $this->tolak(409, 'Perintah sudah terkirim ke ONT atau sudah selesai.');
    }
}
