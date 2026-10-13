<?php

namespace App\Models;

use App\Models\BaseModel;

class RFOModel extends BaseModel
{
    protected $table            = 'd_rfo';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'rfo_id',
        'perusahaan',
        'logo',
        'waktu_gangguan',
        'waktu_selesai',
        'penyebab',
        'impact',
        'action',
        'status',
        'petugas',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';

    /**
     * Pilihan perusahaan pada dropdown beserta logo bawaannya
     * (path relatif terhadap folder public/).
     */
    public const PERUSAHAAN = [
        'PT JURAGAN WIFI INDONESIA'          => 'Logo/SPEEDNET--.png',
        'PT NORLEC TELEKOMUNIKASI INDONESIA' => 'Logo/Sprintnet.png',
        'PT GENEZIS MITRA TECHNOLOGY'        => 'Logo/flazznet.png',
        'PT RTIGA GLOBAL MEDIA'              => 'Logo/Rtiga.png',
    ];

    /**
     * Warna gelombang kop surat RFO (atas & bawah), mengikuti warna logo perusahaan:
     * [gradasi gelap, gradasi terang, warna aksen].
     */
    public const WARNA_KOP = [
        'PT JURAGAN WIFI INDONESIA'          => ['#E8461B', '#F7A21B', '#1414E6'],
        'PT NORLEC TELEKOMUNIKASI INDONESIA' => ['#0E5FA8', '#2FB5EE', '#F39A12'],
        'PT GENEZIS MITRA TECHNOLOGY'        => ['#2F5A2C', '#8FCB7E', '#F28C0F'],
        'PT RTIGA GLOBAL MEDIA'              => ['#1F7BC1', '#3BB4E6', '#36215F'],
    ];

    /** Alamat & kontak kantor pada tanda tangan body email RFO (di bawah nama perusahaan). */
    public const ALAMAT_KANTOR = [
        'MTH Square Lt 2 No.0219',
        'Jl. Letjen MT. Haryono Kav 10 Bidara Cina',
        'Jatinegara, Jakarta Timur DKI Jakarta 13330.',
        'Telp (021-50112225)  Mobile +62 877-0029-5324',
    ];

    /** Nama perusahaan pada tanda tangan surat RFO. */
    public const NAMA_TTD = [
        'PT JURAGAN WIFI INDONESIA'          => 'PT. Juragan Wifi Indonesia',
        'PT NORLEC TELEKOMUNIKASI INDONESIA' => 'PT. Norlec Telekomunikasi Indonesia',
        'PT GENEZIS MITRA TECHNOLOGY'        => 'PT. Genezis Mitra Technology',
        'PT RTIGA GLOBAL MEDIA'              => 'PT. Rtiga Global Media',
    ];

    /** Kalimat pembuka surat RFO (halaman View / Print). */
    public const SURAT_PEMBUKA = "Terima kasih atas dukungan dan kesetiaan untuk terus menggunakan layanan kami.\n"
        . "Kami sangat menghargai kepercayaan anda kepada kami dalam memberikan layanan dan bantuan.\n"
        . "Melalui email ini kami menginformasikan detail RFO (Reason For Outage) untuk kendala yang\n"
        . "terjadi sebelumnya:";

    /** E-mail NOC pada kalimat penutup surat RFO, sesuai perusahaan/logo. */
    public const EMAIL_NOC = [
        'PT JURAGAN WIFI INDONESIA'          => 'noc@jwi.id',
        'PT NORLEC TELEKOMUNIKASI INDONESIA' => 'noc@norlec.id',
        'PT GENEZIS MITRA TECHNOLOGY'        => 'noc@flazznet.id',
        'PT RTIGA GLOBAL MEDIA'              => 'noc@rnet.id',
    ];

    /** Nomor WA Support pada kalimat penutup surat RFO, sesuai perusahaan/logo. */
    public const NOMOR_WA = [
        'PT JURAGAN WIFI INDONESIA'          => '+62 811-1370-5508',
        'PT NORLEC TELEKOMUNIKASI INDONESIA' => '+62 811-1370-5508',
        'PT GENEZIS MITRA TECHNOLOGY'        => '+62 811-1370-550',
        'PT RTIGA GLOBAL MEDIA'              => '+62 811-1370-5508',
    ];

    /** Kalimat penutup surat RFO (halaman View / Print); %1$s = nomor WA Support, %2$s = e-mail NOC. */
    public const SURAT_PENUTUP = "Atas ketidaknyamanan yang ditimbulkan kami sampaikan permohonan maaf.\n"
        . "Sekian informasi yang dapat kami sampaikan. Untuk informasi lebih lanjut, silahkan hubungi\n"
        . "Hotline Support kami di 021-5011-2225 / %1\$s (WA Support) yang beroperasi 24/7\n"
        . "atau e-mail ke %2\$s\n"
        . "\n"
        . "Demikian informasi yang kami sampaikan, Atas perhatian dan kerjasamanya kami ucapkan terima\n"
        . "kasih.";

    /** Nama petugas untuk dropdown: diambil dari data shift NOC (jadwal_noc). */
    public function namaPetugas(): array
    {
        return (new JadwalModel())->distinct()->select('nama')
            ->where('nama !=', '')
            ->orderBy('nama', 'ASC')
            ->findColumn('nama') ?? [];
    }

    /**
     * RFO ID berikutnya untuk tanggal hari ini: RFO-dd/mm/yyyy-NNN (contoh RFO-13/10/2026-001).
     * Nomor urut lanjut terus antar hari dan reset ke 001 setiap ganti bulan.
     */
    public function generateRfoId(): string
    {
        $prefix = 'RFO-' . date('d/m/Y') . '-';

        // Romawi untuk RFO lama berformat RFO-dd/X/yyyy-NNN
        $romawi = [1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'][(int) date('n')];

        // Cari nomor urut terbesar dari semua RFO di bulan ini, tanggal berapa pun:
        // format sekarang RFO-__/mm/yyyy-NNN, serta format lama RFO-__mmyyyy-NNN dan RFO-__/X/yyyy-NNN
        $last = $this->builder()
            ->select("MAX(CAST(SUBSTRING_INDEX(rfo_id, '-', -1) AS UNSIGNED)) AS seq", false)
            ->groupStart()
                ->where("rfo_id LIKE 'RFO-__/" . date('m/Y') . "-%'", null, false)
                ->orWhere("rfo_id LIKE 'RFO-__" . date('mY') . "-%'", null, false)
                ->orWhere("rfo_id LIKE 'RFO-__/" . $romawi . '/' . date('Y') . "-%'", null, false)
            ->groupEnd()
            ->get()
            ->getRowArray();

        $next = ((int) ($last['seq'] ?? 0)) + 1;

        return $prefix . str_pad((string) $next, 3, '0', STR_PAD_LEFT);
    }
}
