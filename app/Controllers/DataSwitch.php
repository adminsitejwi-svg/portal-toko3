<?php

namespace App\Controllers;

use App\Models\SwitchModel;

// Halaman Data Switch (menu Data Teknis NOC > Data Switch). Tambah / edit lewat pop up.
// Tidak boleh dobel: Nama Switch, IP Switch, dan SN.
class DataSwitch extends BaseController
{
    public const TYPE = ['Cisco', 'Arista', 'Juniper', 'TP-Link', 'Extreme'];

    protected $model;

    public function __construct()
    {
        $this->model = new SwitchModel();
    }

    // Halaman utama (view DataSwitch/index.php)
    public function index()
    {
        return view('DataSwitch/index', [
            'switches' => $this->model->orderBy('nama_switch', 'ASC')->findAll(),
            'opsiType' => self::TYPE,
        ]);
    }

    private function collectFields(): array
    {
        $post = fn (string $k) => trim(preg_replace('/\s+/', ' ', (string) $this->request->getPost($k)));

        return [
            'nama_switch'       => $post('nama_switch'),
            'ip_switch'         => $post('ip_switch'),
            'type'              => $post('type'),
            'seri_switch'       => $post('seri_switch') ?: null,
            'sn'                => $post('sn'),
            'versi_switch'      => $post('versi_switch') ?: null,
            'lokasi_pemasangan' => $post('lokasi_pemasangan'),
        ];
    }

    // Validasi isian + cek data ganda; kembalikan pesan error atau null
    private function validasi(array $f, ?int $id = null): ?string
    {
        $rules = [
            'nama_switch'       => 'required|max_length[150]',
            'ip_switch'         => 'required|valid_ip',
            'type'              => 'required|in_list[' . implode(',', self::TYPE) . ']',
            'seri_switch'       => 'permit_empty|max_length[150]',
            'sn'                => 'required|max_length[150]',
            'versi_switch'      => 'permit_empty|max_length[100]',
            'lokasi_pemasangan' => 'required|max_length[255]',
        ];
        $pesan = [
            'nama_switch'       => ['required' => 'Nama Switch wajib diisi.'],
            'ip_switch'         => ['required' => 'IP Switch wajib diisi.', 'valid_ip' => 'IP Switch tidak valid.'],
            'type'              => ['required' => 'Type wajib dipilih.', 'in_list' => 'Type harus Cisco, Arista, Juniper, TP-Link, atau Extreme.'],
            'sn'                => ['required' => 'SN wajib diisi.'],
            'lokasi_pemasangan' => ['required' => 'Lokasi Pemasangan wajib diisi.'],
        ];

        if (! $this->validateData($f, $rules, $pesan)) {
            return implode(' ', $this->validator->getErrors());
        }

        // tidak boleh sama dengan switch lain (collation utf8mb4_unicode_ci: huruf besar/kecil dianggap sama)
        $errors = [];
        foreach (['nama_switch' => 'Nama Switch', 'ip_switch' => 'IP Switch', 'sn' => 'SN'] as $kolom => $label) {
            $q = $this->model->where($kolom, $f[$kolom]);
            if ($id) {
                $q->where('id !=', $id);
            }
            if ($lain = $q->first()) {
                $errors[] = $label . ' "' . $f[$kolom] . '" sudah dipakai switch "' . $lain['nama_switch'] . '".';
            }
        }

        return $errors ? implode(' ', $errors) : null;
    }

    // Gagal: kembali ke halaman dan buka lagi pop up dengan isian sebelumnya
    private function gagal(string $pesan)
    {
        return redirect()->to(site_url('DataSwitch'))
            ->withInput()
            ->with('popup', 'switch')
            ->with('error', $pesan);
    }

    // Simpan data baru (POST DataSwitch/save)
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
            return $this->gagal('Data Switch gagal disimpan, kemungkinan Nama Switch / IP / SN sudah ada.');
        }

        return redirect()->to(site_url('DataSwitch'))->with('success', 'Data Switch berhasil ditambahkan.');
    }

    // Update data (POST DataSwitch/update)
    public function update()
    {
        $id = (int) $this->request->getPost('id');

        if (! $id || ! $this->model->find($id)) {
            return redirect()->to(site_url('DataSwitch'))->with('error', 'Data Switch tidak ditemukan.');
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
            return $this->gagal('Data Switch gagal diperbarui, kemungkinan Nama Switch / IP / SN sudah ada.');
        }

        return redirect()->to(site_url('DataSwitch'))->with('success', 'Data Switch berhasil diperbarui.');
    }

    // Hapus data
    public function delete($id)
    {
        $this->model->delete($id);

        return redirect()->to(site_url('DataSwitch'))->with('success', 'Data Switch berhasil dihapus.');
    }
}
