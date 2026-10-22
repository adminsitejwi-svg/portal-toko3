<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-rumahwifi.png') ?>?v=2">
    <title>View Maintenance</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

        /* ===== FORM ===== */
        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .1);
            width: 100%;
            max-width: 1000px;
            margin: 20px auto;
        }

        h2 {
            text-align: center;
            color: #185a82;
            font-size: 42px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 35px;
            padding-bottom: 15px;
            border-bottom: 3px solid #185a82;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #185a82;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[readonly] {
            background: #f1f5f9;
        }

        button {
            width: 100%;
            height: 55px;
            border: none;
            border-radius: 10px;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
            transition: all .3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        button:hover {
            transform: translateY(-2px);
        }

        .d-flex {
            display: flex;
            gap: 20px;
            margin-top: 25px;
        }

        .d-flex button {
            flex: 1;
        }

        button[type="submit"] {
            background: linear-gradient(135deg, #185a82, #2196f3);
            color: white;
            box-shadow: 0 4px 15px rgba(24, 90, 130, 0.3);
        }

        button[type="submit"]:hover {
            box-shadow: 0 8px 25px rgba(24, 90, 130, 0.4);
        }

        .btn-back {
            background: linear-gradient(135deg, #6b7280, #4b5563);
            color: white;
            box-shadow: 0 4px 15px rgba(75, 85, 99, 0.3);
        }

        .btn-back:hover {
            box-shadow: 0 8px 25px rgba(75, 85, 99, 0.4);
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

                <!-- ====== MENU AKTIF: INVENTORY KANTOR ====== -->
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

        <?php
        // Format tanggal Indonesia untuk surat Maintenance
        $hari  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $tsTanggal   = strtotime($mt['tanggal']);
        $hariTanggal = $hari[date('w', $tsTanggal)] . ', ' . date('j', $tsTanggal) . ' ' . $bulan[(int) date('n', $tsTanggal)] . ' ' . date('Y', $tsTanggal);
        $fmtWaktu    = static fn ($t) => $t ? substr($t, 0, 5) . ' WIB' : '-';

        // Perusahaan dengan kop surat khusus (Kop Surat JWI.pdf, Kop Surat Flazznet.pdf);
        // perusahaan lain tetap memakai kop gelombang
        $kop = [
            'PT JURAGAN WIFI INDONESIA'   => 'jwi',
            'PT GENEZIS MITRA TECHNOLOGY' => 'flazz',
            'PT NORLEC TELEKOMUNIKASI INDONESIA' => 'norlec',
            'PT RTIGA GLOBAL MEDIA'       => 'rtiga',
        ][$mt['perusahaan']] ?? null;
        ?>

        <style>
            /* ===== Surat Maintenance (tampilan seperti dokumen, sama dengan surat RFO) ===== */
            .rfo-toolbar {
                max-width: 794px;
                margin: 0 auto 16px;
            }

            /* Tata letak mengikuti public/Dokumentasi/Format RFO.jpg */
            .rfo-paper {
                position: relative;
                max-width: 794px;
                margin: 0 auto;
                background: #fff;
                box-shadow: 0 1px 6px rgba(0, 0, 0, .15);
                color: #000;
                font-family: Calibri, Carlito, "Segoe UI", Arial, sans-serif;
                font-size: 15px;
                line-height: 1.35;
                min-height: 1123px;
            }

            /* Logo perusahaan kiri atas, Maintenance ID kanan atas */
            /* Gelombang kop surat atas & bawah, warna mengikuti logo perusahaan */
            .rfo-wave {
                display: block;
                width: 100%;
                height: 140px;
            }

            .rfo-wave-bottom {
                position: absolute;
                left: 0;
                bottom: 0;
                transform: rotate(180deg);
            }

            .rfo-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 24px;
                height: 100px;
                padding: 0 24px;
                overflow: hidden;
            }

            /* File logo punya banyak ruang kosong, jadi gambar dibuat lebih
               tinggi dari header dan kelebihannya terpotong (logo lebih besar) */
            .rfo-head img {
                width: 45%;
                height: 150px;
                flex-shrink: 0;
                object-fit: contain;
                object-position: left center;
            }


            /* Ruang bawah agar isi tidak menimpa gelombang bawah */
            /* Isi surat berada di tengah kertas (teks tetap rata kiri) */
            .rfo-body {
                width: fit-content;
                max-width: calc(100% - 80px);
                margin: 0 auto;
                padding: 44px 0 180px;
            }

            .rfo-id {
                margin-left: auto;
                white-space: nowrap;
            }

            .rfo-greet {
                margin-bottom: 19px;
            }

            .rfo-text {
                white-space: pre-line;
            }

            .rfo-table {
                border-collapse: collapse;
                margin: 19px 0;
            }

            .rfo-table td {
                padding: 0;
                vertical-align: top;
            }

            .rfo-table td:first-child {
                width: 140px;
            }

            .rfo-table td:last-child {
                white-space: pre-line;
            }

            .rfo-table td.rfo-colon {
                padding-right: 4px;
            }

            .rfo-sign {
                margin-top: 58px;
            }

            .rfo-sign span {
                display: block;
            }

            /* Jarak antara salam dan nama divisi/perusahaan */
            .rfo-sign .rfo-sign-org {
                margin-top: 38px;
            }

            /* Logo perusahaan di bawah nama petugas NOC; file logo punya banyak ruang kosong,
               jadi bingkai memotong sisa ruang (ukuran per perusahaan di bawah) */
            .rfo-sign-logo {
                width: 150px;
                height: 40px;
                margin-top: 12px;
                overflow: hidden;
            }

            .rfo-sign-logo img {
                width: 100%;
                height: 100%;
                max-width: none;
                object-fit: contain;
                object-position: left center;
            }

            .rfo-sign .rfo-sign-logo + .rfo-sign-org {
                margin-top: 12px;
            }

            .rfo-paper--jwi .rfo-sign-logo {
                height: 29px;
            }

            .rfo-paper--jwi .rfo-sign-logo img {
                width: 160px;
                height: auto;
                margin: -27px 0 0 -4px;
            }

            .rfo-paper--flazz .rfo-sign-logo {
                height: 30px;
            }

            .rfo-paper--flazz .rfo-sign-logo img {
                width: 170px;
                height: auto;
                margin: -28px 0 0 -10px;
            }

            .rfo-paper--norlec .rfo-sign-logo {
                height: 25px;
            }

            .rfo-paper--norlec .rfo-sign-logo img {
                width: 169px;
                height: auto;
                margin: -11px 0 0 -7px;
            }

            .rfo-paper--rtiga .rfo-sign-logo {
                height: 46px;
            }

            .rfo-paper--rtiga .rfo-sign-logo img {
                width: 159px;
                height: auto;
                margin: -6px 0 0 -5px;
            }

            /* ===== Kop surat khusus per perusahaan (Kop Surat JWI.pdf, Kop Surat Flazznet.pdf) =====
               Nomor/Lamp/Perihal kiri atas, logo kanan atas, alamat kantor di bawah */
            .kop-head {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
            }

            .kop-meta {
                border-collapse: collapse;
                font-family: Arial, Helvetica, sans-serif;
                line-height: 1.2;
            }

            .kop-meta td {
                padding: 0;
                vertical-align: top;
            }

            .kop-meta td.rfo-colon {
                padding-right: 8px;
            }

            /* File logo punya banyak ruang kosong; bingkai memotong sisa ruang agar logo pas */
            .kop-logo {
                overflow: hidden;
                flex-shrink: 0;
            }

            .kop-logo img {
                max-width: none;
            }

            .rfo-paper--kop .rfo-body {
                width: auto;
                max-width: none;
                margin-right: 80px;
            }

            .rfo-paper--kop .rfo-table td:first-child {
                width: 170px;
                white-space: nowrap;
            }

            /* --- JWI: api oranye kanan bawah --- */
            .rfo-paper--jwi .kop-head {
                padding: 58px 14px 0 119px;
            }

            .rfo-paper--jwi .kop-meta {
                margin-top: 40px;
                font-size: 16px;
            }

            .rfo-paper--jwi .kop-meta td:first-child {
                width: 96px;
            }

            .rfo-paper--jwi .kop-logo {
                width: 320px;
                height: 62px;
            }

            .rfo-paper--jwi .kop-logo img {
                width: 342px;
                margin: -57px 0 0 -8px;
            }

            .rfo-paper--jwi .rfo-body {
                margin-left: 119px;
                padding: 40px 0 200px;
            }

            /* --- Flazznet (PT Genezis Mitra Technology): pita hijau di bawah --- */
            .rfo-paper--flazz .kop-head {
                padding: 46px 102px 0 90px;
            }

            .rfo-paper--flazz .kop-meta {
                margin-top: 54px;
                font-size: 15px;
            }

            .rfo-paper--flazz .kop-meta td:first-child {
                width: 93px;
            }

            .rfo-paper--flazz .kop-logo {
                width: 205px;
                height: 41px;
            }

            .rfo-paper--flazz .kop-logo img {
                width: 232px;
                margin: -38px 0 0 -14px;
            }

            .rfo-paper--flazz .rfo-body {
                margin-left: 90px;
                padding: 40px 0 170px;
            }

            .flazz-foot {
                position: absolute;
                left: 0;
                right: 0;
                bottom: 0;
                height: 125px;
                padding-top: 14px;
                background: #9BBB59;
                color: #000;
                text-align: center;
                font-family: Calibri, Carlito, "Segoe UI", Arial, sans-serif;
                font-size: 13px;
                line-height: 1.35;
            }

            .flazz-foot .flazz-pt {
                font-weight: 700;
                margin-bottom: 8px;
            }

            .flazz-foot .flazz-web {
                margin-top: 8px;
            }

            /* --- Sprintnet (PT Norlec Telekomunikasi Indonesia): logo S abu-biru kanan bawah --- */
            .rfo-paper--norlec .kop-head {
                padding: 47px 119px 0 94px;
            }

            .rfo-paper--norlec .kop-meta {
                margin-top: 54px;
                font-size: 15px;
            }

            .rfo-paper--norlec .kop-meta td:first-child {
                width: 93px;
            }

            .rfo-paper--norlec .kop-logo {
                width: 240px;
                height: 40px;
            }

            .rfo-paper--norlec .kop-logo img {
                width: 271px;
                margin: -17px 0 0 -11px;
            }

            .rfo-paper--norlec .rfo-body {
                margin-left: 94px;
                padding: 40px 0 200px;
            }

            /* Logo S (public/Logo-footer/LOGO-S.png) pudar & keabuan, terpotong tepi kanan & bawah kertas */
            .norlec-s {
                position: absolute;
                left: 0;
                right: 0;
                bottom: 0;
                height: 230px;
                overflow: hidden;
                pointer-events: none;
            }

            .norlec-s img {
                position: absolute;
                left: 295px;
                top: 0;
                width: 810px;
                max-width: none;
                filter: grayscale(.85);
                opacity: .5;
                mix-blend-mode: multiply;
            }

            .norlec-foot {
                position: absolute;
                left: 94px;
                bottom: 49px;
                font-family: Calibri, Carlito, "Segoe UI", Arial, sans-serif;
                font-size: 13.5px;
                line-height: 1.4;
                color: #000;
            }

            /* --- Rnet (PT Rtiga Global Media): logo kanan atas di atas garis abu tebal --- */
            .rfo-paper--rtiga .kop-head {
                flex-direction: column-reverse;
                align-items: stretch;
                padding: 20px 49px 0 46px;
            }

            .rfo-paper--rtiga .kop-head::before {
                content: "";
                position: absolute;
                top: 92px;
                left: 33px;
                right: 37px;
                height: 5px;
                background: linear-gradient(#6b6b6b, #4a4a4a);
            }

            .rfo-paper--rtiga .kop-meta {
                align-self: flex-start;
                margin-top: 46px;
                font-size: 15px;
            }

            .rfo-paper--rtiga .kop-meta td:first-child {
                width: 93px;
            }

            /* Hanya tanda "rnet" (tanpa tulisan RTIGA GLOBAL MEDIA) seperti pada kop asli */
            .rfo-paper--rtiga .kop-logo {
                align-self: flex-end;
                width: 190px;
                height: 58px;
            }

            .rfo-paper--rtiga .kop-logo img {
                width: 202px;
                margin: -8px 0 0 -6px;
            }

            .rfo-paper--rtiga .rfo-body {
                margin-left: 46px;
                margin-right: 46px;
                padding: 40px 0 170px;
            }

            .rtiga-foot {
                position: absolute;
                left: 46px;
                bottom: 56px;
                font-family: Calibri, Carlito, "Segoe UI", Arial, sans-serif;
                font-size: 15px;
                line-height: 1.45;
                color: #000;
            }

            .jwi-foot sup {
                font-size: 7px;
                line-height: 0;
            }

            /* Logo api (public/Logo-footer/LOGO-API.png) besar & pudar di kanan bawah,
               sebagian terpotong tepi kertas seperti pada kop asli */
            .jwi-flame {
                position: absolute;
                left: 0;
                right: 0;
                bottom: 0;
                height: 230px;
                overflow: hidden;
                pointer-events: none;
            }

            .jwi-flame img {
                position: absolute;
                left: 330px;
                top: -61px;
                width: 820px;
                max-width: none;
                opacity: .65;
                mix-blend-mode: multiply;
            }

            .jwi-foot {
                position: absolute;
                left: 58px;
                bottom: 26px;
                display: flex;
                gap: 32px;
                font-family: Arial, Helvetica, sans-serif;
                font-size: 10.5px;
                font-weight: 700;
                line-height: 1.3;
                color: #111;
            }

            .jwi-foot .jwi-pt {
                font-size: 12.5px;
                margin-bottom: 2px;
            }

            .jwi-foot .jwi-office {
                font-size: 13px;
            }

            .jwi-foot .jwi-col2 {
                padding-top: 22px;
            }

            /* ===== PRINT: hanya kertas surat yang dicetak, pas 1 halaman A4 =====
               Margin halaman 0 agar gelombang kop menempel ke tepi atas & bawah kertas */
            @page {
                size: A4;
                margin: 0;
            }

            @media print {
                body * {
                    visibility: hidden;
                }

                .rfo-paper,
                .rfo-paper * {
                    visibility: visible;
                }

                /* Elemen lain tersembunyi tetap makan tempat; potong agar tidak ada halaman ke-2 */
                html,
                body {
                    background: #fff !important;
                    width: 210mm;
                    height: 297mm;
                    margin: 0 !important;
                    padding: 0 !important;
                    overflow: hidden !important;
                }

                .rfo-paper {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 210mm;
                    height: 297mm;
                    min-height: 0;
                    max-width: none;
                    margin: 0;
                    overflow: hidden;
                    box-shadow: none;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }

                .rfo-paper * {
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
            }
        </style>

        <div class="p-6">
            <!-- TOOLBAR (tidak ikut tercetak) -->
            <div class="rfo-toolbar flex flex-wrap items-center justify-between gap-3">
                <h1 class="rfo-title whitespace-nowrap">View Maintenance</h1>
                <div class="flex flex-wrap items-center gap-2">
                    <a href="<?= site_url('MDMaintenance') ?>" class="rfo-btn rfo-btn-gray"><i class="ti ti-arrow-left"></i> Kembali</a>
                    <a href="<?= site_url('MDMaintenance/edit/' . $mt['id']) ?>" class="rfo-btn rfo-btn-amber"><i class="ti ti-edit"></i> Edit</a>
                    <a href="#" role="button" onclick="window.print(); return false;" class="rfo-btn rfo-btn-blue"><i class="ti ti-printer"></i> Print</a>
                </div>
            </div>

            <!-- KERTAS SURAT MAINTENANCE -->
            <div class="rfo-paper<?= $kop ? ' rfo-paper--kop rfo-paper--' . $kop : '' ?>">
                <?php if ($kop) : ?>
                <div class="kop-head">
                    <table class="kop-meta">
                        <tr>
                            <td>Nomor</td>
                            <td class="rfo-colon">:</td>
                            <td><?= esc($mt['maintenance_id']) ?></td>
                        </tr>
                        <tr>
                            <td>Lamp</td>
                            <td class="rfo-colon">:</td>
                            <td>-</td>
                        </tr>
                        <tr>
                            <td>Perihal</td>
                            <td class="rfo-colon">:</td>
                            <td>Maintenance</td>
                        </tr>
                    </table>
                    <?php if (!empty($mt['logo'])) : ?>
                        <div class="kop-logo"><img src="<?= base_url($mt['logo']) ?>" alt="Logo <?= esc($mt['perusahaan']) ?>"></div>
                    <?php endif; ?>
                </div>
                <?php else : ?>
                <svg class="rfo-wave rfo-wave-top" viewBox="0 0 800 160" preserveAspectRatio="none" aria-hidden="true">
                    <defs>
                        <linearGradient id="rfoWaveTop" x1="0" x2="1" y1="0" y2="0">
                            <stop offset="0" stop-color="<?= esc($warnaKop[0], 'attr') ?>" />
                            <stop offset="1" stop-color="<?= esc($warnaKop[1], 'attr') ?>" />
                        </linearGradient>
                    </defs>
                    <path d="M0,0 H660 C480,34 250,56 0,66 Z" fill="<?= esc($warnaKop[2], 'attr') ?>" />
                    <path d="M0,80 C250,74 470,30 800,22 V62 C560,66 330,128 0,132 Z" fill="url(#rfoWaveTop)" />
                    <path d="M360,148 C540,112 680,86 800,84 V98 C660,104 520,134 360,148 Z" fill="url(#rfoWaveTop)" />
                    <path d="M590,152 C680,128 750,116 800,114 V136 C740,138 670,144 590,152 Z" fill="<?= esc($warnaKop[2], 'attr') ?>" />
                </svg>

                <div class="rfo-head">
                    <?php if (!empty($mt['logo'])) : ?>
                        <img src="<?= base_url($mt['logo']) ?>" alt="Logo <?= esc($mt['perusahaan']) ?>">
                    <?php endif; ?>
                    <div class="rfo-id"><?= esc($mt['maintenance_id']) ?></div>
                </div>
                <?php endif; ?>

                <div class="rfo-body">
                    <div class="rfo-greet">Kepada Pelanggan Yth,</div>

                    <div class="rfo-text"><?= esc($pembuka) ?></div>

                    <table class="rfo-table">
                        <tr>
                            <td>Hari/Tanggal</td>
                            <td class="rfo-colon">:</td>
                            <td><?= esc($hariTanggal) ?></td>
                        </tr>
                        <tr>
                            <td>Kegiatan</td>
                            <td class="rfo-colon">:</td>
                            <td><?= esc($mt['kegiatan']) ?></td>
                        </tr>
                        <tr>
                            <td>Impact</td>
                            <td class="rfo-colon">:</td>
                            <td><?= esc($mt['impact']) ?></td>
                        </tr>
                        <tr>
                            <td>Start Waktu</td>
                            <td class="rfo-colon">:</td>
                            <td><?= esc($fmtWaktu($mt['waktu_mulai'])) ?></td>
                        </tr>
                        <tr>
                            <td>Closed Waktu</td>
                            <td class="rfo-colon">:</td>
                            <td><?= esc($fmtWaktu($mt['waktu_selesai'])) ?></td>
                        </tr>
                        <tr>
                            <td>Estimasi</td>
                            <td class="rfo-colon">:</td>
                            <td><?= esc($mt['estimasi']) ?></td>
                        </tr>
                    </table>

                    <div class="rfo-text"><?= esc($penutup) ?></div>

                    <div class="rfo-sign">
                        <span>Salam,</span>
                        <?php if (!empty($petugas)) : ?>
                            <span><?= esc(implode(' / ', $petugas)) ?></span>
                        <?php endif; ?>
                        <?php if (!empty($mt['logo'])) : ?>
                            <div class="rfo-sign-logo"><img src="<?= base_url($mt['logo']) ?>" alt="Logo <?= esc($mt['perusahaan']) ?>"></div>
                        <?php endif; ?>
                        <span class="rfo-sign-org">Network Operation Center</span>
                        <span><?= esc($namaTtd) ?></span>
                    </div>
                </div>

                <?php if ($kop === 'flazz') : ?>
                <!-- Pita hijau + alamat kantor (Kop Surat Flazznet.pdf) -->
                <div class="flazz-foot">
                    <div class="flazz-pt">PT Genezis Mitra Technology</div>
                    <div>MTH Square, Jl. Letjen M.T. Haryono No.Kav 10 office Lt. 1 No. 0125, RT.6/RW.12, Bidara Cina,</div>
                    <div>Kecamatan Jatinegara, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13330</div>
                    <div class="flazz-web">www.flazznet.id</div>
                </div>
                <?php elseif ($kop === 'norlec') : ?>
                <!-- Logo S kanan bawah + alamat kantor (norlec.pdf) -->
                <div class="norlec-s" aria-hidden="true"><img src="<?= base_url('Logo-footer/LOGO-S.png') ?>" alt=""></div>
                <div class="norlec-foot">
                    <div>PT Juragan Wifi Indonesia</div>
                    <div>MTH Square Lantai 2 No.0219</div>
                    <div>Jl. MT Haryono Kav 10 Bidara Cina Jatinegara</div>
                    <div>Jakarta Timur DKI Jakarta 13330</div>
                </div>
                <?php elseif ($kop === 'rtiga') : ?>
                <!-- Alamat kantor kiri bawah (kop surat rnet.pdf) -->
                <div class="rtiga-foot">
                    <div>PT Juragan Wifi Indonesia (RNET)</div>
                    <div>MTH Square Lantai 3A No.12</div>
                    <div>Jl. MT Haryono Kav 10 Bidara Cina Jatinegara</div>
                    <div>Jakarta Timur DKI Jakarta 13330</div>
                </div>
                <?php elseif ($kop === 'jwi') : ?>
                <!-- Api kanan bawah + alamat kantor (Kop Surat JWI.pdf) -->
                <div class="jwi-flame" aria-hidden="true"><img src="<?= base_url('Logo-footer/LOGO-API.png') ?>" alt=""></div>
                <div class="jwi-foot">
                    <div>
                        <div class="jwi-pt">PT. JURAGAN WIFI INDONESIA</div>
                        <div class="jwi-office">Head Office</div>
                        <div>MTH Square 2<sup>nd</sup> Floor No. 219</div>
                        <div>Jl. MT. Haryono Kav 10, Jakarta Timur 13330</div>
                        <div>Telp +62 21 5011 2225 - www.speednet.id</div>
                    </div>
                    <div class="jwi-col2">
                        <div class="jwi-office">Branch Cirebon</div>
                        <div>Jl. Pangeran Walangsungsang, Sukadana,</div>
                        <div>Kec. Pabuaran, Kab. Cirebon, Jawa Barat 45188</div>
                        <div>Telp +62 21 5011 2225 - www.speednet.id</div>
                    </div>
                </div>
                <?php else : ?>
                <svg class="rfo-wave rfo-wave-bottom" viewBox="0 0 800 160" preserveAspectRatio="none" aria-hidden="true">
                    <defs>
                        <linearGradient id="rfoWaveBottom" x1="0" x2="1" y1="0" y2="0">
                            <stop offset="0" stop-color="<?= esc($warnaKop[0], 'attr') ?>" />
                            <stop offset="1" stop-color="<?= esc($warnaKop[1], 'attr') ?>" />
                        </linearGradient>
                    </defs>
                    <path d="M0,0 H660 C480,34 250,56 0,66 Z" fill="<?= esc($warnaKop[2], 'attr') ?>" />
                    <path d="M0,80 C250,74 470,30 800,22 V62 C560,66 330,128 0,132 Z" fill="url(#rfoWaveBottom)" />
                    <path d="M360,148 C540,112 680,86 800,84 V98 C660,104 520,134 360,148 Z" fill="url(#rfoWaveBottom)" />
                    <path d="M590,152 C680,128 750,116 800,114 V136 C740,138 670,144 590,152 Z" fill="<?= esc($warnaKop[2], 'attr') ?>" />
                </svg>
                <?php endif; ?>
            </div>
        </div>
    </div>

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
        // PENGHALANG KOSMETIK SAJA — bukan security, mudah dilewati
        document.addEventListener('contextmenu', e => e.preventDefault());
        document.addEventListener('keydown', e => {
            if (e.key === 'F12') e.preventDefault();
            if (e.ctrlKey && e.shiftKey && ['I', 'J', 'C'].includes(e.key.toUpperCase())) e.preventDefault();
            if (e.ctrlKey && e.key.toUpperCase() === 'U') e.preventDefault();
        });
    </script>
    <?php if (!session()->get('logged_in')) : ?>
        <script>
            window.location.href = "<?= base_url('/login') ?>";
        </script>
    <?php endif; ?>
</body>

</html>