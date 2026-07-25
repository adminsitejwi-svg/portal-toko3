<?php

/** @var array $user */
$tglGabung = !empty($user['created_at']) ? date('d M Y, H:i', strtotime($user['created_at'])) : '-';
?>
<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('store.png') ?>">
    <title>Profile</title>
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
        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 1px 20px 0 rgba(69, 90, 100, .08);
            margin-bottom: 24px;
        }

        .card-head {
            /* judul "Foto Profil" & "Informasi" */
            padding: 16px 24px;
            border-bottom: 1px solid #eef1f4;
            font-weight: 600;
            font-size: 15px;
            color: #2b3540;
        }

        .card-body {
            /* isi kartu */
            padding: 24px;
        }

        .inp {
            /* kotak Username & Bergabung Sejak (readonly) */
            width: 100%;
            margin-top: 6px;
            padding: 10px 12px;
            border: 1px solid #e3e8ee;
            border-radius: 8px;
            font-size: 14px;
            background: #f3f4f6;
            color: #6b7280;
            cursor: not-allowed;
        }

        .grid-2 {
            /* layout 2 kolom */
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px 24px;
        }

        .avatar-icon {
            /* lingkaran ikon profil */
            width: 84px;
            height: 84px;
            border-radius: 50%;
            background: #eef4f8;
            color: #04a9f5;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f4f7fa;
        }

        .dark body,
        body.darkbody {
            background: #1d2630;
        }

        /* scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px
        }

        ::-webkit-scrollbar-thumb {
            background: #b9c1c9;
            border-radius: 3px
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #3a4658
        }

        /* card */
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

        .card-header {
            padding: 20px 25px;
            border-bottom: 1px solid #f1f1f1;
        }

        .dark .card-header {
            border-color: #37404c
        }

        .card-header h5 {
            font-size: 16px;
            font-weight: 500;
            margin: 0;
            color: #37474f
        }

        .dark .card-header h5 {
            color: #e7eaf0
        }

        .card-body {
            padding: 25px
        }

        /* sidebar transition */
        .pc-sidebar {
            transition: transform .25s ease, width .25s ease
        }

        .pc-link.active {
            color: #fff !important;
        }

        .pc-link.active .pc-micon {
            color: #04a9f5
        }

        /* dropdown */
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

        .custom-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }

        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .dark .custom-scroll::-webkit-scrollbar-thumb {
            background: #475569;
        }

        .dark .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        .brand-text {
            font-size: 18px;
        }

        .vendor-card table td,
        .vendor-card table th {
            white-space: nowrap;
        }

        .vendor-card .custom-scroll {
            scrollbar-width: thin;
        }

        .col-full {
            grid-column: 1 / -1;
        }
    </style>
</head>

