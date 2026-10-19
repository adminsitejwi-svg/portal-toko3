<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('store.png') ?>">
    <title>Edit Data Pelanggan</title>
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

        /* Dark mode: kotak form tetap putih, jadi judul dan teks ID dibuat hitam agar terbaca */
        html.dark .form-container h2 {
            color: #000 !important;
        }

        html.dark .form-container input[readonly] {
            color: #000 !important;
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
                    <a href="<?= site_url('MDMaintenance') ?>" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-tool"></i></span>
                        <span>Maintenance</span>
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
                        <li><a href="<?= site_url('ReportSat') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Tiket FO Loss</a></li>
                        <li><a href="<?= site_url('ResiPengiriman') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Resi Pengiriman</a></li>
                    </ul>
                </li>
                <li class="hasmenu">
                    <a href="#" onclick="toggleSub(this);return false;" class="pc-link active flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-category"></i></span>
                        <span class="flex-1">All NOC</span>
                        <i data-feather="chevron-right" class="arrow w-4 h-4 transition-transform"></i>
                    </a>
                    <ul class="submenu bg-black/20">
                        <li><a href="<?= site_url('DataCenter') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Center</a></li>
                        <li><a href="<?= site_url('Interkoneksi') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Interkoneksi</a></li>
                        <li><a href="<?= site_url('DevicesBackbone') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Devices Backbone</a></li>
                        <li><a href="<?= site_url('Bts') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">BTS</a></li>
                        <li><a href="<?= site_url('PelangganNoc') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Pelanggan</a></li>
                        <li><a href="<?= site_url('VendorNoc') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Vendor</a></li>
                    </ul>
                </li>

                <!-- ====== MENU AKTIF: INVENTORY KANTOR ====== -->


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
            <div class="form-container">
                <h2>Edit Data Pelanggan</h2>
                <form action="<?= site_url('PelangganNoc/update') ?>" method="POST" enctype="multipart/form-data" id="reportForm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= esc($row['id'], 'attr') ?>">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 mt-5">
                        <div class="form-group md:col-span-2">
                            <label>Nama Pelanggan <span style="color:red">*</span></label>
                            <input type="text" name="nama_pelanggan" id="r_nama_pelanggan" maxlength="255" required placeholder="Masukkan Nama Pelanggan" value="<?= esc(old('nama_pelanggan', $row['nama_pelanggan'] ?? '', false)) ?>" class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                        </div>
                        <?php
                        // Isian awal blok vendor & perangkat: input terakhir (gagal validasi) > data yang diedit > kosong
                        $adaOld     = old('nama_pelanggan', null, false) !== null;
                        $vendorAwal = $adaOld
                            ? array_values((array) old('vendor', [], false))
                            : array_map(fn ($v) => [
                                'vendor_id'  => $v['id'],
                                'ip_address' => $v['ip_address'],
                                'vlan_id'    => $v['vlan_id'],
                                'tanpa_vlan' => $v['vlan_id'] === null,
                                'kapasitas'  => $v['kapasitas'],
                            ], $row['vendor_detail'] ?? []);
                        $perangkatAwal = $adaOld ? array_values((array) old('perangkat', [], false)) : ($row['perangkat'] ?? []);
                        ?>
                        <div class="form-group md:col-span-2">
                            <label>BTS <span style="color:red">*</span></label>
                            <select name="bts_id" id="r_bts_id" required onchange="gantiBts()" class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                <option value="">-- Pilih BTS --</option>
                                <?php foreach ($bts as $b) : ?>
                                    <option value="<?= $b['id'] ?>" <?= (string) old('bts_id', $row['bts_id'] ?? '', false) === (string) $b['id'] ? 'selected' : '' ?>><?= esc($b['nama_bts']) ?> (<?= esc($b['ip_address']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>IP Address BTS</label>
                            <input type="text" id="a_ip_address" readonly class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] outline-none" style="background:#f1f5f9">
                        </div>
                        <div class="form-group">
                            <label>Kapasitas BTS</label>
                            <input type="text" id="a_kapasitas" readonly class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] outline-none" style="background:#f1f5f9">
                        </div>
                        <div class="form-group md:col-span-2">
                            <label>Vlan ID BTS</label>
                            <input type="text" id="a_vlan_id" readonly class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] outline-none" style="background:#f1f5f9">
                        </div>
                        <div class="form-group md:col-span-2">
                            <label>Vendor <span style="color:red">*</span></label>
                            <!-- satu blok per vendor: vendor dipilih dari vendor milik BTS (datanya tampil otomatis, tidak bisa diubah) + IP Address, VLAN & Kapasitas pelanggan -->
                            <div id="vendorRows" class="space-y-3"></div>
                            <template id="vendorTemplate">
                                <div class="isian-row border border-[#e3e8ee] rounded-lg p-3">
                                    <div class="flex items-center gap-2">
                                        <select name="vendor[__i__][vendor_id]" data-isi="vendor_id" onchange="isiVendor(this.closest('.isian-row'))" class="flex-1 w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none"></select>
                                        <button type="button" class="w-[46px] h-[46px] shrink-0 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 text-base" title="Hapus vendor" onclick="hapusBaris(this)"><i class="ti ti-trash"></i></button>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mt-2">
                                        <div>
                                            <div class="text-xs text-gray-500 mb-1">Nama PIC</div>
                                            <input type="text" data-kolom="nama_pic" readonly placeholder="Otomatis dari Data Vendor" class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] outline-none" style="background:#f1f5f9">
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500 mb-1">Telpon PIC</div>
                                            <input type="text" data-kolom="telp_pic" readonly placeholder="Otomatis dari Data Vendor" class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] outline-none" style="background:#f1f5f9">
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500 mb-1">Email PIC</div>
                                            <input type="text" data-kolom="email_pic" readonly placeholder="Otomatis dari Data Vendor" class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] outline-none" style="background:#f1f5f9">
                                        </div>
                                        <div class="md:col-span-3">
                                            <div class="text-xs text-gray-500 mb-1">Alamat</div>
                                            <textarea data-kolom="alamat" rows="2" readonly placeholder="Otomatis dari Data Vendor" class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] outline-none" style="background:#f1f5f9"></textarea>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-2 mt-2">
                                        <div>
                                            <div class="text-xs text-gray-500 mb-1">IP Address <span style="color:red">*</span></div>
                                            <input type="text" name="vendor[__i__][ip_address]" data-isi="ip_address" maxlength="45" placeholder="Contoh: 10.10.1.1" class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500 mb-1">Vlan ID <span style="color:red">*</span></div>
                                            <input type="number" name="vendor[__i__][vlan_id]" data-isi="vlan_id" min="1" max="4094" step="1" placeholder="1 - 4094" class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none disabled:bg-gray-100">
                                            <label class="inline-flex items-center gap-2 mt-2 text-sm font-normal">
                                                <input type="checkbox" name="vendor[__i__][tanpa_vlan]" data-isi="tanpa_vlan" value="1" onchange="toggleVlan(this.closest('.isian-row'))"> Tanpa VLAN
                                            </label>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500 mb-1">Kapasitas <span style="color:red">*</span></div>
                                            <input type="text" name="vendor[__i__][kapasitas]" data-isi="kapasitas" maxlength="100" placeholder="Contoh: 20 Mbps" class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <!-- w-auto/h-auto: tombol kecil, tidak ikut gaya tombol besar form -->
                            <button type="button" onclick="addVendorRow()" class="w-auto h-auto mt-2 px-3 py-2 text-sm font-medium bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg gap-1">
                                <i class="ti ti-plus"></i> Tambah Vendor
                            </button>
                        </div>
                        <div class="form-group md:col-span-2">
                            <label>Detail Perangkat Pelanggan di Lokasi</label>
                            <!-- satu baris per perangkat, diketik manual -->
                            <div id="perangkatRows" class="space-y-3"></div>
                            <template id="perangkatTemplate">
                                <div class="isian-row flex items-end gap-2 border border-[#e3e8ee] rounded-lg p-3">
                                    <div class="flex-1 grid grid-cols-1 md:grid-cols-5 gap-2">
                                        <div>
                                            <div class="text-xs text-gray-500 mb-1">Merk</div>
                                            <input type="text" name="perangkat[__i__][merk]" data-isi="merk" maxlength="255" placeholder="Contoh: Mikrotik" class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500 mb-1">Jenis</div>
                                            <input type="text" name="perangkat[__i__][jenis]" data-isi="jenis" maxlength="255" placeholder="Contoh: Router" class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500 mb-1">Type</div>
                                            <input type="text" name="perangkat[__i__][type]" data-isi="type" maxlength="255" placeholder="Contoh: RB750" class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500 mb-1">SN</div>
                                            <input type="text" name="perangkat[__i__][sn]" data-isi="sn" maxlength="255" placeholder="Serial Number" class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500 mb-1">Jumlah</div>
                                            <input type="number" name="perangkat[__i__][jumlah]" data-isi="jumlah" min="1" step="1" placeholder="1" class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                        </div>
                                    </div>
                                    <button type="button" class="w-[46px] h-[46px] shrink-0 rounded-lg bg-red-50 hover:bg-red-100 text-red-600 text-base" title="Hapus perangkat" onclick="hapusBaris(this)"><i class="ti ti-trash"></i></button>
                                </div>
                            </template>
                            <button type="button" onclick="addPerangkatRow()" class="w-auto h-auto mt-2 px-3 py-2 text-sm font-medium bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg gap-1">
                                <i class="ti ti-plus"></i> Tambah Perangkat
                            </button>
                        </div>
                        <div class="form-group md:col-span-2">
                            <label>File Topologi <span class="text-gray-400 font-normal">(PDF, PNG, JPG, Word - maks. 10 MB)</span></label>
                            <input type="file" name="file_topologi" id="r_file_topologi" accept=".pdf,.png,.jpg,.jpeg,.doc,.docx" class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                            <?php if (! empty($row['file_topologi'])) : ?>
                                <!-- saat edit: file yang sudah ada -->
                                <div class="mt-2 text-sm flex flex-wrap items-center gap-3">
                                    <span class="text-gray-500">File saat ini:</span>
                                    <a href="<?= site_url('PelangganNoc/file/' . $row['id']) ?>" target="_blank" rel="noopener" class="text-blue-600 hover:underline inline-flex items-center gap-1"><i class="ti ti-paperclip"></i> <?= esc($row['file_topologi_asli'] ?: $row['file_topologi']) ?></a>
                                    <label class="inline-flex items-center gap-2 mb-0 text-sm font-normal text-red-600">
                                        <input type="checkbox" name="hapus_topologi" value="1"> Hapus file
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Pilih file baru untuk mengganti file saat ini.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="d-flex">
                        <button type="submit">Update</button>
                        <button type="button" class="btn-back" onclick="window.location.href='<?= site_url('PelangganNoc') ?>'">Kembali</button>
                    </div>
                </form>
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
        // Data BTS beserta vendornya (id => data), untuk isian otomatis & pilihan vendor
        const BTS = <?= json_encode((object) array_column($bts, null, 'id'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        const MAX_FILE = 10 * 1024 * 1024;
        const EXT_FILE = ['pdf', 'png', 'jpg', 'jpeg', 'doc', 'docx'];
        let nomorBaris = 0;

        const kosong = v => v === null || v === undefined || String(v).trim() === '';
        const setVal = (id, v) => document.getElementById(id).value = kosong(v) ? '' : v;

        // Vendor milik BTS yang dipilih (id vendor => data vendor)
        function vendorBts() {
            const b = BTS[document.getElementById('r_bts_id').value];
            const daftar = {};
            (b ? b.vendor_detail : []).forEach(v => daftar[v.id] = v);
            return daftar;
        }

        // Pilih BTS -> IP Address, Kapasitas, Vlan ID BTS terisi otomatis
        function isiBts() {
            const b = BTS[document.getElementById('r_bts_id').value];
            setVal('a_ip_address', b && b.ip_address);
            setVal('a_kapasitas', b && b.kapasitas);
            setVal('a_vlan_id', b && (kosong(b.vlan_id) ? 'Tanpa VLAN' : b.vlan_id));
        }

        // Ganti BTS -> pilihan vendor di tiap blok ikut diganti dengan vendor BTS itu
        function gantiBts() {
            isiBts();
            document.querySelectorAll('#vendorRows .isian-row').forEach(isiOpsiVendor);
        }

        // Isi pilihan vendor (vendor BTS) di satu blok; vendor terpilih dipertahankan jika masih ada
        function isiOpsiVendor(row) {
            const pilih = row.querySelector('[data-isi="vendor_id"]');
            const nilai = pilih.dataset.awal ?? pilih.value;
            const daftar = vendorBts();
            const adaBts = document.getElementById('r_bts_id').value !== '';

            pilih.replaceChildren(new Option(adaBts ? '-- Pilih Vendor --' : '-- Pilih BTS terlebih dahulu --', ''));
            Object.values(daftar).forEach(v => pilih.add(new Option(v.nama_vendor, v.id)));
            pilih.value = daftar[nilai] ? nilai : '';
            delete pilih.dataset.awal;
            isiVendor(row);
        }

        // Isi data vendor (tidak bisa diubah) sesuai vendor yang dipilih di blok itu
        function isiVendor(row) {
            const v = vendorBts()[row.querySelector('[data-isi="vendor_id"]').value] || {};
            row.querySelectorAll('[data-kolom]').forEach(el => el.value = v[el.dataset.kolom] || '');
        }

        // Tanpa VLAN dicentang -> Vlan ID di blok itu dikosongkan & dikunci
        function toggleVlan(row) {
            const vlan = row.querySelector('[data-isi="vlan_id"]');
            vlan.disabled = row.querySelector('[data-isi="tanpa_vlan"]').checked;
            if (vlan.disabled) vlan.value = '';
        }

        // Salin template blok/baris, beri nomor unik pada name="...[__i__]..." lalu isi nilainya
        function tambahBaris(idTemplate, idWadah, isi) {
            const row = document.getElementById(idTemplate).content.firstElementChild.cloneNode(true);
            const n = nomorBaris++;
            row.querySelectorAll('[name]').forEach(el => el.name = el.name.replace('__i__', n));
            row.querySelectorAll('input[data-isi]').forEach(el => {
                if (el.type === 'checkbox') el.checked = !!isi[el.dataset.isi] && isi[el.dataset.isi] !== '0';
                else el.value = isi[el.dataset.isi] ?? '';
            });
            document.getElementById(idWadah).appendChild(row);
            return row;
        }

        function addVendorRow(isi) {
            isi = isi || {};
            const row = tambahBaris('vendorTemplate', 'vendorRows', isi);
            row.querySelector('[data-isi="vendor_id"]').dataset.awal = isi.vendor_id ?? '';
            isiOpsiVendor(row);
            toggleVlan(row);
        }

        function addPerangkatRow(isi) {
            tambahBaris('perangkatTemplate', 'perangkatRows', isi || {});
        }

        // Minimal tersisa 1 blok/baris (yang terakhir hanya dikosongkan)
        function hapusBaris(btn) {
            const row = btn.closest('.isian-row');
            if (row.parentElement.children.length > 1) return row.remove();

            row.querySelectorAll('input[data-isi]').forEach(el => el.type === 'checkbox' ? el.checked = false : el.value = '');
            const pilih = row.querySelector('select[data-isi]');
            if (pilih) {
                pilih.value = '';
                isiVendor(row);
                toggleVlan(row);
            }
        }

        // Isian awal (minimal 1 blok vendor & 1 baris perangkat)
        const vendorAwal = <?= json_encode(array_values($vendorAwal), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        const perangkatAwal = <?= json_encode(array_values($perangkatAwal), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        isiBts();
        (vendorAwal.length ? vendorAwal : [{}]).forEach(v => addVendorRow(v));
        (perangkatAwal.length ? perangkatAwal : [{}]).forEach(p => addPerangkatRow(p));

        // ====== VALIDASI SEBELUM SUBMIT ======
        document.getElementById('reportForm').addEventListener('submit', function(e) {
            const peringatan = (title, text) => {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title,
                    text,
                    confirmButtonColor: '#185a82'
                });
            };

            if (document.getElementById('r_nama_pelanggan').value.trim() === '' || document.getElementById('r_bts_id').value === '') {
                return peringatan('Form Belum Lengkap', 'Nama Pelanggan dan BTS wajib diisi.');
            }

            // tiap blok vendor wajib: vendor, IP Address, Kapasitas, dan Vlan ID (atau Tanpa VLAN)
            const vendorKurang = [...document.querySelectorAll('#vendorRows .isian-row')].some(row => {
                const isi = k => row.querySelector('[data-isi="' + k + '"]').value.trim();
                return isi('vendor_id') === '' || isi('ip_address') === '' || isi('kapasitas') === '' ||
                    (!row.querySelector('[data-isi="tanpa_vlan"]').checked && isi('vlan_id') === '');
            });
            if (vendorKurang) {
                return peringatan('Form Belum Lengkap', 'Tiap vendor wajib diisi: pilih vendor, IP Address, Kapasitas, dan Vlan ID atau centang Tanpa VLAN.');
            }

            const file = document.getElementById('r_file_topologi').files[0];
            if (file) {
                const ext = file.name.split('.').pop().toLowerCase();
                if (!EXT_FILE.includes(ext)) return peringatan('File Tidak Didukung', 'File Topologi harus PDF, PNG, JPG, atau Word (DOC/DOCX).');
                if (file.size > MAX_FILE) return peringatan('File Terlalu Besar', 'File Topologi maksimal 10 MB.');
            }
        });
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