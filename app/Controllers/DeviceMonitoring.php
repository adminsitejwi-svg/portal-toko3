<?php

namespace App\Controllers;

use App\Libraries\SnmpKlien;
use App\Models\DeviceMonitoringModel;

// Halaman Device Monitoring (menu NMS JWI Group > Device Monitoring). Tampilan & alur sama dengan
// Preventive Maintenance: daftar + kartu statistik + filter + export, form tambah/edit (halaman sendiri), View.
// Status perangkat dari SNMP v2c (community): dicek saat simpan, tombol Poll, dan otomatis sesuai
// polling interval selama halaman daftar terbuka (GET DeviceMonitoring/poll-due) atau lewat cron:
//   php spark devicemonitoring:poll
class DeviceMonitoring extends BaseController
{
    protected $model;

    public function __construct()
    {
        // waktu polling memakai jam lokal (WIB), sama dengan halaman lain
        date_default_timezone_set('Asia/Jakarta');
        $this->model = new DeviceMonitoringModel();
    }

    private function tabelSiap(): bool
    {
        $db = \Config\Database::connect();

        return $db->tableExists('d_device_monitoring') && $db->tableExists('d_device_monitoring_log');
    }

    private function tabelBelumAda()
    {
        return redirect()->to(site_url('dashboard-manager'))
            ->with('error', 'Tabel Device Monitoring belum ada. Jalankan database/d_device_monitoring.sql terlebih dahulu.');
    }

    // Halaman utama (view DeviceMonitoring/index.php)
    public function index()
    {
        if (! $this->tabelSiap()) {
            return view('DeviceMonitoring/index', ['device' => [], 'tabelSiap' => false]);
        }

        return view('DeviceMonitoring/index', [
            'device'    => $this->model->orderBy('hostname', 'ASC')->findAll(),
            'tabelSiap' => true,
        ]);
    }

    // Form tambah (view DeviceMonitoring/Form.php, dipakai juga untuk edit)
    public function create()
    {
        if (! $this->tabelSiap()) {
            return $this->tabelBelumAda();
        }

        return view('DeviceMonitoring/Form', ['dv' => null, 'saran' => $this->saran()]);
    }

    public function edit($id)
    {
        $dv = $this->tabelSiap() ? $this->model->find($id) : null;
        if (! $dv) {
            return redirect()->to(site_url('DeviceMonitoring'))->with('error', 'Data device tidak ditemukan.');
        }

        return view('DeviceMonitoring/Form', ['dv' => $dv, 'saran' => $this->saran()]);
    }

    // Saran isian (datalist) dari data yang sudah ada
    private function saran(): array
    {
        $hasil = [];
        foreach (['model', 'lokasi'] as $k) {
            $isi       = $this->model->distinct()->select($k)->where($k . ' !=', '')->where($k . ' IS NOT NULL', null, false)->orderBy($k, 'ASC')->findColumn($k) ?? [];
            $hasil[$k] = array_values(array_unique(array_map('trim', $isi)));
        }

        return $hasil;
    }

    // Halaman View: detail device, info SNMP & riwayat status
    public function view($id)
    {
        $dv = $this->tabelSiap() ? $this->model->find($id) : null;
        if (! $dv) {
            return redirect()->to(site_url('DeviceMonitoring'))->with('error', 'Data device tidak ditemukan.');
        }

        return view('DeviceMonitoring/View', [
            'dv'      => $dv,
            'riwayat' => $this->model->riwayat((int) $id),
        ]);
    }

    // ================= SNMP =================

    // Data ringkas satu device untuk JSON (tanpa community)
    private function ringkas(array $d): array
    {
        return [
            'id'          => (int) $d['id'],
            'status'      => DeviceMonitoringModel::statusTampil($d),
            'response_ms' => $d['response_ms'] !== null ? (int) $d['response_ms'] : null,
            'uptime'      => SnmpKlien::formatUptime($d['sys_uptime'] !== null ? (int) $d['sys_uptime'] : null),
            'sys_name'    => $d['sys_name'],
            'last_poll'   => $d['last_poll'] ? date('d-m-Y H:i:s', strtotime($d['last_poll'])) : null,
            'last_error'  => $d['last_error'],
        ];
    }

