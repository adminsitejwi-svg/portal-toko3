/* =====================================================================
 *  Dark mode untuk tema RuangAdmin (lihat ruang.css).
 *
 *  - Dimuat di <head> TANPA defer, agar mode gelap terpasang sebelum
 *    halaman tampil (tidak ada kedipan putih).
 *  - Pilihan disimpan di localStorage ('ra-theme' = 'dark' | 'light'),
 *    jadi berlaku di semua halaman termasuk login.
 *  - Halaman biasa: sidebar mengikuti mode (class "ra-sb-dark"), isi halaman
 *    KEBALIKANNYA (class "dark"): mode terang = sidebar putih + isi gelap,
 *    mode gelap = sidebar gelap + isi putih. Halaman login
 *    (<html data-ra-tema="penuh">) ikut mode seluruhnya.
 *  - Tombol 🌙/☀️ otomatis ditambahkan di topbar (sebelah ikon user);
 *    halaman tanpa topbar mendapat tombol melayang di pojok kanan bawah.
 *  - Tanggal & jam WIB otomatis ditambahkan di topbar (kiri tombol 🌙/☀️).
 * ===================================================================== */
(function () {
    var KEY = 'ra-theme';
    var root = document.documentElement;
    // halaman yang seluruhnya ikut mode (login); halaman lain: isi kebalikan sidebar
    var TEMA_PENUH = root.getAttribute('data-ra-tema') === 'penuh';

    function saved() {
        try {
            return localStorage.getItem(KEY);
        } catch (e) {
            return null;
        }
    }

    function apply(mode) {
        var gelap = mode === 'dark';
        // sidebar mengikuti mode; isi halaman kebalikannya (kecuali login: ikut mode)
        var isiGelap = TEMA_PENUH ? gelap : !gelap;
        root.classList.toggle('ra-sb-dark', gelap);
        root.classList.toggle('dark', isiGelap);
        root.classList.toggle('light', !isiGelap);
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
        return root.classList.contains('ra-sb-dark') ? 'dark' : 'light';
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
    // Layar blur tampil minimal 1 detik (PAGE_BLUR_MS), lalu memudar setelah halaman selesai dimuat.
    var PAGE_BLUR_MS = 1000;
    var pageTimer = null;
    var pageShownAt = Date.now();
    function requestHidePageLoader() {
        var left = PAGE_BLUR_MS - (Date.now() - pageShownAt);
        if (left <= 0) return hidePageLoader();
        clearTimeout(pageTimer);
        pageTimer = setTimeout(hidePageLoader, left);
    }
    function showPageLoader() {
        clearTimeout(pageTimer);
        pageShownAt = Date.now();
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
    window.addEventListener('load', requestHidePageLoader);
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

    // ---- Notifikasi sukses / gagal (banner di tengah atas) tampil DI DEPAN layar "Memuat halaman" ----
    // Banyak halaman menaruh banner di dalam <nav id="sidebar">, yang membatasi tumpukannya di bawah
    // layar memuat. Banner dipindah ke <body> lalu diberi kelas .ra-flash (z-index di ruang.css).
    function angkatNotifikasi(el) {
        if (!el || el.nodeType !== 1 || !el.matches('div.fixed.top-5')) return;
        if (el.parentNode !== document.body) document.body.appendChild(el);
        el.classList.add('ra-flash');
    }

    document.addEventListener('DOMContentLoaded', function () {
        var ada = document.querySelectorAll('div.fixed.top-5');
        for (var i = 0; i < ada.length; i++) angkatNotifikasi(ada[i]);

        // banner yang dibuat belakangan oleh skrip halaman (mis. showFlash setelah simpan lewat AJAX)
        new MutationObserver(function (muts) {
            for (var m = 0; m < muts.length; m++) {
                for (var n = 0; n < muts[m].addedNodes.length; n++) angkatNotifikasi(muts[m].addedNodes[n]);
            }
        }).observe(document.body, { childList: true });
    });

    window.RuangTheme = { toggle: toggle, apply: apply, showLoader: showPageLoader, hideLoader: hidePageLoader };

    // ---- Sidebar navy: tambahan tampilan sesuai referensi (struktur menu tidak diubah) ----
    // logo NOC (public/Logo/Logo-NOC-web.png: Logo-NOC.png tanpa margin kosong); alamat dihitung dari lokasi ruang.js (public/assets/theme/)
    var scriptSrc = (document.currentScript && document.currentScript.src) || '';
    var PUBLIC_URL = scriptSrc.replace(/assets\/theme\/ruang\.js.*$/, '');
    var LOGO = '<img src="' + PUBLIC_URL + 'Logo/Logo-NOC-web.png" alt="Logo JWI Group">';

    // ikon & teks kecil per menu sidebar (berdasarkan label menu)
    var MENU_ICON = {
        'beranda': ['ti-home', 'Ringkasan Data'],
        'data toko': ['ti-building-store', 'Toko Retail'],
        'data penggunaan': ['ti-chart-pie', 'Simcard & Nomor Inet'],
        'master data': ['ti-database', 'Perangkat & Vendor'],
        'lokasi': ['ti-map-pin', 'Peta Lokasi'],
        'spk': ['ti-file-certificate', 'Surat Perintah Kerja'],
        'bast': ['ti-file-check', 'Berita Acara Serah Terima'],
        'pks & contract': ['ti-file-text', 'Perjanjian & Kontrak'],
        'rfo': ['ti-file-alert', 'Reason For Outage'],
        'notifikasi maintenance': ['ti-bell', 'Pemberitahuan Maintenance'],
        'report noc': ['ti-report-analytics', 'Daily & Maintenance'],
        'all noc': ['ti-topology-star-3', 'Data Center & BTS'],
        'jadwal noc': ['ti-calendar-week', 'Shift & Piket'],
        'profile': ['ti-user-circle', 'Akun Saya'],
        'pengguna': ['ti-users', 'Kelola Pengguna'],
        'change log': ['ti-history', 'Riwayat Perubahan']
    };

    // ikon judul halaman, berdasarkan alamat menu yang aktif (huruf kecil)
    var IKON_HALAMAN = {
        'dashboard-manager': 'ti-home',
        'alfamidi': 'ti-building-store',
        'lawson': 'ti-building-store',
        'alfamart': 'ti-building-store',
        'datasi': 'ti-device-sim',
        'nmrinet': 'ti-world',
        'perangkat': 'ti-router',
        'jns_perangkat': 'ti-category',
        'typeperangkat': 'ti-cpu',
        'vendor': 'ti-briefcase',
        'vendorcelulllar': 'ti-antenna',
        'layananvendor': 'ti-list-check',
        'dcadmin': 'ti-server-2',
        'mediakoneksi': 'ti-plug-connected',
        'pemilikproject': 'ti-user-star',
        'pelanggan': 'ti-users-group',
        'nomorinet': 'ti-hash',
        'quotasimcard': 'ti-gauge',
        'vpn': 'ti-shield-lock',
        'map': 'ti-map-pin',
        'rfo': 'ti-file-alert',
        'mdmaintenance': 'ti-bell',
        'netmon': 'ti-activity-heartbeat',
        'historyreport': 'ti-clipboard-text',
        'maintenancereport': 'ti-tool',
        'reportsat': 'ti-report',
        'bastcorporate': 'ti-building-skyscraper',
        'bastalfamidi': 'ti-building-store',
        'bastlawson': 'ti-building-store',
        'bastalfamart': 'ti-building-store',
        'bastdandan': 'ti-building-store',
        'slareport': 'ti-target',
        'monthlyreport': 'ti-calendar-stats',
        'surveyreport': 'ti-zoom-check',
        'installationreport': 'ti-plug-connected',
        'troubleshootreport': 'ti-bug',
        'datacenter': 'ti-building-warehouse',
        'datarakdc': 'ti-server-2',
        'interkoneksi': 'ti-arrows-exchange',
        'vlan': 'ti-network',
        'datarouter': 'ti-router',
        'dataswitch': 'ti-switch-horizontal',
        'dataolt': 'ti-affiliate',
        'ippublic': 'ti-world',
        'ipprivate': 'ti-lock',
        'ftpclient': 'ti-folder-share',
        'devicesbackbone': 'ti-router',
        'bts': 'ti-antenna-bars-5',
        'pelanggannoc': 'ti-users',
        'vendornoc': 'ti-briefcase',
        'calendar': 'ti-calendar-event',
        'piket': 'ti-calendar-user',
        'profile': 'ti-user-circle',
        'inventorykantor': 'ti-package',
        'settings': 'ti-users',
        'logs': 'ti-history'
    };

    // menu sidebar yang masih "Segera Hadir" (samakan dengan 'soon' => true / $reportNoc di app/Config/AllNoc.php)
    var MENU_SEGERA = ['datarouter', 'dataswitch', 'dataolt', 'ippublic', 'ipprivate', 'ftpclient',
        'slareport', 'monthlyreport', 'surveyreport', 'installationreport', 'troubleshootreport',
        'spk', 'pkscontract', 'bastcorporate', 'bastalfamidi', 'bastlawson', 'bastalfamart', 'bastdandan'];

    // ---- Ikon judul halaman: kotak yang sudah ada diganti ikonnya, yang belum punya dipasangkan ----
    function pasangIkonJudul(link) {
        var ikon = link && IKON_HALAMAN[normalPath(link.pathname).split('/').pop()];
        if (!ikon) return;

        var ada = document.querySelector('.rfo-title-ic i');
        if (ada) {
            ada.className = 'ti ' + ikon;
            return;
        }

        var judul = null;
        var calon = document.querySelectorAll('.pc-container h1, .pc-container h2, .pc-container h5');
        for (var i = 0; i < calon.length; i++) {
            if (!calon[i].closest('header, .fixed, [id$="Modal"]')) {
                judul = calon[i];
                break;
            }
        }
        if (!judul) return;

        var bungkus = document.createElement('div');
        bungkus.className = 'ra-title';
        bungkus.innerHTML = '<span class="ra-title-ic"><i class="ti ' + ikon + '"></i></span>';
        judul.parentNode.insertBefore(bungkus, judul);
        bungkus.appendChild(judul);
    }

    var SECTION_ICON = {
        'halaman utama': 'ti-home',
        'data toko jwi group': 'ti-building-store',
        'activation': 'ti-rocket',
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

        // ikon & teks kecil di setiap menu (panah › hanya pada menu dropdown, bawaan halaman)
        var links = sb.querySelectorAll('.pc-link');
        for (var i = 0; i < links.length; i++) {
            var spans = links[i].querySelectorAll('span:not(.pc-micon)');
            var judulMenu = spans.length ? spans[spans.length - 1] : null;
            var label = judulMenu ? judulMenu.textContent.trim().toLowerCase() : '';
            var cfg = MENU_ICON[label];
            var micon = links[i].querySelector('.pc-micon');
            if (cfg && micon) {
                micon.innerHTML = '<i class="ti ' + cfg[0] + '"></i>';
                var kecil = document.createElement('small');
                kecil.className = 'ra-sub';
                kecil.textContent = cfg[1];
                judulMenu.appendChild(kecil);
            }
            if (links[i].closest('.hasmenu')) links[i].closest('.hasmenu').setAttribute('data-ra-menu', label);

            // menu utama (bukan dropdown) yang halamannya belum dibuat juga diberi label "Soon"
            var kunciMenu = links[i].getAttribute('href') === '#' ? '' : normalPath(links[i].pathname || '').split('/').pop();
            if (kunciMenu && MENU_SEGERA.indexOf(kunciMenu) !== -1 && !links[i].querySelector('.ra-soon')) {
                var soonMenu = document.createElement('span');
                soonMenu.className = 'ra-soon';
                soonMenu.textContent = 'Soon';
                links[i].appendChild(soonMenu);
            }
        }

        // ikon di setiap item dropdown (diambil dari IKON_HALAMAN berdasarkan alamat link)
        var subLinks = sb.querySelectorAll('.submenu a[href]');
        for (var s = 0; s < subLinks.length; s++) {
            var a = subLinks[s];
            if (a.querySelector('.ra-sm-ic')) continue;
            var kunci = normalPath(a.pathname || '').split('/').pop();
            var ikonSub = document.createElement('span');
            ikonSub.className = 'ra-sm-ic';
            ikonSub.setAttribute('aria-hidden', 'true');
            ikonSub.innerHTML = '<i class="ti ' + (IKON_HALAMAN[kunci] || 'ti-point') + '"></i>';
            a.insertBefore(ikonSub, a.firstChild);
            a.classList.add('ra-has-ic');
            // menu yang halamannya belum dibuat diberi label kecil "Soon" (daftar di MENU_SEGERA)
            if (MENU_SEGERA.indexOf(kunci) !== -1 && !a.querySelector('.ra-soon')) {
                var soon = document.createElement('span');
                soon.className = 'ra-soon';
                soon.textContent = 'Soon';
                a.appendChild(soon);
            }
        }

        pasangIkonJudul(setupDropdown(sb));
    }

    // ---- Dropdown sidebar: tetap terbuka antar halaman & menandai halaman aktif ----
    var KEY_DROPDOWN = 'ra.sidebar.open';
    var KEY_SCROLL = 'ra.sidebar.scroll';

    function simpanData(store, key, nilai) {
        try { store.setItem(key, nilai); } catch (e) { }
    }

    function bacaData(store, key) {
        try { return store.getItem(key); } catch (e) { return null; }
    }

    function normalPath(p) {
        return p.replace(/\/index\.php(?=\/|$)/i, '').replace(/\/+$/, '').toLowerCase();
    }

    function setupDropdown(sb) {
        var menus = sb.querySelectorAll('.hasmenu');
        var scroller = sb.querySelector('.overflow-y-auto');

        function setBuka(li, buka) {
            var sub = li.querySelector('.submenu');
            var arrow = li.querySelector('.arrow');
            if (sub) sub.classList.toggle('open', buka);
            if (arrow) arrow.style.transform = ''; // arah panah diatur CSS (.ra-open)
            li.classList.toggle('ra-open', buka);
        }

        function simpanBuka() {
            var buka = [];
            for (var i = 0; i < menus.length; i++) {
                if (menus[i].classList.contains('ra-open')) buka.push(menus[i].getAttribute('data-ra-menu'));
            }
            simpanData(localStorage, KEY_DROPDOWN, JSON.stringify(buka));
        }

        // buka/tutup dengan animasi: tinggi dihitung dari isi submenu agar geraknya merata
        function animasiBuka(li, buka) {
            var sub = li.querySelector('.submenu');
            var hemat = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (!sub || hemat) {
                setBuka(li, buka);
                return;
            }
            clearTimeout(sub.raTimer);

            // mulai dari tinggi saat ini, jadi aman bila diklik lagi saat animasi berjalan
            sub.style.transition = 'none';
            sub.style.overflow = 'hidden';
            sub.style.maxHeight = sub.getBoundingClientRect().height + 'px';
            void sub.offsetHeight;
            sub.style.transition = '';

            li.classList.toggle('ra-anim-in', buka);
            setBuka(li, buka);
            var tinggi = buka ? sub.scrollHeight : 0;
            sub.style.maxHeight = tinggi + 'px';

            // menu yang dibuka di bagian bawah digulir halus agar isinya terlihat
            if (buka && scroller) {
                var bawah = li.getBoundingClientRect().top + li.querySelector('.pc-link').offsetHeight + tinggi + 16;
                var batas = scroller.getBoundingClientRect().bottom;
                if (bawah > batas) scroller.scrollBy({ top: bawah - batas, behavior: 'smooth' });
            }

            sub.raTimer = setTimeout(function () {
                sub.style.maxHeight = '';
                sub.style.overflow = '';
                li.classList.remove('ra-anim-in');
            }, 700);
        }

        // toggleSub bawaan tiap halaman diganti: dropdown lain tidak ikut tertutup & statusnya disimpan
        window.toggleSub = function (el) {
            var li = el.closest('.hasmenu');
            if (!li) return;
            animasiBuka(li, !li.classList.contains('ra-open'));
            simpanBuka();
        };

        // cari link yang paling cocok dengan URL (mis. Interkoneksi/edit/5 -> Interkoneksi)
        var path = normalPath(location.pathname);
        var cocok = null;
        var panjang = -1;
        var links = sb.querySelectorAll('a[href]');
        for (var a = 0; a < links.length; a++) {
            if (links[a].getAttribute('href').charAt(0) === '#') continue;
            var p = normalPath(links[a].pathname);
            if (p && (path === p || path.indexOf(p + '/') === 0) && p.length > panjang) {
                cocok = links[a];
                panjang = p.length;
            }
        }

        // pasang tanpa animasi agar menu tidak terlihat membuka ulang di tiap halaman
        sb.classList.add('ra-no-anim');

        var tersimpan = [];
        try { tersimpan = JSON.parse(bacaData(localStorage, KEY_DROPDOWN)) || []; } catch (e) { }
        for (var t = 0; t < menus.length; t++) {
            if (tersimpan.indexOf(menus[t].getAttribute('data-ra-menu')) !== -1) setBuka(menus[t], true);
        }

        var aktif = null;
        if (cocok) {
            // tanda aktif yang tertulis manual di halaman diganti hasil dari URL
            var lama = sb.querySelectorAll('.pc-link.active');
            for (var l = 0; l < lama.length; l++) lama[l].classList.remove('active');

            var induk = cocok.closest('.hasmenu');
            if (induk && cocok.closest('.submenu')) {
                cocok.classList.add('ra-current');
                induk.querySelector('.pc-link').classList.add('active');
                setBuka(induk, true);
            } else {
                cocok.classList.add('active');
            }
            aktif = cocok;
        } else {
            // URL tidak cocok dengan menu mana pun: pakai tanda aktif bawaan halaman
            aktif = sb.querySelector('.pc-link.active');
            var indukLama = aktif && aktif.closest('.hasmenu');
            if (indukLama) setBuka(indukLama, true);
        }
        simpanBuka();

        // posisi scroll menu dipertahankan, dan menu aktif dipastikan terlihat
        if (scroller) {
            var posisi = parseInt(bacaData(sessionStorage, KEY_SCROLL), 10);
            if (!isNaN(posisi)) scroller.scrollTop = posisi;
            if (aktif) {
                var ra = aktif.getBoundingClientRect();
                var rs = scroller.getBoundingClientRect();
                if (ra.top < rs.top) scroller.scrollTop -= rs.top - ra.top + 8;
                else if (ra.bottom > rs.bottom) scroller.scrollTop += ra.bottom - rs.bottom + 8;
            }
            window.addEventListener('pagehide', function () {
                simpanData(sessionStorage, KEY_SCROLL, String(scroller.scrollTop));
            });
        }

        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                sb.classList.remove('ra-no-anim');
            });
        });

        return cocok;
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
