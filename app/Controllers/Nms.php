<?php

namespace App\Controllers;

use App\Libraries\SnmpKlien;
use App\Models\DeviceMonitoringModel;
use App\Models\DeviceNmsModel;
use App\Models\DeviceTrafficModel;

// Halaman NMS / Network Management System (menu Data Teknis NETMON > NMS), mengikuti netmon/nms.php:
//   - Live Monitoring   : ringkasan device, latency/packet loss (ICMP), CPU/RAM (SNMP), traffic live
//                         interface terpilih, daftar interface & semua device; diperbarui otomatis.
//   - History Monitoring: grafik latency, packet loss, CPU, RAM & traffic interface per periode / tanggal custom.
//   - Export PDF        : halaman cetak (Nms/export) -> Simpan sebagai PDF dari browser.
// Data diambil dari polling Device Monitoring (halaman terbuka atau cron: php spark devicemonitoring:poll).
class Nms extends BaseController
{
    /** @var DeviceNmsModel */
    protected $model;

    public function __construct()
    {
        // waktu polling memakai jam lokal (WIB), sama dengan halaman lain
        date_default_timezone_set('Asia/Jakarta');
        $this->model = new DeviceNmsModel();
    }

    private function tabelSiap(): bool
    {
        return \Config\Database::connect()->tableExists('d_device_monitoring') && DeviceNmsModel::siap() && DeviceTrafficModel::siap();
    }

    private function angka($v, int $d = 2): ?float
    {
        return $v === null || $v === '' ? null : round((float) $v, $d);
    }

    /** Semua device + metrik terakhir. */
    private function daftarDevice(): array
    {
        $metrik = $this->model->terakhir();
        $hasil  = [];
        foreach ((new DeviceMonitoringModel())->orderBy('hostname', 'ASC')->findAll() as $d) {
            $m       = $metrik[$d['id']] ?? null;
            $hasil[] = [
                'id'       => (int) $d['id'],
                'hostname' => $d['hostname'],
                'ip'       => $d['ip_address'],
                'vendor'   => $d['vendor'],
                'model'    => $d['model'],
                'group'    => $d['device_group'],
                'lokasi'   => $d['lokasi'],
                'status'   => DeviceMonitoringModel::statusTampil($d),
                'latency'  => $m ? $this->angka($m['latency_ms']) : null,
                'loss'     => $m ? $this->angka($m['packet_loss']) : null,
                'sumber'   => $m['ping_source'] ?? null,
                'cpu'      => $m ? $this->angka($m['cpu_usage'], 1) : null,
                'mem'      => $m ? $this->angka($m['memory_usage'], 1) : null,
                'mem_teks' => $m && $m['memory_total'] ? $this->formatByte((int) $m['memory_used']) . ' / ' . $this->formatByte((int) $m['memory_total']) : null,
                'uptime'   => $d['sys_uptime'] !== null && $d['status'] === 'Up' ? SnmpKlien::formatUptime((int) $d['sys_uptime']) : null,
                'poll'     => $d['last_poll'] ? date('d-m-Y H:i:s', strtotime($d['last_poll'])) : null,
                'interval' => (int) $d['polling_interval'],
                'sysname'  => $d['sys_name'],
            ];
        }

        return $hasil;
    }

    private function formatByte(int $b): string
    {
        foreach (['GB' => 1073741824, 'MB' => 1048576, 'KB' => 1024] as $s => $n) {
            if ($b >= $n) {
                return number_format($b / $n, 1, ',', '.') . ' ' . $s;
            }
        }

        return $b . ' B';
    }

    /** Interface satu device untuk tampilan. */
    private function daftarInterface(int $deviceId): array
    {
        if ($deviceId <= 0) {
            return [];
        }

        return array_map(static function ($i) {
            $rx    = $i['rx_bps'] !== null ? (int) $i['rx_bps'] : null;
            $tx    = $i['tx_bps'] !== null ? (int) $i['tx_bps'] : null;
            $speed = (int) $i['speed_mbps'];

            return [
                'id'      => (int) $i['id'],
                'nama'    => $i['if_name'],
                'alias'   => $i['if_alias'],
                'descr'   => $i['if_descr'],
                'ifIndex' => (int) $i['if_index'],
                'speed'   => DeviceTrafficModel::formatSpeed($i['speed_mbps']),
                'admin'   => (int) $i['admin_status'] === 1 ? 'Up' : ((int) $i['admin_status'] === 2 ? 'Down' : '-'),
                'status'  => DeviceTrafficModel::statusTampil($i, $i['device_status']),
                'rx'      => $rx,
                'tx'      => $tx,
                'rx_teks' => DeviceTrafficModel::formatBps($rx),
                'tx_teks' => DeviceTrafficModel::formatBps($tx),
                'util'    => $speed > 0 && ($rx !== null || $tx !== null) ? round(max($rx, $tx) / ($speed * 1e6) * 100, 1) : null,
                'waktu'   => $i['traffic_at'] ? date('d-m-Y H:i:s', strtotime($i['traffic_at'])) : null,
            ];
        }, (new DeviceTrafficModel())->daftar($deviceId));
    }

    /** Pilih id dari daftar; tidak valid -> $cadangan. */
    private function pilih(int $id, array $list, int $cadangan = 0): int
    {
        return in_array($id, array_column($list, 'id'), true) ? $id : $cadangan;
    }

