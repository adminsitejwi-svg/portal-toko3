<?php

namespace App\Controllers;

use App\Models\VlanModel;
use App\Models\VlanPerangkatModel;

// Halaman Data VLAN (menu All NOC > Data VLAN): tabel VLAN + mapping perangkat.
// Tambah / edit lewat pop up; baris mapping perangkat bisa ditambah sebanyak yang perlu.
class Vlan extends BaseController
{
    // Pilihan dropdown; "Lainnya" / "Other" = isi manual
    public const JENIS_LAYANAN = ['Corporate', 'Retail', 'Management', 'Monitoring', 'Backbone', 'Interkoneksi'];
    public const STATUS        = ['Aktif', 'Non Aktif'];
    public const VENDOR        = ['MikroTik', 'Cisco', 'Huawei', 'Juniper', 'Arista', 'ZTE', 'HSGQ'];
    public const MODE_PORT     = ['Access', 'Trunk', 'Routed'];

    // nilai option "isi manual" di dropdown
    private const MANUAL = '__manual__';

    protected $vlanModel;
    protected $perangkatModel;

    public function __construct()
    {
        $this->vlanModel      = new VlanModel();
        $this->perangkatModel = new VlanPerangkatModel();
    }

    // Halaman utama (view Vlan/index.php)
    public function index()
    {
        $vlan = $this->vlanModel->orderBy('vlan_id', 'ASC')->orderBy('lokasi_pop', 'ASC')->findAll();

        // mapping perangkat dikelompokkan per VLAN
        $perangkat = [];
        foreach ($this->perangkatModel->orderBy('urutan', 'ASC')->orderBy('id', 'ASC')->findAll() as $p) {
            $perangkat[$p['md_vlan_id']][] = $p;
        }
        foreach ($vlan as &$v) {
            $v['perangkat'] = $perangkat[$v['id']] ?? [];
        }
        unset($v);

        return view('Vlan/index', [
            'vlan'          => $vlan,
            'opsiJenis'     => self::JENIS_LAYANAN,
            'opsiStatus'    => self::STATUS,
            'opsiVendor'    => self::VENDOR,
            'opsiModePort'  => self::MODE_PORT,
        ]);
    }

    // Nilai dropdown; bila "isi manual" dipilih, pakai teks dari input manualnya
    private function pilihan(string $nilai, string $manual): string
    {
        return trim($nilai === self::MANUAL ? $manual : $nilai);
    }

    private function collectVlan(): array
    {
        $post = fn (string $k) => trim((string) $this->request->getPost($k));

        return [
            'vlan_id'       => $post('vlan_id'),
            'nama_vlan'     => $post('nama_vlan'),
            'jenis_layanan' => $this->pilihan($post('jenis_layanan'), $post('jenis_layanan_manual')),
            'lokasi_pop'    => $post('lokasi_pop'),
            'status'        => $post('status'),
            'keterangan'    => $post('keterangan') ?: null,
        ];
    }

    // Baris mapping perangkat dari form (baris yang semua isiannya kosong dilewati)
    private function collectPerangkat(): array
    {
        $ambil = function (string $k): array {
            $v = $this->request->getPost($k);
            return is_array($v) ? array_values($v) : [];
        };
        $nama       = $ambil('pr_nama');
        $vendor     = $ambil('pr_vendor');
        $vendorLain = $ambil('pr_vendor_manual');
        $port       = $ambil('pr_port');
        $mode       = $ambil('pr_mode');
        $modeLain   = $ambil('pr_mode_manual');
        $ket        = $ambil('pr_keterangan');

        $baris = [];
        foreach ($nama as $i => $n) {
            $row = [
                'nama_perangkat' => trim((string) $n),
                'vendor'         => $this->pilihan((string) ($vendor[$i] ?? ''), (string) ($vendorLain[$i] ?? '')) ?: null,
                'interface_port' => trim((string) ($port[$i] ?? '')) ?: null,
                'mode_port'      => $this->pilihan((string) ($mode[$i] ?? ''), (string) ($modeLain[$i] ?? '')) ?: null,
                'keterangan'     => trim((string) ($ket[$i] ?? '')) ?: null,
            ];
            if ($row['nama_perangkat'] === '' && ! array_filter(array_slice($row, 1))) {
                continue;
            }
            $baris[] = $row;
        }

        return $baris;
    }

