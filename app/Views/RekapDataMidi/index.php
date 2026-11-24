<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title>Rekap Data Toko</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?= csrf_meta() ?>
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

        /* ===== NAVBAR REKAP DATA TOKO (gaya sama dengan Daily Report SAT Group) ===== */
        /* ===== KARTU HEADER: tab bergaris bawah di dalam kartu ===== */
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

        /* ===== TOMBOL AKSI TABEL: ikon saja (nama tombol tampil saat kursor diarahkan) ===== */
        #reportTable .btn {
            width: 30px;
            height: 30px;
            padding: 0;
            justify-content: center;
            margin: 2px 2px 2px 0;
        }

        #reportTable .btn span {
            display: none;
        }

        #reportTable .btn i {
            font-size: 16px;
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

        /* ===== form import (sama seperti halaman Jadwal Shift) ===== */
        .f-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #37474f;
            margin-bottom: 6px;
        }

        .f-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e3e8ee;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            background: #fff;
            color: #37474f;
        }

        .f-input:focus {
            border-color: #04a9f5;
            box-shadow: 0 0 0 3px rgba(4, 169, 245, .15);
        }

        .dark .f-label {
            color: #cbd5e1;
        }

        .dark .f-input {
            background: rgba(255, 255, 255, .06);
            border-color: rgba(255, 255, 255, .14);
            color: #e2e8f0;
        }

        .dark .f-input option {
            background: #1e293b;
            color: #e2e8f0;
        }

        .btn2 {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid transparent;
        }
        /* SweetAlert (konfirmasi Ganti, info import, hapus) selalu di atas modal & sidebar */
        .swal2-container {
            z-index: 100001 !important;
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/theme/rfo.css') ?>?v=<?= @filemtime(FCPATH . 'assets/theme/rfo.css') ?: '1' ?>">
    <script src="<?= base_url('assets/theme/rfo.js') ?>?v=<?= @filemtime(FCPATH . 'assets/theme/rfo.js') ?: '1' ?>" defer></script>
    <?= view('partials/theme') ?>
</head>

<body class="text-[#37474f] dark:text-[#bfc8d6]">
    <!-- Banner berhasil/gagal: di luar sidebar supaya selalu tampil paling depan -->
        <?php if (session()->getFlashdata('success')) : ?>
            <div id="successAlert" class="fixed top-5 left-1/2 -translate-x-1/2 z-[100000] w-full max-w-md px-4">
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
            <div id="errorAlert" class="fixed top-5 left-1/2 -translate-x-1/2 z-[100000] w-full max-w-md px-4">
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
    <!-- ============ SIDEBAR ============ -->
    <nav id="sidebar" class="pc-sidebar fixed top-0 left-0 h-screen w-sidebar bg-sidebar text-[#a9b7c6] z-[1030] flex flex-col">
        <!-- brand -->
        <div class="flex items-center h-header px-6 shrink-0">
            <a href="#" class="flex items-center gap-2 text-white text-2xl font-semibold">
                <span class="text-primary-500"></span>
                <span class="brand-text">Sistem Operasional <br> JWI Group</span>
            </a>
        </div>
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
                    <ul class="submenu open bg-black/20">
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

        <?php
        // $bagian: fo / modem / vsat (lihat RekapDataTokoModel::BAGIAN)
        $info      = \App\Models\RekapDataTokoModel::BAGIAN[$bagian];
        $isian     = \App\Models\RekapDataTokoModel::ISIAN;
        $kolom     = \App\Models\RekapDataTokoModel::kolomIsian($bagian);
        $namaBulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $opsiUnik  = static function (array $rows, string $k): array {
            $nilai = array_unique(array_filter(array_map('trim', array_column($rows, $k)), 'strlen'));
            natcasesort($nilai);
            return $nilai;
        };
        ?>
        <div class="rfo-page p-6">
            <!-- KARTU HEADER: judul + tab FO + Modem 2 / Modem 1 + Modem 2 / VSAT + Modem -->
            <div class="rfo-panel sat-head mb-5">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <span class="rfo-title-ic is-green"><i class="ti ti-report-analytics"></i></span>
                        <div>
                            <h1 class="rfo-title">Rekap Data Toko</h1>
                            <p class="rfo-subtitle">Rekap data toko FO, Modem &amp; VSAT SAT Group</p>
                        </div>
                    </div>
                    <?php if ($siap) : ?>
                        <div class="flex flex-wrap items-center gap-3">
                            <button type="button" class="rfo-btn rfo-btn-green" id="btnImport"><i class="ti ti-file-import"></i> Import</button>
                            <a href="<?= site_url('RekapDataMidi/FormTambahRKD/' . $bagian) ?>" class="rfo-btn rfo-btn-blue">
                                <i class="ti ti-plus"></i> Tambah
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <nav class="sat-tabs" aria-label="Rekap Data Toko">
                    <?php foreach (\App\Models\RekapDataTokoModel::BAGIAN as $kodeBagian => $b) : ?>
                        <a href="<?= site_url($b['url']) ?>" class="sat-tab <?= $kodeBagian === $bagian ? 'active' : '' ?>" <?= $kodeBagian === $bagian ? 'aria-current="page"' : '' ?>><i class="ti <?= $b['ikon'] ?>"></i> <?= esc($b['nama']) ?></a>
                    <?php endforeach; ?>
                </nav>
            </div>

            <?php if (! $siap) : ?>
                <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
                    <i class="ti ti-alert-circle"></i> Database belum diperbarui. Jalankan blok <b>UPDATE 2026-11-24</b> di <b>database/rekap_data_toko.sql</b>.
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">

                    <!-- TOOLBAR -->
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div class="flex items-center gap-3 flex-wrap">
                            <div id="lengthArea"></div>
                            <?php
                            // Filter Bulan: daftar bulan (YYYY-MM) dari tanggal, terbaru di atas
                            $opsiBulan = [];
                            foreach ($rekap_list as $row) {
                                $tgl = (string) ($row['tanggal'] ?? '');
                                if ((int) substr($tgl, 5, 2) > 0) {
                                    $opsiBulan[substr($tgl, 0, 7)] = $namaBulan[(int) substr($tgl, 5, 2)] . ' ' . substr($tgl, 0, 4);
                                }
                            }
                            krsort($opsiBulan);
                            ?>
                            <select id="filterBulan" class="filter-select" title="Filter Bulan">
                                <option value="">Semua Bulan</option>
                                <?php foreach ($opsiBulan as $ym => $label) : ?>
                                    <option value="<?= esc($ym, 'attr') ?>"><?= esc($label) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select id="filterToko" class="filter-select" title="Filter Toko">
                                <option value="">Semua Toko</option>
                                <?php foreach (\App\Models\RekapDataTokoModel::TOKO as $t) : ?>
                                    <option value="<?= esc($t, 'attr') ?>"><?= esc($t) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select id="filterBranch" class="filter-select" title="Filter Branch">
                                <option value="">Semua Branch</option>
                                <?php foreach ($opsiUnik($rekap_list, 'branch') as $b) : ?>
                                    <option value="<?= esc($b, 'attr') ?>"><?= esc($b) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select id="filterPerusahaan" class="filter-select" title="Filter Perusahaan">
                                <option value="">Semua Perusahaan</option>
                                <?php foreach (array_unique(array_merge(\App\Models\RekapDataTokoModel::PERUSAHAAN, $opsiUnik($rekap_list, 'perusahaan'))) as $p) : ?>
                                    <option value="<?= esc($p, 'attr') ?>"><?= esc($p) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <div class="rfo-search">
                                <i class="ti ti-search"></i>
                                <input type="text" id="customSearch" placeholder="Cari data..." />
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
                                    <th>No</th>
                                    <th>Aksi</th>
                                    <th>Tanggal</th>
                                    <th>Nama Pengisi</th>
                                    <th>Perusahaan</th>
                                    <th>Toko</th>
                                    <th>Branch</th>
                                    <th>Kode Toko</th>
                                    <th>Nama Toko</th>
                                    <?php foreach ($kolom as $k) : ?>
                                        <th><?= esc($isian[$k][3]) ?></th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($rekap_list as $row) : ?>
                                    <?php
                                    // Tanggal. Format: 1 Oktober 2026
                                    $ts  = ! empty($row['tanggal']) ? strtotime($row['tanggal']) : false;
                                    $tgl = $ts ? date('j', $ts) . ' ' . $namaBulan[(int) date('n', $ts)] . ' ' . date('Y', $ts) : '-';
                                    ?>
                                    <tr data-id="<?= (int) $row['id'] ?>" data-bulan="<?= esc(substr((string) ($row['tanggal'] ?? ''), 0, 7), 'attr') ?>">
                                        <td><?= $no++; ?></td>
                                        <td>
                                            <!-- tombol aksi seperti Daily Report SAT Group: lihat, edit, hapus -->
                                            <button type="button"
                                                data-row="<?= esc(json_encode($row), 'attr') ?>"
                                                onclick="openViewModal(JSON.parse(this.dataset.row))"
                                                class="btn btn-sm btn-info" title="View">
                                                <i class="ti ti-eye"></i> <span>View</span>
                                            </button>
                                            <a href="<?= site_url('RekapDataMidi/FormEditRKD/' . $row['id']) ?>"
                                                class="btn btn-sm btn-primary" title="Edit">
                                                <i class="ti ti-edit"></i> <span>Edit</span>
                                            </a>
                                            <button type="button"
                                                onclick="confirmDelete(<?= (int) $row['id'] ?>)"
                                                class="btn btn-sm btn-danger" title="Hapus">
                                                <i class="ti ti-trash"></i> <span>Hapus</span>
                                            </button>
                                        </td>
                                        <td data-order="<?= esc($row['tanggal'] ?? '', 'attr') ?>"><?= esc($tgl) ?></td>
                                        <td class="min-w-[150px]"><?= esc($row['nama_pengisi'] ?: '-') ?></td>
                                        <td class="min-w-[210px]"><?= esc($row['perusahaan'] ?: '-') ?></td>
                                        <td><?= esc($row['toko'] ?: '-') ?></td>
                                        <td data-search="<?= esc(trim((string) $row['branch']), 'attr') ?>"><?= esc($row['branch'] ?: '-') ?></td>
                                        <td class="col-bold"><?= esc($row['kode_toko'] ?: '-') ?></td>
                                        <td class="col-bold whitespace-normal min-w-[200px]"><?= esc($row['nama_toko'] ?: '-') ?></td>
                                        <?php foreach ($kolom as $k) : ?>
                                            <?php $v = trim((string) ($row[$k] ?? '')); ?>
                                            <?php if ($isian[$k][1] === 'dok') : ?>
                                                <?php
                                                $docUrl   = preg_match('~^https?://~i', $v) === 1;
                                                $docLocal = str_starts_with($v, 'uploads/');
                                                ?>
                                                <!-- data-raw: nilai asli dokumen untuk export -->
                                                <td data-raw="<?= esc($v, 'attr') ?>" data-search="<?= esc($docUrl || $docLocal ? '' : $v, 'attr') ?>">
                                                    <?php if ($v === '') : ?>
                                                        -
                                                    <?php elseif ($docUrl || $docLocal) : ?>
                                                        <a href="<?= esc($docUrl ? $v : base_url($v), 'attr') ?>" target="_blank" rel="noopener" class="text-primary-600 hover:underline inline-flex items-center gap-1">
                                                            <i class="ti <?= $docUrl ? 'ti-link' : 'ti-file-text' ?>"></i> <?= $docUrl ? 'Buka Link' : 'Lihat File' ?>
                                                        </a>
                                                    <?php else : ?>
                                                        <span title="<?= esc($v, 'attr') ?>"><?= esc(mb_strimwidth($v, 0, 40, '…')) ?></span>
                                                    <?php endif; ?>
                                                </td>
                                            <?php elseif ($isian[$k][1] === 'area') : ?>
                                                <td class="whitespace-pre-line min-w-[240px]"><?= esc($v === '' ? '-' : $v) ?></td>
                                            <?php else : ?>
                                                <td class="<?= $isian[$k][1] === 'modem' ? 'min-w-[160px]' : '' ?>"><?= esc($v === '' ? '-' : $v) ?></td>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
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
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold">View <?= esc($info['nama']) ?></h3>
                <button type="button" onclick="closeViewModal()"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <?php foreach (\App\Models\RekapDataTokoModel::UMUM as $k => $judul) : ?>
                    <div class="<?= $k === 'nama_toko' ? 'md:col-span-2' : '' ?>">
                        <div class="text-gray-500"><?= esc($judul) ?></div>
                        <div class="font-medium" id="v_<?= $k ?>"></div>
                    </div>
                <?php endforeach; ?>
                <?php foreach ($info['grup'] as $judulGrup => $kolomGrup) : ?>
                    <div class="md:col-span-2 border-t border-gray-200 dark:border-white/10 pt-3 font-semibold text-primary-600"><?= esc($judulGrup) ?></div>
                    <?php foreach ($kolomGrup as $k) : ?>
                        <div class="<?= $isian[$k][1] === 'area' ? 'md:col-span-2' : '' ?>">
                            <div class="text-gray-500"><?= esc($isian[$k][0]) ?></div>
                            <div class="font-medium break-words <?= $isian[$k][1] === 'area' ? 'whitespace-pre-line' : '' ?>" id="v_<?= $k ?>"></div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>

                <!-- isian di luar form bagian ini (data lama / import), hanya tampil bila ada isinya -->
                <div class="md:col-span-2 border-t border-gray-200 dark:border-white/10 pt-3 hidden" id="vLain">
                    <div class="font-semibold text-primary-600 mb-3">Data Lama</div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="vLainIsi"></div>
                </div>

                <div class="md:col-span-2 border-t border-gray-200 dark:border-white/10 pt-3">
                    <div class="text-gray-500">Dibuat</div>
                    <div class="font-medium" id="v_created"></div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeViewModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Tutup</button>
            </div>
        </div>
    </div>

    <!-- ================= MODAL IMPORT EXCEL ================= -->
    <div id="modalImport" class="fixed inset-0 z-[2000] hidden items-center justify-center bg-black/40 px-4">
        <div class="bg-white dark:bg-[#263240] w-full max-w-md rounded-xl shadow-xl">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-white/10">
                <h5 class="font-semibold text-[#2b3540] dark:text-white">Import Rekap Data Toko</h5>
                <button type="button" class="text-gray-400 hover:text-gray-600 text-xl leading-none" onclick="hideImport()">&times;</button>
            </div>
            <div class="px-5 py-4 space-y-4">
                <!-- tabel tujuan import: awalnya mengikuti tab yang sedang dibuka -->
                <div>
                    <label class="f-label">Import ke Tabel</label>
                    <select class="f-input" id="bagianImport">
                        <?php foreach (\App\Models\RekapDataTokoModel::BAGIAN as $kodeBagian => $b) : ?>
                            <option value="<?= esc($kodeBagian, 'attr') ?>" data-url="<?= esc(site_url($b['url']), 'attr') ?>" <?= $kodeBagian === $bagian ? 'selected' : '' ?>><?= esc($b['nama']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Semua baris di file masuk ke tabel <b class="nama-bagian-import"><?= esc($info['nama']) ?></b>.</p>
                </div>
                <div>
                    <label class="f-label">File Excel (.xlsx / .xls)</label>
                    <input type="file" class="f-input" id="fileImport" accept=".xlsx,.xls">
                </div>
                <div>
                    <label class="f-label">Perusahaan</label>
                    <select class="f-input" id="perusahaanImport">
                        <option value="">-- Pilih Perusahaan --</option>
                        <?php foreach (\App\Models\RekapDataTokoModel::PERUSAHAAN as $p) : ?>
                            <option value="<?= esc($p, 'attr') ?>"><?= esc($p) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Semua data di file akan dicatat sebagai data perusahaan ini.</p>
                </div>
                <div>
                    <label class="f-label">Jika perusahaan tersebut sudah ada data di tabel itu</label>
                    <label class="flex items-start gap-2 text-[13px] mb-1.5 cursor-pointer">
                        <input type="radio" name="modeImport" value="ganti" class="mt-0.5" checked>
                        <span><b>Ganti</b> &mdash; hapus semua data <span class="nama-bagian-import"><?= esc($info['nama']) ?></span> perusahaan itu, lalu isi dari file (tabel lain tidak berubah)</span>
                    </label>
                    <label class="flex items-start gap-2 text-[13px] cursor-pointer">
                        <input type="radio" name="modeImport" value="tambah" class="mt-0.5">
                        <span><b>Tambahkan</b> &mdash; data lama tetap, kode toko yang sudah ada di tanggal yang sama dilewati</span>
                    </label>
                </div>
            </div>
            <div class="flex items-center justify-end gap-2 px-5 py-4 border-t border-gray-100 dark:border-white/10">
                <button type="button" class="btn2 bg-gray-100 hover:bg-gray-200 text-gray-700" onclick="hideImport()">Batal</button>
                <button type="button" class="btn2 bg-green-600 hover:bg-green-700 text-white" id="btnProsesImport">Import</button>
            </div>
        </div>
    </div>

    <script>
        <?php
        $jsFlag = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE;
        $dokKolom = \App\Models\RekapDataTokoModel::kolomDok();
        $lain = [];
        foreach (\App\Models\RekapDataTokoModel::kolomLain($bagian) as $k) {
            $lain[$k] = $isian[$k][0];
        }
        ?>
        const BAGIAN = <?= json_encode($bagian, $jsFlag) ?>;
        const NAMA_BAGIAN = <?= json_encode($info['nama'], $jsFlag) ?>;
        const JUDUL_EXPORT = 'Rekap Data Toko - ' + NAMA_BAGIAN;
        const FIELDS = <?= json_encode(array_merge(array_keys(\App\Models\RekapDataTokoModel::UMUM), array_diff($kolom, $dokKolom)), $jsFlag) ?>;
        const FIELDS_DOK = <?= json_encode(array_values(array_intersect($kolom, $dokKolom)), $jsFlag) ?>;
        const DOK_SEMUA = <?= json_encode($dokKolom, $jsFlag) ?>;
        const FIELDS_LAIN = <?= json_encode($lain ?: new \stdClass(), $jsFlag) ?>;

        const BASE_UPLOAD_URL = "<?= base_url() ?>";
        const BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        const tglIndo = t => {
            if (!t) return '-';
            const [y, m, d] = t.split('-');
            return Number(d) + ' ' + BULAN[Number(m) - 1] + ' ' + y;
        };

        function escHtml(v) {
            return String(v).replace(/[&<>"']/g, c => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            } [c]));
        }

        // Dokumen bisa berupa link (mis. Google Drive), file upload (uploads/...), atau teks biasa
        function renderFileLink(path) {
            const v = String(path ?? '').trim().replace(/^\/+/, '');
            if (!v) return '<span class="text-gray-400 text-xs">Tidak ada file</span>';
            const cls = 'inline-flex items-center gap-1 text-primary-600 hover:underline font-medium text-xs';

            if (/^https?:\/\//i.test(v)) {
                const drive = /(drive|docs)\.google\.com/i.test(v);
                return `
                    <a href="${escHtml(v)}" target="_blank" rel="noopener" class="${cls}">
                        <i class="ti ${drive ? 'ti-brand-google-drive' : 'ti-link'}"></i> ${drive ? 'Buka di Google Drive' : 'Buka Link'}
                    </a>
                `;
            }
            if (!v.startsWith('uploads/')) {
                return `<span class="text-xs break-words">${escHtml(v)}</span>`;
            }

            const ext = v.split('.').pop().toLowerCase();
            const isImg = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext);
            return `
                <a href="${BASE_UPLOAD_URL}${escHtml(v)}" target="_blank" rel="noopener" class="${cls}">
                    <i class="ti ${isImg ? 'ti-photo' : 'ti-file-download'}"></i> ${isImg ? 'Lihat Foto' : 'Unduh Dokumen'} (${escHtml(ext.toUpperCase())})
                </a>
            `;
        }

        function openViewModal(d) {
            const set = (id, v) => document.getElementById(id).textContent = (v === null || v === undefined || String(v).trim() === '') ? '-' : v;
            FIELDS.forEach(k => set('v_' + k, d[k]));

            set('v_tanggal', d.tanggal ? tglIndo(d.tanggal.substring(0, 10)) : '-');
            set('v_created', d.created_at ? tglIndo(d.created_at.substring(0, 10)) + ' ' + d.created_at.substring(11, 16) : '-');

            FIELDS_DOK.forEach(k => document.getElementById('v_' + k).innerHTML = renderFileLink(d[k]));

            // isian di luar form bagian ini
            const lain = Object.keys(FIELDS_LAIN).filter(k => String(d[k] ?? '').trim() !== '');
            document.getElementById('vLainIsi').innerHTML = lain.map(k =>
                '<div><div class="text-gray-500">' + escHtml(FIELDS_LAIN[k]) + '</div><div class="font-medium break-words">' +
                (DOK_SEMUA.includes(k) ? renderFileLink(d[k]) : escHtml(d[k])) + '</div></div>').join('');
            document.getElementById('vLain').classList.toggle('hidden', !lain.length);

            document.getElementById('viewModal').classList.remove('hidden');
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }
    </script>

    <script>
        // ---- import excel (sama seperti Import Jadwal Shift) ----
        const IMPORT_URL = "<?= site_url('RekapDataMidi/import') ?>";

        function getCsrf() {
            const el = [...document.getElementsByTagName('meta')].find(m => m.name && m.content && m.name.toLowerCase().includes('csrf'));
            return el ? {
                key: el.name,
                val: el.content
            } : null;
        }

        // Banner sukses/gagal di tengah-atas — sama seperti halaman lain
        function showFlash(type, message) {
            const ok = type === 'success';
            const wrap = document.createElement('div');
            wrap.className = 'fixed top-5 left-1/2 -translate-x-1/2 z-[100000] w-full max-w-md px-4';
            wrap.innerHTML =
                '<div class="' + (ok ? 'bg-green-500' : 'bg-red-500') + ' text-white rounded-xl shadow-xl overflow-hidden">' +
                '<div class="flex items-center gap-3 px-5 py-4">' +
                '<i class="ti ' + (ok ? 'ti-circle-check' : 'ti-alert-circle') + ' text-3xl"></i>' +
                '<div><h4 class="font-bold">' + (ok ? 'Berhasil' : 'Gagal') + '</h4>' +
                '<p class="text-sm">' + escHtml(message) + '</p></div>' +
                '</div>' +
                '<div class="h-1 ' + (ok ? 'bg-green-400' : 'bg-red-400') + '">' +
                '<div class="bar h-full bg-white w-full"></div>' +
                '</div>' +
                '</div>';
            document.body.appendChild(wrap);

            const bar = wrap.querySelector('.bar');
            if (bar) {
                bar.style.transition = 'width 3s linear';
                setTimeout(() => bar.style.width = '0%', 100);
            }
            setTimeout(() => {
                wrap.style.transition = 'all .5s ease';
                wrap.style.opacity = '0';
                wrap.style.transform = 'translate(-50%, -20px)';
                setTimeout(() => wrap.remove(), 500);
            }, 3000);
        }

        function hideImport() {
            const m = document.getElementById('modalImport');
            m.classList.add('hidden');
            m.classList.remove('flex');
        }
        // Dropdown tabel tujuan import
        const bagianImport = document.getElementById('bagianImport');
        const namaBagianImport = () => bagianImport.options[bagianImport.selectedIndex].text;

        function tampilBagianImport() {
            document.querySelectorAll('.nama-bagian-import').forEach(el => el.textContent = namaBagianImport());
        }
        bagianImport.addEventListener('change', tampilBagianImport);

        document.getElementById('modalImport').addEventListener('click', function(e) {
            if (e.target === this) hideImport();
        });
        document.getElementById('btnImport')?.addEventListener('click', function() {
            document.getElementById('fileImport').value = '';
            document.getElementById('perusahaanImport').value = '';
            bagianImport.value = BAGIAN; // awalnya tab yang sedang dibuka
            tampilBagianImport();
            document.querySelector('input[name="modeImport"][value="ganti"]').checked = true;
            const m = document.getElementById('modalImport');
            m.classList.remove('hidden');
            m.classList.add('flex');
        });

        document.getElementById('btnProsesImport').addEventListener('click', async function() {
            const file = document.getElementById('fileImport').files[0];
            if (!file) {
                showFlash('error', 'Pilih file Excel terlebih dahulu.');
                return;
            }
            // dibaca sekali di awal supaya tabel tujuan tidak berubah selama proses
            const tujuan = bagianImport.value;
            const namaTujuan = namaBagianImport();
            const urlTujuan = bagianImport.options[bagianImport.selectedIndex].dataset.url;
            const perusahaan = document.getElementById('perusahaanImport').value;
            if (!perusahaan) {
                showFlash('error', 'Pilih perusahaan untuk data di file ini.');
                return;
            }
            const mode = document.querySelector('input[name="modeImport"]:checked').value;
            if (mode === 'ganti') {
                const r = await Swal.fire({
                    title: 'Ganti data ' + namaTujuan + '?',
                    text: 'Semua data ' + namaTujuan + ' ' + perusahaan + ' akan dihapus lalu diisi ulang dari file.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#16a34a',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, import',
                    cancelButtonText: 'Batal'
                });
                if (!r.isConfirmed) return;
            }

            const btn = this;
            btn.disabled = true;
            btn.textContent = 'Memproses...';

            const body = new FormData();
            body.append('file_import', file);
            body.append('kategori', tujuan);
            body.append('perusahaan', perusahaan);
            body.append('mode', mode);
            const c = getCsrf();
            if (c) body.append(c.key, c.val);

            try {
                const res = await fetch(IMPORT_URL, {
                    method: 'POST',
                    body: body
                });
                const json = await res.json().catch(() => ({}));
                if (res.ok && json.status === 'ok') {
                    hideImport();
                    // baris yang dilewati dilaporkan supaya bisa dicek manual, lalu tabel dimuat ulang
                    if (json.tidakDikenal && json.tidakDikenal.length) {
                        await Swal.fire({
                            icon: 'info',
                            title: 'Sebagian baris dilewati',
                            html: '<div class="text-left text-[13px] mt-2 max-h-60 overflow-y-auto">' +
                                json.tidakDikenal.map(escHtml).join('<br>') + '</div>',
                            confirmButtonColor: '#04a9f5'
                        });
                    }
                    // buka tab tabel tujuan supaya data yang baru masuk langsung terlihat
                    if (tujuan === BAGIAN) window.location.reload();
                    else window.location.href = urlTujuan;
                } else {
                    showFlash('error', json.message || 'Import gagal.');
                }
            } catch (e) {
                showFlash('error', 'Import gagal, coba lagi.');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Import';
            }
        });
    </script>

    <script>
        // ====== DELETE ======
        function confirmDelete(id) {
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
                    window.location.href = "<?= site_url('RekapDataMidi/delete/') ?>" + id;
                }
            });
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
            // Kolom: 0 No, 1 Aksi, 2 Tanggal, 3 Nama Pengisi, 4 Perusahaan, 5 Toko, 6 Branch, 7 Kode, 8 Nama Toko, 9 dst. isian bagian
            const DOC_COLS = <?= json_encode(array_map(static fn ($i) => $i + 9, array_keys(array_intersect($kolom, $dokKolom)))) ?>;
            const LINK_COLS = DOC_COLS.concat(<?= array_search('sharelock_tikor_alamat', $kolom, true) + 9 ?>);
            const EXPORT_COLS = <?= json_encode(array_merge([0], range(2, count($kolom) + 8))) ?>;
            const IMG_EXT = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            const cellText = data => {
                const tmp = document.createElement('div');
                tmp.innerHTML = String(data).replace(/<br\s*\/?>/gi, '\n');
                return tmp.textContent.trim();
            };
            const isLocalDoc = v => /^uploads\//.test(v);
            const docUrl = v => isLocalDoc(v) ? BASE_UPLOAD_URL + v : v;

            // Nilai asli sel: dokumen memakai data-raw (path upload / link), selain itu teksnya
            const rawCell = (data, node) => node && node.dataset && node.dataset.raw !== undefined ? node.dataset.raw : cellText(data);
            // Copy / CSV: dokumen & link ditulis sebagai URL lengkap
            const exportConfig = {
                exportOptions: {
                    columns: EXPORT_COLS,
                    format: {
                        body: (data, row, column, node) => docUrl(rawCell(data, node))
                    }
                }
            };
            // PDF memakai nilai asli dokumen (path upload / link), diganti gambar/link di customize
            const exportConfigPdf = {
                exportOptions: {
                    columns: EXPORT_COLS,
                    format: {
                        body: (data, row, column, node) => rawCell(data, node)
                    }
                }
            };

            // PDF: foto upload jadi gambar, link bisa diklik
            const IMG_CACHE = {};

            function loadImageData(url) {
                return new Promise(resolve => {
                    const img = new Image();
                    img.onload = () => {
                        const max = 240;
                        const k = Math.min(1, max / Math.max(img.width, img.height));
                        const cv = document.createElement('canvas');
                        cv.width = Math.max(1, Math.round(img.width * k));
                        cv.height = Math.max(1, Math.round(img.height * k));
                        const ctx = cv.getContext('2d');
                        ctx.fillStyle = '#fff';
                        ctx.fillRect(0, 0, cv.width, cv.height);
                        ctx.drawImage(img, 0, 0, cv.width, cv.height);
                        resolve(cv.toDataURL('image/jpeg', 0.8));
                    };
                    img.onerror = () => resolve(null);
                    img.src = url;
                });
            }

            async function preloadDocImages(dt) {
                const paths = new Set();
                DOC_COLS.forEach(c => dt.column(c, {
                    search: 'applied'
                }).nodes().each(td => {
                    const v = td.dataset.raw || '';
                    if (isLocalDoc(v) && IMG_EXT.includes(v.split('.').pop().toLowerCase()) && !(v in IMG_CACHE)) paths.add(v);
                }));
                await Promise.all([...paths].map(async v => {
                    IMG_CACHE[v] = await loadImageData(BASE_UPLOAD_URL + v);
                }));
            }

            function pdfDocCell(v, cell) {
                if (!v) return cell;
                if (isLocalDoc(v)) {
                    if (IMG_CACHE[v]) return {
                        image: IMG_CACHE[v],
                        fit: [60, 60],
                        link: docUrl(v)
                    };
                    return {
                        text: 'Unduh ' + v.split('/').pop(),
                        link: docUrl(v),
                        color: '#0563c1',
                        decoration: 'underline'
                    };
                }
                if (/^https?:\/\/\S+$/i.test(v)) {
                    return {
                        text: /(drive|docs)\.google\.com/i.test(v) ? 'Buka Google Drive' : 'Buka Link',
                        link: v,
                        color: '#0563c1',
                        decoration: 'underline'
                    };
                }
                return cell;
            }

            // Excel dibuat di server: link jadi hyperlink, foto upload tertanam sebagai gambar
            function exportExcelServer(dt) {
                const ids = [];
                dt.rows({
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(tr => ids.push(tr.dataset.id));

                const form = document.createElement('form');
                form.method = 'POST';
                form.action = "<?= site_url('RekapDataMidi/exportExcel') ?>";
                const add = (n, v) => {
                    const i = document.createElement('input');
                    i.type = 'hidden';
                    i.name = n;
                    i.value = v;
                    form.appendChild(i);
                };
                add('kategori', BAGIAN);
                add('ids', ids.join(','));
                const c = getCsrf();
                if (c) add(c.key, c.val);
                document.body.appendChild(form);
                form.submit();
                form.remove();
            }

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
                    buttons: [{
                            extend: 'copyHtml5',
                            text: '<i class="ti ti-copy"></i> Copy',
                            title: JUDUL_EXPORT,
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, button, config);
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            text: '<i class="ti ti-file-text"></i> Export CSV',
                            title: JUDUL_EXPORT,
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                            }
                        },
                        {
                            extend: 'excelHtml5', // hanya untuk tampilan tombol; file dibuat di server
                            text: '<i class="ti ti-file-spreadsheet"></i> Export Excel',
                            title: JUDUL_EXPORT,
                            ...exportConfig,
                            action: function(e, dt) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                exportExcelServer(dt);
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            text: '<i class="ti ti-file-type-pdf"></i> Export PDF',
                            title: JUDUL_EXPORT,
                            orientation: 'landscape',
                            pageSize: 'A3',
                            ...exportConfigPdf,
                            action: async function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                const self = this;
                                Swal.fire({
                                    title: 'Menyiapkan PDF...',
                                    allowOutsideClick: false,
                                    didOpen: () => Swal.showLoading()
                                });
                                await preloadDocImages(dt);
                                Swal.close();
                                $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config);
                            },
                            customize: function(doc) {
                                doc.styles.tableHeader = {
                                    fillColor: '#04a9f5',
                                    color: '#fff',
                                    bold: true,
                                    alignment: 'left'
                                };
                                doc.defaultStyle.fontSize = 8;
                                // nomor kolom export = nomor kolom tabel - 1 (kolom Aksi tidak ikut)
                                const body = doc.content[1].table.body;
                                for (let r = 1; r < body.length; r++) {
                                    for (const c of LINK_COLS.map(i => i - 1)) {
                                        const cell = body[r][c];
                                        const v = String(cell && cell.text !== undefined ? cell.text : '').trim();
                                        const out = pdfDocCell(v, cell);
                                        if (out !== cell) body[r][c] = Object.assign({}, out, {
                                            style: cell.style
                                        });
                                    }
                                }
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
                    emptyTable: "Data " + NAMA_BAGIAN + " belum tersedia",
                    zeroRecords: "Tidak ada data yang cocok dengan pencarian",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Selanjutnya"
                    }
                }
            });

            table.on('draw.dt order.dt search.dt', function() {
                let i = table.page.info().start;
                table.column(0, {
                    page: 'current',
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(cell) {
                    cell.innerHTML = ++i;
                });
            });
            table.draw();

            $('#lengthArea').append($('.dataTables_length'));
            $('#exportArea').append($('.dt-buttons'));

            // ====== FILTER DROPDOWN ======

            // Urutan kolom: 0 No, 1 Aksi, 2 Tanggal, 3 Nama Pengisi, 4 Perusahaan, 5 Toko, 6 Branch
            const exactSearch = v => v ? '^' + $.fn.dataTable.util.escapeRegex(v) + '$' : '';

            // Kolom 4 = Perusahaan
            $('#filterPerusahaan').on('change', function() {
                table.column(4).search(exactSearch(this.value), true, false).draw();
            });

            // Kolom 5 = Toko (Lawson / Midi)
            $('#filterToko').on('change', function() {
                table.column(5).search(exactSearch(this.value), true, false).draw();
            });

            // Kolom 6 = Branch
            $('#filterBranch').on('change', function() {
                table.column(6).search(exactSearch(this.value), true, false).draw();
            });

            // ====== SEARCH & RESET ======
            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });
            $('#customSearch').on('keypress', function(e) {
                if (e.which === 13) table.search(this.value).draw();
            });

            // Filter Bulan: cocokkan data-bulan (YYYY-MM) di <tr>
            $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                const bulan = $('#filterBulan').val();
                return settings.nTable.id !== 'reportTable' || !bulan || settings.aoData[dataIndex].nTr.dataset.bulan === bulan;
            });
            $('#filterBulan').on('change', function() {
                table.draw();
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