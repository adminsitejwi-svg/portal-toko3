    <!doctype html>
    <html lang="en" class="light">

    <head>
        <meta charset="utf-8" />
        <title>Beranda</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
        <!-- Feather icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

        <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
        <link rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

        <!-- Font Awesome (for brand & solid icons) -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
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

            .brand-text {
                font-size: 18px;
            }

            .vendor-card table td,
            .vendor-card table th {
                white-space: nowrap;
            }

            .vendor-card .custom-scroll {
                scrollbar-width: thin;
            }

            /* ===== BERANDA (referensi: public/d/tampilan beranda.jpg) ===== */
            .db-page {
                position: relative;
                min-height: calc(100vh - 74px);
                overflow: hidden;
                font-family: 'Inter', sans-serif;
            }

            .db-page > *:not(.db-latar) {
                position: relative;
                z-index: 1;
            }

            .db-judul-top b {
                display: block;
                font-size: 19px;
                font-weight: 800;
                color: var(--ra-heading);
                line-height: 1.15;
            }

            .db-judul-top small {
                font-size: 12.5px;
                color: var(--ra-muted);
            }

            /* kartu utama */
            .db-hero-grid {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 18px;
                margin-bottom: 18px;
            }

            .db-hero {
                position: relative;
                display: flex;
                align-items: center;
                gap: 26px;
                min-height: 120px;
                padding: 20px 26px;
                border-radius: 16px;
                color: #fff !important;
                overflow: hidden;
                transition: transform .2s, box-shadow .2s;
            }

            .db-hero:hover {
                transform: translateY(-2px);
            }

            .db-hero.is-blue {
                background: linear-gradient(110deg, #1d5ff2 0%, #2f7bff 55%, #5aa2ff 100%);
                box-shadow: 0 14px 30px rgba(29, 95, 242, .28);
            }

            .db-hero.is-green {
                background: linear-gradient(110deg, #0e9f6e 0%, #19b981 55%, #48d59c 100%);
                box-shadow: 0 14px 30px rgba(14, 159, 110, .26);
            }

            .db-hero-ic {
                flex: none;
                display: grid;
                place-items: center;
                width: 74px;
                height: 74px;
                border-radius: 50%;
                font-size: 36px;
                background: radial-gradient(circle at 35% 30%, rgba(255, 255, 255, .55), rgba(255, 255, 255, .12) 70%);
                box-shadow: inset 0 0 0 2px rgba(255, 255, 255, .35), 0 8px 18px rgba(0, 0, 0, .15);
            }

            .db-hero-isi {
                position: relative;
                z-index: 1;
            }

            .db-hero-label {
                font-size: 15px;
                font-weight: 600;
                opacity: .95;
            }

            .db-hero-angka {
                font-size: 34px;
                font-weight: 800;
                line-height: 1.15;
            }

            .db-hero-note {
                display: flex;
                align-items: center;
                gap: 4px;
                font-size: 13px;
                opacity: .92;
            }

            .db-hero-hias {
                position: absolute;
                right: 70px;
                bottom: 0;
                width: 55%;
                height: 100%;
                pointer-events: none;
            }

            .db-hero-go {
                position: absolute;
                right: 22px;
                top: 50%;
                transform: translateY(-50%);
                display: grid;
                place-items: center;
                width: 34px;
                height: 34px;
                border-radius: 50%;
                font-size: 18px;
                background: #fff;
                box-shadow: 0 4px 12px rgba(0, 0, 0, .15);
            }

            .db-hero.is-blue .db-hero-go { color: #1d5ff2; }
            .db-hero.is-green .db-hero-go { color: #0e9f6e; }

            /* kartu umum */
            .db-card {
                display: block;
                border: 1px solid var(--ra-border);
                border-radius: 14px;
                background: var(--ra-card);
                box-shadow: 0 6px 22px rgba(31, 42, 68, .07);
            }

            /* kartu toko */
            .db-toko-grid {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 18px;
                margin-bottom: 18px;
            }

            .db-toko {
                overflow: hidden;
                transition: transform .2s, box-shadow .2s;
            }

            .db-toko:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 26px rgba(31, 42, 68, .12);
            }

            .db-toko-atas {
                position: relative;
                display: flex;
                align-items: flex-start;
                gap: 16px;
                padding: 16px 18px 18px;
                min-height: 92px;
            }

            .db-toko-ic {
                flex: none;
                display: grid;
                place-items: center;
                width: 40px;
                height: 40px;
                border-radius: 11px;
                font-size: 24px;
                color: #fff;
            }

            .db-toko-ic.is-ungu { background: linear-gradient(135deg, #a855f7, #7c3aed); }
            .db-toko-ic.is-oranye { background: linear-gradient(135deg, #fb923c, #ea580c); }
            .db-toko-ic.is-teal { background: linear-gradient(135deg, #2dd4bf, #0d9488); }

            .db-toko-nama {
                font-size: 17px;
                font-weight: 800;
                color: var(--ra-heading);
            }

            .db-toko-angka {
                font-size: 28px;
                font-weight: 800;
                line-height: 1;
                color: var(--ra-heading);
            }

            .db-toko-hias {
                position: absolute;
                right: 22px;
                top: 50%;
                transform: translateY(-50%);
                font-size: 84px;
                color: #2f6bff;
                opacity: .08;
                pointer-events: none;
            }

            .db-toko-bawah {
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 12px;
                padding: 9px 12px;
                font-size: 12.5px;
                color: var(--ra-muted);
                border-top: 1px solid var(--ra-border);
            }

            .db-toko-bawah b {
                color: var(--ra-heading);
            }

            .db-sep {
                width: 1px;
                height: 14px;
                background: var(--ra-border);
            }

            .db-badge {
                padding: 2px 10px;
                border-radius: 99px;
                font-size: 11.5px;
                font-weight: 600;
            }

            .db-badge.is-aktif { color: #059669; background: rgba(16, 185, 129, .14); }
            .db-badge.is-abu { color: var(--ra-muted); background: var(--ra-hover); }

            /* daftar */
            .db-list-grid {
                display: grid;
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 18px;
                align-items: start;
                margin-bottom: 120px;
            }

            .db-list-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 10px;
                padding: 14px 16px;
                border-bottom: 1px solid var(--ra-border);
            }

            .db-list-judul {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                font-size: 14.5px;
                font-weight: 800;
                color: var(--ra-heading) !important;
                white-space: nowrap;
            }

            .db-list-judul i {
                font-size: 22px;
            }

            .db-list-judul i.is-blue {
                color: #2f6bff;
            }

            .db-cari {
                position: relative;
                width: 150px;
                max-width: 50%;
            }

            .db-cari i {
                position: absolute;
                left: 10px;
                top: 50%;
                transform: translateY(-50%);
                font-size: 14px;
                color: var(--ra-muted);
            }

            .db-cari input {
                width: 100%;
                padding: 6px 10px 6px 30px;
                border: 1px solid var(--ra-border);
                border-radius: 9px;
                font-size: 12px;
                background: var(--ra-hover);
                color: var(--ra-heading);
                outline: none;
            }

            .db-cari input:focus {
                border-color: #2f6bff;
            }

            .db-list-isi {
                max-height: 360px;
                overflow-y: auto;
                padding: 4px 0;
            }

            .db-row {
                display: flex;
                align-items: center;
                gap: 12px;
                padding: 11px 16px;
                border-bottom: 1px solid var(--ra-border);
            }

            .db-row:last-of-type {
                border-bottom: 0;
            }

            .db-row:hover {
                background: var(--ra-hover);
            }

            .db-avatar {
                flex: none;
                display: grid;
                place-items: center;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                font-size: 20px;
                color: #2f6bff;
                background: rgba(47, 107, 255, .1);
            }

            .db-avatar.is-kecil {
                width: 30px;
                height: 30px;
                font-size: 16px;
                color: var(--ra-muted);
                background: var(--ra-hover);
            }

            .db-row-teks {
                flex: 1;
                min-width: 0;
            }

            .db-row-teks b {
                display: block;
                font-size: 13px;
                font-weight: 700;
                color: var(--ra-heading);
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .db-row-teks small {
                display: block;
                font-size: 11.5px;
                color: var(--ra-muted);
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .db-unit {
                flex: none;
                padding: 4px 12px;
                border-radius: 7px;
                font-size: 12px;
                font-weight: 700;
                color: #fff;
                background: #2f6bff;
            }

            .db-tgl {
                flex: none;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                font-size: 11.5px;
                color: var(--ra-muted);
                white-space: nowrap;
            }

            .db-tgl i {
                width: 7px;
                height: 7px;
                border-radius: 50%;
            }

            .db-tgl i.is-aktif { background: #10b981; }
            .db-tgl i.is-non { background: #ef4444; }

            .db-badge-solid {
                flex: none;
                padding: 3px 9px;
                border-radius: 6px;
                font-size: 11px;
                font-weight: 700;
                color: #fff;
            }

            .db-badge-solid.is-aktif { background: #10b981; }
            .db-badge-solid.is-non { background: #ef4444; }

            .db-kosong {
                padding: 36px 12px;
                text-align: center;
                font-size: 13px;
                color: var(--ra-muted);
            }

            .db-kosong.hidden {
                display: none;
            }

            /* latar gelombang & gedung */
            .db-latar {
                position: absolute;
                left: 0;
                right: 0;
                bottom: 0;
                width: 100%;
                height: 190px;
                z-index: 0;
                pointer-events: none;
            }

            .db-latar-kota rect { fill: rgba(47, 107, 255, .06); }
            .db-latar-gel1 { fill: rgba(47, 107, 255, .07); }
            .db-latar-gel2 { fill: rgba(47, 107, 255, .11); }
            html.dark .db-latar-kota rect { fill: rgba(122, 167, 255, .045); }
            html.dark .db-latar-gel1 { fill: rgba(122, 167, 255, .05); }
            html.dark .db-latar-gel2 { fill: rgba(122, 167, 255, .08); }

            html.dark .db-card {
                box-shadow: 0 6px 22px rgba(0, 0, 0, .25);
            }

            html.dark .db-toko-hias {
                color: #7aa7ff;
            }

            @media (max-width: 1280px) {
                .db-list-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            }

            @media (max-width: 1024px) {
                .db-hero-grid, .db-toko-grid { grid-template-columns: 1fr; }
            }

            @media (max-width: 768px) {
                .db-list-grid { grid-template-columns: 1fr; }
                .db-hero-hias { display: none; }
                .db-tgl { display: none; }
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
                        <a href="<?= site_url('dashboard-manager') ?>" class="pc-link active flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white relative">
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
                            <li><a href="<?= site_url('NomorInet') ?>" class=" block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Nomor INET</a></li>
                            <li><a href="<?= site_url('QuotaSIMCARD') ?>" class=" block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Kuota Simcard</a></li>
                            <li><a href="<?= site_url('VPN') ?>" class=" block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">VPN</a></li>


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

                    <li>
                        <a href="<?= site_url('Profile') ?>"
                            class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">

                            <span class="pc-micon w-5">
                                <i class="ti ti-user-circle"></i>
                            </span>

                            <span>Profile</span>

                        </a>
                    </li>
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
                    <li class="db-judul-top ml-3"><b>Dashboard</b><small>Ringkasan Monitoring Jaringan</small></li>

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

            <!-- CONTENT (referensi: public/d/tampilan beranda.jpg) -->
            <div class="db-page p-6">
                <?php
                // status toko: 0 / 'aktif' = Aktif (sama seperti hitungStatus di controller)
                $statusAktif = static function ($v) {
                    $s = strtolower(trim((string) ($v['status'] ?? '')));
                    return $s === '0' || $s === 'aktif';
                };
                $toko = [
                    ['nama' => 'Alfamidi', 'url' => 'Alfamidi', 'ikon' => 'ti-user-square-rounded', 'warna' => 'is-ungu', 'total' => $total_midi ?? 0, 'aktif' => $total_aktif ?? 0, 'non' => $total_nonaktif ?? 0],
                    ['nama' => 'Lawson', 'url' => 'Lawson', 'ikon' => 'ti-building-store', 'warna' => 'is-oranye', 'total' => $total_lawson ?? 0, 'aktif' => $total_lawson_aktif ?? 0, 'non' => $total_lawson_nonaktif ?? 0],
                    ['nama' => 'Alfamart', 'url' => 'Alfamart', 'ikon' => 'ti-building-store', 'warna' => 'is-teal', 'total' => $total_alfamart ?? 0, 'aktif' => $total_alfamart_aktif ?? 0, 'non' => $total_alfamart_nonaktif ?? 0],
                ];
                $daftarVendor = [
                    ['judul' => 'Vendor Non Cellular', 'ikon' => 'ti-users', 'url' => 'Vendor', 'data' => $vendor ?? [], 'kosong' => 'Data vendor belum tersedia'],
                    ['judul' => 'Vendor Cellular', 'ikon' => 'ti-antenna', 'url' => 'VendorCelulllar', 'data' => $vendorCellular ?? [], 'kosong' => 'Data vendor cellular belum tersedia'],
                ];
                ?>

                <!-- ===== KARTU UTAMA ===== -->
                <div class="db-hero-grid">
                    <a href="<?= site_url('Perangkat') ?>" class="db-hero is-blue" title="Buka Master Data">
                        <span class="db-hero-ic"><i class="ti ti-database"></i></span>
                        <div class="db-hero-isi">
                            <div class="db-hero-label">Total Master Data</div>
                            <div class="db-hero-angka"><?= number_format($totalMasterData ?? 0) ?></div>
                            <div class="db-hero-note"><i class="ti ti-arrow-up"></i> dari <?= (int) ($jumlahJenisMaster ?? 0) ?> jenis master data</div>
                        </div>
                        <!-- hiasan garis gelombang -->
                        <svg class="db-hero-hias" viewBox="0 0 300 120" preserveAspectRatio="none" aria-hidden="true">
                            <path d="M0 105 C40 100 60 70 95 78 S150 110 185 70 S240 20 300 30" fill="none" stroke="rgba(255,255,255,.55)" stroke-width="3" stroke-linecap="round" />
                            <path d="M0 120 L0 105 C40 100 60 70 95 78 S150 110 185 70 S240 20 300 30 L300 120 Z" fill="rgba(255,255,255,.08)" />
                        </svg>
                        <span class="db-hero-go"><i class="ti ti-arrow-right"></i></span>
                    </a>

                    <a href="<?= site_url('DataSI') ?>" class="db-hero is-green" title="Buka Data Penggunaan">
                        <span class="db-hero-ic"><i class="ti ti-shield-check-filled"></i></span>
                        <div class="db-hero-isi">
                            <div class="db-hero-label">Total Data Penggunaan</div>
                            <div class="db-hero-angka"><?= number_format($totalDataPenggunaan ?? 0) ?></div>
                            <div class="db-hero-note">Simcard &amp; Nomor Inet</div>
                        </div>
                        <!-- hiasan grafik batang -->
                        <svg class="db-hero-hias" viewBox="0 0 300 120" preserveAspectRatio="xMaxYMax meet" aria-hidden="true">
                            <?php foreach ([[150, 70], [180, 50], [210, 85], [240, 35], [270, 60]] as [$x, $y]) : ?>
                                <rect x="<?= $x ?>" y="<?= $y ?>" width="16" height="<?= 120 - $y ?>" rx="5" fill="rgba(255,255,255,.22)" />
                            <?php endforeach; ?>
                        </svg>
                        <span class="db-hero-go"><i class="ti ti-arrow-right"></i></span>
                    </a>
                </div>

                <!-- ===== KARTU TOKO ===== -->
                <div class="db-toko-grid">
                    <?php foreach ($toko as $t) : ?>
                        <a href="<?= site_url($t['url']) ?>" class="db-card db-toko" title="Buka data <?= esc($t['nama']) ?>">
                            <div class="db-toko-atas">
                                <span class="db-toko-ic <?= $t['warna'] ?>"><i class="ti <?= $t['ikon'] ?>"></i></span>
                                <div>
                                    <div class="db-toko-nama"><?= esc($t['nama']) ?></div>
                                    <div class="flex items-center gap-3 mt-1">
                                        <span class="db-toko-angka"><?= number_format($t['total']) ?></span>
                                        <span class="db-badge <?= $t['aktif'] > 0 ? 'is-aktif' : 'is-abu' ?>">Aktif</span>
                                    </div>
                                </div>
                                <i class="ti ti-building-store db-toko-hias" aria-hidden="true"></i>
                            </div>
                            <div class="db-toko-bawah">
                                <span>Aktif : <b><?= number_format($t['aktif']) ?></b></span>
                                <span class="db-sep"></span>
                                <span>Non Aktif : <b><?= number_format($t['non']) ?></b></span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>

                <!-- ===== DAFTAR ===== -->
                <div class="db-list-grid">
                    <!-- Merek Perangkat -->
                    <div class="db-card db-list">
                        <div class="db-list-head">
                            <a href="<?= site_url('Perangkat') ?>" class="db-list-judul"><i class="ti ti-chart-bar is-blue"></i> Merek Perangkat</a>
                            <div class="db-cari"><i class="ti ti-search"></i><input type="text" placeholder="Cari..." data-cari="merek"></div>
                        </div>
                        <div class="db-list-isi custom-scroll" data-daftar="merek">
                            <?php foreach ($merekPerangkat ?? [] as $mp) : ?>
                                <div class="db-row">
                                    <span class="db-avatar"><i class="ti ti-router"></i></span>
                                    <div class="db-row-teks">
                                        <b><?= esc($mp['merk_perangkat'] ?: 'Tanpa Merek') ?></b>
                                        <small>Total perangkat</small>
                                    </div>
                                    <span class="db-unit"><?= (int) $mp['jumlah'] ?> unit</span>
                                </div>
                            <?php endforeach; ?>
                            <div class="db-kosong <?= empty($merekPerangkat) ? '' : 'hidden' ?>" data-kosong="Data perangkat belum tersedia">Data perangkat belum tersedia</div>
                        </div>
                    </div>

                    <!-- Vendor Non Cellular & Vendor Cellular -->
                    <?php foreach ($daftarVendor as $i => $dv) : ?>
                        <div class="db-card db-list">
                            <div class="db-list-head">
                                <a href="<?= site_url($dv['url']) ?>" class="db-list-judul"><i class="ti <?= $dv['ikon'] ?> is-blue"></i> <?= esc($dv['judul']) ?></a>
                                <div class="db-cari"><i class="ti ti-search"></i><input type="text" placeholder="Cari vendor..." data-cari="vendor<?= $i ?>"></div>
                            </div>
                            <div class="db-list-isi custom-scroll" data-daftar="vendor<?= $i ?>">
                                <?php foreach ($dv['data'] as $v) : ?>
                                    <?php $aktif = $statusAktif($v); ?>
                                    <div class="db-row">
                                        <span class="db-avatar is-kecil"><i class="ti ti-user"></i></span>
                                        <div class="db-row-teks">
                                            <b title="<?= esc($v['nama_vendor'] ?? '-', 'attr') ?>"><?= esc($v['nama_vendor'] ?? '-') ?></b>
                                            <small title="<?= esc($v['keterangan'] ?? '-', 'attr') ?>"><?= esc($v['keterangan'] ?? '-') ?></small>
                                        </div>
                                        <span class="db-tgl"><i class="<?= $aktif ? 'is-aktif' : 'is-non' ?>"></i><?= !empty($v['created_at']) ? date('d-m-Y H:i', strtotime($v['created_at'])) : '-' ?></span>
                                        <span class="db-badge-solid <?= $aktif ? 'is-aktif' : 'is-non' ?>"><?= $aktif ? 'Aktif' : 'Non Aktif' ?></span>
                                    </div>
                                <?php endforeach; ?>
                                <div class="db-kosong <?= empty($dv['data']) ? '' : 'hidden' ?>" data-kosong="<?= esc($dv['kosong'], 'attr') ?>"><?= esc($dv['kosong']) ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- hiasan latar: gelombang & siluet gedung -->
                <svg class="db-latar" viewBox="0 0 1200 260" preserveAspectRatio="xMidYMax slice" aria-hidden="true">
                    <g class="db-latar-kota">
                        <rect x="880" y="120" width="34" height="140" /><rect x="920" y="80" width="40" height="180" /><rect x="966" y="140" width="28" height="120" />
                        <rect x="1000" y="60" width="44" height="200" /><rect x="1050" y="110" width="30" height="150" /><rect x="1086" y="90" width="38" height="170" />
                        <rect x="1130" y="150" width="30" height="110" /><rect x="1166" y="125" width="34" height="135" /><rect x="830" y="160" width="40" height="100" />
                    </g>
                    <path class="db-latar-gel1" d="M0 200 C200 150 420 250 650 205 S1000 140 1200 190 L1200 260 L0 260 Z" />
                    <path class="db-latar-gel2" d="M0 230 C260 190 480 260 760 228 S1060 190 1200 220 L1200 260 L0 260 Z" />
                </svg>
            </div>
        </div>
        <script>
            // ---- Cari di daftar Merek Perangkat / Vendor (per kartu) ----
            document.querySelectorAll('[data-cari]').forEach(function(input) {
                const daftar = document.querySelector('[data-daftar="' + input.dataset.cari + '"]');
                const baris = daftar.querySelectorAll('.db-row');
                const kosong = daftar.querySelector('.db-kosong');
                input.addEventListener('input', function() {
                    const kata = this.value.toLowerCase().trim();
                    let tampil = 0;
                    baris.forEach(function(r) {
                        const cocok = r.textContent.toLowerCase().includes(kata);
                        r.style.display = cocok ? '' : 'none';
                        if (cocok) tampil++;
                    });
                    kosong.textContent = baris.length ? 'Data tidak ditemukan' : kosong.dataset.kosong;
                    kosong.classList.toggle('hidden', tampil > 0);
                });
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
    </body>

    </html>