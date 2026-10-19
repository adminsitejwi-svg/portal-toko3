<?php

namespace App\Controllers;

use App\Models\DataCenterModel;
use App\Models\InterkoneksiModel;

// Halaman Data Interkoneksi (menu All NOC > Data Interkoneksi di sidebar)
class Interkoneksi extends BaseController
{
    protected $ikModel;

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

    // Simpan data baru (POST Interkoneksi/save)
    public function save()
    {
        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules(), $this->messages())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->ikModel->insert($fields);

        return redirect()->to(site_url('Interkoneksi'))
            ->with('success', 'Data Interkoneksi berhasil ditambahkan.');
    }

    // Update data (POST Interkoneksi/update)
    public function update()
    {
        $id = $this->request->getPost('id');

        if (! $this->ikModel->find($id)) {
            return redirect()->to(site_url('Interkoneksi'))->with('error', 'Data Interkoneksi tidak ditemukan.');
        }

        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules($id), $this->messages())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->ikModel->update($id, $fields);

        return redirect()->to(site_url('Interkoneksi'))
            ->with('success', 'Data Interkoneksi berhasil diperbarui.');
    }

    // Hapus data
    public function delete($id)
    {
        $this->ikModel->delete($id);

        return redirect()->to(site_url('Interkoneksi'))
            ->with('success', 'Data Interkoneksi berhasil dihapus.');
    }
}
