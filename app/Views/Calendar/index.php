<?php

/** @var string|null $username */
?>
<!doctype html>
<html lang="id" class="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?= csrf_meta() ?>
    <link rel="icon" type="image/png" href="<?= base_url('favicon-rumahwifi.png') ?>?v=2">
    <title>Jadwal Shift</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- FullCalendar 6 -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>

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

        .dark body,
        body.darkbody {
            background: #1d2630;
        }

        .pc-sidebar {
            transition: transform .25s ease, width .25s ease
        }

        .brand-text {
            font-size: 18px;
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

        /* ===== form & tombol (Tailwind-friendly, warna brand) ===== */
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

        .btn2 {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: 1px solid transparent;
        }

        /* ===== FullCalendar: tanggal terisi = kotak kecil ===== */
        .fc .fc-daygrid-day-events {
            display: flex;
            flex-wrap: wrap;
            gap: 3px;
            padding: 3px;
            margin: 0;
            min-height: 0;
        }

        .fc .fc-daygrid-event-harness {
            position: static !important;
            margin: 0 !important;
        }

        .fc-daygrid-event {
            padding: 0 !important;
            border: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
            margin: 0 !important;
        }

        .fc-daygrid-event .fc-event-main {
            padding: 0 !important;
        }

        .ev-box {
            width: 22px;
            height: 22px;
            border-radius: 4px;
            color: #fff;
            font-size: .72rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .fc .fc-day-today {
            background: #eef7ff !important;
        }

        .fc-daygrid-day.selected-day {
            outline: 2px solid #04a9f5;
            outline-offset: -2px;
            border-radius: 6px;
        }

        .fc-event {
            cursor: pointer;
        }

        .fc a {
            color: inherit;
        }

        /* ===== MODE DARK: huruf pada data jadwal otomatis putih ===== */
        .dark #Calendar,
        .dark #Calendar .fc-daygrid-day-number,
        .dark #panelList,
        .dark #panelList span,
        .dark #panelList div,
        .dark #noteWrap,
        .dark #noteWrap h6,
        .dark #noteContainer,
        .dark #noteContainer input:not([type="color"]),
        .dark .f-label,
        .dark #btnKemarin,
        .dark #btnBesok {
            color: #fff !important;
        }

        .dark #Calendar .fc-theme-standard td,
        .dark #Calendar .fc-theme-standard th,
        .dark #Calendar .fc-theme-standard .fc-scrollgrid {
            border-color: rgba(255, 255, 255, .12);
        }

        /* header hari (Min-Sab): latar header FullCalendar default putih, ikut warna kartu & huruf seperti Piket */
        .dark #Calendar {
            --fc-page-bg-color: transparent;
        }

        .dark #Calendar .fc-col-header-cell-cushion {
            color: #c3c6d8 !important;
        }

        .dark #Calendar .fc-day-today {
            background: rgba(4, 169, 245, .18) !important;
        }

        .dark #noteContainer input:not([type="color"]) {
            background: transparent;
        }

        /* popup SweetAlert selalu tampil di depan modal (modal z-2000) */
        .swal2-container {
            z-index: 3000 !important;
        }

        /* kotak lebih kecil di layar sempit */
        @media (max-width:640px) {
            .ev-box {
                width: 16px;
                height: 16px;
                font-size: .6rem;
                border-radius: 3px;
            }

            .fc .fc-daygrid-day-events {
                gap: 2px;
                padding: 2px;
            }
        }

        /* swatch warna custom: bulat, mengikuti ukuran input */
        input[type="color"].clr-round {
            -webkit-appearance: none;
            appearance: none;
            border: none;
            padding: 0;
            background: none;
            cursor: pointer;
        }

        input[type="color"].clr-round::-webkit-color-swatch-wrapper {
            padding: 0;
            border-radius: 9999px;
            overflow: hidden;
        }

        input[type="color"].clr-round::-webkit-color-swatch {
            border: 2px solid #e3e8ee;
            border-radius: 9999px;
        }

        input[type="color"].clr-round::-moz-color-swatch {
            border: 2px solid #e3e8ee;
            border-radius: 9999px;
        }

        /* ===== KARTU KANAN (Jadwal Shift): kepala tetap, isi bisa di-scroll ===== */
        @media (min-width: 1280px) {
            .jadwal-panel {
                position: sticky;
                top: 94px;
                max-height: calc(100vh - 200px);
                display: flex;
                flex-direction: column;
                overflow: hidden;
            }

            .jadwal-body {
                flex: 1;
                min-height: 0;
                overflow-y: auto;
                margin-right: -12px;
                padding-right: 12px;
            }
        }

        .jadwal-body::-webkit-scrollbar {
            width: 6px;
        }

        .jadwal-body::-webkit-scrollbar-track {
            margin-bottom: 4px;
            background: transparent;
        }

        .jadwal-body::-webkit-scrollbar-thumb {
            border-radius: 8px;
        }

        /* kotak cari jadwal */
        .jadwal-panel .rfo-search {
            width: 100%;
            margin-bottom: 12px;
        }

        .jadwal-hasil-tgl {
            font-size: .72rem;
            font-weight: 700;
            color: #2f6bff;
            margin-bottom: 4px;
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/theme/rfo.css') ?>?v=<?= @filemtime(FCPATH . 'assets/theme/rfo.css') ?: '1' ?>">
    <?= view('partials/theme') ?>
</head>

<body class="text-[#37474f] dark:text-[#bfc8d6]">

    <!-- ============ SIDEBAR ============ -->
    <nav id="sidebar" class="pc-sidebar fixed top-0 left-0 h-screen w-sidebar bg-sidebar text-[#a9b7c6] z-[1030] flex flex-col">
        <div class="flex items-center h-header px-6 shrink-0">
            <a href="#" class="flex items-center gap-2 text-white text-2xl font-semibold">
                <span class="text-primary-500"></span>
                <span class="brand-text">Sistem Operasional <br> JWI Group</span>
            </a>
        </div>

        <div class="flex-1 overflow-y-auto overflow-x-hidden py-2.5">
            <ul class="px-0">
                <li class="px-6 py-3 text-[11px] uppercase tracking-wide text-[#5b6b7f] font-semibold">Halaman Utama</li>

                <li>
                    <a href="<?= site_url('dashboard-manager') ?>" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white relative">
                        <span class="pc-micon w-5"><i class="ti ti-home fs-5"></i></span><span class="pc-mtext">Beranda</span>
                    </a>
                </li>
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
                    </ul>
                </li>
                <li class="hasmenu">
                    <a href="#" onclick="toggleSub(this);return false;" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-category"></i></span>
                        <span class="flex-1">All NOC</span>
                        <i data-feather="chevron-right" class="arrow w-4 h-4 transition-transform"></i>
                    </a>
                    <ul class="submenu bg-black/20">
                        <li><a href="<?= site_url('DataCenter') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Center</a></li>
                        <li><a href="<?= site_url('Interkoneksi') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Interkoneksi</a></li>
                        <li><a href="<?= site_url('DevicesBackbone') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Devices Backbone</a></li>
                        <li><a href="<?= site_url('Bts') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data BTS</a></li>
                        <li><a href="<?= site_url('PelangganNoc') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Pelanggan</a></li>
                        <li><a href="<?= site_url('VendorNoc') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Vendor</a></li>
                    </ul>
                </li>
                <li class="hasmenu">
                    <a href="#" onclick="toggleSub(this);return false;" class="pc-link active flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-calendar-week"></i></span>
                        <span class="flex-1">Jadwal NOC</span>
                        <i data-feather="chevron-right" class="arrow w-4 h-4 transition-transform" style="transform: rotate(90deg)"></i>
                    </a>
                    <ul class="submenu open bg-black/20">
                        <li><a href="<?= site_url('Calendar') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white text-white font-semibold">Shift</a></li>
                        <li><a href="<?= site_url('Piket') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Piket</a></li>
                    </ul>
                </li>

                <li class="px-6 py-3 text-[11px] uppercase tracking-wide text-[#5b6b7f] font-semibold">Informasi</li>
                <li><a href="<?= site_url('Profile') ?>" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white"><span class="pc-micon w-5"><i class="ti ti-user-circle"></i></span><span>Profile</span></a></li>
                <li>
                    <a href="<?= site_url('InventoryKantor') ?>" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-basket-down"></i></span>
                        <span>Inventory Kantor</span>
                    </a>
                </li>
                <li><a href="<?= site_url('settings') ?>" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white"><span class="pc-micon w-5"><i class="ti ti-settings"></i></span><span>Pengguna</span></a></li>
                <li><a href="<?= site_url('Logs') ?>" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white"><span class="pc-micon w-5"><i class="ti ti-report-search"></i></span><span>Change Log</span></a></li>
            </ul>
        </div>
    </nav>

    <!-- ============ MAIN ============ -->
    <div id="container" class="pc-container ml-sidebar min-h-screen transition-[margin] duration-200">
        <header class="pc-header sticky top-0 z-[1025] bg-white dark:bg-[#263240] h-header flex items-center px-6 shadow-[0_1px_20px_0_rgba(69,90,100,.08)]">
            <ul class="flex items-center gap-1">
                <li><a href="#" onclick="toggleSidebar();return false;" class="head-link flex items-center justify-center w-10 h-10 rounded hover:bg-gray-100 dark:hover:bg-white/5"><i data-feather="menu"></i></a></li>
            </ul>
            <ul class="flex items-center gap-1 ml-auto">
                <li class="relative dropdown">
                    <a href="#" onclick="toggleDrop(event,this)" class="head-link flex items-center justify-center w-10 h-10 rounded hover:bg-gray-100 dark:hover:bg-white/5"><i data-feather="user"></i></a>
                    <div class="dropdown-menu absolute right-0 mt-1 w-64 bg-white dark:bg-[#263240] rounded shadow-lg overflow-hidden border border-gray-100 dark:border-white/10">
                        <div class="flex items-center gap-3 px-5 py-4 bg-primary-500 text-white">
                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center"><i data-feather="user" class="w-5 h-5 text-gray-500"></i></div>
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

        <!-- ===== KONTEN: KALENDER ===== -->
        <div class="rfo-page p-4 sm:p-6">
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic"><i class="ti ti-calendar-event"></i></span>
                    <h1 class="rfo-title">Jadwal Shift</h1>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <button class="rfo-btn rfo-btn-green" id="btnImport"><i class="ti ti-file-import"></i> Import</button>
                    <button class="rfo-btn rfo-btn-blue" id="btnTambah"><i class="ti ti-plus"></i> Tambah Jadwal</button>
                </div>
            </div>

            <div class="flex flex-col xl:flex-row gap-4 items-start">
                <!-- KIRI: kalender -->
                <div class="w-full xl:flex-1 min-w-0 rfo-panel">
                    <div class="flex items-center justify-center gap-4 mb-3">
                        <button class="text-2xl leading-none text-gray-500 hover:text-primary-500 px-2 rounded" id="btnPrev" title="Bulan sebelumnya">&lsaquo;</button>
                        <span class="text-[1.05rem] font-bold min-w-[120px] text-center text-[#202124] dark:text-white" id="calTitle">&mdash;</span>
                        <button class="text-2xl leading-none text-gray-500 hover:text-primary-500 px-2 rounded" id="btnNext" title="Bulan berikutnya">&rsaquo;</button>
                    </div>

                    <div id="Calendar"></div>

                    <div class="flex justify-center mt-4">
                        <div class="inline-flex rounded-lg overflow-hidden border border-gray-200 text-[13px]">
                            <button class="px-4 py-2 hover:bg-gray-50" id="btnKemarin">&lsaquo; Kemarin</button>
                            <button class="px-4 py-2 border-x border-gray-200 text-primary-600 font-semibold hover:bg-gray-50" id="btnHariIni">Hari ini</button>
                            <button class="px-4 py-2 hover:bg-gray-50" id="btnBesok">Besok &rsaquo;</button>
                        </div>
                    </div>
                </div>

                <!-- KANAN: panel detail -->
                <aside class="w-full xl:w-[330px] xl:flex-none rfo-panel jadwal-panel">

                    <!-- kepala kartu: tetap di tempat saat isi kartu di-scroll -->
                    <div class="jadwal-head">
                        <div class="text-[1.35rem] font-bold text-[#202124] dark:text-white mb-3" id="panelDate">&mdash;</div>
                        <!-- cari jadwal di bulan yang sedang tampil (nama, shift, keterangan) -->
                        <div class="rfo-search">
                            <i class="ti ti-search"></i>
                            <input type="text" id="panelSearch" placeholder="Cari nama, shift, keterangan..." autocomplete="off" />
                        </div>
                        <div class="text-[.78rem] font-semibold text-gray-500 mb-2" id="panelLabel">Jadwal Shift</div>
                    </div>

                    <!-- isi kartu: daftar jadwal & tanda (bisa di-scroll) -->
                    <div class="jadwal-body">
                    <div id="panelList">
                        <div class="text-gray-400 text-sm py-2">Memuat&hellip;</div>
                    </div>
                    <div id="noteWrap">
                        <!-- ================= NOTE WARNA ================= -->
                        <div class="mt-6 pt-4 border-t border-gray-200">
                            <div class="flex items-center justify-between mb-3">
                                <h6 class="font-semibold text-sm">Berikan Tanda</h6>
                                <button id="btnTambahNote" class="text-xs px-3 py-1 rounded bg-primary-500 text-white hover:bg-primary-600">
                                    + Tambah
                                </button>
                            </div>
                            <div id="noteContainer" class="space-y-3"></div>
                        </div>
                    </div>
                    </div>
                </aside>


            </div>

        </div>
    </div>

    <!-- ================= MODAL FORM (Tailwind) ================= -->
    <div id="modalForm" class="fixed inset-0 z-[2000] hidden items-center justify-center bg-black/40 px-4">
        <div class="bg-white dark:bg-[#263240] w-full max-w-md rounded-xl shadow-xl">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-white/10">
                <h5 class="font-semibold text-[#2b3540] dark:text-white" id="modalTitle">Tambah Jadwal</h5>
                <button type="button" class="text-gray-400 hover:text-gray-600 text-xl leading-none" onclick="hideModal()">&times;</button>
            </div>
            <div class="px-5 py-4 space-y-4">
                <input type="hidden" id="id">
                <div>
                    <label class="f-label">Tanggal</label>
                    <input type="date" class="f-input" id="tanggal" required>
                </div>
                <div>
                    <label class="f-label">Shift</label>
                    <select class="f-input" id="shift" required>
                        <?php foreach ($label as $k => $v) : ?>
                            <option value="<?= $k ?>"><?= esc($v) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="f-label">Masukan Nama</label>
                    <input type="text" class="f-input" id="nama" placeholder="Masukan Nama" required>
                </div>
                <div>
                    <label class="f-label">Warna</label>
                    <input type="color" class="f-input h-11 p-1" id="warna" value="#04a9f5">
                </div>
                <div>
                    <label class="f-label">Keterangan <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <textarea class="f-input" id="keterangan" rows="2"></textarea>
                </div>
            </div>
            <div class="flex items-center gap-2 px-5 py-4 border-t border-gray-100 dark:border-white/10">
                <button type="button" class="btn2 bg-red-600 hover:bg-red-700 text-white mr-auto hidden" id="btnHapus">Hapus</button>
                <button type="button" class="btn2 bg-gray-100 hover:bg-gray-200 text-gray-700" onclick="hideModal()">Batal</button>
                <button type="button" class="btn2 bg-primary-500 hover:bg-primary-600 text-white" id="btnSimpan">Simpan</button>
            </div>
        </div>

    </div>

    <!-- ================= MODAL IMPORT EXCEL ================= -->
    <div id="modalImport" class="fixed inset-0 z-[2000] hidden items-center justify-center bg-black/40 px-4">
        <div class="bg-white dark:bg-[#263240] w-full max-w-md rounded-xl shadow-xl">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 dark:border-white/10">
                <h5 class="font-semibold text-[#2b3540] dark:text-white">Import Jadwal Shift</h5>
                <button type="button" class="text-gray-400 hover:text-gray-600 text-xl leading-none" onclick="hideImport()">&times;</button>
            </div>
            <div class="px-5 py-4 space-y-4">
                <div>
                    <label class="f-label">File Excel (.xlsx / .xls)</label>
                    <input type="file" class="f-input" id="fileImport" accept=".xlsx,.xls">
                   
                </div>
                <div>
                    <label class="f-label">Jika bulan tersebut sudah ada data</label>
                    <label class="flex items-start gap-2 text-[13px] mb-1.5 cursor-pointer">
                        <input type="radio" name="modeImport" value="ganti" class="mt-0.5" checked>
                        <span><b>Ganti</b> &mdash; hapus semua jadwal shift di bulan itu, lalu isi dari file</span>
                    </label>
                    <label class="flex items-start gap-2 text-[13px] cursor-pointer">
                        <input type="radio" name="modeImport" value="tambah" class="mt-0.5">
                        <span><b>Tambahkan</b> &mdash; data lama tetap, nama yang sudah punya jadwal di tanggal itu dilewati</span>
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
        document.getElementById('shift').addEventListener('change', function() {
            document.getElementById('warna').value = SHIFT_WARNA[this.value] || '#04a9f5';
        });
        const BASE = "<?= site_url('Calendar') ?>";

        const HARI = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const SHIFT_LABEL = <?= json_encode($label) ?>; // 1-3 shift, 4 Off, 5 HO
        const SHIFT_WARNA = <?= json_encode($warna) ?>; // hijau, kuning, biru, merah, ungu

        function ymd(d) {
            return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
        }

        function todayStr() {
            return ymd(new Date());
        }

        function formatTanggal(str) {
            const p = str.split('-').map(Number);
            const dt = new Date(p[0], p[1] - 1, p[2]);
            return HARI[dt.getDay()] + ', ' + p[2] + ' ' + BULAN[p[1] - 1] + ' ' + p[0];
        }

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
            wrap.className = 'fixed top-5 left-1/2 -translate-x-1/2 z-[3000] w-full max-w-md px-4';
            wrap.innerHTML =
                '<div class="' + (ok ? 'bg-green-500' : 'bg-red-500') + ' text-white rounded-xl shadow-xl overflow-hidden">' +
                '<div class="flex items-center gap-3 px-5 py-4">' +
                '<i class="ti ' + (ok ? 'ti-circle-check' : 'ti-alert-circle') + ' text-3xl"></i>' +
                '<div><h4 class="font-bold">' + (ok ? 'Berhasil' : 'Gagal') + '</h4>' +
                '<p class="text-sm">' + message + '</p></div>' +
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

        let Calendar;
        let selectedDate = todayStr();

        document.addEventListener('DOMContentLoaded', function() {
            Calendar = new FullCalendar.Calendar(document.getElementById('Calendar'), {
                initialView: 'dayGridMonth',
                locale: 'id',
                headerToolbar: false,
                height: 'auto', // responsif: ikut tinggi konten
                dayMaxEvents: false,
                events: BASE + '/events',

                eventContent: function(arg) {
                    const box = document.createElement('div');
                    box.className = 'ev-box';
                    const s = arg.event.extendedProps.shift;
                    box.style.background = arg.event.extendedProps.warna || '#04a9f5';
                    box.textContent = (s == 4 ? '0' : (s == 5 ? 'H' : s));
                    box.title = SHIFT_LABEL[s] + ' - ' + arg.event.extendedProps.nama;
                    return {
                        domNodes: [box]
                    };
                },
                datesSet: function(info) {
                    const d = info.view.currentStart;
                    document.getElementById('calTitle').textContent = BULAN[d.getMonth()] + ' ' + d.getFullYear();
                    highlightSelected(selectedDate);
                },
                eventsSet: function() {
                    renderPanel(selectedDate);
                },
                dateClick: function(info) {
                    selectDate(info.dateStr);
                },
                eventClick: function(info) {
                    const p = info.event.extendedProps;
                    openForm({
                        id: info.event.id,
                        tanggal: p.tanggal,
                        shift: p.shift,
                        nama: p.nama,
                        warna: p.warna,
                        keterangan: p.keterangan
                    });
                }
            });
            loadNotes();

            document
                .getElementById("btnTambahNote")
                .addEventListener("click", addNote);
            Calendar.render();
            feather.replace();
        });

        // header pindah bulan
        document.getElementById('btnPrev').addEventListener('click', function() {
            Calendar.prev();
        });
        document.getElementById('btnNext').addEventListener('click', function() {
            Calendar.next();
        });

        // Kemarin / Hari ini / Besok
        function selectDate(dateStr) {
            selectedDate = dateStr;
            highlightSelected(dateStr);
            renderPanel(dateStr);
        }

        function goToDate(dateStr) {
            selectedDate = dateStr;
            Calendar.gotoDate(dateStr);
            highlightSelected(dateStr);
            renderPanel(dateStr);
        }

        function shiftDay(delta) {
            const d = new Date(selectedDate + 'T00:00:00');
            d.setDate(d.getDate() + delta);
            goToDate(ymd(d));
        }
        document.getElementById('btnKemarin').addEventListener('click', function() {
            shiftDay(-1);
        });
        document.getElementById('btnBesok').addEventListener('click', function() {
            shiftDay(1);
        });
        document.getElementById('btnHariIni').addEventListener('click', function() {
            goToDate(todayStr());
        });

        function highlightSelected(dateStr) {
            document.querySelectorAll('.fc-daygrid-day.selected-day').forEach(e => e.classList.remove('selected-day'));
            const cell = document.querySelector('.fc-daygrid-day[data-date="' + dateStr + '"]');
            if (cell) cell.classList.add('selected-day');
        }

        // Hasil cari: semua jadwal yang dimuat kalender (bulan yang tampil), urut tanggal lalu shift
        function renderSearch(q) {
            const items = Calendar.getEvents()
                .map(function(ev) {
                    return Object.assign({
                        id: ev.id,
                        tanggal: ev.startStr
                    }, ev.extendedProps);
                })
                .filter(function(it) {
                    const teks = [it.nama, SHIFT_LABEL[it.shift], it.keterangan].join(' ').toLowerCase();
                    return teks.indexOf(q) !== -1;
                })
                .sort(function(a, b) {
                    return a.tanggal === b.tanggal ? a.shift - b.shift : (a.tanggal < b.tanggal ? -1 : 1);
                });

            document.getElementById('panelLabel').textContent = 'Hasil pencarian (' + items.length + ' jadwal di bulan ini)';
            const box = document.getElementById('panelList');
            if (!items.length) {
                box.innerHTML = '<div class="text-gray-400 text-sm py-2">Tidak ada jadwal yang cocok.</div>';
                return;
            }
            box.innerHTML = items.map(function(it) {
                return '' +
                    '<div class="rounded-lg border border-gray-100 p-3 mb-2.5 cursor-pointer hover:shadow-md transition" ' +
                    'style="border-left:4px solid ' + it.warna + '" ' +
                    'onclick="bukaHasilCari(\'' + it.tanggal + '\')">' +
                    '<div class="jadwal-hasil-tgl">' + formatTanggal(it.tanggal) + '</div>' +
                    '<div class="flex items-center gap-2">' +
                    '<span class="w-3 h-3 rounded-full shrink-0" style="background:' + it.warna + '"></span>' +
                    '<span class="font-semibold text-[#202124] text-[.92rem]">' + SHIFT_LABEL[it.shift] + ' - ' + escapeHtml(it.nama) + '</span>' +
                    '</div>' +
                    (it.keterangan ? '<div class="text-[.8rem] text-gray-500 mt-1.5 ml-5 leading-snug">' + escapeHtml(it.keterangan) + '</div>' : '') +
                    '</div>';
            }).join('');
        }

        // Klik hasil cari: kosongkan pencarian lalu buka tanggal tersebut di kalender
        function bukaHasilCari(dateStr) {
            document.getElementById('panelSearch').value = '';
            goToDate(dateStr);
        }

        document.getElementById('panelSearch').addEventListener('input', function() {
            renderPanel(selectedDate);
        });

        function renderPanel(dateStr) {
            document.getElementById('panelDate').textContent = formatTanggal(dateStr);
            const q = document.getElementById('panelSearch').value.trim().toLowerCase();
            if (q) return renderSearch(q);
            document.getElementById('panelLabel').textContent = 'Jadwal Shift';
            const items = Calendar.getEvents()
                .filter(function(ev) {
                    return ev.startStr === dateStr;
                })
                .map(function(ev) {
                    return Object.assign({
                        id: ev.id
                    }, ev.extendedProps);
                })
                .sort(function(a, b) {
                    return a.shift - b.shift;
                });

            const box = document.getElementById('panelList');
            if (!items.length) {
                box.innerHTML =
                    '<div class="text-gray-400 text-sm py-2">Belum ada jadwal untuk Hari ini.</div>' +
                    '<button class="btn2 mt-1 border border-primary-500 text-primary-600 hover:bg-primary-50" ';
                return;
            }
            box.innerHTML = items.map(function(it) {
                const payload = JSON.stringify(it).replace(/'/g, "&#39;");
                return '' +
                    '<div class="rounded-lg border border-gray-100 p-3 mb-2.5 cursor-pointer hover:shadow-md transition" ' +
                    'style="border-left:4px solid ' + it.warna + '" ' +
                    "onclick='openForm(" + payload + ")'>" +
                    '<div class="flex items-center gap-2">' +
                    '<span class="w-3 h-3 rounded-full shrink-0" style="background:' + it.warna + '"></span>' +
                    '<span class="font-semibold text-[#202124] text-[.92rem]">' + SHIFT_LABEL[it.shift] + ' - ' + escapeHtml(it.nama) + '</span>' +
                    '</div>' +
                    (it.keterangan ? '<div class="text-[.8rem] text-gray-500 mt-1.5 ml-5 leading-snug">' + escapeHtml(it.keterangan) + '</div>' : '') +
                    '</div>';
            }).join('');
        }

        function escapeHtml(s) {
            return (s == null ? '' : String(s)).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        }
        const NOTE_KEY = "calendar_note_warna";

        // state note di memori — diedit dulu, baru dipersist saat tombol "Simpan" ditekan
        // state note di memori — diambil dari server, dipersist saat "Simpan"/tambah/hapus
        let notesState = [];

        async function loadNotes() {
            try {
                const res = await fetch(BASE + '/notes');
                const data = await res.json();
                notesState = Array.isArray(data) ? data : [];
            } catch (e) {
                notesState = [];
            }
            renderNotes();
        }

        async function persistNotes() {
            const body = new FormData();
            body.append('data', JSON.stringify(notesState));
            const c = getCsrf();
            if (c) body.append(c.key, c.val);
            try {
                await fetch(BASE + '/notes', {
                    method: 'POST',
                    body: body
                });
            } catch (e) {}
        }

        function renderNotes() {
            const box = document.getElementById("noteContainer");

            if (!notesState.length) {
                box.innerHTML = `<div class="text-gray-400 text-sm">Belum ada tanda.</div>`;
                return;
            }

            box.innerHTML = notesState.map((item, index) => `
        <div class="rounded-lg border border-gray-100 p-3 space-y-2">
            <div class="flex items-center gap-2">
                <input
                    type="color"
                    value="${item.color || '#04a9f5'}"
                    onchange="updateNoteField(${index}, 'color', this.value)"
                    class="clr-round w-9 h-9 shrink-0">

                <input
                    class="f-input flex-1"
                    placeholder="Keterangan"
                    value="${escapeHtml(item.text || '')}"
                    onchange="updateNoteField(${index}, 'text', this.value)">
            </div>

            <button
                onclick="deleteNote(${index})"
                class="btn2 w-full bg-red-600 hover:bg-red-700 text-white flex items-center justify-center gap-1">
                <i class="fa fa-trash"></i> Hapus
            </button>

            <button
                onclick="saveNoteRow(${index}, this)"
                class="btn2 w-full bg-primary-500 hover:bg-primary-600 text-white text-xs flex items-center justify-center gap-1">
                <i class="fa fa-save"></i> Simpan
            </button>
        </div>
    `).join("");
        }

        async function addNote() {
            notesState.push({
                color: "#04a9f5",
                text: ""
            });
            await persistNotes();
            renderNotes();
        }

        function updateNoteField(index, field, val) {
            notesState[index][field] = val;
            // belum dipersist ke localStorage — menunggu tombol "Simpan" ditekan
        }

        async function saveNoteRow(index) {
            await persistNotes();
            showFlash('success', 'Tanda tersimpan');
        }

        async function deleteNote(index) {
            notesState.splice(index, 1);
            await persistNotes(); // tunggu server selesai simpan
            renderNotes();
            showFlash('success', 'Tanda berhasil dihapus');
        }
        // ---- modal ----
        function showModal() {
            const m = document.getElementById('modalForm');
            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function hideModal() {
            const m = document.getElementById('modalForm');
            m.classList.add('hidden');
            m.classList.remove('flex');
        }
        document.getElementById('modalForm').addEventListener('click', function(e) {
            if (e.target === this) hideModal();
        });

        document.getElementById('btnTambah').addEventListener('click', function() {
            openForm({
                tanggal: selectedDate
            });
        });

        function openForm(d) {
            d = d || {};
            document.getElementById('id').value = d.id || '';
            document.getElementById('tanggal').value = d.tanggal || selectedDate;
            document.getElementById('shift').value = d.shift || '1';
            document.getElementById('nama').value = d.nama || '';
            document.getElementById('warna').value = d.warna || SHIFT_WARNA[d.shift || '1'];
            document.getElementById('keterangan').value = d.keterangan || '';
            document.getElementById('modalTitle').textContent = d.id ? 'Edit Jadwal' : 'Tambah Jadwal';
            document.getElementById('btnHapus').classList.toggle('hidden', !d.id);
            showModal();
        }

        document.getElementById('btnSimpan').addEventListener('click', async function() {
            const isNew = !document.getElementById('id').value;
            const body = new FormData();
            body.append('id', document.getElementById('id').value);
            body.append('tanggal', document.getElementById('tanggal').value);
            body.append('shift', document.getElementById('shift').value);
            body.append('nama', document.getElementById('nama').value);
            body.append('warna', document.getElementById('warna').value);
            body.append('keterangan', document.getElementById('keterangan').value);
            const c = getCsrf();
            if (c) body.append(c.key, c.val);

            const res = await fetch(BASE + '/save', {
                method: 'POST',
                body: body
            });
            if (res.ok) {
                selectedDate = document.getElementById('tanggal').value;
                hideModal();
                Calendar.refetchEvents();
                showFlash('success', isNew ? 'Jadwal berhasil ditambahkan' : 'Jadwal berhasil diperbarui'); // << tambah
            } else {
                showFlash('error', 'Pastikan tanggal, shift, dan nama terisi.'); // << ganti Swal (opsional)
            }
        });

        document.getElementById('btnHapus').addEventListener('click', function() {
            const id = document.getElementById('id').value;
            if (!id) return;
            Swal.fire({
                title: 'Hapus jadwal ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus',
                cancelButtonText: 'Batal'
            }).then(async function(r) {
                if (!r.isConfirmed) return;
                const body = new FormData();
                const c = getCsrf();
                if (c) body.append(c.key, c.val);
                const res = await fetch(BASE + '/delete/' + id, {
                    method: 'POST',
                    body: body
                });
                if (res.ok) {
                    hideModal();
                    Calendar.refetchEvents();
                    showFlash('success', 'Jadwal berhasil dihapus'); // << tambah
                } else {
                    showFlash('error', 'Tidak bisa menghapus.');
                }
            });
        });

        // ---- import excel ----
        function hideImport() {
            const m = document.getElementById('modalImport');
            m.classList.add('hidden');
            m.classList.remove('flex');
        }
        document.getElementById('modalImport').addEventListener('click', function(e) {
            if (e.target === this) hideImport();
        });
        document.getElementById('btnImport').addEventListener('click', function() {
            document.getElementById('fileImport').value = '';
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
            const mode = document.querySelector('input[name="modeImport"]:checked').value;
            if (mode === 'ganti') {
                const r = await Swal.fire({
                    title: 'Ganti jadwal shift?',
                    text: 'Semua jadwal shift di bulan yang ada di file akan dihapus lalu diisi ulang dari file.',
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
            body.append('mode', mode);
            const c = getCsrf();
            if (c) body.append(c.key, c.val);

            try {
                const res = await fetch(BASE + '/import', {
                    method: 'POST',
                    body: body
                });
                const json = await res.json().catch(() => ({}));
                if (res.ok && json.status === 'ok') {
                    hideImport();
                    goToDate(json.awal);
                    Calendar.refetchEvents();
                    let msg = json.masuk + ' jadwal ' + json.bulan + ' berhasil diimport';
                    if (json.dilewati) msg += ', ' + json.dilewati + ' dilewati (sudah ada)';
                    showFlash('success', msg);
                    // kode yang tidak dikenali dilaporkan supaya bisa dicek manual
                    if (json.tidakDikenal && json.tidakDikenal.length) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Sebagian sel dilewati',
                            html: 'Kode shift tidak dikenali:<br><div class="text-left text-[13px] mt-2">' +
                                json.tidakDikenal.map(escapeHtml).join('<br>') + '</div>',
                            confirmButtonColor: '#04a9f5'
                        });
                    }
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

        // ---- shell dashboard ----
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
                if (sb.classList.contains('mobile-open') && !sb.contains(e.target) && !menuBtn) sb.classList.remove('mobile-open');
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
    </script>
    <script>
        // PENGHALANG KOSMETIK SAJA — bukan security, mudah dilewati
        document.addEventListener('contextmenu', e => e.preventDefault()); // klik kanan
        document.addEventListener('keydown', e => {
            if (e.key === 'F12') e.preventDefault(); // F12
            if (e.ctrlKey && e.shiftKey && ['I', 'J', 'C'].includes(e.key.toUpperCase())) e.preventDefault();
            if (e.ctrlKey && e.key.toUpperCase() === 'U') e.preventDefault(); // view-source
        });
    </script>
    <?php if (!session()->get('logged_in')) : ?>
        <script>
            window.location.href = "<?= base_url('/login') ?>";
        </script>
    <?php endif; ?>
</body>

</html>