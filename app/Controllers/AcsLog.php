<?php

namespace App\Controllers;

use App\Libraries\HakAkses;
use App\Models\AcsModel;

// Halaman Log Koneksi (menu ACS JWI Group > Log Koneksi): Inform ONT, login ONT yang ditolak & error dari ONT.
// Tampilan sama dengan Device Monitoring; isi tabel diambil per halaman dari server (data()).
class AcsLog extends BaseController
{
    /** Pilihan filter Kejadian: teks yang dicari di kolom kejadian => label. */
    private const KEJADIAN = [
        '0 BOOTSTRAP'          => 'Pertama terhubung (0 BOOTSTRAP)',
        '1 BOOT'               => 'Baru menyala (1 BOOT)',
        '2 PERIODIC'           => 'Inform rutin (2 PERIODIC)',
        '4 VALUE CHANGE'       => 'Nilai berubah (4 VALUE CHANGE)',
        'Fault dari ONT'       => 'Error dari ONT',
        AcsModel::LOGIN_DITOLAK => 'Login ditolak',
    ];

    private const TAMPIL = ['ok', 'error', 'login'];

    /** Batas baris untuk export. */
    private const MAKS_EXPORT = 5000;

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta'); // waktu lokal (WIB), sama dengan halaman lain
    }

    private function teks(string $k): string
    {
        $v = $this->request->getGet($k);

        return is_string($v) ? trim($v) : '';
    }

    private function tampil(): string
    {
        return in_array($this->teks('tampil'), self::TAMPIL, true) ? $this->teks('tampil') : '';
    }

    // Halaman utama (view AcsLog/index.php); ?tampil=ok|error|login
    public function index()
    {
        $siap = AcsModel::siap();
        $data = [
            'tabelSiap'  => $siap,
            'retensi'    => AcsModel::RETENSI_HARI,
            'kejadian'   => self::KEJADIAN,
            'maksExport' => self::MAKS_EXPORT,
            'tampil'     => $this->tampil(),
            'bisaDevice' => HakAkses::instance()->bisa('AcsDevice'),
        ];

        return view('AcsLog/index', $siap ? $data + ['ringkas' => (new AcsModel())->ringkasLog()] : $data);
    }

    // Satu halaman tabel (GET AcsLog/data, format DataTables server-side)
    public function data()
    {
        if (! AcsModel::siap()) {
            return $this->response->setStatusCode(503)->setJSON(['error' => 'Tabel belum ada.']);
        }
        session_write_close();

        $tgl = static function (string $v) {
            $d = \DateTime::createFromFormat('!Y-m-d', $v);

            return $d && $d->format('Y-m-d') === $v ? $v : '';
        };
        $f = [
            'tampil'   => $this->tampil(),
            'jenis'    => in_array($this->teks('hasil'), ['ok', 'gagal'], true) ? $this->teks('hasil') : '',
            'kejadian' => isset(self::KEJADIAN[$this->teks('kejadian')]) ? $this->teks('kejadian') : '',
            'q'        => mb_substr($this->teks('q'), 0, 100),
            'dari'     => $tgl($this->teks('dari')),
            'sampai'   => $tgl($this->teks('sampai')),
        ];
        $panjang = (int) $this->teks('length');
        $mulai   = max(0, (int) $this->teks('start'));
        $panjang = $panjang === -1 ? self::MAKS_EXPORT : max(1, min(100, $panjang));

        $model   = new AcsModel();
        $ringkas = $model->ringkasLog();
        $cocok   = $model->jumlahLog($f);
        $baris   = [];
        foreach ($model->daftarLog($f, $panjang, $mulai, (int) $this->teks('kolom'), $this->teks('arah') === 'asc') as $i => $l) {
            $baris[] = [
                'no'       => $mulai + $i + 1,
                'waktu'    => date('d-m-Y H:i:s', strtotime($l['created_at'])),
                'jenis'    => $l['jenis'],
                'kejadian' => $l['kejadian'],
                'sn'       => $l['serial_number'],
                'device'   => $l['serial_number'] !== null ? (int) $l['device_id'] : 0,
                'ip'       => $l['ip_address'],
                'detail'   => $l['detail'],
            ];
        }

        return $this->response->setHeader('Cache-Control', 'no-store')->setJSON([
            'draw'            => (int) $this->teks('draw'),
            'recordsTotal'    => $f['tampil'] === '' ? $ringkas['total'] : $ringkas[$f['tampil']],
            'recordsFiltered' => $cocok,
            'data'            => $baris,
            'ringkas'         => $ringkas,
        ]);
    }
}
