<?php

namespace App\Controllers;

use App\Models\DeviceMonitoringModel;
use App\Models\DeviceTrafficModel;

// Halaman Screen / Network Wallboard (menu Data Teknis NETMON > Screen), mengikuti netmon/screen.php:
// grafik traffic live banyak interface dari banyak device dalam satu layar (untuk layar NOC, bisa fullscreen).
// Pilihan grafik & layout disimpan di browser; data dari polling Device Monitoring.
class Screen extends BaseController
{
    /** Pilihan rentang: jam => kode DeviceTrafficModel::RENTANG. */
    private const JAM = [1 => '1h', 6 => '6h', 24 => '24h'];

    private const MAKS_GRAFIK = 16;

    public function __construct()
    {
        // waktu memakai jam lokal (WIB), sama dengan halaman lain
        date_default_timezone_set('Asia/Jakarta');
    }

    private function tabelSiap(): bool
    {
        return \Config\Database::connect()->tableExists('d_device_monitoring') && DeviceTrafficModel::siap();
    }

    /** Interface device yang monitoring-nya aktif: [id => data]. */
    private function interfaceAktif(): array
    {
        $hasil = [];
        foreach ((new DeviceTrafficModel())->daftar() as $i) {
            if (! (int) $i['monitoring_enabled']) {
                continue;
            }
            $hasil[(int) $i['id']] = [
                'id'       => (int) $i['id'],
                'hostname' => $i['hostname'],
                'ip'       => $i['ip_address'],
                'nama'     => $i['if_name'],
                'alias'    => $i['if_alias'],
                'ifIndex'  => (int) $i['if_index'],
                'status'   => DeviceTrafficModel::statusTampil($i, $i['device_status']),
            ];
        }

        return $hasil;
    }

    // Halaman utama (view Screen/index.php)
    public function index()
    {
        $siap = $this->tabelSiap();

        return view('Screen/index', [
            'tabelSiap' => $siap,
            'interface' => $siap ? array_values($this->interfaceAktif()) : [],
            'maks'      => self::MAKS_GRAFIK,
        ]);
    }

    // Data grafik (GET Screen/data?ids=1,2,3&jam=1|6|24&poll=1): poll=1 -> device yang sudah waktunya di-poll dulu
    public function data()
    {
        if (! $this->tabelSiap()) {
            return $this->response->setStatusCode(503)->setJSON(['ok' => false, 'pesan' => 'Tabel belum ada.']);
        }
        session_write_close(); // request lain (pindah halaman) tidak menunggu polling selesai

        $mentah = $this->request->getGet('ids');
        $ids    = array_values(array_unique(array_filter(
            array_map('intval', explode(',', is_string($mentah) ? $mentah : '')),
            static fn ($id) => $id > 0
        )));
        if (! $ids || count($ids) > self::MAKS_GRAFIK) {
            return $this->response->setStatusCode(400)->setJSON(['ok' => false, 'pesan' => 'Pilih antara 1 sampai ' . self::MAKS_GRAFIK . ' interface.']);
        }
        $rentang = self::JAM[(int) $this->request->getGet('jam')] ?? '1h';

        if ($this->request->getGet('poll') === '1') {
            (new DeviceMonitoringModel())->pollJatuhTempo(8.0);
        }

        $model  = new DeviceTrafficModel();
        $aktif  = $this->interfaceAktif();
        $grafik = [];
        foreach ($ids as $id) {
            if (isset($aktif[$id])) {
                $g           = $model->grafik($id, $rentang);
                $grafik[$id] = ['label' => $g['label'], 'rx' => $g['rx'], 'tx' => $g['tx'], 'status' => $aktif[$id]['status']];
            }
        }

        return $this->response->setHeader('Cache-Control', 'no-store')
            ->setJSON(['ok' => true, 'grafik' => (object) $grafik, 'jam' => date('d-m-Y H:i:s')]);
    }
}
