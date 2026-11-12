<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title>Data IP Public</title>
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

        .brand-text {
            font-size: 18px;
        }

        .rfo-page .table-scroll {
            overflow: auto;
        }

        .rfo-page .table-scroll thead th {
            position: sticky !important;
            top: 0;
            z-index: 2;
            box-shadow: inset 0 -1px 0 var(--rf-border);
        }

        .ip-layout {
            display: grid;
            grid-template-columns: 300px minmax(0, 1fr);
            gap: 20px;
            align-items: start;
        }

        @media (max-width: 1024px) {
            .ip-layout {
                grid-template-columns: minmax(0, 1fr);
            }
        }

        .ip-side {
            position: sticky;
            top: 90px;
            margin-bottom: 0;
            padding: 14px 10px 10px;
            max-height: calc(100vh - 110px);
            display: flex;
            flex-direction: column;
        }

        .ip-side-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 6px 10px;
            border-bottom: 1px solid var(--rf-border);
            font-size: 14.5px;
        }

        .ip-side-head b i {
            color: #2f6bff;
            margin-right: 4px;
        }

        .ip-side-cari {
            position: relative;
            margin: 10px 4px 8px;
        }

        .ip-side-cari i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--rf-muted);
        }

        .ip-side-cari input {
            width: 100%;
            padding: 7px 10px 7px 32px;
            border: 1px solid var(--rf-border);
            border-radius: 9px;
            font-size: 13px;
            background: var(--rf-card);
            color: var(--rf-text);
            outline: none;
        }

        .ip-tree {
            overflow-y: auto;
            padding: 0 2px;
            font-size: 13px;
        }

        .ip-item {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 8px;
            border-radius: 8px;
            cursor: pointer;
            color: var(--rf-text);
            user-select: none;
        }

        .ip-item:hover {
            background: var(--rf-soft);
        }

        .ip-item span {
            flex: 1;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .ip-item em {
            font-style: normal;
            font-size: 11px;
            font-weight: 700;
            min-width: 22px;
            padding: 0 6px;
            border-radius: 999px;
            text-align: center;
            color: var(--rf-muted);
            background: var(--rf-soft);
        }

        .ip-item.is-aktif {
            color: #fff;
            background: linear-gradient(135deg, #4f8cff, #2f6bff);
        }

        .ip-item.is-aktif i,
        .ip-item.is-aktif em {
            color: #fff;
        }

        .ip-item.is-aktif em {
            background: rgba(255, 255, 255, .25);
        }

        .ip-pt {
            font-weight: 800;
            font-size: 12px;
            letter-spacing: .2px;
            margin-top: 6px;
        }

        .ip-pt .ti-building-skyscraper {
            color: #2f6bff;
        }

        .ip-folder .ti-folder {
            color: #f59e0b;
        }

        .ip-leaf {
            font-family: ui-monospace, SFMono-Regular, Consolas, monospace;
            font-size: 12.5px;
        }

        .ip-leaf .ti-network {
            color: #16a34a;
        }

        .ip-caret {
            font-size: 13px;
            color: var(--rf-muted);
            transition: transform .15s;
        }

        .ip-grup.is-buka>.ip-item .ip-caret {
            transform: rotate(90deg);
        }

        .ip-anak {
            display: none;
            margin-left: 14px;
            padding-left: 6px;
            border-left: 1px dashed var(--rf-border);
        }

        .ip-grup.is-buka>.ip-anak {
            display: block;
        }

        .ip-kosong {
            padding: 4px 10px;
            font-size: 12px;
            color: var(--rf-muted);
            font-style: italic;
        }

        .ip-cari-sembunyi {
            display: none !important;
        }

        .ipam-panel {
            padding: 18px 20px;
            margin-bottom: 0;
        }

        .ip-card-list {
            display: grid;
            gap: 8px;
        }

        .ip-card-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            padding: 11px 13px;
            border: 1px solid var(--rf-border);
            border-radius: 8px;
            background: var(--rf-card);
            color: var(--rf-text);
        }

        .ip-card-row:hover {
            background: var(--rf-soft);
            border-color: #2f6bff;
        }

        .ipam-head {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 12px;
        }

        .ipam-head h2 {
            font-size: 17px;
            font-weight: 800;
            color: var(--rf-head);
        }

        .ipam-table {
            border-collapse: collapse !important;
            font-size: 13px;
        }

        .ipam-table thead th {
            padding: 10px 12px !important;
            border-top: 1px solid var(--rf-border);
            border-bottom: 1px solid var(--rf-border) !important;
            background: var(--rf-soft);
            color: #2f6bff;
            font-weight: 700;
            text-align: left;
            white-space: nowrap;
        }

        .ipam-table tbody td {
            padding: 9px 12px !important;
            border-bottom: 1px solid var(--rf-border);
            color: var(--rf-text);
            vertical-align: middle;
        }

        .ipam-table tbody tr:hover td {
            background: var(--rf-soft);
        }

        .ipam-btn-grup {
            display: inline-flex;
            border: 1px solid var(--rf-border);
            border-radius: 5px;
            overflow: hidden;
        }

        .ipam-btn-grup button {
            width: 30px;
            height: 28px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            color: var(--rf-text);
            background: var(--rf-card);
        }

        .ipam-btn-grup button+button {
            border-left: 1px solid var(--rf-border);
        }

        .ipam-btn-grup button:hover {
            color: #fff;
            background: #2f6bff;
        }

        .ipam-btn-grup button[title="Hapus"]:hover {
            background: #dc2626;
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/theme/rfo.css') ?>?v=<?= @filemtime(FCPATH . 'assets/theme/rfo.css') ?: '1' ?>">
    <script src="<?= base_url('assets/theme/rfo.js') ?>?v=<?= @filemtime(FCPATH . 'assets/theme/rfo.js') ?: '1' ?>" defer></script>
    <?= view('partials/theme') ?>
</head>

<body class="text-[#37474f] dark:text-[#bfc8d6]">
    <!-- ============ SIDEBAR LENGKAP ============ -->
    <nav id="sidebar" class="pc-sidebar fixed top-0 left-0 h-screen w-sidebar bg-sidebar text-[#a9b7c6] z-[1030] flex flex-col">
        <div class="flex items-center h-header px-6 shrink-0">
            <a href="#" class="flex items-center gap-2 text-white text-2xl font-semibold">
                <span class="text-primary-500"></span>
                <span class="brand-text">Sistem Operasional <br> JWI Group</span>
            </a>
        </div>

        <!-- ALERT SUCCESS / ERROR MELAYANG -->
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

    <!-- ============ MAIN CONTAINER ============ -->
    <div id="container" class="pc-container ml-sidebar min-h-screen transition-[margin] duration-200">

        <!-- HEADER LENGKAP -->
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

        <!-- KONTEN UTAMA -->
        <div class="rfo-page p-6">
            <?php
            $jsonAman = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
            $pohon = [];
            foreach ($opsiPerusahaan as $p) {
                $pohon[$p] = [];
            }
            foreach ($judul as $j) {
                $pohon[$j['perusahaan']][$j['id']] = ['data' => $j, 'sub' => [], 'subnet' => []];
            }
            foreach ($subjudul as $s) {
                if (isset($pohon[$s['perusahaan']][$s['judul_id']])) {
                    $pohon[$s['perusahaan']][$s['judul_id']]['sub'][$s['id']] = ['data' => $s, 'subnet' => []];
                }
            }
            foreach ($subnet as $n) {
                $node = &$pohon[$n['perusahaan']][$n['judul_id']];
                if ($n['subjudul_id'] && isset($node['sub'][$n['subjudul_id']])) {
                    $node['sub'][$n['subjudul_id']]['subnet'][] = $n;
                } else {
                    $node['subnet'][] = $n;
                }
                unset($node);
            }
            $hitungJudul = static fn(array $node) => count($node['subnet']) + array_sum(array_map(static fn($s) => count($s['subnet']), $node['sub']));
            ?>

            <!-- JUDUL HALAMAN DAN TOMBOL TAMBAH -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic is-sky"><i class="ti ti-world"></i></span>
                    <div>
                        <h1 class="rfo-title">Data IP Public</h1>
                        <div class="rfo-subtitle">Alokasi subnet IP public per perusahaan, judul &amp; sub judul</div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" onclick="openJudul()" class="rfo-btn rfo-btn-gray border-0 cursor-pointer"><i class="ti ti-folder-plus"></i> Tambah Judul</button>
                    <button type="button" onclick="openSubjudul()" class="rfo-btn rfo-btn-green border-0 cursor-pointer"><i class="ti ti-folders"></i> Tambah Sub Judul</button>
                    <button type="button" onclick="openSubnet()" class="rfo-btn rfo-btn-blue border-0 cursor-pointer"><i class="ti ti-plus"></i> Tambah Subnet</button>
                </div>
            </div>

            <!-- LAYOUT POHON KIRI DAN PANEL TABEL KANAN -->
            <div class="ip-layout">
                <!-- ====== KIRI: POHON SUBNET ====== -->
                <aside class="ip-side card">
                    <div class="ip-side-head">
                        <b><i class="ti ti-sitemap"></i> Subnets Tree</b>
                    </div>
                    <div class="ip-side-cari">
                        <i class="ti ti-search"></i>
                        <input type="text" id="cariPohon" placeholder="Cari judul / subnet...">
                    </div>
                    <div class="ip-tree" id="ipTree">
                        <?php foreach ($pohon as $p => $daftarJudul) : ?>
                            <?php $jmlP = array_sum(array_map($hitungJudul, $daftarJudul)); ?>
                            <div class="ip-grup is-buka">
                                <a class="ip-item ip-pt" data-pilih="perusahaan" data-nama="<?= esc($p, 'attr') ?>">
                                    <i class="ti ti-chevron-right ip-caret"></i><i class="ti ti-building-skyscraper"></i><span><?= esc($p) ?></span><em><?= $jmlP ?></em>
                                </a>
                                <div class="ip-anak">
                                    <?php foreach ($daftarJudul as $jid => $nj) : ?>
                                        <div class="ip-grup">
                                            <a class="ip-item ip-folder" data-pilih="judul" data-id="<?= (int) $jid ?>">
                                                <i class="ti ti-chevron-right ip-caret"></i><i class="ti ti-folder"></i><span><?= esc($nj['data']['judul']) ?></span><em><?= $hitungJudul($nj) ?></em>
                                            </a>
                                            <div class="ip-anak">
                                                <?php foreach ($nj['sub'] as $sid => $ns) : ?>
                                                    <div class="ip-grup">
                                                        <a class="ip-item ip-folder" data-pilih="sub" data-id="<?= (int) $sid ?>">
                                                            <i class="ti ti-chevron-right ip-caret"></i><i class="ti ti-folder"></i><span><?= esc($ns['data']['sub_judul']) ?></span><em><?= count($ns['subnet']) ?></em>
                                                        </a>
                                                        <div class="ip-anak">
                                                            <?php foreach ($ns['subnet'] as $n) : ?>
                                                                <a class="ip-item ip-leaf" data-pilih="subnet" data-id="<?= (int) $n['id'] ?>"><i class="ti ti-network"></i><span><?= esc($n['subnet']) ?></span></a>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                                <?php foreach ($nj['subnet'] as $n) : ?>
                                                    <a class="ip-item ip-leaf" data-pilih="subnet" data-id="<?= (int) $n['id'] ?>"><i class="ti ti-network"></i><span><?= esc($n['subnet']) ?></span></a>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </aside>

                <!-- ====== KANAN: PANEL TABEL & EXPORT ====== -->
                <section class="ip-isi card min-w-0 ipam-panel" id="panelSubnet">
                    <div class="ipam-head">
                        <h2><i class="ti ti-network"></i> Data Subnet <small id="ipPilihanNama"></small></h2>
                        <div class="flex items-center gap-3 flex-wrap">
                            <!-- Input Search Pengganti Reset Pilihan -->
                            <div class="relative">
                                <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" id="customSearch" class="pl-9 pr-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-[#1d2630] text-gray-800 dark:text-gray-200 focus:outline-none focus:border-blue-500 w-64" placeholder="Cari Subnet / Keyword..." />
                            </div>
                            <!-- Wadah Tombol Single Dropdown Export -->
                            <div id="exportArea" class="relative inline-block text-left"></div>
                        </div>
                    </div>

                    <div id="hierarkiKonten" class="hidden"></div>

                    <div class="table-scroll hidden" id="subnetTableWrap">
                        <table id="tabelSubnet" class="ipam-table display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Subnet description</th>
                                    <th>Subnet</th>
                                    <th>Range IP</th>
                                    <th>Judul</th>
                                    <th>Sub Judul</th>
                                    <th>Perusahaan</th>
                                    <th class="no-export ipam-aksi-th">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($subnet as $row) : ?>
                                    <tr id="subnet-<?= (int) $row['id'] ?>" data-id="<?= (int) $row['id'] ?>" data-perusahaan="<?= esc($row['perusahaan'] ?? '', 'attr') ?>" data-judul="<?= (int) $row['judul_id'] ?>" data-sub="<?= (int) $row['subjudul_id'] ?>">
                                        <td></td>
                                        <td><a class="ipam-link cursor-pointer" data-id="<?= (int) $row['id'] ?>" onclick="pilihDariCard('subnet', <?= (int) $row['id'] ?>)"><?= esc($row['deskripsi'] ?: '-') ?></a></td>
                                        <td><a class="ipam-link cursor-pointer" data-id="<?= (int) $row['id'] ?>" onclick="pilihDariCard('subnet', <?= (int) $row['id'] ?>)"><?= esc($row['subnet']) ?></a></td>
                                        <td class="ipam-range"><?= $row['awal'] !== '-' ? esc($row['awal']) . ' - ' . esc($row['akhir']) : '-' ?></td>
                                        <td><?= esc($row['judul'] ?? '-') ?></td>
                                        <td><?= esc($row['sub_judul'] ?: '-') ?></td>
                                        <td><?= esc($row['perusahaan'] ?? '-') ?></td>
                                        <td class="ipam-aksi">
                                            <span class="ipam-btn-grup">
                                                <button type="button" title="Detail" data-row="<?= esc(json_encode($row), 'attr') ?>" onclick="lihatSubnet(JSON.parse(this.dataset.row))"><i class="ti ti-eye"></i></button>
                                                <button type="button" title="Edit" data-row="<?= esc(json_encode($row), 'attr') ?>" onclick="openSubnet(JSON.parse(this.dataset.row))"><i class="ti ti-pencil"></i></button>
                                                <button type="button" title="Hapus" onclick="hapus('subnet', <?= (int) $row['id'] ?>, this.dataset.nama)" data-nama="<?= esc($row['subnet'], 'attr') ?>"><i class="ti ti-x"></i></button>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- ====== POP UP DETAIL SUBNET ====== -->
    <div id="modal_lihat_subnet" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-xl p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold flex items-center gap-2"><i class="ti ti-id text-blue-500"></i> Detail Subnet</h3>
                <button type="button" onclick="tutup('modal_lihat_subnet')" aria-label="Tutup"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div class="md:col-span-2 bg-blue-50 dark:bg-blue-900/30 p-3 rounded-lg">
                    <div class="text-xs text-blue-600 dark:text-blue-400 font-bold uppercase">Subnet IP</div>
                    <div class="font-bold ip-subnet text-xl text-blue-700 dark:text-blue-300" id="l_subnet"></div>
                </div>
                <div>
                    <div class="text-gray-500">Jumlah IP</div>
                    <div class="font-semibold" id="l_jumlah"></div>
                </div>
                <div>
                    <div class="text-gray-500">Range IP</div>
                    <div class="font-medium ip-subnet" id="l_range"></div>
                </div>
                <div class="md:col-span-2">
                    <div class="text-gray-500">Subnet Description</div>
                    <div class="font-medium whitespace-pre-line bg-gray-50 dark:bg-gray-800 p-2 rounded" id="l_deskripsi"></div>
                </div>
                <div>
                    <div class="text-gray-500">Perusahaan</div>
                    <div class="font-semibold" id="l_perusahaan"></div>
                </div>
                <div>
                    <div class="text-gray-500">Judul</div>
                    <div class="font-semibold" id="l_judul"></div>
                </div>
                <div class="md:col-span-2">
                    <div class="text-gray-500">Sub Judul</div>
                    <div class="font-semibold" id="l_sub"></div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="editDariDetail()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-1"><i class="ti ti-edit"></i> Edit</button>
                <button type="button" onclick="tutup('modal_lihat_subnet')" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Tutup</button>
            </div>
        </div>
    </div>

    <!-- ====== POP UP DETAIL JUDUL ====== -->
    <div id="modal_lihat_judul" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold flex items-center gap-2"><i class="ti ti-folder text-yellow-500"></i> Detail Judul</h3>
                <button type="button" onclick="tutup('modal_lihat_judul')"><i class="ti ti-x text-xl"></i></button>
            </div>
            <div class="space-y-3 text-sm">
                <div>
                    <div class="text-gray-500">Perusahaan</div>
                    <div class="font-semibold text-base" id="v_j_perusahaan"></div>
                </div>
                <div>
                    <div class="text-gray-500">Nama Judul</div>
                    <div class="font-semibold text-base" id="v_j_judul"></div>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="editJudulDariDetail()" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-sm"><i class="ti ti-pencil"></i> Edit</button>
                <button type="button" onclick="tutup('modal_lihat_judul')" class="px-3 py-1.5 bg-gray-500 text-white rounded-lg text-sm">Tutup</button>
            </div>
        </div>
    </div>

    <!-- ====== POP UP DETAIL SUB JUDUL ====== -->
    <div id="modal_lihat_subjudul" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold flex items-center gap-2"><i class="ti ti-folders text-green-500"></i> Detail Sub Judul</h3>
                <button type="button" onclick="tutup('modal_lihat_subjudul')"><i class="ti ti-x text-xl"></i></button>
            </div>
            <div class="space-y-3 text-sm">
                <div>
                    <div class="text-gray-500">Perusahaan</div>
                    <div class="font-semibold" id="v_s_perusahaan"></div>
                </div>
                <div>
                    <div class="text-gray-500">Induk Judul</div>
                    <div class="font-semibold" id="v_s_judul"></div>
                </div>
                <div>
                    <div class="text-gray-500">Nama Sub Judul</div>
                    <div class="font-semibold text-base" id="v_s_subjudul"></div>
                </div>
            </div>
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="editSubjudulDariDetail()" class="px-3 py-1.5 bg-blue-600 text-white rounded-lg text-sm"><i class="ti ti-pencil"></i> Edit</button>
                <button type="button" onclick="tutup('modal_lihat_subjudul')" class="px-3 py-1.5 bg-gray-500 text-white rounded-lg text-sm">Tutup</button>
            </div>
        </div>
    </div>

    <!-- ====== POP UP FORM JUDUL ====== -->
    <div id="modal_judul" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-xl p-6 rfo-form">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold" id="judulFormJudul">Tambah Judul</h3>
                <button type="button" onclick="tutup('modal_judul')"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <form method="POST" id="formJudul" data-save="<?= site_url('IpPublic/judul/save') ?>" data-update="<?= site_url('IpPublic/judul/update') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id">
                <div class="rf-group">
                    <label class="rf-label">Perusahaan <span class="req">*</span></label>
                    <select class="rf-input" name="perusahaan" required>
                        <option value="">-- Pilih Perusahaan --</option>
                        <?php foreach ($opsiPerusahaan as $p) : ?>
                            <option value="<?= esc($p, 'attr') ?>"><?= esc($p) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="rf-group">
                    <label class="rf-label">Judul <span class="req">*</span></label>
                    <input class="rf-input" name="judul" maxlength="150" required placeholder="Contoh: IP Public Jakarta">
                </div>
                <div class="d-flex">
                    <button type="submit"><i class="ti ti-device-floppy"></i> Simpan</button>
                    <button type="button" class="btn-back" onclick="tutup('modal_judul')">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ====== POP UP FORM SUB JUDUL ====== -->
    <div id="modal_subjudul" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-xl p-6 rfo-form">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold" id="judulFormSubjudul">Tambah Sub Judul</h3>
                <button type="button" onclick="tutup('modal_subjudul')"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <form method="POST" id="formSubjudul" data-save="<?= site_url('IpPublic/subjudul/save') ?>" data-update="<?= site_url('IpPublic/subjudul/update') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id">
                <div class="rf-group">
                    <label class="rf-label">Judul <span class="req">*</span></label>
                    <select class="rf-input sel-judul" name="judul_id" required></select>
                </div>
                <div class="rf-group">
                    <label class="rf-label">Perusahaan</label>
                    <input class="rf-input ip-otomatis" data-perusahaan readonly tabindex="-1" placeholder="Otomatis dari Judul">
                </div>
                <div class="rf-group">
                    <label class="rf-label">Sub Judul <span class="req">*</span></label>
                    <input class="rf-input" name="sub_judul" maxlength="150" required placeholder="Contoh: Blok POP Duren Tiga">
                </div>
                <div class="d-flex">
                    <button type="submit"><i class="ti ti-device-floppy"></i> Simpan</button>
                    <button type="button" class="btn-back" onclick="tutup('modal_subjudul')">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ====== POP UP FORM SUBNET ====== -->
    <div id="modal_subnet" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-2xl p-6 rfo-form">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold" id="judulFormSubnet">Tambah Subnet</h3>
                <button type="button" onclick="tutup('modal_subnet')"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <form method="POST" id="formSubnet" data-save="<?= site_url('IpPublic/subnet/save') ?>" data-update="<?= site_url('IpPublic/subnet/update') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
                    <div class="rf-group">
                        <label class="rf-label">Judul <span class="req">*</span></label>
                        <select class="rf-input sel-judul" name="judul_id" required></select>
                    </div>
                    <div class="rf-group">
                        <label class="rf-label">Sub Judul</label>
                        <select class="rf-input" name="subjudul_id"></select>
                    </div>
                    <div class="rf-group md:col-span-2">
                        <label class="rf-label">Perusahaan</label>
                        <input class="rf-input ip-otomatis" data-perusahaan readonly tabindex="-1" placeholder="Otomatis dari Judul">
                    </div>
                    <div class="rf-group md:col-span-2">
                        <label class="rf-label">Subnet <span class="req">*</span></label>
                        <input class="rf-input ip-subnet" name="subnet" maxlength="50" required placeholder="Contoh: 103.10.20.0/29" autocomplete="off">
                    </div>
                    <div class="rf-group md:col-span-2">
                        <label class="rf-label">Subnet Description</label>
                        <textarea class="rf-input" name="deskripsi" rows="3" placeholder="Keterangan pemakaian subnet"></textarea>
                    </div>
                </div>
                <div class="d-flex">
                    <button type="submit"><i class="ti ti-device-floppy"></i> Simpan</button>
                    <button type="button" class="btn-back" onclick="tutup('modal_subnet')">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPT JAVASCRIPT LENGKAP -->
    <script>
        const DATA_JUDUL = <?= json_encode(array_map(fn($j) => ['id' => (int) $j['id'], 'perusahaan' => $j['perusahaan'], 'judul' => $j['judul']], $judul), $jsonAman) ?>;
        const DATA_SUBJUDUL = <?= json_encode(array_map(fn($s) => ['id' => (int) $s['id'], 'judul_id' => (int) $s['judul_id'], 'sub_judul' => $s['sub_judul'], 'perusahaan' => $s['perusahaan'] ?? '', 'judul' => $s['judul'] ?? ''], $subjudul), $jsonAman) ?>;
        const DATA_SUBNET = <?= json_encode(array_map(fn($n) => ['id' => (int) $n['id'], 'subnet' => $n['subnet'], 'deskripsi' => $n['deskripsi'] ?? '', 'perusahaan' => $n['perusahaan'] ?? '', 'judul' => $n['judul'] ?? '', 'sub_judul' => $n['sub_judul'] ?? '', 'awal' => $n['awal'] ?? '-', 'akhir' => $n['akhir'] ?? '-', 'jumlah_ip' => $n['jumlah_ip'] ?? '-'], $subnet), $jsonAman) ?>;
        const PERUSAHAAN = <?= json_encode($opsiPerusahaan, $jsonAman) ?>;

        function escapeHtml(s) {
            return (s == null ? '' : String(s)).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        const judulById = id => DATA_JUDUL.find(j => String(j.id) === String(id));
        const subjudulById = id => DATA_SUBJUDUL.find(s => String(s.id) === String(id));
        const subnetById = id => DATA_SUBNET.find(n => String(n.id) === String(id));

        function isiPilihanJudul(select, terpilih) {
            let html = '<option value="">-- Pilih Judul --</option>';
            PERUSAHAAN.forEach(p => {
                const daftar = DATA_JUDUL.filter(j => j.perusahaan === p);
                if (!daftar.length) return;
                html += '<optgroup label="' + escapeHtml(p) + '">' + daftar.map(j => '<option value="' + j.id + '">' + escapeHtml(j.judul) + '</option>').join('') + '</optgroup>';
            });
            select.innerHTML = html;
            select.value = terpilih ? String(terpilih) : '';
        }

        function tampilPerusahaan(form) {
            const j = judulById(form.elements.judul_id.value);
            form.querySelector('[data-perusahaan]').value = j ? j.perusahaan : '';
        }

        function isiPilihanSubjudul(form, terpilih) {
            const judulId = form.elements.judul_id.value;
            const daftar = DATA_SUBJUDUL.filter(s => String(s.judul_id) === String(judulId));
            form.elements.subjudul_id.innerHTML = '<option value="">' + (judulId ? (daftar.length ? '-- Tanpa Sub Judul --' : '-- Judul ini belum punya Sub Judul --') : '-- Pilih Judul dulu --') + '</option>' +
                daftar.map(s => '<option value="' + s.id + '">' + escapeHtml(s.sub_judul) + '</option>').join('');
            form.elements.subjudul_id.value = terpilih && daftar.some(s => String(s.id) === String(terpilih)) ? String(terpilih) : '';
        }

        function buka(id) {
            document.getElementById(id).classList.remove('hidden');
        }

        function tutup(id) {
            document.getElementById(id).classList.add('hidden');
        }

        function siapkanForm(form, data, judulTambah, judulEdit, idJudulForm) {
            const edit = !!data.id;
            form.reset();
            form.elements.id.value = edit ? data.id : '';
            form.setAttribute('action', edit ? form.dataset.update : form.dataset.save);
            document.getElementById(idJudulForm).textContent = edit ? judulEdit : judulTambah;
        }

        // --- JUDUL ---
        function openJudul(data) {
            data = data || {};
            const f = document.getElementById('formJudul');
            siapkanForm(f, data, 'Tambah Judul', 'Edit Judul', 'judulFormJudul');
            f.elements.perusahaan.value = data.perusahaan || '';
            f.elements.judul.value = data.judul || '';
            buka('modal_judul');
        }

        let JUDUL_DILIHAT = null;

        function lihatJudul(id) {
            const j = judulById(id);
            if (!j) return;
            JUDUL_DILIHAT = j;
            document.getElementById('v_j_perusahaan').textContent = j.perusahaan;
            document.getElementById('v_j_judul').textContent = j.judul;
            buka('modal_lihat_judul');
        }

        function editJudulDariDetail() {
            tutup('modal_lihat_judul');
            if (JUDUL_DILIHAT) openJudul(JUDUL_DILIHAT);
        }

        // --- SUB JUDUL ---
        function openSubjudul(data) {
            data = data || {};
            const f = document.getElementById('formSubjudul');
            siapkanForm(f, data, 'Tambah Sub Judul', 'Edit Sub Judul', 'judulFormSubjudul');
            isiPilihanJudul(f.elements.judul_id, data.judul_id);
            f.elements.sub_judul.value = data.sub_judul || '';
            tampilPerusahaan(f);
            buka('modal_subjudul');
        }

        let SUBJUDUL_DILIHAT = null;

        function lihatSubjudul(id) {
            const s = subjudulById(id);
            if (!s) return;
            SUBJUDUL_DILIHAT = s;
            const j = judulById(s.judul_id);
            document.getElementById('v_s_perusahaan').textContent = j ? j.perusahaan : '-';
            document.getElementById('v_s_judul').textContent = j ? j.judul : '-';
            document.getElementById('v_s_subjudul').textContent = s.sub_judul;
            buka('modal_lihat_subjudul');
        }

        function editSubjudulDariDetail() {
            tutup('modal_lihat_subjudul');
            if (SUBJUDUL_DILIHAT) openSubjudul(SUBJUDUL_DILIHAT);
        }

        // --- SUBNET ---
        function openSubnet(data) {
            data = data || {};
            const f = document.getElementById('formSubnet');
            siapkanForm(f, data, 'Tambah Subnet', 'Edit Subnet', 'judulFormSubnet');
            isiPilihanJudul(f.elements.judul_id, data.judul_id);
            isiPilihanSubjudul(f, data.subjudul_id);
            f.elements.subnet.value = data.subnet || '';
            f.elements.deskripsi.value = data.deskripsi || '';
            tampilPerusahaan(f);
            buka('modal_subnet');
        }

        let SUBNET_DILIHAT = null;

        function lihatSubnet(d) {
            SUBNET_DILIHAT = d;
            const set = (id, v) => document.getElementById(id).textContent = (!v || String(v).trim() === '') ? '-' : v;
            set('l_subnet', d.subnet);
            set('l_jumlah', typeof d.jumlah_ip === 'number' ? d.jumlah_ip.toLocaleString('id-ID') : d.jumlah_ip);
            set('l_range', d.awal && d.awal !== '-' ? d.awal + ' - ' + d.akhir : '-');
            set('l_deskripsi', d.deskripsi);
            set('l_perusahaan', d.perusahaan);
            set('l_judul', d.judul);
            set('l_sub', d.sub_judul);
            buka('modal_lihat_subnet');
        }

        function editDariDetail() {
            tutup('modal_lihat_subnet');
            if (SUBNET_DILIHAT) openSubnet(SUBNET_DILIHAT);
        }

        document.querySelectorAll('.sel-judul').forEach(sel => sel.addEventListener('change', function() {
            const f = this.form;
            tampilPerusahaan(f);
            if (f.elements.subjudul_id) isiPilihanSubjudul(f, '');
        }));

        // --- HAPUS ---
        function hapus(jenis, id, nama) {
            const label = {
                judul: 'Judul',
                subjudul: 'Sub Judul',
                subnet: 'Subnet'
            } [jenis];
            Swal.fire({
                title: 'Hapus ' + label + '?',
                text: '"' + nama + '" akan dihapus secara permanen.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then(r => {
                if (r.isConfirmed) window.location.href = '<?= site_url('IpPublic') ?>/' + jenis + '/delete/' + id;
            });
        }
    </script>

    <script>
        let PILIH = {
            jenis: 'none'
        };

        function cocok(el) {
            if (PILIH.jenis === 'perusahaan') return el.dataset.perusahaan === PILIH.nama;
            if (PILIH.jenis === 'judul') return el.dataset.judul === String(PILIH.id);
            if (PILIH.jenis === 'sub') return el.dataset.sub === String(PILIH.id);
            if (PILIH.jenis === 'subnet') return el.dataset.id === String(PILIH.id);
            return false;
        }

        function namaPilihan() {
            if (PILIH.jenis === 'perusahaan') return PILIH.nama;
            if (PILIH.jenis === 'judul') {
                const j = judulById(PILIH.id);
                return j ? j.perusahaan + ' › ' + j.judul : '';
            }
            if (PILIH.jenis === 'sub') {
                const s = subjudulById(PILIH.id),
                    j = s ? judulById(s.judul_id) : null;
                return j ? j.perusahaan + ' › ' + j.judul + ' › ' + s.sub_judul : '';
            }
            if (PILIH.jenis === 'subnet') {
                const n = subnetById(PILIH.id);
                return n ? n.perusahaan + ' › ' + n.judul + ' › Subnet: ' + n.subnet : '';
            }
            return '';
        }

        function modeKonten(daftar) {
            document.getElementById('hierarkiKonten').classList.toggle('hidden', !daftar);
            document.getElementById('subnetTableWrap').classList.toggle('hidden', daftar);
        }

        function tampilkanHierarki(jenis) {
            const box = document.getElementById('hierarkiKonten'),
                isJudul = jenis === 'perusahaan',
                label = isJudul ? 'Judul' : 'Sub Judul';

            const items = isJudul ?
                DATA_JUDUL.filter(j => j.perusahaan === PILIH.nama).map(j => ({
                    id: j.id,
                    nama: j.judul,
                    jenis: 'judul'
                })) :
                DATA_SUBJUDUL.filter(s => String(s.judul_id) === String(PILIH.id)).map(s => ({
                    id: s.id,
                    nama: s.sub_judul,
                    jenis: 'sub'
                }));

            document.getElementById('ipPilihanNama').textContent = '— ' + namaPilihan();

            let html = '<div class="mb-3 font-bold text-gray-700 dark:text-gray-300">Daftar ' + label + ':</div>';
            if (items.length) {
                html += '<div class="ip-card-list">';
                items.forEach(x => {
                    html += `
                        <div class="ip-card-row">
                            <div class="flex items-center gap-2 cursor-pointer" onclick="pilihDariCard('${x.jenis}', ${x.id})">
                                <i class="ti ${isJudul ? 'ti-folder text-yellow-500' : 'ti-folders text-green-500'} text-xl"></i>
                                <span class="font-medium">${escapeHtml(x.nama)}</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <button type="button" class="p-1 text-gray-500 hover:text-blue-600" title="View" onclick="${isJudul ? `lihatJudul(${x.id})` : `lihatSubjudul(${x.id})`}"><i class="ti ti-eye"></i></button>
                                <button type="button" class="p-1 text-gray-500 hover:text-blue-600" title="Edit" onclick="${isJudul ? `openJudul(judulById(${x.id}))` : `openSubjudul(subjudulById(${x.id}))`}"><i class="ti ti-pencil"></i></button>
                                <button type="button" class="p-1 text-gray-500 hover:text-red-600" title="Hapus" onclick="hapus('${isJudul ? 'judul' : 'subjudul'}', ${x.id}, '${escapeHtml(x.nama)}')"><i class="ti ti-x"></i></button>
                            </div>
                        </div>`;
                });
                html += '</div>';
            } else {
                html += '<div class="ip-kosong">Belum ada ' + label.toLowerCase() + '.</div>';
            }

            box.innerHTML = html;
            modeKonten(true);
        }

        function pilihDariCard(jenis, id) {
            const item = document.querySelector(`#ipTree [data-pilih="${jenis}"][data-id="${id}"]`);
            if (item) {
                pilihNode(item);
            } else if (jenis === 'subnet') {
                // Eksekusi pemanggilan tabel bila klik subnet dari daftar hierarki kartu
                document.querySelectorAll('.ip-item.is-aktif').forEach(x => x.classList.remove('is-aktif'));
                PILIH = {
                    jenis: 'subnet',
                    id: String(id)
                };
                document.getElementById('hierarkiKonten').classList.add('hidden');
                document.getElementById('subnetTableWrap').classList.remove('hidden');
                document.getElementById('ipPilihanNama').textContent = '— ' + namaPilihan();

                if (window.TABEL_SUBNET) {
                    window.TABEL_SUBNET.columns([1, 2, 7]).visible(true);
                    window.TABEL_SUBNET.columns([0, 3, 4, 5, 6]).visible(false);
                    window.TABEL_SUBNET.search('');
                    window.TABEL_SUBNET.draw();
                }
            }
        }

        function pilihNode(el) {
            if (!el) return;
            const jenis = el.dataset.pilih;
            document.querySelectorAll('.ip-item.is-aktif').forEach(x => x.classList.remove('is-aktif'));
            el.classList.add('is-aktif');
            PILIH = {
                jenis,
                id: el.dataset.id,
                nama: el.dataset.nama
            };

            if (el.parentElement.classList.contains('ip-grup')) el.parentElement.classList.add('is-buka');

            if (jenis === 'perusahaan' || jenis === 'judul') {
                if (window.TABEL_SUBNET) TABEL_SUBNET.search('').draw();
                tampilkanHierarki(jenis);
                return;
            }

            // Apabila memilih Sub Judul atau Subnet -> Tampilkan tabel DataTables
            document.getElementById('hierarkiKonten').classList.add('hidden');
            document.getElementById('subnetTableWrap').classList.remove('hidden');
            document.getElementById('ipPilihanNama').textContent = '— ' + namaPilihan();

            if (window.TABEL_SUBNET) {
                if (jenis === 'sub') {
                    // Ketika memencet Sub Judul: tampilkan hanya Subnet dan Aksi
                    window.TABEL_SUBNET.columns([2, 7]).visible(true);
                    window.TABEL_SUBNET.columns([0, 1, 3, 4, 5, 6]).visible(false);
                } else if (jenis === 'subnet') {
                    // Ketika memencet Subnet: tampilkan hanya Subnet Description, Subnet, dan Aksi
                    window.TABEL_SUBNET.columns([1, 2, 7]).visible(true);
                    window.TABEL_SUBNET.columns([0, 3, 4, 5, 6]).visible(false);
                }

                window.TABEL_SUBNET.search('');
                window.TABEL_SUBNET.draw();
            }
        }

        document.getElementById('ipTree').addEventListener('click', e => {
            const caret = e.target.closest('.ip-caret'),
                item = e.target.closest('.ip-item');
            if (!item) return;
            if (caret) {
                item.parentElement.classList.toggle('is-buka');
                return;
            }
            pilihNode(item);
        });

        // Filter Pencarian Pohon Kiri
        document.getElementById('cariPohon').addEventListener('input', function() {
            const kata = this.value.toLowerCase().trim();
            const semua = document.querySelectorAll('#ipTree .ip-item, #ipTree .ip-grup');
            semua.forEach(x => x.classList.remove('ip-cari-sembunyi'));
            if (!kata) return;

            const grup = [...document.querySelectorAll('#ipTree .ip-grup')].reverse();
            document.querySelectorAll('#ipTree .ip-leaf').forEach(l => l.classList.toggle('ip-cari-sembunyi', !l.textContent.toLowerCase().includes(kata)));
            grup.forEach(g => {
                const kepala = g.querySelector(':scope > .ip-item');
                const cocokDiri = kepala.textContent.toLowerCase().includes(kata);
                const adaIsi = [...g.querySelectorAll(':scope > .ip-anak > .ip-leaf, :scope > .ip-anak > .ip-grup')].some(x => !x.classList.contains('ip-cari-sembunyi'));
                g.classList.toggle('ip-cari-sembunyi', !cocokDiri && !adaIsi);
                if (cocokDiri || adaIsi) g.classList.add('is-buka');
            });
        });
    </script>

    <script>
        // ====== DATATABLES FILTER + SINGLE EXPORT DROPDOWN ======
        $(document).ready(function() {
            $.fn.dataTable.ext.search.push(function(settings, data, i) {
                if (settings.nTable.id !== 'tabelSubnet') return true;
                return cocok(settings.aoData[i].nTr);
            });

            const tabel = $('#tabelSubnet').DataTable({
                pageLength: 25,
                lengthChange: false,
                order: [],
                columnDefs: [{
                        targets: 0,
                        orderable: false,
                        searchable: false,
                        render: (d, t, r, meta) => meta.settings._iDisplayStart + meta.row + 1
                    },
                    {
                        targets: [7],
                        orderable: false,
                        searchable: false
                    }
                ],
                dom: 'rtip',
                language: {
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ subnet",
                    infoEmpty: "Menampilkan 0 subnet",
                    emptyTable: "Belum ada data subnet",
                    zeroRecords: "Tidak ada subnet yang cocok"
                }
            });
            window.TABEL_SUBNET = tabel;

            // Inisialisasi Fitur Export DataTables secara terpisah tanpa Print & Colvis
            const buttons = new $.fn.dataTable.Buttons(tabel, {
                buttons: [{
                        extend: 'excelHtml5',
                        text: '<i class="ti ti-file-spreadsheet text-green-600 mr-2"></i> Export Excel',
                        title: 'Data Subnet IP Public',
                        className: 'w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center',
                        exportOptions: {
                            columns: ':not(.no-export)'
                        }
                    },
                    {
                        extend: 'csvHtml5',
                        text: '<i class="ti ti-file-text text-blue-600 mr-2"></i> Export CSV',
                        title: 'Data Subnet IP Public',
                        className: 'w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center',
                        exportOptions: {
                            columns: ':not(.no-export)'
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="ti ti-file-description text-red-600 mr-2"></i> Export PDF',
                        title: 'Data Subnet IP Public',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        className: 'w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center',
                        exportOptions: {
                            columns: ':not(.no-export)'
                        }
                    }
                ]
            });

            // Render Tombol Single Dropdown "Export"
            const exportContainer = $('#exportArea');
            const dropHtml = `
                <button id="btnExportDrop" onclick="toggleExportMenu(event)" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium flex items-center gap-2">
                    <i class="ti ti-download"></i> Export <i class="ti ti-chevron-down text-xs"></i>
                </button>
                <div id="exportMenu" class="hidden absolute right-0 mt-2 w-44 bg-white dark:bg-[#263240] rounded-lg shadow-xl border border-gray-100 dark:border-gray-700 z-50 overflow-hidden py-1">
                </div>
            `;
            exportContainer.html(dropHtml);
            $('#exportMenu').append(buttons.container().find('.dt-button'));

            // Event handler pencarian instan
            $('#customSearch').on('keyup input', function() {
                tabel.search(this.value).draw();
            });
        });

        function toggleExportMenu(e) {
            e.stopPropagation();
            $('#exportMenu').toggleClass('hidden');
        }

        $(document).on('click', function() {
            $('#exportMenu').addClass('hidden');
        });
    </script>

    <!-- ====== SCRIPT TOGGLE SIDEBAR & DROP DOWNS ====== -->
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
                if (sb && sb.classList.contains('mobile-open') && !sb.contains(e.target) && !menuBtn) {
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

    <!-- SCRIPT ALERT TIMEOUT / PROGRESS BAR -->
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

            const boxErr = document.getElementById('errorAlert');
            const barErr = document.getElementById('progressBarError');
            if (boxErr) {
                if (barErr) {
                    barErr.style.transition = 'width 4s linear';
                    setTimeout(function() {
                        barErr.style.width = '0%';
                    }, 100);
                }
                setTimeout(function() {
                    boxErr.style.transition = 'all .5s ease';
                    boxErr.style.opacity = '0';
                    boxErr.style.transform = 'translate(-50%, -20px)';
                    setTimeout(function() {
                        boxErr.remove();
                    }, 500);
                }, 4000);
            }
        });
    </script>
</body>

</html>