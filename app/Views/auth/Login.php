<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="<?= base_url('store.png') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">


    <?= view('partials/theme') ?>
    <style>
        /* Halaman login gaya RuangAdmin (warna dari ruang.css) */
        body.login-page {
            background: linear-gradient(135deg, #6777ef 0%, #5566e6 45%, #3f4bb8 100%) !important;
        }

        html.dark body.login-page {
            background: linear-gradient(135deg, #2b2f55 0%, #1f2238 60%, #1a1c28 100%) !important;
        }

        .login-card {
            background: var(--ra-card);
            border-radius: .6rem;
            box-shadow: 0 1rem 3rem rgba(15, 23, 42, .25);
        }

        .login-logo {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            background: var(--ra-primary-soft);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-input {
            width: 100%;
            border: 1px solid #d1d3e2;
            border-radius: .5rem;
            padding: .8rem 1rem .8rem 2.75rem;
            background: var(--ra-input);
            color: var(--ra-heading);
            transition: border-color .15s, box-shadow .15s;
        }

        html.dark .login-input {
            border-color: var(--ra-border);
        }

        .login-input:focus {
            outline: none;
            border-color: #a7b0f6;
            box-shadow: 0 0 0 .2rem rgba(103, 119, 239, .25);
        }

        .login-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--ra-muted);
            font-size: 18px;
        }

        .login-btn {
            width: 100%;
            background: var(--ra-primary);
            color: #fff;
            font-weight: 700;
            padding: .8rem;
            border-radius: .5rem;
            box-shadow: 0 .25rem .75rem rgba(103, 119, 239, .4);
            transition: background .15s, transform .1s;
        }

        .login-btn:hover {
            background: var(--ra-primary-dark);
        }

        .login-btn:active {
            transform: translateY(1px);
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

    <div class="login-card w-full max-w-md p-8 sm:p-10">

        <!-- logo & judul -->
        <div class="flex flex-col items-center text-center mb-8">
            <div class="login-logo mb-4">
                <i class="ti ti-building-store text-3xl" style="color: var(--ra-primary)"></i>
            </div>
            <h1 class="text-2xl font-extrabold" style="color: var(--ra-heading)">Selamat Datang</h1>
            <p class="text-sm mt-1" style="color: var(--ra-muted)">Sistem Operasional JWI Group</p>
        </div>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="flex items-start gap-2 bg-red-50 border border-red-200 text-red-600 p-3 rounded-lg mb-5 text-sm">
                <i class="ti ti-alert-circle text-lg leading-5"></i>
                <div><?= session()->getFlashdata('error') ?></div>
            </div>
        <?php endif; ?>

        <form action="<?= site_url('login/auth') ?>" method="post">

            <?= csrf_field() ?>

            <div class="mb-4">
                <label class="block mb-2 text-sm font-bold" style="color: var(--ra-heading)">Username</label>
                <div class="relative">
                    <i class="ti ti-user login-icon"></i>
                    <input
                        type="text"
                        name="username"
                        class="login-input"
                        placeholder="Masukkan Username"
                        autocomplete="username"
                        required
                        autofocus>
                </div>
            </div>

            <div class="mb-6">
                <label class="block mb-2 text-sm font-bold" style="color: var(--ra-heading)">Password</label>
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
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-lg"
                        style="color: var(--ra-muted)">
                        <i id="eyeIcon" class="ti ti-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="login-btn flex items-center justify-center gap-2">
                <i class="ti ti-login-2 text-lg"></i> Login
            </button>

        </form>

        <p class="text-center text-xs mt-8" style="color: var(--ra-muted)">
            &copy; <?= date('Y') ?> JWI Group &middot; NOC Control
        </p>
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