    // Poll otomatis (GET DeviceMonitoring/poll-due): polling device yang sudah waktunya, lalu kirim status semua device
    public function pollDue()
    {
        if (! $this->tabelSiap()) {
            return $this->response->setStatusCode(503)->setJSON(['ok' => false, 'pesan' => 'Tabel belum ada.']);
        }
        session_write_close(); // request lain (pindah halaman) tidak menunggu polling selesai

        $n = $this->model->pollJatuhTempo(8.0);

        return $this->response->setJSON([
            'ok'     => true,
            'dipoll' => $n,
            'device' => array_map(fn ($d) => $this->ringkas($d), $this->model->findAll()),
        ]);
    }

    // Poll satu device sekarang (GET DeviceMonitoring/poll/{id}); AJAX = JSON, selain itu kembali ke halaman View
    public function poll($id)
    {
        $dv  = $this->tabelSiap() ? $this->model->find($id) : null;
        $ajx = $this->request->isAJAX();

        if (! $dv) {
            return $ajx ? $this->response->setStatusCode(404)->setJSON(['ok' => false, 'pesan' => 'Data device tidak ditemukan.'])
                : redirect()->to(site_url('DeviceMonitoring'))->with('error', 'Data device tidak ditemukan.');
        }
        if (! DeviceMonitoringModel::bisaPoll($dv)) {
            $pesan = (int) $dv['snmp_enabled'] ? 'SNMP v3 belum didukung (credentials setup pending).' : 'SNMP device ini nonaktif.';

            return $ajx ? $this->response->setJSON(['ok' => false, 'pesan' => $pesan, 'device' => $this->ringkas($dv)])
                : redirect()->to(site_url('DeviceMonitoring/view/' . $id))->with('error', $pesan);
        }

        $baru = $this->model->poll($dv);
        $ok   = $baru['status'] === 'Up';
        $teks = $ok ? 'Device ' . esc($baru['hostname']) . ' terhubung lewat SNMP (' . (int) $baru['response_ms'] . ' ms).'
            : 'Device ' . esc($baru['hostname']) . ' tidak membalas SNMP: ' . esc((string) $baru['last_error']);

        if ($ajx) {
            return $this->response->setJSON(['ok' => $ok, 'pesan' => $teks, 'device' => $this->ringkas($baru)]);
        }

        return redirect()->to(site_url('DeviceMonitoring/view/' . $id))->with($ok ? 'success' : 'error', $teks);
    }

    // Tes koneksi SNMP dari form (POST DeviceMonitoring/test, JSON); tidak menyimpan apa pun.
    // Community kosong saat edit = pakai community yang tersimpan.
    public function test()
    {
        $ip        = trim((string) $this->request->getPost('ip_address'));
        $port      = (int) $this->request->getPost('snmp_port');
        $community = (string) $this->request->getPost('snmp_community');
        $id        = (int) $this->request->getPost('id');

        if (! filter_var($ip, FILTER_VALIDATE_IP)) {
            return $this->response->setJSON(['ok' => false, 'pesan' => 'IP Address tidak valid.']);
        }
        if ($port < 1 || $port > 65535) {
            return $this->response->setJSON(['ok' => false, 'pesan' => 'SNMP Port harus 1 - 65535.']);
        }
        if ($community === '' && $id && $this->tabelSiap() && ($lama = $this->model->find($id))) {
            $community = DeviceMonitoringModel::dekripsi($lama['snmp_community']);
        }
        if ($community === '') {
            return $this->response->setJSON(['ok' => false, 'pesan' => 'SNMP Community wajib diisi.']);
        }

        $hasil = DeviceMonitoringModel::cekSnmp($ip, $port, $community);
        if (! $hasil['ok']) {
            return $this->response->setJSON(['ok' => false, 'pesan' => $hasil['error']]);
        }

        $v = $hasil['nilai'];

        return $this->response->setJSON([
            'ok'    => true,
            'pesan' => 'Terhubung lewat SNMP v2c (' . $hasil['ms'] . ' ms).',
            'info'  => [
                'sysName'     => is_string($v['sysName'] ?? null) ? $v['sysName'] : '-',
                'sysDescr'    => is_string($v['sysDescr'] ?? null) ? mb_substr($v['sysDescr'], 0, 300) : '-',
                'sysUpTime'   => SnmpKlien::formatUptime(is_int($v['sysUpTime'] ?? null) ? $v['sysUpTime'] : null),
                'sysLocation' => is_string($v['sysLocation'] ?? null) && $v['sysLocation'] !== '' ? $v['sysLocation'] : '-',
                'ifNumber'    => is_int($v['ifNumber'] ?? null) ? $v['ifNumber'] : '-',
            ],
        ]);
    }

    // ================= SIMPAN / UBAH / HAPUS =================

