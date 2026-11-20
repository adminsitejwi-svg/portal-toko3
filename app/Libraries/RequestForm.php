<?php

namespace App\Libraries;

/**
 * Bantuan untuk fitur Request (Informasi > Request).
 *
 * Dipakai controller RequestForm dan partials/theme (badge sidebar + pengingat
 * di halaman yang dipilih admin).
 */
class RequestForm
{
    /** Jenis isian form (kunci => [label, ikon tabler]) */
    public const TIPE = [
        'teks'     => ['Jawaban singkat', 'ti-forms'],
        'paragraf' => ['Paragraf', 'ti-align-left'],
        'angka'    => ['Angka', 'ti-123'],
        'email'    => ['Email', 'ti-mail'],
        'tanggal'  => ['Tanggal', 'ti-calendar'],
        'waktu'    => ['Jam', 'ti-clock'],
        'pilihan'  => ['Pilihan ganda', 'ti-circle-dot'],
        'centang'  => ['Kotak centang', 'ti-checkbox'],
        'dropdown' => ['Dropdown', 'ti-select'],
        'skala'    => ['Skala linier', 'ti-adjustments-horizontal'],
        'file'     => ['Upload file', 'ti-upload'],
        'bagian'   => ['Judul bagian', 'ti-heading'],
    ];

    /** Jenis yang memakai daftar opsi */
    public const PAKAI_OPSI = ['pilihan', 'centang', 'dropdown'];

    /** Ekstensi file yang boleh di-upload pengisi */
    public const EKSTENSI = [
        'pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp',
        'doc', 'docx', 'xls', 'xlsx', 'csv', 'ppt', 'pptx', 'txt',
        'zip', 'rar', '7z', 'kmz', 'kml',
    ];

    /** Batas ukuran 1 file upload (KB) */
    public const MAKS_KB = 10240;

    private static ?bool $siap = null;

    /** true bila tabel d_request sudah dibuat (database/d_request.sql) */
    public static function tersedia(): bool
    {
        if (self::$siap === null) {
            try {
                $db = \Config\Database::connect();
                self::$siap = $db->tableExists('d_request')
                    && $db->tableExists('d_request_target')
                    && $db->tableExists('d_request_jawaban');
            } catch (\Throwable $e) {
                self::$siap = false;
            }
        }

        return self::$siap;
    }

    /**
     * Request aktif milik akun ini yang masih perlu diisi
     * (belum dijawab atau diminta revisi, belum lewat batas waktu).
     *
     * @return list<array{id:int, judul:string, halaman:?string, batas_waktu:?string, status_jawaban:?string}>
     */
    public static function tertunda(int $userId): array
    {
        if (!$userId || !self::tersedia()) {
            return [];
        }

        try {
            $db = \Config\Database::connect();

            return $db->table('d_request r')
                ->select('r.id, r.judul, r.halaman, r.batas_waktu, j.status AS status_jawaban')
                ->join('d_request_target t', 't.request_id = r.id AND t.user_id = ' . (int) $userId, 'inner')
                ->join('d_request_jawaban j', 'j.request_id = r.id AND j.user_id = ' . (int) $userId, 'left')
                ->where('r.status', 'aktif')
                ->groupStart()->where('r.batas_waktu IS NULL')->orWhere('r.batas_waktu >=', date('Y-m-d H:i:s'))->groupEnd()
                ->groupStart()->where('j.id IS NULL')->orWhere('j.status', 'revisi')->groupEnd()
                ->orderBy('r.batas_waktu IS NULL', '', false)
                ->orderBy('r.batas_waktu', 'ASC')
                ->get()->getResultArray();
        } catch (\Throwable $e) {
            log_message('error', 'RequestForm::tertunda: ' . $e->getMessage());

            return [];
        }
    }

    /** Decode JSON susunan isian dengan aman */
    public static function fields(?string $json): array
    {
        $data = json_decode((string) $json, true);

        return is_array($data) ? array_values(array_filter($data, 'is_array')) : [];
    }

    /** Label halaman dari Config\HakAkses (atau '-' bila umum) */
    public static function labelHalaman(?string $kunci): string
    {
        if (!$kunci) {
            return 'Umum';
        }

        $semua = config(\Config\HakAkses::class)->semuaHalaman();

        return $semua[$kunci] ?? $kunci;
    }

    /** Teks jawaban satu isian untuk tabel / export */
    public static function teksJawaban(array $field, $nilai): string
    {
        if ($nilai === null || $nilai === '' || $nilai === []) {
            return '';
        }

        if (($field['tipe'] ?? '') === 'file') {
            return is_array($nilai) ? (string) ($nilai['nama'] ?? '') : '';
        }

        if (is_array($nilai)) {
            return implode(', ', array_map('strval', $nilai));
        }

        return (string) $nilai;
    }
}
