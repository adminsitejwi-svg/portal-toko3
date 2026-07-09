    <!doctype html>
    <html lang="en" class="light">

    <head>
        <meta charset="utf-8" />
        <title>Beranda</title>
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
                        <a href="<?= site_url('dashboard-manager') ?>" class="pc-link active flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white relative">
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
                    <h5 class="font-medium text-lg">Dashboard</h5>

                </div>

                <div class="grid grid-cols-12 gap-x-6">
                    <!-- Daily Sales -->



                    <a href="<?= site_url('Jns_perangkat') ?>" class="col-span-12 xl:col-span-4 md:col-span-6">
                        <div class="card hover:shadow-lg transition-all duration-300 cursor-pointer">
                            <div class="card-header !border-b-0">
                                <h5>Total Jenis Perangkat</h5>
                            </div>

                            <div class="card-body">
                                <h3 class="text-2xl font-light flex items-center">
                                    <i class="ti ti-device-desktop text-success-500 text-2xl mr-2"></i>
                                    <?= $totalJenisPerangkat ?>
                                </h3>
                            </div>
                        </div>
                    </a>
                    <a href="<?= site_url('TypePerangkat') ?>" class="col-span-12 xl:col-span-4 md:col-span-6">
                        <div class="card hover:shadow-lg transition-all duration-300 cursor-pointer">
                            <div class="card-header !border-b-0">
                                <h5>Total Type Perangkat</h5>
                            </div>

                            <div class="card-body">
                                <h3 class="text-2xl font-light flex items-center">
                                    <i class="ti ti-devices text-success-500 text-2xl mr-2"></i>
                                    <?= $totalTypePerangkat ?>
                                </h3>
                            </div>
                        </div>
                    </a>
                    <a href="<?= site_url('Vendor') ?>" class="col-span-12 xl:col-span-4 md:col-span-6">
                        <div class="card hover:shadow-lg transition-all duration-300 cursor-pointer">
                            <div class="card-header !border-b-0">
                                <h5>Total Vendor Non Cellular</h5>
                            </div>

                            <div class="card-body">
                                <h3 class="text-2xl font-light flex items-center">
                                    <i class="ti ti-users text-success-500 text-2xl mr-2"></i>
                                    <?= count($vendor) ?>
                                </h3>
                            </div>
                        </div>
                    </a>
                    <a href="<?= site_url('VendorCelulllar') ?>" class="col-span-12 xl:col-span-4 md:col-span-6">
                        <div class="card hover:shadow-lg transition-all duration-300 cursor-pointer">
                            <div class="card-header !border-b-0">
                                <h5>Total Vendor Cellular</h5>
                            </div>

                            <div class="card-body">
                                <h3 class="text-2xl font-light flex items-center">
                                    <i class="ti ti-user text-success-500 text-2xl mr-2"></i>
                                    <?= count($vendorCellular) ?>
                                </h3>
                            </div>
                        </div>
                    </a>
                    <a href="<?= site_url('LayananVendor') ?>" class="col-span-12 xl:col-span-4 md:col-span-6">
                        <div class="card hover:shadow-lg transition-all duration-300 cursor-pointer">
                            <div class="card-header !border-b-0">
                                <h5>Total Layanan Vendor</h5>
                            </div>

                            <div class="card-body">
                                <h3 class="text-2xl font-light flex items-center">
                                    <i class="ti ti-headset text-success-500 text-2xl mr-2"></i>
                                    <?= $totalLayananVendor ?>
                                </h3>
                            </div>
                        </div>
                    </a>
                    <a href="<?= site_url('DCAdmin') ?>" class="col-span-12 xl:col-span-4 md:col-span-6">
                        <div class="card hover:shadow-lg transition-all duration-300 cursor-pointer">
                            <div class="card-header !border-b-0 ">
                                <h5>Total DC</h5>
                            </div>

                            <div class="card-body">
                                <div class="flex items-center justify-between flex-wrap gap-3">
                                    <h3 class="text-2xl font-light flex items-center">
                                        <i class="ti ti-sitemap text-success-500 text-2xl mr-2"></i>
                                        <?= $totalDC ?? 0 ?>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </a>


                    <a href="<?= site_url('MediaKoneksi') ?>" class="col-span-12 xl:col-span-4 md:col-span-6">
                        <div class="card hover:shadow-lg transition-all duration-300 cursor-pointer">
                            <div class="card-header !border-b-0 ">
                                <h5>Total Media Koneksi</h5>
                            </div>

                            <div class="card-body">
                                <div class="flex items-center justify-between flex-wrap gap-3">
                                    <h3 class="text-2xl font-light flex items-center">
                                        <i class="ti ti-world text-success-500 text-2xl mr-2"></i>
                                        <?= $totalMediaKoneksi ?? 0 ?>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="<?= site_url('PemilikProject') ?>" class="col-span-12 xl:col-span-4 md:col-span-6">
                        <div class="card hover:shadow-lg transition-all duration-300 cursor-pointer">
                            <div class="card-header !border-b-0">
                                <h5>Total Pemilik Project</h5>
                            </div>

                            <div class="card-body">
                                <h3 class="text-2xl font-light flex items-center">
                                    <i class="ti ti-user-star text-success-500 text-2xl mr-2"></i>
                                    <?= $totalPemilikProject ?>
                                </h3>
                            </div>
                        </div>
                    </a>
                    <!-- Yearly Sales -->


                    <a href="<?= site_url('Pelanggan') ?>" class="col-span-12 xl:col-span-4 md:col-span-6">
                        <div class="card hover:shadow-lg transition-all duration-300 cursor-pointer">
                            <div class="card-header !border-b-0 ">
                                <h5>Total Kategori Pelanggan</h5>
                            </div>
                            <div class="card-body">
                                <div class="flex items-center justify-between flex-wrap gap-3">
                                    <h3 class="text-2xl font-light flex items-center">
                                        <i class="ti ti-users-group text-success-500 text-2xl mr-2"></i>
                                        <?= $totalPelanggan ?? 0 ?>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </a>


                    <a href="<?= site_url('NomorInet') ?>" class="col-span-12 xl:col-span-4 md:col-span-6">
                        <div class="card hover:shadow-lg transition-all duration-300 cursor-pointer">
                            <div class="card-header !border-b-0 ">
                                <h5>Total Nomor INET</h5>
                            </div>
                            <div class="card-body">
                                <div class="flex items-center justify-between flex-wrap gap-3">
                                    <h3 class="text-2xl font-light flex items-center">
                                        <i class="ti ti-device-sd-card text-success-500 text-2xl mr-2"></i>
                                        <?= $totalNomorInet ?? 0 ?>
                                    </h3>
                                </div>
                            </div>
                        </div>
                    </a>

                    <a href="<?= site_url('NMRInet') ?>" class="col-span-12 xl:col-span-4 md:col-span-6">
                        <div class="card hover:shadow-lg transition-all duration-300 cursor-pointer">
                            <div class="card-header !border-b-0">
                                <h5>Total Data Nomor INET</h5>
                            </div>

                            <div class="card-body">
                                <h3 class="text-2xl font-light flex items-center">
                                    <i class="ti ti-device-sd-card text-success-500 text-2xl mr-2"></i>
                                    <?= $totalPenggunaanInet ?>
                                </h3>
                            </div>
                        </div>
                    </a>
                    <a href="<?= site_url('QuotaSIMCARD') ?>" class="col-span-12 xl:col-span-4 md:col-span-6">
                        <div class="card hover:shadow-lg transition-all duration-300 cursor-pointer">
                            <div class="card-header !border-b-0">
                                <h5>Total Kuota SIMCARD</h5>
                            </div>

                            <div class="card-body">
                                <h3 class="text-2xl font-light flex items-center">
                                    <i class="ti ti-router text-success-500 text-2xl mr-2"></i>
                                    <?= $totalKuotaSIMCARD ?>
                                </h3>
                            </div>
                        </div>
                    </a>


                    <a href="<?= site_url('DataSI') ?>" class="col-span-12 xl:col-span-4 md:col-span-6">
                        <div class="card hover:shadow-lg transition-all duration-300 cursor-pointer">
                            <div class="card-header !border-b-0">
                                <h5>Total Data SIMCARD</h5>
                            </div>

                            <div class="card-body">
                                <h3 class="text-2xl font-light flex items-center">
                                    <i class="ti ti-router text-success-500 text-2xl mr-2"></i>
                                    <?= $totalSIMCARD ?>
                                </h3>
                            </div>
                        </div>
                    </a>

                    <a href="<?= site_url('VPN') ?>" class="col-span-12 xl:col-span-4 md:col-span-6">
                        <div class="card hover:shadow-lg transition-all duration-300 cursor-pointer">
                            <div class="card-header !border-b-0">
                                <h5>Total VPN</h5>
                            </div>

                            <div class="card-body">
                                <h3 class="text-2xl font-light flex items-center">
                                    <i class="ti ti-shield-lock text-success-500 text-2xl mr-2"></i>
                                    <?= $totalVPN ?? 0 ?>
                                </h3>
                            </div>
                        </div>
                    </a>
                    <div class="col-span-12">

                    </div>
                    <!-- Facebook -->
                    <div class="col-span-12 xl:col-span-4">
                        <div class="card">
                            <div class="card-body border-b border-gray-100 dark:border-white/10">
                                <div class="flex items-center justify-center">
                                    <i class="ti ti-building-store text-primary-500 text-4xl"></i>
                                    <div class="ml-auto text-right">
                                        <h3 class="text-2xl mb-1">
                                            <?= number_format($total_midi) ?>
                                        </h3>
                                        <h5 class="text-success-500"><span class="text-gray-400">Alfamidi</span></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body grid grid-cols-2 gap-x-6">
                                <div>
                                    <h6 class="text-center mb-2 text-sm"><span class="text-gray-400">Aktif :</span> <?= number_format($total_aktif) ?></h6>

                                </div>
                                <div>
                                    <h6 class="text-center mb-2 text-sm"><span class="text-gray-400">Non Aktif :</span> <?= number_format($total_nonaktif) ?></h6>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Twitter -->
                    <div class="col-span-12 xl:col-span-4 md:col-span-6">
                        <div class="card">
                            <div class="card-body border-b border-gray-100 dark:border-white/10">
                                <div class="flex items-center justify-center">
                                    <i class="ti ti-building-store text-primary-500 text-4xl"></i>
                                    <div class="ml-auto text-right">
                                        <h3 class="text-2xl mb-1"><?= $total_lawson ?></h3>
                                        <h5 class="text-purple-500"><span class="text-gray-400">Lawson</span></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body grid grid-cols-2 gap-x-6">
                                <div>
                                    <h6 class="text-center mb-2 text-sm"><span class="text-gray-400">Aktif :</span> <?= $total_lawson_aktif ?></h6>

                                </div>
                                <div>
                                    <h6 class="text-center mb-2 text-sm"><span class="text-gray-400">Non Aktif :</span> <?= $total_lawson_nonaktif ?></h6>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Google -->
                    <div class="col-span-12 xl:col-span-4 md:col-span-6">
                        <div class="card">
                            <div class="card-body border-b border-gray-100 dark:border-white/10">
                                <div class="flex items-center justify-center">
                                    <i class="ti ti-building-store text-primary-500 text-4xl"></i>
                                    <div class="ml-auto text-right">
                                        <h3 class="text-2xl mb-1"><?= $total_alfamart ?></h3>
                                        <h5 class="text-purple-500"><span class="text-gray-400">Alfamart</span></h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body grid grid-cols-2 gap-x-6">
                                <div>
                                    <h6 class="text-center mb-2 text-sm"><span class="text-gray-400">Aktif :</span><?= $total_alfamart_aktif ?></h6>

                                </div>
                                <div>
                                    <h6 class="text-center mb-2 text-sm"><span class="text-gray-400">Non Aktif :</span><?= $total_alfamart_nonaktif ?></h6>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Merek Perangkat -->
                    <div class="col-span-12 xl:col-span-4 md:col-span-6">
                        <div class="card">
                            <div class="card-header flex items-center justify-between gap-2 flex-wrap">
                                <h5>Merek Perangkat</h5>

                                <div class="flex items-center gap-2">

                                    <div class="relative">
                                        <input type="text" id="merekSearch" placeholder="Cari..."
                                            class="border border-gray-200 dark:border-white/10 dark:bg-[#1d2630] rounded-lg pl-8 pr-2 py-1.5 text-xs outline-none focus:border-primary-500 w-28">
                                        <i class="ti ti-search absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                                    </div>

                                    <a href="<?= site_url('Perangkat') ?>"
                                        class="inline-flex items-center justify-center w-8 h-8 text-white bg-primary-500 hover:bg-primary-600 rounded-lg transition"
                                        title="Lihat Perangkat">
                                        <i class="ti ti-external-link text-sm"></i>
                                    </a>

                                </div>

                            </div>
                            <div class="card-body !p-0">

                                <?php
                                $totalMerek = count($merekPerangkat ?? []);
                                ?>

                                <div
                                    class="<?= $totalMerek > 5 ? 'overflow-y-auto custom-scroll' : '' ?>"
                                    style="<?= $totalMerek > 5 ? 'max-height:360px;' : '' ?>">

                                    <table class="w-full text-sm">
                                        <tbody id="merekList">
                                            <?php if (!empty($merekPerangkat)) : ?>
                                                <?php foreach ($merekPerangkat as $mp) : ?>
                                                    <tr class="merek-row border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5">
                                                        <td class="px-5 py-4">
                                                            <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-white/10 flex items-center justify-center">
                                                                <i class="ti ti-device-desktop text-gray-500 text-xl"></i>
                                                            </div>
                                                        </td>
                                                        <td class="px-2 py-4">
                                                            <h6 class="font-medium mb-1 merek-name"><?= esc($mp['merk_perangkat'] ?: 'Tanpa Merek') ?></h6>
                                                            <p class="text-gray-400 text-xs">Total perangkat</p>
                                                        </td>
                                                        <td class="px-5 py-4 whitespace-nowrap text-right">
                                                            <span class="text-xs text-white bg-primary-500 px-3 py-1 rounded"><?= $mp['jumlah'] ?> unit</span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>

                                    <div id="merekEmpty" class="text-center text-gray-400 text-sm py-10 <?= !empty($merekPerangkat) ? 'hidden' : '' ?>">
                                        Data perangkat belum tersedia
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Users -->
                    <div class="col-span-12 xl:col-span-4 md:col-span-6">
                        <div class="card vendor-card">
                            <div class="card-header flex items-center justify-between gap-3 flex-wrap">

                                <h5>Vendor Non Celullar</h5>

                                <div class="flex items-center gap-2 flex-wrap">

                                    <div class="relative">
                                        <input
                                            type="text"
                                            id="vendorSearch"
                                            placeholder="Cari vendor..."
                                            class="border border-gray-200 dark:border-white/10 dark:bg-[#1d2630] rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-primary-500 w-56 max-w-full">

                                        <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                    </div>

                                    <a href="<?= site_url('Vendor') ?>"
                                        class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 rounded-lg transition">

                                        <i class="ti ti-external-link"></i>
                                        Lihat

                                    </a>

                                </div>

                            </div>
                            <div class="card-body !p-0">

                                <?php
                                $totalVendor = count($vendor ?? []);
                                ?>

                                <div
                                    class="<?= $totalVendor > 5 ? 'overflow-y-auto custom-scroll' : '' ?>"
                                    style="<?= $totalVendor > 5 ? 'max-height:360px;' : '' ?>">

                                    <div class="overflow-x-auto custom-scroll">
                                        <table class="min-w-[700px] w-full text-sm" style="min-width:560px">
                                            <tbody id="vendorList">

                                                <?php if (!empty($vendor)) : ?>
                                                    <?php foreach ($vendor as $v) : ?>
                                                        <?php
                                                        // Normalisasi: 0/'aktif' => Aktif, selain itu => Non Aktif
                                                        $s = strtolower(trim((string)($v['status'] ?? '')));
                                                        $isAktif = ($s === '0' || $s === 'aktif');
                                                        ?>
                                                        <tr class="vendor-row border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5">

                                                            <td class="px-5 py-4">
                                                                <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-white/10 flex items-center justify-center">
                                                                    <i class="ti ti-user text-gray-500 text-xl"></i>
                                                                </div>
                                                            </td>

                                                            <td class="px-2 py-4">
                                                                <h6 class="font-medium mb-1 vendor-name"><?= esc($v['nama_vendor'] ?? '-') ?></h6>
                                                                <p class="text-gray-400 text-xs"><?= esc($v['keterangan'] ?? '-') ?></p>
                                                            </td>

                                                            <td class="px-2 py-4 whitespace-nowrap text-gray-400 text-xs">
                                                                <i class="fas fa-circle text-[8px] mr-2 <?= $isAktif ? 'text-success-500' : 'text-danger-500' ?>"></i>
                                                                <?= !empty($v['created_at']) ? date('d-m-Y H:i', strtotime($v['created_at'])) : '-' ?>
                                                            </td>

                                                            <td class="px-5 py-4 whitespace-nowrap">
                                                                <?php if ($isAktif) : ?>
                                                                    <span class="text-xs text-white bg-success-500 px-3 py-1 rounded">Aktif</span>
                                                                <?php else : ?>
                                                                    <span class="text-xs text-white bg-danger-500 px-3 py-1 rounded">Non Aktif</span>
                                                                <?php endif; ?>
                                                            </td>

                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>

                                            </tbody>
                                        </table>
                                    </div>

                                    <div
                                        id="vendorEmpty"
                                        class="text-center text-gray-400 text-sm py-10 <?= !empty($vendor) ? 'hidden' : '' ?>">

                                        Data vendor non celullar belum tersedia

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 xl:col-span-4 md:col-span-6">
                        <div class="card">

                            <div class="card-header flex items-center justify-between gap-3 flex-wrap">

                                <h5>Vendor Cellular</h5>

                                <div class="flex items-center gap-2 flex-wrap">

                                    <div class="relative">
                                        <input
                                            type="text"
                                            id="vendorSearch2"
                                            placeholder="Cari vendor..."
                                            class="border border-gray-200 dark:border-white/10 dark:bg-[#1d2630] rounded-lg pl-9 pr-3 py-2 text-sm outline-none focus:border-primary-500 w-56 max-w-full">

                                        <i class="ti ti-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                    </div>

                                    <a href="<?= site_url('VendorCelulllar') ?>"
                                        class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-white bg-primary-500 hover:bg-primary-600 rounded-lg transition">

                                        <i class="ti ti-external-link"></i>
                                        Lihat

                                    </a>

                                </div>

                            </div>
                            <div class="card-body !p-0">

                                <?php
                                $totalVendor = count($vendorCellular ?? []);
                                ?>

                                <div
                                    class="<?= $totalVendor > 5 ? 'ooverflow-y-auto overflow-x-hidden custom-scroll' : '' ?>"
                                    style="<?= $totalVendor > 5 ? 'max-height:360px;' : '' ?>">

                                    <div class="overflow-x-auto custom-scroll">
                                        <table class="w-full text-sm" style="min-width:560px">
                                            <tbody id="vendorList">

                                                <?php if (!empty($vendorCellular)) : ?>
                                                    <?php foreach ($vendorCellular as $v) : ?>
                                                        <?php
                                                        // Normalisasi status: 0/'aktif' => Aktif, selain itu => Non Aktif
                                                        $s = strtolower(trim((string)($v['status'] ?? '')));
                                                        $isAktif = ($s === '0' || $s === 'aktif');
                                                        ?>
                                                        <tr class="vendor-row border-b border-gray-50 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5">

                                                            <td class="px-5 py-4">
                                                                <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-white/10 flex items-center justify-center">
                                                                    <i class="ti ti-user text-gray-500 text-xl"></i>
                                                                </div>
                                                            </td>

                                                            <td class="px-2 py-4">
                                                                <h6 class="font-medium mb-1 vendor-name">
                                                                    <?= esc($v['nama_vendor'] ?? '-') ?>
                                                                </h6>
                                                                <p class="text-gray-400 text-xs">
                                                                    <?= esc($v['keterangan'] ?? '-') ?>
                                                                </p>
                                                            </td>

                                                            <td class="px-2 py-4 whitespace-nowrap text-gray-400 text-xs">
                                                                <i class="fas fa-circle text-[8px] mr-2 <?= $isAktif ? 'text-success-500' : 'text-danger-500' ?>"></i>
                                                                <?= !empty($v['created_at'])
                                                                    ? date('d-m-Y H:i', strtotime($v['created_at']))
                                                                    : '-' ?>
                                                            </td>

                                                            <td class="px-5 py-4 whitespace-nowrap">
                                                                <?php if ($isAktif) : ?>
                                                                    <span class="text-xs text-white bg-success-500 px-3 py-1 rounded">
                                                                        Aktif
                                                                    </span>
                                                                <?php else : ?>
                                                                    <span class="text-xs text-white bg-danger-500 px-3 py-1 rounded">
                                                                        Non Aktif
                                                                    </span>
                                                                <?php endif; ?>
                                                            </td>

                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>

                                            </tbody>
                                        </table>
                                    </div>

                                    <div
                                        id="vendorEmpty"
                                        class="text-center text-gray-400 text-sm py-10 <?= !empty($vendorCellular) ? 'hidden' : '' ?>">

                                        Data vendor celullar belum tersedia

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

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
    </body>

    </html>