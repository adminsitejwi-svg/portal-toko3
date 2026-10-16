<?php

namespace App\Controllers;

use App\Models\BtsModel;
use App\Models\DevicesBackboneModel;
use App\Models\PelangganNocModel;

// Halaman Data Pelanggan (menu All NOC > Data Pelanggan di sidebar)
// Beda dengan controller Pelanggan (menu Pelanggan lama, tabel md_pelanggan)
class PelangganNoc extends BaseController
{
    protected $plgModel;

    // Lokasi file topologi (di luar public, diakses lewat PelangganNoc/file/{id})
    private const UPLOAD_DIR = WRITEPATH . 'uploads/topologi/';

    // Ekstensi file topologi yang boleh diupload => mime untuk ditampilkan
    private const MIME = [
        'pdf'  => 'application/pdf',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'doc'  => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    ];

    public function __construct()
    {
        $this->plgModel = new PelangganNocModel();
    }

    // Halaman utama (view di folder PelangganNoc/index.php)
    public function index()
    {
        return view('PelangganNoc/index', [
            'pelanggan' => $this->plgModel->withRelasi(),
            // Pilihan filter BTS
            'bts'       => (new BtsModel())->withVendors(),
        ]);
    }

    // Pilihan dropdown BTS & Devices Backbone (isian otomatis di form tambah/edit)
    private function formData(): array
    {
        return [
            'bts'     => (new BtsModel())->withVendors(),
            'devices' => (new DevicesBackboneModel())->withDataCenter(),
        ];
    }

    // Halaman form tambah (view PelangganNoc/FormPelangganNoc.php)
    public function create()
    {
        return view('PelangganNoc/FormPelangganNoc', $this->formData());
    }

    // Halaman form edit (view PelangganNoc/EditFormPelangganNoc.php)
    public function edit($id)
    {
        $row = $this->plgModel->find($id);

        if (! $row) {
            return redirect()->to(site_url('PelangganNoc'))->with('error', 'Data Pelanggan tidak ditemukan.');
        }

        return view('PelangganNoc/EditFormPelangganNoc', $this->formData() + ['row' => $row]);
    }

    private function rules(): array
    {
        return [
            'nama_pelanggan'      => 'required|max_length[255]',
            'bts_id'              => 'required|is_not_unique[d_bts.id]',
            'detail_perangkat'    => 'permit_empty',
            'devices_backbone_id' => 'permit_empty|is_not_unique[d_devices_backbone.id]',
            'file_topologi'       => 'uploaded[file_topologi]|max_size[file_topologi,10240]'
                . '|ext_in[file_topologi,' . implode(',', array_keys(self::MIME)) . ']',
        ];
    }

    private function messages(): array
    {
        return [
            'bts_id' => [
                'required'      => 'BTS wajib dipilih.',
                'is_not_unique' => 'BTS tidak ditemukan.',
            ],
            'devices_backbone_id' => ['is_not_unique' => 'Devices Backbone tidak ditemukan.'],
            'file_topologi'       => [
                'max_size' => 'File Topologi maksimal 10 MB.',
                'ext_in'   => 'File Topologi harus PDF, PNG, JPG, atau Word (DOC/DOCX).',
                'uploaded' => 'File Topologi gagal diupload.',
            ],
        ];
    }

    private function collectFields(): array
    {
        $post = fn (string $k) => trim((string) $this->request->getPost($k));

        return [
            'nama_pelanggan'      => $post('nama_pelanggan'),
            'bts_id'              => $post('bts_id'),
            'detail_perangkat'    => $post('detail_perangkat') ?: null,
            'devices_backbone_id' => $post('devices_backbone_id') ?: null,
        ];
    }

    // Validasi field + file (file hanya divalidasi jika ada yang dipilih)
    private function validasi(): ?string
    {
        $rules = $this->rules();
        $file  = $this->request->getFile('file_topologi');

        if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            unset($rules['file_topologi']);
        }

