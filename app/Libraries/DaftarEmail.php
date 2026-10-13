<?php

namespace App\Libraries;

/**
 * Pengurai daftar alamat e-mail dari form kirim email (RFO & Maintenance).
 */
class DaftarEmail
{
    /**
     * Pecah daftar e-mail (pisah koma / titik koma / baris baru).
     * Menerima "nama@domain.com" maupun "Nama <nama@domain.com>" (hasil copy dari webmail),
     * serta membuang spasi tak terlihat hasil copy-paste.
     * Hasil: ['valid' => [...alamat], 'invalid' => [...teks yang bukan e-mail]].
     */
    public static function parse(string $raw): array
    {
        // Spasi non-breaking / zero-width -> spasi biasa
        $raw    = preg_replace('/[\x{00A0}\x{200B}-\x{200D}\x{2060}\x{FEFF}]/u', ' ', $raw) ?? $raw;
        $hasil  = ['valid' => [], 'invalid' => []];

        foreach (preg_split('/[,;\r\n]+/', $raw) as $bagian) {
            $bagian = trim($bagian, " \t\"'");
            if ($bagian === '') {
                continue;
            }

            // Format "Nama <alamat>": ambil alamat di dalam < >
            if (preg_match('/<([^>]+)>/', $bagian, $m)) {
                $bagian = trim($m[1]);
            }

            // Beberapa alamat dipisah spasi dalam satu bagian
            foreach (preg_split('/\s+/', $bagian) as $addr) {
                if (filter_var($addr, FILTER_VALIDATE_EMAIL)) {
                    $hasil['valid'][] = strtolower($addr);
                } else {
                    $hasil['invalid'][] = $addr;
                }
            }
        }

        $hasil['valid'] = array_values(array_unique($hasil['valid']));

        return $hasil;
    }
}
