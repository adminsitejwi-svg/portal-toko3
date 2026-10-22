<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-rumahwifi.png') ?>?v=2">
    <title>Riwayat Email Maintenance</title>
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

        .brand-text {
            font-size: 18px;
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/theme/rfo.css') ?>?v=<?= @filemtime(FCPATH . 'assets/theme/rfo.css') ?: '1' ?>">
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
                <li class="px-6 py-3 text-[11px] uppercase tracking-wide text-[#5b6b7f] font-semibold">NOC Operation</li>
                <li>
                    <a href="<?= site_url('RFO') ?>" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-file-alert"></i></span>
                        <span>RFO</span>
                    </a>
                </li>
                <li>
                    <a href="<?= site_url('MDMaintenance') ?>" class="pc-link active flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
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
                    </ul>
                </li>
                <li class="hasmenu">
                    <a href="#" onclick="toggleSub(this);return false;" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-category"></i></span>
                        <span class="flex-1">All NOC</span>
                        <i data-feather="chevron-right" class="arrow w-4 h-4 transition-transform"></i>
                    </a>
                    <ul class="submenu bg-black/20">
                        <li><a href="<?= site_url('DataCenter') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Center</a></li>
                        <li><a href="<?= site_url('Interkoneksi') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Interkoneksi</a></li>
                        <li><a href="<?= site_url('DevicesBackbone') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Devices Backbone</a></li>
                        <li><a href="<?= site_url('Bts') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data BTS</a></li>
                        <li><a href="<?= site_url('PelangganNoc') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Pelanggan</a></li>
                        <li><a href="<?= site_url('VendorNoc') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Vendor</a></li>
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
            $fmt = static fn ($dt) => $dt ? date('d-m-Y H:i', strtotime($dt)) : '-';

            // Badge per status pengiriman: [kelas warna, ikon]
            $statusBadge = [
                'Terkirim' => ['rb-green', 'ti-send'],
                'Gagal'    => ['rb-red', 'ti-alert-circle'],
            ];

            // Ringkasan untuk kartu statistik
            $total    = count($log);
            $terkirim = count(array_filter($log, static fn ($r) => $r['status'] === 'Terkirim'));
            $gagal    = count(array_filter($log, static fn ($r) => $r['status'] === 'Gagal'));
            $hariIni  = date('Y-m-d');
            $jmlHari  = count(array_filter($log, static fn ($r) => substr((string) $r['created_at'], 0, 10) === $hariIni));

            // Kartu yang diklik: ?tampil=terkirim | gagal | hari-ini (selain itu: semua riwayat)
            $tampilOpsi = [
                'terkirim' => ['Email Terkirim', static fn ($r) => $r['status'] === 'Terkirim'],
                'gagal'    => ['Email Gagal', static fn ($r) => $r['status'] === 'Gagal'],
                'hari-ini' => ['Email Hari Ini', static fn ($r) => substr((string) $r['created_at'], 0, 10) === $hariIni],
            ];
            $tampil   = service('request')->getGet('tampil');
            $tampil   = isset($tampilOpsi[$tampil]) ? $tampil : '';
            $logTabel = $tampil ? array_values(array_filter($log, $tampilOpsi[$tampil][1])) : $log;
            ?>

            <!-- JUDUL -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic is-green"><i class="ti ti-mail"></i></span>
                    <div>
                        <h1 class="rfo-title">Riwayat Email Maintenance</h1>
                        <?php if ($tampil) : ?>
                            <p class="rfo-subtitle">Menampilkan: <b><?= $tampilOpsi[$tampil][0] ?></b> (<?= count($logTabel) ?> data)</p>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <?php if ($tampil) : ?>
                        <a href="<?= site_url('MDMaintenance/email-log') ?>" class="rfo-btn rfo-btn-gray">
                            <i class="ti ti-list"></i> Semua Riwayat
                        </a>
                    <?php endif; ?>
                    <a href="<?= site_url('MDMaintenance') ?>" class="rfo-btn rfo-btn-blue">
                        <i class="ti ti-arrow-left"></i> Kembali ke Maintenance
                    </a>
                </div>
            </div>

            <!-- KARTU STATISTIK -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
                <a href="<?= site_url('MDMaintenance/email-log') ?>" class="rfo-stat rfo-stat-blue rfo-stat-link<?= $tampil === '' ? ' is-active' : '' ?>">
                    <span class="rfo-stat-ic"><i class="ti ti-mail"></i></span>
                    <div>
                        <div class="rfo-stat-label">Total Email</div>
                        <div class="rfo-stat-value"><?= $total ?></div>
                        <div class="rfo-stat-note">Riwayat keseluruhan</div>
                    </div>
                    <i class="ti ti-chart-bar rfo-stat-chart"></i>
                </a>
                <a href="<?= site_url('MDMaintenance/email-log') . '?tampil=terkirim' ?>" class="rfo-stat rfo-stat-green rfo-stat-link<?= $tampil === 'terkirim' ? ' is-active' : '' ?>">
                    <span class="rfo-stat-ic"><i class="ti ti-check"></i></span>
                    <div>
                        <div class="rfo-stat-label">Terkirim</div>
                        <div class="rfo-stat-value"><?= $terkirim ?></div>
                        <div class="rfo-stat-note"><?= $total ? round($terkirim / $total * 100) : 0 ?>% dari total</div>
                    </div>
                    <i class="ti ti-chart-bar rfo-stat-chart"></i>
                </a>
                <a href="<?= site_url('MDMaintenance/email-log') . '?tampil=gagal' ?>" class="rfo-stat rfo-stat-red rfo-stat-link<?= $tampil === 'gagal' ? ' is-active' : '' ?>">
                    <span class="rfo-stat-ic"><i class="ti ti-alert-triangle"></i></span>
                    <div>
                        <div class="rfo-stat-label">Gagal</div>
                        <div class="rfo-stat-value"><?= $gagal ?></div>
                        <div class="rfo-stat-note">Perlu dikirim ulang</div>
                    </div>
                    <i class="ti ti-chart-bar rfo-stat-chart"></i>
                </a>
                <a href="<?= site_url('MDMaintenance/email-log') . '?tampil=hari-ini' ?>" class="rfo-stat rfo-stat-purple rfo-stat-link<?= $tampil === 'hari-ini' ? ' is-active' : '' ?>">
                    <span class="rfo-stat-ic"><i class="ti ti-calendar-event"></i></span>
                    <div>
                        <div class="rfo-stat-label">Hari Ini</div>
                        <div class="rfo-stat-value"><?= $jmlHari ?></div>
                        <div class="rfo-stat-note">Email dikirim hari ini</div>
                    </div>
                    <i class="ti ti-chart-bar rfo-stat-chart"></i>
                </a>
            </div>

            <!-- FILTER + TABEL -->
            <div class="rfo-panel">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
                    <div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <div id="lengthArea"></div>
                            <label class="rfo-field">
                                <i class="ti ti-circle-check-filled" style="color:#16a34a"></i>
                                <select id="filterStatus" class="filter-select" title="Filter Status">
                                    <option value="">Semua Status</option>
                                    <option value="Terkirim">Terkirim</option>
                                    <option value="Gagal">Gagal</option>
                                </select>
                            </label>
                            <label class="rfo-field">
                                <i class="ti ti-tag" style="color:#6d5dfc"></i>
                                <select id="filterMtId" class="filter-select is-wide" title="Filter Maintenance ID">
                                    <option value="">Semua Maintenance ID</option>
                                    <?php foreach (array_unique(array_filter(array_column($logTabel, 'kode_mt'))) as $kode) : ?>
                                        <option value="<?= esc($kode) ?>"><?= esc($kode) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <div class="rfo-search">
                            <i class="ti ti-search"></i>
                            <input type="text" id="customSearch" placeholder="Cari Maintenance ID, perusahaan, penerima, subjek..." />
                        </div>
                        <button type="button" id="btnReset" class="rfo-ghost-btn"><i class="ti ti-refresh"></i> Reset</button>
                        <div id="exportArea"></div>
                    </div>
                </div>

                <!-- TABLE -->
                <div class="table-scroll">
                    <table id="emailTable" class="rfo-table display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Waktu Kirim</th>
                                <th>Maintenance ID</th>
                                <th>Perusahaan</th>
                                <th>Dari</th>
                                <th>Kepada</th>
                                <th>CC</th>
                                <th>Subjek</th>
                                <th>Lampiran</th>
                                <th>Status</th>
                                <th class="no-export">Body Email</th>
                                <th>Dikirim Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($logTabel)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($logTabel as $row) : ?>
                                    <?php [$stKelas, $stIkon] = $statusBadge[$row['status']] ?? ['rb-blue', 'ti-info-circle']; ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td data-order="<?= esc($row['created_at'] ?? '') ?>">
                                            <span class="cell-ic"><i class="ti ti-calendar" style="color:#2f6bff"></i><?= $fmt($row['created_at']); ?></span>
                                        </td>
                                        <td data-search="<?= esc($row['kode_mt'] ?? '') ?>">
                                            <?php if (!empty($row['kode_mt'])) : ?>
                                                <a href="<?= site_url('MDMaintenance/view/' . $row['mt_id']) ?>" class="rfo-link" title="Lihat surat Maintenance"><?= esc($row['kode_mt']); ?></a>
                                            <?php else : ?>
                                                <span class="rfo-id">(Maintenance dihapus)</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="cell-ic"><i class="ti ti-building-skyscraper" style="color:#2f6bff"></i><?= esc($row['perusahaan'] ?? '-'); ?></span>
                                        </td>
                                        <td><?= esc($row['dari'] ?? 'noc@jwi.id'); ?></td>
                                        <td><span class="cell-clip" title="<?= esc($row['kepada'], 'attr') ?>"><?= esc($row['kepada']); ?></span></td>
                                        <td><span class="cell-clip" title="<?= esc($row['cc'] ?? '', 'attr') ?>"><?= esc($row['cc'] ?: '-'); ?></span></td>
                                        <td><span class="cell-clip" title="<?= esc($row['subjek'], 'attr') ?>"><?= esc($row['subjek']); ?></span></td>
                                        <td>
                                            <?php if (!empty($row['lampiran'])) : ?>
                                                <span class="cell-ic"><i class="ti ti-file-type-pdf" style="color:#ef4444"></i><span class="cell-clip" title="<?= esc($row['lampiran'], 'attr') ?>"><?= esc($row['lampiran']); ?></span></span>
                                            <?php else : ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <!-- Pesan error (status Gagal) tampil saat kursor diarahkan ke status -->
                                        <td data-search="<?= esc($row['status']) ?>">
                                            <span class="rb <?= $stKelas ?>" title="<?= esc(trim($row['pesan_error'] ?? ''), 'attr') ?>"><i class="ti <?= $stIkon ?>"></i><?= esc($row['status']); ?></span>
                                        </td>
                                        <td>
                                            <button type="button" onclick="lihatBody(<?= (int) $row['id'] ?>, '<?= esc($row['kode_mt'] ?? '', 'js') ?>')" class="act act-view"><i class="ti ti-mail-opened"></i> Lihat</button>
                                        </td>
                                        <td>
                                            <span class="cell-ic"><i class="ti ti-user" style="color:#6d5dfc"></i><?= esc($row['dikirim_oleh'] ?? '-'); ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div id="emailFooter" class="rfo-footer flex flex-wrap items-center justify-between gap-3 mt-4"></div>
            </div>
        </div>
    </div>

    <!-- MODAL BODY EMAIL (hanya tampilan, tidak bisa diubah) -->
    <div id="bodyModal" class="fixed inset-0 z-[2000] hidden items-center justify-center bg-black/50 p-4" onclick="if (event.target === this) tutupBody()">
        <div class="w-full max-w-2xl rounded-lg bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-[#e3e8ee] px-5 py-3">
                <h5 class="font-medium text-base"><i class="ti ti-mail-opened"></i> Body Email <span id="bodyRfoId" class="text-gray-500"></span></h5>
                <button type="button" onclick="tutupBody()" class="text-xl leading-none text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <div class="px-5 py-4">
                <iframe id="bodyFrame" title="Body Email Maintenance" sandbox="" class="h-[60vh] w-full rounded-lg border border-[#e3e8ee] bg-white"></iframe>
            </div>
        </div>
    </div>

    <script>
        function lihatBody(id, rfoId) {
            $('#bodyRfoId').text(rfoId);
            $('#bodyFrame').attr('src', '<?= site_url('MDMaintenance/email-log/body/') ?>' + parseInt(id, 10));
            $('#bodyModal').removeClass('hidden').addClass('flex');
        }

        function tutupBody() {
            $('#bodyFrame').attr('src', 'about:blank');
            $('#bodyModal').addClass('hidden').removeClass('flex');
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
                    columns: ':visible:not(.no-export)',
                    format: {
                        body: function(data) {
                            const tmp = document.createElement('div');
                            tmp.innerHTML = data;
                            return tmp.textContent.trim();
                        }
                    }
                }
            };

            const table = $('#emailTable').DataTable({
                pageLength: 10,
                lengthMenu: [
                    [10, 15, 25, 50, -1],
                    [10, 15, 25, 50, "Semua"]
                ],
                order: [],
                columnDefs: [{
                    targets: [0],
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
                            title: 'Riwayat Email Maintenance',
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, button, config);
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            text: '<i class="ti ti-file-text"></i> Export CSV',
                            title: 'Riwayat Email Maintenance',
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            text: '<i class="ti ti-file-spreadsheet"></i> Export Excel',
                            title: 'Riwayat Email Maintenance',
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            text: '<i class="ti ti-file-type-pdf"></i> Export PDF',
                            title: 'Riwayat Email Maintenance',
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
                    emptyTable: "Belum ada email Maintenance yang dikirim",
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
            table.draw();

            $('#lengthArea').append($('.dataTables_length'));
            $('#exportArea').append($('.dt-buttons'));
            $('#emailFooter').append($('.dataTables_info'), $('.dataTables_paginate'));

            // ====== FILTER DROPDOWN (cocok persis pada kolom) ======
            function filterColumn(col, value) {
                const re = value ? '^' + $.fn.dataTable.util.escapeRegex(value) + '$' : '';
                table.column(col).search(re, true, false).draw();
            }
            $('#filterMtId').on('change', function() {
                filterColumn(2, this.value);
            });
            $('#filterStatus').on('change', function() {
                filterColumn(9, this.value);
            });

            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });
            $('#customSearch').on('keypress', function(e) {
                if (e.which === 13) table.search(this.value).draw();
            });

            // Reset: kosongkan semua filter & pencarian
            $('#btnReset').on('click', function() {
                $('#filterStatus, #filterMtId, #customSearch').val('');
                table.search('').columns().search('').draw();
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