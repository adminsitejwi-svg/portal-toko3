/* Notifikasi halaman ACS (Settings & Devices), tampilannya sama dengan notifikasi Berhasil / Gagal portal.
   Pakai: AcsNotif('berhasil' | 'gagal', 'Judul', 'Isi', { url: '...', teks: 'Lihat' }) */
(function () {
    var ANTRE = [], TAMPIL = null;

    function berikut() {
        if (TAMPIL || !ANTRE.length) return;
        var n = ANTRE.shift(), ok = n.jenis !== 'gagal', lama = ok ? 5000 : 7000;
        var box = document.createElement('div');
        box.className = 'fixed top-5 left-1/2 -translate-x-1/2 w-full max-w-md px-4';
        box.style.zIndex = 3000;
        box.setAttribute('role', ok ? 'status' : 'alert');
        box.innerHTML = '<div class="' + (ok ? 'bg-green-500' : 'bg-red-500') + ' text-white rounded-xl shadow-xl overflow-hidden">' +
            '<div class="flex items-center gap-3 px-5 py-4"><i class="ti ' + (ok ? 'ti-circle-check' : 'ti-alert-circle') + ' text-3xl"></i>' +
            '<div style="min-width:0;flex:1"><h4 class="font-bold"></h4><p class="text-sm" style="overflow-wrap:anywhere"></p></div>' +
            '<button type="button" aria-label="Tutup" style="border:0;background:none;color:#fff;font-size:20px;cursor:pointer"><i class="ti ti-x"></i></button></div>' +
            '<div class="h-1 ' + (ok ? 'bg-green-400' : 'bg-red-400') + '"><div class="h-full bg-white w-full"></div></div></div>';
        box.querySelector('h4').textContent = n.judul;
        var p = box.querySelector('p');
        p.textContent = n.isi;
        if (n.tautan) {
            var a = document.createElement('a');
            a.href = n.tautan.url;
            a.textContent = n.tautan.teks;
            a.style.cssText = 'color:#fff;font-weight:700;text-decoration:underline;margin-left:6px';
            p.appendChild(a);
        }
        document.body.appendChild(box);
        TAMPIL = box;

        var bar = box.querySelector('.h-full');
        bar.style.transition = 'width ' + lama + 'ms linear';
        setTimeout(function () { bar.style.width = '0%'; }, 50);
        function tutup() {
            if (TAMPIL !== box) return;
            TAMPIL = null;
            clearTimeout(jam);
            box.style.transition = 'all .4s ease';
            box.style.opacity = '0';
            setTimeout(function () { box.remove(); berikut(); }, 400);
        }
        var jam = setTimeout(tutup, lama);
        box.querySelector('button').addEventListener('click', tutup);
    }

    window.AcsNotif = function (jenis, judul, isi, tautan) {
        ANTRE.push({ jenis: jenis, judul: judul, isi: isi, tautan: tautan });
        berikut();
    };
})();
