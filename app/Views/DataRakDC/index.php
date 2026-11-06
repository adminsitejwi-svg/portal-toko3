<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-noc.png') ?>?v=3">
    <link rel="shortcut icon" href="<?= base_url('favicon.ico') ?>?v=3">
    <link rel="apple-touch-icon" href="<?= base_url('apple-touch-icon.png') ?>?v=3">
    <title>Data Rak DC</title>
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

        .rak-bar {
            display: block;
            height: 6px;
            border-radius: 3px;
            background: var(--rf-soft);
            overflow: hidden;
            margin-top: 4px;
        }

        .rak-bar > span {
            display: block;
            height: 100%;
            background: #16a34a;
        }

        .rak-bar.is-sedang > span {
            background: #d97706;
        }

        .rak-bar.is-penuh > span {
            background: #dc2626;
        }

        /* ===== VISUAL RAK =====
           --u = tinggi 1U; posisi perangkat dihitung dari bawah rak */
        .rk-grid {
            --u: 26px;
            display: flex;
            align-items: flex-start;
            gap: 20px;
            overflow-x: auto;
            padding: 4px 4px 14px;
        }

        .rk-rack {
            flex: 0 0 300px;
            max-width: 100%;
            padding: 14px;
            background: var(--rf-card);
            border: 1px solid var(--rf-border);
            border-radius: 12px;
            box-shadow: var(--rf-shadow);
        }

        .rk-rack.is-fokus {
            box-shadow: 0 0 0 2px #2f6bff;
        }

        .rk-rack.is-nonaktif .rk-frame {
            filter: grayscale(.7);
        }

        .rk-judul {
            font-size: 15px;
            font-weight: 700;
            color: var(--rf-head);
        }

        .rk-meta {
            font-size: 12px;
            color: var(--rf-muted);
        }

        .rk-info {
            display: flex;
            flex-wrap: wrap;
            gap: 6px 18px;
            margin: 6px 0 0;
        }

        .rk-info > div {
            min-width: 0;
            max-width: 180px;
        }

        .rk-info dt {
            font-size: 11px;
            font-weight: 400;
            color: var(--rf-muted);
        }

        .rk-info dd {
            margin: 0;
            overflow: hidden;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--rf-head);
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .rk-icon-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            color: #2f6bff;
            background: #e8efff;
        }

        .dark .rk-icon-btn {
            color: #8fb2ff;
            background: rgba(47, 107, 255, .16);
        }

        .rk-frame {
            margin-top: 12px;
            padding: 8px 30px;
            background: #1b222d;
            border: 1px solid #323d4d;
            border-radius: 6px;
        }

        .rk-body {
            position: relative;
            height: calc(var(--n) * var(--u));
            background: #11161d;
            overflow: hidden;
        }

        .rk-frame-wrap {
            position: relative;
        }

        /* nomor U di kiri & kanan rak */
        .rk-nomor {
            position: absolute;
            top: 0;
            bottom: 0;
            width: 26px;
        }

        .rk-nomor.kiri {
            left: -28px;
        }

        .rk-nomor.kanan {
            right: -28px;
        }

        .rk-nomor span {
            position: absolute;
            left: 0;
            right: 0;
            bottom: calc((var(--i) - 1) * var(--u));
            height: var(--u);
            line-height: var(--u);
            font-size: 10px;
            text-align: center;
            color: #8a97a8;
        }

        .rk-slot {
            position: absolute;
            left: 0;
            right: 0;
            bottom: calc((var(--i) - 1) * var(--u));
            height: var(--u);
            padding: 0;
            border-top: 1px solid rgba(255, 255, 255, .07);
            background: transparent;
            color: transparent;
            font-size: 11px;
            cursor: pointer;
        }

        .rk-slot:hover,
        .rk-slot:focus-visible {
            background: rgba(47, 107, 255, .28);
            color: #dbe6ff;
            outline: none;
        }

        .rk-dev {
            position: absolute;
            left: 2px;
            right: 2px;
            bottom: calc(var(--p) * var(--u) + 1px);
            height: calc(var(--s) * var(--u) - 2px);
            z-index: 2;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 0 8px;
            border-radius: 3px;
            background: var(--c);
            color: #fff;
            font-size: 11px;
            line-height: 1.2;
            text-align: left;
            overflow: hidden;
            cursor: pointer;
            transition: opacity .15s, box-shadow .15s;
        }

        .rk-dev:hover,
        .rk-dev:focus-visible {
            box-shadow: inset 0 0 0 2px rgba(255, 255, 255, .85);
            outline: none;
        }

        .rk-dev.is-nonaktif {
            background: color-mix(in srgb, var(--c) 22%, #11161d);
            box-shadow: inset 0 0 0 1px var(--c);
            color: #e4e5e9;
        }

        .rk-dev b {
            font-weight: 700;
            white-space: nowrap;
        }

        .rk-dev .nama {
            flex: 1;
            min-width: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .rk-dev .pos {
            font-size: 10px;
            opacity: .8;
            white-space: nowrap;
        }

        .rk-area.is-mencari .rk-dev:not(.is-cocok),
        .rk-area.is-mencari .rk-lepas-item:not(.is-cocok) {
            opacity: .18;
        }

        .rk-area.is-mencari .rk-dev.is-cocok {
            box-shadow: inset 0 0 0 2px #fff;
        }

        .rk-lepas {
            margin-top: 10px;
            font-size: 12px;
            color: var(--rf-muted);
        }

        .rk-lepas-item {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin: 4px 4px 0 0;
            padding: 3px 8px;
            border-radius: 6px;
            border-left: 4px solid var(--c);
            background: var(--rf-soft);
            color: var(--rf-text);
            font-size: 11.5px;
        }

        .rk-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 6px 14px;
            font-size: 12px;
            color: var(--rf-muted);
        }

        .rk-legend i {
            display: inline-block;
            width: 11px;
            height: 11px;
            margin-right: 5px;
            border-radius: 3px;
            vertical-align: -1px;
            background: var(--c);
        }

        .rk-kosong {
            padding: 48px 16px;
            text-align: center;
            color: var(--rf-muted);
        }

        .rk-kosong > i {
            display: block;
            margin-bottom: 8px;
            font-size: 38px;
        }

        .rf-hint-rak {
            margin-top: 6px;
            font-size: 12px;
            color: var(--rf-muted);
        }

        /* ===== DASHBOARD 2 x 2: Data Center | Data Rak, Data Perangkat | Visual Rak ===== */
        html.dark .rfo-page {
            --rf-card: #21222d;
            --rf-border: #2e2f3b;
            --rf-soft: #171821;
            --rf-text: #e4e5e9;
            --rf-head: #ffffff;
            --rf-muted: #87888c;
            --rak-aksen: #a9dfd8;
            --rak-track: #2b2b36;
            background: #171821;
        }

        .rfo-page {
            --rak-aksen: #2a9d8f;
            --rak-track: #e9edf5;
        }

        .rak-dash {
            display: grid;
            grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);
            gap: 16px;
        }

        .rak-panel {
            min-width: 0;
            margin-bottom: 0 !important;
            padding: 24px;
            background: var(--rf-card) !important;
            border: 1px solid var(--rf-border);
            border-radius: 14px;
            color: var(--rf-text);
        }

        /* tinggi baris tetap, tiap panel punya scroll sendiri */
        .rak-panel.is-atas {
            height: 520px;
            overflow-y: auto;
        }

        .rak-panel.is-gulir {
            /* pas untuk 10 baris tabel perangkat + judul, toolbar & pagination */
            height: 825px;
            overflow-y: auto;
        }

        @media (max-width: 1200px) {
            .rak-dash {
                grid-template-columns: minmax(0, 1fr);
            }

            .rak-panel.is-atas,
            .rak-panel.is-gulir {
                height: auto;
                overflow-y: visible;
            }
        }

        .rak-judul-panel {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 16px;
        }

        .rak-judul-panel h2 {
            font-size: 21px;
            font-weight: 700;
            color: var(--rf-head);
        }

        .rak-judul-panel p {
            margin-top: 2px;
            font-size: 13.5px;
            color: var(--rf-muted);
        }

        .rak-filter {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            margin-bottom: 16px;
        }

        .rak-filter .rfo-search {
            flex: 2 1 200px;
        }

        .rak-page-tombol {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
        }

        /* judul Data Rak: search, export, tambah satu baris di kanan; search yang mengecil kalau panel sempit */
        #panelRak .rak-judul-panel {
            container-type: inline-size;
            align-items: center;
        }

        #panelRak .rak-judul-panel h2 {
            white-space: nowrap;
        }

        /* 160px = lebar terkecil search + dua tombol ikon; kurang dari itu turun ke bawah judul */
        #panelRak .rak-page-tombol {
            flex: 1 1 160px;
            min-width: 0;
            flex-wrap: nowrap;
            justify-content: flex-end;
        }

        #panelRak .rak-page-tombol .rfo-search {
            flex: 0 1 320px;
            min-width: 60px;
        }

        #panelRak .rak-page-tombol > :not(.rfo-search) {
            flex-shrink: 0;
        }

        /* tulisan penuh tidak muat: export & tambah cukup ikon */
        @container (max-width: 430px) {
            #panelRak .rak-page-tombol .rfo-btn,
            #panelRak .rak-page-tombol .dt-button {
                width: 42px;
                min-width: 42px;
                height: 42px;
                padding-left: 0 !important;
                padding-right: 0 !important;
                justify-content: center;
                font-size: 0 !important;
            }

            #panelRak .rak-page-tombol .rfo-btn i,
            #panelRak .rak-page-tombol .dt-button i {
                margin: 0;
                font-size: 17px;
            }

            #panelRak .rak-page-tombol .dt-button::after,
            #panelRak .rak-page-tombol .dt-button .dt-down-arrow {
                display: none !important;
            }
        }

        /* ===== KOTAK DATA CENTER ===== */
        .dc-pils {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
            gap: 14px;
            max-height: 340px;
            overflow-y: auto;
        }

        .dc-pil {
            display: block;
            width: 100%;
            min-width: 0;
            padding: 18px;
            border: 1px solid transparent;
            border-radius: 14px;
            background: var(--rf-soft);
            color: var(--rf-head);
            text-align: left;
            cursor: pointer;
            transition: border-color .15s, opacity .15s;
        }

        .dc-pil:hover {
            border-color: var(--c);
        }

        .dc-pil.is-aktif {
            border-color: var(--c);
            box-shadow: inset 0 0 0 1px var(--c);
        }

        .dc-pil.is-redup {
            opacity: .3;
        }

        .dc-pil > i {
            display: block;
            margin-bottom: 12px;
            font-size: 26px;
            color: var(--c);
        }

        .dc-pil b {
            display: block;
            font-size: 20px;
            font-weight: 700;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dc-pil .nama {
            display: block;
            margin: 8px 0 4px;
            font-size: 14px;
            font-weight: 500;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .dc-pil small {
            display: block;
            font-size: 11.5px;
            color: var(--c);
        }

        html:not(.dark) .dc-pil small,
        html:not(.dark) .dc-pil > i {
            color: color-mix(in srgb, var(--c) 60%, #000);
        }

        .dc-aksi {
            display: flex;
            gap: 4px;
        }

        .dc-aksi[hidden] {
            display: none;
        }

        .dc-aksi button {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            color: var(--rf-text);
            background: var(--rf-soft);
        }

        .dc-aksi button.is-hapus {
            color: #ef4444;
        }

        /* ===== KAPSUL KAPASITAS YANG DIPAKAI (tabel Data Rak) ===== */
        .rak-kapsul {
            display: block;
            min-width: 150px;
        }

        .rak-kapsul .trek {
            display: block;
            height: 14px;
            border-radius: 999px;
            background: var(--rak-track);
            overflow: hidden;
        }

        .rak-kapsul .isi {
            display: block;
            height: 100%;
            border-radius: 999px;
            background: var(--c);
        }

        .rak-kapsul .nilai {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 3px;
            font-size: 11.5px;
            color: var(--rf-muted);
        }

        .rak-kapsul .nilai b {
            color: var(--rf-head);
            font-weight: 700;
        }

        /* ===== TABEL PERANGKAT ===== */
        #panelRak .table-scroll table {
            min-width: 0 !important;
        }

        /* panel rak & perangkat: pagination selalu terlihat di bawah, hanya baris tabel yang di-scroll */
        @media (min-width: 1201px) {
            #panelRak,
            #panelPerangkat {
                display: flex;
                flex-direction: column;
                overflow: hidden;
            }

            #panelRak .table-scroll,
            #panelPerangkat .table-scroll {
                flex: 1;
                min-height: 0;
                overflow-y: auto;
            }

            #panelRak .rfo-table.dataTable thead th,
            #panelPerangkat .rfo-table.dataTable thead th {
                position: sticky;
                top: 0;
                z-index: 1;
                background: var(--rf-card) !important;
            }
        }

        #panelRak .rfo-footer,
        #panelPerangkat .rfo-footer {
            flex-wrap: nowrap;
            flex-shrink: 0;
            margin-top: 10px;
        }

        #panelRak .rfo-footer .dataTables_info,
        #panelPerangkat .rfo-footer .dataTables_info {
            min-width: 0;
            padding: 0;
            font-size: 12px;
        }

        #panelRak .rfo-footer .dataTables_paginate,
        #panelPerangkat .rfo-footer .dataTables_paginate {
            flex-shrink: 0;
            padding: 0;
            white-space: nowrap;
        }

        .rak-panel .table-scroll,
        .rak-panel .rfo-table.dataTable {
            border: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
        }

        .rak-panel .rfo-table.dataTable thead th {
            padding: 12px 18px 12px 8px !important;
            border-bottom: 1px solid var(--rf-border) !important;
            background: transparent !important;
            color: var(--rf-muted) !important;
            font-size: 13.5px;
            font-weight: 500;
            white-space: nowrap;
        }

        .rak-panel .rfo-table.dataTable tbody td {
            padding: 13px 8px !important;
            border-bottom: 1px solid var(--rf-border) !important;
            background: transparent !important;
            color: var(--rf-text) !important;
            white-space: nowrap;
        }

        .rak-panel .rfo-table.dataTable tbody tr:hover td {
            background: var(--rf-soft) !important;
        }

        /* kode rak dipakai sebagai tombol pilih, bukan link: tanpa garis bawah saat diklik */
        .rak-panel .rfo-table a.rfo-link,
        .rak-panel .rfo-table a.rfo-link:hover,
        .rak-panel .rfo-table a.rfo-link:active,
        .rak-panel .rfo-table a.rfo-link:focus {
            text-decoration: none !important;
            cursor: pointer;
        }

        .rak-panel .rfo-table a.rfo-link:focus:not(:focus-visible) {
            outline: none;
        }

        /* rak yang sedang dipilih */
        .rak-panel .rfo-table.dataTable tbody td {
            transition: background-color .2s ease;
        }

        /* !important: gaya baris ganjil & hover DataTables memakai box-shadow dan menimpa garis di baris pertama */
        .rak-panel .rfo-table.dataTable tbody tr.is-aktif td {
            background: rgba(47, 107, 255, .1) !important;
            box-shadow: none !important;
        }

        .rak-panel .rfo-table.dataTable tbody tr.is-aktif td:first-child {
            box-shadow: inset 4px 0 0 #2f6bff !important;
        }

        .rak-panel .rfo-table.dataTable tbody tr.is-aktif td:first-child a {
            font-weight: 700;
        }

        /* Visual Rak saat satu rak dipilih: hanya rak itu, selebar panel */
        .rk-area.is-satu-rak .rk-grid {
            display: block;
            overflow: visible;
            padding: 0;
        }

        .rk-area.is-satu-rak .rk-rack {
            width: 100%;
            max-width: none;
            animation: rakDipilih .3s ease-out;
        }

        @keyframes rakDipilih {
            from {
                opacity: 0;
                transform: translateY(8px);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            .rk-area.is-satu-rak .rk-rack {
                animation: none;
            }
        }

        .rk-area.is-satu-rak .rk-grup {
            margin-bottom: 0;
        }

        .rak-panel .rfo-table .btn {
            margin: 0 !important;
        }

        .rak-nama {
            display: block;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .rak-tag {
            display: inline-block;
            padding: 5px 12px;
            border: 1px solid currentColor;
            border-radius: 5px;
            font-size: 12px;
            font-weight: 600;
            color: var(--c);
            background: color-mix(in srgb, var(--c) 14%, transparent);
        }

        html:not(.dark) .rak-tag {
            color: color-mix(in srgb, var(--c) 55%, #000);
        }

        .rak-tag.is-lepas {
            --c: #fcb859 !important;
        }

        /* ===== ISI VISUAL RAK ===== */
        .rk-dev {
            color: #14141b;
        }

        .rk-rack {
            background: var(--rf-soft);
            border-color: var(--rf-border);
            box-shadow: none;
        }

        .rk-grup {
            margin-bottom: 18px;
        }

        .rk-grup-judul {
            margin-bottom: 8px;
            font-size: 13.5px;
            font-weight: 700;
            color: var(--rf-head);
        }

        .rk-grup.is-kosong .rk-grup-judul {
            display: none;
        }

        .rk-dc-aktif {
            max-width: 60%;
            overflow: hidden;
            font-size: 14px;
            font-weight: 700;
            color: var(--rf-head);
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* nama Data Center ada di baris judul yang tetap terlihat; di layar sempit panel tidak punya scroll sendiri, jadi nama tetap di atas tiap kelompok rak */
        @media (min-width: 1201px) {
            #bagian-visual {
                position: sticky;
                top: -24px;
                z-index: 3;
                flex-wrap: nowrap;
                align-items: center;
                margin: -24px -24px 12px;
                padding: 24px 24px 12px;
                background: var(--rf-card);
            }

            /* kelompok pertama memakai nama di baris judul; kelompok berikutnya tetap punya nama sendiri */
            .rk-grup.is-pertama .rk-grup-judul {
                display: none;
            }

            .rk-grup + .rk-grup {
                padding-top: 16px;
                border-top: 1px solid var(--rf-border);
            }
        }

        @media (max-width: 1200px) {
            .rk-dc-aktif {
                display: none;
            }
        }

        .rk-rack {
            /* jangan tertutup baris judul Visual Rak yang menempel di atas */
            scroll-margin-top: 80px;
        }

        .rak-form-error {
            margin-bottom: 16px;
            padding: 10px 14px;
            border-radius: 10px;
            background: #feecec;
            color: #b91c1c;
            font-size: 13px;
            font-weight: 600;
        }

        .dark .rak-form-error {
            background: rgba(239, 68, 68, .16);
            color: #fca5a5;
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
                            <p class="text-sm"><?= esc(session()->getFlashdata('success')) ?></p>
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
                            <p class="text-sm"><?= esc(session()->getFlashdata('error')) ?></p>
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
                    <a href="#" onclick="toggleSub(this);return false;" class="pc-link active flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
                        <span class="pc-micon w-5"><i class="ti ti-category"></i></span>
                        <span class="flex-1">All NOC</span>
                        <i data-feather="chevron-right" class="arrow w-4 h-4 transition-transform"></i>
                    </a>
                    <ul class="submenu bg-black/20">
                        <li><a href="<?= site_url('DataCenter') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Center</a></li>
                        <li><a href="<?= site_url('DataRakDC') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Rak DC</a></li>
                        <li><a href="<?= site_url('Interkoneksi') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data Interkoneksi</a></li>
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

        <?php
        use App\Models\DataPerangkatModel;

        $angka  = static fn ($n) => DataPerangkatModel::angka($n);
        $json   = static fn ($v) => json_encode($v, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
        $status = static fn ($s) => (int) $s === 1 ? '<span class="rb rb-green">Aktif</span>' : '<span class="rb rb-red">Nonaktif</span>';
        $isi    = static fn ($v) => esc((string) $v !== '' ? $v : '-');
        // Bar pemakaian rak: hijau < 70%, kuning < 90%, merah >= 90%
        $kelasBar = static fn ($persen) => $persen >= 90 ? ' is-penuh' : ($persen >= 70 ? ' is-sedang' : '');
        $persenRak = static fn ($r) => (float) $r['ukuran_u'] > 0 ? min(100, round((float) $r['terpakai_u'] / (float) $r['ukuran_u'] * 100)) : 0;
        // Teks untuk pencarian (perangkat di visual rak & kartu data center)
        $cariPerangkat = static fn ($p) => strtolower(implode(' ', [$p['kode_perangkat'], $p['nama_perangkat'], $p['jenis_perangkat'], $p['merk'], $p['model'], $p['serial_number'], $p['ip_address'], $p['mac_address']]));

        $perangkatPerRak = [];
        foreach ($dataPerangkat as $p) {
            $perangkatPerRak[$p['data_rak_id']][] = $p;
        }
        $rakPerDc = [];
        foreach ($dataRak as $r) {
            $rakPerDc[$r['data_center_id']][] = $r;
        }

        // Warna per jenis perangkat
        $palet = ['#a9dfd8', '#fcb859', '#6cc5f7', '#f2c8ed', '#c4b5fd', '#86efac', '#fda4af', '#cbd5e1'];
        $jenis = array_values(array_unique(array_column($dataPerangkat, 'jenis_perangkat')));
        natcasesort($jenis);
        $warna = [];
        foreach (array_values($jenis) as $i => $nama) {
            $warna[$nama] = $palet[$i % count($palet)];
        }
        ?>
        <div class="rfo-page p-6">
            <!-- JUDUL -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic is-blue"><i class="ti ti-server-2"></i></span>
                    <h1 class="rfo-title">Data Rak DC</h1>
                </div>
            </div>

            <div class="rak-dash">
                <!-- ============ DATA CENTER + FILTER ============ -->
                <section class="card rak-panel">
                    <div class="rak-judul-panel">
                        <div>
                            <h2>Data Center</h2>
                            <p>Ringkasan Data Rak DC</p>
                        </div>
                        <div class="rak-page-tombol">
                            <div class="dc-aksi" id="dcAksi" hidden>
                                <button type="button" title="Lihat Data Center" aria-label="Lihat Data Center" onclick="lihat('dc', dcTerpilih())"><i class="ti ti-eye"></i></button>
                                <button type="button" title="Edit Data Center" aria-label="Edit Data Center" onclick="openForm('dc', dcTerpilih())"><i class="ti ti-edit"></i></button>
                                <button type="button" class="is-hapus" title="Hapus Data Center" aria-label="Hapus Data Center" onclick="confirmDelete('dc', dcTerpilih().id, dcTerpilih().kode_dc)"><i class="ti ti-trash"></i></button>
                            </div>
                            <button type="button" onclick="openForm('dc')" class="rfo-btn rfo-btn-blue"><i class="ti ti-plus"></i> Tambah</button>
                        </div>
                    </div>

                    <div class="rak-filter">
                        <div class="rfo-search">
                            <i class="ti ti-search"></i>
                            <input type="text" id="cariSemua" autocomplete="off" placeholder="Cari kode, nama, IP, SN..." />
                        </div>
                        <button type="button" class="rfo-ghost-btn" onclick="resetFilter()"><i class="ti ti-refresh"></i> Reset</button>
                    </div>

                    <?php if (empty($dataDc)) : ?>
                        <div class="rk-meta">Belum ada Data Center.</div>
                    <?php else : ?>
                        <div class="dc-pils">
                            <?php foreach ($dataDc as $i => $dc) : ?>
                                <button type="button"
                                    class="dc-pil"
                                    style="--c:<?= $palet[$i % count($palet)] ?>"
                                    title="Klik untuk menampilkan data Data Center ini"
                                    data-dc="<?= (int) $dc['id'] ?>"
                                    data-cari="<?= esc(strtolower(implode(' ', [$dc['kode_dc'], $dc['nama_dc'], $dc['gedung'], $dc['pic'], $dc['alamat']])), 'attr') ?>"
                                    data-row="<?= esc($json($dc), 'attr') ?>"
                                    onclick="pilihDc(<?= (int) $dc['id'] ?>)">
                                    <i class="ti ti-building"></i>
                                    <b><?= esc($dc['kode_dc']) ?></b>
                                    <span class="nama"><?= esc($dc['nama_dc']) ?></span>
                                    <small><?= (int) $dc['jumlah_rak'] ?> rak &middot; <?= (int) $dc['jumlah_perangkat'] ?> perangkat<?= (int) $dc['status'] === 1 ? '' : ' &middot; Nonaktif' ?></small>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </section>

                <!-- ============ DATA RAK ============ -->
                <section class="card rak-panel is-atas" id="panelRak">
                    <div class="rak-judul-panel">
                        <div>
                            <h2>Data Rak</h2>
                        </div>
                        <div class="rak-page-tombol">
                            <div class="rfo-search">
                                <i class="ti ti-search"></i>
                                <input type="text" id="cariRak" autocomplete="off" placeholder="Cari data..." />
                            </div>
                            <div id="exportRak"></div>
                            <button type="button" onclick="tambahRak()" class="rfo-btn rfo-btn-blue" title="Tambah Rak"><i class="ti ti-plus"></i> Tambah</button>
                        </div>
                    </div>
                    <div class="table-scroll">
                        <table id="tabelRak" class="rfo-table display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Aksi</th>
                                    <th>Nama</th>
                                    <th>Kapasitas yang Dipakai</th>
                                    <th>Data Center</th>
                                    <th>Ukuran</th>
                                    <th>Tinggi</th>
                                    <th>Lebar</th>
                                    <th>Kedalaman</th>
                                    <th>Lokasi Rak</th>
                                    <th>Kapasitas Listrik</th>
                                    <th>Jumlah Perangkat</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dataRak as $row) : ?>
                                    <?php
                                    // Hijau < 70%, kuning < 90%, merah >= 90%
                                    $persen = $persenRak($row);
                                    $warnaPakai = $persen >= 90 ? '#f87171' : ($persen >= 70 ? '#fcb859' : 'var(--rak-aksen)');
                                    ?>
                                    <tr data-dc="<?= (int) $row['data_center_id'] ?>" data-rak="<?= (int) $row['id'] ?>">
                                        <td class="col-bold"><a class="rfo-link" href="#rak-<?= (int) $row['id'] ?>" data-no-loader title="Saring & lihat di Visual Rak" onclick="pilihRak(<?= (int) $row['data_center_id'] ?>, <?= (int) $row['id'] ?>); return false;"><?= esc($row['kode_rak']) ?></a></td>
                                        <td>
                                            <button type="button" data-row="<?= esc($json($row), 'attr') ?>" onclick="openForm('rak', JSON.parse(this.dataset.row))" class="btn btn-sm btn-primary" title="Edit" aria-label="Edit <?= esc($row['kode_rak'], 'attr') ?>"><i class="ti ti-edit"></i></button>
                                            <button type="button" data-label="<?= esc($row['kode_rak'], 'attr') ?>" onclick="confirmDelete('rak', <?= (int) $row['id'] ?>, this.dataset.label)" class="btn btn-sm btn-danger" title="Hapus" aria-label="Hapus <?= esc($row['kode_rak'], 'attr') ?>"><i class="ti ti-trash"></i></button>
                                            <button type="button" data-row="<?= esc($json($row), 'attr') ?>" onclick="lihat('rak', JSON.parse(this.dataset.row))" class="btn btn-sm btn-info" title="Lihat" aria-label="Lihat <?= esc($row['kode_rak'], 'attr') ?>"><i class="ti ti-eye"></i></button>
                                        </td>
                                        <td><span class="rak-nama" title="<?= esc($row['nama_rak'], 'attr') ?>"><?= esc($row['nama_rak']) ?></span></td>
                                        <td data-order="<?= (int) $persen ?>">
                                            <span class="rak-kapsul" style="--c:<?= $warnaPakai ?>" title="<?= (int) $row['jumlah_perangkat'] ?> perangkat di rak ini">
                                                <span class="nilai"><span><?= esc($angka($row['terpakai_u'])) ?> / <?= esc($angka($row['ukuran_u'])) ?>U</span> <b><?= (int) $persen ?>%</b></span>
                                                <span class="trek" role="progressbar" aria-valuenow="<?= (int) $persen ?>" aria-valuemin="0" aria-valuemax="100"><span class="isi" style="width:<?= (int) $persen ?>%"></span></span>
                                            </span>
                                        </td>
                                        <td><?= esc($row['kode_dc'] . ' ' . $row['nama_dc']) ?></td>
                                        <td><?= esc($angka($row['ukuran_u'])) ?>U</td>
                                        <?php foreach (['tinggi_cm', 'lebar_cm', 'kedalaman_cm'] as $dimensi) : ?>
                                            <td><?= $row[$dimensi] === null ? '-' : esc($angka($row[$dimensi])) . ' cm' ?></td>
                                        <?php endforeach; ?>
                                        <td><?= $isi($row['lokasi_rak']) ?></td>
                                        <td><?= $isi($row['kapasitas_listrik']) ?></td>
                                        <td><?= (int) $row['jumlah_perangkat'] ?></td>
                                        <td><?= $status($row['status']) ?></td>
                                        <td><span class="rak-nama" title="<?= esc((string) $row['keterangan'], 'attr') ?>"><?= $isi($row['keterangan']) ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- ============ DATA PERANGKAT ============ -->
                <section class="card rak-panel is-gulir" id="panelPerangkat">
                    <div class="rak-judul-panel">
                        <div>
                            <h2>Data Perangkat</h2>
                        </div>
                        <button type="button" onclick="tambahPerangkat()" class="rfo-btn rfo-btn-blue"><i class="ti ti-plus"></i> Tambah</button>
                    </div>
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div id="lengthPerangkat"></div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <div class="rfo-search">
                                <i class="ti ti-search"></i>
                                <input type="text" id="cariPerangkat" autocomplete="off" placeholder="Cari data..." />
                            </div>
                            <div id="exportPerangkat"></div>
                        </div>
                    </div>
                    <div class="table-scroll">
                        <table id="tabelPerangkat" class="rfo-table display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Aksi</th>
                                    <th>Data Center</th>
                                    <th>Rak</th>
                                    <th>Posisi</th>
                                    <th>Kode</th>
                                    <th>Nama Perangkat</th>
                                    <th>Jenis</th>
                                    <th>Merk / Model</th>
                                    <th>Serial Number</th>
                                    <th>IP Address</th>
                                    <th>MAC Address</th>
                                    <th>Tanggal Pasang</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($dataPerangkat as $row) : ?>
                                    <tr data-dc="<?= (int) $row['data_center_id'] ?>" data-rak="<?= (int) $row['data_rak_id'] ?>">
                                        <td></td>
                                        <td>
                                            <button type="button" data-row="<?= esc($json($row), 'attr') ?>" onclick="openForm('perangkat', JSON.parse(this.dataset.row))" class="btn btn-sm btn-primary"><i class="ti ti-edit"></i> <span>Edit</span></button>
                                            <button type="button" data-label="<?= esc($row['kode_perangkat'], 'attr') ?>" onclick="confirmDelete('perangkat', <?= (int) $row['id'] ?>, this.dataset.label)" class="btn btn-sm btn-danger"><i class="ti ti-trash"></i> <span>Hapus</span></button>
                                            <button type="button" data-row="<?= esc($json($row), 'attr') ?>" onclick="lihat('perangkat', JSON.parse(this.dataset.row))" class="btn btn-sm btn-info" title="Lihat"><i class="ti ti-eye"></i> <span>View</span></button>
                                        </td>
                                        <td><?= esc($row['kode_dc'] . ' ' . $row['nama_dc']) ?></td>
                                        <td><a class="rfo-link" href="#rak-<?= (int) $row['data_rak_id'] ?>" data-no-loader title="Lihat di Visual Rak" onclick="fokusRak(<?= (int) $row['data_center_id'] ?>, <?= (int) $row['data_rak_id'] ?>); return false;"><?= esc($row['kode_rak']) ?></a></td>
                                        <td><?= $row['posisi_u'] !== null ? esc(DataPerangkatModel::labelU($row['posisi_u'], $row['ukuran_u'])) : '<span class="rb rb-amber">Belum ditempatkan</span>' ?></td>
                                        <td><?= esc($row['kode_perangkat']) ?></td>
                                        <td><?= esc($row['nama_perangkat']) ?></td>
                                        <td><?= esc($row['jenis_perangkat']) ?></td>
                                        <td><?= $isi(trim($row['merk'] . ' ' . $row['model'])) ?></td>
                                        <td><?= $isi($row['serial_number']) ?></td>
                                        <td><?= $isi($row['ip_address']) ?></td>
                                        <td><?= $isi($row['mac_address']) ?></td>
                                        <td><?= $row['tanggal_pasang'] ? esc(date('d/m/Y', strtotime($row['tanggal_pasang']))) : '-' ?></td>
                                        <td><?= $status($row['status']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>

                <!-- ============ VISUAL RAK ============ -->
                <section class="card rak-panel is-gulir" id="panelVisual">
                    <div class="rak-judul-panel" id="bagian-visual">
                        <div>
                            <h2>Visual Rak</h2>
                        </div>
                        <div class="rk-dc-aktif" id="dcVisual"></div>
                    </div>
                    <?php if (! empty($warna)) : ?>
                        <div class="rk-legend mb-4">
                            <?php foreach ($warna as $nama => $c) : ?>
                                <span style="--c:<?= $c ?>"><i></i><?= esc($nama) ?></span>
                            <?php endforeach; ?>
                            <span>Klik U kosong untuk menambah perangkat, klik perangkat untuk detail.</span>
                        </div>
                    <?php endif; ?>

                    <?php if (empty($dataDc)) : ?>
                        <div class="rk-kosong"><i class="ti ti-server-off"></i> Tambahkan Data Center dan Rak untuk melihat visualnya.</div>
                    <?php endif; ?>

                    <div class="rk-area" id="rkArea">
                        <?php foreach ($dataDc as $dc) : ?>
                            <div class="rk-grup<?= empty($rakPerDc[$dc['id']]) ? ' is-kosong' : '' ?>" data-dc="<?= (int) $dc['id'] ?>" data-nama="<?= esc($dc['kode_dc'] . ' - ' . $dc['nama_dc'], 'attr') ?>">
                                <div class="rk-grup-judul"><span><?= esc($dc['kode_dc'] . ' - ' . $dc['nama_dc']) ?></span></div>
                                <?php if (empty($rakPerDc[$dc['id']])) : ?>
                                    <div class="rk-meta">
                                        <?= esc($dc['kode_dc'] . ' - ' . $dc['nama_dc']) ?> belum punya rak.
                                        <button type="button" class="text-primary-500" onclick="openForm('rak', { data_center_id: <?= (int) $dc['id'] ?> })">Tambah Rak</button>
                                    </div>
                                <?php else : ?>
                                    <div class="rk-grid">
                                        <?php foreach ($rakPerDc[$dc['id']] as $rak) : ?>
                                            <?php
                                            $tinggi    = (float) $rak['ukuran_u'];
                                            $barisU    = (int) ceil($tinggi);
                                            $persen    = $persenRak($rak);
                                            $daftar    = $perangkatPerRak[$rak['id']] ?? [];
                                            $terpasang = array_filter($daftar, static fn ($p) => $p['posisi_u'] !== null);
                                            $lepas     = array_filter($daftar, static fn ($p) => $p['posisi_u'] === null);
                                            ?>
                                            <div class="rk-rack<?= (int) $rak['status'] === 1 ? '' : ' is-nonaktif' ?>" id="rak-<?= (int) $rak['id'] ?>" data-rak="<?= (int) $rak['id'] ?>">
                                                <div class="flex items-start justify-between gap-2">
                                                    <div class="min-w-0">
                                                        <div class="rk-judul truncate" title="<?= esc($rak['nama_rak'], 'attr') ?>"><?= esc($rak['kode_rak']) ?> &middot; <?= esc($rak['nama_rak']) ?></div>
                                                        <?php
                                                        $cm = static fn ($n) => $n === null ? '-' : $angka($n) . ' cm';
                                                        $infoRak = [
                                                            'Tinggi'            => $cm($rak['tinggi_cm']),
                                                            'Lebar'             => $cm($rak['lebar_cm']),
                                                            'Kedalaman'         => $cm($rak['kedalaman_cm']),
                                                            'Lokasi Rak'        => $rak['lokasi_rak'] ?: '-',
                                                            'Kapasitas Listrik' => $rak['kapasitas_listrik'] ?: '-',
                                                        ];
                                                        ?>
                                                        <dl class="rk-info">
                                                            <?php foreach ($infoRak as $judul => $nilai) : ?>
                                                                <div>
                                                                    <dt><?= $judul ?></dt>
                                                                    <dd title="<?= esc($nilai, 'attr') ?>"><?= esc($nilai) ?></dd>
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </dl>
                                                    </div>
                                                    <div class="flex gap-1 shrink-0">
                                                        <button type="button" class="rk-icon-btn" title="Detail rak" aria-label="Detail rak <?= esc($rak['kode_rak'], 'attr') ?>" data-row="<?= esc($json($rak), 'attr') ?>" onclick="lihat('rak', JSON.parse(this.dataset.row))"><i class="ti ti-eye"></i></button>
                                                    </div>
                                                </div>
                                                <div class="rk-meta mt-2">Terpakai <?= esc($angka($rak['terpakai_u'])) ?> dari <?= esc($angka($tinggi)) ?>U (<?= (int) $persen ?>%) &middot; <?= (int) $rak['jumlah_perangkat'] ?> perangkat</div>
                                                <span class="rak-bar<?= $kelasBar($persen) ?>"><span style="width:<?= (int) $persen ?>%"></span></span>

                                                <div class="rk-frame">
                                                    <div class="rk-frame-wrap">
                                                        <div class="rk-nomor kiri" aria-hidden="true">
                                                            <?php for ($u = $barisU; $u >= 1; $u--) : ?><span style="--i:<?= $u ?>"><?= $u ?></span><?php endfor; ?>
                                                        </div>
                                                        <div class="rk-nomor kanan" aria-hidden="true">
                                                            <?php for ($u = $barisU; $u >= 1; $u--) : ?><span style="--i:<?= $u ?>"><?= $u ?></span><?php endfor; ?>
                                                        </div>
                                                        <div class="rk-body" style="--n:<?= $tinggi ?>">
                                                            <?php for ($u = $barisU; $u >= 1; $u--) : ?>
                                                                <button type="button" class="rk-slot" style="--i:<?= $u ?>" title="Tambah perangkat di U<?= $u ?>" aria-label="Tambah perangkat di U<?= $u ?>" onclick="openForm('perangkat', { data_rak_id: <?= (int) $rak['id'] ?>, posisi_u: <?= $u ?>, ukuran_u: 1 })">+ Tambah di U<?= $u ?></button>
                                                            <?php endfor; ?>
                                                            <?php foreach ($terpasang as $p) : ?>
                                                                <button type="button"
                                                                    class="rk-dev<?= (int) $p['status'] === 1 ? '' : ' is-nonaktif' ?>"
                                                                    style="--p:<?= (float) $p['posisi_u'] - 1 ?>;--s:<?= (float) $p['ukuran_u'] ?>;--c:<?= $warna[$p['jenis_perangkat']] ?>"
                                                                    title="<?= esc($p['kode_perangkat'] . ' - ' . $p['nama_perangkat'] . ' (' . DataPerangkatModel::labelU($p['posisi_u'], $p['ukuran_u']) . ')', 'attr') ?>"
                                                                    data-cari="<?= esc($cariPerangkat($p), 'attr') ?>"
                                                                    data-row="<?= esc($json($p), 'attr') ?>"
                                                                    onclick="lihat('perangkat', JSON.parse(this.dataset.row))">
                                                                    <b><?= esc($p['kode_perangkat']) ?></b>
                                                                    <span class="nama"><?= esc($p['nama_perangkat']) ?></span>
                                                                    <span class="pos"><?= esc(DataPerangkatModel::labelU($p['posisi_u'], $p['ukuran_u'])) ?></span>
                                                                </button>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    </div>
                                                </div>

                                                <?php if (! empty($lepas)) : ?>
                                                    <div class="rk-lepas">
                                                        Belum ditempatkan (<?= count($lepas) ?>):<br>
                                                        <?php foreach ($lepas as $p) : ?>
                                                            <button type="button" class="rk-lepas-item" style="--c:<?= $warna[$p['jenis_perangkat']] ?>"
                                                                data-cari="<?= esc($cariPerangkat($p), 'attr') ?>"
                                                                data-row="<?= esc($json($p), 'attr') ?>"
                                                                onclick="lihat('perangkat', JSON.parse(this.dataset.row))"><?= esc($p['kode_perangkat']) ?></button>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <!-- ====== POP UP FORM DATA CENTER ====== -->
    <div id="modal_dc" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-3xl p-6 max-h-[90vh] overflow-y-auto rfo-form">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold" data-judul="Data Center"></h3>
                <button type="button" onclick="closeForm('dc')" aria-label="Tutup"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <form method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
                    <div class="rf-group"><label class="rf-label">Kode DC <span class="req">*</span></label><input class="rf-input" name="kode_dc" maxlength="50" required></div>
                    <div class="rf-group"><label class="rf-label">Nama DC <span class="req">*</span></label><input class="rf-input" name="nama_dc" maxlength="150" required></div>
                    <div class="rf-group"><label class="rf-label">Gedung</label><input class="rf-input" name="gedung" maxlength="100"></div>
                    <div class="rf-group"><label class="rf-label">Lantai</label><input class="rf-input" name="lantai" maxlength="50"></div>
                    <div class="rf-group"><label class="rf-label">PIC</label><input class="rf-input" name="pic" maxlength="150"></div>
                    <div class="rf-group"><label class="rf-label">Kontak</label><input class="rf-input" name="kontak" maxlength="30"></div>
                    <div class="rf-group md:col-span-2"><label class="rf-label">Status <span class="req">*</span></label><select class="rf-input" name="status"><option value="1">Aktif</option><option value="0">Nonaktif</option></select></div>
                    <div class="rf-group md:col-span-2"><label class="rf-label">Alamat</label><textarea class="rf-input" name="alamat" rows="2"></textarea></div>
                    <div class="rf-group md:col-span-2"><label class="rf-label">Keterangan</label><textarea class="rf-input" name="keterangan" rows="2"></textarea></div>
                </div>
                <div class="d-flex">
                    <button type="submit"><i class="ti ti-device-floppy"></i> Simpan</button>
                    <button type="button" class="btn-back" onclick="closeForm('dc')">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ====== POP UP FORM RAK ====== -->
    <div id="modal_rak" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-3xl p-6 max-h-[90vh] overflow-y-auto rfo-form">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold" data-judul="Rak"></h3>
                <button type="button" onclick="closeForm('rak')" aria-label="Tutup"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <form method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
                    <div class="rf-group md:col-span-2">
                        <label class="rf-label">Data Center <span class="req">*</span></label>
                        <select class="rf-input" name="data_center_id" required>
                            <option value="">-- Pilih Data Center --</option>
                            <?php foreach ($dataDc as $dc) : ?>
                                <option value="<?= (int) $dc['id'] ?>"><?= esc($dc['kode_dc'] . ' - ' . $dc['nama_dc']) ?><?= (int) $dc['status'] === 1 ? '' : ' (Nonaktif)' ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="rf-group"><label class="rf-label">Kode Rak <span class="req">*</span></label><input class="rf-input" name="kode_rak" maxlength="50" required></div>
                    <div class="rf-group"><label class="rf-label">Nama Rak <span class="req">*</span></label><input class="rf-input" name="nama_rak" maxlength="100" required></div>
                    <div class="rf-group"><label class="rf-label">Ukuran (U) <span class="req">*</span></label><input class="rf-input" type="number" name="ukuran_u" min="1" max="999.99" step="0.01" placeholder="42" required></div>
                    <div class="rf-group"><label class="rf-label">Tinggi (cm)</label><input class="rf-input" type="number" name="tinggi_cm" min="0" step="0.01"></div>
                    <div class="rf-group"><label class="rf-label">Lebar (cm)</label><input class="rf-input" type="number" name="lebar_cm" min="0" step="0.01"></div>
                    <div class="rf-group"><label class="rf-label">Kedalaman (cm)</label><input class="rf-input" type="number" name="kedalaman_cm" min="0" step="0.01"></div>
                    <div class="rf-group"><label class="rf-label">Lokasi Rak</label><input class="rf-input" name="lokasi_rak" maxlength="150"></div>
                    <div class="rf-group"><label class="rf-label">Kapasitas Listrik</label><input class="rf-input" name="kapasitas_listrik" maxlength="50" placeholder="contoh: 16A / 3.5 kW"></div>
                    <div class="rf-group md:col-span-2"><label class="rf-label">Status <span class="req">*</span></label><select class="rf-input" name="status"><option value="1">Aktif</option><option value="0">Nonaktif</option></select></div>
                    <div class="rf-group md:col-span-2"><label class="rf-label">Keterangan</label><textarea class="rf-input" name="keterangan" rows="2"></textarea></div>
                </div>
                <div class="d-flex">
                    <button type="submit"><i class="ti ti-device-floppy"></i> Simpan</button>
                    <button type="button" class="btn-back" onclick="closeForm('rak')">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ====== POP UP FORM PERANGKAT ====== -->
    <div id="modal_perangkat" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-3xl p-6 max-h-[90vh] overflow-y-auto rfo-form">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold" data-judul="Perangkat"></h3>
                <button type="button" onclick="closeForm('perangkat')" aria-label="Tutup"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <form method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
                    <div class="rf-group">
                        <label class="rf-label">Data Center <span class="req">*</span></label>
                        <select class="rf-input" id="p_dc" required>
                            <option value="">-- Pilih Data Center --</option>
                            <?php foreach ($dataDc as $dc) : ?>
                                <option value="<?= (int) $dc['id'] ?>"><?= esc($dc['kode_dc'] . ' - ' . $dc['nama_dc']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="rf-group">
                        <label class="rf-label">Rak <span class="req">*</span></label>
                        <select class="rf-input" name="data_rak_id" id="p_rak" required></select>
                    </div>
                    <div class="rf-group"><label class="rf-label">Ukuran (U) <span class="req">*</span></label><input class="rf-input" type="number" name="ukuran_u" min="0.01" max="999.99" step="0.01" required></div>
                    <div class="rf-group">
                        <label class="rf-label">Posisi U (paling bawah)</label>
                        <input class="rf-input" type="number" name="posisi_u" min="1" max="999.99" step="0.01" placeholder="Kosongkan jika belum ditempatkan">
                    </div>
                    <div class="md:col-span-2 rf-hint-rak mb-4" id="p_hint"></div>
                    <div class="rf-group"><label class="rf-label">Kode Perangkat <span class="req">*</span></label><input class="rf-input" name="kode_perangkat" maxlength="50" required></div>
                    <div class="rf-group"><label class="rf-label">Nama Perangkat <span class="req">*</span></label><input class="rf-input" name="nama_perangkat" maxlength="150" required></div>
                    <div class="rf-group"><label class="rf-label">Jenis Perangkat <span class="req">*</span></label><input class="rf-input" name="jenis_perangkat" maxlength="100" required></div>
                    <div class="rf-group"><label class="rf-label">Merk</label><input class="rf-input" name="merk" maxlength="100"></div>
                    <div class="rf-group"><label class="rf-label">Model</label><input class="rf-input" name="model" maxlength="100"></div>
                    <div class="rf-group"><label class="rf-label">Serial Number</label><input class="rf-input" name="serial_number" maxlength="150"></div>
                    <div class="rf-group"><label class="rf-label">IP Address</label><input class="rf-input" name="ip_address" maxlength="45"></div>
                    <div class="rf-group"><label class="rf-label">MAC Address</label><input class="rf-input" name="mac_address" maxlength="50" placeholder="AA:BB:CC:DD:EE:FF"></div>
                    <div class="rf-group md:col-span-2"><label class="rf-label">Tanggal Pasang</label><input class="rf-input" type="date" name="tanggal_pasang"></div>
                    <div class="rf-group md:col-span-2"><label class="rf-label">Status <span class="req">*</span></label><select class="rf-input" name="status"><option value="1">Aktif</option><option value="0">Nonaktif</option></select></div>
                    <div class="rf-group md:col-span-2"><label class="rf-label">Keterangan</label><textarea class="rf-input" name="keterangan" rows="2"></textarea></div>
                </div>
                <div class="d-flex">
                    <button type="submit"><i class="ti ti-device-floppy"></i> Simpan</button>
                    <button type="button" class="btn-back" onclick="closeForm('perangkat')">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ====== POP UP VIEW ====== -->
    <div id="viewModal" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4" onclick="if (event.target === this) closeViewModal()">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-2xl p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold" id="v_judul"></h3>
                <button type="button" onclick="closeViewModal()" aria-label="Tutup"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm" id="v_isi"></div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" id="v_hapus" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">Hapus</button>
                <button type="button" id="v_edit" class="px-4 py-2 bg-primary-500 hover:bg-primary-600 text-white rounded-lg">Edit</button>
                <button type="button" onclick="closeViewModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Tutup</button>
            </div>
        </div>
    </div>
    <script>
        const URL_DASAR = "<?= site_url('DataRakDC') ?>";
        const DATA_RAK = <?= $json(array_map(static fn ($r) => [
            'id' => (int) $r['id'],
            'dc' => (int) $r['data_center_id'],
            'kode' => $r['kode_rak'],
            'nama' => $r['nama_rak'],
            'kodeDc' => $r['kode_dc'],
            'u' => (float) $r['ukuran_u'],
        ], $dataRak)) ?>;
        const POSISI_RAK = <?= $json(array_values(array_map(static fn ($p) => [
            'id' => (int) $p['id'],
            'rak' => (int) $p['data_rak_id'],
            'awal' => (int) round((float) $p['posisi_u'] * 100),
            'akhir' => (int) round(((float) $p['posisi_u'] + (float) $p['ukuran_u']) * 100),
        ], array_filter($dataPerangkat, static fn ($p) => $p['posisi_u'] !== null)))) ?>;
        const ADA_DC = <?= empty($dataDc) ? 'false' : 'true' ?>;
        const JENIS = { dc: 'Data Center', rak: 'Rak', perangkat: 'Perangkat' };

        // 42.00 -> 42
        const angka = v => (v === null || v === undefined || v === '') ? '' : String(parseFloat(v));
        const labelU = (p, u) => {
            if (p === null || p === undefined || p === '') return 'Belum ditempatkan';
            const atas = parseFloat(p) + parseFloat(u) - 1;
            return atas > parseFloat(p) ? 'U' + angka(p) + ' - U' + angka(atas) : 'U' + angka(p);
        };

        // ====== FORM TAMBAH / EDIT ======
        function openForm(jenis, data) {
            data = data || {};
            if (jenis === 'rak' && !ADA_DC) return peringatan('Tambahkan Data Center lebih dulu.');
            if (jenis === 'perangkat' && !DATA_RAK.length) return peringatan('Tambahkan Rak lebih dulu.');

            const modal = document.getElementById('modal_' + jenis);
            const form = modal.querySelector('form');
            const edit = !!data.id;

            form.reset();
            form.querySelector('.rak-form-error')?.remove();
            form.elements.id.value = edit ? data.id : '';
            form.setAttribute('action', URL_DASAR + '/' + jenis + '/' + (edit ? 'update' : 'save'));

            const judul = modal.querySelector('[data-judul]');
            judul.textContent = (edit ? 'Edit ' : 'Tambah ') + judul.dataset.judul;

            if (jenis === 'perangkat') {
                const rakTerpilih = DATA_RAK.find(r => r.id === Number(data.data_rak_id));
                const dcId = rakTerpilih ? rakTerpilih.dc : (data.data_center_id || '');
                document.getElementById('p_dc').value = dcId;
                isiPilihanRak(dcId);
            }

            Object.keys(data).forEach(k => {
                const el = form.elements[k];
                if (!el || k === 'id' || data[k] === null || data[k] === undefined) return;
                el.value = el.type === 'number' ? angka(data[k]) : data[k];
            });

            if (jenis === 'perangkat') tampilkanSisaU(edit ? Number(data.id) : 0);

            modal.classList.remove('hidden');
        }

        function closeForm(jenis) {
            document.getElementById('modal_' + jenis).classList.add('hidden');
        }

        function peringatan(teks) {
            Swal.fire({ icon: 'warning', title: 'Belum Bisa', text: teks, confirmButtonColor: '#04a9f5' });
        }

        // Pilihan rak mengikuti Data Center yang dipilih
        function isiPilihanRak(dcId) {
            const sel = document.getElementById('p_rak');
            const daftar = DATA_RAK.filter(r => r.dc === Number(dcId));
            sel.innerHTML = '';
            sel.add(new Option(!dcId ? '-- Pilih Data Center dulu --' : (daftar.length ? '-- Pilih Rak --' : 'Data Center ini belum punya rak'), ''));
            daftar.forEach(r => sel.add(new Option(r.kode + ' - ' + r.nama + ' (' + angka(r.u) + 'U)', r.id)));
        }

        // Petunjuk rentang U yang masih kosong pada rak terpilih
        function tampilkanSisaU(idSendiri) {
            const hint = document.getElementById('p_hint');
            const rak = DATA_RAK.find(r => r.id === Number(document.getElementById('p_rak').value));
            if (!rak) {
                hint.textContent = '';
                return;
            }

            const batas = Math.round((rak.u + 1) * 100);
            const terisi = POSISI_RAK.filter(p => p.rak === rak.id && p.id !== idSendiri).sort((a, b) => a.awal - b.awal);
            const kosong = [];
            let kursor = 100;
            terisi.forEach(p => {
                if (p.awal > kursor) kosong.push([kursor, p.awal]);
                kursor = Math.max(kursor, p.akhir);
            });
            if (kursor < batas) kosong.push([kursor, batas]);

            const teks = kosong.map(([a, b]) => {
                const atas = (b - 100) / 100;
                return atas > a / 100 ? 'U' + a / 100 + '-U' + atas : 'U' + a / 100;
            });
            hint.textContent = 'Tinggi rak ' + angka(rak.u) + 'U. ' + (teks.length ? 'U kosong: ' + teks.join(', ') : 'Rak sudah penuh.');
        }

        document.getElementById('p_dc').addEventListener('change', function() {
            isiPilihanRak(this.value);
            tampilkanSisaU(Number(document.querySelector('#modal_perangkat [name=id]').value));
        });
        document.getElementById('p_rak').addEventListener('change', function() {
            tampilkanSisaU(Number(document.querySelector('#modal_perangkat [name=id]').value));
        });

        // ====== VIEW ======
        const STATUS_TEKS = s => Number(s) === 1 ? 'Aktif' : 'Nonaktif';
        const satuan = (v, s) => angka(v) === '' ? '' : angka(v) + s;
        const KOLOM_VIEW = {
            dc: [
                ['Kode DC', d => d.kode_dc], ['Nama DC', d => d.nama_dc], ['Gedung', d => d.gedung], ['Lantai', d => d.lantai],
                ['PIC', d => d.pic], ['Kontak', d => d.kontak], ['Status', d => STATUS_TEKS(d.status)], ['Dibuat', d => d.created_at],
                ['Alamat', d => d.alamat, true], ['Keterangan', d => d.keterangan, true],
            ],
            rak: [
                ['Data Center', d => d.kode_dc + ' - ' + d.nama_dc], ['Kode Rak', d => d.kode_rak], ['Nama Rak', d => d.nama_rak], ['Ukuran', d => satuan(d.ukuran_u, 'U')],
                ['Tinggi', d => satuan(d.tinggi_cm, ' cm')], ['Lebar', d => satuan(d.lebar_cm, ' cm')], ['Kedalaman', d => satuan(d.kedalaman_cm, ' cm')], ['Lokasi Rak', d => d.lokasi_rak],
                ['Kapasitas Listrik', d => d.kapasitas_listrik], ['Status', d => STATUS_TEKS(d.status)], ['Keterangan', d => d.keterangan, true],
            ],
            perangkat: [
                ['Data Center', d => d.kode_dc + ' - ' + d.nama_dc], ['Rak', d => d.kode_rak + ' - ' + d.nama_rak], ['Posisi', d => labelU(d.posisi_u, d.ukuran_u)], ['Ukuran', d => satuan(d.ukuran_u, 'U')],
                ['Kode Perangkat', d => d.kode_perangkat], ['Nama Perangkat', d => d.nama_perangkat], ['Jenis', d => d.jenis_perangkat], ['Merk', d => d.merk], ['Model', d => d.model], ['Serial Number', d => d.serial_number],
                ['IP Address', d => d.ip_address], ['MAC Address', d => d.mac_address], ['Tanggal Pasang', d => d.tanggal_pasang], ['Status', d => STATUS_TEKS(d.status)], ['Keterangan', d => d.keterangan, true],
            ],
        };
        const KODE_LABEL = { dc: 'kode_dc', rak: 'kode_rak', perangkat: 'kode_perangkat' };

        function lihat(jenis, d) {
            document.getElementById('v_judul').textContent = 'Detail ' + JENIS[jenis];
            const isi = document.getElementById('v_isi');
            isi.innerHTML = '';
            KOLOM_VIEW[jenis].forEach(([label, ambil, lebar]) => {
                const nilai = ambil(d);
                const kotak = document.createElement('div');
                if (lebar) kotak.className = 'md:col-span-2';
                const l = document.createElement('div');
                l.className = 'text-gray-500';
                l.textContent = label;
                const v = document.createElement('div');
                v.className = 'font-medium whitespace-pre-line';
                v.textContent = (nilai === null || nilai === undefined || String(nilai).trim() === '') ? '-' : nilai;
                kotak.append(l, v);
                isi.append(kotak);
            });

            document.getElementById('v_edit').onclick = () => {
                closeViewModal();
                openForm(jenis, d);
            };
            document.getElementById('v_hapus').onclick = () => {
                closeViewModal();
                confirmDelete(jenis, d.id, d[KODE_LABEL[jenis]]);
            };
            document.getElementById('viewModal').classList.remove('hidden');
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }

        // ====== DELETE ======
        function confirmDelete(jenis, id, label) {
            Swal.fire({
                title: 'Hapus ' + JENIS[jenis] + '?',
                text: label + ' akan dihapus dan tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    simpanScroll();
                    window.location.href = URL_DASAR + '/' + jenis + '/delete/' + id;
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

        // ====== FILTER ======
        // Pilihan disimpan di sessionStorage agar tetap sama setelah simpan/hapus (halaman dimuat ulang)
        const KUNCI_FILTER = 'dataRakDC.filter.v2';
        const KUNCI_SCROLL = 'dataRakDC.scroll';
        const FILTER = { dc: 0, rak: 0, cari: '', cariRak: '', cariPerangkat: '' };
        const TABEL = [];

        const simpanSesi = (k, v) => {
            try {
                sessionStorage.setItem(k, v);
            } catch (e) {}
        };
        const bacaSesi = k => {
            try {
                return sessionStorage.getItem(k);
            } catch (e) {
                return null;
            }
        };
        const PANEL_GULIR = ['panelPerangkat', 'panelVisual', 'panelRak'];
        const simpanScroll = () => simpanSesi(KUNCI_SCROLL, JSON.stringify({ y: window.scrollY, panel: PANEL_GULIR.map(id => document.getElementById(id).scrollTop) }));

        const cocokCari = (data, kata) => !kata.trim() || data.join(' ').toLowerCase().includes(kata.trim().toLowerCase());

        $.fn.dataTable.ext.search.push(function(settings, data, idx) {
            const tr = settings.aoData[idx].nTr;
            if (FILTER.dc && Number(tr.dataset.dc) !== FILTER.dc) return false;
            // kotak cari di panel Data Rak
            if (settings.nTable.id === 'tabelRak') return cocokCari(data, FILTER.cariRak);
            // Rak hanya menyaring tabel perangkat; daftar rak di Data Center ini tetap tampil semua
            if (settings.nTable.id === 'tabelPerangkat') {
                if (FILTER.rak && Number(tr.dataset.rak) !== FILTER.rak) return false;
                // kotak cari di panel Data Perangkat
                if (!cocokCari(data, FILTER.cariPerangkat)) return false;
            }
            return true;
        });

        // Yang tidak cocok dengan pencarian diredupkan (visual rak & kotak data center)
        function tandaiVisual() {
            const kata = FILTER.cari.trim().toLowerCase();
            document.getElementById('rkArea').classList.toggle('is-mencari', kata !== '');
            document.querySelectorAll('#rkArea [data-cari]').forEach(el => el.classList.toggle('is-cocok', kata !== '' && el.dataset.cari.includes(kata)));
            document.querySelectorAll('.dc-pil').forEach(el => el.classList.toggle('is-redup', kata !== '' && !el.dataset.cari.includes(kata)));
        }

        function terapkanFilter() {
            document.getElementById('cariSemua').value = FILTER.cari;
            document.getElementById('cariRak').value = FILTER.cariRak;
            document.getElementById('cariPerangkat').value = FILTER.cariPerangkat;

            document.querySelectorAll('.dc-pil').forEach(el => el.classList.toggle('is-aktif', Number(el.dataset.dc) === FILTER.dc));
            document.getElementById('dcAksi').hidden = !FILTER.dc;

            // Visual Rak hanya menampilkan rak terpilih; tabel Data Rak tetap lengkap
            const rakTerpilih = DATA_RAK.find(r => r.id === FILTER.rak);
            document.getElementById('rkArea').classList.toggle('is-satu-rak', !!rakTerpilih);
            document.querySelectorAll('.rk-grup').forEach(el => {
                const dc = Number(el.dataset.dc);
                el.hidden = rakTerpilih ? dc !== rakTerpilih.dc : (!!FILTER.dc && dc !== FILTER.dc);
            });
            document.querySelectorAll('.rk-rack').forEach(el => {
                const terpilih = Number(el.dataset.rak) === FILTER.rak;
                el.classList.toggle('is-fokus', terpilih);
                el.hidden = !!rakTerpilih && !terpilih;
            });
            $('#tabelRak').DataTable().rows().nodes().each(tr => {
                tr.classList.toggle('is-aktif', Number(tr.dataset.rak) === FILTER.rak);
            });
            tandaiVisual();
            perbaruiDcVisual();
            TABEL.forEach(t => t.search(FILTER.cari).draw());

            simpanSesi(KUNCI_FILTER, JSON.stringify(FILTER));
        }

        // Nama Data Center di sebelah judul Visual Rak mengikuti kelompok rak yang paling banyak terlihat di panel
        function perbaruiDcVisual() {
            const atas = document.getElementById('bagian-visual').getBoundingClientRect().bottom;
            const bawah = document.getElementById('panelVisual').getBoundingClientRect().bottom;
            const grup = [...document.querySelectorAll('.rk-grup')].filter(el => !el.hidden);
            let aktif = null;
            let terluas = -1;
            grup.forEach((el, i) => {
                el.classList.toggle('is-pertama', i === 0);
                if (el.classList.contains('is-kosong')) return;
                const r = el.getBoundingClientRect();
                const terlihat = Math.min(r.bottom, bawah) - Math.max(r.top, atas);
                if (terlihat > terluas) {
                    terluas = terlihat;
                    aktif = el;
                }
            });
            document.getElementById('dcVisual').textContent = aktif ? aktif.dataset.nama : '';
        }
        document.getElementById('panelVisual').addEventListener('scroll', perbaruiDcVisual, { passive: true });

        // Rak pertama pada Data Center yang dipilih; 0 kalau Data Center itu belum punya rak
        function rakPertama() {
            const rak = DATA_RAK.find(r => !FILTER.dc || r.dc === FILTER.dc);
            return rak ? rak.id : 0;
        }

        const dcTerpilih = () => JSON.parse(document.querySelector('.dc-pil[data-dc="' + FILTER.dc + '"]').dataset.row);

        // Data Center yang pertama diinput (kotak paling awal), patokan bawaan semua data di halaman
        function dcPertama() {
            const el = document.querySelector('.dc-pil');
            return el ? Number(el.dataset.dc) : 0;
        }

        // Klik Data Center yang sedang aktif = kembali ke Data Center pertama
        function pilihDc(id) {
            FILTER.dc = FILTER.dc === id ? dcPertama() : id;
            FILTER.rak = rakPertama();
            terapkanFilter();
        }

        // Geser panel Visual Rak ke awal rak tanpa menggeser halaman; di layar sempit panel tidak punya scroll sendiri
        function tampilkanRak(rak) {
            const el = document.getElementById('rak-' + rak);
            const panel = document.getElementById('panelVisual');
            if (!el) return;
            if (getComputedStyle(panel).overflowY === 'visible') {
                el.scrollIntoView({ block: 'start', inline: 'center' });
                return;
            }
            const kotak = el.getBoundingClientRect();
            const grid = el.closest('.rk-grid').getBoundingClientRect();
            panel.scrollTop += kotak.top - document.getElementById('bagian-visual').getBoundingClientRect().bottom - 12;
            el.closest('.rk-grid').scrollLeft += kotak.left - grid.left - (grid.width - kotak.width) / 2;
        }

        function fokusRak(dc, rak) {
            FILTER.dc = dc;
            FILTER.rak = rak;
            terapkanFilter();
            tampilkanRak(rak);
        }

        // Klik rak yang sedang aktif = kembali ke rak pertama
        function pilihRak(dc, rak) {
            if (FILTER.rak !== rak) return fokusRak(dc, rak);
            const pertama = DATA_RAK.find(r => r.id === rakPertama());
            if (pertama) fokusRak(pertama.dc, pertama.id);
        }

        function resetFilter() {
            Object.assign(FILTER, { dc: dcPertama(), cari: '', cariRak: '', cariPerangkat: '' });
            FILTER.rak = rakPertama();
            terapkanFilter();
        }

        function tambahRak() {
            openForm('rak', { data_center_id: FILTER.dc || '' });
        }

        function tambahPerangkat() {
            openForm('perangkat', { data_center_id: FILTER.dc || '', data_rak_id: FILTER.rak || '', ukuran_u: 1 });
        }

        $('#cariSemua').on('input', function() {
            FILTER.cari = this.value;
            terapkanFilter();
        });
        $('#cariRak').on('input', function() {
            FILTER.cariRak = this.value;
            terapkanFilter();
        });
        $('#cariPerangkat').on('input', function() {
            FILTER.cariPerangkat = this.value;
            terapkanFilter();
        });

        // Halaman dimuat ulang setelah simpan: kembali ke posisi scroll terakhir
        document.querySelectorAll('.fixed.inset-0 form').forEach(f => f.addEventListener('submit', simpanScroll));

        // ====== TABEL ======
        // aksi: kolom yang tidak ikut diexport; tersembunyi: kolom yang tetap ikut diexport & bisa dicari
        function buatTabel(selector, judulExport, areaExport, opsi) {
            const exportConfig = {
                title: judulExport,
                exportOptions: {
                    columns: idx => idx !== opsi.aksi,
                    format: {
                        body: function(data) {
                            const tmp = document.createElement('div');
                            tmp.innerHTML = data;
                            return tmp.textContent.trim();
                        }
                    }
                }
            };
            const tombolExport = (extend, ikon, teks, tambahan) => ({
                extend: extend,
                text: '<i class="ti ' + ikon + '"></i> ' + teks,
                ...exportConfig,
                ...tambahan,
                action: function(e, dt, button, config) {
                    if (isTableEmpty(dt)) return showEmptyExportAlert();
                    $.fn.dataTable.ext.buttons[extend].action.call(this, e, dt, button, config);
                }
            });

            const table = $(selector).DataTable({
                pageLength: opsi.pageLength,
                lengthMenu: [
                    [5, 10, 15, 25, 50, -1],
                    [5, 10, 15, 25, 50, "Semua"]
                ],
                autoWidth: false,
                order: [],
                columnDefs: [{
                    targets: opsi.nomor ? [0, opsi.aksi] : [opsi.aksi],
                    orderable: false,
                    searchable: false
                }, {
                    targets: opsi.tersembunyi || [],
                    visible: false
                }],
                dom: opsi.nomor ? "lBrtip" : "Brtip",
                buttons: [{
                    extend: 'collection',
                    text: '<i class="ti ti-download"></i> Export',
                    titleAttr: 'Export',
                    className: 'export-toggle',
                    buttons: [
                        tombolExport('copyHtml5', 'ti-copy', 'Copy'),
                        tombolExport('csvHtml5', 'ti-file-text', 'Export CSV'),
                        tombolExport('excelHtml5', 'ti-file-spreadsheet', 'Export Excel'),
                        tombolExport('pdfHtml5', 'ti-file-type-pdf', 'Export PDF', {
                            orientation: 'landscape',
                            pageSize: 'A4'
                        })
                    ]
                }],
                language: {
                    lengthMenu: "_MENU_",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 - 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ data)",
                    emptyTable: "Data belum tersedia",
                    zeroRecords: "Tidak ada data yang cocok",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Selanjutnya"
                    }
                }
            });

            if (opsi.nomor) {
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
            }

            const $wrap = $(table.table().container());
            $(areaExport).append($wrap.find('.dt-buttons'));
            if (opsi.areaLength) $(opsi.areaLength).append($wrap.find('.dataTables_length'));
            TABEL.push(table);
        }

        $(document).ready(function() {
            buatTabel('#tabelPerangkat', 'Data Rak DC - Data Perangkat', '#exportPerangkat', { aksi: 1, nomor: true, pageLength: 10, areaLength: '#lengthPerangkat' });
            buatTabel('#tabelRak', 'Data Rak DC - Data Rak', '#exportRak', { aksi: 1, pageLength: 5 });

            // Kembalikan filter terakhir; abaikan pilihan yang datanya sudah dihapus
            let tersimpan = {};
            try {
                tersimpan = JSON.parse(bacaSesi(KUNCI_FILTER)) || {};
            } catch (e) {}
            FILTER.dc = document.querySelector('.dc-pil[data-dc="' + Number(tersimpan.dc) + '"]') ? Number(tersimpan.dc) : dcPertama();
            FILTER.rak = DATA_RAK.some(r => r.id === Number(tersimpan.rak) && r.dc === FILTER.dc) ? Number(tersimpan.rak) : rakPertama();
            FILTER.cari = typeof tersimpan.cari === 'string' ? tersimpan.cari : '';
            FILTER.cariRak = typeof tersimpan.cariRak === 'string' ? tersimpan.cariRak : '';
            FILTER.cariPerangkat = typeof tersimpan.cariPerangkat === 'string' ? tersimpan.cariPerangkat : '';
            terapkanFilter();

            try {
                const scroll = JSON.parse(bacaSesi(KUNCI_SCROLL));
                if (scroll) {
                    window.scrollTo({ top: scroll.y, behavior: 'instant' });
                    PANEL_GULIR.forEach((id, i) => {
                        document.getElementById(id).scrollTop = scroll.panel[i];
                    });
                }
                sessionStorage.removeItem(KUNCI_SCROLL);
            } catch (e) {}
            perbaruiDcVisual();

            // Simpan gagal: buka lagi pop up berisi isian terakhir
            const old = <?= $json(session()->getFlashdata('form_old')) ?>;
            if (old) {
                openForm(old._form, old);

                // Notifikasi di atas tertutup pop up, jadi pesannya ditampilkan di dalam form
                const box = document.createElement('div');
                box.className = 'rak-form-error';
                box.setAttribute('role', 'alert');
                box.textContent = document.getElementById('errorAlert')?.querySelector('p')?.textContent || '';
                document.querySelector('#modal_' + old._form + ' form').prepend(box);
                document.getElementById('errorAlert')?.remove();
            }
        });
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