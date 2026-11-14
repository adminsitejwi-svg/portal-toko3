<?php

namespace App\Controllers;

use App\Models\RFOModel;
use App\Models\SuratTugasModel;

/**
 * Surat Tugas (menu Activation): tampilan & fitur seperti RFO (daftar + kartu statistik,
 * form tambah/edit, View siap Print), tanpa kirim email.
 * Struktur surat dari Downloads/17 SPK NCIX KARET_OCT.pdf.
 */
class SuratTugas extends BaseController
{
    protected SuratTugasModel $model;

    private const TIPE_TTD = ['png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'webp' => 'image/webp'];

    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->model = new SuratTugasModel();
    }

    // Halaman utama: app/Views/SuratTugas/index.php
    public function index()
    {
        return view('SuratTugas/index', [
            'surat'      => $this->model->orderBy('id', 'DESC')->findAll(),
            'perusahaan' => array_keys(RFOModel::PERUSAHAAN),
        ]);
    }

    // Form tambah: app/Views/SuratTugas/Form.php
    public function create()
    {
        return view('SuratTugas/Form', [
            'st'         => null,
            'nomor'      => SuratTugasModel::nomorSurat(),
            'kodeNomor'  => SuratTugasModel::KODE_NOMOR,
            'perusahaan' => RFOModel::PERUSAHAAN,
            'penanda'    => $this->model->penandaTangan(),
        ]);
    }

    // Form edit (tampilan & fitur sama dengan form tambah)
    public function edit($id)
    {
        $st = $this->model->find($id);

        if (! $st) {
            return redirect()->to(site_url('SuratTugas'))->with('error', 'Data Surat Tugas tidak ditemukan.');
        }

        return view('SuratTugas/Form', [
            'st'         => $st,
            'nomor'      => $st['nomor'],
            'kodeNomor'  => SuratTugasModel::KODE_NOMOR,
            'perusahaan' => RFOModel::PERUSAHAAN,
            'penanda'    => $this->model->penandaTangan(),
        ]);
    }

    // Halaman lihat: surat siap cetak (app/Views/SuratTugas/View.php)
    public function view($id)
    {
        $st = $this->model->find($id);

        if (! $st) {
            return redirect()->to(site_url('SuratTugas'))->with('error', 'Data Surat Tugas tidak ditemukan.');
        }

        return view('SuratTugas/View', [
            'st'        => $st,
            'namaPt'    => RFOModel::NAMA_TTD[$st['perusahaan']] ?? $st['perusahaan'],
            'alamat'    => SuratTugasModel::ALAMAT[$st['perusahaan']] ?? SuratTugasModel::ALAMAT['PT JURAGAN WIFI INDONESIA'],
            'teknisi'   => SuratTugasModel::teknisi($st),
            'perangkat' => SuratTugasModel::perangkat($st),
            'adaTtd'    => ! empty($st['ttd']) && is_file(SuratTugasModel::folderTtd() . $st['ttd']),
        ]);
    }

    // Gambar tanda tangan/stempel (GET SuratTugas/ttd/{id})
    public function ttd($id)
    {
        $st   = $this->model->find($id);
        $file = $st['ttd'] ?? '';

        if ($file === '' || ! is_file(SuratTugasModel::folderTtd() . $file)) {
            return $this->response->setStatusCode(404)->setBody('Tanda tangan tidak ditemukan.');
        }

        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

        return $this->response
            ->setContentType(self::TIPE_TTD[$ext] ?? 'application/octet-stream')
            ->setHeader('Cache-Control', 'private, max-age=86400')
            ->setBody((string) file_get_contents(SuratTugasModel::folderTtd() . $file));
    }

    // Aturan validasi bersama untuk save & update
    private function rules(?int $id = null): array
    {
        return [
            // Nomor = {tanggal}/{kode}.{bulan romawi}/{tahun}; boleh sama bila ada beberapa surat di hari yang sama
            'nomor'           => 'required|max_length[50]',
            'perusahaan'      => 'required|in_list[' . implode(',', array_keys(RFOModel::PERUSAHAAN)) . ']',
            'tanggal_surat'   => 'required|valid_date[Y-m-d]',
            'mitra'           => 'permit_empty|max_length[150]',
            'kegiatan'        => 'required|max_length[255]',
            'tanggal_mulai'   => 'required|valid_date[Y-m-d]',
            'tanggal_selesai' => 'required|valid_date[Y-m-d]',
            'keperluan'       => 'required|max_length[255]',
            'lokasi'          => 'required|max_length[255]',
            'penutup'         => 'permit_empty|max_length[255]',
            'ttd_nama'        => 'required|max_length[100]',
            'ttd_jabatan'     => 'required|max_length[100]',
            'ttd_file'        => 'permit_empty|is_image[ttd_file]|ext_in[ttd_file,png,jpg,jpeg,webp]|max_size[ttd_file,2048]',
        ];
    }

    private function pesanValidasi(): array
    {
        return [
            'ttd_file' => [
                'is_image' => 'File tanda tangan harus berupa gambar.',
                'ext_in'   => 'File tanda tangan harus PNG, JPG atau WEBP.',
                'max_size' => 'File tanda tangan maksimal 2 MB.',
            ],
        ];
    }

