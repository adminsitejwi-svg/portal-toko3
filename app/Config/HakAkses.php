<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Daftar halaman yang bisa diatur lewat menu Informasi > Hak Akses.
 *
 * Kunci  = segmen URL pertama halaman tersebut (contoh: site_url('Alfamidi') -> 'Alfamidi').
 *          Semua sub halaman (create, edit, save, delete, view, dst.) ikut kunci yang sama.
 * Nilai  = nama yang tampil di halaman Hak Akses.
 *
 * Urutan grup & halaman mengikuti sidebar. Halaman baru yang belum didaftarkan di sini
 * otomatis hanya bisa dibuka Administrator, jadi daftarkan setiap ada menu baru.
 */
class HakAkses extends BaseConfig
{
    /**
     * Grup => [segmen URL => label]
     *
     * @var array<string, array<string, string>>
     */
    public array $grup = [
        'Halaman Utama' => [
            'dashboard-manager' => 'Beranda',
        ],
        'Data Toko JWI Group' => [
            'Alfamidi'        => 'Data Toko - Alfamidi',
            'Lawson'          => 'Data Toko - Lawson',
            'Alfamart'        => 'Data Toko - Alfamart',
            'DataSI'          => 'Data Penggunaan - Simcard',
            'NMRInet'         => 'Data Penggunaan - Nomor Inet',
            'Perangkat'       => 'Master Data - Merek Perangkat',
            'Jns_perangkat'   => 'Master Data - Jenis Perangkat',
            'TypePerangkat'   => 'Master Data - Type Perangkat',
            'Vendor'          => 'Master Data - Vendor Non Celullar',
            'VendorCelulllar' => 'Master Data - Vendor Celulllar',
            'LayananVendor'   => 'Master Data - Layanan Vendor',
            'DCAdmin'         => 'Master Data - DC',
            'MediaKoneksi'    => 'Master Data - Media Koneksi',
            'PemilikProject'  => 'Master Data - Pemilik Projek',
            'Pelanggan'       => 'Master Data - Kategori Pelanggan',
            'NomorInet'       => 'Master Data - Nomor INET',
            'QuotaSIMCARD'    => 'Master Data - Kuota Simcard',
            'VPN'             => 'Master Data - VPN',
            'Map'             => 'Lokasi',
        ],
        'Activation' => [
            'Spk'          => 'SPK',
            'SuratTugas'   => 'Surat Tugas',
            'BastCorporate' => 'BAST - Corporate',
            'BastAlfamidi' => 'BAST - Alfamidi',
            'BastLawson'   => 'BAST - Lawson',
            'BastAlfamart' => 'BAST - Alfamart',
            'BastDandan'   => 'BAST - Dandan',
            'PksContract'  => 'PKS & Contract',
        ],
        'NOC Operation' => [
            'RFO'                   => 'RFO',
            'MDMaintenance'         => 'Notifikasi Maintenance',
            'HistoryReport'         => 'Report NOC - Daily Report',
            'MaintenanceReport'     => 'Report NOC - Maintenance Report',
            'ReportSat'             => 'Report NOC - Daily Report SAT Group',
            'MonthlyReport'         => 'Report NOC - Monthly Report',
            'SurveyReport'          => 'Report NOC - Survey Report',
            'InstallationReport'    => 'Report NOC - Installation Report',
            'TroubleshootReport'    => 'Report NOC - Troubleshoot Report',
            'PreventiveMaintenance' => 'Report NOC - Preventive Maintenance',
            'DataRakDC'             => 'Data Teknis - Data Rak DC',
            'Interkoneksi'          => 'Data Teknis - Data Center & Interkoneksi',
            'Vlan'                  => 'Data Teknis - Data VLAN',
            'NetMon'                => 'Data Teknis - NetMon',
            'DataRouter'            => 'Data Teknis - Data Router',
            'DataSwitch'            => 'Data Teknis - Data Switch',
            'DataOlt'               => 'Data Teknis - Data OLT',
            'DataCctv'              => 'Data Teknis - Data CCTV',
            'IpPublic'              => 'Data Teknis - Data IP Addres',
            'IpPrivate'             => 'Data Teknis - IP Private',
            'RekapDataMidi'         => 'Data Teknis - Rekap Data Toko',
            'FtpClient'             => 'Data Teknis - FTP Client',
            'DataOdcOdp'            => 'Data Teknis - Data ODC & ODP',
            'Kmz'                   => 'Data Teknis - KMZ',
            'DevicesBackbone'       => 'Data Teknis - Devices Backbone',
            'Bts'                   => 'Data Teknis - Data BTS',
            'PelangganNoc'          => 'Data Teknis - Data Pelanggan',
            'VendorNoc'             => 'Data Teknis - Data Vendor',
            'InventoryKantor'       => 'Data Teknis - Asset Management',
            'DataCenter'            => 'Data Teknis - Data Center (lama)',
            'ResiPengiriman'        => 'Resi Pengiriman',
            'Calendar'              => 'Jadwal NOC - Shift',
            'Piket'                 => 'Jadwal NOC - Piket',
        ],
    ];

    /**
     * Bagian Informasi: selalu bisa dibuka semua role (default).
     *
     * @var list<string>
     */
    public array $selaluBoleh = [
        'Profile',
        'settings',
        'Logs',
        'forgot-password',
        'logout',
    ];

    /**
     * Hanya Administrator, tidak bisa diberikan ke role lain.
     * Boleh berupa segmen ('HakAkses') atau path lengkap ('settings/delete').
     *
     * @var list<string>
     */
    public array $khususAdmin = [
        'HakAkses',
        'register',
        'settings/delete',
    ];

    /** Halaman default role Support & User saat SQL pertama kali dijalankan. */
    public array $defaultRoleBiasa = ['RekapDataMidi'];

    /** Semua kunci halaman yang bisa diatur (urut sesuai sidebar). */
    public function semuaHalaman(): array
    {
        $out = [];
        foreach ($this->grup as $halaman) {
            foreach ($halaman as $kunci => $label) {
                $out[$kunci] = $label;
            }
        }

        return $out;
    }
}
