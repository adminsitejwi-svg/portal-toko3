<?php

namespace App\Controllers;

use App\Models\BtsModel;
use App\Models\BtsVendorModel;
use App\Models\PelangganNocModel;
use App\Models\PelangganNocPerangkatModel;
use App\Models\PelangganNocVendorModel;

// Halaman Data Pelanggan (menu All NOC > Data Pelanggan di sidebar)
// Beda dengan controller Pelanggan (menu Pelanggan lama, tabel md_pelanggan)
class PelangganNoc extends BaseController
{
    protected $plgModel;
    protected $plgVendorModel;
    protected $plgPerangkatModel;

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
        $this->plgModel          = new PelangganNocModel();
        $this->plgVendorModel    = new PelangganNocVendorModel();
        $this->plgPerangkatModel = new PelangganNocPerangkatModel();
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

    // Pilihan dropdown BTS beserta vendornya (pilihan vendor di form tambah/edit)
    private function formData(): array
    {
        return [
            'bts' => (new BtsModel())->withVendors(),
        ];
    }

    // Halaman form tambah (view PelangganNoc/FormPelangganNoc.php)
    public function create()
    {
        return view('PelangganNoc/FormPelangganNoc', $this->formData());
    }

    // Halaman form edit (view PelangganNoc/EditFormPelangganNoc.php), vendor & perangkat ikut diambil
    public function edit($id)
    {
        $row = $this->plgModel->withRelasi((int) $id)[0] ?? null;

        if (! $row) {
            return redirect()->to(site_url('PelangganNoc'))->with('error', 'Data Pelanggan tidak ditemukan.');
        }

        return view('PelangganNoc/EditFormPelangganNoc', $this->formData() + ['row' => $row]);
    }

