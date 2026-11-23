<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title>Screen - Network Wallboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- DataTables core + Buttons -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <!-- Chart.js (grafik traffic) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script src="<?= base_url('assets/theme/nms-grafik.js') ?>?v=<?= @filemtime(FCPATH . 'assets/theme/nms-grafik.js') ?>"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#e3f5fe',
                            100: '#b9e6fc',
                            200: '#8bd5fb',
                            300: '#5cc4f9',
                            400: '#38b7f7',
                            500: '#04a9f5',
                            600: '#03a0ec',
                            700: '#0396e2',
                            800: '#028cd9',
                            900: '#017bc8'
                        },
                        sidebar: '#1c232f',
                        bodybg: '#f4f7fa'
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif']
                    },
                    spacing: {
                        'header': '74px',
                        'sidebar': '264px'
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f4f7fa;
        }

        .dark body {
            background: #1d2630;
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px
        }

        ::-webkit-scrollbar-thumb {
            background: #b9c1c9;
            border-radius: 4px
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #3a4658
        }

        .card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 1px 20px 0 rgba(69, 90, 100, .08);
            margin-bottom: 24px;
        }

        .dark .card {
            background: #263240;
            color: #bfc8d6;
            box-shadow: none
        }

        .card-body {
            padding: 25px
        }

        .pc-sidebar {
            transition: transform .25s ease, width .25s ease
        }

        .pc-link.active {
            color: #fff !important;
        }

        .pc-link.active .pc-micon {
            color: #04a9f5
        }

        .dropdown-menu {
            display: none;
        }

        .dropdown-menu.show {
            display: block;
        }
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: all .3s ease;
        }

        .submenu.open {
            max-height: 1000px;
            overflow: visible;
        }

        @media (max-width:1024px) {
            .pc-sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 1050;
            }

            .pc-sidebar.mobile-open {
                transform: translateX(0);
            }

            .pc-container {
                margin-left: 0 !important;
            }
        }

        .brand-text {
            font-size: 18px;
        }

        /* ===== TABEL: scroll di dalam card, header kolom tetap terlihat ===== */
        .rfo-page .table-scroll {
            overflow: auto;
        }

        .rfo-page .table-scroll thead th {
            /* !important: DataTables memberi position:relative pada kolom yang bisa diurutkan */
            position: sticky !important;
            top: 0;
            z-index: 2;
            box-shadow: inset 0 -1px 0 var(--rf-border);
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/theme/rfo.css') ?>?v=<?= @filemtime(FCPATH . 'assets/theme/rfo.css') ?: '1' ?>">
    <script src="<?= base_url('assets/theme/rfo.js') ?>?v=<?= @filemtime(FCPATH . 'assets/theme/rfo.js') ?: '1' ?>" defer></script>
    <?= view('partials/theme') ?>
</head>

<body class="text-[#37474f] dark:text-[#bfc8d6]">
    <!-- ============ SIDEBAR ============ -->
    <nav id="sidebar" class="pc-sidebar fixed top-0 left-0 h-screen w-sidebar bg-sidebar text-[#a9b7c6] z-[1030] flex flex-col">
        <!-- brand -->
        <div class="flex items-center h-header px-6 shrink-0">
            <a href="#" class="flex items-center gap-2 text-white text-2xl font-semibold">
                <span class="text-primary-500"></span>
                <span class="brand-text">Sistem Operasional <br> JWI Group</span>
            </a>
        </div>
        <?php if (session()->getFlashdata('success')) : ?>
            <div id="successAlert" class="fixed top-5 left-1/2 -translate-x-1/2 z-50 w-full max-w-md px-4">
                <div class="bg-green-500 text-white rounded-xl shadow-xl overflow-hidden">
                    <div class="flex items-center gap-3 px-5 py-4">
                        <i class="ti ti-circle-check text-3xl"></i>
                        <div>
                            <h4 class="font-bold">Berhasil</h4>
                            <p class="text-sm"><?= session()->getFlashdata('success') ?></p>
                        </div>
                    </div>
                    <div class="h-1 bg-green-400">
                        <div id="progressBar" class="h-full bg-white w-full"></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')) : ?>
            <div id="errorAlert" class="fixed top-5 left-1/2 -translate-x-1/2 z-50 w-full max-w-md px-4">
                <div class="bg-red-500 text-white rounded-xl shadow-xl overflow-hidden">
                    <div class="flex items-center gap-3 px-5 py-4">
                        <i class="ti ti-alert-circle text-3xl"></i>
                        <div>
                            <h4 class="font-bold">Gagal</h4>
                            <p class="text-sm"><?= session()->getFlashdata('error') ?></p>
                        </div>
                    </div>
                    <div class="h-1 bg-red-400">
                        <div id="progressBarError" class="h-full bg-white w-full"></div>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const box = document.getElementById('errorAlert');
                    const bar = document.getElementById('progressBarError');
                    if (!box) return;
                    if (bar) {
                        bar.style.transition = 'width 4s linear';
                        setTimeout(function() {
                            bar.style.width = '0%';
                        }, 100);
                    }
                    setTimeout(function() {
                        box.style.transition = 'all .5s ease';
                        box.style.opacity = '0';
                        box.style.transform = 'translate(-50%, -20px)';
                        setTimeout(function() {
                            box.remove();
                        }, 500);
                    }, 4000);
                });
            </script>
        <?php endif; ?>
        <!-- menu -->
        <div class="flex-1 overflow-y-auto overflow-x-hidden py-2.5">
            <ul class="px-0">
                <li class="px-6 py-3 text-[11px] uppercase tracking-wide text-[#5b6b7f] font-semibold">Halaman Utama</li>
                <li>
                    <a href="<?= site_url('dashboard-manager') ?>" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white relative">
                        <span class="pc-micon w-5"><i class="ti ti-home fs-5"></i></span>
                        <span class="pc-mtext">Beranda</span>
                    </a>
                </li>
                <li class="px-6 py-3 text-[11px] uppercase tracking-wide text-[#5b6b7f] font-semibold">Data Toko JWI Group</li>

                <li class="hasmenu">
                    <a href="#" onclick="toggleSub(this);return false;" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-building-store fs-1"></i></span>
                        <span class="flex-1">Data Toko</span>
                        <i data-feather="chevron-right" class="arrow w-4 h-4 transition-transform"></i>
                    </a>
                    <ul class="submenu bg-black/20">
                        <li><a href="<?= site_url('Alfamidi') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">ALFAMIDI</a></li>
                        <li><a href="<?= site_url('Lawson') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">LAWSON</a></li>
                        <li><a href="<?= site_url('Alfamart') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">ALFAMART</a></li>
                    </ul>
                </li>
                <li class="hasmenu">
                    <a href="#" onclick="toggleSub(this);return false;" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-brand-databricks"></i></span>
                        <span class="flex-1">Data Penggunaan</span>
                        <i data-feather="chevron-right" class="arrow w-4 h-4 transition-transform"></i>
                    </a>
                    <ul class="submenu bg-black/20">
                        <li><a href="<?= site_url('DataSI') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Simcard</a></li>
                        <li><a href="<?= site_url('NMRInet') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Nomor Inet</a></li>
                    </ul>
                </li>
                <li class="hasmenu">
                    <a href="#" onclick="toggleSub(this);return false;" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-category"></i></span>
                        <span class="flex-1">Master Data</span>
                        <i data-feather="chevron-right" class="arrow w-4 h-4 transition-transform"></i>
                    </a>
                    <ul class="submenu bg-black/20">
                        <li><a href="<?= site_url('Perangkat') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Merek Perangkat</a></li>
                        <li><a href="<?= site_url('Jns_perangkat') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Jenis Perangkat</a></li>
                        <li><a href="<?= site_url('TypePerangkat') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Type Perangkat</a></li>
                        <li><a href="<?= site_url('Vendor') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Vendor Non Celullar</a></li>
                        <li><a href="<?= site_url('VendorCelulllar') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Vendor Celulllar</a></li>
                        <li><a href="<?= site_url('LayananVendor') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Layanan Vendor</a></li>
                        <li><a href="<?= site_url('DCAdmin') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">DC</a></li>
                        <li><a href="<?= site_url('MediaKoneksi') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Media Koneksi</a></li>
                        <li><a href="<?= site_url('PemilikProject') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Pemilik Projek</a></li>
                        <li><a href="<?= site_url('Pelanggan') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Kategori Pelanggan</a></li>
                        <li><a href="<?= site_url('NomorInet') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Nomor INET</a></li>
                        <li><a href="<?= site_url('QuotaSIMCARD') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Kuota Simcard</a></li>
                        <li><a href="<?= site_url('VPN') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">VPN</a></li>
                    </ul>
                </li>
                <li><a href="<?= site_url('Map') ?>" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white"><span class="pc-micon w-5"><i class="ti ti-map-pin"></i></span><span>Lokasi</span></a></li>
                <li class="px-6 py-3 text-[11px] uppercase tracking-wide text-[#5b6b7f] font-semibold">Activation</li>
                <li>
                    <a href="<?= site_url('Spk') ?>" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-file-certificate"></i></span>
                        <span>SPK</span>
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('SuratTugas') ?>" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-file-text"></i></span>
                        <span>Surat Tugas</span>
                    </a>
                </li>
                <li class="hasmenu">
                    <a href="#" onclick="toggleSub(this);return false;" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-file-check"></i></span>
                        <span class="flex-1">BAST</span>
                        <i data-feather="chevron-right" class="arrow w-4 h-4 transition-transform"></i>
                    </a>
                    <ul class="submenu bg-black/20">
                        <li><a href="<?= site_url('BastCorporate') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Corporate</a></li>
                        <li><a href="<?= site_url('BastAlfamidi') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Alfamidi</a></li>
                        <li><a href="<?= site_url('BastLawson') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Lawson</a></li>
                        <li><a href="<?= site_url('BastAlfamart') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Alfamart</a></li>
                        <li><a href="<?= site_url('BastDandan') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Dandan</a></li>
                    </ul>
                </li>
                <li>
                    <a href="<?= site_url('PksContract') ?>" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-file-text"></i></span>
                        <span>PKS &amp; Contract</span>
                    </a>
                </li>
                <li class="px-6 py-3 text-[11px] uppercase tracking-wide text-[#5b6b7f] font-semibold">NOC Operation</li>
                <li>
                    <a href="<?= site_url('RFO') ?>" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-file-alert"></i></span>
                        <span>RFO</span>
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('MDMaintenance') ?>" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-tool"></i></span>
                        <span>Notifikasi Maintenance</span>
                    </a>
                </li>
                <li class="hasmenu">
                    <a href="#" onclick="toggleSub(this);return false;" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-history"></i></span>
                        <span class="flex-1">Report NOC</span>
                        <i data-feather="chevron-right" class="arrow w-4 h-4 transition-transform"></i>
                    </a>
                    <ul class="submenu bg-black/20">
                        <li><a href="<?= site_url('HistoryReport') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Daily Report</a></li>
                        <li><a href="<?= site_url('MaintenanceReport') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Maintenance Report</a></li>
                        <li><a href="<?= site_url('ReportSat') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Daily Report SAT Group</a></li>
                        <li><a href="<?= site_url('MonthlyReport') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Monthly Report</a></li>
                        <li><a href="<?= site_url('SurveyReport') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Survey Report</a></li>
                        <li><a href="<?= site_url('InstallationReport') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Installation Report</a></li>
                        <li><a href="<?= site_url('TroubleshootReport') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Troubleshoot Report</a></li>
                        <li><a href="<?= site_url('PreventiveMaintenance') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Preventive Maintenance</a></li>
                    </ul>
                </li>
                <li class="hasmenu">
                    <a href="#" onclick="toggleSub(this);return false;" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-category"></i></span>
                        <span class="flex-1">Data Teknis NOC</span>
                        <i data-feather="chevron-right" class="arrow w-4 h-4 transition-transform"></i>
                    </a>
                    <ul class="submenu bg-black/20">
                        <li><a href="<?= site_url('DataRakDC') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Rak DC</a></li>
                        <li><a href="<?= site_url('Interkoneksi') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Center &amp; Interkoneksi</a></li>
                        <li><a href="<?= site_url('Vlan') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data VLAN</a></li>
                        <li><a href="<?= site_url('NetMon') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">NetMon</a></li>
                        <li><a href="<?= site_url('DataRouter') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Router</a></li>
                        <li><a href="<?= site_url('DataSwitch') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Switch</a></li>
                        <li><a href="<?= site_url('DataOlt') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data OLT</a></li>
                        <li><a href="<?= site_url('DataCctv') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data CCTV</a></li>
                        <li><a href="<?= site_url('IpPublic') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data IP Addres</a></li>
                        <li><a href="<?= site_url('RekapDataMidi') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Rekap Data Toko</a></li>
                        <li><a href="<?= site_url('FtpClient') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">FTP Client</a></li>
                        <li><a href="<?= site_url('DataOdcOdp') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data ODC &amp; ODP</a></li>
                        <li><a href="<?= site_url('Kmz') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">KMZ</a></li>
                        <li><a href="<?= site_url('DevicesBackbone') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Devices Backbone</a></li>
                        <li><a href="<?= site_url('Bts') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data BTS</a></li>
                        <li><a href="<?= site_url('PelangganNoc') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Pelanggan</a></li>
                        <li><a href="<?= site_url('VendorNoc') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Vendor</a></li>
                        <li><a href="<?= site_url('InventoryKantor') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Asset Management</a></li>
                        <li><a href="<?= site_url('DataTopologi') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Topologi</a></li>
                    </ul>
                </li>
                <li class="hasmenu">
                    <a href="#" onclick="toggleSub(this);return false;" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-activity-heartbeat"></i></span>
                        <span class="flex-1">Data Teknis NETMON</span>
                        <i data-feather="chevron-right" class="arrow w-4 h-4 transition-transform"></i>
                    </a>
                    <ul class="submenu bg-black/20">
                        <li><a href="<?= site_url('DashboardNetmon') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Dashboard Netmon</a></li>
                        <li><a href="<?= site_url('DeviceMonitoring') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Device Monitoring</a></li>
                        <li><a href="<?= site_url('TrafficMonitoring') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Traffic Monitoring</a></li>
                        <li><a href="<?= site_url('Nms') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">NMS JWI Group</a></li>
                        <li><a href="<?= site_url('Alert') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Alert</a></li>
                        <li><a href="<?= site_url('Screen') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] text-white font-semibold">Screen</a></li>
                    </ul>
                </li>
                <li class="hasmenu">
                    <a href="#" onclick="toggleSub(this);return false;" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-calendar-week"></i></span>
                        <span class="flex-1">Jadwal NOC</span>
                        <i data-feather="chevron-right" class="arrow w-4 h-4 transition-transform"></i>
                    </a>
                    <ul class="submenu bg-black/20">
                        <li><a href="<?= site_url('Calendar') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Shift</a></li>
                        <li><a href="<?= site_url('Piket') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Piket</a></li>
                    </ul>
                </li>

                <li class="px-6 py-3 text-[11px] uppercase tracking-wide text-[#5b6b7f] font-semibold">Informasi</li>
                <li>
                    <a href="<?= site_url('Profile') ?>" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-user-circle"></i></span>
                        <span>Profile</span>
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('settings') ?>" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-settings"></i></span>
                        <span>Pengguna</span>
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('Logs') ?>" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-report-search"></i></span>
                        <span>Change Log</span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- ============ MAIN ============ -->
    <div id="container" class="pc-container ml-sidebar min-h-screen transition-[margin] duration-200">

        <!-- HEADER -->
        <header class="pc-header sticky top-0 z-[1025] bg-white dark:bg-[#263240] h-header flex items-center px-6 shadow-[0_1px_20px_0_rgba(69,90,100,.08)]">
            <ul class="flex items-center gap-1">
                <li><a href="#" onclick="toggleSidebar();return false;" class="head-link flex items-center justify-center w-10 h-10 rounded hover:bg-gray-100 dark:hover:bg-white/5"><i data-feather="menu"></i></a></li>
            </ul>

            <ul class="flex items-center gap-1 ml-auto">
                <li class="relative dropdown">
                    <a href="#" onclick="toggleDrop(event,this)" class="head-link flex items-center justify-center w-10 h-10 rounded hover:bg-gray-100 dark:hover:bg-white/5"><i data-feather="user"></i></a>
                    <div class="dropdown-menu absolute right-0 mt-1 w-64 bg-white dark:bg-[#263240] rounded shadow-lg overflow-hidden border border-gray-100 dark:border-white/10">
                        <div class="flex items-center gap-3 px-5 py-4 bg-primary-500 text-white">
                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                <i data-feather="user" class="w-5 h-5 text-gray-500"></i>
                            </div>
                            <div>
                                <h6 class="font-medium leading-tight"><?= session('username') ?></h6>
                            </div>
                        </div>
                        <div class="py-3 px-3">
                            <button onclick="window.location.href='<?= site_url('logout') ?>'" class="w-full mt-3 bg-primary-500 hover:bg-red-600 text-white py-2 rounded flex items-center justify-center gap-2 text-sm">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </button>
                        </div>
                    </div>
                </li>
            </ul>
        </header>


        <div class="rfo-page p-6" id="screenPage">
            <!-- JUDUL -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-5 sw-judul">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic is-sky"><i class="ti ti-device-tv"></i></span>
                    <div>
                        <h1 class="rfo-title">Network Wallboard</h1>
                        <p class="rfo-subtitle">Traffic live banyak interface dari banyak perangkat dalam satu layar</p>
                    </div>
                </div>
                <?php if ($tabelSiap) : ?>
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="sw-status" id="swUpdate">Menunggu data...</span>
                        <button type="button" id="swFullscreen" class="rfo-btn rfo-btn-blue border-0 cursor-pointer"><i class="ti ti-maximize"></i> Fullscreen</button>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (! $tabelSiap) : ?>
                <div class="rfo-panel mb-6">
                    <p class="font-semibold text-red-600"><i class="ti ti-database-off"></i> Tabel traffic belum ada.</p>
                    <p class="rfo-subtitle mt-1">Jalankan <b>database/d_device_monitoring.sql</b> (kalau belum), lalu <b>database/d_device_traffic.sql</b> di database.</p>
                </div>
            <?php else : ?>
                <!-- PENGATURAN -->
                <div class="rfo-panel mb-6 sw-atur">
                    <div class="sw-baris">
                        <label class="rfo-field">
                            <i class="ti ti-plug-connected" style="color:#6d5dfc"></i>
                            <select id="swInterface" class="sw-select sw-lebar" title="Pilih device / interface">
                                <option value="">Pilih device / interface...</option>
                                <?php foreach ($interface as $i) : ?>
                                    <option value="<?= $i['id'] ?>"><?= esc($i['hostname'] . ' (' . $i['ip'] . ') - ' . $i['nama'] . ($i['alias'] ? ' (' . $i['alias'] . ')' : '')) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="rfo-field">
                            <i class="ti ti-layout-grid" style="color:#2f6bff"></i>
                            <select id="swLayout" class="sw-select" title="Layout (baris x kolom)">
                                <option value="1x1">Layout 1 × 1</option>
                                <option value="2x2" selected>Layout 2 × 2</option>
                                <option value="2x3">Layout 2 × 3</option>
                                <option value="3x3">Layout 3 × 3</option>
                                <option value="4x4">Layout 4 × 4</option>
                            </select>
                        </label>
                        <label class="rfo-field">
                            <i class="ti ti-clock" style="color:#f59e0b"></i>
                            <select id="swJam" class="sw-select" title="Rentang grafik">
                                <option value="1">1 Jam terakhir</option>
                                <option value="6">6 Jam terakhir</option>
                                <option value="24">24 Jam terakhir</option>
                            </select>
                        </label>
                        <button type="button" id="swTambah" class="rfo-btn rfo-btn-blue border-0 cursor-pointer sw-tombol"><i class="ti ti-plus"></i> Add Graph</button>
                        <button type="button" id="swSimpan" class="rfo-ghost-btn"><i class="ti ti-device-floppy"></i> Save Layout</button>
                        <button type="button" id="swReset" class="rfo-ghost-btn"><i class="ti ti-refresh"></i> Reset</button>
                    </div>
                    <p class="sw-hint"><i class="ti ti-info-circle"></i> Pilih interface dari perangkat mana pun, maksimal <?= (int) $maks ?> grafik (mengikuti layout yang dipilih). Layout tersimpan di browser ini. Data diperbarui tiap 60 detik; tekan Esc untuk keluar dari fullscreen.</p>
                </div>

                <!-- GRAFIK -->
                <div class="sw-grid" id="swGrid"></div>
            <?php endif; ?>
        </div>
    </div>

    <style>
        .sw-status { font-size: 12.5px; font-weight: 600; color: var(--rf-muted); }
        .sw-baris { display: flex; flex-wrap: wrap; align-items: center; gap: 12px; }
        .rfo-field .sw-select {
            height: 42px; border: 1px solid var(--rf-border); border-radius: 10px; padding: 0 36px 0 38px;
            font-size: 13px; font-weight: 600; color: var(--rf-text); background-color: var(--rf-card); outline: none; cursor: pointer;
            width: 180px; max-width: 100%; appearance: none; -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%237a869a' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 14px center;
        }
        .rfo-field .sw-lebar { width: 360px; }
        .sw-tombol { height: 42px; padding: 0 18px; font-size: 13px; }
        .sw-hint { margin-top: 12px; font-size: 12.5px; color: var(--rf-muted); }
        /* panel grafik gelap di mode terang & gelap, sama dengan halaman NMS */
        .sw-grid { display: grid; grid-template-columns: repeat(var(--sw-kol, 2), minmax(0, 1fr)); gap: 14px; }
        .sw-card { background: #181b1f; border: 1px solid #2c3036; border-radius: 6px; padding: 14px; min-width: 0; display: flex; flex-direction: column; }
        .sw-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-bottom: 8px; }
        .sw-head strong { display: block; font-size: 13.5px; color: #e3e5e8; overflow-wrap: anywhere; line-height: 1.35; }
        .sw-head small { display: block; margin-top: 2px; font-size: 11.5px; color: #a5abb5; }
        .sw-st { font-weight: 700; }
        .sw-st.is-up { color: #7eb26d; }
        .sw-st.is-down { color: #f2786e; }
        .sw-hapus { border: 0; background: transparent; color: #f2786e; font-size: 12px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 3px; white-space: nowrap; }
        .sw-hapus:hover { text-decoration: underline; }
        .sw-chart { position: relative; height: 240px; }
        .sw-kosong-data { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; color: #a5abb5; font-size: 13px; text-align: center; }
        .sw-kosong-data[hidden] { display: none; }
        .sw-kosong { grid-column: 1 / -1; text-align: center; padding: 50px 15px; border: 1px dashed var(--rf-border); border-radius: 14px; color: var(--rf-muted); font-size: 14px; }

        /* Fullscreen: hanya grafik, tinggi dibagi rata sesuai layout (layar NOC) */
        #screenPage:fullscreen { width: 100vw; height: 100vh; margin: 0; padding: 12px; background: #0b0d10; display: flex; flex-direction: column; overflow: hidden; }
        #screenPage:fullscreen .sw-judul, #screenPage:fullscreen .sw-atur { display: none !important; }
        #screenPage:fullscreen .sw-grid { flex: 1; min-height: 0; gap: 10px; grid-template-rows: repeat(var(--sw-brs, 2), minmax(0, 1fr)); grid-auto-rows: minmax(0, 1fr); }
        #screenPage:fullscreen .sw-card { min-height: 0; padding: 9px 11px; overflow: hidden; }
        #screenPage:fullscreen .sw-head { margin-bottom: 4px; }
        #screenPage:fullscreen .sw-chart { flex: 1; min-height: 0; height: auto; }
        #screenPage:fullscreen .sw-hapus { display: none; }
        #screenPage:fullscreen .sw-kosong { align-self: center; border-color: #2c3036; color: #a5abb5; }

        @media (max-width: 850px) {
            .sw-grid { grid-template-columns: 1fr; }
            #screenPage:fullscreen { overflow-y: auto; }
            #screenPage:fullscreen .sw-grid { grid-template-rows: none; grid-auto-rows: 240px; flex: none; }
        }
        @media (max-width: 640px) { .sw-baris > *, .rfo-field .sw-select { width: 100% !important; justify-content: center; } }
    </style>

    <?php if ($tabelSiap) : ?>
    <script>
        (function() {
            const URL_DATA = '<?= site_url('Screen/data') ?>';
            const INTERFACE = <?= json_encode($interface, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE) ?>;
            const KAPASITAS = { '1x1': 1, '2x2': 4, '2x3': 6, '3x3': 9, '4x4': 16 };
            const KEY = 'portal_screen_layout';

            const $ = id => document.getElementById(id);
            const halaman = $('screenPage'), grid = $('swGrid'), selLayout = $('swLayout'), selJam = $('swJam'), selIf = $('swInterface');
            const grafik = new Map(); // id -> { chart, kartu }
            let pilih = [];           // id interface sesuai urutan tampil
            let nomor = 0, tunda = 0;

            const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' })[c]);
            const maks = () => KAPASITAS[selLayout.value] || 4;
            const info = id => INTERFACE.find(i => i.id === id);
            const pesan = (icon, title, text) => Swal.fire({ icon, title, text, confirmButtonColor: '#2f6bff' });

            function formatBps(v) {
                if (v === null || v === undefined) return '-';
                for (const [s, b] of [['Gbps', 1e9], ['Mbps', 1e6], ['Kbps', 1e3]]) {
                    if (v >= b) return (v / b).toLocaleString('id-ID', { maximumFractionDigits: 2 }) + ' ' + s;
                }
                return Math.round(v) + ' bps';
            }

            function terapkanLayout() {
                const [brs, kol] = selLayout.value.split('x');
                grid.style.setProperty('--sw-brs', brs);
                grid.style.setProperty('--sw-kol', kol);
            }

            function tampilKosong() {
                if (pilih.length) return;
                grid.innerHTML = '<div class="sw-kosong"><i class="ti ti-chart-area-line"></i> ' + (INTERFACE.length
                    ? 'Belum ada grafik. Pilih perangkat dan interface, lalu klik Add Graph.'
                    : 'Belum ada interface. Tambahkan device di Device Monitoring lalu tunggu polling.') + '</div>';
            }

            function status(kartu, st) {
                const el = kartu.querySelector('.sw-st');
                el.textContent = st;
                el.className = 'sw-st' + (st === 'Up' ? ' is-up' : (st === 'Down' ? ' is-down' : ''));
            }

            function hapus(id) {
                const g = grafik.get(id);
                if (g) {
                    if (g.chart) g.chart.destroy();
                    g.kartu.remove();
                }
                grafik.delete(id);
                pilih = pilih.filter(x => x !== id);
                tampilKosong();
            }

            function tambah(id) {
                const i = info(id);
                if (!i || pilih.includes(id) || pilih.length >= maks()) return;
                pilih.push(id);

                const kartu = document.createElement('div');
                kartu.className = 'sw-card';
                kartu.innerHTML =
                    '<div class="sw-head"><div><strong>' + esc(i.hostname + ' / ' + i.nama + (i.alias ? ' (' + i.alias + ')' : '')) + '</strong>' +
                    '<small>' + esc(i.ip) + ' · ifIndex ' + i.ifIndex + ' · <span class="sw-st"></span></small></div>' +
                    '<button type="button" class="sw-hapus"><i class="ti ti-x"></i> Remove</button></div>' +
                    '<div class="sw-chart"><canvas></canvas><div class="sw-kosong-data">Memuat data...</div></div>';
                status(kartu, i.status);
                kartu.querySelector('.sw-hapus').addEventListener('click', () => hapus(id));

                grid.querySelector('.sw-kosong')?.remove();
                grid.appendChild(kartu);
                const chart = window.NmsGrafik ? NmsGrafik.buat(kartu.querySelector('canvas'), [], [
                    { label: 'RX / Download', borderColor: NmsGrafik.WARNA.hijau },
                    { label: 'TX / Upload', borderColor: NmsGrafik.WARNA.kuning }
                ], formatBps, { maks: 1000 }) : null;
                grafik.set(id, { chart, kartu });
            }

            // poll = true: server sekalian mem-poll device yang sudah waktunya
            async function muat(poll) {
                if (!pilih.length) return;
                const n = ++nomor;
                try {
                    const r = await fetch(URL_DATA + '?' + new URLSearchParams({ ids: pilih.join(','), jam: selJam.value, poll: poll ? 1 : 0 }), {
                        cache: 'no-store', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    });
                    const res = await r.json();
                    if (!r.ok || !res.ok) throw new Error(res.pesan || 'HTTP ' + r.status);
                    if (n !== nomor) return; // sudah ada permintaan yang lebih baru
                    pilih.forEach(id => {
                        const g = grafik.get(id), d = res.grafik[id];
                        if (!g || !g.chart) return;
                        const label = d ? d.label : [];
                        g.chart.data.labels = label;
                        g.chart.data.datasets[0].data = d ? d.rx : [];
                        g.chart.data.datasets[1].data = d ? d.tx : [];
                        g.chart.update('none');
                        const kosong = g.kartu.querySelector('.sw-kosong-data');
                        kosong.textContent = 'Belum ada data traffic pada rentang ini.';
                        kosong.hidden = label.length > 0;
                        if (d) status(g.kartu, d.status);
                    });
                    $('swUpdate').textContent = 'Diperbarui: ' + res.jam;
                } catch (e) {
                    if (n === nomor) $('swUpdate').textContent = 'Gagal memperbarui data';
                }
            }

            $('swTambah').addEventListener('click', () => {
                const id = parseInt(selIf.value, 10);
                if (!id) return pesan('info', 'Pilih interface', 'Pilih device / interface terlebih dahulu.');
                if (pilih.includes(id)) return pesan('info', 'Sudah ada', 'Interface ini sudah ditampilkan.');
                if (pilih.length >= maks()) return pesan('warning', 'Layout penuh', 'Layout ini maksimal ' + maks() + ' grafik.');
                tambah(id);
                clearTimeout(tunda);
                tunda = setTimeout(() => muat(false), 400); // beberapa grafik ditambah berurutan: cukup sekali ambil data
            });

            $('swSimpan').addEventListener('click', () => {
                try {
                    localStorage.setItem(KEY, JSON.stringify({ layout: selLayout.value, jam: selJam.value, ids: pilih }));
                    pesan('success', 'Tersimpan', 'Layout Screen berhasil disimpan di browser ini.');
                } catch (e) {
                    pesan('error', 'Gagal', 'Browser tidak mengizinkan penyimpanan layout.');
                }
            });

            $('swReset').addEventListener('click', () => {
                [...pilih].forEach(hapus);
                try { localStorage.removeItem(KEY); } catch (e) { }
                selLayout.value = '2x2';
                selJam.value = '1';
                terapkanLayout();
                tampilKosong();
            });

            selLayout.addEventListener('change', () => {
                terapkanLayout();
                // layout lebih kecil: grafik yang melebihi kapasitas dilepas dari belakang
                while (pilih.length > maks()) hapus(pilih[pilih.length - 1]);
            });
            selJam.addEventListener('change', () => muat(false));

            // ====== FULLSCREEN ======
            if (halaman.requestFullscreen) {
                $('swFullscreen').addEventListener('click', () => {
                    halaman.requestFullscreen().catch(() => pesan('error', 'Gagal', 'Browser tidak dapat mengaktifkan fullscreen.'));
                });
                document.addEventListener('fullscreenchange', () => {
                    setTimeout(() => grafik.forEach(g => g.chart && g.chart.resize()), 150);
                });
            } else {
                $('swFullscreen').hidden = true;
            }

            // layout tersimpan
            let simpan = null;
            try { simpan = JSON.parse(localStorage.getItem(KEY) || 'null'); } catch (e) { }
            if (simpan && KAPASITAS[simpan.layout]) selLayout.value = simpan.layout;
            if (simpan && ['1', '6', '24'].includes(String(simpan.jam))) selJam.value = simpan.jam;
            terapkanLayout();
            (simpan && Array.isArray(simpan.ids) ? simpan.ids : []).forEach(id => tambah(Number(id)));
            tampilKosong();
            muat(false);
            setInterval(() => muat(true), 60000); // tetap jalan di fullscreen layar NOC
        })();
    </script>
    <?php endif; ?>

    <script>
        // ---- Sidebar ----
        let collapsed = false;

        function toggleSidebar() {
            const sb = document.getElementById('sidebar'),
                c = document.getElementById('container');
            if (window.innerWidth < 1024) {
                sb.classList.toggle('mobile-open');
            } else {
                collapsed = !collapsed;
                if (collapsed) {
                    sb.style.transform = 'translateX(-100%)';
                    c.classList.remove('ml-sidebar');
                    c.style.marginLeft = '0';
                } else {
                    sb.style.transform = 'translateX(0)';
                    c.style.marginLeft = '';
                    c.classList.add('ml-sidebar');
                }
            }
        }

        function toggleDrop(e, el) {
            e.preventDefault();
            e.stopPropagation();
            const menu = el.parentElement.querySelector('.dropdown-menu');
            const isOpen = menu.classList.contains('show');
            document.querySelectorAll('.dropdown-menu.show').forEach(m => m.classList.remove('show'));
            if (!isOpen) menu.classList.add('show');
        }

        document.addEventListener('click', function(e) {
            if (window.innerWidth < 1024) {
                const sb = document.getElementById('sidebar');
                const menuBtn = e.target.closest('[onclick*="toggleSidebar"]');
                if (sb.classList.contains('mobile-open') && !sb.contains(e.target) && !menuBtn) {
                    sb.classList.remove('mobile-open');
                }
            }
            document.querySelectorAll('.dropdown-menu.show').forEach(m => m.classList.remove('show'));
        });

        function toggleSub(el) {
            const parent = el.closest('.hasmenu');
            const sub = parent.querySelector('.submenu');
            const arrow = parent.querySelector('.arrow');
            sub.classList.toggle('open');
            if (arrow) arrow.style.transform = sub.classList.contains('open') ? 'rotate(90deg)' : 'rotate(0deg)';
        }

        feather.replace();
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const alertBox = document.getElementById('successAlert');
            const progressBar = document.getElementById('progressBar');
            if (alertBox) {
                if (progressBar) {
                    progressBar.style.transition = "width 3s linear";
                    setTimeout(() => {
                        progressBar.style.width = "0%";
                    }, 100);
                }
                setTimeout(() => {
                    alertBox.style.transition = "all .5s ease";
                    alertBox.style.opacity = "0";
                    alertBox.style.transform = "translate(-50%, -20px)";
                    setTimeout(() => {
                        alertBox.remove();
                    }, 500);
                }, 3000);
            }
        });
    </script>


    <?php if (!session()->get('logged_in')) : ?>
        <script>
            window.location.href = "<?= base_url('/login') ?>";
        </script>
    <?php endif; ?>
</body>
<script>
    // PENGHALANG KOSMETIK SAJA — bukan security, mudah dilewati
    document.addEventListener('contextmenu', e => e.preventDefault());
    document.addEventListener('keydown', e => {
        if (e.key === 'F12') e.preventDefault();
        if (e.ctrlKey && e.shiftKey && ['I', 'J', 'C'].includes(e.key.toUpperCase())) e.preventDefault();
        if (e.ctrlKey && e.key.toUpperCase() === 'U') e.preventDefault();
    });
</script>

</html>