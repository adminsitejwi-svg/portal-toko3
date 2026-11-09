<!DOCTYPE html>
<html lang="id" data-ra-tema="penuh">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <?= view('partials/theme') ?>
    <style>
        /* Halaman login (referensi: public/d/Tampilan login.jpg)
           latar halaman = gambar Background Login.jpg (sama di mode terang & gelap);
           panel kiri selalu navy; panel kanan (form): mode terang navy, mode gelap putih
           kartu dua kolom: kiri panel navy (branding NOC), kanan form Sign In */
        /* latar halaman: gambar jaringan (public/Logo/Background Login.jpg), sama di mode terang & gelap */
        body.login-page,
        html.dark body.login-page {
            font-family: 'Inter', sans-serif !important;
            background: url("<?= base_url('Logo/Background%20Login.jpg') ?>") center / cover no-repeat fixed, #0b1c45 !important;
        }

        .lg-card {
            display: grid;
            grid-template-columns: 1fr 1fr;
            width: 100%;
            max-width: 1060px;
            min-height: 620px;
            border-radius: 22px;
            overflow: hidden;
            background: var(--ra-card);
            box-shadow: 0 24px 60px rgba(15, 30, 60, .16);
        }

        html.dark .lg-card {
            box-shadow: 0 24px 60px rgba(0, 0, 0, .45);
        }

        /* panel kanan (form Sign In):
           mode terang = navy, mengikuti panel kiri; mode gelap = putih.
           variabel warna tema dibuat khusus di dalam panel ini */
        .lg-form {
            background: linear-gradient(160deg, #13284b 0%, #142a4c 100%);
            color-scheme: dark;
            --ra-card: #142a4c;
            --ra-heading: #ffffff;
            --ra-muted: #a9b8d6;
            --ra-input: #0f2142;
            --ra-border: #2a4270;
            --lg-aksen: #7aa7ff;
        }

        html.dark .lg-form {
            background: #ffffff;
            color-scheme: light;
            --ra-card: #ffffff;
            --ra-heading: #14213d;
            --ra-muted: #7a869a;
            --ra-input: #ffffff;
            --ra-border: #e1e6ee;
            --lg-aksen: #2f6fd6;
        }

        /* isian otomatis browser mengikuti warna kotak isian */
        .lg-input:-webkit-autofill {
            -webkit-text-fill-color: var(--ra-heading);
            box-shadow: 0 0 0 1000px var(--ra-input) inset !important;
        }

        /* ===== panel kiri ===== */
        .lg-brand {
            position: relative;
            display: flex;
            flex-direction: column;
            padding: 54px 52px 52px;
            color: #fff;
            background:
                radial-gradient(ellipse 60% 45% at 82% 10%, rgba(45, 105, 190, .55), transparent 70%),
                radial-gradient(ellipse 50% 40% at 20% 0%, rgba(30, 70, 140, .35), transparent 70%),
                linear-gradient(160deg, #10203f 0%, #13284b 55%, #142a4c 100%);
            overflow: hidden;
        }

        /* pola garis halus */
        .lg-brand::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, .025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .025) 1px, transparent 1px);
            background-size: 4px 4px;
            pointer-events: none;
        }

        .lg-brand > * {
            position: relative;
        }

        /* logo N biru terang (public/Logo/Logo-N2.png) menggantikan tulisan "JWI", tanpa kotak */
        .lg-logo {
            display: inline-flex;
            align-items: center;
        }

        .lg-logo img {
            display: block;
            height: 72px;
            width: auto;
        }

        .lg-kecil {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .2em;
            text-transform: uppercase;
            color: rgba(214, 226, 245, .8);
        }

        .lg-garis {
            width: 46px;
            height: 3px;
            border-radius: 3px;
            background: #4f8df7;
        }

        .lg-judul {
            font-size: 44px;
            font-weight: 800;
            line-height: 1.12;
            letter-spacing: -.02em;
        }

        .lg-desk {
            max-width: 370px;
            font-size: 14.5px;
            line-height: 1.75;
            color: rgba(222, 232, 248, .85);
        }

        /* ===== panel kanan ===== */
        .lg-form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 56px 80px 36px;
        }

        .lg-form-isi {
            width: 100%;
            max-width: 366px;
            margin: auto;
        }

        .lg-welcome {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: var(--lg-aksen);
        }

        .lg-title {
            margin-top: 10px;
            font-size: 27px;
            font-weight: 800;
            letter-spacing: -.02em;
            color: var(--ra-heading) !important;
        }

        .lg-sub {
            margin-top: 12px;
            font-size: 13px;
            color: var(--ra-muted);
        }

        .lg-label {
            display: block;
            margin-bottom: 8px;
            font-size: 12.5px;
            font-weight: 700;
            color: var(--ra-heading) !important;
        }

        .lg-input {
            width: 100%;
            height: 48px;
            padding: 0 15px;
            border: 1px solid var(--ra-border);
            border-radius: 9px;
            font-size: 13.5px;
            background: var(--ra-input);
            color: var(--ra-heading);
            transition: border-color .15s, box-shadow .15s;
        }

        .lg-input::placeholder {
            color: #8a94a6;
        }

        .lg-input:focus {
            outline: none;
            border-color: #2f6fd6;
            box-shadow: 0 0 0 4px rgba(47, 111, 214, .12);
        }

        .lg-hide {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            display: grid;
            place-items: center;
            width: 32px;
            height: 32px;
            padding: 0;
            border: 0;
            background: transparent !important;
            box-shadow: none !important;
            outline: none !important;
            font-size: 20px;
            color: #8a94a6;
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
            transition: color .15s;
        }

        .lg-hide:focus,
        .lg-hide:focus-visible,
        .lg-hide:active {
            outline: none !important;
            box-shadow: none !important;
            background: transparent !important;
        }

        .lg-hide:hover {
            color: var(--lg-aksen);
        }

        .lg-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            height: 48px;
            border-radius: 9px;
            font-size: 13.5px;
            font-weight: 700;
            color: #fff;
            background: #2267d4;
            box-shadow: 0 10px 22px rgba(34, 103, 212, .25);
            transition: background-color .15s, transform .15s, box-shadow .15s;
        }

        .lg-btn:hover {
            background: #1d5bbd;
            transform: translateY(-1px);
            box-shadow: 0 14px 26px rgba(34, 103, 212, .32);
        }

        .lg-btn:active {
            transform: translateY(1px);
        }

        .lg-btn i {
            font-size: 18px;
        }

        .lg-aman {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 26px;
            font-size: 11px;
            color: var(--ra-muted);
        }

        .lg-aman::before {
            content: "";
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #10b981;
        }

        .lg-copy {
            margin-top: 64px;
            text-align: center;
            font-size: 9.5px;
            color: var(--ra-muted);
        }

        /* panel kiri (Network Management System) selalu navy di mode terang & gelap;
           warna teksnya dibuat tegas agar tidak tertimpa warna judul/teks bawaan ruang.css */
        body.login-page .lg-brand .lg-judul { color: #ffffff !important; }
        body.login-page .lg-brand .lg-desk { color: rgba(222, 232, 248, .85); }
        body.login-page .lg-brand .lg-kecil { color: rgba(214, 226, 245, .8); }
        body.login-page .lg-brand .lg-logo { color: #ffffff; }

        /* animasi saat dibuka: kartu naik, isi muncul berurutan (delay dari --i) */
        @keyframes lgCardIn {
            from {
                opacity: 0;
                transform: translateY(30px) scale(.98);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        @keyframes lgItemIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        .lg-card {
            animation: lgCardIn .6s cubic-bezier(.22, 1, .36, 1) backwards;
        }

        .lg-anim {
            animation: lgItemIn .5s cubic-bezier(.22, 1, .36, 1) backwards;
            animation-delay: calc(.3s + var(--i, 0) * .07s);
        }

        /* tombol dark/light melayang (dipasang ruang.js) */
        @keyframes lgToggleIn {
            from {
                opacity: 0;
                transform: scale(.3) rotate(-180deg);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        body.login-page .ra-theme-toggle.floating {
            animation: lgToggleIn .6s cubic-bezier(.34, 1.56, .64, 1) .9s backwards;
        }

        /* tahan animasi selama layar "Memuat halaman" (ruang.css) masih tampil */
        html.ra-pageload:not(.ra-pageload-out) body.login-page * {
            animation-play-state: paused;
        }

        @media (prefers-reduced-motion: reduce) {

            .lg-card,
            .lg-anim,
            body.login-page .ra-theme-toggle.floating {
                animation: none;
            }
        }

        @media (max-width: 900px) {
            .lg-card {
                grid-template-columns: 1fr;
                min-height: 0;
            }

            .lg-brand {
                padding: 36px 28px;
            }

            .lg-brand .lg-spasi {
                display: none;
            }

            .lg-judul {
                font-size: 32px;
            }

            .lg-form {
                padding: 40px 28px 28px;
            }

            .lg-copy {
                margin-top: 36px;
            }
        }
    </style>
</head>

<body class="login-page min-h-screen flex items-center justify-center p-4">

    <!-- ALERT SUCCESS -->
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
                <!-- Progress Bar -->
                <div class="h-1 bg-green-400">
                    <div id="progressBar" class="h-full bg-white w-full"></div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="lg-card">

        <!-- ===== KIRI: branding ===== -->
        <div class="lg-brand">
            <div class="lg-logo lg-anim" style="--i:0"><img src="<?= base_url('Logo/Logo-N2.png') ?>" alt="Logo Network Operasional"></div>
            <div class="lg-kecil mt-5 lg-anim" style="--i:1">Network Operation Center</div>

            <div class="lg-garis mt-[66px] lg-anim" style="--i:2"></div>
            <h1 class="lg-judul mt-7 lg-anim" style="--i:3">Network<br>Management<br>System</h1>
            <p class="lg-desk mt-9 lg-anim" style="--i:4">Integrated Customer, Activation &amp; Network Operation Management.</p>

            <div class="flex-1 lg-spasi"></div>
            <div class="lg-kecil lg-anim" style="--i:5; font-size:10px; letter-spacing:.16em">JWI Group · Internal Management System</div>
        </div>

        <!-- ===== KANAN: form Sign In ===== -->
        <div class="lg-form">
            <div class="lg-form-isi">
                <div class="lg-welcome lg-anim" style="--i:2">Welcome Back</div>
                <h2 class="lg-title lg-anim" style="--i:3">Sign in to your account</h2>
                <p class="lg-sub lg-anim" style="--i:4">Masuk untuk mengakses sistem operasional jaringan.</p>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="lg-anim flex items-start gap-2 bg-red-50 border border-red-200 text-red-600 p-3 rounded-lg mt-6 text-sm">
                        <i class="ti ti-alert-circle text-lg leading-5"></i>
                        <div><?= session()->getFlashdata('error') ?></div>
                    </div>
                <?php endif; ?>

                <form action="<?= site_url('login/auth') ?>" method="post" id="loginForm" class="mt-9">
                    <?= csrf_field() ?>

                    <div class="mb-6 lg-anim" style="--i:5">
                        <label class="lg-label" for="username">Username</label>
                        <input type="text" id="username" name="username" value="<?= esc(old('username') ?? '') ?>"
                            class="lg-input" placeholder="Masukkan username" autocomplete="username" required autofocus>
                    </div>

                    <div class="mb-7 lg-anim" style="--i:6">
                        <label class="lg-label" for="password">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" class="lg-input pr-16"
                                placeholder="Masukkan password" autocomplete="current-password" required>
                            <button type="button" onclick="togglePassword()" id="togglePass" class="lg-hide" aria-label="Tampilkan / sembunyikan password" title="Tampilkan password"><i id="eyeIcon" class="ti ti-eye-off"></i></button>
                        </div>
                    </div>

                    <button type="submit" class="lg-btn lg-anim" style="--i:7">
                        Sign In <i class="ti ti-arrow-right"></i>
                    </button>
                </form>

                <div class="lg-aman lg-anim" style="--i:8">Secure internal access</div>
                <div class="lg-copy lg-anim" style="--i:9">&copy; <?= date('Y') ?> JWI Group. All rights reserved.</div>
            </div>
        </div>
    </div>

    <script>
        // SHOW / HIDE PASSWORD: ikon mata dicoret = tersembunyi, mata terbuka = terlihat
        function togglePassword() {
            const password = document.getElementById('password');
            const tombol = document.getElementById('togglePass');
            const tampil = password.type === 'password';
            password.type = tampil ? 'text' : 'password';
            document.getElementById('eyeIcon').className = 'ti ' + (tampil ? 'ti-eye' : 'ti-eye-off');
            tombol.title = tampil ? 'Sembunyikan password' : 'Tampilkan password';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const alertBox = document.getElementById('successAlert');
            const progressBar = document.getElementById('progressBar');
            if (!alertBox) return;
            if (progressBar) {
                progressBar.style.transition = "width 3s linear";
                setTimeout(() => progressBar.style.width = "0%", 100);
            }
            setTimeout(() => {
                alertBox.style.transition = "all 0.5s ease";
                alertBox.style.opacity = "0";
                alertBox.style.transform = "translate(-50%, -20px)";
                setTimeout(() => alertBox.remove(), 500);
            }, 3000);
        });
    </script>
    <script>
        // PENGHALANG KOSMETIK SAJA — bukan security, mudah dilewati
        document.addEventListener('contextmenu', e => e.preventDefault()); // klik kanan
        document.addEventListener('keydown', e => {
            if (e.key === 'F12') e.preventDefault(); // F12
            if (e.ctrlKey && e.shiftKey && ['I', 'J', 'C'].includes(e.key.toUpperCase())) e.preventDefault();
            if (e.ctrlKey && e.key.toUpperCase() === 'U') e.preventDefault(); // view-source
        });
    </script>
</body>

</html>
