<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title>Data VLAN</title>
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

        /* ===== TABEL: scroll di dalam card, header kolom tetap terlihat ===== */
        .rfo-page .table-scroll {
            overflow: auto;
        }

        .rfo-page .table-scroll thead th {
            /* !important: DataTables memberi position:relative pada kolom yang bisa diurutkan */
            position: sticky !important;
            top: 0;
            z-index: 2;
            box-shadow: inset 0 -1px 0 var(--rf-border);
        }

        /* ===== DATA VLAN ===== */
        .vl-status {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
        }

        .vl-status.is-on {
            color: #15803d;
            background: #dcfce7;
        }

        .vl-status.is-off {
            color: #b91c1c;
            background: #fee2e2;
        }

        html.dark .vl-status.is-on {
            color: #86efac;
            background: rgba(34, 197, 94, .16);
        }

        html.dark .vl-status.is-off {
            color: #fca5a5;
            background: rgba(239, 68, 68, .16);
        }

        .vl-map {
            display: flex;
            align-items: flex-start;
            gap: 6px;
            font-size: 12.5px;
            line-height: 1.4;
        }

        .vl-map+.vl-map {
            margin-top: 4px;
        }

        .vl-map i {
            margin-top: 2px;
            color: #2f6bff;
        }

        .vl-section-title {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 12px;
            font-size: 14px;
            font-weight: 800;
            color: var(--rf-head);
        }

        .vl-section-title i {
            font-size: 18px;
            color: #2f6bff;
        }

        .vl-count {
            min-width: 24px;
            padding: 1px 8px;
            border-radius: 999px;
            font-size: 12px;
            text-align: center;
            color: #2f6bff;
            background: #e8efff;
        }

        html.dark .vl-count {
            background: rgba(47, 107, 255, .2);
            color: #8fb2ff;
        }

        .vl-add-row {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 10px;
            border: 1px dashed #2f6bff;
            font-size: 13px;
            font-weight: 700;
            color: #2f6bff;
            background: transparent;
            transition: background-color .15s;
        }

        .vl-add-row:hover {
            background: rgba(47, 107, 255, .08);
        }

        .vl-row {
            margin-bottom: 12px;
            padding: 14px 14px 2px;
            border: 1px solid var(--rf-border);
            border-left: 4px solid #2f6bff;
            border-radius: 12px;
            background: var(--rf-soft);
        }

        .vl-row-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .vl-row-no {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 700;
            color: var(--rf-head);
        }

        .vl-row-no i {
            color: #2f6bff;
        }

        .rfo-form .vl-row-del {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            color: #ef4444;
            background: transparent;
        }

        .rfo-form .vl-row-del:hover {
            background: rgba(239, 68, 68, .1);
        }

        .vl-empty-row {
            padding: 14px;
            border: 1px dashed var(--rf-border);
            border-radius: 12px;
            text-align: center;
            font-size: 13px;
            color: var(--rf-muted);
        }

        .vl-empty-row.hidden {
            display: none;
        }

        .vl-view-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .vl-view-table th,
        .vl-view-table td {
            padding: 8px 10px;
            border: 1px solid var(--rf-border);
            text-align: left;
            vertical-align: top;
        }

        .vl-view-table th {
            background: var(--rf-soft);
            font-weight: 700;
            white-space: nowrap;
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
            <!-- JUDUL -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic is-sky"><i class="ti ti-network"></i></span>
                    <div>
                        <h1 class="rfo-title">Data VLAN</h1>
                        <div class="rfo-subtitle">Daftar VLAN per lokasi / POP beserta mapping perangkatnya</div>
                    </div>
                </div>
                <button type="button" onclick="openVlan()" class="rfo-btn rfo-btn-blue border-0 cursor-pointer">
                    <i class="ti ti-plus"></i> Tambah VLAN
                </button>
            </div>

            <div class="card w-full min-w-0">
                <div class="card-body">
                    <!-- TOOLBAR -->
                    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
                        <div class="flex items-center gap-3 flex-wrap">
                            <div id="lengthArea"></div>
                            <?php
                            // pilihan filter dari isian yang sudah ada
                            $opsiFilter = function (string $kolom) use ($vlan) {
                                $v = array_unique(array_filter(array_map('trim', array_column($vlan, $kolom)), 'strlen'));
                                natcasesort($v);
                                return $v;
                            };
                            ?>
                            <select id="filterJenis" class="filter-select" title="Filter Jenis Layanan">
                                <option value="">Semua Jenis Layanan</option>
                                <?php foreach ($opsiFilter('jenis_layanan') as $v) : ?>
                                    <option value="<?= esc($v, 'attr') ?>"><?= esc($v) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select id="filterLokasi" class="filter-select" title="Filter Lokasi / POP">
                                <option value="">Semua Lokasi / POP</option>
                                <?php foreach ($opsiFilter('lokasi_pop') as $v) : ?>
                                    <option value="<?= esc($v, 'attr') ?>"><?= esc($v) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <select id="filterStatus" class="filter-select" title="Filter Status">
                                <option value="">Semua Status</option>
                                <?php foreach ($opsiStatus as $v) : ?>
                                    <option value="<?= esc($v, 'attr') ?>"><?= esc($v) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="flex items-center gap-3 flex-wrap">
                            <div class="rfo-search">
                                <i class="ti ti-search"></i>
                                <input type="text" id="customSearch" placeholder="Cari VLAN, perangkat, port..." />
                            </div>
                            <button type="button" class="rfo-ghost-btn rfo-reset"><i class="ti ti-refresh"></i> Reset</button>
                            <div id="exportArea"></div>
                        </div>
                    </div>

                    <!-- TABLE -->
                    <div class="table-scroll">
                        <table id="reportTable" class="rfo-table display nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Aksi</th>
                                    <th>VLAN ID</th>
                                    <th>Nama VLAN</th>
                                    <th>Jenis Layanan</th>
                                    <th>Lokasi / POP</th>
                                    <th>Status</th>
                                    <th>Mapping Perangkat</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1; ?>
                                <?php foreach ($vlan as $row) : ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <button type="button" data-row="<?= esc(json_encode($row), 'attr') ?>"
                                                onclick="openVlan(JSON.parse(this.dataset.row))" class="btn btn-sm btn-primary">
                                                <i class="ti ti-edit"></i> <span>Edit</span>
                                            </button>
                                            <button type="button" onclick="confirmDelete(<?= (int) $row['id'] ?>)" class="btn btn-sm btn-danger">
                                                <i class="ti ti-trash"></i> <span>Hapus</span>
                                            </button>
                                            <button type="button" data-row="<?= esc(json_encode($row), 'attr') ?>"
                                                onclick="openViewModal(JSON.parse(this.dataset.row))" class="btn btn-sm btn-info" title="Lihat">
                                                <i class="ti ti-eye"></i> <span>View</span>
                                            </button>
                                        </td>
                                        <td class="col-bold"><?= (int) $row['vlan_id'] ?></td>
                                        <td class="whitespace-normal min-w-[160px]"><?= esc($row['nama_vlan']) ?></td>
                                        <td><?= esc($row['jenis_layanan']) ?></td>
                                        <td class="whitespace-normal min-w-[140px]"><?= esc($row['lokasi_pop']) ?></td>
                                        <td><span class="vl-status <?= $row['status'] === 'Aktif' ? 'is-on' : 'is-off' ?>"><?= esc($row['status']) ?></span></td>
                                        <td class="whitespace-normal min-w-[260px]">
                                            <?php if ($row['perangkat']) : ?>
                                                <?php foreach ($row['perangkat'] as $p) : ?>
                                                    <div class="vl-map">
                                                        <i class="ti ti-router"></i>
                                                        <span><b><?= esc($p['nama_perangkat']) ?></b><?php
                                                            $info = array_filter([$p['vendor'], $p['interface_port'], $p['mode_port']], 'strlen');
                                                            echo $info ? ' &middot; ' . esc(implode(' · ', $info)) : '';
                                                        ?></span>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        <td class="whitespace-pre-line min-w-[220px]"><?= esc($row['keterangan'] ?: '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ====== POP UP VIEW ====== -->
    <div id="viewModal" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4" onclick="if (event.target === this) closeViewModal()">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-3xl p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold">View Data VLAN</h3>
                <button type="button" onclick="closeViewModal()" aria-label="Tutup"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div><div class="text-gray-500">VLAN ID</div><div class="font-medium" id="v_vlan_id"></div></div>
                <div><div class="text-gray-500">Nama VLAN</div><div class="font-medium" id="v_nama_vlan"></div></div>
                <div><div class="text-gray-500">Jenis Layanan</div><div class="font-medium" id="v_jenis_layanan"></div></div>
                <div><div class="text-gray-500">Lokasi / POP</div><div class="font-medium" id="v_lokasi_pop"></div></div>
                <div><div class="text-gray-500">Status</div><div class="font-medium" id="v_status"></div></div>
                <div><div class="text-gray-500">Dibuat</div><div class="font-medium" id="v_created"></div></div>
                <div class="md:col-span-2"><div class="text-gray-500">Keterangan VLAN</div><div class="font-medium whitespace-pre-line" id="v_keterangan"></div></div>
            </div>
            <div class="mt-6">
                <div class="font-bold mb-2 flex items-center gap-2"><i class="ti ti-router text-[#2f6bff]"></i> Mapping Perangkat</div>
                <div class="overflow-x-auto">
                    <table class="vl-view-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Perangkat</th>
                                <th>Vendor</th>
                                <th>Interface / Port</th>
                                <th>Mode Port</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="v_perangkat"></tbody>
                    </table>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeViewModal()" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">Tutup</button>
            </div>
        </div>
    </div>

    <!-- ====== POP UP FORM VLAN (tambah / edit) ====== -->
    <div id="modal_vlan" class="fixed inset-0 bg-black/50 hidden z-[1100] flex items-center justify-center p-4" onclick="if (event.target === this) closeVlan()">
        <div class="bg-white dark:bg-[#263240] rounded-xl shadow-xl w-full max-w-4xl p-6 max-h-[92vh] overflow-y-auto rfo-form">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold" id="vlJudulForm">Tambah VLAN</h3>
                <button type="button" onclick="closeVlan()" aria-label="Tutup"><i class="ti ti-x text-2xl"></i></button>
            </div>
            <form method="POST" id="formVlan" data-save="<?= site_url('Vlan/save') ?>" data-update="<?= site_url('Vlan/update') ?>">
                <?= csrf_field() ?>
                <input type="hidden" name="id">

                <div class="vl-section-title"><i class="ti ti-network"></i> Data VLAN</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
                    <div class="rf-group"><label class="rf-label">VLAN ID <span class="req">*</span></label><input class="rf-input" type="number" name="vlan_id" min="1" max="4094" required placeholder="1 - 4094"></div>
                    <div class="rf-group"><label class="rf-label">Nama VLAN <span class="req">*</span></label><input class="rf-input" name="nama_vlan" maxlength="150" required placeholder="Contoh: VLAN-CORP-JKT"></div>
                    <div class="rf-group">
                        <label class="rf-label">Jenis Layanan <span class="req">*</span></label>
                        <select class="rf-input" name="jenis_layanan" data-manual="jenis_layanan_manual" required>
                            <option value="">-- Pilih Jenis Layanan --</option>
                            <?php foreach ($opsiJenis as $v) : ?>
                                <option value="<?= esc($v, 'attr') ?>"><?= esc($v) ?></option>
                            <?php endforeach; ?>
                            <option value="__manual__">Lainnya (isi manual)...</option>
                        </select>
                        <input class="rf-input mt-2 hidden" name="jenis_layanan_manual" maxlength="100" placeholder="Tulis jenis layanan">
                    </div>
                    <div class="rf-group"><label class="rf-label">Lokasi / POP <span class="req">*</span></label><input class="rf-input" name="lokasi_pop" maxlength="150" required placeholder="Contoh: POP Duren Tiga"></div>
                    <div class="rf-group">
                        <label class="rf-label">Status <span class="req">*</span></label>
                        <select class="rf-input" name="status" required>
                            <?php foreach ($opsiStatus as $v) : ?>
                                <option value="<?= esc($v, 'attr') ?>"><?= esc($v) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="rf-group md:col-span-2"><label class="rf-label">Keterangan VLAN</label><textarea class="rf-input" name="keterangan" rows="2" placeholder="Masukkan keterangan VLAN"></textarea></div>
                </div>

                <!-- mapping perangkat: baris bisa ditambah / dihapus -->
                <div class="flex items-center justify-between gap-3 mt-2 mb-3">
                    <div class="vl-section-title mb-0"><i class="ti ti-router"></i> Mapping Perangkat <span class="vl-count" id="vlJumlahPerangkat">0</span></div>
                    <button type="button" class="vl-add-row" onclick="tambahBarisPerangkat()"><i class="ti ti-plus"></i> Tambah Perangkat</button>
                </div>
                <div id="vlPerangkat"></div>
                <div id="vlPerangkatKosong" class="vl-empty-row">Belum ada mapping perangkat. Klik <b>Tambah Perangkat</b> untuk menambahkan.</div>

                <div class="d-flex mt-4">
                    <button type="submit"><i class="ti ti-device-floppy"></i> Simpan</button>
                    <button type="button" class="btn-back" onclick="closeVlan()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <!-- template satu baris mapping perangkat -->
    <template id="tplPerangkat">
        <div class="vl-row">
            <div class="vl-row-head">
                <span class="vl-row-no"><i class="ti ti-router"></i> Perangkat <b></b></span>
                <button type="button" class="vl-row-del" onclick="hapusBarisPerangkat(this)" title="Hapus baris"><i class="ti ti-trash"></i> Hapus</button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4">
                <div class="rf-group"><label class="rf-label">Nama Perangkat <span class="req">*</span></label><input class="rf-input" name="pr_nama[]" maxlength="150" placeholder="Contoh: CORE-JKT-01"></div>
                <div class="rf-group">
                    <label class="rf-label">Vendor</label>
                    <select class="rf-input" name="pr_vendor[]" data-manual-next>
                        <option value="">-- Pilih Vendor --</option>
                        <?php foreach ($opsiVendor as $v) : ?>
                            <option value="<?= esc($v, 'attr') ?>"><?= esc($v) ?></option>
                        <?php endforeach; ?>
                        <option value="__manual__">Lainnya (isi manual)...</option>
                    </select>
                    <input class="rf-input mt-2 hidden" name="pr_vendor_manual[]" maxlength="100" placeholder="Tulis nama vendor">
                </div>
                <div class="rf-group"><label class="rf-label">Interface / Port</label><input class="rf-input" name="pr_port[]" maxlength="150" placeholder="Contoh: ether1 / Gi0/1 / sfp-sfpplus1"></div>
                <div class="rf-group">
                    <label class="rf-label">Mode Port</label>
                    <select class="rf-input" name="pr_mode[]" data-manual-next>
                        <option value="">-- Pilih Mode Port --</option>
                        <?php foreach ($opsiModePort as $v) : ?>
                            <option value="<?= esc($v, 'attr') ?>"><?= esc($v) ?></option>
                        <?php endforeach; ?>
                        <option value="__manual__">Other (isi manual)...</option>
                    </select>
                    <input class="rf-input mt-2 hidden" name="pr_mode_manual[]" maxlength="50" placeholder="Tulis mode port">
                </div>
                <div class="rf-group md:col-span-2"><label class="rf-label">Keterangan Perangkat</label><input class="rf-input" name="pr_keterangan[]" placeholder="Masukkan keterangan perangkat"></div>
            </div>
        </div>
    </template>

    <?php
    // Gagal simpan (validasi di server): pop up dibuka lagi dengan isian sebelumnya
    $popupGagal = session()->getFlashdata('popup') === 'vlan';
    $isianLama  = [];
    if ($popupGagal) {
        $pilih = static fn ($v, $m) => ($v === '__manual__' ? trim((string) $m) : (string) $v);
        foreach (['id', 'vlan_id', 'nama_vlan', 'lokasi_pop', 'status', 'keterangan'] as $k) {
            $isianLama[$k] = old($k, '', false);
        }
        $isianLama['jenis_layanan'] = $pilih(old('jenis_layanan', '', false), old('jenis_layanan_manual', '', false));
        $ambil = static fn ($k) => is_array(old($k, null, false)) ? array_values(old($k, null, false)) : [];
        $nama  = $ambil('pr_nama');
        $isianLama['perangkat'] = [];
        foreach ($nama as $i => $n) {
            $isianLama['perangkat'][] = [
                'nama_perangkat' => $n,
                'vendor'         => $pilih($ambil('pr_vendor')[$i] ?? '', $ambil('pr_vendor_manual')[$i] ?? ''),
                'interface_port' => $ambil('pr_port')[$i] ?? '',
                'mode_port'      => $pilih($ambil('pr_mode')[$i] ?? '', $ambil('pr_mode_manual')[$i] ?? ''),
                'keterangan'     => $ambil('pr_keterangan')[$i] ?? '',
            ];
        }
    }
    ?>

    <script>
        // ====== DROPDOWN DENGAN PILIHAN "ISI MANUAL" ======
        // input manual = elemen tepat setelah <select> (data-manual-next) atau yang namanya di data-manual
        function inputManual(sel) {
            return sel.dataset.manual ? sel.form.elements[sel.dataset.manual] : sel.nextElementSibling;
        }

        function aturManual(sel, fokus) {
            const manual = sel.value === '__manual__';
            const inp = inputManual(sel);
            inp.classList.toggle('hidden', !manual);
            inp.required = manual && sel.required;
            if (!manual) inp.value = '';
            else if (fokus) inp.focus();
        }

        // isi dropdown dari nilai tersimpan: nilai di luar pilihan = isi manual
        function setPilihan(sel, nilai) {
            nilai = nilai == null ? '' : String(nilai);
            const ada = [...sel.options].some(o => o.value === nilai && o.value !== '__manual__');
            sel.value = ada || nilai === '' ? nilai : '__manual__';
            aturManual(sel, false);
            if (!ada && nilai !== '') inputManual(sel).value = nilai;
        }

        document.addEventListener('change', function(e) {
            const sel = e.target;
            if (sel.matches && sel.matches('#formVlan select[data-manual], #formVlan select[data-manual-next]')) aturManual(sel, true);
        });

        // ====== BARIS MAPPING PERANGKAT ======
        const vlPerangkat = document.getElementById('vlPerangkat');

        function nomoriBaris() {
            const baris = vlPerangkat.querySelectorAll('.vl-row');
            baris.forEach((r, i) => r.querySelector('.vl-row-no b').textContent = i + 1);
            document.getElementById('vlJumlahPerangkat').textContent = baris.length;
            document.getElementById('vlPerangkatKosong').classList.toggle('hidden', baris.length > 0);
        }

        function tambahBarisPerangkat(data, fokus) {
            data = data || {};
            const node = document.getElementById('tplPerangkat').content.firstElementChild.cloneNode(true);
            vlPerangkat.appendChild(node);
            node.querySelector('[name="pr_nama[]"]').value = data.nama_perangkat || '';
            node.querySelector('[name="pr_port[]"]').value = data.interface_port || '';
            node.querySelector('[name="pr_keterangan[]"]').value = data.keterangan || '';
            setPilihan(node.querySelector('[name="pr_vendor[]"]'), data.vendor);
            setPilihan(node.querySelector('[name="pr_mode[]"]'), data.mode_port);
            nomoriBaris();
            if (fokus !== false) {
                node.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                node.querySelector('[name="pr_nama[]"]').focus();
            }
        }

        function hapusBarisPerangkat(btn) {
            btn.closest('.vl-row').remove();
            nomoriBaris();
        }

        // ====== POP UP FORM TAMBAH / EDIT ======
        function openVlan(data) {
            data = data || {};
            const form = document.getElementById('formVlan');
            const edit = !!data.id;

            form.reset();
            form.elements.id.value = edit ? data.id : '';
            form.setAttribute('action', edit ? form.dataset.update : form.dataset.save);
            document.getElementById('vlJudulForm').textContent = edit ? 'Edit VLAN' : 'Tambah VLAN';
            ['vlan_id', 'nama_vlan', 'lokasi_pop', 'keterangan'].forEach(k => form.elements[k].value = data[k] == null ? '' : data[k]);
            form.elements.status.value = data.status || 'Aktif';
            setPilihan(form.elements.jenis_layanan, data.jenis_layanan);

            vlPerangkat.innerHTML = '';
            (data.perangkat || []).forEach(p => tambahBarisPerangkat(p, false));
            nomoriBaris();

            document.getElementById('modal_vlan').classList.remove('hidden');
            setTimeout(() => form.elements.vlan_id.focus(), 50);
        }

        function closeVlan() {
            document.getElementById('modal_vlan').classList.add('hidden');
        }

        document.addEventListener('keydown', e => {
            if (e.key !== 'Escape') return;
            if (!document.getElementById('modal_vlan').classList.contains('hidden')) closeVlan();
            if (!document.getElementById('viewModal').classList.contains('hidden')) closeViewModal();
        });

        // ====== VALIDASI SEBELUM SUBMIT ======
        document.getElementById('formVlan').addEventListener('submit', function(e) {
            const f = this.elements;
            let pesan = '';
            const vid = Number(f.vlan_id.value);
            if (!f.vlan_id.value || !f.nama_vlan.value.trim() || !f.lokasi_pop.value.trim() || !f.jenis_layanan.value ||
                (f.jenis_layanan.value === '__manual__' && !f.jenis_layanan_manual.value.trim())) {
                pesan = 'Semua field bertanda * wajib diisi.';
            } else if (!Number.isInteger(vid) || vid < 1 || vid > 4094) {
                pesan = 'VLAN ID harus angka 1 - 4094.';
            } else {
                vlPerangkat.querySelectorAll('.vl-row').forEach(function(r, i) {
                    if (!pesan && !r.querySelector('[name="pr_nama[]"]').value.trim()) pesan = 'Nama Perangkat pada mapping baris ' + (i + 1) + ' wajib diisi (atau hapus barisnya).';
                });
            }
            if (pesan) {
                e.preventDefault();
                Swal.fire({ icon: 'warning', title: 'Form Belum Lengkap', text: pesan, confirmButtonColor: '#185a82' });
            }
        });

        // ====== VIEW ======
        const BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        function escapeHtml(s) {
            return (s == null ? '' : String(s)).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
        }

        function openViewModal(d) {
            const set = (id, v) => document.getElementById(id).textContent = (v === null || v === undefined || String(v).trim() === '') ? '-' : v;
            ['vlan_id', 'nama_vlan', 'jenis_layanan', 'lokasi_pop', 'status', 'keterangan'].forEach(k => set('v_' + k, d[k]));
            let dibuat = '';
            if (d.created_at) {
                const [y, m, t] = d.created_at.substring(0, 10).split('-');
                dibuat = Number(t) + ' ' + BULAN[Number(m) - 1] + ' ' + y + ' ' + d.created_at.substring(11, 16);
            }
            set('v_created', dibuat);
            const rows = d.perangkat || [];
            document.getElementById('v_perangkat').innerHTML = rows.length ? rows.map((p, i) => '<tr>' +
                '<td>' + (i + 1) + '</td>' +
                '<td><b>' + escapeHtml(p.nama_perangkat) + '</b></td>' +
                '<td>' + escapeHtml(p.vendor || '-') + '</td>' +
                '<td>' + escapeHtml(p.interface_port || '-') + '</td>' +
                '<td>' + escapeHtml(p.mode_port || '-') + '</td>' +
                '<td>' + escapeHtml(p.keterangan || '-') + '</td>' +
                '</tr>').join('') : '<tr><td colspan="6" class="text-center text-gray-400">Belum ada mapping perangkat.</td></tr>';
            document.getElementById('viewModal').classList.remove('hidden');
        }

        function closeViewModal() {
            document.getElementById('viewModal').classList.add('hidden');
        }

        // ====== DELETE ======
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Data VLAN?',
                text: 'VLAN beserta mapping perangkatnya akan dihapus dan tidak dapat dikembalikan.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) window.location.href = "<?= site_url('Vlan/delete/') ?>" + id;
            });
        }

        function isTableEmpty(table) {
            return table.rows({ search: 'applied' }).data().length === 0;
        }

        function showEmptyExportAlert() {
            Swal.fire({ icon: 'warning', title: 'Data Kosong', text: 'Tidak ada data yang bisa diexport.', confirmButtonColor: '#04a9f5' });
        }

        <?php if ($popupGagal) : ?>
            document.addEventListener('DOMContentLoaded', () => openVlan(<?= json_encode($isianLama, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>));
        <?php endif; ?>
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

    <script>
        $(document).ready(function() {
            const exportConfig = {
                exportOptions: {
                    columns: ':visible:not(:eq(1))',
                    format: {
                        body: function(data) {
                            const tmp = document.createElement('div');
                            tmp.innerHTML = String(data).replace(/<\/div>/g, '</div>\n');
                            return tmp.textContent.replace(/[ \t]+/g, ' ').replace(/\n\s*\n/g, '\n').trim();
                        }
                    }
                }
            };
            const tombol = (jenis, teks, ikon, extra) => Object.assign({
                extend: jenis,
                text: '<i class="ti ' + ikon + '"></i> ' + teks,
                title: 'Data VLAN',
                ...exportConfig,
                action: function(e, dt, button, config) {
                    if (isTableEmpty(dt)) return showEmptyExportAlert();
                    $.fn.dataTable.ext.buttons[jenis].action.call(this, e, dt, button, config);
                }
            }, extra || {});

            const table = $('#reportTable').DataTable({
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
                    buttons: [
                        tombol('copyHtml5', 'Copy', 'ti-copy'),
                        tombol('csvHtml5', 'Export CSV', 'ti-file-text'),
                        tombol('excelHtml5', 'Export Excel', 'ti-file-spreadsheet'),
                        tombol('pdfHtml5', 'Export PDF', 'ti-file-type-pdf', {
                            orientation: 'landscape',
                            pageSize: 'A4',
                            customize: function(doc) {
                                doc.styles.tableHeader = { fillColor: '#04a9f5', color: '#fff', bold: true, alignment: 'left' };
                                doc.defaultStyle.fontSize = 9;
                            }
                        })
                    ]
                }],
                language: {
                    lengthMenu: "_MENU_",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 - 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ data)",
                    emptyTable: "Data VLAN belum tersedia",
                    zeroRecords: "Tidak ada data yang cocok dengan pencarian",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Selanjutnya"
                    }
                }
            });

            // nomor urut mengikuti urutan & filter yang tampil
            table.on('draw.dt order.dt search.dt', function() {
                let i = table.page.info().start;
                table.column(0, { page: 'current', search: 'applied', order: 'applied' }).nodes().each(function(cell) {
                    cell.innerHTML = ++i;
                });
            });

            // ====== SCROLL TABEL: lebih dari 10 baris tampil = scroll di dalam card ======
            const MAKS_BARIS = 10;

            function batasiTinggiTabel() {
                const area = document.querySelector('.rfo-page .table-scroll');
                const baris = area.querySelectorAll('#reportTable tbody tr');
                area.style.maxHeight = '';
                if (baris.length > MAKS_BARIS) {
                    const atas = area.querySelector('#reportTable thead').getBoundingClientRect().top;
                    const bawah = baris[MAKS_BARIS - 1].getBoundingClientRect().bottom;
                    area.style.maxHeight = Math.ceil(bawah - atas + area.offsetHeight - area.clientHeight) + 'px';
                }
                area.scrollTop = 0;
            }
            table.on('draw.dt', batasiTinggiTabel);
            $(window).on('resize', batasiTinggiTabel);
            if (document.fonts && document.fonts.ready) document.fonts.ready.then(batasiTinggiTabel);

            table.draw();

            $('#lengthArea').append($('.dataTables_length'));
            $('#exportArea').append($('.dt-buttons'));

            // ====== FILTER DROPDOWN (cocok persis): 4 Jenis Layanan, 5 Lokasi / POP, 6 Status ======
            const filterKolom = (sel, col) => $(sel).on('change', function() {
                const re = this.value ? '^' + $.fn.dataTable.util.escapeRegex(this.value) + '$' : '';
                table.column(col).search(re, true, false).draw();
            });
            filterKolom('#filterJenis', 4);
            filterKolom('#filterLokasi', 5);
            filterKolom('#filterStatus', 6);

            $('#customSearch').on('keyup', function() {
                table.search(this.value).draw();
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