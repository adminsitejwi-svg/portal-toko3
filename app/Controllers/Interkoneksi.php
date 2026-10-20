<?php

namespace App\Controllers;

use App\Models\DataCenterModel;
use App\Models\InterkoneksiModel;

// Halaman Data Interkoneksi (menu All NOC > Data Interkoneksi di sidebar)
class Interkoneksi extends BaseController
{
    protected $ikModel;

    // Lokasi file dokumen (di luar public, diakses lewat Interkoneksi/file/{id})
    private const UPLOAD_DIR = WRITEPATH . 'uploads/interkoneksi/';

    // Ekstensi file dokumen yang boleh diupload => mime untuk ditampilkan
    private const MIME = [
        'pdf'  => 'application/pdf',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
    ];

    public function __construct()
    {
        $this->ikModel = new InterkoneksiModel();
    }

    // Halaman utama (view di folder Interkoneksi/index.php)
    public function index()
    {
        return view('Interkoneksi/index', [
            'interkoneksi' => $this->ikModel->withDataCenter(),
        ]);
    }

    // Pilihan dropdown Sumber di form tambah/edit
    public const SUMBER = [
        'PT. Juragan Wifi Indonesia',
        'PT. Genezis Mitra Technology',
        'PT. Norlec Technologi Indonesia',
    ];

    // Pilihan dropdown Status di form tambah/edit
    public const STATUS = ['Active', 'Terminate'];

    // Pilihan dropdown Data Center, Sumber & Status di form tambah/edit
    private function formData(): array
    {
        return [
            'dataCenter' => (new DataCenterModel())->orderBy('nama_dc', 'ASC')->findAll(),
            'opsiSumber' => self::SUMBER,
            'opsiStatus' => self::STATUS,
        ];
    }

    // Halaman form tambah (view Interkoneksi/FormInterkoneksi.php)
    public function create()
    {
        return view('Interkoneksi/FormInterkoneksi', $this->formData());
    }

    // Halaman form edit (view Interkoneksi/EditFormInterkoneksi.php)
    public function edit($id)
    {
        $row = $this->ikModel->find($id);

        if (! $row) {
            return redirect()->to(site_url('Interkoneksi'))->with('error', 'Data Interkoneksi tidak ditemukan.');
        }

        return view('Interkoneksi/EditFormInterkoneksi', $this->formData() + ['row' => $row]);
    }

    // $id diisi saat update agar kode milik data itu sendiri tidak dianggap duplikat
    private function rules($id = null): array
    {
        return [
            'kode_interkoneksi' => 'required|max_length[100]|is_unique[d_interkoneksi.kode_interkoneksi,id,' . (int) $id . ']',
            'customer'          => 'required|max_length[255]',
            'data_center_id'    => 'required|is_not_unique[md_data_center.id]',
            'sumber'            => 'required|in_list[' . implode(',', self::SUMBER) . ']',
            'tujuan'            => 'required|max_length[255]',
            'label_kabel'       => 'permit_empty|max_length[255]',
            'status'            => 'permit_empty|in_list[' . implode(',', self::STATUS) . ']',
            'note'              => 'permit_empty',
        ];
    }

    private function messages(): array
    {
        return [
            'kode_interkoneksi' => ['is_unique' => 'Kode Interkoneksi sudah dipakai.'],
            'data_center_id'    => [
                'required'      => 'Data Center wajib dipilih.',
                'is_not_unique' => 'Data Center tidak ditemukan.',
            ],
        ];
    }

    private function collectFields(): array
    {
        $post = fn (string $k) => trim((string) $this->request->getPost($k));

        return [
            'kode_interkoneksi' => $post('kode_interkoneksi'),
            'customer'          => $post('customer'),
            'data_center_id'    => $post('data_center_id'),
            'sumber'           => $post('sumber'),
            'tujuan'            => $post('tujuan'),
            'label_kabel'       => $post('label_kabel') ?: null,
            'status'            => $post('status') ?: null,
            'note'              => $post('note') ?: null,
        ];
    }

