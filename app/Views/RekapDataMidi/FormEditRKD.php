<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title>Edit Rekap Data Toko</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                    <a href="#" onclick="toggleSub(this);return false;" class="pc-link active flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-category"></i></span>
                        <span class="flex-1">Data Teknis NOC</span>
                        <i data-feather="chevron-right" class="arrow w-4 h-4 transition-transform"></i>
                    </a>
                    <ul class="submenu open bg-black/20">
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

        <div class="rfo-page p-6">
            <!-- JUDUL -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic is-blue"><i class="ti ti-edit"></i></span>
                    <div>
                        <h1 class="rfo-title">Edit Rekap Data Toko</h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">Rekap Data Toko All</p>
                    </div>
                </div>
                <a href="<?= site_url('RekapDataMidi') ?>" class="rfo-btn rfo-btn-gray">
                    <i class="ti ti-arrow-left"></i> Kembali
                </a>
            </div>

            <!-- Tampilkan error validasi server jika ada -->
            <?php if (session()->getFlashdata('errors')) : ?>
                <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                    <div class="font-semibold mb-1"><i class="ti ti-alert-circle"></i> Periksa kembali isian berikut:</div>
                    <ul class="list-disc pl-5 space-y-0.5">
                        <?php foreach (session()->getFlashdata('errors') as $err) : ?>
                            <li><?= esc($err) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <div class="rfo-panel rfo-form">
                <form action="<?= site_url('RekapDataMidi/update') ?>" method="POST" enctype="multipart/form-data" id="reportForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= esc($row['id']) ?>">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 mt-5">

                        <!-- Nama Pengisi -->
                        <div class="form-group">
                            <label>Nama Pengisi <span style="color:red">*</span></label>
                            <input type="text" name="nama_pengisi" id="r_nama_pengisi" maxlength="100" required
                                placeholder="Masukkan Nama Pengisi"
                                value="<?= esc(old('nama_pengisi', $row['nama_pengisi'] ?? '')) ?>"
                                class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                        </div>

                        <!-- Perusahaan -->
                        <div class="form-group">
                            <label>Perusahaan <span style="color:red">*</span></label>
                            <?php $valPerusahaan = old('perusahaan', $row['perusahaan'] ?? ''); ?>
                            <select name="perusahaan" id="r_perusahaan" required
                                class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                <option value="">-- Pilih Perusahaan --</option>
                                <option value="PT Genezis Mitra Technology" <?= $valPerusahaan === 'PT Genezis Mitra Technology' ? 'selected' : '' ?>>PT Genezis Mitra Technology</option>
                                <option value="PT Rtiga Global Media" <?= $valPerusahaan === 'PT Rtiga Global Media' ? 'selected' : '' ?>>PT Rtiga Global Media</option>
                            </select>
                        </div>

                        <!-- Tanggal -->
                        <div class="form-group">
                            <label>Tanggal <span style="color:red">*</span></label>
                            <input type="date" name="tanggal" id="r_tanggal" required
                                value="<?= esc(old('tanggal', $row['tanggal'] ?? '')) ?>"
                                class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                        </div>

                        <!-- Toko -->
                        <div class="form-group">
                            <label>Toko <span style="color:red">*</span></label>
                            <?php $valToko = old('toko', $row['toko'] ?? ''); ?>
                            <select name="toko" id="r_toko" required
                                class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                <option value="">-- Pilih Toko --</option>
                                <option value="Lawson" <?= $valToko === 'Lawson' ? 'selected' : '' ?>>Lawson</option>
                                <option value="Midi" <?= $valToko === 'Midi' ? 'selected' : '' ?>>Midi</option>
                            </select>
                        </div>

                        <!-- Branch (Dropdown + Isi Manual) -->
                        <div class="form-group md:col-span-2">
                            <label>Branch <span style="color:red">*</span></label>
                            <?php
                            $daftarBranch    = ['Bitung', 'Bekasi', 'Palu', 'Makassar', 'Manado', 'Samarinda', 'Pasuruan', 'Yogyakarta', 'Ambon', 'Kendari', 'Medan'];
                            $dbBranch        = $row['branch'] ?? '';
                            $isManualBranch  = old('branch') !== null
                                ? old('branch') === 'manual'
                                : ($dbBranch !== '' && !in_array($dbBranch, $daftarBranch, true));
                            $selectedBranch  = old('branch') ?? ($isManualBranch ? 'manual' : $dbBranch);
                            $manualValBranch = old('branch_manual') ?? ($isManualBranch ? $dbBranch : '');
                            ?>
                            <select name="branch" id="r_branch" required onchange="toggleManual('r_branch', 'wrap_branch_manual', 'r_branch_manual')"
                                class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                <option value="">-- Pilih Branch --</option>
                                <?php foreach ($daftarBranch as $b) : ?>
                                    <option value="<?= $b ?>" <?= $selectedBranch === $b ? 'selected' : '' ?>><?= $b ?></option>
                                <?php endforeach; ?>
                                <option value="manual" <?= $selectedBranch === 'manual' ? 'selected' : '' ?>>-- Isi Manual --</option>
                            </select>
                            <div id="wrap_branch_manual" class="<?= $isManualBranch ? '' : 'hidden' ?> mt-2">
                                <input type="text" name="branch_manual" id="r_branch_manual" maxlength="100"
                                    placeholder="Ketik nama Branch secara manual"
                                    value="<?= esc($manualValBranch) ?>"
                                    class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                            </div>
                        </div>

                        <!-- Kode Toko -->
                        <div class="form-group">
                            <label>Kode Toko <span style="color:red">*</span></label>
                            <input type="text" name="kode_toko" id="r_kode_toko" maxlength="50" required
                                placeholder="Contoh: L001 / M123"
                                value="<?= esc(old('kode_toko', $row['kode_toko'] ?? '')) ?>"
                                class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                        </div>

                        <!-- Nama Toko -->
                        <div class="form-group">
                            <label>Nama Toko <span style="color:red">*</span></label>
                            <input type="text" name="nama_toko" id="r_nama_toko" maxlength="150" required
                                placeholder="Masukkan Nama Toko"
                                value="<?= esc(old('nama_toko', $row['nama_toko'] ?? '')) ?>"
                                class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                        </div>

                        <!-- SECTION: FO & FORTIGATE -->
                        <div class="form-group md:col-span-2 border-t border-[#e3e8ee] dark:border-white/10 pt-4 mt-2">
                            <h6 class="text-sm font-semibold text-primary-600 mb-1">Perangkat Fortigate & Fiber Optic (FO)</h6>
                        </div>

                        <!-- Foto SN Fortigate -->
                        <div class="form-group md:col-span-2">
                            <label>Foto SN Fortigate <span class="text-gray-400 font-normal">(JPG, PNG, Word DOC/DOCX, PDF - maks. 5 MB)</span></label>
                            <input type="file" name="foto_sn_fortigate" id="r_foto_sn_fortigate"
                                accept=".jpg,.jpeg,.png,.doc,.docx,.pdf"
                                class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                            <?php if (!empty($row['foto_sn_fortigate'])) : ?>
                                <div class="mt-2 text-sm flex flex-wrap items-center gap-3">
                                    <span class="text-gray-500">File saat ini:</span>
                                    <a href="<?= base_url($row['foto_sn_fortigate']) ?>" target="_blank" rel="noopener" class="text-blue-600 hover:underline inline-flex items-center gap-1">
                                        <i class="ti ti-paperclip"></i> <?= esc(basename($row['foto_sn_fortigate'])) ?>
                                    </a>
                                    <label class="inline-flex items-center gap-2 mb-0 text-sm font-normal text-red-600">
                                        <input type="checkbox" name="hapus_foto_sn_fortigate" value="1"> Hapus file
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Pilih file baru untuk mengganti file saat ini.</p>
                            <?php endif; ?>
                        </div>

                        <!-- ID Pelanggan Jika Ada FO -->
                        <div class="form-group">
                            <label>ID Pelanggan Jika Ada FO</label>
                            <input type="text" name="id_pelanggan_fo" id="r_id_pelanggan_fo" maxlength="100"
                                placeholder="Kosongkan jika tidak menggunakan FO"
                                value="<?= esc(old('id_pelanggan_fo', $row['id_pelanggan_fo'] ?? '')) ?>"
                                class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                        </div>

                        <!-- SN ONT FO -->
                        <div class="form-group">
                            <label>SN ONT FO <span class="text-gray-400 font-normal">(JPG, PNG, Word, PDF - maks. 5 MB)</span></label>
                            <input type="file" name="sn_ont_fo" id="r_sn_ont_fo"
                                accept=".jpg,.jpeg,.png,.doc,.docx,.pdf"
                                class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                            <?php if (!empty($row['sn_ont_fo'])) : ?>
                                <div class="mt-2 text-sm flex flex-wrap items-center gap-3">
                                    <span class="text-gray-500">File saat ini:</span>
                                    <a href="<?= base_url($row['sn_ont_fo']) ?>" target="_blank" rel="noopener" class="text-blue-600 hover:underline inline-flex items-center gap-1">
                                        <i class="ti ti-paperclip"></i> <?= esc(basename($row['sn_ont_fo'])) ?>
                                    </a>
                                    <label class="inline-flex items-center gap-2 mb-0 text-sm font-normal text-red-600">
                                        <input type="checkbox" name="hapus_sn_ont_fo" value="1"> Hapus file
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Pilih file baru untuk mengganti file saat ini.</p>
                            <?php endif; ?>
                        </div>

                        <!-- SECTION: MODEM GSM 1 -->
                        <div class="form-group md:col-span-2 border-t border-[#e3e8ee] dark:border-white/10 pt-4 mt-2">
                            <h6 class="text-sm font-semibold text-primary-600 mb-1">Modem GSM 1</h6>
                        </div>

                        <!-- Tipe Modem GSM 1 -->
                        <div class="form-group md:col-span-2">
                            <label>Tipe Modem GSM 1 <span style="color:red">*</span></label>
                            <?php
                            $daftarModem1 = [
                                'Huawei B310',
                                'Huawei B311',
                                'Huawei B312',
                                'Advan V1 pro',
                                'Huawei B315',
                                'Huawei B320',
                                'Tidak ada modem GSM dilokasi'
                            ];
                            $dbModem1        = $row['tipe_modem_gsm_1'] ?? '';
                            $isManualModem1  = old('tipe_modem_gsm_1') !== null
                                ? old('tipe_modem_gsm_1') === 'manual'
                                : ($dbModem1 !== '' && !in_array($dbModem1, $daftarModem1, true));
                            $selectedModem1  = old('tipe_modem_gsm_1') ?? ($isManualModem1 ? 'manual' : $dbModem1);
                            $manualValModem1 = old('tipe_modem_gsm_1_manual') ?? ($isManualModem1 ? $dbModem1 : '');
                            ?>
                            <select name="tipe_modem_gsm_1" id="r_tipe_modem_gsm_1" required onchange="toggleManual('r_tipe_modem_gsm_1', 'wrap_modem1_manual', 'r_tipe_modem_gsm_1_manual')"
                                class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                <option value="">-- Pilih Tipe Modem GSM 1 --</option>
                                <?php foreach ($daftarModem1 as $m1) : ?>
                                    <option value="<?= $m1 ?>" <?= $selectedModem1 === $m1 ? 'selected' : '' ?>><?= $m1 ?></option>
                                <?php endforeach; ?>
                                <option value="manual" <?= $selectedModem1 === 'manual' ? 'selected' : '' ?>>-- Isi Manual --</option>
                            </select>
                            <div id="wrap_modem1_manual" class="<?= $isManualModem1 ? '' : 'hidden' ?> mt-2">
                                <input type="text" name="tipe_modem_gsm_1_manual" id="r_tipe_modem_gsm_1_manual" maxlength="100"
                                    placeholder="Ketik Tipe Modem GSM 1 secara manual"
                                    value="<?= esc($manualValModem1) ?>"
                                    class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                            </div>
                        </div>

                        <!-- Foto IMEI Modem 1 -->
                        <div class="form-group md:col-span-2">
                            <label>Foto IMEI Modem 1 <span class="text-gray-400 font-normal">(JPG, PNG, Word DOC/DOCX, PDF - maks. 5 MB)</span></label>
                            <input type="file" name="foto_imei_modem_1" id="r_foto_imei_modem_1"
                                accept=".jpg,.jpeg,.png,.doc,.docx,.pdf"
                                class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                            <?php if (!empty($row['foto_imei_modem_1'])) : ?>
                                <div class="mt-2 text-sm flex flex-wrap items-center gap-3">
                                    <span class="text-gray-500">File saat ini:</span>
                                    <a href="<?= base_url($row['foto_imei_modem_1']) ?>" target="_blank" rel="noopener" class="text-blue-600 hover:underline inline-flex items-center gap-1">
                                        <i class="ti ti-paperclip"></i> <?= esc(basename($row['foto_imei_modem_1'])) ?>
                                    </a>
                                    <label class="inline-flex items-center gap-2 mb-0 text-sm font-normal text-red-600">
                                        <input type="checkbox" name="hapus_foto_imei_modem_1" value="1"> Hapus file
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Pilih file baru untuk mengganti file saat ini.</p>
                            <?php endif; ?>
                        </div>

                        <!-- Nomor Telepon / MSISDN Modem 1 -->
                        <div class="form-group">
                            <label>Nomor Telepon / Nomor MSISDN (Modem 1)</label>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1.5">Contoh Jawaban : 08111930423</p>
                            <input type="text" name="msisdn_modem_1" id="r_msisdn_modem_1" maxlength="25"
                                placeholder="08111930423"
                                value="<?= esc(old('msisdn_modem_1', $row['msisdn_modem_1'] ?? '')) ?>"
                                class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                        </div>

                        <!-- Nomor ICCID Modem 1 -->
                        <div class="form-group">
                            <label>Nomor ICCID / 16 Angka di Kartu (Modem 1)</label>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1.5">Contoh Jawaban : 6210081415496256</p>
                            <input type="text" name="iccid_modem_1" id="r_iccid_modem_1" maxlength="25"
                                placeholder="6210081415496256"
                                value="<?= esc(old('iccid_modem_1', $row['iccid_modem_1'] ?? '')) ?>"
                                class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                        </div>

                        <!-- SECTION: MODEM GSM 2 -->
                        <div class="form-group md:col-span-2 border-t border-[#e3e8ee] dark:border-white/10 pt-4 mt-2">
                            <h6 class="text-sm font-semibold text-primary-600 mb-1">Modem GSM 2 (Opsional)</h6>
                        </div>

                        <!-- Tipe Modem GSM 2 -->
                        <div class="form-group md:col-span-2">
                            <label>Tipe Modem GSM 2</label>
                            <?php
                            $daftarModem2 = [
                                'Huawei B310',
                                'Huawei B311',
                                'Huawei B312',
                                'Advan V1 pro',
                                'Tidak ada modem GSM dilokasi'
                            ];
                            $dbModem2        = $row['tipe_modem_gsm_2'] ?? '';
                            $isManualModem2  = old('tipe_modem_gsm_2') !== null
                                ? old('tipe_modem_gsm_2') === 'manual'
                                : ($dbModem2 !== '' && !in_array($dbModem2, $daftarModem2, true));
                            $selectedModem2  = old('tipe_modem_gsm_2') ?? ($isManualModem2 ? 'manual' : $dbModem2);
                            $manualValModem2 = old('tipe_modem_gsm_2_manual') ?? ($isManualModem2 ? $dbModem2 : '');
                            ?>
                            <select name="tipe_modem_gsm_2" id="r_tipe_modem_gsm_2" onchange="toggleManual('r_tipe_modem_gsm_2', 'wrap_modem2_manual', 'r_tipe_modem_gsm_2_manual')"
                                class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                <option value="">-- Pilih Tipe Modem GSM 2 --</option>
                                <?php foreach ($daftarModem2 as $m2) : ?>
                                    <option value="<?= $m2 ?>" <?= $selectedModem2 === $m2 ? 'selected' : '' ?>><?= $m2 ?></option>
                                <?php endforeach; ?>
                                <option value="manual" <?= $selectedModem2 === 'manual' ? 'selected' : '' ?>>-- Isi Manual --</option>
                            </select>
                            <div id="wrap_modem2_manual" class="<?= $isManualModem2 ? '' : 'hidden' ?> mt-2">
                                <input type="text" name="tipe_modem_gsm_2_manual" id="r_tipe_modem_gsm_2_manual" maxlength="100"
                                    placeholder="Ketik Tipe Modem GSM 2 secara manual"
                                    value="<?= esc($manualValModem2) ?>"
                                    class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                            </div>
                        </div>

                        <!-- Nomor Telepon / MSISDN Modem 2 -->
                        <div class="form-group">
                            <label>Nomor Telepon / Nomor MSISDN (Modem 2)</label>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1.5">Contoh Jawaban : 08111930423</p>
                            <input type="text" name="msisdn_modem_2" id="r_msisdn_modem_2" maxlength="25"
                                placeholder="08111930423"
                                value="<?= esc(old('msisdn_modem_2', $row['msisdn_modem_2'] ?? '')) ?>"
                                class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                        </div>

                        <!-- Nomor ICCID Modem 2 -->
                        <div class="form-group">
                            <label>Nomor ICCID / 16 Angka di Kartu (Modem 2)</label>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1.5">Contoh Jawaban : 6210081415496256</p>
                            <input type="text" name="iccid_modem_2" id="r_iccid_modem_2" maxlength="25"
                                placeholder="6210081415496256"
                                value="<?= esc(old('iccid_modem_2', $row['iccid_modem_2'] ?? '')) ?>"
                                class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                        </div>

                        <!-- Sharelock / Tikor / Alamat -->
                        <div class="form-group md:col-span-2 border-t border-[#e3e8ee] dark:border-white/10 pt-4 mt-2">
                            <label>Sharelock / Tikor / Alamat <span style="color:red">*</span></label>
                            <textarea name="sharelock_tikor_alamat" id="r_sharelock_tikor_alamat" rows="3" required
                                placeholder="Masukkan Link Sharelock Google Maps, Titik Koordinat, atau Alamat Lengkap Toko"
                                class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none"><?= esc(old('sharelock_tikor_alamat', $row['sharelock_tikor_alamat'] ?? '')) ?></textarea>
                        </div>

                    </div>

                    <div class="d-flex">
                        <button type="submit"><i class="ti ti-device-floppy"></i> Update</button>
                        <button type="button" class="btn-back" onclick="window.location.href='<?= site_url('RekapDataMidi') ?>'"><i class="ti ti-arrow-left"></i> Kembali</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
        const MAX_FILE = 5 * 1024 * 1024; // 5 MB
        const EXT_FILE = ['pdf', 'png', 'jpg', 'jpeg', 'doc', 'docx'];

        function toggleManual(selectId, wrapId, inputId) {
            const val = document.getElementById(selectId).value;
            const wrap = document.getElementById(wrapId);
            const input = document.getElementById(inputId);

            if (val === 'manual') {
                wrap.classList.remove('hidden');
                input.focus();
            } else {
                wrap.classList.add('hidden');
                input.value = '';
            }
        }

        // ====== VALIDASI SEBELUM SUBMIT ======
        document.getElementById('reportForm').addEventListener('submit', function(e) {
            const peringatan = (title, text) => {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title,
                    text,
                    confirmButtonColor: '#185a82'
                });
            };

            if (
                document.getElementById('r_nama_pengisi').value.trim() === '' ||
                document.getElementById('r_perusahaan').value === '' ||
                document.getElementById('r_tanggal').value === '' ||
                document.getElementById('r_toko').value === '' ||
                document.getElementById('r_branch').value === '' ||
                document.getElementById('r_kode_toko').value.trim() === '' ||
                document.getElementById('r_nama_toko').value.trim() === '' ||
                document.getElementById('r_tipe_modem_gsm_1').value === '' ||
                document.getElementById('r_sharelock_tikor_alamat').value.trim() === ''
            ) {
                return peringatan('Form Belum Lengkap', 'Mohon lengkapi semua kolom yang bertanda bintang merah (*).');
            }

            if (document.getElementById('r_branch').value === 'manual' && document.getElementById('r_branch_manual').value.trim() === '') {
                return peringatan('Branch Manual Kosong', 'Silakan ketik nama Branch secara manual.');
            }
            if (document.getElementById('r_tipe_modem_gsm_1').value === 'manual' && document.getElementById('r_tipe_modem_gsm_1_manual').value.trim() === '') {
                return peringatan('Tipe Modem 1 Kosong', 'Silakan ketik Tipe Modem GSM 1 secara manual.');
            }
            if (document.getElementById('r_tipe_modem_gsm_2').value === 'manual' && document.getElementById('r_tipe_modem_gsm_2_manual').value.trim() === '') {
                return peringatan('Tipe Modem 2 Kosong', 'Silakan ketik Tipe Modem GSM 2 secara manual.');
            }

            const fileFields = [{
                    id: 'r_foto_sn_fortigate',
                    label: 'Foto SN Fortigate'
                },
                {
                    id: 'r_sn_ont_fo',
                    label: 'SN ONT FO'
                },
                {
                    id: 'r_foto_imei_modem_1',
                    label: 'Foto IMEI Modem 1'
                }
            ];

            for (const f of fileFields) {
                const file = document.getElementById(f.id).files[0];
                if (file) {
                    const ext = file.name.split('.').pop().toLowerCase();
                    if (!EXT_FILE.includes(ext)) {
                        return peringatan('File Tidak Didukung', f.label + ' harus berformat JPG, PNG, Word (DOC/DOCX), atau PDF.');
                    }
                    if (file.size > MAX_FILE) {
                        return peringatan('File Terlalu Besar', f.label + ' maksimal berukuran 5 MB.');
                    }
                }
            }
        });
    </script>
    <script>
        // PENGHALANG KOSMETIK SAJA — bukan security, mudah dilewati
        document.addEventListener('contextmenu', e => e.preventDefault());
        document.addEventListener('keydown', e => {
            if (e.key === 'F12') e.preventDefault();
            if (e.ctrlKey && e.shiftKey && ['I', 'J', 'C'].includes(e.key.toUpperCase())) e.preventDefault();
            if (e.ctrlKey && e.key.toUpperCase() === 'U') e.preventDefault();
        });
    </script>
    <?php if (!session()->get('logged_in')) : ?>
        <script>
            window.location.href = "<?= base_url('/login') ?>";
        </script>
    <?php endif; ?>
</body>

</html>