/* Grafik "10 Menit" per detik (Traffic Monitoring, NMS Management Live & History).
   Meminta titik baru tiap 1 detik ke endpoint .../detik (App\Libraries\NmsDetik) dan menyusun jendela 600 detik.
   const d = NmsDetik.buat({
       url: sejak => alamat endpoint (sejak = waktu titik terakhir, ms),
       terima: D => ...,      // D.label[600], D.traffic [rx, tx], D.metrik [latency, loss, cpu, ram], D.akhir, D.ringkas
       status: (teks, galat) => ...
   });
   d.nyala(); d.mati(); d.ulang() (ganti interface / device) */
(function () {
    const JENDELA = 600;          // detik
    const JEDA = 1000;            // ms antar permintaan
    const BATAS_TUNGGU = 10000;   // ms, permintaan macet dibatalkan
    const WIB = 7 * 3600 * 1000;  // jam selalu WIB, sama dengan server

    const jam = detik => new Date(detik * 1000 + WIB).toISOString().substr(11, 8);

    function ringkas(data) {
        let n = 0, jumlah = 0, maks = null;
        data.forEach(v => {
            if (v === null) return;
            n++;
            jumlah += v;
            if (maks === null || v > maks) maks = v;
        });
        return { n: n, rata: n ? jumlah / n : null, maks: maks };
    }

    function buat(o) {
        let jalan = false, versi = 0, timer = null, batal = null, gagal = 0, selisihJam = 0;
        let traffic = new Map(), metrik = new Map(); // detik -> nilai
        let akhirT = 0, akhirM = 0, terakhir = null;

        function kosongkan() {
            traffic = new Map();
            metrik = new Map();
            akhirT = akhirM = 0;
            terakhir = null;
        }

        // titik terbaru yang sudah dimiliki; deret yang masih kosong tidak ikut dihitung
        function sejak() {
            const ada = [akhirT, akhirM].filter(Boolean);
            return ada.length ? Math.min.apply(null, ada) : 0;
        }

        function susun(res) {
            const ujung = Math.floor(res.sekarang / 1000);
            const awal = ujung - JENDELA + 1;
            [traffic, metrik].forEach(m => m.forEach((v, k) => { if (k < awal) m.delete(k); }));

            const label = [], rx = [], tx = [], kolom = [[], [], [], []];
            let sampel = 0, balas = 0;
            for (let s = awal; s <= ujung; s++) {
                label.push(jam(s));
                const t = traffic.get(s), m = metrik.get(s);
                rx.push(t ? t[0] : null);
                tx.push(t ? t[1] : null);
                kolom.forEach((k, i) => k.push(m ? m[i] : null));
                if (m) {
                    sampel++;
                    if (m[0] !== null) balas++;
                }
            }
            return {
                label: label,
                traffic: [rx, tx],
                metrik: kolom,
                sumber: res.sumber,
                akhir: {
                    traffic: akhirT ? { waktu: jam(Math.floor(akhirT / 1000)), nilai: traffic.get(Math.floor(akhirT / 1000)) || null } : null,
                    metrik: akhirM ? { waktu: jam(Math.floor(akhirM / 1000)), nilai: metrik.get(Math.floor(akhirM / 1000)) || null } : null
                },
                ringkas: {
                    rx: ringkas(rx), tx: ringkas(tx), latency: ringkas(kolom[0]), loss: ringkas(kolom[1]), cpu: ringkas(kolom[2]), mem: ringkas(kolom[3]),
                    sampel: sampel, uptime: sampel ? balas / sampel * 100 : null
                }
            };
        }

        function terima(res) {
            res.traffic.forEach(x => {
                traffic.set(Math.floor(x[0] / 1000), [x[1], x[2]]);
                if (x[0] > akhirT) akhirT = x[0];
            });
            res.metrik.forEach(x => {
                metrik.set(Math.floor(x[0] / 1000), [x[1], x[2], x[3], x[4]]);
                if (x[0] > akhirM) akhirM = x[0];
            });
            terakhir = susun(res);
            o.terima(terakhir);
            if (o.status) o.status(res.pesan || 'Diperbarui tiap detik', !!res.pesan);
        }

        function jadwalkan(ms) {
            clearTimeout(timer);
            if (jalan) timer = setTimeout(minta, ms);
        }

        function minta() {
            if (!jalan || document.hidden) return; // tab tidak terlihat: berhenti, dilanjutkan saat dibuka lagi
            const v = versi, mulai = performance.now();
            const kontrol = new AbortController();
            batal = kontrol;
            const habis = setTimeout(() => kontrol.abort(), BATAS_TUNGGU);
            fetch(o.url(sejak()), { signal: kontrol.signal, headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
                .then(r => r.json())
                .then(res => {
                    if (v !== versi || !jalan) return;
                    if (!res.ok) throw new Error(res.pesan || 'gagal');
                    gagal = 0;
                    terima(res);
                })
                .catch(() => {
                    if (v !== versi || !jalan) return;
                    gagal++;
                    if (gagal >= 2 && o.status) o.status('Data per detik belum bisa diambil, mencoba lagi...', true);
                    if (o.gagal) o.gagal();
                })
                .finally(() => {
                    clearTimeout(habis);
                    if (v !== versi) return;
                    batal = null;
                    // server lambat / gagal: jeda diperpanjang agar tidak menumpuk
                    jadwalkan(gagal ? Math.min(10000, JEDA * (gagal + 1)) : Math.max(50, JEDA - (performance.now() - mulai)));
                });
        }

        function putus() {
            versi++;
            clearTimeout(timer);
            if (batal) batal.abort();
            batal = null;
        }

        document.addEventListener('visibilitychange', () => {
            if (jalan && !document.hidden && !batal) jadwalkan(0);
        });

        return {
            nyala() {
                if (jalan) return;
                jalan = true;
                gagal = 0;
                jadwalkan(0);
            },
            mati() {
                jalan = false;
                putus();
                kosongkan();
            },
            ulang() {
                putus();
                kosongkan();
                gagal = 0;
                jadwalkan(0);
            },
            aktif: () => jalan,
            data: () => terakhir
        };
    }

    // isi grafik NmsGrafik dengan jendela 600 detik; true kalau ada titik
    function isi(g, label, data) {
        if (!g) return false;
        const n = data.map(d => d.reduce((x, v) => x + (v !== null ? 1 : 0), 0));
        NmsGrafik.modeDetik(g, true);
        g.data.labels = label;
        data.forEach((d, i) => {
            g.data.datasets[i].data = d;
            g.data.datasets[i].titikTunggal = n[i] === 1;
        });
        g.update('none');
        return n.some(x => x > 0);
    }

    window.NmsDetik = { buat: buat, isi: isi };
})();
