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


</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center">

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

    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg">

        <h2 class="text-3xl font-bold text-center mb-6">
            Login
        </h2>

        <?php if (session()->getFlashdata('error')) : ?>

            <div class="bg-red-100 border border-red-300 text-red-700 p-3 rounded-lg mb-4">

                <?= session()->getFlashdata('error') ?>

            </div>

        <?php endif; ?>

        <form action="<?= site_url('login/auth') ?>" method="post">

            <?= csrf_field() ?>

            <div class="mb-4">

                <label class="block mb-2 font-medium">
                    Username
                </label>

                <input
                    type="text"
                    name="username"
                    class="w-full border rounded-lg px-4 py-3"
                    placeholder="Masukkan Username"
                    required>

            </div>

            <div class="mb-4">

                <label class="block mb-2 font-medium">
                    Password
                </label>

                <div class="relative">

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="w-full border rounded-lg px-4 py-3 pr-12"
                        placeholder="Masukkan Password"
                        required>

                    <button
                        type="button"
                        onclick="togglePassword()"
                        class="absolute right-3 top-1/2 -translate-y-1/2">

                        <i id="eyeIcon" class="ti ti-eye"></i>

                    </button>

                </div>

            </div>

            <!-- Lupa Password -->


            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-semibold mt-4">

                Login

            </button>

        </form>


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