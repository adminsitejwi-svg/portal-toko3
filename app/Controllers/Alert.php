<?php

namespace App\Controllers;

use App\Models\DeviceAlertModel;
use App\Models\DeviceMonitoringModel;
use App\Models\DeviceNmsModel;
use App\Models\DeviceTrafficModel;

// Halaman Alert (menu NMS JWI Group > Alert) + data lonceng notifikasi di navbar, mengikuti netmon/alerts.php:
// kartu ringkasan, filter, Acknowledge / Resolve, export CSV, pengaturan alert per interface.
class Alert extends BaseController
{
    /** @var DeviceAlertModel */
    protected $model;

    private const PER_HALAMAN = [10, 25, 50, 100];

    public function __construct()
    {
        // waktu memakai jam lokal (WIB), sama dengan halaman lain
        date_default_timezone_set('Asia/Jakarta');
        $this->model = new DeviceAlertModel();
    }

    private function tabelSiap(): bool
    {
        return \Config\Database::connect()->tableExists('d_device_monitoring')
            && DeviceTrafficModel::siap() && DeviceNmsModel::siap() && DeviceAlertModel::siap();
    }

    /** Token keamanan form & lonceng (disimpan di session). */
    public static function token(): string
    {
        $t = (string) session('alert_token');
        if ($t === '') {
            $t = bin2hex(random_bytes(32));
            session()->set('alert_token', $t);
        }

        return $t;
    }

    private function tokenValid(): bool
    {
        $t = (string) session('alert_token');

        return $t !== '' && hash_equals($t, (string) $this->request->getPost('token'));
    }

    /** Filter dari query string, nilai tidak valid diganti bawaan. */
    private function filter(array $g): array
    {
        $teks = static fn (string $k, string $b = '') => isset($g[$k]) && is_string($g[$k]) ? trim($g[$k]) : $b;
        $pil  = static fn (string $v, array $ada, string $b) => in_array($v, $ada, true) ? $v : $b;
        $tgl  = static function (string $v) {
            $d = \DateTime::createFromFormat('!Y-m-d', $v);

            return $d && $d->format('Y-m-d') === $v ? $v : '';
        };
        $per = static fn (string $k) => in_array((int) $teks($k), self::PER_HALAMAN, true) ? (int) $teks($k) : 25;

        $dari   = $tgl($teks('dari'));
        $sampai = $tgl($teks('sampai'));
        if ($dari !== '' && $sampai !== '' && $dari > $sampai) {
            $sampai = '';
        }

        return [
            'status'   => $pil($teks('status', 'active'), ['active', 'open', 'acknowledged', 'resolved', 'all'], 'active'),
            'severity' => $pil($teks('severity', 'all'), ['all', 'critical', 'warning', 'info'], 'all'),
            'q'        => mb_substr($teks('q'), 0, 100),
            'dari'     => $dari,
            'sampai'   => $sampai,
            'per'      => $per('per'),
            'hal'      => max(1, (int) $teks('hal')),
            'per_if'   => $per('per_if'),
            'hal_if'   => max(1, (int) $teks('hal_if')),
        ];
    }

    // Halaman utama (view Alert/index.php)
    public function index()
    {
        if (! $this->tabelSiap()) {
            return view('Alert/index', ['tabelSiap' => false]);
        }

        $f        = $this->filter($this->request->getGet() ?? []);
        $total    = $this->model->jumlah($f);
        $halMaks  = max(1, (int) ceil($total / $f['per']));
        $f['hal'] = min($f['hal'], $halMaks);

        $totalIf     = $this->model->jumlahInterface();
        $halMaksIf   = max(1, (int) ceil($totalIf / $f['per_if']));
        $f['hal_if'] = min($f['hal_if'], $halMaksIf);

        return view('Alert/index', [
            'tabelSiap' => true,
            'f'         => $f,
            'ringkas'   => $this->model->ringkasan(),
            'alert'     => $this->model->daftar($f, $f['per'], ($f['hal'] - 1) * $f['per']),
            'total'     => $total,
            'halMaks'   => $halMaks,
            'interface' => $this->model->daftarInterface($f['per_if'], ($f['hal_if'] - 1) * $f['per_if']),
            'totalIf'   => $totalIf,
            'halMaksIf' => $halMaksIf,
            'token'     => self::token(),
        ]);
    }

