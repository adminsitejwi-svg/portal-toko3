<?php

namespace App\Controllers;

use App\Models\DataCenterDcModel;
use App\Models\DataPerangkatModel;
use App\Models\DataRakModel;

// Halaman Data Rak DC (menu All NOC > Data Rak DC): Data Center, Data Rak, Data Perangkat & Visual Rak dalam satu halaman
// Relasi: data center -> rak -> perangkat
class DataRakDC extends BaseController
{
    protected $dcModel;
    protected $rakModel;
    protected $perangkatModel;

    // Angka maks 3 digit + 2 desimal, sesuai kolom DECIMAL(5,2)
    private const REGEX_U = 'regex_match[/^\d{1,3}(\.\d{1,2})?$/]';

    public function __construct()
    {
        $this->dcModel        = new DataCenterDcModel();
        $this->rakModel       = new DataRakModel();
        $this->perangkatModel = new DataPerangkatModel();
    }

    // Satu halaman: data center, rak, perangkat & visual rak (filter per data center/rak dilakukan di browser)
    public function index()
    {
        return view('DataRakDC/index', [
            'dataDc'        => $this->dcModel->withJumlah(),
            'dataRak'       => $this->rakModel->withRelasi(),
            'dataPerangkat' => $this->perangkatModel->withRelasi(),
        ]);
    }

    // ----- helper form -----

    // Ambil field dari POST; kolom opsional yang kosong disimpan NULL (angka 0 tetap 0)
    private function ambilFields(array $kolom): array
    {
        $fields = [];
        foreach ($kolom as $k) {
            $nilai      = trim((string) $this->request->getPost($k));
            $fields[$k] = $nilai === '' ? null : $nilai;
        }

        return $fields;
    }

    // Kembali ke halaman asal, pesan error + isian form disimpan agar pop up terbuka lagi
    private function gagal(string $form, array $fields, $id, string $pesan)
    {
        return redirect()->back()
            ->with('error', $pesan)
            ->with('form_old', ['_form' => $form, 'id' => $id] + $fields);
    }

    // 5.5 -> 550; posisi dihitung dalam bilangan bulat agar aman dari pembulatan float
    private function seratus($n): int
    {
        return (int) round((float) $n * 100);
    }

    // ----- Data Center -----

    private function simpanDc($id = null)
    {
        $fields = $this->ambilFields(['kode_dc', 'nama_dc', 'alamat', 'gedung', 'lantai', 'pic', 'kontak', 'status', 'keterangan']);

        $rules = [
            'kode_dc'    => 'required|max_length[50]|is_unique[md_data_center_dc.kode_dc,id,' . (int) $id . ']',
            'nama_dc'    => 'required|max_length[150]',
            'alamat'     => 'permit_empty',
            'gedung'     => 'permit_empty|max_length[100]',
            'lantai'     => 'permit_empty|max_length[50]',
            'pic'        => 'permit_empty|max_length[150]',
            'kontak'     => 'permit_empty|max_length[30]',
            'status'     => 'required|in_list[0,1]',
            'keterangan' => 'permit_empty',
        ];
        $pesan = ['kode_dc' => ['is_unique' => 'Kode Data Center sudah dipakai.']];

        if (! $this->validateData($fields, $rules, $pesan)) {
            return $this->gagal('dc', $fields, $id, implode(' ', $this->validator->getErrors()));
        }

        $id ? $this->dcModel->update($id, $fields) : $this->dcModel->insert($fields);

        return redirect()->back()->with('success', 'Data Center berhasil ' . ($id ? 'diperbarui.' : 'ditambahkan.'));
    }

    public function saveDc()
    {
        return $this->simpanDc();
    }

    public function updateDc()
    {
        $id = (int) $this->request->getPost('id');

        if (! $this->dcModel->find($id)) {
            return redirect()->back()->with('error', 'Data Center tidak ditemukan.');
        }

        return $this->simpanDc($id);
    }

    // Data center yang masih punya rak tidak bisa dihapus
    public function deleteDc($id)
    {
        $jumlah = $this->rakModel->where('data_center_id', $id)->countAllResults();

        if ($jumlah > 0) {
            return redirect()->back()->with('error', "Data Center masih memiliki {$jumlah} rak. Hapus atau pindahkan raknya lebih dulu.");
        }

        $this->dcModel->delete($id);

        return redirect()->back()->with('success', 'Data Center berhasil dihapus.');
    }

    // ----- Data Rak -----

