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
                        <li><a href="<?= site_url('VPN') ?>" class=" block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">VPN</a></li>
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
            <!-- breadcrumb -->
            <div class="flex items-center justify-between mb-6 gap-4 flex-wrap">
                <h5 class="font-medium text-lg">Nomor INET</h5>

                <a href="<?= site_url('NMRInet/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
                    <i class="ti ti-plus"></i> Tambah
                </a>
            </div>

            <div class="card">
                <div class="card-body">

                    <!-- TOOLBAR: dropdown Show (kiri) + Search & Export (kanan) -->
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div id="lengthArea"></div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <div class="custom-search">
                                <input type="text" id="customSearch" placeholder="search..." />
                                <button class="go-btn" type="button"></button>
                            </div>
                            <div id="exportArea"></div>
                        </div>
                    </div>

                    <!-- TABLE (bisa digeser kiri-kanan saat layar sempit) -->
                    <div class="table-scroll">
                        <table id="dcTable" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Aksi</th>

                                    <th>Kode Layanan Vendor</th>
                                    <th>Nama Vendor / Penyedia Layanan</th>
                                    <th>Nama Layanan</th>
                                    <th>Kecepatan / Bandwidth</th>
                                    <th>Harga Layanan</th>
                                    <th>Nomor INET/ID Pelanggan</th>
                                    <th>Kategori Pelanggan Pengguna</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tbody>

                                <?php if (!empty($md_nomer_inet)) : ?>

                                    <?php $no = 1; ?>

                                    <?php foreach ($md_nomer_inet as $row): ?>

                                        <tr>

                                            <td><?= $no++; ?></td>
                                            <td>

                                                <a href="<?= site_url('NMRInet/edit/' . $row['id']) ?>" class="btn btn-primary btn-sm">
                                                    <i class="ti ti-edit"></i>
                                                </a>

                                                <button onclick="confirmDelete(<?= $row['id'] ?>)" class="btn btn-danger btn-sm">
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                                <br>
                                                <button type="button"
                                                    onclick="openDetailModal(<?= $row['id'] ?>)"
                                                    class="btn btn-sm btn-info">
                                                    <i class="ti ti-eye"></i>
                                                </button>

                                            </td>
                                            <td><?= esc($row['kode_layanan_vendor'] ?? '-'); ?></td>
                                            <td><?= esc($row['nama_vendor']); ?></td>

                                            <td><?= esc($row['nama_paket_layanan']); ?></td>

                                            <td><?= esc($row['kecepatan_bandwidth']); ?></td>

                                            <td>
                                                <?= $row['harga_layanan'] != ''
                                                    ? 'Rp ' . number_format($row['harga_layanan'], 0, ',', '.')
                                                    : '-'; ?>
                                            </td>

                                            <td><?= esc($row['nomor_inet']); ?></td>


                                            <td><?= esc($row['kategori_pelanggan']); ?></td>

                                            <td>

                                                <?php if ($row['status'] == 0): ?>

                                                    <span class="badge badge-paid">Aktif</span>

                                                <?php else: ?>

                                                    <span class="badge badge-due">Non Aktif</span>

                                                <?php endif; ?>

                                            </td>

                                            <td><?= esc($row['keterangan']); ?></td>

                                            <td><?= date('d-m-Y H:i', strtotime($row['created_at'])); ?></td>



                                        </tr>

                                    <?php endforeach; ?>

                                <?php endif; ?>

                            </tbody>
                        </table>
                        <div id="detailModal"
                            class="fixed inset-0 bg-black/50 hidden z-50 flex items-center justify-center p-4">

                            <div class="bg-white rounded-xl shadow-xl w-full max-w-xl overflow-hidden">

                                <!-- Header biru -->
                                <div class="flex justify-between items-center bg-primary-500 text-white px-5 py-3">
                                    <h3 class="font-semibold flex items-center gap-2">
                                        Detail Data Nomor INET
                                    </h3>
                                    <button onclick="closeDetailModal()">
                                        <i class="ti ti-x text-xl"></i>
                                    </button>
                                </div>

                                <div class="p-6 max-h-[75vh] overflow-y-auto">

                                    <!-- Keterangan ringkas -->
                                    <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3 mb-5">
                                        <p class="text-xs text-gray-500 mb-1">Keterangan</p>
                                        <p class="text-sm font-medium" id="detail_ringkas">-</p>
                                    </div>

                                    <!-- Detail data -->
                                    <div class="border rounded-lg divide-y">
                                        <div class="flex justify-between px-4 py-2.5 text-sm">
                                            <span class="text-gray-500">Kode Layanan Vendor</span>
                                            <span class="font-medium text-right" id="detail_kode">-</span>
                                        </div>
                                        <div class="flex justify-between px-4 py-2.5 text-sm">
                                            <span class="text-gray-500">Nama Vendor</span>
                                            <span class="font-medium text-right" id="detail_vendor">-</span>
                                        </div>
                                        <div class="flex justify-between px-4 py-2.5 text-sm">
                                            <span class="text-gray-500">Nama Layanan</span>
                                            <span class="font-medium text-right" id="detail_layanan">-</span>
                                        </div>
                                        <div class="flex justify-between px-4 py-2.5 text-sm">
                                            <span class="text-gray-500">Nomor INET / ID Pelanggan</span>
                                            <span class="font-medium text-right" id="detail_nomor">-</span>
                                        </div>
                                        <div class="flex justify-between px-4 py-2.5 text-sm items-center">
                                            <span class="text-gray-500">Password INET</span>
                                            <span class="flex items-center gap-2">
                                                <span class="font-medium" id="detail_password">••••••••</span>
                                                <button type="button" id="detail_toggle_password"
                                                    class="text-gray-400 hover:text-primary-500">
                                                    <i class="ti ti-eye text-lg"></i>
                                                </button>
                                            </span>
                                        </div>
                                        <div class="flex justify-between px-4 py-2.5 text-sm">
                                            <span class="text-gray-500">Kecepatan Bandwidth</span>
                                            <span class="font-medium text-right" id="detail_bandwidth">-</span>
                                        </div>
                                        <div class="flex justify-between px-4 py-2.5 text-sm">
                                            <span class="text-gray-500">Harga Layanan</span>
                                            <span class="font-medium text-right" id="detail_harga">-</span>
                                        </div>
                                        <div class="flex justify-between px-4 py-2.5 text-sm items-center">
                                            <span class="text-gray-500">Status</span>
                                            <span id="detail_status">-</span>
                                        </div>
                                        <div class="flex justify-between px-4 py-2.5 text-sm">
                                            <span class="text-gray-500">Keterangan</span>
                                            <span class="font-medium text-right" id="detail_keterangan">-</span>
                                        </div>
                                        <div class="flex justify-between px-4 py-2.5 text-sm">
                                            <span class="text-gray-500">Created At</span>
                                            <span class="font-medium text-right" id="detail_created">-</span>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        let detailPasswordValue = '';
        let detailPasswordVisible = false;

        function formatRupiah(angka) {
            if (angka === '') return '';
            return 'Rp ' + String(angka).replace(/\D/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        function openDetailModal(id) {
            fetch("<?= site_url('NMRInet/show/') ?>" + id)
                .then(res => {
                    if (!res.ok) throw new Error('not found');
                    return res.json();
                })
                .then(json => {
                    const d = json.data;

                    document.getElementById('detail_ringkas').textContent =
                        'Data Nomor INET ( ID: ' + d.id + ' ) ';

                    document.getElementById('detail_kode').textContent = d.kode_layanan_vendor ?? '-';
                    document.getElementById('detail_vendor').textContent = d.nama_vendor ?? '-';
                    document.getElementById('detail_layanan').textContent = d.nama_layanan ?? '-';
                    document.getElementById('detail_nomor').textContent = d.nomor_inet ?? '-';
                    document.getElementById('detail_bandwidth').textContent = d.kecepatan_bandwidth ?? '-';
                    document.getElementById('detail_keterangan').textContent = d.keterangan ?? '-';
                    document.getElementById('detail_created').textContent = d.created_at ?? '-';

                    // harga dengan format Rupiah
                    if (d.harga_layanan !== null && d.harga_layanan !== '' && d.harga_layanan !== undefined) {
                        const raw = String(d.harga_layanan).split('.')[0].replace(/\D/g, '');
                        document.getElementById('detail_harga').textContent = formatRupiah(raw);
                    } else {
                        document.getElementById('detail_harga').textContent = '-';
                    }

                    // password: simpan, tampilkan tersembunyi dulu
                    detailPasswordValue = d.password_inet || '';
                    detailPasswordVisible = false;
                    renderDetailPassword();

                    // badge status
                    const statusEl = document.getElementById('detail_status');
                    if (d.status == 0) {
                        statusEl.innerHTML = '<span class="badge badge-paid">Aktif</span>';
                    } else {
                        statusEl.innerHTML = '<span class="badge badge-due">Non Aktif</span>';
                    }

                    document.getElementById('detailModal').classList.remove('hidden');
                })
                .catch(() => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Data tidak ditemukan di database.',
                        confirmButtonColor: '#04a9f5'
                    });
                });
        }

        function renderDetailPassword() {
            const el = document.getElementById('detail_password');
            const icon = document.querySelector('#detail_toggle_password i');
            if (detailPasswordVisible) {
                el.textContent = detailPasswordValue !== '' ? detailPasswordValue : '-';
                icon.classList.replace('ti-eye', 'ti-eye-off');
            } else {
                el.textContent = '••••••••';
                icon.classList.replace('ti-eye-off', 'ti-eye');
            }
        }

        document.getElementById('detail_toggle_password').addEventListener('click', function() {
            detailPasswordVisible = !detailPasswordVisible;
            renderDetailPassword();
        });

        function closeDetailModal() {
            detailPasswordVisible = false;
            renderDetailPassword();
            document.getElementById('detailModal').classList.add('hidden');
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

            const table = $('#dcTable').DataTable({
                pageLength: 10,
                lengthMenu: [
                    [10, 15, 25, 50, -1],
                    [10, 15, 25, 50, "Semua"]
                ],
                order: [
                    [1, 'asc']
                ],
                columnDefs: [{
                    targets: 0, // kolom No
                    orderable: false, // tidak bisa di-sort
                    searchable: false // tidak ikut pencarian
                }],
                dom: "lBfrtip",
                buttons: [{
                    extend: 'collection',
                    text: '<i class="ti ti-download"></i> Export',
                    className: 'export-toggle',
                    buttons: [{
                            extend: 'copyHtml5',
                            text: '<i class="ti ti-copy"></i> Copy',
                            title: 'Data Nomer Inet',
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, button, config);
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            text: '<i class="ti ti-file-text"></i> Export CSV',
                            title: 'Data Nomer Inet',
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            text: '<i class="ti ti-file-spreadsheet"></i> Export Excel',
                            title: 'Data Simcard',
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            text: '<i class="ti ti-file-type-pdf"></i> Export PDF',
                            title: 'Data Nomer Inet',
                            orientation: 'landscape',
                            pageSize: 'A4',
                            ...exportConfig,

                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();

                                $.fn.dataTable.ext.buttons.pdfHtml5.action.call(
                                    this,
                                    e,
                                    dt,
                                    button,
                                    config
                                );
                            },

                            customize: function(doc) {
                                doc.styles.tableHeader = {
                                    fillColor: '#04a9f5',
                                    color: '#fff',
                                    bold: true,
                                    alignment: 'left'
                                };
                                doc.defaultStyle.fontSize = 10;
                                doc.content[1].table.widths = ['3%', '9%', '9%', '8%', '8%', '9%', '7%', '9%', '8%', '7%', '5%', '8%', '10%'];
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
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    infoFiltered: "(filtered from _MAX_ total entries)",
                    emptyTable: "Data Nomer Inet belum tersedia",
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
            // pindahkan dropdown Show & tombol Export ke toolbar custom
            $('#lengthArea').append($('.dataTables_length'));
            $('#exportArea').append($('.dt-buttons'));

            // custom search + tombol Go
            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });
            $('.go-btn').on('click', function() {
                table.search($('#customSearch').val()).draw();
            });
            $('#customSearch').on('keypress', function(e) {
                if (e.which === 13) table.search(this.value).draw();
            });
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