<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title>Detail ONT <?= esc($d['serial_number']) ?></title>
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
                        <li><a href="<?= site_url('Nms') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">NMS Management</a></li>
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
                        <li><a href="<?= site_url('AcsDevice') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] text-white font-semibold">Devices</a></li>
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


        <div class="rfo-page p-6" id="acsDetPage">
            <?php
            $tgl  = static fn ($v) => $v ? date('d-m-Y H:i:s', strtotime($v)) : '-';
            $teks = static fn ($v) => $v === null || $v === '' ? '-' : esc($v);
            $dbm  = static fn ($v) => $v === null ? '-' : number_format($v, 2, ',', '.') . ' dBm';
            $baca = static fn (string $g) => '<p class="dt-baca"><i class="ti ti-clock"></i> ' . (isset($waktu[$g]) ? 'Dibaca ' . $tgl($waktu[$g]) : 'Belum terbaca. Data diambil saat ONT mengirim Inform.') . '</p>';
            $tab  = [
                'info'   => ['ti-info-circle', 'Info'],
                'wan'    => ['ti-world', 'WAN'],
                'optik'  => ['ti-wave-sine', 'Redaman'],
                'wifi'   => ['ti-wifi', 'Profile Client (WiFi)'],
                'client' => ['ti-devices', 'Device Terhubung'],
                'blokir' => ['ti-ban', 'Blokir MAC'],
                'param'  => ['ti-list-search', 'Parameter'],
                'riwayat' => ['ti-history', 'Riwayat'],
            ];
            ?>

            <!-- JUDUL -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic is-sky"><i class="ti ti-router"></i></span>
                    <div>
                        <h1 class="rfo-title"><?= esc($d['serial_number']) ?></h1>
                        <p class="rfo-subtitle"><?= esc(trim($d['manufacturer'] . ' ' . $d['product_class'])) ?: 'ONT' ?><?= $d['id_pelanggan'] !== '' ? ' &middot; ID Pelanggan: <b>' . esc($d['id_pelanggan']) . '</b>' . ($d['nama_pelanggan'] !== '' ? ' (' . esc($d['nama_pelanggan']) . ')' : '') : '' ?></p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <?= $d['online'] ? '<span class="rb rb-green"><i class="ti ti-circle-check"></i>Online</span>' : '<span class="rb rb-red"><i class="ti ti-circle-x"></i>Offline</span>' ?>
                    <?php if ($kontrol) : ?>
                        <button type="button" id="dtReboot" class="rfo-btn rfo-btn-red border-0 cursor-pointer"><i class="ti ti-power"></i> Reboot</button>
                    <?php endif; ?>
                    <button type="button" id="dtBaca" class="rfo-btn rfo-btn-green border-0 cursor-pointer"><i class="ti ti-refresh"></i> Baca Ulang</button>
                    <a href="<?= site_url('AcsDevice') ?>" class="rfo-btn rfo-btn-gray"><i class="ti ti-arrow-left"></i> Devices</a>
                </div>
            </div>

            <!-- RINGKASAN -->
            <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-5">
                <div class="dt-kpi"><span>Inform Terakhir</span><b><?= $tgl($d['last_inform']) ?></b><small><?= esc($d['last_event'] ?: '-') ?></small></div>
                <div class="dt-kpi"><span>IP ACS</span><b><?= $teks($d['ip_address']) ?></b><small>Alamat asal Inform</small></div>
                <div class="dt-kpi"><span>RX Power</span><b><?= $dbm($optik['rx']) ?></b><small><?= $optik['status'] ? '<span class="rb ' . $optik['status'][0] . '">' . $optik['status'][1] . '</span>' : 'Belum terbaca' ?></small></div>
                <div class="dt-kpi"><span>Client Aktif</span><b><?= count(array_filter($client, static fn ($c) => $c['aktif'])) ?></b><small><?= count($client) ?> perangkat dikenal</small></div>
            </div>

            <div id="dtProses" class="dt-proses mb-4" hidden><i class="ti ti-loader"></i> <span></span></div>
            <?php if (! $kontrol) : ?><p class="dt-hint mb-3"><i class="ti ti-lock"></i> Akun Anda hanya bisa melihat. Untuk mengubah ONT diperlukan hak akses <b>ACS JWI Group - Kontrol ONT</b>.</p><?php endif; ?>

            <!-- TAB -->
            <div class="dt-tabs mb-4" role="tablist">
                <?php foreach ($tab as $k => [$ikon, $label]) : ?>
                    <button type="button" role="tab" data-tab="<?= $k ?>" class="<?= $k === 'info' ? 'is-active' : '' ?>"><i class="ti <?= $ikon ?>"></i> <?= $label ?></button>
                <?php endforeach; ?>
            </div>

            <!-- INFO -->
            <section class="rfo-panel" data-isi="info">
                <?php
                $dasar = [
                    'Serial Number' => $d['serial_number'], 'Merek' => $d['manufacturer'], 'Model' => $d['product_class'], 'OUI' => $d['oui'],
                    'Versi Software' => $d['software_version'], 'Versi Hardware' => $d['hardware_version'],
                ];
                $pelanggan = [
                    'ID Pelanggan'   => $d['id_pelanggan'] !== '' ? $d['id_pelanggan'] : 'Belum terbaca (bukan PPPoE)',
                    'Nama Pelanggan' => $d['nama_pelanggan'] !== '' ? $d['nama_pelanggan'] : ($d['id_pelanggan'] !== '' ? 'Belum ada di data portal' : '-'),
                ];
                $tampil = $pelanggan + $info + array_filter($dasar, static fn ($v) => $v !== null && $v !== '') + [
                    'Pertama terhubung' => $tgl($d['first_seen']), 'Jumlah Inform' => number_format((int) $d['inform_count'], 0, ',', '.'),
                    'Interval Inform' => $d['inform_interval'] ? (int) $d['inform_interval'] . ' detik' : '-',
                ];
                ?>
                <div class="dt-grid">
                    <?php foreach ($tampil as $l => $v) : ?>
                        <div><span><?= esc($l) ?></span><b><?= $teks($v) ?></b></div>
                    <?php endforeach; ?>
                </div>
                <?= $baca('info') ?>
            </section>

            <!-- WAN -->
            <section class="rfo-panel" data-isi="wan" hidden>
                <?php if (! $d['wan']) : ?><p class="dt-kosong">Data WAN belum terbaca.</p><?php endif; ?>
                <?php foreach ($d['wan'] as $w) : ?>
                    <div class="dt-kartu">
                        <h3><i class="ti ti-world"></i> <?= $teks($w['deskripsi']) ?>
                            <?= $w['status'] === null ? '' : '<span class="rb ' . (strcasecmp($w['status'], 'Connected') === 0 ? 'rb-green' : 'rb-amber') . '">' . esc($w['status']) . '</span>' ?>
                            <span class="rb rb-purple"><?= esc(\App\Libraries\AcsData::jenisWan($w, $wanP)) ?></span>
                            <?= $w['layanan'] ? '<span class="rb rb-blue">' . esc($w['layanan']) . '</span>' : '' ?>
                        </h3>
                        <div class="dt-grid">
                            <div><span>Mode</span><b><?= $teks($w['mode']) ?></b></div>
                            <div><span>Jenis</span><b><?= esc(\App\Libraries\AcsData::jenisWan($w, $wanP)) ?></b></div>
                            <div><span>VLAN</span><b><?= $teks($w['vlan']) ?></b></div>
                            <div><span>PPPoE User</span><b><?= $teks($w['username']) ?></b></div>
                            <div><span>Password</span><b><?php if ($w['ada_password']) : ?><button type="button" class="dt-sandi" data-sandi="/password/<?= (int) $w['id'] ?>">•••••• <i class="ti ti-eye"></i></button><?php else : ?><?= $w['username'] ? 'Tidak dikirim ONT' : '-' ?><?php endif; ?></b></div>
                            <div><span>MAC Address</span><b><?= $teks($w['mac_address']) ?></b></div>
                            <div><span>IP Address</span><b><?= $teks($w['ip_address']) ?></b></div>
                            <div><span>Gateway</span><b><?= $teks($w['gateway']) ?></b></div>
                            <div><span>DNS Servers</span><b><?= $teks($w['dns']) ?></b></div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <p class="dt-baca"><i class="ti ti-clock"></i> <?= $d['last_read'] ? 'Dibaca ' . $tgl($d['last_read']) : 'Belum terbaca. Data diambil saat ONT mengirim Inform.' ?></p>
            </section>

            <!-- OPTIK -->
            <section class="rfo-panel" data-isi="optik" hidden>
                <div class="dt-grid">
                    <div><span>RX Power</span><b><?= $dbm($optik['rx']) ?> <?= $optik['status'] ? '<span class="rb ' . $optik['status'][0] . '">' . $optik['status'][1] . '</span>' : '' ?></b></div>
                    <div><span>TX Power</span><b><?= $dbm($optik['tx']) ?></b></div>
                    <div><span>Suhu</span><b><?= $teks($optik['suhu']) ?></b></div>
                    <div><span>Tegangan</span><b><?= $teks($optik['tegangan']) ?></b></div>
                    <div><span>Arus Bias</span><b><?= $teks($optik['arus']) ?></b></div>
                </div>
                <p class="dt-hint">Suhu, tegangan dan arus ditampilkan apa adanya dari ONT, satuannya bisa berbeda tiap merek.</p>
                <?= $baca('optik') ?>
            </section>

            <!-- PROFILE CLIENT (WIFI) -->
            <section class="rfo-panel" data-isi="wifi" hidden>
                <div class="dt-pita mb-4" role="group" aria-label="Pilihan band">
                    <?php foreach (['2.4 GHz', '5 GHz'] as $i => $b) : ?>
                        <button type="button" data-pita="<?= $b ?>" class="<?= $i === 0 ? 'is-active' : '' ?>"><i class="ti ti-wifi"></i> <?= $b ?> <em><?= count(array_filter($wifi, static fn ($w) => $w['band'] === $b)) ?></em></button>
                    <?php endforeach; ?>
                </div>
                <?php foreach (['2.4 GHz', '5 GHz'] as $i => $b) : ?>
                    <div data-band="<?= $b ?>" <?= $i ? 'hidden' : '' ?>>
                        <?php $isi = array_filter($wifi, static fn ($w) => $w['band'] === $b); ?>
                        <?php if (! $isi) : ?><p class="dt-kosong"><?= $wifi ? 'Tidak ada WiFi ' . $b . ' di ONT ini.' : 'Data WiFi belum terbaca.' ?></p><?php endif; ?>
                        <?php foreach ($isi as $w) : ?>
                            <form class="dt-kartu dt-wifi" data-no="<?= (int) $w['no'] ?>" autocomplete="off">
                                <h3><i class="ti ti-wifi"></i> <?= esc($w['ssid']) ?> <?= $w['aktif'] ? '<span class="rb rb-green">Aktif</span>' : '<span class="rb rb-amber">Nonaktif</span>' ?></h3>
                                <div class="dt-form">
                                    <label><span>SSID</span><input name="ssid" maxlength="32" required value="<?= esc($w['ssid'], 'attr') ?>" <?= $kontrol ? '' : 'disabled' ?>></label>
                                    <label><span>Password baru</span><input name="password" type="password" minlength="8" maxlength="63" autocomplete="new-password" placeholder="<?= $w['param_sandi'] ? 'Kosongkan jika tetap' : 'Tidak tersedia di ONT ini' ?>" <?= $kontrol && $w['param_sandi'] ? '' : 'disabled' ?>></label>
                                    <label><span>Maksimal client</span><input name="maks" type="number" min="1" max="128" value="<?= esc($w['maks'], 'attr') ?>" placeholder="<?= $w['param_maks'] ? '' : 'Tidak tersedia' ?>" <?= $kontrol && $w['param_maks'] ? '' : 'disabled' ?>></label>
                                    <?php if ($kontrol) : ?><button type="submit" class="rfo-btn rfo-btn-blue border-0 cursor-pointer dt-simpan"><i class="ti ti-device-floppy"></i> Simpan</button><?php endif; ?>
                                </div>
                                <div class="dt-grid dt-mini">
                                    <div><span>Password sekarang</span><b><?php if ($w['sandi']) : ?><button type="button" class="dt-sandi" data-sandi="/rahasia/<?= (int) $d['id'] ?>" data-nama="<?= esc($w['sandi'], 'attr') ?>">•••••• <i class="ti ti-eye"></i></button><?php else : ?>Tidak dikirim ONT<?php endif; ?></b></div>
                                    <div><span>Client tersambung</span><b><?= $teks($w['client']) ?></b></div>
                                    <div><span>Kanal</span><b><?= esc($w['kanal']) ?></b></div>
                                    <div><span>Keamanan</span><b><?= $teks($w['keamanan']) ?></b></div>
                                    <div><span>BSSID</span><b><?= $teks($w['bssid']) ?></b></div>
                                </div>
                            </form>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>
                <?= $baca('wifi') ?>
            </section>

            <!-- CLIENT -->
            <section class="rfo-panel" data-isi="client" hidden>
                <div class="table-scroll">
                    <table class="rfo-table dt-table">
                        <thead><tr><th>No</th><?= $kontrol ? '<th>Aksi</th>' : '' ?><th>Nama</th><th>Status</th><th>Lewat</th><th>IP Address</th><th>MAC Address</th><th>Sinyal</th></tr></thead>
                        <tbody>
                            <?php if (! $client) : ?><tr><td colspan="8" class="dt-kosong">Data client belum terbaca.</td></tr><?php endif; ?>
                            <?php foreach ($client as $no => $c) : ?>
                                <tr>
                                    <td><?= $no + 1 ?></td>
                                    <?php if ($kontrol) : ?>
                                        <td class="ra-aksi">
                                            <?php if ($c['ip'] !== '') : ?><button type="button" class="act act-view" data-ping="<?= esc($c['ip'], 'attr') ?>"><i class="ti ti-activity"></i> Ping</button><?php endif; ?>
                                            <?php if (in_array($c['mac'], array_column($blokir['daftar'], 'mac'), true)) : ?><span class="rb rb-red"><i class="ti ti-ban"></i>Diblokir</span><?php else : ?><button type="button" class="act act-del" data-blokir="<?= esc($c['mac'], 'attr') ?>"><i class="ti ti-ban"></i> Blokir</button><?php endif; ?>
                                        </td>
                                    <?php endif; ?>
                                    <td><?= $teks($c['nama']) ?></td>
                                    <td><?= $c['aktif'] ? '<span class="rb rb-green">Aktif</span>' : '<span class="rb rb-amber">Tidak aktif</span>' ?></td>
                                    <td><?= $teks($c['lewat']) ?></td>
                                    <td><?= $teks($c['ip']) ?></td>
                                    <td><?= $teks($c['mac']) ?></td>
                                    <td><?= $c['sinyal'] !== '' ? esc($c['sinyal']) . ' dBm' : '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php if ($ping) : ?>
                    <div class="dt-kartu mt-4">
                        <h3><i class="ti ti-activity"></i> Hasil ping <?= esc($ping['ip']) ?>
                            <?= $ping['status'] === 'Complete' ? '<span class="rb ' . ($ping['ok'] ? 'rb-green' : 'rb-red') . '">' . ($ping['ok'] ? 'Membalas' : 'Tidak membalas') . '</span>' : '<span class="rb rb-amber">' . esc((string) $ping['status']) . '</span>' ?>
                        </h3>
                        <div class="dt-grid">
                            <div><span>Balasan</span><b><?= $ping['ok'] ?> dari <?= $ping['total'] ?></b></div>
                            <div><span>Rata-rata</span><b><?= $ping['ok'] ? $teks($ping['rata']) . ' ms' : '-' ?></b></div>
                            <div><span>Minimum</span><b><?= $ping['ok'] ? $teks($ping['min']) . ' ms' : '-' ?></b></div>
                            <div><span>Maksimum</span><b><?= $ping['ok'] ? $teks($ping['maks']) . ' ms' : '-' ?></b></div>
                        </div>
                        <?= $baca('ping') ?>
                    </div>
                <?php endif; ?>
                <?= $baca('host') ?>
            </section>

            <!-- BLOKIR MAC -->
            <section class="rfo-panel" data-isi="blokir" hidden>
                <?php if (! $blokir['didukung']) : ?>
                    <p class="dt-kosong">Data filter MAC belum terbaca, atau belum didukung untuk merek ONT ini.</p>
                <?php else : ?>
                    <?php if ($blokir['putih']) : ?><p class="dt-hint mb-3"><i class="ti ti-alert-triangle"></i> ONT ini memakai mode <b>daftar putih</b> (hanya MAC terdaftar yang diizinkan), jadi pemblokiran dari sini dinonaktifkan.</p><?php endif; ?>
                    <?php if ($kontrol && ! $blokir['putih']) : ?>
                        <form id="dtBlokir" class="dt-form mb-4" autocomplete="off">
                            <label><span>MAC Address</span><input name="mac" required maxlength="17" placeholder="AA:BB:CC:00:11:22"></label>
                            <button type="submit" class="rfo-btn rfo-btn-red border-0 cursor-pointer dt-simpan"><i class="ti ti-ban"></i> Blokir</button>
                        </form>
                    <?php endif; ?>
                    <div class="table-scroll">
                        <table class="rfo-table dt-table">
                            <thead><tr><th>No</th><?= $kontrol ? '<th>Aksi</th>' : '' ?><th>MAC Address</th><th>Nama</th><th>Status</th></tr></thead>
                            <tbody>
                                <?php if (! $blokir['daftar']) : ?><tr><td colspan="5" class="dt-kosong">Belum ada MAC yang diblokir.</td></tr><?php endif; ?>
                                <?php foreach ($blokir['daftar'] as $no => $m) : ?>
                                    <?php $nama = array_values(array_filter($client, static fn ($c) => $c['mac'] === $m['mac']))[0]['nama'] ?? ''; ?>
                                    <tr>
                                        <td><?= $no + 1 ?></td>
                                        <?php if ($kontrol) : ?><td class="ra-aksi"><button type="button" class="act act-email" data-buka="<?= (int) $m['no'] ?>" data-mac="<?= esc($m['mac'], 'attr') ?>"><i class="ti ti-lock-open"></i> Buka Blokir</button></td><?php endif; ?>
                                        <td><?= $teks($m['mac']) ?></td>
                                        <td><?= $teks($nama) ?></td>
                                        <td><?= $blokir['aktif'] ? '<span class="rb rb-red">' . ($blokir['putih'] ? 'Diizinkan' : 'Diblokir') . '</span>' : '<span class="rb rb-amber">Filter mati</span>' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                <?= $baca('mac') ?>
            </section>

            <!-- PARAMETER -->
            <section class="rfo-panel" data-isi="param" hidden>
                <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                    <span class="dt-muted"><?= count($semua) ?> parameter dari Info, Optik, WiFi dan Client</span>
                    <div class="rfo-search"><i class="ti ti-search"></i><input type="search" id="dtCari" placeholder="Cari parameter..."></div>
                </div>
                <div class="table-scroll dt-gulir">
                    <table class="rfo-table dt-table">
                        <thead><tr><th>Parameter</th><th>Nilai</th></tr></thead>
                        <tbody>
                            <?php if (! $semua) : ?><tr><td colspan="2" class="dt-kosong">Belum ada parameter.</td></tr><?php endif; ?>
                            <?php foreach ($semua as $k => $v) : ?>
                                <tr data-p="<?= esc(mb_strtolower($k . ' ' . $v), 'attr') ?>"><td class="dt-path"><?= esc($k) ?></td><td class="dt-nilai"><?= $v === null ? '<span class="dt-muted">(rahasia)</span>' : $teks($v) ?></td></tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- RIWAYAT -->
            <section class="rfo-panel" data-isi="riwayat" hidden>
                <div class="table-scroll">
                    <table class="rfo-table dt-table">
                        <thead><tr><th>No</th><th>Waktu</th><th>Perintah</th><th>Oleh</th><th>Status</th><th>Hasil</th></tr></thead>
                        <tbody>
                            <?php if (! $tugas) : ?><tr><td colspan="6" class="dt-kosong">Belum ada riwayat.</td></tr><?php endif; ?>
                            <?php $st = ['Pending' => ['rb-blue', 'Menunggu'], 'Sent' => ['rb-amber', 'Terkirim'], 'Completed' => ['rb-green', 'Selesai'], 'Failed' => ['rb-red', 'Gagal']]; ?>
                            <?php foreach ($tugas as $no => $t) : ?>
                                <tr>
                                    <td><?= $no + 1 ?></td>
                                    <td><?= $tgl($t['waktu']) ?></td>
                                    <td class="<?= $t['tulis'] ? 'col-bold' : 'dt-path' ?>"><?= esc($t['label']) ?></td>
                                    <td><?= $teks($t['oleh']) ?></td>
                                    <td><span class="rb <?= $st[$t['status']][0] ?>"><?= $st[$t['status']][1] ?></span>
                                        <?php if ($kontrol && $t['tulis'] && $t['status'] === 'Pending') : ?><button type="button" class="act act-del" data-batal="<?= (int) $t['id'] ?>"><i class="ti ti-x"></i> Batal</button><?php endif; ?></td>
                                    <td><?= $teks($t['hasil']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>

    <style>
        .dt-kpi { border: 1px solid var(--rf-border); border-radius: 14px; padding: 15px 18px; background: var(--rf-card); min-width: 0; }
        .dt-kpi span:not(.rb) { display: block; font-size: 12.5px; font-weight: 600; color: var(--rf-muted); }
        .dt-kpi b { display: block; margin: 3px 0; font-size: 17px; color: var(--rf-text); overflow-wrap: anywhere; }
        .dt-kpi small { font-size: 12px; color: var(--rf-muted); }
        .dt-tabs { display: flex; flex-wrap: wrap; gap: 4px; border-bottom: 1px solid var(--rf-border); }
        .dt-tabs button { padding: 11px 15px; border: 0; background: transparent; font-size: 13px; font-weight: 700; color: var(--rf-muted); border-bottom: 2px solid transparent; margin-bottom: -1px; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; }
        .dt-tabs button:hover { color: var(--rf-text); }
        .dt-tabs button.is-active { color: #2f6bff; border-color: #2f6bff; }
        html.dark .dt-tabs button.is-active { color: #8fb2ff; border-color: #8fb2ff; }
        [data-isi][hidden] { display: none; }
        .dt-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 14px 22px; }
        .dt-grid span:not(.rb) { display: block; font-size: 12px; color: var(--rf-muted); }
        .dt-grid b { font-size: 14px; font-weight: 600; color: var(--rf-text); overflow-wrap: anywhere; }
        .dt-kartu { border: 1px solid var(--rf-border); border-radius: 12px; padding: 16px 18px; margin-bottom: 14px; }
        .dt-kartu h3 { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-bottom: 12px; font-size: 14.5px; font-weight: 700; color: var(--rf-text); }
        .dt-baca, .dt-hint { margin-top: 14px; font-size: 12.5px; color: var(--rf-muted); }
        .dt-muted { color: var(--rf-muted); font-size: 12.5px; }
        .dt-sandi { border: 0; background: transparent; color: #2f6bff; font-weight: 600; cursor: pointer; padding: 0; }
        html.dark .dt-sandi { color: #8fb2ff; }
        /* tabel tanpa DataTables: rfo.css menyembunyikan .rfo-table tanpa kelas .dataTable, jadi dimunculkan lagi di sini */
        .rfo-page table.rfo-table.dt-table { visibility: visible; width: 100%; min-width: 760px; border-collapse: collapse; margin: 0; }
        .dt-table thead th { background: var(--rf-soft); color: var(--rf-head); font-weight: 700; font-size: 12.5px; text-align: left; padding: 13px 12px; border-bottom: 1px solid var(--rf-border); white-space: nowrap; position: sticky; top: 0; }
        .dt-table tbody td { padding: 10px 12px; font-size: 13px; color: var(--rf-text); border-bottom: 1px solid var(--rf-border); white-space: nowrap; }
        .dt-table tbody tr:last-child td { border-bottom: 0; }
        .dt-table .dt-path { font-size: 12px; white-space: normal; overflow-wrap: anywhere; }
        .dt-table .dt-nilai { white-space: normal; overflow-wrap: anywhere; min-width: 220px; }
        .dt-kosong { text-align: center; color: var(--rf-muted) !important; padding: 30px 10px !important; }
        .dt-gulir { max-height: 560px; overflow: auto; }
        .dt-proses { display: flex; align-items: center; gap: 8px; padding: 12px 16px; border-radius: 12px; font-size: 13.5px; font-weight: 600; color: #1d4ed8; background: #e8efff; }
        .dt-proses[hidden], [data-band][hidden] { display: none; }
        html.dark .dt-proses { color: #8fb2ff; background: rgba(47, 107, 255, .16); }
        .dt-proses i { animation: dtPutar 1.2s linear infinite; }
        @keyframes dtPutar { to { transform: rotate(360deg); } }
        .dt-pita { display: inline-flex; border: 1px solid var(--rf-border); border-radius: 10px; overflow: hidden; }
        .dt-pita button { padding: 10px 20px; border: 0; background: var(--rf-card); color: var(--rf-muted); font-size: 13.5px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 7px; }
        .dt-pita button + button { border-left: 1px solid var(--rf-border); }
        .dt-pita button.is-active { background: #2f6bff; color: #fff; }
        .dt-pita em { font-style: normal; font-size: 11.5px; opacity: .75; }
        .dt-form { display: flex; flex-wrap: wrap; align-items: flex-end; gap: 12px; }
        .dt-form label { flex: 1 1 190px; max-width: 320px; }
        .dt-form label span { display: block; margin-bottom: 5px; font-size: 12px; font-weight: 600; color: var(--rf-muted); }
        .dt-form input { width: 100%; height: 42px; padding: 0 12px; border: 1px solid var(--rf-border); border-radius: 10px; background: var(--rf-card); color: var(--rf-text); font-size: 13.5px; font-weight: 600; outline: none; }
        .dt-form input:disabled { opacity: .6; }
        .dt-simpan { height: 42px; padding: 0 18px; font-size: 13px; }
        .dt-mini { margin-top: 16px; padding-top: 14px; border-top: 1px dashed var(--rf-border); }
        .dt-table .act { cursor: pointer; }
    </style>

    <script>
        (function() {
            const URL_DEV = '<?= site_url('AcsDevice') ?>';
            const TOKEN = <?= json_encode($token) ?>;
            const halaman = document.getElementById('acsDetPage');
            const pesan = (icon, title, text) => Swal.fire({ icon, title, text, confirmButtonColor: '#2f6bff' });

            async function kirim(path, isi, dasar) {
                const r = await fetch((dasar || URL_DEV) + path, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }, body: new URLSearchParams(Object.assign({ token: TOKEN }, isi || {})) });
                let j = {};
                try { j = await r.json(); } catch (e) { }
                if (!r.ok || !j.ok) throw new Error(j.pesan || 'Permintaan gagal (HTTP ' + r.status + ').');
                return j;
            }

            // tab diingat di alamat (#wifi) agar tetap terbuka saat halaman dimuat ulang
            function buka(nama) {
                if (!halaman.querySelector('[data-isi="' + nama + '"]')) return;
                halaman.querySelectorAll('[data-tab]').forEach(b => b.classList.toggle('is-active', b.dataset.tab === nama));
                halaman.querySelectorAll('[data-isi]').forEach(s => { s.hidden = s.dataset.isi !== nama; });
            }
            halaman.querySelectorAll('[data-tab]').forEach(b => b.addEventListener('click', () => {
                buka(b.dataset.tab);
                history.replaceState(null, '', '#' + b.dataset.tab);
            }));
            const awal = location.hash.slice(1);
            if (/^[a-z]+$/.test(awal)) buka(awal);

            document.getElementById('dtCari').addEventListener('input', function() {
                const c = this.value.trim().toLowerCase();
                halaman.querySelectorAll('tr[data-p]').forEach(tr => { tr.hidden = c !== '' && !tr.dataset.p.includes(c); });
            });

            document.getElementById('dtBaca').addEventListener('click', async function() {
                this.disabled = true;
                try {
                    pesan('success', 'Baca Ulang', (await kirim('/baca/<?= (int) $d['id'] ?>')).pesan);
                } catch (e) {
                    pesan('error', 'Gagal', e.message);
                } finally {
                    this.disabled = false;
                }
            });

            // ====== PILIHAN BAND ======
            halaman.querySelectorAll('[data-pita]').forEach(b => b.addEventListener('click', () => {
                halaman.querySelectorAll('[data-pita]').forEach(x => x.classList.toggle('is-active', x === b));
                halaman.querySelectorAll('[data-band]').forEach(x => { x.hidden = x.dataset.band !== b.dataset.pita; });
            }));

            // ====== KONTROL ONT ======
            const URL_KTR = '<?= site_url('AcsKontrol') ?>';
            const ID = <?= (int) $d['id'] ?>;
            const proses = document.getElementById('dtProses');
            let pantau = null; // perintah yang sedang ditunggu hasilnya

            function tampilProses(t) {
                proses.hidden = !t;
                if (t) proses.querySelector('span').textContent = t.label + ': ' + (t.status === 'Pending' ? 'menunggu ONT mengirim Inform...' : 'sedang dijalankan ONT...');
            }

            // awasi perintah tulis sampai selesai (termasuk langkah lanjutannya), lalu muat ulang halaman
            async function awasi() {
                try {
                    const r = await fetch(URL_DEV + '/tugas/' + ID, { cache: 'no-store', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } });
                    const j = await r.json();
                    if (!j.ok) return;
                    const jalan = j.tugas.find(t => (t.tulis || /^Baca .*(WLANConfiguration|MacFilter)/.test(t.label)) && (t.status === 'Pending' || t.status === 'Sent') && (t.tulis || pantau));
                    tampilProses(jalan);
                    if (jalan) {
                        if (jalan.tulis && !pantau) pantau = jalan.id;
                        return;
                    }
                    if (!pantau) return;
                    // gagal kalau salah satu langkah sejak perintah itu gagal
                    const langkah = j.tugas.filter(t => t.tulis && t.id >= pantau);
                    const gagal = langkah.find(t => t.status === 'Failed');
                    pantau = null;
                    if (!langkah.length) return;
                    await Swal.fire(gagal
                        ? { icon: 'error', title: 'Gagal', text: gagal.label + ': ' + (gagal.hasil || ''), confirmButtonColor: '#2f6bff' }
                        : { icon: 'success', title: 'Berhasil', text: langkah[langkah.length - 1].label + (langkah[0].label.startsWith('Ping') ? '. Hasilnya muncul setelah ONT selesai ping.' : '. ' + (langkah[0].hasil || '')), confirmButtonColor: '#2f6bff' });
                    location.reload();
                } catch (e) { /* dicoba lagi */ }
            }
            awasi();
            setInterval(() => { if (!document.hidden && !Swal.isVisible()) awasi(); }, 5000);

            async function perintah(path, isi, tanya) {
                if (tanya) {
                    const r = await Swal.fire({ icon: 'warning', title: tanya[0], text: tanya[1], showCancelButton: true, confirmButtonText: 'Ya, lanjutkan', cancelButtonText: 'Batal', confirmButtonColor: '#dc2626', cancelButtonColor: '#94a3b8' });
                    if (!r.isConfirmed) return;
                }
                try {
                    const j = await kirim(path + '/' + ID, isi, URL_KTR);
                    if (j.tugas) pantau = j.tugas;
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: j.pesan, showConfirmButton: false, timer: 3500 });
                    awasi();
                    return true;
                } catch (e) {
                    pesan('error', 'Gagal', e.message);
                }
            }

            document.getElementById('dtReboot')?.addEventListener('click', () => perintah('/reboot', {}, ['Reboot ONT ini?', 'Internet pelanggan terputus sampai ONT menyala kembali.']));
            halaman.querySelectorAll('.dt-wifi').forEach(f => f.addEventListener('submit', async e => {
                e.preventDefault();
                const isi = { no: f.dataset.no, ssid: f.elements.ssid.value, password: f.elements.password.value, maks: f.elements.maks.value };
                if (await perintah('/wifi', isi, ['Simpan perubahan WiFi?', 'Semua perangkat yang tersambung ke WiFi ini akan terputus dan perlu menyambung lagi.'])) f.elements.password.value = '';
            }));
            document.getElementById('dtBlokir')?.addEventListener('submit', e => {
                e.preventDefault();
                const mac = e.target.elements.mac.value;
                perintah('/blokir', { mac }, ['Blokir ' + mac + '?', 'Perangkat ini tidak bisa memakai internet sampai blokirnya dibuka.']);
            });

            halaman.addEventListener('click', async e => {
                const a = e.target.closest('[data-ping],[data-blokir],[data-buka],[data-batal]');
                if (a) {
                    if (a.dataset.ping) perintah('/ping', { ip: a.dataset.ping });
                    else if (a.dataset.blokir) perintah('/blokir', { mac: a.dataset.blokir }, ['Blokir ' + a.dataset.blokir + '?', 'Perangkat ini tidak bisa memakai internet sampai blokirnya dibuka.']);
                    else if (a.dataset.buka) perintah('/buka', { no: a.dataset.buka }, ['Buka blokir ' + a.dataset.mac + '?', 'Perangkat ini bisa memakai internet lagi.']);
                    else if (await perintah('/batal', { tugas: a.dataset.batal })) { pantau = null; setTimeout(() => location.reload(), 1200); }
                    return;
                }
                const b = e.target.closest('[data-sandi]');
                if (!b) return;
                try {
                    b.textContent = (await kirim(b.dataset.sandi, b.dataset.nama ? { nama: b.dataset.nama } : {})).password || '-';
                    setTimeout(() => { b.innerHTML = '•••••• <i class="ti ti-eye"></i>'; }, 15000);
                } catch (err) {
                    pesan('error', 'Gagal', err.message);
                }
            });
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