<?php

namespace App\Controllers;

use App\Models\JadwalModel;
use App\Models\PiketModel;

class Piket extends BaseController
{
    protected $model;

    // Label & warna piket (warna otomatis mengikuti nomor piket)
    public const LABEL = [1 => 'PIKET 1', 2 => 'PIKET 2', 3 => 'PIKET 3'];
    public const WARNA = [1 => '#FFA500', 2 => '#FFFF00', 3 => '#92D050']; // oranye, kuning, hijau
    public const KETERANGAN = [
        1 => 'Menyapu semua area kantor',
        2 => 'Mengepel semua area kantor',
        3 => 'Membuang sampah',
    ];

    private const BULAN = [
        'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4, 'mei' => 5, 'juni' => 6,
        'juli' => 7, 'agustus' => 8, 'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12,
    ];

    public function __construct()
    {
        $this->model = new PiketModel();
    }

    // Halaman utama jadwal piket
    public function index()
    {
        // Nama untuk dropdown diambil dari data shift (jadwal_noc)
        $names = (new JadwalModel())->distinct()->select('nama')
            ->where('nama !=', '')
            ->orderBy('nama', 'ASC')
            ->findColumn('nama') ?? [];

        return view('Piket/index', [
            'names' => $names,
            'label' => self::LABEL,
            'warna' => self::WARNA,
            'keterangan' => self::KETERANGAN,
        ]);
    }

    // Sumber data JSON untuk FullCalendar
    public function events()
    {
        $start = substr($this->request->getGet('start') ?? date('Y-m-01'), 0, 10);
        $end   = substr($this->request->getGet('end')   ?? date('Y-m-t'), 0, 10);

        $events = [];
        foreach ($this->model->getByRange($start, $end) as $r) {
            $p = (int) $r['piket'];
            $events[] = [
                'id'            => $r['id'],
                'title'         => (self::LABEL[$p] ?? 'PIKET') . ' — ' . $r['nama'],
                'start'         => $r['tanggal'],
                'allDay'        => true,
                'color'         => self::WARNA[$p] ?? '#04a9f5',
                'extendedProps' => [
                    'piket'      => $p,
                    'nama'       => $r['nama'],
                    'keterangan' => $r['keterangan'],
                    'tanggal'    => $r['tanggal'],
                ],
            ];
        }

        return $this->response->setJSON($events);
    }

