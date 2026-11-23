<?php
// Laporan NMS (Nms/export): halaman cetak -> tombol "Cetak / Simpan PDF" (dialog print browser).
// Mengikuti netmon/nms_export.php: info device, snapshot saat ini, ringkasan & grafik history.
$s   = $snapshot ?? [];
$r   = $grafik['device']['ringkas'];
$t   = $grafik['traffic'];
$fmt = static fn ($v, int $d, string $sat) => $v === null ? '-' : number_format((float) $v, $d, ',', '.') . $sat;
// logo perusahaan yang dipilih saat export (sama dengan RFO); tanpa pilihan = logo halaman login (auth/Login)
$kop      = $kop ?? null;
$fileLogo = $kop['logo'] ?? 'Logo/Logo-N2.png';
$logo     = is_file(FCPATH . $fileLogo) ? base_url($fileLogo) : null;
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title>Laporan NMS - <?= esc($device['hostname']) ?></title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    <script src="<?= base_url('assets/theme/nms-grafik.js') ?>?v=<?= @filemtime(FCPATH . 'assets/theme/nms-grafik.js') ?>"></script>
    <style>
        * { box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; color: #172033; background: #eef2f7; margin: 0; padding: 25px; }
        .report { max-width: 1100px; margin: auto; background: #fff; padding: 35px; }
        .header { display: flex; justify-content: space-between; align-items: center; gap: 20px; border-bottom: 3px solid #2f6bff; padding-bottom: 18px; }
        .brand { display: flex; align-items: center; gap: 14px; }
        .brand img { height: 46px; }
        .brand.is-kop img { height: 64px; max-width: 260px; object-fit: contain; }
        .header.is-kop, .brand.is-kop .brand-teks { border-color: var(--kop); }
        .brand.is-kop .brand-teks { color: var(--kop); font-size: 19px; }
        .brand-teks { font-size: 22px; font-weight: 800; color: #1d4ed8; letter-spacing: .5px; }
        .subtitle { font-size: 11px; color: #64748b; margin-top: 4px; letter-spacing: 1px; }
        h1 { font-size: 22px; margin: 25px 0 5px; }
        h2 { font-size: 15px; margin: 0 0 12px; }
        .meta { color: #64748b; font-size: 12px; }
        .section { margin-top: 24px; break-inside: avoid; }
        .info { width: 100%; border-collapse: collapse; }
        .info td { padding: 9px 10px; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
        .info td:first-child { width: 190px; color: #64748b; }
        .cards { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
        .kpi { border: 1px solid #e2e8f0; padding: 13px; border-radius: 8px; }
        .kpi small { display: block; color: #64748b; margin-bottom: 7px; font-size: 11px; }
        .kpi strong { font-size: 18px; }
        .charts { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .chart { background: #181b1f; border: 1px solid #2c3036; border-radius: 6px; padding: 14px; min-width: 0; break-inside: avoid; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .chart h2 { color: #e3e5e8; text-align: center; font-size: 14px; font-weight: 600; }
        .chart .kosong { color: #a5abb5; }
        .chart.wide { grid-column: 1 / -1; }
        .canvas-wrap { height: 240px; position: relative; }
        .kosong { color: #64748b; font-size: 13px; padding: 30px 0; text-align: center; }
        .footer { border-top: 1px solid #e2e8f0; margin-top: 28px; padding-top: 12px; color: #64748b; font-size: 11px; display: flex; justify-content: space-between; gap: 10px; flex-wrap: wrap; }
        .actions { text-align: right; margin-bottom: 18px; }
        button { border: 0; background: #2f6bff; color: #fff; padding: 11px 18px; border-radius: 6px; cursor: pointer; font-weight: 700; }
        body.cetak { padding: 0; }
        body.cetak .report { width: 273mm; max-width: none; padding: 0; }
        body.cetak .charts { grid-template-columns: 1fr 1fr; }
        @media print {
            @page { size: A4 landscape; margin: 12mm; }
            body { background: #fff; padding: 0; }
            .report { max-width: none; padding: 0; }
            .actions { display: none; }
            .canvas-wrap canvas { width: 100% !important; height: 100% !important; }
        }
        @media screen and (max-width: 700px) {
            body:not(.cetak) { padding: 10px; }
            body:not(.cetak) .report { padding: 18px; }
            .header { flex-direction: column; align-items: flex-start; }
            body:not(.cetak) .charts { grid-template-columns: 1fr; }
            .cards { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>

<body>
    <div class="report">
        <div class="actions"><button type="button" onclick="window.print()">Cetak / Simpan PDF</button></div>

        <div class="header <?= $kop ? 'is-kop' : '' ?>" <?= $kop ? 'style="--kop:' . esc($kop['warna'], 'attr') . '"' : '' ?>>
            <div class="brand <?= $kop ? 'is-kop' : '' ?>">
                <?php if ($logo) : ?><img id="logoKop" src="<?= $logo ?>" alt="Logo <?= esc($kop['nama'] ?? 'Network Operasional') ?>"><?php endif; ?>
                <div>
                    <div class="brand-teks"><?= esc($kop['nama'] ?? 'NMS · JWI Group') ?></div>
                    <div class="subtitle">NETWORK MANAGEMENT SYSTEM</div>
                </div>
            </div>
            <div style="text-align:right;font-size:12px;color:#64748b">
                <strong>NETWORK PERFORMANCE REPORT</strong><br>
                Dibuat: <?= esc($dibuat) ?><?= $oleh !== '' ? '<br>Oleh: ' . esc($oleh) : '' ?>
            </div>
        </div>

        <h1>Laporan Monitoring Jaringan</h1>
        <div class="meta">Laporan performa perangkat berdasarkan data polling NMS (ping ICMP, SNMP &amp; traffic interface).</div>

        <div class="section">
            <h2>Informasi Device</h2>
            <table class="info">
                <tr><td>Hostname</td><td><strong><?= esc($device['hostname']) ?></strong></td></tr>
                <tr><td>IP Address</td><td><?= esc($device['ip_address']) ?></td></tr>
                <tr><td>Vendor / Model</td><td><?= esc($device['vendor'] . ' / ' . ($device['model'] ?: '-')) ?></td></tr>
                <tr><td>Device Group / Lokasi</td><td><?= esc($device['device_group'] . ' / ' . ($device['lokasi'] ?: '-')) ?></td></tr>
                <tr><td>Interface</td><td><?= $interface ? esc($interface['if_name'] . ($interface['if_alias'] ? ' (' . $interface['if_alias'] . ')' : '')) : '-' ?></td></tr>
                <tr><td>Periode</td><td><?= esc($label) ?></td></tr>
                <tr><td>Status Saat Ini</td><td><?= esc($s['status'] ?? '-') ?><?= ! empty($s['uptime']) ? ' · uptime ' . esc($s['uptime']) : '' ?></td></tr>
            </table>
        </div>

        <div class="section">
            <h2>Snapshot Saat Ini</h2>
            <div class="cards">
                <div class="kpi"><small>Latency</small><strong><?= $fmt($s['latency'] ?? null, 2, ' ms') ?></strong></div>
                <div class="kpi"><small>Packet Loss</small><strong><?= $fmt($s['loss'] ?? null, 0, '%') ?></strong></div>
                <div class="kpi"><small>CPU Usage</small><strong><?= $fmt($s['cpu'] ?? null, 1, '%') ?></strong></div>
                <div class="kpi"><small>Memory Usage</small><strong><?= $fmt($s['mem'] ?? null, 1, '%') ?></strong></div>
            </div>
        </div>

        <div class="section">
            <h2>Ringkasan Periode</h2>
            <div class="cards">
                <div class="kpi"><small>Availability (ping)</small><strong><?= $fmt($r['uptime'], 2, '%') ?></strong><div class="meta"><?= (int) $r['sampel'] ?> sampel</div></div>
                <div class="kpi"><small>Latency rata-rata / puncak</small><strong><?= $fmt($r['lat_avg'], 2, '') ?> / <?= $fmt($r['lat_max'], 2, ' ms') ?></strong><div class="meta">Packet loss rata-rata <?= $fmt($r['loss_avg'], 2, '%') ?></div></div>
                <div class="kpi"><small>CPU rata-rata / puncak</small><strong><?= $fmt($r['cpu_avg'], 1, '%') ?> / <?= $fmt($r['cpu_max'], 1, '%') ?></strong></div>
                <div class="kpi"><small>RAM rata-rata / puncak</small><strong><?= $fmt($r['mem_avg'], 1, '%') ?> / <?= $fmt($r['mem_max'], 1, '%') ?></strong></div>
            </div>
            <?php if ($t) : ?>
                <p class="meta" style="margin-top:10px">Traffic <?= esc($interface['if_name']) ?>: RX rata-rata <?= esc($t['ringkas']['rx_avg']) ?> (puncak <?= esc($t['ringkas']['rx_max']) ?>), TX rata-rata <?= esc($t['ringkas']['tx_avg']) ?> (puncak <?= esc($t['ringkas']['tx_max']) ?>).</p>
            <?php endif; ?>
        </div>

        <div class="section">
            <h2>Grafik History</h2>
            <div class="charts">
                <div class="chart"><h2>Ping Latency (ms)</h2><div class="canvas-wrap"><canvas id="cLat"></canvas></div></div>
                <div class="chart"><h2>Packet Loss (%)</h2><div class="canvas-wrap"><canvas id="cLoss"></canvas></div></div>
                <div class="chart"><h2>CPU Usage (%)</h2><div class="canvas-wrap"><canvas id="cCpu"></canvas></div></div>
                <div class="chart"><h2>Memory Usage (%)</h2><div class="canvas-wrap"><canvas id="cMem"></canvas></div></div>
                <div class="chart wide"><h2>Interface Traffic RX / TX</h2><div class="canvas-wrap"><canvas id="cTraf"></canvas></div></div>
            </div>
        </div>

        <div class="footer">
            <span>NMS · <?= esc($kop['nama'] ?? 'Sistem Operasional JWI Group') ?></span>
            <span>Confidential - Internal Network Report</span>
        </div>
    </div>

    <?php if ($kop && $logo) : ?>
    <script>
        // file logo punya banyak ruang kosong: dipotong di browser agar logo tampil besar (kalau gagal, logo asli tetap dipakai)
        (function () {
            const img = document.getElementById('logoKop');
            function potong() {
                try {
                    const k = Math.min(1, 1000 / img.naturalWidth);
                    const w = Math.round(img.naturalWidth * k), h = Math.round(img.naturalHeight * k);
                    const c = document.createElement('canvas');
                    c.width = w;
                    c.height = h;
                    const x = c.getContext('2d');
                    x.drawImage(img, 0, 0, w, h);
                    const p = x.getImageData(0, 0, w, h).data;
                    let kiri = w, atas = h, kanan = -1, bawah = -1;
                    for (let i = 0; i < w * h; i++) {
                        const a = i * 4;
                        if (p[a + 3] < 16 || (p[a] > 245 && p[a + 1] > 245 && p[a + 2] > 245)) continue; // transparan / putih
                        const px = i % w, py = (i - px) / w;
                        if (px < kiri) kiri = px;
                        if (px > kanan) kanan = px;
                        if (py < atas) atas = py;
                        if (py > bawah) bawah = py;
                    }
                    if (kanan < kiri || bawah < atas) return;
                    const lw = kanan - kiri + 1, lh = bawah - atas + 1;
                    const o = document.createElement('canvas');
                    o.width = lw;
                    o.height = lh;
                    o.getContext('2d').drawImage(c, kiri, atas, lw, lh, 0, 0, lw, lh);
                    img.src = o.toDataURL('image/png');
                } catch (e) { /* logo asli */ }
            }
            if (img.complete && img.naturalWidth) potong();
            else img.addEventListener('load', potong, { once: true });
        })();
    </script>
    <?php endif; ?>

    <script>
        const D = <?= json_encode($grafik, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE) ?>;

        function formatBps(v) {
            if (v === null || v === undefined) return '-';
            for (const [s, b] of [['Gbps', 1e9], ['Mbps', 1e6], ['Kbps', 1e3]]) {
                if (v >= b) return (v / b).toLocaleString('id-ID', { maximumFractionDigits: 2 }) + ' ' + s;
            }
            return Math.round(v) + ' bps';
        }

        function grafik(id, label, sets, fmtY, maks) {
            const el = document.getElementById(id);
            if (!label.length || !sets.some(s => s.data.some(v => v !== null))) {
                el.parentNode.innerHTML = '<div class="kosong">Tidak ada data pada periode ini.</div>';
                return;
            }
            NmsGrafik.buat(el, label, sets, fmtY, { dpr: 3, maks: maks });
        }

        // ukuran grafik disesuaikan dengan lebar kertas saat dicetak
        // (body.cetak = lebar isi A4 landscape, dipasang dulu karena ukuran canvas dihitung sebelum tata letak cetak)
        function siapCetak(ya) {
            document.body.classList.toggle('cetak', ya);
            Object.values(Chart.instances).forEach(g => g.resize());
        }
        window.addEventListener('beforeprint', () => siapCetak(true));
        window.addEventListener('afterprint', () => siapCetak(false));

        const W = NmsGrafik.WARNA;
        const angka = (v, n) => v === null ? '-' : Number(v).toLocaleString('id-ID', { maximumFractionDigits: n });
        const d = D.device;
        grafik('cLat', d.label, [{ label: 'Latency (ms)', data: d.latency, borderColor: W.biru }], v => angka(v, 1) + ' ms');
        grafik('cLoss', d.label, [{ label: 'Packet Loss (%)', data: d.loss, borderColor: W.merah }], v => angka(v, 0) + '%', 100);
        grafik('cCpu', d.label, [{ label: 'CPU (%)', data: d.cpu, borderColor: W.hijau }], v => angka(v, 0) + '%', 100);
        grafik('cMem', d.label, [{ label: 'RAM (%)', data: d.mem, borderColor: W.ungu }], v => angka(v, 0) + '%', 100);
        const t = D.traffic || { label: [], rx: [], tx: [] };
        grafik('cTraf', t.label, [{ label: 'RX / Download', data: t.rx, borderColor: W.hijau }, { label: 'TX / Upload', data: t.tx, borderColor: W.kuning }], formatBps, 1000);
    </script>
</body>

</html>
