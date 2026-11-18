<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RekapDataTokoModel;

class RekapDataMidi extends BaseController
{
    protected $rekapModel;
    protected $uploadPath = 'uploads/rekap_toko/'; // Lokasi di dalam folder public/

    private const PERUSAHAAN = ['PT Genezis Mitra Technology', 'PT Rtiga Global Media'];

    public function __construct()
    {
        $this->rekapModel = new RekapDataTokoModel();
        helper('activity');
    }

    // Mengarah ke app/Views/RekapDataMidi/index.php
    public function index()
    {
        $data = [
            'title'      => 'Rekap Data Toko',
            'rekap_list' => $this->rekapModel->orderBy('id', 'ASC')->findAll(), // urut sesuai file import (baris 1 s/d akhir)
        ];

        return view('RekapDataMidi/index', $data);
    }

    // Menampilkan halaman FormTambahRKD.php
    public function create()
    {
        $data = [
            'title' => 'Form Rekap Data Toko',
        ];

        return view('RekapDataMidi/FormTambahRKD', $data);
    }

    // Simpan data baru (POST: RekapDataMidi/save)
    public function save()
    {
        if (!$this->validate($this->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Tangani pilihan dropdown vs isi manual
        $branch = ($this->request->getPost('branch') === 'manual')
            ? $this->request->getPost('branch_manual')
            : $this->request->getPost('branch');

        $modem1 = ($this->request->getPost('tipe_modem_gsm_1') === 'manual')
            ? $this->request->getPost('tipe_modem_gsm_1_manual')
            : $this->request->getPost('tipe_modem_gsm_1');

        $modem2 = ($this->request->getPost('tipe_modem_gsm_2') === 'manual')
            ? $this->request->getPost('tipe_modem_gsm_2_manual')
            : $this->request->getPost('tipe_modem_gsm_2');

        $kodeToko = esc($this->request->getPost('kode_toko'));

        // Dokumen/foto: upload file (jpg, png, doc, docx, pdf) atau isi link/teks (mis. link Google Drive)
        $fileFortigate  = $this->resolveDoc('foto_sn_fortigate', $kodeToko . '_fortigate');
        $fileOntFo      = $this->resolveDoc('sn_ont_fo', $kodeToko . '_ont_fo');
        $fileImeiModem  = $this->resolveDoc('foto_imei_modem_1', $kodeToko . '_imei_modem1');
        $fileImeiModem2 = $this->resolveDoc('foto_imei_modem_2', $kodeToko . '_imei_modem2');

        $dataSimpan = [
            'nama_pengisi'           => $this->request->getPost('nama_pengisi'),
            'perusahaan'             => $this->request->getPost('perusahaan'),
            'tanggal'                => $this->request->getPost('tanggal'),
            'toko'                   => $this->request->getPost('toko'),
            'branch'                 => $branch,
            'kode_toko'              => $kodeToko,
            'nama_toko'              => $this->request->getPost('nama_toko'),
            'foto_sn_fortigate'      => $fileFortigate,
            'id_pelanggan_fo'        => $this->request->getPost('id_pelanggan_fo'),
            'sn_ont_fo'              => $fileOntFo,
            'tipe_modem_gsm_1'       => $modem1,
            'foto_imei_modem_1'      => $fileImeiModem,
            'msisdn_modem_1'         => $this->request->getPost('msisdn_modem_1'),
            'iccid_modem_1'          => $this->request->getPost('iccid_modem_1'),
            'tipe_modem_gsm_2'       => $modem2,
            'foto_imei_modem_2'      => $fileImeiModem2,
            'msisdn_modem_2'         => $this->request->getPost('msisdn_modem_2'),
            'iccid_modem_2'          => $this->request->getPost('iccid_modem_2'),
            'sharelock_tikor_alamat' => $this->request->getPost('sharelock_tikor_alamat'),
        ];

        $this->rekapModel->save($dataSimpan);

        $newId = $this->rekapModel->getInsertID();
        log_activity('create', 'rekap_data_toko', $newId, null, null, $dataSimpan);

        return redirect()->to('/RekapDataMidi')->with('success', 'Data rekap toko berhasil ditambahkan.');
    }

    // Update data dari form edit (POST: RekapDataMidi/update)
    public function update()
    {
        $id       = $this->request->getPost('id');
        $existing = $this->rekapModel->find($id);

        if (!$existing) {
            return redirect()->to('/RekapDataMidi')->with('error', 'Data tidak ditemukan.');
        }

        if (!$this->validate($this->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $branch = ($this->request->getPost('branch') === 'manual')
            ? $this->request->getPost('branch_manual')
            : $this->request->getPost('branch');

        $modem1 = ($this->request->getPost('tipe_modem_gsm_1') === 'manual')
            ? $this->request->getPost('tipe_modem_gsm_1_manual')
            : $this->request->getPost('tipe_modem_gsm_1');

        $modem2 = ($this->request->getPost('tipe_modem_gsm_2') === 'manual')
            ? $this->request->getPost('tipe_modem_gsm_2_manual')
            : $this->request->getPost('tipe_modem_gsm_2');

        $kodeToko = esc($this->request->getPost('kode_toko'));

        // Dokumen/foto: upload file baru / hapus file / isi link-teks, sesuai pilihan di form
        $fileFortigate  = $this->resolveDoc('foto_sn_fortigate', $kodeToko . '_fortigate', $existing['foto_sn_fortigate'] ?? null);
        $fileOntFo      = $this->resolveDoc('sn_ont_fo', $kodeToko . '_ont_fo', $existing['sn_ont_fo'] ?? null);
        $fileImeiModem  = $this->resolveDoc('foto_imei_modem_1', $kodeToko . '_imei_modem1', $existing['foto_imei_modem_1'] ?? null);
        $fileImeiModem2 = $this->resolveDoc('foto_imei_modem_2', $kodeToko . '_imei_modem2', $existing['foto_imei_modem_2'] ?? null);

        $dataUpdate = [
            'nama_pengisi'           => $this->request->getPost('nama_pengisi'),
            'perusahaan'             => $this->request->getPost('perusahaan'),
            'tanggal'                => $this->request->getPost('tanggal'),
            'toko'                   => $this->request->getPost('toko'),
            'branch'                 => $branch,
            'kode_toko'              => $kodeToko,
            'nama_toko'              => $this->request->getPost('nama_toko'),
            'foto_sn_fortigate'      => $fileFortigate,
            'id_pelanggan_fo'        => $this->request->getPost('id_pelanggan_fo'),
            'sn_ont_fo'              => $fileOntFo,
            'tipe_modem_gsm_1'       => $modem1,
            'foto_imei_modem_1'      => $fileImeiModem,
            'msisdn_modem_1'         => $this->request->getPost('msisdn_modem_1'),
            'iccid_modem_1'          => $this->request->getPost('iccid_modem_1'),
            'tipe_modem_gsm_2'       => $modem2,
            'foto_imei_modem_2'      => $fileImeiModem2,
            'msisdn_modem_2'         => $this->request->getPost('msisdn_modem_2'),
            'iccid_modem_2'          => $this->request->getPost('iccid_modem_2'),
            'sharelock_tikor_alamat' => $this->request->getPost('sharelock_tikor_alamat'),
        ];

        $this->rekapModel->update($id, $dataUpdate);
        log_activity('update', 'rekap_data_toko', $id, null, $existing, $dataUpdate);

        return redirect()->to('/RekapDataMidi')->with('success', 'Data rekap toko berhasil diperbarui.');
    }

    // Menampilkan halaman FormEditRKD.php
    public function edit($id)
    {
        $row = $this->rekapModel->find($id);
        if (!$row) {
            return redirect()->to('/RekapDataMidi')->with('error', 'Data rekap toko tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Rekap Data Toko',
            'row'   => $row,
        ];

        return view('RekapDataMidi/FormEditRKD', $data);
    }

    // Hapus data (GET: RekapDataMidi/delete/(:num))
    public function delete($id)
    {
        $existing = $this->rekapModel->find($id);
        if ($existing) {
            $this->deleteFileIfExists($existing['foto_sn_fortigate']);
            $this->deleteFileIfExists($existing['sn_ont_fo']);
            $this->deleteFileIfExists($existing['foto_imei_modem_1']);
            $this->deleteFileIfExists($existing['foto_imei_modem_2'] ?? null);

            $this->rekapModel->delete($id);
            log_activity('delete', 'rekap_data_toko', $id, null, $existing, null);

            return redirect()->to('/RekapDataMidi')->with('success', 'Data rekap toko berhasil dihapus.');
        }

        return redirect()->to('/RekapDataMidi')->with('error', 'Data tidak ditemukan.');
    }

    // Import dari Excel (contoh: "REKAP PENDATAAN&PERGANTIAN PERANGKAT ... (Jawaban).xlsx").
    // Kolom dibaca dari judul di baris header (KODE TOKO, NAMA TOKO, dst.), jadi urutan kolom bebas.
    // Semua baris diberi perusahaan yang dipilih di form import.
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

        $perusahaan = (string) $this->request->getPost('perusahaan');
        if (! in_array($perusahaan, self::PERUSAHAAN, true)) {
            return $this->importError('Pilih perusahaan untuk data di file ini.');
        }

        try {
            $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($file->getTempName());
            $reader->setReadDataOnly(true);
            $book = $reader->load($file->getTempName());
        } catch (\Throwable $e) {
            return $this->importError('File Excel tidak bisa dibaca.');
        }

        $norm = static fn ($v) => preg_replace('/[^a-z0-9]/', '', strtolower((string) $v));

        // Cari sheet + baris header yang memuat kolom "KODE TOKO"
        $sheet     = null;
        $headerRow = 0;
        foreach ($book->getAllSheets() as $ws) {
            $maxC = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($ws->getHighestDataColumn());
            for ($r = 1; $r <= min(10, $ws->getHighestDataRow()); $r++) {
                for ($c = 1; $c <= $maxC; $c++) {
                    if (str_contains($norm($ws->getCell([$c, $r])->getValue()), 'kodetoko')) {
                        $sheet     = $ws;
                        $headerRow = $r;
                        break 3;
                    }
                }
            }
        }
        if (! $sheet) {
            return $this->importError('Kolom "KODE TOKO" tidak ditemukan di file. Pastikan baris judul kolom ikut di file.');
        }

        $maxRow = $sheet->getHighestDataRow();
        $maxCol = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($sheet->getHighestDataColumn());

        // Peta judul kolom -> field. Pencocokan memakai teks tanpa spasi/tanda baca.
        $aturan = [
            'timestamp'              => static fn ($h) => $h === 'timestamp',
            'nama_pengisi'           => static fn ($h) => str_contains($h, 'namapengisi'),
            'tanggal'                => static fn ($h) => $h === 'tanggal',
            'toko'                   => static fn ($h) => $h === 'toko',
            'branch'                 => static fn ($h) => str_contains($h, 'branch'),
            'kode_toko'              => static fn ($h) => str_contains($h, 'kodetoko'),
            'nama_toko'              => static fn ($h) => str_contains($h, 'namatoko'),
            'foto_sn_fortigate'      => static fn ($h) => str_contains($h, 'fortigate'),
            'id_pelanggan_fo'        => static fn ($h) => str_contains($h, 'idpelanggan'),
            'sn_ont_fo'              => static fn ($h) => str_contains($h, 'snont'),
            'tipe_modem_gsm_1'       => static fn ($h) => str_contains($h, 'tipemodemgsm1'),
            'foto_imei_modem_1'      => static fn ($h) => str_contains($h, 'imeimodem1'),
            'msisdn_modem_1'         => static fn ($h) => str_contains($h, 'msisdn') && str_contains($h, 'modem1'),
            'iccid_modem_1'          => static fn ($h) => str_contains($h, 'iccid') && str_contains($h, 'modem1'),
            'tipe_modem_gsm_2'       => static fn ($h) => str_contains($h, 'tipemodemgsm2'),
            'foto_imei_modem_2'      => static fn ($h) => str_contains($h, 'imeimodem2'),
            'msisdn_modem_2'         => static fn ($h) => str_contains($h, 'msisdn') && str_contains($h, 'modem2'),
            'iccid_modem_2'          => static fn ($h) => str_contains($h, 'iccid') && str_contains($h, 'modem2'),
            'sharelock_tikor_alamat' => static fn ($h) => str_contains($h, 'sharelock') || str_contains($h, 'tikor'),
        ];
        $kolom = [];
        for ($c = 1; $c <= $maxCol; $c++) {
            $h = $norm($sheet->getCell([$c, $headerRow])->getValue());
            foreach ($aturan as $field => $cocok) {
                if (! isset($kolom[$field]) && $h !== '' && $cocok($h)) {
                    $kolom[$field] = $c;
                    break;
                }
            }
        }
        foreach (['toko' => 'TOKO', 'nama_toko' => 'NAMA TOKO'] as $wajib => $judul) {
            if (! isset($kolom[$wajib])) {
                return $this->importError('Kolom "' . $judul . '" tidak ditemukan di file.');
            }
        }

        $mentah = static function (string $field, int $r) use ($sheet, $kolom) {
            if (! isset($kolom[$field])) {
                return null;
            }
            $v = $sheet->getCell([$kolom[$field], $r])->getValue();
            return $v instanceof \PhpOffice\PhpSpreadsheet\RichText\RichText ? $v->getPlainText() : $v;
        };
        $teks = static function (string $field, int $r, int $maks = 0) use ($mentah): string {
            $v = $mentah($field, $r);
            // angka panjang (MSISDN/ICCID/ID pelanggan) jangan sampai jadi 6.21E+17
            if (is_float($v) && floor($v) === $v && abs($v) < 1e20) {
                $v = sprintf('%.0f', $v);
            }
            $v = trim(preg_replace('/\s+/u', ' ', (string) $v));
            return $maks > 0 ? mb_substr($v, 0, $maks) : $v;
        };
        $tanggal = static function (int $r) use ($mentah): ?string {
            foreach (['tanggal', 'timestamp'] as $f) {
                $v = $mentah($f, $r);
                if (is_numeric($v) && $v > 0) {
                    return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject((float) $v)->format('Y-m-d');
                }
                $v = trim((string) $v);
                foreach (['n/j/Y', 'n/j/Y H:i:s', 'd/m/Y', 'Y-m-d', 'Y-m-d H:i:s'] as $fmt) {
                    $d = \DateTime::createFromFormat('!' . $fmt, $v);
                    if ($d && $d->format($fmt) === $v) {
                        return $d->format('Y-m-d');
                    }
                }
            }
            return null;
        };
        // Nomor HP yang tersimpan sebagai angka kehilangan angka 0 di depan
        $msisdn = static fn (string $v): string => preg_match('/^8\d{7,13}$/', $v) ? '0' . $v : $v;

        $data         = [];
        $tidakDikenal = [];
        for ($r = $headerRow + 1; $r <= $maxRow; $r++) {
            $kode = $teks('kode_toko', $r, 50);
            if ($kode === '') {
                continue; // baris kosong
            }

            $tokoRaw = $teks('toko', $r);
            $toko    = match (true) {
                str_contains(strtolower($tokoRaw), 'lawson') => 'Lawson',
                str_contains(strtolower($tokoRaw), 'midi')   => 'Midi',
                default                                      => null,
            };
            if ($toko === null) {
                $tidakDikenal[] = 'Baris ' . $r . ' (' . $kode . '): toko "' . $tokoRaw . '" tidak dikenal';
                continue;
            }

            $tgl = $tanggal($r);
            if ($tgl === null) {
                $tidakDikenal[] = 'Baris ' . $r . ' (' . $kode . '): tanggal tidak terbaca';
                continue;
            }

            $data[] = [
                'nama_pengisi'           => $teks('nama_pengisi', $r, 100),
                'perusahaan'             => $perusahaan,
                'tanggal'                => $tgl,
                'toko'                   => $toko,
                'branch'                 => $teks('branch', $r, 100),
                'kode_toko'              => $kode,
                'nama_toko'              => $teks('nama_toko', $r, 150),
                'foto_sn_fortigate'      => $teks('foto_sn_fortigate', $r, 255) ?: null,
                'id_pelanggan_fo'        => $teks('id_pelanggan_fo', $r, 100) ?: null,
                'sn_ont_fo'              => $teks('sn_ont_fo', $r, 255) ?: null,
                'tipe_modem_gsm_1'       => $teks('tipe_modem_gsm_1', $r, 100),
                'foto_imei_modem_1'      => $teks('foto_imei_modem_1', $r, 255) ?: null,
                'msisdn_modem_1'         => $msisdn($teks('msisdn_modem_1', $r, 150)) ?: null,
                'iccid_modem_1'          => $teks('iccid_modem_1', $r, 150) ?: null,
                'tipe_modem_gsm_2'       => $teks('tipe_modem_gsm_2', $r, 100) ?: null,
                'foto_imei_modem_2'      => $teks('foto_imei_modem_2', $r, 255) ?: null,
                'msisdn_modem_2'         => $msisdn($teks('msisdn_modem_2', $r, 150)) ?: null,
                'iccid_modem_2'          => $teks('iccid_modem_2', $r, 150) ?: null,
                'sharelock_tikor_alamat' => $teks('sharelock_tikor_alamat', $r),
            ];
        }

        if (! $data) {
            return $this->importError('Tidak ada data toko yang bisa dibaca dari sheet "' . trim($sheet->getTitle()) . '".');
        }

        $ganti = $this->request->getPost('mode') !== 'tambah';

        $db = \Config\Database::connect();
        $db->transStart();

        $dihapus = 0;
        if ($ganti) {
            $lama = $this->rekapModel->where('perusahaan', $perusahaan)->findAll();
            foreach ($lama as $row) {
                foreach (['foto_sn_fortigate', 'sn_ont_fo', 'foto_imei_modem_1', 'foto_imei_modem_2'] as $f) {
                    $this->deleteFileIfExists($row[$f] ?? null);
                }
            }
            $dihapus = count($lama);
            if ($dihapus) {
                $this->rekapModel->where('perusahaan', $perusahaan)->delete();
            }
        }

        // Semua baris di file ikut masuk sesuai urutan file (termasuk toko yang diisi lebih dari sekali).
        // Mode "Tambahkan": hanya baris yang sudah ada di database (kode toko + tanggal sama) yang dilewati,
        // supaya file yang sama tidak masuk dua kali.
        $ada = [];
        if (! $ganti) {
            foreach ($this->rekapModel->select('kode_toko, tanggal')->where('perusahaan', $perusahaan)->findAll() as $row) {
                $ada[strtolower($row['kode_toko']) . '|' . substr((string) $row['tanggal'], 0, 10)] = true;
            }
        }

        $now      = date('Y-m-d H:i:s');
        $baru     = [];
        $dilewati = 0;
        foreach ($data as $d) {
            if (isset($ada[strtolower($d['kode_toko']) . '|' . $d['tanggal']])) {
                $dilewati++;
                continue;
            }
            $baru[] = $d + ['created_at' => $now, 'updated_at' => $now];
        }
        foreach (array_chunk($baru, 200) as $chunk) {
            $db->table('rekap_data_toko')->insertBatch($chunk);
        }

        $db->transComplete();
        if (! $db->transStatus()) {
            return $this->importError('Gagal menyimpan data ke database.');
        }

        $masuk = count($baru);
        log_activity('create', 'rekap_data_toko', null, 'Import Excel ' . $masuk . ' data rekap toko (' . $perusahaan . ')'
            . ($ganti ? ', mengganti ' . $dihapus . ' data lama' : ''));

        $pesan = $masuk . ' data toko ' . $perusahaan . ' berhasil diimport';
        if ($dilewati) {
            $pesan .= ', ' . $dilewati . ' dilewati (kode toko & tanggal sudah ada)';
        }
        session()->setFlashdata('success', esc($pesan));

        return $this->response->setJSON([
            'status'       => 'ok',
            'masuk'        => $masuk,
            'dilewati'     => $dilewati,
            'tidakDikenal' => $tidakDikenal,
        ]);
    }

    // Export Excel (POST: RekapDataMidi/exportExcel). ids = urutan baris hasil filter/pencarian di tabel.
    // Link -> hyperlink yang bisa diklik, foto hasil upload -> gambar tertanam, dokumen upload -> link unduh.
    public function exportExcel()
    {
        $ids = array_values(array_filter(array_map('intval', explode(',', (string) $this->request->getPost('ids')))));
        if (! $ids) {
            return redirect()->to('/RekapDataMidi')->with('error', 'Tidak ada data yang bisa diexport.');
        }

        $byId = [];
        foreach (array_chunk($ids, 500) as $chunk) {
            foreach ($this->rekapModel->whereIn('id', $chunk)->findAll() as $row) {
                $byId[$row['id']] = $row;
            }
        }
        $rows = array_values(array_filter(array_map(static fn ($id) => $byId[$id] ?? null, $ids)));

        $kolom = [
            'No'                        => null,
            'Tanggal'                   => 'tanggal',
            'Nama Pengisi'              => 'nama_pengisi',
            'Perusahaan'                => 'perusahaan',
            'Toko'                      => 'toko',
            'Branch'                    => 'branch',
            'Kode Toko'                 => 'kode_toko',
            'Nama Toko'                 => 'nama_toko',
            'ID Pelanggan FO'           => 'id_pelanggan_fo',
            'Tipe Modem GSM 1'          => 'tipe_modem_gsm_1',
            'MSISDN Modem 1'            => 'msisdn_modem_1',
            'ICCID Modem 1'             => 'iccid_modem_1',
            'Tipe Modem GSM 2'          => 'tipe_modem_gsm_2',
            'MSISDN Modem 2'            => 'msisdn_modem_2',
            'ICCID Modem 2'             => 'iccid_modem_2',
            'Foto SN Fortigate'         => 'foto_sn_fortigate',
            'SN ONT FO'                 => 'sn_ont_fo',
            'Foto IMEI Modem 1'         => 'foto_imei_modem_1',
            'Foto IMEI Modem 2'         => 'foto_imei_modem_2',
            'Sharelock / Tikor / Alamat' => 'sharelock_tikor_alamat',
        ];
        $docFields = ['foto_sn_fortigate', 'sn_ont_fo', 'foto_imei_modem_1', 'foto_imei_modem_2'];
        $imgExt    = ['jpg', 'jpeg', 'png', 'gif'];

        $book  = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $book->getActiveSheet();
        $sheet->setTitle('Rekap Data Toko');
        $C = static fn (int $i) => \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
        $last = $C(count($kolom));

        // Judul + header (seperti export Excel sebelumnya)
        $sheet->setCellValue('A1', 'Rekap Data Toko');
        $sheet->mergeCells('A1:' . $last . '1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');

        $c = 1;
        foreach (array_keys($kolom) as $judul) {
            $sheet->setCellValue($C($c++) . '2', $judul);
        }
        $sheet->getStyle('A2:' . $last . '2')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '04A9F5']],
            'alignment' => ['vertical' => 'center', 'wrapText' => true],
        ]);

        $linkStyle = ['font' => ['color' => ['rgb' => '0563C1'], 'underline' => 'single']];

        $r = 3;
        foreach ($rows as $no => $row) {
            $adaGambar = false;
            $c = 1;
            foreach ($kolom as $field) {
                $cell = $C($c) . $r;
                $c++;
                if ($field === null) {
                    $sheet->setCellValue($cell, $no + 1);
                    continue;
                }

                $v = trim((string) ($row[$field] ?? ''));
                if ($v === '') {
                    continue;
                }

                // file hasil upload di server
                if (in_array($field, $docFields, true) && $this->isLocalFile($v)) {
                    $ext  = strtolower(pathinfo($v, PATHINFO_EXTENSION));
                    $path = FCPATH . $v;
                    if (in_array($ext, $imgExt, true) && is_file($path)) {
                        $img = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
                        $img->setPath($path);
                        $img->setHeight(70);
                        $img->setCoordinates($cell);
                        $img->setOffsetX(4);
                        $img->setOffsetY(4);
                        $img->setWorksheet($sheet);
                        $adaGambar = true;
                        // klik sel = buka gambar aslinya
                        $sheet->getCell($cell)->getHyperlink()->setUrl(base_url($v));
                        continue;
                    }
                    $sheet->setCellValue($cell, 'Unduh ' . basename($v));
                    $sheet->getCell($cell)->getHyperlink()->setUrl(base_url($v));
                    $sheet->getStyle($cell)->applyFromArray($linkStyle);
                    continue;
                }

                // link (Google Drive, Maps, dst.)
                if (preg_match('~^https?://\S+$~i', $v)) {
                    $sheet->setCellValueExplicit($cell, $v, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                    $sheet->getCell($cell)->getHyperlink()->setUrl($v);
                    $sheet->getStyle($cell)->applyFromArray($linkStyle);
                    continue;
                }

                // teks biasa; disimpan sebagai teks supaya nomor HP/ICCID tidak jadi 6.21E+17
                if ($field === 'tanggal') {
                    $sheet->setCellValue($cell, $v);
                } else {
                    $sheet->setCellValueExplicit($cell, $v, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                }
            }
            if ($adaGambar) {
                $sheet->getRowDimension($r)->setRowHeight(60);
            }
            $r++;
        }

        // lebar kolom
        $lebar = [6, 12, 20, 28, 9, 14, 12, 26, 18, 18, 16, 22, 18, 16, 22, 22, 22, 22, 22, 40];
        foreach ($lebar as $i => $w) {
            $sheet->getColumnDimension($C($i + 1))->setWidth($w);
        }
        $sheet->getStyle('A3:' . $last . max(3, $r - 1))->getAlignment()->setVertical('top')->setWrapText(true);
        $sheet->getStyle('A2:' . $last . max(2, $r - 1))->getBorders()->getAllBorders()
            ->setBorderStyle('thin')->getColor()->setRGB('E0E0E0');
        $sheet->freezePane('A3');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($book);
        ob_start();
        $writer->save('php://output');
        $isi = ob_get_clean();

        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment; filename="Rekap Data Toko.xlsx"')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($isi);
    }

    private function importError(string $message)
    {
        return $this->response->setStatusCode(422)
            ->setJSON(['status' => 'error', 'message' => $message]);
    }

    private function getValidationRules(): array
    {
        return [
            'nama_pengisi'           => 'required|max_length[100]',
            'perusahaan'             => 'required|in_list[PT Genezis Mitra Technology,PT Rtiga Global Media]',
            'tanggal'                => 'required|valid_date',
            'toko'                   => 'required|in_list[Lawson,Midi]',
            'branch'                 => 'required',
            'kode_toko'              => 'required|max_length[50]',
            'nama_toko'              => 'required|max_length[150]',
            'link_foto_sn_fortigate' => 'permit_empty|max_length[255]',
            'link_sn_ont_fo'         => 'permit_empty|max_length[255]',
            'link_foto_imei_modem_1' => 'permit_empty|max_length[255]',
            'link_foto_imei_modem_2' => 'permit_empty|max_length[255]',
            'foto_sn_fortigate'      => 'permit_empty|max_size[foto_sn_fortigate,5120]|ext_in[foto_sn_fortigate,jpg,jpeg,png,doc,docx,pdf]',
            'sn_ont_fo'              => 'permit_empty|max_size[sn_ont_fo,5120]|ext_in[sn_ont_fo,jpg,jpeg,png,doc,docx,pdf]',
            'tipe_modem_gsm_1'       => 'required',
            'foto_imei_modem_1'      => 'permit_empty|max_size[foto_imei_modem_1,5120]|ext_in[foto_imei_modem_1,jpg,jpeg,png,doc,docx,pdf]',
            'foto_imei_modem_2'      => 'permit_empty|max_size[foto_imei_modem_2,5120]|ext_in[foto_imei_modem_2,jpg,jpeg,png,doc,docx,pdf]',
            'msisdn_modem_1'         => 'permit_empty|max_length[150]',
            'iccid_modem_1'          => 'permit_empty|max_length[150]',
            'msisdn_modem_2'         => 'permit_empty|max_length[150]',
            'iccid_modem_2'          => 'permit_empty|max_length[150]',
            'sharelock_tikor_alamat' => 'required',
        ];
    }

    private function handleUpload($file, string $prefix, ?string $oldFile = null): ?string
    {
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $this->deleteFileIfExists($oldFile);

            // Buat folder otomatis jika belum ada
            if (!is_dir(FCPATH . $this->uploadPath)) {
                mkdir(FCPATH . $this->uploadPath, 0775, true);
            }

            $newName = time() . '_' . preg_replace('/[^A-Za-z0-9\-]/', '', $prefix) . '.' . $file->getExtension();
            $file->move(FCPATH . $this->uploadPath, $newName);

            return $this->uploadPath . $newName;
        }

        return $oldFile;
    }

    // Pilihan dokumen di form: mode_<field> = 'link' (isi teks/link) atau 'file' (upload)
    private function resolveDoc(string $field, string $prefix, ?string $old = null): ?string
    {
        if ($this->request->getPost('mode_' . $field) === 'link') {
            $link = trim((string) $this->request->getPost('link_' . $field));
            if ($old !== null && $old !== $link) {
                $this->deleteFileIfExists($old); // file upload lama diganti link
            }
            return $link === '' ? null : $link;
        }

        if ($this->request->getPost('hapus_' . $field) === '1') {
            $this->deleteFileIfExists($old);
            $old = null;
        }

        return $this->handleUpload($this->request->getFile($field), $prefix, $old);
    }

    // Hanya file hasil upload (uploads/rekap_toko/...) yang boleh dihapus, bukan link/teks
    private function isLocalFile(?string $filePath): bool
    {
        return !empty($filePath)
            && str_starts_with($filePath, $this->uploadPath)
            && !str_contains($filePath, '..');
    }

    private function deleteFileIfExists(?string $filePath): void
    {
        if ($this->isLocalFile($filePath) && is_file(FCPATH . $filePath)) {
            unlink(FCPATH . $filePath);
        }
    }
}
