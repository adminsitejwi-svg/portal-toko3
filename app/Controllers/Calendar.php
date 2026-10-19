<?php

namespace App\Controllers;

use App\Models\JadwalModel;

class Calendar extends BaseController
{
    protected $model;

    // Label & warna default shift (5 = HO, kode "OH"/"HO" di Excel)
    public const LABEL = [1 => 'Shift 1', 2 => 'Shift 2', 3 => 'Shift 3', 4 => 'Off', 5 => 'HO'];
    public const WARNA = [1 => '#92D050', 2 => '#FFFF00', 3 => '#00B0F0', 4 => '#FF0000', 5 => '#8E7CC3'];

    // Kode di sheet Jadwal Shift => nomor shift
    private const KODE = ['P' => 1, 'S' => 2, 'M' => 3, 'O' => 4, 'OH' => 5, 'HO' => 5];

    private const BULAN = [
        'januari' => 1, 'februari' => 2, 'maret' => 3, 'april' => 4, 'mei' => 5, 'juni' => 6,
        'juli' => 7, 'agustus' => 8, 'september' => 9, 'oktober' => 10, 'november' => 11, 'desember' => 12,
    ];

    public function __construct()
    {
        $this->model = new JadwalModel();
    }

    // Halaman utama kalender
    public function index()
    {
        return view('Calendar/index', [
            'label' => self::LABEL,
            'warna' => self::WARNA,
        ]);
    }

