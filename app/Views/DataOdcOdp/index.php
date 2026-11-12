<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title>Data ODC &amp; ODP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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

        /* ===== TABEL: scroll di dalam card ===== */
        .rfo-page .table-scroll {
            overflow: auto;
        }

        .rfo-page .table-scroll thead th {
            position: sticky !important;
            top: 0;
            z-index: 2;
            box-shadow: inset 0 -1px 0 var(--rf-border);
        }

        /* ===== DATA ODC & ODP ===== */
        .oc-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 14px;
        }

        .oc-stat {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px 18px;
            border: 1px solid var(--rf-border);
            border-radius: 16px;
            background: var(--rf-card);
            box-shadow: var(--rf-shadow);
        }

        .oc-stat b {
            display: block;
            font-size: 22px;
            line-height: 1.15;
            color: var(--rf-head);
        }

        .oc-stat span:not(.oc-stat-ic) {
            font-size: 12.5px;
            color: var(--rf-muted);
        }

        .oc-stat-ic {
            flex: 0 0 auto;
            width: 46px;
            height: 46px;
            border-radius: 13px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 23px;
        }

        .oc-stat-ic.is-indigo {
            color: #4f46e5;
            background: #e0e7ff;
        }

        .oc-stat-ic.is-blue {
            color: #2f6bff;
            background: #e8efff;
        }

        .oc-stat-ic.is-green {
            color: #16a34a;
            background: #dcfce7;
        }

        .oc-stat-ic.is-amber {
            color: #d97706;
            background: #fef3c7;
        }

        html.dark .oc-stat-ic {
            background: rgba(255, 255, 255, .08);
        }

        .oc-tabs {
            display: inline-flex;
            flex-wrap: wrap;
            gap: 6px;
            padding: 5px;
            border: 1px solid var(--rf-border);
            border-radius: 12px;
            background: var(--rf-card);
        }

        .oc-tab {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 16px;
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 700;
            color: var(--rf-text);
            transition: background-color .15s, color .15s;
        }

        .oc-tab span {
            min-width: 22px;
            padding: 0 7px;
            border-radius: 999px;
            font-size: 11.5px;
            background: var(--rf-soft);
            color: var(--rf-muted);
        }

        .oc-tab:hover {
            background: var(--rf-soft);
        }

        .oc-tab.active {
            color: #fff;
            background: linear-gradient(135deg, #4f8cff, #2f6bff);
            box-shadow: 0 4px 12px rgba(47, 107, 255, .3);
        }

        .oc-tab.active span {
            color: #2f6bff;
            background: #fff;
        }

        .pg-odp {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 8px;
            margin: -4px 0 16px;
        }

        .pg-odp.hidden {
            display: none;
        }

        .pg-odp>div {
            padding: 8px 10px;
            border: 1px solid var(--rf-border);
            border-radius: 10px;
            background: var(--rf-soft);
        }

        .pg-odp span {
            display: block;
            font-size: 11px;
            color: var(--rf-muted);
        }

        .pg-odp b {
            font-size: 13px;
            color: var(--rf-head);
        }

        /* ===== TAMPILAN GIS (navbar Perangkat / Peta / Tabel) ===== */
        .og-eyebrow {
            font-size: 11.5px;
            font-weight: 800;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #2f6bff;
            margin-bottom: 4px;
        }

        .og-h2 {
            font-size: 17px;
            font-weight: 800;
            color: var(--rf-head);
        }

        .og-sub {
            font-size: 13px;
            color: var(--rf-muted);
        }

        .og-label {
            font-size: 11.5px;
            font-weight: 800;
            letter-spacing: .06em;
            text-transform: uppercase;
            color: var(--rf-muted);
            margin-bottom: 8px;
        }

        .og-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 16px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 700;
            color: #fff;
            border: 0;
            cursor: pointer;
            white-space: nowrap;
            transition: filter .15s, transform .15s;
        }

        .og-btn:hover {
            filter: brightness(1.08);
            transform: translateY(-1px);
        }

        .og-btn-dark {
            background: #111c33;
        }

        html.dark .og-btn-dark {
            background: #e2e8f0;
            color: #111c33;
        }

        .og-btn-blue {
            background: linear-gradient(135deg, #4f8cff, #2f6bff);
            box-shadow: 0 4px 12px rgba(47, 107, 255, .28);
        }

        .og-btn-sm {
            padding: 6px 12px;
            font-size: 12.5px;
            border-radius: 8px;
        }

        .og-btn-soft {
            background: rgba(47, 107, 255, .1);
            color: #2f6bff;
        }

        .og-btn-green {
            background: rgba(16, 185, 129, .12);
            color: #059669;
        }

        .og-btn-red {
            background: rgba(239, 68, 68, .1);
            color: #dc2626;
        }

        .og-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
        }

        .og-stat {
            padding: 14px 16px;
            border: 1px solid var(--rf-border);
            border-radius: 14px;
            background: var(--rf-card);
            box-shadow: var(--rf-shadow);
        }

        .og-stat span {
            display: block;
            font-size: 12.5px;
            color: var(--rf-muted);
        }

        .og-stat b {
            font-size: 24px;
            font-weight: 800;
            color: var(--rf-head);
        }

        .og-stat b small {
            font-size: 14px;
            color: var(--rf-muted);
            font-weight: 600;
        }

        .og-nav {
            display: flex;
            gap: 4px;
            padding: 5px;
            border: 1px solid var(--rf-border);
            border-radius: 14px;
            background: var(--rf-card);
            box-shadow: var(--rf-shadow);
            overflow-x: auto;
        }

        .og-nav-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            color: var(--rf-text);
            white-space: nowrap;
            transition: background-color .15s, color .15s;
        }

        .og-nav-btn i {
            font-size: 18px;
        }

        .og-nav-btn:hover {
            background: var(--rf-soft);
        }

        .og-nav-btn.active {
            color: #fff;
            background: linear-gradient(135deg, #4f8cff, #2f6bff);
            box-shadow: 0 4px 12px rgba(47, 107, 255, .3);
        }

        .og-view.hidden {
            display: none;
        }

        .og-card {
            border: 1px solid var(--rf-border);
            border-radius: 16px;
            background: var(--rf-card);
            box-shadow: var(--rf-shadow);
            overflow: hidden;
        }

        .og-card-head {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            padding: 16px 18px;
            border-bottom: 1px solid var(--rf-border);
        }

        /* pohon wilayah -> ODC -> ODP */
        .og-split {
            display: grid;
            grid-template-columns: 320px minmax(0, 1fr);
            min-height: 560px;
        }

        .og-tree-wrap {
            padding: 14px;
            border-right: 1px solid var(--rf-border);
        }

        .og-tree-search {
            position: relative;
            margin-bottom: 10px;
        }

        .og-tree-search i {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--rf-muted);
        }

        .og-tree-search input {
            width: 100%;
            padding: 9px 12px 9px 34px;
            border: 1px solid var(--rf-border);
            border-radius: 10px;
            background: var(--rf-soft);
            color: var(--rf-text);
            font-size: 13px;
            outline: none;
        }

        .og-tree-search input:focus {
            border-color: #2f6bff;
        }

        .og-tree {
            max-height: 640px;
            overflow-y: auto;
            padding-right: 4px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .og-tree-kosong {
            padding: 24px 8px;
            text-align: center;
            font-size: 13px;
            color: var(--rf-muted);
        }

        .ot-w {
            border: 1px solid var(--rf-border);
            border-radius: 12px;
            padding: 6px;
        }

        .ot-row {
            display: flex;
            align-items: center;
            gap: 4px;
            border-radius: 9px;
        }

        .ot-row.is-aktif {
            background: rgba(47, 107, 255, .1);
        }

        .ot-chev {
            width: 24px;
            height: 24px;
            flex: none;
            display: grid;
            place-items: center;
            border-radius: 6px;
            color: var(--rf-muted);
            transition: transform .15s;
        }

        .ot-chev:hover {
            background: var(--rf-soft);
        }

        .ot-chev.is-open {
            transform: rotate(90deg);
        }

        .ot-chev.is-none {
            visibility: hidden;
        }

        .ot-main {
            flex: 1;
            min-width: 0;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 4px;
            text-align: left;
            border-radius: 8px;
        }

        .ot-badge {
            flex: none;
            min-width: 34px;
            padding: 3px 6px;
            border-radius: 7px;
            font-size: 11px;
            font-weight: 800;
            text-align: center;
            color: #2f6bff;
            background: rgba(47, 107, 255, .1);
        }

        .ot-text {
            min-width: 0;
        }

        .ot-text b {
            display: block;
            font-size: 13px;
            color: var(--rf-head);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .ot-text small {
            display: block;
            font-size: 11.5px;
            color: var(--rf-muted);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .ot-add {
            flex: none;
            padding: 4px 8px;
            border: 1px solid var(--rf-border);
            border-radius: 7px;
            font-size: 11.5px;
            font-weight: 700;
            color: #2f6bff;
            background: var(--rf-card);
        }

        .ot-add:hover {
            border-color: #2f6bff;
        }

        .ot-add.is-green {
            color: #059669;
        }

        .ot-kids {
            margin: 4px 0 2px 14px;
            padding-left: 8px;
            border-left: 2px solid var(--rf-border);
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .ot-odp {
            display: block;
            width: 100%;
            padding: 6px 9px;
            border: 1px solid var(--rf-border);
            border-radius: 8px;
            text-align: left;
        }

        .ot-odp:hover {
            border-color: #93b4ff;
        }

        .ot-odp.is-aktif {
            border-color: #2f6bff;
            background: rgba(47, 107, 255, .08);
        }

        .ot-odp b {
            font-size: 12.5px;
            color: var(--rf-head);
        }

        .ot-odp small {
            display: block;
            font-size: 11px;
            color: var(--rf-muted);
        }

        /* detail kanan */
        .og-detail {
            padding: 18px;
            min-width: 0;
        }

        .og-crumb {
            font-size: 12px;
            font-weight: 600;
            color: #2f6bff;
            margin-bottom: 2px;
        }

        .og-crumb button:hover {
            text-decoration: underline;
        }

        .og-title {
            font-size: 20px;
            font-weight: 800;
            color: var(--rf-head);
        }

        .og-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin: 10px 0 14px;
        }

        .og-chip {
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            background: var(--rf-soft);
            color: var(--rf-text);
        }

        .og-chip.is-blue {
            background: rgba(47, 107, 255, .1);
            color: #2f6bff;
        }

        .og-chip.is-green {
            background: rgba(16, 185, 129, .12);
            color: #059669;
        }

        .og-chip.is-amber {
            background: rgba(245, 158, 11, .14);
            color: #b45309;
        }

        .og-chip.is-red {
            background: rgba(239, 68, 68, .1);
            color: #dc2626;
        }

        a.og-chip:hover {
            text-decoration: underline;
        }

        .og-mini {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 10px;
            padding-top: 14px;
            border-top: 1px solid var(--rf-border);
            margin-bottom: 18px;
        }

        .og-mini>div {
            padding: 12px 14px;
            border: 1px solid var(--rf-border);
            border-radius: 12px;
            background: var(--rf-soft);
        }

        .og-mini span {
            display: block;
            font-size: 12px;
            color: var(--rf-muted);
        }

        .og-mini b {
            font-size: 21px;
            font-weight: 800;
            color: var(--rf-head);
        }

        .og-mini b.is-blue {
            color: #2f6bff;
        }

        .og-mini b.is-green {
            color: #059669;
        }

        .og-sec-head {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
            margin-bottom: 12px;
        }

        .og-kosong {
            display: grid;
            place-items: center;
            gap: 8px;
            min-height: 420px;
            text-align: center;
            color: var(--rf-muted);
            font-size: 13.5px;
        }

        .og-kosong i {
            font-size: 46px;
            color: #93b4ff;
        }

        .og-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
        }

        .og-item {
            padding: 12px 14px;
            border: 1px solid var(--rf-border);
            border-radius: 12px;
            text-align: left;
            background: var(--rf-card);
            transition: border-color .15s, box-shadow .15s;
        }

        .og-item:hover {
            border-color: #93b4ff;
            box-shadow: 0 4px 14px rgba(47, 107, 255, .12);
        }

        .og-item b {
            display: block;
            font-size: 13.5px;
            color: var(--rf-head);
        }

        .og-item small {
            display: block;
            font-size: 12px;
            color: var(--rf-muted);
        }

        .og-bar {
            height: 6px;
            border-radius: 99px;
            background: var(--rf-soft);
            overflow: hidden;
            margin-top: 8px;
        }

        .og-bar i {
            display: block;
            height: 100%;
            background: #10b981;
        }

        .og-bar i.is-high {
            background: #f59e0b;
        }

        .og-bar i.is-full {
            background: #ef4444;
        }

        /* grid port ODP */
        .op-port {
            display: flex;
            flex-direction: column;
            padding: 12px;
            border: 1px solid var(--rf-border);
            border-radius: 12px;
            background: var(--rf-card);
        }

        .op-port.is-isi {
            border-color: #a7f3d0;
            background: #f0fdf7;
        }

        html.dark .op-port.is-isi {
            border-color: rgba(16, 185, 129, .45);
            background: rgba(16, 185, 129, .08);
        }

        .op-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }

        .op-top b {
            font-size: 13px;
            color: var(--rf-head);
        }

        .op-top span {
            font-size: 10.5px;
            font-weight: 800;
            letter-spacing: .04em;
            color: var(--rf-muted);
        }

        .op-port.is-isi .op-top span {
            color: #059669;
        }

        .op-nama {
            font-size: 13.5px;
            font-weight: 800;
            color: var(--rf-head);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .op-sub {
            font-size: 12px;
            color: var(--rf-muted);
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .op-aksi {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
            margin-top: auto;
            padding-top: 10px;
        }

        .op-aksi.is-1 {
            grid-template-columns: 1fr;
        }

        .op-aksi button {
            padding: 7px 6px;
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 700;
        }

        .op-edit,
        .op-isi {
            background: rgba(47, 107, 255, .1);
            color: #2f6bff;
        }

        .op-kosong {
            background: rgba(239, 68, 68, .1);
            color: #dc2626;
        }

        .op-edit:hover,
        .op-isi:hover {
            background: rgba(47, 107, 255, .18);
        }

        .op-kosong:hover {
            background: rgba(239, 68, 68, .18);
        }

        /* peta */
        .og-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            font-size: 12.5px;
            color: var(--rf-text);
        }

        .og-legend span {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .og-legend i {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .og-legend i.is-odc {
            background: #2563eb;
            border-radius: 3px;
        }

        .og-legend i.is-odp {
            background: #10b981;
            border-radius: 3px;
        }

        .og-legend i.is-plg {
            background: #f59e0b;
        }

        .og-legend i.is-jalur {
            width: 16px;
            height: 2px;
            border-radius: 0;
            background: #7c3aed;
        }

        .og-map-box {
            position: relative;
        }

        #ogMap {
            height: 600px;
            z-index: 0;
        }

        .og-map-kosong {
            position: absolute;
            left: 50%;
            top: 16px;
            transform: translateX(-50%);
            z-index: 500;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 13px;
            background: var(--rf-card);
            color: var(--rf-text);
            box-shadow: var(--rf-shadow);
        }

        .og-map-kosong.hidden {
            display: none;
        }

        .og-note {
            padding: 10px 18px;
            font-size: 12px;
            color: var(--rf-muted);
            border-top: 1px solid var(--rf-border);
        }

        /* marker peta: ikon pin lokasi (warna per jenis) */
        .om-pin {
            display: block;
            width: 100%;
            height: 100%;
            filter: drop-shadow(0 2px 3px rgba(0, 0, 0, .4));
            transition: transform .15s;
            transform-origin: 50% 100%;
        }

        .om-pin:hover {
            transform: scale(1.15);
        }

        .om-pin path {
            stroke: #fff;
            stroke-width: 1.3;
        }

        .om-pin.is-odc path, .og-leg-pin.is-odc path { fill: #2563eb; }
        .om-pin.is-odp path, .og-leg-pin.is-odp path { fill: #10b981; }
        .om-pin.is-plg path, .og-leg-pin.is-plg path { fill: #f59e0b; }

        .og-leg-pin {
            width: 15px;
            height: 15px;
        }

        .om-pop {
            min-width: 210px;
            font-family: Inter, sans-serif;
        }

        .om-pop b {
            font-size: 13.5px;
        }

        .om-pop .om-k {
            font-size: 12px;
            color: #64748b;
        }

        .om-pop .om-list {
            max-height: 150px;
            overflow-y: auto;
            margin: 6px 0;
            font-size: 11.5px;
            line-height: 1.55;
        }

        .om-pop .om-list .is-kosong {
            color: #94a3b8;
        }

        .om-pop button {
            margin-top: 6px;
            padding: 5px 10px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            background: #2f6bff;
        }

        @media (max-width: 1100px) {
            .og-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 900px) {
            .og-split {
                grid-template-columns: 1fr;
            }

            .og-tree-wrap {
                border-right: 0;
                border-bottom: 1px solid var(--rf-border);
            }

            .og-tree {
                max-height: 360px;
            }

            #ogMap {
                height: 460px;
            }
        }

        .oc-panel.hidden {
            display: none;
        }

        .oc-section {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 4px 0 12px;
            font-size: 14px;
            font-weight: 800;
            color: var(--rf-head);
        }

        .oc-section i {
            font-size: 18px;
            color: #2f6bff;
        }

        .oc-status {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .oc-status.is-aktif {
            color: #15803d;
            background: #dcfce7;
        }

        .oc-status.is-rencana {
            color: #1d4ed8;
            background: #dbeafe;
        }

        .oc-status.is-maint {
            color: #b45309;
            background: #fef3c7;
        }

        .oc-status.is-off {
            color: #b91c1c;
            background: #fee2e2;
        }

        html.dark .oc-status.is-aktif {
            color: #86efac;
            background: rgba(34, 197, 94, .16);
        }

        html.dark .oc-status.is-rencana {
            color: #93c5fd;
            background: rgba(59, 130, 246, .18);
        }

        html.dark .oc-status.is-maint {
            color: #fcd34d;
            background: rgba(245, 158, 11, .16);
        }

        html.dark .oc-status.is-off {
            color: #fca5a5;
            background: rgba(239, 68, 68, .16);
        }

        .oc-port {
            display: flex;
            align-items: baseline;
            gap: 6px;
            font-size: 12.5px;
        }

        .oc-port span {
            font-size: 11.5px;
            color: var(--rf-muted);
        }

        .oc-bar {
            width: 120px;
            height: 6px;
            margin-top: 4px;
            border-radius: 999px;
            overflow: hidden;
            background: var(--rf-soft);
        }

        .oc-bar i {
            display: block;
            height: 100%;
            background: #22c55e;
        }

        .oc-bar i.is-high {
            background: #f59e0b;
        }

        .oc-bar i.is-full {
            background: #ef4444;
        }

        .oc-map {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            color: #2f6bff !important;
            font-size: 12.5px;
        }

        .oc-map:hover {
            text-decoration: underline;
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
                        <li><a href="<?= site_url('IpPublic') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Data IP Addres</a></li>
                        <li><a href="<?= site_url('RekapDataMidi') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] text-white font-semibold">Rekap Data Toko</a></li>
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
            <?php
            $portTotal = array_sum(array_map('intval', array_column($odp, 'kapasitas_port')));
            $portPakai = array_sum(array_map('intval', array_column($odp, 'port_terpakai')));
            $kelasStatus = ['Aktif' => 'is-aktif', 'Rencana' => 'is-rencana', 'Maintenance' => 'is-maint', 'Tidak Aktif' => 'is-off'];
            $koordinat = static function ($r) {
                if ($r['latitude'] === null || $r['longitude'] === null) return '-';
                $t = rtrim(rtrim($r['latitude'], '0'), '.') . ', ' . rtrim(rtrim($r['longitude'], '0'), '.');
                return '<a href="https://www.google.com/maps?q=' . esc($r['latitude'] . ',' . $r['longitude'], 'url') . '" target="_blank" rel="noopener" class="oc-map" data-no-loader title="Buka di Google Maps"><i class="ti ti-map-pin"></i> ' . esc($t) . '</a>';
            };
            ?>
            <!-- JUDUL -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic is-sky"><i class="ti ti-box-model-2"></i></span>
                    <div>
                        <h1 class="rfo-title">Data ODC &amp; ODP</h1>
                        <div class="rfo-subtitle">Wilayah jaringan, ODC, ODP, dan pelanggan beserta kapasitas &amp; lokasinya</div>
                    </div>
                </div>
            </div>

            <!-- RINGKASAN -->
            <div class="og-stats mb-5">
                <div class="og-stat"><span>Total ODC</span><b><?= count($odc) ?></b></div>
                <div class="og-stat"><span>Total ODP</span><b><?= count($odp) ?></b></div>
                <div class="og-stat"><span>Pelanggan tercatat</span><b><?= count($pelanggan) ?></b></div>
                <div class="og-stat"><span>Port ODP terpakai</span><b><?= $portPakai ?> <small>/ <?= $portTotal ?></small></b></div>
            </div>

            <!-- NAVBAR (satu halaman) -->
            <nav class="og-nav mb-5" role="tablist">
                <button type="button" class="og-nav-btn" data-nav="perangkat"><i class="ti ti-sitemap"></i> Perangkat Jaringan</button>
                <button type="button" class="og-nav-btn" data-nav="peta"><i class="ti ti-map-2"></i> Peta Jaringan</button>
            </nav>

            <!-- ===== NAV: PERANGKAT JARINGAN ===== -->
            <div class="og-view og-card" data-view="perangkat">
                <div class="og-card-head">
                    <div>
                        <h2 class="og-h2">Wilayah &amp; Perangkat Jaringan</h2>
                        <div class="og-sub">Pilih wilayah, ODC, lalu ODP untuk mengelola port dan pelanggan.</div>
                    </div>
                    <button type="button" onclick="bukaForm('wilayah')" class="og-btn og-btn-blue"><i class="ti ti-plus"></i> Wilayah</button>
                </div>
                <div class="og-split">
                    <aside class="og-tree-wrap">
                        <div class="og-label">Wilayah dan Perangkat</div>
                        <div class="og-tree-search"><i class="ti ti-search"></i><input type="text" id="ogCari" placeholder="Cari wilayah, ODC, ODP..."></div>
                        <div class="og-tree" id="ogTree"></div>
                    </aside>
                    <section class="og-detail" id="ogDetail"></section>
                </div>
            </div>

            <!-- ===== NAV: PETA JARINGAN ===== -->
            <div class="og-view og-card hidden" data-view="peta">
                <div class="og-card-head">
                    <div>
                        <h2 class="og-h2">Peta Jaringan</h2>
                        <div class="og-sub">Klik marker untuk melihat detail dan tindakan.</div>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <div class="og-legend">
                            <span><svg class="og-leg-pin is-odc" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 1.5C7.3 1.5 3.5 5.2 3.5 9.8c0 6 7.6 12.3 7.9 12.6.3.3.9.3 1.2 0 .3-.3 7.9-6.6 7.9-12.6 0-4.6-3.8-8.3-8.5-8.3z"/><circle cx="12" cy="9.8" r="3.3" fill="#fff"/></svg> ODC</span>
                            <span><svg class="og-leg-pin is-odp" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 1.5C7.3 1.5 3.5 5.2 3.5 9.8c0 6 7.6 12.3 7.9 12.6.3.3.9.3 1.2 0 .3-.3 7.9-6.6 7.9-12.6 0-4.6-3.8-8.3-8.5-8.3z"/><circle cx="12" cy="9.8" r="3.3" fill="#fff"/></svg> ODP</span>
                            <span><svg class="og-leg-pin is-plg" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 1.5C7.3 1.5 3.5 5.2 3.5 9.8c0 6 7.6 12.3 7.9 12.6.3.3.9.3 1.2 0 .3-.3 7.9-6.6 7.9-12.6 0-4.6-3.8-8.3-8.5-8.3z"/><circle cx="12" cy="9.8" r="3.3" fill="#fff"/></svg> Pelanggan</span>
                            <span><i class="is-jalur"></i> Jalur FO ODC-ODP</span>
                        </div>
                        <select id="ogPetaWilayah" class="filter-select" title="Filter wilayah di peta">
                            <option value="">Semua Wilayah</option>
                            <?php foreach ($wilayah as $w) : ?>
                                <option value="<?= (int) $w['id'] ?>"><?= esc($w['kode_wilayah'] . ' - ' . $w['nama_wilayah']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="og-map-box">
                    <div id="ogMap"></div>
                    <div class="og-map-kosong hidden" id="ogMapKosong"><i class="ti ti-map-pin-off"></i> Belum ada ODC / ODP / pelanggan yang punya koordinat.</div>
                </div>
                <div class="og-note">Jalur garis adalah indikasi hubungan antar titik, bukan hasil pengukuran jalur kabel aktual.</div>
            </div>

        </div>
    </div>

    <!-- ====== POP UP FORM WILAYAH ====== -->
    <div id="modal_wilayah" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4" onclick="if (event.target === this) tutupForm('wilayah')">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-xl p-6 max-h-[92vh] overflow-y-auto rfo-form">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold" data-judul="Wilayah">Tambah Wilayah</h3>
                <button type="button" onclick="tutupForm('wilayah')" aria-label="Tutup"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <form method="POST" data-save="<?= site_url('DataOdcOdp/wilayah/save') ?>" data-update="<?= site_url('DataOdcOdp/wilayah/update') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
                    <div class="rf-group"><label class="rf-label">Kode Wilayah <span class="req">*</span></label><input class="rf-input" name="kode_wilayah" maxlength="30" required placeholder="Contoh: JKT01" style="text-transform:uppercase"></div>
                    <div class="rf-group"><label class="rf-label">Nama Wilayah <span class="req">*</span></label><input class="rf-input" name="nama_wilayah" maxlength="100" required placeholder="Contoh: Duren Tiga"></div>
                </div>
                <div class="rf-hint mb-2"><i class="ti ti-info-circle"></i> Dipakai untuk kode &amp; nama ODC / ODP. Bila diubah, kode &amp; nama ODC / ODP di wilayah ini ikut diperbarui.</div>
                <div class="d-flex">
                    <button type="submit"><i class="ti ti-device-floppy"></i> Simpan</button>
                    <button type="button" class="btn-back" onclick="tutupForm('wilayah')">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ====== POP UP FORM ODC & ODP (satu template, field khusus ditandai data-jenis) ====== -->
    <?php foreach (['odc' => 'ODC', 'odp' => 'ODP'] as $jenis => $LABEL) : ?>
        <div id="modal_<?= $jenis ?>" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4" onclick="if (event.target === this) tutupForm('<?= $jenis ?>')">
            <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-4xl p-6 max-h-[92vh] overflow-y-auto rfo-form">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-xl font-bold" data-judul="<?= $LABEL ?>">Tambah <?= $LABEL ?></h3>
                    <button type="button" onclick="tutupForm('<?= $jenis ?>')" aria-label="Tutup"><i class="ti ti-x text-2xl"></i></button>
                </div>
                <form method="POST" data-jenis="<?= $jenis ?>" data-save="<?= site_url('DataOdcOdp/' . $jenis . '/save') ?>" data-update="<?= site_url('DataOdcOdp/' . $jenis . '/update') ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id">

                    <div class="oc-section"><i class="ti ti-map-2"></i> Area Jaringan</div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4">
                        <div class="rf-group">
                            <label class="rf-label">Area Jaringan <span class="req">*</span></label>
                            <select class="rf-input" name="wilayah_id" required>
                                <option value="">-- Pilih Wilayah --</option>
                                <?php foreach ($wilayah as $w) : ?>
                                    <option value="<?= (int) $w['id'] ?>"><?= esc($w['kode_wilayah'] . ' - ' . $w['nama_wilayah']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="rf-group"><label class="rf-label">Kode <?= $LABEL ?> <span class="auto">(otomatis)</span></label><input class="rf-input" data-otomatis="kode" readonly placeholder="Pilih wilayah dulu"></div>
                        <div class="rf-group"><label class="rf-label">Nama <?= $LABEL ?> <span class="auto">(otomatis)</span></label><input class="rf-input" data-otomatis="nama" readonly placeholder="Pilih wilayah dulu"></div>
                    </div>

                    <div class="oc-section"><i class="ti ti-<?= $jenis === 'odc' ? 'box-model-2' : 'box' ?>"></i> Data <?= $LABEL ?></div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4">
                        <?php if ($jenis === 'odc') : ?>
                            <div class="rf-group"><label class="rf-label">Kapasitas Core <span class="req">*</span></label><input class="rf-input" type="number" name="kapasitas_core" min="1" step="1" required placeholder="Contoh: 48"></div>
                        <?php else : ?>
                            <div class="rf-group">
                                <label class="rf-label">ODC Induk <span class="req">*</span></label>
                                <select class="rf-input" name="odc_id" required>
                                    <option value="">-- Pilih ODC --</option>
                                    <?php
                                    $odcPerWilayah = [];
                                    foreach ($odc as $c) $odcPerWilayah[$c['kode_wilayah'] . ' - ' . $c['nama_wilayah']][] = $c;
                                    foreach ($odcPerWilayah as $grup => $daftar) : ?>
                                        <optgroup label="<?= esc($grup, 'attr') ?>">
                                            <?php foreach ($daftar as $c) : ?>
                                                <option value="<?= (int) $c['id'] ?>" data-wilayah="<?= (int) $c['wilayah_id'] ?>"><?= esc($c['kode_odc'] . ' (' . $c['nama_odc'] . ')') ?></option>
                                            <?php endforeach; ?>
                                        </optgroup>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="rf-group"><label class="rf-label">Kapasitas Port <span class="req">*</span></label><input class="rf-input" type="number" name="kapasitas_port" min="1" step="1" required placeholder="Contoh: 16"></div>
                            <div class="rf-group"><label class="rf-label">Port Terpakai <span class="auto">(otomatis)</span></label><input class="rf-input" type="number" name="port_terpakai" readonly placeholder="Dihitung dari pelanggan">
                                <div class="rf-hint" data-sisa></div>
                            </div>
                        <?php endif; ?>
                        <div class="rf-group">
                            <label class="rf-label">Status <span class="req">*</span></label>
                            <select class="rf-input" name="status" required>
                                <?php foreach ($opsiStatus as $s) : ?>
                                    <option value="<?= esc($s, 'attr') ?>"><?= esc($s) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="oc-section"><i class="ti ti-map-pin"></i> Lokasi</div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4">
                        <div class="rf-group"><label class="rf-label">Latitude</label><input class="rf-input" name="latitude" inputmode="decimal" placeholder="Contoh: -6.2550000"></div>
                        <div class="rf-group"><label class="rf-label">Longitude</label><input class="rf-input" name="longitude" inputmode="decimal" placeholder="Contoh: 106.8456000"></div>
                        <div class="rf-group"><label class="rf-label">Lokasi dan Area</label><input class="rf-input" name="lokasi_area" maxlength="255" placeholder="Contoh: Depan Indomaret / Cluster A"></div>
                        <div class="rf-group md:col-span-3"><label class="rf-label">Alamat</label><textarea class="rf-input" name="alamat" rows="2" placeholder="Masukkan alamat"></textarea></div>
                        <div class="rf-group md:col-span-3"><label class="rf-label">Keterangan</label><textarea class="rf-input" name="keterangan" rows="2" placeholder="Masukkan keterangan"></textarea></div>
                    </div>

                    <div class="d-flex">
                        <button type="submit"><i class="ti ti-device-floppy"></i> Simpan</button>
                        <button type="button" class="btn-back" onclick="tutupForm('<?= $jenis ?>')">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    <?php endforeach; ?>

    <!-- ====== POP UP FORM PELANGGAN ====== -->
    <div id="modal_pelanggan" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4" onclick="if (event.target === this) tutupForm('pelanggan')">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-3xl p-6 max-h-[92vh] overflow-y-auto rfo-form">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold" data-judul="Pelanggan">Tambah Pelanggan</h3>
                <button type="button" onclick="tutupForm('pelanggan')" aria-label="Tutup"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <form method="POST" id="formPelanggan" data-save="<?= site_url('DataOdcOdp/pelanggan/save') ?>" data-update="<?= site_url('DataOdcOdp/pelanggan/update') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id">

                <div class="oc-section"><i class="ti ti-box"></i> ODP</div>
                <div class="rf-group">
                    <label class="rf-label">Pilih ODP <span class="req">*</span></label>
                    <select class="rf-input" name="odp_id" required>
                        <option value="">-- Pilih ODP --</option>
                        <?php
                        $odpPerWilayah = [];
                        foreach ($odp as $p) $odpPerWilayah[$p['kode_wilayah'] . ' - ' . $p['nama_wilayah']][] = $p;
                        foreach ($odpPerWilayah as $grup => $daftar) : ?>
                            <optgroup label="<?= esc($grup, 'attr') ?>">
                                <?php foreach ($daftar as $p) : ?>
                                    <option value="<?= (int) $p['id'] ?>"><?= esc($p['kode_odp'] . ' (' . $p['nama_odp'] . ')') ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="rf-group">
                    <label class="rf-label">Port ODP <span class="req">*</span></label>
                    <select class="rf-input" name="port" required>
                        <option value="">-- Pilih ODP dulu --</option>
                    </select>
                </div>
                <!-- info ODP terpilih -->
                <div class="pg-odp hidden" id="pgOdpInfo">
                    <div><span>ODC Induk</span><b data-i="kode_odc">-</b></div>
                    <div><span>Area Jaringan</span><b data-i="area">-</b></div>
                    <div><span>Port (Terpakai / Kapasitas)</span><b data-i="port">-</b></div>
                    <div><span>Port Tersedia</span><b data-i="pelanggan">-</b></div>
                    <div><span>Status ODP</span><b data-i="status">-</b></div>
                </div>

                <div class="oc-section"><i class="ti ti-user"></i> Data Pelanggan</div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-4">
                    <div class="rf-group"><label class="rf-label">Kode Pelanggan <span class="req">*</span></label><input class="rf-input" name="kode_pelanggan" maxlength="50" required placeholder="Contoh: PLG-0001"></div>
                    <div class="rf-group"><label class="rf-label">Nama Pelanggan <span class="req">*</span></label><input class="rf-input" name="nama_pelanggan" maxlength="150" required placeholder="Nama pelanggan"></div>
                    <div class="rf-group"><label class="rf-label">Telepon</label><input class="rf-input" name="telepon" inputmode="tel" maxlength="20" placeholder="Contoh: 081234567890"></div>
                    <div class="rf-group"><label class="rf-label">Latitude</label><input class="rf-input" name="latitude" inputmode="decimal" placeholder="Contoh: -6.2550000"></div>
                    <div class="rf-group"><label class="rf-label">Longitude</label><input class="rf-input" name="longitude" inputmode="decimal" placeholder="Contoh: 106.8456000"></div>
                    <div class="rf-group md:col-span-3"><label class="rf-label">Alamat</label><textarea class="rf-input" name="alamat" rows="2" placeholder="Masukkan alamat pelanggan"></textarea></div>
                </div>

                <div class="d-flex">
                    <button type="submit"><i class="ti ti-device-floppy"></i> Simpan</button>
                    <button type="button" class="btn-back" onclick="tutupForm('pelanggan')">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <?php
    // tab aktif setelah simpan / hapus; bila gagal, pop up dibuka lagi dengan isian sebelumnya
    $tabFlash   = session()->getFlashdata('tab');
    $popupGagal = session()->getFlashdata('popup');
    $isianLama  = [];
    if (in_array($popupGagal, ['wilayah', 'odc', 'odp', 'pelanggan'], true)) {
        foreach (['id', 'kode_wilayah', 'nama_wilayah', 'wilayah_id', 'odc_id', 'kapasitas_core', 'kapasitas_port', 'port_terpakai', 'latitude', 'longitude', 'lokasi_area', 'status', 'alamat', 'keterangan', 'odp_id', 'kode_pelanggan', 'nama_pelanggan', 'telepon', 'port'] as $k) {
            $isianLama[$k] = old($k, '', false);
        }
    }
    // data untuk pratinjau kode otomatis di form
    $wilayahJs = array_map(fn($w) => ['id' => (int) $w['id'], 'kode' => $w['kode_wilayah'], 'nama' => $w['nama_wilayah']], $wilayah);
    $nomorJs = ['odc' => [], 'odp' => []];
    foreach (['odc' => $odc, 'odp' => $odp] as $j => $daftar) {
        foreach ($daftar as $r) $nomorJs[$j][] = ['id' => (int) $r['id'], 'wilayah' => (int) $r['wilayah_id'], 'nomor' => (int) $r['nomor']];
    }
    // data untuk form pelanggan: info ODP & cek kode dobel
    $odpJs = [];
    foreach ($odp as $p) {
        $odpJs[$p['id']] = [
            'kode' => $p['kode_odp'],
            'kode_odc' => $p['kode_odc'],
            'area' => $p['kode_wilayah'] . ' - ' . $p['nama_wilayah'],
            'kapasitas' => (int) $p['kapasitas_port'],
            'terpakai' => (int) $p['port_terpakai'],
            'pelanggan' => (int) $p['jumlah_pelanggan'],
            'status' => $p['status']
        ];
    }
    $pelangganJs = array_map(fn($r) => ['id' => (int) $r['id'], 'kode' => $r['kode_pelanggan'], 'nama' => $r['nama_pelanggan'], 'odp' => (int) $r['odp_id'], 'port' => (int) $r['port']], $pelanggan);
    ?>

    <script>
        const WILAYAH = <?= json_encode($wilayahJs, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        const NOMOR = <?= json_encode($nomorJs) ?>;
        const DATA_ODP = <?= json_encode((object) $odpJs, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        const DATA_PELANGGAN = <?= json_encode($pelangganJs, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        const DATA_GIS = <?= json_encode(['wilayah' => $wilayah, 'odc' => $odc, 'odp' => $odp, 'pelanggan' => $pelanggan], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        const KOLOM = {
            wilayah: ['kode_wilayah', 'nama_wilayah'],
            odc: ['wilayah_id', 'kapasitas_core', 'latitude', 'longitude', 'lokasi_area', 'status', 'alamat', 'keterangan'],
            odp: ['wilayah_id', 'odc_id', 'kapasitas_port', 'port_terpakai', 'latitude', 'longitude', 'lokasi_area', 'status', 'alamat', 'keterangan'],
            pelanggan: ['odp_id', 'kode_pelanggan', 'nama_pelanggan', 'telepon', 'latitude', 'longitude', 'alamat']
        };

        // ====== KODE & NAMA OTOMATIS (pratinjau; server tetap menentukan nomor saat simpan) ======
        function pratinjauKode(form) {
            const jenis = form.dataset.jenis;
            if (!jenis) return;
            const wid = Number(form.elements.wilayah_id.value);
            const id = Number(form.elements.id.value || 0);
            const kode = form.querySelector('[data-otomatis="kode"]');
            const nama = form.querySelector('[data-otomatis="nama"]');
            const w = WILAYAH.find(x => x.id === wid);
            if (!w) {
                kode.value = '';
                nama.value = '';
                return;
            }
            const milik = NOMOR[jenis].find(x => x.id === id);
            // edit di wilayah yang sama = nomor tetap; data baru / pindah wilayah = nomor berikutnya
            const nomor = milik && milik.wilayah === wid ? milik.nomor :
                Math.max(0, ...NOMOR[jenis].filter(x => x.wilayah === wid).map(x => x.nomor)) + 1;
            const no = String(nomor).padStart(3, '0');
            kode.value = jenis.toUpperCase() + '-' + w.kode + '-' + no;
            nama.value = jenis.toUpperCase() + '-' + w.nama + '-' + no;
        }

        function sisaPort(form) {
            const el = form.querySelector('[data-sisa]');
            if (!el) return;
            const kap = Number(form.elements.kapasitas_port.value || 0);
            const pakai = Number(form.elements.port_terpakai.value || 0);
            el.textContent = kap ? ('Sisa port: ' + Math.max(0, kap - pakai) + (pakai > kap ? ' — melebihi kapasitas!' : '')) : '';
            el.style.color = pakai > kap ? '#ef4444' : '';
        }

        // ====== POP UP FORM ======
        function bukaForm(jenis, data) {
            data = data || {};
            const modal = document.getElementById('modal_' + jenis);
            const form = modal.querySelector('form');
            const edit = !!data.id;
            if (jenis === 'pelanggan' && !Object.keys(DATA_ODP).length) {
                return Swal.fire({
                    icon: 'warning',
                    title: 'Belum Ada ODP',
                    text: 'Tambahkan ODP dulu lewat tombol + ODP pada ODC.',
                    confirmButtonColor: '#04a9f5'
                });
            }
            if (jenis !== 'wilayah' && jenis !== 'pelanggan' && !WILAYAH.length) {
                return Swal.fire({
                    icon: 'warning',
                    title: 'Belum Ada Wilayah',
                    text: 'Tambahkan wilayah dulu lewat tombol + Wilayah.',
                    confirmButtonColor: '#04a9f5'
                });
            }
            if (jenis === 'odp' && !NOMOR.odc.length) {
                return Swal.fire({
                    icon: 'warning',
                    title: 'Belum Ada ODC',
                    text: 'Tambahkan ODC dulu sebagai ODC induk.',
                    confirmButtonColor: '#04a9f5'
                });
            }
            form.reset();
            form.elements.id.value = edit ? data.id : '';
            form.setAttribute('action', edit ? form.dataset.update : form.dataset.save);
            const judul = modal.querySelector('[data-judul]');
            judul.textContent = (edit ? 'Edit ' : 'Tambah ') + judul.dataset.judul;
            KOLOM[jenis].forEach(k => {
                if (form.elements[k] && data[k] !== undefined && data[k] !== null && data[k] !== '') form.elements[k].value = data[k];
            });
            pratinjauKode(form);
            sisaPort(form);
            if (jenis === 'pelanggan') infoOdp(data.port);
            modal.classList.remove('hidden');
            setTimeout(() => {
                const el = form.querySelector('.rf-input:not([readonly])');
                if (el) el.focus();
            }, 50);
        }

        function tutupForm(jenis) {
            document.getElementById('modal_' + jenis).classList.add('hidden');
        }

        document.addEventListener('keydown', e => {
            if (e.key !== 'Escape') return;
            ['wilayah', 'odc', 'odp', 'pelanggan'].forEach(j => {
                if (!document.getElementById('modal_' + j).classList.contains('hidden')) tutupForm(j);
            });
        });

        document.querySelectorAll('form[data-jenis]').forEach(form => {
            form.elements.wilayah_id.addEventListener('change', () => pratinjauKode(form));
            if (form.elements.kapasitas_port) {
                ['kapasitas_port', 'port_terpakai'].forEach(k => form.elements[k].addEventListener('input', () => sisaPort(form)));
                // pilih ODC induk saat wilayah belum dipilih -> wilayah mengikuti ODC
                form.elements.odc_id.addEventListener('change', function() {
                    const opt = this.selectedOptions[0];
                    if (opt && opt.dataset.wilayah && !form.elements.wilayah_id.value) {
                        form.elements.wilayah_id.value = opt.dataset.wilayah;
                        pratinjauKode(form);
                    }
                });
            }
        });

        // ====== VALIDASI SEBELUM SUBMIT ======
        document.querySelector('#modal_wilayah form').addEventListener('submit', function(e) {
            const kode = this.elements.kode_wilayah.value.trim().toUpperCase().replace(/\s+/g, '');
            this.elements.kode_wilayah.value = kode;
            let pesan = '';
            if (!kode || !this.elements.nama_wilayah.value.trim()) pesan = 'Kode Wilayah dan Nama Wilayah wajib diisi.';
            else if (!/^[A-Z0-9]+([-_][A-Z0-9]+)*$/.test(kode)) pesan = 'Kode Wilayah hanya boleh huruf, angka, dan tanda - / _ (contoh: JKT01).';
            else {
                const id = Number(this.elements.id.value || 0);
                const sama = (a, b) => a.trim().toLowerCase() === b.trim().toLowerCase();
                const dobel = WILAYAH.find(w => w.id !== id && (sama(w.kode, kode) || sama(w.nama, this.elements.nama_wilayah.value)));
                if (dobel) pesan = 'Kode / Nama Wilayah sudah dipakai wilayah ' + dobel.kode + ' - ' + dobel.nama + '.';
            }
            if (pesan) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Belum Bisa Disimpan',
                    text: pesan,
                    confirmButtonColor: '#185a82'
                });
            }
        });

        document.querySelectorAll('form[data-jenis]').forEach(form => form.addEventListener('submit', function(e) {
            const f = this.elements;
            const pesan = [];
            if (!f.wilayah_id.value) pesan.push('Area Jaringan wajib dipilih.');
            if (f.kapasitas_core && !(Number(f.kapasitas_core.value) >= 1)) pesan.push('Kapasitas Core wajib diisi angka minimal 1.');
            if (f.odc_id && !f.odc_id.value) pesan.push('ODC Induk wajib dipilih.');
            if (f.kapasitas_port) {
                const kap = Number(f.kapasitas_port.value),
                    pakai = Number(f.port_terpakai.value || 0);
                if (!(kap >= 1)) pesan.push('Kapasitas Port wajib diisi angka minimal 1.');
                else if (pakai > kap) pesan.push('Kapasitas Port tidak boleh kurang dari port yang sudah terisi pelanggan.');
            }
            const lat = f.latitude.value.trim().replace(',', '.'),
                lng = f.longitude.value.trim().replace(',', '.');
            if ((lat || lng) && !(lat && lng && !isNaN(lat) && !isNaN(lng) && Math.abs(lat) <= 90 && Math.abs(lng) <= 180)) {
                pesan.push('Latitude (-90 s/d 90) dan Longitude (-180 s/d 180) harus diisi lengkap dengan angka yang benar.');
            }
            if (pesan.length) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Belum Bisa Disimpan',
                    html: pesan.map(p => '<div class="text-left">• ' + p + '</div>').join(''),
                    confirmButtonColor: '#185a82'
                });
            }
        }));

        // ====== PELANGGAN: info ODP terpilih, pilihan port, cek dobel ======
        const formPelanggan = document.getElementById('formPelanggan');

        function infoOdp(portPilih) {
            const f = formPelanggan.elements;
            const box = document.getElementById('pgOdpInfo');
            const o = DATA_ODP[f.odp_id.value];
            const sekarang = (portPilih !== undefined && portPilih !== null && portPilih !== '') ? Number(portPilih) : Number(f.port.value || 0);
            f.port.innerHTML = '<option value="">' + (o ? '-- Pilih Port --' : '-- Pilih ODP dulu --') + '</option>';
            box.classList.toggle('hidden', !o);
            if (!o) return;
            const id = Number(f.id.value || 0),
                dipakai = {};
            DATA_PELANGGAN.forEach(p => {
                if (p.odp === Number(f.odp_id.value) && p.id !== id) dipakai[p.port] = p.nama;
            });
            for (let i = 1; i <= o.kapasitas; i++) {
                const opt = new Option('Port ' + String(i).padStart(2, '0') + (dipakai[i] ? ' — ' + dipakai[i] : ''), i);
                opt.disabled = !!dipakai[i];
                f.port.add(opt);
            }
            if (sekarang && !dipakai[sekarang] && sekarang <= o.kapasitas) f.port.value = sekarang;
            const sisa = o.kapasitas - Object.keys(dipakai).length;
            const set = (k, v) => box.querySelector('[data-i="' + k + '"]').textContent = v;
            set('kode_odc', o.kode_odc);
            set('area', o.area);
            set('port', Object.keys(dipakai).length + ' / ' + o.kapasitas);
            set('pelanggan', sisa > 0 ? sisa + ' port' : 'Penuh');
            set('status', o.status);
            box.querySelector('[data-i="pelanggan"]').style.color = sisa > 0 ? '' : '#ef4444';
        }
        formPelanggan.elements.odp_id.addEventListener('change', () => infoOdp());
        formPelanggan.elements.telepon.addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9+]/g, '');
        });

        formPelanggan.addEventListener('submit', function(e) {
            const f = this.elements;
            const pesan = [];
            if (!f.odp_id.value) pesan.push('ODP wajib dipilih.');
            else if (!f.port.value) pesan.push('Port ODP wajib dipilih (port yang sudah terisi tidak bisa dipilih).');
            if (!f.kode_pelanggan.value.trim()) pesan.push('Kode Pelanggan wajib diisi.');
            if (!f.nama_pelanggan.value.trim()) pesan.push('Nama Pelanggan wajib diisi.');
            const tel = f.telepon.value.replace(/\D/g, '');
            if (tel && tel.length < 8) pesan.push('Nomor Telepon minimal 8 angka.');
            const lat = f.latitude.value.trim().replace(',', '.'),
                lng = f.longitude.value.trim().replace(',', '.');
            if ((lat || lng) && !(lat && lng && !isNaN(lat) && !isNaN(lng) && Math.abs(lat) <= 90 && Math.abs(lng) <= 180)) {
                pesan.push('Latitude (-90 s/d 90) dan Longitude (-180 s/d 180) harus diisi lengkap dengan angka yang benar.');
            }
            const id = Number(f.id.value || 0);
            const dobel = DATA_PELANGGAN.find(p => p.id !== id && p.kode.toLowerCase() === f.kode_pelanggan.value.trim().toLowerCase());
            if (dobel) pesan.push('Kode Pelanggan "' + dobel.kode + '" sudah dipakai pelanggan "' + dobel.nama + '".');
            if (pesan.length) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Data Belum Bisa Disimpan',
                    html: pesan.map(p => '<div class="text-left">• ' + escapeHtml(p) + '</div>').join(''),
                    confirmButtonColor: '#185a82'
                });
            }
        });

        function escapeHtml(s) {
            return (s == null ? '' : String(s)).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        // ====== HAPUS ======
        function hapus(jenis, id, nama, dipakai) {
            const label = {
                wilayah: 'Wilayah',
                odc: 'ODC',
                odp: 'ODP',
                pelanggan: 'Pelanggan'
            } [jenis];
            if (dipakai > 0) {
                const teks = {
                    wilayah: 'masih punya ' + dipakai + ' ODC / ODP',
                    odc: 'masih menjadi induk ' + dipakai + ' ODP',
                    odp: 'masih punya ' + dipakai + ' pelanggan di port-nya'
                } [jenis];
                return Swal.fire({
                    icon: 'warning',
                    title: 'Belum Bisa Dihapus',
                    text: label + ' "' + nama + '" ' + teks + '. Pindahkan / hapus dulu datanya.',
                    confirmButtonColor: '#04a9f5'
                });
            }
            Swal.fire({
                title: 'Hapus ' + label + '?',
                text: '"' + nama + '" akan dihapus dan tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then(r => {
                if (r.isConfirmed) window.location.href = '<?= site_url('DataOdcOdp/') ?>' + jenis + '/delete/' + id;
            });
        }

        <?php if (in_array($popupGagal, ['wilayah', 'odc', 'odp', 'pelanggan'], true)) : ?>
            document.addEventListener('DOMContentLoaded', () => bukaForm(<?= json_encode($popupGagal) ?>, <?= json_encode($isianLama, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>));
        <?php endif; ?>
    </script>

    <script>
        // ====== TAMPILAN GIS: navbar Perangkat Jaringan / Peta Jaringan (satu halaman) ======
        const angka = v => (v === null || v === undefined || v === '') ? null : Number(v);
        const G = {
            wilayah: DATA_GIS.wilayah.map(w => Object.assign({}, w, {
                id: +w.id
            })),
            odc: DATA_GIS.odc.map(c => Object.assign({}, c, {
                id: +c.id,
                wilayah_id: +c.wilayah_id
            })),
            odp: DATA_GIS.odp.map(p => Object.assign({}, p, {
                id: +p.id,
                wilayah_id: +p.wilayah_id,
                odc_id: +p.odc_id,
                kapasitas_port: +p.kapasitas_port
            })),
            pelanggan: DATA_GIS.pelanggan.map(g => Object.assign({}, g, {
                id: +g.id,
                odp_id: +g.odp_id,
                port: +g.port
            }))
        };
        const cari = (arr, id) => arr.find(x => x.id === Number(id));
        const odcDi = wid => G.odc.filter(c => c.wilayah_id === wid);
        const odpDiOdc = cid => G.odp.filter(p => p.odc_id === cid);
        const odpDiWil = wid => G.odp.filter(p => p.wilayah_id === wid);
        const plgDi = pid => G.pelanggan.filter(g => g.odp_id === pid);
        const plgDiWil = wid => {
            const ids = odpDiWil(wid).map(p => p.id);
            return G.pelanggan.filter(g => ids.includes(g.odp_id));
        };
        const e = escapeHtml;
        const titik = r => (angka(r.latitude) !== null && angka(r.longitude) !== null) ? [Number(r.latitude), Number(r.longitude)] : null;
        const gmaps = r => titik(r) ? 'https://www.google.com/maps?q=' + Number(r.latitude) + ',' + Number(r.longitude) : null;
        const pad2 = n => String(n).padStart(2, '0');
        const kelasStatus = s => ({
            'Aktif': 'is-green',
            'Rencana': 'is-blue',
            'Maintenance': 'is-amber',
            'Tidak Aktif': 'is-red'
        })[s] || '';
        const terpakai = p => plgDi(p.id).length;

        // ====== NAVBAR ======
        const KUNCI_NAV = 'dataOdcOdp.nav';

        function pilihNav(nav) {
            document.querySelectorAll('.og-nav-btn').forEach(b => b.classList.toggle('active', b.dataset.nav === nav));
            document.querySelectorAll('.og-view').forEach(v => v.classList.toggle('hidden', v.dataset.view !== nav));
            try {
                sessionStorage.setItem(KUNCI_NAV, nav);
            } catch (err) {}
            if (nav === 'peta') siapkanPeta();
        }
        document.querySelectorAll('.og-nav-btn').forEach(b => b.addEventListener('click', () => pilihNav(b.dataset.nav)));

        // ====== PILIHAN (wilayah / odc / odp) — disimpan di URL & sessionStorage ======
        const KUNCI_PILIH = 'dataOdcOdp.pilih';
        let pilihan = {
            jenis: null,
            id: null
        };
        const terbuka = new Set();

        function konteks() {
            const o = {
                w: null,
                c: null,
                p: null
            };
            if (pilihan.jenis === 'odp') o.p = cari(G.odp, pilihan.id);
            if (o.p) o.c = cari(G.odc, o.p.odc_id);
            if (pilihan.jenis === 'odc') o.c = cari(G.odc, pilihan.id);
            if (o.c) o.w = cari(G.wilayah, o.c.wilayah_id);
            if (pilihan.jenis === 'wilayah') o.w = cari(G.wilayah, pilihan.id);
            return o;
        }

        function pilih(jenis, id, simpan = true) {
            const daftar = {
                wilayah: G.wilayah,
                odc: G.odc,
                odp: G.odp
            } [jenis];
            pilihan = daftar && cari(daftar, id) ? {
                jenis,
                id: Number(id)
            } : {
                jenis: null,
                id: null
            };
            const k = konteks();
            if (k.w) terbuka.add('w' + k.w.id);
            if (k.c && pilihan.jenis === 'odp') terbuka.add('c' + k.c.id);
            if (pilihan.jenis === 'odc') terbuka.add('c' + pilihan.id);
            renderTree();
            renderDetail();
            if (!simpan) return;
            try {
                sessionStorage.setItem(KUNCI_PILIH, JSON.stringify(pilihan));
            } catch (err) {}
            const url = new URL(location.href);
            ['wilayah', 'odc_id', 'odp_id'].forEach(x => url.searchParams.delete(x));
            if (k.w) url.searchParams.set('wilayah', k.w.kode_wilayah);
            if (k.c) url.searchParams.set('odc_id', k.c.id);
            if (k.p) url.searchParams.set('odp_id', k.p.id);
            history.replaceState(null, '', url);
        }

        // ====== POHON KIRI ======
        const inputCari = document.getElementById('ogCari');
        inputCari.addEventListener('input', renderTree);

        function renderTree() {
            const q = inputCari.value.trim().toLowerCase();
            const cocok = (...s) => !q || s.some(x => String(x || '').toLowerCase().includes(q));
            const k = konteks();
            let html = '';
            G.wilayah.forEach(w => {
                const wCocok = cocok(w.kode_wilayah, w.nama_wilayah);
                const anakOdc = [];
                odcDi(w.id).forEach(c => {
                    const cCocok = wCocok || cocok(c.kode_odc, c.nama_odc);
                    const odps = odpDiOdc(c.id);
                    const tampilOdp = odps.filter(p => cCocok || cocok(p.kode_odp, p.nama_odp));
                    if (!cCocok && !tampilOdp.length) return;
                    const buka = q ? tampilOdp.length > 0 : terbuka.has('c' + c.id);
                    const aktifC = pilihan.jenis === 'odc' && pilihan.id === c.id;
                    anakOdc.push('<div>' +
                        '<div class="ot-row' + (aktifC ? ' is-aktif' : '') + '">' +
                        '<button type="button" class="ot-chev' + (buka ? ' is-open' : '') + (odps.length ? '' : ' is-none') + '" data-toggle="c' + c.id + '" aria-label="Buka ODC"><i class="ti ti-chevron-right"></i></button>' +
                        '<button type="button" class="ot-main" data-pilih="odc:' + c.id + '"><span class="ot-text"><b>' + e(c.kode_odc) + '</b><small>' + e(c.nama_odc) + ' · ' + odps.length + ' ODP · ' + e(c.status) + '</small></span></button>' +
                        '<button type="button" class="ot-add is-green" data-add="odp:' + c.id + '">+ ODP</button>' +
                        '</div>' +
                        (buka && tampilOdp.length ? '<div class="ot-kids">' + tampilOdp.map(p => {
                            const aktifP = pilihan.jenis === 'odp' && pilihan.id === p.id;
                            return '<button type="button" class="ot-odp' + (aktifP ? ' is-aktif' : '') + '" data-pilih="odp:' + p.id + '"><b>' + e(p.nama_odp) + '</b><small>' + e(p.kode_odp) + ' · ' + terpakai(p) + '/' + p.kapasitas_port + ' port</small></button>';
                        }).join('') + '</div>' : '') +
                        '</div>');
                });
                if (!wCocok && !anakOdc.length) return;
                const jumlahOdc = odcDi(w.id).length;
                const buka = q ? anakOdc.length > 0 : terbuka.has('w' + w.id);
                const aktifW = pilihan.jenis === 'wilayah' && pilihan.id === w.id;
                html += '<div class="ot-w">' +
                    '<div class="ot-row' + (aktifW ? ' is-aktif' : '') + '">' +
                    '<button type="button" class="ot-chev' + (buka ? ' is-open' : '') + (jumlahOdc ? '' : ' is-none') + '" data-toggle="w' + w.id + '" aria-label="Buka wilayah"><i class="ti ti-chevron-right"></i></button>' +
                    '<button type="button" class="ot-main" data-pilih="wilayah:' + w.id + '"><span class="ot-badge">' + e(w.kode_wilayah) + '</span><span class="ot-text"><b>' + e(w.nama_wilayah) + '</b><small>' + jumlahOdc + ' ODC · ' + odpDiWil(w.id).length + ' ODP</small></span></button>' +
                    '<button type="button" class="ot-add" data-add="odc:' + w.id + '">+ ODC</button>' +
                    '</div>' +
                    (buka && anakOdc.length ? '<div class="ot-kids">' + anakOdc.join('') + '</div>' : '') +
                    '</div>';
            });
            document.getElementById('ogTree').innerHTML = html || '<div class="og-tree-kosong">' +
                (G.wilayah.length ? 'Tidak ada yang cocok dengan pencarian.' : 'Belum ada wilayah. Klik <b>+ Wilayah</b> untuk mulai.') + '</div>';
        }

        document.getElementById('ogTree').addEventListener('click', ev => {
            const t = ev.target.closest('[data-toggle], [data-pilih], [data-add]');
            if (!t) return;
            if (t.dataset.toggle) {
                const key = t.dataset.toggle;
                terbuka.has(key) ? terbuka.delete(key) : terbuka.add(key);
                return renderTree();
            }
            if (t.dataset.pilih) {
                const [jenis, id] = t.dataset.pilih.split(':');
                return pilih(jenis, id);
            }
            const [jenis, id] = t.dataset.add.split(':');
            if (jenis === 'odc') bukaForm('odc', {
                wilayah_id: id
            });
            else {
                const c = cari(G.odc, id);
                bukaForm('odp', {
                    odc_id: c.id,
                    wilayah_id: c.wilayah_id
                });
            }
        });

        // ====== DETAIL KANAN ======
        const kotakMini = (label, nilai, kelas) => '<div><span>' + label + '</span><b class="' + (kelas || '') + '">' + nilai + '</b></div>';
        const tombol = (teks, ikon, kelas, attr) => '<button type="button" class="og-btn og-btn-sm ' + kelas + '" ' + attr + '><i class="ti ' + ikon + '"></i> ' + teks + '</button>';
        const remah = k => {
            const b = [];
            if (k.w) b.push('<button type="button" data-pilih="wilayah:' + k.w.id + '">' + e(k.w.nama_wilayah) + '</button>');
            if (k.c) b.push('<button type="button" data-pilih="odc:' + k.c.id + '">' + e(k.c.kode_odc) + '</button>');
            if (k.p) b.push('<span>' + e(k.p.kode_odp) + '</span>');
            return '<div class="og-crumb">' + b.join(' › ') + '</div>';
        };
        const barPort = (isi, kap) => {
            const persen = kap ? Math.round(isi / kap * 100) : 0;
            return '<div class="og-bar"><i class="' + (persen >= 100 ? 'is-full' : persen >= 80 ? 'is-high' : '') + '" style="width:' + Math.min(100, persen) + '%"></i></div>';
        };

        function renderDetail() {
            const box = document.getElementById('ogDetail');
            const k = konteks();
            if (pilihan.jenis === 'odp' && k.p) box.innerHTML = detailOdp(k);
            else if (pilihan.jenis === 'odc' && k.c) box.innerHTML = detailOdc(k);
            else if (pilihan.jenis === 'wilayah' && k.w) box.innerHTML = detailWilayah(k);
            else box.innerHTML = '<div class="og-kosong"><div><i class="ti ti-sitemap"></i><div class="mt-2">' +
                (G.wilayah.length ? 'Pilih wilayah, ODC, atau ODP di sebelah kiri untuk melihat detail.' : 'Mulai dengan menambahkan wilayah, lalu ODC dan ODP.') + '</div></div></div>';
        }

        function detailWilayah(k) {
            const w = k.w,
                odcs = odcDi(w.id),
                odps = odpDiWil(w.id);
            const kap = odps.reduce((s, p) => s + p.kapasitas_port, 0),
                isi = plgDiWil(w.id).length;
            return '<div class="og-sec-head"><div><div class="og-crumb">Wilayah</div><div class="og-title">' + e(w.kode_wilayah + ' - ' + w.nama_wilayah) + '</div></div>' +
                '<div class="flex flex-wrap gap-2">' +
                tombol('Edit', 'ti-edit', 'og-btn-soft', 'data-edit="wilayah:' + w.id + '"') +
                tombol('Hapus', 'ti-trash', 'og-btn-red', 'data-hapus="wilayah:' + w.id + '"') +
                tombol('ODC', 'ti-plus', 'og-btn-blue', 'data-add="odc:' + w.id + '"') + '</div></div>' +
                '<div class="og-mini">' + kotakMini('ODC', odcs.length) + kotakMini('ODP', odps.length) + kotakMini('Pelanggan', isi, 'is-blue') + kotakMini('Port tersedia', Math.max(0, kap - isi), 'is-green') + '</div>' +
                '<div class="og-sec-head"><div><div class="og-h2">Daftar ODC</div><div class="og-sub">Klik ODC untuk melihat ODP di dalamnya.</div></div></div>' +
                (odcs.length ? '<div class="og-grid">' + odcs.map(c => {
                    const p = odpDiOdc(c.id);
                    return '<button type="button" class="og-item" data-pilih="odc:' + c.id + '"><b>' + e(c.kode_odc) + '</b><small>' + e(c.nama_odc) + '</small><small>' + p.length + ' ODP · ' + c.kapasitas_core + ' core · ' + e(c.status) + '</small></button>';
                }).join('') + '</div>' : '<div class="og-sub">Belum ada ODC di wilayah ini.</div>');
        }

        function detailOdc(k) {
            const c = k.c,
                odps = odpDiOdc(c.id);
            const kap = odps.reduce((s, p) => s + p.kapasitas_port, 0),
                isi = odps.reduce((s, p) => s + terpakai(p), 0);
            const map = gmaps(c);
            return '<div class="og-sec-head"><div>' + remah(k) + '<div class="og-title">' + e(c.nama_odc) + '</div><div class="og-sub">' + e([c.lokasi_area, c.alamat].filter(Boolean).join(' · ') || (k.w.kode_wilayah + ' - ' + k.w.nama_wilayah)) + '</div></div>' +
                '<div class="flex flex-wrap gap-2">' +
                tombol('Edit', 'ti-edit', 'og-btn-soft', 'data-edit="odc:' + c.id + '"') +
                tombol('Hapus', 'ti-trash', 'og-btn-red', 'data-hapus="odc:' + c.id + '"') +
                tombol('ODP', 'ti-plus', 'og-btn-blue', 'data-add="odp:' + c.id + '"') + '</div></div>' +
                '<div class="og-chips"><span class="og-chip">' + e(c.kode_odc) + '</span><span class="og-chip">Kapasitas ' + c.kapasitas_core + ' core</span><span class="og-chip ' + kelasStatus(c.status) + '">' + e(c.status) + '</span>' +
                (map ? '<a class="og-chip is-blue" href="' + map + '" target="_blank" rel="noopener" data-no-loader><i class="ti ti-map-pin"></i> Buka Google Maps</a>' : '') + '</div>' +
                '<div class="og-mini">' + kotakMini('ODP', odps.length) + kotakMini('Kapasitas port', kap) + kotakMini('Port terpakai', isi, 'is-blue') + kotakMini('Port tersedia', Math.max(0, kap - isi), 'is-green') + '</div>' +
                '<div class="og-sec-head"><div><div class="og-h2">Daftar ODP</div><div class="og-sub">Klik ODP untuk mengelola port dan pelanggan.</div></div></div>' +
                (odps.length ? '<div class="og-grid">' + odps.map(p => '<button type="button" class="og-item" data-pilih="odp:' + p.id + '"><b>' + e(p.nama_odp) + '</b><small>' + e(p.kode_odp) + ' · ' + e(p.status) + '</small><small>' + terpakai(p) + ' / ' + p.kapasitas_port + ' port terpakai</small>' + barPort(terpakai(p), p.kapasitas_port) + '</button>').join('') + '</div>' :
                    '<div class="og-sub">Belum ada ODP di ODC ini.</div>');
        }

        function detailOdp(k) {
            const p = k.p,
                isi = {},
                map = gmaps(p);
            plgDi(p.id).forEach(g => isi[g.port] = g);
            const pakai = Object.keys(isi).length;
            let port = '';
            for (let i = 1; i <= p.kapasitas_port; i++) {
                const g = isi[i];
                port += g ?
                    '<div class="op-port is-isi"><div class="op-top"><b>Port ' + pad2(i) + '</b><span>TERPAKAI</span></div>' +
                    '<div class="op-nama" title="' + e(g.nama_pelanggan) + '">' + e(g.nama_pelanggan) + '</div><div class="op-sub">ID: ' + e(g.kode_pelanggan) + '</div>' +
                    '<div class="op-sub">' + (g.telepon ? e(g.telepon) : 'Telepon belum diisi') + '</div>' +
                    '<div class="op-aksi"><button type="button" class="op-edit" data-edit-plg="' + g.id + '">Edit</button><button type="button" class="op-kosong" data-kosong="' + g.id + '">Kosongkan</button></div></div>' :
                    '<div class="op-port"><div class="op-top"><b>Port ' + pad2(i) + '</b><span>KOSONG</span></div>' +
                    '<div class="op-nama">Belum ada pelanggan</div><div class="op-sub">Siap digunakan</div>' +
                    '<div class="op-aksi is-1"><button type="button" class="op-isi" data-isi="' + i + '">+ Isi Port</button></div></div>';
            }
            return '<div class="og-sec-head"><div>' + remah(k) + '<div class="og-title">' + e(p.nama_odp) + '</div><div class="og-sub">' + e([p.lokasi_area, p.alamat].filter(Boolean).join(' · ') || (k.w.kode_wilayah + ' - ' + k.w.nama_wilayah)) + '</div></div>' +
                '<div class="flex flex-wrap gap-2">' +
                tombol('Edit', 'ti-edit', 'og-btn-soft', 'data-edit="odp:' + p.id + '"') +
                tombol('Hapus', 'ti-trash', 'og-btn-red', 'data-hapus="odp:' + p.id + '"') +
                tombol('Download Ekspor CSV', 'ti-download', 'og-btn-green', 'data-csv="' + p.id + '"') + '</div></div>' +
                '<div class="og-chips"><span class="og-chip">' + e(p.kode_odp) + '</span><span class="og-chip">Kapasitas ' + p.kapasitas_port + ' port</span><span class="og-chip is-blue">' + pakai + ' terpakai</span>' +
                '<span class="og-chip is-green">' + Math.max(0, p.kapasitas_port - pakai) + ' tersedia</span><span class="og-chip ' + kelasStatus(p.status) + '">' + e(p.status) + '</span>' +
                (map ? '<a class="og-chip" href="' + map + '" target="_blank" rel="noopener" data-no-loader><i class="ti ti-map-pin"></i> Buka Google Maps</a>' : '') + '</div>' +
                '<div class="og-mini">' + kotakMini('Kapasitas port', p.kapasitas_port) + kotakMini('Port terpakai', pakai, 'is-blue') + kotakMini('Port tersedia', Math.max(0, p.kapasitas_port - pakai), 'is-green') + '</div>' +
                '<div class="og-sec-head"><div><div class="og-h2">Pengelolaan Port &amp; Pelanggan</div><div class="og-sub">Setiap port ditampilkan, termasuk port yang masih kosong.</div></div>' +
                (pakai < p.kapasitas_port ? tombol('Isi Port', 'ti-plus', 'og-btn-blue', 'data-isi="0"') : '<span class="og-chip is-red">ODP penuh</span>') + '</div>' +
                '<div class="og-grid">' + port + '</div>';
        }

        document.getElementById('ogDetail').addEventListener('click', ev => {
            const t = ev.target.closest('[data-pilih], [data-edit], [data-hapus], [data-add], [data-isi], [data-edit-plg], [data-kosong], [data-csv]');
            if (!t) return;
            const d = t.dataset;
            if (d.pilih) {
                const [j, id] = d.pilih.split(':');
                return pilih(j, id);
            }
            if (d.edit) {
                const [j, id] = d.edit.split(':');
                return bukaForm(j, cari(G[j], id));
            }
            if (d.hapus) {
                const [j, id] = d.hapus.split(':');
                const r = cari(G[j], id);
                if (j === 'wilayah') return hapus('wilayah', r.id, r.kode_wilayah + ' - ' + r.nama_wilayah, odcDi(r.id).length + odpDiWil(r.id).length);
                if (j === 'odc') return hapus('odc', r.id, r.kode_odc, odpDiOdc(r.id).length);
                return hapus('odp', r.id, r.kode_odp, plgDi(r.id).length);
            }
            if (d.add) {
                const [j, id] = d.add.split(':');
                if (j === 'odc') return bukaForm('odc', {
                    wilayah_id: id
                });
                const c = cari(G.odc, id);
                return bukaForm('odp', {
                    odc_id: c.id,
                    wilayah_id: c.wilayah_id
                });
            }
            const p = konteks().p;
            if (d.isi !== undefined) {
                let port = Number(d.isi);
                if (!port) {
                    const dipakai = plgDi(p.id).map(g => g.port);
                    for (port = 1; dipakai.includes(port); port++);
                }
                return bukaForm('pelanggan', {
                    odp_id: p.id,
                    port
                });
            }
            if (d.editPlg) return bukaForm('pelanggan', cari(G.pelanggan, d.editPlg));
            if (d.kosong) {
                const g = cari(G.pelanggan, d.kosong);
                return Swal.fire({
                    icon: 'warning',
                    title: 'Kosongkan Port ' + pad2(g.port) + '?',
                    text: 'Data pelanggan "' + g.kode_pelanggan + ' - ' + g.nama_pelanggan + '" akan dihapus dan tidak dapat dikembalikan.',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Kosongkan',
                    cancelButtonText: 'Batal'
                }).then(r => {
                    if (r.isConfirmed) window.location.href = '<?= site_url('DataOdcOdp/pelanggan/delete/') ?>' + g.id;
                });
            }
            if (d.csv) return unduhCsv(cari(G.odp, d.csv));
        });

        // CSV port & pelanggan satu ODP (dibuat di browser)
        function unduhCsv(p) {
            const sel = v => '"' + String(v == null ? '' : v).replace(/"/g, '""') + '"';
            const isi = {};
            plgDi(p.id).forEach(g => isi[g.port] = g);
            const baris = [
                ['Port', 'Status', 'Kode Pelanggan', 'Nama Pelanggan', 'Telepon', 'Latitude', 'Longitude', 'Alamat']
            ];
            for (let i = 1; i <= p.kapasitas_port; i++) {
                const g = isi[i];
                baris.push(g ? ['Port ' + pad2(i), 'Terpakai', g.kode_pelanggan, g.nama_pelanggan, g.telepon, g.latitude, g.longitude, g.alamat] : ['Port ' + pad2(i), 'Kosong', '', '', '', '', '', '']);
            }
            const blob = new Blob(['﻿' + baris.map(b => b.map(sel).join(',')).join('\r\n')], {
                type: 'text/csv;charset=utf-8'
            });
            const a = document.createElement('a');
            a.href = URL.createObjectURL(blob);
            a.download = p.kode_odp + '.csv';
            a.setAttribute('data-no-loader', '');
            document.body.appendChild(a);
            a.click();
            setTimeout(() => {
                URL.revokeObjectURL(a.href);
                a.remove();
            }, 500);
        }

        // ====== PETA JARINGAN (Leaflet) ======
        let peta = null,
            lapisan = null;
        const filterPeta = document.getElementById('ogPetaWilayah');
        filterPeta.addEventListener('change', gambarPeta);

        function siapkanPeta() {
            if (!window.L) {
                document.getElementById('ogMapKosong').textContent = 'Peta gagal dimuat (periksa koneksi internet).';
                return document.getElementById('ogMapKosong').classList.remove('hidden');
            }
            if (!peta) {
                peta = L.map('ogMap');
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap'
                }).addTo(peta);
                lapisan = L.featureGroup().addTo(peta);
                gambarPeta();
            }
            setTimeout(() => peta.invalidateSize(), 60);
        }

        function lihatDetail(jenis, id) {
            pilihNav('perangkat');
            pilih(jenis, id);
            document.getElementById('ogDetail').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }

        function gambarPeta() {
            if (!peta) return;
            lapisan.clearLayers();
            const wid = Number(filterPeta.value || 0);
            const ikut = r => !wid || r.wilayah_id === wid;
            // ikon pin lokasi; ujung bawah pin = titik koordinat
            const ukuranPin = { 'is-odc': 36, 'is-odp': 32, 'is-plg': 26 };
            const ikon = kelas => {
                const s = ukuranPin[kelas];
                return L.divIcon({
                    className: '',
                    html: '<svg class="om-pin ' + kelas + '" viewBox="0 0 24 24"><path d="M12 1.5C7.3 1.5 3.5 5.2 3.5 9.8c0 6 7.6 12.3 7.9 12.6.3.3.9.3 1.2 0 .3-.3 7.9-6.6 7.9-12.6 0-4.6-3.8-8.3-8.5-8.3z"/><circle cx="12" cy="9.8" r="3.3" fill="#fff"/></svg>',
                    iconSize: [s, s],
                    iconAnchor: [s / 2, s * 0.94],
                    popupAnchor: [0, -s * 0.85]
                });
            };
            const tombolDetail = (j, id) => '<button type="button" onclick="lihatDetail(\'' + j + '\',' + id + ')">Lihat Detail</button>';
            let ada = 0;

            G.odp.filter(ikut).forEach(p => {
                const a = titik(p),
                    c = cari(G.odc, p.odc_id),
                    b = c && titik(c);
                if (a && b) L.polyline([b, a], {
                    color: '#7c3aed',
                    weight: 2.5,
                    opacity: .85
                }).addTo(lapisan);
            });
            G.pelanggan.forEach(g => {
                const p = cari(G.odp, g.odp_id),
                    t = titik(g);
                if (!t || !p || !ikut(p)) return;
                ada++;
                L.marker(t, {
                        icon: ikon('is-plg')
                    }).addTo(lapisan)
                    .bindPopup('<div class="om-pop"><b>' + e(g.nama_pelanggan) + '</b><div class="om-k">ID: ' + e(g.kode_pelanggan) + '</div><div class="om-k">' + e(p.kode_odp) + ' · Port ' + pad2(g.port) + '</div><div class="om-k">' + (g.telepon ? e(g.telepon) : 'Telepon belum diisi') + '</div>' + tombolDetail('odp', p.id) + '</div>');
            });
            G.odc.filter(ikut).forEach(c => {
                const t = titik(c);
                if (!t) return;
                ada++;
                L.marker(t, {
                        icon: ikon('is-odc'),
                        zIndexOffset: 200
                    }).addTo(lapisan)
                    .bindPopup('<div class="om-pop"><b>' + e(c.kode_odc) + '</b><div class="om-k">' + e(c.nama_odc) + '</div><div class="om-k">' + c.kapasitas_core + ' core · ' + odpDiOdc(c.id).length + ' ODP · ' + e(c.status) + '</div>' + tombolDetail('odc', c.id) + '</div>');
            });
            G.odp.filter(ikut).forEach(p => {
                const t = titik(p);
                if (!t) return;
                ada++;
                const isi = {};
                plgDi(p.id).forEach(g => isi[g.port] = g);
                let daftar = '';
                for (let i = 1; i <= p.kapasitas_port; i++) {
                    daftar += isi[i] ? '<div>P' + pad2(i) + ' ' + e(isi[i].kode_pelanggan) + ' · ' + e(isi[i].nama_pelanggan) + '</div>' : '<div class="is-kosong">P' + pad2(i) + ' Kosong</div>';
                }
                L.marker(t, {
                        icon: ikon('is-odp'),
                        zIndexOffset: 100
                    }).addTo(lapisan)
                    .bindPopup('<div class="om-pop"><b>' + e(p.kode_odp) + '</b><div class="om-k">' + e(p.nama_odp) + ' · ' + Object.keys(isi).length + '/' + p.kapasitas_port + ' port</div><div class="om-list">' + daftar + '</div>' + tombolDetail('odp', p.id) + '</div>');
            });

            document.getElementById('ogMapKosong').classList.toggle('hidden', ada > 0);
            if (ada) peta.fitBounds(lapisan.getBounds(), {
                padding: [40, 40],
                maxZoom: 17
            });
            else peta.setView([-6.2, 106.84], 11);
        }

        // ====== MULAI ======
        (function() {
            const url = new URL(location.href);
            const qOdp = url.searchParams.get('odp_id'),
                qOdc = url.searchParams.get('odc_id'),
                qWil = url.searchParams.get('wilayah');
            let awal = null;
            if (qOdp && cari(G.odp, qOdp)) awal = {
                jenis: 'odp',
                id: qOdp
            };
            else if (qOdc && cari(G.odc, qOdc)) awal = {
                jenis: 'odc',
                id: qOdc
            };
            else if (qWil) {
                const w = G.wilayah.find(x => x.kode_wilayah.toLowerCase() === qWil.toLowerCase());
                if (w) awal = {
                    jenis: 'wilayah',
                    id: w.id
                };
            }
            if (!awal) {
                try {
                    awal = JSON.parse(sessionStorage.getItem(KUNCI_PILIH) || 'null');
                } catch (err) {}
            }
            // data yang dipilih sudah terhapus -> naik ke induknya
            if (awal && awal.jenis && !cari({
                    wilayah: G.wilayah,
                    odc: G.odc,
                    odp: G.odp
                } [awal.jenis] || [], awal.id)) awal = null;
            if (!awal && G.wilayah.length === 1) awal = {
                jenis: 'wilayah',
                id: G.wilayah[0].id
            };
            pilih(awal ? awal.jenis : null, awal ? awal.id : null, !!awal);

            let nav = null;
            try {
                nav = sessionStorage.getItem(KUNCI_NAV);
            } catch (err) {}
            if (qOdp || qOdc || qWil) nav = 'perangkat';
            pilihNav(['perangkat', 'peta'].includes(nav) ? nav : 'perangkat');
        })();
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