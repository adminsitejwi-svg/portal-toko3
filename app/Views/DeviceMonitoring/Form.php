<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title>Form Device Monitoring</title>
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
                        <li><a href="<?= site_url('RekapDataMidi') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] text-white font-semibold">Rekap Data Toko</a></li>
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

        <?php
        // $dv = null: tambah; selain itu edit. Isian lama (old) dipakai bila simpan gagal.
        $M    = \App\Models\DeviceMonitoringModel::class;
        $edit = $dv !== null;
        $val  = static fn (string $k, $bawaan = '') => old($k, $dv[$k] ?? $bawaan, false) ?? '';

        // Pilihan + "Other": nilai di luar pilihan = Other dengan isian manual
        $pilih = static function (string $k, array $opsi) use ($dv) {
            $lama = old($k, null, false);
            if ($lama !== null) {
                return [$lama, old($k . '_lain', '', false)];
            }
            $v = (string) ($dv[$k] ?? '');
            if ($v === '' || in_array($v, $opsi, true)) {
                return [$v, ''];
            }

            return ['Other', $v];
        };
        [$vendor, $vendorLain] = $pilih('vendor', $M::VENDOR);
        [$group, $groupLain]   = $pilih('device_group', $M::DEVICE_GROUP);

        // Centang: saat simpan gagal ikut isian terakhir; device baru default aktif
        $adaOld    = old('hostname', null, false) !== null;
        $centang   = static fn (string $k) => $adaOld ? (bool) old($k, false, false) : ($dv === null || (int) $dv[$k] === 1);
        $versi     = $val('snmp_version', 'v2c') ?: 'v2c';
        $adaComm   = $edit && $M::dekripsi($dv['snmp_community']) !== '';
        ?>
        <style>
            .sr-section {
                display: flex;
                align-items: center;
                gap: 10px;
                margin: 4px 0 16px;
                padding-bottom: 10px;
                border-bottom: 1px solid var(--rf-border, #e3e8ee);
                font-size: 15px;
                font-weight: 800;
                color: var(--rf-head, #1e293b);
            }

            .sr-section .sr-no {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 26px;
                height: 26px;
                border-radius: 8px;
                background: #2f6bff;
                color: #fff;
                font-size: 13px;
            }

            .sr-opsi {
                display: flex;
                flex-wrap: wrap;
                gap: 8px;
            }

            .sr-opsi label {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 7px 14px;
                border: 1px solid var(--rf-border, #e3e8ee);
                border-radius: 10px;
                cursor: pointer;
                font-size: 13.5px;
                font-weight: 600;
                user-select: none;
                transition: border-color .15s, background .15s;
            }

            .sr-opsi input {
                accent-color: #2f6bff;
            }

            .sr-opsi label:has(input:checked) {
                border-color: #2f6bff;
                background: rgba(47, 107, 255, .08);
                color: #2f6bff;
            }

            .dm-pass {
                position: relative;
            }

            .dm-pass .rf-input {
                padding-right: 42px;
            }

            .dm-pass button {
                position: absolute;
                right: 8px;
                top: 50%;
                transform: translateY(-50%);
                width: 30px;
                height: 30px;
                border-radius: 8px;
                color: var(--rf-muted, #64748b);
            }

            .dm-pass button:hover {
                color: #2f6bff;
                background: rgba(47, 107, 255, .08);
            }

            .dm-test {
                margin-top: 14px;
                padding: 12px 14px;
                border-radius: 10px;
                border: 1px solid var(--rf-border, #e3e8ee);
                font-size: 13px;
            }

            .dm-test.is-ok {
                border-color: rgba(22, 163, 74, .4);
                background: rgba(22, 163, 74, .06);
            }

            .dm-test.is-gagal {
                border-color: rgba(220, 38, 38, .4);
                background: rgba(220, 38, 38, .06);
            }

            .dm-test dl {
                display: grid;
                grid-template-columns: 110px minmax(0, 1fr);
                gap: 4px 10px;
                margin-top: 8px;
            }

            .dm-test dt {
                color: var(--rf-muted, #64748b);
            }

            .dm-test dd {
                font-weight: 600;
                word-break: break-word;
            }

            .dm-v3 {
                margin-top: 10px;
                padding: 10px 12px;
                border-radius: 10px;
                background: rgba(245, 158, 11, .1);
                color: #b45309;
                font-size: 13px;
            }
        </style>

        <div class="rfo-page p-6">
            <!-- JUDUL -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic is-blue"><i class="ti <?= $edit ? 'ti-edit' : 'ti-device-desktop-plus' ?>"></i></span>
                    <div>
                        <h1 class="rfo-title"><?= $edit ? 'Edit Device' : 'Tambah Device' ?></h1>
                        <p class="rfo-subtitle">Informasi device &amp; konfigurasi SNMP untuk monitoring otomatis</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="<?= site_url('DeviceMonitoring') ?>" class="rfo-btn rfo-btn-gray">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <form action="<?= site_url($edit ? 'DeviceMonitoring/update' : 'DeviceMonitoring/save') ?>" method="POST" id="dmForm" class="rfo-form" autocomplete="off">
                <?= csrf_field() ?>
                <?php if ($edit) : ?>
                    <input type="hidden" name="id" value="<?= (int) $dv['id'] ?>">
                <?php endif; ?>

                <!-- 1. INFORMASI DEVICE -->
                <div class="rfo-panel mb-6">
                    <div class="sr-section"><span class="sr-no">1</span> Informasi Device</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
                        <div class="rf-group">
                            <label class="rf-label" for="hostname"><i class="ti ti-server"></i> Hostname <span class="req">*</span></label>
                            <input type="text" name="hostname" id="hostname" maxlength="150" required value="<?= esc($val('hostname')) ?>" placeholder="Contoh: CORE-RTR-01" class="rf-input">
                        </div>
                        <div class="rf-group">
                            <label class="rf-label" for="ip_address"><i class="ti ti-network" style="color:#2f6bff"></i> IP Address <span class="req">*</span></label>
                            <input type="text" name="ip_address" id="ip_address" maxlength="45" required value="<?= esc($val('ip_address')) ?>" placeholder="Contoh: 10.10.10.1" class="rf-input">
                        </div>
                        <div class="rf-group md:col-span-2">
                            <label class="rf-label"><i class="ti ti-building-factory-2"></i> Vendor <span class="req">*</span></label>
                            <div class="sr-opsi">
                                <?php foreach (array_merge($M::VENDOR, ['Other']) as $o) : ?>
                                    <label><input type="radio" name="vendor" value="<?= esc($o, 'attr') ?>" <?= $vendor === $o ? 'checked' : '' ?>> <?= esc($o) ?></label>
                                <?php endforeach; ?>
                            </div>
                            <input type="text" name="vendor_lain" id="vendor_lain" maxlength="100" value="<?= esc($vendorLain) ?>" placeholder="Tulis nama vendor, contoh: Huawei" class="rf-input mt-2 hidden">
                        </div>
                        <div class="rf-group">
                            <label class="rf-label" for="model"><i class="ti ti-cpu"></i> Model</label>
                            <input type="text" name="model" id="model" list="saran_model" maxlength="150" value="<?= esc($val('model')) ?>" placeholder="Contoh: CCR1036-8G-2S+" class="rf-input">
                        </div>
                        <div class="rf-group">
                            <label class="rf-label" for="lokasi"><i class="ti ti-map-pin"></i> Location</label>
                            <input type="text" name="lokasi" id="lokasi" list="saran_lokasi" maxlength="255" value="<?= esc($val('lokasi')) ?>" placeholder="Contoh: POP Cikarang / Rak 2" class="rf-input">
                        </div>
                        <div class="rf-group md:col-span-2">
                            <label class="rf-label"><i class="ti ti-category"></i> Device Group <span class="req">*</span></label>
                            <div class="sr-opsi">
                                <?php foreach (array_merge($M::DEVICE_GROUP, ['Other']) as $o) : ?>
                                    <label><input type="radio" name="device_group" value="<?= esc($o, 'attr') ?>" <?= $group === $o ? 'checked' : '' ?>> <?= esc($o) ?></label>
                                <?php endforeach; ?>
                            </div>
                            <input type="text" name="device_group_lain" id="device_group_lain" maxlength="100" value="<?= esc($groupLain) ?>" placeholder="Tulis device group, contoh: Access Point" class="rf-input mt-2 hidden">
                        </div>
                        <div class="rf-group md:col-span-2">
                            <label class="rf-label" for="deskripsi"><i class="ti ti-notes"></i> Description</label>
                            <textarea name="deskripsi" id="deskripsi" rows="3" placeholder="Opsional" class="rf-input"><?= esc($val('deskripsi')) ?></textarea>
                        </div>
                    </div>
                </div>

                <!-- 2. SNMP CONFIGURATION -->
                <div class="rfo-panel mb-6">
                    <div class="sr-section"><span class="sr-no">2</span> SNMP Configuration</div>
                    <div class="rf-group">
                        <label class="rf-label"><i class="ti ti-plug-connected"></i> SNMP Version <span class="req">*</span></label>
                        <div class="sr-opsi">
                            <?php foreach ($M::SNMP_VERSION as $kode => $label) : ?>
                                <label><input type="radio" name="snmp_version" value="<?= esc($kode, 'attr') ?>" <?= $versi === $kode ? 'checked' : '' ?>> <?= esc($label) ?></label>
                            <?php endforeach; ?>
                        </div>
                        <div id="infoV3" class="dm-v3 hidden"><i class="ti ti-alert-triangle"></i> Setup credentials SNMP v3 belum tersedia. Device tetap tersimpan, tetapi belum di-polling sampai SNMP v3 didukung.</div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4">
                        <div class="rf-group">
                            <label class="rf-label" for="snmp_port"><i class="ti ti-plug"></i> SNMP Port <span class="req">*</span></label>
                            <input type="number" name="snmp_port" id="snmp_port" min="1" max="65535" required value="<?= esc($val('snmp_port', 161)) ?>" class="rf-input">
                        </div>
                        <div class="rf-group">
                            <label class="rf-label" for="snmp_community"><i class="ti ti-key"></i> SNMP Community (v2c) <span class="req" id="reqComm">*</span></label>
                            <div class="dm-pass">
                                <input type="password" name="snmp_community" id="snmp_community" maxlength="100" value="<?= esc(old('snmp_community', $edit ? '' : $M::COMMUNITY_DEFAULT, false) ?? '') ?>" placeholder="<?= $adaComm ? 'Tersimpan (kosongkan bila tidak diubah)' : 'Community SNMP' ?>" class="rf-input" autocomplete="new-password">
                                <button type="button" onclick="lihatCommunity(this)" title="Tampilkan / sembunyikan"><i class="ti ti-eye"></i></button>
                            </div>
                            <?php if ($adaComm) : ?>
                                <p class="rf-hint">Community tersimpan terenkripsi. Kosongkan bila tidak diubah.</p>
                            <?php endif; ?>
                        </div>
                        <div class="rf-group">
                            <label class="rf-label" for="polling_interval"><i class="ti ti-clock"></i> Polling Interval (seconds) <span class="req">*</span></label>
                            <input type="number" name="polling_interval" id="polling_interval" min="<?= $M::INTERVAL_MIN ?>" max="<?= $M::INTERVAL_MAX ?>" required value="<?= esc($val('polling_interval', 60)) ?>" class="rf-input">
                            <p class="rf-hint">Minimal <?= $M::INTERVAL_MIN ?> detik.</p>
                        </div>
                    </div>
                    <div class="rf-group">
                        <div class="sr-opsi">
                            <label><input type="checkbox" name="snmp_enabled" id="snmp_enabled" value="1" <?= $centang('snmp_enabled') ? 'checked' : '' ?>> Enable SNMP</label>
                            <label><input type="checkbox" name="monitoring_enabled" id="monitoring_enabled" value="1" <?= $centang('monitoring_enabled') ? 'checked' : '' ?>> Enable Monitoring</label>
                        </div>
                        <p class="rf-hint">Enable Monitoring: device di-poll otomatis sesuai polling interval. Saat disimpan, aplikasi langsung mencoba terhubung lewat SNMP.</p>
                    </div>

                    <button type="button" id="btnTest" onclick="testSnmp()" class="rfo-btn rfo-btn-green border-0 cursor-pointer">
                        <i class="ti ti-plug-connected"></i> Test Koneksi SNMP
                    </button>
                    <div id="hasilTest" class="dm-test hidden"></div>
                </div>

                <div class="rfo-actions">
                    <button type="submit" class="rfo-btn rfo-btn-blue"><i class="ti ti-device-floppy"></i> Simpan</button>
                    <button type="button" class="rfo-btn rfo-btn-gray" onclick="window.location.href='<?= site_url('DeviceMonitoring') ?>'"><i class="ti ti-arrow-left"></i> Kembali</button>
                </div>
            </form>
        </div>
    </div>

    <?php foreach ($saran as $kolom => $isi) : ?>
        <datalist id="saran_<?= esc($kolom, 'attr') ?>"><?php foreach ($isi as $v) : ?><option value="<?= esc($v, 'attr') ?>"></option><?php endforeach; ?></datalist>
    <?php endforeach; ?>

    <script>
        const ADA_COMMUNITY = <?= $adaComm ? 'true' : 'false' ?>;

        function escapeHtml(s) {
            return String(s ?? '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        // ====== PILIHAN "Other": tampilkan isian manual ======
        function aturLain(nama) {
            const pilih = document.querySelector('input[name="' + nama + '"]:checked');
            const lain = document.getElementById(nama + '_lain');
            const tampil = pilih && pilih.value === 'Other';
            lain.classList.toggle('hidden', !tampil);
            lain.required = tampil;
        }
        ['vendor', 'device_group'].forEach(nama => {
            document.querySelectorAll('input[name="' + nama + '"]').forEach(r => r.addEventListener('change', () => {
                aturLain(nama);
                if (r.value === 'Other') document.getElementById(nama + '_lain').focus();
            }));
            aturLain(nama);
        });

        // ====== SNMP: v3 belum didukung, community wajib untuk v2c aktif ======
        function aturSnmp() {
            const v3 = (document.querySelector('input[name="snmp_version"]:checked') || {}).value === 'v3';
            const aktif = document.getElementById('snmp_enabled').checked;
            document.getElementById('infoV3').classList.toggle('hidden', !v3);
            document.getElementById('btnTest').disabled = v3;
            const wajib = !v3 && aktif && !ADA_COMMUNITY;
            document.getElementById('snmp_community').required = wajib;
            document.getElementById('reqComm').classList.toggle('hidden', !wajib);
        }
        document.querySelectorAll('input[name="snmp_version"], #snmp_enabled').forEach(el => el.addEventListener('change', aturSnmp));
        aturSnmp();

        function lihatCommunity(btn) {
            const inp = document.getElementById('snmp_community');
            const lihat = inp.type === 'password';
            inp.type = lihat ? 'text' : 'password';
            btn.innerHTML = '<i class="ti ' + (lihat ? 'ti-eye-off' : 'ti-eye') + '"></i>';
        }

        // ====== TEST KONEKSI SNMP (tidak menyimpan) ======
        function testSnmp() {
            const f = document.getElementById('dmForm');
            const btn = document.getElementById('btnTest');
            const box = document.getElementById('hasilTest');
            const data = new FormData();
            ['ip_address', 'snmp_port', 'snmp_community'].forEach(k => data.append(k, f.elements[k].value.trim()));
            if (f.elements.id) data.append('id', f.elements.id.value);
            const csrf = f.querySelector('input[name="<?= csrf_token() ?>"]');
            if (csrf) data.append(csrf.name, csrf.value);

            btn.disabled = true;
            btn.innerHTML = '<i class="ti ti-loader"></i> Menghubungkan...';
            box.className = 'dm-test';
            box.innerHTML = '<i class="ti ti-loader"></i> Menghubungi ' + escapeHtml(f.elements.ip_address.value) + ' lewat SNMP...';

            fetch('<?= site_url('DeviceMonitoring/test') ?>', { method: 'POST', body: data, headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(res => {
                    box.className = 'dm-test ' + (res.ok ? 'is-ok' : 'is-gagal');
                    let html = '<b class="' + (res.ok ? 'text-emerald-600' : 'text-red-600') + '"><i class="ti ' + (res.ok ? 'ti-circle-check' : 'ti-circle-x') + '"></i> ' + escapeHtml(res.pesan) + '</b>';
                    if (res.ok && res.info) {
                        html += '<dl>' + [
                            ['sysName', res.info.sysName], ['sysDescr', res.info.sysDescr], ['Uptime', res.info.sysUpTime],
                            ['sysLocation', res.info.sysLocation], ['Interface', res.info.ifNumber]
                        ].map(([k, v]) => '<dt>' + k + '</dt><dd>' + escapeHtml(v) + '</dd>').join('') + '</dl>';
                    }
                    box.innerHTML = html;
                })
                .catch(() => {
                    box.className = 'dm-test is-gagal';
                    box.innerHTML = '<b class="text-red-600"><i class="ti ti-circle-x"></i> Test gagal dijalankan, coba lagi.</b>';
                })
                .finally(() => {
                    btn.innerHTML = '<i class="ti ti-plug-connected"></i> Test Koneksi SNMP';
                    aturSnmp();
                });
        }

        // ====== VALIDASI SEBELUM SUBMIT (server tetap memeriksa ulang) ======
        const POLA_IPV4 = /^(25[0-5]|2[0-4]\d|1?\d?\d)(\.(25[0-5]|2[0-4]\d|1?\d?\d)){3}$/;
        document.getElementById('dmForm').addEventListener('submit', function(e) {
            const isi = id => (document.getElementById(id).value || '').trim();
            const radio = nama => document.querySelector('input[name="' + nama + '"]:checked');
            const pesan = [];

            if (!isi('hostname') || !isi('ip_address')) pesan.push('Hostname dan IP Address wajib diisi.');
            const ip = isi('ip_address');
            if (ip && !ip.includes(':') && !POLA_IPV4.test(ip)) pesan.push('IP Address tidak valid (contoh 192.168.1.1).');
            if (!radio('vendor')) pesan.push('Vendor wajib dipilih.');
            else if (radio('vendor').value === 'Other' && !isi('vendor_lain')) pesan.push('Vendor Other wajib diisi manual.');
            if (!radio('device_group')) pesan.push('Device Group wajib dipilih.');
            else if (radio('device_group').value === 'Other' && !isi('device_group_lain')) pesan.push('Device Group Other wajib diisi manual.');
            const port = +isi('snmp_port');
            if (!Number.isInteger(port) || port < 1 || port > 65535) pesan.push('SNMP Port harus 1 - 65535.');
            const interval = +isi('polling_interval');
            if (!Number.isInteger(interval) || interval < <?= $M::INTERVAL_MIN ?> || interval > <?= $M::INTERVAL_MAX ?>) pesan.push('Polling Interval harus <?= $M::INTERVAL_MIN ?> - <?= $M::INTERVAL_MAX ?> detik.');
            if (document.getElementById('snmp_community').required && !isi('snmp_community')) pesan.push('SNMP Community (v2c) wajib diisi bila SNMP aktif.');

            if (pesan.length) {
                e.preventDefault();
                Swal.fire({ icon: 'warning', title: 'Form Belum Lengkap', html: pesan.map(p => '<div class="text-left">• ' + escapeHtml(p) + '</div>').join(''), confirmButtonColor: '#2f6bff' });
                return false;
            }
            const btn = this.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.innerHTML = '<i class="ti ti-loader"></i> Menyimpan &amp; menghubungkan...';
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