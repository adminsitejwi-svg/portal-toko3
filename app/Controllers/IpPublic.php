<?php

namespace App\Controllers;

use App\Models\IpPublicJudulModel;
use App\Models\IpPublicSubjudulModel;
use App\Models\IpPublicSubnetModel;

// Halaman Data IP Public (menu Data Teknis NOC > Data IP Public). Data bertingkat, tambah / edit lewat pop up:
//   Judul     : Perusahaan (dropdown) + Judul (isi manual)
//   Sub Judul : Judul (dropdown) + Sub Judul
//   Subnet    : Judul & Sub Judul (dropdown, Perusahaan otomatis dari Judul) + Subnet + Subnet Description
// Tidak boleh dobel: judul per perusahaan, sub judul per judul, subnet di semua data.
class IpPublic extends BaseController
{
    public const PERUSAHAAN = [
        'PT JURAGAN WIFI INDONESIA',
        'PT ANAGRAM DATA TEKNOLOGI',
        'PT NORLEC TELEKOMUNIKASI INDONESIA',
        'PT GENESIS TELEKOMUNIKASI INDONESIA',
    ];

    protected $judulModel;
    protected $subjudulModel;
    protected $subnetModel;

    public function __construct()
    {
        $this->judulModel    = new IpPublicJudulModel();
        $this->subjudulModel = new IpPublicSubjudulModel();
        $this->subnetModel   = new IpPublicSubnetModel();
    }

    // Halaman utama (view IpPublic/index.php): tab Subnet, Judul, Sub Judul
    public function index()
    {
        $judul = $this->judulModel->orderBy('perusahaan', 'ASC')->orderBy('judul', 'ASC')->findAll();

        $subjudul = $this->subjudulModel
            ->select('md_ip_public_subjudul.*, j.judul, j.perusahaan')
            ->join('md_ip_public_judul j', 'j.id = md_ip_public_subjudul.judul_id', 'left')
            ->orderBy('j.perusahaan', 'ASC')->orderBy('j.judul', 'ASC')->orderBy('md_ip_public_subjudul.sub_judul', 'ASC')
            ->findAll();

        $subnet = $this->subnetModel
            ->select('d_ip_public_subnet.*, j.judul, j.perusahaan, s.sub_judul')
            ->join('md_ip_public_judul j', 'j.id = d_ip_public_subnet.judul_id', 'left')
            ->join('md_ip_public_subjudul s', 's.id = d_ip_public_subnet.subjudul_id', 'left')
            ->orderBy('j.perusahaan', 'ASC')->orderBy('j.judul', 'ASC')->orderBy('s.sub_judul', 'ASC')->orderBy('d_ip_public_subnet.subnet', 'ASC')
            ->findAll();

        // jumlah pemakaian (untuk info & larangan hapus)
        $pakaiJudul    = [];
        $pakaiSubjudul = [];
        foreach ($subjudul as $s) {
            $pakaiJudul[$s['judul_id']] = ($pakaiJudul[$s['judul_id']] ?? 0) + 1;
        }
        foreach ($subnet as &$n) {
            $pakaiJudul[$n['judul_id']] = ($pakaiJudul[$n['judul_id']] ?? 0) + 1;
            if ($n['subjudul_id']) {
                $pakaiSubjudul[$n['subjudul_id']] = ($pakaiSubjudul[$n['subjudul_id']] ?? 0) + 1;
            }
            // ukuran & range alamat (seperti kolom di phpIPAM)
            $n += self::infoSubnet($n['subnet']);
        }
        unset($n);

        return view('IpPublic/index', [
            'opsiPerusahaan' => self::PERUSAHAAN,
            'judul'          => $judul,
            'subjudul'       => $subjudul,
            'subnet'         => $subnet,
            'pakaiJudul'     => $pakaiJudul,
            'pakaiSubjudul'  => $pakaiSubjudul,
        ]);
    }

