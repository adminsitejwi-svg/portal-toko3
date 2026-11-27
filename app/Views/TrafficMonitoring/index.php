<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title>Traffic Monitoring</title>
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
    <script src="<?= base_url('assets/theme/nms-detik.js') ?>?v=<?= @filemtime(FCPATH . 'assets/theme/nms-detik.js') ?>"></script>

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
                        <li><a href="<?= site_url('TrafficMonitoring') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] text-white font-semibold">Traffic Monitoring</a></li>
                        <li><a href="<?= site_url('Nms') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">NMS Management</a></li>
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

        <div class="rfo-page p-6">
            <?php
            $fmt = static fn ($dt) => $dt ? date('d-m-Y H:i:s', strtotime($dt)) : '-';
            $tm  = \App\Models\DeviceTrafficModel::class;

            // Badge per status interface: [kelas warna, ikon]
            $statusBadge = [
                'Up'          => ['rb-green', 'ti-circle-check'],
                'Down'        => ['rb-red', 'ti-circle-x'],
                'Disabled'    => ['rb-amber', 'ti-plug-off'],
                'Not Present' => ['rb-purple', 'ti-unlink'],
                'Unknown'     => ['rb-blue', 'ti-help-circle'],
            ];

            // Data interface untuk tabel, pilihan grafik & JS
            $baris  = [];
            $kembar = array_count_values(array_map(static fn ($i) => $i['device_id'] . '|' . $i['if_name'], $interface));
            foreach ($interface as $i) {
                // nomor pembeda hanya untuk nama yang kembar di device yang sama (mis. sesi PPPoE lama & baru)
                $nama  = $i['if_name'] . ($kembar[$i['device_id'] . '|' . $i['if_name']] > 1 ? ' #' . (int) $i['if_index'] : '');
                $rx    = $i['rx_bps'] !== null ? (int) $i['rx_bps'] : null;
                $tx    = $i['tx_bps'] !== null ? (int) $i['tx_bps'] : null;
                $speed = (int) $i['speed_mbps'];
                $baris[] = $i + [
                    'st'    => $tm::statusTampil($i, $i['device_status']),
                    'rx'    => $rx,
                    'tx'    => $tx,
                    'util'  => $speed > 0 && ($rx !== null || $tx !== null) ? round(max($rx, $tx) / ($speed * 1e6) * 100, 1) : null,
                    'nama'  => $nama,
                    'label' => $nama . ($i['if_alias'] ? ' (' . $i['if_alias'] . ')' : ''),
                ];
            }

            // Ringkasan untuk kartu statistik
            $total = count($baris);
            $up    = count(array_filter($baris, static fn ($b) => $b['st'] === 'Up'));
            $down  = count(array_filter($baris, static fn ($b) => $b['st'] === 'Down'));
            $sumRx = array_sum(array_map(static fn ($b) => $b['st'] === 'Up' ? (int) $b['rx'] : 0, $baris));
            $sumTx = array_sum(array_map(static fn ($b) => $b['st'] === 'Up' ? (int) $b['tx'] : 0, $baris));

            $infoJs = [];
            foreach ($baris as $b) {
                $infoJs[(int) $b['id']] = [
                    'nama'     => $b['nama'],
                    'alias'    => $b['if_alias'],
                    'descr'    => $b['if_descr'],
                    'hostname' => $b['hostname'],
                    'ip'       => $b['ip_address'],
                    'speed'    => $tm::formatSpeed($b['speed_mbps']),
                    'status'   => $b['st'],
                    'rx'       => $tm::formatBps($b['rx']),
                    'tx'       => $tm::formatBps($b['tx']),
                    'waktu'    => $b['traffic_at'] ? $fmt($b['traffic_at']) : null,
                ];
            }
            $pilihId = $pilih ? (int) $pilih['id'] : 0;
            ?>

            <!-- JUDUL -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic is-sky"><i class="ti ti-chart-area-line"></i></span>
                    <div>
                        <h1 class="rfo-title">Traffic Monitoring</h1>
                        <p class="rfo-subtitle">Bandwidth RX/TX tiap interface perangkat lewat SNMP, diperbarui otomatis sesuai polling interval device</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="<?= site_url('DeviceMonitoring') ?>" class="rfo-btn rfo-btn-gray">
                        <i class="ti ti-device-desktop-analytics"></i> Device Monitoring
                    </a>
                    <?php if ($tabelSiap && $device) : ?>
                        <button type="button" id="btnPollSemua" onclick="pollSemua()" class="rfo-btn rfo-btn-green border-0 cursor-pointer">
                            <i class="ti ti-refresh"></i> Poll Sekarang
                        </button>
                    <?php endif; ?>
                </div>
            </div>

            <?php if (! $tabelSiap) : ?>
                <div class="rfo-panel mb-6">
                    <p class="font-semibold text-red-600"><i class="ti ti-database-off"></i> Tabel Traffic Monitoring belum ada.</p>
                    <p class="rfo-subtitle mt-1">Jalankan file <b>database/d_device_monitoring.sql</b> (kalau belum) lalu <b>database/d_device_traffic.sql</b> di database, kemudian muat ulang halaman ini.</p>
                </div>
            <?php elseif (! $device) : ?>
                <div class="rfo-panel mb-6">
                    <p class="font-semibold"><i class="ti ti-devices"></i> Belum ada device.</p>
                    <p class="rfo-subtitle mt-1">Tambahkan perangkat di <a class="rfo-link" href="<?= site_url('DeviceMonitoring/create') ?>">Device Monitoring</a> (SNMP v2c aktif), traffic interface-nya akan muncul di sini setelah di-poll.</p>
                </div>
            <?php elseif (! $baris) : ?>
                <div class="rfo-panel mb-6">
                    <p class="font-semibold"><i class="ti ti-hourglass-high"></i> Belum ada data interface.</p>
                    <p class="rfo-subtitle mt-1">Klik <b>Poll Sekarang</b> atau tunggu polling berikutnya. Traffic (RX/TX) mulai terhitung dari polling kedua, karena rate dihitung dari selisih counter antar polling.</p>
                </div>
            <?php endif; ?>

            <!-- KARTU STATISTIK (klik = filter status tabel) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
                <a href="#tabelInterface" data-status="" class="rfo-stat rfo-stat-blue rfo-stat-link tm-kartu is-active">
                    <span class="rfo-stat-ic"><i class="ti ti-plug-connected"></i></span>
                    <div>
                        <div class="rfo-stat-label">Total Interface</div>
                        <div class="rfo-stat-value" id="statTotal"><?= $total ?></div>
                        <div class="rfo-stat-note">Pada device terpilih</div>
                    </div>
                    <i class="ti ti-chart-bar rfo-stat-chart"></i>
                </a>
                <a href="#tabelInterface" data-status="Up" class="rfo-stat rfo-stat-green rfo-stat-link tm-kartu">
                    <span class="rfo-stat-ic"><i class="ti ti-circle-check"></i></span>
                    <div>
                        <div class="rfo-stat-label">Interface Up</div>
                        <div class="rfo-stat-value" id="statUp"><?= $up ?></div>
                        <div class="rfo-stat-note">Link terhubung</div>
                    </div>
                    <i class="ti ti-chart-bar rfo-stat-chart"></i>
                </a>
                <a href="#tabelInterface" data-status="Down" class="rfo-stat rfo-stat-red rfo-stat-link tm-kartu">
                    <span class="rfo-stat-ic"><i class="ti ti-circle-x"></i></span>
                    <div>
                        <div class="rfo-stat-label">Interface Down</div>
                        <div class="rfo-stat-value" id="statDown"><?= $down ?></div>
                        <div class="rfo-stat-note">Aktif tapi link putus</div>
                    </div>
                    <i class="ti ti-chart-bar rfo-stat-chart"></i>
                </a>
                <div class="rfo-stat rfo-stat-purple">
                    <span class="rfo-stat-ic"><i class="ti ti-arrows-down-up"></i></span>
                    <div>
                        <div class="rfo-stat-label">Total Traffic</div>
                        <div class="rfo-stat-value tm-stat-traffic">
                            <span title="RX / Download"><i class="ti ti-arrow-down"></i><span id="statRx"><?= $tm::formatBps($sumRx) ?></span></span>
                            <span title="TX / Upload"><i class="ti ti-arrow-up"></i><span id="statTx"><?= $tm::formatBps($sumTx) ?></span></span>
                        </div>
                        <div class="rfo-stat-note">Jumlah RX / TX interface Up</div>
                    </div>
                    <i class="ti ti-chart-bar rfo-stat-chart"></i>
                </div>
            </div>

            <?php if ($tabelSiap && $device) : ?>
                <!-- GRAFIK INTERFACE TERPILIH -->
                <div class="rfo-panel mb-6" id="panelGrafik">
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
                        <form method="get" action="<?= site_url('TrafficMonitoring') ?>" class="flex items-center gap-3 flex-wrap">
                            <label class="rfo-field">
                                <i class="ti ti-router" style="color:#2f6bff"></i>
                                <select name="device" class="tm-select" title="Pilih Device" onchange="this.form.submit()">
                                    <?php foreach ($device as $d) : ?>
                                        <option value="<?= (int) $d['id'] ?>" <?= $deviceId === (int) $d['id'] ? 'selected' : '' ?>><?= esc($d['hostname']) ?> (<?= esc($d['ip_address']) ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                            <label class="rfo-field">
                                <i class="ti ti-plug-connected" style="color:#6d5dfc"></i>
                                <select id="pilihInterface" class="tm-select" title="Pilih Interface" onchange="tampilkanInterface(this.value)" <?= $baris ? '' : 'disabled' ?>>
                                    <?php if (! $baris) : ?>
                                        <option value="">Belum ada interface</option>
                                    <?php endif; ?>
                                    <?php foreach ($baris as $b) : ?>
                                        <option value="<?= (int) $b['id'] ?>" <?= $pilihId === (int) $b['id'] ? 'selected' : '' ?>><?= esc($b['label']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </form>
                        <div class="tm-rentang" role="group" aria-label="Rentang grafik">
                            <?php foreach ($tm::RENTANG as $kode => [$labelRentang]) : ?>
                                <button type="button" data-rentang="<?= $kode ?>"><?= $labelRentang ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <?php if ($pilih) : ?>
                        <div class="flex flex-wrap items-start justify-between gap-3 mb-5">
                            <div>
                                <div class="tm-if-nama"><i class="ti ti-plug-connected"></i> <span id="ifNama"></span></div>
                                <div class="rb-sub mt-1" id="ifInfo"></div>
                            </div>
                            <span id="ifStatus"></span>
                        </div>

                        <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-5">
                            <div class="tm-mini">
                                <div class="tm-mini-label"><i class="ti ti-arrow-down" style="color:#7eb26d"></i> RX / Download</div>
                                <div class="tm-mini-value" id="nowRx">-</div>
                                <div class="rb-sub" id="nowWaktu">-</div>
                            </div>
                            <div class="tm-mini">
                                <div class="tm-mini-label"><i class="ti ti-arrow-up" style="color:#eab839"></i> TX / Upload</div>
                                <div class="tm-mini-value" id="nowTx">-</div>
                                <div class="rb-sub">Rate sampel terakhir</div>
                            </div>
                            <div class="tm-mini">
                                <div class="tm-mini-label"><i class="ti ti-math-avg" style="color:#16a34a"></i> Rata-rata RX / TX</div>
                                <div class="tm-mini-value tm-mini-dua"><span id="avgRx">-</span><span id="avgTx">-</span></div>
                                <div class="rb-sub tm-label-rentang">1 Jam terakhir</div>
                            </div>
                            <div class="tm-mini">
                                <div class="tm-mini-label"><i class="ti ti-trending-up" style="color:#f59e0b"></i> Puncak RX / TX</div>
                                <div class="tm-mini-value tm-mini-dua"><span id="maxRx">-</span><span id="maxTx">-</span></div>
                                <div class="rb-sub tm-label-rentang">1 Jam terakhir</div>
                            </div>
                        </div>

                        <div class="tm-chart-box">
                            <canvas id="grafikTraffic"></canvas>
                            <div id="grafikKosong" class="tm-chart-kosong" hidden><i class="ti ti-chart-line"></i> Belum ada riwayat traffic pada rentang ini.</div>
                        </div>
                        <p class="rf-hint tm-detik mt-3" id="detikInfo" hidden><i class="ti ti-activity"></i> <span></span></p>
                    <?php else : ?>
                        <p class="rfo-subtitle"><i class="ti ti-info-circle"></i> Belum ada interface untuk ditampilkan grafiknya.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <!-- FILTER + TABEL INTERFACE -->
            <div class="rfo-panel" id="tabelInterface">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
                    <div class="flex items-center gap-3 flex-wrap">
                        <div id="lengthArea"></div>
                        <label class="rfo-field">
                            <i class="ti ti-activity" style="color:#16a34a"></i>
                            <select id="filterStatus" class="filter-select" title="Filter Status">
                                <option value="">Semua Status</option>
                                <?php foreach (array_keys($statusBadge) as $s) : ?>
                                    <option value="<?= esc($s) ?>"><?= esc($s) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="rfo-field tm-cek" title="Sembunyikan interface yang belum ada traffic">
                            <input type="checkbox" id="hanyaTraffic">
                            <span>Hanya ada traffic</span>
                        </label>
                    </div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <div class="rfo-search">
                            <i class="ti ti-search"></i>
                            <input type="text" id="customSearch" placeholder="Cari device, IP, interface, comment..." />
                        </div>
                        <button type="button" class="rfo-ghost-btn rfo-reset"><i class="ti ti-refresh"></i> Reset</button>
                        <div id="exportArea"></div>
                    </div>
                </div>

                <div class="table-scroll">
                    <table id="tmTable" class="rfo-table display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th class="no-export">Aksi</th>
                                <th>Device</th>
                                <th>Interface</th>
                                <th>Speed</th>
                                <th>Status</th>
                                <th>RX / Download</th>
                                <th>TX / Upload</th>
                                <th>Utilisasi</th>
                                <th>Update Terakhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; ?>
                            <?php foreach ($baris as $b) : ?>
                                <?php [$sKelas, $sIkon] = $statusBadge[$b['st']]; ?>
                                <tr data-id="<?= (int) $b['id'] ?>" class="<?= $pilihId === (int) $b['id'] ? 'tm-dipilih' : '' ?>">
                                    <td><?= $no++; ?></td>
                                    <td>
                                        <button type="button" onclick="tampilkanInterface(<?= (int) $b['id'] ?>, true)" class="act act-view" title="Tampilkan grafik">
                                            <i class="ti ti-chart-line"></i> Grafik
                                        </button>
                                    </td>
                                    <td>
                                        <div class="col-bold"><?= esc($b['hostname']) ?></div>
                                        <div class="rb-sub"><?= esc($b['ip_address']) ?></div>
                                    </td>
                                    <td>
                                        <div class="col-bold"><?= esc($b['nama']) ?></div>
                                        <?php if ($b['if_alias']) : ?>
                                            <div class="rb-sub"><span class="cell-clip" title="<?= esc($b['if_alias'], 'attr') ?>"><?= esc($b['if_alias']) ?></span></div>
                                        <?php endif; ?>
                                    </td>
                                    <td data-order="<?= (int) $b['speed_mbps'] ?>"><?= $tm::formatSpeed($b['speed_mbps']) ?></td>
                                    <td class="tm-status" data-search="<?= esc($b['st']) ?>"><span class="rb <?= $sKelas ?>"><i class="ti <?= $sIkon ?>"></i><?= esc($b['st']) ?></span></td>
                                    <td class="tm-rx" data-order="<?= $b['rx'] ?? -1 ?>"><?= $tm::formatBps($b['rx']) ?></td>
                                    <td class="tm-tx" data-order="<?= $b['tx'] ?? -1 ?>"><?= $tm::formatBps($b['tx']) ?></td>
                                    <td class="tm-util" data-order="<?= $b['util'] ?? -1 ?>"><?= $b['util'] !== null ? '<div class="tm-bar"><span style="width:' . min(100, $b['util']) . '%"></span></div>' . number_format($b['util'], 1, ',', '.') . '%' : '-' ?></td>
                                    <td class="tm-waktu"><?= $b['traffic_at'] ? $fmt($b['traffic_at']) : '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p class="rf-hint mt-3"><i class="ti ti-info-circle"></i> Selama halaman ini terbuka, device di-poll otomatis sesuai polling interval masing-masing (dicek tiap 15 detik). Rate = selisih counter octets interface &times; 8 / selisih waktu polling; riwayat disimpan <?= $tm::RETENSI_HARI ?> hari. <span id="infoPoll"></span></p>
            </div>
        </div>
    </div>

    <style>
        /* dropdown Device & Interface: tampilan sama dengan .filter-select, tapi tidak ikut dikosongkan tombol Reset */
        .rfo-field .tm-select {
            height: 42px; border: 1px solid var(--rf-border); border-radius: 10px; padding: 0 36px 0 38px;
            font-size: 13px; font-weight: 600; color: var(--rf-text); background-color: var(--rf-card); outline: none; cursor: pointer;
            width: 260px; max-width: 100%; appearance: none; -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%237a869a' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 14px center;
        }
        #pilihInterface { width: 320px; }
        .tm-stat-traffic { display: flex; flex-wrap: wrap; gap: 4px 14px; font-size: 17px; line-height: 1.35; }
        .tm-stat-traffic .ti { font-size: 15px; margin-right: 2px; }
        .tm-rentang { display: inline-flex; border: 1px solid var(--rf-border); border-radius: 8px; overflow: hidden; }
        .tm-rentang button { padding: 8px 14px; font-size: 13px; font-weight: 600; color: var(--rf-muted); background: var(--rf-card); border: 0; cursor: pointer; }
        .tm-rentang button + button { border-left: 1px solid var(--rf-border); }
        .tm-rentang button.is-active { background: #2f6bff; color: #fff; }
        .tm-if-nama { font-size: 16px; font-weight: 700; color: var(--rf-text); }
        .tm-mini { border: 1px solid var(--rf-border); border-radius: 10px; padding: 14px 16px; background: var(--rf-soft); }
        .tm-mini-label { font-size: 12px; color: var(--rf-muted); margin-bottom: 6px; font-weight: 600; }
        .tm-mini-value { font-size: 20px; font-weight: 700; color: var(--rf-text); line-height: 1.3; }
        .tm-mini-dua { font-size: 15px; display: flex; flex-direction: column; }
        /* panel grafik gelap di mode terang & gelap, sama dengan halaman NMS */
        .tm-chart-box { position: relative; height: 320px; padding: 14px; background: #181b1f; border: 1px solid #2c3036; border-radius: 6px; }
        .tm-chart-kosong { position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; gap: 6px; color: #a5abb5; font-size: 14px; }
        .tm-chart-kosong[hidden], .tm-detik[hidden] { display: none; }
        .tm-detik.is-galat, .tm-detik.is-galat .ti { color: #ef4444; }
        /* tanda sedang memuat, sama dengan History Monitoring (NMS Management) */
        .tm-chart-box canvas, .tm-mini-dua { transition: opacity .2s ease; }
        #panelGrafik.is-muat .tm-chart-box canvas, #panelGrafik.is-muat .tm-mini-dua { opacity: .35; }
        #panelGrafik.is-muat .tm-chart-kosong { display: none; }
        #panelGrafik.is-muat .tm-chart-box::after { content: "Memuat data..."; position: absolute; inset: 0; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; color: #e3e5e8; }
        .tm-bar { display: inline-block; vertical-align: middle; width: 60px; height: 6px; border-radius: 3px; background: var(--rf-border); margin-right: 8px; overflow: hidden; }
        .tm-bar span { display: block; height: 100%; background: linear-gradient(90deg, #22c55e, #f59e0b 70%, #ef4444); }
        .tm-cek { cursor: pointer; gap: 8px; }
        .tm-cek input { accent-color: #2f6bff; }
        #tmTable tbody tr.tm-dipilih td { background: rgba(47, 107, 255, .07); }
        @media (max-width: 640px) { .tm-chart-box { height: 250px; } .tm-rentang button { padding: 7px 10px; } }
    </style>

    <script>
        function escapeHtml(s) {
            return String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        function isTableEmpty(table) {
            return table.rows({ search: 'applied' }).data().length === 0;
        }

        function showEmptyExportAlert() {
            Swal.fire({ icon: 'warning', title: 'Data Kosong', text: 'Tidak ada data yang bisa diexport.', confirmButtonColor: '#04a9f5' });
        }

        // Format bit per detik (sama dengan DeviceTrafficModel::formatBps)
        function formatBps(v) {
            if (v === null || v === undefined) return '-';
            const satuan = [['Gbps', 1e9], ['Mbps', 1e6], ['Kbps', 1e3]];
            for (const [s, b] of satuan) {
                if (v >= b) return (v / b).toLocaleString('id-ID', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ' + s;
            }
            return Math.round(v).toLocaleString('id-ID') + ' bps';
        }

        const STATUS_BADGE = <?= json_encode($statusBadge, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        const INFO = <?= json_encode((object) $infoJs, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        const DEVICE_ID = <?= (int) $deviceId ?>;
        const URL_GRAFIK = '<?= site_url('TrafficMonitoring/grafik/') ?>';
        const URL_DETIK = '<?= site_url('TrafficMonitoring/detik/') ?>';
        const URL_DATA = '<?= site_url('TrafficMonitoring/data') ?>' + '?device=' + DEVICE_ID;
        const LABEL_RENTANG = <?= json_encode(array_map(static fn ($r) => $r[0], $tm::RENTANG)) ?>;

        let pilihId = <?= $pilihId ?>;
        // rentang terakhir diingat di browser: muat ulang halaman / ganti device tidak kembali ke 1 Jam
        const KUNCI_RENTANG = 'tm.rentang';
        let rentang = '1h';
        try {
            const r = localStorage.getItem(KUNCI_RENTANG);
            if (Object.prototype.hasOwnProperty.call(LABEL_RENTANG, r)) rentang = r;
        } catch (e) {}

        function tandaiRentang() {
            document.querySelectorAll('.tm-rentang button').forEach(b => b.classList.toggle('is-active', b.dataset.rentang === rentang));
        }

        // device & interface yang tampil ditulis di alamat, jadi muat ulang halaman tetap di pilihan yang sama
        function tulisAlamat() {
            if (!DEVICE_ID) return;
            const url = new URL(window.location.href);
            url.searchParams.set('device', DEVICE_ID);
            if (pilihId) url.searchParams.set('interface', pilihId); else url.searchParams.delete('interface');
            history.replaceState(null, '', url);
        }
        let grafik = null;

        const badge = st => {
            const [kelas, ikon] = STATUS_BADGE[st] || STATUS_BADGE.Unknown;
            return '<span class="rb ' + kelas + '"><i class="ti ' + ikon + '"></i>' + escapeHtml(st) + '</span>';
        };

        // ====== INFO INTERFACE TERPILIH ======
        function tampilInfo() {
            const i = INFO[pilihId];
            if (!i || !document.getElementById('ifNama')) return;
            document.getElementById('ifNama').textContent = i.nama + (i.alias ? ' (' + i.alias + ')' : '');
            document.getElementById('ifInfo').textContent = i.hostname + ' / ' + i.ip + ' / Speed ' + i.speed + (i.descr && i.descr !== i.nama.replace(/ #[0-9]+$/, '') ? ' / ' + i.descr : '');
            document.getElementById('ifStatus').innerHTML = badge(i.status);
            document.getElementById('nowRx').textContent = i.rx;
            document.getElementById('nowTx').textContent = i.tx;
            document.getElementById('nowWaktu').textContent = i.waktu ? 'Sampel ' + i.waktu : 'Belum ada sampel';
            if (detik && detik.aktif() && detik.data()) sekarangDetik(detik.data()); // nilai per detik tidak ditimpa nilai polling
        }

        // Pilih interface (dropdown / tombol Grafik di tabel).
        // Namanya jangan sama dengan id dropdown (pilihInterface): di dalam <form>, onchange akan membaca elemennya, bukan fungsi ini.
        function tampilkanInterface(id, gulir) {
            id = parseInt(id, 10);
            if (!INFO[id]) return;
            pilihId = id;
            document.getElementById('pilihInterface').value = id;
            (window.TM_TABLE ? window.TM_TABLE.rows().nodes().toArray() : document.querySelectorAll('#tmTable tbody tr')).forEach(tr => tr.classList.toggle('tm-dipilih', parseInt(tr.dataset.id, 10) === id));
            tulisAlamat();
            tampilInfo();
            muatGrafik();
            if (gulir) document.getElementById('panelGrafik').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        // ====== GRAFIK (assets/theme/nms-grafik.js, tampilan sama dengan halaman NMS) ======
        function buatGrafik() {
            const canvas = document.getElementById('grafikTraffic');
            if (!canvas || !window.NmsGrafik) return;
            grafik = NmsGrafik.buat(canvas, [], [
                { label: 'RX / Download', borderColor: NmsGrafik.WARNA.hijau },
                { label: 'TX / Upload', borderColor: NmsGrafik.WARNA.kuning }
            ], formatBps, { maks: 1000 });
            siapkanDetik();
        }

        let nomorMuat = 0;

        // ====== RENTANG 5 / 10 MENIT PER DETIK (assets/theme/nms-detik.js) ======
        const LEBAR_DETIK = { '5m': 300, '10m': 600 }; // rentang yang diisi per detik: lebar jendela (detik)
        let detik = null;

        function sekarangDetik(D) {
            const t = D.akhir.traffic;
            if (!t || !t.nilai) return;
            document.getElementById('nowRx').textContent = formatBps(t.nilai[0]);
            document.getElementById('nowTx').textContent = formatBps(t.nilai[1]);
            document.getElementById('nowWaktu').textContent = 'Sampel ' + t.waktu;
        }

        function terimaDetik(D) {
            const r = D.ringkas;
            document.getElementById('panelGrafik').classList.remove('is-muat');
            sekarangDetik(D);
            document.getElementById('grafikKosong').hidden = NmsDetik.isi(grafik, D.label, D.traffic);
            document.getElementById('avgRx').innerHTML = '<i class="ti ti-arrow-down"></i> ' + formatBps(r.rx.rata);
            document.getElementById('avgTx').innerHTML = '<i class="ti ti-arrow-up"></i> ' + formatBps(r.tx.rata);
            document.getElementById('maxRx').innerHTML = '<i class="ti ti-arrow-down"></i> ' + formatBps(r.rx.maks);
            document.getElementById('maxTx').innerHTML = '<i class="ti ti-arrow-up"></i> ' + formatBps(r.tx.maks);
            document.querySelectorAll('.tm-label-rentang').forEach(el => el.textContent = LABEL_RENTANG[rentang] + ' terakhir (' + r.rx.n + ' sampel)');
        }

        function tampilDetik(ya) {
            const el = document.getElementById('detikInfo');
            el.hidden = !ya;
            el.classList.remove('is-galat');
            el.lastElementChild.textContent = ya ? 'Mengambil data per detik...' : '';
        }

        function siapkanDetik() {
            if (!grafik || !window.NmsDetik) return;
            detik = NmsDetik.buat({
                url: sejak => URL_DETIK + pilihId + '?sejak=' + sejak,
                jendela: () => LEBAR_DETIK[rentang] || 600,
                terima: terimaDetik,
                gagal: () => {
                    document.getElementById('panelGrafik').classList.remove('is-muat');
                    if (detik.data()) return;
                    // belum ada data: grafik interface sebelumnya dikosongkan agar tidak dikira milik interface yang baru dipilih
                    document.getElementById('grafikKosong').hidden = NmsDetik.isi(grafik, [], [[], []]);
                    ['avgRx', 'avgTx', 'maxRx', 'maxTx'].forEach(id => document.getElementById(id).textContent = '-');
                },
                status: (teks, galat) => {
                    const el = document.getElementById('detikInfo');
                    el.classList.toggle('is-galat', galat);
                    el.lastElementChild.textContent = teks;
                }
            });
        }

        // diam = true: refresh otomatis dari polling, tanpa tanda "Memuat data..."
        function muatGrafik(diam) {
            if (!grafik || !pilihId) return;
            const nomor = ++nomorMuat;
            const panel = document.getElementById('panelGrafik');
            const perDetik = !!detik && !!LEBAR_DETIK[rentang];
            if (perDetik && diam) return; // sudah diperbarui tiap detik
            if (!diam) panel.classList.add('is-muat'); // tanda sedang memuat (grafik diredupkan), seperti History Monitoring
            tampilDetik(perDetik);
            if (perDetik) {
                if (detik.aktif()) detik.ulang(); else detik.nyala();
                return;
            }
            if (detik) detik.mati();
            fetch(URL_GRAFIK + pilihId + '?rentang=' + rentang, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(res => {
                    if (nomor !== nomorMuat) return; // sudah ada permintaan yang lebih baru
                    if (!res.ok) throw new Error(res.pesan || 'gagal');
                    panel.classList.remove('is-muat');
                    NmsGrafik.modeDetik(grafik, false);
                    grafik.data.labels = res.label;
                    grafik.data.datasets[0].data = res.rx;
                    grafik.data.datasets[1].data = res.tx;
                    grafik.update('none');
                    document.getElementById('grafikKosong').hidden = res.label.length > 0;
                    const r = res.ringkas;
                    document.getElementById('avgRx').innerHTML = '<i class="ti ti-arrow-down"></i> ' + escapeHtml(r.rx_avg);
                    document.getElementById('avgTx').innerHTML = '<i class="ti ti-arrow-up"></i> ' + escapeHtml(r.tx_avg);
                    document.getElementById('maxRx').innerHTML = '<i class="ti ti-arrow-down"></i> ' + escapeHtml(r.rx_max);
                    document.getElementById('maxTx').innerHTML = '<i class="ti ti-arrow-up"></i> ' + escapeHtml(r.tx_max);
                    document.querySelectorAll('.tm-label-rentang').forEach(el => el.textContent = LABEL_RENTANG[rentang] + ' terakhir (' + r.sampel + ' sampel)');
                })
                .catch(() => {
                    if (nomor !== nomorMuat || !panel.classList.contains('is-muat')) return; // refresh otomatis gagal: dicoba lagi pada polling berikutnya
                    // grafik interface sebelumnya dikosongkan agar tidak dikira milik interface yang baru dipilih
                    panel.classList.remove('is-muat');
                    NmsGrafik.modeDetik(grafik, false);
                    grafik.data.labels = [];
                    grafik.data.datasets.forEach(d => d.data = []);
                    grafik.update('none');
                    ['avgRx', 'avgTx', 'maxRx', 'maxTx'].forEach(id => document.getElementById(id).textContent = '-');
                    document.getElementById('grafikKosong').hidden = false;
                    Swal.fire({ icon: 'error', title: 'Gagal', text: 'Data grafik gagal dimuat.', confirmButtonColor: '#2f6bff' });
                });
        }

        document.querySelectorAll('.tm-rentang button').forEach(btn => btn.addEventListener('click', function() {
            const lama = rentang;
            rentang = this.dataset.rentang;
            try { localStorage.setItem(KUNCI_RENTANG, rentang); } catch (e) {}
            tandaiRentang();
            // 5 <-> 10 Menit: data per detik yang sudah terkumpul dipakai lagi, tidak mulai dari awal
            if (detik && detik.aktif() && LEBAR_DETIK[lama] && LEBAR_DETIK[rentang] && detik.susunUlang()) return;
            muatGrafik();
        }));

        // ====== DATA LIVE (hasil polling diperbarui tanpa reload) ======
        function perbaruiBaris(d) {
            const tr = document.querySelector('#tmTable tbody tr[data-id="' + d.id + '"]');
            if (!tr || !window.TM_TABLE) return;
            const st = tr.querySelector('.tm-status');
            st.dataset.search = d.status;
            st.innerHTML = badge(d.status);
            const rx = tr.querySelector('.tm-rx'), tx = tr.querySelector('.tm-tx'), ut = tr.querySelector('.tm-util');
            rx.dataset.order = d.rx ?? -1;
            rx.textContent = d.rx_teks;
            tx.dataset.order = d.tx ?? -1;
            tx.textContent = d.tx_teks;
            ut.dataset.order = d.util ?? -1;
            ut.innerHTML = d.util !== null ? '<div class="tm-bar"><span style="width:' + Math.min(100, d.util) + '%"></span></div>' + d.util.toLocaleString('id-ID', { minimumFractionDigits: 1, maximumFractionDigits: 1 }) + '%' : '-';
            tr.querySelector('.tm-waktu').textContent = d.traffic_at || '-';
            window.TM_TABLE.row(tr).invalidate('dom');
        }

        function perbaruiStatistik(list) {
            const up = list.filter(d => d.status === 'Up');
            document.getElementById('statTotal').textContent = list.length;
            document.getElementById('statUp').textContent = up.length;
            document.getElementById('statDown').textContent = list.filter(d => d.status === 'Down').length;
            document.getElementById('statRx').textContent = formatBps(up.reduce((n, d) => n + (d.rx || 0), 0));
            document.getElementById('statTx').textContent = formatBps(up.reduce((n, d) => n + (d.tx || 0), 0));
        }

        let sedangPoll = false;

        function pollJatuhTempo(manual) {
            if (sedangPoll) return Promise.resolve();
            sedangPoll = true;
            return fetch(URL_DATA, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(res => {
                    if (!res.ok) return;
                    let grafikBaru = false;
                    res.interface.forEach(d => {
                        perbaruiBaris(d);
                        const i = INFO[d.id];
                        if (!i) return;
                        if (d.id === pilihId && d.traffic_at !== i.waktu) grafikBaru = true;
                        Object.assign(i, { status: d.status, rx: d.rx_teks, tx: d.tx_teks, waktu: d.traffic_at });
                    });
                    perbaruiStatistik(res.interface);
                    window.TM_TABLE && window.TM_TABLE.draw(false);
                    tampilInfo();
                    if (grafikBaru) muatGrafik(true);
                    // interface baru (belum ada di halaman): muat ulang agar ikut tampil
                    if (res.interface.some(d => !INFO[d.id])) window.location.reload();
                    const jam = new Date().toLocaleTimeString('id-ID');
                    document.getElementById('infoPoll').textContent = 'Terakhir dicek ' + jam + (res.dipoll ? ' (' + res.dipoll + ' device di-poll).' : '.');
                })
                .catch(() => {
                    if (manual) Swal.fire({ icon: 'error', title: 'Gagal', text: 'Polling gagal, coba lagi.', confirmButtonColor: '#2f6bff' });
                })
                .finally(() => sedangPoll = false);
        }

        // Tombol "Poll Sekarang": poll device yang sudah jatuh tempo + segarkan data semua interface
        function pollSemua() {
            const btn = document.getElementById('btnPollSemua');
            btn.disabled = true;
            btn.innerHTML = '<i class="ti ti-loader"></i> Polling...';
            pollJatuhTempo(true).finally(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="ti ti-refresh"></i> Poll Sekarang';
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            const exportConfig = {
                exportOptions: {
                    columns: ':visible:not(.no-export)',
                    format: {
                        body: function(data) {
                            const tmp = document.createElement('div');
                            tmp.innerHTML = String(data).replace(/<\/div>/g, '</div>\n');
                            return tmp.textContent.replace(/[ \t]+/g, ' ').replace(/\n\s*\n/g, '\n').trim();
                        }
                    }
                }
            };
            const tombol = (jenis, teks, ikon, extra) => Object.assign({
                extend: jenis,
                text: '<i class="ti ' + ikon + '"></i> ' + teks,
                title: 'Data Traffic Monitoring',
                ...exportConfig,
                action: function(e, dt, button, config) {
                    if (isTableEmpty(dt)) return showEmptyExportAlert();
                    $.fn.dataTable.ext.buttons[jenis].action.call(this, e, dt, button, config);
                }
            }, extra || {});

            const table = $('#tmTable').DataTable({
                pageLength: 10,
                // halaman, urutan, pencarian & filter tabel tetap saat halaman dimuat ulang (per tab browser)
                stateSave: true,
                stateDuration: -1,
                stateSaveParams: (s, d) => {
                    d.tm = { device: DEVICE_ID, status: $('#filterStatus').val(), traffic: $('#hanyaTraffic').prop('checked') };
                },
                stateLoadParams: (s, d) => {
                    if (!d.tm) return;
                    if (d.tm.device !== DEVICE_ID) { // device lain: pencarian & halaman milik device sebelumnya tidak dibawa
                        d.search.search = '';
                        d.start = 0;
                    }
                    $('#filterStatus').val(d.tm.status || '');
                    $('#hanyaTraffic').prop('checked', !!d.tm.traffic);
                },
                lengthMenu: [
                    [10, 15, 25, 50, -1],
                    [10, 15, 25, 50, "Semua"]
                ],
                order: [],
                columnDefs: [{
                    targets: [0, 1],
                    orderable: false,
                    searchable: false
                }],
                dom: "lBfrtip",
                buttons: [{
                    extend: 'collection',
                    text: '<i class="ti ti-download"></i> Export',
                    className: 'export-toggle',
                    buttons: [
                        tombol('copyHtml5', 'Copy', 'ti-copy'),
                        tombol('csvHtml5', 'Export CSV', 'ti-file-text'),
                        tombol('excelHtml5', 'Export Excel', 'ti-file-spreadsheet'),
                        tombol('pdfHtml5', 'Export PDF', 'ti-file-type-pdf', {
                            orientation: 'landscape',
                            pageSize: 'A4',
                            customize: function(doc) {
                                doc.styles.tableHeader = { fillColor: '#04a9f5', color: '#fff', bold: true, alignment: 'left' };
                                doc.defaultStyle.fontSize = 8;
                            }
                        })
                    ]
                }],
                language: {
                    lengthMenu: "_MENU_",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ interface",
                    infoEmpty: "Menampilkan 0 - 0 dari 0 interface",
                    infoFiltered: "(disaring dari _MAX_ interface)",
                    emptyTable: "Data interface belum tersedia",
                    zeroRecords: "Tidak ada interface yang cocok dengan pencarian",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Selanjutnya"
                    }
                }
            });
            window.TM_TABLE = table;

            // nomor urut mengikuti urutan & filter yang tampil
            table.on('draw.dt order.dt search.dt', function() {
                let i = table.page.info().start;
                table.column(0, { page: 'current', search: 'applied', order: 'applied' }).nodes().each(function(cell) {
                    cell.innerHTML = ++i;
                });
            });
            table.draw(false);

            $('#lengthArea').append($('.dataTables_length'));
            $('#exportArea').append($('.dt-buttons'));

            // ====== FILTER ======
            const STATUS_COL = 5, RX_COL = 6;
            $('#filterStatus').on('change', function() {
                const re = this.value ? '^' + $.fn.dataTable.util.escapeRegex(this.value) + '$' : '';
                table.column(STATUS_COL).search(re, true, false).draw();
                $('.tm-kartu').removeClass('is-active').filter('[data-status="' + this.value + '"]').addClass('is-active');
            });

            // kartu statistik = filter status
            $('.tm-kartu').on('click', function(e) {
                e.preventDefault();
                $('#filterStatus').val(this.dataset.status).trigger('change');
                document.getElementById('tabelInterface').scrollIntoView({ behavior: 'smooth', block: 'start' });
            });

            // hanya interface yang sudah ada traffic (RX atau TX > 0)
            $.fn.dataTable.ext.search.push(function(settings, data, idx) {
                if (settings.nTable.id !== 'tmTable' || !document.getElementById('hanyaTraffic').checked) return true;
                const tr = table.row(idx).node();
                return parseInt(tr.querySelector('.tm-rx').dataset.order, 10) > 0 || parseInt(tr.querySelector('.tm-tx').dataset.order, 10) > 0;
            });
            $('#hanyaTraffic').on('change', () => table.draw());

            // tampilkan lagi filter yang tersimpan
            $('#customSearch').val(table.search());
            $('.tm-kartu').removeClass('is-active').filter('[data-status="' + $('#filterStatus').val() + '"]').addClass('is-active');
            if ($('#hanyaTraffic').prop('checked')) table.draw(false);

            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });

            $('.rfo-reset').on('click', function() {
                $('#customSearch').val('');
                $('#hanyaTraffic').prop('checked', false);
                $('#filterStatus').val('').trigger('change');
                table.search('').order([]).draw();
            });

            // ====== GRAFIK + POLLING OTOMATIS: cek tiap 15 detik, server hanya mem-poll device yang sudah waktunya ======
            tandaiRentang();
            tulisAlamat();
            buatGrafik();
            tampilInfo();
            muatGrafik();
            <?php if ($tabelSiap && $device) : ?>
                pollJatuhTempo(false);
                setInterval(() => {
                    if (!document.hidden) pollJatuhTempo(false);
                }, 15000);
            <?php endif; ?>
        });
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