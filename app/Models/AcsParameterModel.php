<?php

namespace App\Models;

use Config\Database;

// Parameter Master (ACS JWI Group), mengikuti Parameter Master speednet_acs: daftar path TR-069 per model (d_acs_parameter)
// digabung dengan path yang benar-benar terbaca dari ONT (d_acs_data). Hanya nama path, nilainya tidak ditampilkan.
class AcsParameterModel
{
    /** ONT terbaru per model yang dihitung, agar halaman tetap ringan saat ONT sudah ratusan. */
    public const MAKS_ONT = 100;

    /** Hasil hitung path dari ONT disimpan sekian detik. */
    private const CACHE = 300;

    /** @var \CodeIgniter\Database\BaseConnection */
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public static function siap(): bool
    {
        static $siap = null;

        return $siap ??= Database::connect()->tableExists('d_acs_parameter');
    }

    /** Model di master + model ONT yang pernah terhubung. */
    public function daftarModel(): array
    {
        $m = array_column($this->db->table('d_acs_parameter')->distinct()->select('model')->orderBy('model', 'ASC')->get()->getResultArray(), 'model');
        $d = array_column($this->db->table('d_acs_device')->distinct()->select('product_class')->where('product_class !=', '')->orderBy('product_class', 'ASC')->get()->getResultArray(), 'product_class');

        $hasil = [];
        foreach (array_merge($m, $d) as $x) {
            $hasil[mb_strtolower($x)] ??= $x;
        }

        return array_values($hasil);
    }

    /** Semua path master (untuk saran di form profil Provisioning). */
    public function semuaPath(): array
    {
        return array_column($this->db->table('d_acs_parameter')->distinct()->select('path')->get()->getResultArray(), 'path');
    }

    /**
     * Katalog satu model.
     *
     * @return array{ont: int, aktual: int, data: list<array{path: string, kelompok: string, ont: int, ditemukan: bool}>}
     */
    public function katalog(string $model): array
    {
        $master = array_column($this->db->table('d_acs_parameter')->select('path')->where('model', $model)->get()->getResultArray(), 'path');
        $aktual = $this->aktual($model);
        $semua  = array_unique(array_merge($master, array_map('strval', array_keys($aktual))));
        natcasesort($semua);

        $data = [];
        foreach ($semua as $path) {
            $data[] = ['path' => $path, 'kelompok' => self::kelompok($path), 'ont' => $aktual[$path] ?? 0, 'ditemukan' => isset($aktual[$path])];
        }

        return [
            'ont'    => $this->db->table('d_acs_device')->where('product_class', $model)->countAllResults(),
            'aktual' => count($aktual),
            'data'   => $data,
        ];
    }

    /** @return array<string, int> path => jumlah ONT yang memilikinya */
    private function aktual(string $model): array
    {
        if (! AcsModel::dataSiap()) {
            return [];
        }
        $kunci = 'acs_param_' . md5(mb_strtolower($model));
        $simpan = cache($kunci);
        if (is_array($simpan)) {
            return $simpan;
        }
        $hitung = [];
        $id     = array_column($this->db->table('d_acs_device')->select('id')->where('product_class', $model)
            ->orderBy('last_inform', 'DESC')->limit(self::MAKS_ONT)->get()->getResultArray(), 'id');
        foreach ($id as $x) { // satu ONT satu kueri, agar memori tidak membengkak
            foreach ($this->db->table('d_acs_data')->select('isi')->where('device_id', $x)->where('grup !=', 'ping')->get()->getResultArray() as $b) {
                $j = json_decode((string) $b['isi'], true);
                foreach (['p', 'r'] as $k) {
                    foreach (is_array($j[$k] ?? null) ? $j[$k] : [] as $path => $nilai) {
                        if (! str_ends_with((string) $path, '.')) {
                            $hitung[$path] = ($hitung[$path] ?? 0) + 1;
                        }
                    }
                }
            }
        }
        cache()->save($kunci, $hitung, self::CACHE);

        return $hitung;
    }

    private static function kelompok(string $path): string
    {
        $p = explode('.', $path);
        if (($p[1] ?? '') === 'LANDevice') {
            return ['WLANConfiguration' => 'WiFi', 'Hosts' => 'Client'][$p[3] ?? ''] ?? 'LAN';
        }

        return ['DeviceInfo' => 'Device Info', 'ManagementServer' => 'Management Server', 'WANDevice' => 'WAN',
            'Services' => 'Services', 'X_HW_Security' => 'Huawei Security', '' => 'Root'][$p[1] ?? ''] ?? $p[1];
    }
}
