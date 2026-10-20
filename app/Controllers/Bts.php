<?php

namespace App\Controllers;

use App\Models\BtsModel;
use App\Models\BtsVendorModel;
use App\Models\VendorNocModel;

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

    // Pilihan dropdown Vendor di form tambah/edit, diambil dari menu Data Vendor (d_vendor)
    private function formData(): array
    {
        return ['dataVendor' => (new VendorNocModel())->orderBy('nama_vendor', 'ASC')->findAll()];
    }

    // Halaman form tambah (view Bts/FormBts.php)
    public function create()
    {
        return view('Bts/FormBts', $this->formData());
    }

    // Halaman form edit (view Bts/EditFormBts.php), vendor + IP Address & VLAN-nya diambil dari d_bts_vendor
    public function edit($id)
    {
        $row = $this->btsModel->withVendors((int) $id)[0] ?? null;

        if (! $row) {
            return redirect()->to(site_url('Bts'))->with('error', 'Data BTS tidak ditemukan.');
        }

        return view('Bts/EditFormBts', $this->formData() + ['row' => $row]);
    }

    private function rules(): array
    {
        return [
            'nama_bts' => 'required|max_length[255]',
        ];
    }

    private function collectFields(): array
    {
        $post = fn (string $k) => trim((string) $this->request->getPost($k));

        return [
            'nama_bts' => $post('nama_bts'),
        ];
    }

    // Baris vendor dari input vendor[n][vendor_id|ip_address|kapasitas|vlan_id|tanpa_vlan] (baris yang kosong semua dibuang)
    private function collectVendors(): array
    {
        $rows = [];

        foreach ((array) $this->request->getPost('vendor') as $input) {
            $input     = (array) $input;
            $isi       = fn (string $k) => trim((string) ($input[$k] ?? ''));
            $tanpaVlan = ! empty($input['tanpa_vlan']);

            if ($isi('vendor_id') === '' && $isi('ip_address') === '' && $isi('kapasitas') === '' && $isi('vlan_id') === '') {
                continue;
            }

            $rows[] = [
                'vendor_id'  => $isi('vendor_id'),
                'ip_address' => $isi('ip_address'),
                'kapasitas'  => $isi('kapasitas'),
                'vlan_id'    => $tanpaVlan ? null : $isi('vlan_id'),
            ];
        }

        return $rows;
    }

    // Validasi field + tiap baris vendor; kembalikan pesan error atau null jika lolos
    private function validasi(array $fields, array $vendors): ?string
    {
        $errors = [];

        if (! $this->validateData($fields, $this->rules())) {
            $errors = array_values($this->validator->getErrors());
        }

        if (empty($vendors)) {
            $errors[] = 'Minimal 1 Vendor wajib diisi.';
        }

        $vendorAda = array_column((new VendorNocModel())->select('id')->findAll(), 'id', 'id');

        foreach ($vendors as $i => $v) {
            $baris = 'Vendor ' . ($i + 1) . ': ';

            if (! isset($vendorAda[$v['vendor_id']])) {
                $errors[] = $baris . 'pilih vendor dari Data Vendor.';
            }
            if (! filter_var($v['ip_address'], FILTER_VALIDATE_IP)) {
                $errors[] = $baris . ($v['ip_address'] === '' ? 'IP Address wajib diisi.' : 'IP Address tidak valid.');
            }
            if ($v['kapasitas'] === '' || mb_strlen($v['kapasitas']) > 100) {
                $errors[] = $baris . ($v['kapasitas'] === '' ? 'Kapasitas wajib diisi.' : 'Kapasitas maksimal 100 karakter.');
            }
            if ($v['vlan_id'] !== null && (! ctype_digit($v['vlan_id']) || (int) $v['vlan_id'] < 1 || (int) $v['vlan_id'] > 4094)) {
                $errors[] = $baris . ($v['vlan_id'] === '' ? 'Vlan ID wajib diisi, atau centang Tanpa VLAN.' : 'Vlan ID harus angka 1 - 4094.');
            }
        }

        return $errors ? implode(' ', $errors) : null;
    }

    // Ganti seluruh vendor (beserta IP Address, Kapasitas & VLAN-nya) milik BTS dengan isian baru
    private function simpanVendors(int $btsId, array $vendors): void
    {
        $this->btsVendorModel->where('bts_id', $btsId)->delete();

        foreach ($vendors as $v) {
            $this->btsVendorModel->insert([
                'bts_id'     => $btsId,
                'vendor_id'  => (int) $v['vendor_id'],
                'ip_address' => $v['ip_address'],
                'kapasitas'  => $v['kapasitas'],
                'vlan_id'    => $v['vlan_id'] === null ? null : (int) $v['vlan_id'],
            ]);
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

    // Hapus data (vendor beserta IP Address & VLAN-nya ikut dihapus)
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
