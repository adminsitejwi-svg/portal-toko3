<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="<?= base_url('store.png') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white p-8 rounded-xl shadow-lg">

        <h2 class="text-2xl font-bold text-center mb-6">
            Reset Password
        </h2>



        <form id="resetForm" action="<?= site_url('forgot-password/update') ?>" method="post" novalidate>

            <?= csrf_field() ?>

            <!-- error dari server (CodeIgniter) jika ada -->
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-lg text-sm">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <div class="mb-4">
                <label class="block mb-2 font-medium">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="<?= old('username') ?>"
                    class="w-full border rounded-lg px-4 py-3">
                <p class="error-msg text-red-500 text-sm mt-1 hidden" data-for="username"></p>
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-medium">Password Baru</label>
                <div class="relative">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="w-full border rounded-lg px-4 py-3 pr-12">
                    <button
                        type="button"
                        onclick="togglePassword('password','icon1')"
                        class="absolute right-3 top-1/2 -translate-y-1/2">
                        <i id="icon1" class="ti ti-eye"></i>
                    </button>
                </div>
                <p class="error-msg text-red-500 text-sm mt-1 hidden" data-for="password"></p>
            </div>

            <div class="mb-6">
                <label class="block mb-2 font-medium">Confirm Password Baru</label>
                <div class="relative">
                    <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        class="w-full border rounded-lg px-4 py-3 pr-12">
                </div>
                <p class="error-msg text-red-500 text-sm mt-1 hidden" data-for="confirm_password"></p>
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg">
                Simpan Password Baru
            </button>

        </form>
        <div class="text-center mt-5">

            <a href="<?= site_url('login') ?>"
                class="text-blue-600 font-medium">

                Kembali ke Login

            </a>

        </div>

    </div>
    <script>
        function showError(field, message) {
            const input = document.getElementById(field);
            const msg = document.querySelector('.error-msg[data-for="' + field + '"]');
            if (msg) {
                msg.textContent = message;
                msg.classList.remove('hidden');
            }
            if (input) input.classList.add('border-red-500');
        }

        function clearError(field) {
            const input = document.getElementById(field);
            const msg = document.querySelector('.error-msg[data-for="' + field + '"]');
            if (msg) {
                msg.textContent = '';
                msg.classList.add('hidden');
            }
            if (input) input.classList.remove('border-red-500');
        }

        document.getElementById('resetForm').addEventListener('submit', function(e) {
            let valid = true;

            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            const confirm = document.getElementById('confirm_password').value;

            ['username', 'password', 'confirm_password'].forEach(clearError);

            if (username === '') {
                showError('username', 'Username wajib diisi');
                valid = false;
            }
            if (password === '') {
                showError('password', 'Password baru wajib diisi');
                valid = false;
            }
            if (confirm === '') {
                showError('confirm_password', 'Konfirmasi password wajib diisi');
                valid = false;
            }

            // cek password cocok (hanya jika keduanya terisi)
            if (password !== '' && confirm !== '' && password !== confirm) {
                showError('confirm_password', 'Konfirmasi password tidak cocok');
                valid = false;
            }

            if (!valid) e.preventDefault();
        });

        // hapus pesan error saat user mulai mengetik
        ['username', 'password', 'confirm_password'].forEach(function(id) {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', () => clearError(id));
        });
    </script>
    <script>
        function togglePassword(inputId, iconId) {
            let input = document.getElementById(inputId);
            let icon = document.getElementById(iconId);

            if (input.type === 'password') {
                input.type = 'text';

                icon.classList.remove('ti-eye');
                icon.classList.add('ti-eye-off');
            } else {
                input.type = 'password';

                icon.classList.remove('ti-eye-off');
                icon.classList.add('ti-eye');
            }
        }
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