<body class="text-[#37474f] dark:text-[#bfc8d6]">
    <?php if (session()->getFlashdata('success')) : ?>
        <div id="successAlert" class="fixed top-5 left-1/2 -translate-x-1/2 z-[2000] w-full max-w-md px-4">
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
        <div id="errorAlert" class="fixed top-5 left-1/2 -translate-x-1/2 z-[2000] w-full max-w-md px-4">
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
    <?php endif; ?>
    <!-- ============ SIDEBAR ============ -->
    <nav id="sidebar" class="pc-sidebar fixed top-0 left-0 h-screen w-sidebar bg-sidebar text-[#a9b7c6] z-[1030] flex flex-col">
        <div class="flex items-center h-header px-6 shrink-0">
            <a href="#" class="flex items-center gap-2 text-white text-2xl font-semibold">
                <span class="text-primary-500"></span>
                <span class="brand-text">Sistem Operasional <br> JWI Group</span>
            </a>
        </div>

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
                        <li><a href="<?= site_url('RipotRetail') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Task On Progress</a></li>
                        <li><a href="<?= site_url('RipotRetail/progress') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Task Done</a></li>
                        <li><a href="<?= site_url('RipotActive') ?>" class="block pl-[52px] pr-6 py-2 text-[13px] hover:text-white">Aktivasi Retail</a></li>
                    </ul>
                </li>
                <li><a href="<?= site_url('Map') ?>" class="pc-link flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white"><span class="pc-micon w-5"><i class="ti ti-map-pin"></i></span><span>Lokasi</span></a></li>

                <li class="px-6 py-3 text-[11px] uppercase tracking-wide text-[#5b6b7f] font-semibold">Informasi</li>
                <li>
                    <a href="<?= site_url('Profile') ?>" class="pc-link active flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">
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
        <header class="pc-header sticky top-0 z-[1025] bg-white dark:bg-[#263240] h-header flex items-center px-6 shadow-[0_1px_20px_0_rgba(69,90,100,.08)]">
            <ul class="flex items-center gap-1">
                <li><a href="#" onclick="toggleSidebar();return false;" class="head-link flex items-center justify-center w-10 h-10 rounded hover:bg-gray-100 dark:hover:bg-white/5"><i data-feather="menu"></i></a></li>

            </ul>

            <ul class="flex items-center gap-1 ml-auto">
                <!-- theme -->

                <!-- settings -->

                <!-- notifications -->

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
                            <button onclick="window.location.href='<?= site_url('logout') ?>'"
                                class="w-full mt-3 bg-primary-500 hover:bg-red-600 text-white py-2 rounded flex items-center justify-center gap-2 text-sm">

                                <i class="fas fa-sign-out-alt"></i>
                                Logout

                            </button>
                        </div>
                    </div>
                </li>
            </ul>
        </header>

        <div class="p-6 max-w-[980px] mx-auto">
            <!-- ===== FOTO PROFIL (ikon) ===== -->
            <div class="card">
                <div class="card-head">Halaman Profile</div>
                <div class="card-body">
                    <div style="display:flex;align-items:center;gap:24px;flex-wrap:wrap">
                        <div class="avatar-icon">
                            <i class="ti ti-user" style="font-size:44px"></i>
                        </div>
                        <div>
                            <div>
                                <div style="font-weight:600;font-size:16px;color:#2b3540"><?= esc($user['username']) ?></div>
                                <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:8px">
                                    <a href="<?= site_url('forgot-password') ?>"
                                        style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:#04a9f5;color:#fff;border-radius:8px;font-size:13px;font-weight:600;text-decoration:none;transition:background .2s"
                                        onmouseover="this.style.background='#0396e2'"
                                        onmouseout="this.style.background='#04a9f5'">
                                        <i class="ti ti-lock"></i> Ubah Password
                                    </a>

                                    <button type="button" onclick="konfirmasiHapusAkun()"
                                        style="display:inline-flex;align-items:center;gap:6px;padding:8px 16px;background:#dc2626;color:#fff;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;transition:background .2s"
                                        onmouseover="this.style.background='#b91c1c'"
                                        onmouseout="this.style.background='#dc2626'">
                                        <i class="ti ti-trash"></i> Hapus Akun
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <form id="formHapusAkun" action="<?= site_url('profile/delete') ?>" method="post" style="display:none">
                <?= csrf_field() ?>
            </form>
            <!-- ===== INFORMASI ===== -->
            <div class="card">
                <div class="card-head">Informasi</div>
                <div class="card-body">
                    <div class="grid-2">
                        <div>
                            <label>Username</label>
                            <input type="text" class="inp" value="<?= esc($user['username']) ?>" readonly>
                        </div>
                        <div>
                            <label>Bergabung Sejak</label>
                            <input type="text" class="inp" value="<?= esc($tglGabung) ?>" readonly>
                        </div>
                        <div class="col-full">
                            <label>Terakhir Ganti Password</label>
                            <input type="text" id="pwChangedAt" class="inp col-full" value="—" readonly>
                        </div>

                    </div>


                </div>

            </div>

        </div>
    </div>
    <script>
        function konfirmasiHapusAkun() {
            Swal.fire({
                title: 'Hapus Akun?',
                text: 'Data akun Anda akan dihapus secara permanen, Dan tidak bisa login kembali.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, hapus akun',
                cancelButtonText: 'Batal'
            }).then(function(result) {
                if (result.isConfirmed) {
                    document.getElementById('formHapusAkun').submit();
                }
            });
        }
        document.addEventListener('DOMContentLoaded', function() {
            ['successAlert', 'errorAlert'].forEach(function(id) {
                const box = document.getElementById(id);
                if (!box) return;
                const bar = box.querySelector('#progressBar, #progressBarError');
                if (bar) {
                    bar.style.transition = 'width 3s linear';
                    setTimeout(() => {
                        bar.style.width = '0%';
                    }, 100);
                }
                setTimeout(function() {
                    box.style.transition = 'all .5s ease';
                    box.style.opacity = '0';
                    box.style.transform = 'translate(-50%, -20px)';
                    setTimeout(() => box.remove(), 500);
                }, 3000);
            });
        });
    </script>
    <script>
        (function() {
            const KEY = 'pwChangedAt';
            const el = document.getElementById('pwChangedAt');
            if (!el) return;

            <?php if (session()->getFlashdata('pw_just_changed')): ?>
                // Baru saja ganti password → catat waktu lokal Windows user
                const now = new Date();
                const tgl = now.toLocaleString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
                localStorage.setItem(KEY, tgl);
                el.value = tgl;
            <?php else: ?>
                // Bukan dari aksi ganti password → tampilkan nilai tersimpan (kalau ada)
                const saved = localStorage.getItem(KEY);
                el.value = saved ? saved : '—';
            <?php endif; ?>
        })();

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

    <?php if (!session()->get('logged_in')) : ?>
        <script>
            window.location.href = "<?= base_url('/login') ?>";
        </script>
    <?php endif; ?>
</body>

</html>