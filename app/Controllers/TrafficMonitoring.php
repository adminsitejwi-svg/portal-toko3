<?php

namespace App\Controllers;

use App\Libraries\NmsDetik;
use App\Models\DeviceMonitoringModel;
use App\Models\DeviceTrafficModel;

// Halaman Traffic Monitoring (menu NMS JWI Group > Traffic Monitoring), mengikuti netmon/traffic.php:
// kartu ringkasan interface, grafik RX/TX interface terpilih, daftar interface + filter + export.
// Device diambil dari Device Monitoring; traffic di-poll otomatis sesuai polling interval device
// selama halaman terbuka (GET TrafficMonitoring/data) atau lewat cron: php spark devicemonitoring:poll
class TrafficMonitoring extends BaseController
{
    /** @var DeviceTrafficModel */
    protected $model;

    public function __construct()
    {
        // waktu polling memakai jam lokal (WIB), sama dengan halaman lain
        date_default_timezone_set('Asia/Jakarta');
        $this->model = new DeviceTrafficModel();
    }

    private function tabelSiap(): bool
    {
        return \Config\Database::connect()->tableExists('d_device_monitoring') && DeviceTrafficModel::siap();
    }

    // Halaman utama (view TrafficMonitoring/index.php); ?device=ID&interface=ID
    public function index()
    {
        if (! $this->tabelSiap()) {
            return view('TrafficMonitoring/index', ['tabelSiap' => false, 'device' => [], 'interface' => [], 'deviceId' => 0, 'pilih' => null]);
        }

        $device   = (new DeviceMonitoringModel())->orderBy('hostname', 'ASC')->findAll();
        $idDevice = array_map('intval', array_column($device, 'id'));
        $deviceId = (int) $this->request->getGet('device');
        $pilihId  = (int) $this->request->getGet('interface');
        // tautan hanya berisi interface: device mengikuti interface itu
        if (! in_array($deviceId, $idDevice, true) && $pilihId > 0) {
            $deviceId = (int) ($this->model->find($pilihId)['device_id'] ?? 0);
        }
        // selalu satu device; belum dipilih / tidak valid = device pertama
        if (! in_array($deviceId, $idDevice, true)) {
            $deviceId = $idDevice[0] ?? 0;
        }

        // isi dropdown interface, grafik & tabel hanya milik device terpilih
        $interface = $deviceId ? $this->model->daftar($deviceId) : [];

        // interface terpilih: dari URL, kalau tidak ada = interface Up dengan traffic terbesar
        $pilih = null;
        foreach ($interface as $i) {
            if ((int) $i['id'] === $pilihId) {
                $pilih = $i;
                break;
            }
        }
        if (! $pilih && $interface) {
            $urut = $interface;
            usort($urut, static fn ($a, $b) => ((int) $b['rx_bps'] + (int) $b['tx_bps']) <=> ((int) $a['rx_bps'] + (int) $a['tx_bps']));
            $pilih = $urut[0];
        }

        return view('TrafficMonitoring/index', [
            'tabelSiap' => true,
            'device'    => $device,
            'interface' => $interface,
            'deviceId'  => $deviceId,
            'pilih'     => $pilih,
        ]);
    }

    // Data satu interface untuk JSON
    private function ringkas(array $i): array
    {
        $rx    = $i['rx_bps'] !== null ? (int) $i['rx_bps'] : null;
        $tx    = $i['tx_bps'] !== null ? (int) $i['tx_bps'] : null;
        $speed = (int) $i['speed_mbps'];

        return [
            'id'         => (int) $i['id'],
            'status'     => DeviceTrafficModel::statusTampil($i, $i['device_status']),
            'rx'         => $rx,
            'tx'         => $tx,
            'rx_teks'    => DeviceTrafficModel::formatBps($rx),
            'tx_teks'    => DeviceTrafficModel::formatBps($tx),
            'util'       => $speed > 0 && ($rx !== null || $tx !== null) ? round(max($rx, $tx) / ($speed * 1e6) * 100, 1) : null,
            'traffic_at' => $i['traffic_at'] ? date('d-m-Y H:i:s', strtotime($i['traffic_at'])) : null,
        ];
    }

    // Poll otomatis (GET TrafficMonitoring/data?device=ID): poll device yang sudah waktunya, lalu kirim data interface
    public function data()
    {
        if (! $this->tabelSiap()) {
            return $this->response->setStatusCode(503)->setJSON(['ok' => false, 'pesan' => 'Tabel belum ada.']);
        }
        session_write_close(); // request lain (pindah halaman) tidak menunggu polling selesai

        $n = (new DeviceMonitoringModel())->pollJatuhTempo(8.0);

        return $this->response->setJSON([
            'ok'        => true,
            'dipoll'    => $n,
            'interface' => array_map(fn ($i) => $this->ringkas($i), $this->model->daftar((int) $this->request->getGet('device'))),
        ]);
    }

    // Data grafik satu interface (GET TrafficMonitoring/grafik/{id}?rentang=5m|10m|1h|6h|24h|7d)
    public function grafik($id)
    {
        if (! $this->tabelSiap()) {
            return $this->response->setStatusCode(503)->setJSON(['ok' => false, 'pesan' => 'Tabel belum ada.']);
        }
        $rentang = (string) $this->request->getGet('rentang');
        if (! isset(DeviceTrafficModel::RENTANG[$rentang])) {
            $rentang = '1h';
        }
        if (! $this->model->find((int) $id)) {
            return $this->response->setStatusCode(404)->setJSON(['ok' => false, 'pesan' => 'Interface tidak ditemukan.']);
        }

        return $this->response->setJSON(['ok' => true, 'rentang' => $rentang] + $this->model->grafik((int) $id, $rentang));
    }

    // Grafik 10 Menit per detik (GET TrafficMonitoring/detik/{id}?sejak=ms), dipanggil tiap 1 detik selama grafik tampil
    public function detik($id)
    {
        if (! $this->tabelSiap()) {
            return $this->response->setStatusCode(503)->setJSON(['ok' => false, 'pesan' => 'Tabel belum ada.']);
        }
        session_write_close(); // request lain tidak menunggu balasan perangkat

        $i = $this->model->find((int) $id);
        $d = $i ? (new DeviceMonitoringModel())->find((int) $i['device_id']) : null;
        if (! $d) {
            return $this->response->setStatusCode(404)->setJSON(['ok' => false, 'pesan' => 'Interface tidak ditemukan.']);
        }

        return $this->response->setJSON(['ok' => true] + NmsDetik::ambil($d, $i, false, max(0, (int) $this->request->getGet('sejak'))));
    }
}
