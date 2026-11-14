<?php

namespace App\Models;

/**
 * Surat Tugas (menu Activation > Surat Tugas), struktur dari "17 SPK NCIX KARET_OCT.pdf".
 * Perusahaan & logo sama dengan RFO (RFOModel::PERUSAHAAN).
 */
class SuratTugasModel extends BaseModel
{
    protected $table      = 'd_surat_tugas';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nomor',
        'perusahaan',
        'logo',
        'tanggal_surat',
        'mitra',
        'kegiatan',
        'tanggal_mulai',
        'tanggal_selesai',
        'keperluan',
        'teknisi',
        'lokasi',
        'perangkat',
        'penutup',
        'ttd_nama',
        'ttd_jabatan',
        'ttd',
        'dibuat_oleh',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Kode perusahaan di nomor surat: {tanggal}/{kode}.{bulan romawi}/{tahun},
     * contoh 14/JWI.XI/2026 (tanggal & bulan mengikuti tanggal surat).
     */
    public const KODE_NOMOR = [
        'PT JURAGAN WIFI INDONESIA'          => 'JWI',
        'PT NORLEC TELEKOMUNIKASI INDONESIA' => 'NTI',
        'PT GENEZIS MITRA TECHNOLOGY'        => 'GMT',
        'PT RTIGA GLOBAL MEDIA'              => 'RGM',
    ];

    public const ROMAWI = [1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];

    /** Alamat kantor di kaki surat, sesuai perusahaan. */
    public const ALAMAT = [
        'PT JURAGAN WIFI INDONESIA' => [
            'PT Juragan Wifi Indonesia',
            'MTH Square Lantai 2 No.0219',
            'Jl. MT Haryono Kav 10 Bidara Cina Jatinegara',
            'Jakarta Timur DKI Jakarta 13330',
        ],
        'PT NORLEC TELEKOMUNIKASI INDONESIA' => [
            'PT Norlec Telekomunikasi Indonesia',
            'MTH Square Lantai 2 No.0219',
            'Jl. MT Haryono Kav 10 Bidara Cina Jatinegara',
            'Jakarta Timur DKI Jakarta 13330',
        ],
        'PT GENEZIS MITRA TECHNOLOGY' => [
            'PT Genezis Mitra Technology',
            'MTH Square Lantai 1 No.0125',
            'Jl. MT Haryono Kav 10 Bidara Cina Jatinegara',
            'Jakarta Timur DKI Jakarta 13330',
        ],
        'PT RTIGA GLOBAL MEDIA' => [
            'PT Rtiga Global Media (RNET)',
            'MTH Square Lantai 3A No.12',
            'Jl. MT Haryono Kav 10 Bidara Cina Jatinegara',
            'Jakarta Timur DKI Jakarta 13330',
        ],
    ];

    /** Nilai awal form tambah (sesuai contoh surat). */
    public const BAWAAN = [
        'mitra'       => 'NeuCentrix',
        'kegiatan'    => 'Maintenance Server',
        'penutup'     => 'keperluan perizinan',
        'ttd_nama'    => 'Muhamad Aprianto',
        'ttd_jabatan' => 'Head of NOC',
    ];

    public const HARI  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    public const BULAN = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

    /** Folder file tanda tangan/stempel. */
    public static function folderTtd(): string
    {
        return WRITEPATH . 'uploads/surat_tugas/';
    }

    /**
     * Nomor surat dari tanggal surat & perusahaan: {tanggal}/{kode}.{bulan romawi}/{tahun}.
     * Contoh 14 November 2026, JWI -> 14/JWI.XI/2026.
     */
    public static function nomorSurat(string $perusahaan = 'PT JURAGAN WIFI INDONESIA', ?string $tanggal = null): string
    {
        $ts   = $tanggal ? strtotime($tanggal) : time();
        $kode = self::KODE_NOMOR[$perusahaan] ?? self::KODE_NOMOR['PT JURAGAN WIFI INDONESIA'];

        return date('j', $ts) . '/' . $kode . '.' . self::ROMAWI[(int) date('n', $ts)] . '/' . date('Y', $ts);
    }

    /**
     * Cek nomor otomatis dari form tambah: {tanggal}/{kode}.{bulan romawi}/{tahun}
     * dengan kode sesuai perusahaan. Nomor diisi browser dari tanggal di komputer (Windows).
     */
    public static function nomorValid(string $nomor, string $perusahaan): bool
    {
        if (! preg_match('#^(\d{1,2})/([A-Z]+)\.([IVX]+)/(\d{4})$#', $nomor, $m)) {
            return false;
        }

        return (int) $m[1] >= 1 && (int) $m[1] <= 31
            && $m[2] === (self::KODE_NOMOR[$perusahaan] ?? '')
            && in_array($m[3], self::ROMAWI, true);
    }