    private function simpanRak($id = null)
    {
        $fields = $this->ambilFields(['data_center_id', 'kode_rak', 'nama_rak', 'ukuran_u', 'tinggi_cm', 'lebar_cm', 'kedalaman_cm', 'lokasi_rak', 'kapasitas_listrik', 'status', 'keterangan']);

        $dimensi = 'permit_empty|regex_match[/^\d{1,6}(\.\d{1,2})?$/]';
        $rules   = [
            'data_center_id'    => 'required|is_not_unique[md_data_center_dc.id]',
            'kode_rak'          => 'required|max_length[50]',
            'nama_rak'          => 'required|max_length[100]',
            'ukuran_u'          => 'required|' . self::REGEX_U . '|greater_than_equal_to[1]',
            'tinggi_cm'         => $dimensi,
            'lebar_cm'          => $dimensi,
            'kedalaman_cm'      => $dimensi,
            'lokasi_rak'        => 'permit_empty|max_length[150]',
            'kapasitas_listrik' => 'permit_empty|max_length[50]',
            'status'            => 'required|in_list[0,1]',
            'keterangan'        => 'permit_empty',
        ];
        $pesanAngka = ['regex_match' => 'harus berupa angka dengan maksimal 2 desimal.'];
        $pesan      = [
            'data_center_id' => ['required' => 'Data Center wajib dipilih.', 'is_not_unique' => 'Data Center tidak ditemukan.'],
            'ukuran_u'       => ['regex_match' => 'Ukuran rak (U) harus angka, maksimal 999.99.', 'greater_than_equal_to' => 'Ukuran rak minimal 1U.'],
            'tinggi_cm'      => ['regex_match' => 'Tinggi ' . $pesanAngka['regex_match']],
            'lebar_cm'       => ['regex_match' => 'Lebar ' . $pesanAngka['regex_match']],
            'kedalaman_cm'   => ['regex_match' => 'Kedalaman ' . $pesanAngka['regex_match']],
        ];

        if (! $this->validateData($fields, $rules, $pesan)) {
            return $this->gagal('rak', $fields, $id, implode(' ', $this->validator->getErrors()));
        }

        // kode rak unik per data center
        $kembar = $this->rakModel->where('data_center_id', $fields['data_center_id'])->where('kode_rak', $fields['kode_rak']);
        if ($id) {
            $kembar->where('id !=', $id);
        }
        if ($kembar->countAllResults() > 0) {
            return $this->gagal('rak', $fields, $id, 'Kode Rak sudah dipakai di Data Center ini.');
        }

        // ukuran rak tidak boleh lebih kecil dari posisi perangkat yang sudah terpasang
        if ($id) {
            foreach ($this->perangkatModel->where('data_rak_id', $id)->where('posisi_u IS NOT NULL')->findAll() as $p) {
                if ($this->seratus($p['posisi_u']) + $this->seratus($p['ukuran_u']) > $this->seratus($fields['ukuran_u']) + 100) {
                    return $this->gagal('rak', $fields, $id, 'Ukuran rak terlalu kecil: perangkat ' . $p['kode_perangkat']
                        . ' terpasang di ' . DataPerangkatModel::labelU($p['posisi_u'], $p['ukuran_u']) . '.');
                }
            }
        }

        $id ? $this->rakModel->update($id, $fields) : $this->rakModel->insert($fields);

        return redirect()->back()->with('success', 'Data Rak berhasil ' . ($id ? 'diperbarui.' : 'ditambahkan.'));
    }

    public function saveRak()
    {
        return $this->simpanRak();
    }

    public function updateRak()
    {
        $id = (int) $this->request->getPost('id');

        if (! $this->rakModel->find($id)) {
            return redirect()->back()->with('error', 'Data Rak tidak ditemukan.');
        }

        return $this->simpanRak($id);
    }

    // Rak yang masih berisi perangkat tidak bisa dihapus
    public function deleteRak($id)
    {
        $jumlah = $this->perangkatModel->where('data_rak_id', $id)->countAllResults();

        if ($jumlah > 0) {
            return redirect()->back()->with('error', "Rak masih berisi {$jumlah} perangkat. Hapus atau pindahkan perangkatnya lebih dulu.");
        }

        $this->rakModel->delete($id);

        return redirect()->back()->with('success', 'Data Rak berhasil dihapus.');
    }

    // ----- Data Perangkat -----