        // validate() (bukan validateData) agar rule file (uploaded/max_size/ext_in) membaca file dari request
        if (! $this->validate($rules, $this->messages())) {
            return implode(' ', $this->validator->getErrors());
        }

        return null;
    }

    // Pindahkan file topologi baru ke folder upload; kembalikan [nama_simpan, nama_asli] atau null
    private function simpanFile(): ?array
    {
        $file = $this->request->getFile('file_topologi');

        if (! $file || ! $file->isValid() || $file->hasMoved()) {
            return null;
        }

        if (! is_dir(self::UPLOAD_DIR)) {
            mkdir(self::UPLOAD_DIR, 0755, true);
        }

        $ext  = strtolower($file->getClientExtension());
        $nama = $file->getRandomName();
        $nama = pathinfo($nama, PATHINFO_FILENAME) . '.' . $ext;
        $file->move(self::UPLOAD_DIR, $nama);

        return [$nama, $file->getClientName()];
    }

    private function hapusFile(?string $nama): void
    {
        if ($nama && is_file(self::UPLOAD_DIR . basename($nama))) {
            unlink(self::UPLOAD_DIR . basename($nama));
        }
    }

    // Simpan data baru (POST PelangganNoc/save)
    public function save()
    {
        $fields = $this->collectFields();

        if ($error = $this->validasi()) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        if ($file = $this->simpanFile()) {
            [$fields['file_topologi'], $fields['file_topologi_asli']] = $file;
        }

        $this->plgModel->insert($fields);

        return redirect()->to(site_url('PelangganNoc'))
            ->with('success', 'Data Pelanggan berhasil ditambahkan.');
    }

    // Update data (POST PelangganNoc/update); file lama diganti hanya jika upload file baru
    public function update()
    {
        $id  = $this->request->getPost('id');
        $old = $this->plgModel->find($id);

        if (! $old) {
            return redirect()->to(site_url('PelangganNoc'))->with('error', 'Data Pelanggan tidak ditemukan.');
        }

        $fields = $this->collectFields();

        if ($error = $this->validasi()) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        if ($file = $this->simpanFile()) {
            [$fields['file_topologi'], $fields['file_topologi_asli']] = $file;
            $this->hapusFile($old['file_topologi']);
        } elseif ($this->request->getPost('hapus_topologi')) {
            $fields['file_topologi']      = null;
            $fields['file_topologi_asli'] = null;
            $this->hapusFile($old['file_topologi']);
        }

        $this->plgModel->update($id, $fields);

        return redirect()->to(site_url('PelangganNoc'))
            ->with('success', 'Data Pelanggan berhasil diperbarui.');
    }

    // Hapus data (file topologinya ikut dihapus)
    public function delete($id)
    {
        $row = $this->plgModel->find($id);

        if ($row) {
            $this->plgModel->delete($id);
            $this->hapusFile($row['file_topologi']);
        }

        return redirect()->to(site_url('PelangganNoc'))
            ->with('success', 'Data Pelanggan berhasil dihapus.');
    }

    // Buka file topologi (GET PelangganNoc/file/{id}): PDF & gambar tampil di browser, Word diunduh
    public function file($id)
    {
        $row  = $this->plgModel->find($id);
        $path = $row && $row['file_topologi'] ? self::UPLOAD_DIR . basename($row['file_topologi']) : null;

        if (! $path || ! is_file($path)) {
            return $this->response->setStatusCode(404)->setBody('File tidak ditemukan.');
        }

        $ext         = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $disposition = in_array($ext, ['pdf', 'png', 'jpg', 'jpeg'], true) ? 'inline' : 'attachment';
        $namaUnduh   = str_replace(['"', "\r", "\n"], '', $row['file_topologi_asli'] ?: basename($path));

        return $this->response
            ->setHeader('Content-Type', self::MIME[$ext] ?? 'application/octet-stream')
            ->setHeader('Content-Disposition', $disposition . '; filename="' . $namaUnduh . '"')
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setHeader('Cache-Control', 'no-store, no-cache')
            ->setBody(file_get_contents($path));
    }
}