    /** Form edit: tanggal/bulan/tahun nomor lama tetap, hanya kode perusahaan yang diganti. */
    public static function gantiKodeNomor(string $nomor, string $perusahaan): string
    {
        $kode   = self::KODE_NOMOR[$perusahaan] ?? null;
        $bagian = explode('/', $nomor);
        if ($kode === null || count($bagian) !== 3) {
            return $nomor;
        }
        $titik     = strpos($bagian[1], '.');
        $bagian[1] = $kode . ($titik !== false ? substr($bagian[1], $titik) : '');

        return implode('/', $bagian);
    }

    /**
     * Penanda tangan per perusahaan: nama & jabatan dari surat terakhir perusahaan itu,
     * bila belum ada dikosongkan. Hasil: [perusahaan => ['nama' => ..., 'jabatan' => ...]].
     */
    public function penandaTangan(): array
    {
        $hasil = [];
        foreach (array_keys(self::KODE_NOMOR) as $pt) {
            $hasil[$pt] = ['nama' => '', 'jabatan' => ''];
        }

        $rows = $this->builder()->select('perusahaan, ttd_nama, ttd_jabatan')->orderBy('id', 'DESC')->get()->getResultArray();
        $sudah = [];
        foreach ($rows as $r) {
            if (isset($sudah[$r['perusahaan']])) {
                continue;
            }
            $sudah[$r['perusahaan']] = true;
            $hasil[$r['perusahaan']] = ['nama' => $r['ttd_nama'], 'jabatan' => $r['ttd_jabatan']];
        }

        return $hasil;
    }

    /** Daftar nama teknisi dari kolom JSON. */
    public static function teknisi(array $st): array
    {
        $isi = json_decode((string) ($st['teknisi'] ?? ''), true);

        return is_array($isi) ? array_values(array_filter(array_map('strval', $isi), static fn ($n) => trim($n) !== '')) : [];
    }

    /** Daftar perangkat {merk, jenis, sn} dari kolom JSON. */
    public static function perangkat(array $st): array
    {
        $isi = json_decode((string) ($st['perangkat'] ?? ''), true);

        return is_array($isi) ? array_values(array_filter($isi, 'is_array')) : [];
    }

    /** "Septiana dan Irfan" / "A, B dan C". */
    public static function gabungNama(array $nama): string
    {
        if (count($nama) <= 1) {
            return (string) ($nama[0] ?? '');
        }
        $akhir = array_pop($nama);

        return implode(', ', $nama) . ' dan ' . $akhir;
    }

    /** Tanggal Indonesia: 12 Oktober 2026. */
    public static function tanggalIndo(?string $tgl): string
    {
        if (! $tgl) {
            return '-';
        }
        $ts = strtotime($tgl);

        return date('j', $ts) . ' ' . self::BULAN[(int) date('n', $ts)] . ' ' . date('Y', $ts);
    }

    /** Hari pada surat: "Rabu" atau "Rabu - Jumat". */
    public static function hariTugas(array $st): string
    {
        $a = strtotime($st['tanggal_mulai']);
        $b = strtotime($st['tanggal_selesai']);
        $h = self::HARI[date('w', $a)];

        return $a === $b ? $h : $h . ' - ' . self::HARI[date('w', $b)];
    }

    /** Tanggal pada surat: "14 Oktober 2026", "14-16 Oktober 2026", "30 Oktober - 2 November 2026". */
    public static function tanggalTugas(array $st): string
    {
        $a = strtotime($st['tanggal_mulai']);
        $b = strtotime($st['tanggal_selesai']);

        if ($a === $b) {
            return self::tanggalIndo($st['tanggal_mulai']);
        }
        if (date('Y-m', $a) === date('Y-m', $b)) {
            return date('j', $a) . '-' . date('j', $b) . ' ' . self::BULAN[(int) date('n', $b)] . ' ' . date('Y', $b);
        }
        if (date('Y', $a) === date('Y', $b)) {
            return date('j', $a) . ' ' . self::BULAN[(int) date('n', $a)] . ' - ' . self::tanggalIndo($st['tanggal_selesai']);
        }

        return self::tanggalIndo($st['tanggal_mulai']) . ' - ' . self::tanggalIndo($st['tanggal_selesai']);
    }

    /** Status pelaksanaan menurut tanggal hari ini: Akan Datang / Berlangsung / Selesai. */
    public static function status(array $st): string
    {
        $hariIni = date('Y-m-d');
        if ($hariIni < $st['tanggal_mulai']) {
            return 'Akan Datang';
        }

        return $hariIni > $st['tanggal_selesai'] ? 'Selesai' : 'Berlangsung';
    }
}
