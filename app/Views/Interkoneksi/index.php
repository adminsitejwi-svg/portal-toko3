<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title>Data Center &amp; Interkoneksi</title>
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

        /* ===== TAB HALAMAN (seperti tab Master Data crm.jwi.id/Vendor) ===== */
        .sat-tabs {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 4px;
            padding: 6px 10px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
        }

        .sat-tab {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 9px;
            border-radius: 5px;
            font-size: 12.5px;
            color: #475569;
            white-space: nowrap;
            transition: background .15s, color .15s;
        }

        .sat-tab i {
            font-size: 14px;
        }

        .sat-tab:hover {
            background: #f3f4f6;
            color: #1e293b;
        }

        .sat-tab.active {
            background: #eef0f3;
            color: #94a3b8;
            cursor: default;
        }

        .dark .sat-tabs {
            background: #263240;
            border-color: rgba(255, 255, 255, .1);
        }

        .dark .sat-tab {
            color: #bfc8d6;
        }

        .dark .sat-tab:hover,
        .dark .sat-tab.active {
            background: rgba(255, 255, 255, .06);
        }

        .dark .sat-tab.active {
            color: #8996a4;
        }

        /* ===== KARTU KIRI: kategori Data Center (gaya kartu Jadwal Shift) ===== */
        @media (min-width: 1280px) {
            .dc-panel {
                position: sticky;
                top: 94px;
                max-height: calc(100vh - 120px);
                display: flex;
                flex-direction: column;
                overflow: hidden;
            }

            .dc-body {
                flex: 1;
                min-height: 0;
                overflow-y: auto;
                margin-right: -12px;
                padding-right: 12px;
            }
        }

        @media (max-width: 1279px) {
            .dc-body {
                max-height: 320px;
                overflow-y: auto;
            }
        }

        .dc-body::-webkit-scrollbar {
            width: 6px;
        }

        .dc-panel .rfo-search {
            width: 100%;
            margin-bottom: 12px;
        }

        .dc-item {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            text-align: left;
            padding: 10px 12px;
            margin-bottom: 8px;
            border: 1px solid var(--rf-border);
            border-left: 4px solid var(--dc-color, #2f6bff);
            border-radius: 10px;
            background: var(--rf-card);
            cursor: pointer;
            transition: box-shadow .2s, background-color .2s, border-color .2s;
        }

        .dc-item.hidden {
            display: none;
        }

        .dc-item:focus-visible {
            outline: 2px solid var(--dc-color, #2f6bff);
            outline-offset: 2px;
        }

        /* tombol edit/hapus Data Center: muncul saat kartu disorot (selalu tampil di layar sentuh) */
        .dc-actions {
            display: flex;
            gap: 2px;
            flex: 0 0 auto;
            opacity: 0;
            transition: opacity .15s;
        }

        .dc-item:hover .dc-actions,
        .dc-item:focus-within .dc-actions,
        .dc-item.active .dc-actions,
        .dc-group-head:hover .dc-actions,
        .dc-group-head:focus-within .dc-actions {
            opacity: 1;
        }

        @media (hover: none) {
            .dc-actions {
                opacity: 1;
            }
        }

        .dc-act {
            width: 26px;
            height: 26px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            color: var(--rf-muted);
        }

        .dc-act:hover {
            background: var(--rf-soft);
            color: #2f6bff;
        }

        .dc-act.is-del:hover {
            color: #ef4444;
        }

        .dc-add {
            margin-left: auto;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            color: #fff;
            background: linear-gradient(135deg, #5b8cff, #2f6bff);
            box-shadow: 0 4px 12px rgba(47, 107, 255, .3);
        }

        .dc-add:hover {
            filter: brightness(1.08);
        }

        /* ===== GRUP DROPDOWN DATA CENTER (nama sama) — mengikuti gaya dropdown sidebar ===== */
        .dc-group {
            margin-bottom: 8px;
            border: 1px solid var(--rf-border);
            border-left: 4px solid var(--dc-color, #2f6bff);
            border-radius: 10px;
            background: var(--rf-card);
            transition: box-shadow .2s, border-color .2s;
        }

        .dc-group:hover {
            box-shadow: 0 6px 16px rgba(31, 42, 68, .08);
        }

        .dc-group-head {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 12px;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color .15s;
        }

        .dc-group-head:hover,
        .dc-group.open>.dc-group-head {
            background: var(--rf-soft);
        }

        .dc-act.is-add:hover {
            color: #10b981;
        }

        .dc-group-head:focus-visible {
            outline: 2px solid var(--dc-color, #2f6bff);
            outline-offset: 2px;
        }

        /* salah satu lokasi rak di grup sedang dipilih */
        .dc-group.has-active {
            border-color: var(--dc-color, #2f6bff);
            box-shadow: 0 0 0 1px var(--dc-color, #2f6bff) inset;
        }

        /* panah dalam kotak: tertutup ke bawah, terbuka ke atas (seperti sidebar) */
        .dc-arrow {
            flex: 0 0 auto;
            width: 26px;
            height: 26px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--rf-border);
            border-radius: 8px;
            background: var(--rf-card);
            color: var(--rf-muted);
            font-size: 15px;
            transition: transform .5s cubic-bezier(.22, 1, .36, 1), color .15s, border-color .15s;
        }

        .dc-group.open .dc-arrow {
            transform: rotate(180deg);
            color: var(--dc-color, #2f6bff);
            border-color: var(--dc-color, #2f6bff);
        }

        /* buka/tutup halus: tinggi 0fr -> 1fr */
        .dc-sub-wrap {
            display: grid;
            grid-template-rows: 0fr;
            transition: grid-template-rows .5s cubic-bezier(.22, 1, .36, 1);
        }

        .dc-group.open>.dc-sub-wrap {
            grid-template-rows: 1fr;
        }

        .dc-sub-list {
            min-height: 0;
            overflow: hidden;
            margin: 0 10px 0 22px;
            padding-left: 10px;
            border-left: 1px solid var(--rf-border);
            transition: margin .5s cubic-bezier(.22, 1, .36, 1);
        }

        .dc-group.open .dc-sub-list {
            margin-top: 6px;
            margin-bottom: 8px;
            border-left-color: var(--dc-color, #2f6bff);
        }

        #dcList.dc-no-anim *,
        #dcList.dc-no-anim *::before {
            transition: none !important;
            animation: none !important;
        }

        /* baris lokasi rak di dalam dropdown */
        .dc-item.dc-child {
            margin: 2px 0;
            padding: 7px 8px;
            border: 0;
            border-radius: 8px;
            background: transparent;
            box-shadow: none;
        }

        .dc-item.dc-child:hover {
            background: var(--rf-soft);
            box-shadow: none;
        }

        .dc-item.dc-child.active {
            background: color-mix(in srgb, var(--dc-color, #2f6bff) 14%, transparent);
            border: 0;
            box-shadow: none;
        }

        .dc-item.dc-child .dc-dot {
            width: 8px;
            height: 8px;
        }

        .dc-item.dc-child .dc-name {
            font-size: .84rem;
        }

        /* alamat cukup satu baris (lengkapnya terlihat di pop up Edit) */
        .dc-item.dc-child .dc-sub {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .dc-item.dc-child.active .dc-name {
            color: var(--dc-color, #2f6bff);
        }

        /* item dropdown muncul berurutan saat dibuka */
        @keyframes dcSubMasuk {
            from {
                opacity: 0;
                transform: translateX(-8px);
            }
        }

        .dc-group.open.dc-anim-in .dc-child {
            animation: dcSubMasuk .45s cubic-bezier(.22, 1, .36, 1) backwards;
        }

        .dc-group.open.dc-anim-in .dc-child:nth-child(2) {
            animation-delay: .05s;
        }

        .dc-group.open.dc-anim-in .dc-child:nth-child(3) {
            animation-delay: .1s;
        }

        .dc-group.open.dc-anim-in .dc-child:nth-child(n+4) {
            animation-delay: .15s;
        }

        @media (prefers-reduced-motion: reduce) {

            .dc-sub-wrap,
            .dc-arrow {
                transition: none;
            }

            .dc-group.open.dc-anim-in .dc-child {
                animation: none;
            }
        }

        .dc-item:hover {
            box-shadow: 0 6px 16px rgba(31, 42, 68, .08);
        }

        .dc-item.active {
            background: var(--rf-soft);
            border-color: var(--dc-color, #2f6bff);
            box-shadow: 0 0 0 1px var(--dc-color, #2f6bff) inset;
        }

        .dc-dot {
            flex: 0 0 auto;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--dc-color, #2f6bff);
        }

        .dc-name {
            display: block;
            font-size: .9rem;
            font-weight: 600;
            color: var(--rf-head);
            line-height: 1.25;
        }

        .dc-sub {
            display: block;
            margin-top: 2px;
            font-size: .76rem;
            color: var(--rf-muted);
        }

        .dc-count {
            flex: 0 0 auto;
            margin-left: auto;
            min-width: 30px;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 700;
            text-align: center;
            color: var(--rf-muted);
            background: var(--rf-soft);
        }

        .dc-item.active .dc-count,
        .dc-count.has {
            color: #fff;
            background: var(--dc-color, #2f6bff);
        }

        /* ===== KARTU KANAN: tabel bisa di-scroll di dalam card, header kolom tetap terlihat.
           Tingginya diatur batasiTinggiTabel() agar scroll muncul setelah 10 baris ===== */
        .rfo-page .table-scroll {
            overflow: auto;
        }

        .rfo-page .table-scroll thead th {
            /* !important: DataTables memberi position:relative pada kolom yang bisa diurutkan */
            position: sticky !important;
            top: 0;
            z-index: 2;
            /* border ikut hilang saat sticky pada border-collapse, jadi digambar dengan shadow */
            box-shadow: inset 0 -1px 0 var(--rf-border);
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
                    <a href="#" onclick="toggleSub(this);return false;" class="pc-link active flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
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
            <!-- JUDUL -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic is-sky"><i class="ti ti-arrows-exchange"></i></span>
                    <h1 class="rfo-title">Data Center &amp; Interkoneksi</h1>
                </div>
                <button type="button" onclick="openIk()" class="rfo-btn rfo-btn-blue border-0 cursor-pointer">
                    <i class="ti ti-plus"></i> Tambah Interkoneksi
                </button>
            </div>

            <?php
            // Jumlah data interkoneksi per data center (untuk angka di kartu kiri)
            $jumlahDc = array_count_values(array_map('strval', array_column($interkoneksi, 'data_center_id')));
            // warna penanda tiap data center (bergantian sesuai urutan)
            $warnaDc = ['#2f6bff', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899', '#84cc16', '#f97316', '#14b8a6'];
            ?>
            <div class="flex flex-col xl:flex-row gap-6 items-start">
                <!-- KIRI: kategori Data Center (klik = filter tabel) -->
                <aside class="w-full xl:w-[300px] xl:flex-none rfo-panel dc-panel">
                    <div class="dc-head">
                        <div class="flex items-center gap-2 text-[1.1rem] font-bold text-[#202124] dark:text-white mb-3">
                            <i class="ti ti-building-warehouse text-[#2f6bff]"></i> Data Center
                            <button type="button" class="dc-add" onclick="openDc()" title="Tambah Data Center" aria-label="Tambah Data Center"><i class="ti ti-plus"></i></button>
                        </div>
                        <div class="rfo-search">
                            <i class="ti ti-search"></i>
                            <input type="text" id="dcSearch" placeholder="Cari data center..." autocomplete="off" />
                        </div>
                    </div>

                    <?php
                    // Data Center dengan nama sama (beda lokasi rak) disatukan jadi satu grup dropdown
                    $grupDc = [];
                    foreach ($dataCenter as $dc) {
                        $grupDc[mb_strtolower(trim($dc['nama_dc']))][] = $dc;
                    }

                    // Satu kartu Data Center. $anak = baris di dalam dropdown (judulnya lokasi rak)
                    $kartuDc = function (array $dc, bool $anak) use ($jumlahDc) {
                        $n = $jumlahDc[(string) $dc['id']] ?? 0;
                        $rak = $dc['lokasi_rak'] ? 'Rak ' . $dc['lokasi_rak'] : '-';
                        ob_start(); ?>
                        <!-- klik kartu = filter tabel; ikon pensil / tong sampah = edit / hapus Data Center -->
                        <div role="button" tabindex="0" class="dc-item<?= $anak ? ' dc-child' : '' ?>" data-dc="<?= (int) $dc['id'] ?>"
                            data-cari="<?= esc(mb_strtolower($dc['nama_dc'] . ' ' . $dc['lokasi_rak'] . ' ' . $dc['alamat_dc']), 'attr') ?>"
                            data-row="<?= esc(json_encode(['id' => $dc['id'], 'nama_dc' => $dc['nama_dc'], 'lokasi_rak' => $dc['lokasi_rak'], 'alamat_dc' => $dc['alamat_dc']]), 'attr') ?>"
                            data-jumlah="<?= $n ?>">
                            <span class="dc-dot"></span>
                            <span class="min-w-0 flex-1">
                                <span class="dc-name"><?= esc($anak ? $rak : $dc['nama_dc']) ?></span>
                                <span class="dc-sub"><?= esc($anak ? ($dc['alamat_dc'] ?: '-') : ($dc['lokasi_rak'] ? $rak : ($dc['alamat_dc'] ?: '-'))) ?></span>
                            </span>
                            <span class="dc-actions">
                                <?php if (! $anak) : ?>
                                    <button type="button" class="dc-act is-add" data-aksi="tambah" title="Tambah lokasi rak ke Data Center ini" aria-label="Tambah lokasi rak ke Data Center ini"><i class="ti ti-plus"></i></button>
                                <?php endif; ?>
                                <button type="button" class="dc-act" data-aksi="edit" title="Edit Data Center" aria-label="Edit Data Center"><i class="ti ti-edit"></i></button>
                                <button type="button" class="dc-act is-del" data-aksi="hapus" title="Hapus Data Center" aria-label="Hapus Data Center"><i class="ti ti-trash"></i></button>
                            </span>
                            <span class="dc-count <?= $n ? 'has' : '' ?>"><?= $n ?></span>
                        </div>
                    <?php return ob_get_clean();
                    };
                    ?>
                    <div class="dc-body" id="dcList">
                        <?php $g = 0; ?>
                        <?php foreach ($grupDc as $kunci => $daftar) : ?>
                            <?php $warna = $warnaDc[$g++ % count($warnaDc)]; ?>
                            <?php if (count($daftar) === 1) : ?>
                                <div class="dc-single" style="--dc-color:<?= $warna ?>"><?= $kartuDc($daftar[0], false) ?></div>
                            <?php else : ?>
                                <?php $total = 0;
                                foreach ($daftar as $dc) $total += $jumlahDc[(string) $dc['id']] ?? 0; ?>
                                <!-- grup dropdown (gaya dropdown sidebar): klik judul = buka / tutup daftar lokasi rak -->
                                <div class="dc-group" data-grup="<?= esc($kunci, 'attr') ?>" style="--dc-color:<?= $warna ?>">
                                    <div role="button" tabindex="0" class="dc-group-head" aria-expanded="false"
                                        data-row="<?= esc(json_encode(['nama_dc' => $daftar[0]['nama_dc'], 'alamat_dc' => $daftar[0]['alamat_dc']]), 'attr') ?>">
                                        <span class="dc-dot"></span>
                                        <span class="min-w-0 flex-1 text-left">
                                            <span class="dc-name"><?= esc($daftar[0]['nama_dc']) ?></span>
                                            <span class="dc-sub"><?= count($daftar) ?> lokasi rak</span>
                                        </span>
                                        <!-- + = tambah lokasi rak baru ke kelompok Data Center ini -->
                                        <span class="dc-actions">
                                            <button type="button" class="dc-act is-add" data-aksi="tambah" title="Tambah lokasi rak ke Data Center ini" aria-label="Tambah lokasi rak ke Data Center ini"><i class="ti ti-plus"></i></button>
                                        </span>
                                        <span class="dc-count <?= $total ? 'has' : '' ?>"><?= $total ?></span>
                                        <span class="dc-arrow"><i class="ti ti-chevron-down"></i></span>
                                    </div>
                                    <div class="dc-sub-wrap">
                                        <div class="dc-sub-list">
                                            <?php foreach ($daftar as $dc) : ?>
                                                <?= $kartuDc($dc, true) ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        <div id="dcEmpty" class="<?= $dataCenter ? 'hidden' : '' ?> text-gray-400 text-sm py-2"><?= $dataCenter ? 'Data center tidak ditemukan.' : 'Belum ada data center. Klik + untuk menambah.' ?></div>
                    </div>
                </aside>

                <!-- KANAN: tabel -->
            <div class="card w-full xl:flex-1 min-w-0">
                <div class="card-body">

                    <!-- TOOLBAR -->
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div class="flex items-center gap-3 flex-wrap">
                            <div id="lengthArea"></div>
                            <!-- FILTER -->
                            <?php
                            // Pilihan filter diambil dari isian yang sudah ada (data diisi manual)
                            $opsiFilter = function (string $kolom) use ($interkoneksi) {
                                $v = array_unique(array_filter(array_map('trim', array_column($interkoneksi, $kolom)), 'strlen'));
                                natcasesort($v);
                                return $v;
                            };
                            ?>
                            <select id="filterCustomer" class="filter-select" title="Filter Customer">
                                <option value="">Semua Customer</option>
                                <?php foreach ($opsiFilter('customer') as $v) : ?>
                                    <option value="<?= esc($v, 'attr') ?>"><?= esc($v) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select id="filterStatus" class="filter-select" title="Filter Status">
                                <option value="">Semua Status</option>
                                <?php foreach ($opsiFilter('status') as $v) : ?>
                                    <option value="<?= esc($v, 'attr') ?>"><?= esc($v) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <div class="rfo-search">
                                <i class="ti ti-search"></i>
                                <input type="text" id="customSearch" placeholder="Cari data..." />
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
                                    <th>Aksi</th>
                                    <th>Kode Interkoneksi</th>
                                    <th>Customer</th>
                                    <th>Nama Data Center</th>
                                    <th>Lokasi Rak</th>
                                    <th>Sumber</th>
                                    <th>Tujuan</th>
                                    <th>Label Kabel</th>
                                    <th>Status</th>
                                    <th>Note</th>
                                    <th>Dokumen</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($interkoneksi)) : ?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($interkoneksi as $row) : ?>
                                        <tr data-dc="<?= (int) $row['data_center_id'] ?>">
                                            <td><?= $no++; ?></td>
                                            <td>
                                                <button type="button"
                                                    data-row="<?= esc(json_encode($row), 'attr') ?>"
                                                    onclick="openIk(JSON.parse(this.dataset.row))"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="ti ti-edit"></i> <span>Edit</span>
                                                </button>

                                                <button type="button"
                                                    onclick="confirmDelete(<?= $row['id'] ?>)"
                                                    class="btn btn-sm btn-danger">
                                                    <i class="ti ti-trash"></i> <span>Hapus</span>
                                                </button>

                                                <button type="button"
                                                    data-row="<?= esc(json_encode($row), 'attr') ?>"
                                                    onclick="openViewModal(JSON.parse(this.dataset.row))"
                                                    class="btn btn-sm btn-info" title="Lihat">
                                                    <i class="ti ti-eye"></i> <span>View</span>
                                                </button>
                                            </td>
                                            <td class="col-bold"><?= esc($row['kode_interkoneksi']); ?></td>
                                            <td class="whitespace-normal min-w-[180px]"><?= esc($row['customer']); ?></td>
                                            <td class="whitespace-normal min-w-[180px]"><?= esc($row['nama_dc'] ?: '-'); ?></td>
                                            <td class="col-bold"><?= esc($row['lokasi_rak'] ?: '-'); ?></td>
                                            <td class="whitespace-normal min-w-[160px]"><?= esc($row['sumber']); ?></td>
                                            <td class="whitespace-normal min-w-[160px]"><?= esc($row['tujuan']); ?></td>
                                            <td><?= esc($row['label_kabel'] ?: '-'); ?></td>
                                            <td><?= esc($row['status'] ?: '-'); ?></td>
                                            <td class="whitespace-pre-line min-w-[250px]"><?= esc($row['note'] ?: '-'); ?></td>
                                            <td>
                                                <?php if ($row['file_dokumen']) : ?>
                                                    <a href="<?= site_url('Interkoneksi/file/' . $row['id']) ?>" target="_blank" rel="noopener"
                                                        class="text-blue-600 hover:underline inline-flex items-center gap-1" title="<?= esc($row['file_dokumen_asli'], 'attr') ?>">
                                                        <i class="ti ti-paperclip"></i> Lihat
                                                    </a>
                                                <?php else : ?>
                                                    -
                                                <?php endif; ?>
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
        </div>
    </div>

    <!-- ====== POP UP VIEW ====== -->
    <div id="viewModal" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4" onclick="if (event.target === this) closeViewModal()">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold">View Data Interkoneksi</h3>
                <button type="button" onclick="closeViewModal()"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <div class="text-gray-500">Kode Interkoneksi</div>
                    <div class="font-medium" id="v_kode_interkoneksi"></div>
                </div>
                <div>
                    <div class="text-gray-500">Customer</div>
                    <div class="font-medium" id="v_customer"></div>
                </div>
                <div>
                    <div class="text-gray-500">Nama Data Center</div>
                    <div class="font-medium" id="v_nama_dc"></div>
                </div>
                <div>
                    <div class="text-gray-500">Lokasi Rak</div>
                    <div class="font-medium" id="v_lokasi_rak"></div>
                </div>
                <div>
                    <div class="text-gray-500">Sumber</div>
                    <div class="font-medium" id="v_sumber"></div>
                </div>
                <div>
                    <div class="text-gray-500">Tujuan</div>
                    <div class="font-medium" id="v_tujuan"></div>
                </div>
                <div>
                    <div class="text-gray-500">Label Kabel</div>
                    <div class="font-medium" id="v_label_kabel"></div>
                </div>
                <div>
                    <div class="text-gray-500">Status</div>
                    <div class="font-medium" id="v_status"></div>
                </div>
                <div class="md:col-span-2">
                    <div class="text-gray-500">Note</div>
                    <div class="font-medium whitespace-pre-line" id="v_note"></div>
                </div>
                <div class="md:col-span-2">
                    <div class="text-gray-500">Dokumen</div>
                    <div class="font-medium" id="v_dokumen"></div>
                </div>
                <div>
                    <div class="text-gray-500">Dibuat</div>
                    <div class="font-medium" id="v_created"></div>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeViewModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Tutup</button>
            </div>
        </div>
    </div>

    <!-- ====== POP UP FORM DATA CENTER (tambah / edit) ====== -->
    <div id="modal_dc" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4" onclick="if (event.target === this) closeForm('dc')">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto rfo-form">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold" data-judul="Data Center"></h3>
                <button type="button" onclick="closeForm('dc')" aria-label="Tutup"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <form method="POST" id="formDc" data-save="<?= site_url('DataCenter/save') ?>" data-update="<?= site_url('DataCenter/update') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id">
                <?php
                // pilihan kelompok = Data Center yang namanya sudah ada (kunci sama dengan pengelompokan kartu kiri)
                $kelompokDc = [];
                foreach ($dataCenter as $dc) {
                    $kunciDc = mb_strtolower(trim($dc['nama_dc']));
                    if (! isset($kelompokDc[$kunciDc])) $kelompokDc[$kunciDc] = $dc;
                }
                ?>
                <div class="rf-group">
                    <label class="rf-label"><i class="ti ti-folders"></i> Kelompok Data Center</label>
                    <!-- tanpa name: hanya mengisi Nama & Alamat di bawah, tidak ikut dikirim -->
                    <select class="rf-input" id="dcKelompok">
                        <option value="">— Data Center baru —</option>
                        <?php foreach ($kelompokDc as $kunciDc => $dc) : ?>
                            <option value="<?= esc($kunciDc, 'attr') ?>" data-nama="<?= esc($dc['nama_dc'], 'attr') ?>" data-alamat="<?= esc($dc['alamat_dc'], 'attr') ?>"><?= esc($dc['nama_dc']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="rf-hint">Pilih Data Center yang sudah ada agar lokasi rak ini masuk ke kelompoknya. Nama &amp; alamat terisi otomatis.</div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
                    <div class="rf-group"><label class="rf-label">Nama Data Center <span class="req">*</span></label><input class="rf-input" name="nama_dc" maxlength="255" required placeholder="Masukkan Nama Data Center"></div>
                    <div class="rf-group"><label class="rf-label">Lokasi Rak <span class="req">*</span></label><input class="rf-input" name="lokasi_rak" maxlength="255" required placeholder="Masukkan Lokasi Rak"></div>
                    <div class="rf-group md:col-span-2"><label class="rf-label">Lokasi Data Center (Alamat) <span class="req">*</span></label><textarea class="rf-input" name="alamat_dc" rows="3" required placeholder="Masukkan Alamat Data Center"></textarea></div>
                </div>
                <div class="d-flex">
                    <button type="submit"><i class="ti ti-device-floppy"></i> Simpan</button>
                    <button type="button" class="btn-back" onclick="closeForm('dc')">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ====== POP UP FORM INTERKONEKSI (tambah / edit) ====== -->
    <div id="modal_ik" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4" onclick="if (event.target === this) closeForm('ik')">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-3xl p-6 max-h-[90vh] overflow-y-auto rfo-form">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold" data-judul="Data Interkoneksi"></h3>
                <button type="button" onclick="closeForm('ik')" aria-label="Tutup"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <form method="POST" id="formIk" enctype="multipart/form-data" data-save="<?= site_url('Interkoneksi/save') ?>" data-update="<?= site_url('Interkoneksi/update') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
                    <div class="rf-group"><label class="rf-label">Kode Interkoneksi <span class="req">*</span></label><input class="rf-input" name="kode_interkoneksi" maxlength="100" required placeholder="Masukkan Kode Interkoneksi"></div>
                    <div class="rf-group"><label class="rf-label">Customer <span class="req">*</span></label><input class="rf-input" name="customer" maxlength="255" required placeholder="Masukkan Customer"></div>
                    <div class="rf-group md:col-span-2">
                        <label class="rf-label">Data Center <span class="req">*</span></label>
                        <select class="rf-input" name="data_center_id" required>
                            <option value="">-- Pilih Data Center --</option>
                            <?php foreach ($dataCenter as $dc) : ?>
                                <option value="<?= esc($dc['id'], 'attr') ?>"><?= esc($dc['nama_dc'] . ($dc['lokasi_rak'] ? ' - ' . $dc['lokasi_rak'] : '')) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="rf-group">
                        <label class="rf-label">Sumber <span class="req">*</span></label>
                        <select class="rf-input" name="sumber" required>
                            <option value="">-- Pilih Sumber --</option>
                            <?php foreach ($opsiSumber as $v) : ?>
                                <option value="<?= esc($v, 'attr') ?>"><?= esc($v) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="rf-group"><label class="rf-label">Tujuan <span class="req">*</span></label><input class="rf-input" name="tujuan" maxlength="255" required placeholder="Masukkan Tujuan"></div>
                    <div class="rf-group"><label class="rf-label">Label Kabel</label><input class="rf-input" name="label_kabel" maxlength="255" placeholder="Masukkan Label Kabel"></div>
                    <div class="rf-group">
                        <label class="rf-label">Status</label>
                        <select class="rf-input" name="status">
                            <option value="">-- Pilih Status --</option>
                            <?php foreach ($opsiStatus as $v) : ?>
                                <option value="<?= esc($v, 'attr') ?>"><?= esc($v) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="rf-group md:col-span-2"><label class="rf-label">Note</label><textarea class="rf-input" name="note" rows="3" placeholder="Masukkan Note"></textarea></div>
                    <div class="rf-group md:col-span-2">
                        <label class="rf-label">Dokumen <span class="text-gray-400 font-normal">(PDF, PNG, JPG - maks. 10 MB)</span></label>
                        <input class="rf-input" type="file" name="file_dokumen" accept=".pdf,.png,.jpg,.jpeg">
                        <!-- saat edit: file yang sudah ada -->
                        <div id="ikFileLama" class="hidden mt-2 text-sm flex flex-wrap items-center gap-3">
                            <span class="text-gray-500">File saat ini:</span>
                            <a href="#" target="_blank" rel="noopener" class="text-blue-600 hover:underline inline-flex items-center gap-1"><i class="ti ti-paperclip"></i> <span></span></a>
                            <label class="inline-flex items-center gap-2 mb-0 text-sm font-normal text-red-600">
                                <input type="checkbox" name="hapus_dokumen" value="1"> Hapus file
                            </label>
                            <p class="w-full text-xs text-gray-500">Pilih file baru untuk mengganti file saat ini.</p>
                        </div>
                    </div>
                </div>
                <div class="d-flex">
                    <button type="submit"><i class="ti ti-device-floppy"></i> Simpan</button>
                    <button type="button" class="btn-back" onclick="closeForm('ik')">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <?php
    // Gagal simpan (validasi di server): pop up dibuka lagi dengan isian sebelumnya
    $popupGagal = session()->getFlashdata('popup');
    $isianLama  = [];
    if ($popupGagal === 'dc') {
        foreach (['id', 'nama_dc', 'lokasi_rak', 'alamat_dc'] as $k) {
            $isianLama[$k] = old($k, '', false);
        }
    } elseif ($popupGagal === 'ik') {
        foreach (['id', 'kode_interkoneksi', 'customer', 'data_center_id', 'sumber', 'tujuan', 'label_kabel', 'status', 'note'] as $k) {
            $isianLama[$k] = old($k, '', false);
        }
        // saat edit: tampilkan lagi file dokumen yang sudah tersimpan
        foreach ($interkoneksi as $r) {
            if ($isianLama['id'] !== '' && (string) $r['id'] === (string) $isianLama['id']) {
                $isianLama['file_dokumen']      = $r['file_dokumen'];
                $isianLama['file_dokumen_asli'] = $r['file_dokumen_asli'];
            }
        }
    }
    ?>

    <script>
        // ====== POP UP FORM TAMBAH / EDIT (Data Center & Interkoneksi) ======
        const URL_FILE = '<?= site_url('Interkoneksi/file/') ?>';

        function openForm(jenis, data) {
            data = data || {};
            const modal = document.getElementById('modal_' + jenis);
            const form = modal.querySelector('form');
            const edit = !!data.id;

            form.reset();
            form.elements.id.value = edit ? data.id : '';
            form.setAttribute('action', edit ? form.dataset.update : form.dataset.save);
            const judul = modal.querySelector('[data-judul]');
            judul.textContent = (edit ? 'Edit ' : 'Tambah ') + judul.dataset.judul;

            Object.keys(data).forEach(k => {
                const el = form.elements[k];
                if (el && k !== 'id' && el.type !== 'file' && el.type !== 'checkbox') el.value = data[k] === null ? '' : data[k];
            });

            if (jenis === 'ik') {
                const lama = document.getElementById('ikFileLama');
                const adaFile = edit && !!data.file_dokumen;
                lama.classList.toggle('hidden', !adaFile);
                if (adaFile) {
                    const a = lama.querySelector('a');
                    a.href = URL_FILE + data.id;
                    a.querySelector('span').textContent = data.file_dokumen_asli || data.file_dokumen;
                }
            }

            modal.classList.remove('hidden');
            const pertama = form.querySelector('.rf-input');
            if (pertama) setTimeout(() => pertama.focus(), 50);
        }

        function closeForm(jenis) {
            document.getElementById('modal_' + jenis).classList.add('hidden');
        }

        function openDc(data) {
            data = data || {};
            openForm('dc', data);
            const form = document.getElementById('formDc');
            const kunci = String(data.nama_dc || '').trim().toLowerCase();
            const sel = document.getElementById('dcKelompok');
            sel.value = [...sel.options].some(o => o.value === kunci) ? kunci : '';
            aturKelompokDc(false);
            // dari tombol + di judul Data Center: nama & alamat sudah terisi, langsung ke Lokasi Rak
            if (!data.id && kunci) setTimeout(() => form.elements.lokasi_rak.focus(), 60);
        }

        // Kelompok dipilih -> Nama Data Center dikunci sama persis dengan kelompoknya (agar masuk grup yang sama)
        function aturKelompokDc(isiAlamat) {
            const form = document.getElementById('formDc');
            const opt = document.getElementById('dcKelompok').selectedOptions[0];
            const nama = form.elements.nama_dc;
            const adaKelompok = !!(opt && opt.value);
            const alamat = form.elements.alamat_dc;
            nama.readOnly = adaKelompok;
            if (adaKelompok) {
                nama.value = opt.dataset.nama;
                if (isiAlamat && opt.dataset.alamat) alamat.value = opt.dataset.alamat;
            } else if (isiAlamat) {
                nama.value = '';
                // alamat yang terisi dari kelompok sebelumnya ikut dikosongkan (alamat yang sudah diubah sendiri dibiarkan)
                if (alamat.value === alamatKelompokTerakhir) alamat.value = '';
                nama.focus();
            }
            alamatKelompokTerakhir = adaKelompok ? (opt.dataset.alamat || '') : '';
        }
        let alamatKelompokTerakhir = '';

        document.getElementById('dcKelompok').addEventListener('change', () => aturKelompokDc(true));

        // tombol + di judul Data Center (kartu tunggal / judul grup): tambah lokasi rak ke kelompok itu
        function tambahKeKelompok(row) {
            openDc({ nama_dc: row.nama_dc, alamat_dc: row.alamat_dc });
        }

        // Tambah interkoneksi: Data Center terisi otomatis dari kartu kiri yang sedang dipilih
        function openIk(data) {
            if (!document.querySelector('#dcList .dc-item')) {
                return Swal.fire({ icon: 'warning', title: 'Belum Bisa', text: 'Tambahkan Data Center lebih dulu (tombol + di kartu Data Center).', confirmButtonColor: '#04a9f5' });
            }
            if (!data) {
                const aktif = document.querySelector('#dcList .dc-item.active');
                data = aktif ? { data_center_id: aktif.dataset.dc } : {};
            }
            openForm('ik', data);
        }

        function hapusDc(item) {
            const d = JSON.parse(item.dataset.row);
            const jumlah = Number(item.dataset.jumlah);
            if (jumlah > 0) {
                return Swal.fire({ icon: 'warning', title: 'Belum Bisa Dihapus', text: 'Data Center "' + d.nama_dc + '" masih dipakai ' + jumlah + ' data interkoneksi. Pindahkan atau hapus data interkoneksinya dulu.', confirmButtonColor: '#04a9f5' });
            }
            Swal.fire({
                title: 'Hapus Data Center?',
                text: '"' + d.nama_dc + '" akan dihapus dan tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then(r => {
                if (r.isConfirmed) window.location.href = '<?= site_url('DataCenter/delete/') ?>' + d.id;
            });
        }

        // Esc = tutup pop up form yang sedang terbuka
        document.addEventListener('keydown', e => {
            if (e.key !== 'Escape') return;
            ['dc', 'ik'].forEach(j => {
                if (!document.getElementById('modal_' + j).classList.contains('hidden')) closeForm(j);
            });
        });

        // ====== VALIDASI SEBELUM SUBMIT ======
        function formBelumLengkap(form) {
            const kosong = [...form.querySelectorAll('[required]')].some(el => !el.value.trim());
            if (kosong) {
                Swal.fire({ icon: 'warning', title: 'Form Belum Lengkap', text: 'Semua field bertanda * wajib diisi.', confirmButtonColor: '#185a82' });
            }
            return kosong;
        }

        document.getElementById('formDc').addEventListener('submit', function(e) {
            if (formBelumLengkap(this)) e.preventDefault();
        });

        document.getElementById('formIk').addEventListener('submit', function(e) {
            if (formBelumLengkap(this)) return e.preventDefault();
            // File dokumen (opsional): cek ekstensi & ukuran sebelum dikirim
            const file = this.elements.file_dokumen.files[0];
            if (!file) return;
            const ext = file.name.split('.').pop().toLowerCase();
            let pesan = '';
            if (!['pdf', 'png', 'jpg', 'jpeg'].includes(ext)) pesan = 'File Dokumen harus PDF, PNG, atau JPG.';
            else if (file.size > 10 * 1024 * 1024) pesan = 'File Dokumen maksimal 10 MB.';
            if (pesan) {
                e.preventDefault();
                Swal.fire({ icon: 'warning', title: 'File Tidak Valid', text: pesan, confirmButtonColor: '#185a82' });
            }
        });

        <?php if ($popupGagal === 'dc' || $popupGagal === 'ik') : ?>
            document.addEventListener('DOMContentLoaded', () => openForm(<?= json_encode($popupGagal) ?>, <?= json_encode($isianLama, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>));
        <?php endif; ?>
    </script>

    <script>
        // Yang ditampilkan di pop up view (nama_dc & lokasi_rak hasil join ke md_data_center)
        const VIEW_FIELDS = ['kode_interkoneksi', 'customer', 'nama_dc', 'lokasi_rak', 'sumber', 'tujuan', 'label_kabel', 'status', 'note'];

        // ====== VIEW ======
        const BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        // '2026-10-01' -> '1 Oktober 2026'
        const tglIndo = t => {
            if (!t) return '-';
            const [y, m, d] = t.split('-');
            return Number(d) + ' ' + BULAN[Number(m) - 1] + ' ' + y;
        };

        function openViewModal(d) {
            const set = (id, v) => document.getElementById(id).textContent = (v === null || v === undefined || String(v).trim() === '') ? '-' : v;
            VIEW_FIELDS.forEach(k => set('v_' + k, d[k]));
            set('v_created', d.created_at ? tglIndo(d.created_at.substring(0, 10)) + ' ' + d.created_at.substring(11, 16) : '');

            // Link file dokumen (dibuka lewat Interkoneksi/file/{id})
            const dok = document.getElementById('v_dokumen');
            dok.textContent = '-';
            if (d.file_dokumen) {
                const a = document.createElement('a');
                a.href = '<?= site_url('Interkoneksi/file/') ?>' + d.id;
                a.target = '_blank';
                a.rel = 'noopener';
                a.className = 'text-blue-600 hover:underline';
                a.textContent = d.file_dokumen_asli || d.file_dokumen;
                dok.replaceChildren(a);
            }


            document.getElementById('viewModal').classList.remove('hidden');
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }
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
                    window.location.href = "<?= site_url('Interkoneksi/delete/') ?>" + id;
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
                            title: 'Data Interkoneksi',
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, button, config);
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            text: '<i class="ti ti-file-text"></i> Export CSV',
                            title: 'Data Interkoneksi',
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            text: '<i class="ti ti-file-spreadsheet"></i> Export Excel',
                            title: 'Data Interkoneksi',
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            text: '<i class="ti ti-file-type-pdf"></i> Export PDF',
                            title: 'Data Interkoneksi',
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
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 - 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ data)",
                    emptyTable: "Data Interkoneksi belum tersedia",
                    zeroRecords: "Tidak ada data yang cocok dengan pencarian",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Selanjutnya"
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

            // ====== SCROLL TABEL: lebih dari 10 baris tampil = scroll di dalam card ======
            // Dihitung dari baris yang sedang tampil, karena tingginya bisa berbeda (Note panjang)
            const MAKS_BARIS = 10;

            function batasiTinggiTabel() {
                const area = document.querySelector('.rfo-page .table-scroll');
                const baris = area.querySelectorAll('#reportTable tbody tr');
                area.style.maxHeight = '';
                if (baris.length > MAKS_BARIS) {
                    const atas = area.querySelector('#reportTable thead').getBoundingClientRect().top;
                    const bawah = baris[MAKS_BARIS - 1].getBoundingClientRect().bottom;
                    // ditambah tebal border dan scrollbar horizontal area tabel
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

            // ====== FILTER DROPDOWN (cocok persis: kolom 3 Customer, kolom 9 Status) ======
            const filterKolom = (sel, col) => $(sel).on('change', function() {
                const re = this.value ? '^' + $.fn.dataTable.util.escapeRegex(this.value) + '$' : '';
                table.column(col).search(re, true, false).draw();
            });
            filterKolom('#filterCustomer', 3);
            filterKolom('#filterStatus', 9);

            // ====== KARTU KIRI: filter per Data Center (pakai id dari data-dc di <tr>) ======
            let dcAktif = '';
            $.fn.dataTable.ext.search.push(function(settings, data, idx) {
                if (settings.nTable.id !== 'reportTable' || !dcAktif) return true;
                return table.row(idx).node().dataset.dc === dcAktif;
            });

            function pilihDc(id) {
                dcAktif = id;
                $('#dcList .dc-item').each(function() {
                    $(this).toggleClass('active', this.dataset.dc === id);
                });
                // judul grup ikut ditandai bila salah satu lokasi raknya sedang dipilih
                $('#dcList .dc-group').each(function() {
                    $(this).toggleClass('has-active', !!this.querySelector('.dc-item.active'));
                });
                table.draw();
            }

            // ====== GRUP DROPDOWN DATA CENTER (nama sama) — buka/tutup seperti dropdown sidebar ======
            // grup yang terbuka diingat selama tab browser masih dibuka
            const KUNCI_GRUP = 'interkoneksi.dcOpen';
            let grupTerbuka = [];
            try {
                grupTerbuka = JSON.parse(sessionStorage.getItem(KUNCI_GRUP) || '[]');
            } catch (err) {}

            function setGrup(el, buka, simpan) {
                el.classList.toggle('open', buka);
                el.querySelector('.dc-group-head').setAttribute('aria-expanded', buka ? 'true' : 'false');
                if (!simpan) return;
                grupTerbuka = grupTerbuka.filter(k => k !== el.dataset.grup);
                if (buka) grupTerbuka.push(el.dataset.grup);
                try {
                    sessionStorage.setItem(KUNCI_GRUP, JSON.stringify(grupTerbuka));
                } catch (err) {}
            }

            // saat halaman dibuka: kembalikan tanpa animasi
            $('#dcList').addClass('dc-no-anim');
            $('#dcList .dc-group').each(function() {
                setGrup(this, grupTerbuka.includes(this.dataset.grup), false);
            });
            requestAnimationFrame(() => requestAnimationFrame(() => $('#dcList').removeClass('dc-no-anim')));

            $('#dcList').on('click', '.dc-group-head', function(e) {
                if (e.target.closest('[data-aksi="tambah"]')) return tambahKeKelompok(JSON.parse(this.dataset.row));
                const grup = this.closest('.dc-group');
                // sedang mencari: grup dibuka otomatis, klik judul tidak menutupnya
                if ($('#dcSearch').val().trim()) return;
                const buka = !grup.classList.contains('open');
                grup.classList.toggle('dc-anim-in', buka);
                setGrup(grup, buka, true);
            });

            $('#dcList').on('click', '.dc-item', function(e) {
                const aksi = e.target.closest('[data-aksi]');
                if (aksi) {
                    e.stopPropagation();
                    if (aksi.dataset.aksi === 'tambah') return tambahKeKelompok(JSON.parse(this.dataset.row));
                    return aksi.dataset.aksi === 'edit' ? openDc(JSON.parse(this.dataset.row)) : hapusDc(this);
                }
                // klik lagi data center yang sedang aktif = filter dilepas (tampil semua data)
                pilihDc(this.dataset.dc === dcAktif ? '' : this.dataset.dc);
            });
            // judul grup bisa dibuka / ditutup dengan keyboard (Enter / Spasi)
            $('#dcList').on('keydown', '.dc-group-head', function(e) {
                if (e.target !== this || (e.key !== 'Enter' && e.key !== ' ')) return;
                e.preventDefault();
                this.click();
            });
            // kartu bisa dipilih dengan keyboard (Enter / Spasi)
            $('#dcList').on('keydown', '.dc-item', function(e) {
                if (e.target !== this || (e.key !== 'Enter' && e.key !== ' ')) return;
                e.preventDefault();
                this.click();
            });

            // cari data center di kartu kiri
            $('#dcSearch').on('input keyup', function() {
                const q = this.value.trim().toLowerCase();
                let ada = 0;
                $('#dcList .dc-item[data-cari]').each(function() {
                    const cocok = !q || this.dataset.cari.includes(q);
                    $(this).toggleClass('hidden', !cocok);
                    // kartu tunggal: sembunyikan juga pembungkusnya
                    $(this).parent('.dc-single').toggleClass('hidden', !cocok);
                    if (cocok) ada++;
                });
                // grup: tampil bila ada lokasi rak yang cocok, dan dibuka selama mencari
                $('#dcList .dc-group').each(function() {
                    const cocok = this.querySelectorAll('.dc-item:not(.hidden)').length > 0;
                    $(this).toggleClass('hidden', !cocok);
                    setGrup(this, q ? cocok : grupTerbuka.includes(this.dataset.grup), false);
                });
                $('#dcEmpty').toggleClass('hidden', ada > 0);
            });

            // tombol Reset: lepas filter data center
            $('.rfo-reset').on('click', function() {
                pilihDc('');
            });

            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
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