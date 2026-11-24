<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RekapDataTokoModel;

// Halaman Rekap Data Toko, dibagi 3 tab: FO + Modem 2, Modem 1 + Modem 2, VSAT + Modem.
// Susunan form & kolom tiap tab diatur di RekapDataTokoModel::BAGIAN.
class RekapDataMidi extends BaseController
{
    protected $rekapModel;
    protected $uploadPath = 'uploads/rekap_toko/'; // Lokasi di dalam folder public/

    private const EXT_DOK = ['jpg', 'jpeg', 'png', 'doc', 'docx', 'pdf'];

    public function __construct()
    {
        $this->rekapModel = new RekapDataTokoModel();
        helper('activity');
    }

    // Halaman utama per bagian (view RekapDataMidi/index.php)
    public function index(string $bagian = 'fo')
    {
        $siap = $this->siap();

        return view('RekapDataMidi/index', [
            'title'      => 'Rekap Data Toko',
            'bagian'     => $bagian,
            'siap'       => $siap,
            'rekap_list' => $siap
                ? $this->rekapModel->where('kategori', $bagian)->orderBy('id', 'ASC')->findAll() // urut sesuai file import (baris 1 s/d akhir)
                : [],
        ]);
    }

    // Halaman form tambah (view RekapDataMidi/FormTambahRKD.php)
    public function create(string $bagian = 'fo')
    {
        if (! $this->siap()) {
            return $this->belumSiap();
        }

        return view('RekapDataMidi/FormTambahRKD', [
            'title'  => 'Form Rekap Data Toko',
            'bagian' => $bagian,
            'row'    => [],
        ]);
    }

    // Halaman form edit (view RekapDataMidi/FormEditRKD.php)
    public function edit($id)
    {
        if (! $this->siap()) {
            return $this->belumSiap();
        }

        $row = $this->rekapModel->find($id);
        if (! $row) {
            return redirect()->to(site_url('RekapDataMidi'))->with('error', 'Data rekap toko tidak ditemukan.');
        }

        return view('RekapDataMidi/FormEditRKD', [
            'title'  => 'Edit Rekap Data Toko',
            'bagian' => $row['kategori'],
            'row'    => $row,
        ]);
    }

    // Simpan data baru (POST: RekapDataMidi/save)
    public function save()
    {
        if (! $this->siap()) {
            return $this->belumSiap();
        }

        $bagian = (string) $this->request->getPost('kategori');
        if (! isset(RekapDataTokoModel::BAGIAN[$bagian])) {
            return redirect()->to(site_url('RekapDataMidi'))->with('error', 'Bagian rekap data toko tidak dikenal.');
        }

        $fields = $this->collectFields($bagian);
        $errors = $this->cekIsian($bagian, $fields);
        if ($errors) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $data = ['kategori' => $bagian] + $fields + $this->collectDok($bagian, $fields['kode_toko']);

        $id = $this->rekapModel->insert($data);
        log_activity('create', 'rekap_data_toko', $id, null, null, $data);

        return redirect()->to($this->urlBagian($bagian))
            ->with('success', 'Data ' . RekapDataTokoModel::BAGIAN[$bagian]['nama'] . ' berhasil ditambahkan.');
    }

    // Update data dari form edit (POST: RekapDataMidi/update). Kolom di luar form bagian tidak diubah.
    public function update()
    {
        if (! $this->siap()) {
            return $this->belumSiap();
        }

        $id       = $this->request->getPost('id');
        $existing = $id ? $this->rekapModel->find((int) $id) : null;
        if (! $existing) {
            return redirect()->to(site_url('RekapDataMidi'))->with('error', 'Data tidak ditemukan.');
        }

        $bagian = $existing['kategori'];
        $fields = $this->collectFields($bagian);
        $errors = $this->cekIsian($bagian, $fields, $existing);
        if ($errors) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $data = $fields + $this->collectDok($bagian, $fields['kode_toko'], $existing);

        $this->rekapModel->update($existing['id'], $data);
        log_activity('update', 'rekap_data_toko', $existing['id'], null, $existing, $data);

        return redirect()->to($this->urlBagian($bagian))
            ->with('success', 'Data ' . RekapDataTokoModel::BAGIAN[$bagian]['nama'] . ' berhasil diperbarui.');
    }

