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

    // ===== Request (Informasi > Request) =====
    // menu Request untuk semua akun + badge jumlah request yang belum diisi,
    // dan pengingat di halaman yang dipilih Administrator pada request tersebut.
    $tampilRequest = \App\Libraries\RequestForm::tersedia();
    $rqTertunda    = $tampilRequest ? \App\Libraries\RequestForm::tertunda((int) session()->get('user_id')) : [];
    $rqSegmen      = strtolower(explode('/', trim(uri_string(), '/'))[0] ?? '');
    $rqDiHalaman   = $rqSegmen === '' || $rqSegmen === 'request' ? [] : array_values(array_filter(
        $rqTertunda,
        static fn ($r) => $r['halaman'] && strtolower($r['halaman']) === $rqSegmen
    ));
?>
<?php if ($hakAkses->bisa('Alert') && \App\Models\DeviceAlertModel::siap()) : ?>
    <!-- lonceng notifikasi alert di navbar (untuk akun yang boleh membuka halaman Alert) -->
    <link rel="stylesheet" href="<?= base_url('assets/theme/notifikasi.css') ?>?v=<?= $ver('assets/theme/notifikasi.css') ?>">
    <script>
        window.NOTIF_ALERT = <?= json_encode([
            'alert' => site_url('Alert'),
            'data'  => site_url('Alert/notifikasi'),
            'baca'  => site_url('Alert/dibaca'),
            'cek'   => site_url('Alert/cek'),
            'token' => \App\Controllers\Alert::token(),
        ], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP) ?>;
    </script>
    <script src="<?= base_url('assets/theme/notifikasi.js') ?>?v=<?= $ver('assets/theme/notifikasi.js') ?>"></script>
<?php endif; ?>
<?php if ($terlarang) : ?>
    <style id="hak-akses-css">
<?php foreach ($terlarang as $k) : $k = addcslashes($k, '"\\'); ?>
        #sidebar li:has(> a[href$="/<?= $k ?>" i]),
<?php endforeach; ?>
        #sidebar li.__hak_akses_kosong { display: none !important; }
    </style>
