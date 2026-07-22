<?php

/**
 * @var array $lawson         Data toko yang sedang diedit
 * @var array $pemilik_projek
 * @var array $dc
 * @var array $jenis
 * @var array $perangkat
 * @var array $type_perangkat
 * @var array $nomor_inet   Data Penggunaan Nomor INET (sudah di-join sampai nama vendor)
 * @var array $simcard      Data Penggunaan Simcard (sudah di-join sampai nama vendor)
 * @var array $vpn          Master tujuan koneksi VPN
 */
?>
<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <title>Toko Lawson</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="<?= base_url('store.png') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

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

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        .page-wrapper {
            max-width: 1280px;
            margin: 0 auto;
        }

        .page-header {
            background: linear-gradient(135deg, #185a82 0%, #0f3d5c 100%);
            color: white;
            padding: 20px 30px;
            border-radius: 10px 10px 0 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .page-header h2 {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: .3px;
        }

        .form-card {
            background: #fff;
            border-radius: 0 0 10px 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .08);
            padding: 30px;
        }

        .section-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: #185a82;
            border-left: 3px solid #185a82;
            padding-left: 10px;
            margin: 28px 0 16px;
        }

        .section-title:first-child {
            margin-top: 0;
        }

        .section-note {
            font-size: 12px;
            color: #6b7280;
            margin: -10px 0 16px 13px;
            font-style: italic;
        }

        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
        }

        .col-full {
            grid-column: 1 / -1;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        label .req {
            color: #e53e3e;
            margin-left: 2px;
        }

        label .auto {
            color: #04a9f5;
            font-weight: 500;
            font-size: 11px;
            margin-left: 4px;
        }

        input[type="text"],
        input[type="date"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 9px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            color: #1f2937;
            background: #fff;
            transition: border-color .2s, box-shadow .2s;
        }

        input:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #185a82;
            box-shadow: 0 0 0 3px rgba(24, 90, 130, .12);
        }

        input[readonly],
        input:disabled {
            background: #f3f4f6;
            color: #6b7280;
            cursor: not-allowed;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .vendor-box {
            border: 1px solid #dbeafe;
            background: #f8fbff;
            border-radius: 8px;
            padding: 18px;
            margin-top: 4px;
        }

        .vendor-box .grid-3 {
            margin-top: 0;
        }

        .vendor-box-title {
            font-size: 12px;
            font-weight: 700;
            color: #185a82;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .empty-hint {
            font-size: 13px;
            color: #9ca3af;
            padding: 10px 4px;
        }

        .map-section {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            overflow: hidden;
        }

        .map-inputs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            padding: 14px;
            background: #f9fafb;
            border-bottom: 1px solid #e5e7eb;
        }

        .map-status {
            padding: 6px 14px;
            background: #f0f9ff;
            border-bottom: 1px solid #bae6fd;
            font-size: 12px;
            color: #0369a1;
            display: flex;
            align-items: center;
            gap: 6px;
            min-height: 30px;
        }

        .map-status .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #94a3b8;
            flex-shrink: 0;
            transition: background .3s;
        }

        .map-status.found .dot {
            background: #22c55e;
        }

        .map-status.error .dot {
            background: #ef4444;
        }

        #map {
            height: 340px;
            width: 100%;
        }

        .upload-zone {
            border: 2px dashed #d1d5db;
            border-radius: 8px;
            padding: 24px;
            text-align: center;
            cursor: pointer;
            transition: border-color .2s, background .2s;
            position: relative;
        }

        .upload-zone:hover,
        .upload-zone.drag-over {
            border-color: #185a82;
            background: #f0f8ff;
        }

        .upload-zone input[type="file"] {
            position: absolute;
            inset: 0;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
        }

        .upload-icon {
            font-size: 32px;
            margin-bottom: 8px;
        }

        .upload-text {
            font-size: 14px;
            color: #374151;
            font-weight: 600;
        }

        .upload-hint {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 4px;
        }

        .upload-preview {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 6px;
            margin-top: 10px;
        }

        .upload-preview .file-icon {
            font-size: 20px;
        }

        .upload-preview .file-name {
            font-size: 13px;
            font-weight: 600;
            color: #166534;
            flex: 1;
            word-break: break-all;
        }

        .upload-preview .file-size {
            font-size: 12px;
            color: #6b7280;
        }

        .upload-preview .remove-file {
            cursor: pointer;
            color: #dc2626;
            font-size: 16px;
            padding: 2px 6px;
            border-radius: 4px;
            transition: background .2s;
        }

        .upload-preview .remove-file:hover {
            background: #fee2e2;
        }

        .action-bar {
            display: flex;
            gap: 12px;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }

        .btn-save {
            flex: 1;
            padding: 12px;
            background: linear-gradient(135deg, #185a82, #0f3d5c);
            color: white;
            border: none;
            border-radius: 7px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: .3px;
            transition: opacity .2s, transform .1s;
        }

        .btn-save:hover {
            opacity: .9;
            transform: translateY(-1px);
        }

        .btn-back {
            padding: 12px 24px;
            background: #6b7280;
            color: white;
            border: none;
            border-radius: 7px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: background .2s;
        }

        .btn-back:hover {
            background: #4b5563;
        }

        @media (max-width: 900px) {
            .grid-3 {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 640px) {

            .grid-2,
            .grid-3 {
                grid-template-columns: 1fr;
            }

            .map-inputs {
                grid-template-columns: 1fr;
            }

            .form-card {
                padding: 18px;
            }
        }

        .brand-text {
            font-size: 18px;
        }
    </style>
    <style>
        .existing-file {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            margin-bottom: 4px;
            font-size: 12px;
            color: #1d4ed8;
        }

        .swal-image-popup {
            border-radius: 10px !important;
            overflow: hidden !important;
            padding: 0 !important;
        }

        .swal-image-close {
            color: #fff !important;
            background: rgba(0, 0, 0, .5) !important;
            border-radius: 50% !important;
            width: 32px !important;
            height: 32px !important;
            font-size: 18px !important;
            position: absolute !important;
            top: 10px !important;
            right: 10px !important;
            z-index: 10 !important;
        }

        .swal-image-close:hover {
            background: rgba(0, 0, 0, .8) !important;
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
                    <a href="#" onclick="toggleSub(this);return false;" class="pc-link active flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
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
                <li class="hasmenu">
                    <a href="#" onclick="toggleSub(this);return false;" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-report-medical"></i></span>
                        <span class="flex-1">Report NOC</span>
                        <i data-feather="chevron-right" class="arrow w-4 h-4 transition-transform"></i>
                    </a>
                    <ul class="submenu bg-black/20">
                        <li><a href="<?= site_url('RipotRetail') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white <?= ($filterMode ?? '') === 'down' ? 'active-store' : '' ?>">Task On Progress</a></li>
                        <li><a href="<?= site_url('RipotRetail/progress') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white <?= ($filterMode ?? '') === 'progress' ? 'active-store' : '' ?>">Task Done</a></li>
                        <li><a href="<?= site_url('RipotActive') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Aktivasi Retail</a></li>


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
            <div class="page-wrapper">
                <div class="page-header">
                    <div>
                        <h2>Edit Data Toko Lawson</h2>
                        <div style="font-size:13px;opacity:.75">ID: <?= $lawson['id'] ?> &bull; <?= esc($lawson['nama_lawson']) ?></div>
                    </div>
                </div>

                <div class="form-card">
                    <form action="<?= site_url('Lawson/update') ?>" method="POST" id="FormMidi" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $lawson['id'] ?>">

                        <!-- ═══ A. INFORMASI TOKO ═══ -->
                        <div class="section-title">DATA TOKO</div>
                        <div class="grid-3">
                            <div class="form-group">
                                <label>Pemilik Project <span class="req">*</span></label>
                                <select name="pemilik_projek_id" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    <option value="">— Pilih —</option>
                                    <?php foreach ($pemilik_projek as $row): ?>
                                        <option value="<?= $row['id'] ?>" <?= $lawson['pemilik_projek_id'] == $row['id'] ? 'selected' : '' ?>><?= esc($row['nama_pemilik']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Nama Lawson <span class="req">*</span></label>
                                <input type="text" name="nama_lawson" value="<?= esc($lawson['nama_lawson']) ?>" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="form-group">
                                <label>Kode Toko <span class="req">*</span></label>
                                <input type="text" name="kode_toko" value="<?= esc($lawson['kode_toko']) ?>" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="form-group">
                                <label>Alamat Toko <span class="req">*</span></label>
                                <input type="text" name="alamat_lawson" value="<?= esc($lawson['alamat_lawson']) ?>" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="form-group">
                                <label>DC <span class="req">*</span></label>
                                <select name="nama_dc_id" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    <option value="">— Pilih —</option>
                                    <?php foreach ($dc as $row): ?>
                                        <option value="<?= $row['id'] ?>" <?= $lawson['nama_dc_id'] == $row['id'] ? 'selected' : '' ?>><?= esc($row['nama_dc']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>PIC Toko <span class="req">*</span></label>
                                <input type="text" name="pic_toko" value="<?= esc($lawson['pic_toko']) ?>" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="form-group">
                                <label>Nomor HP PIC <span class="req">*</span></label>
                                <input type="text" name="nomor_hp_pic" id="nomor_hp_pic" value="<?= esc($lawson['nomor_hp_pic']) ?>"
                                    placeholder="Masukan Nomor HP PIC"
                                    inputmode="numeric" pattern="[0-9]*" maxlength="15"
                                    required
                                    class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="form-group">
                                <label>Tanggal Instalasi <span class="req">*</span></label>
                                <input type="date" name="tanggal_installasi" value="<?= esc($lawson['tanggal_installasi']) ?>" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="form-group">
                                <label>Tanggal Aktivasi <span class="req">*</span></label>
                                <input type="date" name="tanggal_aktivasi" value="<?= esc($lawson['tanggal_aktivasi']) ?>" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                            </div>


                        </div>
                        <div class="section-title">Titik Koordinat & Peta</div>
                        <div class="map-section">
                            <div class="map-inputs">
                                <?php
                                $lat = '';
                                $lng = '';
                                if (!empty($lawson['titik_koor_toko']) && strpos($lawson['titik_koor_toko'], ',') !== false) {
                                    [$lat, $lng] = array_map('trim', explode(',', $lawson['titik_koor_toko'], 2));
                                }
                                ?>
                                <div class="form-group">
                                    <label>Latitude <span class="req">*</span></label>
                                    <input type="text" id="latitude_input" value="<?= esc($lat) ?>" placeholder="-6.200000" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label>Longitude <span class="req">*</span></label>
                                    <input type="text" id="longitude_input" value="<?= esc($lng) ?>" placeholder="106.816666" autocomplete="off">
                                </div>
                                <input type="hidden" name="titik_koor_toko" id="titik_koor_toko" value="<?= esc($lawson['titik_koor_toko']) ?>">
                                <input type="hidden" name="map_toko" id="map_toko" value="<?= esc($lawson['map_toko']) ?>">
                            </div>
                            <div class="map-status" id="map_status">
                                <span class="dot"></span>
                                <span id="map_status_text">Masukkan Latitude &amp; Longitude untuk menampilkan peta</span>
                            </div>
                            <div id="map"></div>
                        </div>

                        <!-- ═══ B. DATA VENDOR ═══ -->
                        <div class="section-title">Data Vendor</div>
                        <div class="grid-3">
                            <div class="form-group">
                                <label>Media Koneksi <span class="req">*</span></label>
                                <select name="media_koneksi" id="media_koneksi_select" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    <option value="">— Pilih —</option>
                                    <option value="0" <?= (string)($lawson['media_koneksi'] ?? '') === '0' ? 'selected' : '' ?>>Non Cellular</option>
                                    <option value="1" <?= (string)($lawson['media_koneksi'] ?? '') === '1' ? 'selected' : '' ?>>Cellular</option>
                                </select>
                            </div>
                        </div>

                        <!-- ── Blok Non-Cellular ── -->
                        <div class="vendor-box mt-4" id="box_non_cellular" style="display:none">
                            <div class="vendor-box-title"><i class="ti ti-wifi"></i> Data Penggunaan Nomor INET</div>
                            <?php if (empty($nomor_inet)): ?>
                                <div class="empty-hint">Belum ada data penggunaan Nomor INET yang aktif. Tambahkan dulu lewat menu <b>Nomor Inet</b>.</div>
                            <?php else: ?>
                                <div class="grid-3">
                                    <div class="form-group">
                                        <label>Kode Layanan Vendor <span class="req">*</span></label>
                                        <select id="kode_layanan_select" name="nomor_inet_id" class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                            <option value="">— Pilih —</option>
                                            <?php foreach ($nomor_inet as $row): ?>
                                                <option value="<?= $row['usage_id'] ?>" <?= $lawson['nomor_inet_id'] == $row['usage_id'] ? 'selected' : '' ?>><?= esc($row['kode_layanan_vendor']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>ID Pelanggan / No.INET <span class="auto">(auto)</span></label>
                                        <input type="text" id="disp_id_pelanggan" readonly class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="form-group">
                                        <label>Password INET <span class="auto">(auto)</span></label>
                                        <div style="position:relative">
                                            <input type="password" id="disp_password_inet" readonly class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none" style="padding-right:44px">
                                            <button type="button" id="toggle_password_inet" title="Lihat / sembunyikan password"
                                                style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#6b7280;font-size:17px;padding:0;line-height:1">
                                                <i class="ti ti-eye" id="toggle_password_icon"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Vendor <span class="auto">(auto)</span></label>
                                        <input type="text" id="disp_nama_vendor_inet" readonly class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Layanan Vendor <span class="auto">(auto)</span></label>
                                        <input type="text" id="disp_nama_layanan" readonly class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="form-group">
                                        <label>Harga Layanan <span class="auto">(auto)</span></label>
                                        <input type="text" id="disp_harga_layanan" readonly class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="form-group">
                                        <label>Kecepatan / Bandwidth <span class="auto">(auto)</span></label>
                                        <input type="text" id="disp_kecepatan_bandwidth" readonly class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- ── Blok Cellular ── -->
                        <div class="vendor-box mt-4" id="box_cellular" style="display:none">
                            <div class="vendor-box-title"><i class="ti ti-antenna"></i> Data Penggunaan Simcard</div>
                            <?php if (empty($simcard)): ?>
                                <div class="empty-hint">Belum ada data penggunaan Simcard yang aktif. Tambahkan dulu lewat menu <b>Simcard</b>.</div>
                            <?php else: ?>
                                <div class="grid-3">
                                    <div class="form-group">
                                        <label>Kode Quota SimCard <span class="req">*</span></label>
                                        <select id="kode_quota_select" name="simcard_id" class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                            <option value="">— Pilih —</option>
                                            <?php foreach ($simcard as $row): ?>
                                                <option value="<?= $row['usage_id'] ?>" <?= $lawson['simcard_id'] == $row['usage_id'] ? 'selected' : '' ?>><?= esc($row['kode_quota_simcard']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Nomor MSISDN <span class="auto">(auto)</span></label>
                                        <input type="text" id="disp_nomor_msisdn" readonly class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="form-group">
                                        <label>Nomor ISSID / IMEI <span class="auto">(auto)</span></label>
                                        <input type="text" id="disp_nomor_imei" readonly class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Vendor <span class="auto">(auto)</span></label>
                                        <input type="text" id="disp_nama_vendor_simcard" readonly class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="form-group">
                                        <label>Nama Paket Data <span class="auto">(auto)</span></label>
                                        <input type="text" id="disp_nama_paket_data" readonly class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="form-group">
                                        <label>Harga Paket Quota <span class="auto">(auto)</span></label>
                                        <input type="text" id="disp_harga_quota" readonly class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    </div>
                                    <div class="form-group">
                                        <label>Isi Quota Internet Sim Card ( Gbps / Mbps / Kbps ) <span class="auto">(auto)</span></label>
                                        <input type="text" id="disp_quota_internet" readonly class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- ═══ C. DATA TEKNIS ═══ -->
                        <div class="section-title">Data Teknis</div>
                        <div class="grid-3">
                            <div class="form-group">
                                <label>Jenis Perangkat <span class="req">*</span></label>
                                <select name="jenis_perangkat_id" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    <option value="">— Pilih —</option>
                                    <?php foreach ($jenis as $row): ?>
                                        <option value="<?= $row['id'] ?>" <?= $lawson['jenis_perangkat_id'] == $row['id'] ? 'selected' : '' ?>><?= esc($row['jenis_perangkat']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Merk Perangkat <span class="req">*</span></label>
                                <select name="merk_perangkat_id" id="merk_perangkat_select" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    <option value="">— Pilih —</option>
                                    <?php foreach ($perangkat as $row): ?>
                                        <option value="<?= $row['id'] ?>" <?= $lawson['merk_perangkat_id'] == $row['id'] ? 'selected' : '' ?>><?= esc($row['merk_perangkat']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Type Perangkat <span class="req">*</span></label>
                                <select name="type_perangkat_id" id="type_perangkat_select" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    <option value="">— Pilih Merk dulu —</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Kategori IP Address <span class="req">*</span></label>
                                <select name="kategori_ip_address" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    <option value="">— Pilih —</option>
                                    <option value="Static" <?= $lawson['kategori_ip_address'] == 'Static' ? 'selected' : '' ?>>Static</option>
                                    <option value="Dynamic" <?= $lawson['kategori_ip_address'] == 'Dynamic' ? 'selected' : '' ?>>Dynamic</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Jenis IP Address <span class="req">*</span></label>
                                <select name="jenis_ip_address" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    <option value="">— Pilih —</option>
                                    <option value="Public" <?= $lawson['jenis_ip_address'] == 'Public' ? 'selected' : '' ?>>Public</option>
                                    <option value="Private" <?= $lawson['jenis_ip_address'] == 'Private' ? 'selected' : '' ?>>Private</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>IP Address Toko <span class="req">*</span></label>
                                <input type="text" name="ip_address_toko" id="ip_address_toko" value="<?= esc($lawson['ip_address_toko']) ?>"
                                    placeholder="Masukan IP Address Toko"
                                    inputmode="numeric" pattern="[0-9.]*" maxlength="15"
                                    required
                                    class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="form-group">
                                <label>Type Koneksi <span class="req">*</span></label>
                                <select name="type_koneksi" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    <option value="">— Pilih —</option>
                                    <option value="IPSEC" <?= $lawson['type_koneksi'] == 'IPSEC' ? 'selected' : '' ?>>IPSEC</option>
                                    <option value="L2TP" <?= $lawson['type_koneksi'] == 'L2TP' ? 'selected' : '' ?>>L2TP</option>
                                    <option value="PPTP" <?= $lawson['type_koneksi'] == 'PPTP' ? 'selected' : '' ?>>PPTP</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Kode Tujuan Koneksi (VPN) <span class="req">*</span></label>
                                <select name="vpn_id" id="vpn_select" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    <option value="">— Pilih —</option>
                                    <?php foreach ($vpn as $row): ?>
                                        <option value="<?= $row['id'] ?>" <?= $lawson['vpn_id'] == $row['id'] ? 'selected' : '' ?>><?= esc($row['kode_tujuan_koneksi']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Tujuan Koneksi <span class="auto">(auto)</span></label>
                                <input type="text" id="disp_tujuan_koneksi" readonly class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="form-group">
                                <label>IP Address Tujuan <span class="auto">(auto)</span></label>
                                <input type="text" id="disp_ip_address_tujuan" readonly class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="form-group">
                                <label>Serial Number Perangkat <span class="req">*</span></label>
                                <input type="text" name="serial_number_perangkat" value="<?= esc($lawson['serial_number_perangkat']) ?>" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                            </div>
                            <div class="form-group">
                                <label>Status <span class="req">*</span></label>
                                <select name="status" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    <option value="">— Pilih Status —</option>
                                    <option value="0" <?= (string)$lawson['status'] === '0' ? 'selected' : '' ?>>Aktif</option>
                                    <option value="1" <?= (string)$lawson['status'] === '1' ? 'selected' : '' ?>>Non Aktif</option>
                                </select>
                            </div>
                        </div>

                        <!-- ═══ MAP / TITIK KOOR ═══ -->


                        <!-- ═══ KETERANGAN & LAMPIRAN ═══ -->
                        <div class="section-title">Keterangan & Lampiran</div>
                        <div class="grid-2">
                            <div class="form-group">
                                <label>Keterangan <span class="req">*</span></label>
                                <textarea name="keterangan" rows="4" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none"><?= esc($lawson['keterangan']) ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>
                                    Upload Lampiran
                                    <small style="font-weight:400;color:#6b7280">(JPG / PNG / PDF • Maks. 10 file • 5 MB/file)</small>
                                </label>
                                <?php if (!empty($lawson['upload_lampiran'])): ?>
                                    <?php
                                    $decoded = json_decode($lawson['upload_lampiran'], true);
                                    $existingFiles = is_array($decoded) ? $decoded : [$lawson['upload_lampiran']];
                                    ?>
                                    <div id="existing_files_wrap">
                                        <div style="font-size:12px;font-weight:600;color:#374151;margin-bottom:6px">
                                            File Tersimpan (<?= count($existingFiles) ?> file):
                                        </div>
                                        <?php foreach ($existingFiles as $index => $f): ?>
                                            <?php
                                            $ext  = strtolower(pathinfo($f, PATHINFO_EXTENSION));
                                            $icon = $ext === 'pdf' ? '📄' : '🖼️';
                                            ?>
                                            <div class="existing-file" id="existing_file_<?= $index ?>">
                                                <span><?= $icon ?></span>
                                                <span style="flex:1;word-break:break-all;font-size:12px"><?= esc($f) ?></span>
                                                <button type="button"
                                                    onclick="previewFile('<?= esc($f) ?>', '<?= $ext ?>')"
                                                    style="background:#185a82;color:white;border:none;border-radius:4px;padding:3px 8px;font-size:11px;cursor:pointer;margin-right:4px">
                                                    👁 Lihat
                                                </button>
                                                <button type="button"
                                                    onclick="removeExistingFile(<?= $index ?>, '<?= esc($f) ?>')"
                                                    style="background:#dc2626;color:white;border:none;border-radius:4px;padding:3px 8px;font-size:11px;cursor:pointer">
                                                    🗑 Hapus
                                                </button>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                    <input type="hidden" name="existing_files" id="existing_files_input"
                                        value="<?= esc($lawson['upload_lampiran']) ?>">
                                <?php endif; ?>

                                <div class="upload-zone" id="upload_zone">
                                    <input type="file" name="file_input[]" id="file_input" accept=".jpg,.jpeg,.png,.pdf" multiple>
                                    <div class="upload-icon">📂</div>
                                    <div class="upload-text">Klik atau seret file ke sini</div>
                                    <div class="upload-hint">Format: JPG, PNG, PDF • Maks. 10 file • 5 MB per file</div>
                                </div>
                                <div id="file_counter" style="font-size:12px;color:#6b7280;margin-top:4px;display:none">
                                    <span id="file_count">0</span>/10 file dipilih
                                </div>
                                <div id="upload_preview"></div>
                            </div>
                        </div>

                        <div class="action-bar">
                            <button type="submit" class="btn-save">Simpan Perubahan</button>
                            <button type="button" class="btn-back" onclick="window.location.href='<?= site_url('Lawson') ?>'">Kembali</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // PENGHALANG KOSMETIK SAJA — bukan security, mudah dilewati
        document.addEventListener('contextmenu', e => e.preventDefault()); // klik kanan
        document.addEventListener('keydown', e => {
            if (e.key === 'F12') e.preventDefault(); // F12
            if (e.ctrlKey && e.shiftKey && ['I', 'J', 'C'].includes(e.key.toUpperCase())) e.preventDefault();
            if (e.ctrlKey && e.key.toUpperCase() === 'U') e.preventDefault(); // view-source
        });
        /* ════════════════════════════════════════════════
   0. HELPER — Format angka ke ribuan (10000 -> 10.000)
   ════════════════════════════════════════════════ */
        function formatRibuan(angka) {
            if (angka === null || angka === undefined || angka === '') return '';
            // ubah ke angka dulu (menangani "100000.00", "100000", 100000, dll)
            const floatVal = parseFloat(angka);
            if (isNaN(floatVal)) return '';
            // bulatkan ke integer (buang desimal), lalu format ribuan
            const intVal = Math.round(floatVal);
            return intVal.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }
        /* ════════════════════════════════════════════════
   9. RESTRICT INPUT — Nomor HP PIC & IP Address Toko
   ════════════════════════════════════════════════ */
        (function() {
            const hpInput = document.getElementById('nomor_hp_pic');
            const ipInput = document.getElementById('ip_address_toko');

            if (hpInput) {
                hpInput.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }

            if (ipInput) {
                ipInput.addEventListener('input', function() {
                    // izinkan angka dan titik saja (format IP)
                    this.value = this.value.replace(/[^0-9.]/g, '');
                });
            }
        })();
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
                if (sb.classList.contains('mobile-open') && !sb.contains(e.target) && !menuBtn) sb.classList.remove('mobile-open');
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
        /* ════════════════════════════════════════════════
           1. MEDIA KONEKSI — toggle blok Non-Cellular / Cellular
           ════════════════════════════════════════════════ */
        (function() {
            const mediaSel = document.getElementById('media_koneksi_select');
            const boxNonCell = document.getElementById('box_non_cellular');
            const boxCell = document.getElementById('box_cellular');
            const nomorInetSel = document.getElementById('kode_layanan_select');
            const simcardSel = document.getElementById('kode_quota_select');

            function setRequired(select, isRequired) {
                if (!select) return;
                if (isRequired) select.setAttribute('required', 'required');
                else select.removeAttribute('required');
            }

            function showNonCellular() {
                boxNonCell.style.display = 'block';
                boxCell.style.display = 'none';
                setRequired(nomorInetSel, true);
                setRequired(simcardSel, false);
                if (simcardSel) simcardSel.value = '';
                fillSimcard('');
            }

            function showCellular() {
                boxCell.style.display = 'block';
                boxNonCell.style.display = 'none';
                setRequired(simcardSel, true);
                setRequired(nomorInetSel, false);
                if (nomorInetSel) nomorInetSel.value = '';
                fillNomorInet('');
            }

            function hideAll() {
                boxNonCell.style.display = 'none';
                boxCell.style.display = 'none';
                setRequired(nomorInetSel, false);
                setRequired(simcardSel, false);
            }

            if (mediaSel) {
                mediaSel.addEventListener('change', function() {
                    if (mediaSel.value === '0') showNonCellular();
                    else if (mediaSel.value === '1') showCellular();
                    else hideAll();
                });
            }
        })();

        /* ════════════════════════════════════════════════
           2. AUTO-FILL — Data Penggunaan Nomor INET
           ════════════════════════════════════════════════ */
        const ALL_NOMOR_INET = <?= json_encode($nomor_inet ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

        function fillNomorInet(usageId) {
            const item = ALL_NOMOR_INET.find(x => String(x.usage_id) === String(usageId));
            // sinkronkan dropdown Kode Layanan (untuk init edit) + isi ID Pelanggan/No.INET otomatis
            const kodeLayananSelEl = document.getElementById('kode_layanan_select');
            if (kodeLayananSelEl && String(kodeLayananSelEl.value) !== String(usageId)) {
                kodeLayananSelEl.value = item ? String(usageId) : '';
            }
            document.getElementById('disp_id_pelanggan').value = item ? (item.id_pelanggan || item.nomor_inet || '') : '';
            document.getElementById('disp_password_inet').value = item ? (item.password_inet || '') : '';
            document.getElementById('disp_nama_vendor_inet').value = item ? (item.nama_vendor || '') : '';
            document.getElementById('disp_nama_layanan').value = item ? (item.nama_layanan || '') : '';
            document.getElementById('disp_harga_layanan').value = item ? formatRibuan(item.harga_layanan) : '';
            document.getElementById('disp_kecepatan_bandwidth').value = item ? (item.kecepatan_bandwidth || '') : '';
        }
        const kodeLayananSelectEl = document.getElementById('kode_layanan_select');
        if (kodeLayananSelectEl) kodeLayananSelectEl.addEventListener('change', e => fillNomorInet(e.target.value));
        // Eye toggle — lihat/sembunyikan Password INET
        const togglePwBtn = document.getElementById('toggle_password_inet');
        if (togglePwBtn) togglePwBtn.addEventListener('click', function() {
            const pw = document.getElementById('disp_password_inet');
            const icon = document.getElementById('toggle_password_icon');
            const show = pw.type === 'password';
            pw.type = show ? 'text' : 'password';
            icon.className = show ? 'ti ti-eye-off' : 'ti ti-eye';
        });

        /* ════════════════════════════════════════════════
           3. AUTO-FILL — Data Penggunaan Simcard
           ════════════════════════════════════════════════ */
        const ALL_SIMCARD = <?= json_encode($simcard ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

        function fillSimcard(usageId) {
            const item = ALL_SIMCARD.find(x => String(x.usage_id) === String(usageId));
            // sinkronkan dropdown Kode Quota (untuk init edit) + isi MSISDN otomatis
            const kodeQuotaSelEl = document.getElementById('kode_quota_select');
            if (kodeQuotaSelEl && String(kodeQuotaSelEl.value) !== String(usageId)) {
                kodeQuotaSelEl.value = item ? String(usageId) : '';
            }
            document.getElementById('disp_nomor_msisdn').value = item ? (item.nomor_msisdn || '') : '';
            document.getElementById('disp_nomor_imei').value = item ? (item.nomor_imei || '') : '';
            document.getElementById('disp_nama_vendor_simcard').value = item ? (item.nama_vendor || '') : '';
            document.getElementById('disp_nama_paket_data').value = item ? (item.nama_paket_data || '') : '';
            document.getElementById('disp_harga_quota').value = item ? formatRibuan(item.harga_quota) : '';
            document.getElementById('disp_quota_internet').value = item ? (item.quota_internet || '') : '';
        }
        const kodeQuotaSelectEl = document.getElementById('kode_quota_select');
        if (kodeQuotaSelectEl) kodeQuotaSelectEl.addEventListener('change', e => fillSimcard(e.target.value));

        /* ════════════════════════════════════════════════
           4. AUTO-FILL — VPN (Tujuan Koneksi)
           ════════════════════════════════════════════════ */
        const ALL_VPN = <?= json_encode($vpn ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

        function fillVpn(vpnId) {
            const item = ALL_VPN.find(x => String(x.id) === String(vpnId));
            document.getElementById('disp_tujuan_koneksi').value = item ? (item.tujuan_koneksi || '') : '';
            document.getElementById('disp_ip_address_tujuan').value = item ? (item.ip_address_tujuan || '') : '';
        }
        const vpnSelectEl = document.getElementById('vpn_select');
        if (vpnSelectEl) vpnSelectEl.addEventListener('change', e => fillVpn(e.target.value));

        /* ════════════════════════════════════════════════
           5. Merk Perangkat → Type Perangkat (cascading)
           ════════════════════════════════════════════════ */
        const ALL_TYPES = <?= json_encode(array_map(function ($t) {
                                return [
                                    'id'                 => $t['id'],
                                    'merek_perangkat_id' => $t['merek_perangkat_id'],
                                    'type_perangkat'     => $t['type_perangkat'],
                                ];
                            }, $type_perangkat ?? []), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

        (function() {
            const merkSel = document.getElementById('merk_perangkat_select');
            const typeSel = document.getElementById('type_perangkat_select');
            if (!merkSel || !typeSel) return;

            function refreshTypes(selectedTypeId) {
                const merkId = merkSel.value;
                typeSel.innerHTML = '';
                if (!merkId) {
                    typeSel.add(new Option('— Pilih Merk dulu —', ''));
                    return;
                }

                const matched = ALL_TYPES.filter(t => String(t.merek_perangkat_id) === String(merkId));
                if (matched.length === 0) {
                    typeSel.add(new Option('— Tidak ada type untuk merk ini —', ''));
                    return;
                }

                typeSel.add(new Option('— Pilih —', ''));
                matched.forEach(t => {
                    const o = new Option(t.type_perangkat, t.id);
                    if (selectedTypeId && String(selectedTypeId) === String(t.id)) o.selected = true;
                    typeSel.add(o);
                });
            }
            merkSel.addEventListener('change', () => refreshTypes());
            refreshTypes(<?= (int) ($lawson['type_perangkat_id'] ?? 0) ?>);
        })();

        /* ════════════════════════════════════════════════
           6. MAP – Auto-update ketika user ketik lat/lng
           ════════════════════════════════════════════════ */
        const map = L.map('map').setView([-6.200000, 106.816666], 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© <a href="https://openstreetmap.org">OpenStreetMap</a>'
        }).addTo(map);

        let marker = null;

        function setMapStatus(state, text) {
            const el = document.getElementById('map_status');
            el.className = 'map-status ' + state;
            document.getElementById('map_status_text').textContent = text;
        }

        function isValidCoord(lat, lng) {
            return !isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180;
        }

        function updateMap() {
            const latVal = document.getElementById('latitude_input').value.trim();
            const lngVal = document.getElementById('longitude_input').value.trim();
            if (!latVal || !lngVal) {
                setMapStatus('', 'Masukkan Latitude & Longitude untuk menampilkan peta');
                return;
            }

            const lat = parseFloat(latVal),
                lng = parseFloat(lngVal);
            if (!isValidCoord(lat, lng)) {
                setMapStatus('error', 'Koordinat tidak valid. Periksa kembali format Latitude & Longitude.');
                return;
            }
            map.flyTo([lat, lng], 17, {
                animate: true,
                duration: 1.2
            });
            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng], {
                        icon: L.icon({
                            iconUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png',
                            shadowUrl: 'https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png',
                            iconSize: [25, 41],
                            iconAnchor: [12, 41],
                            popupAnchor: [1, -34]
                        })
                    }).addTo(map)
                    .bindPopup(`<b>Titik Koordinat Toko</b><br>Lat: ${lat}<br>Lng: ${lng}`)
                    .openPopup();
            }
            document.getElementById('titik_koor_toko').value = `${lat},${lng}`;
            document.getElementById('map_toko').value = `https://www.google.com/maps?q=${lat},${lng}`;
            setMapStatus('found', `📍 Titik ditemukan → Lat: ${lat}, Long: ${lng}`);
        }
        let mapDebounce;
        ['latitude_input', 'longitude_input'].forEach(id => {
            document.getElementById(id).addEventListener('input', () => {
                clearTimeout(mapDebounce);
                mapDebounce = setTimeout(updateMap, 500);
            });
        });
        map.on('click', function(e) {
            document.getElementById('latitude_input').value = e.latlng.lat.toFixed(6);
            document.getElementById('longitude_input').value = e.latlng.lng.toFixed(6);
            updateMap();
        });

        /* ════════════════════════════════════════════════
           7. UPLOAD LAMPIRAN – multi file, maks 10
           ════════════════════════════════════════════════ */
        const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'application/pdf'];
        const ALLOWED_EXTS = ['.jpg', '.jpeg', '.png', '.pdf'];
        const MAX_SIZE_BYTES = 5 * 1024 * 1024;
        const MAX_FILES = 10;

        const fileInput = document.getElementById('file_input');
        const uploadZone = document.getElementById('upload_zone');
        const previewDiv = document.getElementById('upload_preview');
        const counterDiv = document.getElementById('file_counter');
        const countSpan = document.getElementById('file_count');
        let selectedFiles = [];

        uploadZone.addEventListener('dragover', e => {
            e.preventDefault();
            uploadZone.classList.add('drag-over');
        });
        uploadZone.addEventListener('dragleave', () => uploadZone.classList.remove('drag-over'));
        uploadZone.addEventListener('drop', e => {
            e.preventDefault();
            uploadZone.classList.remove('drag-over');
            if (e.dataTransfer.files.length) handleFiles(e.dataTransfer.files);
        });
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length) handleFiles(fileInput.files);
            fileInput.value = '';
        });

        function generateRandomName(ext) {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let name = '';
            for (let i = 0; i < 20; i++) name += chars.charAt(Math.floor(Math.random() * chars.length));
            return name + ext;
        }

        function formatSize(bytes) {
            return bytes < 1024 * 1024 ? (bytes / 1024).toFixed(1) + ' KB' : (bytes / 1024 / 1024).toFixed(1) + ' MB';
        }

        function handleFiles(fileList) {
            const incoming = Array.from(fileList);
            const errors = [];
            const valid = [];
            for (const file of incoming) {
                const ext = '.' + file.name.split('.').pop().toLowerCase();
                if (!ALLOWED_TYPES.includes(file.type) && !ALLOWED_EXTS.includes(ext)) {
                    errors.push(`<b>${file.name}</b> — format tidak didukung`);
                    continue;
                }
                if (file.size > MAX_SIZE_BYTES) {
                    errors.push(`<b>${file.name}</b> — ukuran ${formatSize(file.size)} melebihi 5 MB`);
                    continue;
                }
                if (selectedFiles.some(f => f.file.name === file.name && f.file.size === file.size)) {
                    errors.push(`<b>${file.name}</b> — sudah ditambahkan`);
                    continue;
                }
                valid.push({
                    file,
                    ext
                });
            }
            const available = MAX_FILES - selectedFiles.length;
            if (valid.length > available) {
                const rejected = valid.splice(available);
                rejected.forEach(f => errors.push(`<b>${f.file.name}</b> — batas 10 file tercapai`));
            }
            if (errors.length) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Beberapa File Ditolak',
                    html: errors.map(e => `• ${e}`).join('<br>'),
                    confirmButtonColor: '#185a82'
                });
            }
            for (const {
                    file,
                    ext
                }
                of valid) {
                selectedFiles.push({
                    file,
                    randomName: generateRandomName(ext)
                });
            }
            renderPreview();
        }

        function renderPreview() {
            previewDiv.innerHTML = '';
            if (selectedFiles.length === 0) {
                counterDiv.style.display = 'none';
                return;
            }
            counterDiv.style.display = 'block';
            countSpan.textContent = selectedFiles.length;
            countSpan.style.color = selectedFiles.length >= MAX_FILES ? '#dc2626' : '#6b7280';
            selectedFiles.forEach((item, index) => {
                const ext = '.' + item.file.name.split('.').pop().toLowerCase();
                const icon = ext === '.pdf' ? '📄' : '🖼️';
                const div = document.createElement('div');
                div.className = 'upload-preview';
                div.style.marginTop = '6px';
                div.innerHTML = `
                    <span class="file-icon">${icon}</span>
                    <span class="file-name">${item.randomName} <small style="color:#6b7280">(${item.file.name})</small></span>
                    <span class="file-size">${formatSize(item.file.size)}</span>
                    <span class="remove-file" data-index="${index}" title="Hapus file">✕</span>
                `;
                previewDiv.appendChild(div);
            });
            previewDiv.querySelectorAll('.remove-file').forEach(btn => {
                btn.addEventListener('click', () => {
                    selectedFiles.splice(parseInt(btn.dataset.index), 1);
                    renderPreview();
                });
            });
        }

        /* ════════════════════════════════════════════════
           8. FORM SUBMIT – validasi final
           ════════════════════════════════════════════════ */
        document.getElementById('FormMidi').addEventListener('submit', function(e) {
            // ── Tidak ada perubahan? Tampilkan pesan & jangan kirim (tidak masuk history) ──
            if (window.__editSnapshot != null) {
                const efNow = document.getElementById('existing_files_input');
                const fileLamaSekarang = efNow ? efNow.value : '';
                const tidakBerubah = window.__serializeFormlawson() === window.__editSnapshot &&
                    selectedFiles.length === 0 &&
                    fileLamaSekarang === window.__editExistingFiles;
                if (tidakBerubah) {
                    e.preventDefault();
                    e.stopPropagation();
                    Swal.fire({
                        icon: 'info',
                        title: 'Tidak Ada Perubahan',
                        text: 'Data belum diubah, jadi tidak ada yang perlu disimpan.',
                        confirmButtonColor: '#185a82'
                    });
                    return;
                }
            }
            const latVal = document.getElementById('latitude_input').value.trim();
            const lngVal = document.getElementById('longitude_input').value.trim();
            if (latVal || lngVal) {
                const lat = parseFloat(latVal),
                    lng = parseFloat(lngVal);
                if (!isValidCoord(lat, lng)) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Koordinat Tidak Valid',
                        text: 'Periksa kembali nilai Latitude dan Longitude.',
                        confirmButtonColor: '#185a82'
                    });
                    return;
                }
            }
            if (selectedFiles.length > 0) {
                const dt = new DataTransfer();
                selectedFiles.forEach(item => dt.items.add(item.file));
                fileInput.files = dt.files;
            }
        }, true);

        <?php if (!session()->get('logged_in')) : ?>
            window.location.href = "<?= base_url('/login') ?>";
        <?php endif; ?>
    </script>
    <script>
        /* ════════════════════════════════════════════════
           INIT EDIT — tampilkan blok & auto-fill sesuai data tersimpan
           ════════════════════════════════════════════════ */
        document.addEventListener('DOMContentLoaded', function() {
            // Tampilkan blok Non-Cellular / Cellular sesuai data
            const mediaSel = document.getElementById('media_koneksi_select');
            if (mediaSel && mediaSel.value !== '') {
                mediaSel.dispatchEvent(new Event('change'));
            }

            // Set ulang pilihan tersimpan lalu auto-fill (dispatch change di atas
            // TIDAK mengosongkan select yang sesuai jalurnya, hanya lawannya)
            const inetSel = document.getElementById('kode_layanan_select');
            if (inetSel && inetSel.value) fillNomorInet(inetSel.value);

            const simSel = document.getElementById('kode_quota_select');
            if (simSel && simSel.value) fillSimcard(simSel.value);

            const vpnSel = document.getElementById('vpn_select');
            if (vpnSel && vpnSel.value) fillVpn(vpnSel.value);

            // Tampilkan peta jika koordinat sudah ada
            const latEl = document.getElementById('latitude_input');
            const lngEl = document.getElementById('longitude_input');
            if (latEl && lngEl && latEl.value.trim() && lngEl.value.trim()) {
                updateMap();
            }
        });

        /* ════════════════════════════════════════════════
           DETEKSI "TIDAK ADA PERUBAHAN"
           ════════════════════════════════════════════════ */
        window.__serializeFormlawson = function() {
            const fd = new FormData(document.getElementById('FormMidi'));
            fd.delete('file_input[]');
            const parts = [];
            for (const pair of fd.entries()) {
                if (typeof pair[1] === 'string') parts.push(pair[0] + '=' + pair[1]);
            }
            return parts.join('&');
        };
        window.__editSnapshot = null;
        window.__editExistingFiles = '';
        document.addEventListener('DOMContentLoaded', function() {
            // Snapshot diambil setelah INIT EDIT di atas selesai (urutan listener terjaga)
            window.__editSnapshot = window.__serializeFormlawson();
            const efInit = document.getElementById('existing_files_input');
            window.__editExistingFiles = efInit ? efInit.value : '';
        });

        /* ════════════════════════════════════════════════
           FILE LAMA — Preview & Hapus (fitur lama dipertahankan)
           ════════════════════════════════════════════════ */
        let existingFilesList = <?php
                                if (!empty($lawson['upload_lampiran'])) {
                                    $dec = json_decode($lawson['upload_lampiran'], true);
                                    echo json_encode(is_array($dec) ? $dec : [$lawson['upload_lampiran']]);
                                } else {
                                    echo '[]';
                                }
                                ?>;

        function previewFile(filename, ext) {
            const fileUrl = '<?= site_url('Lawson/file/') ?>' + filename;

            if (['jpg', 'jpeg', 'png'].includes(ext)) {
                Swal.fire({
                    imageUrl: fileUrl,
                    imageAlt: filename,
                    width: '90vw',
                    padding: '0',
                    background: '#000',
                    showCloseButton: true,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'swal-image-popup',
                        closeButton: 'swal-image-close',
                    },
                    didOpen: () => {
                        const title = document.querySelector('.swal2-title');
                        const img = document.querySelector('.swal2-image');
                        if (title) title.remove();
                        if (img) {
                            img.style.cssText = 'width:100%;max-height:90vh;object-fit:contain;margin:0;display:block;border-radius:0';
                        }
                    }
                });
            } else if (ext === 'pdf') {
                const a = document.createElement('a');
                a.href = fileUrl;
                a.download = filename;
                a.click();
            }
        }

        function removeExistingFile(index, filename) {
            Swal.fire({
                title: 'Hapus File?',
                html: `File <b>${filename}</b> akan dihapus dari lampiran.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    const el = document.getElementById('existing_file_' + index);
                    if (el) {
                        el.style.transition = 'all .3s ease';
                        el.style.opacity = '0';
                        el.style.height = '0';
                        el.style.overflow = 'hidden';
                        el.style.marginBottom = '0';
                        el.style.padding = '0';
                        setTimeout(() => el.remove(), 300);
                    }

                    existingFilesList = existingFilesList.filter(f => f !== filename);

                    const hiddenInput = document.getElementById('existing_files_input');
                    if (hiddenInput) {
                        hiddenInput.value = existingFilesList.length > 0 ?
                            JSON.stringify(existingFilesList) : '';
                    }

                    const wrap = document.getElementById('existing_files_wrap');
                    if (wrap) {
                        const counter = wrap.querySelector('div');
                        if (counter) counter.textContent = `File Tersimpan (${existingFilesList.length} file):`;
                        if (existingFilesList.length === 0) {
                            wrap.innerHTML = '<div style="font-size:12px;color:#6b7280;font-style:italic">Semua file lama dihapus.</div>';
                        }
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'File dihapus',
                        text: 'File akan dihapus permanen saat kamu menyimpan perubahan.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        }
    </script>
</body>

</html>