/* =====================================================================
 *  Dark mode untuk tema RuangAdmin (lihat ruang.css).
 *
 *  - Dimuat di <head> TANPA defer, agar mode gelap terpasang sebelum
 *    halaman tampil (tidak ada kedipan putih).
 *  - Pilihan disimpan di localStorage ('ra-theme' = 'dark' | 'light'),
 *    jadi berlaku di semua halaman termasuk login.
 *  - Tombol 🌙/☀️ otomatis ditambahkan di topbar (sebelah ikon user);
 *    halaman tanpa topbar mendapat tombol melayang di pojok kanan bawah.
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

    window.RuangTheme = { toggle: toggle, apply: apply };

    document.addEventListener('DOMContentLoaded', function () {
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
        } else {
            btn.classList.add('floating');
            document.body.appendChild(btn);
        }

        apply(current());
    });
})();
