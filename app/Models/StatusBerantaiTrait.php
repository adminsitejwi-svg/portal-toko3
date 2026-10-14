<?php

namespace App\Models;

/**
 * Status / priority berantai antar shift untuk data Shift Handover hasil duplikat.
 *
 * Saat report diduplikat (mis. Pagi -> Siang), setiap baris salinan menyimpan id baris
 * asalnya di kolom asal_id. Jika baris salinan diubah, kolom yang terdaftar di
 * $kolomBerantai pada model (status dan/atau priority) ikut disamakan ke baris asal
 * di shift-shift sebelumnya. Kolom lain tidak diubah.
 *
 * Model pemakai wajib mendefinisikan: protected array $kolomBerantai = ['status', ...];
 */
trait StatusBerantaiTrait
{
    /**
     * Telusuri asal_id ke belakang dan samakan kolom berantai setiap baris asal.
     *
     * @param array $row    Baris yang diubah (data sebelum update juga boleh, asal_id tidak berubah)
     * @param array $fields Data baru hasil input user (hanya kolom berantai yang dipakai)
     *
     * @return array{jumlah: int, nilai: array<string, string>} jumlah baris yang ikut diubah
     *                                                         & kolom => nilai yang diterapkan
     */
    public function samakanShiftSebelumnya(array $row, array $fields): array
    {
        $nilai   = array_intersect_key($fields, array_flip($this->kolomBerantai));
        $berubah = [];
        $jumlah  = 0;
        $dilihat = [(int) $row['id'] => true];
        $asalId  = (int) ($row['asal_id'] ?? 0);

        while ($nilai && $asalId > 0 && ! isset($dilihat[$asalId])) {
            $dilihat[$asalId] = true;

            $asal = $this->find($asalId);
            if (! $asal) {
                break; // baris asal sudah dihapus: rantai putus
            }

            $beda = array_diff_assoc($nilai, array_intersect_key($asal, $nilai));
            if ($beda) {
                $this->update($asalId, $beda);
                $berubah += $beda;
                $jumlah++;
            }

            $asalId = (int) ($asal['asal_id'] ?? 0);
        }

        return ['jumlah' => $jumlah, 'nilai' => $berubah];
    }

    /**
     * Tambahan pesan sukses, mis. " 2 data di shift sebelumnya ikut diperbarui (Status: Down, Priority: High)."
     */
    public static function pesanBerantai(array $hasil): string
    {
        if (! $hasil['jumlah']) {
            return '';
        }

        $isi = [];
        foreach ($hasil['nilai'] as $kolom => $v) {
            $isi[] = ucfirst($kolom) . ': ' . $v;
        }

        return ' ' . $hasil['jumlah'] . ' data di shift sebelumnya ikut diperbarui (' . implode(', ', $isi) . ').';
    }

    /**
     * Saat baris dihapus, salinannya disambungkan ke asal baris tsb
     * supaya rantai shift sebelumnya tidak putus.
     */
    public function sambungRantai(array $row): void
    {
        $this->builder()
            ->where('asal_id', $row['id'])
            ->update(['asal_id' => $row['asal_id'] ?? null]);
    }
}
