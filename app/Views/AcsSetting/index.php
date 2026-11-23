<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title>ACS Settings</title>
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
                        <li><a href="<?= site_url('AcsDevice') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Devices</a></li>
                        <li><a href="<?= site_url('AcsParameter') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Parameter Master</a></li>
                        <li><a href="<?= site_url('AcsProvisioning') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Provisioning</a></li>
                        <li><a href="<?= site_url('AcsSetting') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] text-white font-semibold">Settings</a></li>
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


        <div class="rfo-page p-6" id="acsPage">
            <?php
            $tgl  = static fn ($v) => $v ? date('d-m-Y H:i:s', strtotime($v)) : '-';
            ?>

            <!-- JUDUL -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic is-sky"><i class="ti ti-settings"></i></span>
                    <div>
                        <h1 class="rfo-title">ACS Settings</h1>
                        <p class="rfo-subtitle">Koneksi ONT ke ACS JWI Group lewat TR-069</p>
                    </div>
                </div>
                <?php if ($tabelSiap) : ?>
                    <button type="button" id="acsUji" class="rfo-btn rfo-btn-blue border-0 cursor-pointer"><i class="ti ti-plug-connected"></i> Uji Server ACS</button>
                <?php endif; ?>
            </div>

            <?php if (! $tabelSiap) : ?>
                <div class="rfo-panel mb-6">
                    <p class="font-semibold text-red-600"><i class="ti ti-database-off"></i> Tabel ACS belum ada.</p>
                    <p class="rfo-subtitle mt-1">Jalankan <b>database/d_acs.sql</b> di database.</p>
                </div>
            <?php else : ?>
                <?php
                $siap   = $kunciSiap && $s['ada_password'];
                $akhir  = $ringkas['akhir'];
                ?>
                <?php if (! $kunciSiap) : ?>
                    <div class="acs-peringatan"><i class="ti ti-alert-triangle"></i> <b>encryption.key</b> di file .env belum diisi, jadi password ACS tidak bisa disimpan.</div>
                <?php elseif (! $s['ada_password']) : ?>
                    <div class="acs-peringatan"><i class="ti ti-alert-triangle"></i> ACS Password belum diisi. ONT belum bisa terhubung sampai password disimpan.</div>
                <?php endif; ?>

                <!-- KARTU -->
                <div class="grid grid-cols-2 xl:grid-cols-4 gap-5 mb-6" data-segar="kartu">
                    <div class="rfo-stat rfo-stat-<?= $siap ? 'green' : 'red' ?>">
                        <span class="rfo-stat-ic"><i class="ti ti-server-cog"></i></span>
                        <div>
                            <div class="rfo-stat-label">Server ACS</div>
                            <div class="rfo-stat-value"><?= $siap ? 'Aktif' : 'Belum Aktif' ?></div>
                            <div class="rfo-stat-note"><?= $siap ? 'Siap menerima ONT' : 'ONT ditolak' ?></div>
                        </div>
                    </div>
                    <div class="rfo-stat rfo-stat-blue">
                        <span class="rfo-stat-ic"><i class="ti ti-router"></i></span>
                        <div>
                            <div class="rfo-stat-label">Total ONT</div>
                            <div class="rfo-stat-value"><?= $ringkas['total'] ?></div>
                            <div class="rfo-stat-note">Pernah terhubung</div>
                        </div>
                    </div>
                    <div class="rfo-stat rfo-stat-green">
                        <span class="rfo-stat-ic"><i class="ti ti-circle-check"></i></span>
                        <div>
                            <div class="rfo-stat-label">Online</div>
                            <div class="rfo-stat-value"><?= $ringkas['online'] ?></div>
                            <div class="rfo-stat-note"><?= $ringkas['total'] - $ringkas['online'] ?> offline</div>
                        </div>
                    </div>
                    <div class="rfo-stat rfo-stat-purple">
                        <span class="rfo-stat-ic"><i class="ti ti-clock"></i></span>
                        <div>
                            <div class="rfo-stat-label">Inform Terakhir</div>
                            <div class="rfo-stat-value acs-jam"><?= $akhir ? date('H:i:s', strtotime($akhir)) : '-' ?></div>
                            <div class="rfo-stat-note"><?= $akhir ? date('d-m-Y', strtotime($akhir)) : 'Belum ada ONT' ?></div>
                        </div>
                    </div>
                </div>

                <div class="acs-dua">
                    <!-- PENGATURAN -->
                    <form method="post" action="<?= site_url('AcsSetting/simpan') ?>" class="rfo-panel rfo-form" id="acsForm" autocomplete="off">
                        <input type="hidden" name="token" value="<?= esc($token, 'attr') ?>">
                        <h3 class="acs-judul mb-4"><i class="ti ti-link"></i> Koneksi ONT</h3>

                        <div class="rf-group">
                            <label class="rf-label" for="acs_url"><i class="ti ti-world"></i> ACS URL <span class="req">*</span></label>
                            <div class="acs-baris">
                                <input type="text" name="acs_url" id="acs_url" class="rf-input" maxlength="255" required placeholder="dev.jwi.id" value="<?= esc($s['acs_url'], 'attr') ?>">
                                <button type="button" class="rfo-ghost-btn" data-salin="acs_url" title="Salin"><i class="ti ti-copy"></i></button>
                            </div>
                            <p class="acs-hint">Cukup domainnya saja, tanpa https:// dan tanpa alamat halaman.</p>
                        </div>
                        <div class="acs-form2">
                            <div class="rf-group">
                                <label class="rf-label" for="acs_username"><i class="ti ti-user"></i> ACS Username <span class="req">*</span></label>
                                <div class="acs-baris">
                                    <input type="text" name="acs_username" id="acs_username" class="rf-input" maxlength="64" required value="<?= esc($s['acs_username'], 'attr') ?>">
                                    <button type="button" class="rfo-ghost-btn" data-salin="acs_username" title="Salin"><i class="ti ti-copy"></i></button>
                                </div>
                            </div>
                            <div class="rf-group">
                                <label class="rf-label" for="acs_password"><i class="ti ti-key"></i> ACS Password <?= $s['ada_password'] ? '' : '<span class="req">*</span>' ?></label>
                                <div class="acs-baris">
                                    <input type="password" name="acs_password" id="acs_password" class="rf-input" minlength="8" maxlength="64" autocomplete="new-password" <?= $s['ada_password'] ? '' : 'required' ?> placeholder="<?= $s['ada_password'] ? 'Kosongkan jika tidak diubah' : 'Minimal 8 karakter' ?>">
                                    <button type="button" class="rfo-ghost-btn" id="acsLihat" title="Lihat password"><i class="ti ti-eye"></i></button>
                                    <button type="button" class="rfo-ghost-btn" id="acsAcak" title="Buat password acak"><i class="ti ti-dice-5"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="acs-info">
                            <span>Connection Request</span><b>Belum tersedia</b>
                            <span>Perintah ke ONT</span><b>Baca data, WiFi, blokir MAC, ping, reboot</b>
                            <span>Terakhir diubah</span><b><?= $s['updated_at'] ? $tgl($s['updated_at']) . ' oleh ' . esc($s['updated_by']) : '-' ?></b>
                        </div>
                        <button type="submit" class="rfo-btn rfo-btn-green border-0 cursor-pointer"><i class="ti ti-device-floppy"></i> Simpan</button>
                    </form>

                    <!-- PANDUAN -->
                    <div class="rfo-panel">
                        <h3 class="acs-judul mb-4"><i class="ti ti-list-check"></i> Isi di ONT</h3>
                        <ol class="acs-langkah">
                            <li>Buka web ONT, masuk ke menu <b>TR-069</b> (Huawei: System Tools / Advanced &rarr; TR-069).</li>
                            <li>Aktifkan <b>CWMP / TR-069</b>, lalu isi <b>ACS URL</b> dengan <b>http://<?= esc($s['acs_url']) ?></b>. ONT lama (mis. HG8245H5) umumnya gagal lewat https. <b>ACS Username</b> dan <b>ACS Password</b> sama seperti di halaman ini.</li>
                            <li>Aktifkan <b>Periodic Inform</b> (intervalnya bebas, mengikuti ONT).</li>
                            <li>Pastikan ONT punya <b>WAN TR-069</b> atau internet yang bisa membuka alamat ACS di atas.</li>
                            <li>Simpan. Kalau URL, username dan password sama, ONT langsung terhubung saat mengirim Inform dan datanya tampil di menu <b>Devices</b>.</li>
                        </ol>
                        <p class="acs-hint mt-3"><i class="ti ti-info-circle"></i> Data ONT dibaca otomatis saat baru terhubung atau baru menyala. Setelah itu data WAN dibaca paling cepat tiap 10 menit, data lain tiap 30-60 menit. Password PPPoE biasanya tidak dikirim ONT karena standar TR-069 hanya mengizinkan password ditulis.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <style>
        .acs-peringatan { display: flex; align-items: center; gap: 8px; padding: 12px 16px; margin-bottom: 20px; border-radius: 12px; font-size: 13.5px; color: #b45309; background: #fff4e0; border: 1px solid #fde3b0; }
        html.dark .acs-peringatan { color: #fcd34d; background: rgba(245, 158, 11, .14); border-color: rgba(245, 158, 11, .3); }
        .acs-jam { font-size: 22px; }
        .acs-dua { display: grid; grid-template-columns: minmax(0, 3fr) minmax(0, 2fr); gap: 20px; }
        .acs-judul { font-size: 15px; font-weight: 700; color: var(--rf-text); display: flex; align-items: center; gap: 8px; }
        .acs-form2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px; }
        .acs-baris { display: flex; gap: 8px; }
        .acs-baris .rf-input { flex: 1; min-width: 0; }
        .acs-baris .rfo-ghost-btn { padding: 0 12px !important; height: auto; min-height: 42px; }
        .acs-hint { margin-top: 5px; font-size: 12.5px; color: var(--rf-muted); }
        .acs-info { display: grid; grid-template-columns: auto 1fr; gap: 6px 18px; margin: 4px 0 18px; padding: 12px 14px; border-radius: 10px; background: var(--rf-soft); font-size: 13px; color: var(--rf-muted); }
        .acs-info b { color: var(--rf-text); font-weight: 600; }
        .acs-langkah { margin: 0; padding-left: 20px; list-style: decimal; font-size: 13.5px; line-height: 1.6; color: var(--rf-text); }
        .acs-langkah li { margin-bottom: 8px; }
        .acs-muted { color: var(--rf-muted); font-size: 12.5px; font-weight: 500; }
        @media (max-width: 1100px) { .acs-dua { grid-template-columns: minmax(0, 1fr); } }
        @media (max-width: 640px) { #acsPage .rfo-stat-ic { display: none; } .acs-form2 { grid-template-columns: 1fr; } .acs-baris { flex-wrap: wrap; } .acs-baris .rf-input { flex-basis: 100%; } }
    </style>

    <?php if ($tabelSiap) : ?>
    <script src="<?= base_url('assets/theme/acs-notif.js') ?>?v=<?= @filemtime(FCPATH . 'assets/theme/acs-notif.js') ?: '1' ?>"></script>
    <script>
        (function() {
            const URL_ACS = '<?= site_url('AcsSetting') ?>';
            const TOKEN = <?= json_encode($token) ?>;
            const $ = id => document.getElementById(id);
            const halaman = $('acsPage');
            const pesan = (jenis, judul, isi) => AcsNotif(jenis === 'error' ? 'gagal' : 'berhasil', judul, isi);

            async function kirim(path) {
                const body = new URLSearchParams({ token: TOKEN });
                const r = await fetch(URL_ACS + path, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }, body });
                let j = {};
                try { j = await r.json(); } catch (e) { }
                if (!r.ok || !j.ok) throw new Error(j.pesan || 'Permintaan gagal (HTTP ' + r.status + ').');
                return j;
            }

            async function salin(teks) {
                try {
                    await navigator.clipboard.writeText(teks);
                } catch (e) { // alamat http: clipboard API tidak tersedia
                    const t = document.createElement('textarea');
                    t.value = teks;
                    t.style.cssText = 'position:fixed;opacity:0';
                    document.body.appendChild(t);
                    t.select();
                    document.execCommand('copy');
                    t.remove();
                }
                pesan('success', 'Berhasil', 'Tersalin.');
            }

            // ====== FORM ======
            const inputSandi = $('acs_password');
            const ikonMata = buka => { $('acsLihat').innerHTML = '<i class="ti ' + (buka ? 'ti-eye-off' : 'ti-eye') + '"></i>'; };
            let dariServer = false; // password yang tampil diambil dari server, bukan ketikan baru

            $('acsLihat').addEventListener('click', async () => {
                if (inputSandi.type === 'text') {
                    inputSandi.type = 'password';
                    if (dariServer) { inputSandi.value = ''; dariServer = false; }
                    return ikonMata(false);
                }
                if (!inputSandi.value && !inputSandi.required) {
                    try {
                        inputSandi.value = (await kirim('/password')).password;
                        dariServer = true;
                    } catch (e) { return pesan('error', 'Gagal', e.message); }
                }
                inputSandi.type = 'text';
                ikonMata(true);
            });
            inputSandi.addEventListener('input', () => { dariServer = false; });
            $('acsAcak').addEventListener('click', () => {
                const huruf = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
                inputSandi.value = Array.from(crypto.getRandomValues(new Uint32Array(14)), n => huruf[n % huruf.length]).join('');
                inputSandi.type = 'text';
                dariServer = false;
                ikonMata(true);
            });
            $('acsForm').addEventListener('submit', () => { if (dariServer) inputSandi.value = ''; }); // tidak diubah
            halaman.querySelectorAll('[data-salin]').forEach(b => b.addEventListener('click', () => salin($(b.dataset.salin).value)));

            $('acsUji').addEventListener('click', async function() {
                const isi = this.innerHTML;
                this.disabled = true;
                this.innerHTML = '<i class="ti ti-loader"></i> Menguji...';
                try {
                    pesan('success', 'Server ACS Siap', (await kirim('/uji')).pesan);
                } catch (e) {
                    pesan('error', 'Server ACS Bermasalah', e.message);
                } finally {
                    this.disabled = false;
                    this.innerHTML = isi;
                }
            });

            // ====== DATA DIPERBARUI TIAP 15 DETIK (form yang sedang diisi tidak terganggu) ======
            async function segarkan() {
                if (document.hidden || Swal.isVisible()) return;
                try {
                    const r = await fetch(window.location.href, { cache: 'no-store' });
                    if (!r.ok || r.redirected) return;
                    const baru = new DOMParser().parseFromString(await r.text(), 'text/html');
                    halaman.querySelectorAll('[data-segar]').forEach(el => {
                        const ganti = baru.querySelector('[data-segar="' + el.dataset.segar + '"]');
                        if (ganti) el.innerHTML = ganti.innerHTML;
                    });
                } catch (err) { /* dicoba lagi pada putaran berikutnya */ }
            }
            setInterval(segarkan, 15000);

            // ====== NOTIFIKASI ONT TERHUBUNG / DITOLAK ======
            const URL_DEV = '<?= site_url('AcsDevice') ?>';
            const LIHAT_DEV = <?= \App\Libraries\HakAkses::instance()->bisa('AcsDevice') ? 'true' : 'false' ?>;
            const AWAL = <?= json_encode(['online' => array_map('intval', array_column($koneksi['online'], 'id')), 'log' => $koneksi['log']]) ?>;
            let online = new Set(AWAL.online), logAkhir = AWAL.log, sedangCek = false;

            async function cekKoneksi() {
                if (document.hidden || sedangCek) return;
                sedangCek = true;
                try {
                    const r = await fetch(URL_ACS + '/status?sejak=' + logAkhir, { cache: 'no-store', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                    if (!r.ok || r.redirected) return;
                    const j = await r.json();
                    if (!j.ok) return;
                    const baru = j.online.filter(o => !online.has(Number(o.id)));
                    online = new Set(j.online.map(o => Number(o.id)));
                    logAkhir = j.log;
                    if (baru.length) {
                        const satu = baru[0];
                        AcsNotif('berhasil', 'ONT Terhubung',
                            baru.length === 1 ? (satu.model ? satu.model + ' - ' : '') + 'SN ' + satu.sn + ' terhubung ke ACS.' : baru.length + ' ONT terhubung ke ACS.',
                            LIHAT_DEV ? { url: baru.length === 1 ? URL_DEV + '/detail/' + satu.id : URL_DEV, teks: 'Lihat di Devices' } : null);
                    }
                    if (j.tolak) AcsNotif('gagal', 'ONT Belum Terhubung', j.tolak);
                    if (baru.length) segarkan();
                } catch (e) { /* dicoba lagi pada putaran berikutnya */ } finally {
                    sedangCek = false;
                }
            }
            setInterval(cekKoneksi, 10000);
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