<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('store.png') ?>">
    <title>Shift Handover</title>
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

        /* ===== INVOICE-STYLE TABLE ===== */
        table.ho-table {
            width: 100% !important;
            border-collapse: collapse;
        }

        table.ho-table thead th {
            background: #f7f9fb;
            color: #6b7785;
            font-weight: 500;
            font-size: 13px;
            text-align: left;
            padding: 14px 16px;
            border-top: 1px solid #edf0f3;
            border-bottom: 1px solid #edf0f3;
            white-space: nowrap;
        }

        .dark table.ho-table thead th {
            background: #2b3543;
            color: #9fb0c2;
            border-color: #37404c;
        }

        /* Kolom PIC & ringkasan boleh turun baris (kelas nowrap DataTables menimpanya) */
        table.ho-table tbody td.whitespace-normal {
            white-space: normal !important;
        }

        table.ho-table tbody td.whitespace-pre-line {
            white-space: pre-line !important;
        }

        /* Link "Selengkapnya" di kolom teks panjang */
        .ringkasan-more {
            color: #04a9f5;
            font-weight: 600;
            font-size: 12px;
            white-space: nowrap;
        }

        .ringkasan-more:hover {
            text-decoration: underline;
        }

        table.ho-table tbody td {
            padding: 16px;
            font-size: 14px;
            color: #3b4754;
            border-bottom: 1px solid #f0f2f5;
            vertical-align: middle;
            white-space: nowrap;
        }

        .dark table.ho-table tbody td {
            color: #bfc8d6;
            border-color: #37404c;
        }

        table.ho-table tbody tr:hover {
            background: #fafbfc;
        }

        .dark table.ho-table tbody tr:hover {
            background: rgba(255, 255, 255, .03);
        }

        table.ho-table tbody td.col-bold {
            font-weight: 600;
            color: #2b3540;
        }

        .dark table.ho-table tbody td.col-bold {
            color: #e7eaf0;
        }

        /* status badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
        }

        .badge-paid {
            background: #e7f8f1;
            color: #1aae6f;
        }

        .badge-pending {
            background: #fdf3e3;
            color: #d89a16;
        }

        .badge-due {
            background: #ffd2dc;
            color: #ff0000;
        }

        .badge-service {
            background: #e3f2fd;
            color: #1976d2;
        }

        /* ===== LENGTH (Show) DROPDOWN ===== */
        .dataTables_length {
            font-size: 0;
        }

        .dataTables_length select {
            font-size: 13px;
            border: 1px solid #e3e8ee;
            border-radius: 8px;
            padding: 9px 32px 9px 14px;
            color: #3b4754;
            outline: none;
            background: #fff;
            min-width: 130px;
            cursor: pointer;
        }

        .dark .dataTables_length select {
            background: #263240;
            color: #bfc8d6;
            border-color: #37404c;
        }

        .dataTables_length select:focus {
            border-color: #04a9f5;
        }

        .dataTables_filter {
            display: none;
        }

        .custom-search {
            position: relative;
            width: 240px;
            max-width: 100%;
        }

        .custom-search input {
            width: 100%;
            border: 1px solid #e3e8ee;
            border-radius: 8px;
            padding: 9px 44px 9px 14px;
            font-size: 13px;
            outline: none;
            color: #3b4754;
            background: #fff;
        }

        .dark .custom-search input {
            background: #263240;
            color: #bfc8d6;
            border-color: #37404c;
        }

        .custom-search input:focus {
            border-color: #04a9f5;
        }

        .custom-search .go-btn {
            position: absolute;
            right: 6px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            color: #8a95a1;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 4px 6px;
        }

        .custom-search .go-btn:hover {
            color: #04a9f5;
        }

        /* ===== PAGINATION ===== */
        .dataTables_paginate {
            font-size: 13px;
            margin-top: 1rem;
        }

        .dataTables_paginate .paginate_button {
            padding: 5px 11px !important;
            margin: 0 3px !important;
            border-radius: 6px !important;
            border: none !important;
            color: #5b6b7f !important;
            background: transparent !important;
        }

        .dataTables_paginate .paginate_button.current {
            background: #04a9f5 !important;
            color: #fff !important;
        }

        .dataTables_paginate .paginate_button:hover {
            background: #f0f2f5 !important;
            color: #3b4754 !important;
        }

        .dataTables_paginate .paginate_button.current:hover {
            background: #0396e2 !important;
            color: #fff !important;
        }

        .dataTables_info {
            font-size: 13px;
            color: #8a95a1;
            margin-top: 1rem;
        }

        /* ===== EXPORT DROPDOWN ===== */
        div.dt-buttons {
            display: inline-block;
        }

        button.dt-button.export-toggle {
            background: #fff !important;
            border: 1px solid #e3e8ee !important;
            color: #5b6b7f !important;
            border-radius: 8px !important;
            padding: 9px 16px !important;
            font-size: 13px !important;
            display: inline-flex !important;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            min-width: 130px;
            justify-content: center;
        }

        .dark button.dt-button.export-toggle {
            background: #263240 !important;
            color: #bfc8d6 !important;
            border-color: #37404c !important;
        }

        button.dt-button.export-toggle:hover {
            border-color: #04a9f5 !important;
            color: #04a9f5 !important;
        }

        div.dt-button-collection {
            background: #fff !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 8px !important;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .12) !important;
            padding: 6px !important;
            min-width: 170px;
        }

        .dark div.dt-button-collection {
            background: #263240 !important;
            border-color: #37404c !important;
        }

        div.dt-button-collection button.dt-button {
            display: flex !important;
            align-items: center;
            gap: 10px;
            width: 100%;
            text-align: left;
            background: transparent !important;
            border: none !important;
            color: #3b4754 !important;
            padding: 8px 12px !important;
            font-size: 14px !important;
            border-radius: 6px !important;
            margin: 0 !important;
        }

        .dark div.dt-button-collection button.dt-button {
            color: #ffffff !important;
        }

        div.dt-button-collection button.dt-button:hover {
            background: #f1f5f9 !important;
        }

        .dark div.dt-button-collection button.dt-button:hover {
            background: rgba(255, 255, 255, .05) !important;
        }

        .table-scroll {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-scroll table {
            min-width: 760px;
        }

        .brand-text {
            font-size: 18px;
        }
        /* ===== SHIFT HANDOVER: header & tab ===== */
        .ho-eyebrow {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: #1d6fd8;
        }

        .ho-title {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            line-height: 1.3;
        }

        .ho-sub {
            font-size: 12px;
            color: #6b7280;
            margin-top: 2px;
        }

        .ho-pic {
            font-weight: 700;
            text-transform: uppercase;
            color: #4b5563;
        }

        .ho-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            padding: 6px;
            background: #f8fafc;
            border: 1px solid #e5e9f0;
            border-radius: 12px;
        }

        .ho-tab {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border: 1px solid transparent;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            color: #4b5563;
            transition: all .15s ease;
        }

        .ho-tab:hover {
            background: #fff;
        }

        .ho-tab.active {
            background: #fff;
            border-color: #e5e9f0;
            color: #1e4fa3;
            box-shadow: 0 2px 8px rgba(15, 23, 42, .08);
        }

        .ho-count {
            min-width: 22px;
            padding: 2px 7px;
            border-radius: 6px;
            background: #eef1f5;
            color: #4b5563;
            font-size: 12px;
            text-align: center;
        }

        .ho-tab.active .ho-count {
            background: #e3edfb;
            color: #1e4fa3;
        }

        .dark .ho-title { color: #e5e7eb; }
        .dark .ho-pic { color: #cbd5e1; }
        .dark .ho-tabs { background: rgba(255, 255, 255, .03); border-color: rgba(255, 255, 255, .08); }
        .dark .ho-tab { color: #bfc8d6; }
        .dark .ho-tab:hover,
        .dark .ho-tab.active { background: #263240; border-color: rgba(255, 255, 255, .1); }
        .dark .ho-tab.active { color: #7cb4ff; }
        .dark .ho-count { background: rgba(255, 255, 255, .06); color: #bfc8d6; }
    </style>
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
                <li>
                    <a href="<?= site_url('RFO') ?>" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-file-alert"></i></span>
                        <span>RFO</span>
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('MDMaintenance') ?>" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-tool"></i></span>
                        <span>Maintenance</span>
                    </a>
                </li>
                <li class="hasmenu">
                    <a href="#" onclick="toggleSub(this);return false;" class="pc-link active flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-history"></i></span>
                        <span class="flex-1">Report NOC</span>
                        <i data-feather="chevron-right" class="arrow w-4 h-4 transition-transform"></i>
                    </a>
                    <ul class="submenu bg-black/20">
                        <li><a href="<?= site_url('HistoryReport') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Daily Report</a></li>
                        <li><a href="<?= site_url('MaintenanceReport') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Maintenance Report</a></li>
                    </ul>
                </li>

                <li class="px-6 py-3 text-[11px] uppercase tracking-wide text-[#5b6b7f] font-semibold">Informasi</li>
                <li>
                    <a href="<?= site_url('Profile') ?>" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-user-circle"></i></span>
                        <span>Profile</span>
                    </a>
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
                 <li>
                    <a href="<?= site_url('InventoryKantor') ?>"
                        class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-basket-down"></i></span>
                        <span>Inventory Kantor</span>
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

        <div class="p-6">
            <?php
            $hm = static fn ($t) => $t ? substr($t, 0, 5) : '-';
            $bulan = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
            $tglPendek = static function ($t) use ($bulan) {
                if (! $t) return '-';
                $ts = strtotime($t);
                return date('d', $ts) . ' ' . $bulan[(int) date('n', $ts)] . ' ' . date('Y', $ts);
            };
            // Kolom teks panjang: lebih dari satu paragraf -> paragraf pertama + "Selengkapnya" (isi lengkap di pop up)
            $tdTeks = static function ($teks, string $label, string $info = '', string $minW = 'min-w-[240px]') {
                $teks = trim((string) $teks);
                if ($teks === '') $teks = '-';
                $paragraf = array_values(array_filter(array_map('trim', preg_split('/\R+/', $teks)), 'strlen'));
                $isi = esc($teks);
                if (count($paragraf) > 1) {
                    $isi = esc($paragraf[0]) . '… <button type="button" class="ringkasan-more" onclick="openTeksModal(this)"'
                        . ' data-full="' . esc($teks, 'attr') . '" data-label="' . esc($label, 'attr') . '" data-info="' . esc($info, 'attr') . '">Selengkapnya</button>';
                }
                return '<td class="whitespace-pre-line ' . $minW . '" data-search="' . esc($teks, 'attr') . '" data-full="' . esc($teks, 'attr') . '">' . $isi . '</td>';
            };
            // Badge warna status & priority
            $statusBadge = [
                'Resolved'    => 'badge-paid',
                'On Progress' => 'badge-service',
                'Monitoring'  => 'badge-pending',
                'Down'        => 'badge-due',
                'Pending'     => 'badge-pending',
            ];
            $priorityBadge = [
                'High'   => 'badge-due',
                'Medium' => 'badge-pending',
                'Low'    => 'badge-service',
            ];

            $kirimBadge = [
                'Requested'  => 'badge-pending',
                'Shipped'    => 'badge-service',
                'In Transit' => 'badge-service',
                'Received'   => 'badge-paid',
                'Installed'  => 'badge-paid',
                'Closed'     => 'badge-paid',
            ];

            $mtBadge = [
                'Planned'     => 'badge-service',
                'On Progress' => 'badge-pending',
                'Done'        => 'badge-paid',
                'Pending'     => 'badge-due',
            ];

            $kategoriBadge = [
                'Info'      => 'badge-service',
                'Note'      => 'badge-pending',
                'Recurring' => 'badge-paid',
            ];
            $catatanStatusBadge = [
                'Open' => 'badge-pending',
                'Done' => 'badge-paid',
            ];

            // Form yang gagal validasi di server (dibuka lagi beserta tab-nya)
            $oldForm = old('_form');
            if (in_array($oldForm, ['gangguan', 'followup', 'pengiriman', 'maintenance', 'aktivasi', 'catatan'], true)) {
                $activeTab = $oldForm;
            }

            $tabs = [
                'gangguan'   => ['icon' => '🚨', 'label' => 'Gangguan', 'count' => count($gangguan)],
                'followup'   => ['icon' => '🔔', 'label' => 'Follow Up', 'count' => count($followUp)],
                'pengiriman' => ['icon' => '📦', 'label' => 'Pengiriman', 'count' => count($pengiriman)],
                'maintenance' => ['icon' => '🛠️', 'label' => 'Maintenance', 'count' => count($maintenance)],
                'aktivasi'    => ['icon' => '⚡', 'label' => 'Aktivasi', 'count' => count($aktivasi)],
                'catatan'     => ['icon' => '📌', 'label' => 'Catatan', 'count' => count($catatan)],
            ];
            ?>

            <!-- HEADER SHIFT HANDOVER -->
            <div class="flex flex-wrap items-start justify-between gap-4 mb-5">
                <div class="flex items-start gap-3">
                    <a href="<?= site_url('HistoryReport') ?>" title="Kembali" class="mt-5 w-9 h-9 rounded-lg flex items-center justify-center hover:bg-gray-100 dark:hover:bg-white/5">
                        <i class="ti ti-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <div class="ho-eyebrow">Shift Handover</div>
                        <h4 class="ho-title"><?= esc($report['shift']); ?> · <?= $tglPendek($report['tanggal']); ?></h4>
                        <div class="ho-sub">
                            <span class="ho-pic"><?= esc($report['pic_shift']); ?></span>
                            · <?= $hm($report['jam_mulai']); ?>–<?= $hm($report['jam_selesai']); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB -->
            <div class="ho-tabs mb-6" role="tablist">
                <?php foreach ($tabs as $key => $t) : ?>
                    <button type="button" role="tab" data-tab="<?= $key ?>" onclick="switchTab('<?= $key ?>')"
                        class="ho-tab <?= $activeTab === $key ? 'active' : '' ?>">
                        <span><?= $t['icon'] ?></span>
                        <span><?= $t['label'] ?></span>
                        <span class="ho-count"><?= $t['count'] ?></span>
                    </button>
                <?php endforeach; ?>
            </div>

            <!-- ====== PANEL GANGGUAN ====== -->
            <div class="ho-panel <?= $activeTab === 'gangguan' ? '' : 'hidden' ?>" data-panel="gangguan">
                <div class="card">
                    <div class="card-body">

                        <!-- TOOLBAR -->
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                            <div class="length-area"></div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <div class="custom-search">
                                    <input type="text" class="search-input" placeholder="search..." />
                                    <button class="go-btn" type="button"></button>
                                </div>
                                <div class="export-area"></div>
                                <button type="button" onclick="openGangguanModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
                                    <i class="ti ti-plus"></i> Tambah
                                </button>
                            </div>
                        </div>

                        <!-- TABLE -->
                        <div class="table-scroll">
                            <table id="gangguanTable" class="ho-table display nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Aksi</th>
                                        <th>Group</th>
                                        <th>Sub Group</th>
                                        <th>Customer / Site</th>
                                        <th>CID / Ticket</th>
                                        <th>Status</th>
                                        <th>Priority</th>
                                        <th>Gangguan / Problem</th>
                                        <th>Tindakan</th>
                                        <th>Next Action / Handover</th>
                                        <th>PIC</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($gangguan as $row) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td>
                                                <button type="button"
                                                    data-row="<?= esc(json_encode($row), 'attr') ?>"
                                                    onclick="openViewModal('gangguan', JSON.parse(this.dataset.row))"
                                                    title="View"
                                                    class="btn btn-sm">
                                                        <i class="ti ti-eye"></i>
                                                </button>

                                                <button type="button"
                                                    data-row="<?= esc(json_encode($row), 'attr') ?>"
                                                    onclick="openGangguanModal(JSON.parse(this.dataset.row))"
                                                    title="Edit"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="ti ti-edit"></i>
                                                </button>

                                                <button type="button"
                                                    onclick="confirmDelete('<?= site_url('HistoryReport/gangguan/delete/' . $row['id']) ?>')"
                                                    title="Hapus"
                                                    class="btn btn-sm btn-danger">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </td>
                                            <td><?= esc($row['grup']); ?></td>
                                            <td><?= esc($row['sub_grup'] ?: '-'); ?></td>
                                            <td class="whitespace-normal min-w-[160px]"><?= esc($row['customer_site']); ?></td>
                                            <td><?= esc($row['cid_ticket'] ?: '-'); ?></td>
                                            <td><span class="badge <?= $statusBadge[$row['status']] ?? 'badge-service' ?>"><?= esc($row['status']); ?></span></td>
                                            <td><span class="badge <?= $priorityBadge[$row['priority']] ?? 'badge-service' ?>"><?= esc($row['priority']); ?></span></td>
                                            <?= $tdTeks($row['gangguan'], 'Gangguan / Problem', $row['customer_site']); ?>
                                            <?= $tdTeks($row['tindakan'], 'Tindakan', $row['customer_site']); ?>
                                            <?= $tdTeks($row['next_action'], 'Next Action / Handover', $row['customer_site']); ?>
                                            <td class="whitespace-normal min-w-[140px]"><?= esc($row['pic']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ====== PANEL FOLLOW UP ====== -->
            <div class="ho-panel <?= $activeTab === 'followup' ? '' : 'hidden' ?>" data-panel="followup">
                <div class="card">
                    <div class="card-body">

                        <!-- TOOLBAR -->
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                            <div class="length-area"></div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <div class="custom-search">
                                    <input type="text" class="search-input" placeholder="search..." />
                                    <button class="go-btn" type="button"></button>
                                </div>
                                <div class="export-area"></div>
                                <button type="button" onclick="openFollowUpModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
                                    <i class="ti ti-plus"></i> Tambah
                                </button>
                            </div>
                        </div>

                        <!-- TABLE -->
                        <div class="table-scroll">
                            <table id="followUpTable" class="ho-table display nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Aksi</th>
                                        <th>Group</th>
                                        <th>Customer / Site</th>
                                        <th>Priority</th>
                                        <th>Due Date</th>
                                        <th>PIC</th>
                                        <th>Issue</th>
                                        <th>Action / Next Step</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($followUp as $row) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td>
                                                <button type="button"
                                                    data-row="<?= esc(json_encode($row), 'attr') ?>"
                                                    onclick="openViewModal('followup', JSON.parse(this.dataset.row))"
                                                    title="View"
                                                    class="btn btn-sm">
                                                        <i class="ti ti-eye"></i>
                                                </button>

                                                <button type="button"
                                                    data-row="<?= esc(json_encode($row), 'attr') ?>"
                                                    onclick="openFollowUpModal(JSON.parse(this.dataset.row))"
                                                    title="Edit"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="ti ti-edit"></i>
                                                </button>

                                                <button type="button"
                                                    onclick="confirmDelete('<?= site_url('HistoryReport/followup/delete/' . $row['id']) ?>')"
                                                    title="Hapus"
                                                    class="btn btn-sm btn-danger">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </td>
                                            <td><?= esc($row['grup']); ?></td>
                                            <td class="whitespace-normal min-w-[160px]"><?= esc($row['customer_site']); ?></td>
                                            <td><span class="badge <?= $priorityBadge[$row['priority']] ?? 'badge-service' ?>"><?= esc($row['priority']); ?></span></td>
                                            <td data-order="<?= esc($row['due_date'] ?? '') ?>"><?= $row['due_date'] ? date('d-m-Y', strtotime($row['due_date'])) : '-'; ?></td>
                                            <td class="whitespace-normal min-w-[140px]"><?= esc($row['pic']); ?></td>
                                            <?= $tdTeks($row['issue'], 'Issue', $row['customer_site']); ?>
                                            <?= $tdTeks($row['action'], 'Action / Next Step', $row['customer_site']); ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ====== PANEL PENGIRIMAN ====== -->
            <div class="ho-panel <?= $activeTab === 'pengiriman' ? '' : 'hidden' ?>" data-panel="pengiriman">
                <div class="card">
                    <div class="card-body">

                        <!-- TOOLBAR -->
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                            <div class="length-area"></div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <div class="custom-search">
                                    <input type="text" class="search-input" placeholder="search..." />
                                    <button class="go-btn" type="button"></button>
                                </div>
                                <div class="export-area"></div>
                                <button type="button" onclick="openPengirimanModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
                                    <i class="ti ti-plus"></i> Tambah
                                </button>
                            </div>
                        </div>

                        <!-- TABLE -->
                        <div class="table-scroll">
                            <table id="pengirimanTable" class="ho-table display nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Aksi</th>
                                        <th>Group</th>
                                        <th>Customer</th>
                                        <th>Device</th>
                                        <th>Tracking / Resi</th>
                                        <th>Status</th>
                                        <th>ETA</th>
                                        <th>PIC</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($pengiriman as $row) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td>
                                                <button type="button"
                                                    data-row="<?= esc(json_encode($row), 'attr') ?>"
                                                    onclick="openViewModal('pengiriman', JSON.parse(this.dataset.row))"
                                                    title="View"
                                                    class="btn btn-sm">
                                                        <i class="ti ti-eye"></i>
                                                </button>

                                                <button type="button"
                                                    data-row="<?= esc(json_encode($row), 'attr') ?>"
                                                    onclick="openPengirimanModal(JSON.parse(this.dataset.row))"
                                                    title="Edit"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="ti ti-edit"></i>
                                                </button>

                                                <button type="button"
                                                    onclick="confirmDelete('<?= site_url('HistoryReport/pengiriman/delete/' . $row['id']) ?>')"
                                                    title="Hapus"
                                                    class="btn btn-sm btn-danger">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </td>
                                            <td><?= esc($row['grup']); ?></td>
                                            <td class="whitespace-normal min-w-[160px]"><?= esc($row['customer']); ?></td>
                                            <td class="whitespace-normal min-w-[160px]"><?= esc($row['device']); ?></td>
                                            <td><?= esc($row['tracking_resi'] ?: '-'); ?></td>
                                            <td><span class="badge <?= $kirimBadge[$row['status']] ?? 'badge-service' ?>"><?= esc($row['status']); ?></span></td>
                                            <td data-order="<?= esc($row['eta'] ?? '') ?>"><?= $row['eta'] ? date('d-m-Y', strtotime($row['eta'])) : '-'; ?></td>
                                            <td class="whitespace-normal min-w-[140px]"><?= esc($row['pic']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ====== PANEL MAINTENANCE ====== -->
            <div class="ho-panel <?= $activeTab === 'maintenance' ? '' : 'hidden' ?>" data-panel="maintenance">
                <div class="card">
                    <div class="card-body">

                        <!-- TOOLBAR -->
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                            <div class="length-area"></div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <div class="custom-search">
                                    <input type="text" class="search-input" placeholder="search..." />
                                    <button class="go-btn" type="button"></button>
                                </div>
                                <div class="export-area"></div>
                                <button type="button" onclick="openMaintenanceModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
                                    <i class="ti ti-plus"></i> Tambah
                                </button>
                            </div>
                        </div>

                        <!-- TABLE -->
                        <div class="table-scroll">
                            <table id="maintenanceTable" class="ho-table display nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Aksi</th>
                                        <th>Group</th>
                                        <th>Site</th>
                                        <th>Equipment</th>
                                        <th>Schedule</th>
                                        <th>Status</th>
                                        <th>PIC</th>
                                        <th>Issue</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($maintenance as $row) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td>
                                                <button type="button"
                                                    data-row="<?= esc(json_encode($row), 'attr') ?>"
                                                    onclick="openViewModal('maintenance', JSON.parse(this.dataset.row))"
                                                    title="View"
                                                    class="btn btn-sm">
                                                        <i class="ti ti-eye"></i>
                                                </button>

                                                <button type="button"
                                                    data-row="<?= esc(json_encode($row), 'attr') ?>"
                                                    onclick="openMaintenanceModal(JSON.parse(this.dataset.row))"
                                                    title="Edit"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="ti ti-edit"></i>
                                                </button>

                                                <button type="button"
                                                    onclick="confirmDelete('<?= site_url('HistoryReport/maintenance/delete/' . $row['id']) ?>')"
                                                    title="Hapus"
                                                    class="btn btn-sm btn-danger">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </td>
                                            <td><?= esc($row['grup']); ?></td>
                                            <td class="whitespace-normal min-w-[160px]"><?= esc($row['site']); ?></td>
                                            <td class="whitespace-normal min-w-[160px]"><?= esc($row['equipment']); ?></td>
                                            <td data-order="<?= esc($row['schedule']) ?>"><?= date('d-m-Y', strtotime($row['schedule'])); ?></td>
                                            <td><span class="badge <?= $mtBadge[$row['status']] ?? 'badge-service' ?>"><?= esc($row['status']); ?></span></td>
                                            <td class="whitespace-normal min-w-[140px]"><?= esc($row['pic']); ?></td>
                                            <?= $tdTeks($row['issue'], 'Issue', $row['site'] . ' · ' . $row['equipment']); ?>
                                            <?= $tdTeks($row['action'], 'Action', $row['site'] . ' · ' . $row['equipment']); ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ====== PANEL AKTIVASI ====== -->
            <div class="ho-panel <?= $activeTab === 'aktivasi' ? '' : 'hidden' ?>" data-panel="aktivasi">
                <div class="card">
                    <div class="card-body">

                        <!-- TOOLBAR -->
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                            <div class="length-area"></div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <div class="custom-search">
                                    <input type="text" class="search-input" placeholder="search..." />
                                    <button class="go-btn" type="button"></button>
                                </div>
                                <div class="export-area"></div>
                                <button type="button" onclick="openAktivasiModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
                                    <i class="ti ti-plus"></i> Tambah
                                </button>
                            </div>
                        </div>

                        <!-- TABLE -->
                        <div class="table-scroll">
                            <table id="aktivasiTable" class="ho-table display nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Aksi</th>
                                        <th>ID Pelanggan</th>
                                        <th>Nama Pelanggan</th>
                                        <th>Kapasitas (Mbps)</th>
                                        <th>SN</th>
                                        <th>Tanggal Aktivasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($aktivasi as $row) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td>
                                                <button type="button"
                                                    data-row="<?= esc(json_encode($row), 'attr') ?>"
                                                    onclick="openViewModal('aktivasi', JSON.parse(this.dataset.row))"
                                                    title="View"
                                                    class="btn btn-sm">
                                                        <i class="ti ti-eye"></i>
                                                </button>

                                                <button type="button"
                                                    data-row="<?= esc(json_encode($row), 'attr') ?>"
                                                    onclick="openAktivasiModal(JSON.parse(this.dataset.row))"
                                                    title="Edit"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="ti ti-edit"></i>
                                                </button>

                                                <button type="button"
                                                    onclick="confirmDelete('<?= site_url('HistoryReport/aktivasi/delete/' . $row['id']) ?>')"
                                                    title="Hapus"
                                                    class="btn btn-sm btn-danger">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </td>
                                            <td><?= esc($row['id_pelanggan']); ?></td>
                                            <td class="whitespace-normal min-w-[160px]"><?= esc($row['nama_pelanggan']); ?></td>
                                            <td><?= esc($row['kapasitas_mbps']); ?></td>
                                            <td class="whitespace-normal min-w-[140px]"><?= esc($row['sn']); ?></td>
                                            <td data-order="<?= esc($row['tanggal_aktivasi'] ?? '') ?>"><?= $row['tanggal_aktivasi'] ? date('d-m-Y', strtotime($row['tanggal_aktivasi'])) : '-'; ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ====== PANEL CATATAN ====== -->
            <div class="ho-panel <?= $activeTab === 'catatan' ? '' : 'hidden' ?>" data-panel="catatan">
                <div class="card">
                    <div class="card-body">

                        <!-- TOOLBAR -->
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                            <div class="length-area"></div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <div class="custom-search">
                                    <input type="text" class="search-input" placeholder="search..." />
                                    <button class="go-btn" type="button"></button>
                                </div>
                                <div class="export-area"></div>
                                <button type="button" onclick="openCatatanModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
                                    <i class="ti ti-plus"></i> Tambah
                                </button>
                            </div>
                        </div>

                        <!-- TABLE -->
                        <div class="table-scroll">
                            <table id="catatanTable" class="ho-table display nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Aksi</th>
                                        <th>Group</th>
                                        <th>Kategori</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Judul</th>
                                        <th>Catatan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $no = 1; ?>
                                    <?php foreach ($catatan as $row) : ?>
                                        <?php $poin = \App\Models\CatatanModel::poin($row['catatan']); ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td>
                                                <button type="button"
                                                    data-row="<?= esc(json_encode($row + ['poin' => $poin]), 'attr') ?>"
                                                    onclick="openViewModal('catatan', JSON.parse(this.dataset.row))"
                                                    title="View"
                                                    class="btn btn-sm">
                                                        <i class="ti ti-eye"></i>
                                                </button>

                                                <button type="button"
                                                    data-row="<?= esc(json_encode($row + ['poin' => $poin]), 'attr') ?>"
                                                    onclick="openCatatanModal(JSON.parse(this.dataset.row))"
                                                    title="Edit"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="ti ti-edit"></i>
                                                </button>

                                                <button type="button"
                                                    onclick="confirmDelete('<?= site_url('HistoryReport/catatan/delete/' . $row['id']) ?>')"
                                                    title="Hapus"
                                                    class="btn btn-sm btn-danger">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </td>
                                            <td><?= esc($row['grup']); ?></td>
                                            <td><span class="badge <?= $kategoriBadge[$row['kategori']] ?? 'badge-service' ?>"><?= esc($row['kategori']); ?></span></td>
                                            <td><span class="badge <?= $priorityBadge[$row['priority']] ?? 'badge-service' ?>"><?= esc($row['priority']); ?></span></td>
                                            <td><span class="badge <?= $catatanStatusBadge[$row['status']] ?? 'badge-service' ?>"><?= esc($row['status']); ?></span></td>
                                            <td class="whitespace-normal min-w-[180px] font-medium"><?= esc($row['judul']); ?></td>
                                            <!-- poin catatan bernomor, satu per baris -->
                                            <?php
                                            $teksPoin = '';
                                            foreach ($poin as $i => $p) {
                                                $teksPoin .= ($i ? "\n" : '') . ($i + 1) . '. ' . $p;
                                            }
                                            ?>
                                            <?= $tdTeks($teksPoin, 'Catatan', $row['judul'], 'min-w-[280px]'); ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ====== POP UP TAMBAH / EDIT CATATAN ====== -->
    <div id="catatanModal" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-3xl p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold" id="catatanModalTitle">Tambah Catatan</h3>
                <button type="button" onclick="closeModal('catatanModal')"><i class="ti ti-x text-2xl"></i></button>
            </div>

            <form action="<?= site_url('HistoryReport/catatan/save') ?>" method="POST" id="catatanForm">
                <?= csrf_field() ?>
                <input type="hidden" name="_form" value="catatan">
                <input type="hidden" name="id" id="c_id">
                <input type="hidden" name="report_id" value="<?= esc($report['id']) ?>">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Group <span class="text-red-500">*</span></label>
                        <select name="grup" id="c_grup" required class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih Group --</option>
                            <?php foreach ($catatanGrupOptions as $opt) : ?>
                                <option value="<?= esc($opt, 'attr') ?>"><?= esc($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Kategori <span class="text-red-500">*</span></label>
                        <select name="kategori" id="c_kategori" required class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($catatanKategoriOptions as $opt) : ?>
                                <option value="<?= esc($opt, 'attr') ?>"><?= esc($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Priority <span class="text-red-500">*</span></label>
                        <select name="priority" id="c_priority" required class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih Priority --</option>
                            <?php foreach ($priorityOptions as $opt) : ?>
                                <option value="<?= esc($opt, 'attr') ?>"><?= esc($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="c_status" required class="w-full border rounded-lg p-3">
                            <?php foreach ($catatanStatusOptions as $opt) : ?>
                                <option value="<?= esc($opt, 'attr') ?>"><?= esc($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium">Judul <span class="text-red-500">*</span></label>
                        <input type="text" name="judul" id="c_judul" maxlength="255" required placeholder="Masukan Judul" class="w-full border rounded-lg p-3">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium">Catatan <span class="text-red-500">*</span></label>
                        <!-- baris catatan ditambah lewat tombol "+ Tambah Catatan" -->
                        <div id="c_list" class="space-y-2"></div>
                        <button type="button" onclick="addCatatanRow('', true)" class="mt-2 px-3 py-2 text-sm text-blue-600 border border-blue-200 hover:bg-blue-50 rounded-lg flex items-center gap-1">
                            <i class="ti ti-plus"></i> Tambah Catatan
                        </button>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal('catatanModal')" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Batal</button>
                    <button type="submit" id="catatanSubmit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ====== POP UP TAMBAH / EDIT MAINTENANCE ====== -->
    <div id="maintenanceModal" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-3xl p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold" id="maintenanceModalTitle">Tambah Maintenance</h3>
                <button type="button" onclick="closeModal('maintenanceModal')"><i class="ti ti-x text-2xl"></i></button>
            </div>

            <form action="<?= site_url('HistoryReport/maintenance/save') ?>" method="POST" id="maintenanceForm">
                <?= csrf_field() ?>
                <input type="hidden" name="_form" value="maintenance">
                <input type="hidden" name="id" id="m_id">
                <input type="hidden" name="report_id" value="<?= esc($report['id']) ?>">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Group <span class="text-red-500">*</span></label>
                        <select name="grup" id="m_grup" required class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih Group --</option>
                            <?php foreach ($grupOptions as $opt) : ?>
                                <option value="<?= esc($opt, 'attr') ?>"><?= esc($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Site <span class="text-red-500">*</span></label>
                        <input type="text" name="site" id="m_site" maxlength="255" required placeholder="Masukan Site" class="w-full border rounded-lg p-3">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Equipment <span class="text-red-500">*</span></label>
                        <input type="text" name="equipment" id="m_equipment" maxlength="255" required placeholder="Masukan Equipment" class="w-full border rounded-lg p-3">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Schedule <span class="text-red-500">*</span></label>
                        <input type="date" name="schedule" id="m_schedule" required class="w-full border rounded-lg p-3">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="m_status" required class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih Status --</option>
                            <?php foreach ($maintenanceStatusOptions as $opt) : ?>
                                <option value="<?= esc($opt, 'attr') ?>"><?= esc($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium">PIC <span class="text-red-500">*</span></label>
                        <input type="text" name="pic" id="m_pic" maxlength="255" required placeholder="Masukan Nama PIC" class="w-full border rounded-lg p-3">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium">Issue</label>
                        <textarea name="issue" id="m_issue" rows="3" placeholder="Masukan issue" class="w-full border rounded-lg p-3"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium">Action</label>
                        <textarea name="action" id="m_action" rows="3" placeholder="Masukan action" class="w-full border rounded-lg p-3"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal('maintenanceModal')" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Batal</button>
                    <button type="submit" id="maintenanceSubmit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ====== POP UP TAMBAH / EDIT PENGIRIMAN ====== -->
    <div id="pengirimanModal" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-3xl p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold" id="pengirimanModalTitle">Tambah Pengiriman</h3>
                <button type="button" onclick="closeModal('pengirimanModal')"><i class="ti ti-x text-2xl"></i></button>
            </div>

            <form action="<?= site_url('HistoryReport/pengiriman/save') ?>" method="POST" id="pengirimanForm">
                <?= csrf_field() ?>
                <input type="hidden" name="_form" value="pengiriman">
                <input type="hidden" name="id" id="p_id">
                <input type="hidden" name="report_id" value="<?= esc($report['id']) ?>">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Group <span class="text-red-500">*</span></label>
                        <select name="grup" id="p_grup" required class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih Group --</option>
                            <?php foreach ($grupOptions as $opt) : ?>
                                <option value="<?= esc($opt, 'attr') ?>"><?= esc($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Customer <span class="text-red-500">*</span></label>
                        <input type="text" name="customer" id="p_customer" maxlength="255" required placeholder="Masukan Customer" class="w-full border rounded-lg p-3">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Device <span class="text-red-500">*</span></label>
                        <input type="text" name="device" id="p_device" maxlength="255" required placeholder="Masukan Device" class="w-full border rounded-lg p-3">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Tracking / Resi</label>
                        <input type="text" name="tracking_resi" id="p_tracking_resi" maxlength="255" placeholder="Masukan No. Tracking / Resi" class="w-full border rounded-lg p-3">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="p_status" required class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih Status --</option>
                            <?php foreach ($pengirimanStatusOptions as $opt) : ?>
                                <option value="<?= esc($opt, 'attr') ?>"><?= esc($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium">ETA</label>
                        <input type="date" name="eta" id="p_eta" class="w-full border rounded-lg p-3">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium">PIC <span class="text-red-500">*</span></label>
                        <input type="text" name="pic" id="p_pic" maxlength="255" required placeholder="Masukan Nama PIC" class="w-full border rounded-lg p-3">
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal('pengirimanModal')" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Batal</button>
                    <button type="submit" id="pengirimanSubmit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ====== POP UP TAMBAH / EDIT GANGGUAN ====== -->
    <div id="gangguanModal" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-3xl p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold" id="gangguanModalTitle">Tambah Gangguan</h3>
                <button type="button" onclick="closeModal('gangguanModal')"><i class="ti ti-x text-2xl"></i></button>
            </div>

            <form action="<?= site_url('HistoryReport/gangguan/save') ?>" method="POST" id="gangguanForm">
                <?= csrf_field() ?>
                <input type="hidden" name="_form" value="gangguan">
                <input type="hidden" name="id" id="g_id">
                <input type="hidden" name="report_id" value="<?= esc($report['id']) ?>">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Group <span class="text-red-500">*</span></label>
                        <select name="grup" id="g_grup" required class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih Group --</option>
                            <?php foreach ($grupOptions as $opt) : ?>
                                <option value="<?= esc($opt, 'attr') ?>"><?= esc($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Sub Group</label>
                        <input type="text" name="sub_grup" id="g_sub_grup" maxlength="255" placeholder="Masukan Sub Group" class="w-full border rounded-lg p-3">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Customer / Site <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_site" id="g_customer_site" maxlength="255" required placeholder="Masukan Customer / Site" class="w-full border rounded-lg p-3">
                    </div>
                    <div>
                        <label class="text-sm font-medium">CID / Ticket</label>
                        <input type="text" name="cid_ticket" id="g_cid_ticket" maxlength="255" placeholder="Masukan CID / Ticket" class="w-full border rounded-lg p-3">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="g_status" required class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih Status --</option>
                            <?php foreach ($statusOptions as $opt) : ?>
                                <option value="<?= esc($opt, 'attr') ?>"><?= esc($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Priority <span class="text-red-500">*</span></label>
                        <select name="priority" id="g_priority" required class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih Priority --</option>
                            <?php foreach ($priorityOptions as $opt) : ?>
                                <option value="<?= esc($opt, 'attr') ?>"><?= esc($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium">Gangguan / Problem <span class="text-red-500">*</span></label>
                        <textarea name="gangguan" id="g_gangguan" rows="3" required placeholder="Masukan gangguan / problem" class="w-full border rounded-lg p-3"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium">Tindakan</label>
                        <textarea name="tindakan" id="g_tindakan" rows="3" placeholder="Masukan tindakan yang sudah dilakukan" class="w-full border rounded-lg p-3"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium">Next Action / Handover</label>
                        <textarea name="next_action" id="g_next_action" rows="3" placeholder="Masukan next action / handover" class="w-full border rounded-lg p-3"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium">PIC <span class="text-red-500">*</span></label>
                        <input type="text" name="pic" id="g_pic" maxlength="255" required placeholder="Masukan Nama PIC" class="w-full border rounded-lg p-3">
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal('gangguanModal')" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Batal</button>
                    <button type="submit" id="gangguanSubmit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ====== POP UP TAMBAH / EDIT FOLLOW UP ====== -->
    <div id="followUpModal" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-3xl p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold" id="followUpModalTitle">Tambah Follow Up</h3>
                <button type="button" onclick="closeModal('followUpModal')"><i class="ti ti-x text-2xl"></i></button>
            </div>

            <form action="<?= site_url('HistoryReport/followup/save') ?>" method="POST" id="followUpForm">
                <?= csrf_field() ?>
                <input type="hidden" name="_form" value="followup">
                <input type="hidden" name="id" id="f_id">
                <input type="hidden" name="report_id" value="<?= esc($report['id']) ?>">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Group <span class="text-red-500">*</span></label>
                        <select name="grup" id="f_grup" required class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih Group --</option>
                            <?php foreach ($grupOptions as $opt) : ?>
                                <option value="<?= esc($opt, 'attr') ?>"><?= esc($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Customer / Site <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_site" id="f_customer_site" maxlength="255" required placeholder="Masukan Customer / Site" class="w-full border rounded-lg p-3">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Priority <span class="text-red-500">*</span></label>
                        <select name="priority" id="f_priority" required class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih Priority --</option>
                            <?php foreach ($priorityOptions as $opt) : ?>
                                <option value="<?= esc($opt, 'attr') ?>"><?= esc($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Due Date</label>
                        <input type="date" name="due_date" id="f_due_date" class="w-full border rounded-lg p-3">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium">PIC <span class="text-red-500">*</span></label>
                        <input type="text" name="pic" id="f_pic" maxlength="255" required placeholder="Masukan Nama PIC" class="w-full border rounded-lg p-3">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium">Issue <span class="text-red-500">*</span></label>
                        <textarea name="issue" id="f_issue" rows="3" required placeholder="Masukan issue" class="w-full border rounded-lg p-3"></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium">Action / Next Step</label>
                        <textarea name="action" id="f_action" rows="3" placeholder="Masukan action / next step" class="w-full border rounded-lg p-3"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal('followUpModal')" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Batal</button>
                    <button type="submit" id="followUpSubmit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ====== POP UP TAMBAH / EDIT AKTIVASI ====== -->
    <div id="aktivasiModal" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-3xl p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold" id="aktivasiModalTitle">Tambah Aktivasi</h3>
                <button type="button" onclick="closeModal('aktivasiModal')"><i class="ti ti-x text-2xl"></i></button>
            </div>

            <form action="<?= site_url('HistoryReport/aktivasi/save') ?>" method="POST" id="aktivasiForm">
                <?= csrf_field() ?>
                <input type="hidden" name="_form" value="aktivasi">
                <input type="hidden" name="id" id="a_id">
                <input type="hidden" name="report_id" value="<?= esc($report['id']) ?>">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">ID Pelanggan <span class="text-red-500">*</span></label>
                        <input type="text" name="id_pelanggan" id="a_id_pelanggan" maxlength="100" required placeholder="Masukan ID Pelanggan" class="w-full border rounded-lg p-3">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Nama Pelanggan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_pelanggan" id="a_nama_pelanggan" maxlength="255" required placeholder="Masukan Nama Pelanggan" class="w-full border rounded-lg p-3">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Kapasitas (Mbps) <span class="text-red-500">*</span></label>
                        <input type="text" name="kapasitas_mbps" id="a_kapasitas_mbps" maxlength="50" required placeholder="Contoh: 100" class="w-full border rounded-lg p-3">
                    </div>
                    <div>
                        <label class="text-sm font-medium">SN <span class="text-red-500">*</span></label>
                        <input type="text" name="sn" id="a_sn" maxlength="255" required placeholder="Masukan Serial Number" class="w-full border rounded-lg p-3">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium">Tanggal Aktivasi <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_aktivasi" id="a_tanggal_aktivasi" required class="w-full border rounded-lg p-3">
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeModal('aktivasiModal')" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Batal</button>
                    <button type="submit" id="aktivasiSubmit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ====== POP UP VIEW (Gangguan / Follow Up / Pengiriman / Maintenance / Aktivasi / Catatan) ====== -->
    <div id="viewModal" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4" onclick="if (event.target === this) closeModal('viewModal')">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold" id="viewModalTitle">View</h3>
                <button type="button" onclick="closeModal('viewModal')"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <dl id="viewModalBody" class="space-y-3"></dl>
            <div class="flex justify-end mt-6">
                <button type="button" onclick="closeModal('viewModal')" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Tutup</button>
            </div>
        </div>
    </div>

    <!-- ====== POP UP TEKS LENGKAP (Selengkapnya) ====== -->
    <div id="teksModal" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4" onclick="if (event.target === this) closeModal('teksModal')">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="text-xl font-bold" id="tk_label"></h3>
                    <div class="text-sm text-gray-500 mt-1" id="tk_info"></div>
                </div>
                <button type="button" onclick="closeModal('teksModal')"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <div class="text-sm leading-relaxed whitespace-pre-line break-words" id="tk_text"></div>
            <div class="flex justify-end mt-6">
                <button type="button" onclick="closeModal('teksModal')" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        // Selengkapnya: tampilkan teks lengkap kolom di pop up
        function openTeksModal(btn) {
            document.getElementById('tk_label').textContent = btn.dataset.label || '';
            document.getElementById('tk_info').textContent = btn.dataset.info || '';
            document.getElementById('tk_text').textContent = btn.dataset.full || '-';
            document.getElementById('teksModal').classList.remove('hidden');
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModal('teksModal');
        });
    </script>

    <script>
        // ====== TAB ======
        function switchTab(key) {
            document.querySelectorAll('.ho-tab').forEach(t => t.classList.toggle('active', t.dataset.tab === key));
            document.querySelectorAll('.ho-panel').forEach(p => p.classList.toggle('hidden', p.dataset.panel !== key));
            // simpan tab di URL agar tetap terbuka saat halaman dimuat ulang
            const url = new URL(window.location);
            url.searchParams.set('tab', key);
            history.replaceState(null, '', url);
            // DataTables perlu hitung ulang lebar kolom setelah panel tampil
            if (window.jQuery && $.fn.dataTable) $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust();
        }

        // ====== POP UP VIEW (hanya baca) ======
        const VIEW_FIELDS = {
            gangguan: {
                label: 'Gangguan',
                fields: [['grup', 'Group'], ['sub_grup', 'Sub Group'], ['customer_site', 'Customer / Site'], ['cid_ticket', 'CID / Ticket'], ['status', 'Status'], ['priority', 'Priority'], ['gangguan', 'Gangguan / Problem'], ['tindakan', 'Tindakan'], ['next_action', 'Next Action / Handover'], ['pic', 'PIC']],
            },
            followup: {
                label: 'Follow Up',
                fields: [['grup', 'Group'], ['customer_site', 'Customer / Site'], ['priority', 'Priority'], ['due_date', 'Due Date'], ['pic', 'PIC'], ['issue', 'Issue'], ['action', 'Action / Next Step']],
            },
            pengiriman: {
                label: 'Pengiriman',
                fields: [['grup', 'Group'], ['customer', 'Customer'], ['device', 'Device'], ['tracking_resi', 'Tracking / Resi'], ['status', 'Status'], ['eta', 'ETA'], ['pic', 'PIC']],
            },
            maintenance: {
                label: 'Maintenance',
                fields: [['grup', 'Group'], ['site', 'Site'], ['equipment', 'Equipment'], ['schedule', 'Schedule'], ['status', 'Status'], ['pic', 'PIC'], ['issue', 'Issue'], ['action', 'Action']],
            },
            aktivasi: {
                label: 'Aktivasi',
                fields: [['id_pelanggan', 'ID Pelanggan'], ['nama_pelanggan', 'Nama Pelanggan'], ['kapasitas_mbps', 'Kapasitas (Mbps)'], ['sn', 'SN'], ['tanggal_aktivasi', 'Tanggal Aktivasi']],
            },
            catatan: {
                label: 'Catatan',
                fields: [['grup', 'Group'], ['kategori', 'Kategori'], ['priority', 'Priority'], ['status', 'Status'], ['judul', 'Judul'], ['poin', 'Catatan']],
            },
        };

        function openViewModal(jenis, d) {
            const cfg = VIEW_FIELDS[jenis];
            document.getElementById('viewModalTitle').textContent = 'View ' + cfg.label;
            const body = document.getElementById('viewModalBody');
            body.innerHTML = '';
            cfg.fields.forEach(([key, label]) => {
                let v = d[key];
                if (Array.isArray(v)) v = v.map((p, i) => (i + 1) + '. ' + p).join('\n'); // poin catatan
                const wrap = document.createElement('div');
                const dt = document.createElement('dt');
                dt.className = 'text-xs font-semibold text-gray-500 uppercase';
                dt.textContent = label;
                const dd = document.createElement('dd');
                dd.className = 'whitespace-pre-line text-sm break-words';
                dd.textContent = (v === null || v === undefined || String(v).trim() === '') ? '-' : v;
                wrap.append(dt, dd);
                body.appendChild(wrap);
            });
            document.getElementById('viewModal').classList.remove('hidden');
        }

        // ====== POP UP TAMBAH / EDIT ======
        // Satu fungsi untuk kedua form: prefix id field g_ (gangguan) / f_ (follow up)
        function openFormModal(cfg, d) {
            d = d || {};
            const isEdit = !!d.id;
            const f = document.getElementById(cfg.form);
            f.reset();
            f.action = isEdit ? cfg.urlUpdate : cfg.urlSave;
            document.getElementById(cfg.title).textContent = (isEdit ? 'Edit ' : 'Tambah ') + cfg.label;
            document.getElementById(cfg.submit).textContent = isEdit ? 'Update' : 'Simpan';

            ['id', ...cfg.fields].forEach(k => document.getElementById(cfg.prefix + k).value = d[k] || '');
            if (cfg.fill) cfg.fill(d, isEdit);

            document.getElementById(cfg.modal).classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        const FORM_GANGGUAN = {
            label: 'Gangguan', prefix: 'g_', modal: 'gangguanModal', form: 'gangguanForm',
            title: 'gangguanModalTitle', submit: 'gangguanSubmit',
            urlSave: "<?= site_url('HistoryReport/gangguan/save') ?>",
            urlUpdate: "<?= site_url('HistoryReport/gangguan/update') ?>",
            fields: ['grup', 'sub_grup', 'customer_site', 'cid_ticket', 'status', 'priority', 'gangguan', 'tindakan', 'next_action', 'pic'],
            wajib: ['grup', 'customer_site', 'status', 'priority', 'gangguan', 'pic'],
        };
        const FORM_FOLLOW_UP = {
            label: 'Follow Up', prefix: 'f_', modal: 'followUpModal', form: 'followUpForm',
            title: 'followUpModalTitle', submit: 'followUpSubmit',
            urlSave: "<?= site_url('HistoryReport/followup/save') ?>",
            urlUpdate: "<?= site_url('HistoryReport/followup/update') ?>",
            fields: ['grup', 'customer_site', 'priority', 'due_date', 'pic', 'issue', 'action'],
            wajib: ['grup', 'customer_site', 'priority', 'pic', 'issue'],
        };

        const FORM_PENGIRIMAN = {
            label: 'Pengiriman', prefix: 'p_', modal: 'pengirimanModal', form: 'pengirimanForm',
            title: 'pengirimanModalTitle', submit: 'pengirimanSubmit',
            urlSave: "<?= site_url('HistoryReport/pengiriman/save') ?>",
            urlUpdate: "<?= site_url('HistoryReport/pengiriman/update') ?>",
            fields: ['grup', 'customer', 'device', 'tracking_resi', 'status', 'eta', 'pic'],
            wajib: ['grup', 'customer', 'device', 'status', 'pic'],
        };

        const openGangguanModal = d => openFormModal(FORM_GANGGUAN, d);
        const openFollowUpModal = d => openFormModal(FORM_FOLLOW_UP, d);
        const openPengirimanModal = d => openFormModal(FORM_PENGIRIMAN, d);

        const FORM_MAINTENANCE = {
            label: 'Maintenance', prefix: 'm_', modal: 'maintenanceModal', form: 'maintenanceForm',
            title: 'maintenanceModalTitle', submit: 'maintenanceSubmit',
            urlSave: "<?= site_url('HistoryReport/maintenance/save') ?>",
            urlUpdate: "<?= site_url('HistoryReport/maintenance/update') ?>",
            fields: ['grup', 'site', 'equipment', 'schedule', 'status', 'pic', 'issue', 'action'],
            wajib: ['grup', 'site', 'equipment', 'schedule', 'status', 'pic'],
        };
        const openMaintenanceModal = d => openFormModal(FORM_MAINTENANCE, d);

        const FORM_AKTIVASI = {
            label: 'Aktivasi', prefix: 'a_', modal: 'aktivasiModal', form: 'aktivasiForm',
            title: 'aktivasiModalTitle', submit: 'aktivasiSubmit',
            urlSave: "<?= site_url('HistoryReport/aktivasi/save') ?>",
            urlUpdate: "<?= site_url('HistoryReport/aktivasi/update') ?>",
            fields: ['id_pelanggan', 'nama_pelanggan', 'kapasitas_mbps', 'sn', 'tanggal_aktivasi'],
            wajib: ['id_pelanggan', 'nama_pelanggan', 'kapasitas_mbps', 'sn', 'tanggal_aktivasi'],
        };
        const openAktivasiModal = d => openFormModal(FORM_AKTIVASI, d);

        // ====== CATATAN: beberapa baris poin catatan ======
        function addCatatanRow(value, focus) {
            const list = document.getElementById('c_list');
            const row = document.createElement('div');
            row.className = 'catatan-row flex items-start gap-2';
            row.innerHTML =
                '<span class="catatan-no w-6 pt-3 text-sm text-gray-500 text-right"></span>' +
                '<textarea name="catatan[]" rows="2" placeholder="Masukan catatan" class="flex-1 border rounded-lg p-3"></textarea>' +
                '<button type="button" title="Hapus baris" class="mt-2 w-9 h-9 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-lg">' +
                '<i class="ti ti-x"></i></button>';
            row.querySelector('textarea').value = value || '';
            row.querySelector('button').onclick = () => {
                row.remove();
                if (!list.children.length) addCatatanRow(''); // minimal satu baris
                renumberCatatan();
            };
            list.appendChild(row);
            renumberCatatan();
            if (focus) row.querySelector('textarea').focus();
        }

        function renumberCatatan() {
            document.querySelectorAll('#c_list .catatan-no').forEach((el, i) => el.textContent = (i + 1) + '.');
        }

        const FORM_CATATAN = {
            label: 'Catatan', prefix: 'c_', modal: 'catatanModal', form: 'catatanForm',
            title: 'catatanModalTitle', submit: 'catatanSubmit',
            urlSave: "<?= site_url('HistoryReport/catatan/save') ?>",
            urlUpdate: "<?= site_url('HistoryReport/catatan/update') ?>",
            fields: ['grup', 'kategori', 'priority', 'status', 'judul'],
            wajib: ['grup', 'kategori', 'priority', 'status', 'judul'],
            fill: function(d) {
                if (!d.status) document.getElementById('c_status').value = 'Open';
                // edit: d.poin dari tabel; gagal validasi: d.catatan berupa array input lama
                const poin = Array.isArray(d.catatan) ? d.catatan : (d.poin || []);
                document.getElementById('c_list').innerHTML = '';
                (poin.length ? poin : ['']).forEach(p => addCatatanRow(p));
            },
            // minimal satu poin catatan terisi
            check: () => [...document.querySelectorAll('#c_list textarea')].some(t => t.value.trim() !== ''),
        };
        const openCatatanModal = d => openFormModal(FORM_CATATAN, d);

        // ====== VALIDASI SEBELUM SUBMIT ======
        [FORM_GANGGUAN, FORM_FOLLOW_UP, FORM_PENGIRIMAN, FORM_MAINTENANCE, FORM_AKTIVASI, FORM_CATATAN].forEach(cfg => {
            document.getElementById(cfg.form).addEventListener('submit', function(e) {
                const kosong = cfg.wajib.some(k => document.getElementById(cfg.prefix + k).value.trim() === '') ||
                    (cfg.check && !cfg.check());
                if (kosong) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Form Belum Lengkap',
                        text: 'Semua field bertanda * wajib diisi.',
                        confirmButtonColor: '#185a82'
                    });
                    return false;
                }
            });
        });

        <?php
        // Gagal validasi di server: buka lagi pop up dengan isian terakhir
        $oldKeys = ['id', 'grup', 'sub_grup', 'customer_site', 'cid_ticket', 'status', 'priority', 'gangguan', 'tindakan', 'next_action', 'pic', 'due_date', 'issue', 'action', 'customer', 'device', 'tracking_resi', 'eta', 'site', 'equipment', 'schedule', 'kategori', 'judul', 'catatan', 'id_pelanggan', 'nama_pelanggan', 'kapasitas_mbps', 'sn', 'tanggal_aktivasi'];
        $oldData = [];
        foreach ($oldKeys as $k) {
            $oldData[$k] = old($k, null, false);
        }
        ?>
        <?php if ($oldForm === 'gangguan') : ?>
            openGangguanModal(<?= json_encode($oldData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);
        <?php elseif ($oldForm === 'followup') : ?>
            openFollowUpModal(<?= json_encode($oldData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);
        <?php elseif ($oldForm === 'pengiriman') : ?>
            openPengirimanModal(<?= json_encode($oldData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);
        <?php elseif ($oldForm === 'maintenance') : ?>
            openMaintenanceModal(<?= json_encode($oldData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);
        <?php elseif ($oldForm === 'aktivasi') : ?>
            openAktivasiModal(<?= json_encode($oldData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);
        <?php elseif ($oldForm === 'catatan') : ?>
            openCatatanModal(<?= json_encode($oldData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);
        <?php endif; ?>
    </script>

    <script>
        // ====== DELETE ======
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

        function isTableEmpty(table) {
            return table.rows({ search: 'applied' }).data().length === 0;
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
                    setTimeout(() => { progressBar.style.width = "0%"; }, 100);
                }
                setTimeout(() => {
                    alertBox.style.transition = "all .5s ease";
                    alertBox.style.opacity = "0";
                    alertBox.style.transform = "translate(-50%, -20px)";
                    setTimeout(() => { alertBox.remove(); }, 500);
                }, 3000);
            }
        });
    </script>


    <script>
        $(document).ready(function() {
            const exportConfig = {
                exportOptions: {
                    columns: (idx) => idx !== 1, // tanpa kolom Aksi
                    format: {
                        body: function(data, row, column, node) {
                            // kolom teks panjang: export teks lengkap, bukan potongan + "Selengkapnya"
                            if (node && node.dataset && node.dataset.full !== undefined) return node.dataset.full;
                            const tmp = document.createElement('div');
                            tmp.innerHTML = data;
                            return tmp.textContent.trim();
                        }
                    }
                }
            };

            // tombol export dengan cek data kosong
            function exportBtn(ext, text, title, extra) {
                return {
                    extend: ext,
                    text: text,
                    title: title,
                    ...exportConfig,
                    ...(extra || {}),
                    action: function(e, dt, button, config) {
                        if (isTableEmpty(dt)) return showEmptyExportAlert();
                        $.fn.dataTable.ext.buttons[ext].action.call(this, e, dt, button, config);
                    }
                };
            }

            // Inisialisasi satu tabel beserta toolbar di panelnya
            function initTable(tableId, title, emptyText) {
                const $panel = $('#' + tableId).closest('.ho-panel');

                const table = $('#' + tableId).DataTable({
                    pageLength: 10,
                    lengthMenu: [
                        [10, 15, 25, 50, -1],
                        [10, 15, 25, 50, "Semua"]
                    ],
                    order: [],
                    autoWidth: false,
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
                            exportBtn('copyHtml5', '<i class="ti ti-copy"></i> Copy', title),
                            exportBtn('csvHtml5', '<i class="ti ti-file-text"></i> Export CSV', title),
                            exportBtn('excelHtml5', '<i class="ti ti-file-spreadsheet"></i> Export Excel', title),
                            exportBtn('pdfHtml5', '<i class="ti ti-file-type-pdf"></i> Export PDF', title, {
                                orientation: 'landscape',
                                pageSize: 'A4',
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
                            })
                        ]
                    }],
                    language: {
                        lengthMenu: "_MENU_",
                        info: "Showing _START_ to _END_ of _TOTAL_ entries",
                        infoEmpty: "Showing 0 to 0 of 0 entries",
                        infoFiltered: "(filtered from _MAX_ total entries)",
                        emptyTable: emptyText,
                        zeroRecords: "Tidak ada data yang cocok dengan pencarian",
                        paginate: {
                            previous: "Previous",
                            next: "Next"
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

                // pindahkan kontrol bawaan DataTables ke toolbar panel ini
                const $wrap = $('#' + tableId + '_wrapper');
                $panel.find('.length-area').append($wrap.find('.dataTables_length'));
                $panel.find('.export-area').append($wrap.find('.dt-buttons'));

                const $search = $panel.find('.search-input');
                $search.on('keyup', function() {
                    table.search(this.value).draw();
                });
                $panel.find('.go-btn').on('click', function() {
                    table.search($search.val()).draw();
                });
                $search.on('keypress', function(e) {
                    if (e.which === 13) table.search(this.value).draw();
                });
            }

            initTable('gangguanTable', 'Data Gangguan', 'Data gangguan belum tersedia');
            initTable('followUpTable', 'Data Follow Up', 'Data follow up belum tersedia');
            initTable('pengirimanTable', 'Data Pengiriman', 'Data pengiriman belum tersedia');
            initTable('maintenanceTable', 'Data Maintenance', 'Data maintenance belum tersedia');
            initTable('aktivasiTable', 'Data Aktivasi', 'Data aktivasi belum tersedia');
            initTable('catatanTable', 'Data Catatan', 'Data catatan belum tersedia');
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
