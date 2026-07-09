    <!doctype html>
    <html lang="en" class="light">

    <head>
        <meta charset="utf-8" />
        <title>Change Log</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="icon" type="image/png" href="<?= base_url('store.png') ?>">
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
        <!-- Feather icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

        <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
        <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
        <!-- Font Awesome (for brand & solid icons) -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
                            success: {
                                500: '#1de9b6',
                                600: '#1bd1a3'
                            },
                            warning: {
                                500: '#f4c22b'
                            },
                            danger: {
                                500: '#f44236',
                                600: '#e23b30'
                            },
                            purple: {
                                500: '#7759de'
                            },
                            sidebar: '#1c232f',
                            sidebaractive: '#04a9f5',
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

            /* scrollbar */
            ::-webkit-scrollbar {
                width: 6px;
                height: 6px
            }

            ::-webkit-scrollbar-thumb {
                background: #b9c1c9;
                border-radius: 3px
            }

            .dark ::-webkit-scrollbar-thumb {
                background: #3a4658
            }

            /* card */
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

            .card-header {
                padding: 20px 25px;
                border-bottom: 1px solid #f1f1f1;
            }

            .dark .card-header {
                border-color: #37404c
            }

            .card-header h5 {
                font-size: 16px;
                font-weight: 500;
                margin: 0;
                color: #37474f
            }

            .dark .card-header h5 {
                color: #e7eaf0
            }

            .card-body {
                padding: 25px
            }

            /* sidebar transition */
            .pc-sidebar {
                transition: transform .25s ease, width .25s ease
            }

            .pc-link.active {
                color: #fff !important;
            }

            .pc-link.active .pc-micon {
                color: #04a9f5
            }

            /* dropdown */
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

            .custom-scroll::-webkit-scrollbar {
                width: 6px;
            }

            .custom-scroll::-webkit-scrollbar-track {
                background: transparent;
            }

            .custom-scroll::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 9999px;
            }

            .custom-scroll::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }

            .dark .custom-scroll::-webkit-scrollbar-thumb {
                background: #475569;
            }

            .dark .custom-scroll::-webkit-scrollbar-thumb:hover {
                background: #64748b;
            }

            /* ===== History / Log Aktivitas ===== */
            .filter-input {
                border: 1px solid #d6dde6;
                border-radius: 8px;
                padding: 7px 10px;
                font-size: 13px;
                background: #fff;
                color: #37474f;
            }

            .dark .filter-input {
                background: #1f2a36;
                border-color: #37404c;
                color: #cdd6e2;
            }

            .badge {
                display: inline-flex;
                align-items: center;
                gap: 4px;
                padding: 3px 10px;
                border-radius: 999px;
                font-size: 12px;
                font-weight: 600;
                white-space: nowrap;
            }

            .badge-create {
                background: #e6f9f3;
                color: #0d9c79;
            }

            .badge-update {
                background: #fff4d6;
                color: #a9791c;
            }

            .badge-delete {
                background: #fde8e6;
                color: #d23b30;
            }

            .table-chip {
                display: inline-block;
                padding: 2px 8px;
                border-radius: 6px;
                background: #eef3f8;
                color: #37474f;
                font-size: 12px;
                font-family: monospace;
            }

            .dark .table-chip {
                background: #2b3744;
                color: #cdd6e2;
            }

            .btn-detail {
                width: 32px;
                height: 32px;
                border-radius: 6px;
                background: #04a9f5;
                color: #fff;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                border: none;
                cursor: pointer;
            }

            .btn-detail:hover {
                background: #0396e2;
            }

            .custom-search {
                display: flex;
                align-items: center;
            }

            .custom-search input {
                border: 1px solid #d6dde6;
                border-right: none;
                border-radius: 8px 0 0 8px;
                padding: 8px 12px;
                font-size: 13px;
                outline: none;
            }

            .dark .custom-search input {
                background: #1f2a36;
                border-color: #37404c;
                color: #cdd6e2;
            }

            .custom-search .go-btn {
                width: 38px;
                height: 38px;
                border: 1px solid #04a9f5;
                background: #04a9f5;
                border-radius: 0 8px 8px 0;
                cursor: pointer;
                position: relative;
            }

            .custom-search .go-btn::after {
                content: "\f002";
                font-family: "Font Awesome 6 Free";
                font-weight: 900;
                color: #fff;
                position: absolute;
                inset: 0;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            table.dataTable thead th {
                font-size: 13px;
            }

            table.dataTable tbody td {
                font-size: 13px;
                vertical-align: middle;
            }

            .table-scroll {
                width: 100%;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .table-scroll table {
                min-width: 880px;
            }

            div.dt-buttons .dt-button {
                background: #04a9f5;
                color: #fff;
                border: none;
                border-radius: 8px;
                padding: 8px 14px;
                font-size: 13px;
            }

            div.dt-buttons .dt-button:hover {
                background: #0396e2;
            }
        </style>
        <style>
            /* sembunyikan search bawaan DataTables — pakai custom search saja */
            .dataTables_filter {
                display: none;
            }

            /* ===== TABEL MODERN ===== */
            #logTable {
                width: 100% !important;
                border-collapse: separate;
                border-spacing: 0;
            }

            #logTable thead th {
                background: #f8fafc;
                color: #64748b;
                font-weight: 600;
                font-size: 11px;
                text-transform: uppercase;
                letter-spacing: .04em;
                text-align: left;
                padding: 14px 16px;
                border-bottom: 1px solid #e8edf2;
                white-space: nowrap;
            }

            .dark #logTable thead th {
                background: #2b3543;
                color: #9fb0c2;
                border-color: #37404c;
            }

            #logTable tbody td {
                padding: 13px 16px;
                font-size: 13px;
                color: #3b4754;
                border-bottom: 1px solid #f1f4f7;
                vertical-align: middle;
            }

            .dark #logTable tbody td {
                color: #bfc8d6;
                border-color: #37404c;
            }

            #logTable tbody tr {
                transition: background .15s;
            }

            #logTable tbody tr:hover {
                background: #f6fbff;
            }

            .dark #logTable tbody tr:hover {
                background: rgba(255, 255, 255, .03);
            }

            #logTable tbody tr:last-child td {
                border-bottom: none;
            }

            /* ===== Length & Pagination polish ===== */
            .dataTables_length select {
                border: 1px solid #d6dde6;
                border-radius: 8px;
                padding: 8px 30px 8px 12px;
                font-size: 13px;
                background: #fff;
                color: #37474f;
                min-width: 110px;
                cursor: pointer;
            }

            .dark .dataTables_length select {
                background: #1f2a36;
                border-color: #37404c;
                color: #cdd6e2;
            }

            .dataTables_paginate {
                margin-top: 14px;
                font-size: 13px;
            }

            .dataTables_paginate .paginate_button {
                padding: 6px 12px !important;
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
                background: #eef2f6 !important;
                color: #3b4754 !important;
            }

            .dataTables_info {
                font-size: 13px;
                color: #8a95a1;
                margin-top: 14px;
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

                <div id="successAlert"
                    class="fixed top-5 left-1/2 -translate-x-1/2 z-50 w-full max-w-md px-4">

                    <div class="bg-green-500 text-white rounded-xl shadow-xl overflow-hidden">

                        <div class="flex items-center gap-3 px-5 py-4">

                            <i class="ti ti-circle-check text-3xl"></i>

                            <div>

                                <h4 class="font-bold">
                                    Login Berhasil!
                                </h4>

                                <p class="text-sm">
                                    <?= session()->getFlashdata('success') ?>
                                </p>

                            </div>

                        </div>

                        <div class="h-1 bg-green-400">

                            <div id="progressBar"
                                class="h-full bg-white w-full">
                            </div>

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
                            class="pc-link active flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">

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

                    <!-- settings -->

                    <!-- notifications -->

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
                                <button onclick="window.location.href='<?= site_url('logout') ?>'"
                                    class="w-full mt-3 bg-primary-500 hover:bg-red-600 text-white py-2 rounded flex items-center justify-center gap-2 text-sm">

                                    <i class="fas fa-sign-out-alt"></i>
                                    Logout

                                </button>
                            </div>
                        </div>
                    </li>
                </ul>
            </header>

            <!-- CONTENT -->
            <div class="p-6">
                <!-- breadcrumb -->
                <div class="flex items-center justify-between mb-6">
                    <h5 class="font-medium text-lg">Change Log</h5>
                </div>


                <!-- TABEL HISTORY -->
                <div class="card">
                    <div class="card-body">
                        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                            <div id="lengthArea"></div>
                            <div class="flex items-center gap-3 flex-wrap">
                                <!-- Filter urutan baru / lama -->
                                <select id="sortOrder" class="filter-input" style="min-width:130px;cursor:pointer">
                                    <option value="desc">Terbaru</option>
                                    <option value="asc">Terlama</option>
                                </select>
                                <div class="custom-search">
                                    <input type="text" id="customSearch" placeholder="search..." />
                                    <button class="go-btn" type="button"></button>
                                </div>
                                <div id="exportArea"></div>
                            </div>
                        </div>
                        <div class="table-scroll">
                            <table id="logTable" class="display nowrap" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Username</th>
                                        <th>Tanggal</th>
                                        <th>Jam</th>
                                        <th>Aksi</th>
                                        <th>Halaman</th>
                                        <th>Keterangan</th>
                                        <th>Detail</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php helper('activity'); ?>
                                    <?php if (!empty($logs)) : ?>
                                        <?php $no = 1; ?>
                                        <?php foreach ($logs as $row) : ?>
                                            <?php $ts = strtotime($row['created_at']); ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td class="font-medium"><?= esc($row['username'] ?? '-') ?></td>
                                                <td data-order="<?= $ts ?>"><?= date('d-m-Y', $ts) ?></td>
                                                <td><?= date('H:i:s', $ts) ?></td>
                                                <td>
                                                    <?php $act = strtolower($row['action']); ?>
                                                    <?php if ($act === 'create') : ?>
                                                        <span class="badge badge-create"><i class="ti ti-plus"></i> Tambah</span>
                                                    <?php elseif ($act === 'update') : ?>
                                                        <span class="badge badge-update"><i class="ti ti-edit"></i> Ubah</span>
                                                    <?php elseif ($act === 'delete') : ?>
                                                        <span class="badge badge-delete"><i class="ti ti-trash"></i> Hapus</span>
                                                    <?php else : ?>
                                                        <span class="badge"><?= esc($row['action']) ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><span class="table-chip"><?= esc(activity_page_name($row['table_name'])) ?></span></td>
                                                <td><?= esc(activity_format_description($row['description'])) ?></td>
                                                <td>
                                                    <button type="button" class="btn-detail"
                                                        data-user="<?= esc($row['username'] ?? '-', 'attr') ?>"
                                                        data-action="<?= esc(activity_label_action($row['action']), 'attr') ?>"
                                                        data-table="<?= esc(activity_page_name($row['table_name']), 'attr') ?>"
                                                        data-record="<?= esc($row['record_id'] ?? '-', 'attr') ?>"
                                                        data-time="<?= date('d-m-Y H:i:s', $ts) ?>"
                                                        data-desc="<?= esc(activity_format_description($row['description']), 'attr') ?>"
                                                        data-old='<?= esc($row['old_data'] ?? '', 'attr') ?>'
                                                        data-new='<?= esc($row['new_data'] ?? '', 'attr') ?>'>
                                                        <i class="ti ti-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODAL DETAIL -->
            <div id="detailModal" class="fixed inset-0 z-[2000] hidden items-center justify-center bg-black/50 p-4">
                <div class="bg-white dark:bg-[#263240] rounded-xl shadow-2xl w-full max-w-2xl max-h-[85vh] overflow-hidden flex flex-col">
                    <div class="flex items-center justify-between px-5 py-4 bg-primary-500 text-white">
                        <h3 class="font-semibold flex items-center gap-2"><i class="ti ti-history"></i> Detail Aktivitas</h3>
                        <button onclick="closeDetail()" class="hover:opacity-80"><i class="ti ti-x text-xl"></i></button>
                    </div>
                    <div class="p-5 overflow-y-auto custom-scroll text-sm" id="detailBody"></div>
                </div>
            </div>
        </div>
        <script>
            // ---- Search merek perangkat ----
            (function() {
                const input = document.getElementById('merekSearch');
                if (!input) return;
                const rows = document.querySelectorAll('#merekList .merek-row');
                const emptyBox = document.getElementById('merekEmpty');

                input.addEventListener('keyup', function() {
                    const keyword = this.value.toLowerCase().trim();
                    let visible = 0;
                    rows.forEach(function(row) {
                        const match = row.innerText.toLowerCase().includes(keyword);
                        row.style.display = match ? '' : 'none';
                        if (match) visible++;
                    });
                    if (emptyBox) {
                        if (visible === 0) {
                            emptyBox.textContent = rows.length === 0 ? 'Data perangkat belum tersedia' : 'Merek tidak ditemukan';
                            emptyBox.classList.remove('hidden');
                        } else {
                            emptyBox.classList.add('hidden');
                        }
                    }
                });
            })();
        </script>

        <script>
            document.getElementById('vendorSearch').addEventListener('keyup', function() {

                let keyword = this.value.toLowerCase();
                let found = false;

                document.querySelectorAll('.vendor-row').forEach(function(row) {

                    let text = row.textContent.toLowerCase();

                    if (text.includes(keyword)) {
                        row.style.display = '';
                        found = true;
                    } else {
                        row.style.display = 'none';
                    }

                });

                document.getElementById('vendorEmpty').classList.toggle('hidden', found);

            });
        </script>
        <script>
            // ---- Sidebar toggle ----
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
            // Tutup sidebar di mobile kalau klik di luar area sidebar & tombol menu
            document.addEventListener('click', function(e) {
                if (window.innerWidth < 1024) {
                    const sb = document.getElementById('sidebar');
                    const menuBtn = e.target.closest('[onclick*="toggleSidebar"]');
                    if (sb.classList.contains('mobile-open') && !sb.contains(e.target) && !menuBtn) {
                        sb.classList.remove('mobile-open');
                    }
                }
                // tutup dropdown header (gabung dengan logika lama)
                document.querySelectorAll('.dropdown-menu.show').forEach(m => m.classList.remove('show'));
            });
            // ---- Submenu ----
            function toggleSub(el) {

                const parent = el.closest('.hasmenu');
                const sub = parent.querySelector('.submenu');
                const arrow = parent.querySelector('.arrow');

                sub.classList.toggle('open');

                if (arrow) {

                    arrow.style.transform = sub.classList.contains('open') ?
                        'rotate(90deg)' :
                        'rotate(0deg)';
                }
            }

            // ---- Theme ----
            function setTheme(t) {
                if (t === 'dark') document.documentElement.classList.add('dark');
                else document.documentElement.classList.remove('dark');
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

                        alertBox.style.transition = "all 0.5s ease";

                        alertBox.style.opacity = "0";

                        alertBox.style.transform =
                            "translate(-50%, -20px)";

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
        <script>
            $(document).ready(function() {
                var table = $('#logTable').DataTable({
                    pageLength: 10,
                    lengthMenu: [
                        [10, 15, 25, 50, -1],
                        [10, 15, 25, 50, "Semua"]
                    ],
                    order: [
                        [0, 'asc']
                    ],
                    columnDefs: [{
                        targets: [0, 7],
                        orderable: false,
                        searchable: false
                    }],
                    order: [
                        [2, 'desc'] // urutkan berdasarkan Tanggal, terbaru dulu
                    ],

                    language: {
                        lengthMenu: "_MENU_",
                        info: "Menampilkan _START_-_END_ dari _TOTAL_ aktivitas",
                        infoEmpty: "Tidak ada aktivitas",
                        infoFiltered: "(disaring dari _MAX_ total)",
                        emptyTable: "Belum ada aktivitas tercatat",
                        zeroRecords: "Tidak ada aktivitas yang cocok dengan pencarian",
                        paginate: {
                            previous: "Sebelumnya",
                            next: "Berikutnya"
                        }
                    }
                });

                // penomoran ulang kolom No mengikuti urutan & halaman
                table.on('draw.dt order.dt search.dt', function() {
                    var i = table.page.info().start;
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

                $('#customSearch').on('keyup', function() {
                    table.search(this.value).draw();
                });
                $('.go-btn').on('click', function() {
                    table.search($('#customSearch').val()).draw();
                });
                $('#customSearch').on('keypress', function(e) {
                    if (e.which === 13) table.search(this.value).draw();
                });

                $('#sortOrder').on('change', function() {
                    table.order([2, this.value]).draw(); // kolom 2 = Tanggal (punya data-order timestamp)
                });

                // ubah nama field jadi label rapi: nama_dc -> "Nama DC", media_koneksi_id -> "Media Koneksi"
                function prettyField(key) {
                    var k = String(key).replace(/_id$/, '').replace(/_/g, ' ').trim();
                    // singkatan yang ingin tetap kapital
                    var upper = {
                        dc: 'DC',
                        ip: 'IP',
                        id: 'ID',
                        hp: 'HP',
                        pic: 'PIC',
                        dns: 'DNS',
                        url: 'URL',
                        sn: 'SN'
                    };
                    return k.split(' ').map(function(w) {
                        var lw = w.toLowerCase();
                        if (upper[lw]) return upper[lw];
                        return w.charAt(0).toUpperCase() + w.slice(1);
                    }).join(' ');
                }

                function renderSnapshot(jsonStr) {
                    if (!jsonStr) return '<em class="text-gray-400">-</em>';
                    try {
                        var obj = JSON.parse(jsonStr);
                        var keys = Object.keys(obj);
                        if (!keys.length) return '<em class="text-gray-400">-</em>';
                        var rows = keys.map(function(k) {
                            var v = obj[k];

                            // ===== Khusus field status: 0/aktif => Aktif (hijau), lainnya => Non Aktif (merah) =====
                            if (String(k).toLowerCase() === 'status') {
                                var sv = String(v === null ? '' : v).toLowerCase().trim();
                                var isAktif = (sv === '0' || sv === 'aktif');
                                var label = isAktif ? 'Aktif' : 'Non Aktif';
                                var cls = isAktif ?
                                    'bg-success-600 text-white' :
                                    'bg-danger-500 text-white';
                                var badge = '<span class="inline-block px-3 py-1 rounded-full text-xs font-semibold ' + cls + '">' + label + '</span>';
                                return '<tr><td class="pr-3 py-1 font-medium align-top whitespace-nowrap">' + prettyField(k) +
                                    '</td><td class="py-1 break-all">' + badge + '</td></tr>';
                            }

                            if (v === null) v = '';
                            var safe = String(v).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                            if (safe === '') safe = '<em class="opacity-50">kosong</em>';
                            return '<tr><td class="pr-3 py-1 font-medium align-top whitespace-nowrap">' + prettyField(k) +
                                '</td><td class="py-1 break-all">' + safe + '</td></tr>';
                        }).join('');
                        return '<table class="w-full text-sm">' + rows + '</table>';
                    } catch (e) {
                        return '<em class="text-gray-400">-</em>';
                    }
                }

                $('#logTable').on('click', '.btn-detail', function() {
                    var d = this.dataset;
                    var html =
                        '<div class="mb-4 p-3 rounded-lg bg-gray-50 dark:bg-white/5 text-sm">' +
                        '<div class="text-gray-500 text-xs mb-1">Keterangan</div>' +
                        '<div class="font-medium">' + (d.desc || '-') + '</div>' +
                        '</div>' +
                        '<div class="grid grid-cols-2 gap-3 mb-4">' +
                        '<div><div class="text-gray-500 text-xs">User</div><div class="font-medium">' + d.user + '</div></div>' +
                        '<div><div class="text-gray-500 text-xs">Waktu</div><div class="font-medium">' + d.time + '</div></div>' +
                        '<div><div class="text-gray-500 text-xs">Aksi</div><div class="font-medium">' + d.action + '</div></div>' +
                        '<div><div class="text-gray-500 text-xs">Halaman</div><div class="font-medium">' + d.table + '</div></div>' +
                        '<div><div class="text-gray-500 text-xs">ID Baris</div><div class="font-medium">' + d.record + '</div></div>' +
                        '</div>' +
                        '<div class="grid md:grid-cols-2 gap-4">' +
                        '<div><div class="text-xs font-semibold text-red-500 mb-1">Data Lama</div><div class="border dark:border-white/10 rounded p-2">' + renderSnapshot(d.old) + '</div></div>' +
                        '<div><div class="text-xs font-semibold text-green-600 mb-1">Data Baru</div><div class="border dark:border-white/10 rounded p-2">' + renderSnapshot(d.new) + '</div></div>' +
                        '</div>';
                    document.getElementById('detailBody').innerHTML = html;
                    var m = document.getElementById('detailModal');
                    m.classList.remove('hidden');
                    m.classList.add('flex');
                });
            });

            function closeDetail() {
                var m = document.getElementById('detailModal');
                m.classList.add('hidden');
                m.classList.remove('flex');
            }
            document.getElementById('detailModal').addEventListener('click', function(e) {
                if (e.target === this) closeDetail();
            });
        </script>
    </body>

    </html>