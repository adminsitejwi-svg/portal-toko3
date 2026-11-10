<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title>Monthly Report</title>
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

        /* ===== MONTHLY REPORT ===== */
        /* header 2 baris: baris kedua menempel di bawah baris pertama saat tabel di-scroll */
        .rfo-page .table-scroll thead tr.mr-head2 th {
            top: var(--mr-h1, 38px);
        }

        #reportTable thead th.mr-grup,
        #reportTable thead tr.mr-head2 th {
            text-align: center;
        }

        #reportTable td.mr-num {
            text-align: center;
            font-variant-numeric: tabular-nums;
        }

        #reportTable td.mr-long {
            white-space: normal;
            min-width: 220px;
            max-width: 360px;
        }
        .mr-section {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 6px 0 12px;
            font-size: 14px;
            font-weight: 800;
            color: var(--rf-head);
        }

        .mr-section i {
            font-size: 18px;
            color: #2f6bff;
        }

        .mr-section .mr-opsional {
            font-size: 12px;
            font-weight: 500;
            color: #94a3b8;
        }

        .mr-durasi {
            background: #f1f5f9 !important;
            font-weight: 700;
            text-align: center;
            cursor: default;
        }

        html.dark .mr-durasi {
            background: rgba(255, 255, 255, .06) !important;
        }

        .mr-chip {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            color: #0369a1;
            background: #e0f2fe;
        }

        html.dark .mr-chip {
            color: #7dd3fc;
            background: rgba(14, 165, 233, .16);
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
                        <li><a href="<?= site_url('IpPublic') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data IP Public</a></li>
                        <li><a href="<?= site_url('IpPrivate') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data IP Private</a></li>
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
            <?php
            // tanggal + jam untuk tabel: 10/11/2026 08:30
            $tgl = fn ($v) => $v ? date('d/m/Y H:i', strtotime($v)) : '-';
            // menit -> ["7 jam", "15 menit"]
            $jm = fn ($m) => $m === null ? ['-', '-'] : [intdiv((int) $m, 60) . " jam", ((int) $m % 60) . " menit"];

            $BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $daftarBulan = [];
            foreach ($report as $r) {
                $k = substr($r['start_time'], 0, 7);
                $daftarBulan[$k] = $BULAN[(int) substr($k, 5, 2) - 1] . ' ' . substr($k, 0, 4);
            }
            krsort($daftarBulan);
            // bawaan: bulan berjalan bila sudah ada datanya
            $bulanIni = isset($daftarBulan[date('Y-m')]) ? date('Y-m') : '';

            $opsi = function (array $isi) {
                natcasesort($isi);
                return $isi;
            };
            ?>
            <!-- JUDUL -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic is-sky"><i class="ti ti-calendar-stats"></i></span>
                    <div>
                        <h1 class="rfo-title">Monthly Report</h1>
                        <div class="rfo-subtitle">Rekap gangguan per bulan: durasi, problem, action, solver &amp; penggunaan backup</div>
                    </div>
                </div>
                <button type="button" onclick="openReport()" class="rfo-btn rfo-btn-blue border-0 cursor-pointer">
                    <i class="ti ti-plus"></i> Tambah Report
                </button>
            </div>

            <div class="card w-full min-w-0">
                <div class="card-body">
                    <!-- TOOLBAR -->
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div class="flex items-center gap-3 flex-wrap">
                            <div id="lengthArea"></div>
                            <select id="filterBulan" class="filter-select" title="Filter Bulan">
                                <option value="">Semua Bulan</option>
                                <?php foreach ($daftarBulan as $k => $label) : ?>
                                    <option value="<?= esc($k, 'attr') ?>" <?= $k === $bulanIni ? 'selected' : '' ?>><?= esc($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select id="filterClient" class="filter-select" title="Filter Client">
                                <option value="">Semua Client</option>
                                <?php foreach ($opsi(array_unique(array_column($report, 'client'))) as $v) : ?>
                                    <option value="<?= esc($v, 'attr') ?>"><?= esc($v) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select id="filterLayanan" class="filter-select" title="Filter Jenis Layanan">
                                <option value="">Semua Layanan</option>
                                <?php foreach ($opsi(array_unique(array_column($report, 'jenis_layanan'))) as $v) : ?>
                                    <option value="<?= esc($v, 'attr') ?>"><?= esc($v) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <div class="rfo-search">
                                <i class="ti ti-search"></i>
                                <input type="text" id="customSearch" placeholder="Cari client, problem, solver..." />
                            </div>
                            <button type="button" class="rfo-ghost-btn rfo-reset"><i class="ti ti-refresh"></i> Reset</button>
                            <div id="exportArea"></div>
                        </div>
                    </div>

                    <!-- TABLE -->
                    <div class="table-scroll">
                        <table id="reportTable" class="rfo-table display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2" class="no-export">Aksi</th>
                                    <th rowspan="2">Client</th>
                                    <th rowspan="2">Start Time</th>
                                    <th rowspan="2">Finish Time</th>
                                    <th colspan="2" class="mr-grup">Duration</th>
                                    <th rowspan="2">Jenis Layanan</th>
                                    <th rowspan="2">Problem</th>
                                    <th rowspan="2">Action</th>
                                    <th rowspan="2">Note</th>
                                    <th rowspan="2">Solver</th>
                                    <th colspan="2" class="mr-grup">Using Backup</th>
                                    <th colspan="2" class="mr-grup">Duration</th>
                                </tr>
                                <tr class="mr-head2">
                                    <th>Hour</th>
                                    <th>Minute</th>
                                    <th>Start Time</th>
                                    <th>Finish Time</th>
                                    <th>Hour</th>
                                    <th>Minute</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($report as $row) : ?>
                                    <?php
                                    [$jam, $menit]   = $jm($row['durasi_menit']);
                                    [$bJam, $bMenit] = $jm($row['backup_durasi_menit']);
                                    ?>
                                    <tr data-bulan="<?= esc(substr($row['start_time'], 0, 7), 'attr') ?>" data-durasi="<?= (int) $row['durasi_menit'] ?>" data-backup="<?= (int) $row['backup_durasi_menit'] ?>">
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <button type="button" data-row="<?= esc(json_encode($row), 'attr') ?>"
                                                onclick="openReport(JSON.parse(this.dataset.row))" class="btn btn-sm btn-primary">
                                                <i class="ti ti-edit"></i> <span>Edit</span>
                                            </button>
                                            <button type="button" onclick="confirmDelete(<?= (int) $row['id'] ?>, this.dataset.nama)" data-nama="<?= esc($row['client'] . ' (' . $tgl($row['start_time']) . ')', 'attr') ?>" class="btn btn-sm btn-danger">
                                                <i class="ti ti-trash"></i> <span>Hapus</span>
                                            </button>
                                            <button type="button" data-row="<?= esc(json_encode($row), 'attr') ?>"
                                                onclick="openViewModal(JSON.parse(this.dataset.row))" class="btn btn-sm btn-info" title="Lihat">
                                                <i class="ti ti-eye"></i> <span>View</span>
                                            </button>
                                        </td>
                                        <td class="col-bold"><?= esc($row['client']) ?></td>
                                        <td data-order="<?= esc($row['start_time'], 'attr') ?>"><?= $tgl($row['start_time']) ?></td>
                                        <td data-order="<?= esc($row['finish_time'], 'attr') ?>"><?= $tgl($row['finish_time']) ?></td>
                                        <td class="mr-num" data-order="<?= (int) $row['durasi_menit'] ?>"><?= $jam ?></td>
                                        <td class="mr-num" data-order="<?= (int) $row['durasi_menit'] % 60 ?>"><?= $menit ?></td>
                                        <td><span class="mr-chip"><?= esc($row['jenis_layanan']) ?></span></td>
                                        <td class="mr-long"><?= nl2br(esc($row['problem'])) ?></td>
                                        <td class="mr-long"><?= nl2br(esc($row['action'])) ?></td>
                                        <td class="mr-long"><?= $row['note'] ? nl2br(esc($row['note'])) : '-' ?></td>
                                        <td><?= esc($row['solver']) ?></td>
                                        <td data-order="<?= esc((string) $row['backup_start'], 'attr') ?>"><?= $tgl($row['backup_start']) ?></td>
                                        <td data-order="<?= esc((string) $row['backup_finish'], 'attr') ?>"><?= $tgl($row['backup_finish']) ?></td>
                                        <td class="mr-num" data-order="<?= (int) $row['backup_durasi_menit'] ?>"><?= $bJam ?></td>
                                        <td class="mr-num" data-order="<?= (int) $row['backup_durasi_menit'] % 60 ?>"><?= $bMenit ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ====== POP UP VIEW ====== -->
    <div id="viewModal" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4" onclick="if (event.target === this) closeViewModal()">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-3xl p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold">View Monthly Report</h3>
                <button type="button" onclick="closeViewModal()" aria-label="Tutup"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div><div class="text-gray-500">Client</div><div class="font-medium" id="v_client"></div></div>
                <div><div class="text-gray-500">Jenis Layanan</div><div class="font-medium" id="v_jenis_layanan"></div></div>
                <div><div class="text-gray-500">Solver</div><div class="font-medium" id="v_solver"></div></div>
                <div><div class="text-gray-500">Start Time</div><div class="font-medium" id="v_start_time"></div></div>
                <div><div class="text-gray-500">Finish Time</div><div class="font-medium" id="v_finish_time"></div></div>
                <div><div class="text-gray-500">Duration</div><div class="font-medium" id="v_durasi"></div></div>
                <div class="md:col-span-3"><div class="text-gray-500">Problem</div><div class="font-medium whitespace-pre-line" id="v_problem"></div></div>
                <div class="md:col-span-3"><div class="text-gray-500">Action</div><div class="font-medium whitespace-pre-line" id="v_action"></div></div>
                <div class="md:col-span-3"><div class="text-gray-500">Note</div><div class="font-medium whitespace-pre-line" id="v_note"></div></div>
                <div><div class="text-gray-500">Using Backup - Start Time</div><div class="font-medium" id="v_backup_start"></div></div>
                <div><div class="text-gray-500">Using Backup - Finish Time</div><div class="font-medium" id="v_backup_finish"></div></div>
                <div><div class="text-gray-500">Duration Backup</div><div class="font-medium" id="v_backup_durasi"></div></div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeViewModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Tutup</button>
            </div>
        </div>
    </div>

    <!-- ====== POP UP FORM REPORT (tambah / edit) ====== -->
    <div id="modal_report" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4" onclick="if (event.target === this) closeReport()">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-4xl p-6 max-h-[92vh] overflow-y-auto rfo-form">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold" id="mrJudulForm">Tambah Monthly Report</h3>
                <button type="button" onclick="closeReport()" aria-label="Tutup"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <form method="POST" id="formReport" data-save="<?= site_url('MonthlyReport/save') ?>" data-update="<?= site_url('MonthlyReport/update') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4">
                    <div class="rf-group"><label class="rf-label">Client <span class="req">*</span></label><input class="rf-input" name="client" list="saranClient" maxlength="150" required placeholder="Nama client" autocomplete="off"></div>
                    <div class="rf-group"><label class="rf-label">Jenis Layanan <span class="req">*</span></label><input class="rf-input" name="jenis_layanan" list="saranLayanan" maxlength="100" required placeholder="Contoh: Dedicated Internet" autocomplete="off"></div>
                    <div class="rf-group"><label class="rf-label">Solver <span class="req">*</span></label><input class="rf-input" name="solver" list="saranSolver" maxlength="150" required placeholder="Nama yang menangani" autocomplete="off"></div>
                </div>

                <div class="mr-section"><i class="ti ti-clock"></i> Waktu Gangguan</div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4">
                    <div class="rf-group col-span-2 md:col-span-1"><label class="rf-label">Start Time <span class="req">*</span></label><input type="datetime-local" class="rf-input" name="start_time" required></div>
                    <div class="rf-group col-span-2 md:col-span-1"><label class="rf-label">Finish Time <span class="req">*</span></label><input type="datetime-local" class="rf-input" name="finish_time" required></div>
                    <div class="rf-group"><label class="rf-label">Duration Hour</label><input class="rf-input mr-durasi" id="durJam" readonly tabindex="-1" value="-"></div>
                    <div class="rf-group"><label class="rf-label">Duration Minute</label><input class="rf-input mr-durasi" id="durMenit" readonly tabindex="-1" value="-"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
                    <div class="rf-group"><label class="rf-label">Problem <span class="req">*</span></label><textarea class="rf-input" name="problem" rows="3" required placeholder="Masalah yang terjadi"></textarea></div>
                    <div class="rf-group"><label class="rf-label">Action <span class="req">*</span></label><textarea class="rf-input" name="action" rows="3" required placeholder="Tindakan penanganan"></textarea></div>
                    <div class="rf-group md:col-span-2"><label class="rf-label">Note</label><textarea class="rf-input" name="note" rows="2" placeholder="Opsional"></textarea></div>
                </div>

                <div class="mr-section"><i class="ti ti-arrows-exchange"></i> Using Backup <span class="mr-opsional">(opsional, kosongkan bila tidak pakai backup)</span></div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-x-4">
                    <div class="rf-group col-span-2 md:col-span-1"><label class="rf-label">Start Time</label><input type="datetime-local" class="rf-input" name="backup_start"></div>
                    <div class="rf-group col-span-2 md:col-span-1"><label class="rf-label">Finish Time</label><input type="datetime-local" class="rf-input" name="backup_finish"></div>
                    <div class="rf-group"><label class="rf-label">Duration Hour</label><input class="rf-input mr-durasi" id="bDurJam" readonly tabindex="-1" value="-"></div>
                    <div class="rf-group"><label class="rf-label">Duration Minute</label><input class="rf-input mr-durasi" id="bDurMenit" readonly tabindex="-1" value="-"></div>
                </div>
                <div class="rf-hint mb-2"><i class="ti ti-info-circle"></i> Duration Hour &amp; Minute dihitung otomatis dari Start Time sampai Finish Time.</div>

                <div class="d-flex">
                    <button type="submit"><i class="ti ti-device-floppy"></i> Simpan</button>
                    <button type="button" class="btn-back" onclick="closeReport()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <datalist id="saranClient"><?php foreach ($saranClient as $v) : ?><option value="<?= esc($v, 'attr') ?>"></option><?php endforeach; ?></datalist>
    <datalist id="saranLayanan"><?php foreach ($saranLayanan as $v) : ?><option value="<?= esc($v, 'attr') ?>"></option><?php endforeach; ?></datalist>
    <datalist id="saranSolver"><?php foreach ($saranSolver as $v) : ?><option value="<?= esc($v, 'attr') ?>"></option><?php endforeach; ?></datalist>

    <?php
    // Gagal simpan (validasi): pop up dibuka lagi dengan isian sebelumnya
    $KOLOM_FORM = ['id', 'client', 'start_time', 'finish_time', 'jenis_layanan', 'problem', 'action', 'note', 'solver', 'backup_start', 'backup_finish'];
    $popupGagal = session()->getFlashdata('popup') === 'monthly';
    $isianLama  = [];
    if ($popupGagal) {
        foreach ($KOLOM_FORM as $k) {
            $isianLama[$k] = old($k, '', false);
        }
    }
    ?>

    <script>
        const KOLOM_FORM = <?= json_encode(array_values(array_diff($KOLOM_FORM, ['id']))) ?>;
        const BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        // "2026-11-10 08:30:00" -> "2026-11-10T08:30" (nilai input datetime-local)
        const keInput = v => v ? String(v).substring(0, 16).replace(' ', 'T') : '';

        // selisih menit dua isian datetime-local (null bila belum lengkap)
        function selisihMenit(mulai, selesai) {
            if (!mulai || !selesai) return null;
            const a = new Date(mulai), b = new Date(selesai);
            if (isNaN(a) || isNaN(b)) return null;
            return Math.floor((b - a) / 60000);
        }

        // ====== HITUNG DURATION OTOMATIS DI FORM ======
        function hitungDurasi() {
            const f = document.getElementById('formReport').elements;
            const tampil = (m, idJam, idMenit) => {
                const jam = document.getElementById(idJam), menit = document.getElementById(idMenit);
                if (m === null) { jam.value = menit.value = '-'; jam.style.color = menit.style.color = ''; return; }
                const salah = m < 0;
                jam.value = salah ? '!' : Math.floor(m / 60) + ' jam';
                menit.value = salah ? '!' : (m % 60) + ' menit';
                jam.style.color = menit.style.color = salah ? '#dc2626' : '';
                jam.title = menit.title = salah ? 'Finish Time sebelum Start Time' : '';
            };
            tampil(selisihMenit(f.start_time.value, f.finish_time.value), 'durJam', 'durMenit');
            tampil(selisihMenit(f.backup_start.value, f.backup_finish.value), 'bDurJam', 'bDurMenit');
        }
        ['start_time', 'finish_time', 'backup_start', 'backup_finish'].forEach(k => {
            const el = document.getElementById('formReport').elements[k];
            el.addEventListener('input', hitungDurasi);
            el.addEventListener('change', hitungDurasi);
        });

        // ====== POP UP FORM TAMBAH / EDIT ======
        function openReport(data) {
            data = data || {};
            const form = document.getElementById('formReport');
            const edit = !!data.id;
            form.reset();
            form.elements.id.value = edit ? data.id : '';
            form.setAttribute('action', edit ? form.dataset.update : form.dataset.save);
            document.getElementById('mrJudulForm').textContent = edit ? 'Edit Monthly Report' : 'Tambah Monthly Report';
            KOLOM_FORM.forEach(k => {
                const el = form.elements[k];
                const v = data[k] == null ? '' : data[k];
                el.value = el.type === 'datetime-local' ? keInput(v) : v;
            });
            hitungDurasi();
            document.getElementById('modal_report').classList.remove('hidden');
            setTimeout(() => form.elements.client.focus(), 50);
        }

        function closeReport() {
            document.getElementById('modal_report').classList.add('hidden');
        }

        document.addEventListener('keydown', e => {
            if (e.key !== 'Escape') return;
            if (!document.getElementById('modal_report').classList.contains('hidden')) closeReport();
            if (!document.getElementById('viewModal').classList.contains('hidden')) closeViewModal();
        });

        // ====== VALIDASI SEBELUM SUBMIT (server tetap memeriksa ulang) ======
        document.getElementById('formReport').addEventListener('submit', function(e) {
            const f = this.elements;
            const isi = k => f[k].value.trim();
            const pesan = [];

            if (!isi('client') || !isi('jenis_layanan') || !isi('solver') || !isi('problem') || !isi('action')) {
                pesan.push('Semua field bertanda * wajib diisi.');
            }
            const durasi = selisihMenit(isi('start_time'), isi('finish_time'));
            if (!isi('start_time') || !isi('finish_time')) pesan.push('Start Time dan Finish Time (tanggal & jam) wajib diisi.');
            else if (durasi !== null && durasi < 0) pesan.push('Finish Time tidak boleh sebelum Start Time.');

            if (isi('backup_start') || isi('backup_finish')) {
                const b = selisihMenit(isi('backup_start'), isi('backup_finish'));
                if (!isi('backup_start') || !isi('backup_finish')) pesan.push('Using Backup: Start Time dan Finish Time harus diisi keduanya (atau kosongkan keduanya).');
                else if (b !== null && b < 0) pesan.push('Using Backup: Finish Time tidak boleh sebelum Start Time.');
            }

            if (pesan.length) {
                e.preventDefault();
                Swal.fire({ icon: 'warning', title: 'Data Belum Bisa Disimpan', html: pesan.map(p => '<div class="text-left">• ' + escapeHtml(p) + '</div>').join(''), confirmButtonColor: '#185a82' });
            }
        });

        // ====== VIEW ======
        function escapeHtml(s) {
            return (s == null ? '' : String(s)).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        // "2026-11-10 08:30:00" -> "10 November 2026 08:30"
        function formatWaktu(v) {
            if (!v) return '';
            const [y, m, t] = v.substring(0, 10).split('-');
            return Number(t) + ' ' + BULAN[Number(m) - 1] + ' ' + y + ' ' + v.substring(11, 16);
        }

        const formatDurasi = m => (m === null || m === undefined || m === '') ? '' : Math.floor(m / 60) + ' jam ' + (m % 60) + ' menit';

        function openViewModal(d) {
            const set = (id, v) => document.getElementById(id).textContent = (v === null || v === undefined || String(v).trim() === '') ? '-' : v;
            ['client', 'jenis_layanan', 'solver', 'problem', 'action', 'note'].forEach(k => set('v_' + k, d[k]));
            ['start_time', 'finish_time', 'backup_start', 'backup_finish'].forEach(k => set('v_' + k, formatWaktu(d[k])));
            set('v_durasi', formatDurasi(d.durasi_menit === null ? null : Number(d.durasi_menit)));
            set('v_backup_durasi', formatDurasi(d.backup_durasi_menit === null ? null : Number(d.backup_durasi_menit)));
            document.getElementById('viewModal').classList.remove('hidden');
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }

        // ====== DELETE ======
        function confirmDelete(id, nama) {
            Swal.fire({
                title: 'Hapus Monthly Report?',
                text: '"' + nama + '" akan dihapus dan tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) window.location.href = "<?= site_url('MonthlyReport/delete/') ?>" + id;
            });
        }

        function isTableEmpty(table) {
            return table.rows({ search: 'applied' }).data().length === 0;
        }

        function showEmptyExportAlert() {
            Swal.fire({ icon: 'warning', title: 'Data Kosong', text: 'Tidak ada data yang bisa diexport.', confirmButtonColor: '#04a9f5' });
        }

        <?php if ($popupGagal) : ?>
            document.addEventListener('DOMContentLoaded', () => openReport(<?= json_encode($isianLama, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>));
        <?php endif; ?>
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


    <script>
        $(document).ready(function() {
            // judul kolom saat export (header 2 baris digabung jadi 1 baris)
            const JUDUL_EXPORT = {
                5: 'Duration Hour',
                6: 'Duration Minute',
                12: 'Using Backup Start Time',
                13: 'Using Backup Finish Time',
                14: 'Using Backup Duration Hour',
                15: 'Using Backup Duration Minute'
            };
            const judulFile = () => {
                const bulan = $('#filterBulan option:selected');
                return 'Monthly Report' + (bulan.val() ? ' ' + bulan.text() : '');
            };
            const exportConfig = {
                exportOptions: {
                    columns: ':visible:not(.no-export)',
                    format: {
                        header: (data, col) => JUDUL_EXPORT[col] || $('<div>').html(data).text().trim(),
                        body: function(data) {
                            const tmp = document.createElement('div');
                            tmp.innerHTML = String(data).replace(/<br\s*\/?>/gi, '\n').replace(/<\/div>/g, '</div>\n');
                            return tmp.textContent.replace(/[ \t]+/g, ' ').replace(/\n\s*\n/g, '\n').trim();
                        }
                    }
                }
            };
            const tombol = (jenis, teks, ikon, extra) => Object.assign({
                extend: jenis,
                text: '<i class="ti ' + ikon + '"></i> ' + teks,
                title: judulFile,
                filename: judulFile,
                ...exportConfig,
                action: function(e, dt, button, config) {
                    if (isTableEmpty(dt)) return showEmptyExportAlert();
                    $.fn.dataTable.ext.buttons[jenis].action.call(this, e, dt, button, config);
                }
            }, extra || {});

            // filter bulan (berdasarkan bulan Start Time)
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                if (settings.nTable.id !== 'reportTable') return true;
                const bulan = $('#filterBulan').val();
                return !bulan || settings.aoData[dataIndex].nTr.dataset.bulan === bulan;
            });

            const table = $('#reportTable').DataTable({
                pageLength: 10,
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
                            pageSize: 'A3',
                            customize: function(doc) {
                                doc.styles.tableHeader = { fillColor: '#04a9f5', color: '#fff', bold: true, alignment: 'left' };
                                doc.defaultStyle.fontSize = 8;
                            }
                        })
                    ]
                }],
                language: {
                    lengthMenu: "_MENU_",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 - 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ data)",
                    emptyTable: "Monthly Report belum tersedia",
                    zeroRecords: "Tidak ada data yang cocok dengan filter / pencarian",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Selanjutnya"
                    }
                }
            });

            // nomor urut mengikuti urutan & filter yang tampil
            table.on('draw.dt order.dt search.dt', function() {
                let i = table.page.info().start;
                table.column(0, { page: 'current', search: 'applied', order: 'applied' }).nodes().each(function(cell) {
                    cell.innerHTML = ++i;
                });
            });
            // ====== SCROLL TABEL: lebih dari 10 baris tampil = scroll di dalam card ======
            const MAKS_BARIS = 10;

            function batasiTinggiTabel() {
                const area = document.querySelector('.rfo-page .table-scroll');
                const baris = area.querySelectorAll('#reportTable tbody tr');
                const head1 = area.querySelector('#reportTable thead tr');
                // header baris kedua menempel tepat di bawah baris pertama
                area.style.setProperty('--mr-h1', head1.getBoundingClientRect().height + 'px');
                area.style.maxHeight = '';
                if (baris.length > MAKS_BARIS) {
                    const atas = area.querySelector('#reportTable thead').getBoundingClientRect().top;
                    const bawah = baris[MAKS_BARIS - 1].getBoundingClientRect().bottom;
                    area.style.maxHeight = Math.ceil(bawah - atas + area.offsetHeight - area.clientHeight) + 'px';
                }
                area.scrollTop = 0;
            }
            table.on('draw.dt', batasiTinggiTabel);
            $(window).on('resize', batasiTinggiTabel);
            if (document.fonts && document.fonts.ready) document.fonts.ready.then(batasiTinggiTabel);

            table.draw();

            $('#lengthArea').append($('.dataTables_length'));
            $('#exportArea').append($('.dt-buttons'));

            // ====== FILTER DROPDOWN (cocok persis): Client kolom 2, Jenis Layanan kolom 7 ======
            const filterKolom = (sel, col) => $(sel).on('change', function() {
                const re = this.value ? '^' + $.fn.dataTable.util.escapeRegex(this.value) + '$' : '';
                table.column(col).search(re, true, false).draw();
            });
            filterKolom('#filterClient', 2);
            filterKolom('#filterLayanan', 7);
            $('#filterBulan').on('change', () => table.draw());

            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });
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