    // Cek perangkat muat di rak & tidak menimpa perangkat lain; kembalikan pesan error atau null
    private function cekPosisi(array $fields, $id = null): ?string
    {
        if ($fields['posisi_u'] === null) {
            return null;
        }

        $rak   = $this->rakModel->find($fields['data_rak_id']);
        $awal  = $this->seratus($fields['posisi_u']);
        $akhir = $awal + $this->seratus($fields['ukuran_u']);

        if ($akhir > $this->seratus($rak['ukuran_u']) + 100) {
            return 'Perangkat melebihi tinggi rak ' . $rak['kode_rak'] . ' (' . DataPerangkatModel::angka($rak['ukuran_u']) . 'U).';
        }

        $lain = $this->perangkatModel->where('data_rak_id', $fields['data_rak_id'])->where('posisi_u IS NOT NULL');
        if ($id) {
            $lain->where('id !=', $id);
        }

        foreach ($lain->findAll() as $p) {
            $awalLain  = $this->seratus($p['posisi_u']);
            $akhirLain = $awalLain + $this->seratus($p['ukuran_u']);

            if ($awal < $akhirLain && $awalLain < $akhir) {
                return 'Posisi bentrok dengan perangkat ' . $p['kode_perangkat'] . ' di ' . DataPerangkatModel::labelU($p['posisi_u'], $p['ukuran_u']) . '.';
            }
        }

        return null;
    }

    private function simpanPerangkat($id = null)
    {
        $fields = $this->ambilFields(['data_rak_id', 'kode_perangkat', 'nama_perangkat', 'jenis_perangkat', 'merk', 'model', 'serial_number', 'ukuran_u', 'posisi_u', 'ip_address', 'mac_address', 'tanggal_pasang', 'status', 'keterangan']);

        $rules = [
            'data_rak_id'     => 'required|is_not_unique[md_data_rak.id]',
            'kode_perangkat'  => 'required|max_length[50]|is_unique[md_data_perangkat.kode_perangkat,id,' . (int) $id . ']',
            'nama_perangkat'  => 'required|max_length[150]',
            'jenis_perangkat' => 'required|max_length[100]',
            'merk'            => 'permit_empty|max_length[100]',
            'model'           => 'permit_empty|max_length[100]',
            'serial_number'   => 'permit_empty|max_length[150]',
            'ukuran_u'        => 'required|' . self::REGEX_U . '|greater_than[0]',
            'posisi_u'        => 'permit_empty|' . self::REGEX_U . '|greater_than_equal_to[1]',
            'ip_address'      => 'permit_empty|max_length[45]|valid_ip',
            'mac_address'     => 'permit_empty|regex_match[/^([0-9A-Fa-f]{2}[:-]){5}[0-9A-Fa-f]{2}$/]',
            'tanggal_pasang'  => 'permit_empty|valid_date[Y-m-d]',
            'status'          => 'required|in_list[0,1]',
            'keterangan'      => 'permit_empty',
        ];
        $pesan = [
            'data_rak_id'    => ['required' => 'Rak wajib dipilih.', 'is_not_unique' => 'Rak tidak ditemukan.'],
            'kode_perangkat' => ['is_unique' => 'Kode Perangkat sudah dipakai.'],
            'ukuran_u'       => ['regex_match' => 'Ukuran perangkat (U) harus angka, maksimal 999.99.', 'greater_than' => 'Ukuran perangkat harus lebih dari 0.'],
            'posisi_u'       => ['regex_match' => 'Posisi U harus angka, maksimal 999.99.', 'greater_than_equal_to' => 'Posisi U minimal 1.'],
            'ip_address'     => ['valid_ip' => 'Format IP Address tidak valid.'],
            'mac_address'    => ['regex_match' => 'Format MAC Address tidak valid, contoh AA:BB:CC:DD:EE:FF.'],
        ];

        if (! $this->validateData($fields, $rules, $pesan)) {
            return $this->gagal('perangkat', $fields, $id, implode(' ', $this->validator->getErrors()));
        }

        if ($error = $this->cekPosisi($fields, $id)) {
            return $this->gagal('perangkat', $fields, $id, $error);
        }

        $id ? $this->perangkatModel->update($id, $fields) : $this->perangkatModel->insert($fields);

        return redirect()->back()->with('success', 'Data Perangkat berhasil ' . ($id ? 'diperbarui.' : 'ditambahkan.'));
    }

    public function savePerangkat()
    {
        return $this->simpanPerangkat();
    }

    public function updatePerangkat()
    {
        $id = (int) $this->request->getPost('id');

        if (! $this->perangkatModel->find($id)) {
            return redirect()->back()->with('error', 'Data Perangkat tidak ditemukan.');
        }

        return $this->simpanPerangkat($id);
    }

    public function deletePerangkat($id)
    {
        $this->perangkatModel->delete($id);

        return redirect()->back()->with('success', 'Data Perangkat berhasil dihapus.');
    }
}
