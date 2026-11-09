<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Daftar menu "All NOC", "Report NOC" & "Activation" (nama, ikon, keterangan) untuk halaman "Segera Hadir".
 *
 * - route : alamat halaman (sama dengan link di sidebar)
 * - judul : nama menu
 * - ikon  : ikon Tabler (ti-...)
 * - info  : penjelasan singkat di kartu
 * - soon  : true = halaman belum dibuat, tampil "Segera Hadir"
 *
 * Saat halaman yang masih "soon" selesai dibuat: ubah soon jadi false,
 * lalu arahkan route-nya ke controller halaman tersebut di Routes.php.
 */
class AllNoc extends BaseConfig
{
    public array $menu = [
        ['route' => 'DataRakDC',       'judul' => 'Data Rak DC',                 'ikon' => 'ti-server-2',        'info' => 'Data center, rak, perangkat & visual rak.', 'soon' => false],
        ['route' => 'Interkoneksi',    'judul' => 'Data Center & Interkoneksi',  'ikon' => 'ti-arrows-exchange', 'info' => 'Data center beserta interkoneksi pelanggan.', 'soon' => false],
        ['route' => 'Vlan',            'judul' => 'Data VLAN',                   'ikon' => 'ti-network',         'info' => 'VLAN per lokasi / POP dan mapping perangkat.', 'soon' => false],
        ['route' => 'NetMon',          'judul' => 'NetMon',                      'ikon' => 'ti-activity-heartbeat', 'info' => 'Kumpulan link website monitoring jaringan.', 'soon' => false],
        ['route' => 'DataRouter',      'judul' => 'Data Router',                 'ikon' => 'ti-router',          'info' => 'Inventaris router beserta lokasi & konfigurasi.', 'soon' => true],
        ['route' => 'DataSwitch',      'judul' => 'Data Switch',                 'ikon' => 'ti-switch-horizontal', 'info' => 'Inventaris switch, port & uplink.', 'soon' => true],
        ['route' => 'DataOlt',         'judul' => 'Data OLT',                    'ikon' => 'ti-affiliate',       'info' => 'Data OLT, PON port & pelanggan terhubung.', 'soon' => true],
        ['route' => 'IpPublic',        'judul' => 'Data IP Public',              'ikon' => 'ti-world',           'info' => 'Alokasi blok & alamat IP public.', 'soon' => true],
        ['route' => 'IpPrivate',       'judul' => 'Data IP Private',             'ikon' => 'ti-lock',            'info' => 'Alokasi subnet & alamat IP private.', 'soon' => true],
        ['route' => 'FtpClient',       'judul' => 'FTP Client',                  'ikon' => 'ti-folder-share',    'info' => 'Akses file server FTP NOC dari portal.', 'soon' => true],
        ['route' => 'DataOdcOdp',     'judul' => 'Data ODC & ODP',            'ikon' => 'ti-box-model-2',      'info' => 'Data ODC (Optical Distribution Cabinet) & ODP (Optical Distribution Point).', 'soon' => true],
        ['route' => 'Kmz',            'judul' => 'KMZ',                       'ikon' => 'ti-map-2',            'info' => 'File peta KMZ jalur kabel & titik jaringan.', 'soon' => true],
        ['route' => 'DevicesBackbone', 'judul' => 'Devices Backbone',            'ikon' => 'ti-router',          'info' => 'Perangkat jaringan backbone.', 'soon' => false],
        ['route' => 'Bts',             'judul' => 'Data BTS',                    'ikon' => 'ti-antenna-bars-5',  'info' => 'Data BTS & vendor BTS.', 'soon' => false],
        ['route' => 'PelangganNoc',    'judul' => 'Data Pelanggan',              'ikon' => 'ti-users',           'info' => 'Pelanggan NOC beserta perangkat & vendor.', 'soon' => false],
        ['route' => 'VendorNoc',       'judul' => 'Data Vendor',                 'ikon' => 'ti-briefcase',       'info' => 'Daftar vendor NOC.', 'soon' => false],
        ['route' => 'InventoryKantor', 'judul' => 'Asset Management',            'ikon' => 'ti-package',         'info' => 'Inventaris aset kantor.', 'soon' => false],
    ];

    // Menu Report NOC yang belum dibuat (dropdown Report NOC di sidebar), format sama dengan $menu
    public array $reportNoc = [
        ['route' => 'SlaReport',           'judul' => 'SLA Report',           'ikon' => 'ti-target',         'info' => 'Laporan pencapaian SLA layanan pelanggan.', 'soon' => true],
        ['route' => 'MonthlyReport',       'judul' => 'Monthly Report',       'ikon' => 'ti-calendar-stats', 'info' => 'Rekap laporan NOC bulanan.', 'soon' => true],
        ['route' => 'SurveyReport',        'judul' => 'Survey Report',        'ikon' => 'ti-zoom-check',     'info' => 'Laporan hasil survey lokasi.', 'soon' => true],
        ['route' => 'InstallationReport',  'judul' => 'Installation Report',  'ikon' => 'ti-plug-connected', 'info' => 'Laporan pekerjaan instalasi.', 'soon' => true],
        ['route' => 'TroubleshootReport',  'judul' => 'Troubleshoot Report',  'ikon' => 'ti-bug',            'info' => 'Laporan penanganan gangguan / troubleshoot.', 'soon' => true],
    ];

    // Menu Activation (bagian sidebar sebelum NOC Operation), semuanya masih "Segera Hadir"
    public array $activation = [
        ['route' => 'Spk',            'judul' => 'SPK',                     'ikon' => 'ti-file-certificate',    'info' => 'Surat Perintah Kerja untuk pekerjaan aktivasi.', 'soon' => true],
        ['route' => 'BastCorporate',  'judul' => 'BAST Corporate',          'ikon' => 'ti-building-skyscraper', 'info' => 'Berita Acara Serah Terima pelanggan corporate.', 'soon' => true],
        ['route' => 'BastAlfamidi',   'judul' => 'BAST Alfamidi',           'ikon' => 'ti-building-store',      'info' => 'Berita Acara Serah Terima toko Alfamidi.', 'soon' => true],
        ['route' => 'BastLawson',     'judul' => 'BAST Lawson',             'ikon' => 'ti-building-store',      'info' => 'Berita Acara Serah Terima toko Lawson.', 'soon' => true],
        ['route' => 'BastAlfamart',   'judul' => 'BAST Alfamart',           'ikon' => 'ti-building-store',      'info' => 'Berita Acara Serah Terima toko Alfamart.', 'soon' => true],
        ['route' => 'BastDandan',     'judul' => 'BAST Dandan',             'ikon' => 'ti-building-store',      'info' => 'Berita Acara Serah Terima toko Dandan.', 'soon' => true],
        ['route' => 'PksContract',    'judul' => 'PKS & Contract',          'ikon' => 'ti-file-text',           'info' => 'Perjanjian Kerja Sama & kontrak pelanggan.', 'soon' => true],
    ];

    // Menu yang masih "Segera Hadir" (All NOC, Report NOC & Activation), dicari berdasarkan route
    public function cari(string $route): ?array
    {
        foreach (array_merge($this->menu, $this->reportNoc, $this->activation) as $m) {
            if (strcasecmp($m['route'], $route) === 0) {
                return $m;
            }
        }

        return null;
    }
}
