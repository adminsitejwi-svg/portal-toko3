<?php

namespace App\Controllers;

use App\Models\DataCenterModel;
use App\Models\DevicesBackboneModel;

// Halaman Devices Backbone (menu All NOC > Devices Backbone di sidebar)
class DevicesBackbone extends BaseController
{
    protected $dbbModel;

    public function __construct()
    {
        $this->dbbModel = new DevicesBackboneModel();
    }

    // Halaman utama (view di folder DevicesBackbone/index.php)
    public function index()
    {
        return view('DevicesBackbone/index', [
            'devices' => $this->dbbModel->withDataCenter(),
        ]);
    }

    // Pilihan dropdown Lokasi Penempatan (Data Center) di form tambah/edit
    private function formData(): array
    {
        return ['dataCenter' => (new DataCenterModel())->orderBy('nama_dc', 'ASC')->findAll()];
    }

    // Halaman form tambah (view DevicesBackbone/FormDevicesBackbone.php)
    public function create()
    {
        return view('DevicesBackbone/FormDevicesBackbone', $this->formData());
    }

    // Halaman form edit (view DevicesBackbone/EditFormDevicesBackbone.php)
    public function edit($id)
    {
        $row = $this->dbbModel->find($id);

        if (! $row) {
            return redirect()->to(site_url('DevicesBackbone'))->with('error', 'Devices Backbone tidak ditemukan.');
        }

        return view('DevicesBackbone/EditFormDevicesBackbone', $this->formData() + ['row' => $row]);
    }

    private function rules(): array
    {
        return [
            'nama_perangkat' => 'required|max_length[255]',
            'type'           => 'required|max_length[255]',
            'sn'             => 'permit_empty|max_length[255]',
            'jumlah'         => 'required|is_natural_no_zero',
            'data_center_id' => 'required|is_not_unique[md_data_center.id]',
        ];
    }

    private function messages(): array
    {
        return [
            'jumlah'         => ['is_natural_no_zero' => 'Jumlah harus angka minimal 1.'],
            'data_center_id' => [
                'required'      => 'Lokasi Penempatan wajib dipilih.',
                'is_not_unique' => 'Lokasi Penempatan tidak ditemukan di Data Center.',
            ],
        ];
    }

    private function collectFields(): array
    {
        $post = fn (string $k) => trim((string) $this->request->getPost($k));

        return [
            'nama_perangkat' => $post('nama_perangkat'),
            'type'           => $post('type'),
            'sn'             => $post('sn') ?: null,
            'jumlah'         => $post('jumlah'),
            'data_center_id' => $post('data_center_id'),
        ];
    }

    // Simpan data baru (POST DevicesBackbone/save)
    public function save()
    {
        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules(), $this->messages())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->dbbModel->insert($fields);

        return redirect()->to(site_url('DevicesBackbone'))
            ->with('success', 'Devices Backbone berhasil ditambahkan.');
    }

    // Update data (POST DevicesBackbone/update)
    public function update()
    {
        $id = $this->request->getPost('id');

        if (! $this->dbbModel->find($id)) {
            return redirect()->to(site_url('DevicesBackbone'))->with('error', 'Devices Backbone tidak ditemukan.');
        }

        $fields = $this->collectFields();

        if (! $this->validateData($fields, $this->rules(), $this->messages())) {
            return redirect()->back()
                ->withInput()
                ->with('error', implode(' ', $this->validator->getErrors()));
        }

        $this->dbbModel->update($id, $fields);

        return redirect()->to(site_url('DevicesBackbone'))
            ->with('success', 'Devices Backbone berhasil diperbarui.');
    }

    // Hapus data
    public function delete($id)
    {
        $this->dbbModel->delete($id);

        return redirect()->to(site_url('DevicesBackbone'))
            ->with('success', 'Devices Backbone berhasil dihapus.');
    }
}
