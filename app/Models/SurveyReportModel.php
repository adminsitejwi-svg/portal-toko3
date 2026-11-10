<?php

namespace App\Models;

use App\Models\BaseModel;

// Survey Report (menu Report NOC > Survey Report); perusahaan, logo & kontak memakai data RFO (RFOModel)
// Struktur mengikuti "Survey Report Form Yesnet.docx".
class SurveyReportModel extends BaseModel
{
    protected $table      = 'd_survey_report';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'survey_id',
        'perusahaan',
        'logo',
        'nama_pelanggan',
        'tipe_bisnis',
        'alamat',
        'telepon',
        'email_pelanggan',
        'tgl_spk',
        'no_spk',
        'tanggal_pelaksanaan',
        'jenis_layanan',
        'layanan',
        'catatan',
        'rekomendasi',
        'resume',
        'pelaksana',
        'lampiran',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /** Pilihan Tipe Bisnis, urutan sama dengan docx (selain ini = isian bebas dari pilihan "Dll"). */
    public const TIPE_BISNIS = ['Corporate', 'Personal', 'Government'];

    /** Pilihan Jenis Survey (selain ini = isian bebas dari pilihan "Dll"). */
    public const JENIS_LAYANAN = ['FO', 'Wireless', 'VSAT'];

    /**
     * Warna kop & tabel dokumen (pengganti oranye #FF6600 di docx), diambil dari warna logo perusahaan:
     * Speednet = biru huruf "NET", Sprintnet = biru tua, Rnet = biru, Flazznet = hijau (logonya tanpa biru).
     * Bar section memakai versi lebih muda dari warna ini (di docx: #FE9D59).
     */
    public const WARNA_LOGO = [
        'PT JURAGAN WIFI INDONESIA'          => '#0000FF',
        'PT NORLEC TELEKOMUNIKASI INDONESIA' => '#04619C',
        'PT GENEZIS MITRA TECHNOLOGY'        => '#344C2E',
        'PT RTIGA GLOBAL MEDIA'              => '#1A75BB',
    ];

    /** Warna lebih muda (dicampur putih), untuk bar section seperti #FF6600 -> #FE9D59 di docx. */
    public static function warnaMuda(string $hex, float $putih = 0.35): string
    {
        [$r, $g, $b] = sscanf(ltrim($hex, '#'), '%02x%02x%02x');
        $campur      = static fn (int $c) => (int) round($c + (255 - $c) * $putih);

        return sprintf('#%02X%02X%02X', $campur($r), $campur($g), $campur($b));
    }

    /** Folder foto lampiran. */
    public static function folderFoto(): string
    {
        return WRITEPATH . 'uploads/survey-report/';
    }

    /**
     * Foto lampiran beserta keterangannya: [['file' => nama file, 'ket' => keterangan], ...].
     * Data lama berupa daftar nama file saja tetap terbaca (keterangan kosong).
     */
    public static function lampiran(?array $sr): array
    {
        $isi   = json_decode((string) ($sr['lampiran'] ?? ''), true);
        $hasil = [];

        foreach (is_array($isi) ? $isi : [] as $item) {
            if (is_string($item)) {
                $hasil[] = ['file' => $item, 'ket' => ''];
            } elseif (is_array($item) && is_string($item['file'] ?? null)) {
                $hasil[] = ['file' => $item['file'], 'ket' => (string) ($item['ket'] ?? '')];
            }
        }

        return $hasil;
    }

    /** Daftar nama file foto lampiran saja. */
    public static function foto(?array $sr): array
    {
        return array_column(self::lampiran($sr), 'file');
    }

    /** Baris tabel Sisi Internal/Teknisi vs Sisi Klien: [[internal, klien], ...]. */
    public static function rekomendasi(?array $sr): array
    {
        $isi = json_decode((string) ($sr['rekomendasi'] ?? ''), true);

        return array_values(array_filter(array_map(
            static fn ($b) => is_array($b) ? [trim((string) ($b[0] ?? '')), trim((string) ($b[1] ?? ''))] : null,
            is_array($isi) ? $isi : []
        ), static fn ($b) => $b !== null && ($b[0] !== '' || $b[1] !== '')));
    }

    /** Poin catatan: satu poin per baris, nomor/bullet di depan dibuang (dokumen menomori ulang 1), 2), ...). */
    public static function poinCatatan(?string $catatan): array
    {
        $baris = preg_split('/\R/', trim((string) $catatan)) ?: [];

        return array_values(array_filter(array_map(
            static fn ($b) => trim(preg_replace('/^\s*(\d+[\.\)]|[-•*])\s*/u', '', $b)),
            $baris
        ), 'strlen'));
    }

    /**
     * Survey ID berikutnya untuk tanggal hari ini: SVR-dd/mm/yyyy-NNN (contoh SVR-10/11/2026-001).
     * Nomor urut lanjut terus antar hari dan reset ke 001 setiap ganti bulan (sama dengan RFO ID).
     */
    public function generateSurveyId(): string
    {
        $last = $this->builder()
            ->select("MAX(CAST(SUBSTRING_INDEX(survey_id, '-', -1) AS UNSIGNED)) AS seq", false)
            ->where("survey_id LIKE 'SVR-__/" . date('m/Y') . "-%'", null, false)
            ->get()
            ->getRowArray();

        $next = ((int) ($last['seq'] ?? 0)) + 1;

        return 'SVR-' . date('d/m/Y') . '-' . str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }
}
