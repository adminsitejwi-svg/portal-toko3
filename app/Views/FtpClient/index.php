<?php // FTP Client (menu Data Teknis NOC) — layout sidebar/header sama dengan halaman lain (diambil dari ComingSoon) ?>
<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title>FTP Client</title>
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

        /* ===== KARTU NETMON ===== */
        .nm-total {
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12.5px;
            font-weight: 700;
            color: #2f6bff;
            background: #e8efff;
        }

        html.dark .nm-total {
            color: #8fb2ff;
            background: rgba(47, 107, 255, .18);
        }

        .nm-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .nm-card {
            position: relative;
            display: flex;
            flex-direction: column;
            padding: 20px;
            border: 1px solid var(--rf-border);
            border-radius: 16px;
            background: var(--rf-card);
            box-shadow: var(--rf-shadow);
            overflow: hidden;
            transition: transform .2s ease, box-shadow .2s ease;
        }

        /* garis warna di atas kartu */
        .nm-card::before {
            content: "";
            position: absolute;
            inset: 0 0 auto 0;
            height: 4px;
            background: var(--nm-c);
        }

        .nm-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(31, 42, 68, .1);
        }

        .nm-card.hidden {
            display: none;
        }

        .nm-card-head {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .nm-ic {
            flex: 0 0 auto;
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: var(--nm-c);
            background: color-mix(in srgb, var(--nm-c) 14%, transparent);
        }

        .nm-title {
            font-size: 1rem;
            font-weight: 800;
            color: var(--rf-head);
            line-height: 1.3;
            word-break: break-word;
        }

        .nm-host {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 3px;
            font-size: 12.5px;
            color: var(--rf-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .nm-actions {
            display: flex;
            gap: 2px;
            flex: 0 0 auto;
        }

        .nm-act {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: var(--rf-muted);
            transition: background-color .15s, color .15s;
        }

        .nm-act:hover {
            background: var(--rf-soft);
            color: #2f6bff;
        }

        .nm-act.is-del:hover {
            color: #ef4444;
        }

        /* keterangan maksimal 3 baris agar tinggi kartu seragam */
        .nm-desc {
            flex: 1;
            margin: 14px 0 18px;
            font-size: 13px;
            line-height: 1.55;
            color: var(--rf-text);
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            white-space: pre-line;
        }

        .nm-open {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 10px 16px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 700;
            color: #fff !important;
            background: linear-gradient(135deg, #4f8cff, #2f6bff);
            box-shadow: 0 6px 16px rgba(47, 107, 255, .3);
            transition: filter .15s, transform .15s;
        }

        .nm-open:hover {
            filter: brightness(1.06);
            transform: translateY(-1px);
        }

        .nm-empty {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 48px 20px;
        }

        .nm-empty-ic {
            width: 64px;
            height: 64px;
            margin-bottom: 14px;
            border-radius: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: #2f6bff;
            background: #e8efff;
        }

        html.dark .nm-empty-ic {
            background: rgba(47, 107, 255, .18);
        }

        /* ===== NET HOME & SEGERA HADIR ===== */
        .nh-chip,
        .nh-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border-radius: 999px;
            font-weight: 700;
        }

        .nh-chip {
            padding: 6px 12px;
            font-size: 12.5px;
        }

        .nh-badge {
            margin-top: 5px;
            padding: 2px 9px;
            font-size: 11.5px;
        }

        .nh-chip.is-ok,
        .nh-badge.is-ok {
            color: #15803d;
            background: #dcfce7;
        }

        .nh-chip.is-soon,
        .nh-badge.is-soon {
            color: #b45309;
            background: #fef3c7;
        }

        html.dark .nh-chip.is-ok,
        html.dark .nh-badge.is-ok {
            color: #86efac;
            background: rgba(34, 197, 94, .16);
        }

        html.dark .nh-chip.is-soon,
        html.dark .nh-badge.is-soon {
            color: #fcd34d;
            background: rgba(245, 158, 11, .16);
        }

        /* kartu menu yang belum tersedia: sedikit pudar, tombol garis */
        .nm-card.is-soon .nm-ic {
            filter: grayscale(.4);
            opacity: .85;
        }

        .nm-open.is-soon {
            color: #b45309 !important;
            background: transparent;
            border: 1px dashed #f59e0b;
            box-shadow: none;
        }

        html.dark .nm-open.is-soon {
            color: #fcd34d !important;
        }

        .nh-soon {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding: 56px 24px;
        }

        .nh-soon-ic {
            width: 88px;
            height: 88px;
            margin-bottom: 16px;
            border-radius: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 44px;
            color: #f59e0b;
            background: #fef3c7;
            box-shadow: 0 10px 24px rgba(245, 158, 11, .2);
        }

        html.dark .nh-soon-ic {
            background: rgba(245, 158, 11, .16);
        }

        .nh-soon-title {
            margin-top: 14px;
            font-size: 1.35rem;
            font-weight: 800;
            color: var(--rf-head);
        }

        .nh-soon-text {
            max-width: 460px;
            margin: 8px 0 0;
            font-size: 14px;
            line-height: 1.6;
            color: var(--rf-muted);
        }
    </style>
    <link rel="stylesheet" href="<?= base_url('assets/theme/rfo.css') ?>?v=<?= @filemtime(FCPATH . 'assets/theme/rfo.css') ?: '1' ?>">
    <style>
        /* ===== FTP CLIENT ===== */
        .fc-card { background:#fff; border:1px solid #e6ebf1; border-radius:14px; box-shadow:0 1px 3px rgba(15,23,42,.04); margin-bottom:22px; overflow:hidden; }
        html.dark .fc-card { background:#263240; border-color:rgba(255,255,255,.08); }
        .fc-card-head { display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap; padding:16px 22px; background:#fafbfd; border-bottom:1px solid #edf1f5; }
        html.dark .fc-card-head { background:rgba(255,255,255,.03); border-color:rgba(255,255,255,.06); }
        .fc-card-title { display:flex; align-items:center; gap:12px; }
        .fc-card-title h2 { font-size:15px; font-weight:700; color:#0f172a; }
        html.dark .fc-card-title h2 { color:#e5ebf3; }
        .fc-card-title p { font-size:12px; color:#64748b; }
        .fc-ic { width:38px; height:38px; border-radius:10px; display:inline-flex; align-items:center; justify-content:center; background:#e8efff; color:#2f6bff; font-size:20px; flex-shrink:0; }
        html.dark .fc-ic { background:rgba(47,107,255,.18); color:#8fb2ff; }
        .fc-body { padding:20px 22px; }
        .fc-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:16px; }
        .fc-label { display:block; font-size:12.5px; font-weight:600; color:#334155; margin-bottom:6px; }
        html.dark .fc-label { color:#cbd5e1; }
        .fc-input { width:100%; height:44px; padding:0 14px; border:1px solid #dbe2ea; border-radius:10px; background:#fff; color:#0f172a; font-size:14px; outline:none; transition:border-color .15s, box-shadow .15s; }
        .fc-input:focus { border-color:#2f6bff; box-shadow:0 0 0 3px rgba(47,107,255,.15); }
        html.dark .fc-input { background:#1d2630; border-color:rgba(255,255,255,.12); color:#e5ebf3; }
        .fc-input:disabled { opacity:.6; cursor:not-allowed; }
        .fc-hint { font-size:11.5px; color:#94a3b8; margin-top:5px; }
        .fc-check { display:inline-flex; align-items:center; gap:8px; font-size:13px; color:#475569; cursor:pointer; user-select:none; }
        html.dark .fc-check { color:#b6c2d1; }
        .fc-check input { width:16px; height:16px; accent-color:#2f6bff; }
        .fc-foot { display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; padding-top:16px; margin-top:16px; border-top:1px solid #edf1f5; }
        html.dark .fc-foot { border-color:rgba(255,255,255,.06); }
        .fc-note { font-size:12px; color:#64748b; max-width:640px; line-height:1.6; }
        .fc-btn { display:inline-flex; align-items:center; justify-content:center; gap:7px; height:40px; padding:0 16px; border-radius:10px; font-size:13.5px; font-weight:600; border:1px solid transparent; cursor:pointer; transition:filter .15s, background .15s; white-space:nowrap; }
        .fc-btn:disabled { opacity:.55; cursor:not-allowed; }
        .fc-btn-primary { background:#2f6bff; color:#fff; box-shadow:0 4px 12px rgba(47,107,255,.28); }
        .fc-btn-primary:hover:not(:disabled) { filter:brightness(1.07); }
        .fc-btn-lg { height:46px; padding:0 22px; }
        .fc-btn-light { background:#fff; color:#334155; border-color:#dbe2ea; }
        .fc-btn-light:hover:not(:disabled) { background:#f1f5f9; }
        html.dark .fc-btn-light { background:#1d2630; color:#cbd5e1; border-color:rgba(255,255,255,.12); }
        html.dark .fc-btn-light:hover:not(:disabled) { background:rgba(255,255,255,.06); }
        .fc-btn-danger { background:#fff; color:#dc2626; border-color:#fecaca; }
        .fc-btn-danger:hover:not(:disabled) { background:#fef2f2; }
        html.dark .fc-btn-danger { background:transparent; border-color:rgba(248,113,113,.35); color:#f87171; }
        .fc-btn-sm { height:32px; padding:0 10px; font-size:12.5px; border-radius:8px; }
        .fc-status { display:inline-flex; align-items:center; gap:8px; height:40px; padding:0 16px; border:1px solid #e2e8f0; border-radius:10px; background:#fff; font-size:13px; font-weight:600; color:#475569; }
        html.dark .fc-status { background:#263240; border-color:rgba(255,255,255,.1); color:#cbd5e1; }
        .fc-dot { width:9px; height:9px; border-radius:50%; background:#cbd5e1; }
        .fc-status.is-on { color:#059669; border-color:#a7f3d0; background:#ecfdf5; }
        html.dark .fc-status.is-on { color:#34d399; border-color:rgba(52,211,153,.35); background:rgba(16,185,129,.1); }
        .fc-status.is-on .fc-dot { background:#10b981; box-shadow:0 0 0 3px rgba(16,185,129,.2); }
        /* akun tersimpan */
        .fc-akun { display:flex; flex-wrap:wrap; gap:10px; margin-bottom:18px; }
        .fc-akun-item { display:inline-flex; align-items:center; gap:8px; padding:6px 6px 6px 12px; border:1px solid #dbe2ea; border-radius:10px; font-size:12.5px; background:#fff; cursor:pointer; transition:border-color .15s, background .15s; }
        .fc-akun-item:hover, .fc-akun-item.is-active { border-color:#2f6bff; background:#f4f7ff; }
        html.dark .fc-akun-item { background:#1d2630; border-color:rgba(255,255,255,.12); color:#cbd5e1; }
        html.dark .fc-akun-item:hover, html.dark .fc-akun-item.is-active { border-color:#5b8cff; background:rgba(47,107,255,.12); }
        .fc-akun-item b { font-weight:700; }
        .fc-akun-item small { color:#94a3b8; }
        .fc-akun-del { width:24px; height:24px; border-radius:6px; display:inline-flex; align-items:center; justify-content:center; color:#94a3b8; }
        .fc-akun-del:hover { background:#fee2e2; color:#dc2626; }
        /* explorer */
        .fc-toolbar { display:flex; align-items:center; justify-content:space-between; gap:10px; flex-wrap:wrap; padding:14px 22px; border-bottom:1px solid #edf1f5; }
        html.dark .fc-toolbar { border-color:rgba(255,255,255,.06); }
        .fc-crumb { display:flex; align-items:center; flex-wrap:wrap; gap:2px; font-size:13px; min-width:0; }
        .fc-crumb a { padding:4px 8px; border-radius:6px; color:#2f6bff; font-weight:600; }
        .fc-crumb a:hover { background:#eef3ff; }
        html.dark .fc-crumb a { color:#8fb2ff; }
        html.dark .fc-crumb a:hover { background:rgba(47,107,255,.15); }
        .fc-crumb span { color:#94a3b8; }
        .fc-tools { display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
        .fc-search { position:relative; }
        .fc-search i { position:absolute; left:11px; top:50%; transform:translateY(-50%); color:#94a3b8; }
        .fc-search input { height:32px; padding:0 10px 0 32px; width:190px; font-size:12.5px; border-radius:8px; }
        .fc-tablewrap { overflow-x:auto; position:relative; min-height:120px; }
        .fc-table { width:100%; border-collapse:collapse; font-size:13px; }
        .fc-table th { text-align:left; font-size:11.5px; text-transform:uppercase; letter-spacing:.04em; color:#64748b; font-weight:700; padding:10px 14px; background:#fafbfd; border-bottom:1px solid #edf1f5; white-space:nowrap; }
        html.dark .fc-table th { background:rgba(255,255,255,.03); border-color:rgba(255,255,255,.06); color:#94a3b8; }
        .fc-table td { padding:9px 14px; border-bottom:1px solid #f1f5f9; white-space:nowrap; color:#334155; }
        html.dark .fc-table td { border-color:rgba(255,255,255,.05); color:#cbd5e1; }
        .fc-table tr:hover td { background:#f8fafc; }
        html.dark .fc-table tr:hover td { background:rgba(255,255,255,.03); }
        .fc-table tr.is-selected td { background:#eef3ff; }
        html.dark .fc-table tr.is-selected td { background:rgba(47,107,255,.12); }
        .fc-name { display:inline-flex; align-items:center; gap:10px; font-weight:600; color:#0f172a; max-width:520px; overflow:hidden; text-overflow:ellipsis; }
        html.dark .fc-name { color:#e5ebf3; }
        a.fc-name:hover { color:#2f6bff; }
        .fc-name i { font-size:20px; flex-shrink:0; }
        .fc-muted { color:#94a3b8 !important; font-size:12.5px; }
        .fc-act { display:inline-flex; gap:4px; }
        .fc-act button, .fc-act a { width:30px; height:30px; display:inline-flex; align-items:center; justify-content:center; border-radius:8px; color:#64748b; font-size:16px; }
        .fc-act button:hover, .fc-act a:hover { background:#eef3ff; color:#2f6bff; }
        .fc-act .is-del:hover { background:#fee2e2; color:#dc2626; }
        html.dark .fc-act button:hover, html.dark .fc-act a:hover { background:rgba(255,255,255,.08); }
        .fc-empty { text-align:center; padding:56px 20px; }
        .fc-empty .fc-ic { width:64px; height:64px; font-size:32px; border-radius:16px; background:#f1f5f9; color:#94a3b8; margin-bottom:16px; }
        html.dark .fc-empty .fc-ic { background:rgba(255,255,255,.06); }
        .fc-empty h3 { font-size:17px; font-weight:700; color:#0f172a; margin-bottom:8px; }
        html.dark .fc-empty h3 { color:#e5ebf3; }
        .fc-empty p { font-size:13px; color:#64748b; max-width:460px; margin:0 auto; line-height:1.7; }
        .fc-loading { position:absolute; inset:0; display:none; align-items:center; justify-content:center; background:rgba(255,255,255,.6); z-index:2; font-size:13px; color:#2f6bff; gap:8px; font-weight:600; }
        html.dark .fc-loading { background:rgba(29,38,48,.6); }
        .fc-loading.show { display:flex; }
        .fc-drop { position:absolute; inset:8px; border:2px dashed #2f6bff; border-radius:12px; background:rgba(47,107,255,.07); display:none; align-items:center; justify-content:center; flex-direction:column; gap:6px; color:#2f6bff; font-weight:700; z-index:3; pointer-events:none; }
        .fc-drop i { font-size:36px; }
        .fc-drop.show { display:flex; }
        .fc-summary { padding:10px 22px; font-size:12px; color:#64748b; display:flex; justify-content:space-between; flex-wrap:wrap; gap:8px; }
        /* antrean upload */
        .fc-queue { position:fixed; right:20px; bottom:20px; width:380px; max-width:calc(100vw - 32px); background:#fff; border:1px solid #e2e8f0; border-radius:14px; box-shadow:0 18px 40px rgba(15,23,42,.18); z-index:1060; display:none; overflow:hidden; }
        html.dark .fc-queue { background:#263240; border-color:rgba(255,255,255,.1); }
        .fc-queue.show { display:block; }
        .fc-queue-head { display:flex; align-items:center; justify-content:space-between; padding:12px 16px; font-size:13px; font-weight:700; border-bottom:1px solid #edf1f5; color:#0f172a; }
        html.dark .fc-queue-head { border-color:rgba(255,255,255,.06); color:#e5ebf3; }
        .fc-bar { height:6px; background:#e2e8f0; border-radius:99px; overflow:hidden; }
        html.dark .fc-bar { background:rgba(255,255,255,.1); }
        .fc-bar > div { height:100%; width:0; background:#2f6bff; transition:width .2s; }
        .fc-queue-list { max-height:220px; overflow-y:auto; padding:6px 16px 12px; font-size:12px; }
        .fc-queue-list div { display:flex; justify-content:space-between; gap:10px; padding:4px 0; color:#475569; }
        html.dark .fc-queue-list div { color:#b6c2d1; }
        .fc-queue-list span:first-child { overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
        .fc-ok { color:#059669; } .fc-err { color:#dc2626; }
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

        <div class="rfo-page p-4 sm:p-6">
            <!-- JUDUL -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic"><i class="ti ti-folder-share"></i></span>
                    <div>
                        <h1 class="rfo-title">FTP Client</h1>
                        <div class="rfo-subtitle">Kelola koneksi dan jelajahi file pada FTP Server.</div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span id="fcStatus" class="fc-status"><span class="fc-dot"></span><span id="fcStatusText">Not Connected</span></span>
                    <button type="button" id="btnDisconnect" class="fc-btn fc-btn-danger hidden"><i class="ti ti-plug-connected-x"></i> Disconnect</button>
                </div>
            </div>

            <?php if (! $ftpAda) : ?>
                <div class="rfo-panel mb-5" style="border-left:4px solid #f59e0b">
                    <b>Ekstensi FTP PHP belum aktif.</b> Aktifkan <code>extension=ftp</code> di php.ini lalu restart server agar FTP/FTPS bisa dipakai<?= $sftpAda ? ' (SFTP tetap bisa dipakai)' : '' ?>.
                </div>
            <?php endif; ?>

            <!-- CONNECTION MANAGER -->
            <div class="fc-card">
                <div class="fc-card-head">
                    <div class="fc-card-title">
                        <span class="fc-ic"><i class="ti ti-plug-connected"></i></span>
                        <div>
                            <h2>Connection Manager</h2>
                            <p>Masukkan detail server FTP yang akan diakses.</p>
                        </div>
                    </div>
                </div>
                <div class="fc-body">
                    <div id="fcAkunWrap" class="<?= $akun ? '' : 'hidden' ?>">
                        <label class="fc-label">Akun Tersimpan</label>
                        <div id="fcAkun" class="fc-akun"></div>
                    </div>

                    <form id="fcForm" autocomplete="off">
                        <input type="hidden" name="akun_id" id="akun_id" value="">
                        <div class="fc-grid">
                            <div>
                                <label class="fc-label" for="protokol">Protokol</label>
                                <select name="protokol" id="protokol" class="fc-input">
                                    <?php foreach ($protokol as $kode => $nama) : ?>
                                        <option value="<?= esc($kode) ?>" data-port="<?= (int) $portBawaan[$kode] ?>" <?= ($kode === 'sftp' && ! $sftpAda) || ($kode !== 'sftp' && ! $ftpAda) ? 'disabled' : '' ?>><?= esc($nama) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="fc-label" for="host">IP Address / Hostname</label>
                                <input type="text" name="host" id="host" class="fc-input" placeholder="IP / hostname server" required>
                            </div>
                            <div>
                                <label class="fc-label" for="port">Port</label>
                                <input type="number" name="port" id="port" class="fc-input" min="1" max="65535" value="21" placeholder="Port server">
                                <div class="fc-hint">FTP/FTPS umumnya 21, SFTP 22, atau port khusus server.</div>
                            </div>
                            <div>
                                <label class="fc-label" for="username">Username</label>
                                <input type="text" name="username" id="username" class="fc-input" placeholder="Username FTP" autocomplete="off">
                            </div>
                            <div>
                                <label class="fc-label" for="password">Password</label>
                                <input type="password" name="password" id="password" class="fc-input" placeholder="Masukkan password FTP" autocomplete="new-password">
                                <div id="pwHint" class="fc-hint hidden">Kosongkan untuk memakai password yang tersimpan.</div>
                            </div>
                            <div>
                                <label class="fc-label" for="folder_awal">Folder Awal</label>
                                <input type="text" name="folder_awal" id="folder_awal" class="fc-input" placeholder="Folder setelah connect">
                                <div class="fc-hint">Opsional. Kosong = folder home akun.</div>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-x-6 gap-y-3 mt-4">
                            <label class="fc-check" id="pasifWrap"><input type="checkbox" name="pasif" id="pasif" value="1" checked> Mode pasif (disarankan)</label>
                            <label class="fc-check"><input type="checkbox" name="simpan" id="simpan" value="1"> Simpan akun ini</label>
                            <input type="text" name="nama_akun" id="nama_akun" class="fc-input hidden" style="max-width:260px;height:36px" placeholder="Nama akun tersimpan" maxlength="100">
                        </div>

                        <div class="fc-foot">
                            <div class="fc-note">Kredensial disimpan sementara pada sesi PHP agar koneksi dapat digunakan untuk memuat ulang daftar file. Klik Disconnect untuk menghapusnya dari sesi. Akun yang disimpan memakai password terenkripsi.</div>
                            <button type="submit" id="btnConnect" class="fc-btn fc-btn-primary fc-btn-lg"><i class="ti ti-plug-connected"></i> Connect</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- REMOTE FILE EXPLORER -->
            <div class="fc-card">
                <div class="fc-card-head">
                    <div class="fc-card-title">
                        <span class="fc-ic"><i class="ti ti-folders"></i></span>
                        <div>
                            <h2>Remote File Explorer</h2>
                            <p>Daftar file dan folder pada server FTP.</p>
                        </div>
                    </div>
                    <div id="fcImportBtns" class="fc-tools hidden">
                        <button type="button" class="fc-btn fc-btn-primary" onclick="document.getElementById('pilihFile').click()"><i class="ti ti-file-upload"></i> Import File</button>
                        <button type="button" class="fc-btn fc-btn-light" onclick="document.getElementById('pilihFolder').click()"><i class="ti ti-folder-up"></i> Import Folder</button>
                        <button type="button" class="fc-btn fc-btn-light" onclick="document.getElementById('pilihZip').click()"><i class="ti ti-file-zip"></i> Import ZIP (Ekstrak)</button>
                    </div>
                </div>

                <input type="file" id="pilihFile" multiple class="hidden">
                <input type="file" id="pilihFolder" webkitdirectory directory multiple class="hidden">
                <input type="file" id="pilihZip" accept=".zip,application/zip" multiple class="hidden">

                <div id="fcExplorer" class="hidden">
                    <div class="fc-toolbar">
                        <div id="fcCrumb" class="fc-crumb"></div>
                        <div class="fc-tools">
                            <div class="fc-search"><i class="ti ti-search"></i><input type="text" id="fcCari" class="fc-input" placeholder="Cari di folder ini"></div>
                            <button type="button" id="btnNaik" class="fc-btn fc-btn-light fc-btn-sm" title="Folder atas"><i class="ti ti-arrow-up"></i></button>
                            <button type="button" id="btnRefresh" class="fc-btn fc-btn-light fc-btn-sm" title="Muat ulang"><i class="ti ti-refresh"></i></button>
                            <button type="button" id="btnMkdir" class="fc-btn fc-btn-light fc-btn-sm"><i class="ti ti-folder-plus"></i> Folder Baru</button>
                            <button type="button" id="btnHapusPilih" class="fc-btn fc-btn-danger fc-btn-sm hidden"><i class="ti ti-trash"></i> <span>Hapus (<span id="jmlPilih">0</span>)</span></button>
                        </div>
                    </div>
                    <div class="fc-tablewrap" id="fcDropArea">
                        <div id="fcLoading" class="fc-loading"><i class="ti ti-loader-2 animate-spin"></i> Memuat...</div>
                        <div id="fcDrop" class="fc-drop"><i class="ti ti-cloud-upload"></i> Lepaskan file / folder untuk diimport ke folder ini</div>
                        <table class="fc-table">
                            <thead>
                                <tr>
                                    <th style="width:36px"><input type="checkbox" id="pilihSemua"></th>
                                    <th>Nama</th>
                                    <th>Ukuran</th>
                                    <th>Diubah</th>
                                    <th>Izin</th>
                                    <th style="width:120px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="fcBody"></tbody>
                        </table>
                    </div>
                    <div class="fc-summary">
                        <span id="fcInfo"></span>
                        <span><i class="ti ti-info-circle"></i> Seret &amp; lepas file atau folder ke tabel untuk mengimport.</span>
                    </div>
                </div>

                <div id="fcKosong" class="fc-empty">
                    <span class="fc-ic"><i class="ti ti-plug-connected-x"></i></span>
                    <h3>Belum terhubung ke FTP Server</h3>
                    <p>Isi IP atau hostname, port, username, dan password pada Connection Manager, kemudian tekan Connect untuk membuka daftar file.</p>
                </div>
            </div>
        </div>

        <!-- ANTREAN IMPORT -->
        <div id="fcQueue" class="fc-queue">
            <div class="fc-queue-head">
                <span id="fcQueueTitle">Mengimport...</span>
                <button type="button" id="fcQueueClose" class="hidden text-gray-400 hover:text-gray-600"><i class="ti ti-x"></i></button>
            </div>
            <div class="px-4 pt-3">
                <div class="flex justify-between text-xs mb-1.5"><span id="fcQueueNow" class="truncate pr-3 text-gray-500"></span><b id="fcQueuePct">0%</b></div>
                <div class="fc-bar"><div id="fcQueueBar"></div></div>
            </div>
            <div id="fcQueueList" class="fc-queue-list"></div>
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

    <script>
        // ================= FTP CLIENT =================
        const FC_URL = {
            connect: "<?= site_url('FtpClient/connect') ?>",
            disconnect: "<?= site_url('FtpClient/disconnect') ?>",
            list: "<?= site_url('FtpClient/list') ?>",
            mkdir: "<?= site_url('FtpClient/mkdir') ?>",
            rename: "<?= site_url('FtpClient/rename') ?>",
            hapus: "<?= site_url('FtpClient/delete') ?>",
            download: "<?= site_url('FtpClient/download') ?>",
            upload: "<?= site_url('FtpClient/upload') ?>",
            catat: "<?= site_url('FtpClient/catat') ?>",
            hapusAkun: "<?= site_url('FtpClient/akun/delete/') ?>"
        };
        const FC_CHUNK = <?= (int) $chunk ?>;
        let fcAkun = <?= json_encode($akun, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?>;
        const fcSesi = <?= json_encode($sesi, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?>;
        let fcFolder = '/';
        let fcIsi = [];
        let fcSibuk = false;

        const $id = id => document.getElementById(id);
        const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
        const gabung = (dir, nama) => (dir.replace(/\/+$/, '') + '/' + nama).replace(/\/+/g, '/');

        function pesan(icon, title, text) {
            return Swal.fire({ icon, title, text, confirmButtonColor: '#2f6bff' });
        }

        function toast(icon, title) {
            Swal.fire({ toast: true, position: 'top-end', icon, title, showConfirmButton: false, timer: 2200, timerProgressBar: true });
        }

        async function minta(url, data, method) {
            const opsi = { method: method || (data ? 'POST' : 'GET'), headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } };
            if (data) opsi.body = data instanceof FormData ? data : (() => { const f = new FormData(); Object.entries(data).forEach(([k, v]) => Array.isArray(v) ? v.forEach(x => f.append(k + '[]', x)) : f.append(k, v)); return f; })();
            let res;
            try {
                res = await fetch(url, opsi);
            } catch (e) {
                return { ok: false, pesan: 'Tidak dapat menghubungi server portal.' };
            }
            try {
                return await res.json();
            } catch (e) {
                return { ok: false, pesan: res.status === 413 ? 'Ukuran kiriman melebihi batas server.' : 'Sesi login berakhir atau server error. Muat ulang halaman.' };
            }
        }

        // ---------- Format ----------
        function ukuran(b) {
            if (b === null || b === undefined) return '-';
            const s = ['B', 'KB', 'MB', 'GB', 'TB'];
            let i = 0;
            b = Number(b);
            while (b >= 1024 && i < s.length - 1) { b /= 1024; i++; }
            return (i ? b.toFixed(b < 10 ? 2 : 1) : b) + ' ' + s[i];
        }

        function waktu(t) {
            if (!t) return '-';
            const d = new Date(t * 1000);
            const p = n => String(n).padStart(2, '0');
            return p(d.getDate()) + '-' + p(d.getMonth() + 1) + '-' + d.getFullYear() + ' ' + p(d.getHours()) + ':' + p(d.getMinutes());
        }

        function ikon(item) {
            if (item.tipe === 'dir') return ['ti-folder-filled', '#f5a524'];
            if (item.tipe === 'link') return ['ti-link', '#64748b'];
            const ext = (item.nama.split('.').pop() || '').toLowerCase();
            const peta = {
                pdf: ['ti-file-type-pdf', '#ef4444'], doc: ['ti-file-type-doc', '#2f6bff'], docx: ['ti-file-type-doc', '#2f6bff'],
                xls: ['ti-file-spreadsheet', '#16a34a'], xlsx: ['ti-file-spreadsheet', '#16a34a'], csv: ['ti-file-type-csv', '#16a34a'],
                ppt: ['ti-file-type-ppt', '#ea580c'], pptx: ['ti-file-type-ppt', '#ea580c'], txt: ['ti-file-text', '#64748b'], log: ['ti-file-text', '#64748b'],
                zip: ['ti-file-zip', '#d97706'], rar: ['ti-file-zip', '#d97706'], '7z': ['ti-file-zip', '#d97706'], gz: ['ti-file-zip', '#d97706'], tar: ['ti-file-zip', '#d97706'],
                jpg: ['ti-photo', '#8b5cf6'], jpeg: ['ti-photo', '#8b5cf6'], png: ['ti-photo', '#8b5cf6'], gif: ['ti-photo', '#8b5cf6'], webp: ['ti-photo', '#8b5cf6'],
                sql: ['ti-database', '#0891b2'], json: ['ti-braces', '#0891b2'], xml: ['ti-code', '#0891b2'], kmz: ['ti-map-2', '#059669'], kml: ['ti-map-2', '#059669'],
                mp4: ['ti-movie', '#db2777'], mp3: ['ti-music', '#db2777'], rsc: ['ti-router', '#0891b2'], backup: ['ti-router', '#0891b2']
            };
            return peta[ext] || ['ti-file', '#64748b'];
        }

        // ---------- Akun tersimpan ----------
        function renderAkun() {
            $id('fcAkunWrap').classList.toggle('hidden', !fcAkun.length);
            const aktif = $id('akun_id').value;
            $id('fcAkun').innerHTML = fcAkun.map(a =>
                '<span class="fc-akun-item' + (String(a.id) === aktif ? ' is-active' : '') + '" data-id="' + a.id + '" title="' + esc(a.username + '@' + a.host + ':' + a.port) + '">' +
                '<i class="ti ti-server-2" style="color:#2f6bff"></i><span><b>' + esc(a.nama) + '</b> <small>' + esc(a.protokol.toUpperCase() + ' · ' + a.host) + '</small></span>' +
                '<button type="button" class="fc-akun-del" data-hapus="' + a.id + '" title="Hapus akun tersimpan"><i class="ti ti-x"></i></button></span>'
            ).join('');
        }

        $id('fcAkun').addEventListener('click', async function(e) {
            const del = e.target.closest('[data-hapus]');
            if (del) {
                e.stopPropagation();
                const a = fcAkun.find(x => String(x.id) === del.dataset.hapus);
                const ok = await Swal.fire({ icon: 'warning', title: 'Hapus akun tersimpan?', text: a ? a.nama : '', showCancelButton: true, confirmButtonColor: '#dc2626', confirmButtonText: 'Ya, Hapus', cancelButtonText: 'Batal' });
                if (!ok.isConfirmed) return;
                const r = await minta(FC_URL.hapusAkun + del.dataset.hapus, {});
                if (!r.ok) return pesan('error', 'Gagal', r.pesan);
                fcAkun = r.akun;
                if ($id('akun_id').value === del.dataset.hapus) kosongkanAkun();
                renderAkun();
                return toast('success', r.pesan);
            }
            const item = e.target.closest('[data-id]');
            if (!item) return;
            const a = fcAkun.find(x => String(x.id) === item.dataset.id);
            if (!a) return;
            if ($id('akun_id').value === String(a.id)) return kosongkanAkun();
            $id('akun_id').value = a.id;
            $id('protokol').value = a.protokol;
            $id('host').value = a.host;
            $id('port').value = a.port;
            $id('username').value = a.username;
            $id('password').value = '';
            $id('folder_awal').value = a.folder_awal || '';
            $id('pasif').checked = Number(a.pasif) === 1;
            $id('nama_akun').value = a.nama;
            $id('pwHint').classList.remove('hidden');
            $id('password').placeholder = 'Tersimpan (kosongkan untuk memakai)';
            aturProtokol();
            renderAkun();
        });

        function kosongkanAkun() {
            $id('akun_id').value = '';
            $id('pwHint').classList.add('hidden');
            $id('password').placeholder = 'Masukkan password FTP';
            renderAkun();
        }

        // ---------- Form koneksi ----------
        let portSebelum = $id('protokol').selectedOptions[0].dataset.port;

        function aturProtokol() {
            $id('pasifWrap').classList.toggle('hidden', $id('protokol').value === 'sftp');
        }
        $id('protokol').addEventListener('change', function() {
            const baru = this.selectedOptions[0].dataset.port;
            if (!$id('port').value || $id('port').value === portSebelum) $id('port').value = baru;
            portSebelum = baru;
            aturProtokol();
        });
        $id('simpan').addEventListener('change', function() {
            $id('nama_akun').classList.toggle('hidden', !this.checked);
            if (this.checked) $id('nama_akun').focus();
        });

        function setTerhubung(label) {
            const on = !!label;
            $id('fcStatus').classList.toggle('is-on', on);
            $id('fcStatusText').textContent = on ? 'Connected · ' + label : 'Not Connected';
            $id('btnDisconnect').classList.toggle('hidden', !on);
            $id('fcExplorer').classList.toggle('hidden', !on);
            $id('fcImportBtns').classList.toggle('hidden', !on);
            $id('fcKosong').classList.toggle('hidden', on);
            $id('fcForm').querySelectorAll('input, select').forEach(el => { if (el.type !== 'hidden') el.disabled = on; });
            $id('btnConnect').disabled = on;
            $id('btnConnect').innerHTML = on ? '<i class="ti ti-circle-check"></i> Connected' : '<i class="ti ti-plug-connected"></i> Connect';
        }

        $id('fcForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            if (!$id('host').value.trim()) return pesan('warning', 'Form Belum Lengkap', 'IP Address / Hostname wajib diisi.');
            const btn = $id('btnConnect');
            btn.disabled = true;
            btn.innerHTML = '<i class="ti ti-loader-2 animate-spin"></i> Menghubungkan...';
            const r = await minta(FC_URL.connect, new FormData(this));
            if (!r.ok) {
                btn.disabled = false;
                btn.innerHTML = '<i class="ti ti-plug-connected"></i> Connect';
                return pesan('error', 'Gagal Terhubung', r.pesan);
            }
            fcAkun = r.akun;
            $id('password').value = '';
            $id('simpan').checked = false;
            $id('nama_akun').classList.add('hidden');
            renderAkun();
            setTerhubung(r.label);
            tampil(r.folder, r.isi);
            toast('success', r.pesan);
        });

        $id('btnDisconnect').addEventListener('click', async function() {
            if (fcSibuk) return pesan('warning', 'Import Berjalan', 'Tunggu import selesai sebelum Disconnect.');
            await minta(FC_URL.disconnect, {});
            setTerhubung(null);
            fcIsi = [];
            $id('fcBody').innerHTML = '';
            toast('success', 'Koneksi FTP diputus.');
        });

        // ---------- Explorer ----------
        function loading(on) {
            $id('fcLoading').classList.toggle('show', on);
        }

        async function buka(path) {
            loading(true);
            const r = await minta(FC_URL.list + '?path=' + encodeURIComponent(path));
            loading(false);
            if (!r.ok) return pesan('error', 'Gagal Membuka Folder', r.pesan);
            tampil(r.folder, r.isi);
        }

        function tampil(folder, isi) {
            fcFolder = folder;
            fcIsi = isi;
            $id('fcCari').value = '';
            $id('pilihSemua').checked = false;
            // breadcrumb
            const bagian = folder.split('/').filter(Boolean);
            let jalur = '';
            $id('fcCrumb').innerHTML = '<a href="#" data-buka="/"><i class="ti ti-home"></i></a>' + bagian.map(b => {
                jalur += '/' + b;
                return '<span>/</span><a href="#" data-buka="' + esc(jalur) + '">' + esc(b) + '</a>';
            }).join('');
            $id('btnNaik').disabled = folder === '/';
            renderIsi();
        }

        function renderIsi() {
            const cari = $id('fcCari').value.trim().toLowerCase();
            const data = fcIsi.filter(x => !cari || x.nama.toLowerCase().includes(cari));
            const body = $id('fcBody');
            if (!data.length) {
                body.innerHTML = '<tr><td colspan="6" class="fc-muted" style="text-align:center;padding:36px">' + (cari ? 'Tidak ada yang cocok dengan pencarian.' : 'Folder kosong. Import file atau folder untuk mulai backup.') + '</td></tr>';
            } else {
                body.innerHTML = data.map(x => {
                    const p = gabung(fcFolder, x.nama);
                    const [ic, warna] = ikon(x);
                    const nama = x.tipe === 'dir' ?
                        '<a href="#" class="fc-name" data-buka="' + esc(p) + '"><i class="ti ' + ic + '" style="color:' + warna + '"></i>' + esc(x.nama) + '</a>' :
                        '<span class="fc-name"><i class="ti ' + ic + '" style="color:' + warna + '"></i>' + esc(x.nama) + '</span>';
                    return '<tr data-path="' + esc(p) + '" data-tipe="' + x.tipe + '">' +
                        '<td><input type="checkbox" class="fc-pilih"></td>' +
                        '<td>' + nama + '</td>' +
                        '<td class="fc-muted">' + (x.tipe === 'dir' ? '-' : ukuran(x.ukuran)) + '</td>' +
                        '<td class="fc-muted">' + waktu(x.waktu) + '</td>' +
                        '<td class="fc-muted">' + esc(x.izin || '-') + '</td>' +
                        '<td><span class="fc-act">' +
                        (x.tipe === 'dir' ? '<button type="button" data-aksi="buka" title="Buka"><i class="ti ti-folder-open"></i></button>' :
                            '<a href="' + FC_URL.download + '?path=' + encodeURIComponent(p) + '" title="Download"><i class="ti ti-download"></i></a>') +
                        '<button type="button" data-aksi="rename" title="Ganti nama"><i class="ti ti-pencil"></i></button>' +
                        '<button type="button" data-aksi="hapus" class="is-del" title="Hapus"><i class="ti ti-trash"></i></button>' +
                        '</span></td></tr>';
                }).join('');
            }
            const jmlDir = fcIsi.filter(x => x.tipe === 'dir').length;
            const total = fcIsi.filter(x => x.tipe !== 'dir').reduce((t, x) => t + Number(x.ukuran || 0), 0);
            $id('fcInfo').textContent = jmlDir + ' folder, ' + (fcIsi.length - jmlDir) + ' file (' + ukuran(total) + ')';
            hitungPilih();
        }

        function hitungPilih() {
            const n = document.querySelectorAll('.fc-pilih:checked').length;
            $id('jmlPilih').textContent = n;
            $id('btnHapusPilih').classList.toggle('hidden', !n);
            document.querySelectorAll('#fcBody tr[data-path]').forEach(tr => tr.classList.toggle('is-selected', tr.querySelector('.fc-pilih').checked));
        }

        document.addEventListener('click', function(e) {
            const b = e.target.closest('[data-buka]');
            if (b) {
                e.preventDefault();
                buka(b.dataset.buka);
            }
        });
        $id('fcCari').addEventListener('input', renderIsi);
        $id('btnRefresh').addEventListener('click', () => buka(fcFolder));
        $id('btnNaik').addEventListener('click', () => buka(fcFolder.replace(/\/[^/]+\/?$/, '') || '/'));
        $id('pilihSemua').addEventListener('change', function() {
            document.querySelectorAll('.fc-pilih').forEach(c => c.checked = this.checked);
            hitungPilih();
        });

        $id('fcBody').addEventListener('change', e => { if (e.target.classList.contains('fc-pilih')) hitungPilih(); });
        $id('fcBody').addEventListener('click', async function(e) {
            const btn = e.target.closest('[data-aksi]');
            if (!btn) return;
            const tr = btn.closest('tr');
            const path = tr.dataset.path;
            const nama = path.split('/').pop();
            if (btn.dataset.aksi === 'buka') return buka(path);
            if (btn.dataset.aksi === 'rename') {
                const r = await Swal.fire({ title: 'Ganti Nama', input: 'text', inputValue: nama, showCancelButton: true, confirmButtonText: 'Simpan', cancelButtonText: 'Batal', confirmButtonColor: '#2f6bff', inputValidator: v => !v.trim() ? 'Nama wajib diisi' : undefined });
                if (!r.isConfirmed || r.value.trim() === nama) return;
                const h = await minta(FC_URL.rename, { path, nama_baru: r.value.trim() });
                if (!h.ok) return pesan('error', 'Gagal', h.pesan);
                toast('success', h.pesan);
                return buka(fcFolder);
            }
            if (btn.dataset.aksi === 'hapus') hapus([{ path, tipe: tr.dataset.tipe }]);
        });

        $id('btnHapusPilih').addEventListener('click', function() {
            const item = [...document.querySelectorAll('.fc-pilih:checked')].map(c => {
                const tr = c.closest('tr');
                return { path: tr.dataset.path, tipe: tr.dataset.tipe };
            });
            if (item.length) hapus(item);
        });

        async function hapus(item) {
            const adaFolder = item.some(x => x.tipe === 'dir');
            const r = await Swal.fire({
                icon: 'warning',
                title: 'Hapus ' + (item.length === 1 ? item[0].path.split('/').pop() : item.length + ' item') + '?',
                text: (adaFolder ? 'Folder dihapus beserta seluruh isinya. ' : '') + 'File di server FTP yang dihapus tidak dapat dikembalikan.',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            });
            if (!r.isConfirmed) return;
            loading(true);
            const h = await minta(FC_URL.hapus, { paths: item.map(x => x.path), tipe: item.map(x => x.tipe) });
            loading(false);
            if (!h.ok) pesan('error', 'Gagal Menghapus', h.pesan);
            else toast('success', h.pesan);
            buka(fcFolder);
        }

        $id('btnMkdir').addEventListener('click', async function() {
            const r = await Swal.fire({ title: 'Folder Baru', input: 'text', inputPlaceholder: 'Nama folder', showCancelButton: true, confirmButtonText: 'Buat', cancelButtonText: 'Batal', confirmButtonColor: '#2f6bff', inputValidator: v => !v.trim() ? 'Nama folder wajib diisi' : undefined });
            if (!r.isConfirmed) return;
            const h = await minta(FC_URL.mkdir, { path: fcFolder, nama: r.value.trim() });
            if (!h.ok) return pesan('error', 'Gagal', h.pesan);
            toast('success', h.pesan);
            buka(fcFolder);
        });

        // ---------- Import (upload per potongan) ----------
        function idAcak() {
            return Array.from(crypto.getRandomValues(new Uint8Array(12)), b => b.toString(16).padStart(2, '0')).join('');
        }

        async function unggahSatu(item, tujuan, onProgress) {
            const total = Math.max(1, Math.ceil(item.file.size / FC_CHUNK));
            const uid = idAcak();
            for (let i = 0; i < total; i++) {
                const f = new FormData();
                f.append('upload_id', uid);
                f.append('index', i);
                f.append('total', total);
                f.append('path', tujuan);
                f.append('relpath', item.relpath);
                if (item.ekstrak) f.append('ekstrak', '1');
                f.append('potongan', item.file.slice(i * FC_CHUNK, (i + 1) * FC_CHUNK), 'potongan');
                const r = await minta(FC_URL.upload, f);
                if (!r.ok) return r;
                onProgress(Math.min(item.file.size, (i + 1) * FC_CHUNK));
                if (r.selesai) return r;
            }
            return { ok: false, pesan: 'Upload tidak selesai.' };
        }

        /** item: [{file, relpath, ekstrak}]; jenis: file|folder|zip */
        async function importKe(item, jenis, namaFolder) {
            if (!item.length) return;
            if (fcSibuk) return pesan('warning', 'Import Berjalan', 'Tunggu import sebelumnya selesai.');
            fcSibuk = true;
            const tujuan = fcFolder;
            const totalByte = item.reduce((t, x) => t + x.file.size, 0) || 1;
            let selesaiByte = 0, ok = 0, gagal = 0;
            const list = $id('fcQueueList');
            list.innerHTML = '';
            $id('fcQueueClose').classList.add('hidden');
            $id('fcQueueTitle').textContent = 'Mengimport ' + item.length + ' ' + (jenis === 'zip' ? 'ZIP' : 'file') + ' ke ' + tujuan;
            $id('fcQueue').classList.add('show');
            const setBar = b => {
                const pct = Math.round((b / totalByte) * 100);
                $id('fcQueueBar').style.width = pct + '%';
                $id('fcQueuePct').textContent = pct + '%';
            };
            setBar(0);

            for (const it of item) {
                $id('fcQueueNow').textContent = it.relpath + (it.ekstrak ? ' (diekstrak)' : '');
                const r = await unggahSatu(it, tujuan, b => setBar(selesaiByte + b));
                selesaiByte += it.file.size;
                setBar(selesaiByte);
                const baris = document.createElement('div');
                baris.innerHTML = '<span title="' + esc(r.ok ? (r.pesan || '') : r.pesan) + '">' + esc(it.relpath) + '</span>' +
                    (r.ok ? '<span class="fc-ok"><i class="ti ti-check"></i></span>' : '<span class="fc-err" title="' + esc(r.pesan) + '"><i class="ti ti-x"></i> gagal</span>');
                list.prepend(baris);
                r.ok ? ok++ : gagal++;
                if (!r.ok && /Belum terhubung|Sesi login/.test(r.pesan)) {
                    gagal += item.length - ok - gagal;
                    break;
                }
            }

            if (jenis !== 'zip') minta(FC_URL.catat, { jumlah: ok, gagal, jenis, nama: namaFolder || '', path: tujuan });
            fcSibuk = false;
            $id('fcQueueNow').textContent = '';
            $id('fcQueueTitle').textContent = 'Import selesai: ' + ok + ' berhasil' + (gagal ? ', ' + gagal + ' gagal' : '');
            $id('fcQueueClose').classList.remove('hidden');
            if (!gagal) setTimeout(() => { if (!fcSibuk) $id('fcQueue').classList.remove('show'); }, 4000);
            if (fcFolder === tujuan) buka(fcFolder);
            if (gagal) pesan('warning', 'Sebagian Gagal', gagal + ' file gagal diimport. Arahkan kursor ke tanda gagal untuk melihat penyebabnya.');
        }
        $id('fcQueueClose').addEventListener('click', () => $id('fcQueue').classList.remove('show'));

        $id('pilihFile').addEventListener('change', function() {
            importKe([...this.files].map(f => ({ file: f, relpath: f.name })), 'file');
            this.value = '';
        });
        $id('pilihFolder').addEventListener('change', function() {
            const files = [...this.files];
            const nama = files[0] ? files[0].webkitRelativePath.split('/')[0] : '';
            importKe(files.map(f => ({ file: f, relpath: f.webkitRelativePath || f.name })), 'folder', nama);
            this.value = '';
        });
        $id('pilihZip').addEventListener('change', function() {
            const bukanZip = [...this.files].filter(f => !/\.zip$/i.test(f.name));
            if (bukanZip.length) pesan('warning', 'Bukan ZIP', 'Hanya file .zip yang diekstrak: ' + bukanZip.map(f => f.name).join(', '));
            importKe([...this.files].filter(f => /\.zip$/i.test(f.name)).map(f => ({ file: f, relpath: f.name, ekstrak: true })), 'zip');
            this.value = '';
        });

        // ---------- Drag & drop (file & folder) ----------
        function bacaEntry(entry, jalur) {
            return new Promise(resolve => {
                if (entry.isFile) {
                    entry.file(f => resolve([{ file: f, relpath: jalur + f.name }]), () => resolve([]));
                } else if (entry.isDirectory) {
                    const reader = entry.createReader();
                    const semua = [];
                    const baca = () => reader.readEntries(async batch => {
                        if (!batch.length) {
                            const anak = await Promise.all(semua.map(e => bacaEntry(e, jalur + entry.name + '/')));
                            resolve(anak.flat());
                        } else {
                            semua.push(...batch);
                            baca();
                        }
                    }, () => resolve([]));
                    baca();
                } else resolve([]);
            });
        }

        const area = $id('fcDropArea');
        let dragN = 0;
        area.addEventListener('dragenter', e => { e.preventDefault(); dragN++; $id('fcDrop').classList.add('show'); });
        area.addEventListener('dragleave', e => { e.preventDefault(); if (--dragN <= 0) { dragN = 0; $id('fcDrop').classList.remove('show'); } });
        area.addEventListener('dragover', e => e.preventDefault());
        area.addEventListener('drop', async e => {
            e.preventDefault();
            dragN = 0;
            $id('fcDrop').classList.remove('show');
            const entries = [...(e.dataTransfer.items || [])].map(i => i.webkitGetAsEntry && i.webkitGetAsEntry()).filter(Boolean);
            let item;
            if (entries.length) {
                item = (await Promise.all(entries.map(en => bacaEntry(en, '')))).flat();
            } else {
                item = [...e.dataTransfer.files].map(f => ({ file: f, relpath: f.name }));
            }
            const adaFolder = entries.some(en => en.isDirectory);
            importKe(item, adaFolder ? 'folder' : 'file', adaFolder ? entries.filter(en => en.isDirectory).map(en => en.name).join(', ') : '');
        });

        window.addEventListener('beforeunload', e => { if (fcSibuk) { e.preventDefault(); e.returnValue = ''; } });

        // ---------- Awal ----------
        renderAkun();
        aturProtokol();
        if (fcSesi) {
            $id('protokol').value = fcSesi.protokol;
            $id('host').value = fcSesi.host;
            $id('port').value = fcSesi.port;
            $id('username').value = fcSesi.username;
            if (fcSesi.akun_id) $id('akun_id').value = fcSesi.akun_id;
            renderAkun();
            setTerhubung(fcSesi.protokol.toUpperCase() + ' · ' + fcSesi.username + '@' + fcSesi.host + ':' + fcSesi.port);
            buka(fcSesi.folder || '/');
        }
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