    private function rules(): array
    {
        return [
            'nama_pelanggan' => 'required|max_length[255]',
            'bts_id'         => 'required|is_not_unique[d_bts.id]',
            'file_topologi'  => 'uploaded[file_topologi]|max_size[file_topologi,10240]'
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
            'file_topologi' => [
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
            'nama_pelanggan' => $post('nama_pelanggan'),
            'bts_id'         => $post('bts_id'),
        ];
    }

    // Baris isian berulang dari input name[n][kolom]; baris yang kolom $wajibSalahSatu-nya kosong semua dibuang
    private function collectBaris(string $name, array $kolom, array $wajibSalahSatu): array
    {
        $rows = [];

        foreach ((array) $this->request->getPost($name) as $input) {
            $input = (array) $input;
            $row   = [];
            foreach ($kolom as $k) {
                $row[$k] = trim((string) ($input[$k] ?? ''));
            }

            if (implode('', array_intersect_key($row, array_flip($wajibSalahSatu))) === '') {
                continue;
            }

            $row['tanpa_vlan'] = ! empty($input['tanpa_vlan']);
            $rows[]            = $row;
        }

        return $rows;
    }

    // Vendor pelanggan: vendor[n][vendor_id|ip_address|vlan_id|tanpa_vlan|kapasitas]
    private function collectVendors(): array
    {
        return array_map(fn ($v) => [
            'vendor_id'  => $v['vendor_id'],
            'ip_address' => $v['ip_address'],
            'vlan_id'    => $v['tanpa_vlan'] ? null : $v['vlan_id'],
            'kapasitas'  => $v['kapasitas'],
        ], $this->collectBaris('vendor', ['vendor_id', 'ip_address', 'vlan_id', 'kapasitas'], ['vendor_id', 'ip_address', 'vlan_id', 'kapasitas']));
    }

    // Detail perangkat: perangkat[n][merk|jenis|type|sn|jumlah] (jumlah kosong = 1)
    private function collectPerangkat(): array
    {
        return array_map(fn ($p) => [
            'merk'   => $p['merk'],
            'jenis'  => $p['jenis'],
            'type'   => $p['type'],
            'sn'     => $p['sn'],
            'jumlah' => $p['jumlah'] === '' ? '1' : $p['jumlah'],
        ], $this->collectBaris('perangkat', ['merk', 'jenis', 'type', 'sn', 'jumlah'], ['merk', 'jenis', 'type', 'sn']));
    }

    // Validasi field + file (file hanya divalidasi jika ada yang dipilih) + vendor & perangkat
    private function validasi(array $fields, array $vendors, array $perangkat): ?string
    {
        $errors = [];
        $rules  = $this->rules();
        $file   = $this->request->getFile('file_topologi');

        if (! $file || $file->getError() === UPLOAD_ERR_NO_FILE) {
            unset($rules['file_topologi']);
        }

        // validate() (bukan validateData) agar rule file (uploaded/max_size/ext_in) membaca file dari request
        if (! $this->validate($rules, $this->messages())) {
            $errors = array_values($this->validator->getErrors());
        }

        if (empty($vendors)) {
            $errors[] = 'Minimal 1 Vendor wajib diisi.';
        }

        // Vendor yang boleh dipilih = vendor milik BTS pelanggan
        $vendorBts = array_flip(array_column(
            (new BtsVendorModel())->select('vendor_id')->where('bts_id', (int) $fields['bts_id'])->findAll(),
            'vendor_id'
        ));

        foreach ($vendors as $i => $v) {
            $baris = 'Vendor ' . ($i + 1) . ': ';

            if (! isset($vendorBts[$v['vendor_id']])) {
                $errors[] = $baris . 'pilih vendor dari vendor BTS yang dipilih.';
            }
            if (! filter_var($v['ip_address'], FILTER_VALIDATE_IP)) {
                $errors[] = $baris . ($v['ip_address'] === '' ? 'IP Address wajib diisi.' : 'IP Address tidak valid.');
            }
            if ($v['vlan_id'] !== null && (! ctype_digit($v['vlan_id']) || (int) $v['vlan_id'] < 1 || (int) $v['vlan_id'] > 4094)) {
                $errors[] = $baris . ($v['vlan_id'] === '' ? 'Vlan ID wajib diisi, atau centang Tanpa VLAN.' : 'Vlan ID harus angka 1 - 4094.');
            }
            if ($v['kapasitas'] === '' || mb_strlen($v['kapasitas']) > 100) {
                $errors[] = $baris . 'Kapasitas wajib diisi (maks. 100 karakter).';
            }
        }

        foreach ($perangkat as $i => $p) {
            $baris = 'Perangkat ' . ($i + 1) . ': ';

            if (! ctype_digit($p['jumlah']) || (int) $p['jumlah'] < 1) {
                $errors[] = $baris . 'Jumlah harus angka minimal 1.';
            }
            foreach (['merk', 'jenis', 'type', 'sn'] as $k) {
                if (mb_strlen($p[$k]) > 255) {
                    $errors[] = $baris . 'Merk, Jenis, Type & SN maksimal 255 karakter.';
                    break;
                }
            }
        }

        return $errors ? implode(' ', $errors) : null;
    }

    // Ganti seluruh vendor & detail perangkat milik pelanggan dengan isian baru
    private function simpanRelasi(int $pelangganId, array $vendors, array $perangkat): void
    {
        $this->plgVendorModel->where('pelanggan_id', $pelangganId)->delete();
        $this->plgPerangkatModel->where('pelanggan_id', $pelangganId)->delete();

        foreach ($vendors as $v) {
            $this->plgVendorModel->insert([
                'pelanggan_id' => $pelangganId,
                'vendor_id'    => (int) $v['vendor_id'],
                'ip_address'   => $v['ip_address'],
                'vlan_id'      => $v['vlan_id'] === null ? null : (int) $v['vlan_id'],
                'kapasitas'    => $v['kapasitas'],
            ]);
        }
        foreach ($perangkat as $p) {
            $this->plgPerangkatModel->insert([
                'pelanggan_id' => $pelangganId,
                'merk'         => $p['merk'] ?: null,
                'jenis'        => $p['jenis'] ?: null,
                'type'         => $p['type'] ?: null,
                'sn'           => $p['sn'] ?: null,
                'jumlah'       => (int) $p['jumlah'],
            ]);
        }
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
        $fields    = $this->collectFields();
        $vendors   = $this->collectVendors();
        $perangkat = $this->collectPerangkat();

        if ($error = $this->validasi($fields, $vendors, $perangkat)) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        if ($file = $this->simpanFile()) {
            [$fields['file_topologi'], $fields['file_topologi_asli']] = $file;
        }

        $db = db_connect();
        $db->transStart();
        $id = $this->plgModel->insert($fields);
        $this->simpanRelasi((int) $id, $vendors, $perangkat);
        $db->transComplete();

        if (! $db->transStatus()) {
            $this->hapusFile($fields['file_topologi'] ?? null);

            return redirect()->back()->withInput()->with('error', 'Data Pelanggan gagal disimpan.');
        }

        return redirect()->to(site_url('PelangganNoc'))
            ->with('success', 'Data Pelanggan berhasil ditambahkan.');
    }

    // Update data (POST PelangganNoc/update); file lama diganti hanya jika upload file baru
    public function update()
    {
        $id  = (int) $this->request->getPost('id');
        $old = $this->plgModel->find($id);

        if (! $old) {
            return redirect()->to(site_url('PelangganNoc'))->with('error', 'Data Pelanggan tidak ditemukan.');
        }

        $fields    = $this->collectFields();
        $vendors   = $this->collectVendors();
        $perangkat = $this->collectPerangkat();

        if ($error = $this->validasi($fields, $vendors, $perangkat)) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $fileLamaDihapus = false;
        if ($file = $this->simpanFile()) {
            [$fields['file_topologi'], $fields['file_topologi_asli']] = $file;
            $fileLamaDihapus = true;
        } elseif ($this->request->getPost('hapus_topologi')) {
            $fields['file_topologi']      = null;
            $fields['file_topologi_asli'] = null;
            $fileLamaDihapus              = true;
        }

        $db = db_connect();
        $db->transStart();
        $this->plgModel->update($id, $fields);
        $this->simpanRelasi($id, $vendors, $perangkat);
        $db->transComplete();

        if (! $db->transStatus()) {
            $this->hapusFile($file[0] ?? null);

            return redirect()->back()->withInput()->with('error', 'Data Pelanggan gagal diperbarui.');
        }

        if ($fileLamaDihapus) {
            $this->hapusFile($old['file_topologi']);
        }

        return redirect()->to(site_url('PelangganNoc'))
            ->with('success', 'Data Pelanggan berhasil diperbarui.');
    }

    // Hapus data (vendor, detail perangkat & file topologinya ikut dihapus)
    public function delete($id)
    {
        $row = $this->plgModel->find($id);

        if ($row) {
            $db = db_connect();
            $db->transStart();
            $this->plgVendorModel->where('pelanggan_id', (int) $id)->delete();
            $this->plgPerangkatModel->where('pelanggan_id', (int) $id)->delete();
            $this->plgModel->delete($id);
            $db->transComplete();

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
