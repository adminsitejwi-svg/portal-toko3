<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title>Alert Center</title>
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
                        <span class="flex-1">Data Teknis NETMON</span>
                        <i data-feather="chevron-right" class="arrow w-4 h-4 transition-transform"></i>
                    </a>
                    <ul class="submenu bg-black/20">
                        <li><a href="<?= site_url('DashboardNetmon') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Dashboard Netmon</a></li>
                        <li><a href="<?= site_url('DeviceMonitoring') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Device Monitoring</a></li>
                        <li><a href="<?= site_url('TrafficMonitoring') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Traffic Monitoring</a></li>
                        <li><a href="<?= site_url('Nms') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">NMS</a></li>
                        <li><a href="<?= site_url('Alert') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] text-white font-semibold">Alert</a></li>
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


        <div class="rfo-page p-6" id="alertPage">
            <?php
            $tgl = static fn ($v) => $v ? date('d-m-Y H:i:s', strtotime($v)) : '-';
            // [kelas badge, ikon, label]
            $SEV = ['critical' => ['rb-red', 'ti-urgent', 'Critical'], 'warning' => ['rb-amber', 'ti-alert-triangle', 'Warning'], 'info' => ['rb-blue', 'ti-info-circle', 'Info']];
            $STS = ['open' => ['rb-red', 'ti-circle-dot', 'Open'], 'acknowledged' => ['rb-blue', 'ti-user-check', 'Acknowledged'], 'resolved' => ['rb-green', 'ti-circle-check', 'Resolved']];
            $badge = static fn (array $b) => '<span class="rb ' . $b[0] . '"><i class="ti ' . $b[1] . '"></i>' . $b[2] . '</span>';
            ?>

            <!-- JUDUL -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic is-sky"><i class="ti ti-bell-ringing"></i></span>
                    <div>
                        <h1 class="rfo-title">Alert Center</h1>
                        <p class="rfo-subtitle">Pantauan terpusat gangguan jaringan: perangkat offline, latency, packet loss, CPU, RAM &amp; interface down</p>
                    </div>
                </div>
                <a href="<?= site_url('Nms') ?>" class="rfo-btn rfo-btn-blue"><i class="ti ti-network"></i> NMS</a>
            </div>

            <?php if (! $tabelSiap) : ?>
                <div class="rfo-panel mb-6">
                    <p class="font-semibold text-red-600"><i class="ti ti-database-off"></i> Tabel Alert belum ada.</p>
                    <p class="rfo-subtitle mt-1">Jalankan <b>database/d_device_monitoring.sql</b>, <b>database/d_device_traffic.sql</b> dan <b>database/d_device_nms.sql</b> (kalau belum), lalu <b>database/d_device_alert.sql</b> di database.</p>
                </div>
            <?php else : ?>
                <?php
                // filter yang sedang dipakai, hanya yang berbeda dari bawaan (agar URL pendek)
                $bawaan  = ['status' => 'active', 'severity' => 'all', 'q' => '', 'dari' => '', 'sampai' => '', 'per' => 25, 'hal' => 1, 'per_if' => 25, 'hal_if' => 1];
                $url     = static function (array $ubah = [], string $path = 'Alert') use ($f, $bawaan) {
                    $q = http_build_query(array_filter($ubah + $f, static fn ($v, $k) => $v !== $bawaan[$k], ARRAY_FILTER_USE_BOTH));

                    return site_url($path) . ($q !== '' ? '?' . $q : '');
                };
                $kembali = (string) parse_url($url(), PHP_URL_QUERY);
                $sisip   = static fn () => '<input type="hidden" name="token" value="' . esc($token, 'attr') . '"><input type="hidden" name="kembali" value="' . esc($kembali, 'attr') . '">';
                // tombol halaman: Prev, 2 halaman di kiri-kanan halaman aktif, Next
                $pager = static function (string $kunci, int $hal, int $maks, int $total, int $per, int $jml, string $nama) use ($url) {
                    $awal = $total ? ($hal - 1) * $per + 1 : 0;
                    $h    = '<div class="al-pager"><span>Menampilkan ' . $awal . '–' . ($total ? $awal + $jml - 1 : 0) . ' dari ' . number_format($total, 0, ',', '.') . ' ' . $nama . ' · Halaman ' . $hal . ' / ' . $maks . '</span><span class="al-hal">';
                    $h .= $hal > 1 ? '<a href="' . esc($url([$kunci => $hal - 1]), 'attr') . '">‹ Prev</a>' : '<em>‹ Prev</em>';
                    for ($p = max(1, $hal - 2); $p <= min($maks, $hal + 2); $p++) {
                        $h .= $p === $hal ? '<b>' . $p . '</b>' : '<a href="' . esc($url([$kunci => $p]), 'attr') . '">' . $p . '</a>';
                    }
                    $h .= $hal < $maks ? '<a href="' . esc($url([$kunci => $hal + 1]), 'attr') . '">Next ›</a>' : '<em>Next ›</em>';

                    return $h . '</span></div>';
                };
                $kartu = [
                    ['red', 'ti-circle-dot', 'Open Alerts', $ringkas['open'], 'Belum ditangani'],
                    ['blue', 'ti-user-check', 'Acknowledged', $ringkas['acknowledged'], 'Sedang ditangani'],
                    ['red', 'ti-urgent', 'Critical Active', $ringkas['critical'], 'Masih aktif'],
                    ['amber', 'ti-alert-triangle', 'Warning Active', $ringkas['warning'], 'Masih aktif'],
                    ['green', 'ti-circle-check', 'Resolved', $ringkas['resolved'], 'Sudah selesai'],
                ];
                ?>

                <!-- KARTU RINGKASAN -->
                <div class="al-kartu mb-6" data-segar="kartu">
                    <?php foreach ($kartu as [$warna, $ikon, $label, $nilai, $ket]) : ?>
                        <div class="rfo-stat rfo-stat-<?= $warna ?>">
                            <span class="rfo-stat-ic"><i class="ti <?= $ikon ?>"></i></span>
                            <div>
                                <div class="rfo-stat-label"><?= $label ?></div>
                                <div class="rfo-stat-value"><?= number_format((int) $nilai, 0, ',', '.') ?></div>
                                <div class="rfo-stat-note"><?= $ket ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- DAFTAR ALERT -->
                <div class="rfo-panel mb-6">
                    <form method="get" action="<?= site_url('Alert') ?>" class="al-filter" id="alFilter">
                        <label class="rfo-field">
                            <i class="ti ti-activity" style="color:#2f6bff"></i>
                            <select name="status" class="al-select" data-kirim title="Filter Status">
                                <?php foreach (['active' => 'Active Alerts', 'open' => 'Open', 'acknowledged' => 'Acknowledged', 'resolved' => 'Resolved', 'all' => 'Semua Status'] as $k => $v) : ?>
                                    <option value="<?= $k ?>" <?= $f['status'] === $k ? 'selected' : '' ?>><?= $v ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="rfo-field">
                            <i class="ti ti-alert-triangle" style="color:#f59e0b"></i>
                            <select name="severity" class="al-select" data-kirim title="Filter Severity">
                                <?php foreach (['all' => 'Semua Severity', 'critical' => 'Critical', 'warning' => 'Warning', 'info' => 'Info'] as $k => $v) : ?>
                                    <option value="<?= $k ?>" <?= $f['severity'] === $k ? 'selected' : '' ?>><?= $v ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <div class="rfo-search">
                            <i class="ti ti-search"></i>
                            <input type="search" name="q" value="<?= esc($f['q'], 'attr') ?>" maxlength="100" placeholder="Cari hostname / IP..." />
                        </div>
                        <input type="date" name="dari" class="al-input" value="<?= esc($f['dari'], 'attr') ?>" title="Tanggal mulai" aria-label="Tanggal mulai">
                        <input type="date" name="sampai" class="al-input" value="<?= esc($f['sampai'], 'attr') ?>" title="Tanggal akhir" aria-label="Tanggal akhir">
                        <label class="rfo-field">
                            <i class="ti ti-list-numbers" style="color:#16a34a"></i>
                            <select name="per" class="al-select al-select-kecil" data-kirim title="Baris per halaman">
                                <?php foreach ([10, 25, 50, 100] as $n) : ?>
                                    <option value="<?= $n ?>" <?= $f['per'] === $n ? 'selected' : '' ?>><?= $n ?> / halaman</option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <input type="hidden" name="per_if" value="<?= (int) $f['per_if'] ?>">
                        <button type="submit" class="rfo-btn rfo-btn-blue border-0 cursor-pointer al-tombol"><i class="ti ti-filter"></i> Terapkan</button>
                        <a href="<?= site_url('Alert') ?>" class="rfo-ghost-btn"><i class="ti ti-refresh"></i> Reset</a>
                        <a href="<?= esc($url(['hal' => 1, 'hal_if' => 1, 'per' => 25, 'per_if' => 25], 'Alert/export'), 'attr') ?>" class="rfo-ghost-btn" download><i class="ti ti-file-spreadsheet"></i> Export CSV</a>
                    </form>

                    <div data-segar="alert">
                        <div class="table-scroll">
                            <table class="rfo-table al-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Aksi</th>
                                        <th>Severity</th>
                                        <th>Alert / Device</th>
                                        <th>Pesan</th>
                                        <th>Status</th>
                                        <th>Terakhir Terlihat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (! $alert) : ?>
                                        <tr><td colspan="7" class="al-kosong">Tidak ada alert untuk filter ini.</td></tr>
                                    <?php endif; ?>
                                    <?php foreach ($alert as $no => $a) : ?>
                                        <tr>
                                            <td><?= ($f['hal'] - 1) * $f['per'] + $no + 1 ?></td>
                                            <td class="ra-aksi">
                                                <?php if ($a['status'] === 'resolved') : ?>
                                                    <span class="rb-sub">Selesai</span>
                                                <?php else : ?>
                                                    <?php if ($a['status'] === 'open') : ?>
                                                        <form method="post" action="<?= site_url('Alert/acknowledge/' . $a['id']) ?>" class="al-form">
                                                            <?= $sisip() ?>
                                                            <button type="submit" class="act act-view"><i class="ti ti-user-check"></i> Acknowledge</button>
                                                        </form>
                                                    <?php endif; ?>
                                                    <form method="post" action="<?= site_url('Alert/resolve/' . $a['id']) ?>" class="al-form" data-tanya="<?= esc($a['title'], 'attr') ?>">
                                                        <?= $sisip() ?>
                                                        <button type="submit" class="act act-email"><i class="ti ti-check"></i> Resolve</button>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $badge($SEV[$a['severity']] ?? $SEV['info']) ?></td>
                                            <td>
                                                <div class="col-bold"><?= esc($a['title']) ?></div>
                                                <div class="rb-sub"><?= esc(implode(' · ', array_filter([$a['hostname'] ?? 'Unknown device', $a['ip_address'], $a['if_name']]))) ?></div>
                                            </td>
                                            <td class="al-pesan"><?= esc($a['message'] ?: '-') ?></td>
                                            <td class="al-status">
                                                <?= $badge($STS[$a['status']]) ?>
                                                <?php if ($a['acknowledged_by']) : ?>
                                                    <div class="rb-sub mt-1">oleh <?= esc($a['acknowledged_by']) ?>, <?= $tgl($a['acknowledged_at']) ?></div>
                                                <?php endif; ?>
                                                <?php if ($a['resolved_at']) : ?>
                                                    <div class="rb-sub mt-1">selesai <?= $tgl($a['resolved_at']) ?></div>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $tgl($a['last_seen']) ?><div class="rb-sub">Pertama: <?= $tgl($a['first_seen']) ?></div></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?= $pager('hal', $f['hal'], $halMaks, $total, $f['per'], count($alert), 'alert') ?>
                    </div>
                    <p class="al-hint mt-3"><i class="ti ti-info-circle"></i> Alert muncul otomatis saat: device tidak membalas ping, latency &ge; 200 ms, packet loss &ge; 20%, CPU atau RAM &ge; 90%, atau interface fisik down (admin up). Alert selesai sendiri kalau kondisinya pulih.</p>
                </div>

                <!-- PENGATURAN ALERT INTERFACE -->
                <div class="rfo-panel" id="alInterface">
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div>
                            <h3 class="al-judul"><i class="ti ti-settings"></i> Interface Alert Settings</h3>
                            <span class="rb-sub">Exclude hanya mematikan alert, monitoring interface tetap jalan</span>
                        </div>
                        <form method="get" action="<?= site_url('Alert') ?>">
                            <?php foreach (['status', 'severity', 'q', 'dari', 'sampai', 'per', 'hal'] as $k) : ?>
                                <input type="hidden" name="<?= $k ?>" value="<?= esc((string) $f[$k], 'attr') ?>">
                            <?php endforeach; ?>
                            <label class="rfo-field">
                                <i class="ti ti-list-numbers" style="color:#16a34a"></i>
                                <select name="per_if" class="al-select al-select-kecil" data-kirim title="Baris per halaman">
                                    <?php foreach ([10, 25, 50, 100] as $n) : ?>
                                        <option value="<?= $n ?>" <?= $f['per_if'] === $n ? 'selected' : '' ?>><?= $n ?> / halaman</option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </form>
                    </div>
                    <div data-segar="interface">
                        <div class="table-scroll">
                            <table class="rfo-table al-table">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Aksi</th>
                                        <th>Device</th>
                                        <th>Interface</th>
                                        <th>Status Interface</th>
                                        <th>Alert</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (! $interface) : ?>
                                        <tr><td colspan="6" class="al-kosong">Belum ada interface terdaftar.</td></tr>
                                    <?php endif; ?>
                                    <?php foreach ($interface as $no => $i) : ?>
                                        <?php $aktif = $i['dikecualikan'] === null; ?>
                                        <tr>
                                            <td><?= ($f['hal_if'] - 1) * $f['per_if'] + $no + 1 ?></td>
                                            <td class="ra-aksi">
                                                <form method="post" action="<?= site_url('Alert/interface/' . $i['id']) ?>" class="al-form">
                                                    <?= $sisip() ?>
                                                    <input type="hidden" name="aktif" value="<?= $aktif ? 0 : 1 ?>">
                                                    <button type="submit" class="act <?= $aktif ? 'act-del' : 'act-email' ?>"><i class="ti <?= $aktif ? 'ti-bell-off' : 'ti-bell' ?>"></i> <?= $aktif ? 'Exclude' : 'Enable' ?></button>
                                                </form>
                                            </td>
                                            <td><div class="col-bold"><?= esc($i['hostname']) ?></div><div class="rb-sub"><?= esc($i['ip_address']) ?></div></td>
                                            <td><?= esc($i['if_name']) ?><?= $i['if_alias'] ? '<div class="rb-sub">' . esc($i['if_alias']) . '</div>' : '' ?></td>
                                            <td><?= $badge(match ((int) $i['oper_status']) {
                                                1       => ['rb-green', 'ti-circle-check', 'Up'],
                                                2       => ['rb-red', 'ti-circle-x', 'Down'],
                                                default => ['rb-blue', 'ti-help-circle', 'Unknown'],
                                            }) ?></td>
                                            <td><?= $badge($aktif ? ['rb-green', 'ti-bell', 'Alert Enabled'] : ['rb-amber', 'ti-bell-off', 'Excluded']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?= $pager('hal_if', $f['hal_if'], $halMaksIf, $totalIf, $f['per_if'], count($interface), 'interface') ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <style>
        .al-kartu { display: grid; grid-template-columns: repeat(5, minmax(0, 1fr)); gap: 16px; }
        .al-kartu .rfo-stat { gap: 14px; padding: 18px; }
        .al-filter { display: flex; flex-wrap: wrap; align-items: center; gap: 12px; margin-bottom: 16px; }
        .rfo-field .al-select {
            height: 42px; border: 1px solid var(--rf-border); border-radius: 10px; padding: 0 36px 0 38px;
            font-size: 13px; font-weight: 600; color: var(--rf-text); background-color: var(--rf-card); outline: none; cursor: pointer;
            width: 185px; max-width: 100%; appearance: none; -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%237a869a' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 14px center;
        }
        .rfo-field .al-select-kecil { width: 165px; }
        .al-input { height: 42px; border: 1px solid var(--rf-border); border-radius: 10px; padding: 0 12px; font-size: 13px; font-weight: 600; color: var(--rf-text); background: var(--rf-card); outline: none; }
        .al-tombol { height: 42px; padding: 0 18px; font-size: 13px; }
        .al-judul { font-size: 15px; font-weight: 700; color: var(--rf-text); display: flex; align-items: center; gap: 8px; }
        /* tabel tanpa DataTables: rfo.css menyembunyikan .rfo-table tanpa kelas .dataTable, jadi dimunculkan lagi di sini */
        .rfo-page table.rfo-table.al-table { visibility: visible; width: 100%; min-width: 900px; border-collapse: collapse; margin: 0; }
        .al-table thead th { background: var(--rf-soft); color: var(--rf-head); font-weight: 700; font-size: 12.5px; text-align: left; padding: 14px 10px; border-bottom: 1px solid var(--rf-border); white-space: nowrap; }
        .al-table tbody td { padding: 12px 10px; font-size: 13px; color: var(--rf-text); border-bottom: 1px solid var(--rf-border); vertical-align: middle; white-space: nowrap; }
        .al-table tbody tr:last-child td { border-bottom: 0; }
        .al-table tbody tr:hover td { background: var(--rf-soft); }
        .al-table .al-pesan { white-space: normal; min-width: 190px; }
        .al-table .al-status { white-space: normal; min-width: 150px; }
        .al-table .al-kosong { text-align: center; color: var(--rf-muted); padding: 34px 10px; }
        .al-hint { font-size: 12.5px; color: var(--rf-muted); }
        .al-form { display: inline-block; margin: 0; }
        .al-form .act { cursor: pointer; }
        .al-pager { display: flex; justify-content: space-between; align-items: center; gap: 10px; flex-wrap: wrap; padding-top: 14px; font-size: 12px; color: var(--rf-muted); }
        .al-hal { display: inline-flex; flex-wrap: wrap; gap: 5px; }
        .al-hal > * { min-width: 32px; height: 32px; padding: 0 10px; border-radius: 8px; border: 1px solid var(--rf-border); background: var(--rf-card); color: var(--rf-text); font-weight: 600; font-style: normal; display: inline-flex; align-items: center; justify-content: center; }
        .al-hal a:hover { border-color: #6d8dff; color: #2f6bff; }
        .al-hal b { background: #2f6bff; border-color: #2f6bff; color: #fff; }
        .al-hal em { opacity: .4; }
        @media (max-width: 1279px) { .al-kartu { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
        @media (max-width: 767px) { .al-kartu { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; } .al-kartu .rfo-stat-ic { display: none; } }
        @media (max-width: 640px) { .al-filter > *, .al-filter .al-select { width: 100% !important; justify-content: center; } }
    </style>

    <script>
        (function() {
            const halaman = document.getElementById('alertPage');
            if (!halaman.querySelector('[data-segar]')) return;

            // select langsung menerapkan filter
            halaman.addEventListener('change', e => {
                if (e.target.matches('[data-kirim]')) e.target.form.requestSubmit();
            });

            // Resolve: tanya dulu
            halaman.addEventListener('submit', e => {
                const form = e.target;
                if (!form.dataset.tanya || form.dataset.yakin) return;
                e.preventDefault();
                Swal.fire({
                    icon: 'question', title: 'Resolve alert ini?', text: form.dataset.tanya,
                    showCancelButton: true, confirmButtonText: 'Ya, Resolve', cancelButtonText: 'Batal',
                    confirmButtonColor: '#16a34a', cancelButtonColor: '#94a3b8'
                }).then(r => {
                    if (!r.isConfirmed) return;
                    form.dataset.yakin = '1';
                    form.requestSubmit();
                });
            });

            // data diperbarui tiap 60 detik tanpa memuat ulang halaman (filter yang sedang diketik tidak hilang)
            async function segarkan() {
                if (document.hidden || Swal.isVisible()) return;
                try {
                    const r = await fetch(window.location.href, { cache: 'no-store' });
                    if (!r.ok || r.redirected) return;
                    const baru = new DOMParser().parseFromString(await r.text(), 'text/html');
                    halaman.querySelectorAll('[data-segar]').forEach(el => {
                        const ganti = baru.querySelector('[data-segar="' + el.dataset.segar + '"]');
                        if (ganti && !Swal.isVisible()) el.innerHTML = ganti.innerHTML;
                    });
                } catch (err) { /* dicoba lagi pada putaran berikutnya */ }
            }
            setInterval(segarkan, 60000);
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