    // Pilihan + "Other" (isian manual): kembalikan nilai yang disimpan
    private function pilihan(string $kolom, array $opsi): string
    {
        $pilih = trim((string) $this->request->getPost($kolom));
        if ($pilih === 'Other') {
            return trim(preg_replace('/\s+/', ' ', (string) $this->request->getPost($kolom . '_lain')));
        }

        return in_array($pilih, $opsi, true) ? $pilih : '';
    }

    private function collectFields(): array
    {
        $satu     = fn (string $k) => trim(preg_replace('/\s+/', ' ', (string) $this->request->getPost($k)));
        $opsional = static fn ($v) => $v === '' ? null : $v;
        $versi    = (string) $this->request->getPost('snmp_version');

        return [
            'hostname'           => $satu('hostname'),
            'ip_address'         => $satu('ip_address'),
            'vendor'             => $this->pilihan('vendor', DeviceMonitoringModel::VENDOR),
            'model'              => $opsional($satu('model')),
            'device_group'       => $this->pilihan('device_group', DeviceMonitoringModel::DEVICE_GROUP),
            'lokasi'             => $opsional($satu('lokasi')),
            'deskripsi'          => $opsional(trim(preg_replace("/\r\n?/", "\n", (string) $this->request->getPost('deskripsi')))),
            'snmp_version'       => isset(DeviceMonitoringModel::SNMP_VERSION[$versi]) ? $versi : '',
            'snmp_port'          => $satu('snmp_port'),
            'polling_interval'   => $satu('polling_interval'),
            'snmp_enabled'       => $this->request->getPost('snmp_enabled') ? 1 : 0,
            'monitoring_enabled' => $this->request->getPost('monitoring_enabled') ? 1 : 0,
        ];
    }

    // Validasi isian; $community = isian baru (string kosong = tidak diubah); $adaLama = sudah punya community tersimpan
    private function validasi(array $f, string $community, bool $adaLama, ?int $id = null): ?string
    {
        $errors = [];
        $wajib  = ['hostname' => 'Hostname', 'ip_address' => 'IP Address', 'vendor' => 'Vendor', 'device_group' => 'Device Group', 'snmp_version' => 'SNMP Version'];
        foreach ($wajib as $k => $label) {
            if ($f[$k] === '') {
                $errors[] = $label . ' wajib diisi' . (in_array($k, ['vendor', 'device_group'], true) ? ' (pilihan Other: isi manual).' : '.');
            }
        }

        $maks = ['hostname' => ['Hostname', 150], 'vendor' => ['Vendor', 100], 'model' => ['Model', 150], 'device_group' => ['Device Group', 100], 'lokasi' => ['Location', 255]];
        foreach ($maks as $k => [$label, $n]) {
            if ($f[$k] !== null && mb_strlen($f[$k]) > $n) {
                $errors[] = $label . ' maksimal ' . $n . ' karakter.';
            }
        }

        if ($f['ip_address'] !== '' && ! filter_var($f['ip_address'], FILTER_VALIDATE_IP)) {
            $errors[] = 'IP Address tidak valid (contoh 192.168.1.1).';
        }
        if (! ctype_digit($f['snmp_port']) || (int) $f['snmp_port'] < 1 || (int) $f['snmp_port'] > 65535) {
            $errors[] = 'SNMP Port harus angka 1 - 65535.';
        }
        if (! ctype_digit($f['polling_interval']) || (int) $f['polling_interval'] < DeviceMonitoringModel::INTERVAL_MIN || (int) $f['polling_interval'] > DeviceMonitoringModel::INTERVAL_MAX) {
            $errors[] = 'Polling Interval harus ' . DeviceMonitoringModel::INTERVAL_MIN . ' - ' . DeviceMonitoringModel::INTERVAL_MAX . ' detik.';
        }
        if (mb_strlen($community) > 100) {
            $errors[] = 'SNMP Community maksimal 100 karakter.';
        }
        if ($f['snmp_version'] === 'v2c' && $f['snmp_enabled'] && $community === '' && ! $adaLama) {
            $errors[] = 'SNMP Community (v2c) wajib diisi bila SNMP aktif.';
        }

        // Tidak boleh ada hostname / IP yang sama
        foreach (['hostname' => 'Hostname', 'ip_address' => 'IP Address'] as $k => $label) {
            if ($f[$k] === '') {
                continue;
            }
            $q = $this->model->where($k, $f[$k]);
            if ($id) {
                $q->where('id !=', $id);
            }
            if ($q->countAllResults() > 0) {
                $errors[] = $label . ' "' . esc($f[$k]) . '" sudah terdaftar.';
            }
        }

        return $errors ? implode(' ', $errors) : null;
    }

