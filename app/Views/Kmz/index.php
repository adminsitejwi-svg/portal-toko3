<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-noc.png') ?>?v=3">
    <link rel="shortcut icon" href="<?= base_url('favicon.ico') ?>?v=3">
    <link rel="apple-touch-icon" href="<?= base_url('apple-touch-icon.png') ?>?v=3">
    <title>KMZ</title>
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
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/@tmcw/togeojson@5.8.1/dist/togeojson.umd.js"></script>
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

        .kz-badge.is-kml {
            color: #047857;
            background: #d1fae5;
        }

        html.dark .kz-badge {
            color: #93c5fd;
            background: rgba(59, 130, 246, .18);
        }

        html.dark .kz-badge.is-kml {
            color: #6ee7b7;
            background: rgba(16, 185, 129, .18);
        }

        .kz-ic {
            color: #2f6bff;
            font-size: 16px;
            vertical-align: -2px;
        }

        /* ===== VIEW KMZ / KML (pop up peta) ===== */
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

        #kzMap {
            height: min(68vh, 640px);
            min-height: 320px;
            border-radius: 14px;
            border: 1px solid var(--rf-border);
            overflow: hidden;
            z-index: 0;
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
                        <li><a href="<?= site_url('SlaReport') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">SLA Report</a></li>
                        <li><a href="<?= site_url('MonthlyReport') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Monthly Report</a></li>
                        <li><a href="<?= site_url('SurveyReport') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Survey Report</a></li>
                        <li><a href="<?= site_url('InstallationReport') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Installation Report</a></li>
                        <li><a href="<?= site_url('TroubleshootReport') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Troubleshoot Report</a></li>
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
                        <li><a href="<?= site_url('IpPublic') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data IP Public</a></li>
                        <li><a href="<?= site_url('IpPrivate') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data IP Private</a></li>
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
            $totalUkuran = array_sum(array_map('intval', array_column($files, 'ukuran')));
            ?>
            <!-- JUDUL -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic is-sky"><i class="ti ti-map-2"></i></span>
                    <div>
                        <h1 class="rfo-title">KMZ</h1>
                        <div class="rfo-subtitle">File peta KMZ / KML jalur kabel &amp; titik jaringan</div>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="kz-chip"><i class="ti ti-files"></i> <?= count($files) ?> file</span>
                    <span class="kz-chip"><i class="ti ti-database"></i> <?= $fmtUkuran($totalUkuran) ?></span>
                </div>
            </div>

            <!-- UPLOAD -->
            <div class="rfo-panel mb-6">
                <form method="POST" action="<?= site_url('Kmz/upload') ?>" enctype="multipart/form-data" id="formKmz">
                    <?= csrf_field() ?>
                    <label class="kz-drop" id="kzDrop" for="fileKmz">
                        <input type="file" name="file_kmz" id="fileKmz" accept=".kmz,.kml" hidden>
                        <span class="kz-drop-ic"><i class="ti ti-cloud-upload"></i></span>
                        <span class="kz-drop-title">Klik atau tarik file KMZ / KML ke sini</span>
                        <span class="kz-drop-sub">Hanya file .kmz atau .kml, maksimal 30 MB</span>
                    </label>

                    <!-- muncul setelah file dipilih: format, ukuran, tanggal & jam terbaca otomatis -->
                    <div class="kz-info hidden" id="kzInfo">
                        <div class="kz-info-file">
                            <span class="kz-badge" id="kzFormat">KMZ</span>
                            <span class="kz-info-nama" id="kzNama">-</span>
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
                            <select id="filterFormat" class="filter-select" title="Filter Format">
                                <option value="">Semua Format</option>
                                <option value="KMZ">KMZ</option>
                                <option value="KML">KML</option>
                            </select>
                        </div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <div class="rfo-search">
                                <i class="ti ti-search"></i>
                                <input type="text" id="customSearch" placeholder="Cari nama file..." />
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
                                    <th>Nama File</th>
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
                                            <button type="button" class="btn btn-sm btn-success" title="Lihat di peta" onclick="lihatKmz(this)"
                                                data-url="<?= site_url('Kmz/download/' . $row['id']) ?>"
                                                data-info="<?= esc(json_encode(['nama' => $row['nama_file'], 'format' => $row['format'], 'ukuran' => $fmtUkuran($row['ukuran']), 'waktu' => $fmtWaktu($row['created_at']), 'oleh' => $row['diupload_oleh'] ?: '-']), 'attr') ?>">
                                                <i class="ti ti-map-search"></i> <span>View</span>
                                            </button>
                                            <a href="<?= site_url('Kmz/download/' . $row['id']) ?>" class="btn btn-sm btn-info" data-no-loader title="Download file">
                                                <i class="ti ti-download"></i> <span>Download</span>
                                            </a>
                                            <button type="button" onclick="confirmDelete(<?= (int) $row['id'] ?>, this.dataset.nama)" data-nama="<?= esc($row['nama_file'], 'attr') ?>" class="btn btn-sm btn-danger">
                                                <i class="ti ti-trash"></i> <span>Hapus</span>
                                            </button>
                                        </td>
                                        <td class="col-bold whitespace-normal min-w-[220px]"><i class="ti ti-file-type-<?= $row['format'] === 'KML' ? 'xml' : 'zip' ?> kz-ic"></i> <?= esc($row['nama_file']) ?></td>
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

    <!-- ====== POP UP VIEW: isi file KMZ / KML ditampilkan di peta ====== -->
    <div id="viewKmz" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4" onclick="if (event.target === this) tutupKmz()">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-7xl p-5 max-h-[95vh] overflow-y-auto">
            <div class="flex justify-between items-center gap-3 mb-4">
                <h3 class="text-lg font-bold flex items-center gap-2 min-w-0"><i class="ti ti-map-2 text-[#2f6bff]"></i> <span class="truncate" id="kvJudul">View KMZ</span></h3>
                <button type="button" onclick="tutupKmz()" aria-label="Tutup"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <div class="kz-view-body">
                <div id="kzMap"></div>
                <div class="kz-side">
                    <div class="kz-status" id="kvStatus">Memuat file...</div>
                    <div class="kz-stat">
                        <div><b id="kvTitik">0</b><span>Titik</span></div>
                        <div><b id="kvJalur">0</b><span>Jalur</span></div>
                        <div><b id="kvArea">0</b><span>Area</span></div>
                    </div>
                    <div class="kz-side-item"><span>Nama File</span><b id="kvNama">-</b></div>
                    <div class="kz-side-item"><span>Format</span><b id="kvFormat">-</b></div>
                    <div class="kz-side-item"><span>Ukuran</span><b id="kvUkuran">-</b></div>
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
        const inputKmz = document.getElementById('fileKmz');
        const drop = document.getElementById('kzDrop');
        const info = document.getElementById('kzInfo');
        let jamTimer = null;

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
            inputKmz.value = '';
            info.classList.add('hidden');
            drop.classList.remove('hidden');
            clearInterval(jamTimer);
        }

        function tolak(pesan) {
            batalPilih();
            Swal.fire({ icon: 'warning', title: 'File Tidak Bisa Diupload', text: pesan, confirmButtonColor: '#185a82' });
        }

        function pilihFile(file) {
            if (!file) return batalPilih();
            const ext = file.name.split('.').pop().toLowerCase();
            if (!['kmz', 'kml'].includes(ext)) return tolak('File harus berformat .kmz atau .kml.');
            if (file.size <= 0) return tolak('File kosong.');
            if (file.size > MAKS_BYTE) return tolak('Ukuran file ' + fmtUkuran(file.size) + ' melebihi batas 30 MB.');

            const format = ext.toUpperCase();
            const badge = document.getElementById('kzFormat');
            badge.textContent = format;
            badge.className = 'kz-badge is-' + ext;
            document.getElementById('kzNama').textContent = file.name;
            document.getElementById('kzFormatTeks').textContent = format === 'KMZ' ? 'KMZ (Google Earth terkompresi)' : 'KML (Google Earth)';
            document.getElementById('kzUkuran').textContent = fmtUkuran(file.size);
            // tanggal & jam berjalan sampai tombol Upload ditekan (tercatat di server saat file diterima)
            const tampilJam = () => document.getElementById('kzWaktu').textContent = waktuSekarang();
            tampilJam();
            clearInterval(jamTimer);
            jamTimer = setInterval(tampilJam, 1000);

            drop.classList.add('hidden');
            info.classList.remove('hidden');
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

        document.getElementById('formKmz').addEventListener('submit', function(e) {
            if (!inputKmz.files.length) {
                e.preventDefault();
                return tolak('Pilih file KMZ / KML terlebih dahulu.');
            }
            clearInterval(jamTimer);
            const btn = document.getElementById('kzKirim');
            btn.disabled = true;
            btn.innerHTML = '<i class="ti ti-loader-2"></i> Mengupload...';
        });

        // ====== VIEW: buka KMZ / KML di peta ======
        // KMZ = ZIP berisi file .kml (dibuka dengan JSZip); KML dibaca langsung. KML diubah ke GeoJSON (togeojson)
        // lalu digambar dengan Leaflet. Gambar overlay / ikon khusus di dalam KMZ tidak ditampilkan.
        let kzPeta = null;
        let kzLapisan = null;
        let kzMuatKe = 0;

        function siapkanPeta() {
            if (kzPeta) return kzPeta;
            kzPeta = L.map('kzMap', { zoomControl: true }).setView([-2.5, 118], 5);
            const jalan = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap' });
            const satelit = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', { maxZoom: 19, attribution: '&copy; Esri' });
            jalan.addTo(kzPeta);
            L.control.layers({ 'Peta Jalan': jalan, 'Satelit': satelit }).addTo(kzPeta);
            return kzPeta;
        }

        function statusKmz(teks, error) {
            const el = document.getElementById('kvStatus');
            el.textContent = teks;
            el.classList.toggle('is-error', !!error);
            el.classList.toggle('hidden', !teks);
        }

        // teks polos dari deskripsi KML (yang kadang berisi HTML) — tidak dirender sebagai HTML
        function teksPolos(html) {
            // togeojson memberi deskripsi HTML sebagai objek { '@type': 'html', value: '...' }
            if (html && typeof html === 'object') html = html.value;
            if (!html) return '';
            const doc = new DOMParser().parseFromString(String(html).replace(/<br\s*\/?>/gi, '\n'), 'text/html');
            doc.querySelectorAll('script, style').forEach(el => el.remove());
            return (doc.body.textContent || '').trim();
        }

        async function bacaKml(buf, format) {
            if (format === 'KMZ') {
                const zip = await JSZip.loadAsync(buf);
                const daftar = Object.values(zip.files).filter(f => !f.dir && /\.kml$/i.test(f.name));
                if (!daftar.length) throw new Error('Tidak ada file KML di dalam KMZ.');
                // doc.kml (nama standar Google Earth) didahulukan
                daftar.sort((a, b) => (/(^|\/)doc\.kml$/i.test(b.name) ? 1 : 0) - (/(^|\/)doc\.kml$/i.test(a.name) ? 1 : 0));
                return daftar[0].async('string');
            }
            return new TextDecoder('utf-8').decode(buf);
        }

        async function lihatKmz(btn) {
            const d = JSON.parse(btn.dataset.info);
            const urutan = ++kzMuatKe;
            document.getElementById('kvJudul').textContent = d.nama;
            document.getElementById('kvNama').textContent = d.nama;
            document.getElementById('kvFormat').textContent = d.format;
            document.getElementById('kvUkuran').textContent = d.ukuran;
            document.getElementById('kvWaktu').textContent = d.waktu;
            document.getElementById('kvOleh').textContent = d.oleh;
            document.getElementById('kvDownload').href = btn.dataset.url;
            ['kvTitik', 'kvJalur', 'kvArea'].forEach(id => document.getElementById(id).textContent = '0');
            document.getElementById('viewKmz').classList.remove('hidden');

            const peta = siapkanPeta();
            setTimeout(() => peta.invalidateSize(), 60);
            if (kzLapisan) {
                peta.removeLayer(kzLapisan);
                kzLapisan = null;
            }
            statusKmz('Memuat file...', false);

            try {
                const res = await fetch(btn.dataset.url, { credentials: 'same-origin' });
                if (!res.ok) throw new Error('File tidak bisa diambil dari server.');
                const teks = await bacaKml(await res.arrayBuffer(), d.format);
                if (urutan !== kzMuatKe) return; // sudah membuka file lain

                const xml = new DOMParser().parseFromString(teks, 'text/xml');
                if (xml.getElementsByTagName('parsererror').length) throw new Error('Isi KML rusak / tidak bisa dibaca.');
                const geo = toGeoJSON.kml(xml);
                const fitur = (geo.features || []).filter(f => f.geometry);

                const hitung = { titik: 0, jalur: 0, area: 0 };
                fitur.forEach(f => {
                    const t = f.geometry.type;
                    const geos = t === 'GeometryCollection' ? f.geometry.geometries.map(g => g.type) : [t];
                    geos.forEach(g => {
                        if (/Point/.test(g)) hitung.titik++;
                        else if (/LineString/.test(g)) hitung.jalur++;
                        else if (/Polygon/.test(g)) hitung.area++;
                    });
                });
                document.getElementById('kvTitik').textContent = hitung.titik;
                document.getElementById('kvJalur').textContent = hitung.jalur;
                document.getElementById('kvArea').textContent = hitung.area;

                if (!fitur.length) {
                    statusKmz('File terbaca, tetapi tidak ada titik / jalur / area yang bisa ditampilkan.', true);
                    return;
                }

                kzLapisan = L.geoJSON({ type: 'FeatureCollection', features: fitur }, {
                    // warna mengikuti style di file KML bila ada
                    style: f => ({
                        color: f.properties.stroke || '#2f6bff',
                        weight: Math.max(2, Number(f.properties['stroke-width']) || 3),
                        opacity: f.properties['stroke-opacity'] ?? 1,
                        fillColor: f.properties.fill || f.properties.stroke || '#2f6bff',
                        fillOpacity: f.properties['fill-opacity'] ?? .25
                    }),
                    pointToLayer: (f, latlng) => L.circleMarker(latlng, {
                        radius: 6,
                        weight: 2,
                        color: '#fff',
                        fillColor: f.properties['marker-color'] || f.properties.stroke || '#ef4444',
                        fillOpacity: 1
                    }),
                    onEachFeature: (f, layer) => {
                        const nama = f.properties.name || '';
                        const ket = teksPolos(f.properties.description);
                        if (!nama && !ket) return;
                        const isi = document.createElement('div');
                        isi.className = 'kz-popup';
                        if (nama) isi.appendChild(Object.assign(document.createElement('b'), { textContent: nama }));
                        if (ket) isi.appendChild(Object.assign(document.createElement('div'), { textContent: ket }));
                        layer.bindPopup(isi);
                        if (nama) layer.bindTooltip(nama, { sticky: true });
                    }
                }).addTo(peta);

                const batas = kzLapisan.getBounds();
                if (batas.isValid()) peta.fitBounds(batas, { padding: [24, 24], maxZoom: 17 });
                statusKmz('', false);
            } catch (err) {
                if (urutan !== kzMuatKe) return;
                statusKmz('Gagal menampilkan file: ' + (err && err.message ? err.message : 'tidak diketahui'), true);
            }
        }

        function tutupKmz() {
            document.getElementById('viewKmz').classList.add('hidden');
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape' && !document.getElementById('viewKmz').classList.contains('hidden')) tutupKmz();
        });

        // ====== DELETE ======
        function confirmDelete(id, nama) {
            Swal.fire({
                title: 'Hapus File KMZ?',
                text: '"' + nama + '" akan dihapus dan tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) window.location.href = "<?= site_url('Kmz/delete/') ?>" + id;
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
                title: 'Data KMZ',
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
                order: [[5, 'desc']],
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
                    emptyTable: "Belum ada file KMZ / KML",
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

            // ====== FILTER DROPDOWN (cocok persis): 3 Format ======
            const filterKolom = (sel, col) => $(sel).on('change', function() {
                const re = this.value ? '^' + $.fn.dataTable.util.escapeRegex(this.value) + '$' : '';
                table.column(col).search(re, true, false).draw();
            });
            filterKolom('#filterFormat', 3);

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