    // Simpan: tambah (id kosong) atau edit (id ada)
    public function save()
    {
        $id   = $this->request->getPost('id');
        $data = [
            'tanggal'    => $this->request->getPost('tanggal'),
            'nama'       => $this->request->getPost('nama'),
            'piket'      => (int) $this->request->getPost('piket'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        if (! $data['tanggal'] || ! $data['nama'] || ! isset(self::LABEL[$data['piket']])) {
            return $this->response->setStatusCode(422)
                ->setJSON(['status' => 'error', 'message' => 'Tanggal, nama, dan piket wajib diisi.']);
        }

        if ($id) {
            $this->model->update($id, $data);
        } else {
            $id = $this->model->insert($data);
        }

        return $this->response->setJSON(['status' => 'ok', 'id' => $id]);
    }

    // Hapus
    public function delete($id = null)
    {
        if ($id) {
            $this->model->delete($id);
        }
        return $this->response->setJSON(['status' => 'ok']);
    }

    // Import dari Excel: sheet "Jadwal Piket" (blok per minggu: baris Tanggal lalu PIKET 1/2/3)
    public function import()
    {
        $file = $this->request->getFile('file_import');
        if (! $file || ! $file->isValid()) {
            return $this->importError('File belum dipilih atau gagal diupload.');
        }
        if (! in_array(strtolower($file->getClientExtension()), ['xlsx', 'xls'], true)) {
            return $this->importError('File harus berformat Excel (.xlsx / .xls).');
        }
        if ($file->getSize() > 5 * 1024 * 1024) {
            return $this->importError('Ukuran file maksimal 5 MB.');
        }

        try {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file->getTempName());
            $reader->setReadDataOnly(true);
            $book = $reader->load($file->getTempName());
        } catch (\Throwable $e) {
            return $this->importError('File Excel tidak bisa dibaca.');
        }

        // cari sheet piket (nama sheet bisa ada spasi di belakang)
        $sheet = null;
        foreach ($book->getAllSheets() as $ws) {
            if (stripos(trim($ws->getTitle()), 'piket') !== false) {
                $sheet = $ws;
                break;
            }
        }
        if (! $sheet) {
            return $this->importError('Sheet "Jadwal Piket" tidak ditemukan di file.');
        }

        $rows = $sheet->toArray(null, true, false, false);

        // bulan & tahun dari judul, mis. "JADWAL PIKET BULAN OKTOBER 2026"
        $bulan = $tahun = null;
        foreach (array_slice($rows, 0, 10) as $row) {
            foreach ($row as $v) {
                if (is_string($v) && preg_match('/(' . implode('|', array_keys(self::BULAN)) . ')\s+(\d{4})/i', $v, $m)) {
                    $bulan = self::BULAN[strtolower($m[1])];
                    $tahun = (int) $m[2];
                    break 2;
                }
            }
        }
        if (! $bulan) {
            return $this->importError('Bulan & tahun tidak ditemukan di judul sheet (contoh: "JADWAL PIKET BULAN OKTOBER 2026").');
        }

        // baris "Tanggal" menentukan kolom -> tanggal, baris "PIKET n" di bawahnya berisi nama;
        // baris kosong menutup blok (supaya keterangan piket di bawah tabel tidak ikut terbaca)
        $data = [];
        $kolomTanggal = [];
        foreach ($rows as $row) {
            $kepala = strtolower(trim((string) ($row[0] ?? '')));

            if ($kepala === '') {
                $kolomTanggal = [];
                continue;
            }

            if ($kepala === 'tanggal') {
                $kolomTanggal = [];
                foreach ($row as $c => $v) {
                    if ($c > 0 && is_numeric($v) && checkdate($bulan, (int) $v, $tahun)) {
                        $kolomTanggal[$c] = sprintf('%04d-%02d-%02d', $tahun, $bulan, (int) $v);
                    }
                }
                continue;
            }

            if ($kolomTanggal && preg_match('/^piket\s*([123])$/', $kepala, $m)) {
                $piket = (int) $m[1];
                foreach ($kolomTanggal as $c => $tanggal) {
                    $nama = trim(preg_replace('/\s+/', ' ', (string) ($row[$c] ?? '')));
                    if ($nama === '') {
                        continue;
                    }
                    $data[] = [
                        'tanggal'    => $tanggal,
                        'nama'       => $nama,
                        'piket'      => $piket,
                        'keterangan' => self::KETERANGAN[$piket],
                    ];
                }
            }
        }

        if (! $data) {
            return $this->importError('Tidak ada data piket yang bisa dibaca dari sheet "' . trim($sheet->getTitle()) . '".');
        }

        $awal  = sprintf('%04d-%02d-01', $tahun, $bulan);
        $akhir = date('Y-m-t', strtotime($awal));
        $ganti = $this->request->getPost('mode') !== 'tambah';

        $db = \Config\Database::connect();
        $db->transStart();

        $dihapus = 0;
        if ($ganti) {
            $dihapus = $this->model->where('tanggal >=', $awal)->where('tanggal <=', $akhir)->countAllResults();
            $this->model->where('tanggal >=', $awal)->where('tanggal <=', $akhir)->delete();
        }

        $masuk = $dilewati = 0;
        foreach ($data as $d) {
            $ada = $this->model->where([
                'tanggal' => $d['tanggal'],
                'nama'    => $d['nama'],
                'piket'   => $d['piket'],
            ])->countAllResults();
            if ($ada) {
                $dilewati++;
                continue;
            }
            $this->model->insert($d);
            $masuk++;
        }

        $db->transComplete();
        if (! $db->transStatus()) {
            return $this->importError('Gagal menyimpan data ke database.');
        }

        return $this->response->setJSON([
            'status'   => 'ok',
            'bulan'    => ucfirst(array_search($bulan, self::BULAN, true)) . ' ' . $tahun,
            'awal'     => $awal,
            'masuk'    => $masuk,
            'dilewati' => $dilewati,
            'dihapus'  => $dihapus,
        ]);
    }

    private function importError(string $message)
    {
        return $this->response->setStatusCode(422)
            ->setJSON(['status' => 'error', 'message' => $message]);
    }
}
