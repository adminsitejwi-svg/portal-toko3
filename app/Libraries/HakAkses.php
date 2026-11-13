<?php

namespace App\Libraries;

use Config\HakAkses as HakAksesConfig;

/**
 * Cek hak akses user yang sedang login.
 *
 * Sumber data:
 *  - m_role         : daftar role (is_admin = 1 -> bisa semua halaman)
 *  - m_role_akses   : halaman yang boleh dibuka per role
 *  - m_user_akses   : halaman khusus per akun (dipakai bila login.akses_khusus = 1)
 *  - login.role_id  : role tiap akun
 *
 * Kalau SQL database/m_role_hak_akses.sql belum dijalankan, semua halaman tetap terbuka
 * seperti sebelumnya supaya aplikasi tidak terkunci.
 *
 * Pakai: \App\Libraries\HakAkses::instance()->bisa('Alfamidi')
 */
class HakAkses
{
    private static ?self $instance = null;

    private HakAksesConfig $config;
    private bool $tersedia = false;
    private bool $admin = false;
    private ?array $role = null;

    /** @var array<string, true> kunci halaman (huruf kecil) yang boleh */
    private array $boleh = [];

    /** @var array<string, string> huruf kecil => kunci asli */
    private array $petaKunci = [];

    public static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /** Reset cache (dipakai setelah login / setelah admin mengubah akses). */
    public static function reset(): void
    {
        self::$instance = null;
    }

    private function __construct()
    {
        $this->config = config(HakAksesConfig::class);

        foreach (array_keys($this->config->semuaHalaman()) as $kunci) {
            $this->petaKunci[strtolower($kunci)] = $kunci;
        }

        $userId = session()->get('user_id');
        if (!$userId) {
            return;
        }

        $db = \Config\Database::connect();

        try {
            if (!$db->tableExists('m_role') || !$db->fieldExists('role_id', 'login')) {
                return; // SQL belum dijalankan -> mode lama (semua terbuka)
            }

            $this->tersedia = true;

            $user = $db->table('login')->select('id, role_id, akses_khusus')
                ->where('id', $userId)->get()->getRowArray();
            if (!$user) {
                return;
            }

            if ($user['role_id']) {
                $this->role = $db->table('m_role')->where('id', $user['role_id'])->get()->getRowArray();
            }

            if ($this->role && (int) $this->role['is_admin'] === 1) {
                $this->admin = true;

                return;
            }

            if ((int) ($user['akses_khusus'] ?? 0) === 1) {
                $rows = $db->table('m_user_akses')->select('halaman')
                    ->where('user_id', $user['id'])->get()->getResultArray();
            } elseif ($this->role) {
                $rows = $db->table('m_role_akses')->select('halaman')
                    ->where('role_id', $this->role['id'])->get()->getResultArray();
            } else {
                $rows = [];
            }

            foreach ($rows as $r) {
                $this->boleh[strtolower($r['halaman'])] = true;
            }
        } catch (\Throwable $e) {
            log_message('error', 'HakAkses: ' . $e->getMessage());
            $this->tersedia = false;
        }
    }

    /** true bila tabel hak akses sudah ada di database */
    public function tersedia(): bool
    {
        return $this->tersedia;
    }

    /** Administrator (atau mode lama sebelum SQL dijalankan) */
    public function isAdmin(): bool
    {
        return $this->admin || !$this->tersedia;
    }

    public function namaRole(): string
    {
        return $this->role['nama'] ?? ($this->tersedia ? 'Tanpa Role' : 'Administrator');
    }

    /**
     * Boleh membuka path ini? $path = uri tanpa base, contoh "Alfamidi/edit/3".
     */
    public function bolehPath(string $path): bool
    {
        $path = trim($path, '/');
        $lower = strtolower($path);
        $segmen = strtolower(explode('/', $path)[0] ?? '');

        if ($segmen === '') {
            return true;
        }

        // khusus admin (cek dulu supaya settings/delete tidak lolos lewat "settings")
        foreach ($this->config->khususAdmin as $k) {
            $k = strtolower($k);
            if ($lower === $k || str_starts_with($lower, $k . '/')) {
                return $this->isAdmin();
            }
        }

        if ($this->isAdmin()) {
            return true;
        }

        foreach ($this->config->selaluBoleh as $k) {
            if ($segmen === strtolower($k)) {
                return true;
            }
        }

        return isset($this->boleh[$segmen]);
    }

    /** Boleh membuka halaman dengan kunci ini? */
    public function bisa(string $kunci): bool
    {
        return $this->bolehPath($kunci);
    }

    /**
     * Kunci halaman (huruf sesuai config) yang TIDAK boleh dibuka — dipakai sidebar.
     *
     * @return list<string>
     */
    public function halamanTerlarang(): array
    {
        if ($this->isAdmin()) {
            return [];
        }

        $out = [];
        foreach ($this->petaKunci as $lower => $kunci) {
            if (!isset($this->boleh[$lower])) {
                $out[] = $kunci;
            }
        }

        return $out;
    }

    /** Halaman pertama yang boleh dibuka (tujuan setelah login / saat akses ditolak). */
    public function halamanAwal(): string
    {
        if ($this->bisa('dashboard-manager')) {
            return 'dashboard-manager';
        }

        foreach ($this->petaKunci as $lower => $kunci) {
            if (isset($this->boleh[$lower])) {
                return $kunci;
            }
        }

        return 'Profile';
    }
}
