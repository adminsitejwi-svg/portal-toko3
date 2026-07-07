<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <title>Lokasi</title>
    <link rel="icon" type="image/png" href="<?= base_url('store.png') ?>">
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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
        #midi_map,
        #mapLawson,
        #mapAlfamart {
            width: 100% !important;
            height: 600px !important;
            min-height: 600px;
            display: block;
        }

        .leaflet-container {
            width: 100% !important;
            height: 100% !important;
        }
    </style>
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
        #mediaKoneksiTable {
            width: 100% !important;
            border-collapse: collapse;
        }

        #mediaKoneksiTable thead th {
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

        .dark #mediaKoneksiTable thead th {
            background: #2b3543;
            color: #9fb0c2;
            border-color: #37404c;
        }

        #mediaKoneksiTable tbody td {
            padding: 16px;
            font-size: 14px;
            color: #3b4754;
            border-bottom: 1px solid #f0f2f5;
            vertical-align: middle;
            white-space: nowrap;
        }

        .dark #mediaKoneksiTable tbody td {
            color: #bfc8d6;
            border-color: #37404c;
        }

        #mediaKoneksiTable tbody tr:hover {
            background: #fafbfc;
        }

        .dark #mediaKoneksiTable tbody tr:hover {
            background: rgba(255, 255, 255, .03);
        }

        #mediaKoneksiTable tbody td.col-bold {
            font-weight: 600;
            color: #2b3540;
        }

        .dark #mediaKoneksiTable tbody td.col-bold {
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

        .store-link {
            position: relative;
            color: #64748b;
            text-decoration: none;
            transition: all .3s ease;
            padding-bottom: 4px;
        }

        .store-link:hover {
            color: #04a9f5;
        }

        .store-link::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 0%;
            height: 2px;
            background: #04a9f5;
            transition: .3s;
        }

        .store-link:hover::after {
            width: 100%;
        }

        .active-store {
            color: #04a9f5 !important;
            font-weight: 600;
        }

        .active-store::after {
            width: 100% !important;
        }

        .dark .store-link {
            color: #94a3b8;
        }

        .dark .active-store {
            color: #38bdf8 !important;
        }

        .dark .active-store::after {
            background: #38bdf8;
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
                        <li><a href="<?= site_url('NomorInet') ?>" class=" block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Nomor INET</a></li>
                        <li><a href="<?= site_url('QuotaSIMCARD') ?>" class=" block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Kuota Simcard</a></li>


                    </ul>

                </li>

                <li><a href="<?= site_url('Map') ?>" class="pc-link active flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white"><span class="pc-micon w-5"><i class="ti ti-map-pin"></i></span><span>Lokasi</span></a></li>


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
            <!-- breadcrumb -->
            <div class="flex items-center justify-between mb-6">
                <h5 class="font-medium text-lg">Lokasi</h5>

                <div class="flex items-center gap-6 text-sm font-medium">
                    <a href="javascript:void(0)"
                        class="store-link active-store"
                        onclick="showStore('alfamidi', this)">
                        Alfamidi
                    </a>

                    <a href="javascript:void(0)"
                        class="store-link"
                        onclick="showStore('lawson', this)">
                        Lawson
                    </a>

                    <a href="javascript:void(0)"
                        class="store-link"
                        onclick="showStore('alfamart', this)">
                        Alfamart
                    </a>

                </div>

            </div>
            <div id="alfamidiSection">
                <div class="card">
                    <div class="card-body p-0" style="overflow:hidden;border-radius:inherit">

                        <!-- TOP BAR -->
                        <div style="background:#0f3d5c;padding:12px 18px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap">
                            <div style="display:flex;align-items:center;gap:10px">
                                <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                                    <rect width="28" height="28" rx="7" fill="#185a82" />
                                    <path d="M14 6C10.13 6 7 9.13 7 13c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1114 10.5a2.5 2.5 0 010 5z" fill="#fff" />
                                </svg>
                                <div>
                                    <div style="color:#fff;font-size:14px;font-weight:600">Peta Lokasi Alfamidi</div>
                                    <div style="color:#93c5fd;font-size:11px;margin-top:1px">Titik koordinat toko — klik marker untuk detail</div>
                                </div>
                            </div>
                            <span id="midi_update_lbl" style="font-size:11px;color:#93c5fd"></span>
                        </div>

                        <!-- STATISTIK -->
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);background:#f9fafb;border-bottom:1px solid #e5e7eb">
                            <div style="text-align:center;padding:12px 8px;border-right:1px solid #e5e7eb">
                                <div style="font-size:22px;font-weight:700;color:#185a82" id="midi_s_total">—</div>
                                <div style="font-size:11px;color:#6b7280;margin-top:2px">Total Toko</div>
                            </div>
                            <div style="text-align:center;padding:12px 8px;border-right:1px solid #e5e7eb">
                                <div style="font-size:22px;font-weight:700;color:#22c55e" id="midi_s_aktif">—</div>
                                <div style="font-size:11px;color:#6b7280;margin-top:2px">Aktif</div>
                            </div>
                            <div style="text-align:center;padding:12px 8px">
                                <div style="font-size:22px;font-weight:700;color:#ef4444" id="midi_s_nonaktif">—</div>
                                <div style="font-size:11px;color:#6b7280;margin-top:2px">Non Aktif</div>
                            </div>
                        </div>

                        <!-- FILTER -->
                        <div style="display:flex;gap:8px;padding:10px 14px;border-bottom:1px solid #e5e7eb;flex-wrap:wrap">
                            <input type="text" id="midi_f_search" placeholder="Cari nama / kode / alamat..."
                                style="flex:1;min-width:160px;padding:5px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:12px;height:30px">
                            <select id="midi_f_status" style="padding:5px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:12px;height:30px;background:#fff">
                                <option value="">Semua Status</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Non Aktif">Non Aktif</option>
                            </select>
                            <select id="midi_f_dc" style="padding:5px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:12px;height:30px;background:#fff">
                                <option value="">Semua DC</option>
                            </select>
                            <select id="midi_f_vendor" style="padding:5px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:12px;height:30px;background:#fff">
                                <option value="">Semua Vendor</option>
                            </select>
                        </div>

                        <!-- MAP -->
                        <div style="position:relative">
                            <div id="midi_map_loader" style="position:absolute;inset:0;background:#f9fafb;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;z-index:999">
                                <div style="width:32px;height:32px;border:3px solid #e5e7eb;border-top-color:#185a82;border-radius:50%;animation:midiSpin .7s linear infinite"></div>
                                <p style="font-size:13px;color:#6b7280">Memuat data koordinat...</p>
                            </div>
                            <div id="midi_map"
                                style="height:calc(100vh - 260px);width:100%;">
                            </div>
                        </div>

                        <!-- LEGEND -->
                        <div style="display:flex;align-items:center;gap:14px;padding:9px 14px;background:#f9fafb;border-top:1px solid #e5e7eb;flex-wrap:wrap;font-size:12px;color:#6b7280">
                            <span style="display:flex;align-items:center;gap:5px"><span style="width:10px;height:10px;border-radius:50%;background:#22c55e;display:inline-block"></span>Aktif</span>
                            <span style="display:flex;align-items:center;gap:5px"><span style="width:10px;height:10px;border-radius:50%;background:#ef4444;display:inline-block"></span>Non Aktif</span>
                            <span id="midi_count_lbl" style="margin-left:auto;font-size:11px"></span>
                        </div>

                    </div>
                </div>
            </div>
            <div id="lawsonSection" style="display:none;">
                <div class="card">
                    <div class="card-body p-0" style="border-radius:inherit">

                        <div style="background:#166534;padding:12px 18px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap">
                            <div style="display:flex;align-items:center;gap:10px">
                                <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                                    <rect width="28" height="28" rx="7" fill="#14532d" />
                                    <path d="M14 6C10.13 6 7 9.13 7 13c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1114 10.5a2.5 2.5 0 010 5z" fill="#fff" />
                                </svg>
                                <div>
                                    <div style="color:#fff;font-size:14px;font-weight:600">Peta Lokasi Lawson</div>
                                    <div style="color:#bbf7d0;font-size:11px;margin-top:1px">Titik koordinat toko Lawson — klik marker untuk detail</div>
                                </div>
                            </div>
                            <span id="lawson_update_lbl" style="font-size:11px;color:#bbf7d0"></span>
                        </div>

                        <!-- Statistik -->
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);background:#f9fafb;border-bottom:1px solid #e5e7eb">
                            <div style="text-align:center;padding:12px">
                                <div id="lawson_total" style="font-size:22px;font-weight:700;color:#166534">0</div>
                                <div style="font-size:11px;color:#6b7280">Total Toko</div>
                            </div>

                            <div style="text-align:center;padding:12px">
                                <div id="lawson_aktif" style="font-size:22px;font-weight:700;color:#22c55e">0</div>
                                <div style="font-size:11px;color:#6b7280">Aktif</div>
                            </div>

                            <div style="text-align:center;padding:12px">
                                <div id="lawson_nonaktif" style="font-size:22px;font-weight:700;color:#ef4444">0</div>
                                <div style="font-size:11px;color:#6b7280">Non Aktif</div>
                            </div>
                        </div>

                        <!-- FILTER -->
                        <div style="display:flex;gap:8px;padding:10px 14px;border-bottom:1px solid #e5e7eb;flex-wrap:wrap">
                            <input type="text" id="lawson_search" placeholder="Cari nama / kode / alamat..."
                                style="flex:1;min-width:160px;padding:5px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:12px;height:30px">

                            <select id="lawson_status"
                                style="padding:5px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:12px;height:30px">
                                <option value="">Semua Status</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Non Aktif">Non Aktif</option>
                            </select>

                            <select id="lawson_dc"
                                style="padding:5px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:12px;height:30px">
                                <option value="">Semua DC</option>
                            </select>

                            <select id="lawson_vendor"
                                style="padding:5px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:12px;height:30px">
                                <option value="">Semua Vendor</option>
                            </select>
                        </div>

                        <div id="mapLawson"
                            style="height:calc(100vh - 260px);width:100%;">
                        </div>
                        <div style="display:flex;align-items:center;gap:14px;padding:9px 14px;background:#f9fafb;border-top:1px solid #e5e7eb;flex-wrap:wrap;font-size:12px;color:#6b7280">
                            <span style="display:flex;align-items:center;gap:5px"><span style="width:10px;height:10px;border-radius:50%;background:#22c55e;display:inline-block"></span>Aktif</span>
                            <span style="display:flex;align-items:center;gap:5px"><span style="width:10px;height:10px;border-radius:50%;background:#ef4444;display:inline-block"></span>Non Aktif</span>
                            <span id="lawson_count" style="margin-left:auto;font-size:11px"></span>
                        </div>

                    </div>
                </div>
            </div>
            <div id="alfamartSection" style="display:none;">
                <div class="card">
                    <div class="card-body p-0" style="border-radius:inherit">

                        <div style="background:#dc2626;padding:12px 18px;display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap">
                            <div style="display:flex;align-items:center;gap:10px">
                                <svg width="28" height="28" viewBox="0 0 28 28" fill="none">
                                    <rect width="28" height="28" rx="7" fill="#991b1b" />
                                    <path d="M14 6C10.13 6 7 9.13 7 13c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5A2.5 2.5 0 1114 10.5a2.5 2.5 0 010 5z" fill="#fff" />
                                </svg>
                                <div>
                                    <div style="color:#fff;font-size:14px;font-weight:600">Peta Lokasi Alfamart</div>
                                    <div style="color:#fecaca;font-size:11px;margin-top:1px">Titik koordinat toko Alfamart — klik marker untuk detail</div>
                                </div>
                            </div>
                            <span id="alfamart_update_lbl" style="font-size:11px;color:#fecaca"></span>
                        </div>

                        <!-- Statistik -->
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);background:#f9fafb;border-bottom:1px solid #e5e7eb">
                            <div style="text-align:center;padding:12px">
                                <div id="alfamart_total" style="font-size:22px;font-weight:700;color:#dc2626">0</div>
                                <div style="font-size:11px;color:#6b7280">Total Toko</div>
                            </div>

                            <div style="text-align:center;padding:12px">
                                <div id="alfamart_aktif" style="font-size:22px;font-weight:700;color:#22c55e">0</div>
                                <div style="font-size:11px;color:#6b7280">Aktif</div>
                            </div>

                            <div style="text-align:center;padding:12px">
                                <div id="alfamart_nonaktif" style="font-size:22px;font-weight:700;color:#ef4444">0</div>
                                <div style="font-size:11px;color:#6b7280">Non Aktif</div>
                            </div>
                        </div>

                        <!-- FILTER -->
                        <div style="display:flex;gap:8px;padding:10px 14px;border-bottom:1px solid #e5e7eb;flex-wrap:wrap">
                            <input type="text" id="alfamart_search" placeholder="Cari nama / kode / alamat..."
                                style="flex:1;min-width:160px;padding:5px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:12px;height:30px">

                            <select id="alfamart_status"
                                style="padding:5px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:12px;height:30px">
                                <option value="">Semua Status</option>
                                <option value="Aktif">Aktif</option>
                                <option value="Non Aktif">Non Aktif</option>
                            </select>

                            <select id="alfamart_dc"
                                style="padding:5px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:12px;height:30px">
                                <option value="">Semua DC</option>
                            </select>

                            <select id="alfamart_vendor"
                                style="padding:5px 10px;border:1px solid #d1d5db;border-radius:6px;font-size:12px;height:30px">
                                <option value="">Semua Vendor</option>
                            </select>
                        </div>

                        <div id="mapAlfamart"
                            style="height:calc(100vh - 260px);width:100%;">
                        </div>
                        <div style="display:flex;align-items:center;gap:14px;padding:9px 14px;background:#f9fafb;border-top:1px solid #e5e7eb;flex-wrap:wrap;font-size:12px;color:#6b7280">
                            <span style="display:flex;align-items:center;gap:5px"><span style="width:10px;height:10px;border-radius:50%;background:#22c55e;display:inline-block"></span>Aktif</span>
                            <span style="display:flex;align-items:center;gap:5px"><span style="width:10px;height:10px;border-radius:50%;background:#ef4444;display:inline-block"></span>Non Aktif</span>
                            <span id="alfamart_count" style="margin-left:auto;font-size:11px"></span>
                        </div>

                    </div>
                </div>
            </div>

        </div>


    </div>
    <!-- Deklarasi variabel global DULU, sebelum IIFE apapun -->
    <script>
        let lawsonMap = null;
        let alfamartMap = null;
        let midiMap = null;

        // Konversi status dari database: 0 = Aktif, 1 = Non Aktif.
        // Tetap mengembalikan teks "Aktif" / "Non Aktif" untuk ditampilkan.
        function statusLabel(d) {
            return String(d.status) === '0' ? 'Aktif' : 'Non Aktif';
        }
    </script>

    <!-- Script Lawson -->
    <script>
        (function() {
            lawsonMap = L.map('mapLawson').setView([-6.2, 106.82], 11);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(lawsonMap);

            // icon pin berwarna (sama seperti Alfamidi)
            function mkIcon(c) {
                const s = `<svg xmlns="http://www.w3.org/2000/svg" width="30" height="40" viewBox="0 0 30 40">
            <path d="M15 0C7.27 0 1 6.27 1 14c0 10.5 14 26 14 26S29 24.5 29 14C29 6.27 22.73 0 15 0z" fill="${c}" stroke="#fff" stroke-width="1.5"/>
            <circle cx="15" cy="14" r="5.5" fill="#fff"/></svg>`;
                return L.icon({
                    iconUrl: 'data:image/svg+xml;base64,' + btoa(s),
                    iconSize: [30, 40],
                    iconAnchor: [15, 40],
                    popupAnchor: [0, -42]
                });
            }
            const IC = {
                a: mkIcon('#22c55e'),
                n: mkIcon('#ef4444')
            };

            const layer = L.layerGroup().addTo(lawsonMap);
            let allData = [];

            function render(data) {
                layer.clearLayers();
                data.forEach(d => {
                    if (!d.titik_koor_toko) return;
                    const c = d.titik_koor_toko.split(',');
                    const lat = parseFloat(c[0]);
                    const lng = parseFloat(c[1]);
                    if (isNaN(lat) || isNaN(lng)) return;
                    L.marker([lat, lng], {
                            icon: statusLabel(d) === 'Aktif' ? IC.a : IC.n
                        })
                        .bindPopup(lawsonPopup(d), {
                            maxWidth: 260,
                            minWidth: 220
                        })
                        .addTo(layer);
                });
                document.getElementById('lawson_count').innerHTML = 'Menampilkan ' + data.length + ' dari ' + allData.length + ' toko';
                if (data.length > 0) {
                    try {
                        lawsonMap.fitBounds(layer.getBounds().pad(0.12));
                    } catch (e) {}
                }
            }

            function lawsonRow(k, v) {
                if (!v && v !== 0) return '';
                return `
        <div style="display:flex;gap:8px;margin:3px 0;font-size:12px">
            <span style="color:#6b7280;min-width:78px;font-size:11px;text-transform:uppercase">${k}</span>
            <span style="color:#1f2937;flex:1">${v}</span>
        </div>`;
            }

            function lawsonPopup(d) {
                const label = statusLabel(d);
                const statusColor = label === 'Aktif' ?
                    'background:#dcfce7;color:#166534' :
                    'background:#fee2e2;color:#991b1b';
                const coord = d.titik_koor_toko || '';
                const mapUrl = d.map_toko ? d.map_toko : (coord ? 'https://www.google.com/maps?q=' + coord : '');
                return `
        <div>
            <div style="background:#166534;padding:10px 14px">
                <div style="color:#fff;font-size:13px;font-weight:600">${d.nama_lawson || '—'}</div>
                <div style="color:#bbf7d0;font-size:11px">${d.kode_toko || ''}</div>
            </div>
            <div style="padding:10px 14px;background:#fff">
                ${lawsonRow('Alamat', d.alamat_lawson)}
                ${lawsonRow('PIC', d.pic_toko)}
                ${lawsonRow('No HP', d.nomor_hp_pic)}
                ${lawsonRow('DC', d.nama_dc)}
                ${lawsonRow('Vendor', d.nama_vendor)}
                ${lawsonRow('Media', d.media_koneksi)}
                ${lawsonRow('Bandwidth', d.kapasitas_bandwidth)}
                ${lawsonRow('IP Address', d.ip_address)}
                ${lawsonRow('Perangkat', d.jenis_perangkat)}
                ${lawsonRow('Merk', d.merk_perangkat)}
                ${lawsonRow('Type',d.type_perangkat_nama)}
                ${lawsonRow('Serial', d.serial_number_perangkat)}
                ${lawsonRow('Instalasi', d.tanggal_installasi)}
                ${lawsonRow('Aktivasi', d.tanggal_aktivasi)}
                ${lawsonRow('Keterangan', d.keterangan)}
            </div>
            <div style="padding:8px 14px;border-top:1px solid #f3f4f6;background:#fff;display:flex;justify-content:space-between;align-items:center">
                <span style="${statusColor};padding:2px 10px;border-radius:20px;font-size:11px;font-weight:600">${label}</span>
                ${mapUrl ? `<a href="${mapUrl}" target="_blank" style="font-size:11px;color:#166534;text-decoration:none">↗ Buka Maps</a>` : ''}
            </div>
        </div>`;
            }

            fetch('<?= site_url('Lawson/getMapData') ?>')
                .then(r => r.json())
                .then(res => {
                    allData = Array.isArray(res) ? res : [];
                    document.getElementById('lawson_total').innerHTML = allData.length;
                    document.getElementById('lawson_aktif').innerHTML = allData.filter(x => statusLabel(x) === 'Aktif').length;
                    document.getElementById('lawson_nonaktif').innerHTML = allData.filter(x => statusLabel(x) === 'Non Aktif').length;
                    document.getElementById('lawson_update_lbl').textContent = 'Update: ' + new Date().toLocaleTimeString('id-ID');
                    render(allData);
                })
                .catch(() => {
                    document.getElementById('lawson_count').innerHTML = '<span style="color:red">Gagal memuat data</span>';
                });
        })();
    </script>

    <!-- Script Alfamart -->
    <script>
        (function() {
            alfamartMap = L.map('mapAlfamart').setView([-6.2, 106.82], 11);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap'
            }).addTo(alfamartMap);

            const layer = L.layerGroup().addTo(alfamartMap);
            let allData = [];

            function mkIcon(c) {
                const s = `<svg xmlns="http://www.w3.org/2000/svg" width="30" height="40" viewBox="0 0 30 40">
        <path d="M15 0C7.27 0 1 6.27 1 14c0 10.5 14 26 14 26S29 24.5 29 14C29 6.27 22.73 0 15 0z" fill="${c}" stroke="#fff" stroke-width="1.5"/>
        <circle cx="15" cy="14" r="5.5" fill="#fff"/></svg>`;
                return L.icon({
                    iconUrl: 'data:image/svg+xml;base64,' + btoa(s),
                    iconSize: [30, 40],
                    iconAnchor: [15, 40],
                    popupAnchor: [0, -42]
                });
            }
            const IC = {
                a: mkIcon('#22c55e'),
                n: mkIcon('#ef4444')
            };

            function render(data) {
                layer.clearLayers();
                data.forEach(d => {
                    if (!d.titik_koor_toko) return;
                    const c = d.titik_koor_toko.split(',');
                    const lat = parseFloat(c[0]);
                    const lng = parseFloat(c[1]);
                    if (isNaN(lat) || isNaN(lng)) return;
                    L.marker([lat, lng], {
                            icon: statusLabel(d) === 'Aktif' ? IC.a : IC.n
                        })
                        .bindPopup(alfamartPopup(d), {
                            maxWidth: 260,
                            minWidth: 220
                        })
                        .addTo(layer);
                });
                document.getElementById('alfamart_count').innerHTML = 'Menampilkan ' + data.length + ' dari ' + allData.length + ' toko';
                if (data.length > 0) {
                    try {
                        alfamartMap.fitBounds(layer.getBounds().pad(0.12));
                    } catch (e) {}
                }
            }

            function alfamartRow(k, v) {
                if (!v && v !== 0) return '';

                return `
        <div style="display:flex;gap:8px;margin:3px 0;font-size:12px">
            <span style="color:#6b7280;min-width:78px;font-size:11px;text-transform:uppercase">
                ${k}
            </span>
            <span style="color:#1f2937;flex:1">
                ${v}
            </span>
        </div>
    `;
            }

            function alfamartPopup(d) {

                const label = statusLabel(d);

                const statusColor =
                    label === 'Aktif' ?
                    'background:#dcfce7;color:#166534' :
                    'background:#fee2e2;color:#991b1b';

                const coord = d.titik_koor_toko || '';

                const mapUrl = d.map_toko ?
                    d.map_toko :
                    (coord ? 'https://www.google.com/maps?q=' + coord : '');

                return `
        <div>
            <div style="background:#dc2626;padding:10px 14px">
                <div style="color:#fff;font-size:13px;font-weight:600">
                    ${d.nama_alfamart || '—'}
                </div>
                <div style="color:#fecaca;font-size:11px">
                    ${d.kode_toko || ''}
                </div>
            </div>

            <div style="padding:10px 14px;background:#fff">
                ${alfamartRow('Alamat', d.alamat_alfamart)}
                ${alfamartRow('PIC', d.pic_toko)}
                ${alfamartRow('No HP', d.nomor_hp_pic)}
                ${alfamartRow('DC', d.nama_dc)}
                ${alfamartRow('Vendor', d.nama_vendor)}
                ${alfamartRow('Media', d.media_koneksi)}
                ${alfamartRow('Bandwidth', d.kapasitas_bandwidth)}
                ${alfamartRow('IP Address', d.ip_address)}
                ${alfamartRow('Perangkat', d.jenis_perangkat)}
                ${alfamartRow('Merk', d.merk_perangkat)}
                ${alfamartRow('Type',d.type_perangkat_nama)}
                ${alfamartRow('Serial', d.serial_number_perangkat)}
                ${alfamartRow('Instalasi', d.tanggal_installasi)}
                ${alfamartRow('Aktivasi', d.tanggal_aktivasi)}
                ${alfamartRow('Keterangan', d.keterangan)}
            </div>

            <div style="padding:8px 14px;border-top:1px solid #f3f4f6;background:#fff;
                        display:flex;justify-content:space-between;align-items:center">
                <span style="${statusColor};
                             padding:2px 10px;
                             border-radius:20px;
                             font-size:11px;
                             font-weight:600">
                    ${label}
                </span>

                ${
                    mapUrl
                    ? `<a href="${mapUrl}" target="_blank"
                        style="font-size:11px;color:#dc2626;text-decoration:none">
                        ↗ Buka Maps
                       </a>`
                    : ''
                }
            </div>
        </div>
    `;
            }
            fetch('<?= site_url('Alfamart/getMapData') ?>')
                .then(r => r.json())
                .then(res => {
                    allData = Array.isArray(res) ? res : [];
                    document.getElementById('alfamart_total').innerHTML = allData.length;
                    document.getElementById('alfamart_aktif').innerHTML = allData.filter(x => statusLabel(x) === 'Aktif').length;
                    document.getElementById('alfamart_nonaktif').innerHTML = allData.filter(x => statusLabel(x) === 'Non Aktif').length;
                    document.getElementById('alfamart_update_lbl').textContent = 'Update: ' + new Date().toLocaleTimeString('id-ID');
                    render(allData);
                })
                .catch(() => {
                    document.getElementById('alfamart_count').innerHTML = '<span style="color:red">Gagal memuat data</span>';
                });
        })();
    </script>

    <!-- Script Alfamidi -->
    <script>
        (function() {
            midiMap = L.map('midi_map', {
                zoomControl: true
            }).setView([-6.2, 106.82], 11);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap'
            }).addTo(midiMap);

            function mkIcon(c) {
                const s = `<svg xmlns="http://www.w3.org/2000/svg" width="30" height="40" viewBox="0 0 30 40">
            <path d="M15 0C7.27 0 1 6.27 1 14c0 10.5 14 26 14 26S29 24.5 29 14C29 6.27 22.73 0 15 0z" fill="${c}" stroke="#fff" stroke-width="1.5"/>
            <circle cx="15" cy="14" r="5.5" fill="#fff"/></svg>`;
                return L.icon({
                    iconUrl: 'data:image/svg+xml;base64,' + btoa(s),
                    iconSize: [30, 40],
                    iconAnchor: [15, 40],
                    popupAnchor: [0, -42]
                });
            }
            const IC = {
                a: mkIcon('#22c55e'),
                n: mkIcon('#ef4444')
            };

            let allData = [],
                grp = L.layerGroup().addTo(midiMap);

            function row(k, v) {
                if (!v && v !== 0) return '';
                return `<div style="display:flex;gap:8px;margin:3px 0;font-size:12px;align-items:flex-start">
            <span style="color:#6b7280;min-width:78px;flex-shrink:0;font-size:11px;text-transform:uppercase;letter-spacing:.3px">${k}</span>
            <span style="color:#1f2937;flex:1;word-break:break-word">${v}</span></div>`;
            }

            function popup(d) {
                const label = statusLabel(d);
                const sc = label === 'Aktif' ? 'background:#dcfce7;color:#166534' : 'background:#fee2e2;color:#991b1b';
                const coord = d.titik_koor_toko || '';
                const mUrl = d.map_toko || (coord ? 'https://www.google.com/maps?q=' + coord : '');
                return `<div>
            <div style="background:#0f3d5c;padding:10px 14px">
                <div style="color:#fff;font-size:13px;font-weight:600">${d.nama_alfamidi||'—'}</div>
                <div style="color:#93c5fd;font-size:11px;margin-top:2px">${d.kode_toko||''}</div>
            </div>
            <div style="padding:10px 14px;background:#fff">
                ${row('Alamat',d.alamat_alfamidi)}
                ${row('PIC',d.pic_toko)}
                ${row('No. HP',d.nomor_hp_pic)}
                ${row('DC',d.nama_dc)}
                ${row('Vendor',d.nama_vendor)}
                ${row('Media',d.media_koneksi)}
                ${row('Bandwidth',d.kapasitas_bandwidth)}
                ${row('IP Address',d.ip_address)}
                ${row('Perangkat',d.jenis_perangkat)}
                ${row('Merk',d.merk_perangkat)}
                ${row('Type',d.type_perangkat_nama)}
                ${row('Serial No.',d.serial_number_perangkat)}
                ${row('Instalasi',d.tanggal_installasi)}
                ${row('Aktivasi',d.tanggal_aktivasi)}
                ${d.keterangan ? row('Keterangan',d.keterangan) : ''}
            </div>
            <div style="padding:8px 14px 10px;border-top:1px solid #f3f4f6;background:#fff;display:flex;align-items:center;justify-content:space-between">
                <span style="${sc};padding:2px 9px;border-radius:20px;font-size:11px;font-weight:600">${label}</span>
                ${mUrl ? `<a href="${mUrl}" target="_blank" style="font-size:11px;color:#185a82;text-decoration:none">&#8599; Buka Maps</a>` : ''}
            </div>
        </div>`;
            }

            function render(data) {
                grp.clearLayers();
                let n = 0;
                data.forEach(d => {
                    if (!d.titik_koor_toko) return;
                    const p = d.titik_koor_toko.split(',');
                    if (p.length < 2) return;
                    const lat = parseFloat(p[0].trim()),
                        lng = parseFloat(p[1].trim());
                    if (isNaN(lat) || isNaN(lng)) return;
                    L.marker([lat, lng], {
                            icon: statusLabel(d) === 'Aktif' ? IC.a : IC.n
                        })
                        .bindPopup(popup(d), {
                            className: 'lf-midi',
                            maxWidth: 260,
                            minWidth: 220
                        })
                        .addTo(grp);
                    n++;
                });
                document.getElementById('midi_count_lbl').textContent = `Menampilkan ${n} dari ${allData.length} toko`;
                if (n > 0) {
                    try {
                        midiMap.fitBounds(grp.getBounds().pad(0.12));
                    } catch (e) {}
                }
            }

            function doFilter() {
                const q = document.getElementById('midi_f_search').value.toLowerCase();
                const st = document.getElementById('midi_f_status').value;
                const dc = document.getElementById('midi_f_dc').value;
                const vn = document.getElementById('midi_f_vendor').value;
                render(allData.filter(d =>
                    (!q || (d.nama_alfamidi || '').toLowerCase().includes(q) || (d.kode_toko || '').toLowerCase().includes(q) || (d.alamat_alfamidi || '').toLowerCase().includes(q)) &&
                    (!st || statusLabel(d) === st) &&
                    (!dc || d.nama_dc === dc) &&
                    (!vn || d.nama_vendor === vn)
                ));
            }
            ['midi_f_search', 'midi_f_status', 'midi_f_dc', 'midi_f_vendor'].forEach(id => {
                document.getElementById(id).addEventListener('input', doFilter);
                document.getElementById(id).addEventListener('change', doFilter);
            });

            function fillSel(id, vals) {
                const s = document.getElementById(id);
                [...new Set(vals.filter(Boolean))].sort().forEach(v => {
                    const o = document.createElement('option');
                    o.value = v;
                    o.textContent = v;
                    s.appendChild(o);
                });
            }

            fetch('<?= site_url('Alfamidi/getMapData') ?>')
                .then(r => {
                    if (!r.ok) throw 0;
                    return r.json();
                })
                .then(res => {
                    allData = Array.isArray(res) ? res : (res.data || []);
                    const a = allData.filter(d => statusLabel(d) === 'Aktif').length;
                    document.getElementById('midi_s_total').textContent = allData.length;
                    document.getElementById('midi_s_aktif').textContent = a;
                    document.getElementById('midi_s_nonaktif').textContent = allData.length - a;
                    document.getElementById('midi_update_lbl').textContent = 'Update: ' + new Date().toLocaleTimeString('id-ID');
                    fillSel('midi_f_dc', allData.map(d => d.nama_dc));
                    fillSel('midi_f_vendor', allData.map(d => d.nama_vendor));
                    render(allData);
                    document.getElementById('midi_map_loader').style.display = 'none';
                })
                .catch(() => {
                    document.getElementById('midi_map_loader').innerHTML = '<p style="color:#dc2626;font-size:13px">Gagal memuat data dari server.</p>';
                });
        })();
    </script>

    <!-- Sidebar, dropdown, submenu, showStore — SATU blok, tanpa duplikat -->
    <script>
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

        // ← SATU fungsi showStore dengan invalidateSize
        function showStore(store, el) {
            document.querySelectorAll('.store-link').forEach(item => item.classList.remove('active-store'));
            el.classList.add('active-store');

            document.getElementById('alfamidiSection').style.display = 'none';
            document.getElementById('lawsonSection').style.display = 'none';
            document.getElementById('alfamartSection').style.display = 'none';
            document.getElementById(store + 'Section').style.display = 'block';

            setTimeout(() => {
                if (store === 'lawson' && lawsonMap) lawsonMap.invalidateSize();
                if (store === 'alfamart' && alfamartMap) alfamartMap.invalidateSize();
                if (store === 'alfamidi' && midiMap) midiMap.invalidateSize();
            }, 50);
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

    <script>
        // PENGHALANG KOSMETIK SAJA — bukan security, mudah dilewati
        document.addEventListener('contextmenu', e => e.preventDefault()); // klik kanan
        document.addEventListener('keydown', e => {
            if (e.key === 'F12') e.preventDefault(); // F12
            if (e.ctrlKey && e.shiftKey && ['I', 'J', 'C'].includes(e.key.toUpperCase())) e.preventDefault();
            if (e.ctrlKey && e.key.toUpperCase() === 'U') e.preventDefault(); // view-source
        });
    </script>
</body>

</html>