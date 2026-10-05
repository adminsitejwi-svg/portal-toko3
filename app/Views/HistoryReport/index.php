<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('store.png') ?>">
    <title>Daily Report</title>
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
                <li class="ml-2">
                    <!-- filter Kategori Daily Report -->
                    <select id="filterKategori" class="border rounded-lg px-3 py-2 text-sm bg-white dark:bg-[#263240] max-w-[200px]" title="Filter Kategori">
                        <?php foreach ($kategoriOptions as $opt) : ?>
                            <option value="<?= esc($opt, 'attr') ?>"><?= esc($opt) ?></option>
                        <?php endforeach; ?>
                    </select>
                </li>
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
            <!-- breadcrumb -->
            <div class="flex items-center justify-between mb-6">
                <h5 class="font-medium text-lg">Daily Report</h5>
                <button type="button" onclick="openReportModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition">
                    <i class="ti ti-plus"></i> Tambah
                </button>
            </div>

            <?php
            // Badge warna per shift (mengikuti warna Jadwal NOC: hijau / kuning / biru)
            $shiftBadge = [
                'Pagi'  => 'badge-paid',
                'Siang' => 'badge-pending',
                'Malam' => 'badge-service',
            ];
            $hm = static fn ($t) => $t ? substr($t, 0, 5) : '-';

            // Card rekap per jenis data Shift Handover (jumlah dari database)
            $dataCards = [
                'gangguan'    => ['label' => 'Gangguan', 'icon' => '🚨', 'color' => 'rekap-red'],
                'followup'    => ['label' => 'Follow Up', 'icon' => '🔔', 'color' => 'rekap-amber'],
                'pengiriman'  => ['label' => 'Pengiriman', 'icon' => '📦', 'color' => 'rekap-blue'],
                'maintenance' => ['label' => 'Maintenance', 'icon' => '🛠️', 'color' => 'rekap-green'],
            ];
            ?>

            <!-- REKAP PER JENIS DATA -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
                <?php foreach ($dataCards as $key => $card) : ?>
                    <div class="card mb-0">
                        <div class="card-body">
                            <div class="flex items-center gap-4">
                                <div class="grup-icon <?= $card['color'] ?>"><?= $card['icon'] ?></div>
                                <div>
                                    <div class="text-[12px] uppercase tracking-wide text-gray-500 font-semibold"><?= $card['label'] ?></div>
                                    <div class="text-2xl font-bold leading-tight"><?= $dataCount[$key] ?? 0 ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="card">
                <div class="card-body">

                    <!-- TOOLBAR -->
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

                    <!-- TABLE -->
                    <div class="table-scroll">
                        <table id="reportTable" class="display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Aksi</th>
                                    <th>Tanggal</th>
                                    <th>Shift</th>
                                    <th>Kategori</th>
                                    <th>PIC Shift</th>
                                    <th>Periode</th>
                                    <?php foreach ($grupList as $g) : ?>
                                        <th class="text-center"><?= esc($g) ?></th>
                                    <?php endforeach; ?>
                                    <th>Status</th>
                                    <th>Ringkasan / Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($report)) : ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($report as $row) : ?>
                                        <tr>
                                            <td><?= $no++; ?></td>
                                            <td>
                                                <!-- semua aksi dalam satu menu titik tiga -->
                                                <button type="button"
                                                    data-row="<?= esc(json_encode($row), 'attr') ?>"
                                                    data-settings="<?= site_url('HistoryReport/gangguan/' . $row['id']) ?>"
                                                    data-laporan="<?= site_url('HistoryReport/laporan/' . $row['id']) ?>"
                                                    data-status="<?= array_sum($grupCount[$row['id']] ?? []) > 0 ? 'Completed' : 'Draft' ?>"
                                                    data-copy="<?= esc($copyText[$row['id']] ?? '', 'attr') ?>"
                                                    onclick="toggleAksiMenu(event, this)"
                                                    title="Aksi"
                                                    class="aksi-btn">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                            </td>
                                            <td data-order="<?= esc($row['tanggal']) ?>"><?= date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                                            <td><span class="badge <?= $shiftBadge[$row['shift']] ?? 'badge-service' ?>"><?= esc($row['shift']); ?></span></td>
                                            <td class="whitespace-normal min-w-[140px]"><?= esc($row['kategori'] ?: '-'); ?></td>
                                            <td class="whitespace-normal min-w-[160px]"><?= esc($row['pic_shift']); ?></td>
                                            <td data-order="<?= esc($row['jam_mulai'] ?? '') ?>"><?= $hm($row['jam_mulai']); ?> - <?= $hm($row['jam_selesai']); ?></td>
                                            <?php foreach ($grupList as $g) : ?>
                                                <?php $n = $grupCount[$row['id']][$g] ?? 0; ?>
                                                <td class="text-center"><span class="grup-count <?= $n ? 'has' : '' ?>"><?= $n ?></span></td>
                                            <?php endforeach; ?>
                                            <?php
                                            // Draft = Shift Handover (gangguan/follow up/pengiriman/maintenance/catatan) belum diisi
                                            $isiHandover = array_sum($grupCount[$row['id']] ?? []);
                                            ?>
                                            <td>
                                                <?php if ($isiHandover > 0) : ?>
                                                    <span class="badge badge-paid"><i class="ti ti-circle-check mr-1"></i>Completed</span>
                                                <?php else : ?>
                                                    <span class="badge badge-draft"><i class="ti ti-pencil mr-1"></i>Draft</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="whitespace-pre-line min-w-[320px]"><?= esc($row['ringkasan']); ?></td>
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

    <!-- ====== POP UP TAMBAH / EDIT ====== -->
    <div id="reportModal" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold" id="reportModalTitle">Tambah Daily Report</h3>
                <button type="button" onclick="closeReportModal()"><i class="ti ti-x text-2xl"></i></button>
            </div>

            <form action="<?= site_url('HistoryReport/save') ?>" method="POST" id="reportForm">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="r_id">
                <input type="hidden" name="duplikat_dari" id="r_duplikat_dari">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium">Tanggal <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal" id="r_tanggal" required class="w-full border rounded-lg p-3">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Shift <span class="text-red-500">*</span></label>
                        <select name="shift" id="r_shift" required class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih Shift --</option>
                            <option value="Pagi">Pagi</option>
                            <option value="Siang">Siang</option>
                            <option value="Malam">Malam</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium">Kategori <span class="text-red-500">*</span></label>
                        <select name="kategori" id="r_kategori" required class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($kategoriOptions as $opt) : ?>
                                <option value="<?= esc($opt, 'attr') ?>"><?= esc($opt) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium">PIC Shift <span class="text-red-500">*</span></label>
                        <!-- nama diambil dari data shift (jadwal_noc), seperti Jadwal Piket -->
                        <select name="pic_shift" id="r_pic_shift" required class="w-full border rounded-lg p-3">
                            <option value="">-- Pilih Nama --</option>
                            <?php foreach ($picOptions as $nama) : ?>
                                <option value="<?= esc($nama, 'attr') ?>"><?= esc($nama) ?></option>
                            <?php endforeach; ?>
                            <option value="__manual__">Isi Manual...</option>
                        </select>
                        <!-- muncul saat "Isi Manual" dipilih -->
                        <input type="text" name="pic_manual" id="r_pic_manual" maxlength="255" placeholder="Masukan Nama" class="w-full border rounded-lg p-3 mt-2 hidden">
                        <?php if (empty($picOptions)) : ?>
                            <p class="text-[12px] text-gray-500 mt-1">Belum ada nama di data Shift, pilih "Isi Manual" untuk mengetik nama.</p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label class="text-sm font-medium">Jam Mulai <span class="text-red-500">*</span></label>
                        <input type="time" name="jam_mulai" id="r_jam_mulai" required class="w-full border rounded-lg p-3">
                    </div>
                    <div>
                        <label class="text-sm font-medium">Jam Selesai <span class="text-red-500">*</span></label>
                        <input type="time" name="jam_selesai" id="r_jam_selesai" required class="w-full border rounded-lg p-3">
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium">Ringkasan / Keterangan <span class="text-red-500">*</span></label>
                        <textarea name="ringkasan" id="r_ringkasan" rows="5" required placeholder="Masukkan ringkasan kejadian selama shift" class="w-full border rounded-lg p-3"></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeReportModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Batal</button>
                    <button type="submit" id="reportSubmit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ====== POP UP VIEW (data d_report) ====== -->
    <div id="viewModal" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4" onclick="if (event.target === this) closeViewModal()">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold">View Daily Report</h3>
                <button type="button" onclick="closeViewModal()"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <div class="text-gray-500">Tanggal</div>
                    <div class="font-medium" id="v_tanggal"></div>
                </div>
                <div>
                    <div class="text-gray-500">Shift</div>
                    <div class="font-medium" id="v_shift"></div>
                </div>
                <div>
                    <div class="text-gray-500">Kategori</div>
                    <div class="font-medium" id="v_kategori"></div>
                </div>
                <div>
                    <div class="text-gray-500">PIC Shift</div>
                    <div class="font-medium" id="v_pic_shift"></div>
                </div>
                <div>
                    <div class="text-gray-500">Periode</div>
                    <div class="font-medium" id="v_periode"></div>
                </div>
                <div>
                    <div class="text-gray-500">Status</div>
                    <div><span id="v_status" class="badge"></span></div>
                </div>
                <div>
                    <div class="text-gray-500">Dibuat</div>
                    <div class="font-medium" id="v_created"></div>
                </div>
                <div class="md:col-span-2">
                    <div class="text-gray-500">Ringkasan / Keterangan</div>
                    <div class="font-medium whitespace-pre-line" id="v_ringkasan"></div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeViewModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Tutup</button>
            </div>
        </div>
    </div>

    <!-- ====== MENU AKSI (titik tiga) ====== -->
    <div id="aksiMenu" class="hidden" role="menu">
        <div class="aksi-title">Aksi</div>
        <button type="button" onclick="aksiRun('edit')"><i class="ti ti-edit"></i> Edit</button>
        <button type="button" onclick="aksiRun('view')"><i class="ti ti-eye"></i> View</button>
        <button type="button" onclick="aksiRun('detail')"><i class="ti ti-file-description"></i> Detail (Shift Laporan)</button>
        <button type="button" onclick="aksiRun('duplikat')"><i class="ti ti-copy-plus"></i> Duplikat</button>
        <button type="button" onclick="aksiRun('copy')"><i class="ti ti-copy"></i> Copy</button>
        <button type="button" onclick="aksiRun('settings')"><i class="ti ti-tool"></i> Settings (Shift Handover)</button>
        <hr>
        <button type="button" onclick="aksiRun('hapus')" class="danger"><i class="ti ti-trash"></i> Hapus</button>
    </div>

    <script>
        // ====== MENU AKSI (titik tiga) ======
        let aksiRow = null, aksiSettings = '', aksiCopy = '', aksiLaporan = '', aksiStatus = '', aksiOwner = null;

        function toggleAksiMenu(e, btn) {
            e.stopPropagation();
            const menu = document.getElementById('aksiMenu');
            if (aksiOwner === btn && !menu.classList.contains('hidden')) return closeAksiMenu();

            closeAksiMenu();
            aksiRow = JSON.parse(btn.dataset.row);
            aksiSettings = btn.dataset.settings;
            aksiCopy = btn.dataset.copy;
            aksiLaporan = btn.dataset.laporan;
            aksiStatus = btn.dataset.status;
            aksiOwner = btn;
            btn.classList.add('open');
            menu.classList.remove('hidden');

            // di bawah tombol; pindah ke atas / geser kiri jika mentok layar
            const r = btn.getBoundingClientRect();
            const mw = menu.offsetWidth, mh = menu.offsetHeight;
            let top = r.bottom + 4, left = r.left;
            if (top + mh > window.innerHeight) top = Math.max(8, r.top - mh - 4);
            if (left + mw > window.innerWidth) left = window.innerWidth - mw - 8;
            menu.style.top = top + 'px';
            menu.style.left = left + 'px';
        }

        function closeAksiMenu() {
            document.getElementById('aksiMenu').classList.add('hidden');
            if (aksiOwner) aksiOwner.classList.remove('open');
            aksiOwner = null;
        }

        function aksiRun(aksi) {
            const d = aksiRow;
            closeAksiMenu();
            if (!d) return;
            if (aksi === 'edit') openReportModal(d);
            if (aksi === 'view') openViewModal(d, aksiStatus);
            if (aksi === 'detail') window.location.href = aksiLaporan;
            if (aksi === 'duplikat') duplikatReport(d);
            if (aksi === 'copy') copyReport(aksiCopy);
            if (aksi === 'settings') window.location.href = aksiSettings;
            if (aksi === 'hapus') confirmDelete(d.id);
        }

        document.addEventListener('click', e => {
            if (!e.target.closest('#aksiMenu')) closeAksiMenu();
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeAksiMenu();
        });
        window.addEventListener('scroll', closeAksiMenu, true);
        window.addEventListener('resize', closeAksiMenu);

        // ====== DUPLIKAT / COPY / DETAIL ======
        const hm = t => t ? String(t).substring(0, 5) : '-';
        const tglIndo = t => t ? t.split('-').reverse().join('-') : '-';

        // Duplikat: buka form tambah dengan isian baris ini, tanggal diganti hari ini.
        // Gangguan, follow up, pengiriman, maintenance & catatan ikut disalin di server saat disimpan.
        function duplikatReport(d) {
            // Shift otomatis maju ke shift berikutnya: Pagi -> Siang -> Malam -> Pagi
            const urutShift = ['Pagi', 'Siang', 'Malam'];
            const idx = urutShift.indexOf(d.shift);
            const shiftBaru = idx === -1 ? d.shift : urutShift[(idx + 1) % urutShift.length];
            openReportModal({ ...d, id: '', tanggal: todayStr(), shift: shiftBaru });
            document.getElementById('r_duplikat_dari').value = d.id;
            document.getElementById('reportModalTitle').textContent = 'Duplikat Daily Report';
        }

        // Copy: salin "NOC SHIFT REPORT" (format WhatsApp, disusun di server) ke clipboard
        function copyReport(text) {
            const done = () => Swal.fire({ icon: 'success', title: 'Tersalin', text: 'Report siap di-paste ke WhatsApp.', timer: 1500, showConfirmButton: false });
            const fallback = () => {
                // untuk akses lewat http non-localhost (clipboard API tidak tersedia)
                const ta = document.createElement('textarea');
                ta.value = text;
                ta.style.position = 'fixed';
                ta.style.opacity = '0';
                document.body.appendChild(ta);
                ta.select();
                const ok = document.execCommand('copy');
                ta.remove();
                ok ? done() : Swal.fire({ icon: 'error', title: 'Gagal', text: 'Data tidak dapat disalin.' });
            };

            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(done, fallback);
            } else {
                fallback();
            }
        }

        // View: tampilkan data report (d_report) di pop up
        function openViewModal(d, status) {
            document.getElementById('v_tanggal').textContent = tglIndo(d.tanggal);
            document.getElementById('v_shift').textContent = d.shift || '-';
            document.getElementById('v_kategori').textContent = d.kategori || '-';
            document.getElementById('v_pic_shift').textContent = d.pic_shift || '-';
            document.getElementById('v_periode').textContent = hm(d.jam_mulai) + ' - ' + hm(d.jam_selesai);
            document.getElementById('v_created').textContent = d.created_at ? tglIndo(d.created_at.substring(0, 10)) + ' ' + d.created_at.substring(11, 16) : '-';
            document.getElementById('v_ringkasan').textContent = d.ringkasan || '-';

            const st = document.getElementById('v_status');
            st.textContent = status || 'Draft';
            st.className = 'badge ' + (status === 'Completed' ? 'badge-paid' : 'badge-draft');

            document.getElementById('viewModal').classList.remove('hidden');
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }
    </script>

    <script>
        // ====== POP UP TAMBAH / EDIT ======
        const URL_SAVE = "<?= site_url('HistoryReport/save') ?>";
        const URL_UPDATE = "<?= site_url('HistoryReport/update') ?>";

        function togglePicManual(focus) {
            const manual = document.getElementById('r_pic_shift').value === '__manual__';
            const inp = document.getElementById('r_pic_manual');
            inp.classList.toggle('hidden', !manual);
            inp.required = manual;
            if (manual && focus) inp.focus();
        }
        document.getElementById('r_pic_shift').addEventListener('change', () => togglePicManual(true));

        // Pilih PIC; nama yang tidak ada lagi di data shift tetap ditampilkan (seperti Jadwal Piket)
        function setPic(nama) {
            const sel = document.getElementById('r_pic_shift');
            sel.querySelectorAll('option[data-extra]').forEach(o => o.remove());
            if (nama && ![...sel.options].some(o => o.value === nama)) {
                const opt = new Option(nama, nama);
                opt.dataset.extra = '1';
                sel.add(opt, sel.options[sel.options.length - 1]); // sebelum "Isi Manual"
            }
            sel.value = nama || '';
        }

        function todayStr() {
            const t = new Date();
            return t.getFullYear() + '-' + String(t.getMonth() + 1).padStart(2, '0') + '-' + String(t.getDate()).padStart(2, '0');
        }

        // d kosong = tambah; d berisi data baris = edit
        function openReportModal(d) {
            d = d || {};
            const isEdit = !!d.id;
            const f = document.getElementById('reportForm');
            f.reset();
            f.action = isEdit ? URL_UPDATE : URL_SAVE;
            document.getElementById('reportModalTitle').textContent = isEdit ? 'Edit Daily Report' : 'Tambah Daily Report';
            document.getElementById('reportSubmit').textContent = isEdit ? 'Update' : 'Simpan';

            document.getElementById('r_id').value = d.id || '';
            document.getElementById('r_duplikat_dari').value = '';
            document.getElementById('r_tanggal').value = d.tanggal || todayStr();
            document.getElementById('r_shift').value = d.shift || '';
            document.getElementById('r_kategori').value = d.kategori || '';
            setPic(d.pic_shift || '');
            document.getElementById('r_pic_manual').value = d.pic_manual || '';
            document.getElementById('r_jam_mulai').value = (d.jam_mulai || '').substring(0, 5);
            document.getElementById('r_jam_selesai').value = (d.jam_selesai || '').substring(0, 5);
            document.getElementById('r_ringkasan').value = d.ringkasan || '';
            togglePicManual(false);

            document.getElementById('reportModal').classList.remove('hidden');
        }

        function closeReportModal() {
            document.getElementById('reportModal').classList.add('hidden');
        }

        // ====== VALIDASI SEBELUM SUBMIT ======
        document.getElementById('reportForm').addEventListener('submit', function(e) {
            const wajib = ['r_tanggal', 'r_shift', 'r_kategori', 'r_pic_shift', 'r_jam_mulai', 'r_jam_selesai', 'r_ringkasan']
                .map(id => document.getElementById(id).value.trim());
            if (document.getElementById('r_pic_shift').value === '__manual__') {
                wajib.push(document.getElementById('r_pic_manual').value.trim());
            }

            if (wajib.includes('')) {
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

        <?php
        // Gagal validasi di server: buka lagi pop up dengan isian terakhir
        $oldKeys = ['id', 'tanggal', 'shift', 'kategori', 'pic_shift', 'pic_manual', 'jam_mulai', 'jam_selesai', 'ringkasan'];
        $oldData = [];
        foreach ($oldKeys as $k) {
            $oldData[$k] = old($k, null, false);
        }
        ?>
        <?php if ($oldData['tanggal'] !== null || $oldData['shift'] !== null) : ?>
            openReportModal(<?= json_encode($oldData, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>);
        <?php endif; ?>
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
                    window.location.href = "<?= site_url('HistoryReport/delete/') ?>" + id;
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
                            title: 'Data Daily Report',
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, button, config);
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            text: '<i class="ti ti-file-text"></i> Export CSV',
                            title: 'Data Daily Report',
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            text: '<i class="ti ti-file-spreadsheet"></i> Export Excel',
                            title: 'Data Daily Report',
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            text: '<i class="ti ti-file-type-pdf"></i> Export PDF',
                            title: 'Data Daily Report',
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
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    infoFiltered: "(filtered from _MAX_ total entries)",
                    emptyTable: "Data daily report belum tersedia",
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

            $('#lengthArea').append($('.dataTables_length'));
            $('#exportArea').append($('.dt-buttons'));

            // Filter Kategori: kolom "Kategori" dicocokkan persis (NOC Corp Dan Retail / NOC Alfa Grup)
            const kategoriCol = $('#reportTable thead th').filter(function() {
                return $(this).text().trim() === 'Kategori';
            }).index();
            $('#filterKategori').on('change', function() {
                const v = this.value;
                table.column(kategoriCol).search('^' + $.fn.dataTable.util.escapeRegex(v) + '$', true, false).draw();
            }).trigger('change'); // kategori pertama langsung terfilter saat halaman dibuka

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