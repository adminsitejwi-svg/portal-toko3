<?php

namespace App\Controllers;

use App\Models\IpPublicHostModel;
use App\Models\IpPublicJudulModel;
use App\Models\IpPublicSubjudulModel;
use App\Models\IpPublicSubnetModel;

// Halaman Data IP Public (menu Data Teknis NOC > Data IP Public). Data bertingkat, tambah / edit lewat pop up:
//   Judul     : Perusahaan (dropdown) + Judul (isi manual)
//   Sub Judul : Judul (dropdown) + Induk Sub Judul (opsional, sub judul di dalam sub judul) + Sub Judul
//   Subnet    : Judul & Sub Judul (dropdown, Perusahaan otomatis dari Judul) + Subnet + Subnet Description
//   Hostname  : Subnet (dropdown) + IP Address (harus di dalam range subnet) + Hostname + Description
// Tidak boleh dobel: judul per perusahaan, sub judul per induk, subnet di semua data, IP per subnet.
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
    protected $hostModel;

    public function __construct()
    {
        $this->judulModel    = new IpPublicJudulModel();
        $this->subjudulModel = new IpPublicSubjudulModel();
        $this->subnetModel   = new IpPublicSubnetModel();
        $this->hostModel     = new IpPublicHostModel();
    }

    /** true bila SQL update 2026-11-20 (parent_id + d_ip_public_host) sudah dijalankan */
    private function bertingkatSiap(): bool
    {
        static $siap = null;
        if ($siap === null) {
            $db   = \Config\Database::connect();
            $siap = $db->fieldExists('parent_id', 'md_ip_public_subjudul') && $db->tableExists('d_ip_public_host');
        }

        return $siap;
    }

    /**
     * Jalur lengkap sub judul ("Induk › Anak") per id.
     *
     * @param array<int, array> $subById
     */
    private static function jalurSubjudul(array $subById, int $id): string
    {
        $nama  = [];
        $lihat = [];
        while ($id && isset($subById[$id]) && !isset($lihat[$id])) {
            $lihat[$id] = true;
            array_unshift($nama, $subById[$id]['sub_judul']);
            $id = (int) ($subById[$id]['parent_id'] ?? 0);
        }

        return implode(' › ', $nama);
    }

    /** id sub judul ini + semua turunannya */
    private function turunanSubjudul(int $id): array
    {
        $semua = [$id];
        $antri = [$id];
        while ($antri) {
            $anak = array_map('intval', array_column(
                $this->subjudulModel->select('id')->whereIn('parent_id', $antri)->findAll(),
                'id'
            ));
            $anak  = array_values(array_diff($anak, $semua));
            $semua = array_merge($semua, $anak);
            $antri = $anak;
        }

        return $semua;
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

        $siap = $this->bertingkatSiap();

        // jalur lengkap sub judul bertingkat ("Induk › Anak")
        $subById = [];
        foreach ($subjudul as $s) {
            $subById[(int) $s['id']] = $s;
        }
        foreach ($subjudul as &$s) {
            $s['parent_id'] = $siap && isset($subById[(int) ($s['parent_id'] ?? 0)]) ? (int) $s['parent_id'] : null;
            $s['jalur']     = self::jalurSubjudul($subById, (int) $s['id']);
        }
        unset($s);

        // Data Hostname, diurutkan per subnet lalu alamat IP
        $host = [];
        if ($siap) {
            $host = $this->hostModel->findAll();
            usort($host, static fn ($a, $b) => [(int) $a['subnet_id'], @inet_pton($a['ip_address']) ?: $a['ip_address']]
                <=> [(int) $b['subnet_id'], @inet_pton($b['ip_address']) ?: $b['ip_address']]);
        }
        $jmlHost = [];
        foreach ($host as $h) {
            $jmlHost[(int) $h['subnet_id']] = ($jmlHost[(int) $h['subnet_id']] ?? 0) + 1;
        }

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
                // tampilkan jalur lengkap sub judul di tabel & detail
                $n['sub_judul'] = self::jalurSubjudul($subById, (int) $n['subjudul_id']) ?: $n['sub_judul'];
            }
            // ukuran & range alamat (seperti kolom di phpIPAM)
            $n += self::infoSubnet($n['subnet']);
            $n['jml_host'] = $jmlHost[(int) $n['id']] ?? 0;
        }
        unset($n);

        return view('IpPublic/index', [
            'opsiPerusahaan' => self::PERUSAHAAN,
            'judul'          => $judul,
            'subjudul'       => $subjudul,
            'subnet'         => $subnet,
            'host'           => $host,
            'pakaiJudul'     => $pakaiJudul,
            'pakaiSubjudul'  => $pakaiSubjudul,
            'bertingkatSiap' => $siap,
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

    private function fieldSubjudul(): array
    {
        $f = ['judul_id' => (int) $this->request->getPost('judul_id'), 'sub_judul' => $this->teks('sub_judul')];
        if ($this->bertingkatSiap()) {
            $f['parent_id'] = (int) $this->request->getPost('parent_id') ?: null;
        }

        return $f;
    }

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

        // Induk Sub Judul (sub judul di dalam sub judul): harus di Judul yang sama & bukan dirinya / turunannya
        $parentId = $f['parent_id'] ?? null;
        if ($parentId) {
            $induk = $this->subjudulModel->find($parentId);
            if (! $induk) {
                return 'Induk Sub Judul tidak ditemukan.';
            }
            if ((int) $induk['judul_id'] !== $f['judul_id']) {
                return 'Induk Sub Judul harus berada di Judul yang sama.';
            }
            if ($id && in_array((int) $parentId, $this->turunanSubjudul($id), true)) {
                return 'Induk Sub Judul tidak boleh sub judul itu sendiri atau turunannya.';
            }
        }

        // tidak boleh dobel di induk yang sama
        $q = $this->subjudulModel->where('judul_id', $f['judul_id'])->where('sub_judul', $f['sub_judul']);
        if ($this->bertingkatSiap()) {
            $parentId ? $q->where('parent_id', $parentId) : $q->where('parent_id', null);
        }
        if ($id) {
            $q->where('id !=', $id);
        }
        if ($q->first()) {
            return 'Sub Judul "' . $f['sub_judul'] . '" sudah ada di ' . ($parentId ? 'induk sub judul' : 'judul') . ' tersebut.';
        }

        return null;
    }

    public function saveSubjudul()
    {
        $f = $this->fieldSubjudul();

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

        $f = $this->fieldSubjudul();

        if ($e = $this->validasiSubjudul($f, $id)) {
            return $this->gagal('subjudul', $e);
        }
        if (! $this->simpan($this->subjudulModel, $f, $id)) {
            return $this->gagal('subjudul', 'Sub Judul gagal diperbarui, kemungkinan sudah ada.');
        }

        // Judul sub judul berpindah: semua sub judul turunannya & subnet di bawahnya ikut pindah judul
        if ((int) $old['judul_id'] !== $f['judul_id']) {
            $turunan = $this->bertingkatSiap() ? $this->turunanSubjudul($id) : [$id];
            foreach ($turunan as $sid) {
                if ($sid !== $id) {
                    $this->subjudulModel->update($sid, ['judul_id' => $f['judul_id']]);
                }
            }
            foreach ($this->subnetModel->whereIn('subjudul_id', $turunan)->findAll() as $n) {
                $this->subnetModel->update($n['id'], ['judul_id' => $f['judul_id']]);
            }
        }

        return $this->berhasil('subjudul', 'Sub Judul "' . $f['sub_judul'] . '" berhasil diperbarui.');
    }

    public function deleteSubjudul($id)
    {
        $id = (int) $id;
        if ($this->bertingkatSiap() && $this->subjudulModel->where('parent_id', $id)->countAllResults()) {
            return redirect()->to(site_url('IpPublic') . '?tab=subjudul')->with('error', 'Sub Judul masih punya Sub Judul di dalamnya, hapus / pindahkan dulu.');
        }
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
        // subnet diubah: hostname yang sudah ada harus tetap di dalam range subnet baru
        if ($id && $this->bertingkatSiap()) {
            $luar = array_filter(
                $this->hostModel->where('subnet_id', $id)->findAll(),
                static fn ($h) => ! self::ipDalamSubnet($h['ip_address'], $f['subnet'])
            );
            if ($luar) {
                return count($luar) . ' hostname (contoh ' . reset($luar)['ip_address'] . ') berada di luar subnet ' . $f['subnet'] . '. Pindahkan / hapus hostname tersebut dulu.';
            }
        }

        return null;
    }

    /** IP (v4 / v6) berada di dalam subnet CIDR? */
    public static function ipDalamSubnet(string $ip, string $cidr): bool
    {
        if (! preg_match('#^([0-9a-fA-F:.]+)/(\d{1,3})$#', $cidr, $m)) {
            return false;
        }
        $a = @inet_pton($ip);
        $b = @inet_pton($m[1]);
        if ($a === false || $b === false || strlen($a) !== strlen($b)) {
            return false;
        }
        $prefix = (int) $m[2];
        if ($prefix > strlen($a) * 8) {
            return false;
        }
        $penuh = intdiv($prefix, 8);
        $sisa  = $prefix % 8;
        if (strncmp($a, $b, $penuh) !== 0) {
            return false;
        }
        if ($sisa === 0) {
            return true;
        }
        $mask = (0xFF << (8 - $sisa)) & 0xFF;

        return (ord($a[$penuh]) & $mask) === (ord($b[$penuh]) & $mask);
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
        $id = (int) $id;
        if ($this->bertingkatSiap() && ($n = $this->hostModel->where('subnet_id', $id)->countAllResults())) {
            return redirect()->to(site_url('IpPublic') . '?subnet=' . $id)->with('error', 'Subnet masih punya ' . $n . ' hostname, hapus hostname tersebut dulu.');
        }
        $this->subnetModel->delete($id);

        return $this->berhasil('subnet', 'Subnet berhasil dihapus.');
    }

    // ================= HOSTNAME (di dalam subnet) =================

    private function fieldHost(): array
    {
        $ip = strtolower(str_replace(' ', '', (string) $this->request->getPost('ip_address')));

        return [
            'subnet_id'  => (int) $this->request->getPost('subnet_id'),
            'ip_address' => $ip,
            'hostname'   => $this->teks('hostname'),
            'deskripsi'  => trim(preg_replace("/\r\n?/", "\n", (string) $this->request->getPost('deskripsi'))) ?: null,
        ];
    }

    private function validasiHost(array &$f, ?int $id = null): ?string
    {
        $subnet = $f['subnet_id'] ? $this->subnetModel->find($f['subnet_id']) : null;
        if (! $subnet) {
            return 'Subnet wajib dipilih.';
        }
        if ($f['ip_address'] === '') {
            return 'IP Address wajib diisi.';
        }
        if (! filter_var($f['ip_address'], FILTER_VALIDATE_IP)) {
            return 'IP Address tidak valid, contoh 103.10.20.2.';
        }
        // rapikan penulisan IPv6
        $f['ip_address'] = inet_ntop(inet_pton($f['ip_address']));
        if (! self::ipDalamSubnet($f['ip_address'], $subnet['subnet'])) {
            return 'IP ' . $f['ip_address'] . ' tidak berada di dalam subnet ' . $subnet['subnet'] . '.';
        }
        // IPv4 /0 - /30: alamat network & broadcast tidak bisa dipakai host
        $info = self::infoSubnet($subnet['subnet']);
        $pfx  = (int) substr(strrchr($subnet['subnet'], '/'), 1);
        if ($info['awal'] !== '-' && $pfx <= 30 && in_array($f['ip_address'], [$info['awal'], $info['akhir']], true)) {
            return 'IP ' . $f['ip_address'] . ' adalah alamat ' . ($f['ip_address'] === $info['awal'] ? 'network' : 'broadcast') . ' subnet, pilih IP lain.';
        }
        if ($f['hostname'] === '') {
            return 'Hostname wajib diisi.';
        }
        if (mb_strlen($f['hostname']) > 150) {
            return 'Hostname maksimal 150 karakter.';
        }
        $q = $this->hostModel->where('subnet_id', $f['subnet_id'])->where('ip_address', $f['ip_address']);
        if ($id) {
            $q->where('id !=', $id);
        }
        if ($q->first()) {
            return 'IP ' . $f['ip_address'] . ' sudah dipakai hostname lain di subnet ini.';
        }

        return null;
    }

    // berhasil / gagal: kembali ke halaman dengan subnet tersebut terpilih
    private function kembaliHost(int $subnetId, string $jenis, string $pesan)
    {
        $r = redirect()->to(site_url('IpPublic') . '?subnet=' . $subnetId)->with($jenis, $pesan);

        return $jenis === 'error' ? $r->withInput()->with('popup', 'host') : $r;
    }

    public function saveHost()
    {
        if (! $this->bertingkatSiap()) {
            return $this->berhasil('subnet', 'Tabel hostname belum ada. Jalankan SQL update di database/d_ip_public.sql dulu.');
        }
        $f = $this->fieldHost();

        if ($e = $this->validasiHost($f)) {
            return $this->kembaliHost($f['subnet_id'], 'error', $e);
        }
        if (! $this->simpan($this->hostModel, $f)) {
            return $this->kembaliHost($f['subnet_id'], 'error', 'Hostname gagal disimpan, kemungkinan IP sudah dipakai.');
        }

        return $this->kembaliHost($f['subnet_id'], 'success', 'Hostname "' . $f['hostname'] . '" (' . $f['ip_address'] . ') berhasil ditambahkan.');
    }

    public function updateHost()
    {
        if (! $this->bertingkatSiap()) {
            return $this->berhasil('subnet', 'Tabel hostname belum ada. Jalankan SQL update di database/d_ip_public.sql dulu.');
        }
        $id  = (int) $this->request->getPost('id');
        $old = $id ? $this->hostModel->find($id) : null;
        if (! $old) {
            return $this->berhasil('subnet', 'Hostname tidak ditemukan.');
        }

        $f = $this->fieldHost();

        if ($e = $this->validasiHost($f, $id)) {
            return $this->kembaliHost($f['subnet_id'] ?: (int) $old['subnet_id'], 'error', $e);
        }
        if (! $this->simpan($this->hostModel, $f, $id)) {
            return $this->kembaliHost($f['subnet_id'], 'error', 'Hostname gagal diperbarui, kemungkinan IP sudah dipakai.');
        }

        return $this->kembaliHost($f['subnet_id'], 'success', 'Hostname "' . $f['hostname'] . '" berhasil diperbarui.');
    }

    public function deleteHost($id)
    {
        $h = $this->bertingkatSiap() ? $this->hostModel->find((int) $id) : null;
        if (! $h) {
            return $this->berhasil('subnet', 'Hostname tidak ditemukan.');
        }
        $this->hostModel->delete((int) $id);

        return $this->kembaliHost((int) $h['subnet_id'], 'success', 'Hostname "' . $h['hostname'] . '" berhasil dihapus.');
    }
}
