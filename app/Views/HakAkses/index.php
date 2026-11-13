<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title>Hak Akses</title>
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
                        <li><a href="<?= site_url('NomorInet') ?>" class=" block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Nomor INET</a></li>
                        <li><a href="<?= site_url('QuotaSIMCARD') ?>" class=" block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Kuota Simcard</a></li>
                        <li><a href="<?= site_url('VPN') ?>" class=" block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">VPN</a></li>

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
                        <li><a href="<?= site_url('RekapDataMidi') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] text-white font-semibold">Rekap Data Toko</a></li>
                        <li><a href="<?= site_url('FtpClient') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">FTP Client</a></li>
                        <li><a href="<?= site_url('DataOdcOdp') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data ODC &amp; ODP</a></li>
                        <li><a href="<?= site_url('Kmz') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">KMZ</a></li>
                        <li><a href="<?= site_url('DevicesBackbone') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Devices Backbone</a></li>
                        <li><a href="<?= site_url('Bts') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data BTS</a></li>
                        <li><a href="<?= site_url('PelangganNoc') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Pelanggan</a></li>
                        <li><a href="<?= site_url('VendorNoc') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Vendor</a></li>
                        <li><a href="<?= site_url('InventoryKantor') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Asset Management</a></li>
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
                <li>
                    <a href="<?= site_url('Profile') ?>" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-user-circle"></i></span>
                        <span>Profile</span>
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('settings') ?>"
                        class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">

                        <span class="pc-micon w-5">
                            <i class="ti ti-settings"></i>
                        </span>

                        <span>Pengguna</span>

                    </a>
                </li>
                <li>
                    <a href="<?= site_url('Logs') ?>"
                        class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">

                        <span class="pc-micon w-5">
                            <i class="ti ti-report-search"></i>
                        </span>

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
                <!-- profile -->
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

        <?php
        // label halaman per kunci (untuk ringkasan di kartu role / tabel akun)
        $labelHalaman = [];
        foreach ($grup as $isi) {
            foreach ($isi as $k => $l) {
                $labelHalaman[$k] = $l;
            }
        }
        $totalHalaman = count($labelHalaman);
        ?>
        <style>
            .ha-tabs { display: flex; gap: 6px; border-bottom: 1px solid #e5e7eb; margin-bottom: 20px; }
            .dark .ha-tabs { border-color: rgba(255,255,255,.1); }
            .ha-tab { padding: 10px 18px; font-weight: 600; font-size: 14px; color: #6b7280; border-bottom: 2px solid transparent; margin-bottom: -1px; cursor: pointer; display: flex; align-items: center; gap: 6px; }
            .ha-tab.active { color: #04a9f5; border-color: #04a9f5; }
            .ha-panel { display: none; }
            .ha-panel.active { display: block; }
            .ha-roles { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }
            .ha-role { border: 1px solid #e5e7eb; border-radius: 12px; padding: 18px; display: flex; flex-direction: column; gap: 10px; }
            .dark .ha-role { border-color: rgba(255,255,255,.1); }
            .ha-role h3 { font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
            .ha-badge { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 600; padding: 3px 9px; border-radius: 999px; background: #e3f5fe; color: #0284c7; }
            .ha-badge.admin { background: #fef3c7; color: #b45309; }
            .ha-badge.abu { background: #f1f5f9; color: #475569; }
            .dark .ha-badge { background: rgba(4,169,245,.15); color: #7dd3fc; }
            .dark .ha-badge.admin { background: rgba(245,158,11,.15); color: #fcd34d; }
            .dark .ha-badge.abu { background: rgba(255,255,255,.08); color: #cbd5e1; }
            .ha-chips { display: flex; flex-wrap: wrap; gap: 4px; }
            .ha-chip { font-size: 11px; padding: 2px 8px; border-radius: 6px; background: #f1f5f9; color: #475569; }
            .dark .ha-chip { background: rgba(255,255,255,.06); color: #cbd5e1; }
            .ha-meta { font-size: 12px; color: #6b7280; }
            .ha-actions { display: flex; gap: 6px; margin-top: auto; padding-top: 6px; }
            .ha-btn { display: inline-flex; align-items: center; gap: 5px; font-size: 13px; font-weight: 600; padding: 7px 12px; border-radius: 8px; border: 1px solid #e5e7eb; background: #fff; color: #374151; cursor: pointer; }
            .ha-btn:hover { background: #f9fafb; }
            .ha-btn.biru { background: #04a9f5; border-color: #04a9f5; color: #fff; }
            .ha-btn.biru:hover { background: #0396e2; }
            .ha-btn.merah { color: #dc2626; border-color: #fecaca; }
            .ha-btn.merah:hover { background: #fef2f2; }
            .dark .ha-btn { background: #1d2630; border-color: rgba(255,255,255,.12); color: #e2e8f0; }
            .dark .ha-btn.biru { background: #04a9f5; border-color: #04a9f5; color: #fff; }
            .dark .ha-btn.merah { color: #fca5a5; border-color: rgba(220,38,38,.4); }
            .ha-info { display: flex; gap: 10px; padding: 12px 16px; border-radius: 10px; background: #e3f5fe; color: #075985; font-size: 13px; margin-bottom: 18px; }
            .dark .ha-info { background: rgba(4,169,245,.1); color: #bae6fd; }
            .ha-alert { padding: 12px 16px; border-radius: 10px; font-size: 13px; margin-bottom: 16px; }
            .ha-alert.err { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
            .dark .ha-alert.err { background: rgba(220,38,38,.1); color: #fca5a5; border-color: rgba(220,38,38,.3); }
            .ha-tbl { width: 100%; border-collapse: collapse; font-size: 14px; }
            .ha-tbl th { text-align: left; font-size: 12px; text-transform: uppercase; letter-spacing: .03em; color: #6b7280; padding: 10px 12px; border-bottom: 1px solid #e5e7eb; }
            .ha-tbl td { padding: 12px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
            .dark .ha-tbl th, .dark .ha-tbl td { border-color: rgba(255,255,255,.08); }

            /* modal */
            .ha-modal { position: fixed; inset: 0; z-index: 2000; background: rgba(15,23,42,.55); display: none; align-items: flex-start; justify-content: center; padding: 40px 16px; overflow-y: auto; }
            .ha-modal.show { display: flex; }
            .ha-box { background: #fff; border-radius: 14px; width: 100%; max-width: 860px; box-shadow: 0 25px 60px rgba(0,0,0,.25); }
            .dark .ha-box { background: #263240; color: #e2e8f0; }
            .ha-box-head { display: flex; align-items: center; justify-content: space-between; padding: 18px 22px; border-bottom: 1px solid #e5e7eb; }
            .dark .ha-box-head, .dark .ha-box-foot { border-color: rgba(255,255,255,.1); }
            .ha-box-head h3 { font-size: 17px; font-weight: 700; }
            .ha-box-body { padding: 20px 22px; }
            .ha-box-foot { display: flex; justify-content: flex-end; gap: 8px; padding: 14px 22px; border-top: 1px solid #e5e7eb; }
            .ha-field { margin-bottom: 14px; }
            .ha-field label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
            .ha-input { width: 100%; padding: 9px 12px; border: 1px solid #d1d5db; border-radius: 8px; font-size: 14px; background: #fff; color: inherit; }
            .dark .ha-input { background: #1d2630; border-color: rgba(255,255,255,.15); }
            .ha-input:focus { outline: none; border-color: #04a9f5; box-shadow: 0 0 0 3px rgba(4,169,245,.15); }
            .ha-grup { border: 1px solid #e5e7eb; border-radius: 10px; margin-bottom: 12px; overflow: hidden; }
            .dark .ha-grup { border-color: rgba(255,255,255,.1); }
            .ha-grup-head { display: flex; align-items: center; justify-content: space-between; padding: 10px 14px; background: #f8fafc; font-weight: 700; font-size: 13px; }
            .dark .ha-grup-head { background: rgba(255,255,255,.04); }
            .ha-grup-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 2px 12px; padding: 10px 14px; }
            .ha-cek { display: flex; align-items: center; gap: 8px; font-size: 13px; padding: 5px 0; cursor: pointer; }
            .ha-cek input { width: 16px; height: 16px; accent-color: #04a9f5; }
            .ha-cek.kunci { opacity: .75; cursor: default; }
            .ha-switch { display: flex; align-items: center; gap: 10px; padding: 12px 14px; border: 1px dashed #cbd5e1; border-radius: 10px; margin-bottom: 14px; font-size: 13px; cursor: pointer; }
            .ha-switch input { width: 18px; height: 18px; accent-color: #04a9f5; }
            .ha-mati { opacity: .45; pointer-events: none; }
        </style>

        <div class="rfo-page p-6">
            <!-- JUDUL -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic"><i class="ti ti-shield-lock"></i></span>
                    <h1 class="rfo-title">Hak Akses</h1>
                </div>
                <?php if ($tersedia) : ?>
                    <button type="button" class="rfo-btn rfo-btn-blue" onclick="bukaRole(0)">
                        <i class="ti ti-plus"></i> Tambah Role
                    </button>
                <?php endif; ?>
            </div>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="ha-alert err"><i class="ti ti-alert-circle"></i> <?= esc(session()->getFlashdata('error')) ?></div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <?php if (!$tersedia) : ?>
                        <div class="ha-info">
                            <i class="ti ti-database-exclamation text-xl"></i>
                            <div>
                                <b>Tabel hak akses belum ada di database.</b><br>
                                Jalankan file <code>database/m_role_hak_akses.sql</code> di database (lokal &amp; hosting), lalu buka halaman ini lagi.
                                Sebelum itu semua akun masih bisa membuka semua halaman seperti biasa.
                            </div>
                        </div>
                    <?php else : ?>

                        <div class="ha-info">
                            <i class="ti ti-info-circle text-xl"></i>
                            <div>
                                Halaman di bagian <b>Informasi</b> (Profile, Pengguna, Change Log) selalu bisa dibuka semua role.
                                Menu <b>Hak Akses</b>, tambah akun, dan hapus akun hanya untuk <b>Administrator</b>.
                                Halaman yang tidak dicentang otomatis hilang dari sidebar dan ditolak bila URL-nya dibuka langsung.
                            </div>
                        </div>

                        <div class="ha-tabs">
                            <div class="ha-tab active" data-tab="role"><i class="ti ti-users-group"></i> Role (<?= count($roles) ?>)</div>
                            <div class="ha-tab" data-tab="akun"><i class="ti ti-user-cog"></i> Akun (<?= count($akun) ?>)</div>
                        </div>

                        <!-- ===== TAB ROLE ===== -->
                        <div class="ha-panel active" id="panel-role">
                            <div class="ha-roles">
                                <?php foreach ($roles as $r) : $admin = (int) $r['is_admin'] === 1; ?>
                                    <div class="ha-role">
                                        <h3>
                                            <i class="ti <?= $admin ? 'ti-crown' : 'ti-user-shield' ?>"></i>
                                            <?= esc($r['nama']) ?>
                                        </h3>
                                        <?php if ($r['keterangan']) : ?>
                                            <div class="ha-meta"><?= esc($r['keterangan']) ?></div>
                                        <?php endif; ?>
                                        <div class="flex flex-wrap gap-1">
                                            <?php if ($admin) : ?>
                                                <span class="ha-badge admin"><i class="ti ti-star"></i> Semua halaman</span>
                                            <?php else : ?>
                                                <span class="ha-badge"><?= count($r['halaman']) ?> / <?= $totalHalaman ?> halaman</span>
                                            <?php endif; ?>
                                            <span class="ha-badge abu"><i class="ti ti-user"></i> <?= $r['jumlah_akun'] ?> akun</span>
                                        </div>
                                        <?php if (!$admin) : ?>
                                            <div class="ha-chips">
                                                <?php foreach (array_slice($r['halaman'], 0, 6) as $h) : ?>
                                                    <span class="ha-chip"><?= esc($labelHalaman[$h] ?? $h) ?></span>
                                                <?php endforeach; ?>
                                                <?php if (count($r['halaman']) > 6) : ?>
                                                    <span class="ha-chip">+<?= count($r['halaman']) - 6 ?> lainnya</span>
                                                <?php endif; ?>
                                                <span class="ha-chip">+ Informasi</span>
                                            </div>
                                        <?php endif; ?>
                                        <div class="ha-actions">
                                            <button type="button" class="ha-btn biru" onclick="bukaRole(<?= (int) $r['id'] ?>)">
                                                <i class="ti ti-<?= $admin ? 'pencil' : 'checklist' ?>"></i> <?= $admin ? 'Ubah' : 'Atur Halaman' ?>
                                            </button>
                                            <?php if (!$admin) : ?>
                                                <button type="button" class="ha-btn merah" onclick="hapusRole(<?= (int) $r['id'] ?>, <?= esc(json_encode($r['nama']), 'attr') ?>, <?= (int) $r['jumlah_akun'] ?>)">
                                                    <i class="ti ti-trash"></i> Hapus
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- ===== TAB AKUN ===== -->
                        <div class="ha-panel" id="panel-akun">
                            <div class="table-scroll">
                                <table class="ha-tbl">
                                    <thead>
                                        <tr>
                                            <th style="width:50px">No</th>
                                            <th>Username</th>
                                            <th>Role</th>
                                            <th>Halaman yang Bisa Dibuka</th>
                                            <th style="width:120px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($akun as $i => $a) :
                                            $admin  = (int) ($a['is_admin'] ?? 0) === 1;
                                            $khusus = !$admin && (int) $a['akses_khusus'] === 1;
                                            $jml    = 0;
                                            if (!$admin) {
                                                if ($khusus) {
                                                    $jml = count($a['halaman']);
                                                } else {
                                                    foreach ($roles as $r) {
                                                        if ((int) $r['id'] === (int) $a['role_id']) { $jml = count($r['halaman']); }
                                                    }
                                                }
                                            }
                                        ?>
                                            <tr>
                                                <td><?= $i + 1 ?></td>
                                                <td>
                                                    <b><?= esc($a['username']) ?></b>
                                                    <?php if ((int) $a['id'] === (int) session('user_id')) : ?><span class="ha-badge abu">Anda</span><?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($a['role_nama']) : ?>
                                                        <span class="ha-badge <?= $admin ? 'admin' : '' ?>"><?= esc($a['role_nama']) ?></span>
                                                    <?php else : ?>
                                                        <span class="ha-badge abu">Tanpa Role</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($admin) : ?>
                                                        Semua halaman
                                                    <?php elseif ($khusus) : ?>
                                                        <span class="ha-badge">Akses khusus</span> <?= $jml ?> halaman + Informasi
                                                    <?php else : ?>
                                                        Ikut role &middot; <?= $jml ?> halaman + Informasi
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="ha-btn biru" onclick="bukaAkun(<?= (int) $a['id'] ?>)">
                                                        <i class="ti ti-settings"></i> Atur
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if ($tersedia) : ?>
        <!-- ===== MODAL ROLE ===== -->
        <div class="ha-modal" id="modalRole">
            <form class="ha-box" method="post" action="<?= site_url('HakAkses/role/save') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="roleId">
                <div class="ha-box-head">
                    <h3 id="roleJudul">Tambah Role</h3>
                    <button type="button" class="ha-btn" onclick="tutupModal('modalRole')"><i class="ti ti-x"></i></button>
                </div>
                <div class="ha-box-body">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="ha-field">
                            <label>Nama Role</label>
                            <input type="text" name="nama" id="roleNama" class="ha-input" required maxlength="100" placeholder="contoh: Support">
                        </div>
                        <div class="ha-field">
                            <label>Keterangan</label>
                            <input type="text" name="keterangan" id="roleKet" class="ha-input" maxlength="255" placeholder="opsional">
                        </div>
                    </div>
                    <div id="roleAdminInfo" class="ha-info" style="display:none">
                        <i class="ti ti-crown text-xl"></i>
                        <div>Role Administrator selalu bisa membuka semua halaman. Yang bisa diubah hanya nama &amp; keterangan.</div>
                    </div>
                    <div id="roleHalaman"><?= view('HakAkses/_halaman', ['grup' => $grup]) ?></div>
                </div>
                <div class="ha-box-foot">
                    <button type="button" class="ha-btn" onclick="tutupModal('modalRole')">Batal</button>
                    <button type="submit" class="ha-btn biru"><i class="ti ti-device-floppy"></i> Simpan</button>
                </div>
            </form>
        </div>

        <!-- ===== MODAL AKUN ===== -->
        <div class="ha-modal" id="modalAkun">
            <form class="ha-box" method="post" action="<?= site_url('HakAkses/akun/save') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="user_id" id="akunId">
                <div class="ha-box-head">
                    <h3 id="akunJudul">Atur Akun</h3>
                    <button type="button" class="ha-btn" onclick="tutupModal('modalAkun')"><i class="ti ti-x"></i></button>
                </div>
                <div class="ha-box-body">
                    <div class="ha-field">
                        <label>Role</label>
                        <select name="role_id" id="akunRole" class="ha-input" required>
                            <?php foreach ($roles as $r) : ?>
                                <option value="<?= (int) $r['id'] ?>"><?= esc($r['nama']) ?><?= (int) $r['is_admin'] === 1 ? ' (semua halaman)' : '' ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <label class="ha-switch" id="akunKhususWrap">
                        <input type="checkbox" name="akses_khusus" value="1" id="akunKhusus">
                        <span><b>Akses khusus untuk akun ini</b><br>
                            <span class="ha-meta">Kalau dicentang, halaman akun ini dipilih sendiri di bawah (tidak ikut role). Kalau tidak, halamannya mengikuti role.</span></span>
                    </label>
                    <div id="akunAdminInfo" class="ha-info" style="display:none">
                        <i class="ti ti-crown text-xl"></i>
                        <div>Role Administrator selalu bisa membuka semua halaman.</div>
                    </div>
                    <div id="akunHalaman"><?= view('HakAkses/_halaman', ['grup' => $grup]) ?></div>
                </div>
                <div class="ha-box-foot">
                    <button type="button" class="ha-btn" onclick="tutupModal('modalAkun')">Batal</button>
                    <button type="submit" class="ha-btn biru"><i class="ti ti-device-floppy"></i> Simpan</button>
                </div>
            </form>
        </div>

        <script>
            const HA_ROLES = <?= json_encode(array_map(static fn ($r) => [
                'id' => (int) $r['id'], 'nama' => $r['nama'], 'keterangan' => $r['keterangan'],
                'is_admin' => (int) $r['is_admin'] === 1, 'halaman' => $r['halaman'],
            ], $roles), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP) ?>;
            const HA_AKUN = <?= json_encode(array_map(static fn ($a) => [
                'id' => (int) $a['id'], 'username' => $a['username'], 'role_id' => (int) $a['role_id'],
                'akses_khusus' => (int) $a['akses_khusus'] === 1, 'halaman' => $a['halaman'],
            ], $akun), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP) ?>;

            const roleById = id => HA_ROLES.find(r => r.id === id);

            // tab
            function pilihTab(nama) {
                document.querySelectorAll('.ha-tab').forEach(t => t.classList.toggle('active', t.dataset.tab === nama));
                document.querySelectorAll('.ha-panel').forEach(p => p.classList.toggle('active', p.id === 'panel-' + nama));
            }
            document.querySelectorAll('.ha-tab').forEach(t => t.addEventListener('click', () => {
                pilihTab(t.dataset.tab);
                history.replaceState(null, '', '#' + t.dataset.tab);
            }));
            if (location.hash === '#akun') pilihTab('akun');

            // modal
            function bukaModal(id) { document.getElementById(id).classList.add('show'); document.body.style.overflow = 'hidden'; }
            function tutupModal(id) { document.getElementById(id).classList.remove('show'); document.body.style.overflow = ''; }
            document.querySelectorAll('.ha-modal').forEach(m => m.addEventListener('click', e => { if (e.target === m) tutupModal(m.id); }));
            document.addEventListener('keydown', e => { if (e.key === 'Escape') document.querySelectorAll('.ha-modal.show').forEach(m => tutupModal(m.id)); });

            // centang halaman di dalam wadah
            function isiCentang(wadah, daftar) {
                const set = new Set((daftar || []).map(s => s.toLowerCase()));
                wadah.querySelectorAll('input[name="halaman[]"]').forEach(c => c.checked = set.has(c.value.toLowerCase()));
                perbaruiHitung(wadah);
            }
            function perbaruiHitung(wadah) {
                wadah.querySelectorAll('.ha-grup').forEach(g => {
                    if (!g.querySelector('.ha-semua')) return; // grup Informasi (terkunci)
                    const semua = g.querySelectorAll('input[name="halaman[]"]');
                    const dicek = g.querySelectorAll('input[name="halaman[]"]:checked');
                    g.querySelector('.ha-hitung').textContent = dicek.length + '/' + semua.length;
                    const all = g.querySelector('.ha-semua');
                    all.checked = semua.length > 0 && dicek.length === semua.length;
                    all.indeterminate = dicek.length > 0 && dicek.length < semua.length;
                });
            }
            document.querySelectorAll('.ha-halaman').forEach(w => {
                w.addEventListener('change', e => {
                    if (e.target.classList.contains('ha-semua')) {
                        e.target.closest('.ha-grup').querySelectorAll('input[name="halaman[]"]').forEach(c => c.checked = e.target.checked);
                    }
                    perbaruiHitung(w);
                });
            });
            function setAktif(wadah, aktif) {
                wadah.classList.toggle('ha-mati', !aktif);
                wadah.querySelectorAll('input[name="halaman[]"]').forEach(c => c.disabled = !aktif);
            }

            // ===== role =====
            function bukaRole(id) {
                const r = id ? roleById(id) : null;
                const wadah = document.querySelector('#roleHalaman .ha-halaman');
                document.getElementById('roleId').value = r ? r.id : '';
                document.getElementById('roleJudul').textContent = r ? 'Ubah Role: ' + r.nama : 'Tambah Role';
                document.getElementById('roleNama').value = r ? r.nama : '';
                document.getElementById('roleKet').value = r ? (r.keterangan || '') : '';
                const admin = !!(r && r.is_admin);
                document.getElementById('roleAdminInfo').style.display = admin ? 'flex' : 'none';
                document.getElementById('roleHalaman').style.display = admin ? 'none' : '';
                isiCentang(wadah, r ? r.halaman : []);
                bukaModal('modalRole');
                setTimeout(() => document.getElementById('roleNama').focus(), 50);
            }
            function hapusRole(id, nama, jumlah) {
                if (jumlah > 0) {
                    Swal.fire({ icon: 'info', title: 'Role masih dipakai', text: 'Role "' + nama + '" masih dipakai ' + jumlah + ' akun. Pindahkan akunnya ke role lain dulu di tab Akun.', confirmButtonColor: '#04a9f5' });
                    return;
                }
                Swal.fire({
                    title: 'Hapus role "' + nama + '"?', icon: 'warning', showCancelButton: true,
                    confirmButtonColor: '#dc2626', cancelButtonColor: '#6b7280', confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal'
                }).then(r => { if (r.isConfirmed) location.href = '<?= site_url('HakAkses/role/delete/') ?>' + id; });
            }

            // ===== akun =====
            const akunRole = document.getElementById('akunRole');
            const akunKhusus = document.getElementById('akunKhusus');
            let akunAktif = null;

            function segarkanAkun(pakaiDataAkun) {
                const role = roleById(parseInt(akunRole.value, 10));
                const admin = !!(role && role.is_admin);
                const wadah = document.querySelector('#akunHalaman .ha-halaman');
                document.getElementById('akunAdminInfo').style.display = admin ? 'flex' : 'none';
                document.getElementById('akunKhususWrap').style.display = admin ? 'none' : 'flex';
                document.getElementById('akunHalaman').style.display = admin ? 'none' : '';
                if (admin) return;
                if (akunKhusus.checked) {
                    if (pakaiDataAkun && akunAktif && akunAktif.akses_khusus) isiCentang(wadah, akunAktif.halaman);
                    setAktif(wadah, true);
                } else {
                    isiCentang(wadah, role ? role.halaman : []); // tampilkan halaman role (hanya lihat)
                    setAktif(wadah, false);
                }
            }
            function bukaAkun(id) {
                akunAktif = HA_AKUN.find(a => a.id === id);
                if (!akunAktif) return;
                document.getElementById('akunId').value = akunAktif.id;
                document.getElementById('akunJudul').textContent = 'Atur Akun: ' + akunAktif.username;
                if (akunAktif.role_id && roleById(akunAktif.role_id)) akunRole.value = akunAktif.role_id;
                akunKhusus.checked = akunAktif.akses_khusus;
                segarkanAkun(true);
                bukaModal('modalAkun');
            }
            akunRole.addEventListener('change', () => segarkanAkun(false));
            akunKhusus.addEventListener('change', () => {
                // baru dinyalakan: mulai dari halaman role supaya tinggal ditambah/dikurangi
                if (akunKhusus.checked) {
                    const wadah = document.querySelector('#akunHalaman .ha-halaman');
                    const role = roleById(parseInt(akunRole.value, 10));
                    isiCentang(wadah, akunAktif && akunAktif.akses_khusus ? akunAktif.halaman : (role ? role.halaman : []));
                }
                segarkanAkun(false);
            });
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

        // ---- Dropdowns ----
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

        // ---- Submenu ----
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
        // notifikasi sukses
        document.addEventListener('DOMContentLoaded', function () {
            const box = document.getElementById('successAlert');
            if (box) setTimeout(() => { box.style.transition = 'all .5s ease'; box.style.opacity = '0'; setTimeout(() => box.remove(), 500); }, 3000);
        });
    </script>
</body>

</html>
