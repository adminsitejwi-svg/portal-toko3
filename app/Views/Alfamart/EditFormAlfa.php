<?php

/**
 * @var array $midi
 * @var array $pemilik_projek
 * @var array $dc
 * @var array $media
 * @var array $vendor
 * @var array $layanan
 * @var array $jenis
 * @var array $perangkat
 */
?>
<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <title>Toko Alfamart</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="<?= base_url('store.png') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- DataTables core + Buttons -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
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
            justify-content: space-between;
        }

        .page-header h2 {
            font-size: 20px;
            font-weight: 700;
        }

        .page-header .subtitle {
            font-size: 13px;
            opacity: .75;
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

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        /* Map */
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

        /* Bandwidth */
        .bandwidth-row {
            display: flex;
            gap: 8px;
            align-items: flex-end;
        }

        .bandwidth-row select {
            flex: 1;
        }

        .btn-add-bw {
            white-space: nowrap;
            padding: 9px 14px;
            background: #185a82;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            height: 38px;
        }

        .btn-add-bw:hover {
            background: #0f3d5c;
        }

        /* Upload */
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
            margin-top: 6px;
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
        }

        .upload-preview .remove-file:hover {
            background: #fee2e2;
        }

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

        /* Action bar */
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
        }

        .btn-save:hover {
            opacity: .9;
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

        /* SweetAlert image fullscreen */
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

        /* ===== INVOICE-STYLE TABLE ===== */
        #mediaKoneksiTable {
            width: 100% !important;
            border-collapse: collapse;
        }

        #mediaKoneksiTable thead th {
            background: #f7f9fb;
            color: #6b7785;
            font-weight: 500;
            font-size: 13px;
            text-align: left;
            padding: 14px 16px;
            border-top: 1px solid #edf0f3;
            border-bottom: 1px solid #edf0f3;
            white-space: nowrap;
        }

        .dark #mediaKoneksiTable thead th {
            background: #2b3543;
            color: #9fb0c2;
            border-color: #37404c;
        }

        #mediaKoneksiTable tbody td {
            padding: 16px;
            font-size: 14px;
            color: #3b4754;
            border-bottom: 1px solid #f0f2f5;
            vertical-align: middle;
            white-space: nowrap;
        }

        .dark #mediaKoneksiTable tbody td {
            color: #bfc8d6;
            border-color: #37404c;
        }

        #mediaKoneksiTable tbody tr:hover {
            background: #fafbfc;
        }

        .dark #mediaKoneksiTable tbody tr:hover {
            background: rgba(255, 255, 255, .03);
        }

        #mediaKoneksiTable tbody td.col-bold {
            font-weight: 600;
            color: #2b3540;
        }

        .dark #mediaKoneksiTable tbody td.col-bold {
            color: #e7eaf0;
        }

        /* status badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
        }

        .badge-paid {
            background: #e7f8f1;
            color: #1aae6f;
        }

        .badge-pending {
            background: #fdf3e3;
            color: #d89a16;
        }

        .badge-due {
            background: #ffd2dc;
            color: #ff0000;
        }

        /* ===== LENGTH (Show) DROPDOWN — diperlebar, tanpa teks ===== */
        .dataTables_length {
            font-size: 0;
        }

        .dataTables_length select {
            font-size: 13px;
            border: 1px solid #e3e8ee;
            border-radius: 8px;
            padding: 9px 32px 9px 14px;
            color: #3b4754;
            outline: none;
            background: #fff;
            min-width: 130px;
            cursor: pointer;
        }

        .dark .dataTables_length select {
            background: #263240;
            color: #bfc8d6;
            border-color: #37404c;
        }

        .dataTables_length select:focus {
            border-color: #04a9f5;
        }

        /* sembunyikan search bawaan, pakai custom */
        .dataTables_filter {
            display: none;
        }

        .custom-search {
            position: relative;
            width: 240px;
            max-width: 100%;
        }

        .custom-search input {
            width: 100%;
            border: 1px solid #e3e8ee;
            border-radius: 8px;
            padding: 9px 44px 9px 14px;
            font-size: 13px;
            outline: none;
            color: #3b4754;
            background: #fff;
        }

        .dark .custom-search input {
            background: #263240;
            color: #bfc8d6;
            border-color: #37404c;
        }

        .custom-search input:focus {
            border-color: #04a9f5;
        }

        .custom-search .go-btn {
            position: absolute;
            right: 6px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            color: #8a95a1;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 4px 6px;
        }

        .custom-search .go-btn:hover {
            color: #04a9f5;
        }

        /* ===== PAGINATION ===== */
        .dataTables_paginate {
            font-size: 13px;
            margin-top: 1rem;
        }

        .dataTables_paginate .paginate_button {
            padding: 5px 11px !important;
            margin: 0 3px !important;
            border-radius: 6px !important;
            border: none !important;
            color: #5b6b7f !important;
            background: transparent !important;
        }

        .dataTables_paginate .paginate_button.current {
            background: #04a9f5 !important;
            color: #fff !important;
        }

        .dataTables_paginate .paginate_button:hover {
            background: #f0f2f5 !important;
            color: #3b4754 !important;
        }

        .dataTables_paginate .paginate_button.current:hover {
            background: #0396e2 !important;
            color: #fff !important;
        }

        .dataTables_info {
            font-size: 13px;
            color: #8a95a1;
            margin-top: 1rem;
        }

        /* ===== EXPORT DROPDOWN ===== */
        div.dt-buttons {
            display: inline-block;
        }

        button.dt-button.export-toggle {
            background: #fff !important;
            border: 1px solid #e3e8ee !important;
            color: #5b6b7f !important;
            border-radius: 8px !important;
            padding: 9px 16px !important;
            font-size: 13px !important;
            display: inline-flex !important;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            min-width: 130px;
            justify-content: center;
        }

        .dark button.dt-button.export-toggle {
            background: #263240 !important;
            color: #bfc8d6 !important;
            border-color: #37404c !important;
        }

        button.dt-button.export-toggle:hover {
            border-color: #04a9f5 !important;
            color: #04a9f5 !important;
        }

        div.dt-button-collection {
            background: #fff !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 8px !important;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .12) !important;
            padding: 6px !important;
            min-width: 170px;
        }

        .dark div.dt-button-collection {
            background: #263240 !important;
            border-color: #37404c !important;
        }

        div.dt-button-collection button.dt-button {
            display: flex !important;
            align-items: center;
            gap: 10px;
            width: 100%;
            text-align: left;
            background: transparent !important;
            border: none !important;
            color: #3b4754 !important;
            padding: 8px 12px !important;
            font-size: 14px !important;
            border-radius: 6px !important;
            margin: 0 !important;
        }

        .dark div.dt-button-collection button.dt-button {
            color: #ffffff !important;
        }

        div.dt-button-collection button.dt-button:hover {
            background: #f1f5f9 !important;
        }

        .dark div.dt-button-collection button.dt-button:hover {
            background: rgba(255, 255, 255, .05) !important;
        }

        /* ===== SCROLL HORIZONTAL DI MOBILE ===== */
        .table-scroll {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-scroll table {
            min-width: 760px;
        }

        .brand-text {
            font-size: 18px;
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
                        <li><a href="<?= site_url('Pelanggan') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Pelanggan</a></li>
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

                        <span>Case Lock</span>

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
                        <h2>Edit Data Toko Alfamart</h2>
                        <div class="subtitle">ID: <?= $midi['id'] ?> • <?= esc($midi['nama_alfamart']) ?></div>
                    </div>
                </div>

                <div class="form-card">
                    <form action="<?= site_url('Alfamart/update') ?>" method="POST" id="FormEditMidi" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $midi['id'] ?>">

                        <!-- ═══ INFORMASI TOKO ═══ -->
                        <div class="section-title">Informasi Toko</div>
                        <div class="grid-3">
                            <div class="form-group">
                                <label>Nama Alfamart <span class="req">*</span></label>
                                <input type="text" name="nama_alfamart"
                                    value="<?= esc($midi['nama_alfamart']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Kode Toko <span class="req">*</span></label>
                                <input type="text" name="kode_toko"
                                    value="<?= esc($midi['kode_toko']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Alamat Toko <span class="req">*</span></label>
                                <input type="text" name="alamat_alfamart"
                                    value="<?= esc($midi['alamat_alfamart']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>PIC Toko <span class="req">*</span></label>
                                <input type="text" name="pic_toko"
                                    value="<?= esc($midi['pic_toko']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Nomor HP PIC <span class="req">*</span></label>
                                <input type="text" name="nomor_hp_pic"
                                    value="<?= esc($midi['nomor_hp_pic']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Status <span class="req">*</span></label>
                                <select name="status" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    <option value="">— Pilih Status —</option>
                                    <option value="0" <?= $midi['status'] == '0'     ? 'selected' : '' ?>>Aktif</option>
                                    <option value="1" <?= $midi['status'] == '1' ? 'selected' : '' ?>>Non Aktif</option>
                                </select>
                            </div>
                        </div>

                        <!-- ═══ RELASI MASTER DATA ═══ -->
                        <div class="section-title">Data Vendor</div>
                        <div class="grid-2">

                            <div class="form-group">
                                <label>Vendor <span class="req">*</span></label>
                                <select name="vendor_id" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    <option value="">— Pilih —</option>
                                    <?php foreach ($vendor as $row): ?>
                                        <option value="<?= $row['id'] ?>"
                                            <?= $midi['vendor_id'] == $row['id'] ? 'selected' : '' ?>>
                                            <?= esc($row['nama_vendor']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Layanan Vendor <span class="req">*</span></label>
                                <select name="layanan_vendor_id" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    <option value="">— Pilih —</option>
                                    <?php foreach ($layanan as $row): ?>
                                        <option value="<?= $row['id'] ?>"
                                            <?= $midi['layanan_vendor_id'] == $row['id'] ? 'selected' : '' ?>>
                                            <?= esc($row['nama_layanan']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                        </div>
                        <div class="section-title">Data Perangkat</div>
                        <div class="grid-3">
                            <div class="form-group">
                                <label>Pemilik Project <span class="req">*</span></label>
                                <select name="pemilik_projek_id" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    <option value="">— Pilih —</option>
                                    <?php foreach ($pemilik_projek as $row): ?>
                                        <option value="<?= $row['id'] ?>"
                                            <?= $midi['pemilik_projek_id'] == $row['id'] ? 'selected' : '' ?>>
                                            <?= esc($row['nama_pemilik']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>DC <span class="req">*</span></label>
                                <select name="nama_dc_id" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    <option value="">— Pilih —</option>
                                    <?php foreach ($dc as $row): ?>
                                        <option value="<?= $row['id'] ?>"
                                            <?= $midi['nama_dc_id'] == $row['id'] ? 'selected' : '' ?>>
                                            <?= esc($row['nama_dc']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Jenis Perangkat <span class="req">*</span></label>
                                <select name="jenis_perangkat_id" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    <option value="">— Pilih —</option>
                                    <?php foreach ($jenis as $row): ?>
                                        <option value="<?= $row['id'] ?>"
                                            <?= $midi['jenis_perangkat_id'] == $row['id'] ? 'selected' : '' ?>>
                                            <?= esc($row['jenis_perangkat']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Merk Perangkat <span class="req">*</span></label>
                                <select name="merk_perangkat_id" id="merk_perangkat_select" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    <option value="">— Pilih —</option>
                                    <?php foreach ($perangkat as $row): ?>
                                        <option value="<?= $row['id'] ?>" <?= $midi['merk_perangkat_id'] == $row['id'] ? 'selected' : '' ?>>
                                            <?= esc($row['merk_perangkat']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Type Perangkat <span class="req">*</span></label>
                                <select name="type_perangkat" id="type_perangkat_select" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    <option value="">— Pilih —</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Media Koneksi <span class="req">*</span></label>
                                <?php $hasBackup = !empty($midi['backup_media_koneksi']); ?>
                                <div style="display:flex; gap:10px; align-items:flex-end;">
                                    <!-- Media koneksi utama -->
                                    <select name="media_koneksi_id" id="media_koneksi_select" required
                                        class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none"
                                        style="flex:1;">
                                        <option value="">— Pilih —</option>
                                        <?php foreach ($media as $row): ?>
                                            <option value="<?= $row['id'] ?>"
                                                <?= $midi['media_koneksi_id'] == $row['id'] ? 'selected' : '' ?>>
                                                <?= esc($row['media_koneksi']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <!-- Backup koneksi: text input, otomatis tampil kalau sudah ada datanya -->
                                    <div id="backup_wrapper"
                                        style="flex:1; <?= $hasBackup ? 'display:flex' : 'display:none' ?>; flex-direction:column; gap:5px;">
                                        <input type="text" name="backup_media_koneksi" id="backup_media_koneksi_input"
                                            value="<?= esc($midi['backup_media_koneksi'] ?? '') ?>"
                                            placeholder="Masukan Backup"
                                            <?= $hasBackup ? 'required' : '' ?>
                                            class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- ═══ DETAIL PERANGKAT & JARINGAN ═══ -->
                        <div class="section-title">Detail Perangkat & Jaringan</div>
                        <div class="grid-3">
                            <div class="form-group">
                                <label>IP Address</label>
                                <input type="text" name="ip_address"
                                    value="<?= esc($midi['ip_address']) ?>" placeholder="192.168.x.x">
                            </div>

                            <div class="form-group" style="grid-column: span 2">
                                <label>Serial Number Perangkat</label>
                                <input type="text" name="serial_number_perangkat"
                                    value="<?= esc($midi['serial_number_perangkat']) ?>" placeholder="S/N perangkat">
                            </div>
                            <div class="form-group">
                                <label>Tanggal Instalasi</label>
                                <input type="date" name="tanggal_installasi"
                                    value="<?= esc($midi['tanggal_installasi']) ?>">
                            </div>
                            <div class="form-group">
                                <label>Tanggal Aktivasi</label>
                                <input type="date" name="tanggal_aktivasi"
                                    value="<?= esc($midi['tanggal_aktivasi']) ?>">
                            </div>
                            <div class="form-group">
                                <label>Kapasitas Bandwidth <span class="req">*</span></label>
                                <div class="bandwidth-row">
                                    <select name="kapasitas_bandwidth" id="kapasitas_bandwidth" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none">
                                        <option value="">— Pilih —</option>
                                        <?php
                                        $bwOptions = ['1 Mbps', '2 Mbps', '5 Mbps', '10 Mbps', '20 Mbps', '50 Mbps', '100 Mbps'];
                                        // Tambahkan nilai dari DB jika tidak ada di daftar
                                        if (!empty($midi['kapasitas_bandwidth']) && !in_array($midi['kapasitas_bandwidth'], $bwOptions)) {
                                            $bwOptions[] = $midi['kapasitas_bandwidth'];
                                        }
                                        foreach ($bwOptions as $bw):
                                        ?>
                                            <option value="<?= $bw ?>"
                                                <?= $midi['kapasitas_bandwidth'] == $bw ? 'selected' : '' ?>>
                                                <?= $bw ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="button" class="btn-add-bw" id="btn_add_bw">+ Baru</button>
                                </div>
                            </div>
                        </div>

                        <!-- ═══ MAP / TITIK KOOR ═══ -->
                        <div class="section-title">Titik Koordinat & Peta</div>
                        <div class="map-section">
                            <div class="map-inputs">
                                <?php
                                $lat = '';
                                $lng = '';
                                if (!empty($midi['titik_koor_toko'])) {
                                    [$lat, $lng] = array_map('trim', explode(',', $midi['titik_koor_toko']));
                                }
                                ?>
                                <div class="form-group">
                                    <label>Latitude</label>
                                    <input type="text" id="latitude_input" value="<?= esc($lat) ?>"
                                        placeholder="Cth: -6.200000" autocomplete="off">
                                </div>
                                <div class="form-group">
                                    <label>Longitude</label>
                                    <input type="text" id="longitude_input" value="<?= esc($lng) ?>"
                                        placeholder="Cth: 106.816666" autocomplete="off">
                                </div>
                                <input type="hidden" name="titik_koor_toko" id="titik_koor_toko"
                                    value="<?= esc($midi['titik_koor_toko']) ?>">
                                <input type="hidden" name="map_toko" id="map_toko"
                                    value="<?= esc($midi['map_toko']) ?>">
                            </div>
                            <div class="map-status" id="map_status">
                                <span class="dot"></span>
                                <span id="map_status_text">Masukkan Latitude &amp; Longitude untuk menampilkan peta</span>
                            </div>
                            <div id="map"></div>
                        </div>

                        <!-- ═══ KETERANGAN & LAMPIRAN ═══ -->
                        <div class="section-title">Keterangan & Lampiran</div>
                        <div class="grid-2">
                            <div class="form-group">
                                <label>Keterangan <span class="req">*</span></label>
                                <textarea name="keterangan" rows="4" required class="w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none"><?= esc($midi['keterangan']) ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Upload Lampiran
                                    <small style="font-weight:400;color:#6b7280">(JPG/PNG/PDF • Maks. 10 file • 5MB/file)</small>
                                </label>

                                <!-- File lama -->
                                <!-- File lama -->
                                <?php if (!empty($midi['upload_lampiran'])): ?>
                                    <?php
                                    $decoded = json_decode($midi['upload_lampiran'], true);
                                    $existingFiles = is_array($decoded) ? $decoded : [$midi['upload_lampiran']];
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
                                    <!-- Hidden: kirim daftar file lama yang masih dipertahankan -->
                                    <input type="hidden" name="existing_files" id="existing_files_input"
                                        value="<?= esc($midi['upload_lampiran']) ?>">
                                <?php endif; ?>

                                <!-- Upload baru -->
                                <div class="upload-zone" id="upload_zone">
                                    <input type="file" name="file_input[]" id="file_input"
                                        accept=".jpg,.jpeg,.png,.pdf" multiple>
                                    <div class="upload-icon">📂</div>
                                    <div class="upload-text">Klik atau seret file ke sini</div>
                                    <div class="upload-hint">Format: JPG, PNG, PDF • Maks. 10 file • 5 MB per file</div>
                                </div>
                                <div id="file_counter" style="font-size:12px;color:#6b7280;margin-top:4px;display:none">
                                    <span id="file_count">0</span>/10 file baru dipilih
                                </div>
                                <div id="upload_preview"></div>
                            </div>
                        </div>

                        <div class="action-bar">
                            <button type="submit" class="btn-save">Simpan Perubahan</button>
                            <button type="button" class="btn-back"
                                onclick="window.location.href='<?= site_url('Alfamart') ?>'">
                                Kembali
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function() {
            const mediaSel = document.getElementById('media_koneksi_select');
            const backupWrap = document.getElementById('backup_wrapper');
            const backupInp = document.getElementById('backup_media_koneksi_input');
            if (!mediaSel) return;

            function hideBackup() {
                backupWrap.style.display = 'none';
                backupInp.value = '';
                backupInp.removeAttribute('required');
            }

            function showBackup() {
                backupWrap.style.display = 'flex';
                backupInp.setAttribute('required', 'required');
                backupInp.value = ''; // kosong / polos
                backupInp.focus();
            }

            // hanya jalan saat user MENGUBAH dropdown, bukan saat halaman dibuka
            mediaSel.addEventListener('change', function() {
                if (!mediaSel.value) {
                    hideBackup();
                    return;
                }
                Swal.fire({
                    title: 'Apakah Toko Ini, <br> Menggunakan Backup',
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Tidak',
                    confirmButtonColor: '#185a82',
                    cancelButtonColor: '#6b7280'
                }).then(res => {
                    res.isConfirmed ? showBackup() : hideBackup();
                });
            });
        })();
    </script>
    <script>
        /* ══ MAP ══════════════════════════════════════════════ */
        const initLat = <?= !empty($lat) ? (float)$lat : -6.200000 ?>;
        const initLng = <?= !empty($lng) ? (float)$lng : 106.816666 ?>;

        const map = L.map('map').setView([initLat, initLng], <?= !empty($lat) ? 17 : 12 ?>);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        let marker = null;

        <?php if (!empty($lat) && !empty($lng)): ?>
            marker = L.marker([<?= (float)$lat ?>, <?= (float)$lng ?>])
                .addTo(map)
                .bindPopup('<b>Titik Koordinat Toko</b><br>Lat: <?= $lat ?><br>Lng: <?= $lng ?>')
                .openPopup();
            setMapStatus('found', `📍 Titik ditemukan → Lat: <?= $lat ?>, Long: <?= $lng ?>`);
        <?php endif; ?>

        function setMapStatus(state, text) {
            const el = document.getElementById('map_status');
            el.className = 'map-status ' + state;
            document.getElementById('map_status_text').textContent = text;
        }

        function isValidCoord(lat, lng) {
            return !isNaN(lat) && !isNaN(lng) &&
                lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180;
        }

        function updateMap() {
            const lat = parseFloat(document.getElementById('latitude_input').value.trim());
            const lng = parseFloat(document.getElementById('longitude_input').value.trim());

            if (isNaN(lat) || isNaN(lng)) {
                setMapStatus('', 'Masukkan Latitude & Longitude untuk menampilkan peta');
                return;
            }
            if (!isValidCoord(lat, lng)) {
                setMapStatus('error', 'Koordinat tidak valid.');
                return;
            }

            map.flyTo([lat, lng], 17, {
                animate: true,
                duration: 1.2
            });

            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng]).addTo(map)
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

        /* ══ BANDWIDTH ════════════════════════════════════════ */
        document.getElementById('btn_add_bw').addEventListener('click', function() {
            Swal.fire({
                title: 'Tambah Kapasitas Bandwidth',
                html: `<div style="display:flex;gap:8px;align-items:center">
            <input type="number" id="swal_bw_val" class="swal2-input" placeholder="Cth: 25" min="1" style="flex:1;margin:0">
            <select id="swal_bw_unit" class="swal2-input" style="flex:1;margin:0">
                <option value="Mbps">Mbps</option>
                <option value="Gbps">Gbps</option>
                <option value="Kbps">Kbps</option>
            </select></div>`,
                showCancelButton: true,
                confirmButtonText: 'Tambahkan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#185a82',
                preConfirm: () => {
                    const v = document.getElementById('swal_bw_val').value.trim();
                    const u = document.getElementById('swal_bw_unit').value;
                    if (!v || isNaN(v) || parseFloat(v) <= 0) {
                        Swal.showValidationMessage('Masukkan nilai bandwidth yang valid');
                        return false;
                    }
                    return `${v} ${u}`;
                }
            }).then(r => {
                if (r.isConfirmed) {
                    const sel = document.getElementById('kapasitas_bandwidth');
                    if (Array.from(sel.options).some(o => o.value === r.value)) {
                        sel.value = r.value;
                        return;
                    }
                    sel.add(new Option(r.value, r.value, true, true));
                }
            });
        });

        /* ══ UPLOAD MULTI-FILE ════════════════════════════════ */
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
            const c = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            let n = '';
            for (let i = 0; i < 20; i++) n += c[Math.floor(Math.random() * c.length)];
            return n + ext;
        }

        function formatSize(b) {
            return b < 1048576 ? (b / 1024).toFixed(1) + ' KB' : (b / 1048576).toFixed(1) + ' MB';
        }

        function handleFiles(fileList) {
            const errors = [],
                valid = [];
            Array.from(fileList).forEach(file => {
                const ext = '.' + file.name.split('.').pop().toLowerCase();
                if (!ALLOWED_TYPES.includes(file.type) && !ALLOWED_EXTS.includes(ext))
                    return errors.push(`<b>${file.name}</b> — format tidak didukung`);
                if (file.size > MAX_SIZE_BYTES)
                    return errors.push(`<b>${file.name}</b> — melebihi 5 MB`);
                if (selectedFiles.some(f => f.file.name === file.name && f.file.size === file.size))
                    return errors.push(`<b>${file.name}</b> — sudah ditambahkan`);
                valid.push({
                    file,
                    ext
                });
            });

            const avail = MAX_FILES - selectedFiles.length;
            if (valid.length > avail)
                valid.splice(avail).forEach(f => errors.push(`<b>${f.file.name}</b> — batas 10 file tercapai`));

            if (errors.length)
                Swal.fire({
                    icon: 'warning',
                    title: 'Beberapa File Ditolak',
                    html: errors.map(e => `• ${e}`).join('<br>'),
                    confirmButtonColor: '#185a82'
                });

            valid.forEach(({
                file,
                ext
            }) => selectedFiles.push({
                file,
                randomName: generateRandomName(ext)
            }));
            renderPreview();
        }

        function renderPreview() {
            previewDiv.innerHTML = '';
            if (!selectedFiles.length) {
                counterDiv.style.display = 'none';
                return;
            }
            counterDiv.style.display = 'block';
            countSpan.textContent = selectedFiles.length;
            countSpan.style.color = selectedFiles.length >= MAX_FILES ? '#dc2626' : '#6b7280';

            selectedFiles.forEach((item, i) => {
                const ext = '.' + item.file.name.split('.').pop().toLowerCase();
                const icon = ext === '.pdf' ? '📄' : '🖼️';
                const div = document.createElement('div');
                div.className = 'upload-preview';
                div.innerHTML = `
            <span class="file-icon">${icon}</span>
            <span class="file-name">${item.randomName}
                <small style="color:#6b7280">(${item.file.name})</small>
            </span>
            <span class="file-size">${formatSize(item.file.size)}</span>
            <span class="remove-file" data-i="${i}">✕</span>`;
                previewDiv.appendChild(div);
            });

            previewDiv.querySelectorAll('.remove-file').forEach(btn => {
                btn.addEventListener('click', () => {
                    selectedFiles.splice(parseInt(btn.dataset.i), 1);
                    renderPreview();
                });
            });
        }

        document.getElementById('FormEditMidi').addEventListener('submit', function() {
            if (selectedFiles.length > 0) {
                const dt = new DataTransfer();
                selectedFiles.forEach(item => dt.items.add(item.file));
                fileInput.files = dt.files;
            }
        }, true);

        /* ══ FILE LAMA — Preview & Hapus ═════════════════════ */
        // Simpan daftar file lama yang masih ada
        let existingFilesList = <?php
                                if (!empty($midi['upload_lampiran'])) {
                                    $dec = json_decode($midi['upload_lampiran'], true);
                                    echo json_encode(is_array($dec) ? $dec : [$midi['upload_lampiran']]);
                                } else {
                                    echo '[]';
                                }
                                ?>;

        function previewFile(filename, ext) {
            const fileUrl = '<?= site_url('Alfamart/file/') ?>' + filename;

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
                        // Hapus title & content wrapper agar hanya gambar
                        const title = document.querySelector('.swal2-title');
                        const img = document.querySelector('.swal2-image');
                        if (title) title.remove();
                        if (img) {
                            img.style.cssText = `
                        width: 100%;
                        max-height: 90vh;
                        object-fit: contain;
                        margin: 0;
                        display: block;
                        border-radius: 0;
                    `;
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
                    // Sembunyikan elemen di UI
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

                    // Hapus dari array
                    existingFilesList = existingFilesList.filter(f => f !== filename);

                    // Update hidden input
                    const hiddenInput = document.getElementById('existing_files_input');
                    if (hiddenInput) {
                        hiddenInput.value = existingFilesList.length > 0 ?
                            JSON.stringify(existingFilesList) :
                            '';
                    }

                    // Update counter
                    const wrap = document.getElementById('existing_files_wrap');
                    if (wrap) {
                        const counter = wrap.querySelector('div[style*="File Tersimpan"]');
                        if (counter) {
                            counter.textContent = `File Tersimpan (${existingFilesList.length} file):`;
                        }
                        // Jika semua dihapus, sembunyikan wrap
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

        // ---- Dropdowns ----
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

        // ---- Submenu ----
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
    <script>
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

            // saat halaman dibuka: merk sudah ter-set (selected), langsung filter + pilih type tersimpan
            refreshTypes(<?= (int) ($midi['type_perangkat'] ?? 0) ?>);
        })();
    </script>
</body>

</html>