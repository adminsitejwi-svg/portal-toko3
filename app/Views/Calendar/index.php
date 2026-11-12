<?php

/** @var string|null $username */
?>
<!doctype html>
<html lang="id" class="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?= csrf_meta() ?>
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
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
        .jadwal-body {
            max-height: 420px;
            overflow-y: auto;
            margin-right: -12px;
            padding-right: 12px;
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
            color: #6d5dfc;
            margin-bottom: 4px;
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/theme/rfo.css') ?>?v=<?= @filemtime(FCPATH . 'assets/theme/rfo.css') ?: '1' ?>">
    <link rel="stylesheet" href="<?= base_url('assets/theme/jadwal.css') ?>?v=<?= @filemtime(FCPATH . 'assets/theme/jadwal.css') ?: '1' ?>">
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
                    </ul>
                </li>
                <li class="hasmenu">
                    <a href="#" onclick="toggleSub(this);return false;" class="pc-link active flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
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
                <li><a href="<?= site_url('Profile') ?>" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white"><span class="pc-micon w-5"><i class="ti ti-user-circle"></i></span><span>Profile</span></a></li>
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
        <div class="rfo-page sh-page p-4 sm:p-6">
            <!-- ===== JUDUL + TOMBOL ===== -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic"><i class="ti ti-calendar-event"></i></span>
                    <div>
                        <h1 class="rfo-title">Jadwal Shift</h1>
                        <div class="rfo-subtitle">Kelola jadwal shift NOC / NOC Operation Center</div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <button class="rfo-btn rfo-btn-green" id="btnImport"><i class="ti ti-file-import"></i> Import</button>
                    <button class="rfo-btn sh-btn-purple" id="btnTambah"><i class="ti ti-plus"></i> Tambah Jadwal</button>
                </div>
            </div>

            <div class="flex flex-col xl:flex-row gap-5 items-start">
                <!-- ===== KIRI: ringkasan + kalender ===== -->
                <div class="w-full xl:flex-1 min-w-0">
                    <!-- ringkasan periode yang sedang tampil (dihitung dari jadwal di kalender) -->
                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
                        <div class="sh-stat">
                            <span class="sh-stat-ic is-indigo"><i class="ti ti-users"></i></span>
                            <div>
                                <div class="sh-stat-label">Total Personel</div>
                                <div class="sh-stat-value" id="stPersonel">0</div>
                                <div class="sh-stat-note">Orang</div>
                            </div>
                        </div>
                        <div class="sh-stat">
                            <span class="sh-stat-ic is-purple"><i class="ti ti-calendar-check"></i></span>
                            <div>
                                <div class="sh-stat-label">Total Shift</div>
                                <div class="sh-stat-value" id="stShift">0</div>
                                <div class="sh-stat-note">Shift Aktif</div>
                            </div>
                        </div>
                        <div class="sh-stat">
                            <span class="sh-stat-ic is-green"><i class="ti ti-circle-check"></i></span>
                            <div>
                                <div class="sh-stat-label">Terjadwal</div>
                                <div class="sh-stat-value" id="stHari">0</div>
                                <div class="sh-stat-note">Hari</div>
                            </div>
                        </div>
                        <div class="sh-stat">
                            <span class="sh-stat-ic is-violet"><i class="ti ti-clock-hour-4"></i></span>
                            <div>
                                <div class="sh-stat-label">Rata-rata / Hari</div>
                                <div class="sh-stat-value" id="stRata">0</div>
                                <div class="sh-stat-note">Personel</div>
                            </div>
                        </div>
                    </div>

                    <!-- kalender -->
                    <div class="rfo-panel sh-cal">
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                            <div class="flex items-center gap-2">
                                <button class="sh-nav" id="btnPrev" title="Sebelumnya" aria-label="Sebelumnya"><i class="ti ti-chevron-left"></i></button>
                                <div class="sh-month"><i class="ti ti-calendar-month"></i> <span id="calTitle">&mdash;</span></div>
                                <button class="sh-nav" id="btnNext" title="Berikutnya" aria-label="Berikutnya"><i class="ti ti-chevron-right"></i></button>
                            </div>
                            <div class="sh-views" role="group" aria-label="Tampilan kalender">
                                <button type="button" data-view="dayGridMonth" class="active"><i class="ti ti-calendar-month"></i> Bulan</button>
                                <button type="button" data-view="dayGridWeek"><i class="ti ti-layout-grid"></i> Minggu</button>
                                <button type="button" data-view="dayGridDay"><i class="ti ti-list"></i> Hari</button>
                            </div>
                        </div>

                        <div id="Calendar"></div>

                        <div class="sh-cal-foot">
                            <div class="sh-legend">
                                <span class="sh-legend-title">Keterangan Shift:</span>
                                <?php foreach ($label as $k => $v) : ?>
                                    <span class="sh-legend-item"><span class="ev-box" style="background:<?= esc($warna[$k], 'attr') ?>"><?= $k == 4 ? '0' : ($k == 5 ? 'H' : $k) ?></span> <?= esc($v) ?></span>
                                <?php endforeach; ?>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" class="sh-foot-btn" id="btnPrevBawah"><i class="ti ti-chevron-left"></i> <span data-periode="prev">Bulan Sebelumnya</span></button>
                                <button type="button" class="sh-foot-btn is-next" id="btnNextBawah"><span data-periode="next">Bulan Berikutnya</span> <i class="ti ti-chevron-right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ===== KANAN ===== -->
                <aside class="w-full xl:w-[340px] xl:flex-none flex flex-col gap-5">
                    <!-- jam sekarang (WIB) -->
                    <div class="sh-clock">
                        <div class="sh-clock-row"><i class="ti ti-calendar"></i> <span id="jamTanggal">&mdash;</span></div>
                        <div class="sh-clock-row is-time"><i class="ti ti-clock"></i> <span id="jamWaktu">--:--:--</span> WIB</div>
                    </div>

                    <!-- jadwal pada tanggal yang dipilih -->
                    <div class="rfo-panel jadwal-panel sh-side">
                        <!-- kepala kartu: tetap di tempat saat isi kartu di-scroll -->
                        <div class="jadwal-head">
                            <div class="flex items-center justify-between gap-2 mb-1">
                                <div class="sh-side-title"><i class="ti ti-calendar-event"></i> <span id="panelLabel">Jadwal Shift</span></div>
                                <span class="sh-badge" id="panelCount">0 Personel</span>
                            </div>
                            <div class="text-[.82rem] text-[var(--rf-muted)] mb-3" id="panelDate">&mdash;</div>
                            <div class="sh-daynav mb-3">
                                <button type="button" id="btnKemarin"><i class="ti ti-chevron-left"></i> Kemarin</button>
                                <button type="button" id="btnHariIni" class="is-today">Hari ini</button>
                                <button type="button" id="btnBesok">Besok <i class="ti ti-chevron-right"></i></button>
                            </div>
                            <!-- cari jadwal di periode yang sedang tampil (nama, shift, keterangan) -->
                            <div class="rfo-search">
                                <i class="ti ti-search"></i>
                                <input type="text" id="panelSearch" placeholder="Cari nama, shift, keterangan..." autocomplete="off" />
                            </div>
                        </div>

                        <!-- isi kartu: daftar jadwal (bisa di-scroll) -->
                        <div class="jadwal-body">
                            <div id="panelList">
                                <div class="text-gray-400 text-sm py-2">Memuat&hellip;</div>
                            </div>
                            <button type="button" class="sh-more hidden" id="btnLihatSemua"></button>
                        </div>
                    </div>

                    <!-- statistik periode yang tampil -->
                    <div class="rfo-panel sh-side">
                        <div class="sh-side-title mb-4"><i class="ti ti-chart-bar"></i> <span id="statJudul">Statistik Bulan Ini</span></div>
                        <div class="sh-mini-stats">
                            <div><span class="sh-mini-ic is-green"><i class="ti ti-circle-check"></i></span><span class="sh-mini-label">Terjadwal</span><span class="sh-mini-val"><b id="msHari">0</b> Hari</span></div>
                            <?php foreach ([1, 2, 3] as $k) : ?>
                                <div><span class="sh-mini-ic" style="--c:<?= esc($warna[$k], 'attr') ?>"><i class="ti ti-calendar-event"></i></span><span class="sh-mini-label"><?= esc($label[$k]) ?></span><span class="sh-mini-val"><b id="msShift<?= $k ?>">0</b> Hari</span></div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- ================= NOTE WARNA ================= -->
                    <div class="rfo-panel sh-side" id="noteWrap">
                        <div class="flex items-center justify-between mb-3">
                            <h6 class="sh-side-title"><i class="ti ti-palette"></i> Berikan Tanda</h6>
                            <button id="btnTambahNote" class="sh-add-note"><i class="ti ti-plus"></i> Tambah</button>
                        </div>
                        <div id="noteContainer" class="space-y-3"></div>
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
        const HARI_PENDEK = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
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
                // kotak diurutkan per shift (1, 2, 3, Off, HO) lalu nama
                eventOrder: 'shift,nama',
                // nama hari Indonesia (Min..Sab); tampilan Minggu/Hari ikut tanggalnya
                dayHeaderContent: function(arg) {
                    const nama = HARI_PENDEK[arg.date.getDay()];
                    return arg.view.type === 'dayGridMonth' ? nama : nama + ', ' + arg.date.getDate() + ' ' + BULAN[arg.date.getMonth()].substring(0, 3);
                },

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
                    document.getElementById('calTitle').textContent = judulPeriode(info.view);
                    tandaiTampilan(info.view.type);
                    highlightSelected(selectedDate);
                    renderStats();
                },
                eventsSet: function() {
                    renderPanel(selectedDate);
                    renderStats();
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
            // lebar kartu bisa berubah setelah render (gaya Tailwind CDN, sidebar dibuka/tutup):
            // ukur ulang kalender agar kolom Sabtu tidak terpotong
            if (window.ResizeObserver) {
                new ResizeObserver(() => Calendar.updateSize()).observe(document.querySelector('.sh-cal'));
            }
            feather.replace();
        });

        // header pindah bulan (tombol atas & bawah kalender)
        ['btnPrev', 'btnPrevBawah'].forEach(id => document.getElementById(id).addEventListener('click', () => Calendar.prev()));
        ['btnNext', 'btnNextBawah'].forEach(id => document.getElementById(id).addEventListener('click', () => Calendar.next()));

        // ---- tampilan Bulan / Minggu / Hari ----
        const NAMA_PERIODE = {
            dayGridMonth: 'Bulan',
            dayGridWeek: 'Minggu',
            dayGridDay: 'Hari'
        };

        document.querySelectorAll('.sh-views [data-view]').forEach(function(btn) {
            btn.addEventListener('click', function() {
                Calendar.changeView(btn.dataset.view, selectedDate);
            });
        });

        function tandaiTampilan(type) {
            document.querySelectorAll('.sh-views [data-view]').forEach(b => b.classList.toggle('active', b.dataset.view === type));
            document.querySelector('[data-periode="prev"]').textContent = NAMA_PERIODE[type] + ' Sebelumnya';
            document.querySelector('[data-periode="next"]').textContent = NAMA_PERIODE[type] + ' Berikutnya';
        }

        // judul di atas kalender sesuai tampilan
        function judulPeriode(view) {
            const a = view.currentStart;
            if (view.type === 'dayGridMonth') return BULAN[a.getMonth()] + ' ' + a.getFullYear();
            if (view.type === 'dayGridDay') return a.getDate() + ' ' + BULAN[a.getMonth()] + ' ' + a.getFullYear();
            const b = new Date(view.currentEnd.getTime() - 86400000);
            return a.getDate() + (a.getMonth() !== b.getMonth() ? ' ' + BULAN[a.getMonth()].substring(0, 3) : '') + ' – ' +
                b.getDate() + ' ' + BULAN[b.getMonth()].substring(0, 3) + ' ' + b.getFullYear();
        }

        // ---- ringkasan & statistik periode yang tampil (shift 1-3 = masuk; Off & HO tidak dihitung masuk) ----
        function renderStats() {
            if (!Calendar || !Calendar.view) return;
            const view = Calendar.view;
            const awal = ymd(view.currentStart);
            const akhir = ymd(view.currentEnd); // eksklusif
            const items = Calendar.getEvents()
                .map(ev => Object.assign({ tanggal: ev.startStr }, ev.extendedProps))
                .filter(it => it.tanggal >= awal && it.tanggal < akhir);

            const personel = new Set();
            const shiftAda = new Set();
            const perHari = {};
            const hariPerShift = { 1: new Set(), 2: new Set(), 3: new Set() };
            items.forEach(function(it) {
                personel.add(String(it.nama || '').trim().toLowerCase());
                const s = Number(it.shift);
                if (s >= 1 && s <= 3) {
                    shiftAda.add(s);
                    perHari[it.tanggal] = (perHari[it.tanggal] || 0) + 1;
                    hariPerShift[s].add(it.tanggal);
                }
            });
            const jumlahHarian = Object.values(perHari);
            const min = jumlahHarian.length ? Math.min(...jumlahHarian) : 0;
            const max = jumlahHarian.length ? Math.max(...jumlahHarian) : 0;

            document.getElementById('stPersonel').textContent = personel.size;
            document.getElementById('stShift').textContent = shiftAda.size;
            document.getElementById('stHari').textContent = jumlahHarian.length;
            document.getElementById('stRata').textContent = min === max ? max : min + '–' + max;
            document.getElementById('msHari').textContent = jumlahHarian.length;
            [1, 2, 3].forEach(s => document.getElementById('msShift' + s).textContent = hariPerShift[s].size);

            const kini = new Date();
            let judul = 'Statistik ' + judulPeriode(view);
            if (view.type === 'dayGridMonth' && view.currentStart.getMonth() === kini.getMonth() && view.currentStart.getFullYear() === kini.getFullYear()) judul = 'Statistik Bulan Ini';
            document.getElementById('statJudul').textContent = judul;
        }

        // ---- jam WIB di kartu ungu ----
        const FORMAT_WIB = new Intl.DateTimeFormat('en-GB', {
            timeZone: 'Asia/Jakarta',
            year: 'numeric',
            month: 'numeric',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hourCycle: 'h23'
        });

        function tickJam() {
            const bag = {};
            FORMAT_WIB.formatToParts(new Date()).forEach(p => bag[p.type] = p.value);
            const tgl = bag.year + '-' + String(bag.month).padStart(2, '0') + '-' + String(bag.day).padStart(2, '0');
            document.getElementById('jamTanggal').textContent = formatTanggal(tgl);
            document.getElementById('jamWaktu').textContent = bag.hour + ':' + bag.minute + ':' + bag.second;
        }
        tickJam();
        setInterval(tickJam, 1000);

        // Kemarin / Hari ini / Besok
        function selectDate(dateStr) {
            if (dateStr !== selectedDate) lihatSemua = false;
            selectedDate = dateStr;
            highlightSelected(dateStr);
            renderPanel(dateStr);
        }

        function goToDate(dateStr) {
            if (dateStr !== selectedDate) lihatSemua = false;
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

            document.getElementById('panelLabel').textContent = 'Hasil Pencarian';
            document.getElementById('panelCount').textContent = items.length + ' Jadwal';
            document.getElementById('btnLihatSemua').classList.add('hidden');
            const box = document.getElementById('panelList');
            if (!items.length) {
                box.innerHTML = '<div class="text-gray-400 text-sm py-2">Tidak ada jadwal yang cocok.</div>';
                return;
            }
            box.innerHTML = items.map(function(it) {
                return barisJadwal(it, 'onclick="bukaHasilCari(\'' + it.tanggal + '\')"', formatTanggal(it.tanggal));
            }).join('');
        }

        // Satu baris jadwal di kartu kanan (garis & titik warna shift, nama, keterangan)
        function barisJadwal(it, aksi, tanggal) {
            const warna = escapeHtml(it.warna || '#6d5dfc').replace(/"/g, '');
            return '' +
                '<div class="sh-item" style="--c:' + warna + '" ' + aksi + '>' +
                '<span class="sh-item-dot"></span>' +
                '<div class="min-w-0">' +
                (tanggal ? '<div class="jadwal-hasil-tgl">' + tanggal + '</div>' : '') +
                '<div class="sh-item-name">' + (SHIFT_LABEL[it.shift] || 'Shift') + ' – ' + escapeHtml(it.nama) + '</div>' +
                (it.keterangan ? '<div class="sh-item-sub">' + escapeHtml(it.keterangan) + '</div>' : '') +
                '</div>' +
                '</div>';
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
            document.getElementById('panelLabel').textContent = dateStr === todayStr() ? 'Jadwal Shift Hari Ini' : 'Jadwal Shift';
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

            // jumlah personel masuk (shift 1-3) di tanggal ini
            const masuk = new Set(items.filter(it => it.shift >= 1 && it.shift <= 3).map(it => String(it.nama).trim().toLowerCase()));
            document.getElementById('panelCount').textContent = masuk.size + ' Personel';

            const box = document.getElementById('panelList');
            const tombol = document.getElementById('btnLihatSemua');
            if (!items.length) {
                box.innerHTML = '<div class="text-gray-400 text-sm py-2">Belum ada jadwal untuk tanggal ini.</div>';
                tombol.classList.add('hidden');
                return;
            }
            // tampil 5 dulu; sisanya lewat tombol "Lihat Semua"
            const tampil = lihatSemua ? items : items.slice(0, BATAS_LIST);
            box.innerHTML = tampil.map(function(it) {
                const payload = JSON.stringify(it).replace(/'/g, "&#39;");
                return barisJadwal(it, "onclick='openForm(" + payload + ")'");
            }).join('');
            tombol.classList.toggle('hidden', items.length <= BATAS_LIST);
            tombol.innerHTML = lihatSemua ?
                'Tampilkan Lebih Sedikit <i class="ti ti-chevron-up"></i>' :
                'Lihat Semua (' + items.length + ') <i class="ti ti-chevron-down"></i>';
        }

        const BATAS_LIST = 5;
        let lihatSemua = false;
        document.getElementById('btnLihatSemua').addEventListener('click', function() {
            lihatSemua = !lihatSemua;
            renderPanel(selectedDate);
        });

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