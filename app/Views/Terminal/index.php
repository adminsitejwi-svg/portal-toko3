<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title>Terminal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

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
            <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic is-sky"><i class="ti ti-terminal-2"></i></span>
                    <div>
                        <h1 class="rfo-title">Terminal</h1>
                        <p class="rfo-subtitle">Alat jaringan dari server portal &amp; SSH ke MikroTik, OLT, switch. Setiap perintah dicatat di Change Log.</p>
                    </div>
                </div>
            </div>

            <div class="trm" id="trm">
                <div class="trm-bar">
                    <span class="trm-titik"><i></i><i></i><i></i></span>
                    <b id="trmJudul">Terminal Portal</b>
                    <span class="trm-alat">
                        <button type="button" id="trmSsh" title="Login SSH ke perangkat"><i class="ti ti-plug-connected"></i> <span>SSH</span></button>
                        <button type="button" id="trmPutus" title="Putuskan SSH" hidden><i class="ti ti-plug-connected-x"></i> <span>Putus</span></button>
                        <button type="button" id="trmSalin" title="Salin isi layar"><i class="ti ti-copy"></i></button>
                        <button type="button" id="trmBersih" title="Bersihkan layar"><i class="ti ti-eraser"></i></button>
                        <button type="button" id="trmPenuh" title="Layar penuh"><i class="ti ti-maximize"></i></button>
                    </span>
                </div>
                <div class="trm-layar" id="trmLayar">
                    <div id="trmIsi" aria-live="polite"></div>
                    <form class="trm-baris" id="trmForm" autocomplete="off">
                        <label for="trmInput" id="trmPrompt"></label>
                        <input type="text" id="trmInput" spellcheck="false" autocapitalize="off" autocomplete="off" maxlength="4000" aria-label="Perintah">
                    </form>
                </div>
            </div>
            <div class="trm-cepat" id="trmCepat">
                <?php foreach (['help', 'ping 8.8.8.8', 'tracert 8.8.8.8', 'nslookup dev.jwi.id', 'port 192.168.34.1 22', 'ip'] as $c) : ?>
                    <button type="button" data-isi="<?= esc($c, 'attr') ?>"><?= esc($c) ?></button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <style>
        .trm { display: flex; flex-direction: column; height: calc(100vh - 290px); min-height: 380px; border-radius: 14px; overflow: hidden; background: #0b1020; border: 1px solid #1f2a44; box-shadow: 0 12px 34px rgba(15, 23, 42, .22); }
        .trm:fullscreen { height: 100vh; border-radius: 0; }
        .trm-bar { display: flex; align-items: center; gap: 12px; padding: 9px 14px; background: #141c31; color: #cbd5e1; font-size: 13px; }
        .trm-bar b { font-weight: 600; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .trm-titik { display: flex; gap: 6px; }
        .trm-titik i { width: 11px; height: 11px; border-radius: 50%; background: #ef4444; }
        .trm-titik i:nth-child(2) { background: #f59e0b; }
        .trm-titik i:nth-child(3) { background: #22c55e; }
        .trm-alat { margin-left: auto; display: flex; gap: 6px; }
        .trm-alat button { display: inline-flex; align-items: center; gap: 6px; min-height: 32px; padding: 0 10px; border: 0; border-radius: 8px; background: rgba(148, 163, 184, .14); color: #e2e8f0; cursor: pointer; font-size: 13px; }
        .trm-alat button[hidden] { display: none; }
        .trm-alat button:hover { background: rgba(148, 163, 184, .28); }
        .trm-layar { flex: 1; overflow: auto; padding: 14px 16px; font: 13.5px/1.55 Consolas, 'Cascadia Mono', Menlo, 'Courier New', monospace; color: #d6e2f5; cursor: text; }
        .trm-layar pre { margin: 0; font: inherit; white-space: pre-wrap; overflow-wrap: anywhere; }
        .trm-layar .p { color: #4ade80; }
        .trm-layar .galat { color: #fca5a5; }
        .trm-layar .info { color: #7dd3fc; }
        .trm-baris { display: flex; gap: 8px; align-items: baseline; }
        .trm-baris label { color: #4ade80; white-space: nowrap; }
        .trm-baris input { flex: 1; min-width: 60px; padding: 0; border: 0 !important; outline: 0 !important; box-shadow: none !important; background: transparent !important; color: #f8fafc !important; font: inherit; caret-color: #4ade80; }
        .trm-baris.sibuk label { color: #fbbf24; }
        .trm-cepat { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px; }
        .trm-cepat button { padding: 6px 12px; border-radius: 999px; border: 1px solid rgba(100, 116, 139, .35); background: transparent; color: inherit; font: 12.5px Consolas, Menlo, monospace; cursor: pointer; }
        .trm-cepat button:hover { border-color: #2f6bff; color: #2f6bff; }
        .trm-form label { display: block; text-align: left; font-size: 13px; font-weight: 600; margin: 10px 0 4px; }
        .trm-form input, .trm-form select { width: 100%; padding: 9px 12px; border: 1px solid #cbd5e1; border-radius: 10px; font-size: 14px; background: #fff; color: #1e293b; }
        .trm-form .dua { display: grid; grid-template-columns: 1fr 100px; gap: 10px; }
        .trm-daftar { text-align: left; max-height: 40vh; overflow: auto; background: #0b1020; color: #d6e2f5; padding: 12px; border-radius: 10px; font: 12.5px/1.5 Consolas, monospace; white-space: pre-wrap; overflow-wrap: anywhere; }
    </style>

    <script>
        (function() {
            const URL_T = '<?= site_url('Terminal') ?>';
            const OPSI = <?= json_encode(['token' => $token, 'bantuan' => $bantuan, 'mode' => $mode, 'ssh' => $ssh], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?>;
            const $ = id => document.getElementById(id);
            const isi = $('trmIsi'), input = $('trmInput'), layar = $('trmLayar'), form = $('trmForm');
            const riwayat = [];
            let posisi = 0, sibuk = false, ssh = OPSI.ssh, batal = null, antre = [], sandi = null;

            // ====== LAYAR ======
            const gulir = () => { layar.scrollTop = layar.scrollHeight; };
            function tulis(teks, kelas) {
                const p = document.createElement('pre');
                if (kelas) p.className = kelas;
                p.textContent = teks;
                isi.appendChild(p);
                while (isi.childElementCount > 1500) isi.firstChild.remove();
                gulir();
            }
            const teksPrompt = () => ssh ? '[' + ssh.label + ']' + (ssh.mode === 'shell' ? '#' : ' >') : 'portal>';
            function prompt() {
                $('trmPrompt').textContent = sandi ? 'Password:' : teksPrompt();
                $('trmJudul').textContent = ssh ? 'SSH ' + ssh.label + ' (' + OPSI.mode[ssh.mode] + ')' : 'Terminal Portal';
                $('trmSsh').hidden = !!ssh;
                $('trmPutus').hidden = !ssh;
                $('trmCepat').style.display = ssh ? 'none' : '';
                input.type = sandi ? 'password' : 'text';
            }
            function atur(s) {
                sibuk = s;
                form.classList.toggle('sibuk', s);
                input.readOnly = s;
                if (s) $('trmPrompt').textContent = 'menunggu... (Ctrl+C)';
                else { prompt(); gulir(); }
            }

            // ====== SERVER ======
            async function kirim(aksi, data) {
                batal = new AbortController();
                let r;
                try {
                    r = await fetch(URL_T + '/' + aksi, {
                        method: 'POST',
                        signal: batal.signal,
                        headers: { 'X-Terminal-Token': OPSI.token, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
                        body: new URLSearchParams(data || {})
                    });
                } catch (e) {
                    throw new Error(e.name === 'AbortError' ? '^C Berhenti menunggu. Di server, perintah bisa masih berjalan sampai batas waktunya.' : 'Server tidak terjangkau.');
                }
                try {
                    return await r.json();
                } catch (e) {
                    throw new Error(e.name === 'AbortError' ? '^C Berhenti menunggu.' : 'Server tidak memberi jawaban yang benar (HTTP ' + r.status + '). Coba muat ulang halaman.');
                }
            }

            async function jalankan(baris) {
                atur(true);
                try {
                    const j = await kirim('jalankan', { perintah: baris, tujuan: ssh ? 'ssh' : 'alat' });
                    if (j.putus) ssh = null;
                    if (j.ok) { if (j.hasil) tulis(j.hasil); }
                    else { tulis(j.pesan || j.message || 'Gagal.', 'galat'); antre = []; }
                } catch (e) { tulis(e.message, 'galat'); antre = []; }
                atur(false);
            }

            async function login(d) {
                atur(true);
                try {
                    let j = await kirim('ssh', d);
                    if (j.setuju) {
                        const ya = await Swal.fire({
                            icon: 'warning',
                            target: document.fullscreenElement || document.body,
                            title: j.berubah ? 'Kunci perangkat BERUBAH' : 'Perangkat baru',
                            text: (j.berubah ? 'Kunci SSH ' + j.ip + ' berbeda dari sebelumnya. Lanjutkan hanya jika perangkat memang baru diganti atau di-reset. ' : 'Pertama kali terhubung ke ' + j.ip + '. ') + 'Sidik kunci: ' + j.sidik,
                            showCancelButton: true, confirmButtonText: 'Percaya & lanjutkan', cancelButtonText: 'Batal', focusCancel: !!j.berubah
                        });
                        if (!ya.isConfirmed) { tulis('Login dibatalkan.', 'info'); return; }
                        j = await kirim('ssh', Object.assign({}, d, { sidik: j.sidik }));
                    }
                    if (j.ok) {
                        ssh = { label: j.label, mode: j.mode };
                        tulis('Terhubung ke ' + j.label + '. Semua perintah dikirim ke perangkat ini. Ketik exit untuk memutuskan.', 'info');
                    } else {
                        tulis(j.pesan || j.message || 'Login gagal.', 'galat');
                        antre = [];
                    }
                } catch (e) {
                    tulis(e.message, 'galat');
                    antre = [];
                } finally {
                    d.password = '';
                    atur(false);
                    fokus();
                }
            }

            async function putus() {
                atur(true);
                try { await kirim('putus'); tulis('SSH diputus.', 'info'); ssh = null; }
                catch (e) { tulis(e.message, 'galat'); }
                atur(false);
            }

            // ====== PERINTAH ======
            function bantuan() {
                const lebar = Math.max(...Object.keys(OPSI.bantuan).map(k => k.length)) + 3;
                tulis(Object.entries(OPSI.bantuan).map(([k, v]) => k.padEnd(lebar) + v).join('\n') +
                    '\n\nPanah atas/bawah = riwayat, Ctrl+L = bersihkan, Ctrl+C = berhenti menunggu.' +
                    '\nOLT / switch: ssh <user>@<host> -m shell. Tiap kiriman memulai sesi baru, jadi tempel semua baris (enable, config, ...) sekaligus.', 'info');
            }

            async function proses(baris) {
                tulis(teksPrompt() + ' ' + baris, 'p');
                const kata = baris.trim().split(/\s+/), nama = kata[0].toLowerCase();
                if (baris.trim() === '') return;
                if (riwayat[riwayat.length - 1] !== baris) riwayat.push(baris);
                posisi = riwayat.length;
                if (nama === 'clear' || nama === 'cls') { isi.textContent = ''; return; }
                if (ssh) {
                    if (kata.length === 1 && ['exit', 'quit', 'logout'].includes(nama)) return putus();
                    return jalankan(baris);
                }
                if (nama === 'help' || nama === '?') return bantuan();
                if (nama === 'exit') return tulis('Tidak ada SSH yang terhubung.', 'info');
                if (nama === 'ssh') {
                    const d = { port: 22, mode: 'exec' };
                    for (let i = 1; i < kata.length; i++) {
                        if (kata[i] === '-p') d.port = kata[++i];
                        else if (kata[i] === '-m') d.mode = kata[++i];
                        else {
                            const at = kata[i].lastIndexOf('@');
                            d.user = kata[i].slice(0, Math.max(at, 0));
                            d.host = kata[i].slice(at + 1);
                        }
                    }
                    if (!d.user || !d.host) return tulis('Contoh: ssh admin@192.168.34.1 -p 22', 'galat');
                    antre = []; // baris tempelan setelah ssh tidak ikut dianggap password / perintah
                    sandi = d;
                    return prompt();
                }
                return jalankan(baris);
            }

            async function jalankanAntrean() {
                while (antre.length && !sibuk && !sandi) await proses(antre.shift());
                fokus();
            }
            const fokus = () => input.focus({ preventScroll: true });

            form.addEventListener('submit', e => {
                e.preventDefault();
                if (sibuk) return;
                const v = input.value;
                input.value = '';
                if (sandi) {
                    const d = sandi;
                    sandi = null;
                    d.password = v;
                    tulis('Password: ******', 'p');
                    return login(d);
                }
                antre.push(v);
                jalankanAntrean();
            });

            // tempel banyak baris: tampilkan dulu sebelum dijalankan
            input.addEventListener('paste', async e => {
                if (sandi || sibuk) return;
                const teks = (e.clipboardData.getData('text') || '').replace(/\r\n?/g, '\n');
                if (!teks.trim().includes('\n')) return;
                e.preventDefault();
                const baris = teks.split('\n').map(b => b.trimEnd()).filter(b => b.trim() !== '');
                const kotak = document.createElement('div');
                kotak.className = 'trm-daftar';
                kotak.textContent = baris.join('\n');
                const ya = await Swal.fire({ title: 'Jalankan ' + baris.length + ' baris?', html: kotak, showCancelButton: true, confirmButtonText: 'Jalankan', cancelButtonText: 'Batal', target: document.fullscreenElement || document.body });
                if (!ya.isConfirmed || sibuk || sandi) return fokus();
                antre = ssh && ssh.mode === 'shell' ? [baris.join('\n')] : baris;
                jalankanAntrean();
            });

            input.addEventListener('keydown', e => {
                if (e.ctrlKey && e.key.toLowerCase() === 'l') { e.preventDefault(); isi.textContent = ''; return; }
                if (e.ctrlKey && !e.shiftKey && e.key.toLowerCase() === 'c') {
                    if (sibuk) {
                        e.preventDefault();
                        antre = [];
                        if (batal) batal.abort();
                    } else if (input.selectionStart === input.selectionEnd && !String(getSelection())) {
                        e.preventDefault();
                        tulis((sandi ? 'Password:' : teksPrompt() + ' ' + input.value) + ' ^C', 'p');
                        input.value = '';
                        sandi = null;
                        prompt();
                    }
                    return;
                }
                if (sandi || sibuk || (e.key !== 'ArrowUp' && e.key !== 'ArrowDown')) return;
                e.preventDefault();
                posisi = Math.min(riwayat.length, Math.max(0, posisi + (e.key === 'ArrowUp' ? -1 : 1)));
                input.value = riwayat[posisi] || '';
            });

            // ====== TOMBOL ======
            layar.addEventListener('click', () => { if (!String(getSelection())) fokus(); });
            $('trm').addEventListener('contextmenu', e => e.stopPropagation()); // klik kanan (salin/tempel) boleh di terminal
            $('trmBersih').addEventListener('click', () => { isi.textContent = ''; fokus(); });
            $('trmPenuh').addEventListener('click', () => {
                if (document.fullscreenElement) document.exitFullscreen();
                else $('trm').requestFullscreen();
                fokus();
            });
            $('trmSalin').addEventListener('click', async () => {
                try { await navigator.clipboard.writeText(isi.innerText); tulis('Isi layar disalin.', 'info'); }
                catch (e) { tulis('Browser tidak mengizinkan salin otomatis. Blok teks lalu Ctrl+C.', 'galat'); }
            });
            $('trmPutus').addEventListener('click', () => { if (!sibuk) putus().then(fokus); });
            $('trmCepat').addEventListener('click', e => {
                const b = e.target.closest('[data-isi]');
                if (b && !sibuk && !sandi) { input.value = b.dataset.isi; fokus(); }
            });
            $('trmSsh').addEventListener('click', async () => {
                if (sibuk) return;
                const pilihan = Object.entries(OPSI.mode).map(([k, v]) => '<option value="' + k + '">' + v + '</option>').join('');
                const r = await Swal.fire({
                    title: 'Login SSH',
                    target: document.fullscreenElement || document.body,
                    html: '<div class="trm-form"><div class="dua"><div><label for="sHost">Host / IP</label><input id="sHost" placeholder="192.168.34.1" autocomplete="off"></div>' +
                        '<div><label for="sPort">Port</label><input id="sPort" type="number" min="1" max="65535" value="22"></div></div>' +
                        '<label for="sUser">Username</label><input id="sUser" autocomplete="off"><label for="sSandi">Password</label><input id="sSandi" type="password" autocomplete="new-password">' +
                        '<label for="sMode">Jenis perangkat</label><select id="sMode">' + pilihan + '</select></div>',
                    showCancelButton: true, confirmButtonText: 'Hubungkan', cancelButtonText: 'Batal', focusConfirm: false,
                    didOpen: () => $('sHost').focus(),
                    preConfirm: () => {
                        const d = { host: $('sHost').value.trim(), port: $('sPort').value, user: $('sUser').value.trim(), password: $('sSandi').value, mode: $('sMode').value };
                        if (!d.host || !d.user) { Swal.showValidationMessage('Host dan username wajib diisi.'); return false; }
                        return d;
                    }
                });
                if (!r.isConfirmed) return fokus();
                sandi = null;
                antre = [];
                tulis('portal> ssh ' + r.value.user + '@' + r.value.host + ' -p ' + r.value.port, 'p');
                login(r.value);
            });

            // ====== MULAI ======
            tulis('Terminal Portal. Ketik help untuk daftar perintah.\nPerintah dijalankan dari server portal, bukan dari PC Anda.', 'info');
            if (location.protocol !== 'https:' && !['localhost', '127.0.0.1'].includes(location.hostname)) {
                tulis('PERHATIAN: halaman ini tidak memakai https, password SSH bisa terbaca di jaringan.', 'galat');
            }
            if (ssh) tulis('Masih terhubung ke ' + ssh.label + '. Ketik exit untuk memutuskan.', 'info');
            prompt();
            fokus();
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