    // Ambil field dari request
    private function collectFields(): array
    {
        $post = fn (string $k) => trim((string) $this->request->getPost($k));

        $teknisi = array_values(array_filter(
            array_map(static fn ($n) => trim((string) $n), (array) $this->request->getPost('teknisi')),
            static fn ($n) => $n !== ''
        ));

        $merk      = (array) $this->request->getPost('perangkat_merk');
        $jenis     = (array) $this->request->getPost('perangkat_jenis');
        $sn        = (array) $this->request->getPost('perangkat_sn');
        $perangkat = [];
        foreach ($merk as $i => $m) {
            $baris = [
                'merk'  => trim((string) $m),
                'jenis' => trim((string) ($jenis[$i] ?? '')),
                'sn'    => trim((string) ($sn[$i] ?? '')),
            ];
            if ($baris['merk'] !== '' || $baris['jenis'] !== '' || $baris['sn'] !== '') {
                $perangkat[] = $baris;
            }
        }

        $perusahaan = $post('perusahaan');

        return [
            'nomor'           => $post('nomor'),
            'perusahaan'      => $perusahaan,
            // Logo selalu mengikuti logo bawaan perusahaan yang dipilih
            'logo'            => RFOModel::PERUSAHAAN[$perusahaan] ?? null,
            'tanggal_surat'   => $post('tanggal_surat'),
            'mitra'           => $post('mitra') ?: null,
            'kegiatan'        => $post('kegiatan'),
            'tanggal_mulai'   => $post('tanggal_mulai'),
            'tanggal_selesai' => $post('tanggal_selesai'),
            'keperluan'       => $post('keperluan'),
            'teknisi'         => json_encode($teknisi, JSON_UNESCAPED_UNICODE),
            'lokasi'          => $post('lokasi'),
            'perangkat'       => json_encode($perangkat, JSON_UNESCAPED_UNICODE),
            'penutup'         => $post('penutup') ?: null,
            'ttd_nama'        => $post('ttd_nama'),
            'ttd_jabatan'     => $post('ttd_jabatan'),
        ];
    }

    // Cek tambahan di luar aturan validasi; null = aman
    private function cekLain(array $f): ?string
    {
        if (json_decode($f['teknisi'], true) === []) {
            return 'Isi minimal satu nama teknisi.';
        }
        if ($f['tanggal_selesai'] < $f['tanggal_mulai']) {
            return 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.';
        }

        return null;
    }

    // Simpan file tanda tangan baru (bila diunggah); hasil = nama file atau null
    private function simpanTtd(): ?string
    {
        $file = $this->request->getFile('ttd_file');

        if (! $file || ! $file->isValid() || $file->hasMoved()) {
            return null;
        }

        if (! is_dir(SuratTugasModel::folderTtd())) {
            mkdir(SuratTugasModel::folderTtd(), 0775, true);
        }
        $nama = 'ttd_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($file->getExtension());
        $file->move(SuratTugasModel::folderTtd(), $nama);

        return $nama;
    }

    private function hapusFileTtd(?string $nama): void
    {
        if ($nama && is_file(SuratTugasModel::folderTtd() . basename($nama))) {
            @unlink(SuratTugasModel::folderTtd() . basename($nama));
        }
    }

    // Simpan data baru (POST SuratTugas/save)
    public function save()
    {
        if (! $this->validate($this->rules(), $this->pesanValidasi())) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $fields = $this->collectFields();
        if ($pesan = $this->cekLain($fields)) {
            return redirect()->back()->withInput()->with('error', $pesan);
        }
        // Nomor otomatis (tidak bisa diisi manual); bila tidak sesuai format, susun ulang dari tanggal hari ini
        if (! SuratTugasModel::nomorValid($fields['nomor'], $fields['perusahaan'])) {
            $fields['nomor'] = SuratTugasModel::nomorSurat($fields['perusahaan']);
        }

        $fields['ttd']         = $this->simpanTtd();
        $fields['dibuat_oleh'] = session()->get('username');
        $this->model->insert($fields);

        return redirect()->to(site_url('SuratTugas'))
            ->with('success', 'Surat Tugas ' . esc($fields['nomor']) . ' berhasil ditambahkan.');
    }

    // Update data (POST SuratTugas/update)
    public function update()
    {
        $id  = (int) $this->request->getPost('id');
        $old = $this->model->find($id);

        if (! $old) {
            return redirect()->to(site_url('SuratTugas'))->with('error', 'Data Surat Tugas tidak ditemukan.');
        }

        if (! $this->validate($this->rules($id), $this->pesanValidasi())) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $fields = $this->collectFields();
        if ($pesan = $this->cekLain($fields)) {
            return redirect()->back()->withInput()->with('error', $pesan);
        }

        // Nomor tidak bisa diubah manual: tetap nomor lama, hanya kode ikut perusahaan
        $fields['nomor'] = SuratTugasModel::gantiKodeNomor($old['nomor'], $fields['perusahaan']);

        // Tanda tangan: ganti bila ada file baru, hapus bila dicentang
        $baru = $this->simpanTtd();
        if ($baru !== null) {
            $fields['ttd'] = $baru;
            $this->hapusFileTtd($old['ttd']);
        } elseif ($this->request->getPost('hapus_ttd')) {
            $fields['ttd'] = null;
            $this->hapusFileTtd($old['ttd']);
        }

        $this->model->update($id, $fields);

        return redirect()->to(site_url('SuratTugas'))
            ->with('success', 'Surat Tugas ' . esc($fields['nomor']) . ' berhasil diperbarui.');
    }

    // Hapus data (beserta file tanda tangan)
    public function delete($id)
    {
        $st = $this->model->find($id);

        if ($st) {
            $this->model->delete($id);
            $this->hapusFileTtd($st['ttd']);
        }

        return redirect()->to(site_url('SuratTugas'))->with('success', 'Data Surat Tugas berhasil dihapus.');
    }
}
