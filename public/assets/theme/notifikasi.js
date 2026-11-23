/* Lonceng notifikasi alert di navbar (dipasang partials/theme.php untuk akun yang boleh membuka halaman Alert).
   Mengikuti netmon/public/assets/js/app.js: badge jumlah belum dibaca, 8 notifikasi terbaru, tandai semua dibaca,
   diperbarui tiap 15 detik. Alamat & token dari window.NOTIF_ALERT. */
(function () {
    var C = window.NOTIF_ALERT;
    if (!C) return;

    var JEDA = 15000;      // muat ulang notifikasi
    var JEDA_CEK = 30000;  // minta server poll device yang sudah waktunya

    function esc(v) {
        return String(v === null || v === undefined ? '' : v).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[c];
        });
    }

    function waktu(v) {
        var d = new Date(String(v || '').replace(' ', 'T') + '+07:00'); // waktu server = WIB
        if (isNaN(d.getTime())) return esc(v || '-');
        return d.toLocaleString('id-ID', { timeZone: 'Asia/Jakarta', day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit' });
    }

    function pasang() {
        var bar = document.querySelector('.pc-header ul.ml-auto');
        if (!bar || bar.querySelector('.ra-notif')) return;

        var li = document.createElement('li');
        li.className = 'ra-notif';
        li.innerHTML =
            '<button type="button" class="ra-notif-btn" title="Notifikasi" aria-label="Notifikasi" aria-expanded="false">' +
            '<i class="ti ti-bell"></i><span class="ra-notif-badge" hidden>0</span></button>' +
            '<div class="ra-notif-box" hidden>' +
            '<div class="ra-notif-head"><div><strong>Notifikasi</strong><small>Memuat...</small></div>' +
            '<button type="button" class="ra-notif-baca">Tandai semua dibaca</button></div>' +
            '<div class="ra-notif-list"><div class="ra-notif-kosong">Memuat notifikasi...</div></div>' +
            '<a class="ra-notif-foot" href="' + esc(C.alert) + '">Lihat Alert Center</a></div>';

        // di kiri tombol logout (tombol logout dipasang ruang.js)
        var logout = bar.querySelector('.ra-logout');
        bar.insertBefore(li, logout ? logout.closest('li') : null);

        var btn = li.querySelector('.ra-notif-btn');
        var box = li.querySelector('.ra-notif-box');
        var badge = li.querySelector('.ra-notif-badge');
        var list = li.querySelector('.ra-notif-list');
        var sub = li.querySelector('.ra-notif-head small');
        var baca = li.querySelector('.ra-notif-baca');
        var terbaru = 0;
        var nomor = 0;

        function muat() {
            var n = ++nomor;
            return fetch(C.data, { cache: 'no-store', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (r) {
                    if (!r.ok) throw new Error('HTTP ' + r.status);
                    return r.json();
                })
                .then(function (res) {
                    if (n !== nomor) return; // sudah ada permintaan yang lebih baru
                    if (!res.ok) throw new Error('data notifikasi gagal');
                    var belum = Number(res.unread_count || 0);
                    badge.textContent = belum > 99 ? '99+' : belum;
                    badge.hidden = belum < 1;
                    sub.textContent = belum + ' belum dibaca';
                    baca.disabled = belum < 1;

                    var data = res.notifications || [];
                    terbaru = data.length ? Number(data[0].id) : 0;
                    if (!data.length) {
                        list.innerHTML = '<div class="ra-notif-kosong">Belum ada notifikasi.</div>';
                        return;
                    }
                    list.innerHTML = data.map(function (d) {
                        var sev = ['critical', 'warning', 'info'].indexOf(d.severity) !== -1 ? d.severity : 'info';
                        var pulih = d.event_type === 'recovery';
                        var baru = Number(d.is_read) === 0;
                        return '<a class="ra-notif-item' + (baru ? ' is-baru' : '') + '" href="' + esc(C.alert) + '?status=all" title="' + esc(d.message) + '">' +
                            '<span class="ra-notif-ic ' + (pulih ? 'pulih' : sev) + '"><i class="ti ' + (pulih ? 'ti-circle-check' : 'ti-alert-circle') + '"></i></span>' +
                            '<span class="ra-notif-isi"><strong>' + esc(d.title) + '</strong>' +
                            '<span>' + esc(d.hostname || 'Unknown Device') + (d.ip_address ? ' · ' + esc(d.ip_address) : '') + '</span>' +
                            '<small>' + (pulih ? 'RECOVERY' : 'ALERT') + ' · ' + waktu(d.created_at) + '</small></span>' +
                            (baru ? '<span class="ra-notif-titik"></span>' : '') + '</a>';
                    }).join('');
                })
                .catch(function () {
                    if (n !== nomor) return;
                    list.innerHTML = '<div class="ra-notif-kosong is-gagal">Gagal memuat notifikasi.</div>';
                });
        }

        function buka(ya) {
            box.hidden = !ya;
            btn.setAttribute('aria-expanded', String(ya));
            if (ya) muat();
        }

        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            buka(box.hidden);
        });
        document.addEventListener('click', function (e) {
            if (!li.contains(e.target)) buka(false);
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !box.hidden) {
                buka(false);
                btn.focus();
            }
        });

        baca.addEventListener('click', function () {
            baca.disabled = true;
            var body = new URLSearchParams();
            body.set('token', C.token);
            body.set('sampai', terbaru);
            fetch(C.baca, { method: 'POST', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, body: body })
                .then(function (r) { return r.json().then(function (j) { if (!r.ok || !j.ok) throw new Error(j.pesan || 'gagal'); }); })
                .then(muat)
                .catch(function () {
                    baca.disabled = false;
                    sub.textContent = 'Gagal menandai, coba lagi.';
                });
        });

        function cek() {
            if (document.hidden) return;
            fetch(C.cek, { cache: 'no-store', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } })
                .then(function (r) { return r.ok ? r.json() : null; })
                .then(function (j) { if (j && j.dipoll) muat(); })
                .catch(function () { });
        }

        muat();
        setInterval(function () { if (!document.hidden) muat(); }, JEDA);
        setInterval(cek, JEDA_CEK);
        document.addEventListener('visibilitychange', function () { if (!document.hidden) muat(); });
    }

    // setelah ruang.js selesai merapikan navbar
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', pasang);
    else pasang();
})();
