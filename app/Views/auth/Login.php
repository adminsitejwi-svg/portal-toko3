<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="<?= base_url('favicon-rumahwifi.png') ?>?v=2">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <?= view('partials/theme') ?>
    <style>
        /* Halaman login (referensi: refensiLogin.png) */
        /* latar: gambar jaringan (public/Logo/Background Login.jpg) + lapisan gelap tipis agar kartu tetap menonjol */
        body.login-page {
            background:
                linear-gradient(180deg, rgba(8, 18, 48, .25), rgba(8, 18, 48, .45)),
                url("<?= base_url('Logo/Background%20Login.jpg') ?>") center / cover no-repeat fixed,
                #0b1c45 !important;
        }

        html.dark body.login-page {
            background:
                linear-gradient(180deg, rgba(4, 10, 30, .55), rgba(4, 10, 30, .7)),
                url("<?= base_url('Logo/Background%20Login.jpg') ?>") center / cover no-repeat fixed,
                #071330 !important;
        }

        .login-card {
            background: var(--ra-card);
            border: 1px solid #eef1f8;
            border-radius: 24px;
            padding: 40px 38px 32px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, .45);
        }

        html.dark .login-card {
            border-color: var(--ra-border);
            box-shadow: 0 24px 60px rgba(0, 0, 0, .4);
        }

        .login-logo {
            width: 92px;
            height: 92px;
            padding: 16px;
            border-radius: 24px;
            background: linear-gradient(135deg, #eef0ff, #e4ebff);
            box-shadow: 0 10px 24px rgba(47, 107, 255, .15);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        html.dark .login-logo {
            background: #eef0ff;
        }

        .login-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .login-title {
            font-size: 32px;
            font-weight: 800;
            line-height: 1.15;
            color: var(--ra-heading);
        }

        .login-sub {
            margin-top: 6px;
            font-size: 16px;
            color: var(--ra-muted);
        }

        .login-label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 700;
            color: var(--ra-heading);
        }

        .login-input {
            width: 100%;
            height: 52px;
            border: 1px solid #e3e8f4;
            border-radius: 12px;
            padding: 0 1rem 0 3rem;
            font-size: 15px;
            background: #f5f7fd;
            color: var(--ra-heading);
            transition: border-color .15s, box-shadow .15s, background-color .15s;
        }

        html.dark .login-input {
            background: var(--ra-input);
            border-color: var(--ra-border);
        }

        .login-input:focus {
            outline: none;
            background: var(--ra-card);
            border-color: #6d8dff;
            box-shadow: 0 0 0 4px rgba(47, 107, 255, .12);
        }

        .login-icon {
            position: absolute;
            left: 1.05rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--ra-muted);
            font-size: 20px;
        }

        .login-eye {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: var(--ra-muted);
        }

        .login-eye:hover {
            color: #2f6bff;
        }

        .login-check {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            color: var(--ra-text);
            cursor: pointer;
        }

        .login-check input {
            width: 20px;
            height: 20px;
            accent-color: #3b5bff;
            cursor: pointer;
        }

        .login-btn {
            position: relative;
            width: 100%;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            font-size: 17px;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(90deg, #3b5bff 0%, #5b6cff 50%, #3d7bff 100%);
            box-shadow: 0 12px 26px rgba(59, 91, 255, .35);
            transition: transform .15s, box-shadow .15s, filter .15s;
        }

        .login-btn:hover {
            transform: translateY(-1px);
            filter: brightness(1.05);
            box-shadow: 0 16px 30px rgba(59, 91, 255, .42);
        }

        .login-btn:active {
            transform: translateY(1px);
        }

        .login-btn-arrow {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            background: rgba(255, 255, 255, .22);
        }

        .login-divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin: 26px 0 20px;
            font-size: 13px;
            color: var(--ra-muted);
        }

        .login-divider::before,
        .login-divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #e6eaf3;
        }

        html.dark .login-divider::before,
        html.dark .login-divider::after {
            background: var(--ra-border);
        }

        .login-info {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px;
            border-radius: 14px;
            background: #f5f7fd;
            border: 1px solid #eef1f8;
        }

        html.dark .login-info {
            background: var(--ra-input);
            border-color: var(--ra-border);
        }

        .login-info-ic {
            flex: 0 0 auto;
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            color: #3b5bff;
            background: #e4e9ff;
        }

        html.dark .login-info-ic {
            color: #a5b4fc;
            background: rgba(59, 91, 255, .2);
        }

        .login-info b {
            display: block;
            font-size: 14px;
            color: var(--ra-heading);
        }

        .login-info div span {
            display: block;
            margin-top: 2px;
            font-size: 13px;
            color: var(--ra-muted);
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 30px 22px 24px;
            }

            .login-title {
                font-size: 26px;
            }
        }
    </style>