    // Setelah simpan: langsung hubungkan ke perangkat lewat SNMP (bila SNMP v2c aktif); kembalikan teks hasil
    private function hubungkan(int $id): string
    {
        $dv = $this->model->find($id);
        if (! $dv || ! DeviceMonitoringModel::bisaPoll($dv)) {
            return '';
        }
        $baru = $this->model->poll($dv);

        return $baru['status'] === 'Up'
            ? ' Terhubung lewat SNMP (' . (int) $baru['response_ms'] . ' ms).'
            : ' Namun SNMP belum membalas: ' . esc((string) $baru['last_error']);
    }

    // Simpan data baru (POST DeviceMonitoring/save)
    public function save()
    {
        if (! $this->tabelSiap()) {
            return $this->tabelBelumAda();
        }
        $f         = $this->collectFields();
        $community = (string) $this->request->getPost('snmp_community');

        if ($error = $this->validasi($f, $community, false)) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $f['snmp_port']        = (int) $f['snmp_port'];
        $f['polling_interval'] = (int) $f['polling_interval'];
        $f['snmp_community']   = $community !== '' ? DeviceMonitoringModel::enkripsi($community) : null;
        $f['status']           = 'Unknown';

        try {
            $id = $this->model->insert($f);
        } catch (\Throwable $e) {
            $id = false;
        }
        if (! $id) {
            return redirect()->back()->withInput()->with('error', 'Device gagal disimpan, silakan coba lagi.');
        }

        return redirect()->to(site_url('DeviceMonitoring'))
            ->with('success', 'Device ' . esc($f['hostname']) . ' berhasil ditambahkan.' . $this->hubungkan((int) $id));
    }

    // Update data (POST DeviceMonitoring/update)
    public function update()
    {
        $id  = (int) $this->request->getPost('id');
        $old = $id && $this->tabelSiap() ? $this->model->find($id) : null;
        if (! $old) {
            return redirect()->to(site_url('DeviceMonitoring'))->with('error', 'Data device tidak ditemukan.');
        }

        $f         = $this->collectFields();
        $community = (string) $this->request->getPost('snmp_community');

        if ($error = $this->validasi($f, $community, DeviceMonitoringModel::dekripsi($old['snmp_community']) !== '', $id)) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $f['snmp_port']        = (int) $f['snmp_port'];
        $f['polling_interval'] = (int) $f['polling_interval'];
        if ($community !== '') {
            $f['snmp_community'] = DeviceMonitoringModel::enkripsi($community);
        }
        // target SNMP berubah: status lama tidak berlaku lagi
        if ($f['ip_address'] !== $old['ip_address'] || $f['snmp_port'] !== (int) $old['snmp_port'] || $community !== '' || $f['snmp_version'] !== $old['snmp_version']) {
            $f['status'] = 'Unknown';
        }

        try {
            $ok = $this->model->update($id, $f) !== false;
        } catch (\Throwable $e) {
            $ok = false;
        }
        if (! $ok) {
            return redirect()->back()->withInput()->with('error', 'Device gagal diperbarui.');
        }

        return redirect()->to(site_url('DeviceMonitoring'))
            ->with('success', 'Device ' . esc($f['hostname']) . ' berhasil diperbarui.' . $this->hubungkan($id));
    }

    // Hapus device beserta riwayat statusnya
    public function delete($id)
    {
        $dv = $this->tabelSiap() ? $this->model->find($id) : null;
        if ($dv) {
            $this->model->delete($id);
            \Config\Database::connect()->table('d_device_monitoring_log')->where('device_id', (int) $id)->delete();
            if (\App\Models\DeviceAlertModel::siap()) {
                (new \App\Models\DeviceAlertModel())->hapusDevice((int) $id); // alert & notifikasi (sebelum interface dihapus)
            }
            if (\App\Models\DeviceTrafficModel::siap()) {
                (new \App\Models\DeviceTrafficModel())->hapusDevice((int) $id); // interface & riwayat traffic
            }
            if (\App\Models\DeviceNmsModel::siap()) {
                (new \App\Models\DeviceNmsModel())->hapusDevice((int) $id); // riwayat ping, CPU & RAM (NMS)
            }
        }

        return redirect()->to(site_url('DeviceMonitoring'))->with('success', 'Device berhasil dihapus.');
    }
}
