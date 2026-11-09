<?php

namespace App\Controllers;

use App\Models\NetMonModel;

// Halaman NetMon (menu All NOC > NetMon): kartu link website monitoring.
// Tambah / edit lewat pop up di halaman yang sama; tombol "Buka Link" membuka link di tab baru.
class NetMon extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new NetMonModel();
    }

    // Halaman utama (view NetMon/index.php)
    public function index()
    {
        return view('NetMon/index', [
            'netmon' => $this->model->orderBy('judul', 'ASC')->findAll(),
        ]);
    }

    private function rules(): array
    {
        return [
            'judul'      => 'required|max_length[150]',
            'link'       => 'required|max_length[500]',
            'keterangan' => 'permit_empty',
        ];
    }

    private function collectFields(): array
    {
        $post = fn (string $k) => trim((string) $this->request->getPost($k));

        $link = $post('link');
        // alamat tanpa http(s):// dianggap https, mis. "monitor.jwi.id" -> "https://monitor.jwi.id"
        if ($link !== '' && ! preg_match('#^[a-z][a-z0-9+.-]*://#i', $link)) {
            $link = 'https://' . $link;
        }

        return [
            'judul'      => $post('judul'),
            'link'       => $link,
            'keterangan' => $post('keterangan') ?: null,
        ];
    }

    // Link hanya boleh http/https (mencegah link "javascript:" dsb. di tombol)
    private function linkValid(string $link): bool
    {
        $skema = strtolower((string) parse_url($link, PHP_URL_SCHEME));

        return filter_var($link, FILTER_VALIDATE_URL) !== false && in_array($skema, ['http', 'https'], true);
    }

    // Validasi; kembalikan pesan error atau null
    private function validasi(array $fields): ?string
    {
        if (! $this->validateData($fields, $this->rules())) {
            return implode(' ', $this->validator->getErrors());
        }
        if (! $this->linkValid($fields['link'])) {
            return 'Link website tidak valid. Contoh: https://monitor.contoh.com';
        }

        return null;
    }

    // Gagal validasi: kembali ke halaman dan buka lagi pop up dengan isian sebelumnya
    private function gagal(string $pesan)
    {
        return redirect()->to(site_url('NetMon'))
            ->withInput()
            ->with('popup', 'netmon')
            ->with('error', $pesan);
    }

    // Simpan data baru (POST NetMon/save)
    public function save()
    {
        $fields = $this->collectFields();

        if ($error = $this->validasi($fields)) {
            return $this->gagal($error);
        }

        $this->model->insert($fields);

        return redirect()->to(site_url('NetMon'))
            ->with('success', 'NetMon berhasil ditambahkan.');
    }

    // Update data (POST NetMon/update)
    public function update()
    {
        $id = $this->request->getPost('id');

        if (! $id || ! $this->model->find($id)) {
            return redirect()->to(site_url('NetMon'))->with('error', 'Data NetMon tidak ditemukan.');
        }

        $fields = $this->collectFields();

        if ($error = $this->validasi($fields)) {
            return $this->gagal($error);
        }

        $this->model->update($id, $fields);

        return redirect()->to(site_url('NetMon'))
            ->with('success', 'NetMon berhasil diperbarui.');
    }

    // Hapus data
    public function delete($id)
    {
        $this->model->delete($id);

        return redirect()->to(site_url('NetMon'))
            ->with('success', 'NetMon berhasil dihapus.');
    }
}
