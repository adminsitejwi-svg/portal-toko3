<?php

namespace App\Controllers;

use App\Models\BtsModel;
use App\Models\BtsVendorModel;
use App\Models\VendorModel;

// Halaman BTS (menu All NOC > BTS di sidebar)
class Bts extends BaseController
{
    protected $btsModel;
    protected $btsVendorModel;

    public function __construct()
    {
        $this->btsModel       = new BtsModel();
        $this->btsVendorModel = new BtsVendorModel();
    }

    // Halaman utama (view di folder Bts/index.php)
    public function index()
    {
        return view('Bts/index', [
            'bts' => $this->btsModel->withVendors(),
        ]);
    }

    // Saran nama vendor di form tambah/edit, diambil dari menu Vendor (tetap bisa diketik manual)
    private function formData(): array
    {
        $saranVendor = array_unique(array_filter(array_map('trim', array_column(
            (new VendorModel())->select('nama_vendor')->findAll(),
            'nama_vendor'
        )), 'strlen'));
        natcasesort($saranVendor);

        return ['saranVendor' => $saranVendor];
    }

    // Halaman form tambah (view Bts/FormBts.php)
    public function create()
    {
        return view('Bts/FormBts', $this->formData());
    }

    // Halaman form edit (view Bts/EditFormBts.php), vendor diambil dari d_bts_vendor
    public function edit($id)
    {
        $row = $this->btsModel->find($id);

        if (! $row) {
            return redirect()->to(site_url('Bts'))->with('error', 'Data BTS tidak ditemukan.');
        }

        $row['vendors'] = array_column(
            $this->btsVendorModel->where('bts_id', (int) $id)->orderBy('id', 'ASC')->findAll(),
            'vendor'
        );

        return view('Bts/EditFormBts', $this->formData() + ['row' => $row]);
    }

    private function rules(bool $tanpaVlan): array
    {
        return [
            'nama_bts'   => 'required|max_length[255]',
            'ip_address' => 'required|valid_ip',
            'kapasitas'  => 'required|max_length[100]',
            'vlan_id'    => $tanpaVlan ? 'permit_empty' : 'required|is_natural_no_zero|less_than_equal_to[4094]',
        ];
    }

    private function messages(): array
    {
        return [
            'ip_address' => ['valid_ip' => 'IP Address tidak valid.'],
            'vlan_id'    => [
                'required'              => 'Vlan ID wajib diisi, atau centang Tanpa VLAN.',
                'is_natural_no_zero'    => 'Vlan ID harus angka 1 - 4094.',
                'less_than_equal_to'    => 'Vlan ID harus angka 1 - 4094.',
            ],
        ];
    }

    private function collectFields(): array
    {
        $post = fn (string $k) => trim((string) $this->request->getPost($k));

        return [
            'nama_bts'   => $post('nama_bts'),
            'ip_address' => $post('ip_address'),
            'kapasitas'  => $post('kapasitas'),
            'vlan_id'    => $this->tanpaVlan() ? null : $post('vlan_id'),
        ];
    }

    private function tanpaVlan(): bool
    {
        return (bool) $this->request->getPost('tanpa_vlan');
    }

    // Vendor dari input vendor[] (baris kosong & duplikat dibuang)
    private function collectVendors(): array
    {
        $vendors = array_map('trim', (array) $this->request->getPost('vendor'));

        return array_values(array_unique(array_filter($vendors, 'strlen')));
    }

    // Validasi field + vendor; kembalikan pesan error atau null jika lolos
    private function validasi(array $fields, array $vendors): ?string
    {
        $errors = [];

        if (! $this->validateData($fields, $this->rules($this->tanpaVlan()), $this->messages())) {
            $errors = array_values($this->validator->getErrors());
        }
        if (empty($vendors)) {
            $errors[] = 'Minimal 1 Vendor wajib diisi.';
        }
        foreach ($vendors as $v) {
            if (mb_strlen($v) > 255) {
                $errors[] = 'Nama Vendor maksimal 255 karakter.';
                break;
            }
        }

        return $errors ? implode(' ', $errors) : null;
    }

    private function simpanVendors(int $btsId, array $vendors): void
    {
        $this->btsVendorModel->where('bts_id', $btsId)->delete();

        foreach ($vendors as $v) {
            $this->btsVendorModel->insert(['bts_id' => $btsId, 'vendor' => $v]);
        }
    }

    // Simpan data baru (POST Bts/save)
    public function save()
    {
        $fields  = $this->collectFields();
        $vendors = $this->collectVendors();

        if ($error = $this->validasi($fields, $vendors)) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $db = db_connect();
        $db->transStart();
        $id = $this->btsModel->insert($fields);
        $this->simpanVendors((int) $id, $vendors);
        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'Data BTS gagal disimpan.');
        }

        return redirect()->to(site_url('Bts'))
            ->with('success', 'Data BTS berhasil ditambahkan.');
    }

    // Update data (POST Bts/update)
    public function update()
    {
        $id = (int) $this->request->getPost('id');

        if (! $this->btsModel->find($id)) {
            return redirect()->to(site_url('Bts'))->with('error', 'Data BTS tidak ditemukan.');
        }

        $fields  = $this->collectFields();
        $vendors = $this->collectVendors();

        if ($error = $this->validasi($fields, $vendors)) {
            return redirect()->back()->withInput()->with('error', $error);
        }

        $db = db_connect();
        $db->transStart();
        $this->btsModel->update($id, $fields);
        $this->simpanVendors($id, $vendors);
        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'Data BTS gagal diperbarui.');
        }

        return redirect()->to(site_url('Bts'))
            ->with('success', 'Data BTS berhasil diperbarui.');
    }

    // Hapus data (vendornya ikut dihapus)
    public function delete($id)
    {
        $db = db_connect();
        $db->transStart();
        $this->btsVendorModel->where('bts_id', (int) $id)->delete();
        $this->btsModel->delete($id);
        $db->transComplete();

        return redirect()->to(site_url('Bts'))
            ->with('success', 'Data BTS berhasil dihapus.');
    }
}
