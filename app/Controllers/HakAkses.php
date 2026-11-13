<?php

namespace App\Controllers;

use App\Libraries\HakAkses as Akses;
use Config\HakAkses as HakAksesConfig;

/**
 * Halaman Informasi > Hak Akses (khusus Administrator, dijaga AuthFilter).
 *
 * Tab Role : buat/ubah/hapus role + pilih halaman yang boleh dibuka role tsb.
 * Tab Akun : pilih role tiap akun; bisa juga "akses khusus" (pilih halaman per akun).
 * Bagian Informasi (Profile, Pengguna, Change Log) selalu terbuka untuk semua role.
 */
class HakAkses extends BaseController
{
    private HakAksesConfig $cfg;

    public function __construct()
    {
        $this->cfg = config(HakAksesConfig::class);
    }

    private function db()
    {
        return \Config\Database::connect();
    }

    public function index()
    {
        $akses = Akses::instance();
        $data  = [
            'tersedia' => $akses->tersedia(),
            'grup'     => $this->cfg->grup,
            'roles'    => [],
            'akun'     => [],
        ];

        if ($akses->tersedia()) {
            $db = $this->db();

            $roles = $db->table('m_role')->orderBy('is_admin', 'DESC')->orderBy('nama', 'ASC')->get()->getResultArray();

            $halamanRole = [];
            foreach ($db->table('m_role_akses')->get()->getResultArray() as $r) {
                $halamanRole[$r['role_id']][] = $r['halaman'];
            }

            $jumlahAkun = [];
            foreach ($db->table('login')->select('role_id, COUNT(*) AS n')->groupBy('role_id')->get()->getResultArray() as $r) {
                $jumlahAkun[(int) $r['role_id']] = (int) $r['n'];
            }

            foreach ($roles as &$r) {
                $r['halaman']     = $this->rapikan($halamanRole[$r['id']] ?? []);
                $r['jumlah_akun'] = $jumlahAkun[(int) $r['id']] ?? 0;
            }
            unset($r);

            $halamanUser = [];
            foreach ($db->table('m_user_akses')->get()->getResultArray() as $r) {
                $halamanUser[$r['user_id']][] = $r['halaman'];
            }

            $akun = $db->table('login')
                ->select('login.id, login.username, login.role_id, login.akses_khusus, m_role.nama AS role_nama, m_role.is_admin')
                ->join('m_role', 'm_role.id = login.role_id', 'left')
                ->orderBy('login.username', 'ASC')->get()->getResultArray();

            foreach ($akun as &$a) {
                $a['halaman'] = $this->rapikan($halamanUser[$a['id']] ?? []);
            }
            unset($a);

            $data['roles'] = $roles;
            $data['akun']  = $akun;
        }

        return view('HakAkses/index', $data);
    }

    /* ======================= ROLE ======================= */

    public function saveRole()
    {
        if (!Akses::instance()->tersedia()) {
            return $this->belumSiap();
        }

        $db   = $this->db();
        $id   = (int) $this->request->getPost('id');
        $nama = trim((string) $this->request->getPost('nama'));
        $ket  = trim((string) $this->request->getPost('keterangan'));

        if ($nama === '') {
            return redirect()->to(site_url('HakAkses'))->with('error', 'Nama role wajib diisi.');
        }

        $dobel = $db->table('m_role')->where('nama', $nama)->where('id !=', $id)->countAllResults();
        if ($dobel) {
            return redirect()->to(site_url('HakAkses'))->with('error', 'Role "' . $nama . '" sudah ada.');
        }

        $lama = $id ? $db->table('m_role')->where('id', $id)->get()->getRowArray() : null;
        if ($id && !$lama) {
            return redirect()->to(site_url('HakAkses'))->with('error', 'Role tidak ditemukan.');
        }

        $db->transStart();

        if ($lama) {
            $db->table('m_role')->where('id', $id)->update([
                'nama' => $nama, 'keterangan' => $ket ?: null, 'updated_at' => date('Y-m-d H:i:s'),
            ]);
        } else {
            $db->table('m_role')->insert([
                'nama' => $nama, 'keterangan' => $ket ?: null, 'is_admin' => 0, 'created_at' => date('Y-m-d H:i:s'),
            ]);
            $id = (int) $db->insertID();
        }

        // role Administrator selalu bisa semua halaman -> daftar halaman tidak dipakai
        if (!$lama || (int) $lama['is_admin'] !== 1) {
            $this->simpanHalaman('m_role_akses', 'role_id', $id, $this->halamanDariPost());
        }

        $db->transComplete();

        return redirect()->to(site_url('HakAkses') . '#role')
            ->with('success', 'Role "' . $nama . '" berhasil disimpan.');
    }

