<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('store.png') ?>">
    <title>Laporan Shift</title>
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

        /* ===== MENU AKSI (titik tiga) — fixed agar tidak terpotong .table-scroll ===== */
        .aksi-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #5b6b7f;
        }

        .aksi-btn:hover,
        .aksi-btn.open {
            background: #eef1f5;
            color: #1e4fa3;
        }

        #aksiMenu {
            position: fixed;
            z-index: 1050;
            min-width: 190px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .12);
            padding: 6px;
        }

        #aksiMenu .aksi-title {
            padding: 6px 12px 8px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #94a3b8;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 4px;
        }

        #aksiMenu button {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            color: #3b4754;
            text-align: left;
        }

        #aksiMenu button i {
            font-size: 17px;
            color: #64748b;
        }

        #aksiMenu button:hover {
            background: #f3f4f6;
        }

        #aksiMenu button.danger,
        #aksiMenu button.danger i {
            color: #dc2626;
        }

        #aksiMenu hr {
            margin: 4px 0;
            border-color: #f1f5f9;
        }

        .dark #aksiMenu { background: #263240; border-color: rgba(255, 255, 255, .1); }
        .dark #aksiMenu button { color: #bfc8d6; }
        .dark #aksiMenu button:hover { background: rgba(255, 255, 255, .05); }

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

        .rekap-red { background: #fde8ec; }
        .rekap-amber { background: #fdf3e3; }
        .rekap-blue { background: #e3edfb; }
        .rekap-green { background: #e7f8f1; }
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
        #reportTable {
            width: 100% !important;
            border-collapse: collapse;
        }

        #reportTable thead th {
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

        .dark #reportTable thead th {
            background: #2b3543;
            color: #9fb0c2;
            border-color: #37404c;
        }

        /* Kolom PIC & ringkasan boleh turun baris (kelas nowrap DataTables menimpanya) */
        #reportTable tbody td.whitespace-normal {
            white-space: normal !important;
        }

        #reportTable tbody td.whitespace-pre-line {
            white-space: pre-line !important;
        }

        #reportTable tbody td {
            padding: 16px;
            font-size: 14px;
            color: #3b4754;
            border-bottom: 1px solid #f0f2f5;
            vertical-align: middle;
            white-space: nowrap;
        }

        .dark #reportTable tbody td {
            color: #bfc8d6;
            border-color: #37404c;
        }

        #reportTable tbody tr:hover {
            background: #fafbfc;
        }

        .dark #reportTable tbody tr:hover {
            background: rgba(255, 255, 255, .03);
        }

        #reportTable tbody td.col-bold {
            font-weight: 600;
            color: #2b3540;
        }

        .dark #reportTable tbody td.col-bold {
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

        .badge-draft {
            background: #eef1f5;
            color: #64748b;
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
        /* ===== HALAMAN SHIFT LAPORAN ===== */
        .lp-eyebrow { font-size: 11px; font-weight: 700; letter-spacing: .12em; text-transform: uppercase; color: #1d6fd8; }
        .lp-title { font-size: 24px; font-weight: 700; color: #1f2937; line-height: 1.3; }
        .lp-sub { font-size: 12px; color: #6b7280; margin-top: 4px; display: flex; flex-wrap: wrap; align-items: center; gap: 6px; }
        .lp-pic { font-weight: 700; text-transform: uppercase; color: #4b5563; }

        .lp-btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; border: 1px solid #e3e8ee; border-radius: 8px; background: #fff; font-size: 13px; font-weight: 500; color: #3b4754; }
        .lp-btn:hover { background: #f8fafc; }
        .lp-btn-primary { background: #2563eb; border-color: #2563eb; color: #fff; }
        .lp-btn-primary:hover { background: #1d4ed8; }

        .lp-chips { display: flex; flex-wrap: wrap; gap: 6px; padding: 6px; background: #f8fafc; border: 1px solid #e5e9f0; border-radius: 12px; }
        .lp-chip { display: inline-flex; align-items: center; gap: 8px; padding: 10px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; color: #4b5563; }
        .lp-chip:hover { background: #fff; box-shadow: 0 2px 8px rgba(15, 23, 42, .08); }
        .lp-count { min-width: 22px; padding: 2px 7px; border-radius: 6px; background: #eef1f5; color: #4b5563; font-size: 12px; text-align: center; }

        .lp-sec-title { display: flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 700; margin-bottom: 14px; }
        .lp-empty { padding: 18px; border: 1px dashed #e2e8f0; border-radius: 10px; text-align: center; font-size: 13px; color: #94a3b8; }

        .lp-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .lp-table th { background: #f8fafc; color: #5b6b7f; font-weight: 600; text-align: left; padding: 10px 12px; border-bottom: 1px solid #e5e9f0; white-space: nowrap; }
        .lp-table td { padding: 10px 12px; border-bottom: 1px solid #f1f5f9; vertical-align: top; white-space: pre-line; min-width: 90px; }
        .lp-table tbody tr:hover { background: #fafbfc; }

        .dark .lp-title { color: #e5e7eb; }
        .dark .lp-pic { color: #cbd5e1; }
        .dark .lp-btn { background: #263240; border-color: rgba(255, 255, 255, .1); color: #bfc8d6; }
        .dark .lp-chips { background: rgba(255, 255, 255, .03); border-color: rgba(255, 255, 255, .08); }
        .dark .lp-chip { color: #bfc8d6; }
        .dark .lp-chip:hover { background: #263240; }
        .dark .lp-count { background: rgba(255, 255, 255, .06); color: #bfc8d6; }
        .dark .lp-table th { background: rgba(255, 255, 255, .04); color: #bfc8d6; border-color: rgba(255, 255, 255, .08); }
        .dark .lp-table td { border-color: rgba(255, 255, 255, .06); }

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
                <li>
                    <a href="<?= site_url('HistoryReport') ?>" class="pc-link active flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-history"></i></span>
                        <span>Report NOC</span>
                    </a>
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
            $tgl = static fn ($t) => $t ? date('d-m-Y', strtotime($t)) : '-';
            $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $ts = strtotime($report['tanggal']);
            $tanggalPanjang = date('j', $ts) . ' ' . $bulan[(int) date('n', $ts)] . ' ' . date('Y', $ts);
            $v = static fn ($x) => trim((string) $x) === '' ? '-' : esc($x);

            // Badge warna (sama dengan halaman Shift Handover)
            $badge = [
                'High' => 'badge-due', 'Medium' => 'badge-pending', 'Low' => 'badge-service',
                'Resolved' => 'badge-paid', 'On Progress' => 'badge-service', 'Monitoring' => 'badge-pending', 'Down' => 'badge-due', 'Pending' => 'badge-pending',
                'Requested' => 'badge-pending', 'Shipped' => 'badge-service', 'In Transit' => 'badge-service', 'Received' => 'badge-paid', 'Installed' => 'badge-paid', 'Closed' => 'badge-paid',
                'Planned' => 'badge-service', 'Done' => 'badge-paid',
                'Open' => 'badge-pending',
                'Info' => 'badge-service', 'Note' => 'badge-pending', 'Recurring' => 'badge-paid',
            ];
            $b = static fn ($x) => '<span class="badge ' . ($badge[$x] ?? 'badge-service') . '">' . esc($x) . '</span>';

            $bagian = [
                'gangguan'    => ['icon' => '🚨', 'label' => 'Gangguan / Incident'],
                'followup'    => ['icon' => '🔔', 'label' => 'Follow Up'],
                'pengiriman'  => ['icon' => '📦', 'label' => 'Pengiriman Perangkat'],
                'maintenance' => ['icon' => '🛠️', 'label' => 'Maintenance'],
                'catatan'     => ['icon' => '📌', 'label' => 'Catatan'],
            ];
            $total = 0;
            foreach ($bagian as $k => $_) {
                $total += count($anak[$k] ?? []);
            }
            ?>

            <!-- HEADER LAPORAN -->
            <div class="flex flex-wrap items-start justify-between gap-4 mb-5">
                <div class="flex items-start gap-3">
                    <a href="<?= site_url('HistoryReport') ?>" title="Kembali" class="mt-5 w-9 h-9 rounded-lg flex items-center justify-center hover:bg-gray-100 dark:hover:bg-white/5">
                        <i class="ti ti-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <div class="lp-eyebrow">Shift Laporan</div>
                        <h4 class="lp-title"><?= esc($report['shift']); ?> · <?= $tanggalPanjang ?></h4>
                        <div class="lp-sub">
                            <span class="lp-pic"><?= esc($report['pic_shift']); ?></span>
                            · <?= $hm($report['jam_mulai']); ?>–<?= $hm($report['jam_selesai']); ?>
                            ·
                            <?php if ($total > 0) : ?>
                                <span class="badge badge-paid"><i class="ti ti-circle-check mr-1"></i>Completed</span>
                            <?php else : ?>
                                <span class="badge badge-draft"><i class="ti ti-pencil mr-1"></i>Draft</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" onclick="copyLaporan()" class="lp-btn"><i class="ti ti-copy"></i> Copy WhatsApp</button>
                    <a href="<?= site_url('HistoryReport/gangguan/' . $report['id']) ?>" class="lp-btn lp-btn-primary"><i class="ti ti-tool"></i> Edit Data</a>
                </div>
            </div>

            <!-- JUMLAH PER BAGIAN (klik untuk lompat ke bagiannya) -->
            <div class="lp-chips mb-6">
                <?php foreach ($bagian as $k => $s) : ?>
                    <a href="#lp-<?= $k ?>" class="lp-chip">
                        <span><?= $s['icon'] ?></span>
                        <span><?= $s['label'] ?></span>
                        <span class="lp-count"><?= count($anak[$k] ?? []) ?></span>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- RINGKASAN -->
            <div class="card mb-6">
                <div class="card-body">
                    <h6 class="lp-sec-title">📝 Ringkasan</h6>
                    <div class="whitespace-pre-line text-sm"><?= $v($report['ringkasan']) ?></div>
                </div>
            </div>

            <?php
            // Kolom tabel tiap bagian: judul kolom => fungsi isi sel
            $kolom = [
                'gangguan' => [
                    'Group' => fn ($r) => esc($r['grup']),
                    'Sub Group' => fn ($r) => $v($r['sub_grup']),
                    'Customer / Site' => fn ($r) => $v($r['customer_site']),
                    'CID / Ticket' => fn ($r) => $v($r['cid_ticket']),
                    'Status' => fn ($r) => $b($r['status']),
                    'Priority' => fn ($r) => $b($r['priority']),
                    'Problem' => fn ($r) => $v($r['gangguan']),
                    'Tindakan' => fn ($r) => $v($r['tindakan']),
                    'Next Action / Handover' => fn ($r) => $v($r['next_action']),
                    'PIC' => fn ($r) => $v($r['pic']),
                ],
                'followup' => [
                    'Group' => fn ($r) => esc($r['grup']),
                    'Customer / Site' => fn ($r) => $v($r['customer_site']),
                    'Priority' => fn ($r) => $b($r['priority']),
                    'Due Date' => fn ($r) => $tgl($r['due_date']),
                    'PIC' => fn ($r) => $v($r['pic']),
                    'Issue' => fn ($r) => $v($r['issue']),
                    'Action / Next Step' => fn ($r) => $v($r['action']),
                ],
                'pengiriman' => [
                    'Group' => fn ($r) => esc($r['grup']),
                    'Customer' => fn ($r) => $v($r['customer']),
                    'Device' => fn ($r) => $v($r['device']),
                    'Tracking / Resi' => fn ($r) => $v($r['tracking_resi']),
                    'Status' => fn ($r) => $b($r['status']),
                    'ETA' => fn ($r) => $tgl($r['eta']),
                    'PIC' => fn ($r) => $v($r['pic']),
                ],
                'maintenance' => [
                    'Group' => fn ($r) => esc($r['grup']),
                    'Site' => fn ($r) => $v($r['site']),
                    'Equipment' => fn ($r) => $v($r['equipment']),
                    'Schedule' => fn ($r) => $tgl($r['schedule']),
                    'Status' => fn ($r) => $b($r['status']),
                    'PIC' => fn ($r) => $v($r['pic']),
                    'Issue' => fn ($r) => $v($r['issue']),
                    'Action' => fn ($r) => $v($r['action']),
                ],
                'catatan' => [
                    'Group' => fn ($r) => esc($r['grup']),
                    'Kategori' => fn ($r) => $b($r['kategori']),
                    'Priority' => fn ($r) => $b($r['priority']),
                    'Status' => fn ($r) => $b($r['status']),
                    'Judul' => fn ($r) => '<b>' . $v($r['judul']) . '</b>',
                    // poin catatan bernomor, satu per baris
                    'Catatan' => function ($r) {
                        $out = [];
                        foreach (\App\Models\CatatanModel::poin($r['catatan']) as $i => $p) {
                            $out[] = ($i + 1) . '. ' . esc($p);
                        }

                        return implode("\n", $out);
                    },
                ],
            ];
            ?>

            <!-- BAGIAN-BAGIAN LAPORAN -->
            <?php foreach ($bagian as $k => $s) : ?>
                <?php $rows = $anak[$k] ?? []; ?>
                <div class="card mb-6" id="lp-<?= $k ?>">
                    <div class="card-body">
                        <h6 class="lp-sec-title">
                            <?= $s['icon'] ?> <?= $s['label'] ?>
                            <span class="lp-count"><?= count($rows) ?></span>
                        </h6>

                        <?php if (! $rows) : ?>
                            <div class="lp-empty">Belum ada data <?= strtolower($s['label']) ?>.</div>
                        <?php else : ?>
                            <div class="table-scroll">
                                <table class="lp-table">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <?php foreach (array_keys($kolom[$k]) as $judul) : ?>
                                                <th><?= $judul ?></th>
                                            <?php endforeach; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($rows as $i => $r) : ?>
                                            <tr>
                                                <td><?= $i + 1 ?></td>
                                                <?php foreach ($kolom[$k] as $isi) : ?>
                                                    <td><?= $isi($r) ?></td>
                                                <?php endforeach; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        // Copy: teks "NOC SHIFT REPORT" (format WhatsApp) yang sama dengan tombol Copy di halaman History Report
        const LAPORAN_TEXT = <?= json_encode($copyText, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;

        function copyLaporan() {
            const done = () => Swal.fire({ icon: 'success', title: 'Tersalin', text: 'Report siap di-paste ke WhatsApp.', timer: 1500, showConfirmButton: false });
            const fallback = () => {
                // untuk akses lewat http non-localhost (clipboard API tidak tersedia)
                const ta = document.createElement('textarea');
                ta.value = LAPORAN_TEXT;
                ta.style.position = 'fixed';
                ta.style.opacity = '0';
                document.body.appendChild(ta);
                ta.select();
                const ok = document.execCommand('copy');
                ta.remove();
                ok ? done() : Swal.fire({ icon: 'error', title: 'Gagal', text: 'Data tidak dapat disalin.' });
            };

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(LAPORAN_TEXT).then(done, fallback);
            } else {
                fallback();
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