    // Validasi; kembalikan pesan error atau null
    private function validasi(array $fields, array $perangkat, $id = null): ?string
    {
        $rules = [
            'vlan_id'       => 'required|is_natural_no_zero|less_than_equal_to[4094]',
            'nama_vlan'     => 'required|max_length[150]',
            'jenis_layanan' => 'required|max_length[100]',
            'lokasi_pop'    => 'required|max_length[150]',
            'status'        => 'required|in_list[' . implode(',', self::STATUS) . ']',
        ];
        $pesan = [
            'vlan_id' => [
                'is_natural_no_zero'    => 'VLAN ID harus angka 1 - 4094.',
                'less_than_equal_to'    => 'VLAN ID harus angka 1 - 4094.',
            ],
            'jenis_layanan' => ['required' => 'Jenis Layanan wajib dipilih / diisi.'],
            'status'        => ['in_list' => 'Status harus Aktif atau Non Aktif.'],
        ];

        if (! $this->validateData($fields, $rules, $pesan)) {
            return implode(' ', $this->validator->getErrors());
        }

        // VLAN ID yang sama boleh di lokasi/POP berbeda, tapi tidak dobel di lokasi yang sama
        $dobel = $this->vlanModel->where('vlan_id', $fields['vlan_id'])->where('lokasi_pop', $fields['lokasi_pop']);
        if ($id) {
            $dobel->where('id !=', $id);
        }
        if ($dobel->countAllResults() > 0) {
            return 'VLAN ID ' . $fields['vlan_id'] . ' sudah ada di lokasi / POP "' . $fields['lokasi_pop'] . '".';
        }

        foreach ($perangkat as $i => $p) {
            $no = $i + 1;
            if ($p['nama_perangkat'] === '') {
                return 'Nama Perangkat pada mapping baris ' . $no . ' wajib diisi.';
            }
            if (mb_strlen($p['nama_perangkat']) > 150 || mb_strlen((string) $p['vendor']) > 100
                || mb_strlen((string) $p['interface_port']) > 150 || mb_strlen((string) $p['mode_port']) > 50) {
                return 'Isian mapping perangkat baris ' . $no . ' terlalu panjang.';
            }
        }

        return null;
    }

    // Gagal validasi: kembali ke halaman dan buka lagi pop up dengan isian sebelumnya
    private function gagal(string $pesan)
    {
        return redirect()->to(site_url('Vlan'))
            ->withInput()
            ->with('popup', 'vlan')
            ->with('error', $pesan);
    }

    // Simpan VLAN + mapping perangkatnya dalam satu transaksi
    private function simpan(array $fields, array $perangkat, $id = null): bool
    {
        $db = \Config\Database::connect();
        $db->transStart();

        if ($id) {
            $this->vlanModel->update($id, $fields);
            $this->perangkatModel->where('md_vlan_id', $id)->delete();
        } else {
            $id = $this->vlanModel->insert($fields);
        }

        foreach ($perangkat as $i => $p) {
            $this->perangkatModel->insert($p + ['md_vlan_id' => $id, 'urutan' => $i]);
        }

        $db->transComplete();

        return $db->transStatus();
    }

    // Simpan data baru (POST Vlan/save)
    public function save()
    {
        $fields    = $this->collectVlan();
        $perangkat = $this->collectPerangkat();

        if ($error = $this->validasi($fields, $perangkat)) {
            return $this->gagal($error);
        }
        if (! $this->simpan($fields, $perangkat)) {
            return $this->gagal('Gagal menyimpan data VLAN.');
        }

        return redirect()->to(site_url('Vlan'))->with('success', 'Data VLAN berhasil ditambahkan.');
    }

    // Update data (POST Vlan/update)
    public function update()
    {
        $id = $this->request->getPost('id');

        if (! $id || ! $this->vlanModel->find($id)) {
            return redirect()->to(site_url('Vlan'))->with('error', 'Data VLAN tidak ditemukan.');
        }

        $fields    = $this->collectVlan();
        $perangkat = $this->collectPerangkat();

        if ($error = $this->validasi($fields, $perangkat, $id)) {
            return $this->gagal($error);
        }
        if (! $this->simpan($fields, $perangkat, $id)) {
            return $this->gagal('Gagal menyimpan data VLAN.');
        }

        return redirect()->to(site_url('Vlan'))->with('success', 'Data VLAN berhasil diperbarui.');
    }

    // Hapus VLAN (mapping perangkatnya ikut terhapus)
    public function delete($id)
    {
        $db = \Config\Database::connect();
        $db->transStart();
        $this->perangkatModel->where('md_vlan_id', $id)->delete();
        $this->vlanModel->delete($id);
        $db->transComplete();

        return redirect()->to(site_url('Vlan'))->with('success', 'Data VLAN berhasil dihapus.');
    }
}
