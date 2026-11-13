<?php
/**
 * Tema tampilan RuangAdmin + dark mode untuk semua halaman.
 * Dipanggil tepat sebelum </head> dengan: <?= view('partials/theme') ?>
 *
 * Versi file (filemtime) ditambahkan agar browser memuat ulang saat tema diubah.
 */
$ver = static fn (string $file) => @filemtime(FCPATH . $file) ?: '1';
?>
    <!-- ===== Tema RuangAdmin + dark mode (public/assets/theme) ===== -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('assets/theme/ruang.css') ?>?v=<?= $ver('assets/theme/ruang.css') ?>">
    <script src="<?= base_url('assets/theme/ruang.js') ?>?v=<?= $ver('assets/theme/ruang.js') ?>"></script>
<?php
/*
 * ===== Hak Akses (Informasi > Hak Akses) =====
 * Sidebar ada di tiap view, jadi menu yang tidak boleh dibuka disembunyikan dari sini:
 *  - CSS langsung menyembunyikan link (tanpa kedip)
 *  - JS merapikan grup/judul yang jadi kosong & menambah menu "Hak Akses" untuk Administrator
 * Penjagaan sebenarnya tetap di App\Filters\AuthFilter (URL yang diketik manual tetap ditolak).
 */
if (session()->get('logged_in')) :
    $hakAkses  = \App\Libraries\HakAkses::instance();
    $terlarang = $hakAkses->halamanTerlarang();
    $tampilHakAkses = $hakAkses->tersedia() && $hakAkses->isAdmin();
    $aksesDitolak   = session()->getFlashdata('akses_ditolak');
?>
<?php if ($terlarang) : ?>
    <style id="hak-akses-css">
<?php foreach ($terlarang as $k) : $k = addcslashes($k, '"\\'); ?>
        #sidebar li:has(> a[href$="/<?= $k ?>" i]),
<?php endforeach; ?>
        #sidebar li.__hak_akses_kosong { display: none !important; }
    </style>
<?php endif; ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var sb = document.getElementById('sidebar');
            if (!sb) return;
            var tersembunyi = function (el) { return getComputedStyle(el).display === 'none'; };

            <?php if ($tampilHakAkses) : ?>
            // menu Hak Akses (khusus Administrator) di bawah Change Log
            var logs = sb.querySelector('a[href$="/Logs" i]');
            if (logs && !sb.querySelector('a[href$="/HakAkses" i]')) {
                var li = document.createElement('li');
                var aktif = /\/HakAkses(\/|$)/i.test(location.pathname);
                li.innerHTML = '<a href="<?= site_url('HakAkses') ?>" class="pc-link' + (aktif ? ' active' : '') +
                    ' flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">' +
                    '<span class="pc-micon w-5"><i class="ti ti-shield-lock"></i></span><span>Hak Akses</span></a>';
                logs.closest('li').after(li);
                if (aktif) sb.querySelectorAll('.pc-link.active').forEach(function (a) { if (a !== li.firstChild) a.classList.remove('active'); });
            }
            <?php endif; ?>

            // grup dropdown yang semua isinya tersembunyi
            sb.querySelectorAll('li.hasmenu').forEach(function (g) {
                var isi = g.querySelectorAll('.submenu > li');
                if (isi.length && Array.prototype.every.call(isi, tersembunyi)) g.classList.add('__hak_akses_kosong');
            });

            // judul bagian (Data Toko, Activation, NOC Operation, ...) yang tidak punya menu tersisa
            sb.querySelectorAll('ul').forEach(function (ul) {
                var judul = null, adaIsi = false;
                var tutup = function () { if (judul && !adaIsi) judul.classList.add('__hak_akses_kosong'); };
                Array.prototype.forEach.call(ul.children, function (li) {
                    if (li.tagName !== 'LI') return;
                    if (li.classList.contains('uppercase') && !li.querySelector('a')) {
                        tutup(); judul = li; adaIsi = false;
                    } else if (li.querySelector('a') && !tersembunyi(li)) {
                        adaIsi = true;
                    }
                });
                tutup();
            });

            <?php if ($aksesDitolak) : ?>
            var pesan = <?= json_encode($aksesDitolak) ?>;
            if (window.Swal) {
                Swal.fire({ icon: 'warning', title: 'Akses Ditolak', text: pesan, confirmButtonColor: '#04a9f5' });
            } else {
                var t = document.createElement('div');
                t.textContent = pesan;
                t.style.cssText = 'position:fixed;top:20px;left:50%;transform:translateX(-50%);z-index:99999;background:#dc2626;color:#fff;padding:12px 18px;border-radius:10px;box-shadow:0 10px 30px rgba(0,0,0,.2);font-size:14px';
                document.body.appendChild(t);
                setTimeout(function () { t.remove(); }, 4000);
            }
            <?php endif; ?>
        });
    </script>
<?php endif; ?>
