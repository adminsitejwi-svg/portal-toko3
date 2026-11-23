<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title>Dashboard Netmon</title>
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
    <script src="<?= base_url('assets/theme/rose-grafik.js') ?>?v=<?= @filemtime(FCPATH . 'assets/theme/rose-grafik.js') ?>"></script>

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
                        <li><a href="<?= site_url('DashboardNetmon') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] text-white font-semibold">Dashboard Netmon</a></li>
                        <li><a href="<?= site_url('DeviceMonitoring') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Device Monitoring</a></li>
                        <li><a href="<?= site_url('TrafficMonitoring') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Traffic Monitoring</a></li>
                        <li><a href="<?= site_url('Nms') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">NMS JWI Group</a></li>
                        <li><a href="<?= site_url('Alert') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Alert</a></li>
                        <li><a href="<?= site_url('Screen') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Screen</a></li>
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


        <div class="rfo-page p-6" id="dashPage">
            <!-- JUDUL -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic is-sky"><i class="ti ti-dashboard"></i></span>
                    <div>
                        <h1 class="rfo-title">Network Overview</h1>
                        <p class="rfo-subtitle">Ringkasan status &amp; performa seluruh perangkat jaringan</p>
                    </div>
                </div>
                <?php if ($tabelSiap) : ?>
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="dn-jam" id="dnJam"></span>
                        <button type="button" id="dnRefresh" class="rfo-btn rfo-btn-blue border-0 cursor-pointer"><i class="ti ti-refresh"></i> Refresh Data</button>
                    </div>
                <?php endif; ?>
            </div>

            <?php if (! $tabelSiap) : ?>
                <div class="rfo-panel mb-6">
                    <p class="font-semibold text-red-600"><i class="ti ti-database-off"></i> Tabel monitoring belum ada.</p>
                    <p class="rfo-subtitle mt-1">Jalankan <b>database/d_device_monitoring.sql</b> (kalau belum), lalu <b>database/d_device_nms.sql</b> di database.</p>
                </div>
            <?php else : ?>
                <!-- KARTU STATUS -->
                <div class="grid grid-cols-2 xl:grid-cols-4 gap-5 mb-5">
                    <?php foreach ([
                        ['blue', 'ti-server-2', 'Total Devices', 'stTotal', 'Perangkat terdaftar'],
                        ['green', 'ti-circle-check', 'Devices Online', 'stOnline', 'Beroperasi saat ini'],
                        ['red', 'ti-circle-x', 'Devices Offline', 'stOffline', 'Tidak merespons'],
                        ['amber', 'ti-help-circle', 'Unknown Status', 'stUnknown', 'Belum dipantau'],
                    ] as [$warna, $ikon, $label, $id, $ket]) : ?>
                        <div class="rfo-stat rfo-stat-<?= $warna ?>">
                            <span class="rfo-stat-ic"><i class="ti <?= $ikon ?>"></i></span>
                            <div>
                                <div class="rfo-stat-label"><?= $label ?></div>
                                <div class="rfo-stat-value" id="<?= $id ?>">-</div>
                                <div class="rfo-stat-note"><?= $ket ?></div>
                            </div>
                            <i class="ti ti-chart-bar rfo-stat-chart"></i>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- AVAILABILITY & RATA-RATA -->
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
                    <div class="dn-kpi">
                        <div class="dn-kpi-label"><span>Network Availability</span><i class="ti ti-heartbeat" style="color:#16a34a"></i></div>
                        <div class="dn-kpi-value" id="kAvail">-</div>
                        <div class="dn-meter"><span id="kAvailBar"></span></div>
                        <div class="dn-kpi-note" id="kAvailNote"></div>
                    </div>
                    <div class="dn-kpi">
                        <div class="dn-kpi-label"><span>Average Latency</span><i class="ti ti-clock-bolt" style="color:#0ea5e9"></i></div>
                        <div class="dn-kpi-value" id="kLat">-</div>
                        <div class="dn-kpi-note">Rata-rata respon ping device online</div>
                    </div>
                    <div class="dn-kpi">
                        <div class="dn-kpi-label"><span>Average CPU Load</span><i class="ti ti-cpu" style="color:#16a34a"></i></div>
                        <div class="dn-kpi-value" id="kCpu">-</div>
                        <div class="dn-kpi-note">Rata-rata pemakaian CPU</div>
                    </div>
                    <div class="dn-kpi">
                        <div class="dn-kpi-label"><span>Average RAM Usage</span><i class="ti ti-database" style="color:#8b5cf6"></i></div>
                        <div class="dn-kpi-value" id="kRam">-</div>
                        <div class="dn-kpi-note">Rata-rata pemakaian memori</div>
                    </div>
                </div>

                <!-- GRAFIK -->
                <div class="dn-grafik mb-6">
                    <div class="dn-gelap">
                        <div class="dn-gelap-head">
                            <div>
                                <h3><i class="ti ti-chart-area-line"></i> Network Performance</h3>
                                <p>Riwayat pemakaian CPU &amp; RAM</p>
                            </div>
                            <select id="dnDevice" class="dn-select" title="Pilih device" aria-label="Pilih device"></select>
                        </div>
                        <div class="dn-chart">
                            <canvas id="cPerforma"></canvas>
                            <div id="cPerformaKosong" class="dn-chart-kosong" hidden><i class="ti ti-chart-line"></i> Belum ada data CPU &amp; RAM 24 jam terakhir.</div>
                        </div>
                    </div>
                    <div class="rfo-panel dn-pie">
                        <h3 class="dn-judul"><i class="ti ti-chart-pie"></i> Device Distribution</h3>
                        <p class="dn-sub">Status perangkat saat ini</p>
                        <div class="dn-rose" id="dnRose"></div>
                    </div>
                </div>

                <!-- DEVICE TERBARU -->
                <div class="rfo-panel mb-6">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div>
                            <h3 class="dn-judul"><i class="ti ti-server-2"></i> Network Devices</h3>
                            <p class="dn-sub">10 perangkat terakhir didaftarkan</p>
                        </div>
                        <?php if ($bisaDevice) : ?><a href="<?= site_url('DeviceMonitoring') ?>" class="rfo-ghost-btn">View All <i class="ti ti-arrow-up-right"></i></a><?php endif; ?>
                    </div>
                    <div class="table-scroll">
                        <table class="rfo-table dn-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Device Name</th>
                                    <th>IP Address</th>
                                    <th>Vendor</th>
                                    <th>Device Group</th>
                                    <th>Status</th>
                                    <th>Latency</th>
                                    <th>CPU Load</th>
                                    <th>RAM Usage</th>
                                </tr>
                            </thead>
                            <tbody id="dnDevices"></tbody>
                        </table>
                    </div>
                </div>

                <?php if ($bisaAlert) : ?>
                    <!-- ALERT TERBARU -->
                    <div class="rfo-panel">
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                            <div>
                                <h3 class="dn-judul"><i class="ti ti-bell-ringing"></i> Recent Alerts</h3>
                                <p class="dn-sub">5 kejadian jaringan terbaru</p>
                            </div>
                            <a href="<?= site_url('Alert') ?>" class="rfo-ghost-btn">View All <i class="ti ti-arrow-up-right"></i></a>
                        </div>
                        <div id="dnAlert"></div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>

    <style>
        .dn-jam { font-size: 12.5px; font-weight: 600; color: var(--rf-muted); }
        .dn-kpi { border: 1px solid var(--rf-border); border-radius: 16px; padding: 18px 20px; background: var(--rf-card); box-shadow: var(--rf-shadow); min-width: 0; }
        .dn-kpi-label { display: flex; justify-content: space-between; align-items: center; font-size: 13.5px; font-weight: 600; color: var(--rf-head); }
        .dn-kpi-label i { font-size: 22px; }
        .dn-kpi-value { margin: 8px 0 4px; font-size: 26px; font-weight: 800; color: var(--rf-text); line-height: 1.2; }
        .dn-kpi-note { font-size: 12.5px; color: var(--rf-muted); }
        .dn-meter { height: 7px; border-radius: 4px; background: var(--rf-border); overflow: hidden; margin: 8px 0; }
        .dn-meter span { display: block; height: 100%; width: 0; background: linear-gradient(90deg, #22c55e, #16a34a); transition: width .4s ease; }
        .dn-grafik { display: grid; grid-template-columns: minmax(0, 2fr) minmax(0, 1fr); gap: 20px; }
        /* panel grafik gelap di mode terang & gelap, sama dengan halaman NMS */
        .dn-gelap { background: #181b1f; border: 1px solid #2c3036; border-radius: 6px; padding: 18px 20px; min-width: 0; display: flex; flex-direction: column; }
        .dn-gelap-head { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 12px; }
        .dn-gelap h3 { font-size: 15px; font-weight: 700; color: #e3e5e8; display: flex; align-items: center; gap: 8px; }
        .dn-gelap p { font-size: 12.5px; color: #a5abb5; margin-top: 2px; }
        .dn-select { height: 38px; max-width: 100%; width: 260px; border: 1px solid #3a3f47 !important; border-radius: 8px; padding: 0 12px; font-size: 13px; font-weight: 600; color: #e3e5e8 !important; background-color: #22252b !important; outline: none; cursor: pointer; color-scheme: dark; }
        .dn-select:focus { border-color: #6ed0e0 !important; }
        .dn-chart { position: relative; flex: 1 1 340px; min-height: 340px; }
        .dn-chart-kosong { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; gap: 6px; color: #a5abb5; font-size: 14px; text-align: center; }
        .dn-chart-kosong[hidden] { display: none; }
        .dn-pie { min-width: 0; }
        .dn-rose { height: 350px; margin-top: 10px; }
        .dn-judul { font-size: 15px; font-weight: 700; color: var(--rf-text); display: flex; align-items: center; gap: 8px; }
        .dn-sub { font-size: 12.5px; color: var(--rf-muted); margin-top: 2px; }
        /* tabel tanpa DataTables: rfo.css menyembunyikan .rfo-table tanpa kelas .dataTable, jadi dimunculkan lagi di sini */
        .rfo-page table.rfo-table.dn-table { visibility: visible; width: 100%; min-width: 900px; border-collapse: collapse; margin: 0; }
        .dn-table thead th { background: var(--rf-soft); color: var(--rf-head); font-weight: 700; font-size: 12.5px; text-align: left; padding: 14px; border-bottom: 1px solid var(--rf-border); white-space: nowrap; }
        .dn-table tbody td { padding: 12px 14px; font-size: 13px; color: var(--rf-text); border-bottom: 1px solid var(--rf-border); vertical-align: middle; white-space: nowrap; }
        .dn-table tbody tr:last-child td { border-bottom: 0; }
        .dn-table tbody tr:hover td { background: var(--rf-soft); }
        .dn-table .dn-kosong, .dn-kosong { text-align: center; color: var(--rf-muted); padding: 30px 10px; font-size: 13.5px; }
        .dn-alert { display: flex; align-items: center; gap: 14px; padding: 13px 4px; border-top: 1px solid var(--rf-border); }
        .dn-alert:first-child { border-top: 0; }
        .dn-alert-ic { width: 38px; height: 38px; flex: 0 0 38px; border-radius: 11px; display: flex; align-items: center; justify-content: center; font-size: 19px; }
        .dn-alert-ic.critical { color: #ef4444; background: rgba(239, 68, 68, .13); }
        .dn-alert-ic.warning { color: #d97706; background: rgba(245, 158, 11, .15); }
        .dn-alert-ic.info { color: #2f6bff; background: rgba(47, 107, 255, .13); }
        .dn-alert-isi { flex: 1; min-width: 0; }
        .dn-alert-isi strong { display: block; font-size: 13.5px; color: var(--rf-text); }
        .dn-alert-isi span { display: block; font-size: 12.5px; color: var(--rf-muted); overflow-wrap: anywhere; }
        .dn-alert-kanan { text-align: right; font-size: 12px; color: var(--rf-muted); white-space: nowrap; }
        @media (max-width: 1100px) { .dn-grafik { grid-template-columns: minmax(0, 1fr); } }
        @media (max-width: 640px) { .dn-chart { flex-basis: 260px; min-height: 260px; } .dn-select { width: 100%; } .dn-alert { flex-wrap: wrap; } .dn-alert-kanan { width: 100%; text-align: left; padding-left: 52px; } }
    </style>

    <?php if ($tabelSiap) : ?>
    <script>
        (function() {
            const URL_DATA = '<?= site_url('DashboardNetmon/data') ?>';
            let D = <?= json_encode($isi, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE) ?>;

            const $ = id => document.getElementById(id);
            const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' })[c]);
            const fmt = (v, d, sat) => v === null || v === undefined ? '--' : Number(v).toLocaleString('id-ID', { minimumFractionDigits: d, maximumFractionDigits: d }) + sat;
            const fmtPersen = v => v === null ? '-' : Number(v).toLocaleString('id-ID', { maximumFractionDigits: 1 }) + '%';
            const BADGE = {
                'Up': ['rb-green', 'ti-circle-check'], 'Down': ['rb-red', 'ti-circle-x'], 'Monitoring Off': ['rb-amber', 'ti-eye-off'],
                'SNMP Off': ['rb-amber', 'ti-plug-off'], 'v3 Pending': ['rb-purple', 'ti-key'], 'Unknown': ['rb-blue', 'ti-help-circle'],
                'open': ['rb-red', 'ti-circle-dot', 'Open'], 'acknowledged': ['rb-blue', 'ti-user-check', 'Acknowledged'], 'resolved': ['rb-green', 'ti-circle-check', 'Resolved']
            };
            const badge = st => {
                const [k, i, t] = BADGE[st] || BADGE.Unknown;
                return '<span class="rb ' + k + '"><i class="ti ' + i + '"></i>' + esc(t || st) + '</span>';
            };

            const chart = window.NmsGrafik ? NmsGrafik.buat($('cPerforma'), [], [
                { label: 'CPU Usage (%)', borderColor: NmsGrafik.WARNA.hijau },
                { label: 'RAM Usage (%)', borderColor: NmsGrafik.WARNA.ungu }
            ], fmtPersen, { maks: 100 }) : null;

            function tampil() {
                const j = D.jumlah;
                $('stTotal').textContent = j.total.toLocaleString('id-ID');
                $('stOnline').textContent = j.online.toLocaleString('id-ID');
                $('stOffline').textContent = j.offline.toLocaleString('id-ID');
                $('stUnknown').textContent = j.unknown.toLocaleString('id-ID');
                $('kAvail').textContent = fmt(D.availability, 2, '%');
                $('kAvailBar').style.width = D.availability + '%';
                $('kAvailNote').textContent = j.online + ' dari ' + j.total + ' device online';
                $('kLat').textContent = fmt(D.latency, 2, ' ms');
                $('kCpu').textContent = fmt(D.cpu, 1, '%');
                $('kRam').textContent = fmt(D.ram, 1, '%');
                $('dnJam').textContent = 'Diperbarui: ' + D.jam + ' WIB';

                $('dnDevice').innerHTML = '<option value="0">All Devices</option>' + D.pilihan.map(p =>
                    '<option value="' + p.id + '"' + (p.id === D.device ? ' selected' : '') + '>' + esc(p.nama) + '</option>').join('');

                if (chart) {
                    chart.data.labels = D.performa.label;
                    chart.data.datasets[0].data = D.performa.cpu;
                    chart.data.datasets[1].data = D.performa.ram;
                    chart.update('none');
                }
                $('cPerformaKosong').hidden = D.performa.label.length > 0;

                if (window.RoseGrafik) {
                    RoseGrafik.gambar($('dnRose'), [
                        { label: 'Online', nilai: j.online, warna: '#16a07a', satuan: 'device' },
                        { label: 'Offline', nilai: j.offline, warna: '#dc4a4a', satuan: 'device' },
                        { label: 'Unknown', nilai: j.unknown, warna: '#c98612', satuan: 'device' }
                    ]);
                }

                $('dnDevices').innerHTML = D.terbaru.length ? D.terbaru.map((d, no) =>
                    '<tr><td>' + (no + 1) + '</td><td class="col-bold">' + esc(d.hostname) + '</td><td>' + esc(d.ip) + '</td><td>' + esc(d.vendor || '-') + '</td>' +
                    '<td>' + esc(d.group || '-') + '</td><td>' + badge(d.status) + '</td><td>' + fmt(d.latency, 2, ' ms') + '</td>' +
                    '<td>' + fmt(d.cpu, 1, '%') + '</td><td>' + fmt(d.ram, 1, '%') + '</td></tr>').join('')
                    : '<tr><td colspan="9" class="dn-kosong">Belum ada device terdaftar.</td></tr>';

                if ($('dnAlert')) {
                    const a = D.alert || [];
                    $('dnAlert').innerHTML = a.length ? a.map(x =>
                        '<div class="dn-alert"><span class="dn-alert-ic ' + esc(x.severity) + '"><i class="ti ti-alert-triangle"></i></span>' +
                        '<div class="dn-alert-isi"><strong>' + esc(x.judul) + '</strong><span>' + esc(x.pesan) + '</span></div>' +
                        '<div class="dn-alert-kanan">' + badge(x.status) + '<div class="mt-1">' + esc(x.waktu) + '</div></div></div>').join('')
                        : '<div class="dn-kosong"><i class="ti ti-shield-check"></i> Belum ada alert.</div>';
                }
            }

            let nomor = 0;

            // poll = true: server sekalian mem-poll device yang sudah waktunya
            async function muat(poll) {
                const n = ++nomor;
                const tombol = $('dnRefresh');
                tombol.disabled = true;
                try {
                    const r = await fetch(URL_DATA + '?' + new URLSearchParams({ device: $('dnDevice').value || 0, poll: poll ? 1 : 0 }), {
                        cache: 'no-store', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                    });
                    const res = await r.json();
                    if (!r.ok || !res.ok) throw new Error(res.pesan || 'HTTP ' + r.status);
                    if (n !== nomor) return; // sudah ada permintaan yang lebih baru
                    D = res;
                    tampil();
                } catch (e) {
                    if (n === nomor) $('dnJam').textContent = 'Gagal memperbarui data';
                } finally {
                    if (n === nomor) tombol.disabled = false;
                }
            }

            $('dnDevice').addEventListener('change', function() {
                const u = new URL(window.location.href);
                if (this.value === '0') u.searchParams.delete('device'); else u.searchParams.set('device', this.value);
                history.replaceState(null, '', u);
                muat(false);
            });
            $('dnRefresh').addEventListener('click', () => muat(true));

            tampil();
            setInterval(() => { if (!document.hidden && document.activeElement !== $('dnDevice')) muat(true); }, 60000);
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