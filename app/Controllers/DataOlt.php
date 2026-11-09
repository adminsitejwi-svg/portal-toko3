<?php

namespace App\Controllers;

use App\Models\OltModel;

// Halaman Data OLT (menu Data Teknis NOC > Data OLT). Tambah / edit lewat pop up.
// Tidak boleh dobel: Nama OLT, IP OLT, dan SN.
class DataOlt extends BaseController
{
    public const TYPE = ['ZTE', 'HSGQ'];

    protected $model;

    public function __construct()
    {
        $this->model = new OltModel();
    }

    // Halaman utama (view DataOlt/index.php)
    public function index()
    {
        return view('DataOlt/index', [
            'olts' => $this->model->orderBy('nama_olt', 'ASC')->findAll(),
            'opsiType' => self::TYPE,
        ]);
    }

    private function collectFields(): array
    {
        $post = fn (string $k) => trim(preg_replace('/\s+/', ' ', (string) $this->request->getPost($k)));

        return [
            'nama_olt'       => $post('nama_olt'),
            'ip_olt'         => $post('ip_olt'),
            'type'              => $post('type'),
            'seri_olt'       => $post('seri_olt') ?: null,
            'sn'                => $post('sn'),
            'versi_olt'      => $post('versi_olt') ?: null,
            'lokasi_pemasangan' => $post('lokasi_pemasangan'),
        ];
    }

    // Validasi isian + cek data ganda; kembalikan pesan error atau null
    private function validasi(array $f, ?int $id = null): ?string
    {
        $rules = [
            'nama_olt'       => 'required|max_length[150]',
            'ip_olt'         => 'required|valid_ip',
            'type'              => 'required|in_list[' . implode(',', self::TYPE) . ']',
            'seri_olt'       => 'permit_empty|max_length[150]',
            'sn'                => 'required|max_length[150]',
            'versi_olt'      => 'permit_empty|max_length[100]',
            'lokasi_pemasangan' => 'required|max_length[255]',
        ];
        $pesan = [
            'nama_olt'       => ['required' => 'Nama OLT wajib diisi.'],
            'ip_olt'         => ['required' => 'IP OLT wajib diisi.', 'valid_ip' => 'IP OLT tidak valid.'],
            'type'              => ['required' => 'Type wajib dipilih.', 'in_list' => 'Type harus ZTE atau HSGQ.'],
            'sn'                => ['required' => 'SN wajib diisi.'],
            'lokasi_pemasangan' => ['required' => 'Lokasi Pemasangan wajib diisi.'],
        ];

        if (! $this->validateData($f, $rules, $pesan)) {
            return implode(' ', $this->validator->getErrors());
        }

        // tidak boleh sama dengan OLT lain (collation utf8mb4_unicode_ci: huruf besar/kecil dianggap sama)
        $errors = [];
        foreach (['nama_olt' => 'Nama OLT', 'ip_olt' => 'IP OLT', 'sn' => 'SN'] as $kolom => $label) {
            $q = $this->model->where($kolom, $f[$kolom]);
            if ($id) {
                $q->where('id !=', $id);
            }
            if ($lain = $q->first()) {
                $errors[] = $label . ' "' . $f[$kolom] . '" sudah dipakai OLT "' . $lain['nama_olt'] . '".';
            }
        }

        return $errors ? implode(' ', $errors) : null;
    }

    // Gagal: kembali ke halaman dan buka lagi pop up dengan isian sebelumnya
    private function gagal(string $pesan)
    {
        return redirect()->to(site_url('DataOlt'))
            ->withInput()
            ->with('popup', 'olt')
            ->with('error', $pesan);
    }

    // Simpan data baru (POST DataOlt/save)
    public function save()
    {
        $f = $this->collectFields();

        if ($error = $this->validasi($f)) {
            return $this->gagal($error);
        }

        // cadangan bila dua orang menyimpan data yang sama bersamaan (UNIQUE di database):
        // tergantung setting, query yang ditolak database melempar error atau mengembalikan false
        try {
            $ok = $this->model->insert($f) !== false;
        } catch (\Throwable $e) {
            $ok = false;
        }
        if (! $ok) {
            return $this->gagal('Data OLT gagal disimpan, kemungkinan Nama OLT / IP / SN sudah ada.');
        }

        return redirect()->to(site_url('DataOlt'))->with('success', 'Data OLT berhasil ditambahkan.');
    }

    // Update data (POST DataOlt/update)
    public function update()
    {
        $id = (int) $this->request->getPost('id');

        if (! $id || ! $this->model->find($id)) {
            return redirect()->to(site_url('DataOlt'))->with('error', 'Data OLT tidak ditemukan.');
        }

        $f = $this->collectFields();

        if ($error = $this->validasi($f, $id)) {
            return $this->gagal($error);
        }

        try {
            $ok = $this->model->update($id, $f) !== false;
        } catch (\Throwable $e) {
            $ok = false;
        }
        if (! $ok) {
            return $this->gagal('Data OLT gagal diperbarui, kemungkinan Nama OLT / IP / SN sudah ada.');
        }

        return redirect()->to(site_url('DataOlt'))->with('success', 'Data OLT berhasil diperbarui.');
    }

    // Hapus data
    public function delete($id)
    {
        $this->model->delete($id);

        return redirect()->to(site_url('DataOlt'))->with('success', 'Data OLT berhasil dihapus.');
    }
}