    // Hapus data (GET: RekapDataMidi/delete/(:num))
    public function delete($id)
    {
        $existing = $this->rekapModel->find($id);
        if (! $existing) {
            return redirect()->to(site_url('RekapDataMidi'))->with('error', 'Data tidak ditemukan.');
        }

        foreach (RekapDataTokoModel::kolomDok() as $f) {
            $this->deleteFileIfExists($existing[$f] ?? null);
        }
        $this->rekapModel->delete($id);
        log_activity('delete', 'rekap_data_toko', $id, null, $existing, null);

        $bagian = $existing['kategori'] ?? 'fo';

        return redirect()->to($this->urlBagian($bagian))
            ->with('success', 'Data ' . RekapDataTokoModel::BAGIAN[$bagian]['nama'] . ' berhasil dihapus.');
    }

    // Kolom `kategori` baru ada setelah blok UPDATE 2026-11-24 di database/rekap_data_toko.sql dijalankan
    private function siap(): bool
    {
        return \Config\Database::connect()->fieldExists('kategori', 'rekap_data_toko');
    }

    private function belumSiap()
    {
        return redirect()->to(site_url('RekapDataMidi'))
            ->with('error', 'Database belum diperbarui. Jalankan blok UPDATE 2026-11-24 di database/rekap_data_toko.sql.');
    }

    private function urlBagian(string $bagian): string
    {
        return site_url(RekapDataTokoModel::BAGIAN[$bagian]['url']);
    }

    // Isian form satu bagian (selain dokumen)
    private function collectFields(string $bagian): array
    {
        $post = fn (string $k) => trim((string) $this->request->getPost($k));
        // dropdown dengan pilihan "Isi Manual"
        $pilih = fn (string $k) => $post($k) === 'manual' ? $post($k . '_manual') : $post($k);

        $fields = [];
        foreach (array_keys(RekapDataTokoModel::UMUM) as $k) {
            $fields[$k] = $k === 'branch' ? $pilih($k) : $post($k);
        }
        foreach (RekapDataTokoModel::kolomIsian($bagian) as $k) {
            $jenis = RekapDataTokoModel::ISIAN[$k][1];
            if ($jenis === 'dok') {
                continue;
            }
            $v          = $jenis === 'modem' ? $pilih($k) : $post($k);
            $fields[$k] = $v === '' && $k !== 'sharelock_tikor_alamat' ? null : $v;
        }

        return $fields;
    }

    // Periksa isian & file sebelum disimpan. Hasil: daftar pesan salah (kosong = lolos)
    private function cekIsian(string $bagian, array $fields, array $existing = []): array
    {
        // perusahaan lama yang sudah tidak ada di pilihan tetap boleh saat data lamanya diedit
        $perusahaan = array_filter(array_merge(RekapDataTokoModel::PERUSAHAAN, [$existing['perusahaan'] ?? '']), 'strlen');

        $rules = [
            'nama_pengisi' => ['label' => 'Nama Pengisi', 'rules' => 'required|max_length[100]'],
            'perusahaan'   => ['label' => 'Perusahaan', 'rules' => 'required|in_list[' . implode(',', $perusahaan) . ']'],
            'tanggal'      => ['label' => 'Tanggal', 'rules' => 'required|valid_date[Y-m-d]'],
            'toko'         => ['label' => 'Toko', 'rules' => 'required|in_list[' . implode(',', RekapDataTokoModel::TOKO) . ']'],
            'branch'       => ['label' => 'Branch', 'rules' => 'required|max_length[100]'],
            'kode_toko'    => ['label' => 'Kode Toko', 'rules' => 'required|max_length[50]'],
            'nama_toko'    => ['label' => 'Nama Toko', 'rules' => 'required|max_length[150]'],
        ];
        foreach (RekapDataTokoModel::kolomIsian($bagian) as $k) {
            [$judul, $jenis, $maks] = RekapDataTokoModel::ISIAN[$k];
            if ($jenis === 'dok') {
                continue;
            }
            $wajib     = in_array($k, RekapDataTokoModel::BAGIAN[$bagian]['wajib'], true);
            $rules[$k] = ['label' => $judul, 'rules' => ($wajib ? 'required' : 'permit_empty') . ($maks ? '|max_length[' . $maks . ']' : '')];
        }

        foreach ($rules as &$r) {
            $r['errors'] = [
                'required'   => '{field} wajib diisi.',
                'in_list'    => '{field} tidak ada di pilihan.',
                'max_length' => '{field} maksimal {param} karakter.',
                'valid_date' => '{field} tidak valid.',
            ];
        }
        unset($r);

        $errors = $this->validateData($fields, $rules) ? [] : $this->validator->getErrors();

        foreach (RekapDataTokoModel::kolomIsian($bagian) as $k) {
            [$judul, $jenis, $maks] = RekapDataTokoModel::ISIAN[$k];
            if ($jenis !== 'dok') {
                continue;
            }
            if ($this->request->getPost('mode_' . $k) === 'link') {
                if (mb_strlen(trim((string) $this->request->getPost('link_' . $k))) > $maks) {
                    $errors[$k] = $judul . ' maksimal ' . $maks . ' karakter.';
                }
                continue;
            }
            $file = $this->request->getFile($k);
            if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            if (! $file->isValid()) {
                $errors[$k] = $judul . ' gagal diupload: ' . $file->getErrorString();
            } elseif (! in_array(strtolower($file->getClientExtension()), self::EXT_DOK, true) || ! in_array(strtolower($file->getExtension()), self::EXT_DOK, true)) {
                $errors[$k] = $judul . ' harus berformat JPG, PNG, Word (DOC/DOCX), atau PDF.';
            } elseif ($file->getSize() > 5 * 1024 * 1024) {
                $errors[$k] = $judul . ' maksimal berukuran 5 MB.';
            }
        }

        return $errors;
    }

