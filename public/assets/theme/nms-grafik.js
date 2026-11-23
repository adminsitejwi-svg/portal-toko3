/* Grafik NMS (Chart.js): dipakai halaman Nms/index.php dan laporan Nms/export.php agar tampilannya sama.
   Tampilan mengikuti public/d/tampilann grafik.jpg: panel gelap (di mode terang & gelap), garis tipis lurus antar titik,
   isian transparan, grid mendatar + tegak, legenda di bawah tengah.
   NmsGrafik.buat(canvas, label, sets, fmtY, opsi) -> Chart;  sets = [{ label, data, borderColor }] */
(function () {
    const T = { panel: '#181b1f', grid: 'rgba(204,204,220,.13)', teks: '#c7cad1', kuat: '#f1f3f5', tooltip: '#22252b', batas: '#3a3f47', bantu: 'rgba(204,204,220,.5)' };
    const WARNA = { hijau: '#7eb26d', kuning: '#eab839', biru: '#6ed0e0', merah: '#e24d42', ungu: '#b877d9' };
    const ISIAN = '2e';  // kepekatan isian area (hex alpha)

    // latar panel ikut digambar di canvas: Chrome mencetak canvas transparan menjadi putih
    const latar = {
        id: 'nmsLatar',
        beforeDraw(c) {
            const k = c.ctx;
            k.save();
            k.fillStyle = T.panel;
            k.fillRect(0, 0, c.width, c.height);
            k.restore();
        }
    };

    // garis bantu tegak mengikuti kursor
    const garisBantu = {
        id: 'nmsGarisBantu',
        afterDatasetsDraw(c) {
            const aktif = c.tooltip && c.tooltip.getActiveElements ? c.tooltip.getActiveElements() : [];
            if (!aktif.length) return;
            const x = aktif[0].element.x, a = c.chartArea, k = c.ctx;
            k.save();
            k.beginPath();
            k.lineWidth = 1;
            k.strokeStyle = T.bantu;
            k.moveTo(x, a.top);
            k.lineTo(x, a.bottom);
            k.stroke();
            k.restore();
        }
    };

    // legenda: potongan garis warna + nama
    function legenda(c) {
        return c.data.datasets.map((s, i) => ({
            text: s.label, datasetIndex: i, hidden: !c.isDatasetVisible(i), fontColor: T.teks,
            pointStyle: 'line', strokeStyle: s.borderColor, fillStyle: s.borderColor, lineWidth: 3
        }));
    }

    function buat(el, label, sets, fmtY, opsi) {
        if (!el || !window.Chart) return null;
        opsi = opsi || {};
        return new Chart(el, {
            type: 'line',
            data: {
                labels: label,
                datasets: sets.map(s => Object.assign({
                    data: [], fill: 'origin', backgroundColor: s.borderColor + ISIAN, spanGaps: true,
                    tension: 0, borderWidth: 1.5, borderJoinStyle: 'round',
                    // satu titik saja tidak membentuk garis, jadi ditampilkan sebagai titik
                    pointRadius: c => c.dataset.data.length === 1 ? 3 : 0,
                    pointBackgroundColor: s.borderColor, pointBorderWidth: 0,
                    pointHoverRadius: 4, pointHoverBackgroundColor: s.borderColor, pointHoverBorderColor: T.panel, pointHoverBorderWidth: 2,
                    pointHitRadius: 12
                }, s))
            },
            options: {
                responsive: true, maintainAspectRatio: false, animation: false,
                devicePixelRatio: opsi.dpr || undefined,
                layout: { padding: { top: 6, right: 6 } },
                interaction: { intersect: false, mode: 'index' },
                plugins: {
                    legend: {
                        position: 'bottom', align: 'center',
                        labels: { usePointStyle: true, pointStyleWidth: 20, padding: 14, font: { size: 11 }, generateLabels: legenda }
                    },
                    tooltip: {
                        backgroundColor: T.tooltip, borderColor: T.batas, borderWidth: 1, titleColor: T.teks, bodyColor: T.kuat,
                        cornerRadius: 4, padding: 10, caretSize: 0, caretPadding: 12, boxPadding: 6, bodySpacing: 5,
                        usePointStyle: true, titleFont: { size: 11, weight: '500' }, bodyFont: { size: 12, weight: '600' },
                        callbacks: {
                            label: c => ' ' + c.dataset.label + ': ' + fmtY(c.parsed.y),
                            labelPointStyle: () => ({ pointStyle: 'circle', rotation: 0 }),
                            labelColor: c => ({ borderColor: c.dataset.borderColor, backgroundColor: c.dataset.borderColor })
                        }
                    }
                },
                scales: {
                    x: { grid: { color: T.grid, drawTicks: false }, border: { color: T.grid }, ticks: { color: T.teks, maxTicksLimit: 8, maxRotation: 0, padding: 8, font: { size: 11 } } },
                    y: {
                        beginAtZero: true, suggestedMax: opsi.maks, border: { display: false }, grid: { color: T.grid, drawTicks: false },
                        ticks: { color: T.teks, maxTicksLimit: 6, padding: 8, font: { size: 11 }, callback: v => fmtY(v) }
                    }
                }
            },
            plugins: [latar, garisBantu]
        });
    }

    window.NmsGrafik = { buat: buat, WARNA: WARNA };
})();
