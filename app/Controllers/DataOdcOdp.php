<?php

namespace App\Controllers;

use App\Models\OdcModel;
use App\Models\OdpModel;
use App\Models\PelangganOdpModel;
use App\Models\WilayahModel;

// Halaman Data ODC & ODP (menu Data Teknis NOC > Data ODC & ODP): pohon Wilayah > ODC > ODP + grid port pelanggan, dan peta; form berupa pop up.
// Kode & nama ODC / ODP dibuat otomatis: ODC-{kode wilayah}-001 / ODC-{nama wilayah}-001 (urut per wilayah).
class DataOdcOdp extends BaseController
{
    public const STATUS = ['Aktif', 'Rencana', 'Maintenance', 'Tidak Aktif'];

    protected $wilayahModel;
    protected $odcModel;
    protected $odpModel;
    protected $pelangganModel;

    public function __construct()
    {
        $this->wilayahModel = new WilayahModel();
        $this->odcModel     = new OdcModel();
        $this->odpModel     = new OdpModel();
        $this->pelangganModel = new PelangganOdpModel();
    }

    // ------------------------------------------------------------------
    //  HALAMAN
    // ------------------------------------------------------------------
    public function index()
    {
        $db = \Config\Database::connect();

        $wilayah = $this->wilayahModel->orderBy('kode_wilayah', 'ASC')->findAll();
        $jumlahOdc = array_column($db->query('SELECT wilayah_id, COUNT(*) n FROM d_odc GROUP BY wilayah_id')->getResultArray(), 'n', 'wilayah_id');
        $jumlahOdp = array_column($db->query('SELECT wilayah_id, COUNT(*) n FROM d_odp GROUP BY wilayah_id')->getResultArray(), 'n', 'wilayah_id');
        foreach ($wilayah as &$w) {
            $w['jumlah_odc'] = (int) ($jumlahOdc[$w['id']] ?? 0);
            $w['jumlah_odp'] = (int) ($jumlahOdp[$w['id']] ?? 0);
        }
        unset($w);

        $odc = $db->table('d_odc c')
            ->select('c.*, w.kode_wilayah, w.nama_wilayah,
                (SELECT COUNT(*) FROM d_odp p WHERE p.odc_id = c.id) AS jumlah_odp')
            ->join('md_wilayah w', 'w.id = c.wilayah_id')
            ->orderBy('w.kode_wilayah', 'ASC')->orderBy('c.nomor', 'ASC')
            ->get()->getResultArray();

        $odp = $db->table('d_odp p')
            ->select('p.*, w.kode_wilayah, w.nama_wilayah, c.kode_odc, c.nama_odc,
                (SELECT COUNT(*) FROM d_pelanggan_odp g WHERE g.odp_id = p.id) AS jumlah_pelanggan')
            ->join('md_wilayah w', 'w.id = p.wilayah_id')
            ->join('d_odc c', 'c.id = p.odc_id')
            ->orderBy('w.kode_wilayah', 'ASC')->orderBy('p.nomor', 'ASC')
            ->get()->getResultArray();

        // pelanggan per ODP (grid port)
        $pelanggan = $db->table('d_pelanggan_odp g')
            ->select('g.*, p.kode_odp, p.nama_odp, c.kode_odc, w.kode_wilayah, w.nama_wilayah')
            ->join('d_odp p', 'p.id = g.odp_id')
            ->join('d_odc c', 'c.id = p.odc_id')
            ->join('md_wilayah w', 'w.id = p.wilayah_id')
            ->orderBy('w.kode_wilayah', 'ASC')->orderBy('p.nomor', 'ASC')->orderBy('g.port', 'ASC')
            ->get()->getResultArray();

        return view('DataOdcOdp/index', [
            'wilayah'    => $wilayah,
            'odc'        => $odc,
            'odp'        => $odp,
            'pelanggan'  => $pelanggan,
            'opsiStatus' => self::STATUS,
        ]);
    }

    // Kembali ke halaman pada tab tertentu; bila gagal, pop up dibuka lagi dengan isian sebelumnya
    private function kembali(string $tab, string $pesan, bool $berhasil = true)
    {
        $r = redirect()->to(site_url('DataOdcOdp'))->with('tab', $tab);

        return $berhasil
            ? $r->with('success', $pesan)
            : $r->withInput()->with('popup', $tab)->with('error', $pesan);
    }

    private function post(string $k): string
    {
        return trim(preg_replace('/\s+/', ' ', (string) $this->request->getPost($k)));
    }

    // ------------------------------------------------------------------
    //  KODE & NAMA OTOMATIS
    // ------------------------------------------------------------------
    private function kodeOtomatis(string $jenis, array $wilayah, int $nomor): array
    {
        $prefix = strtoupper($jenis); // ODC / ODP
        $no     = sprintf('%03d', $nomor);

        return [
            'kode_' . $jenis => $prefix . '-' . $wilayah['kode_wilayah'] . '-' . $no,
            'nama_' . $jenis => $prefix . '-' . $wilayah['nama_wilayah'] . '-' . $no,
        ];
    }

    private function nomorBerikutnya(string $jenis, int $wilayahId): int
    {
        $row = \Config\Database::connect()->table('d_' . $jenis)
            ->selectMax('nomor', 'maks')->where('wilayah_id', $wilayahId)->get()->getRowArray();

        return (int) ($row['maks'] ?? 0) + 1;
    }

    // ------------------------------------------------------------------
    //  WILAYAH
    // ------------------------------------------------------------------
    private function validasiWilayah(array $f, ?int $id): ?string
    {
        if ($f['kode_wilayah'] === '' || $f['nama_wilayah'] === '') {
            return 'Kode Wilayah dan Nama Wilayah wajib diisi.';
        }
        if (! preg_match('/^[A-Z0-9]+(?:[-_][A-Z0-9]+)*$/', $f['kode_wilayah']) || strlen($f['kode_wilayah']) > 30) {
            return 'Kode Wilayah hanya boleh huruf, angka, dan tanda - / _ (maks. 30 karakter), contoh: JKT01.';
        }
        if (mb_strlen($f['nama_wilayah']) > 100) {
            return 'Nama Wilayah maksimal 100 karakter.';
        }
        foreach (['kode_wilayah' => 'Kode Wilayah', 'nama_wilayah' => 'Nama Wilayah'] as $k => $label) {
            $q = $this->wilayahModel->where($k, $f[$k]);
            if ($id) {
                $q->where('id !=', $id);
            }
            if ($q->first()) {
                return $label . ' "' . $f[$k] . '" sudah ada.';
            }
        }

        return null;
    }

    private function fieldWilayah(): array
    {
        return [
            'kode_wilayah' => strtoupper(str_replace(' ', '', $this->post('kode_wilayah'))),
            'nama_wilayah' => $this->post('nama_wilayah'),
        ];
    }

    public function saveWilayah()
    {
        $f = $this->fieldWilayah();
        if ($e = $this->validasiWilayah($f, null)) {
            return $this->kembali('wilayah', $e, false);
        }
        $this->wilayahModel->insert($f);

        return $this->kembali('wilayah', 'Wilayah ' . $f['kode_wilayah'] . ' - ' . $f['nama_wilayah'] . ' berhasil ditambahkan.');
    }

    // Kode / nama wilayah berubah -> kode & nama seluruh ODC & ODP di wilayah itu ikut diperbarui (nomor tetap)
    public function updateWilayah()
    {
        $id   = (int) $this->request->getPost('id');
        $lama = $this->wilayahModel->find($id);
        if (! $lama) {
            return $this->kembali('wilayah', 'Wilayah tidak ditemukan.', false);
        }

        $f = $this->fieldWilayah();
        if ($e = $this->validasiWilayah($f, $id)) {
            return $this->kembali('wilayah', $e, false);
        }

        $db = \Config\Database::connect();
        $db->transStart();
        $this->wilayahModel->update($id, $f);
        if ($f['kode_wilayah'] !== $lama['kode_wilayah'] || $f['nama_wilayah'] !== $lama['nama_wilayah']) {
            foreach (['odc' => $this->odcModel, 'odp' => $this->odpModel] as $jenis => $model) {
                foreach ($model->where('wilayah_id', $id)->findAll() as $r) {
                    $model->update($r['id'], $this->kodeOtomatis($jenis, $f, (int) $r['nomor']));
                }
            }
        }
        $db->transComplete();

        if (! $db->transStatus()) {
            return $this->kembali('wilayah', 'Wilayah gagal diperbarui.', false);
        }

        return $this->kembali('wilayah', 'Wilayah berhasil diperbarui.');
    }

    public function deleteWilayah($id)
    {
        $id  = (int) $id;
        $odc = $this->odcModel->where('wilayah_id', $id)->countAllResults();
        $odp = $this->odpModel->where('wilayah_id', $id)->countAllResults();
        if ($odc || $odp) {
            return redirect()->to(site_url('DataOdcOdp'))->with('tab', 'wilayah')
                ->with('error', 'Wilayah masih dipakai ' . $odc . ' ODC dan ' . $odp . ' ODP. Hapus / pindahkan dulu ODC & ODP-nya.');
        }
        $this->wilayahModel->delete($id);

        return $this->kembali('wilayah', 'Wilayah berhasil dihapus.');
    }

    // ------------------------------------------------------------------
    //  ODC & ODP (aturan sama, ODP punya ODC induk & port)
    // ------------------------------------------------------------------
    private function fieldPerangkat(string $jenis): array
    {
        $angka = fn (string $k) => $this->post($k);
        $f = [
            'wilayah_id'  => $angka('wilayah_id'),
            'latitude'    => $this->post('latitude'),
            'longitude'   => $this->post('longitude'),
            'lokasi_area' => $this->post('lokasi_area') ?: null,
            'status'      => $this->post('status'),
            'alamat'      => trim((string) $this->request->getPost('alamat')) ?: null,
            'keterangan'  => trim((string) $this->request->getPost('keterangan')) ?: null,
        ];
        if ($jenis === 'odc') {
            $f['kapasitas_core'] = $angka('kapasitas_core');
        } else {
            $f['odc_id']         = $angka('odc_id');
            $f['kapasitas_port'] = $angka('kapasitas_port');
        }

        return $f;
    }

    private function validasiPerangkat(string $jenis, array &$f): ?string
    {
        $err = [];
        $nama = strtoupper($jenis);

        $wilayah = ctype_digit($f['wilayah_id']) ? $this->wilayahModel->find((int) $f['wilayah_id']) : null;
        if (! $wilayah) {
            $err[] = 'Area Jaringan (wilayah) wajib dipilih.';
        }
        if (! in_array($f['status'], self::STATUS, true)) {
            $err[] = 'Status wajib dipilih (Aktif / Rencana / Maintenance / Tidak Aktif).';
        }

        if ($jenis === 'odc') {
            if (! ctype_digit($f['kapasitas_core']) || (int) $f['kapasitas_core'] < 1) {
                $err[] = 'Kapasitas Core wajib diisi angka minimal 1.';
            }
        } else {
            if (! ctype_digit($f['odc_id']) || ! $this->odcModel->find((int) $f['odc_id'])) {
                $err[] = 'ODC Induk wajib dipilih.';
            }
            $kap = ctype_digit($f['kapasitas_port']) ? (int) $f['kapasitas_port'] : -1;
            if ($kap < 1 || $kap > 256) {
                $err[] = 'Kapasitas Port wajib diisi angka 1 - 256.';
            }
        }

        // koordinat: boleh kosong, tapi bila diisi harus lengkap & dalam rentang
        $lat = str_replace(',', '.', $f['latitude']);
        $lng = str_replace(',', '.', $f['longitude']);
        if ($lat === '' && $lng === '') {
            $f['latitude'] = $f['longitude'] = null;
        } elseif (! is_numeric($lat) || ! is_numeric($lng) || abs((float) $lat) > 90 || abs((float) $lng) > 180) {
            $err[] = 'Latitude (-90 s/d 90) dan Longitude (-180 s/d 180) harus diisi lengkap dengan angka yang benar.';
        } else {
            $f['latitude']  = round((float) $lat, 7);
            $f['longitude'] = round((float) $lng, 7);
        }

        if (mb_strlen((string) $f['lokasi_area']) > 255) {
            $err[] = 'Lokasi dan Area maksimal 255 karakter.';
        }

        return $err ? implode(' ', $err) : null;
    }

    private function simpanPerangkat(string $jenis, ?int $id)
    {
        $model = $jenis === 'odc' ? $this->odcModel : $this->odpModel;
        $label = strtoupper($jenis);

        $lama = null;
        if ($id !== null) {
            $lama = $model->find($id);
            if (! $lama) {
                return $this->kembali($jenis, 'Data ' . $label . ' tidak ditemukan.', false);
            }
        }

        $f = $this->fieldPerangkat($jenis);
        if ($e = $this->validasiPerangkat($jenis, $f)) {
            return $this->kembali($jenis, $e, false);
        }

        if ($jenis === 'odp') {
            // port terpakai = jumlah pelanggan di ODP (otomatis); kapasitas tidak boleh di bawah port yang sudah terisi
            $isi = $id ? \Config\Database::connect()->table('d_pelanggan_odp')->select('MAX(port) AS port, COUNT(*) AS n')->where('odp_id', $id)->get()->getRowArray() : null;
            $portTertinggi = (int) ($isi['port'] ?? 0);
            if ($portTertinggi > (int) $f['kapasitas_port']) {
                return $this->kembali('odp', 'Kapasitas Port tidak boleh kurang dari ' . $portTertinggi . ' karena Port ' . $portTertinggi . ' sudah terisi pelanggan.', false);
            }
            $f['port_terpakai'] = (int) ($isi['n'] ?? 0);
        }

        $wilayah = $this->wilayahModel->find((int) $f['wilayah_id']);

        // nomor urut: data baru / pindah wilayah = nomor berikutnya di wilayah itu; selain itu tetap
        // (dicoba ulang bila bentrok dengan penyimpanan lain pada saat bersamaan — UNIQUE wilayah_id + nomor)
        for ($coba = 0; $coba < 3; $coba++) {
            $tetap = $lama && (int) $lama['wilayah_id'] === (int) $wilayah['id'];
            $nomor = $tetap ? (int) $lama['nomor'] : $this->nomorBerikutnya($jenis, (int) $wilayah['id']);
            $data  = $f + ['nomor' => $nomor] + $this->kodeOtomatis($jenis, $wilayah, $nomor);

            try {
                $ok = $id === null ? $model->insert($data) !== false : $model->update($id, $data) !== false;
            } catch (\Throwable $e) {
                $ok = false;
            }
            if ($ok) {
                $pesan = $label . ' ' . $data['kode_' . $jenis] . ' (' . $data['nama_' . $jenis] . ') berhasil ' . ($id === null ? 'ditambahkan.' : 'diperbarui.');

                return $this->kembali($jenis, $pesan);
            }
            if ($tetap) {
                break;
            }
        }

        return $this->kembali($jenis, 'Data ' . $label . ' gagal disimpan, coba lagi.', false);
    }

    public function saveOdc()
    {
        return $this->simpanPerangkat('odc', null);
    }

    public function updateOdc()
    {
        return $this->simpanPerangkat('odc', (int) $this->request->getPost('id'));
    }

    public function deleteOdc($id)
    {
        $id  = (int) $id;
        $odp = $this->odpModel->where('odc_id', $id)->countAllResults();
        if ($odp) {
            return redirect()->to(site_url('DataOdcOdp'))->with('tab', 'odc')
                ->with('error', 'ODC masih menjadi induk ' . $odp . ' ODP. Pindahkan / hapus dulu ODP-nya.');
        }
        $this->odcModel->delete($id);

        return $this->kembali('odc', 'ODC berhasil dihapus.');
    }

    public function saveOdp()
    {
        return $this->simpanPerangkat('odp', null);
    }

    public function updateOdp()
    {
        return $this->simpanPerangkat('odp', (int) $this->request->getPost('id'));
    }

    // ODP yang masih punya pelanggan tidak bisa dihapus
    public function deleteOdp($id)
    {
        $id = (int) $id;
        $pelanggan = \Config\Database::connect()->table('d_pelanggan_odp')->where('odp_id', $id)->countAllResults();
        if ($pelanggan) {
            return redirect()->to(site_url('DataOdcOdp'))->with('tab', 'odp')
                ->with('error', 'ODP masih punya ' . $pelanggan . ' pelanggan. Kosongkan dulu port pelanggannya di detail ODP.');
        }
        $this->odpModel->delete($id);

        return $this->kembali('odp', 'ODP berhasil dihapus.');
    }

    // ------------------------------------------------------------------
    //  PELANGGAN (pilih ODP + port; Kode Pelanggan & port per ODP tidak boleh dobel)
    // ------------------------------------------------------------------
    private function fieldPelanggan(): array
    {
        return [
            'odp_id'         => $this->post('odp_id'),
            'port'           => $this->post('port'),
            'kode_pelanggan' => $this->post('kode_pelanggan'),
            'nama_pelanggan' => $this->post('nama_pelanggan'),
            // telepon disimpan angka saja (awalan + dibuang)
            'telepon'        => substr(preg_replace('/\D/', '', $this->post('telepon')), 0, 20) ?: null,
            'latitude'       => $this->post('latitude'),
            'longitude'      => $this->post('longitude'),
            'alamat'         => trim((string) $this->request->getPost('alamat')) ?: null,
        ];
    }

    // Validasi + cek dobel; koordinat dirapikan di $f. Kembalikan pesan error atau null
    private function validasiPelanggan(array &$f, ?int $id): ?string
    {
        $err = [];

        $odp = ctype_digit($f['odp_id']) ? $this->odpModel->find((int) $f['odp_id']) : null;
        if (! $odp) {
            $err[] = 'ODP wajib dipilih.';
        }
        // port: 1 s/d kapasitas ODP dan belum dipakai pelanggan lain di ODP yang sama
        if (! ctype_digit($f['port']) || (int) $f['port'] < 1) {
            $err[] = 'Port wajib dipilih.';
        } elseif ($odp) {
            if ((int) $f['port'] > (int) $odp['kapasitas_port']) {
                $err[] = 'Port ' . (int) $f['port'] . ' melebihi kapasitas ' . $odp['kode_odp'] . ' (' . $odp['kapasitas_port'] . ' port).';
            } else {
                $q = $this->pelangganModel->where('odp_id', $odp['id'])->where('port', (int) $f['port']);
                if ($id) {
                    $q->where('id !=', $id);
                }
                if ($lain = $q->first()) {
                    $err[] = 'Port ' . (int) $f['port'] . ' di ' . $odp['kode_odp'] . ' sudah dipakai pelanggan "' . $lain['nama_pelanggan'] . '".';
                }
            }
        }
        if ($f['kode_pelanggan'] === '' || mb_strlen($f['kode_pelanggan']) > 50) {
            $err[] = $f['kode_pelanggan'] === '' ? 'Kode Pelanggan wajib diisi.' : 'Kode Pelanggan maksimal 50 karakter.';
        }
        if ($f['nama_pelanggan'] === '' || mb_strlen($f['nama_pelanggan']) > 150) {
            $err[] = $f['nama_pelanggan'] === '' ? 'Nama Pelanggan wajib diisi.' : 'Nama Pelanggan maksimal 150 karakter.';
        }
        if ($f['telepon'] !== null && strlen($f['telepon']) < 8) {
            $err[] = 'Nomor Telepon minimal 8 angka.';
        }

        $lat = str_replace(',', '.', $f['latitude']);
        $lng = str_replace(',', '.', $f['longitude']);
        if ($lat === '' && $lng === '') {
            $f['latitude'] = $f['longitude'] = null;
        } elseif (! is_numeric($lat) || ! is_numeric($lng) || abs((float) $lat) > 90 || abs((float) $lng) > 180) {
            $err[] = 'Latitude (-90 s/d 90) dan Longitude (-180 s/d 180) harus diisi lengkap dengan angka yang benar.';
        } else {
            $f['latitude']  = round((float) $lat, 7);
            $f['longitude'] = round((float) $lng, 7);
        }

        if ($f['kode_pelanggan'] !== '') {
            $q = $this->pelangganModel->where('kode_pelanggan', $f['kode_pelanggan']);
            if ($id) {
                $q->where('id !=', $id);
            }
            if ($lain = $q->first()) {
                $err[] = 'Kode Pelanggan "' . $f['kode_pelanggan'] . '" sudah dipakai pelanggan "' . $lain['nama_pelanggan'] . '".';
            }
        }

        return $err ? implode(' ', $err) : null;
    }

    // port_terpakai ODP selalu = jumlah pelanggan di ODP itu
    private function sinkronPort(int ...$odpIds): void
    {
        foreach (array_unique(array_filter($odpIds)) as $odpId) {
            $n = $this->pelangganModel->where('odp_id', $odpId)->countAllResults();
            \Config\Database::connect()->table('d_odp')->where('id', $odpId)->update(['port_terpakai' => $n]);
        }
    }

    private function simpanPelanggan(?int $id)
    {
        $lama = $id ? $this->pelangganModel->find($id) : null;
        if ($id !== null && ! $lama) {
            return redirect()->to(site_url('DataOdcOdp'))->with('tab', 'pelanggan')->with('error', 'Data pelanggan tidak ditemukan.');
        }

        $f = $this->fieldPelanggan();
        if ($e = $this->validasiPelanggan($f, $id)) {
            return $this->kembali('pelanggan', $e, false);
        }

        try {
            $ok = ($id ? $this->pelangganModel->update($id, $f) : $this->pelangganModel->insert($f)) !== false;
        } catch (\Throwable $e) {
            $ok = false;
        }
        if (! $ok) {
            return $this->kembali('pelanggan', 'Data pelanggan gagal disimpan, kemungkinan Kode Pelanggan / Port sudah dipakai.', false);
        }
        $this->sinkronPort((int) $f['odp_id'], (int) ($lama['odp_id'] ?? 0));

        return $this->kembali('pelanggan', $id
            ? 'Data pelanggan berhasil diperbarui.'
            : 'Pelanggan ' . $f['kode_pelanggan'] . ' - ' . $f['nama_pelanggan'] . ' berhasil ditambahkan.');
    }

    public function savePelanggan()
    {
        return $this->simpanPelanggan(null);
    }

    public function updatePelanggan()
    {
        return $this->simpanPelanggan((int) $this->request->getPost('id'));
    }

    public function deletePelanggan($id)
    {
        $lama = $this->pelangganModel->find((int) $id);
        if ($lama) {
            $this->pelangganModel->delete((int) $id);
            $this->sinkronPort((int) $lama['odp_id']);
        }

        return $this->kembali('pelanggan', 'Data pelanggan berhasil dihapus.');
    }
}