    // Dokumen/foto satu bagian: upload file (jpg, png, doc, docx, pdf) atau link/teks (mis. link Google Drive)
    private function collectDok(string $bagian, string $kodeToko, array $existing = []): array
    {
        $hasil = [];
        foreach (array_intersect(RekapDataTokoModel::kolomIsian($bagian), RekapDataTokoModel::kolomDok()) as $k) {
            $hasil[$k] = $this->resolveDoc($k, $kodeToko . '_' . str_replace('_', '', $k), $existing[$k] ?? null);
        }

        return $hasil;
    }

    // Import dari Excel (contoh: "REKAP PENDATAAN&PERGANTIAN PERANGKAT ... (Jawaban).xlsx").
    // Kolom dibaca dari judul di baris header (KODE TOKO, NAMA TOKO, dst.), jadi urutan kolom bebas.
    // Semua baris diberi perusahaan yang dipilih di form import, dan masuk ke tabel (bagian) yang dipilih di dropdown.
    public function import()
    {
        if (! $this->siap()) {
            return $this->importError('Database belum diperbarui. Jalankan blok UPDATE 2026-11-24 di database/rekap_data_toko.sql.');
        }
        $bagian = (string) $this->request->getPost('kategori');
        if (! isset(RekapDataTokoModel::BAGIAN[$bagian])) {
            return $this->importError('Bagian rekap data toko tidak dikenal.');
        }
        $namaBagian = RekapDataTokoModel::BAGIAN[$bagian]['nama'];

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
        if (! in_array($perusahaan, RekapDataTokoModel::PERUSAHAAN, true)) {
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
            'kategori_koneksi_vsat'  => static fn ($h) => str_contains($h, 'kategorikoneksi'),
            'nama_vendor'            => static fn ($h) => str_contains($h, 'namavendor'),
            'nomor_inet'             => static fn ($h) => str_contains($h, 'nomorinet') || str_contains($h, 'nomerinet'),
            'kategori_produk'        => static fn ($h) => str_contains($h, 'kategoriproduk'),
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
                'kategori'               => $bagian,
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
                'tipe_modem_gsm_1'       => $teks('tipe_modem_gsm_1', $r, 100) ?: null,
                'foto_imei_modem_1'      => $teks('foto_imei_modem_1', $r, 255) ?: null,
                'msisdn_modem_1'         => $msisdn($teks('msisdn_modem_1', $r, 150)) ?: null,
                'iccid_modem_1'          => $teks('iccid_modem_1', $r, 150) ?: null,
                'tipe_modem_gsm_2'       => $teks('tipe_modem_gsm_2', $r, 100) ?: null,
                'foto_imei_modem_2'      => $teks('foto_imei_modem_2', $r, 255) ?: null,
                'msisdn_modem_2'         => $msisdn($teks('msisdn_modem_2', $r, 150)) ?: null,
                'iccid_modem_2'          => $teks('iccid_modem_2', $r, 150) ?: null,
                'sharelock_tikor_alamat' => $teks('sharelock_tikor_alamat', $r),
                'kategori_koneksi_vsat'  => $teks('kategori_koneksi_vsat', $r, 100) ?: null,
                'nama_vendor'            => $teks('nama_vendor', $r, 150) ?: null,
                'nomor_inet'             => $teks('nomor_inet', $r, 100) ?: null,
                'kategori_produk'        => $teks('kategori_produk', $r, 100) ?: null,
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
            // hanya data perusahaan itu di bagian ini; bagian lain tidak disentuh
            $lama = $this->rekapModel->where('kategori', $bagian)->where('perusahaan', $perusahaan)->findAll();
            foreach ($lama as $row) {
                foreach (RekapDataTokoModel::kolomDok() as $f) {
                    $this->deleteFileIfExists($row[$f] ?? null);
                }
            }
            $dihapus = count($lama);
            if ($dihapus) {
                $this->rekapModel->where('kategori', $bagian)->where('perusahaan', $perusahaan)->delete();
            }
        }

        // Semua baris di file ikut masuk sesuai urutan file (termasuk toko yang diisi lebih dari sekali).
        // Mode "Tambahkan": hanya baris yang sudah ada di database (kode toko + tanggal sama) yang dilewati,
        // supaya file yang sama tidak masuk dua kali.
        $ada = [];
        if (! $ganti) {
            foreach ($this->rekapModel->select('kode_toko, tanggal')->where('kategori', $bagian)->where('perusahaan', $perusahaan)->findAll() as $row) {
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
        log_activity('create', 'rekap_data_toko', null, 'Import Excel ' . $masuk . ' data rekap toko ' . $namaBagian . ' (' . $perusahaan . ')'
            . ($ganti ? ', mengganti ' . $dihapus . ' data lama' : ''));

        $pesan = $masuk . ' data ' . $namaBagian . ' ' . $perusahaan . ' berhasil diimport';
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
    // Kolom mengikuti bagian (tab); kolom di luar bagian hanya ikut bila ada isinya (data lama / import).
    // Link -> hyperlink yang bisa diklik, foto hasil upload -> gambar tertanam, dokumen upload -> link unduh.
    public function exportExcel()
    {
        $ids = array_values(array_filter(array_map('intval', explode(',', (string) $this->request->getPost('ids')))));
        $bagian = (string) $this->request->getPost('kategori');
        if (! $ids || ! isset(RekapDataTokoModel::BAGIAN[$bagian])) {
            return redirect()->to(site_url('RekapDataMidi'))->with('error', 'Tidak ada data yang bisa diexport.');
        }
        $judulFile = 'Rekap Data Toko - ' . RekapDataTokoModel::BAGIAN[$bagian]['nama'];

        $byId = [];
        foreach (array_chunk($ids, 500) as $chunk) {
            foreach ($this->rekapModel->whereIn('id', $chunk)->findAll() as $row) {
                $byId[$row['id']] = $row;
            }
        }
        $rows = array_values(array_filter(array_map(static fn ($id) => $byId[$id] ?? null, $ids)));

        // judul kolom => [nama kolom, lebar]
        $kolom = ['No' => [null, 6], 'Tanggal' => ['tanggal', 12], 'Nama Pengisi' => ['nama_pengisi', 20],
            'Perusahaan' => ['perusahaan', 28], 'Toko' => ['toko', 9], 'Branch' => ['branch', 14],
            'Kode Toko' => ['kode_toko', 12], 'Nama Toko' => ['nama_toko', 26]];
        foreach (RekapDataTokoModel::kolomIsian($bagian) as $k) {
            $kolom[RekapDataTokoModel::ISIAN[$k][3]] = [$k, $k === 'sharelock_tikor_alamat' ? 40 : 22];
        }
        foreach (RekapDataTokoModel::kolomLain($bagian) as $k) {
            if (array_filter(array_column($rows, $k), static fn ($v) => trim((string) $v) !== '')) {
                $kolom[RekapDataTokoModel::ISIAN[$k][3] . ' (data lama)'] = [$k, 22];
            }
        }
        $docFields = RekapDataTokoModel::kolomDok();
        $imgExt    = ['jpg', 'jpeg', 'png', 'gif'];

        $book  = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $book->getActiveSheet();
        $sheet->setTitle(RekapDataTokoModel::BAGIAN[$bagian]['nama']);
        $C = static fn (int $i) => \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
        $last = $C(count($kolom));

        // Judul + header (seperti export Excel sebelumnya)
        $sheet->setCellValue('A1', $judulFile);
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
            foreach ($kolom as [$field]) {
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
        foreach (array_values($kolom) as $i => [, $w]) {
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
            ->setHeader('Content-Disposition', 'attachment; filename="' . $judulFile . '.xlsx"')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($isi);
    }

    private function importError(string $message)
    {
        return $this->response->setStatusCode(422)
            ->setJSON(['status' => 'error', 'message' => $message]);
    }

    private function handleUpload($file, string $prefix, ?string $oldFile = null): ?string
    {
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $this->deleteFileIfExists($oldFile);

            // Buat folder otomatis jika belum ada
            if (!is_dir(FCPATH . $this->uploadPath)) {
                mkdir(FCPATH . $this->uploadPath, 0775, true);
            }

            $newName = time() . '_' . preg_replace('/[^A-Za-z0-9\-]/', '', $prefix) . '.' . strtolower($file->getExtension());
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
