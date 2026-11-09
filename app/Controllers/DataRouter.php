<?php

namespace App\Controllers;

use App\Models\RouterModel;

// Halaman Data Router (menu Data Teknis NOC > Data Router). Tambah / edit lewat pop up.
// Tidak boleh dobel: Nama Router, SN, dan IP (Mainlink / Backup / Loopback) milik router lain.
class DataRouter extends BaseController
{
    public const TYPE = ['MikroTik', 'Juniper', 'Cisco', 'Huawei'];

    // kolom IP beserta labelnya (dipakai validasi & pesan)
    private const IP = [
        'ip_mainlink' => 'IP Mainlink',
        'ip_backup'   => 'IP Backup',
        'ip_loopback' => 'IP Loopback',
    ];

    protected $model;

    public function __construct()
    {
        $this->model = new RouterModel();
    }

    // Halaman utama (view DataRouter/index.php)
    public function index()
    {
        return view('DataRouter/index', [
            'router'   => $this->model->orderBy('nama_router', 'ASC')->findAll(),
            'opsiType' => self::TYPE,
        ]);
    }

    private function collectFields(): array
    {
        $post = fn (string $k) => trim(preg_replace('/\s+/', ' ', (string) $this->request->getPost($k)));

        return [
            'nama_router'       => $post('nama_router'),
            'ip_mainlink'       => $post('ip_mainlink'),
            'ip_backup'         => $post('ip_backup') ?: null,
            'ip_loopback'       => $post('ip_loopback') ?: null,
            'type'              => $post('type'),
            'seri_router'       => $post('seri_router') ?: null,
            'sn'                => $post('sn'),
            'versi_router'      => $post('versi_router') ?: null,
            'lokasi_pemasangan' => $post('lokasi_pemasangan'),
        ];
    }

    // Validasi isian + cek data ganda; kembalikan pesan error atau null
    private function validasi(array $f, ?int $id = null): ?string
    {
        $rules = [
            'nama_router'       => 'required|max_length[150]',
            'ip_mainlink'       => 'required|valid_ip',
            'ip_backup'         => 'permit_empty|valid_ip',
            'ip_loopback'       => 'permit_empty|valid_ip',
            'type'              => 'required|in_list[' . implode(',', self::TYPE) . ']',
            'seri_router'       => 'permit_empty|max_length[150]',
            'sn'                => 'required|max_length[150]',
            'versi_router'      => 'permit_empty|max_length[100]',
            'lokasi_pemasangan' => 'required|max_length[255]',
        ];
        $pesan = [
            'nama_router'       => ['required' => 'Nama Router wajib diisi.'],
            'ip_mainlink'       => ['required' => 'IP Mainlink wajib diisi.', 'valid_ip' => 'IP Mainlink tidak valid.'],
            'ip_backup'         => ['valid_ip' => 'IP Backup tidak valid.'],
            'ip_loopback'       => ['valid_ip' => 'IP Loopback tidak valid.'],
            'type'              => ['required' => 'Type wajib dipilih.', 'in_list' => 'Type harus MikroTik, Juniper, Cisco, atau Huawei.'],
            'sn'                => ['required' => 'SN wajib diisi.'],
            'lokasi_pemasangan' => ['required' => 'Lokasi Pemasangan wajib diisi.'],
        ];

        if (! $this->validateData($f, $rules, $pesan)) {
            return implode(' ', $this->validator->getErrors());
        }

        $errors = [];

        // nama router & SN tidak boleh sama dengan router lain
        // (collation utf8mb4_unicode_ci: huruf besar/kecil dianggap sama)
        foreach (['nama_router' => 'Nama Router', 'sn' => 'SN'] as $kolom => $label) {
            $q = $this->model->where($kolom, $f[$kolom]);
            if ($id) {
                $q->where('id !=', $id);
            }
            if ($lain = $q->first()) {
                $errors[] = $label . ' "' . $f[$kolom] . '" sudah dipakai router "' . $lain['nama_router'] . '".';
            }
        }

        // IP dalam satu router tidak boleh kembar
        $ipIsi = array_filter(array_intersect_key($f, self::IP));
        if (count($ipIsi) !== count(array_unique($ipIsi))) {
            $errors[] = 'IP Mainlink, IP Backup, dan IP Loopback tidak boleh sama.';
        }

        // IP tidak boleh dipakai router lain (di kolom IP mana pun)
        foreach ($ipIsi as $kolom => $ip) {
            $q = $this->model->groupStart()
                ->where('ip_mainlink', $ip)->orWhere('ip_backup', $ip)->orWhere('ip_loopback', $ip)
                ->groupEnd();
            if ($id) {
                $q->where('id !=', $id);
            }
            if ($lain = $q->first()) {
                $errors[] = self::IP[$kolom] . ' ' . $ip . ' sudah dipakai router "' . $lain['nama_router'] . '".';
            }
        }

        return $errors ? implode(' ', $errors) : null;
    }

    // Gagal: kembali ke halaman dan buka lagi pop up dengan isian sebelumnya
    private function gagal(string $pesan)
    {
        return redirect()->to(site_url('DataRouter'))
            ->withInput()
            ->with('popup', 'router')
            ->with('error', $pesan);
    }

    // Simpan data baru (POST DataRouter/save)
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
            return $this->gagal('Data Router gagal disimpan, kemungkinan Nama Router / SN sudah ada.');
        }

        return redirect()->to(site_url('DataRouter'))->with('success', 'Data Router berhasil ditambahkan.');
    }

    // Update data (POST DataRouter/update)
    public function update()
    {
        $id = (int) $this->request->getPost('id');

        if (! $id || ! $this->model->find($id)) {
            return redirect()->to(site_url('DataRouter'))->with('error', 'Data Router tidak ditemukan.');
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
            return $this->gagal('Data Router gagal diperbarui, kemungkinan Nama Router / SN sudah ada.');
        }

        return redirect()->to(site_url('DataRouter'))->with('success', 'Data Router berhasil diperbarui.');
    }

    // Hapus data
    public function delete($id)
    {
        $this->model->delete($id);

        return redirect()->to(site_url('DataRouter'))->with('success', 'Data Router berhasil dihapus.');
    }
}