    // Export CSV (GET Alert/export, filter sama dengan tabel)
    public function export()
    {
        if (! $this->tabelSiap()) {
            return redirect()->to(site_url('Alert'));
        }

        // sel yang diawali = + - @ bisa dijalankan Excel sebagai rumus
        $aman = static fn ($v) => is_string($v) && ! is_numeric($v) && preg_match('/^[=+\-@\t\r]/', $v) ? "'" . $v : $v;

        $fh = fopen('php://temp', 'r+');
        fwrite($fh, "\xEF\xBB\xBF"); // BOM UTF-8 untuk Excel
        fputcsv($fh, ['Alert ID', 'Alert Type', 'Severity', 'Title', 'Message', 'Hostname', 'IP Address', 'Interface', 'Metric Value',
            'Threshold Value', 'Status', 'First Seen', 'Last Seen', 'Acknowledged At', 'Acknowledged By', 'Resolved At'], ',', '"', '\\');
        foreach ($this->model->dataExport($this->filter($this->request->getGet() ?? [])) as $b) {
            fputcsv($fh, array_map($aman, array_values($b)), ',', '"', '\\');
        }
        rewind($fh);
        $isi = stream_get_contents($fh);
        fclose($fh);

        return $this->response->setHeader('Cache-Control', 'no-store')
            ->setHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->setHeader('Content-Disposition', 'attachment; filename="alert-history-' . date('Ymd-His') . '.csv"')
            ->setBody($isi);
    }

    // Kembali ke halaman Alert dengan filter yang sedang dipakai
    private function kembali(string $jenis, string $pesan)
    {
        $q = [];
        parse_str((string) $this->request->getPost('kembali'), $q);
        $f     = $this->filter($q);
        $query = http_build_query(array_intersect_key($f, $q));

        return redirect()->to(site_url('Alert') . ($query !== '' ? '?' . $query : ''))->with($jenis, $pesan);
    }

    // POST Alert/acknowledge/{id}
    public function acknowledge($id)
    {
        if (! $this->tabelSiap() || ! $this->tokenValid()) {
            return $this->kembali('error', 'Token keamanan tidak valid, muat ulang halaman.');
        }

        return $this->model->acknowledge((int) $id, (string) session('username'))
            ? $this->kembali('success', 'Alert berhasil di-acknowledge.')
            : $this->kembali('error', 'Alert tidak ditemukan atau sudah diproses.');
    }

    // POST Alert/resolve/{id}
    public function resolve($id)
    {
        if (! $this->tabelSiap() || ! $this->tokenValid()) {
            return $this->kembali('error', 'Token keamanan tidak valid, muat ulang halaman.');
        }

        return $this->model->selesaikan((int) $id)
            ? $this->kembali('success', 'Alert berhasil di-resolve.')
            : $this->kembali('error', 'Alert tidak ditemukan atau sudah selesai.');
    }

    // POST Alert/interface/{id} (aktif=1 / 0)
    public function aturInterface($id)
    {
        if (! $this->tabelSiap() || ! $this->tokenValid()) {
            return $this->kembali('error', 'Token keamanan tidak valid, muat ulang halaman.');
        }
        $aktif = (string) $this->request->getPost('aktif') === '1';

        if (! $this->model->aturInterface((int) $id, $aktif, (string) session('username'))) {
            return $this->kembali('error', 'Interface tidak ditemukan.');
        }

        return $this->kembali('success', $aktif ? 'Alert interface berhasil diaktifkan.' : 'Interface dikecualikan dari alert.');
    }

    // ---------- Lonceng notifikasi di navbar (public/assets/theme/notifikasi.js) ----------

    // GET Alert/notifikasi
    public function notifikasi()
    {
        if (! $this->tabelSiap()) {
            return $this->response->setStatusCode(503)->setJSON(['ok' => false]);
        }
        $userId = (int) session('user_id');
        session_write_close();

        return $this->response->setHeader('Cache-Control', 'no-store')
            ->setJSON(['ok' => true] + $this->model->lonceng($userId));
    }

    // POST Alert/dibaca (sampai = id notifikasi terbaru yang sudah tampil)
    public function dibaca()
    {
        if (! $this->tabelSiap() || ! $this->tokenValid()) {
            return $this->response->setStatusCode(403)->setJSON(['ok' => false, 'pesan' => 'Token keamanan tidak valid.']);
        }
        $this->model->tandaiDibaca((int) session('user_id'), (int) $this->request->getPost('sampai'));

        return $this->response->setJSON(['ok' => true]);
    }

    // GET Alert/cek: poll device yang sudah waktunya, supaya alert tetap terdeteksi dari halaman mana pun
    public function cek()
    {
        if (! $this->tabelSiap()) {
            return $this->response->setStatusCode(503)->setJSON(['ok' => false]);
        }
        session_write_close(); // request lain (pindah halaman) tidak menunggu polling selesai

        return $this->response->setJSON(['ok' => true, 'dipoll' => (new DeviceMonitoringModel())->pollJatuhTempo(8.0)]);
    }
}
