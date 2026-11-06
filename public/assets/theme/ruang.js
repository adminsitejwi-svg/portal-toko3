/* =====================================================================
 *  Dark mode untuk tema RuangAdmin (lihat ruang.css).
 *
 *  - Dimuat di <head> TANPA defer, agar mode gelap terpasang sebelum
 *    halaman tampil (tidak ada kedipan putih).
 *  - Pilihan disimpan di localStorage ('ra-theme' = 'dark' | 'light'),
 *    jadi berlaku di semua halaman termasuk login.
 *  - Tombol 🌙/☀️ otomatis ditambahkan di topbar (sebelah ikon user);
 *    halaman tanpa topbar mendapat tombol melayang di pojok kanan bawah.
 *  - Tanggal & jam WIB otomatis ditambahkan di topbar (kiri tombol 🌙/☀️).
 * ===================================================================== */
(function () {
    var KEY = 'ra-theme';
    var root = document.documentElement;

    function saved() {
        try {
            return localStorage.getItem(KEY);
        } catch (e) {
            return null;
        }
    }

    function apply(mode) {
        root.classList.toggle('dark', mode === 'dark');
        root.classList.toggle('light', mode !== 'dark');
        var icons = document.querySelectorAll('.ra-theme-toggle i');
        for (var i = 0; i < icons.length; i++) {
            icons[i].className = 'ti ' + (mode === 'dark' ? 'ti-sun' : 'ti-moon');
        }
        var btns = document.querySelectorAll('.ra-theme-toggle');
        for (var j = 0; j < btns.length; j++) {
            btns[j].title = mode === 'dark' ? 'Mode terang' : 'Mode gelap';
        }
    }

    function current() {
        return root.classList.contains('dark') ? 'dark' : 'light';
    }

    function toggle() {
        var mode = current() === 'dark' ? 'light' : 'dark';
        try {
            localStorage.setItem(KEY, mode);
        } catch (e) { /* mode tetap berlaku di halaman ini */ }
        apply(mode);
    }

    // pasang sedini mungkin
    apply(saved() === 'dark' ? 'dark' : 'light');

    // Sembunyikan bagian yang diubah ruang.js (sidebar, topbar) sampai tampilan baru terpasang,
    // agar saat reload tidak berkedip ke tampilan lama. Pengaman: tetap dibuka setelah 3 detik.
    root.classList.add('ra-loading');
    function ready() {
        root.classList.remove('ra-loading');
    }
    setTimeout(ready, 3000);

    // ---- Layar "Memuat halaman" (lihat ruang.css: html.ra-pageload) ----
    // Tampil sejak <head> sampai halaman selesai dimuat, lalu memudar.
    // Tampil lagi saat pindah halaman (klik link / kirim form), agar ada tanda sedang memuat.
    var pageTimer = null;
    function showPageLoader() {
        clearTimeout(pageTimer);
        root.classList.remove('ra-pageload-out');
        root.classList.add('ra-pageload', 'ra-pageload-leave');
        // pengaman: kalau ternyata tidak pindah halaman (mis. unduhan), tutup sendiri
        pageTimer = setTimeout(hidePageLoader, 10000);
    }
    function hidePageLoader() {
        clearTimeout(pageTimer);
        if (!root.classList.contains('ra-pageload')) return;
        root.classList.add('ra-pageload-out');
        pageTimer = setTimeout(function () {
            root.classList.remove('ra-pageload', 'ra-pageload-out', 'ra-pageload-leave');
        }, 650);
    }
    root.classList.add('ra-pageload');
    pageTimer = setTimeout(hidePageLoader, 8000);
    window.addEventListener('load', hidePageLoader);
    // kembali lewat tombol Back (halaman dari cache browser): jangan biarkan loader menempel
    window.addEventListener('pageshow', function (e) {
        if (e.persisted) hidePageLoader();
    });

    document.addEventListener('click', function (e) {
        if (e.defaultPrevented || e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
        var a = e.target.closest && e.target.closest('a[href]');
        if (!a || a.hasAttribute('download') || a.hasAttribute('data-no-loader')) return;
        if (a.target && a.target !== '_self') return;
        var href = a.getAttribute('href');
        if (!href || href.charAt(0) === '#' || /^(javascript|mailto|tel):/i.test(href)) return;
        var url;
        try {
            url = new URL(a.href, location.href);
        } catch (err) {
            return;
        }
        if (url.origin !== location.origin) return;
        // link ke bagian di halaman yang sama
        if (url.pathname === location.pathname && url.search === location.search && url.hash) return;
        // file PDF/unduhan tidak mengganti halaman
        if (/\/pdf\/|\.(pdf|xlsx?|csv|zip|docx?)$/i.test(url.pathname)) return;
        showPageLoader();
    });

    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (form.target && form.target !== '_self') return;
        if (form.hasAttribute('data-no-loader')) return;
        // tunggu skrip lain (validasi/AJAX) memutuskan; kalau dibatalkan, loader tidak tampil
        setTimeout(function () {
            if (!e.defaultPrevented) showPageLoader();
        }, 0);
    });

    // ---- Animasi pop up (form Tambah / Edit / View) — lihat ruang.css: .ra-modal ----
    // Pop up = div.fixed.inset-0 yang tersembunyi saat halaman dibuka. Muncul: CSS (animasi jalan
    // setiap kali display berubah dari none). Tutup: saat halaman menambah class "hidden",
    // class itu ditahan sebentar agar animasi keluar sempat jalan, lalu dipasang lagi.
    var MODAL_OUT_MS = 220;
    function watchModal(el) {
        el.classList.add('ra-modal');
        el._raOpen = false;
        new MutationObserver(function () {
            if (el._raClosing) return;
            var hidden = el.classList.contains('hidden');
            if (!hidden) {
                el._raOpen = true;
                return;
            }
            if (!el._raOpen) return;
            el._raClosing = true;
            el.classList.remove('hidden');
            el.classList.add('ra-modal-out');
            setTimeout(function () {
                el.classList.add('hidden');
                el.classList.remove('ra-modal-out');
                el._raOpen = false;
                el._raClosing = false;
            }, MODAL_OUT_MS);
        }).observe(el, { attributes: true, attributeFilter: ['class'] });
    }

    document.addEventListener('DOMContentLoaded', function () {
        var list = document.querySelectorAll('div.fixed.inset-0');
        for (var i = 0; i < list.length; i++) {
            if (getComputedStyle(list[i]).display === 'none') watchModal(list[i]);
        }
    });

    window.RuangTheme = { toggle: toggle, apply: apply, showLoader: showPageLoader, hideLoader: hidePageLoader };

    // ---- Sidebar navy: tambahan tampilan sesuai referensi (struktur menu tidak diubah) ----
    // logo RumahWifi (public/Logo/RumahWifi-logo.png); alamat dihitung dari lokasi ruang.js (public/assets/theme/)
    var scriptSrc = (document.currentScript && document.currentScript.src) || '';
    var PUBLIC_URL = scriptSrc.replace(/assets\/theme\/ruang\.js.*$/, '');
    var LOGO = '<img src="' + PUBLIC_URL + 'Logo/RumahWifi-logo.png" alt="Logo JWI Group">';

    // ikon & warna per menu (berdasarkan label menu)
    var MENU_ICON = {
        'beranda': ['fa-house', '#4f8cff'],
        'data toko': ['fa-store', '#c084fc'],
        'data penggunaan': ['fa-chart-pie', '#f472b6'],
        'master data': ['fa-database', '#fbbf24'],
        'lokasi': ['fa-location-dot', '#fb923c'],
        'rfo': ['fa-file-lines', '#60a5fa'],
        'notifikasi maintenance': ['fa-bell', '#f87171'],
        'report noc': ['fa-chart-column', '#818cf8'],
        'all noc': ['fa-sitemap', '#38bdf8'],
        'jadwal noc': ['fa-calendar-days', '#a78bfa'],
        'profile': ['fa-user', '#60a5fa'],
        'inventory kantor': ['fa-box', '#f59e0b'],
        'pengguna': ['fa-users', '#818cf8'],
        'change log': ['fa-file-pen', '#93c5fd']
    };

    var SECTION_ICON = {
        'halaman utama': 'ti-home',
        'noc operation': 'ti-settings',
        'informasi': 'ti-info-circle'
    };

    function enhanceSidebar() {
        var sb = document.getElementById('sidebar');
        if (!sb || sb.classList.contains('ra-enhanced')) return;
        sb.classList.add('ra-enhanced');

        // brand: logo + "Sistem Operasional / JWI Group"
        var brand = sb.firstElementChild;
        var brandLink = brand && brand.querySelector('a');
        if (brandLink) {
            brandLink.innerHTML = '<span class="ra-logo">' + LOGO + '</span>' +
                '<span class="ra-brand"><small>Sistem Operasional</small><b>JWI Group</b></span>';
        }

        // judul kelompok menu
        var heads = sb.querySelectorAll('li[class*="uppercase"]');
        for (var h = 0; h < heads.length; h++) {
            var key = heads[h].textContent.trim().toLowerCase();
            var ic = document.createElement('i');
            ic.className = 'ti ' + (SECTION_ICON[key] || 'ti-point') + ' ra-sec-ic';
            heads[h].insertBefore(ic, heads[h].firstChild);
        }

        // ikon berwarna di setiap menu (panah › hanya pada menu dropdown, bawaan halaman)
        var links = sb.querySelectorAll('.pc-link');
        for (var i = 0; i < links.length; i++) {
            var spans = links[i].querySelectorAll('span:not(.pc-micon)');
            var label = spans.length ? spans[spans.length - 1].textContent.trim().toLowerCase() : '';
            var cfg = MENU_ICON[label];
            var micon = links[i].querySelector('.pc-micon');
            if (cfg && micon) {
                micon.innerHTML = '<i class="fa-solid ' + cfg[0] + '"></i>';
                micon.style.color = cfg[1];
            }
        }

        // halaman di dalam submenu: tandai link-nya & induknya
        var path = location.pathname.replace(/\/+$/, '').toLowerCase();
        var subs = sb.querySelectorAll('.submenu a[href]');
        for (var s = 0; s < subs.length; s++) {
            var p = subs[s].pathname.replace(/\/+$/, '').toLowerCase();
            if (p && (path === p || path.indexOf(p + '/') === 0)) {
                subs[s].classList.add('ra-current');
                var parent = subs[s].closest('.hasmenu');
                var toggleLink = parent && parent.querySelector('.pc-link');
                if (toggleLink && !sb.querySelector('.pc-link.active')) toggleLink.classList.add('active');
            }
        }
    }

    // ---- Tanggal & jam WIB di topbar: elemen [data-wib="tanggal"] / [data-wib="jam"], diperbarui tiap 30 detik ----
    function startWibClock() {
        var els = document.querySelectorAll('[data-wib]');
        if (!els.length || !window.Intl) return;
        var tgl = new Intl.DateTimeFormat('id-ID', { timeZone: 'Asia/Jakarta', weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
        var jam = new Intl.DateTimeFormat('id-ID', { timeZone: 'Asia/Jakarta', hour: '2-digit', minute: '2-digit', hour12: false });
        function update() {
            var now = new Date();
            for (var i = 0; i < els.length; i++) {
                els[i].textContent = els[i].getAttribute('data-wib') === 'jam'
                    ? jam.format(now).replace('.', ':') + ' WIB'
                    : tgl.format(now);
            }
        }
        update();
        setInterval(update, 30000);
    }

    // ---- Topbar: pop up user (nama + Logout) diganti satu ikon logout, klik = langsung logout ----
    function simplifyLogout() {
        var drop = document.querySelector('.pc-header li.dropdown');
        var btn = drop && drop.querySelector('.dropdown-menu [onclick*="logout"]');
        if (!btn) return;
        var m = btn.getAttribute('onclick').match(/location\.href\s*=\s*'([^']+)'/);
        if (!m) return;

        var a = document.createElement('a');
        a.href = m[1];
        a.className = 'head-link ra-logout flex items-center justify-center w-10 h-10 rounded';
        a.title = 'Logout';
        a.innerHTML = '<i class="ti ti-logout"></i>';

        var li = document.createElement('li');
        li.appendChild(a);
        drop.parentNode.replaceChild(li, drop);
    }

    document.addEventListener('DOMContentLoaded', function () {
        enhanceSidebar();
        simplifyLogout();

        var btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'ra-theme-toggle';
        btn.innerHTML = '<i class="ti ti-moon"></i>';
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            toggle();
        });

        // topbar: daftar ikon kanan (ul.ml-auto) di header .pc-header
        var bar = document.querySelector('.pc-header ul.ml-auto');
        if (bar) {
            var li = document.createElement('li');
            li.appendChild(btn);
            bar.insertBefore(li, bar.firstChild);

            // tanggal & jam WIB di kiri tombol dark mode (diisi oleh startWibClock)
            var clock = document.createElement('li');
            clock.className = 'ra-clock';
            clock.innerHTML = '<i class="ti ti-calendar-event"></i>' +
                '<div><b data-wib="tanggal"></b><span data-wib="jam"></span></div>';
            bar.insertBefore(clock, bar.firstChild);
        } else {
            btn.classList.add('floating');
            document.body.appendChild(btn);
        }

        apply(current());
        startWibClock();
        ready();
    });
})();