    // Sumber data JSON untuk FullCalendar
    public function events()
    {
        // FullCalendar mengirim start & end sesuai bulan yang sedang tampil.
        // Kalau kosong, default ke bulan berjalan (mengikuti tanggal sistem Windows).
        $start = $this->request->getGet('start') ?? date('Y-m-01');
        $end   = $this->request->getGet('end')   ?? date('Y-m-t');
        $start = substr($start, 0, 10);
        $end   = substr($end, 0, 10);

        $rows = $this->model->getByRange($start, $end);

        $events = [];
        foreach ($rows as $r) {
            $events[] = [
                'id'            => $r['id'],
                'title'         => (self::LABEL[$r['shift']] ?? 'Shift') . ' — ' . $r['nama'],
                'start'         => $r['tanggal'],
                'allDay'        => true,
                'color'         => $r['warna'],
                'extendedProps' => [
                    'shift'      => (int) $r['shift'],
                    'nama'       => $r['nama'],
                    'warna'      => $r['warna'],
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
            'shift'      => $this->request->getPost('shift'),
            'nama'       => $this->request->getPost('nama'),
            'warna'      => $this->request->getPost('warna') ?: '#3788d8',
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        if (! $data['tanggal'] || ! $data['shift'] || ! $data['nama']) {
            return $this->response->setStatusCode(422)
                ->setJSON(['status' => 'error', 'message' => 'Tanggal, shift, dan nama wajib diisi.']);
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

    // Import dari Excel: sheet "Jadwal Shift". Tiap tabel diawali judul
    // "Jadwal <grup> Bulan <bulan> <tahun>" -> <grup> jadi keterangan.
    // Warna sel ikut disimpan; warna yang ada di legenda (Tanggal Merah, Cuti, dst.)
    // ditambahkan ke keterangan.
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
            // warna sel dibutuhkan, jadi tidak memakai setReadDataOnly
            $book = \PhpOffice\PhpSpreadsheet\IOFactory::load($file->getTempName());
        } catch (\Throwable $e) {
            return $this->importError('File Excel tidak bisa dibaca.');
        }

        $sheet = null;
        foreach ($book->getAllSheets() as $ws) {
            if (stripos(trim($ws->getTitle()), 'shift') !== false) {
                $sheet = $ws;
                break;
            }
        }
        if (! $sheet) {
            return $this->importError('Sheet "Jadwal Shift" tidak ditemukan di file.');
        }

        $maxRow = $sheet->getHighestDataRow();
        $maxCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($sheet->getHighestDataColumn());

        $nilai = static fn (int $c, int $r) => trim(preg_replace('/\s+/', ' ', (string) $sheet->getCell([$c, $r])->getCalculatedValue()));
        $warna = static function (int $c, int $r) use ($sheet): ?string {
            $fill = $sheet->getCell([$c, $r])->getStyle()->getFill();
            if ($fill->getFillType() !== \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID) {
                return null;
            }
            $rgb = strtoupper($fill->getStartColor()->getRGB());
            return $rgb === 'FFFFFF' ? null : '#' . $rgb;
        };

        // legenda: sel berwarna tanpa isi + teks di sebelah kanannya, di luar baris petugas
        $legenda = [];
        for ($r = 1; $r <= $maxRow; $r++) {
            if (is_numeric($nilai(1, $r))) {
                continue;
            }
            for ($c = 1; $c < $maxCol; $c++) {
                $w    = $warna($c, $r);
                $teks = $nilai($c + 1, $r);
                if ($w && $nilai($c, $r) === '' && mb_strlen($teks) > 2) {
                    $legenda[$w] = $teks;
                }
            }
        }

        $polaJudul = '/^jadwal\s+(.+?)\s+bulan\s+(' . implode('|', array_keys(self::BULAN)) . ')\s+(\d{4})/i';

        $data         = [];
        $tidakDikenal = [];
        $grup         = $bulan = $tahun = null;
        $kolomTanggal = [];
        $periode      = [];
        for ($r = 1; $r <= $maxRow; $r++) {
            $a = $nilai(1, $r);

            if (preg_match($polaJudul, $a, $m)) {
                $grup  = $m[1];
                $bulan = self::BULAN[strtolower($m[2])];
                $tahun = (int) $m[3];
                $periode[sprintf('%04d-%02d', $tahun, $bulan)] = true;
                $kolomTanggal = [];
                continue;
            }

            if (! $grup) {
                continue;
            }

            // baris "TANGGAL" berisi nomor hari 1..31
            if (strtolower($nilai(3, $r)) === 'tanggal') {
                $kolomTanggal = [];
                for ($c = 4; $c <= $maxCol; $c++) {
                    $v = $nilai($c, $r);
                    if (is_numeric($v) && checkdate($bulan, (int) $v, $tahun)) {
                        $kolomTanggal[$c] = sprintf('%04d-%02d-%02d', $tahun, $bulan, (int) $v);
                    }
                }
                continue;
            }

            // baris petugas: kolom NO berisi angka, kolom NAMA terisi
            $nama = $nilai(2, $r);
            if (! $kolomTanggal || ! is_numeric($a) || $nama === '') {
                continue;
            }

            foreach ($kolomTanggal as $c => $tanggal) {
                $kode  = strtoupper($nilai($c, $r));
                $w     = $warna($c, $r);
                $tanda = $w !== null ? ($legenda[$w] ?? null) : null;

                $shift = self::KODE[$kode] ?? null;
                if ($shift === null) {
                    if ($tanda === null) {
                        if ($kode !== '') {
                            $tidakDikenal[] = $nama . ' tgl ' . (int) substr($tanggal, 8) . ' (' . $kode . ')';
                        }
                        continue;
                    }
                    $shift = 4; // sel bertanda legenda (mis. Cuti) tanpa kode shift dianggap tidak masuk
                }

                $data[] = [
                    'tanggal'    => $tanggal,
                    'shift'      => $shift,
                    'nama'       => $nama,
                    'warna'      => $w ?? self::WARNA[$shift],
                    'keterangan' => $grup . ($tanda ? ' - ' . $tanda : ''),
                ];
            }
        }

        if (! $data) {
            return $this->importError('Tidak ada jadwal shift yang bisa dibaca dari sheet "' . trim($sheet->getTitle()) . '".');
        }

        $ganti = $this->request->getPost('mode') !== 'tambah';

        $db = \Config\Database::connect();
        $db->transStart();

        if ($ganti) {
            foreach (array_keys($periode) as $ym) {
                $this->model->where('tanggal >=', $ym . '-01')
                    ->where('tanggal <=', date('Y-m-t', strtotime($ym . '-01')))
                    ->delete();
            }
        }

        $masuk = $dilewati = 0;
        foreach ($data as $d) {
            // satu orang satu jadwal per hari
            $ada = $this->model->where(['tanggal' => $d['tanggal'], 'nama' => $d['nama']])->countAllResults();
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

        $bulanNama = array_map(static function ($ym) {
            [$y, $mo] = explode('-', $ym);
            return ucfirst(array_search((int) $mo, self::BULAN, true)) . ' ' . $y;
        }, array_keys($periode));

        return $this->response->setJSON([
            'status'       => 'ok',
            'bulan'        => implode(', ', $bulanNama),
            'awal'         => array_key_first($periode) . '-01',
            'masuk'        => $masuk,
            'dilewati'     => $dilewati,
            'tidakDikenal' => $tidakDikenal,
        ]);
    }

    private function importError(string $message)
    {
        return $this->response->setStatusCode(422)
            ->setJSON(['status' => 'error', 'message' => $message]);
    }

    public function notes()
    {
        $file = WRITEPATH . 'calendar_notes.json';

        // POST -> simpan
        if (strtolower($this->request->getMethod()) === 'post') {
            $raw = $this->request->getPost('data') ?? '[]';
            $arr = json_decode($raw, true);
            if (! is_array($arr)) {
                $arr = [];
            }
            file_put_contents($file, json_encode(array_values($arr), JSON_UNESCAPED_UNICODE));
            return $this->response->setJSON(['status' => 'ok']);
        }

        // GET -> ambil
        $json = is_file($file) ? file_get_contents($file) : '[]';
        $data = json_decode($json, true);
        if (! is_array($data)) {
            $data = [];
        }
        return $this->response->setJSON($data);
    }
}
