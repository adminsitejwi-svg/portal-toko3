<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title>Data CCTV & NVR</title>
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

        /* ===== JUMLAH PER GROUP ===== */
        .grup-count {
            display: inline-block;
            min-width: 28px;
            padding: 2px 8px;
            border-radius: 6px;
            background: #f1f5f9;
            color: #94a3b8;
            font-weight: 600;
            text-align: center;
        }

        .grup-count.has {
            background: #e3edfb;
            color: #1e4fa3;
        }

        .grup-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background: #e3edfb;
            color: #1e4fa3;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .rekap-red {
            background: #fde8ec;
        }

        .rekap-amber {
            background: #fdf3e3;
        }

        .rekap-blue {
            background: #e3edfb;
        }

        .rekap-green {
            background: #e7f8f1;
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

        /* ===== TOMBOL AKSI IKON ===== */
        .aksi-group {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .aksi-icon {
            width: 26px;
            height: 26px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: background .15s, color .15s;
        }

        .aksi-view {
            background: #e8f4fd;
            color: #0ea5e9;
        }

        .aksi-view:hover {
            background: #0ea5e9;
            color: #fff;
        }

        .aksi-edit {
            background: #eaf0fd;
            color: #2563eb;
        }

        .aksi-edit:hover {
            background: #2563eb;
            color: #fff;
        }

        .aksi-hapus {
            background: #fdecec;
            color: #dc2626;
        }

        .aksi-hapus:hover {
            background: #dc2626;
            color: #fff;
        }

        .dark .aksi-view {
            background: rgba(14, 165, 233, .15);
        }

        .dark .aksi-edit {
            background: rgba(37, 99, 235, .18);
        }

        .dark .aksi-hapus {
            background: rgba(220, 38, 38, .18);
        }

        /* ===== NAVBAR DAILY REPORT SAT GROUP ===== */
        .rfo-page .sat-head {
            padding-bottom: 0;
        }

        .rfo-page .sat-head .sat-tabs {
            margin: 18px -22px 0;
            padding: 0 22px !important;
            gap: 6px;
            background: transparent !important;
            border: 0 !important;
            border-top: 1px solid var(--rf-border) !important;
            border-radius: 0 !important;
            box-shadow: none !important;
        }

        .rfo-page .sat-head .sat-tab {
            padding: 12px 6px;
            margin-right: 14px;
            border: 0;
            border-radius: 0;
            font-size: 13px;
            font-weight: 600;
            color: var(--rf-muted);
            background: transparent !important;
            box-shadow: none;
        }

        .rfo-page .sat-head .sat-tab:hover {
            color: #2f6bff;
        }

        .rfo-page .sat-head .sat-tab.active {
            color: #2f6bff;
            box-shadow: inset 0 -2px 0 #2f6bff;
        }

        .sat-tabs {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 4px;
            padding: 6px 10px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
        }

        .sat-tab {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 9px;
            border-radius: 5px;
            font-size: 12.5px;
            color: #475569;
            white-space: nowrap;
            transition: background .15s, color .15s;
        }

        .sat-tab i {
            font-size: 14px;
        }

        .sat-tab:hover {
            background: #f3f4f6;
            color: #1e293b;
        }

        .sat-tab.active {
            background: var(--ra-primary-soft, #eef0fd);
            color: var(--ra-primary, #6777ef);
            font-weight: 600;
            box-shadow: inset 0 -2px 0 var(--ra-primary, #6777ef);
            cursor: default;
        }

        .dark .sat-tabs {
            background: #263240;
            border-color: rgba(255, 255, 255, .1);
        }

        .dark .sat-tab {
            color: #bfc8d6;
        }

        .dark .sat-tab:hover {
            background: rgba(255, 255, 255, .06);
        }

        .dark .sat-tab.active {
            background: rgba(103, 119, 239, .22);
            color: #c7cdfb;
            box-shadow: inset 0 -2px 0 #8f9cf5;
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
                            <p class="text-sm"><?= esc(session()->getFlashdata('success')) ?></p>
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
                            <p class="text-sm"><?= esc(session()->getFlashdata('error')) ?></p>
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
                    <a href="#" onclick="toggleSub(this);return false;" class="pc-link active flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-category"></i></span>
                        <span class="flex-1">Data Teknis NOC</span>
                        <i data-feather="chevron-right" class="arrow w-4 h-4 transition-transform"></i>
                    </a>
                    <ul class="submenu bg-black/20 open">
                        <li><a href="<?= site_url('DataRakDC') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Rak DC</a></li>
                        <li><a href="<?= site_url('Interkoneksi') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Center &amp; Interkoneksi</a></li>
                        <li><a href="<?= site_url('Vlan') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data VLAN</a></li>
                        <li><a href="<?= site_url('NetMon') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">NetMon</a></li>
                        <li><a href="<?= site_url('DataRouter') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Router</a></li>
                        <li><a href="<?= site_url('DataSwitch') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Switch</a></li>
                        <li><a href="<?= site_url('DataOlt') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data OLT</a></li>
                        <li><a href="<?= site_url('DataCctv') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] active font-bold text-white">Data CCTV</a></li>
                        <li><a href="<?= site_url('IpPublic') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data IP Address</a></li>
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
                                <h6 class="font-medium leading-tight"><?= esc(session('username') ?? 'User') ?></h6>
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
            <!-- KARTU HEADER -->
            <div class="rfo-panel sat-head mb-5">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <span class="rfo-title-ic is-sky"><i class="ti ti-device-cctv"></i></span>
                        <div>
                            <h1 class="rfo-title">Data Monitoring CCTV & NVR</h1>
                            <p class="rfo-subtitle">Rekapitulasi unit Perangkat NVR, Kamera CCTV, IP &amp; Status Jaringan NOC</p>
                        </div>
                    </div>
                    <button type="button" id="btnTambahData" onclick="tambahDataAktif()" class="rfo-btn rfo-btn-blue">
                        <i class="ti ti-plus"></i> Tambah NVR
                    </button>
                </div>

                <!-- NAVBAR SUB-KATEGORI DATA TEKNIS CCTV -->
                <nav class="sat-tabs" aria-label="Data CCTV Tabs">
                    <button type="button" onclick="switchTab('tab-nvr')" id="btn-tab-nvr" class="sat-tab active"><i class="ti ti-server"></i> Data NVR</button>
                    <button type="button" onclick="switchTab('tab-cctv')" id="btn-tab-cctv" class="sat-tab"><i class="ti ti-device-cctv"></i> Data CCTV</button>
                </nav>
            </div>

            <div class="card">
                <div class="card-body">

                    <!-- TAB NVR CONTENT -->
                    <div id="tab-nvr" class="tab-content">
                        <!-- TOOLBAR NVR -->
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                            <div class="flex items-center gap-3 flex-wrap">
                                <div id="lengthAreaNvr"></div>
                                <select id="filterNamaNvr" class="px-3 py-2 border rounded-lg dark:bg-[#1d2630] dark:border-gray-600">
                                    <option value="">Semua Nama NVR</option>
                                </select>
                                <select id="filterIpNvr" class="px-3 py-2 border rounded-lg dark:bg-[#1d2630] dark:border-gray-600">
                                    <option value="">Semua IP Address NVR</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <div class="rfo-search">
                                    <i class="ti ti-search"></i>
                                    <input type="text" id="customSearchNvr" placeholder="Cari data NVR..." />
                                </div>
                                <button type="button" onclick="resetFilter('nvr')" class="rfo-ghost-btn rfo-reset"><i class="ti ti-refresh"></i> Reset</button>
                                <div id="exportAreaNvr"></div>
                            </div>
                        </div>

                        <!-- TABLE NVR -->
                        <div class="table-scroll">
                            <table id="tabelNvr" class="rfo-table display nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Aksi</th>
                                        <th>Nama NVR</th>
                                        <th>IP Address NVR</th>
                                        <th>Lokasi / BTS</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($nvrList)) : ?>
                                        <?php $no = 1; ?>
                                        <?php foreach ($nvrList as $row) : ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td>
                                                    <button type="button" data-row="<?= esc(json_encode($row), 'attr') ?>"
                                                        onclick="editNvr(JSON.parse(this.dataset.row))" class="btn btn-sm btn-primary">
                                                        <i class="ti ti-edit"></i> <span>Edit</span>
                                                    </button>
                                                    <button type="button" onclick="hapusNvr(<?= (int) $row['id'] ?>, this.dataset.nama)" data-nama="<?= esc($row['nama_nvr'], 'attr') ?>" class="btn btn-sm btn-danger">
                                                        <i class="ti ti-trash"></i> <span>Hapus</span>
                                                    </button>
                                                    <button type="button" data-row="<?= esc(json_encode($row), 'attr') ?>"
                                                        onclick="lihatNvr(JSON.parse(this.dataset.row))" class="btn btn-sm btn-info" title="Lihat">
                                                        <i class="ti ti-eye"></i> <span>View</span>
                                                    </button>
                                                </td>
                                                <td class="col-bold"><?= esc($row['nama_nvr']); ?></td>
                                                <td class="font-mono text-blue-600 dark:text-blue-400"><?= esc($row['ip_nvr']); ?></td>
                                                <td class="whitespace-normal min-w-[180px]"><?= esc($row['lokasi_nvr']); ?> <?= !empty($row['nama_bts']) ? '<span class="text-xs bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 px-2 py-0.5 rounded ml-1">' . esc($row['nama_bts']) . '</span>' : '' ?></td>
                                                <td class="whitespace-pre-line min-w-[200px]"><?= esc($row['keterangan'] ?: '-'); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TAB CCTV CONTENT -->
                    <div id="tab-cctv" class="tab-content hidden">
                        <!-- TOOLBAR CCTV -->
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                            <div class="flex items-center gap-3 flex-wrap">
                                <div id="lengthAreaCctv"></div>
                                <select id="filterNamaCctv" class="px-3 py-2 border rounded-lg dark:bg-[#1d2630] dark:border-gray-600">
                                    <option value="">Semua Nama Kamera</option>
                                </select>
                                <select id="filterIpCctv" class="px-3 py-2 border rounded-lg dark:bg-[#1d2630] dark:border-gray-600">
                                    <option value="">Semua IP Address Kamera</option>
                                </select>
                                <select id="filterIndukNvr" class="px-3 py-2 border rounded-lg dark:bg-[#1d2630] dark:border-gray-600">
                                    <option value="">Semua Induk NVR</option>
                                </select>
                            </div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <div class="rfo-search">
                                    <i class="ti ti-search"></i>
                                    <input type="text" id="customSearchCctv" placeholder="Cari data CCTV..." />
                                </div>
                                <button type="button" onclick="resetFilter('cctv')" class="rfo-ghost-btn rfo-reset"><i class="ti ti-refresh"></i> Reset</button>
                                <div id="exportAreaCctv"></div>
                            </div>
                        </div>

                        <!-- TABLE CCTV -->
                        <div class="table-scroll">
                            <table id="tabelCctv" class="rfo-table display nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Aksi</th>
                                        <th>Nama Kamera</th>
                                        <th>IP Address CCTV</th>
                                        <th>Induk NVR</th>
                                        <th>Lokasi Kamera</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($cctvList)) : ?>
                                        <?php $no = 1; ?>
                                        <?php foreach ($cctvList as $row) : ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td>
                                                    <button type="button" data-row="<?= esc(json_encode($row), 'attr') ?>"
                                                        onclick="editCctv(JSON.parse(this.dataset.row))" class="btn btn-sm btn-primary">
                                                        <i class="ti ti-edit"></i> <span>Edit</span>
                                                    </button>
                                                    <button type="button" onclick="hapusCctv(<?= (int) $row['id'] ?>, this.dataset.nama)" data-nama="<?= esc($row['nama_cctv'], 'attr') ?>" class="btn btn-sm btn-danger">
                                                        <i class="ti ti-trash"></i> <span>Hapus</span>
                                                    </button>
                                                    <button type="button" data-row="<?= esc(json_encode($row), 'attr') ?>"
                                                        onclick="lihatCctv(JSON.parse(this.dataset.row))" class="btn btn-sm btn-info" title="Lihat">
                                                        <i class="ti ti-eye"></i> <span>View</span>
                                                    </button>
                                                </td>
                                                <td class="col-bold"><?= esc($row['nama_cctv']); ?></td>
                                                <td class="font-mono text-green-600 dark:text-green-400"><?= esc($row['ip_cctv']); ?></td>
                                                <td><?= esc($row['nama_nvr'] ?: '-'); ?></td>
                                                <td class="whitespace-normal min-w-[180px]"><?= esc($row['lokasi_cctv']); ?></td>
                                                <td class="whitespace-pre-line min-w-[200px]"><?= esc($row['keterangan'] ?: '-'); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- MODAL FORM NVR -->
    <div id="modal_nvr" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-xl p-6 rfo-form">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold" id="judulModalNvr">Tambah NVR</h3>
                <button type="button" onclick="tutupModal('modal_nvr')"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <form method="POST" id="formNvr" action="<?= site_url('DataCctv/saveNvr') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="nvr_id">
                <div class="rf-group mb-4">
                    <label class="rf-label">Nama NVR <span class="text-red-500">*</span></label>
                    <input class="w-full px-3 py-2 border rounded-lg dark:bg-[#1d2630] dark:border-gray-600" name="nama_nvr" id="nvr_nama" required placeholder="Contoh: NVR DC Duren Tiga">
                </div>
                <div class="rf-group mb-4">
                    <label class="rf-label">IP NVR <span class="text-red-500">*</span></label>
                    <input class="w-full px-3 py-2 border rounded-lg font-mono dark:bg-[#1d2630] dark:border-gray-600" name="ip_nvr" id="nvr_ip" required placeholder="Contoh: 192.168.1.100">
                </div>
                <div class="rf-group mb-4">
                    <label class="rf-label">Lokasi / BTS</label>
                    <input class="w-full px-3 py-2 border rounded-lg dark:bg-[#1d2630] dark:border-gray-600" name="lokasi_nvr" id="nvr_lokasi" placeholder="Contoh: Lantai 2 Server Room">
                </div>
                <div class="rf-group mb-4">
                    <label class="rf-label">Keterangan</label>
                    <textarea class="w-full px-3 py-2 border rounded-lg dark:bg-[#1d2630] dark:border-gray-600" name="keterangan" id="nvr_keterangan" rows="3" placeholder="Catatan opsional"></textarea>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-lg" onclick="tutupModal('modal_nvr')">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg flex items-center gap-2"><i class="ti ti-device-floppy"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL FORM CCTV -->
    <div id="modal_cctv" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-xl p-6 rfo-form">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold" id="judulModalCctv">Tambah CCTV</h3>
                <button type="button" onclick="tutupModal('modal_cctv')"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <form method="POST" id="formCctv" action="<?= site_url('DataCctv/saveCctv') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="cctv_id">
                <div class="rf-group mb-4">
                    <label class="rf-label">Induk NVR <span class="text-red-500">*</span></label>
                    <select class="w-full px-3 py-2 border rounded-lg dark:bg-[#1d2630] dark:border-gray-600" name="nvr_id" id="cctv_nvr_id" required>
                        <option value="">-- Pilih Induk NVR --</option>
                        <?php foreach ($nvrList ?? [] as $nvr) : ?>
                            <option value="<?= $nvr['id'] ?>"><?= esc($nvr['nama_nvr']) ?> (<?= esc($nvr['ip_nvr']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="rf-group mb-4">
                    <label class="rf-label">Nama Kamera CCTV <span class="text-red-500">*</span></label>
                    <input class="w-full px-3 py-2 border rounded-lg dark:bg-[#1d2630] dark:border-gray-600" name="nama_cctv" id="cctv_nama" required placeholder="Contoh: CAM-01 Lobby Depan">
                </div>
                <div class="rf-group mb-4">
                    <label class="rf-label">IP CCTV <span class="text-red-500">*</span></label>
                    <input class="w-full px-3 py-2 border rounded-lg font-mono dark:bg-[#1d2630] dark:border-gray-600" name="ip_cctv" id="cctv_ip" required placeholder="Contoh: 192.168.1.101">
                </div>
                <div class="rf-group mb-4">
                    <label class="rf-label">Lokasi CCTV</label>
                    <input class="w-full px-3 py-2 border rounded-lg dark:bg-[#1d2630] dark:border-gray-600" name="lokasi_cctv" id="cctv_lokasi" placeholder="Contoh: Area Parkir Depan">
                </div>
                <div class="rf-group mb-4">
                    <label class="rf-label">Keterangan</label>
                    <textarea class="w-full px-3 py-2 border rounded-lg dark:bg-[#1d2630] dark:border-gray-600" name="keterangan" id="cctv_keterangan" rows="3" placeholder="Catatan opsional"></textarea>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-lg" onclick="tutupModal('modal_cctv')">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg flex items-center gap-2"><i class="ti ti-device-floppy"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ====== POP UP VIEW ====== -->
    <div id="viewModal" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4" onclick="if (event.target === this) closeViewModal()">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-xl p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold" id="v_judul">View Detail</h3>
                <button type="button" onclick="closeViewModal()"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm" id="v_content"></div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeViewModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Tutup</button>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC PERSIS SAMPLING FILE RESIPENGIRIMAN -->
    <script>
        function openModalNvr() {
            $('#formNvr')[0].reset();
            $('#nvr_id').val('');
            $('#formNvr').attr('action', '<?= site_url('DataCctv/saveNvr') ?>');
            $('#judulModalNvr').text('Tambah NVR');
            $('#modal_nvr').removeClass('hidden');
        }

        function editNvr(data) {
            $('#nvr_id').val(data.id);
            $('#nvr_nama').val(data.nama_nvr);
            $('#nvr_ip').val(data.ip_nvr);
            $('#nvr_lokasi').val(data.lokasi_nvr);
            $('#nvr_keterangan').val(data.keterangan);
            $('#formNvr').attr('action', '<?= site_url('DataCctv/updateNvr') ?>');
            $('#judulModalNvr').text('Edit NVR');
            $('#modal_nvr').removeClass('hidden');
        }

        function lihatNvr(d) {
            $('#v_judul').html('<i class="ti ti-server text-blue-500 mr-2"></i> View Data NVR');
            $('#v_content').html(`
                <div><div class="text-gray-500">Nama NVR</div><div class="font-medium">${d.nama_nvr || '-'}</div></div>
                <div><div class="text-gray-500">IP Address</div><div class="font-medium font-mono text-blue-500">${d.ip_nvr || '-'}</div></div>
                <div class="md:col-span-2"><div class="text-gray-500">Lokasi / BTS</div><div class="font-medium">${d.lokasi_nvr || '-'}</div></div>
                <div class="md:col-span-2"><div class="text-gray-500">Keterangan</div><div class="font-medium whitespace-pre-line">${d.keterangan || '-'}</div></div>
            `);
            $('#viewModal').removeClass('hidden');
        }

        function openModalCctv() {
            $('#formCctv')[0].reset();
            $('#cctv_id').val('');
            $('#formCctv').attr('action', '<?= site_url('DataCctv/saveCctv') ?>');
            $('#judulModalCctv').text('Tambah CCTV');
            $('#modal_cctv').removeClass('hidden');
        }

        function editCctv(data) {
            $('#cctv_id').val(data.id);
            $('#cctv_nvr_id').val(data.nvr_id);
            $('#cctv_nama').val(data.nama_cctv);
            $('#cctv_ip').val(data.ip_cctv);
            $('#cctv_lokasi').val(data.lokasi_cctv);
            $('#cctv_keterangan').val(data.keterangan);
            $('#formCctv').attr('action', '<?= site_url('DataCctv/updateCctv') ?>');
            $('#judulModalCctv').text('Edit CCTV');
            $('#modal_cctv').removeClass('hidden');
        }

        function lihatCctv(d) {
            $('#v_judul').html('<i class="ti ti-device-cctv text-green-500 mr-2"></i> View Data CCTV');
            $('#v_content').html(`
                <div><div class="text-gray-500">Nama Kamera</div><div class="font-medium">${d.nama_cctv || '-'}</div></div>
                <div><div class="text-gray-500">IP Address</div><div class="font-medium font-mono text-green-500">${d.ip_cctv || '-'}</div></div>
                <div><div class="text-gray-500">Induk NVR</div><div class="font-medium">${d.nama_nvr || '-'}</div></div>
                <div><div class="text-gray-500">Lokasi Kamera</div><div class="font-medium">${d.lokasi_cctv || '-'}</div></div>
                <div class="md:col-span-2"><div class="text-gray-500">Keterangan</div><div class="font-medium whitespace-pre-line">${d.keterangan || '-'}</div></div>
            `);
            $('#viewModal').removeClass('hidden');
        }

        function tutupModal(id) {
            $('#' + id).addClass('hidden');
        }

        function closeViewModal() {
            $('#viewModal').addClass('hidden');
        }

        function confirmDelete(url) {
            Swal.fire({
                title: 'Hapus Data?',
                text: 'Data yang dihapus tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        }

        function hapusNvr(id, nama) {
            confirmDelete("<?= site_url('DataCctv/deleteNvr/') ?>" + id);
        }

        function hapusCctv(id, nama) {
            confirmDelete("<?= site_url('DataCctv/deleteCctv/') ?>" + id);
        }

        function isTableEmpty(table) {
            return table.rows({
                search: 'applied'
            }).data().length === 0;
        }

        function showEmptyExportAlert() {
            Swal.fire({
                icon: 'warning',
                title: 'Data Kosong',
                text: 'Tidak ada data yang bisa diexport.',
                confirmButtonColor: '#04a9f5'
            });
        }

        function switchTab(tabId) {
            $('.tab-content').addClass('hidden');
            $('#' + tabId).removeClass('hidden');
            $('.sat-tab').removeClass('active');
            $('#btn-' + tabId).addClass('active');

            // Satu tombol Tambah mengikuti tab yang sedang dibuka.
            const tombolTambah = document.getElementById('btnTambahData');
            if (tombolTambah) {
                tombolTambah.innerHTML = tabId === 'tab-cctv' ?
                    '<i class="ti ti-plus"></i> Tambah CCTV' :
                    '<i class="ti ti-plus"></i> Tambah NVR';
            }
        }

        function tambahDataAktif() {
            if ($('#tab-cctv').is(':visible')) {
                openModalCctv();
            } else {
                openModalNvr();
            }
        }

        function resetFilter(type) {
            if (type === 'nvr') {
                $('#customSearchNvr').val('');
                $('#filterNamaNvr, #filterIpNvr').val('');
                tableNvr.search('').columns().search('').draw();
            } else {
                $('#customSearchCctv').val('');
                $('#filterNamaCctv, #filterIpCctv, #filterIndukNvr').val('');
                tableCctv.search('').columns().search('').draw();
            }
        }
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
                if (sb && sb.classList.contains('mobile-open') && !sb.contains(e.target) && !menuBtn) {
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
        let tableNvr, tableCctv;

        function isiFilterDropdown(table, columnIndex, selector) {
            const dropdown = $(selector);
            const placeholder = dropdown.find('option:first').text();
            dropdown.empty().append($('<option>', {
                value: '',
                text: placeholder
            }));

            table.column(columnIndex).data().unique().sort().each(function(value) {
                const textValue = $('<div>').html(value).text().trim();
                if (textValue !== '') {
                    dropdown.append($('<option>', {
                        value: textValue,
                        text: textValue
                    }));
                }
            });
        }

        function terapkanFilterDropdown(table, columnIndex, selector) {
            $(selector).on('change', function() {
                const value = $.fn.dataTable.util.escapeRegex($(this).val());
                table.column(columnIndex).search(value ? '^' + value + '$' : '', true, false).draw();
            });
        }

        $(document).ready(function() {
            const exportConfig = {
                exportOptions: {
                    columns: ':visible',
                    format: {
                        body: function(data) {
                            const tmp = document.createElement('div');
                            tmp.innerHTML = data;
                            return tmp.textContent.trim();
                        }
                    }
                }
            };

            // DATATABLES NVR
            tableNvr = $('#tabelNvr').DataTable({
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
                    buttons: [{
                            extend: 'copyHtml5',
                            text: '<i class="ti ti-copy"></i> Copy',
                            title: 'Data NVR',
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, button, config);
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            text: '<i class="ti ti-file-text"></i> Export CSV',
                            title: 'Data NVR',
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            text: '<i class="ti ti-file-spreadsheet"></i> Export Excel',
                            title: 'Data NVR',
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            text: '<i class="ti ti-file-type-pdf"></i> Export PDF',
                            title: 'Data NVR',
                            orientation: 'landscape',
                            pageSize: 'A4',
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
                            },
                            customize: function(doc) {
                                doc.styles.tableHeader = {
                                    fillColor: '#04a9f5',
                                    color: '#fff',
                                    bold: true,
                                    alignment: 'left'
                                };
                                doc.defaultStyle.fontSize = 10;
                                doc.content[1].layout = {
                                    hLineWidth: () => 0.5,
                                    vLineWidth: () => 0.5,
                                    hLineColor: () => '#e0e0e0',
                                    vLineColor: () => '#e0e0e0'
                                };
                            }
                        }
                    ]
                }],
                language: {
                    lengthMenu: "_MENU_",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 - 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ data)",
                    emptyTable: "Data NVR belum tersedia",
                    zeroRecords: "Tidak ada data yang cocok dengan pencarian",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Selanjutnya"
                    }
                }
            });

            tableNvr.on('draw.dt order.dt search.dt', function() {
                let i = tableNvr.page.info().start;
                tableNvr.column(0, {
                    page: 'current',
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell) {
                    cell.innerHTML = ++i;
                });
            });
            tableNvr.draw();

            $('#lengthAreaNvr').append($('#tabelNvr_wrapper .dataTables_length'));
            $('#exportAreaNvr').append($('#tabelNvr_wrapper .dt-buttons'));

            $('#customSearchNvr').on('keyup', function() {
                tableNvr.search(this.value).draw();
            });

            // Filter dropdown NVR: Nama NVR dan IP Address NVR.
            isiFilterDropdown(tableNvr, 2, '#filterNamaNvr');
            isiFilterDropdown(tableNvr, 3, '#filterIpNvr');
            terapkanFilterDropdown(tableNvr, 2, '#filterNamaNvr');
            terapkanFilterDropdown(tableNvr, 3, '#filterIpNvr');

            // DATATABLES CCTV
            tableCctv = $('#tabelCctv').DataTable({
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
                    buttons: [{
                            extend: 'copyHtml5',
                            text: '<i class="ti ti-copy"></i> Copy',
                            title: 'Data CCTV',
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, button, config);
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            text: '<i class="ti ti-file-text"></i> Export CSV',
                            title: 'Data CCTV',
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            text: '<i class="ti ti-file-spreadsheet"></i> Export Excel',
                            title: 'Data CCTV',
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            text: '<i class="ti ti-file-type-pdf"></i> Export PDF',
                            title: 'Data CCTV',
                            orientation: 'landscape',
                            pageSize: 'A4',
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.pdfHtml5.action.call(this, e, dt, button, config);
                            },
                            customize: function(doc) {
                                doc.styles.tableHeader = {
                                    fillColor: '#04a9f5',
                                    color: '#fff',
                                    bold: true,
                                    alignment: 'left'
                                };
                                doc.defaultStyle.fontSize = 10;
                                doc.content[1].layout = {
                                    hLineWidth: () => 0.5,
                                    vLineWidth: () => 0.5,
                                    hLineColor: () => '#e0e0e0',
                                    vLineColor: () => '#e0e0e0'
                                };
                            }
                        }
                    ]
                }],
                language: {
                    lengthMenu: "_MENU_",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 - 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ data)",
                    emptyTable: "Data CCTV belum tersedia",
                    zeroRecords: "Tidak ada data yang cocok dengan pencarian",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Selanjutnya"
                    }
                }
            });

            tableCctv.on('draw.dt order.dt search.dt', function() {
                let i = tableCctv.page.info().start;
                tableCctv.column(0, {
                    page: 'current',
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell) {
                    cell.innerHTML = ++i;
                });
            });
            tableCctv.draw();

            $('#lengthAreaCctv').append($('#tabelCctv_wrapper .dataTables_length'));
            $('#exportAreaCctv').append($('#tabelCctv_wrapper .dt-buttons'));

            $('#customSearchCctv').on('keyup', function() {
                tableCctv.search(this.value).draw();
            });

            // Filter dropdown CCTV: Nama Kamera, IP Address Kamera, dan Induk NVR.
            isiFilterDropdown(tableCctv, 2, '#filterNamaCctv');
            isiFilterDropdown(tableCctv, 3, '#filterIpCctv');
            isiFilterDropdown(tableCctv, 4, '#filterIndukNvr');
            terapkanFilterDropdown(tableCctv, 2, '#filterNamaCctv');
            terapkanFilterDropdown(tableCctv, 3, '#filterIpCctv');
            terapkanFilterDropdown(tableCctv, 4, '#filterIndukNvr');
        });
    </script>

    <?php if (!session()->get('logged_in')) : ?>
        <script>
            window.location.href = "<?= base_url('/login') ?>";
        </script>
    <?php endif; ?>
</body>
<script>
    document.addEventListener('contextmenu', e => e.preventDefault());
    document.addEventListener('keydown', e => {
        if (e.key === 'F12') e.preventDefault();
        if (e.ctrlKey && e.shiftKey && ['I', 'J', 'C'].includes(e.key.toUpperCase())) e.preventDefault();
        if (e.ctrlKey && e.key.toUpperCase() === 'U') e.preventDefault();
    });
</script>

</html>