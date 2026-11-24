<?php

namespace App\Models;

use CodeIgniter\Model;

// Rekap Data Toko: satu tabel, datanya dipisah per bagian lewat kolom `kategori`
class RekapDataTokoModel extends Model
{
    protected $table            = 'rekap_data_toko';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;

    protected $allowedFields    = [
        'kategori', // bagian: fo / modem / vsat
        'nama_pengisi',
        'perusahaan', // <-- Tambahkan ini
        'tanggal',
        'toko',
        'branch',
        'kode_toko',
        'nama_toko',
        'foto_sn_fortigate',
        'id_pelanggan_fo',
        'sn_ont_fo',
        'tipe_modem_gsm_1',
        'foto_imei_modem_1',
        'msisdn_modem_1',
        'iccid_modem_1',
        'tipe_modem_gsm_2',
        'foto_imei_modem_2',
        'msisdn_modem_2',
        'iccid_modem_2',
        'sharelock_tikor_alamat',
        'kategori_koneksi_vsat',
        'nama_vendor',
        'nomor_inet',
        'kategori_produk',
    ];

    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /** Pilihan perusahaan di form. */
    public const PERUSAHAAN = ['PT Juragan Wifi Indonesia', 'PT Genezis Mitra Technology'];

    /** Pilihan toko di form. */
    public const TOKO = ['Midi', 'Lawson'];

    /** Pilihan branch di form (selain isi manual). */
    public const BRANCH = ['Bitung', 'Bekasi', 'Palu', 'Makassar', 'Manado', 'Samarinda', 'Pasuruan', 'Yogyakarta', 'Ambon', 'Kendari', 'Medan'];

    /** Pilihan tipe modem GSM di form (selain isi manual). */
    public const TIPE_MODEM = ['Huawei B310', 'Huawei B311', 'Huawei B312', 'Huawei B315', 'Huawei B320', 'Advan V1 pro', 'Tidak ada modem GSM dilokasi'];

    /** Kolom yang sama di semua bagian: nama kolom => judul. */
    public const UMUM = [
        'nama_pengisi' => 'Nama Pengisi',
        'perusahaan'   => 'Perusahaan',
        'tanggal'      => 'Tanggal',
        'toko'         => 'Toko',
        'branch'       => 'Branch',
        'kode_toko'    => 'Kode Toko',
        'nama_toko'    => 'Nama Toko',
    ];

    /**
     * Kolom isian per bagian: nama kolom => [judul form, jenis, panjang maksimal, judul kolom tabel].
     * Jenis: teks, dok (link/teks atau upload file), modem (dropdown + isi manual), area (textarea).
     */
    public const ISIAN = [
        'foto_sn_fortigate'      => ['Foto Fortigate', 'dok', 255, 'Foto Fortigate'],
        'id_pelanggan_fo'        => ['ID Pelanggan', 'teks', 100, 'ID Pelanggan'],
        'sn_ont_fo'              => ['SN ONT FO', 'dok', 255, 'SN ONT FO'],
        'tipe_modem_gsm_1'       => ['Tipe Modem GSM 1', 'modem', 100, 'Tipe Modem GSM 1'],
        'foto_imei_modem_1'      => ['Foto IMEI Modem 1', 'dok', 255, 'Foto IMEI Modem 1'],
        'msisdn_modem_1'         => ['Nomor Telepon / Nomor MSISDN (Modem 1)', 'teks', 150, 'MSISDN Modem 1'],
        'iccid_modem_1'          => ['Nomor ICCID / 16 Angka di Kartu (Modem 1)', 'teks', 150, 'ICCID Modem 1'],
        'tipe_modem_gsm_2'       => ['Tipe Modem GSM 2', 'modem', 100, 'Tipe Modem GSM 2'],
        'foto_imei_modem_2'      => ['Foto IMEI Modem 2', 'dok', 255, 'Foto IMEI Modem 2'],
        'msisdn_modem_2'         => ['Nomor Telepon / Nomor MSISDN (Modem 2)', 'teks', 150, 'MSISDN Modem 2'],
        'iccid_modem_2'          => ['Nomor ICCID / 16 Angka di Kartu (Modem 2)', 'teks', 150, 'ICCID Modem 2'],
        'sharelock_tikor_alamat' => ['Sharelock / Tikor / Alamat', 'area', 0, 'Sharelock / Tikor / Alamat'],
        'kategori_koneksi_vsat'  => ['Kategori Koneksi VSAT', 'teks', 100, 'Kategori Koneksi VSAT'],
        'nama_vendor'            => ['Nama Vendor', 'teks', 150, 'Nama Vendor'],
        'nomor_inet'             => ['Nomor Inet', 'teks', 100, 'Nomor Inet'],
        'kategori_produk'        => ['Kategori Produk', 'teks', 100, 'Kategori Produk'],
    ];

