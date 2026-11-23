<?php

namespace App\Controllers;

use App\Libraries\HakAkses;
use App\Models\DeviceAlertModel;
use App\Models\DeviceMonitoringModel;
use App\Models\DeviceNmsModel;

// Halaman Dashboard Netmon (menu Data Teknis NETMON > Dashboard Netmon), mengikuti netmon/index.php:
// ringkasan status device, availability, rata-rata latency/CPU/RAM, grafik Network Performance,
// Device Distribution, device terbaru dan alert terbaru. Data dari polling Device Monitoring.
class DashboardNetmon extends BaseController
{
    /** @var DeviceNmsModel */
    protected $model;

    public function __construct()
    {
        // waktu memakai jam lokal (WIB), sama dengan halaman lain
        date_default_timezone_set('Asia/Jakarta');
        $this->model = new DeviceNmsModel();
    }

    private function tabelSiap(): bool
    {
        return \Config\Database::connect()->tableExists('d_device_monitoring') && DeviceNmsModel::siap();
    }

    /** Rata-rata angka yang terisi; null kalau tidak ada. */
    private function rata(array $nilai, int $desimal): ?float
    {
        $nilai = array_filter($nilai, static fn ($v) => $v !== null && $v !== '');

        return $nilai ? round(array_sum(array_map('floatval', $nilai)) / count($nilai), $desimal) : null;
    }

    /** Semua data dashboard (render awal dan refresh). $deviceId 0 = All Devices. */
    private function isi(int $deviceId): array
    {
        $angka  = static fn ($v, int $d) => $v === null || $v === '' ? null : round((float) $v, $d);
        $metrik = $this->model->terakhir();

        $jumlah  = ['online' => 0, 'offline' => 0, 'unknown' => 0];
        $latency = $cpu = $ram = $pilihan = $terbaru = [];
        foreach ((new DeviceMonitoringModel())->orderBy('id', 'DESC')->findAll() as $d) {
            $m      = $metrik[$d['id']] ?? null;
            $status = DeviceMonitoringModel::statusTampil($d);
            $jumlah[$status === 'Up' ? 'online' : ($status === 'Down' ? 'offline' : 'unknown')]++;

            if ($status === 'Up') {
                $latency[] = $m['latency_ms'] ?? null;
            }
            $cpu[] = $m['cpu_usage'] ?? null;
            $ram[] = $m['memory_usage'] ?? null;

            if ((int) $d['snmp_enabled'] && (int) $d['monitoring_enabled']) {
                $pilihan[] = ['id' => (int) $d['id'], 'nama' => $d['hostname'] . ' - ' . $d['ip_address']];
            }
            if (count($terbaru) < 10) {
                $terbaru[] = [
                    'hostname' => $d['hostname'],
                    'ip'       => $d['ip_address'],
                    'vendor'   => $d['vendor'],
                    'group'    => $d['device_group'],
                    'status'   => $status,
                    'latency'  => $m ? $angka($m['latency_ms'], 2) : null,
                    'cpu'      => $m ? $angka($m['cpu_usage'], 1) : null,
                    'ram'      => $m ? $angka($m['memory_usage'], 1) : null,
                ];
            }
        }
        usort($pilihan, static fn ($a, $b) => strcasecmp($a['nama'], $b['nama']));
        if (! in_array($deviceId, array_column($pilihan, 'id'), true)) {
            $deviceId = 0;
        }

        // alert terbaru hanya untuk akun yang boleh membuka halaman Alert
        $alert = null;
        if (DeviceAlertModel::siap() && HakAkses::instance()->bisa('Alert')) {
            $alert = array_map(static fn ($a) => [
                'hostname' => $a['hostname'] ?? 'Unknown Device',
                'judul'    => $a['title'],
                'pesan'    => $a['message'],
                'severity' => $a['severity'],
                'status'   => $a['status'],
                'waktu'    => date('d-m-Y H:i:s', strtotime($a['first_seen'])),
            ], (new DeviceAlertModel())->terbaru(5));
        }

        $total = array_sum($jumlah);

        return [
            'jumlah'       => ['total' => $total] + $jumlah,
            'availability' => $total ? round($jumlah['online'] / $total * 100, 2) : 0,
            'latency'      => $this->rata($latency, 2),
            'cpu'          => $this->rata($cpu, 1),
            'ram'          => $this->rata($ram, 1),
            'pilihan'      => $pilihan,
            'device'       => $deviceId,
            'performa'     => $this->model->performa($deviceId),
            'terbaru'      => $terbaru,
            'alert'        => $alert,
            'jam'          => date('H:i:s'),
        ];
    }

    // Halaman utama (view DashboardNetmon/index.php); ?device=ID
    public function index()
    {
        $akses = HakAkses::instance();
        $siap  = $this->tabelSiap();

        return view('DashboardNetmon/index', [
            'tabelSiap'   => $siap,
            'isi'         => $siap ? $this->isi((int) $this->request->getGet('device')) : null,
            'bisaDevice'  => $akses->bisa('DeviceMonitoring'),
            'bisaAlert'   => $akses->bisa('Alert'),
        ]);
    }

    // Data terbaru (GET DashboardNetmon/data?device=ID&poll=1): poll=1 -> device yang sudah waktunya di-poll dulu
    public function data()
    {
        if (! $this->tabelSiap()) {
            return $this->response->setStatusCode(503)->setJSON(['ok' => false, 'pesan' => 'Tabel belum ada.']);
        }
        session_write_close(); // request lain (pindah halaman) tidak menunggu polling selesai

        if ($this->request->getGet('poll') === '1') {
            (new DeviceMonitoringModel())->pollJatuhTempo(8.0);
        }

        return $this->response->setHeader('Cache-Control', 'no-store')
            ->setJSON(['ok' => true] + $this->isi((int) $this->request->getGet('device')));
    }
}