    private function teks(string $k): string
    {
        return trim(preg_replace('/\s+/', ' ', (string) $this->request->getPost($k)));
    }

    // Gagal: kembali ke halaman, buka lagi pop up (judul / subjudul / subnet) dengan isian sebelumnya
    private function gagal(string $popup, string $pesan)
    {
        return redirect()->to(site_url('IpPublic') . '?tab=' . $popup)
            ->withInput()
            ->with('popup', $popup)
            ->with('error', $pesan);
    }

    private function berhasil(string $tab, string $pesan)
    {
        return redirect()->to(site_url('IpPublic') . '?tab=' . $tab)->with('success', $pesan);
    }

    // Simpan (insert / update); kembalikan true bila berhasil (query yang ditolak database bisa melempar error atau false)
    private function simpan($model, array $data, ?int $id = null): bool
    {
        try {
            return ($id ? $model->update($id, $data) : $model->insert($data)) !== false;
        } catch (\Throwable $e) {
            return false;
        }
    }

    // ================= JUDUL =================

    private function validasiJudul(array $f, ?int $id = null): ?string
    {
        if (! in_array($f['perusahaan'], self::PERUSAHAAN, true)) {
            return 'Perusahaan wajib dipilih.';
        }
        if ($f['judul'] === '') {
            return 'Judul wajib diisi.';
        }
        if (mb_strlen($f['judul']) > 150) {
            return 'Judul maksimal 150 karakter.';
        }
        $q = $this->judulModel->where('perusahaan', $f['perusahaan'])->where('judul', $f['judul']);
        if ($id) {
            $q->where('id !=', $id);
        }
        if ($q->first()) {
            return 'Judul "' . $f['judul'] . '" sudah ada di ' . $f['perusahaan'] . '.';
        }

        return null;
    }

    public function saveJudul()
    {
        $f = ['perusahaan' => $this->teks('perusahaan'), 'judul' => $this->teks('judul')];

        if ($e = $this->validasiJudul($f)) {
            return $this->gagal('judul', $e);
        }
        if (! $this->simpan($this->judulModel, $f)) {
            return $this->gagal('judul', 'Judul gagal disimpan, kemungkinan sudah ada.');
        }

        return $this->berhasil('judul', 'Judul "' . $f['judul'] . '" berhasil ditambahkan.');
    }

    public function updateJudul()
    {
        $id = (int) $this->request->getPost('id');
        if (! $id || ! $this->judulModel->find($id)) {
            return $this->berhasil('judul', 'Judul tidak ditemukan.');
        }

        $f = ['perusahaan' => $this->teks('perusahaan'), 'judul' => $this->teks('judul')];

        if ($e = $this->validasiJudul($f, $id)) {
            return $this->gagal('judul', $e);
        }
        if (! $this->simpan($this->judulModel, $f, $id)) {
            return $this->gagal('judul', 'Judul gagal diperbarui, kemungkinan sudah ada.');
        }

        return $this->berhasil('judul', 'Judul "' . $f['judul'] . '" berhasil diperbarui.');
    }

    public function deleteJudul($id)
    {
        $id = (int) $id;
        if ($this->subjudulModel->where('judul_id', $id)->countAllResults() || $this->subnetModel->where('judul_id', $id)->countAllResults()) {
            return redirect()->to(site_url('IpPublic') . '?tab=judul')->with('error', 'Judul masih dipakai Sub Judul / Subnet, hapus data tersebut dulu.');
        }
        $this->judulModel->delete($id);

        return $this->berhasil('judul', 'Judul berhasil dihapus.');
    }

    // ================= SUB JUDUL =================

