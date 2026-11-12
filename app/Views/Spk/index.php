<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title><?= esc($bast['judul']) ?></title>
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
    <!-- View KMZ/KML: peta Leaflet + pembaca KML (JSZip di atas dipakai juga untuk membuka KMZ) -->
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

        .rekap-red {
            background: #fde8ec;
        }

        .rekap-amber {
            background: #fdf3e3;
        }

        .rekap-blue {
            background: #e3edfb;
        }

        .rekap-green {
            background: #e7f8f1;
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

        /* ===== TABEL: scroll di dalam card, header kolom tetap terlihat ===== */
        .rfo-page .table-scroll {
            overflow: auto;
        }

        .rfo-page .table-scroll thead th {
            position: sticky !important;
            top: 0;
            z-index: 2;
            box-shadow: inset 0 -1px 0 var(--rf-border);
        }

        /* ===== KMZ ===== */
        .kz-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12.5px;
            font-weight: 700;
            color: #2f6bff;
            background: #e8efff;
        }

        html.dark .kz-chip {
            color: #8fb2ff;
            background: rgba(47, 107, 255, .18);
        }

        .kz-drop {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            padding: 34px 20px;
            border: 2px dashed var(--rf-border);
            border-radius: 16px;
            text-align: center;
            cursor: pointer;
            transition: border-color .15s, background-color .15s;
        }

        .kz-drop:hover,
        .kz-drop.is-over {
            border-color: #2f6bff;
            background: rgba(47, 107, 255, .05);
        }

        .kz-drop.hidden,
        .kz-info.hidden {
            display: none;
        }

        .kz-drop-ic {
            width: 60px;
            height: 60px;
            margin-bottom: 6px;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            color: #2f6bff;
            background: #e8efff;
        }

        html.dark .kz-drop-ic {
            background: rgba(47, 107, 255, .18);
        }

        .kz-drop-title {
            font-size: 15px;
            font-weight: 800;
            color: var(--rf-head);
        }

        .kz-drop-sub {
            font-size: 12.5px;
            color: var(--rf-muted);
        }

        .kz-info {
            padding: 18px;
            border: 1px solid var(--rf-border);
            border-radius: 16px;
            background: var(--rf-soft);
        }

        .kz-info-file {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
        }

        .kz-info-nama {
            min-width: 0;
            font-size: 15px;
            font-weight: 800;
            color: var(--rf-head);
            word-break: break-all;
        }

        .kz-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 10px;
            margin-bottom: 16px;
        }

        .kz-info-grid>div {
            display: flex;
            flex-direction: column;
            gap: 3px;
            padding: 10px 12px;
            border-radius: 10px;
            background: var(--rf-card);
            border: 1px solid var(--rf-border);
        }

        .kz-info-grid span {
            font-size: 11.5px;
            color: var(--rf-muted);
        }

        .kz-info-grid b {
            font-size: 13.5px;
            color: var(--rf-head);
        }

        .kz-info-aksi {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .kz-badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
            color: #1d4ed8;
            background: #dbeafe;
        }

        .kz-badge.is-pdf {
            color: #b91c1c;
            background: #fee2e2;
        }

        .kz-badge.is-doc,
        .kz-badge.is-docx {
            color: #1e40af;
            background: #dbeafe;
        }

        .kz-badge.is-png,
        .kz-badge.is-jpg {
            color: #047857;
            background: #d1fae5;
        }

        html.dark .kz-badge {
            color: #93c5fd;
            background: rgba(59, 130, 246, .18);
        }

        html.dark .kz-badge.is-pdf {
            color: #fca5a5;
            background: rgba(239, 68, 68, .18);
        }

        html.dark .kz-badge.is-png,
        html.dark .kz-badge.is-jpg {
            color: #6ee7b7;
            background: rgba(16, 185, 129, .18);
        }

        .kz-ic {
            color: #2f6bff;
            font-size: 16px;
            vertical-align: -2px;
        }

        /* ===== VIEW BAST (pop up pratinjau dokumen) ===== */
        .kz-view-body {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 280px;
            gap: 16px;
        }

        @media (max-width: 1023px) {
            .kz-view-body {
                grid-template-columns: 1fr;
            }
        }

        /* pratinjau dokumen di pop up View */
        .bc-preview {
            height: min(72vh, 680px);
            min-height: 320px;
            border-radius: 14px;
            border: 1px solid var(--rf-border);
            background: var(--rf-soft);
            overflow: auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .bc-preview iframe {
            width: 100%;
            height: 100%;
            border: 0;
            background: #fff;
        }

        .bc-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .bc-word {
            display: grid;
            justify-items: center;
            gap: 6px;
            padding: 24px;
            text-align: center;
            color: var(--rf-muted);
            font-size: 13px;
        }

        .bc-word i {
            font-size: 64px;
            color: #2b579a;
        }

        .bc-word b {
            font-size: 15px;
            color: var(--rf-head);
        }

        /* hasil baca otomatis isi dokumen */
        .bc-status {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            padding: 9px 12px;
            border-radius: 10px;
            font-size: 12.5px;
            color: #1d4ed8;
            background: #dbeafe;
        }

        .bc-status.is-ok {
            color: #047857;
            background: #d1fae5;
        }

        .bc-status.is-warn {
            color: #b45309;
            background: #fef3c7;
        }

        html.dark .bc-status {
            color: #93c5fd;
            background: rgba(59, 130, 246, .18);
        }

        html.dark .bc-status.is-ok {
            color: #6ee7b7;
            background: rgba(16, 185, 129, .18);
        }

        html.dark .bc-status.is-warn {
            color: #fcd34d;
            background: rgba(245, 158, 11, .18);
        }

        .bc-hasil {
            margin-bottom: 10px;
        }

        .bc-hasil b.hidden,
        .bc-manual.hidden {
            display: none;
        }

        .bc-manual {
            margin-top: 4px;
            height: 38px;
            font-size: 13px;
        }

        .bc-hasil b {
            word-break: break-word;
        }

        .bc-kosong,
        .bc-tunggu {
            font-weight: 500;
            font-style: italic;
            color: var(--rf-muted);
        }

        .bc-putar {
            display: inline-block;
            animation: bcPutar 1s linear infinite;
        }

        @keyframes bcPutar {
            to {
                transform: rotate(360deg);
            }
        }

        .kz-side {
            display: flex;
            flex-direction: column;
            gap: 10px;
            min-width: 0;
        }

        .kz-side-item {
            padding: 10px 12px;
            border: 1px solid var(--rf-border);
            border-radius: 10px;
            background: var(--rf-soft);
        }

        .kz-side-item span {
            display: block;
            font-size: 11.5px;
            color: var(--rf-muted);
        }

        .kz-side-item b {
            font-size: 13.5px;
            color: var(--rf-head);
            word-break: break-word;
        }

        .kz-stat {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }

        .kz-stat>div {
            padding: 8px 6px;
            border-radius: 10px;
            text-align: center;
            border: 1px solid var(--rf-border);
            background: var(--rf-card);
        }

        .kz-stat b {
            display: block;
            font-size: 18px;
            color: var(--rf-head);
        }

        .kz-stat span {
            font-size: 11px;
            color: var(--rf-muted);
        }

        .kz-status {
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 12.5px;
            color: #1d4ed8;
            background: #dbeafe;
        }

        .kz-status.is-error {
            color: #b91c1c;
            background: #fee2e2;
        }

        html.dark .kz-status {
            color: #93c5fd;
            background: rgba(59, 130, 246, .18);
        }

        html.dark .kz-status.is-error {
            color: #fca5a5;
            background: rgba(239, 68, 68, .18);
        }

        .kz-status.hidden {
            display: none;
        }

        /* isi pop up titik/jalur di peta: teks saja, panjang dibatasi */
        .kz-popup b {
            display: block;
            margin-bottom: 4px;
        }

        .kz-popup div {
            max-height: 160px;
            overflow: auto;
            white-space: pre-line;
            font-size: 12px;
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/theme/rfo.css') ?>?v=<?= @filemtime(FCPATH . 'assets/theme/rfo.css') ?: '1' ?>">
    <script src="<?= base_url('assets/theme/rfo.js') ?>?v=<?= @filemtime(FCPATH . 'assets/theme/rfo.js') ?: '1' ?>" defer></script>
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

        <div class="rfo-page p-6">
            <?php
            // ukuran dalam B / KB / MB (format Indonesia)
            $fmtUkuran = static function ($b) {
                $b = (int) $b;
                if ($b >= 1048576) return number_format($b / 1048576, 2, ',', '.') . ' MB';
                if ($b >= 1024) return number_format($b / 1024, 1, ',', '.') . ' KB';
                return $b . ' B';
            };
            $BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            $fmtWaktu = static function ($dt) use ($BULAN) {
                if (! $dt) return '-';
                $t = strtotime($dt);
                return (int) date('j', $t) . ' ' . $BULAN[(int) date('n', $t) - 1] . ' ' . date('Y', $t) . ', ' . date('H:i', $t) . ' WIB';
            };
            $fmtTanggal = static function ($d) use ($BULAN) {
                if (! $d) return '-';
                $t = strtotime($d);
                return (int) date('j', $t) . ' ' . $BULAN[(int) date('n', $t) - 1] . ' ' . date('Y', $t);
            };
            // ikon tabler per format
            $IKON = ['PDF' => 'ti-file-type-pdf', 'DOC' => 'ti-file-type-doc', 'DOCX' => 'ti-file-type-docx', 'PNG' => 'ti-file-type-png', 'JPG' => 'ti-file-type-jpg'];
            $totalUkuran = array_sum(array_map('intval', array_column($files, 'ukuran')));
            $daftarKeperluan = array_values(array_unique(array_filter(array_column($files, 'keperluan'))));
            sort($daftarKeperluan, SORT_NATURAL | SORT_FLAG_CASE);
            $daftarLokasi = array_values(array_unique(array_filter(array_column($files, 'lokasi'))));
            sort($daftarLokasi, SORT_NATURAL | SORT_FLAG_CASE);
            ?>
            <!-- JUDUL -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic is-sky"><i class="ti <?= esc($bast['ikon'], 'attr') ?>"></i></span>
                    <div>
                        <h1 class="rfo-title"><?= esc($bast['judul']) ?></h1>
                        <div class="rfo-subtitle"><?= esc($bast['subjudul']) ?></div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="kz-chip"><i class="ti ti-files"></i> <?= count($files) ?> dokumen</span>
                    <span class="kz-chip"><i class="ti ti-database"></i> <?= $fmtUkuran($totalUkuran) ?></span>
                </div>
            </div>

            <!-- UPLOAD -->
            <div class="rfo-panel mb-6">
                <form method="POST" action="<?= site_url($bast['route'] . '/upload') ?>" enctype="multipart/form-data" id="formKmz" class="rfo-form">
                    <?= csrf_field() ?>
                    <!-- Nomor SPK, Tanggal, Keperluan & Lokasi dibaca otomatis dari isi dokumen;
                         kotak isian manual hanya muncul untuk data yang tidak terbaca (lihat bacaDokumen) -->

                    <label class="kz-drop" id="kzDrop" for="fileKmz">
                        <input type="file" name="dokumen" id="fileKmz" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" hidden>
                        <span class="kz-drop-ic"><i class="ti ti-cloud-upload"></i></span>
                        <span class="kz-drop-title">Klik atau tarik dokumen SPK ke sini</span>
                        <span class="kz-drop-sub">PDF, Word (.doc / .docx), PNG, atau JPG, maksimal 30 MB. Nomor SPK, tanggal, keperluan &amp; lokasi dibaca otomatis dari isi dokumen</span>
                    </label>

                    <!-- muncul setelah file dipilih: format, ukuran, tanggal & jam terbaca otomatis -->
                    <div class="kz-info hidden" id="kzInfo">
                        <div class="kz-info-file">
                            <span class="kz-badge" id="kzFormat">PDF</span>
                            <span class="kz-info-nama" id="kzNama">-</span>
                        </div>
                        <div class="bc-status" id="bcStatus"></div>
                        <div class="kz-info-grid bc-hasil">
                            <div><span>Nomor SPK</span><b id="bcNo">-</b><input class="rf-input bc-manual hidden" name="no_spk" maxlength="100" placeholder="Isi manual, contoh: <?= esc($bast['contohNo'], 'attr') ?>"></div>
                            <div><span>Tanggal SPK</span><b id="bcTanggal">-</b><input class="rf-input bc-manual hidden" type="date" name="tanggal_spk"></div>
                            <div><span>Keperluan</span><b id="bcKeperluan">-</b><input class="rf-input bc-manual hidden" name="keperluan" maxlength="255" placeholder="Isi manual, contoh: Aktivasi link baru / perbaikan"></div>
                            <div><span>Lokasi</span><b id="bcLokasi">-</b><input class="rf-input bc-manual hidden" name="lokasi" maxlength="255" placeholder="Isi manual, contoh: Jl. Sudirman No. 5, Jakarta"></div>
                        </div>
                        <div class="kz-info-grid">
                            <div><span>Format</span><b id="kzFormatTeks">-</b></div>
                            <div><span>Ukuran</span><b id="kzUkuran">-</b></div>
                            <div><span>Tanggal &amp; Jam Upload</span><b id="kzWaktu">-</b></div>
                        </div>
                        <div class="kz-info-aksi">
                            <button type="button" class="rfo-ghost-btn" id="kzBatal"><i class="ti ti-x"></i> Batal</button>
                            <button type="submit" class="rfo-btn rfo-btn-blue border-0 cursor-pointer" id="kzKirim"><i class="ti ti-upload"></i> Upload</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card w-full min-w-0">
                <div class="card-body">
                    <!-- TOOLBAR -->
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div class="flex items-center gap-3 flex-wrap">
                            <div id="lengthArea"></div>
                            <select id="filterKeperluan" class="filter-select" title="Filter Keperluan">
                                <option value="">Semua Keperluan</option>
                                <?php foreach ($daftarKeperluan as $v) : ?>
                                    <option value="<?= esc($v, 'attr') ?>"><?= esc($v) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select id="filterFormat" class="filter-select" title="Filter Format">
                                <option value="">Semua Format</option>
                                <?php foreach (['PDF', 'DOC', 'DOCX', 'PNG', 'JPG'] as $v) : ?>
                                    <option value="<?= $v ?>"><?= $v ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <div class="rfo-search">
                                <i class="ti ti-search"></i>
                                <input type="text" id="customSearch" placeholder="Cari Nomor SPK, keperluan..." />
                            </div>
                            <button type="button" class="rfo-ghost-btn rfo-reset"><i class="ti ti-refresh"></i> Reset</button>
                            <div id="exportArea"></div>
                        </div>
                    </div>

                    <!-- TABLE -->
                    <div class="table-scroll">
                        <table id="reportTable" class="rfo-table display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th class="no-export">Aksi</th>
                                    <th>Nomor SPK</th>
                                    <th>Tanggal SPK</th>
                                    <th>Keperluan</th>
                                    <th>Lokasi</th>
                                    <th>Dokumen</th>
                                    <th>Format</th>
                                    <th>Ukuran</th>
                                    <th>Tanggal &amp; Jam Upload</th>
                                    <th>Diupload Oleh</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($files as $row) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-success" title="Lihat dokumen" onclick="lihatKmz(this)"
                                                data-url="<?= site_url($bast['route'] . '/lihat/' . $row['id']) ?>"
                                                data-download="<?= site_url($bast['route'] . '/download/' . $row['id']) ?>"
                                                data-info="<?= esc(json_encode(['no' => $row['no_spk'] ?: '-', 'tanggal' => $fmtTanggal($row['tanggal_spk']), 'keperluan' => $row['keperluan'] ?: '-', 'lokasi' => $row['lokasi'] ?: '-', 'nama' => $row['nama_file'], 'format' => $row['format'], 'ukuran' => $fmtUkuran($row['ukuran']), 'waktu' => $fmtWaktu($row['created_at']), 'oleh' => $row['diupload_oleh'] ?: '-']), 'attr') ?>">
                                                <i class="ti ti-eye"></i> <span>View</span>
                                            </button>
                                            <a href="<?= site_url($bast['route'] . '/download/' . $row['id']) ?>" class="btn btn-sm btn-info" data-no-loader title="Download dokumen">
                                                <i class="ti ti-download"></i> <span>Download</span>
                                            </a>
                                            <button type="button" onclick="confirmDelete(<?= (int) $row['id'] ?>, this.dataset.nama)" data-nama="<?= esc(($row['no_spk'] ?: $row['nama_file']) . ($row['keperluan'] ? ' - ' . $row['keperluan'] : ''), 'attr') ?>" class="btn btn-sm btn-danger">
                                                <i class="ti ti-trash"></i> <span>Hapus</span>
                                            </button>
                                        </td>
                                        <td class="col-bold"><?= esc($row['no_spk'] ?: '-') ?></td>
                                        <td data-order="<?= esc($row['tanggal_spk'] ?? '', 'attr') ?>"><?= $fmtTanggal($row['tanggal_spk']) ?></td>
                                        <td class="whitespace-normal min-w-[160px]"><?= esc($row['keperluan'] ?: '-') ?></td>
                                        <td class="whitespace-normal min-w-[160px]"><?= esc($row['lokasi'] ?: '-') ?></td>
                                        <td class="whitespace-normal min-w-[200px]"><i class="ti <?= $IKON[$row['format']] ?? 'ti-file' ?> kz-ic"></i> <?= esc($row['nama_file']) ?></td>
                                        <td><span class="kz-badge is-<?= strtolower($row['format']) ?>"><?= esc($row['format']) ?></span></td>
                                        <!-- data-order: urutkan berdasarkan byte, tampil dalam KB / MB -->
                                        <td data-order="<?= (int) $row['ukuran'] ?>"><?= $fmtUkuran($row['ukuran']) ?></td>
                                        <td data-order="<?= esc($row['created_at'], 'attr') ?>"><?= $fmtWaktu($row['created_at']) ?></td>
                                        <td><?= esc($row['diupload_oleh'] ?: '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ====== POP UP VIEW: PDF & gambar tampil langsung, Word cukup diunduh ====== -->
    <div id="viewKmz" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4" onclick="if (event.target === this) tutupKmz()">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-7xl p-5 max-h-[95vh] overflow-y-auto">
            <div class="flex justify-between items-center gap-3 mb-4">
                <h3 class="text-lg font-bold flex items-center gap-2 min-w-0"><i class="ti ti-file-search text-[#2f6bff]"></i> <span class="truncate" id="kvJudul">View SPK</span></h3>
                <button type="button" onclick="tutupKmz()" aria-label="Tutup"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <div class="kz-view-body">
                <div class="bc-preview" id="bcPreview"></div>
                <div class="kz-side">
                    <div class="kz-side-item"><span>Nomor SPK</span><b id="kvNo">-</b></div>
                    <div class="kz-side-item"><span>Tanggal SPK</span><b id="kvTanggal">-</b></div>
                    <div class="kz-side-item"><span>Keperluan</span><b id="kvKeperluan">-</b></div>
                    <div class="kz-side-item"><span>Lokasi</span><b id="kvLokasi">-</b></div>
                    <div class="kz-side-item"><span>Nama File</span><b id="kvNama">-</b></div>
                    <div class="kz-side-item"><span>Format &amp; Ukuran</span><b id="kvFormat">-</b></div>
                    <div class="kz-side-item"><span>Tanggal &amp; Jam Upload</span><b id="kvWaktu">-</b></div>
                    <div class="kz-side-item"><span>Diupload Oleh</span><b id="kvOleh">-</b></div>
                    <a href="#" id="kvDownload" class="rfo-btn rfo-btn-blue justify-center" data-no-loader><i class="ti ti-download"></i> Download</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ====== PILIH / TARIK FILE: format, ukuran, tanggal & jam dibaca otomatis ======
        const MAKS_BYTE = <?= (int) $maksByte ?>;
        const BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        const FORMAT = { pdf: 'PDF', doc: 'DOC', docx: 'DOCX', png: 'PNG', jpg: 'JPG', jpeg: 'JPG' };
        const NAMA_FORMAT = { PDF: 'PDF (Dokumen)', DOC: 'Word 97-2003 (.doc)', DOCX: 'Word (.docx)', PNG: 'Gambar PNG', JPG: 'Gambar JPG' };
        const inputKmz = document.getElementById('fileKmz');
        const drop = document.getElementById('kzDrop');
        const info = document.getElementById('kzInfo');
        let jamTimer = null;

        // ====== BACA ISI DOKUMEN OTOMATIS (tanpa isi manual) ======
        // PDF: pdf.js (teks); PDF hasil scan & gambar PNG/JPG: OCR tesseract.js; DOCX: XML di dalam arsip (JSZip);
        // DOC lama: teks yang terbaca dari isi file. Pustaka dimuat hanya saat dibutuhkan.
        const PUSTAKA = {
            pdf: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js',
            pdfWorker: 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js',
            ocr: 'https://cdn.jsdelivr.net/npm/tesseract.js@5.1.1/dist/tesseract.min.js'
        };
        const dimuat = {};

        function muatScript(url) {
            if (!dimuat[url]) dimuat[url] = new Promise((ok, gagal) => {
                const s = document.createElement('script');
                s.src = url;
                s.onload = ok;
                s.onerror = () => gagal(new Error('Pustaka pembaca dokumen gagal dimuat (cek koneksi internet).'));
                document.head.appendChild(s);
            });
            return dimuat[url];
        }

        function statusBaca(teks, jenis) {
            const el = document.getElementById('bcStatus');
            el.className = 'bc-status' + (jenis ? ' is-' + jenis : '');
            el.innerHTML = teks;
        }

        async function ocrGambar(sumber) {
            await muatScript(PUSTAKA.ocr);
            const worker = await Tesseract.createWorker('ind+eng', 1, {
                logger: m => {
                    if (m.status === 'recognizing text') statusBaca('<i class="ti ti-loader-2 bc-putar"></i> Membaca teks gambar (OCR) ' + Math.round(m.progress * 100) + '%...');
                }
            });
            try {
                const hasil = await worker.recognize(sumber);
                return hasil.data.text || '';
            } finally {
                await worker.terminate();
            }
        }

        async function bacaPdf(file) {
            await muatScript(PUSTAKA.pdf);
            pdfjsLib.GlobalWorkerOptions.workerSrc = PUSTAKA.pdfWorker;
            const pdf = await pdfjsLib.getDocument({ data: await file.arrayBuffer() }).promise;
            const halaman = Math.min(pdf.numPages, 5);   // data SPK ada di halaman awal
            let teks = '';
            for (let n = 1; n <= halaman; n++) {
                const page = await pdf.getPage(n);
                const isi = await page.getTextContent();
                // susun per baris berdasarkan posisi vertikal, lalu urut kiri ke kanan
                const baris = {};
                isi.items.forEach(it => {
                    if (!it.str.trim()) return;
                    const y = Math.round(it.transform[5] / 3) * 3;
                    (baris[y] = baris[y] || []).push(it);
                });
                Object.keys(baris).map(Number).sort((a, b) => b - a).forEach(y => {
                    teks += baris[y].sort((a, b) => a.transform[4] - b.transform[4]).map(it => it.str).join(' ') + '\n';
                });
            }
            // PDF hasil scan (tanpa teks): halaman pertama digambar lalu dibaca dengan OCR
            if (teks.replace(/\s/g, '').length < 20) {
                statusBaca('<i class="ti ti-loader-2 bc-putar"></i> PDF berupa hasil scan, membaca dengan OCR...');
                const page = await pdf.getPage(1);
                const vp = page.getViewport({ scale: 2 });
                const canvas = document.createElement('canvas');
                canvas.width = vp.width;
                canvas.height = vp.height;
                await page.render({ canvasContext: canvas.getContext('2d'), viewport: vp }).promise;
                teks = await ocrGambar(canvas);
            }
            return teks;
        }

        async function bacaDocx(file) {
            const zip = await JSZip.loadAsync(await file.arrayBuffer());
            const xml = await zip.file('word/document.xml').async('string');
            // sel tabel dipisah " : " (label | isi), paragraf & baris tabel jadi baris baru
            return xml
                .replace(/<\/w:tc>/g, ' : ')
                .replace(/<\/w:p>|<\/w:tr>|<w:br[^>]*\/>/g, '\n')
                .replace(/<w:tab[^>]*\/>/g, ' ')
                .replace(/<[^>]+>/g, '')
                .replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&quot;/g, '"').replace(/&apos;/g, "'");
        }

        async function bacaDoc(file) {
            // Word 97-2003: teks disimpan sebagai UTF-16LE / ANSI di dalam berkas; ambil potongan teks yang terbaca
            const buf = new Uint8Array(await file.arrayBuffer());
            const u16 = new TextDecoder('utf-16le').decode(buf);
            const ansi = new TextDecoder('windows-1252').decode(buf);
            const potong = s => (s.match(/[\x20-\x7EÀ-ɏ\r\n\t]{4,}/g) || []).join('\n');
            return potong(u16) + '\n' + potong(ansi);
        }

        const BULAN_ANGKA = {
            jan: 1, januari: 1, january: 1, feb: 2, peb: 2, februari: 2, pebruari: 2, february: 2, mar: 3, maret: 3, march: 3,
            apr: 4, april: 4, mei: 5, may: 5, jun: 6, juni: 6, june: 6, jul: 7, juli: 7, july: 7,
            agu: 8, agt: 8, agst: 8, agus: 8, agustus: 8, aug: 8, august: 8, sep: 9, sept: 9, september: 9,
            okt: 10, oktober: 10, oct: 10, october: 10, nov: 11, nop: 11, november: 11, nopember: 11, des: 12, desember: 12, dec: 12, december: 12
        };

        // cari tanggal di teks; kembalikan 'YYYY-MM-DD' atau null
        function cariTanggal(teks) {
            const jadi = (d, m, y) => {
                y = Number(y);
                if (y < 100) y += 2000;
                d = Number(d);
                m = Number(m);
                const t = new Date(y, m - 1, d);
                if (!(y >= 2000 && y <= 2100) || t.getMonth() !== m - 1 || t.getDate() !== d) return null;
                return y + '-' + String(m).padStart(2, '0') + '-' + String(d).padStart(2, '0');
            };
            const pola = [
                [/\b(\d{1,2})\s+([A-Za-z]{3,9})\.?\s+(\d{4})\b/g, m => BULAN_ANGKA[m[2].toLowerCase()] ? jadi(m[1], BULAN_ANGKA[m[2].toLowerCase()], m[3]) : null],
                [/\b([A-Za-z]{3,9})\.?\s+(\d{1,2}),?\s+(\d{4})\b/g, m => BULAN_ANGKA[m[1].toLowerCase()] ? jadi(m[2], BULAN_ANGKA[m[1].toLowerCase()], m[3]) : null],
                [/\b(\d{4})[-\/.](\d{1,2})[-\/.](\d{1,2})\b/g, m => jadi(m[3], m[2], m[1])],
                [/\b(\d{1,2})[-\/.](\d{1,2})[-\/.](\d{2,4})\b/g, m => jadi(m[1], m[2], m[3])]
            ];
            let terbaik = null;
            pola.forEach(([re, ubah]) => {
                for (const m of teks.matchAll(re)) {
                    const h = ubah(m);
                    if (h && (terbaik === null || m.index < terbaik.i)) terbaik = { i: m.index, v: h };
                }
            });
            return terbaik && terbaik.v;
        }

        // ambil nilai setelah label ("Label : nilai" atau label di satu baris, nilai di baris berikutnya)
        function ambilLabel(baris, labels, cocok) {
            const semuaLabel = /\b(nomor|no\.?|tanggal|tgl|keperluan|pekerjaan|perihal|tujuan|lokasi|tempat|alamat|pelaksana|teknisi|telepon|telp|email|jabatan|nama)\b\s*[:：]/i;
            for (const label of labels) {
                const re = new RegExp('^\\s*' + label + '\\s*(?:[:：|]+\\s*)+(.+)$', 'i');
                const reSendiri = new RegExp('^\\s*' + label + '\\s*[:：|]*\\s*$', 'i');
                for (let i = 0; i < baris.length; i++) {
                    let v = null;
                    const m = baris[i].match(re);
                    if (m) v = m[1];
                    else if (reSendiri.test(baris[i]) && baris[i + 1]) v = baris[i + 1].replace(/^[\s:：|]+/, '');
                    if (!v) continue;
                    // potong bila di baris yang sama ada label lain ("... Lokasi : ...")
                    const sisa = v.search(semuaLabel);
                    if (sisa > 0) v = v.slice(0, sisa);
                    v = v.replace(/^[\s:：|]+|[\s:：|,;]+$/g, '').replace(/\s+/g, ' ');
                    if (v && (!cocok || cocok.test(v))) return v;
                }
            }
            return null;
        }

        function uraiSpk(teks) {
            const bersih = teks.replace(/\r/g, '\n').replace(/[ \t\u00A0]+/g, ' ');
            const baris = bersih.split('\n').map(s => s.trim()).filter(Boolean);
            const kodeDok = /[A-Z0-9]+(?:[\/\-.][A-Z0-9]+){2,}/i;

            let no = ambilLabel(baris, ['nomor\\s*spk', 'no\\.?\\s*spk', 'nomor\\s*surat\\s*perintah\\s*kerja', 'no\\.?\\s*surat\\s*perintah\\s*kerja', 'nomor\\s*surat', 'no\\.?\\s*surat', 'nomor\\s*dokumen', 'no\\.?\\s*dokumen']) ||
                ambilLabel(baris, ['nomor', 'no\\.?'], kodeDok);
            if (no) {
                const k = no.match(kodeDok);
                no = (k ? k[0] : no.split(' ')[0]).slice(0, 100);
            } else {
                // kode dokumen yang memuat kata SPK, mis. 015/SPK/NOC/XI/2026
                const m = bersih.match(/[A-Z0-9]*(?:[\/\-.][A-Z0-9]+)*[\/\-.]?SPK(?:[\/\-.][A-Z0-9]+)+|SPK[\/\-.][A-Z0-9\/\-.]+/i);
                no = m ? m[0].slice(0, 100) : null;
            }

            const tglLabel = ambilLabel(baris, ['tanggal\\s*spk', 'tanggal\\s*surat', 'tanggal\\s*pelaksanaan', 'tanggal\\s*pekerjaan', 'tanggal\\s*kegiatan', 'tanggal', 'tgl\\.?', 'hari\\s*/\\s*tanggal', 'waktu', 'date']);
            const tanggal = (tglLabel && cariTanggal(tglLabel)) || cariTanggal(bersih);

            const keperluan = ambilLabel(baris, ['keperluan', 'jenis\\s*pekerjaan', 'uraian\\s*pekerjaan', 'deskripsi\\s*pekerjaan', 'pekerjaan', 'perihal', 'hal', 'tujuan', 'kegiatan', 'job\\s*desc(?:ription)?']);
            const lokasi = ambilLabel(baris, ['lokasi\\s*pekerjaan', 'lokasi', 'tempat\\s*pekerjaan', 'tempat', 'alamat\\s*pekerjaan', 'alamat', 'site', 'nama\\s*site', 'location']);

            const potong = (v, n) => v ? v.slice(0, n) : null;
            return { no: potong(no, 100), tanggal, keperluan: potong(keperluan, 255), lokasi: potong(lokasi, 255) };
        }

        let bacaKe = 0;          // abaikan hasil bacaan file sebelumnya bila file diganti
        let sedangBaca = false;

        async function bacaDokumen(file, format) {
            const ke = ++bacaKe;
            sedangBaca = true;
            const f = document.getElementById('formKmz').elements;
            ['no_spk', 'tanggal_spk', 'keperluan', 'lokasi'].forEach(k => f[k].value = '');
            ['bcNo', 'bcTanggal', 'bcKeperluan', 'bcLokasi'].forEach(id => {
                const b = document.getElementById(id);
                b.innerHTML = '<span class="bc-tunggu">membaca...</span>';
                b.classList.remove('hidden');
                b.nextElementSibling.classList.add('hidden');
            });
            document.getElementById('kzKirim').disabled = true;
            statusBaca('<i class="ti ti-loader-2 bc-putar"></i> Membaca isi dokumen...');

            let hasil = { no: null, tanggal: null, keperluan: null, lokasi: null };
            let gagal = null;
            try {
                const teks = format === 'PDF' ? await bacaPdf(file)
                    : format === 'DOCX' ? await bacaDocx(file)
                    : format === 'DOC' ? await bacaDoc(file)
                    : await ocrGambar(file);
                hasil = uraiSpk(teks);
            } catch (err) {
                gagal = err && err.message ? err.message : 'Isi dokumen tidak bisa dibaca.';
            }
            if (ke !== bacaKe) return;   // file sudah diganti / dibatalkan

            const BLN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            // terbaca = tampil sebagai teks; tidak terbaca = muncul kotak isian manual (boleh dikosongkan)
            const tampil = (id, v, teks) => {
                const b = document.getElementById(id);
                b.innerHTML = v ? escapeHtml(teks || v) : '<span class="bc-kosong">Tidak terbaca</span>';
                b.nextElementSibling.classList.toggle('hidden', !!v);
            };
            f.no_spk.value = hasil.no || '';
            f.tanggal_spk.value = hasil.tanggal || '';
            f.keperluan.value = hasil.keperluan || '';
            f.lokasi.value = hasil.lokasi || '';
            tampil('bcNo', hasil.no);
            tampil('bcTanggal', hasil.tanggal, hasil.tanggal && (Number(hasil.tanggal.slice(8)) + ' ' + BLN[Number(hasil.tanggal.slice(5, 7)) - 1] + ' ' + hasil.tanggal.slice(0, 4)));
            tampil('bcKeperluan', hasil.keperluan);
            tampil('bcLokasi', hasil.lokasi);

            const terbaca = ['no', 'tanggal', 'keperluan', 'lokasi'].filter(k => hasil[k]).length;
            if (gagal) statusBaca('<i class="ti ti-alert-triangle"></i> ' + escapeHtml(gagal) + ' Silakan isi data SPK secara manual di bawah.', 'warn');
            else if (terbaca === 4) statusBaca('<i class="ti ti-circle-check"></i> Data SPK terbaca otomatis dari isi dokumen.', 'ok');
            else if (terbaca) statusBaca('<i class="ti ti-info-circle"></i> ' + terbaca + ' dari 4 data terbaca. Lengkapi yang tidak terbaca secara manual (boleh dikosongkan).', 'warn');
            else statusBaca('<i class="ti ti-alert-triangle"></i> Data SPK tidak ditemukan di isi dokumen. Silakan isi secara manual di bawah (boleh dikosongkan).', 'warn');

            sedangBaca = false;
            document.getElementById('kzKirim').disabled = false;
        }

        function escapeHtml(s) {
            return (s == null ? '' : String(s)).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        function fmtUkuran(b) {
            if (b >= 1048576) return (b / 1048576).toFixed(2).replace('.', ',') + ' MB';
            if (b >= 1024) return (b / 1024).toFixed(1).replace('.', ',') + ' KB';
            return b + ' B';
        }

        function waktuSekarang() {
            const d = new Date();
            const dua = n => String(n).padStart(2, '0');
            return d.getDate() + ' ' + BULAN[d.getMonth()] + ' ' + d.getFullYear() + ', ' + dua(d.getHours()) + ':' + dua(d.getMinutes()) + ':' + dua(d.getSeconds()) + ' WIB';
        }

        function batalPilih() {
            bacaKe++;
            sedangBaca = false;
            inputKmz.value = '';
            info.classList.add('hidden');
            drop.classList.remove('hidden');
            clearInterval(jamTimer);
        }

        function tolak(pesan) {
            batalPilih();
            Swal.fire({ icon: 'warning', title: 'Dokumen Tidak Bisa Diupload', text: pesan, confirmButtonColor: '#185a82' });
        }

        function pilihFile(file) {
            if (!file) return batalPilih();
            const ext = file.name.split('.').pop().toLowerCase();
            const format = FORMAT[ext];
            if (!format) return tolak('Dokumen harus berformat PDF, Word (.doc / .docx), PNG, atau JPG.');
            if (file.size <= 0) return tolak('File kosong.');
            if (file.size > MAKS_BYTE) return tolak('Ukuran file ' + fmtUkuran(file.size) + ' melebihi batas 30 MB.');

            const badge = document.getElementById('kzFormat');
            badge.textContent = format;
            badge.className = 'kz-badge is-' + format.toLowerCase();
            document.getElementById('kzNama').textContent = file.name;
            document.getElementById('kzFormatTeks').textContent = NAMA_FORMAT[format];
            document.getElementById('kzUkuran').textContent = fmtUkuran(file.size);
            // tanggal & jam berjalan sampai tombol Upload ditekan (tercatat di server saat file diterima)
            const tampilJam = () => document.getElementById('kzWaktu').textContent = waktuSekarang();
            tampilJam();
            clearInterval(jamTimer);
            jamTimer = setInterval(tampilJam, 1000);

            drop.classList.add('hidden');
            info.classList.remove('hidden');
            bacaDokumen(file, format);
        }

        inputKmz.addEventListener('change', () => pilihFile(inputKmz.files[0]));
        document.getElementById('kzBatal').addEventListener('click', batalPilih);

        ['dragenter', 'dragover'].forEach(t => drop.addEventListener(t, e => {
            e.preventDefault();
            drop.classList.add('is-over');
        }));
        ['dragleave', 'drop'].forEach(t => drop.addEventListener(t, e => {
            e.preventDefault();
            drop.classList.remove('is-over');
        }));
        drop.addEventListener('drop', e => {
            const file = e.dataTransfer.files[0];
            if (!file) return;
            const dt = new DataTransfer();
            dt.items.add(file);
            inputKmz.files = dt.files;
            pilihFile(file);
        });

        // Nomor SPK yang sudah ada (cek dobel sebelum dikirim; server tetap memeriksa)
        const NO_SPK = <?= json_encode(array_map(fn ($r) => ['no' => $r['no_spk'], 'keperluan' => $r['keperluan']], $files), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

        document.getElementById('formKmz').addEventListener('submit', function(e) {
            const f = this.elements;
            const pesan = [];
            if (!inputKmz.files.length) pesan.push('Pilih dokumen SPK terlebih dahulu.');
            if (sedangBaca) pesan.push('Isi dokumen masih dibaca, tunggu sebentar.');
            const no = f.no_spk.value.trim().toLowerCase();
            const dobel = no && NO_SPK.find(x => x.no && x.no.toLowerCase() === no);
            if (dobel) pesan.push('Nomor SPK "' + dobel.no + '" sudah dipakai' + (dobel.keperluan ? ' (' + dobel.keperluan + ')' : '') + '.');
            if (pesan.length) {
                e.preventDefault();
                return Swal.fire({ icon: 'warning', title: 'Dokumen Belum Bisa Diupload', html: pesan.map(p => '<div class="text-left">• ' + escapeHtml(p) + '</div>').join(''), confirmButtonColor: '#185a82' });
            }
            clearInterval(jamTimer);
            const btn = document.getElementById('kzKirim');
            btn.disabled = true;
            btn.innerHTML = '<i class="ti ti-loader-2"></i> Mengupload...';
        });

        // ====== VIEW: PDF di iframe, gambar di <img>, Word hanya bisa diunduh ======
        function lihatKmz(btn) {
            const d = JSON.parse(btn.dataset.info);
            const set = (id, v) => document.getElementById(id).textContent = v;
            set('kvJudul', d.no !== '-' ? d.no + (d.keperluan !== '-' ? ' — ' + d.keperluan : '') : d.nama);
            set('kvNo', d.no);
            set('kvTanggal', d.tanggal);
            set('kvKeperluan', d.keperluan);
            set('kvLokasi', d.lokasi);
            set('kvNama', d.nama);
            set('kvFormat', d.format + ' · ' + d.ukuran);
            set('kvWaktu', d.waktu);
            set('kvOleh', d.oleh);
            document.getElementById('kvDownload').href = btn.dataset.download;

            const box = document.getElementById('bcPreview');
            box.innerHTML = '';
            if (d.format === 'PDF') {
                const fr = document.createElement('iframe');
                fr.src = btn.dataset.url;
                fr.title = d.nama;
                box.appendChild(fr);
            } else if (d.format === 'PNG' || d.format === 'JPG') {
                const img = document.createElement('img');
                img.src = btn.dataset.url;
                img.alt = d.nama;
                box.appendChild(img);
            } else {
                box.innerHTML = '<div class="bc-word"><i class="ti ti-file-type-doc"></i><b>Pratinjau Word tidak tersedia di browser</b><span>Klik Download untuk membuka dokumen di Microsoft Word.</span></div>';
            }
            document.getElementById('viewKmz').classList.remove('hidden');
        }

        function tutupKmz() {
            document.getElementById('viewKmz').classList.add('hidden');
            document.getElementById('bcPreview').innerHTML = '';
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && !document.getElementById('viewKmz').classList.contains('hidden')) tutupKmz();
        });

        // ====== DELETE ======
        function confirmDelete(id, nama) {
            Swal.fire({
                title: 'Hapus SPK?',
                text: '"' + nama + '" beserta dokumennya akan dihapus dan tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) window.location.href = <?= json_encode(site_url($bast['route'] . '/delete/')) ?> + id;
            });
        }

        function isTableEmpty(table) {
            return table.rows({ search: 'applied' }).data().length === 0;
        }

        function showEmptyExportAlert() {
            Swal.fire({ icon: 'warning', title: 'Data Kosong', text: 'Tidak ada data yang bisa diexport.', confirmButtonColor: '#04a9f5' });
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
                    columns: ':visible:not(.no-export)',
                    format: {
                        body: function(data) {
                            const tmp = document.createElement('div');
                            tmp.innerHTML = String(data).replace(/<\/div>/g, '</div>\n');
                            return tmp.textContent.replace(/[ \t]+/g, ' ').replace(/\n\s*\n/g, '\n').trim();
                        }
                    }
                }
            };
            const tombol = (jenis, teks, ikon, extra) => Object.assign({
                extend: jenis,
                text: '<i class="ti ' + ikon + '"></i> ' + teks,
                title: <?= json_encode('Data ' . $bast['judul']) ?>,
                ...exportConfig,
                action: function(e, dt, button, config) {
                    if (isTableEmpty(dt)) return showEmptyExportAlert();
                    $.fn.dataTable.ext.buttons[jenis].action.call(this, e, dt, button, config);
                }
            }, extra || {});

            const table = $('#reportTable').DataTable({
                pageLength: 10,
                lengthMenu: [
                    [10, 15, 25, 50, -1],
                    [10, 15, 25, 50, "Semua"]
                ],
                order: [[3, 'desc']],
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
                        tombol('copyHtml5', 'Copy', 'ti-copy'),
                        tombol('csvHtml5', 'Export CSV', 'ti-file-text'),
                        tombol('excelHtml5', 'Export Excel', 'ti-file-spreadsheet'),
                        tombol('pdfHtml5', 'Export PDF', 'ti-file-type-pdf', {
                            orientation: 'landscape',
                            pageSize: 'A4',
                            customize: function(doc) {
                                doc.styles.tableHeader = { fillColor: '#04a9f5', color: '#fff', bold: true, alignment: 'left' };
                                doc.defaultStyle.fontSize = 9;
                            }
                        })
                    ]
                }],
                language: {
                    lengthMenu: "_MENU_",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 - 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ data)",
                    emptyTable: <?= json_encode('Belum ada dokumen ' . $bast['judul']) ?>,
                    zeroRecords: "Tidak ada data yang cocok dengan pencarian",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Selanjutnya"
                    }
                }
            });

            // nomor urut mengikuti urutan & filter yang tampil
            table.on('draw.dt order.dt search.dt', function() {
                let i = table.page.info().start;
                table.column(0, { page: 'current', search: 'applied', order: 'applied' }).nodes().each(function(cell) {
                    cell.innerHTML = ++i;
                });
            });

            // ====== SCROLL TABEL: lebih dari 10 baris tampil = scroll di dalam card ======
            const MAKS_BARIS = 10;

            function batasiTinggiTabel() {
                const area = document.querySelector('.rfo-page .table-scroll');
                const baris = area.querySelectorAll('#reportTable tbody tr');
                area.style.maxHeight = '';
                if (baris.length > MAKS_BARIS) {
                    const atas = area.querySelector('#reportTable thead').getBoundingClientRect().top;
                    const bawah = baris[MAKS_BARIS - 1].getBoundingClientRect().bottom;
                    area.style.maxHeight = Math.ceil(bawah - atas + area.offsetHeight - area.clientHeight) + 'px';
                }
                area.scrollTop = 0;
            }
            table.on('draw.dt', batasiTinggiTabel);
            $(window).on('resize', batasiTinggiTabel);
            if (document.fonts && document.fonts.ready) document.fonts.ready.then(batasiTinggiTabel);

            table.draw();

            $('#lengthArea').append($('.dataTables_length'));
            $('#exportArea').append($('.dt-buttons'));

            // ====== FILTER DROPDOWN (cocok persis): 4 Keperluan, 7 Format ======
            const filterKolom = (sel, col) => $(sel).on('change', function() {
                const re = this.value ? '^' + $.fn.dataTable.util.escapeRegex(this.value) + '$' : '';
                table.column(col).search(re, true, false).draw();
            });
            filterKolom('#filterKeperluan', 4);
            filterKolom('#filterFormat', 7);

            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
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