    private const MODEM_1 = ['tipe_modem_gsm_1', 'foto_imei_modem_1', 'msisdn_modem_1', 'iccid_modem_1'];
    private const MODEM_2 = ['tipe_modem_gsm_2', 'foto_imei_modem_2', 'msisdn_modem_2', 'iccid_modem_2'];

    /**
     * Tiga bagian halaman Rekap Data Toko (tab di kartu header).
     * grup = susunan form: judul grup => daftar kolom; wajib = kolom bertanda * selain kolom umum.
     */
    public const BAGIAN = [
        'fo' => [
            'nama'  => 'FO + Modem 2',
            'ikon'  => 'ti-topology-star-3',
            'url'   => 'RekapDataMidi',
            'grup'  => [
                'Fortigate'              => ['foto_sn_fortigate'],
                'Fiber Optic (FO)'       => ['id_pelanggan_fo', 'sn_ont_fo'],
                'Modem GSM 1 (Jika Ada)' => self::MODEM_1,
                'Modem GSM 2 (Jika Ada)' => self::MODEM_2,
                'Lokasi'                 => ['sharelock_tikor_alamat'],
            ],
            'wajib' => ['sharelock_tikor_alamat'],
        ],
        'modem' => [
            'nama'  => 'Modem 1 + Modem 2',
            'ikon'  => 'ti-router',
            'url'   => 'RekapDataMidi/modem',
            'grup'  => [
                'Fortigate'   => ['foto_sn_fortigate'],
                'Modem GSM 1' => self::MODEM_1,
                'Modem GSM 2' => self::MODEM_2,
                'Lokasi'      => ['sharelock_tikor_alamat'],
            ],
            'wajib' => ['tipe_modem_gsm_1', 'sharelock_tikor_alamat'],
        ],
        'vsat' => [
            'nama'  => 'VSAT + Modem',
            'ikon'  => 'ti-satellite',
            'url'   => 'RekapDataMidi/vsat',
            'grup'  => [
                'Fortigate'              => ['foto_sn_fortigate'],
                'Modem GSM 1 (Jika Ada)' => self::MODEM_1,
                'Modem GSM 2 (Jika Ada)' => self::MODEM_2,
                'Lokasi'                 => ['sharelock_tikor_alamat'],
                'VSAT'                   => ['kategori_koneksi_vsat', 'nama_vendor', 'nomor_inet', 'kategori_produk'],
            ],
            // modem GSM di FO & VSAT tidak wajib diisi
            'wajib' => ['sharelock_tikor_alamat'],
        ],
    ];

    /** Kolom isian satu bagian, urut sesuai form. */
    public static function kolomIsian(string $bagian): array
    {
        return array_merge(...array_values(self::BAGIAN[$bagian]['grup']));
    }

    /** Kolom isian di luar form bagian ini (bisa masih terisi dari data lama / import). */
    public static function kolomLain(string $bagian): array
    {
        return array_values(array_diff(array_keys(self::ISIAN), self::kolomIsian($bagian)));
    }

    /** Kolom dokumen (link/teks atau file upload). */
    public static function kolomDok(): array
    {
        return array_keys(array_filter(self::ISIAN, static fn ($d) => $d[1] === 'dok'));
    }
}