    private function validasiSubjudul(array $f, ?int $id = null): ?string
    {
        if (! $f['judul_id'] || ! $this->judulModel->find($f['judul_id'])) {
            return 'Judul wajib dipilih.';
        }
        if ($f['sub_judul'] === '') {
            return 'Sub Judul wajib diisi.';
        }
        if (mb_strlen($f['sub_judul']) > 150) {
            return 'Sub Judul maksimal 150 karakter.';
        }
        $q = $this->subjudulModel->where('judul_id', $f['judul_id'])->where('sub_judul', $f['sub_judul']);
        if ($id) {
            $q->where('id !=', $id);
        }
        if ($q->first()) {
            return 'Sub Judul "' . $f['sub_judul'] . '" sudah ada di judul tersebut.';
        }

        return null;
    }

    public function saveSubjudul()
    {
        $f = ['judul_id' => (int) $this->request->getPost('judul_id'), 'sub_judul' => $this->teks('sub_judul')];

        if ($e = $this->validasiSubjudul($f)) {
            return $this->gagal('subjudul', $e);
        }
        if (! $this->simpan($this->subjudulModel, $f)) {
            return $this->gagal('subjudul', 'Sub Judul gagal disimpan, kemungkinan sudah ada.');
        }

        return $this->berhasil('subjudul', 'Sub Judul "' . $f['sub_judul'] . '" berhasil ditambahkan.');
    }

    public function updateSubjudul()
    {
        $id  = (int) $this->request->getPost('id');
        $old = $id ? $this->subjudulModel->find($id) : null;
        if (! $old) {
            return $this->berhasil('subjudul', 'Sub Judul tidak ditemukan.');
        }

        $f = ['judul_id' => (int) $this->request->getPost('judul_id'), 'sub_judul' => $this->teks('sub_judul')];

        if ($e = $this->validasiSubjudul($f, $id)) {
            return $this->gagal('subjudul', $e);
        }
        if (! $this->simpan($this->subjudulModel, $f, $id)) {
            return $this->gagal('subjudul', 'Sub Judul gagal diperbarui, kemungkinan sudah ada.');
        }

        // Judul sub judul berpindah: subnet di bawahnya ikut pindah judul
        if ((int) $old['judul_id'] !== $f['judul_id']) {
            foreach ($this->subnetModel->where('subjudul_id', $id)->findAll() as $n) {
                $this->subnetModel->update($n['id'], ['judul_id' => $f['judul_id']]);
            }
        }

        return $this->berhasil('subjudul', 'Sub Judul "' . $f['sub_judul'] . '" berhasil diperbarui.');
    }

    public function deleteSubjudul($id)
    {
        $id = (int) $id;
        if ($this->subnetModel->where('subjudul_id', $id)->countAllResults()) {
            return redirect()->to(site_url('IpPublic') . '?tab=subjudul')->with('error', 'Sub Judul masih dipakai Subnet, hapus / pindahkan subnet tersebut dulu.');
        }
        $this->subjudulModel->delete($id);

        return $this->berhasil('subjudul', 'Sub Judul berhasil dihapus.');
    }

    // ================= SUBNET =================

