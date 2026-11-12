<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title>Form Survey Report</title>
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

        <?php
        // $sr = null: tambah; selain itu edit. Isian lama (old) dipakai bila simpan gagal.
        $edit = $sr !== null;
        $val  = static fn (string $k) => old($k, $sr[$k] ?? '', false) ?? '';

        // Pilihan + "Dll": nilai di luar pilihan = Dll dengan keterangan
        $pilih = static function (string $k, array $opsi) use ($sr) {
            $lama = old($k, null, false);
            if ($lama !== null) {
                return [$lama, old($k . '_lain', '', false)];
            }
            $v = (string) ($sr[$k] ?? '');
            if ($v === '' || in_array($v, $opsi, true)) {
                return [$v, ''];
            }

            return ['Dll', $v === 'Dll' ? '' : $v];
        };
        [$tipeBisnis, $tipeBisnisLain]     = $pilih('tipe_bisnis', \App\Models\SurveyReportModel::TIPE_BISNIS);
        [$jenisLayanan, $jenisLayananLain] = $pilih('jenis_layanan', \App\Models\SurveyReportModel::JENIS_LAYANAN);

        $fotoLama = \App\Models\SurveyReportModel::lampiran($sr);

        // Baris tabel Sisi Internal/Teknisi vs Sisi Klien: isian lama (gagal simpan) > data tersimpan > 3 baris kosong
        $oldA = old('rek_internal', null, false);
        $oldB = old('rek_klien', null, false);
        if (is_array($oldA) || is_array($oldB)) {
            $rekBaris = [];
            foreach (array_keys((array) $oldA + (array) $oldB) as $i) {
                $rekBaris[] = [(string) ($oldA[$i] ?? ''), (string) ($oldB[$i] ?? '')];
            }
        } else {
            $rekBaris = \App\Models\SurveyReportModel::rekomendasi($sr);
        }
        while (count($rekBaris) < 3) {
            $rekBaris[] = ['', ''];
        }
        $maksFoto = \App\Controllers\SurveyReport::MAKS_FOTO;
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

            .sr-foto-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 12px;
            }

            .sr-foto-item {
                position: relative;
                border: 1px solid var(--rf-border, #e3e8ee);
                border-radius: 10px;
                overflow: hidden;
                background: #f8fafc;
            }

            html.dark .sr-foto-item {
                background: rgba(255, 255, 255, .04);
            }

            .sr-foto-item img {
                display: block;
                width: 100%;
                height: 110px;
                object-fit: cover;
            }

            .sr-foto-item .sr-foto-aksi {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 6px;
                padding: 6px 8px;
                font-size: 12px;
            }

            .sr-foto-item.is-hapus img {
                opacity: .3;
                filter: grayscale(1);
            }

            .sr-foto-item.is-hapus .sr-foto-aksi {
                color: #dc2626;
                font-weight: 700;
            }

            .sr-foto-baru {
                position: absolute;
                top: 6px;
                left: 6px;
                padding: 2px 8px;
                border-radius: 999px;
                background: #16a34a;
                color: #fff;
                font-size: 11px;
                font-weight: 700;
            }

            /* Tabel Sisi Internal/Teknisi vs Sisi Klien di form */
            .sr-rek-wadah {
                overflow-x: auto;
            }

            .sr-rek {
                width: 100%;
                min-width: 560px;
                border-collapse: separate;
                border-spacing: 0 8px;
            }

            .sr-rek th {
                text-align: left;
                font-size: 12.5px;
                font-weight: 700;
                padding: 0 6px;
            }

            .sr-rek td {
                padding: 0 6px;
                vertical-align: top;
            }

            .sr-rek .sr-rek-no {
                width: 42px;
                text-align: center;
                font-weight: 700;
                padding-top: 10px;
            }

            .sr-rek .sr-rek-aksi {
                width: 46px;
            }

            .sr-rek-hapus {
                width: 38px;
                height: 38px;
                border-radius: 10px;
                color: #dc2626;
                background: rgba(220, 38, 38, .08);
            }

            .sr-foto-item .sr-foto-ket {
                padding: 6px 8px 0;
            }

            .sr-foto-item .sr-foto-ket .rf-input {
                padding: 6px 8px;
                font-size: 12.5px;
            }

            .sr-drop {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 6px;
                padding: 22px 16px;
                border: 2px dashed var(--rf-border, #cbd5e1);
                border-radius: 12px;
                text-align: center;
                cursor: pointer;
                transition: border-color .15s, background .15s;
            }

            .sr-drop:hover,
            .sr-drop.is-over {
                border-color: #2f6bff;
                background: rgba(47, 107, 255, .05);
            }

            .sr-drop i {
                font-size: 30px;
                color: #2f6bff;
            }
        </style>

        <div class="rfo-page p-6">
            <!-- JUDUL -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic is-blue"><i class="ti <?= $edit ? 'ti-edit' : 'ti-file-plus' ?>"></i></span>
                    <div>
                        <h1 class="rfo-title"><?= $edit ? 'Edit Survey Report' : 'Tambah Survey Report' ?></h1>
                        <p class="rfo-subtitle">Data calon pelanggan, keterangan survey, resume/rekomendasi &amp; lampiran foto</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="<?= site_url('SurveyReport') ?>" class="rfo-btn rfo-btn-gray">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <form action="<?= site_url($edit ? 'SurveyReport/update' : 'SurveyReport/save') ?>" method="POST" id="srForm" class="rfo-form" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <?php if ($edit) : ?>
                    <input type="hidden" name="id" value="<?= (int) $sr['id'] ?>">
                <?php endif; ?>

                <!-- IDENTITAS: Survey ID & perusahaan / logo -->
                <div class="rfo-panel mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
                        <div class="rf-group">
                            <label class="rf-label"><i class="ti ti-hash" style="color:#6d5dfc"></i> Survey ID</label>
                            <input type="text" value="<?= esc($nextId) ?>" readonly class="rf-input">
                            <?php if (! $edit) : ?>
                                <p class="rf-hint">Nomor dibuat otomatis saat disimpan.</p>
                            <?php endif; ?>
                        </div>
                        <div class="rf-group">
                            <label class="rf-label" for="perusahaan"><i class="ti ti-building-skyscraper"></i> Perusahaan / Logo <span class="req">*</span></label>
                            <select name="perusahaan" id="perusahaan" required class="rf-input">
                                <option value="">-- Pilih Perusahaan --</option>
                                <?php foreach ($perusahaan as $nama => $logo) : ?>
                                    <option value="<?= esc($nama) ?>" data-logo="<?= base_url($logo) ?>" <?= $val('perusahaan') === $nama ? 'selected' : '' ?>><?= esc($nama) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div id="logo_box" class="rfo-logo-box hidden">
                                <img id="logo_preview" src="" alt="logo perusahaan" class="h-20 w-auto max-w-full object-contain">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 1. DATA CALON PELANGGAN -->
                <div class="rfo-panel mb-6">
                    <div class="sr-section"><span class="sr-no">1</span> Data Calon Pelanggan</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
                        <div class="rf-group md:col-span-2">
                            <label class="rf-label" for="nama_pelanggan"><i class="ti ti-user"></i> Nama Calon Pelanggan <span class="req">*</span></label>
                            <input type="text" name="nama_pelanggan" id="nama_pelanggan" maxlength="200" required value="<?= esc($val('nama_pelanggan')) ?>" placeholder="Nama calon pelanggan / instansi" class="rf-input">
                        </div>
                        <div class="rf-group md:col-span-2">
                            <label class="rf-label"><i class="ti ti-briefcase"></i> Tipe Bisnis <span class="req">*</span></label>
                            <div class="sr-opsi">
                                <?php foreach (array_merge(\App\Models\SurveyReportModel::TIPE_BISNIS, ['Dll']) as $o) : ?>
                                    <label><input type="radio" name="tipe_bisnis" value="<?= esc($o, 'attr') ?>" <?= $tipeBisnis === $o ? 'checked' : '' ?>> <?= esc($o) ?></label>
                                <?php endforeach; ?>
                            </div>
                            <input type="text" name="tipe_bisnis_lain" id="tipe_bisnis_lain" maxlength="100" value="<?= esc($tipeBisnisLain) ?>" placeholder="Sebutkan tipe bisnis lainnya" class="rf-input mt-2 hidden">
                        </div>
                        <div class="rf-group md:col-span-2">
                            <label class="rf-label" for="alamat"><i class="ti ti-map-pin"></i> Alamat <span class="req">*</span></label>
                            <textarea name="alamat" id="alamat" rows="2" required placeholder="Alamat lengkap lokasi survey" class="rf-input"><?= esc($val('alamat')) ?></textarea>
                        </div>
                        <div class="rf-group">
                            <label class="rf-label" for="telepon"><i class="ti ti-phone"></i> Telepon/HP</label>
                            <input type="text" name="telepon" id="telepon" maxlength="50" value="<?= esc($val('telepon')) ?>" placeholder="Contoh: 0812-3456-7890" class="rf-input">
                        </div>
                        <div class="rf-group">
                            <label class="rf-label" for="email_pelanggan"><i class="ti ti-mail"></i> Email</label>
                            <input type="text" name="email_pelanggan" id="email_pelanggan" maxlength="255" value="<?= esc($val('email_pelanggan')) ?>" placeholder="pelanggan@contoh.com" class="rf-input">
                            <p class="rf-hint">Otomatis diisi sebagai tujuan (Kepada) saat kirim email.</p>
                        </div>
                        <div class="rf-group">
                            <label class="rf-label" for="tgl_spk"><i class="ti ti-calendar"></i> Tgl SPK</label>
                            <input type="date" name="tgl_spk" id="tgl_spk" value="<?= esc($val('tgl_spk')) ?>" class="rf-input">
                        </div>
                        <div class="rf-group">
                            <label class="rf-label" for="no_spk"><i class="ti ti-file-certificate"></i> No. SPK</label>
                            <input type="text" name="no_spk" id="no_spk" maxlength="100" value="<?= esc($val('no_spk')) ?>" placeholder="Nomor SPK" class="rf-input">
                        </div>
                        <div class="rf-group">
                            <label class="rf-label" for="tanggal_pelaksanaan"><i class="ti ti-calendar-check" style="color:#16a34a"></i> Tanggal Pelaksanaan <span class="req">*</span></label>
                            <input type="date" name="tanggal_pelaksanaan" id="tanggal_pelaksanaan" required value="<?= esc($val('tanggal_pelaksanaan') ?: ($edit ? '' : date('Y-m-d'))) ?>" class="rf-input">
                        </div>
                    </div>
                </div>

                <!-- 2. KETERANGAN SURVEY -->
                <div class="rfo-panel mb-6">
                    <div class="sr-section"><span class="sr-no">2</span> Keterangan Survey</div>
                    <div class="rf-group">
                        <label class="rf-label"><i class="ti ti-network"></i> Jenis Survey <span class="req">*</span></label>
                        <div class="sr-opsi">
                            <?php foreach (array_merge(\App\Models\SurveyReportModel::JENIS_LAYANAN, ['Dll']) as $o) : ?>
                                <label><input type="radio" name="jenis_layanan" value="<?= esc($o, 'attr') ?>" <?= $jenisLayanan === $o ? 'checked' : '' ?>> <?= esc($o) ?></label>
                            <?php endforeach; ?>
                        </div>
                        <input type="text" name="jenis_layanan_lain" id="jenis_layanan_lain" maxlength="100" value="<?= esc($jenisLayananLain) ?>" placeholder="Sebutkan jenis survey lainnya" class="rf-input mt-2 hidden">
                    </div>
                    <div class="rf-group">
                        <label class="rf-label" for="layanan"><i class="ti ti-world"></i> Layanan <span class="req">*</span></label>
                        <input type="text" name="layanan" id="layanan" list="saran_layanan" maxlength="200" required value="<?= esc($val('layanan')) ?>" placeholder="Contoh: Manage Service" autocomplete="off" class="rf-input">
                    </div>
                    <div class="rf-group">
                        <label class="rf-label" for="catatan"><i class="ti ti-notes"></i> Catatan</label>
                        <textarea name="catatan" id="catatan" rows="6" placeholder="Satu poin per baris, contoh:&#10;Survey hanya dilakukan di gedung lantai 6&#10;Total AP yang digunakan ada 25 Unit" class="rf-input"><?= esc($val('catatan')) ?></textarea>
                        <p class="rf-hint">Satu poin per baris. Di dokumen otomatis bernomor 1), 2), 3), ...</p>
                    </div>

                    <div class="rf-group">
                        <label class="rf-label"><i class="ti ti-table"></i> Tabel Sisi Internal/Teknisi &amp; Sisi Klien</label>
                        <div class="sr-rek-wadah">
                            <table class="sr-rek">
                                <thead>
                                    <tr>
                                        <th class="sr-rek-no">No</th>
                                        <th>Sisi Internal/Teknisi</th>
                                        <th>Sisi Klien</th>
                                        <th class="sr-rek-aksi"></th>
                                    </tr>
                                </thead>
                                <tbody id="srRekBody">
                                    <?php foreach ($rekBaris as $i => [$a, $b]) : ?>
                                        <tr>
                                            <td class="sr-rek-no"><?= $i + 1 ?></td>
                                            <td><textarea name="rek_internal[]" rows="2" class="rf-input" placeholder="Sisi internal / teknisi"><?= esc($a) ?></textarea></td>
                                            <td><textarea name="rek_klien[]" rows="2" class="rf-input" placeholder="Sisi klien"><?= esc($b) ?></textarea></td>
                                            <td class="sr-rek-aksi"><button type="button" class="sr-rek-hapus" title="Hapus baris"><i class="ti ti-trash"></i></button></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" id="srRekTambah" class="rfo-ghost-btn mt-3"><i class="ti ti-plus"></i> Tambah Baris</button>
                        <p class="rf-hint">Baris kosong tidak disimpan. Di dokumen (View, PDF &amp; email) jumlah baris tabel sama dengan baris yang diisi; bila tidak diisi, tabel tidak ditampilkan.</p>
                    </div>
                </div>

                <!-- 3. RESUME/REKOMENDASI -->
                <div class="rfo-panel mb-6">
                    <div class="sr-section"><span class="sr-no">3</span> Resume/Rekomendasi</div>
                    <div class="rf-group">
                        <label class="rf-label" for="resume"><i class="ti ti-file-description"></i> Isi Resume/Rekomendasi</label>
                        <textarea name="resume" id="resume" rows="5" placeholder="Ringkasan hasil survey & rekomendasi (opsional)" class="rf-input"><?= esc($val('resume')) ?></textarea>
                    </div>
                </div>

                <!-- LAMPIRAN FOTO -->
                <div class="rfo-panel mb-6">
                    <div class="sr-section"><span class="sr-no"><i class="ti ti-photo"></i></span> Lampiran Foto</div>

                    <?php if ($fotoLama) : ?>
                        <div class="rf-label mb-2">Foto tersimpan <span class="rf-hint">(keterangan tampil di bawah foto pada dokumen; centang "Hapus" untuk membuang saat disimpan)</span></div>
                        <div class="sr-foto-grid mb-4">
                            <?php foreach ($fotoLama as $i => $l) : ?>
                                <?php $url = site_url('SurveyReport/foto/' . $sr['id'] . '/' . rawurlencode($l['file'])); ?>
                                <div class="sr-foto-item">
                                    <a href="<?= $url ?>" target="_blank" rel="noopener"><img src="<?= $url ?>" alt="Foto <?= $i + 1 ?>" loading="lazy"></a>
                                    <div class="sr-foto-ket"><input type="text" name="ket_lama[<?= esc($l['file'], 'attr') ?>]" maxlength="255" value="<?= esc(old('ket_lama.' . $l['file'], $l['ket'], false) ?? $l['ket']) ?>" placeholder="Keterangan foto" class="rf-input"></div>
                                    <label class="sr-foto-aksi">
                                        <span>Foto <?= $i + 1 ?></span>
                                        <span><input type="checkbox" name="hapus_foto[]" value="<?= esc($l['file'], 'attr') ?>" class="sr-hapus"> Hapus</span>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <label class="sr-drop" id="srDrop">
                        <i class="ti ti-photo-plus"></i>
                        <span class="font-semibold">Klik atau seret foto ke sini</span>
                        <span class="rf-hint">JPG, PNG, atau WEBP, maks. 5 MB per foto, total maks. <?= $maksFoto ?> foto. Isi keterangan di bawah tiap foto, contoh: Server existing saat ini.</span>
                        <input type="file" name="lampiran[]" id="lampiran" accept="image/jpeg,image/png,image/webp" multiple class="hidden">
                    </label>
                    <div id="srFotoBaru" class="sr-foto-grid mt-4"></div>
                </div>

                <!-- DILAKSANAKAN OLEH -->
                <div class="rfo-panel mb-6">
                    <div class="sr-section"><span class="sr-no"><i class="ti ti-signature"></i></span> Dilaksanakan Oleh</div>
                    <div class="rf-group">
                        <label class="rf-label" for="pelaksana"><i class="ti ti-users"></i> Nama Pelaksana</label>
                        <input type="text" name="pelaksana" id="pelaksana" list="saran_pelaksana" maxlength="255" value="<?= esc($val('pelaksana')) ?>" placeholder="Nama pelaksana survey (opsional)" autocomplete="off" class="rf-input">
                        <p class="rf-hint">Tampil di halaman terakhir dokumen, di atas tulisan NOC.</p>
                    </div>
                </div>

                <div class="rfo-actions">
                    <button type="submit" class="rfo-btn rfo-btn-blue"><i class="ti ti-device-floppy"></i> Simpan</button>
                    <button type="button" class="rfo-btn rfo-btn-gray" onclick="window.location.href='<?= site_url('SurveyReport') ?>'"><i class="ti ti-arrow-left"></i> Kembali</button>
                </div>
            </form>
        </div>
    </div>

    <?php foreach ($saran as $kolom => $isi) : ?>
        <datalist id="saran_<?= esc($kolom, 'attr') ?>"><?php foreach ($isi as $v) : ?><option value="<?= esc($v, 'attr') ?>"></option><?php endforeach; ?></datalist>
    <?php endforeach; ?>

    <script>
        // ====== PREVIEW LOGO (logo bawaan sesuai perusahaan yang dipilih) ======
        function showLogo() {
            const opt = document.getElementById('perusahaan').selectedOptions[0];
            const box = document.getElementById('logo_box');
            if (opt && opt.dataset.logo) {
                document.getElementById('logo_preview').src = opt.dataset.logo;
                box.classList.remove('hidden');
            } else {
                box.classList.add('hidden');
            }
        }
        document.getElementById('perusahaan').addEventListener('change', showLogo);
        showLogo();

        // ====== PILIHAN "Dll": tampilkan isian keterangan ======
        function aturLain(nama) {
            const pilih = document.querySelector('input[name="' + nama + '"]:checked');
            const lain = document.getElementById(nama + '_lain');
            const tampil = pilih && pilih.value === 'Dll';
            lain.classList.toggle('hidden', !tampil);
            lain.required = tampil;
        }
        ['tipe_bisnis', 'jenis_layanan'].forEach(nama => {
            document.querySelectorAll('input[name="' + nama + '"]').forEach(r => r.addEventListener('change', () => {
                aturLain(nama);
                if (r.value === 'Dll') document.getElementById(nama + '_lain').focus();
            }));
            aturLain(nama);
        });

        // ====== TABEL SISI INTERNAL/TEKNISI vs SISI KLIEN: tambah / hapus baris ======
        const rekBody = document.getElementById('srRekBody');

        function nomoriRek() {
            rekBody.querySelectorAll('tr').forEach((tr, i) => tr.querySelector('.sr-rek-no').textContent = i + 1);
        }

        document.getElementById('srRekTambah').addEventListener('click', () => {
            const tr = document.createElement('tr');
            tr.innerHTML = '<td class="sr-rek-no"></td>'
                + '<td><textarea name="rek_internal[]" rows="2" class="rf-input" placeholder="Sisi internal / teknisi"></textarea></td>'
                + '<td><textarea name="rek_klien[]" rows="2" class="rf-input" placeholder="Sisi klien"></textarea></td>'
                + '<td class="sr-rek-aksi"><button type="button" class="sr-rek-hapus" title="Hapus baris"><i class="ti ti-trash"></i></button></td>';
            rekBody.appendChild(tr);
            nomoriRek();
            tr.querySelector('textarea').focus();
        });

        rekBody.addEventListener('click', e => {
            const tombol = e.target.closest('.sr-rek-hapus');
            if (!tombol) return;
            const tr = tombol.closest('tr');
            // sisakan minimal satu baris (dikosongkan saja)
            if (rekBody.rows.length > 1) tr.remove();
            else tr.querySelectorAll('textarea').forEach(t => t.value = '');
            nomoriRek();
        });

        // ====== FOTO LAMPIRAN ======
        const MAKS_FOTO = <?= (int) $maksFoto ?>;
        const MAKS_UKURAN = 5 * 1024 * 1024;
        const inputFoto = document.getElementById('lampiran');
        let fotoBaru = new DataTransfer();
        let ketBaru = []; // keterangan foto baru, urutan sama dengan fotoBaru

        function jumlahFotoLama() {
            return document.querySelectorAll('.sr-hapus:not(:checked)').length;
        }

        function tambahFoto(files) {
            const ditolak = [];
            [...files].forEach(f => {
                if (!['image/jpeg', 'image/png', 'image/webp'].includes(f.type)) return ditolak.push(f.name + ' (bukan foto JPG/PNG/WEBP)');
                if (f.size > MAKS_UKURAN) return ditolak.push(f.name + ' (lebih dari 5 MB)');
                if (jumlahFotoLama() + fotoBaru.files.length >= MAKS_FOTO) return ditolak.push(f.name + ' (melebihi ' + MAKS_FOTO + ' foto)');
                fotoBaru.items.add(f);
                ketBaru.push('');
            });
            inputFoto.files = fotoBaru.files;
            gambarFotoBaru();
            if (ditolak.length) {
                Swal.fire({ icon: 'warning', title: 'Sebagian Foto Tidak Ditambahkan', html: ditolak.map(t => '<div class="text-left">• ' + escapeHtml(t) + '</div>').join(''), confirmButtonColor: '#2f6bff' });
            }
        }

        function hapusFotoBaru(idx) {
            const sisa = new DataTransfer();
            [...fotoBaru.files].forEach((f, i) => { if (i !== idx) sisa.items.add(f); });
            fotoBaru = sisa;
            ketBaru.splice(idx, 1);
            inputFoto.files = fotoBaru.files;
            gambarFotoBaru();
        }

        function gambarFotoBaru() {
            const wadah = document.getElementById('srFotoBaru');
            wadah.querySelectorAll('img').forEach(img => URL.revokeObjectURL(img.src));
            wadah.innerHTML = '';
            [...fotoBaru.files].forEach((f, i) => {
                const el = document.createElement('div');
                el.className = 'sr-foto-item';
                el.innerHTML = '<span class="sr-foto-baru">Baru</span><img alt=""><div class="sr-foto-ket"><input type="text" name="ket_baru[]" maxlength="255" placeholder="Keterangan foto" class="rf-input"></div><div class="sr-foto-aksi"><span class="truncate"></span><button type="button" class="text-red-600 font-semibold"><i class="ti ti-x"></i> Batal</button></div>';
                el.querySelector('img').src = URL.createObjectURL(f);
                el.querySelector('.truncate').textContent = f.name;
                const ket = el.querySelector('input');
                ket.value = ketBaru[i] || '';
                ket.addEventListener('input', () => ketBaru[i] = ket.value);
                el.querySelector('button').addEventListener('click', () => hapusFotoBaru(i));
                wadah.appendChild(el);
            });
        }

        function escapeHtml(s) {
            return String(s).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        inputFoto.addEventListener('change', function() {
            // input file menggantikan isinya sendiri; ambil file yang dipilih lalu gabungkan dengan yang sudah ada
            const dipilih = [...this.files].filter(f => ![...fotoBaru.files].includes(f));
            inputFoto.files = fotoBaru.files;
            tambahFoto(dipilih);
        });

        const drop = document.getElementById('srDrop');
        ['dragenter', 'dragover'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.add('is-over'); }));
        ['dragleave', 'drop'].forEach(ev => drop.addEventListener(ev, e => { e.preventDefault(); drop.classList.remove('is-over'); }));
        drop.addEventListener('drop', e => tambahFoto(e.dataTransfer.files));

        document.querySelectorAll('.sr-hapus').forEach(cb => cb.addEventListener('change', function() {
            this.closest('.sr-foto-item').classList.toggle('is-hapus', this.checked);
        }));

        // ====== VALIDASI SEBELUM SUBMIT (server tetap memeriksa ulang) ======
        document.getElementById('srForm').addEventListener('submit', function(e) {
            const isi = id => (document.getElementById(id).value || '').trim();
            const radio = nama => document.querySelector('input[name="' + nama + '"]:checked');
            const pesan = [];

            if (!isi('perusahaan') || !isi('nama_pelanggan') || !isi('alamat') || !isi('tanggal_pelaksanaan') || !isi('layanan')) {
                pesan.push('Semua field bertanda * wajib diisi.');
            }
            if (!radio('tipe_bisnis')) pesan.push('Tipe Bisnis wajib dipilih.');
            if (!radio('jenis_layanan')) pesan.push('Jenis Survey wajib dipilih.');

            const email = isi('email_pelanggan');
            if (email && email.split(/[,;]/).map(s => s.trim()).filter(Boolean).some(s => !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(s))) {
                pesan.push('Email pelanggan tidak valid. Gunakan format nama@domain.com.');
            }
            if (isi('telepon') && !/^[0-9+()\-\s\/]+$/.test(isi('telepon'))) {
                pesan.push('Telepon/HP hanya boleh berisi angka, spasi, +, -, ( ) dan /.');
            }
            if (jumlahFotoLama() + fotoBaru.files.length > MAKS_FOTO) {
                pesan.push('Foto lampiran maksimal ' + MAKS_FOTO + ' foto.');
            }

            if (pesan.length) {
                e.preventDefault();
                Swal.fire({ icon: 'warning', title: 'Form Belum Lengkap', html: pesan.map(p => '<div class="text-left">• ' + escapeHtml(p) + '</div>').join(''), confirmButtonColor: '#2f6bff' });
                return false;
            }
            this.querySelector('button[type="submit"]').disabled = true;
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