    public function deleteRole($id)
    {
        if (!Akses::instance()->tersedia()) {
            return $this->belumSiap();
        }

        $db   = $this->db();
        $role = $db->table('m_role')->where('id', (int) $id)->get()->getRowArray();

        if (!$role) {
            return redirect()->to(site_url('HakAkses'))->with('error', 'Role tidak ditemukan.');
        }
        if ((int) $role['is_admin'] === 1) {
            return redirect()->to(site_url('HakAkses'))->with('error', 'Role Administrator tidak bisa dihapus.');
        }

        $dipakai = $db->table('login')->where('role_id', $role['id'])->countAllResults();
        if ($dipakai) {
            return redirect()->to(site_url('HakAkses'))
                ->with('error', 'Role "' . $role['nama'] . '" masih dipakai ' . $dipakai . ' akun. Pindahkan akunnya ke role lain dulu.');
        }

        $db->table('m_role_akses')->where('role_id', $role['id'])->delete();
        $db->table('m_role')->where('id', $role['id'])->delete();

        return redirect()->to(site_url('HakAkses') . '#role')
            ->with('success', 'Role "' . $role['nama'] . '" berhasil dihapus.');
    }

    /* ======================= AKUN ======================= */

    public function saveAkun()
    {
        if (!Akses::instance()->tersedia()) {
            return $this->belumSiap();
        }

        $db      = $this->db();
        $userId  = (int) $this->request->getPost('user_id');
        $roleId  = (int) $this->request->getPost('role_id');
        $khusus  = $this->request->getPost('akses_khusus') ? 1 : 0;

        $user = $db->table('login')->where('id', $userId)->get()->getRowArray();
        $role = $db->table('m_role')->where('id', $roleId)->get()->getRowArray();

        if (!$user || !$role) {
            return redirect()->to(site_url('HakAkses') . '#akun')->with('error', 'Akun atau role tidak ditemukan.');
        }

        // jangan sampai tidak ada Administrator sama sekali
        if ((int) $role['is_admin'] !== 1) {
            $admins = $db->table('login')->select('login.id')
                ->join('m_role', 'm_role.id = login.role_id')
                ->where('m_role.is_admin', 1)->get()->getResultArray();
            $ids = array_map('intval', array_column($admins, 'id'));

            if (in_array($userId, $ids, true) && count($ids) <= 1) {
                return redirect()->to(site_url('HakAkses') . '#akun')
                    ->with('error', 'Akun ini Administrator terakhir. Jadikan akun lain Administrator dulu.');
            }
        }

        if ((int) $role['is_admin'] === 1) {
            $khusus = 0; // Administrator selalu semua halaman
        }

        $db->transStart();
        $db->table('login')->where('id', $userId)->update(['role_id' => $roleId, 'akses_khusus' => $khusus]);
        $this->simpanHalaman('m_user_akses', 'user_id', $userId, $khusus ? $this->halamanDariPost() : []);
        $db->transComplete();

        return redirect()->to(site_url('HakAkses') . '#akun')
            ->with('success', 'Hak akses akun "' . $user['username'] . '" berhasil disimpan.');
    }

    /* ======================= helper ======================= */

    /** halaman[] dari form, hanya kunci yang terdaftar di Config\HakAkses */
    private function halamanDariPost(): array
    {
        $post = (array) ($this->request->getPost('halaman') ?? []);

        return $this->rapikan($post);
    }

    /** buang kunci yang tidak dikenal + samakan huruf besar/kecil dengan config */
    private function rapikan(array $daftar): array
    {
        $peta = [];
        foreach (array_keys($this->cfg->semuaHalaman()) as $k) {
            $peta[strtolower($k)] = $k;
        }

        $out = [];
        foreach ($daftar as $h) {
            $h = strtolower(trim((string) $h));
            if (isset($peta[$h])) {
                $out[$peta[$h]] = true;
            }
        }

        return array_keys($out);
    }

    private function simpanHalaman(string $tabel, string $kolom, int $id, array $halaman): void
    {
        $db = $this->db();
        $db->table($tabel)->where($kolom, $id)->delete();

        if ($halaman) {
            $db->table($tabel)->insertBatch(array_map(
                static fn ($h) => [$kolom => $id, 'halaman' => $h],
                $halaman
            ));
        }
    }

    private function belumSiap()
    {
        return redirect()->to(site_url('HakAkses'))
            ->with('error', 'Tabel hak akses belum ada. Jalankan database/m_role_hak_akses.sql dulu.');
    }
}
