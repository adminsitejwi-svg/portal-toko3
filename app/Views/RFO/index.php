<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-rumahwifi.png') ?>?v=2">
    <title>RFO</title>
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

        /* pop up Kirim Email RFO: scrollbar berhenti sebelum lengkungan sudut kotak (radius 18px dari rfo.css) */
        #emailBox::-webkit-scrollbar-track {
            margin: 18px 0;
            background: transparent;
        }

        #emailBox::-webkit-scrollbar-thumb {
            border-radius: 8px;
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
                    <a href="<?= site_url('RFO') ?>" class="pc-link active flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
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

            // Badge per status RFO: [kelas warna, ikon]
            $statusBadge = [
                'Resolved'    => ['rb-green', 'ti-circle-check-filled'],
                'Monitoring'  => ['rb-blue', 'ti-eye'],
                'On Progress' => ['rb-amber', 'ti-loader-2'],
                'Pending'     => ['rb-red', 'ti-clock'],
            ];
            // Badge per status email: [kelas warna, ikon]
            $emailBadge = [
                'Terkirim'      => ['rb-green', 'ti-send'],
                'Gagal'         => ['rb-red', 'ti-alert-circle'],
                'Belum Dikirim' => ['rb-blue', 'ti-mail-forward'],
            ];
            // Warna & ikon impact ditebak dari isi teks (impact berupa teks bebas)
            $impactBadge = static function (string $teks): array {
                $s = strtolower($teks);
                if (preg_match('/site\s*down|power|listrik|pln/', $s)) return ['rb-amber', 'ti-bolt'];
                if (preg_match('/cut|loss|putus|drop|rusak/', $s)) return ['rb-red', 'ti-alert-triangle-filled'];
                if (preg_match('/link|down|lambat|latency/', $s)) return ['rb-blue', 'ti-link'];
                return ['rb-purple', 'ti-building'];
            };

            // Ringkasan untuk kartu statistik
            $total    = count($rfo);
            $resolved = count(array_filter($rfo, static fn ($r) => $r['status'] === 'Resolved'));
            $terkirim = 0;
            $belum    = 0;
            foreach ($rfo as $r) {
                $st = $statusEmail[$r['id']]['status'] ?? 'Belum Dikirim';
                if ($st === 'Terkirim') $terkirim++;
                if ($st === 'Belum Dikirim') $belum++;
            }
            $persenResolved = $total ? round($resolved / $total * 100) : 0;

            ?>

            <!-- JUDUL -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic"><i class="ti ti-file-alert"></i></span>
                    <h1 class="rfo-title">RFO (Reason For Outage)</h1>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="<?= site_url('RFO/email-log') ?>" class="rfo-btn rfo-btn-green">
                        <i class="ti ti-mail"></i> Riwayat Email
                    </a>
                    <a href="<?= site_url('RFO/create') ?>" class="rfo-btn rfo-btn-blue">
                        <i class="ti ti-plus"></i> Tambah RFO
                    </a>
                </div>
            </div>

            <!-- KARTU STATISTIK -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-6">
                <div class="rfo-stat rfo-stat-blue">
                    <span class="rfo-stat-ic"><i class="ti ti-file-text"></i></span>
                    <div>
                        <div class="rfo-stat-label">Total RFO</div>
                        <div class="rfo-stat-value"><?= $total ?></div>
                        <div class="rfo-stat-note">Data keseluruhan</div>
                    </div>
                    <i class="ti ti-chart-bar rfo-stat-chart"></i>
                </div>
                <div class="rfo-stat rfo-stat-green">
                    <span class="rfo-stat-ic"><i class="ti ti-check"></i></span>
                    <div>
                        <div class="rfo-stat-label">Resolved</div>
                        <div class="rfo-stat-value"><?= $resolved ?></div>
                        <div class="rfo-stat-note"><?= $persenResolved ?>% dari total</div>
                    </div>
                    <i class="ti ti-chart-bar rfo-stat-chart"></i>
                </div>
                <div class="rfo-stat rfo-stat-amber">
                    <span class="rfo-stat-ic"><i class="ti ti-hourglass-high"></i></span>
                    <div>
                        <div class="rfo-stat-label">Belum Dikirim</div>
                        <div class="rfo-stat-value"><?= $belum ?></div>
                        <div class="rfo-stat-note">Menunggu kirim</div>
                    </div>
                    <i class="ti ti-chart-bar rfo-stat-chart"></i>
                </div>
                <div class="rfo-stat rfo-stat-purple">
                    <span class="rfo-stat-ic"><i class="ti ti-send"></i></span>
                    <div>
                        <div class="rfo-stat-label">Terkirim</div>
                        <div class="rfo-stat-value"><?= $terkirim ?></div>
                        <div class="rfo-stat-note">Email telah dikirim</div>
                    </div>
                    <i class="ti ti-chart-bar rfo-stat-chart"></i>
                </div>
            </div>

            <!-- FILTER + TABEL -->
            <div class="rfo-panel">
                <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
                    <div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <div id="lengthArea"></div>
                            <label class="rfo-field">
                                <i class="ti ti-building-skyscraper" style="color:#2f6bff"></i>
                                <select id="filterPerusahaan" class="filter-select is-wide" title="Filter Perusahaan">
                                    <option value="">Semua Perusahaan</option>
                                    <?php foreach ($perusahaan as $nama) : ?>
                                        <option value="<?= esc($nama) ?>"><?= esc($nama) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                            <label class="rfo-field">
                                <i class="ti ti-mail-filled" style="color:#2f6bff"></i>
                                <select id="filterStatusEmail" class="filter-select" title="Filter Status Email">
                                    <option value="">Semua Status Email</option>
                                    <option value="Terkirim">Terkirim</option>
                                    <option value="Gagal">Gagal</option>
                                    <option value="Belum Dikirim">Belum Dikirim</option>
                                </select>
                            </label>
                            <label class="rfo-field">
                                <i class="ti ti-tag" style="color:#6d5dfc"></i>
                                <select id="filterRfoId" class="filter-select" title="Filter RFO ID">
                                    <option value="">Semua RFO ID</option>
                                    <?php foreach ($rfo as $row) : ?>
                                        <option value="<?= esc($row['rfo_id']) ?>"><?= esc($row['rfo_id']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <div class="rfo-search">
                            <i class="ti ti-search"></i>
                            <input type="text" id="customSearch" placeholder="Cari RFO ID, perusahaan, penyebab, impact..." />
                        </div>
                        <button type="button" id="btnReset" class="rfo-ghost-btn"><i class="ti ti-refresh"></i> Reset</button>
                        <div id="exportArea"></div>
                    </div>
                </div>

                <!-- TABLE -->
                <div class="table-scroll">
                    <table id="rfoTable" class="rfo-table display nowrap" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Aksi</th>
                                <th>RFO ID</th>
                                <th>Perusahaan</th>
                                <th>Waktu Gangguan</th>
                                <th>Waktu Selesai</th>
                                <th>Penyebab / Jenis Gangguan</th>
                                <th>Impact</th>
                                <th>Action / Tindakan</th>
                                <th>Status</th>
                                <th>Status Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($rfo)) : ?>
                                <?php $no = 1; ?>
                                <?php foreach ($rfo as $row) : ?>
                                    <?php
                                    [$impKelas, $impIkon] = $impactBadge($row['impact']);
                                    [$stKelas, $stIkon]   = $statusBadge[$row['status']] ?? ['rb-blue', 'ti-info-circle'];
                                    // Status kirim email terakhir; data-search dipakai filter (cocok persis)
                                    $mail       = $statusEmail[$row['id']] ?? null;
                                    $mailStatus = $mail['status'] ?? 'Belum Dikirim';
                                    [$mKelas, $mIkon] = $emailBadge[$mailStatus] ?? $emailBadge['Belum Dikirim'];
                                    ?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td>
                                            <a href="<?= site_url('RFO/view/' . $row['id']) ?>" class="act act-view" title="Lihat / Print">
                                                <i class="ti ti-eye"></i> View
                                            </a>
                                            <a href="<?= site_url('RFO/edit/' . $row['id']) ?>" class="act act-edit" title="Edit">
                                                <i class="ti ti-pencil"></i> Edit
                                            </a>
                                            <button type="button" onclick="confirmDelete(<?= $row['id'] ?>)" class="act act-del" title="Hapus">
                                                <i class="ti ti-trash"></i> Hapus
                                            </button>
                                            <button type="button"
                                                onclick="openEmailModal(<?= $row['id'] ?>, '<?= esc($row['rfo_id'], 'js') ?>', '<?= esc(\App\Models\RFOModel::EMAIL_NOC[$row['perusahaan']] ?? '', 'js') ?>')"
                                                class="act act-email" title="Kirim Email">
                                                <i class="ti ti-mail"></i> Email
                                            </button>
                                        </td>
                                        <td class="rfo-id"><?= esc($row['rfo_id']); ?></td>
                                        <td data-search="<?= esc($row['perusahaan']) ?>">
                                            <span class="cell-ic"><i class="ti ti-building-skyscraper" style="color:#2f6bff"></i><?= esc($row['perusahaan']); ?></span>
                                        </td>
                                        <td data-order="<?= esc($row['waktu_gangguan'] ?? '') ?>">
                                            <span class="cell-ic"><i class="ti ti-calendar" style="color:#2f6bff"></i><?= $fmt($row['waktu_gangguan']); ?></span>
                                        </td>
                                        <td data-order="<?= esc($row['waktu_selesai'] ?? '') ?>">
                                            <span class="cell-ic"><i class="ti ti-calendar" style="color:#ef4444"></i><?= $fmt($row['waktu_selesai']); ?></span>
                                        </td>
                                        <td><span class="cell-clip" title="<?= esc($row['penyebab'], 'attr') ?>"><?= esc($row['penyebab']); ?></span></td>
                                        <td><span class="rb rb-wrap <?= $impKelas ?>"><i class="ti <?= $impIkon ?>"></i><?= esc($row['impact']); ?></span></td>
                                        <td>
                                            <span class="cell-ic"><i class="ti ti-tool" style="color:#2f6bff"></i><span class="cell-clip" title="<?= esc($row['action'], 'attr') ?>"><?= esc($row['action']); ?></span></span>
                                        </td>
                                        <td data-search="<?= esc($row['status']) ?>"><span class="rb <?= $stKelas ?>"><i class="ti <?= $stIkon ?>"></i><?= esc($row['status']); ?></span></td>
                                        <td data-search="<?= esc($mailStatus) ?>">
                                            <span class="rb <?= $mKelas ?>"><i class="ti <?= $mIkon ?>"></i><?= esc($mailStatus) ?></span>
                                            <?php if ($mail) : ?>
                                                <div class="rb-sub" title="Kepada: <?= esc($mail['kepada'], 'attr') ?>"><?= $fmt($mail['created_at']) ?></div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div id="rfoFooter" class="rfo-footer flex flex-wrap items-center justify-between gap-3 mt-4"></div>
            </div>
        </div>
    </div>

    <!-- MODAL KIRIM EMAIL (akun pengirim dipilih di dropdown "Dari") -->
    <div id="emailModal" class="fixed inset-0 z-[2000] hidden items-center justify-center bg-black/50 p-4">
        <div id="emailBox" class="w-full max-w-2xl max-h-[95vh] overflow-y-auto rounded-lg bg-white shadow-xl">
            <form action="<?= site_url('RFO/email') ?>" method="POST" id="emailForm">
                <?= csrf_field() ?>
                <div id="emailIds"></div>
                <div class="flex items-center justify-between border-b border-[#e3e8ee] px-5 py-3">
                    <h5 class="font-medium text-base"><i class="ti ti-mail"></i> Kirim Email RFO</h5>
                    <button type="button" onclick="closeEmailModal()" class="text-xl leading-none text-gray-500 hover:text-gray-700">&times;</button>
                </div>
                <div class="space-y-4 px-5 py-4 text-sm">
                    <div>
                        <label class="mb-1 block font-medium">Data RFO</label>
                        <div id="emailRfoList" class="rounded-lg bg-[#f1f5f9] px-3 py-2 text-[#3b4754] break-words"></div>
                    </div>
                    <div>
                        <label class="mb-1 block font-medium">Dari <span style="color:red">*</span></label>
                        <select name="dari" id="emailDari" required class="w-full rounded-lg border border-[#e3e8ee] bg-white px-3 py-2 outline-none focus:border-primary-500">
                            <?php foreach ($akunEmail as $alamat => $label) : ?>
                                <option value="<?= esc($alamat) ?>"><?= esc($label) ?> &lt;<?= esc($alamat) ?>&gt;</option>
                            <?php endforeach; ?>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Otomatis dipilih sesuai perusahaan RFO, bisa diganti.</p>
                    </div>
                    <div>
                        <label class="mb-1 block font-medium">Kepada <span style="color:red">*</span></label>
                        <input type="text" name="kepada" id="emailKepada" required placeholder="pelanggan@contoh.com, lainnya@contoh.com" class="w-full rounded-lg border border-[#e3e8ee] px-3 py-2 outline-none focus:border-primary-500">
                        <p class="mt-1 text-xs text-gray-500">Lebih dari satu alamat dipisah koma.</p>
                    </div>
                    <div>
                        <label class="mb-1 block font-medium">CC</label>
                        <input type="text" name="cc" placeholder="opsional" class="w-full rounded-lg border border-[#e3e8ee] px-3 py-2 outline-none focus:border-primary-500">
                    </div>
                    <div>
                        <label class="mb-1 block font-medium">Subjek</label>
                        <input type="text" name="subjek" placeholder="Kosongkan: otomatis &quot;RFO [RFO ID] - [Perusahaan]&quot;" class="w-full rounded-lg border border-[#e3e8ee] px-3 py-2 outline-none focus:border-primary-500">
                    </div>
                    <div>
                        <label class="mb-1 block font-medium">Isi Email</label>
                        <!-- Hanya preview (sandbox, tanpa script): isi email tidak bisa diubah, sama dengan yang dikirim -->
                        <iframe id="emailBodyFrame" title="Isi Email RFO" sandbox="" class="h-[300px] w-full rounded-lg border border-[#e3e8ee] bg-white"></iframe>
                        <p class="mt-1 text-xs text-gray-500">Isi email otomatis sesuai perusahaan RFO dan tidak bisa diubah.</p>
                    </div>
                    <div>
                        <label class="mb-1 block font-medium">Lampiran</label>
                        <div class="flex items-center justify-between gap-2 rounded-lg border border-[#e3e8ee] bg-[#f1f5f9] px-3 py-2">
                            <span class="flex items-center gap-1.5 text-[#3b4754] break-all"><i class="ti ti-file-type-pdf text-red-600 text-lg"></i> <span id="emailPdfNama"></span></span>
                            <button type="button" id="btnCekPdf" onclick="toggleCekPdf()" class="shrink-0 inline-flex items-center gap-1 rounded-md bg-blue-600 px-2.5 py-1 text-xs text-white hover:bg-blue-700"><i class="ti ti-eye"></i> Cek PDF</button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Dibuat otomatis dari surat RFO (sama dengan halaman View). PDF terkunci, tidak bisa diubah.</p>
                        <iframe id="emailPdfFrame" title="Preview PDF RFO" class="mt-2 hidden h-[60vh] w-full rounded-lg border border-[#e3e8ee]"></iframe>
                    </div>
                </div>
                <div class="flex justify-end gap-2 border-t border-[#e3e8ee] px-5 py-3">
                    <button type="button" onclick="closeEmailModal()" class="rounded-lg bg-gray-500 px-4 py-2 text-sm text-white hover:bg-gray-600">Batal</button>
                    <button type="submit" id="emailSubmit" class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-600 px-4 py-2 text-sm text-white hover:bg-emerald-700"><i class="ti ti-send"></i> Kirim</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ====== KIRIM EMAIL (satu data per klik tombol email) ======
        let emailRfoId = null;

        // dariDefault: e-mail NOC perusahaan RFO; dipilih bila ada di dropdown, selain itu akun pertama
        function openEmailModal(id, rfoId, dariDefault) {
            emailRfoId = parseInt(id, 10);
            const dari = $('#emailDari');
            dari.val(dari.find('option[value="' + dariDefault + '"]').length ? dariDefault : dari.find('option:first').val());
            $('#emailIds').html('<input type="hidden" name="ids[]" value="' + emailRfoId + '">');
            $('#emailRfoList').text(rfoId);
            // Nama file sama dengan RfoPdf::namaFile(): karakter selain huruf/angka/- jadi "-"
            $('#emailPdfNama').text(rfoId.replace(/[^A-Za-z0-9\-]+/g, '-') + '.pdf');
            tutupCekPdf();
            $('#emailBodyFrame').attr('src', '<?= site_url('RFO/email-preview/') ?>' + emailRfoId);
            $('#emailModal').removeClass('hidden').addClass('flex');
            $('#emailKepada').trigger('focus');
        }

        function closeEmailModal() {
            tutupCekPdf();
            $('#emailBodyFrame').attr('src', 'about:blank');
            $('#emailModal').addClass('hidden').removeClass('flex');
        }

        // Preview PDF lampiran di dalam form (dimuat saat tombol Cek PDF diklik)
        function toggleCekPdf() {
            const frame = $('#emailPdfFrame');
            if (!frame.hasClass('hidden')) return tutupCekPdf();
            frame.attr('src', '<?= site_url('RFO/pdf/') ?>' + emailRfoId + '#toolbar=0').removeClass('hidden');
            $('#emailBox').removeClass('max-w-2xl').addClass('max-w-3xl');
            $('#btnCekPdf').html('<i class="ti ti-eye-off"></i> Tutup PDF');
        }

        function tutupCekPdf() {
            $('#emailPdfFrame').addClass('hidden').attr('src', 'about:blank');
            $('#emailBox').removeClass('max-w-3xl').addClass('max-w-2xl');
            $('#btnCekPdf').html('<i class="ti ti-eye"></i> Cek PDF');
        }

        $(document).on('submit', '#emailForm', function() {
            $('#emailSubmit').prop('disabled', true).html('<i class="ti ti-loader"></i> Mengirim...');
        });

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
                    window.location.href = "<?= site_url('RFO/delete/') ?>" + id;
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

            const table = $('#rfoTable').DataTable({
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
                            title: 'Data RFO (Reason For Outage)',
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.copyHtml5.action.call(this, e, dt, button, config);
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            text: '<i class="ti ti-file-text"></i> Export CSV',
                            title: 'Data RFO (Reason For Outage)',
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.csvHtml5.action.call(this, e, dt, button, config);
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            text: '<i class="ti ti-file-spreadsheet"></i> Export Excel',
                            title: 'Data RFO (Reason For Outage)',
                            ...exportConfig,
                            action: function(e, dt, button, config) {
                                if (isTableEmpty(dt)) return showEmptyExportAlert();
                                $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, button, config);
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            text: '<i class="ti ti-file-type-pdf"></i> Export PDF',
                            title: 'Data RFO (Reason For Outage)',
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
                    emptyTable: "Data RFO belum tersedia",
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
            $('#rfoFooter').append($('.dataTables_info'), $('.dataTables_paginate'));

            // ====== FILTER DROPDOWN (cocok persis pada kolom) ======
            function filterColumn(col, value) {
                const re = value ? '^' + $.fn.dataTable.util.escapeRegex(value) + '$' : '';
                table.column(col).search(re, true, false).draw();
            }
            $('#filterRfoId').on('change', function() {
                filterColumn(2, this.value);
            });
            $('#filterPerusahaan').on('change', function() {
                filterColumn(3, this.value);
            });
            $('#filterStatusEmail').on('change', function() {
                filterColumn(10, this.value);
            });

            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
            });
            $('#customSearch').on('keypress', function(e) {
                if (e.which === 13) table.search(this.value).draw();
            });

            // Reset: kosongkan semua filter & pencarian
            $('#btnReset').on('click', function() {
                $('#filterPerusahaan, #filterStatusEmail, #filterRfoId, #customSearch').val('');
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