    // Subnet IPv4 / IPv6 dengan prefix, contoh 103.10.20.0/29 atau 2001:db8::/48; kembalikan bentuk rapi atau null
    public static function rapikanSubnet(string $v): ?string
    {
        $v = str_replace(' ', '', $v);
        if (! preg_match('#^([0-9a-fA-F:.]+)/(\d{1,3})$#', $v, $m)) {
            return null;
        }
        [, $ip, $prefix] = $m;
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return (int) $prefix <= 32 ? $ip . '/' . (int) $prefix : null;
        }
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return (int) $prefix <= 128 ? strtolower($ip) . '/' . (int) $prefix : null;
        }

        return null;
    }

    /**
     * Ukuran & range subnet: ['jumlah_ip' => total alamat (angka, IPv4) / teks (IPv6), 'awal' => alamat network, 'akhir' => broadcast].
     * Bila subnet tidak bisa dihitung, nilai berisi '-'.
     */
    public static function infoSubnet(string $cidr): array
    {
        if (! preg_match('#^([0-9.]+)/(\d{1,2})$#', $cidr, $m) || ! filter_var($m[1], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) || (int) $m[2] > 32) {
            if (preg_match('#^[0-9a-f:]+/(\d{1,3})$#i', $cidr, $v6)) {
                return ['jumlah_ip' => '2^' . (128 - (int) $v6[1]), 'awal' => '-', 'akhir' => '-'];
            }

            return ['jumlah_ip' => '-', 'awal' => '-', 'akhir' => '-'];
        }

        $prefix = (int) $m[2];
        $ip     = ip2long($m[1]);
        $mask   = $prefix === 0 ? 0 : (~0 << (32 - $prefix)) & 0xFFFFFFFF;
        $awal   = $ip & $mask;
        $akhir  = $awal | (~$mask & 0xFFFFFFFF);

        return ['jumlah_ip' => 2 ** (32 - $prefix), 'awal' => long2ip($awal), 'akhir' => long2ip($akhir)];
    }

    private function fieldSubnet(): array
    {
        $subjudulId = (int) $this->request->getPost('subjudul_id');

        return [
            'judul_id'    => (int) $this->request->getPost('judul_id'),
            'subjudul_id' => $subjudulId ?: null,
            'subnet'      => self::rapikanSubnet((string) $this->request->getPost('subnet')) ?? trim((string) $this->request->getPost('subnet')),
            'deskripsi'   => trim(preg_replace("/\r\n?/", "\n", (string) $this->request->getPost('deskripsi'))) ?: null,
        ];
    }

    private function validasiSubnet(array &$f, ?int $id = null): ?string
    {
        $judul = $f['judul_id'] ? $this->judulModel->find($f['judul_id']) : null;
        if (! $judul) {
            return 'Judul wajib dipilih.';
        }
        if ($f['subjudul_id']) {
            $sub = $this->subjudulModel->find($f['subjudul_id']);
            if (! $sub) {
                return 'Sub Judul tidak ditemukan.';
            }
            // Judul selalu mengikuti Sub Judul yang dipilih
            $f['judul_id'] = (int) $sub['judul_id'];
        }
        if ($f['subnet'] === '') {
            return 'Subnet wajib diisi.';
        }
        if (self::rapikanSubnet($f['subnet']) === null) {
            return 'Subnet tidak valid. Gunakan format IP/prefix, contoh 103.10.20.0/29.';
        }
        $q = $this->subnetModel->where('subnet', $f['subnet']);
        if ($id) {
            $q->where('id !=', $id);
        }
        if ($q->first()) {
            return 'Subnet ' . $f['subnet'] . ' sudah terdaftar.';
        }

        return null;
    }

    public function saveSubnet()
    {
        $f = $this->fieldSubnet();

        if ($e = $this->validasiSubnet($f)) {
            return $this->gagal('subnet', $e);
        }
        if (! $this->simpan($this->subnetModel, $f)) {
            return $this->gagal('subnet', 'Subnet gagal disimpan, kemungkinan sudah terdaftar.');
        }

        return $this->berhasil('subnet', 'Subnet ' . $f['subnet'] . ' berhasil ditambahkan.');
    }

    public function updateSubnet()
    {
        $id = (int) $this->request->getPost('id');
        if (! $id || ! $this->subnetModel->find($id)) {
            return $this->berhasil('subnet', 'Subnet tidak ditemukan.');
        }

        $f = $this->fieldSubnet();

        if ($e = $this->validasiSubnet($f, $id)) {
            return $this->gagal('subnet', $e);
        }
        if (! $this->simpan($this->subnetModel, $f, $id)) {
            return $this->gagal('subnet', 'Subnet gagal diperbarui, kemungkinan sudah terdaftar.');
        }

        return $this->berhasil('subnet', 'Subnet ' . $f['subnet'] . ' berhasil diperbarui.');
    }

    public function deleteSubnet($id)
    {
        $this->subnetModel->delete((int) $id);

        return $this->berhasil('subnet', 'Subnet berhasil dihapus.');
    }
}