</head>

<body class="login-page min-h-screen flex items-center justify-center p-4">

    <!-- ALERT SUCCESS -->
    <?php if (session()->getFlashdata('success')) : ?>

        <div id="successAlert"
            class="fixed top-5 left-1/2 -translate-x-1/2 z-50 w-full max-w-md px-4">

            <div class="bg-green-500 text-white rounded-xl shadow-xl overflow-hidden">

                <div class="flex items-center gap-3 px-5 py-4">

                    <i class="ti ti-circle-check text-3xl"></i>

                    <div>
                        <h4 class="font-bold">
                            Berhasil
                        </h4>

                        <p class="text-sm">
                            <?= session()->getFlashdata('success') ?>
                        </p>
                    </div>

                </div>

                <!-- Progress Bar -->
                <div class="h-1 bg-green-400">

                    <div id="progressBar"
                        class="h-full bg-white w-full">
                    </div>

                </div>

            </div>

        </div>

    <?php endif; ?>

    <div class="login-card w-full max-w-[460px]">

        <!-- logo & judul -->
        <div class="flex flex-col items-center text-center mb-7">
            <div class="login-logo mb-5">
                <img src="<?= base_url('Logo/RumahWifi-logo.png') ?>" alt="Logo JWI Group">
            </div>
            <h1 class="login-title">Selamat Datang</h1>
            <p class="login-sub">Sistem Operasional JWI Group</p>
        </div>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="flex items-start gap-2 bg-red-50 border border-red-200 text-red-600 p-3 rounded-xl mb-5 text-sm">
                <i class="ti ti-alert-circle text-lg leading-5"></i>
                <div><?= session()->getFlashdata('error') ?></div>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('login/auth') ?>" method="post" id="loginForm">

            <?= csrf_field() ?>

            <div class="mb-5">
                <label class="login-label" for="username">Username</label>
                <div class="relative">
                    <i class="ti ti-user login-icon"></i>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="<?= esc(old('username') ?? '') ?>"
                        class="login-input"
                        placeholder="Masukkan Username"
                        autocomplete="username"
                        required
                        autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label class="login-label" for="password">Password</label>
                <div class="relative">
                    <i class="ti ti-lock login-icon"></i>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="login-input pr-12"
                        placeholder="Masukkan Password"
                        autocomplete="current-password"
                        required>

                    <button
                        type="button"
                        onclick="togglePassword()"
                        title="Tampilkan / sembunyikan password"
                        class="login-eye">
                        <i id="eyeIcon" class="ti ti-eye"></i>
                    </button>
                </div>
            </div>

            <!-- ingat saya (hanya username, disimpan di browser) -->
            <div class="flex items-center gap-3 mb-6">
                <label class="login-check">
                    <input type="checkbox" id="rememberMe">
                    <span>Ingat saya</span>
                </label>
            </div>

            <button type="submit" class="login-btn">
                <span class="flex items-center justify-center gap-2"><i class="ti ti-login-2 text-xl"></i> Login</span>
                <span class="login-btn-arrow"><i class="ti ti-arrow-right"></i></span>
            </button>

        </form>

        <div class="login-divider"><span>atau</span></div>

        <div class="login-info">
            <span class="login-info-ic"><i class="ti ti-shield-check-filled"></i></span>
            <div>
                <b>Akses hanya untuk pengguna terdaftar</b>
                <span>Pastikan akun Anda aman dan tidak dibagikan.</span>
            </div>
        </div>
    </div>

    <script>
        // SHOW / HIDE PASSWORD
        function togglePassword() {

            let password = document.getElementById('password');
            let icon = document.getElementById('eyeIcon');

            if (password.type === 'password') {

                password.type = 'text';

                icon.classList.remove('ti-eye');
                icon.classList.add('ti-eye-off');

            } else {

                password.type = 'password';

                icon.classList.remove('ti-eye-off');
                icon.classList.add('ti-eye');
            }
        }

        // INGAT SAYA: hanya username yang disimpan di browser ini (password tidak pernah disimpan)
        (function() {
            const KEY = 'login_username';
            const user = document.getElementById('username');
            const cek = document.getElementById('rememberMe');
            let saved = null;
            try {
                saved = localStorage.getItem(KEY);
            } catch (e) {}
            if (saved && !user.value) {
                user.value = saved;
                cek.checked = true;
                user.removeAttribute('autofocus');
                document.getElementById('password').focus();
            } else if (saved) {
                cek.checked = true;
            }
            document.getElementById('loginForm').addEventListener('submit', function() {
                try {
                    if (cek.checked) localStorage.setItem(KEY, user.value.trim());
                    else localStorage.removeItem(KEY);
                } catch (e) {}
            });
        })();

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

                    alertBox.style.transition = "all 0.5s ease";
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