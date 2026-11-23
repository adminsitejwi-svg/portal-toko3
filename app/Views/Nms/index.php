<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title>NMS - Network Management System</title>
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
                        <span class="flex-1">NMS JWI Group</span>
                        <i data-feather="chevron-right" class="arrow w-4 h-4 transition-transform"></i>
                    </a>
                    <ul class="submenu bg-black/20">
                        <li><a href="<?= site_url('DashboardNetmon') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Dashboard Netmon</a></li>
                        <li><a href="<?= site_url('DeviceMonitoring') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Device Monitoring</a></li>
                        <li><a href="<?= site_url('TrafficMonitoring') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Traffic Monitoring</a></li>
                        <li><a href="<?= site_url('Nms') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] text-white font-semibold">NMS Management</a></li>
                        <li><a href="<?= site_url('Alert') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Alert</a></li>
                        <li><a href="<?= site_url('Screen') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Screen</a></li>
                    </ul>
                </li>
                <li class="hasmenu">
                    <a href="#" onclick="toggleSub(this);return false;" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-server-cog"></i></span>
                        <span class="flex-1">ACS JWI Group</span>
                        <i data-feather="chevron-right" class="arrow w-4 h-4 transition-transform"></i>
                    </a>
                    <ul class="submenu bg-black/20">
                        <li><a href="<?= site_url('DashboardAcs') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Dashboard ACS</a></li>
                        <li><a href="<?= site_url('AcsDevice') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Devices</a></li>
                        <li><a href="<?= site_url('AcsParameter') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Parameter Master</a></li>
                        <li><a href="<?= site_url('AcsProvisioning') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Provisioning</a></li>
                        <li><a href="<?= site_url('AcsSetting') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Settings</a></li>
                        <li><a href="<?= site_url('AcsLog') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Log Koneksi</a></li>
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


        <div class="rfo-page p-6" id="nmsPage">
            <?php
            $tab      = $tab ?? 'live';
            $live     = $live ?? ['ringkas' => ['total' => 0, 'up' => 0, 'down' => 0, 'lain' => 0], 'devices' => [], 'device' => 0, 'interface' => 0, 'ifaces' => []];
            $periode  = $periode ?? '24h';
            $mulai    = $mulai ?? '';
            $akhir    = $akhir ?? '';
            $custom   = $mulai !== '' && $akhir !== '';
            $nm       = \App\Models\DeviceNmsModel::class;
            $deviceId = (int) $live['device'];
            $urlTab   = static fn ($t) => site_url('Nms') . '?' . http_build_query(['tab' => $t, 'device' => $deviceId ?: null, 'interface' => $live['interface'] ?: null]);
            ?>

            <!-- JUDUL -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic is-sky"><i class="ti ti-network"></i></span>
                    <div>
                        <h1 class="rfo-title">Network Management System</h1>
                        <p class="rfo-subtitle">Pantauan terpusat perangkat jaringan: status, latency, packet loss, CPU, RAM &amp; traffic interface, live maupun history</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <?php if ($tabelSiap) : ?>
                        <?php if ($tab === 'live') : ?>
                            <span class="nms-live"><span class="nms-dot"></span> LIVE <span id="nmsJam" class="nms-jam"></span></span>
                        <?php else : ?>
                            <span class="nms-live is-history"><i class="ti ti-history"></i> HISTORY</span>
                        <?php endif; ?>
                        <button type="button" id="nmsFullscreen" class="rfo-btn rfo-btn-gray border-0 cursor-pointer"><i class="ti ti-maximize"></i> <span>Fullscreen</span></button>
                        <?php if ($tab === 'live' && $live['devices']) : ?>
                            <button type="button" id="nmsPollBtn" class="rfo-btn rfo-btn-green border-0 cursor-pointer"><i class="ti ti-refresh"></i> Poll Sekarang</button>
                        <?php endif; ?>
                    <?php endif; ?>
                    <a href="<?= site_url('DeviceMonitoring') ?>" class="rfo-btn rfo-btn-gray"><i class="ti ti-device-desktop-analytics"></i> Device Monitoring</a>
                </div>
            </div>

            <?php if (! $tabelSiap) : ?>
                <div class="rfo-panel mb-6">
                    <p class="font-semibold text-red-600"><i class="ti ti-database-off"></i> Tabel NMS belum ada.</p>
                    <p class="rfo-subtitle mt-1">Jalankan <b>database/d_device_monitoring.sql</b> dan <b>database/d_device_traffic.sql</b> (kalau belum), lalu <b>database/d_device_nms.sql</b> di database, kemudian muat ulang halaman ini.</p>
                </div>
            <?php else : ?>

                <!-- TAB -->
                <div class="nms-tabs mb-5">
                    <a href="<?= esc($urlTab('live'), 'attr') ?>" class="<?= $tab === 'live' ? 'is-active' : '' ?>"><i class="ti ti-broadcast"></i> Live Monitoring</a>
                    <a href="<?= esc($urlTab('history'), 'attr') ?>" class="<?= $tab === 'history' ? 'is-active' : '' ?>"><i class="ti ti-chart-histogram"></i> History Monitoring</a>
                </div>

                <!-- KARTU RINGKASAN DEVICE -->
                <div class="grid grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
                    <div class="rfo-stat rfo-stat-blue">
                        <span class="rfo-stat-ic"><i class="ti ti-devices"></i></span>
                        <div>
                            <div class="rfo-stat-label">Total Device</div>
                            <div class="rfo-stat-value" id="stTotal"><?= (int) $live['ringkas']['total'] ?></div>
                            <div class="rfo-stat-note">Terdaftar di Device Monitoring</div>
                        </div>
                        <i class="ti ti-chart-bar rfo-stat-chart"></i>
                    </div>
                    <div class="rfo-stat rfo-stat-green">
                        <span class="rfo-stat-ic"><i class="ti ti-circle-check"></i></span>
                        <div>
                            <div class="rfo-stat-label">Up</div>
                            <div class="rfo-stat-value" id="stUp"><?= (int) $live['ringkas']['up'] ?></div>
                            <div class="rfo-stat-note">SNMP membalas</div>
                        </div>
                        <i class="ti ti-chart-bar rfo-stat-chart"></i>
                    </div>
                    <div class="rfo-stat rfo-stat-red">
                        <span class="rfo-stat-ic"><i class="ti ti-circle-x"></i></span>
                        <div>
                            <div class="rfo-stat-label">Down</div>
                            <div class="rfo-stat-value" id="stDown"><?= (int) $live['ringkas']['down'] ?></div>
                            <div class="rfo-stat-note">Tidak membalas SNMP</div>
                        </div>
                        <i class="ti ti-chart-bar rfo-stat-chart"></i>
                    </div>
                    <div class="rfo-stat rfo-stat-purple">
                        <span class="rfo-stat-ic"><i class="ti ti-help-circle"></i></span>
                        <div>
                            <div class="rfo-stat-label">Unknown / Off</div>
                            <div class="rfo-stat-value" id="stLain"><?= (int) $live['ringkas']['lain'] ?></div>
                            <div class="rfo-stat-note">Belum di-poll / monitoring mati</div>
                        </div>
                        <i class="ti ti-chart-bar rfo-stat-chart"></i>
                    </div>
                </div>

                <?php if (! $live['devices']) : ?>
                    <div class="rfo-panel mb-6">
                        <p class="font-semibold"><i class="ti ti-devices"></i> Belum ada device.</p>
                        <p class="rfo-subtitle mt-1">Tambahkan perangkat di <a class="rfo-link" href="<?= site_url('DeviceMonitoring/create') ?>">Device Monitoring</a> (SNMP v2c aktif). Latency, CPU, RAM &amp; traffic akan muncul di sini setelah di-poll.</p>
                    </div>
                <?php else : ?>

                    <!-- PILIH DEVICE + INFO -->
                    <div class="rfo-panel mb-6">
                        <div class="flex flex-wrap items-center justify-between gap-4">
                            <div class="flex flex-wrap items-center gap-3">
                                <label class="rfo-field">
                                    <i class="ti ti-router" style="color:#2f6bff"></i>
                                    <select id="nmsDevice" class="nms-select" title="Pilih Device">
                                        <?php foreach ($live['devices'] as $d) : ?>
                                            <option value="<?= (int) $d['id'] ?>" <?= $deviceId === $d['id'] ? 'selected' : '' ?>><?= esc($d['hostname']) ?> (<?= esc($d['ip']) ?>)</option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                                <div>
                                    <div class="nms-dev-nama"><span id="devNama"></span> <span id="devStatus"></span></div>
                                    <div class="rb-sub mt-1" id="devInfo"></div>
                                </div>
                            </div>
                            <div class="nms-dev-meta" id="devMeta"></div>
                        </div>
                    </div>

                    <?php if ($tab === 'live') : ?>
                        <!-- KPI DEVICE TERPILIH -->
                        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
                            <div class="nms-kpi">
                                <div class="nms-kpi-label"><i class="ti ti-clock-bolt" style="color:#0ea5e9"></i> Ping Latency</div>
                                <div class="nms-kpi-value" id="kLat">-</div>
                                <div class="rb-sub" id="kLatSub">ICMP</div>
                            </div>
                            <div class="nms-kpi">
                                <div class="nms-kpi-label"><i class="ti ti-wave-square" style="color:#ef4444"></i> Packet Loss</div>
                                <div class="nms-kpi-value" id="kLoss">-</div>
                                <div class="rb-sub" id="kLossSub">ICMP</div>
                            </div>
                            <div class="nms-kpi">
                                <div class="nms-kpi-label"><i class="ti ti-cpu" style="color:#16a34a"></i> CPU Usage</div>
                                <div class="nms-kpi-value" id="kCpu">-</div>
                                <div class="nms-meter"><span id="kCpuBar"></span></div>
                            </div>
                            <div class="nms-kpi">
                                <div class="nms-kpi-label"><i class="ti ti-database" style="color:#8b5cf6"></i> Memory Usage</div>
                                <div class="nms-kpi-value" id="kMem">-</div>
                                <div class="nms-meter"><span id="kMemBar"></span></div>
                                <div class="rb-sub mt-1" id="kMemSub"></div>
                            </div>
                        </div>

                        <!-- TRAFFIC LIVE INTERFACE TERPILIH -->
                        <div class="rfo-panel mb-6" id="panelLive">
                            <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
                                <h3 class="nms-judul"><i class="ti ti-chart-area-line"></i> Live Traffic <span class="rb-sub" id="liveNama"></span></h3>
                                <label class="rfo-field">
                                    <i class="ti ti-plug-connected" style="color:#6d5dfc"></i>
                                    <select id="nmsIface" class="nms-select nms-select-lebar" title="Pilih Interface"></select>
                                </label>
                            </div>
                            <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-5">
                                <div class="tm-mini">
                                    <div class="tm-mini-label"><i class="ti ti-arrow-down" style="color:#7eb26d"></i> RX / Download</div>
                                    <div class="tm-mini-value" id="lRx">-</div>
                                    <div class="rb-sub">Rate sampel terakhir</div>
                                </div>
                                <div class="tm-mini">
                                    <div class="tm-mini-label"><i class="ti ti-arrow-up" style="color:#eab839"></i> TX / Upload</div>
                                    <div class="tm-mini-value" id="lTx">-</div>
                                    <div class="rb-sub">Rate sampel terakhir</div>
                                </div>
                                <div class="tm-mini">
                                    <div class="tm-mini-label"><i class="ti ti-gauge" style="color:#f59e0b"></i> Utilisasi</div>
                                    <div class="tm-mini-value" id="lUtil">-</div>
                                    <div class="rb-sub" id="lSpeed">Speed -</div>
                                </div>
                                <div class="tm-mini">
                                    <div class="tm-mini-label"><i class="ti ti-clock" style="color:#16a34a"></i> Sampel Terakhir</div>
                                    <div class="tm-mini-value nms-kecil" id="lWaktu">-</div>
                                    <div class="rb-sub">Sesuai polling interval device</div>
                                </div>
                            </div>
                            <div class="nms-chart-box">
                                <canvas id="cLive"></canvas>
                                <div id="cLiveKosong" class="nms-chart-kosong" hidden><i class="ti ti-chart-line"></i> Menunggu sampel traffic interface ini (1 jam terakhir).</div>
                            </div>
                        </div>

                        <!-- DAFTAR INTERFACE -->
                        <div class="rfo-panel mb-6">
                            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                                <div>
                                    <h3 class="nms-judul"><i class="ti ti-plug-connected"></i> Interface Monitoring</h3>
                                    <span class="rb-sub" id="ifJumlah"></span>
                                </div>
                                <button type="button" id="ifToggle" class="rfo-ghost-btn" aria-expanded="true"><i class="ti ti-eye-off"></i> <span>Sembunyikan Interface List</span></button>
                            </div>
                            <div id="ifBody">
                                <div class="flex flex-wrap items-center gap-3 mb-4">
                                    <div class="rfo-search">
                                        <i class="ti ti-search"></i>
                                        <input type="text" id="ifCari" placeholder="Cari interface / comment..." />
                                    </div>
                                    <label class="rfo-field">
                                        <i class="ti ti-activity" style="color:#16a34a"></i>
                                        <select id="ifStatus" class="nms-select nms-select-kecil" title="Filter Status">
                                            <option value="">Semua Status</option>
                                            <option>Up</option>
                                            <option>Down</option>
                                            <option>Disabled</option>
                                            <option>Not Present</option>
                                            <option>Unknown</option>
                                        </select>
                                    </label>
                                    <label class="rfo-field">
                                        <i class="ti ti-list-numbers" style="color:#f59e0b"></i>
                                        <select id="ifPer" class="nms-select nms-select-kecil" title="Baris per halaman">
                                            <?php foreach ([10, 25, 50, 100] as $n) : ?>
                                                <option value="<?= $n ?>" <?= $n === 25 ? 'selected' : '' ?>><?= $n ?> / halaman</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </label>
                                    <button type="button" id="ifReset" class="rfo-ghost-btn"><i class="ti ti-refresh"></i> Reset</button>
                                </div>
                                <div class="table-scroll">
                                    <table class="rfo-table nms-table">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Aksi</th>
                                                <th>Interface</th>
                                                <th>Speed</th>
                                                <th>Admin</th>
                                                <th>Status</th>
                                                <th>RX / Download</th>
                                                <th>TX / Upload</th>
                                                <th>Utilisasi</th>
                                                <th>Sampel Terakhir</th>
                                            </tr>
                                        </thead>
                                        <tbody id="ifTbody"></tbody>
                                    </table>
                                </div>
                                <div class="nms-pager" id="ifPager"></div>
                            </div>
                        </div>

                        <!-- SEMUA DEVICE -->
                        <div class="rfo-panel">
                            <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                                <h3 class="nms-judul"><i class="ti ti-server-2"></i> All Monitored Devices</h3>
                                <div class="rfo-search">
                                    <i class="ti ti-search"></i>
                                    <input type="text" id="devCari" placeholder="Cari hostname, IP, vendor, lokasi..." />
                                </div>
                            </div>
                            <div class="table-scroll">
                                <table class="rfo-table nms-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Device</th>
                                            <th>Group / Lokasi</th>
                                            <th>Status</th>
                                            <th>Latency</th>
                                            <th>Packet Loss</th>
                                            <th>CPU</th>
                                            <th>RAM</th>
                                            <th>Uptime</th>
                                            <th>Poll Terakhir</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="devTbody"></tbody>
                                </table>
                            </div>
                            <p class="rf-hint mt-3"><i class="ti ti-info-circle"></i> Selama halaman ini terbuka, device di-poll otomatis sesuai polling interval masing-masing (dicek tiap 15 detik).
                                <?= $bisaPing ? 'Latency &amp; packet loss dari ping ICMP server.' : 'Server tidak bisa menjalankan ping, latency memakai waktu respon SNMP.' ?>
                                CPU &amp; RAM lewat SNMP (HOST-RESOURCES-MIB, Cisco, Juniper). Riwayat disimpan <?= $nm::RETENSI_HARI ?> hari, traffic <?= \App\Models\DeviceTrafficModel::RETENSI_HARI ?> hari. <span id="infoPoll"></span></p>
                        </div>

                    <?php else : ?>
                        <!-- FILTER HISTORY -->
                        <div class="rfo-panel mb-6">
                            <div class="flex flex-wrap items-end gap-3">
                                <div>
                                    <div class="nms-label">Rentang</div>
                                    <div class="nms-seg" id="hMode">
                                        <button type="button" data-mode="periode" class="<?= $custom ? '' : 'is-active' ?>">Periode</button>
                                        <button type="button" data-mode="custom" class="<?= $custom ? 'is-active' : '' ?>">Tanggal Custom</button>
                                    </div>
                                </div>
                                <div id="hPeriodeWrap" <?= $custom ? 'hidden' : '' ?>>
                                    <div class="nms-label">Periode</div>
                                    <div class="nms-seg" id="hPeriode">
                                        <?php foreach ($nm::PERIODE as $kode => [$lbl]) : ?>
                                            <button type="button" data-periode="<?= $kode ?>" class="<?= $kode === $periode ? 'is-active' : '' ?>"><?= $lbl ?></button>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div id="hCustomWrap" class="flex flex-wrap items-end gap-3" <?= $custom ? '' : 'hidden' ?>>
                                    <label>
                                        <span class="nms-label">Tanggal mulai</span>
                                        <input type="date" id="hMulai" class="nms-input" value="<?= esc($mulai, 'attr') ?>" max="<?= date('Y-m-d') ?>">
                                    </label>
                                    <label>
                                        <span class="nms-label">Tanggal akhir</span>
                                        <input type="date" id="hAkhir" class="nms-input" value="<?= esc($akhir, 'attr') ?>" max="<?= date('Y-m-d') ?>">
                                    </label>
                                </div>
                                <label>
                                    <span class="nms-label">Interface (grafik traffic)</span>
                                    <select id="hIface" class="nms-input nms-select-lebar" title="Pilih interface: grafik traffic langsung diperbarui">
                                        <?php if (! $live['ifaces']) : ?>
                                            <option value="0">Belum ada interface</option>
                                        <?php endif; ?>
                                        <?php foreach ($live['ifaces'] as $i) : ?>
                                            <option value="<?= $i['id'] ?>" <?= (int) $live['interface'] === $i['id'] ? 'selected' : '' ?>><?= esc($i['nama'] . $i['beda'] . ($i['alias'] ? ' (' . $i['alias'] . ')' : '')) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </label>
                                <button type="button" id="hExport" class="rfo-btn rfo-btn-gray border-0 cursor-pointer"><i class="ti ti-file-type-pdf"></i> Export PDF</button>
                            </div>
                            <p class="rf-hint mt-3"><i class="ti ti-calendar"></i> <span id="hLabel">-</span>. Riwayat latency, packet loss, CPU &amp; RAM disimpan <?= $nm::RETENSI_HARI ?> hari, traffic interface <?= \App\Models\DeviceTrafficModel::RETENSI_HARI ?> hari.</p>
                        </div>

                        <!-- POP UP EXPORT PDF: pilih perusahaan / logo (sama dengan form RFO) -->
                        <div id="pdfModal" class="fixed inset-0 z-[2000] hidden items-center justify-center bg-black/50 p-4">
                            <form id="pdfForm" class="nms-pdf" role="dialog" aria-modal="true" aria-labelledby="pdfJudul">
                                <div class="nms-pdf-head">
                                    <h3 id="pdfJudul" class="nms-judul"><i class="ti ti-file-type-pdf"></i> Export PDF</h3>
                                    <button type="button" class="nms-pdf-x" data-tutup aria-label="Tutup">&times;</button>
                                </div>
                                <div class="nms-pdf-isi">
                                    <label for="pdfPerusahaan" class="nms-label"><i class="ti ti-building-skyscraper"></i> Perusahaan / Logo <span style="color:#ef4444">*</span></label>
                                    <select id="pdfPerusahaan" class="nms-input" required>
                                        <option value="">-- Pilih Perusahaan --</option>
                                        <?php foreach ($perusahaan as $nama => $fileLogo) : ?>
                                            <option value="<?= esc($nama, 'attr') ?>" data-logo="<?= base_url($fileLogo) ?>"><?= esc($nama) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <div id="pdfLogoBox" class="rfo-logo-box hidden mt-3">
                                        <img id="pdfLogo" src="" alt="logo perusahaan" class="h-20 w-auto max-w-full object-contain">
                                    </div>
                                </div>
                                <div class="nms-pdf-kaki">
                                    <button type="button" class="rfo-ghost-btn" data-tutup>Batal</button>
                                    <button type="submit" class="rfo-btn rfo-btn-blue border-0 cursor-pointer nms-pdf-ok"><i class="ti ti-file-export"></i> Export</button>
                                </div>
                            </form>
                        </div>

                        <!-- RINGKASAN HISTORY -->
                        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
                            <div class="nms-kpi">
                                <div class="nms-kpi-label"><i class="ti ti-heartbeat" style="color:#16a34a"></i> Availability (ping)</div>
                                <div class="nms-kpi-value" id="hAvail">-</div>
                                <div class="rb-sub" id="hSampel">-</div>
                            </div>
                            <div class="nms-kpi">
                                <div class="nms-kpi-label"><i class="ti ti-clock-bolt" style="color:#0ea5e9"></i> Latency rata-rata / puncak</div>
                                <div class="nms-kpi-value nms-kecil" id="hLat">-</div>
                                <div class="rb-sub" id="hLoss">Packet loss rata-rata -</div>
                            </div>
                            <div class="nms-kpi">
                                <div class="nms-kpi-label"><i class="ti ti-cpu" style="color:#16a34a"></i> CPU rata-rata / puncak</div>
                                <div class="nms-kpi-value nms-kecil" id="hCpu">-</div>
                            </div>
                            <div class="nms-kpi">
                                <div class="nms-kpi-label"><i class="ti ti-database" style="color:#8b5cf6"></i> RAM rata-rata / puncak</div>
                                <div class="nms-kpi-value nms-kecil" id="hMem">-</div>
                            </div>
                        </div>

                        <!-- GRAFIK HISTORY: 4 grafik pertama milik DEVICE (tidak bergantung interface), grafik traffic milik INTERFACE -->
                        <?php $namaDev = ''; foreach ($live['devices'] as $d) { if ($d['id'] === $deviceId) { $namaDev = $d['hostname']; } } ?>
                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-5" id="hGrafik">
                            <?php foreach (['cLat' => ['ti-clock-bolt', 'Ping Latency (ms)'], 'cLoss' => ['ti-wave-square', 'Packet Loss (%)'], 'cCpu' => ['ti-cpu', 'CPU Usage (%)'], 'cMem' => ['ti-database', 'Memory Usage (%)']] as $id => [$ikon, $judul]) : ?>
                                <div class="rfo-panel">
                                    <h3 class="nms-judul mb-4"><i class="ti <?= $ikon ?>"></i> <?= $judul ?> <span class="nms-skop" title="Data perangkat, sama untuk semua interface"><i class="ti ti-router"></i> Device · <?= esc($namaDev) ?></span></h3>
                                    <div class="nms-chart-box is-kecil">
                                        <canvas id="<?= $id ?>"></canvas>
                                        <div id="<?= $id ?>Kosong" class="nms-chart-kosong" hidden><i class="ti ti-chart-line"></i> Belum ada data pada rentang ini.</div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <div class="rfo-panel xl:col-span-2">
                                <h3 class="nms-judul mb-2"><i class="ti ti-chart-area-line"></i> Interface Traffic RX / TX <span class="nms-skop is-if"><i class="ti ti-plug-connected"></i> Per interface</span></h3>
                                <p class="rb-sub mb-4"><span id="hIfNama"></span> <span id="hTrafRingkas"></span></p>
                                <div class="nms-chart-box">
                                    <canvas id="cTraf"></canvas>
                                    <div id="cTrafKosong" class="nms-chart-kosong" hidden><i class="ti ti-chart-line"></i> Belum ada riwayat traffic interface ini pada rentang ini.</div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <style>
        .nms-tabs { display: flex; gap: 6px; border-bottom: 1px solid var(--rf-border); flex-wrap: wrap; }
        .nms-tabs a { padding: 11px 16px; font-size: 13px; font-weight: 700; color: var(--rf-muted); border-bottom: 2px solid transparent; margin-bottom: -1px; display: inline-flex; align-items: center; gap: 6px; }
        .nms-tabs a:hover { color: var(--rf-text); }
        .nms-tabs a.is-active { color: #2f6bff; border-color: #2f6bff; }
        .nms-live { display: inline-flex; align-items: center; gap: 7px; font-size: 12px; font-weight: 700; color: #16a34a; letter-spacing: .04em; }
        .nms-live.is-history { color: var(--rf-muted); }
        .nms-jam { color: var(--rf-muted); font-weight: 600; }
        .nms-dot { width: 8px; height: 8px; border-radius: 50%; background: #22c55e; box-shadow: 0 0 0 0 rgba(34, 197, 94, .6); animation: nmsDenyut 1.6s infinite; }
        @keyframes nmsDenyut { 70% { box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); } 100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); } }
        .rfo-field .nms-select {
            height: 42px; border: 1px solid var(--rf-border); border-radius: 10px; padding: 0 36px 0 38px;
            font-size: 13px; font-weight: 600; color: var(--rf-text); background-color: var(--rf-card); outline: none; cursor: pointer;
            width: 280px; max-width: 100%; appearance: none; -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%237a869a' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 14px center;
        }
        .rfo-field .nms-select-kecil { width: 170px; }
        .nms-select-lebar { width: 340px !important; }
        .nms-input { display: block; height: 42px; border: 1px solid var(--rf-border); border-radius: 10px; padding: 0 12px; font-size: 13px; font-weight: 600; color: var(--rf-text); background: var(--rf-card); outline: none; max-width: 100%; }
        .nms-label { display: block; font-size: 11px; font-weight: 700; color: var(--rf-muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: .04em; }
        .nms-seg { display: inline-flex; flex-wrap: wrap; border: 1px solid var(--rf-border); border-radius: 10px; overflow: hidden; }
        .nms-seg button { height: 40px; padding: 0 14px; font-size: 13px; font-weight: 600; color: var(--rf-muted); background: var(--rf-card); border: 0; cursor: pointer; }
        .nms-seg button + button { border-left: 1px solid var(--rf-border); }
        .nms-seg button.is-active { background: #2f6bff; color: #fff; }
        .nms-dev-nama { font-size: 16px; font-weight: 700; color: var(--rf-text); display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .nms-dev-meta { display: flex; flex-wrap: wrap; gap: 8px 22px; font-size: 12px; color: var(--rf-muted); }
        .nms-dev-meta b { display: block; color: var(--rf-text); font-size: 13px; }
        .nms-judul { font-size: 15px; font-weight: 700; color: var(--rf-text); display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
        .nms-kpi, .tm-mini { border: 1px solid var(--rf-border); border-radius: 12px; padding: 16px 18px; background: var(--rf-card); min-width: 0; }
        .tm-mini { background: var(--rf-soft); border-radius: 10px; padding: 14px 16px; }
        .nms-kpi-label, .tm-mini-label { font-size: 12px; color: var(--rf-muted); margin-bottom: 6px; font-weight: 600; }
        .nms-kpi-value { font-size: 24px; font-weight: 700; color: var(--rf-text); line-height: 1.3; }
        .tm-mini-value { font-size: 20px; font-weight: 700; color: var(--rf-text); line-height: 1.3; }
        .nms-kecil { font-size: 16px !important; }
        .nms-meter { height: 6px; border-radius: 3px; background: var(--rf-border); overflow: hidden; margin-top: 8px; }
        .nms-meter span { display: block; height: 100%; width: 0; background: #22c55e; transition: width .4s ease; }
        .nms-chart-box { position: relative; height: 320px; }
        .nms-chart-box.is-kecil { height: 260px; }
        .nms-chart-kosong { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; gap: 6px; color: var(--rf-muted); font-size: 14px; text-align: center; }
        .nms-chart-kosong[hidden] { display: none; }
        /* Tabel NMS diisi JavaScript (bukan DataTables): rfo.css menyembunyikan .rfo-table tanpa kelas .dataTable,
           jadi dimunculkan lagi di sini + gaya disamakan dengan tabel DataTables halaman lain */
        .rfo-page table.rfo-table.nms-table { visibility: visible; width: 100%; min-width: 1100px; border-collapse: collapse; margin: 0; }
        .nms-table thead th { background: var(--rf-soft); color: var(--rf-head); font-weight: 700; font-size: 12.5px; text-align: left; padding: 14px; border-bottom: 1px solid var(--rf-border); white-space: nowrap; }
        .nms-table tbody td { padding: 12px 14px; font-size: 13px; color: var(--rf-text); border-bottom: 1px solid var(--rf-border); vertical-align: middle; white-space: nowrap; }
        .nms-table tbody tr:last-child td { border-bottom: 0; }
        .nms-table tbody tr:hover td { background: var(--rf-soft); }
        .nms-table tbody tr.is-pilih td { background: rgba(47, 107, 255, .08); }
        .nms-table .nms-kosong { text-align: center; color: var(--rf-muted); padding: 26px 10px; white-space: normal; }
        .tm-bar { display: inline-block; vertical-align: middle; width: 60px; height: 6px; border-radius: 3px; background: var(--rf-border); margin-right: 8px; overflow: hidden; }
        .tm-bar span { display: block; height: 100%; background: linear-gradient(90deg, #22c55e, #f59e0b 70%, #ef4444); }
        .nms-pager { display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap; padding-top: 14px; font-size: 12px; color: var(--rf-muted); }
        .nms-pager button { height: 32px; padding: 0 12px; border-radius: 8px; border: 1px solid var(--rf-border); background: var(--rf-card); color: var(--rf-text); font-weight: 600; cursor: pointer; }
        .nms-pager button:disabled { opacity: .4; cursor: default; }
        #ifBody[hidden] { display: none !important; }
        .nms-skop { display: inline-flex; align-items: center; gap: 4px; font-size: 11px; font-weight: 700; padding: 3px 9px; border-radius: 999px; background: rgba(47, 107, 255, .10); color: #2f6bff; }
        .nms-skop.is-if { background: rgba(109, 93, 252, .12); color: #6d5dfc; }
        .nms-pdf { width: 100%; max-width: 460px; border-radius: 16px; background: var(--rf-card); border: 1px solid var(--rf-border); box-shadow: 0 24px 60px rgba(15, 23, 42, .35); overflow: hidden; }
        .nms-pdf-head, .nms-pdf-kaki { display: flex; align-items: center; justify-content: space-between; padding: 15px 20px; border-bottom: 1px solid var(--rf-border); }
        .nms-pdf-kaki { justify-content: flex-end; gap: 10px; border: 0; border-top: 1px solid var(--rf-border); }
        .nms-pdf-isi { padding: 18px 20px; }
        .nms-pdf-isi .nms-input { width: 100%; }
        .nms-pdf-x { border: 0; background: transparent; font-size: 24px; line-height: 1; color: var(--rf-muted); cursor: pointer; }
        .nms-pdf-ok { height: 42px; padding: 0 20px; font-size: 13px; }
        /* Panel grafik gelap (public/d/tampilann grafik.jpg), sama di mode terang & gelap */
        #hGrafik .rfo-panel, #panelLive .nms-chart-box { background: #181b1f !important; border: 1px solid #2c3036; border-radius: 6px; }
        #panelLive .nms-chart-box { padding: 14px; }
        #hGrafik .nms-judul { justify-content: center; color: #e3e5e8; font-size: 14px; font-weight: 600; }
        #hGrafik .rb-sub { color: #a5abb5; text-align: center; }
        #hGrafik .rb-sub b { color: #e3e5e8; }
        #hGrafik .nms-skop { background: rgba(110, 208, 224, .14); color: #6ed0e0; }
        #hGrafik .nms-skop.is-if { background: rgba(234, 184, 57, .15); color: #eab839; }
        #hGrafik .nms-chart-kosong, #panelLive .nms-chart-kosong { color: #a5abb5; }
        #panelLive .nms-chart-box canvas { transition: opacity .2s ease; }
        #panelLive.is-muat .nms-chart-box canvas { opacity: .35; }
        #panelLive.is-muat .nms-chart-kosong { display: none; }
        #panelLive.is-muat .nms-chart-box::after { content: "Memuat data..."; position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; color: #e3e5e8; }
        #hGrafik .nms-chart-box { transition: opacity .2s ease; }
        #hGrafik.is-muat .nms-chart-box { opacity: .35; }
        #hGrafik.is-muat .nms-chart-box::after { content: "Memuat data..."; position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; color: #e3e5e8; }
        /* Fullscreen: halaman konten saja (tanpa sidebar & header), cocok untuk layar NOC */
        #nmsPage:fullscreen { overflow-y: auto; background: #f4f7fa; padding: 24px; }
        .dark #nmsPage:fullscreen { background: #1d2630; }
        #nmsPage:fullscreen .nms-chart-box { height: 360px; }
        @media (max-width: 640px) {
            .nms-chart-box { height: 250px; }
            .rfo-field .nms-select, .nms-select-lebar, .nms-input { width: 100% !important; }
            .nms-kpi-value { font-size: 19px; }
        }
    </style>

    <script>
        (function() {
            const TAB = <?= json_encode($tab) ?>;
            const SIAP = <?= json_encode((bool) $tabelSiap && ! empty($live['devices'])) ?>;
            const URL_NMS = '<?= site_url('Nms') ?>';
            const URL_DATA = '<?= site_url('Nms/data') ?>';
            const URL_HISTORY = '<?= site_url('Nms/history') ?>';
            const URL_EXPORT = '<?= site_url('Nms/export') ?>';
            let S = <?= json_encode($live, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE) ?>;

            const $ = id => document.getElementById(id);
            const esc = s => String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
            const angka = (v, d) => v === null || v === undefined ? null : Number(v).toLocaleString('id-ID', { minimumFractionDigits: d, maximumFractionDigits: d });
            const fmt = (v, d, sat) => v === null || v === undefined ? '-' : angka(v, d) + sat;

            function formatBps(v) {
                if (v === null || v === undefined) return '-';
                for (const [s, b] of [['Gbps', 1e9], ['Mbps', 1e6], ['Kbps', 1e3]]) {
                    if (v >= b) return (v / b).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ' + s;
                }
                return Math.round(v).toLocaleString('id-ID') + ' bps';
            }

            const BADGE = {
                'Up': ['rb-green', 'ti-circle-check'], 'Down': ['rb-red', 'ti-circle-x'], 'Disabled': ['rb-amber', 'ti-plug-off'],
                'Not Present': ['rb-purple', 'ti-unlink'], 'Unknown': ['rb-blue', 'ti-help-circle'],
                'Monitoring Off': ['rb-amber', 'ti-eye-off'], 'SNMP Off': ['rb-amber', 'ti-plug-off'], 'v3 Pending': ['rb-purple', 'ti-key']
            };
            const badge = st => {
                const [k, i] = BADGE[st] || BADGE.Unknown;
                return '<span class="rb ' + k + '"><i class="ti ' + i + '"></i>' + esc(st) + '</span>';
            };
            const warnaPersen = v => v === null ? '#22c55e' : (v >= 85 ? '#ef4444' : (v >= 60 ? '#f59e0b' : '#22c55e'));
            const devPilih = () => S.devices.find(d => d.id === S.device) || null;

            // ====== GRAFIK (assets/theme/nms-grafik.js), panel gelap di semua tema ======
            const grafik = [];
            const W = window.NmsGrafik ? NmsGrafik.WARNA : {};

            function buatGrafik(id, sets, fmtY, opsi) {
                const g = window.NmsGrafik ? NmsGrafik.buat($(id), [], sets, fmtY, opsi) : null;
                if (g) grafik.push(g);
                return g;
            }

            function isiGrafik(g, idKosong, label, data) {
                if (!g) return;
                g.data.labels = label;
                data.forEach((d, i) => g.data.datasets[i].data = d);
                g.update('none');
                $(idKosong).hidden = label.length > 0;
            }

            const fmtMs = v => fmt(v, 1, ' ms');
            const fmtPersen = v => fmt(v, 0, '%');
            const setRx = { label: 'RX / Download', borderColor: W.hijau };
            const setTx = { label: 'TX / Upload', borderColor: W.kuning };

            // ====== INFO DEVICE TERPILIH (kedua tab) ======
            function renderDevice() {
                const d = devPilih();
                if (!d) return;
                $('devNama').textContent = d.hostname;
                $('devStatus').innerHTML = badge(d.status);
                $('devInfo').textContent = [d.ip, d.vendor + (d.model ? ' ' + d.model : ''), d.group, d.lokasi].filter(Boolean).join(' / ');
                $('devMeta').innerHTML =
                    '<div>Uptime<b>' + esc(d.uptime || '-') + '</b></div>' +
                    '<div>Poll terakhir<b>' + esc(d.poll || '-') + '</b></div>' +
                    '<div>Interval<b>' + esc(d.interval) + ' detik</b></div>';
            }

            function urlState(extra) {
                const u = new URL(window.location.href);
                u.searchParams.set('tab', TAB);
                u.searchParams.set('device', S.device);
                if (S.interface) u.searchParams.set('interface', S.interface); else u.searchParams.delete('interface');
                Object.entries(extra || {}).forEach(([k, v]) => v === null || v === '' ? u.searchParams.delete(k) : u.searchParams.set(k, v));
                history.replaceState(null, '', u);
            }

            if (!SIAP) return;

            // ganti device = muat ulang halaman (interface ikut berganti)
            $('nmsDevice').addEventListener('change', function() {
                const u = new URL(URL_NMS);
                u.searchParams.set('tab', TAB);
                u.searchParams.set('device', this.value);
                if (TAB === 'history') {
                    const q = new URL(window.location.href).searchParams;
                    ['periode', 'mulai', 'akhir'].forEach(k => q.get(k) && u.searchParams.set(k, q.get(k)));
                }
                window.location.href = u;
            });

            // ====== FULLSCREEN ======
            const halaman = $('nmsPage');
            const btnFs = $('nmsFullscreen');
            if (btnFs && halaman.requestFullscreen) {
                btnFs.addEventListener('click', () => {
                    (document.fullscreenElement === halaman ? document.exitFullscreen() : halaman.requestFullscreen()).catch(() => {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Browser tidak dapat mengaktifkan fullscreen.', confirmButtonColor: '#2f6bff' });
                    });
                });
                document.addEventListener('fullscreenchange', () => {
                    const aktif = document.fullscreenElement === halaman;
                    btnFs.querySelector('i').className = 'ti ' + (aktif ? 'ti-minimize' : 'ti-maximize');
                    btnFs.querySelector('span').textContent = aktif ? 'Keluar Fullscreen' : 'Fullscreen';
                    grafik.forEach(g => g.resize());
                });
            } else if (btnFs) {
                btnFs.hidden = true;
            }

            renderDevice();

            if (TAB === 'live') {
                // ====== LIVE MONITORING ======
                const gLive = buatGrafik('cLive', [setRx, setTx], formatBps, { maks: 1000 });
                let halIf = 1;

                function renderKpi() {
                    const d = devPilih();
                    const sumber = d && d.sumber === 'snmp' ? 'Waktu respon SNMP' : 'ICMP ping';
                    $('kLat').textContent = fmt(d && d.latency, 2, ' ms');
                    $('kLatSub').textContent = sumber;
                    $('kLoss').textContent = fmt(d && d.loss, 0, '%');
                    $('kLoss').style.color = d && d.loss > 0 ? '#ef4444' : '';
                    $('kLossSub').textContent = sumber;
                    $('kCpu').textContent = fmt(d && d.cpu, 1, '%');
                    $('kCpuBar').style.width = Math.min(100, (d && d.cpu) || 0) + '%';
                    $('kCpuBar').style.background = warnaPersen(d && d.cpu);
                    $('kMem').textContent = fmt(d && d.mem, 1, '%');
                    $('kMemBar').style.width = Math.min(100, (d && d.mem) || 0) + '%';
                    $('kMemBar').style.background = warnaPersen(d && d.mem);
                    $('kMemSub').textContent = (d && d.mem_teks) || (d && d.cpu === null && d.mem === null ? 'SNMP tidak mengirim data CPU/RAM' : '');
                }

                function renderLive() {
                    const sel = $('nmsIface');
                    sel.innerHTML = S.ifaces.length
                        ? S.ifaces.map(i => '<option value="' + i.id + '"' + (i.id === S.interface ? ' selected' : '') + '>' + esc(i.nama + i.beda + (i.alias ? ' (' + i.alias + ')' : '')) + '</option>').join('')
                        : '<option value="">Belum ada interface</option>';
                    sel.disabled = !S.ifaces.length;
                    const i = S.ifaces.find(x => x.id === S.interface);
                    $('liveNama').textContent = i ? '· ' + i.nama + (i.alias ? ' (' + i.alias + ')' : '') : '';
                    $('lRx').textContent = i ? i.rx_teks : '-';
                    $('lTx').textContent = i ? i.tx_teks : '-';
                    $('lUtil').textContent = i && i.util !== null ? fmt(i.util, 1, '%') : '-';
                    $('lSpeed').textContent = 'Speed ' + (i ? i.speed : '-');
                    $('lWaktu').textContent = (i && i.waktu) || '-';
                    isiGrafik(gLive, 'cLiveKosong', S.live.label, [S.live.rx, S.live.tx]);
                }

                function renderInterface() {
                    const cari = $('ifCari').value.trim().toLowerCase();
                    const st = $('ifStatus').value;
                    const per = parseInt($('ifPer').value, 10);
                    const list = S.ifaces.filter(i => (!st || i.status === st) &&
                        (!cari || (i.nama + ' ' + (i.alias || '') + ' ' + (i.descr || '') + i.beda).toLowerCase().includes(cari)));
                    const hal = Math.max(1, Math.ceil(list.length / per));
                    halIf = Math.min(halIf, hal);
                    const awal = (halIf - 1) * per;
                    const isi = list.slice(awal, awal + per);
                    $('ifJumlah').textContent = S.ifaces.length + ' interface' + (list.length !== S.ifaces.length ? ', ' + list.length + ' cocok dengan filter' : '');
                    $('ifTbody').innerHTML = isi.length ? isi.map((i, no) =>
                        '<tr class="' + (i.id === S.interface ? 'is-pilih' : '') + '">' +
                        '<td>' + (awal + no + 1) + '</td>' +
                        '<td class="ra-aksi"><button type="button" class="act act-view" data-live="' + i.id + '" title="Tampilkan di Live Traffic"><i class="ti ti-chart-line"></i> Live</button> ' +
                        '<a class="act act-edit" href="' + URL_NMS + '?tab=history&device=' + S.device + '&interface=' + i.id + '&periode=24h" title="History 24 jam"><i class="ti ti-history"></i> History</a></td>' +
                        '<td><div class="col-bold">' + esc(i.nama + i.beda) + '</div>' + (i.alias ? '<div class="rb-sub">' + esc(i.alias) + '</div>' : '') + '</td>' +
                        '<td>' + esc(i.speed) + '</td><td>' + esc(i.admin) + '</td><td>' + badge(i.status) + '</td>' +
                        '<td>' + esc(i.rx_teks) + '</td><td>' + esc(i.tx_teks) + '</td>' +
                        '<td>' + (i.util !== null ? '<div class="tm-bar"><span style="width:' + Math.min(100, i.util) + '%"></span></div>' + fmt(i.util, 1, '%') : '-') + '</td>' +
                        '<td>' + esc(i.waktu || '-') + '</td>' +
                        '</tr>').join('') : '<tr><td colspan="10" class="nms-kosong">' + (S.ifaces.length ? 'Tidak ada interface yang cocok dengan filter.' : 'Belum ada data interface. Traffic mulai terhitung dari polling kedua.') + '</td></tr>';
                    $('ifPager').innerHTML = '<span>Menampilkan ' + (list.length ? awal + 1 : 0) + '–' + Math.min(list.length, awal + per) + ' dari ' + list.length + ' interface</span>' +
                        '<span><button type="button" data-hal="' + (halIf - 1) + '"' + (halIf <= 1 ? ' disabled' : '') + '>‹ Prev</button> <span style="padding:0 8px">Halaman ' + halIf + ' / ' + hal + '</span> ' +
                        '<button type="button" data-hal="' + (halIf + 1) + '"' + (halIf >= hal ? ' disabled' : '') + '>Next ›</button></span>';
                }

                function renderSemuaDevice() {
                    const cari = $('devCari').value.trim().toLowerCase();
                    const list = S.devices.filter(d => !cari || [d.hostname, d.ip, d.vendor, d.model, d.group, d.lokasi].join(' ').toLowerCase().includes(cari));
                    $('devTbody').innerHTML = list.length ? list.map((d, no) =>
                        '<tr class="' + (d.id === S.device ? 'is-pilih' : '') + '">' +
                        '<td>' + (no + 1) + '</td>' +
                        '<td><div class="col-bold">' + esc(d.hostname) + '</div><div class="rb-sub">' + esc(d.ip) + ' · ' + esc(d.vendor) + '</div></td>' +
                        '<td>' + esc(d.group) + (d.lokasi ? '<div class="rb-sub">' + esc(d.lokasi) + '</div>' : '') + '</td>' +
                        '<td>' + badge(d.status) + '</td>' +
                        '<td>' + fmt(d.latency, 2, ' ms') + '</td>' +
                        '<td style="' + (d.loss > 0 ? 'color:#ef4444;font-weight:700' : '') + '">' + fmt(d.loss, 0, '%') + '</td>' +
                        '<td>' + fmt(d.cpu, 1, '%') + '</td><td>' + fmt(d.mem, 1, '%') + '</td>' +
                        '<td>' + esc(d.uptime || '-') + '</td><td>' + esc(d.poll || '-') + '</td>' +
                        '<td class="ra-aksi"><a class="act act-view" href="' + URL_NMS + '?tab=live&device=' + d.id + '"><i class="ti ti-eye"></i> Detail</a></td>' +
                        '</tr>').join('') : '<tr><td colspan="11" class="nms-kosong">Tidak ada device yang cocok.</td></tr>';
                }

                function renderSemua() {
                    $('stTotal').textContent = S.ringkas.total;
                    $('stUp').textContent = S.ringkas.up;
                    $('stDown').textContent = S.ringkas.down;
                    $('stLain').textContent = S.ringkas.lain;
                    $('nmsJam').textContent = S.jam || '';
                    renderDevice();
                    renderKpi();
                    renderLive();
                    renderInterface();
                    renderSemuaDevice();
                }

                // ambil data terbaru (server mem-poll device yang sudah waktunya)
                let sedang = false;
                let nomorPilih = 0;

                function muat(manual) {
                    if (sedang) return Promise.resolve();
                    sedang = true;
                    const minta = S.interface;
                    return fetch(URL_DATA + '?device=' + S.device + '&interface=' + (S.interface || 0), { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                        .then(r => r.json())
                        .then(res => {
                            if (!res.ok) return;
                            if (S.interface !== minta) {
                                // interface sudah diganti saat menunggu: pilihan & grafik yang baru tidak ditimpa data interface lama
                                res.interface = S.interface;
                                res.live = S.live;
                            } else {
                                $('panelLive').classList.remove('is-muat');
                            }
                            S = res;
                            renderSemua();
                            $('infoPoll').textContent = 'Terakhir dicek ' + res.jam + (res.dipoll ? ' (' + res.dipoll + ' device di-poll).' : '.');
                        })
                        .catch(() => {
                            if (manual) Swal.fire({ icon: 'error', title: 'Gagal', text: 'Polling gagal, coba lagi.', confirmButtonColor: '#2f6bff' });
                        })
                        .finally(() => sedang = false);
                }

                function pilihInterface(id) {
                    S.interface = parseInt(id, 10) || 0;
                    urlState();
                    const n = ++nomorPilih;
                    const panel = $('panelLive');
                    panel.classList.add('is-muat'); // tanda sedang memuat (grafik diredupkan), seperti History Monitoring
                    renderLive();
                    renderInterface();
                    fetch(URL_DATA + '?device=' + S.device + '&interface=' + S.interface + '&poll=0', { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                        .then(r => r.json())
                        .then(res => {
                            if (n !== nomorPilih) return; // sudah pilih interface lain
                            if (!res.ok) throw new Error(res.pesan || 'gagal');
                            panel.classList.remove('is-muat');
                            S = res;
                            renderSemua();
                        })
                        .catch(() => {
                            if (n !== nomorPilih || !panel.classList.contains('is-muat')) return;
                            // grafik interface sebelumnya dikosongkan agar tidak dikira milik interface yang baru dipilih
                            panel.classList.remove('is-muat');
                            S.live = { label: [], rx: [], tx: [] };
                            renderLive();
                            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Data grafik gagal dimuat.', confirmButtonColor: '#2f6bff' });
                        });
                }

                $('nmsIface').addEventListener('change', function() { pilihInterface(this.value); });
                $('ifTbody').addEventListener('click', e => {
                    const b = e.target.closest('[data-live]');
                    if (!b) return;
                    pilihInterface(b.dataset.live);
                    $('panelLive').scrollIntoView({ behavior: 'smooth', block: 'start' });
                });
                $('ifPager').addEventListener('click', e => {
                    const b = e.target.closest('[data-hal]');
                    if (b && !b.disabled) { halIf = parseInt(b.dataset.hal, 10); renderInterface(); }
                });
                ['ifCari', 'ifStatus', 'ifPer'].forEach(id => $(id).addEventListener('input', () => { halIf = 1; renderInterface(); }));
                $('ifReset').addEventListener('click', () => { $('ifCari').value = ''; $('ifStatus').value = ''; $('ifPer').value = '25'; halIf = 1; renderInterface(); });
                $('devCari').addEventListener('input', renderSemuaDevice);

                // sembunyikan / tampilkan daftar interface (diingat di browser)
                const KUNCI = 'nms.ifHidden';
                function aturIf(sembunyi) {
                    $('ifBody').hidden = sembunyi;
                    $('ifToggle').setAttribute('aria-expanded', sembunyi ? 'false' : 'true');
                    $('ifToggle').innerHTML = '<i class="ti ' + (sembunyi ? 'ti-eye' : 'ti-eye-off') + '"></i> <span>' + (sembunyi ? 'Tampilkan' : 'Sembunyikan') + ' Interface List</span>';
                    try { localStorage.setItem(KUNCI, sembunyi ? '1' : '0'); } catch (e) {}
                }
                let awalSembunyi = false;
                try { awalSembunyi = localStorage.getItem(KUNCI) === '1'; } catch (e) {}
                aturIf(awalSembunyi);
                $('ifToggle').addEventListener('click', () => aturIf(!$('ifBody').hidden));

                $('nmsPollBtn').addEventListener('click', function() {
                    this.disabled = true;
                    this.innerHTML = '<i class="ti ti-loader"></i> Polling...';
                    muat(true).finally(() => { this.disabled = false; this.innerHTML = '<i class="ti ti-refresh"></i> Poll Sekarang'; });
                });

                renderSemua();
                urlState();
                muat(false);
                setInterval(() => { if (!document.hidden) muat(false); }, 15000);
            } else {
                // ====== HISTORY MONITORING ======
                const gLat = buatGrafik('cLat', [{ label: 'Latency (ms)', borderColor: W.biru }], fmtMs);
                const gLoss = buatGrafik('cLoss', [{ label: 'Packet Loss (%)', borderColor: W.merah }], fmtPersen, { maks: 100 });
                const gCpu = buatGrafik('cCpu', [{ label: 'CPU (%)', borderColor: W.hijau }], fmtPersen, { maks: 100 });
                const gMem = buatGrafik('cMem', [{ label: 'RAM (%)', borderColor: W.ungu }], fmtPersen, { maks: 100 });
                const gTraf = buatGrafik('cTraf', [setRx, setTx], formatBps, { maks: 1000 });

                let mode = $('hMode').querySelector('.is-active').dataset.mode;
                let periode = $('hPeriode').querySelector('.is-active')?.dataset.periode || '24h';

                $('hMode').addEventListener('click', e => {
                    const b = e.target.closest('button');
                    if (!b) return;
                    mode = b.dataset.mode;
                    $('hMode').querySelectorAll('button').forEach(x => x.classList.toggle('is-active', x === b));
                    $('hPeriodeWrap').hidden = mode !== 'periode';
                    $('hCustomWrap').hidden = mode !== 'custom';
                    muat(true); // Tanggal Custom: langsung dimuat kalau kedua tanggal sudah terisi
                });
                $('hPeriode').addEventListener('click', e => {
                    const b = e.target.closest('button');
                    if (!b) return;
                    periode = b.dataset.periode;
                    $('hPeriode').querySelectorAll('button').forEach(x => x.classList.toggle('is-active', x === b));
                    muat();
                });
                // semua pilihan otomatis memuat grafik (tanpa tombol Tampilkan)
                $('hIface').addEventListener('change', () => muat(true));
                ['hMulai', 'hAkhir'].forEach(id => $(id).addEventListener('change', () => muat(true)));

                // parameter rentang; null kalau tanggal custom belum valid.
                // diam = true: tanggal yang belum lengkap diabaikan tanpa peringatan (masih diisi pengguna)
                function param(diam) {
                    const p = { device: S.device, interface: parseInt($('hIface').value, 10) || 0 };
                    if (mode === 'custom') {
                        const a = $('hMulai').value, b = $('hAkhir').value;
                        if (diam && (!a || !b)) return null;
                        if (!a || !b || a > b) {
                            Swal.fire({ icon: 'warning', title: 'Tanggal belum valid', text: 'Isi tanggal mulai dan akhir (tanggal mulai tidak boleh setelah tanggal akhir).', confirmButtonColor: '#2f6bff' });
                            return null;
                        }
                        p.mulai = a;
                        p.akhir = b;
                    } else {
                        p.periode = periode;
                    }
                    return p;
                }

                let nomor = 0;

                function muat(diam) {
                    const p = param(diam);
                    if (!p) return;
                    S.interface = p.interface;
                    urlState({ periode: p.periode || null, mulai: p.mulai || null, akhir: p.akhir || null });
                    const n = ++nomor;
                    $('hGrafik').classList.add('is-muat'); // tanda sedang memuat (grafik diredupkan)
                    fetch(URL_HISTORY + '?' + new URLSearchParams(p), { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                        .then(r => r.json())
                        .then(res => {
                            if (n !== nomor || !res.ok) return;
                            $('hGrafik').classList.remove('is-muat');
                            const d = res.device, r = d.ringkas;
                            $('hLabel').textContent = 'Rentang: ' + res.label_rentang;
                            $('hAvail').textContent = fmt(r.uptime, 2, '%');
                            $('hSampel').textContent = r.sampel + ' sampel polling';
                            $('hLat').textContent = fmt(r.lat_avg, 2, ' ms') + ' / ' + fmt(r.lat_max, 2, ' ms');
                            $('hLoss').textContent = 'Packet loss rata-rata ' + fmt(r.loss_avg, 2, '%');
                            $('hCpu').textContent = fmt(r.cpu_avg, 1, '%') + ' / ' + fmt(r.cpu_max, 1, '%');
                            $('hMem').textContent = fmt(r.mem_avg, 1, '%') + ' / ' + fmt(r.mem_max, 1, '%');
                            isiGrafik(gLat, 'cLatKosong', d.label, [d.latency]);
                            isiGrafik(gLoss, 'cLossKosong', d.label, [d.loss]);
                            isiGrafik(gCpu, 'cCpuKosong', d.cpu.some(v => v !== null) ? d.label : [], [d.cpu]);
                            isiGrafik(gMem, 'cMemKosong', d.mem.some(v => v !== null) ? d.label : [], [d.mem]);
                            const t = res.traffic;
                            const opsi = $('hIface').selectedOptions[0];
                            $('hIfNama').innerHTML = t && opsi ? '<b>' + esc(opsi.textContent) + '</b> ·' : '';
                            $('hTrafRingkas').textContent = t ? 'RX rata-rata ' + t.ringkas.rx_avg + ' (puncak ' + t.ringkas.rx_max + ') · TX rata-rata ' + t.ringkas.tx_avg + ' (puncak ' + t.ringkas.tx_max + ')' : '';
                            isiGrafik(gTraf, 'cTrafKosong', t ? t.label : [], t ? [t.rx, t.tx] : [[], []]);
                        })
                        .catch(() => {
                            if (n !== nomor) return;
                            $('hGrafik').classList.remove('is-muat');
                            Swal.fire({ icon: 'error', title: 'Gagal', text: 'Data history gagal dimuat.', confirmButtonColor: '#2f6bff' });
                        });
                }

                // ====== EXPORT PDF: pilih perusahaan / logo dulu ======
                const pdfModal = $('pdfModal'), pdfPilih = $('pdfPerusahaan');
                const KEY_LOGO = 'nms.pdf.perusahaan'; // pilihan terakhir diingat di browser ini

                function pdfTampil(ya) {
                    pdfModal.classList.toggle('hidden', !ya);
                    pdfModal.classList.toggle('flex', ya);
                    (ya ? pdfPilih : $('hExport')).focus();
                }

                function pdfLogo() {
                    const logo = pdfPilih.selectedOptions[0]?.dataset.logo;
                    if (logo) $('pdfLogo').src = logo;
                    $('pdfLogoBox').classList.toggle('hidden', !logo);
                }

                $('hExport').addEventListener('click', () => {
                    if (!param()) return; // tanggal custom belum valid: peringatan sudah tampil
                    if (!pdfPilih.value) {
                        try { pdfPilih.value = localStorage.getItem(KEY_LOGO) || ''; } catch (e) { }
                        if (pdfPilih.selectedIndex < 0) pdfPilih.value = '';
                    }
                    pdfLogo();
                    pdfTampil(true);
                });
                pdfPilih.addEventListener('change', pdfLogo);
                pdfModal.addEventListener('click', e => {
                    if (e.target === pdfModal || e.target.closest('[data-tutup]')) pdfTampil(false);
                });
                document.addEventListener('keydown', e => {
                    if (e.key === 'Escape' && pdfModal.classList.contains('flex')) pdfTampil(false);
                });
                $('pdfForm').addEventListener('submit', e => {
                    e.preventDefault();
                    const p = param();
                    if (!p || !pdfPilih.value) return;
                    try { localStorage.setItem(KEY_LOGO, pdfPilih.value); } catch (err) { }
                    p.perusahaan = pdfPilih.value;
                    pdfTampil(false);
                    window.open(URL_EXPORT + '?' + new URLSearchParams(p), '_blank', 'noopener');
                });

                muat();
            }
        })();
    </script>

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