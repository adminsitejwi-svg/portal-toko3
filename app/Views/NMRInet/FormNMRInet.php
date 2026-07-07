<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('store.png') ?>">
    <title>Nomer Inet</title>
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
        #dcTable {
            width: 100% !important;
            border-collapse: collapse;
        }

        #dcTable thead th {
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

        .dark #dcTable thead th {
            background: #2b3543;
            color: #9fb0c2;
            border-color: #37404c;
        }

        #dcTable tbody td {
            padding: 16px;
            font-size: 14px;
            color: #3b4754;
            border-bottom: 1px solid #f0f2f5;
            vertical-align: middle;
            white-space: nowrap;
        }

        .dark #dcTable tbody td {
            color: #bfc8d6;
            border-color: #37404c;
        }

        #dcTable tbody tr:hover {
            background: #fafbfc;
        }

        .dark #dcTable tbody tr:hover {
            background: rgba(255, 255, 255, .03);
        }

        #dcTable tbody td.col-bold {
            font-weight: 600;
            color: #2b3540;
        }

        .dark #dcTable tbody td.col-bold {
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

        /* ===== LENGTH (Show) DROPDOWN — diperlebar, tanpa teks ===== */
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

        /* sembunyikan search bawaan, pakai custom */
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

        /* ===== SCROLL HORIZONTAL DI MOBILE ===== */
        .table-scroll {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-scroll table {
            min-width: 760px;
        }
    </style>
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f7fa;
        }

        .page-wrapper {
            max-width: 1280px;
            margin: 0 auto;
        }

        .page-header {
            background: linear-gradient(135deg, #185a82 0%, #0f3d5c 100%);
            color: white;
            padding: 20px 30px;
            border-radius: 10px 10px 0 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .page-header h2 {
            font-size: 20px;
            font-weight: 700;
        }

        .page-header .subtitle {
            font-size: 13px;
            opacity: .75;
        }

        .form-card {
            background: #fff;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .08);
            padding: 30px;
        }

        .section-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: #185a82;
            border-left: 3px solid #185a82;
            padding-left: 10px;
            margin: 28px 0 16px;
        }

        .section-title:first-child {
            margin-top: 0;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        label .req {
            color: #e53e3e;
            margin-left: 2px;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            color: #1f2937;
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #185a82;
            box-shadow: 0 0 0 3px rgba(24, 90, 130, .12);
        }

        input:disabled,
        select:disabled {
            background: #f3f4f6;
            color: #9ca3af;
            cursor: not-allowed;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .readonly-box {
            padding: 9px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 14px;
            background: #f9fafb;
            color: #374151;
            min-height: 40px;
        }

        .hint {
            font-size: 11px;
            color: #9ca3af;
            font-weight: 400;
        }

        .action-bar {
            display: flex;
            gap: 12px;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        .btn-save {
            flex: 1;
            padding: 12px;
            background: linear-gradient(135deg, #185a82, #0f3d5c);
            color: white;
            border: none;
            border-radius: 7px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-save:hover {
            opacity: .9;
        }

        .btn-back {
            padding: 12px 24px;
            background: #6b7280;
            color: white;
            border: none;
            border-radius: 7px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-back:hover {
            background: #4b5563;
        }

        @media (max-width: 640px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }

            .form-card {
                padding: 18px;
            }
        }

        .brand-text {
            font-size: 18px;
        }
    </style>
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
                    <a href="#" onclick="toggleSub(this);return false;" class="pc-link active flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
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
                        <li><a href="<?= site_url('NomorInet') ?>" class=" block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Nomor INET</a></li>
                        <li><a href="<?= site_url('QuotaSIMCARD') ?>" class=" block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Kuota Simcard</a></li>


                    </ul>

                </li>

                <li><a href="<?= site_url('Map') ?>" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white"><span class="pc-micon w-5"><i class="ti ti-map-pin"></i></span><span>Lokasi</span></a></li>


                <li class="px-6 py-3 text-[11px] uppercase tracking-wide text-[#5b6b7f] font-semibold">Pengaturan</li>
                <li>
                <li>
                    <a href="<?= site_url('settings') ?>"
                        class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">

                        <span class="pc-micon w-5">
                            <i class="ti ti-settings"></i>
                        </span>

                        <span>Pengguna</span>

                    </a>
                </li>
                <li>
                    <a href="<?= site_url('Logs') ?>"
                        class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">

                        <span class="pc-micon w-5">
                            <i class="ti ti-report-search"></i>
                        </span>

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
                <!-- theme -->

                <!-- profile -->
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
            <div class="page-wrapper">

                <div class="page-header">
                    <div>
                        <h2>Tambah Data Nomor Inet</h2>
                        <div class="subtitle">Lengkapi data berikut untuk menambah data nomor inet baru</div>
                    </div>
                </div>

                <div class="form-card">
                    <form action="<?= site_url('NMRInet/save') ?>" method="POST" id="FormNMRInet">
                        <?= csrf_field() ?>

                        <!-- ═══ DATA LAYANAN (dari Master Nomor INET) ═══ -->
                        <div class="section-title">Data Layanan</div>
                        <div class="grid-2">

                            <!-- Pilih master nomor inet (paket layanan) -->
                            <div class="form-group">
                                <label for="nomor_inet_id">Kode Layanan Vendor</label>
                                <select name="nomor_inet_id" id="nomor_inet_id" required
                                    class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    <option value="">— Pilih Kode Layanan Vendor —</option>
                                    <?php foreach ($md_nomer_inet as $ni): ?>
                                        <option value="<?= $ni['id'] ?>"
                                            data-vendor="<?= esc($ni['nama_vendor'] ?? '-') ?>"
                                            data-layanan="<?= esc($ni['nama_paket_layanan'] ?? '-') ?>"
                                            data-bw="<?= esc($ni['kecepatan_bandwidth'] ?? '-') ?>"
                                            data-harga="<?= esc($ni['harga_layanan'] ?? '') ?>"
                                            data-nomor="<?= esc($ni['nomor_inet'] ?? '-') ?>"
                                            data-password="<?= esc($ni['password_inet'] ?? '', 'attr') ?>"
                                            data-haspass="<?= !empty($ni['password_inet']) ? '1' : '0' ?>">
                                            <?= esc($ni['kode_layanan_vendor'] ?? '-') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <!-- Vendor (read-only) -->
                            <div class="form-group">
                                <label>Nama Vendor / Penyedia Layanan</label>
                                <div class="readonly-box w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none" id="vendor_display">—</div>
                            </div>

                            <!-- Bandwidth (read-only) -->
                            <div class="form-group">
                                <label>Nama Layanan</label>
                                <div class="readonly-box" id="layanan_display">—</div>
                            </div>

                            <div class="form-group">
                                <label>Kecepatan / Bandwidth</label>
                                <div class="readonly-box" id="bw_display">—</div>
                            </div>

                            <!-- Harga (read-only) -->
                            <div class="form-group">
                                <label>Harga Layanan</label>
                                <div class="readonly-box" id="harga_display">—</div>
                            </div>

                            <!-- Nomor INET (read-only) -->
                            <div class="form-group">
                                <label>Nomor INET / ID Pelanggan</label>
                                <div class="readonly-box" id="nomor_display">—</div>
                            </div>

                            <!-- Password (read-only indikator) -->
                            <div class="form-group">
                                <label>Password INET / ID Pelanggan</label>
                                <div style="position:relative">
                                    <input type="password" id="pass_display" readonly value=""
                                        class="w-full min-h-[46px] px-4 py-3 pr-12 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-[#f9fafb] outline-none">
                                    <button type="button" id="btn_toggle_password"
                                        style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:transparent;border:none;padding:4px;cursor:pointer;color:#8a95a1">
                                        <i class="ti ti-eye" style="font-size:18px"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- ═══ PELANGGAN ═══ -->
                        <div class="section-title">Data Pelanggan</div>
                        <div class="grid-2">

                            <div class="form-group">
                                <label>Kategori Pelanggan <span class="req">*</span></label>
                                <select name="pelanggan_id" id="pelanggan_id" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    <option value="">— Pilih Kategori Pelanggan —</option>
                                    <?php foreach ($md_pelanggan as $p): ?>
                                        <option value="<?= $p['id'] ?>"
                                            data-kategori="<?= esc($p['kategori_pelanggan'] ?? '', 'attr') ?>">
                                            <?= esc($p['kategori_pelanggan'] ?? '-') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="hint" id="kategori_note"></span>
                            </div>

                            <div class="form-group">
                                <label>Status <span class="req">*</span></label>
                                <select name="status" id="status" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    <option value="">— Pilih Status —</option>
                                    <option value="0">Aktif</option>
                                    <option value="1">Non Aktif</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>ID Pelanggan
                                    <span class="hint">(Personal / Perusahaan / Sekolah)</span>
                                </label>
                                <input type="text" name="id_pelanggan" id="id_pelanggan" placeholder="Isi manual" disabled>
                            </div>

                            <div class="form-group">
                                <label>Kode Toko
                                    <span class="hint">(Alfamidi / Alfamart / Lawson)</span>
                                </label>
                                <input type="text" name="kode_toko" id="kode_toko" placeholder="Isi manual" disabled>
                            </div>
                        </div>

                        <!-- ═══ KETERANGAN ═══ -->
                        <div class="section-title">Keterangan </div>
                        <div class="form-group">
                            <textarea name="keterangan" id="keterangan" rows="3" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none"></textarea>
                        </div>

                        <div class="action-bar">
                            <button type="submit" class="btn-save">Simpan</button>
                            <button type="button" class="btn-back"
                                onclick="window.location.href='<?= site_url('NMRInet') ?>'">Kembali</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

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

        // ---- Dropdowns ----
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

        // ---- Submenu ----
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
        feather.replace();

        /* ============ HELPER: FORMAT RUPIAH ============ */
        function toRupiah(angka) {
            if (angka === '' || angka === null || angka === undefined || isNaN(angka)) return '—';

            // parse sebagai float dulu supaya desimal .00 dihitung benar, lalu bulatkan
            const num = Math.round(parseFloat(angka));

            return 'Rp ' + num.toLocaleString('id-ID');
        }

        /* ============ ELEMEN: DATA LAYANAN ============ */
        const inetSel = document.getElementById('nomor_inet_id');
        const vendorBox = document.getElementById('vendor_display');
        const layananBox = document.getElementById('layanan_display');
        const bwBox = document.getElementById('bw_display');
        const hargaBox = document.getElementById('harga_display');
        const nomorBox = document.getElementById('nomor_display');
        const passBox = document.getElementById('pass_display');

        // hidden input untuk kirim nilai actual nomor_inet & password_inet kalau backend butuh (opsional)
        // <input type="hidden" name="nomor_inet_actual" id="nomor_inet_actual">
        // <input type="hidden" name="password_inet_actual" id="password_inet_actual">

        function resetLayananDisplay() {
            vendorBox.textContent = '—';
            layananBox.textContent = '—';
            bwBox.textContent = '—';
            hargaBox.textContent = '—';
            nomorBox.textContent = '—';
            passBox.textContent = '—';
        }

        inetSel.addEventListener('change', function() {
            const opt = inetSel.options[inetSel.selectedIndex];

            if (!opt || !opt.value) {
                resetLayananDisplay();
                return;
            }

            vendorBox.textContent = opt.dataset.vendor || '—';
            layananBox.textContent = opt.dataset.layanan || '—';
            bwBox.textContent = opt.dataset.bw || '—';
            hargaBox.textContent = opt.dataset.harga ? toRupiah(opt.dataset.harga) : '—';
            nomorBox.textContent = opt.dataset.nomor || '—';
            passBox.value = opt.dataset.password || '';
        });

        /* ============ ELEMEN: DATA PELANGGAN ============ */
        const pelangganSel = document.getElementById('pelanggan_id');
        const idPelangganInp = document.getElementById('id_pelanggan');
        const kodeTokoInp = document.getElementById('kode_toko');
        const kategoriNote = document.getElementById('kategori_note');

        // daftar kategori yang dianggap "toko" (case-insensitive)
        const KATEGORI_TOKO = ['alfamidi', 'alfamart', 'lawson'];

        function applyKategori() {
            const opt = pelangganSel.options[pelangganSel.selectedIndex];
            const kategori = (opt && opt.dataset.kategori ? opt.dataset.kategori : '').toLowerCase().trim();

            let pakaiToko = false;
            let pakaiId = false;

            if (kategori !== '') {
                pakaiToko = KATEGORI_TOKO.includes(kategori);
                pakaiId = !pakaiToko;
            }

            // ID Pelanggan
            idPelangganInp.disabled = !pakaiId;
            if (!pakaiId) idPelangganInp.value = '';

            // Kode Toko
            kodeTokoInp.disabled = !pakaiToko;
            if (!pakaiToko) kodeTokoInp.value = '';

            // Note keterangan kategori
            if (kategori === '') {
                kategoriNote.textContent = '';
            } else if (pakaiToko) {
                kategoriNote.textContent = 'Isi Kode Toko di bawah.';
            } else {
                kategoriNote.textContent = 'Isi ID Pelanggan di bawah.';
            }
        }

        pelangganSel.addEventListener('change', applyKategori);
        applyKategori(); // jalankan sekali saat load, untuk handle kondisi form edit / old input

        /* ============ VALIDASI SEBELUM SUBMIT ============ */
        document.getElementById('FormNMRInet').addEventListener('submit', function(e) {
            let hasError = false;
            let errorMsg = '';

            // validasi paket layanan wajib dipilih
            if (!inetSel.value) {
                hasError = true;
                errorMsg = 'Silakan pilih Kode Layanan Vendor terlebih dahulu.';
            }

            // validasi kategori pelanggan wajib dipilih
            else if (!pelangganSel.value) {
                hasError = true;
                errorMsg = 'Silakan pilih Kategori Pelanggan terlebih dahulu.';
            }

            // validasi id_pelanggan / kode_toko sesuai kategori aktif
            else if (!idPelangganInp.disabled && idPelangganInp.value.trim() === '') {
                hasError = true;
                errorMsg = 'ID Pelanggan wajib diisi.';
            } else if (!kodeTokoInp.disabled && kodeTokoInp.value.trim() === '') {
                hasError = true;
                errorMsg = 'Kode Toko wajib diisi.';
            }

            // validasi status
            else if (!document.getElementById('status').value) {
                hasError = true;
                errorMsg = 'Silakan pilih Status terlebih dahulu.';
            }

            // validasi keterangan
            else if (document.getElementById('keterangan').value.trim() === '') {
                hasError = true;
                errorMsg = 'Silakan isi kolom keterangan terlebih dahulu.';
            }

            if (hasError) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Belum Lengkap',
                    text: errorMsg,
                    confirmButtonColor: '#185a82'
                });
                return false;
            }

            // pastikan field disabled tetap terkirim ke server saat submit valid
            idPelangganInp.disabled = false;
            kodeTokoInp.disabled = false;
        });

        document.getElementById('btn_toggle_password').addEventListener('click', function() {
            const input = document.getElementById('pass_display');
            const icon = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('ti-eye', 'ti-eye-off');
            } else {
                input.type = 'password';
                icon.classList.replace('ti-eye-off', 'ti-eye');
            }
        });
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
                    window.location.href =
                        "<?= site_url('NMRInet/delete/') ?>" + id;
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


    <?php if (!session()->get('logged_in')) : ?>
        <script>
            window.location.href = "<?= base_url('/login') ?>";
        </script>
    <?php endif; ?>
</body>
<script>
    // PENGHALANG KOSMETIK SAJA — bukan security, mudah dilewati
    document.addEventListener('contextmenu', e => e.preventDefault()); // klik kanan
    document.addEventListener('keydown', e => {
        if (e.key === 'F12') e.preventDefault(); // F12
        if (e.ctrlKey && e.shiftKey && ['I', 'J', 'C'].includes(e.key.toUpperCase())) e.preventDefault();
        if (e.ctrlKey && e.key.toUpperCase() === 'U') e.preventDefault(); // view-source
    });
</script>

</html>