    /** Data Live Monitoring lengkap (dipakai render awal dan refresh otomatis). */
    private function dataLive(int $deviceId, int $interfaceId): array
    {
        $device   = $this->daftarDevice();
        $deviceId = $this->pilih($deviceId, $device, $device[0]['id'] ?? 0);
        $iface    = $this->daftarInterface($deviceId);

        // interface default: yang Up dengan traffic terbesar
        $cadangan = 0;
        if ($iface) {
            $urut = $iface;
            usort($urut, static fn ($a, $b) => ((int) $b['rx'] + (int) $b['tx']) <=> ((int) $a['rx'] + (int) $a['tx']));
            $cadangan = $urut[0]['id'];
        }
        $interfaceId = $this->pilih($interfaceId, $iface, $cadangan);

        $ringkas = ['total' => count($device), 'up' => 0, 'down' => 0, 'lain' => 0];
        foreach ($device as $d) {
            $k = $d['status'] === 'Up' ? 'up' : ($d['status'] === 'Down' ? 'down' : 'lain');
            $ringkas[$k]++;
        }

        return [
            'ringkas'   => $ringkas,
            'devices'   => $device,
            'device'    => $deviceId,
            'interface' => $interfaceId,
            'ifaces'    => $iface,
            'live'      => $interfaceId ? $this->model->liveTraffic($interfaceId) : ['label' => [], 'rx' => [], 'tx' => []],
            'jam'       => date('H:i:s'),
        ];
    }

    // Halaman utama (view Nms/index.php); ?tab=live|history&device=ID&interface=ID&periode=24h&mulai=&akhir=
    public function index()
    {
        if (! $this->tabelSiap()) {
            return view('Nms/index', ['tabelSiap' => false]);
        }

        $periode = (string) $this->request->getGet('periode');

        return view('Nms/index', [
            'tabelSiap' => true,
            'tab'       => $this->request->getGet('tab') === 'history' ? 'history' : 'live',
            'live'      => $this->dataLive((int) $this->request->getGet('device'), (int) $this->request->getGet('interface')),
            'periode'   => isset(DeviceNmsModel::PERIODE[$periode]) ? $periode : '24h',
            'mulai'     => (string) $this->request->getGet('mulai'),
            'akhir'     => (string) $this->request->getGet('akhir'),
            'bisaPing'  => DeviceNmsModel::bisaPing(),
        ]);
    }

    // Refresh Live (GET Nms/data?device=ID&interface=ID): poll device yang sudah waktunya lalu kirim data terbaru
    public function data()
    {
        if (! $this->tabelSiap()) {
            return $this->response->setStatusCode(503)->setJSON(['ok' => false, 'pesan' => 'Tabel belum ada.']);
        }
        session_write_close(); // request lain (pindah halaman) tidak menunggu polling selesai

        $n = (new DeviceMonitoringModel())->pollJatuhTempo(8.0);

        return $this->response->setJSON(['ok' => true, 'dipoll' => $n]
            + $this->dataLive((int) $this->request->getGet('device'), (int) $this->request->getGet('interface')));
    }

    /** Device + interface + rentang dari query string (History & Export). */
    private function paramHistory(): ?array
    {
        $device = (new DeviceMonitoringModel())->find((int) $this->request->getGet('device'));
        if (! $device) {
            return null;
        }
        $interface = (new DeviceTrafficModel())->where('device_id', $device['id'])->find((int) $this->request->getGet('interface'));

        [$dari, $sampai, $label, $detik] = DeviceNmsModel::rentang(
            (string) $this->request->getGet('periode'),
            (string) $this->request->getGet('mulai'),
            (string) $this->request->getGet('akhir')
        );

        return compact('device', 'interface', 'dari', 'sampai', 'label', 'detik');
    }

    private function grafikHistory(array $p): array
    {
        return [
            'label_rentang' => $p['label'],
            'device'        => $this->model->history((int) $p['device']['id'], $p['dari'], $p['sampai'], $p['detik']),
            'traffic'       => $p['interface'] ? $this->model->historyTraffic((int) $p['interface']['id'], $p['dari'], $p['sampai'], $p['detik']) : null,
        ];
    }

    // Data grafik History (GET Nms/history?device=ID&interface=ID&periode=24h | mulai=Y-m-d&akhir=Y-m-d)
    public function history()
    {
        if (! $this->tabelSiap()) {
            return $this->response->setStatusCode(503)->setJSON(['ok' => false, 'pesan' => 'Tabel belum ada.']);
        }
        session_write_close(); // tidak menunggu kunci session request lain (mis. polling halaman lain)

        $p = $this->paramHistory();
        if (! $p) {
            return $this->response->setStatusCode(404)->setJSON(['ok' => false, 'pesan' => 'Device tidak ditemukan.']);
        }

        return $this->response->setJSON(['ok' => true] + $this->grafikHistory($p));
    }

    // Laporan cetak / PDF (GET Nms/export?device=ID&interface=ID&periode=... | mulai&akhir)
    public function export()
    {
        if (! $this->tabelSiap()) {
            return redirect()->to(site_url('Nms'))->with('error', 'Tabel NMS belum ada. Jalankan database/d_device_nms.sql terlebih dahulu.');
        }
        $p = $this->paramHistory();
        if (! $p) {
            return redirect()->to(site_url('Nms'))->with('error', 'Device tidak ditemukan.');
        }

        $snapshot = null;
        foreach ($this->daftarDevice() as $d) {
            if ($d['id'] === (int) $p['device']['id']) {
                $snapshot = $d;
            }
        }

        return view('Nms/export', [
            'device'    => $p['device'],
            'snapshot'  => $snapshot,
            'interface' => $p['interface'],
            'label'     => $p['label'],
            'grafik'    => $this->grafikHistory($p),
            'dibuat'    => date('d-m-Y H:i:s'),
            'oleh'      => (string) session('username'),
        ]);
    }
}