<?php endif; ?>
    <script>
        <?php if ($tampilHakAkses) : ?>
        // menu Hak Akses (khusus Administrator) di bawah Change Log.
        // Disisipkan saat readyState "interactive" (sebelum DOMContentLoaded) supaya ikut dihias
        // ruang.js (ikon + teks kecil "Role & Akses Halaman" dari MENU_ICON['hak akses']).
        (function () {
            function sisipkan() {
                var sb = document.getElementById('sidebar');
                if (!sb || sb.querySelector('a[href$="/HakAkses" i]')) return;
                var logs = sb.querySelector('a[href$="/Logs" i]');
                if (!logs) return;
                var li = document.createElement('li');
                li.innerHTML = '<a href="<?= site_url('HakAkses') ?>" class="pc-link' +
                    ' flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">' +
                    '<span class="pc-micon w-5"><i class="ti ti-shield-lock"></i></span><span>Hak Akses</span></a>';
                logs.closest('li').after(li);
                if (/\/HakAkses(\/|$)/i.test(location.pathname)) {
                    sb.querySelectorAll('.pc-link.active').forEach(function (a) { a.classList.remove('active'); });
                    li.firstChild.classList.add('active');
                }
            }
            if (document.readyState === 'loading') {
                document.addEventListener('readystatechange', function () {
                    if (document.readyState === 'interactive') sisipkan();
                });
            } else {
                sisipkan();
            }
        })();
        <?php endif; ?>

        <?php if ($tampilRequest) : ?>
        // menu Request (semua akun) di bawah Hak Akses / Change Log, dengan badge request yang belum diisi
        (function () {
            function sisipkanRequest() {
                var sb = document.getElementById('sidebar');
                if (!sb || sb.querySelector('a[href$="/Request" i]')) return;
                var acuan = sb.querySelector('a[href$="/HakAkses" i]') || sb.querySelector('a[href$="/Logs" i]');
                if (!acuan) return;
                var jml = <?= count($rqTertunda) ?>;
                var li = document.createElement('li');
                li.innerHTML = '<a href="<?= site_url('Request') ?>" class="pc-link' +
                    ' flex items-center gap-3 px-6 py-2.5 text-[14px] hover:text-white">' +
                    '<span class="pc-micon w-5"><i class="ti ti-forms"></i></span><span>Request</span>' +
                    (jml ? '<b class="rq-badge" title="Request yang belum diisi" style="margin-left:auto;min-width:20px;height:20px;padding:0 6px;border-radius:999px;background:#ef4444;color:#fff;font-size:11px;font-weight:700;display:inline-flex;align-items:center;justify-content:center">' + jml + '</b>' : '') +
                    '</a>';
                acuan.closest('li').after(li);
                if (/\/Request(\/|$)/i.test(location.pathname)) {
                    sb.querySelectorAll('.pc-link.active').forEach(function (a) { a.classList.remove('active'); });
                    li.firstChild.classList.add('active');
                }
            }
            if (document.readyState === 'loading') {
                document.addEventListener('readystatechange', function () {
                    if (document.readyState === 'interactive') sisipkanRequest();
                });
            } else {
                sisipkanRequest();
            }
        })();
        <?php endif; ?>

        <?php if ($rqDiHalaman) : ?>
        // pengingat request untuk halaman ini (dipilih Administrator saat membuat request)
        document.addEventListener('DOMContentLoaded', function () {
            var daftar = <?= json_encode(array_map(static fn ($r) => [
                'url'   => site_url('Request/isi/' . $r['id']),
                'judul' => $r['judul'],
                'batas' => $r['batas_waktu'] ? date('d M Y H:i', strtotime($r['batas_waktu'])) : '',
                'revisi' => $r['status_jawaban'] === 'revisi',
            ], $rqDiHalaman), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?>;
            var kunci = 'rq-tutup-' + location.pathname;
            try { if (sessionStorage.getItem(kunci)) return; } catch (e) {}
            var gelap = document.documentElement.classList.contains('dark');
            var box = document.createElement('div');
            box.setAttribute('role', 'status');
            box.style.cssText = 'position:fixed;right:20px;bottom:20px;z-index:3000;width:340px;max-width:calc(100vw - 32px);' +
                'border-radius:14px;box-shadow:0 18px 45px rgba(15,23,42,.25);overflow:hidden;font-size:13px;' +
                (gelap ? 'background:#263240;color:#e2e8f0;border:1px solid rgba(255,255,255,.1)' : 'background:#fff;color:#334155;border:1px solid #e2e8f0');
            var esc = function (s) { var d = document.createElement('div'); d.textContent = s; return d.innerHTML; };
            var isi = '<div style="display:flex;align-items:center;gap:8px;padding:12px 14px;background:#04a9f5;color:#fff;font-weight:700">' +
                '<i class="ti ti-forms" style="font-size:18px"></i><span style="flex:1">Ada ' + daftar.length + ' request untuk halaman ini</span>' +
                '<button type="button" aria-label="Tutup" style="background:none;border:0;color:#fff;font-size:18px;cursor:pointer;line-height:1">&times;</button></div>' +
                '<div style="padding:8px 14px 12px">';
            daftar.forEach(function (r) {
                isi += '<a href="' + esc(r.url) + '" style="display:block;padding:8px 0;border-bottom:1px dashed ' + (gelap ? 'rgba(255,255,255,.1)' : '#e2e8f0') + ';color:inherit;text-decoration:none">' +
                    '<b style="display:block">' + esc(r.judul) + '</b>' +
                    '<span style="font-size:12px;opacity:.75">' + (r.revisi ? '<span style="color:#f59e0b;font-weight:600">Perlu revisi</span> · ' : '') +
                    (r.batas ? 'Batas ' + esc(r.batas) : 'Tanpa batas waktu') + '</span>' +
                    '<span style="display:inline-block;margin-top:4px;color:#04a9f5;font-weight:600">Isi sekarang &rarr;</span></a>';
            });
            box.innerHTML = isi + '</div>';
            box.querySelector('button').addEventListener('click', function () {
                try { sessionStorage.setItem(kunci, '1'); } catch (e) {}
                box.remove();
            });
            document.body.appendChild(box);
        });
        <?php endif; ?>

        document.addEventListener('DOMContentLoaded', function () {
            var sb = document.getElementById('sidebar');
            if (!sb) return;
            var tersembunyi = function (el) { return getComputedStyle(el).display === 'none'; };

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