    // Validasi field lalu file dokumen (file hanya divalidasi jika ada yang dipilih); kembalikan pesan error atau null
    private function validasi(array $fields, $id = null): ?string
    {
        if (! $this->validateData($fields, $this->rules($id), $this->messages())) {
            return implode(' ', $this->validator->getErrors());
        }

        $file = $this->request->getFile('file_dokumen');
        if ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
            // validate() (bukan validateData) agar rule file membaca file dari request
            $ok = $this->validate([
                'file_dokumen' => 'uploaded[file_dokumen]|max_size[file_dokumen,10240]'
                    . '|ext_in[file_dokumen,' . implode(',', array_keys(self::MIME)) . ']',
            ], [
                'file_dokumen' => [
                    'max_size' => 'File Dokumen maksimal 10 MB.',
                    'ext_in'   => 'File Dokumen harus PDF, PNG, atau JPG.',
                    'uploaded' => 'File Dokumen gagal diupload.',
                ],
            ]);
            if (! $ok) {
                return implode(' ', $this->validator->getErrors());
            }
        }

        return null;
    }

    // Pindahkan file dokumen baru ke folder upload; kembalikan [nama_simpan, nama_asli] atau null
    private function simpanFile(): ?array
    {
        $file = $this->request->getFile('file_dokumen');

        if (! $file || ! $file->isValid() || $file->hasMoved()) {
            return null;
        }

        if (! is_dir(self::UPLOAD_DIR)) {
            mkdir(self::UPLOAD_DIR, 0755, true);
        }

        $ext  = strtolower($file->getClientExtension());
        $nama = pathinfo($file->getRandomName(), PATHINFO_FILENAME) . '.' . $ext;
        $file->move(self::UPLOAD_DIR, $nama);

        return [$nama, $file->getClientName()];
    }

    private function hapusFile(?string $nama): void
    {
        if ($nama && is_file(self::UPLOAD_DIR . basename($nama))) {
            unlink(self::UPLOAD_DIR . basename($nama));
        }
    }

    // Simpan data baru (POST Interkoneksi/save)
    public function save()
    {
        $fields = $this->collectFields();

        if ($error = $this->validasi($fields)) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        if ($file = $this->simpanFile()) {
            [$fields['file_dokumen'], $fields['file_dokumen_asli']] = $file;
        }

        $this->ikModel->insert($fields);

        return redirect()->to(site_url('Interkoneksi'))
            ->with('success', 'Data Interkoneksi berhasil ditambahkan.');
    }

    // Update data (POST Interkoneksi/update); file lama diganti hanya jika upload file baru
    public function update()
    {
        $id = $this->request->getPost('id');
        $old = $this->ikModel->find($id);

        if (! $old) {
            return redirect()->to(site_url('Interkoneksi'))->with('error', 'Data Interkoneksi tidak ditemukan.');
        }

        $fields = $this->collectFields();

        if ($error = $this->validasi($fields, $id)) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $fileLamaDihapus = false;
        if ($file = $this->simpanFile()) {
            [$fields['file_dokumen'], $fields['file_dokumen_asli']] = $file;
            $fileLamaDihapus = true;
        } elseif ($this->request->getPost('hapus_dokumen')) {
            $fields['file_dokumen']      = null;
            $fields['file_dokumen_asli'] = null;
            $fileLamaDihapus             = true;
        }

        $this->ikModel->update($id, $fields);

        if ($fileLamaDihapus) {
            $this->hapusFile($old['file_dokumen']);
        }

        return redirect()->to(site_url('Interkoneksi'))
            ->with('success', 'Data Interkoneksi berhasil diperbarui.');
    }

    // Hapus data (file dokumennya ikut dihapus)
    public function delete($id)
    {
        $row = $this->ikModel->find($id);

        if ($row) {
            $this->ikModel->delete($id);
            $this->hapusFile($row['file_dokumen']);
        }

        return redirect()->to(site_url('Interkoneksi'))
            ->with('success', 'Data Interkoneksi berhasil dihapus.');
    }

    // Buka file dokumen (GET Interkoneksi/file/{id}): PDF & gambar tampil di browser
    public function file($id)
    {
        $row  = $this->ikModel->find($id);
        $path = $row && $row['file_dokumen'] ? self::UPLOAD_DIR . basename($row['file_dokumen']) : null;

        if (! $path || ! is_file($path)) {
            return $this->response->setStatusCode(404)->setBody('File tidak ditemukan.');
        }

        $ext       = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $namaUnduh = str_replace(['"', "\r", "\n"], '', $row['file_dokumen_asli'] ?: basename($path));

        return $this->response
            ->setHeader('Content-Type', self::MIME[$ext] ?? 'application/octet-stream')
            ->setHeader('Content-Disposition', (isset(self::MIME[$ext]) ? 'inline' : 'attachment') . '; filename="' . $namaUnduh . '"')
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setHeader('Cache-Control', 'no-store, no-cache')
            ->setBody(file_get_contents($path));
    }
}
