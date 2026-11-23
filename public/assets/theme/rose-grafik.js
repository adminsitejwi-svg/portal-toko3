/* Grafik bulat model rose (Nightingale), mengikuti public/d/tsmpilsn grsfik pie.jpg:
   tiap kategori mendapat sudut sama besar, panjang jari-jari = nilainya, warna bergradasi lingkaran,
   legenda di atas, persen di dalam sektor, sektor membesar + kotak persen saat disorot.
   RoseGrafik.gambar(elemen, [{ label, nilai, warna, satuan }]) -> digambar ulang setiap dipanggil. */
(function () {
    var UK = 400, P = UK / 2, R = 172, R_SOROT = 188;
    var LAPIS = [[0.82, 0.10], [0.56, 0.13], [0.30, 0.16]]; // [bagian jari-jari, kepekatan putih]: makin ke tengah makin terang
    var nomor = 0;

    function esc(v) {
        return String(v === null || v === undefined ? '' : v).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[c];
        });
    }

    function titik(r, sudut) { // sudut 0 = jam 12, searah jarum jam
        return (P + r * Math.sin(sudut)).toFixed(2) + ' ' + (P - r * Math.cos(sudut)).toFixed(2);
    }

    function sektor(r, a, b) {
        return 'M' + P + ' ' + P + 'L' + titik(r, a) + 'A' + r.toFixed(2) + ' ' + r.toFixed(2) + ' 0 ' + (b - a > Math.PI ? 1 : 0) + ' 1 ' + titik(r, b) + 'Z';
    }

    function persen(v) {
        return v.toLocaleString('id-ID', { maximumFractionDigits: 1 }) + '%';
    }

    function gambar(el, data) {
        var total = data.reduce(function (n, d) { return n + d.nilai; }, 0);
        var maks = Math.max.apply(null, data.map(function (d) { return d.nilai; }).concat([0]));
        var lebar = 2 * Math.PI / data.length;
        var id = 'rose' + (++nomor);

        var legenda = data.map(function (d) {
            return '<li><svg viewBox="0 0 22 12" width="22" height="12" aria-hidden="true"><path d="M0 6Q6 0 11 0T22 6Q16 12 11 12T0 6Z" fill="' + esc(d.warna) + '"/></svg>' +
                esc(d.label) + ' <b>' + d.nilai + '</b></li>';
        }).join('');

        var svg = '';
        data.forEach(function (d, i) {
            var a = i * lebar, b = a + lebar, tengah = a + lebar / 2;
            var p = total ? d.nilai / total * 100 : 0;
            svg += '<path class="rose-dasar" d="' + sektor(R, a, b) + '"/>';
            if (d.nilai > 0) {
                var r = R * d.nilai / maks;
                svg += '<g class="rose-sektor" tabindex="0" role="img" data-i="' + i + '" aria-label="' + esc(d.label + ' ' + d.nilai + ' ' + (d.satuan || '') + ', ' + persen(p)) + '">' +
                    '<path class="rose-sorot" d="' + sektor(r * R_SOROT / R, a, b) + '" fill="' + esc(d.warna) + '"/>' +
                    '<path d="' + sektor(r, a, b) + '" fill="' + esc(d.warna) + '"/>' +
                    LAPIS.map(function (l) { return '<path d="' + sektor(r * l[0], a, b) + '" fill="#fff" fill-opacity="' + l[1] + '"/>'; }).join('') +
                    '<path class="rose-tepi" d="' + sektor(r, a, b) + '"/></g>';
                var rt = Math.max(r * 0.6, 46);
                svg += '<text class="rose-teks' + (r < 70 ? ' is-luar' : '') + '" x="' + titik(r < 70 ? r + 26 : rt, tengah).replace(' ', '" y="') + '">' + persen(p) + '</text>';
            } else {
                svg += '<text class="rose-teks is-nol" x="' + titik(R * 0.55, tengah).replace(' ', '" y="') + '">' + persen(0) + '</text>';
            }
        });

        el.classList.add('rose');
        el.innerHTML = '<ul class="rose-legenda">' + legenda + '</ul>' +
            '<div class="rose-kotak"><svg viewBox="0 0 ' + UK + ' ' + UK + '" id="' + id + '">' + svg + '</svg><div class="rose-tip" hidden></div></div>';

        var kotak = el.querySelector('.rose-kotak'), tip = el.querySelector('.rose-tip');
        function tampil(g, e) {
            var d = data[g.getAttribute('data-i')];
            var b = kotak.getBoundingClientRect(), s = g.getBoundingClientRect();
            tip.textContent = d.label + ': ' + d.nilai + ' ' + (d.satuan || '') + ' (' + persen(d.nilai / total * 100) + ')';
            tip.hidden = false;
            var x = (e && e.clientX ? e.clientX : s.left + s.width / 2) - b.left;
            var y = (e && e.clientY ? e.clientY : s.top + s.height / 2) - b.top;
            tip.style.left = Math.max(tip.offsetWidth / 2, Math.min(b.width - tip.offsetWidth / 2, x)) + 'px';
            tip.style.top = Math.max(0, y - tip.offsetHeight - 14) + 'px';
        }
        Array.prototype.forEach.call(el.querySelectorAll('.rose-sektor'), function (g) {
            g.addEventListener('mousemove', function (e) { tampil(g, e); });
            g.addEventListener('focus', function () { tampil(g); });
            g.addEventListener('mouseleave', function () { tip.hidden = true; });
            g.addEventListener('blur', function () { tip.hidden = true; });
        });
    }

    var css = document.createElement('style');
    css.textContent =
        '.rose{display:flex;flex-direction:column;align-items:center;height:100%}' +
        '.rose-legenda{display:flex;flex-wrap:wrap;justify-content:center;gap:6px 18px;margin:0 0 8px;padding:0;list-style:none;font-size:13px;color:var(--rf-text,#5b6b7f)}' +
        '.rose-legenda li{display:inline-flex;align-items:center;gap:6px}' +
        '.rose-legenda b{font-weight:700}' +
        '.rose-kotak{position:relative;flex:1;min-height:0;width:100%;display:flex;justify-content:center}' +
        '.rose-kotak>svg{width:100%;height:100%;max-width:340px}' +
        '.rose-dasar{fill:var(--rf-muted,#8a96ad);fill-opacity:.09;stroke:var(--rf-card,#fff);stroke-width:2}' +
        '.rose-tepi{fill:none;stroke:var(--rf-card,#fff);stroke-width:2}' +
        '.rose-sektor{cursor:pointer;outline:none}' +
        '.rose-sorot{opacity:0;transition:opacity .2s ease}' +
        '.rose-sektor:hover .rose-sorot,.rose-sektor:focus-visible .rose-sorot{opacity:.75}' +
        '.rose-teks{font-size:20px;font-weight:800;fill:#fff;text-anchor:middle;dominant-baseline:central;pointer-events:none}' +
        '.rose-teks.is-luar{font-size:17px;fill:var(--rf-text,#37474f)}' +
        '.rose-teks.is-nol{font-size:15px;font-weight:600;fill:var(--rf-muted,#8a96ad)}' +
        '.rose-tip{position:absolute;transform:translateX(-50%);background:rgba(60,64,70,.94);color:#fff;font-size:13px;font-weight:700;padding:6px 10px;border-radius:4px;white-space:nowrap;pointer-events:none;z-index:5}' +
        '.rose-tip[hidden]{display:none}';
    document.head.appendChild(css);

    window.RoseGrafik = { gambar: gambar };
})();
