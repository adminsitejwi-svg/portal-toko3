<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title><?= esc($row['nama']) ?> - Data Topologi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?= csrf_meta() ?>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { box-sizing: border-box; }
        html, body { margin: 0; height: 100%; overflow: hidden; font-family: Inter, system-ui, sans-serif; background: #f1f5f9; }
        .tp-bar { height: 52px; display: flex; align-items: center; gap: 12px; padding: 0 14px; background: #1c2434; color: #e2e8f0; }
        .tp-back { display: inline-flex; align-items: center; gap: 6px; color: #e2e8f0; text-decoration: none; font-size: 14px; padding: 7px 12px; border-radius: 8px; background: rgba(255, 255, 255, .08); white-space: nowrap; }
        .tp-back:hover { background: rgba(255, 255, 255, .16); }
        .tp-name { font-weight: 600; font-size: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; min-width: 0; flex: 1; }
        .tp-status { font-size: 12.5px; color: #94a3b8; white-space: nowrap; display: inline-flex; align-items: center; gap: 5px; }
        .tp-status.is-dirty { color: #fbbf24; }
        .tp-status.is-ok { color: #4ade80; }
        .tp-btn { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; font-weight: 600; padding: 7px 12px; border-radius: 8px; border: 0; cursor: pointer; text-decoration: none; white-space: nowrap; }
        .tp-btn-save { background: #04a9f5; color: #fff; }
        .tp-btn-save:hover { filter: brightness(1.08); }
        .tp-btn-dl { background: rgba(255, 255, 255, .08); color: #e2e8f0; }
        .tp-btn-dl:hover { background: rgba(255, 255, 255, .16); }
        .tp-btn[hidden] { display: none; }
        #drawio { display: block; width: 100%; height: calc(100% - 52px); border: 0; background: #fff; }
        .tp-loading { position: absolute; inset: 52px 0 0 0; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px; color: #475569; font-size: 14px; background: #f8fafc; }
        .tp-loading.hide { display: none; }
        .tp-loading .tp-err-ic { font-size: 42px; color: #ef4444; }
        .tp-loading h3 { margin: 0; color: #1e293b; font-size: 17px; }
        .tp-loading p { margin: 0; max-width: 460px; text-align: center; line-height: 1.5; }
        .tp-retry { display: inline-flex; align-items: center; gap: 6px; margin-top: 6px; padding: 9px 16px; border-radius: 8px; border: 0; background: #04a9f5; color: #fff; font-weight: 600; font-size: 14px; cursor: pointer; }
        .tp-src { font-size: 11.5px; color: #94a3b8; white-space: nowrap; }
        @keyframes tp-spin { to { transform: rotate(360deg); } }
        .tp-spin { display: inline-block; animation: tp-spin 1s linear infinite; }
        @media (max-width: 640px) {
            .tp-back span, .tp-btn span { display: none; }
        }
    </style>
</head>

<body>
    <div class="tp-bar">
        <a href="<?= site_url('DataTopologi') ?>" class="tp-back" id="btnKembali"><i class="ti ti-arrow-left"></i> <span>Data Topologi</span></a>
        <div class="tp-name" title="<?= esc($row['nama'], 'attr') ?>"><i class="ti ti-topology-star-3"></i> <?= esc($row['nama']) ?></div>
        <span class="tp-status" id="tpStatus">
            <?php if (! empty($row['updated_at']) && ! empty($row['gambar'])) : ?>
                <i class="ti ti-cloud-check"></i> Tersimpan <?= esc(date('d-m-Y H:i', strtotime($row['updated_at']))) ?>
            <?php else : ?>
                <i class="ti ti-pencil"></i> Belum pernah disimpan
            <?php endif; ?>
        </span>
        <span class="tp-src" id="tpSumber"></span>
        <button type="button" class="tp-btn tp-btn-save" id="btnSimpan" title="Simpan (Ctrl+S)"><i class="ti ti-device-floppy"></i> <span>Simpan</span></button>
        <a href="<?= site_url('DataTopologi/download/' . $row['id']) ?>" class="tp-btn tp-btn-dl" id="btnDownload" <?= empty($row['gambar']) ? 'hidden' : '' ?>><i class="ti ti-download"></i> <span>PNG</span></a>
    </div>

    <div class="tp-loading" id="tpLoading"></div>
    <iframe id="drawio" title="Editor topologi draw.io"></iframe>

    <script>
        // Editor draw.io (protokol JSON postMessage / "Embed Mode").
        // Urutan sumber editor: 1) draw.io yang terpasang di server portal (public/drawio, tidak butuh internet)
        //                       2) embed.diagrams.net (cadangan, butuh internet)
        // Kalau sumber tidak menjawab (event "init") dalam BATAS_MUAT ms, pindah ke sumber berikutnya;
        // kalau semua gagal, tampil pesan error + tombol Coba Lagi.
        // Simpan: draw.io mengirim XML -> diminta export "xmlpng" (PNG yang juga memuat diagram) -> dikirim ke server.
        const LIBS = 'general;network;cisco19;citrix;rack;signs;arrows2;mockups';
        const PARAM = 'embed=1&proto=json&spin=1&libraries=1&saveAndExit=1&lang=id&libs=' + encodeURIComponent(LIBS);
        const LOKAL_URL = new URL("<?= base_url('drawio/index.html') ?>", location.href);
        const SUMBER = [{
                nama: 'server portal',
                url: LOKAL_URL.href + '?' + PARAM + '&stealth=1',
                origin: LOKAL_URL.origin
            },
            {
                nama: 'diagrams.net (online)',
                url: 'https://embed.diagrams.net/?' + PARAM,
                origin: 'https://embed.diagrams.net'
            }
        ];
        const BATAS_MUAT = 20000;
        let sumberKe = 0;
        let timerMuat = null;
        const ID = <?= (int) $row['id'] ?>;
        const NAMA = <?= json_encode($row['nama'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        const SAVE_URL = "<?= site_url('DataTopologi/simpanDiagram') ?>";
        const LIST_URL = "<?= site_url('DataTopologi') ?>";

        let xmlTerakhir = <?= json_encode((string) ($row['xml'] ?? ''), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        let dirty = false;
        let keluarSetelahSimpan = false;
        let menyimpan = false;
        let siap = false;

        const frame = document.getElementById('drawio');
        const statusEl = document.getElementById('tpStatus');

        function kirim(msg) {
            frame.contentWindow.postMessage(JSON.stringify(msg), SUMBER[sumberKe].origin);
        }

        // ---- memuat editor dari sumber ke-n, dengan batas waktu ----
        const loadingEl = document.getElementById('tpLoading');

        function tampilMemuat(teks) {
            loadingEl.classList.remove('hide');
            loadingEl.innerHTML = '<i class="ti ti-loader-2 tp-spin" style="font-size:30px"></i>';
            loadingEl.appendChild(document.createTextNode(teks));
        }

        function muatSumber(n) {
            clearTimeout(timerMuat);
            siap = false;
            sumberKe = n;
            tampilMemuat('Memuat editor draw.io dari ' + SUMBER[n].nama + '...');
            frame.src = SUMBER[n].url;
            timerMuat = setTimeout(() => {
                if (siap) return;
                if (sumberKe + 1 < SUMBER.length) muatSumber(sumberKe + 1);
                else gagalMuat();
            }, BATAS_MUAT);
        }

        function gagalMuat() {
            frame.removeAttribute('src');
            loadingEl.classList.remove('hide');
            loadingEl.innerHTML =
                '<i class="ti ti-plug-connected-x tp-err-ic"></i>' +
                '<h3>Editor draw.io tidak bisa dimuat</h3>' +
                '<p>Editor tidak termuat dari server portal maupun dari diagrams.net. Cek koneksi internet / jaringan, lalu coba lagi. ' +
                'Diagram yang sudah tersimpan tetap aman dan gambarnya tetap bisa di-download dari halaman Data Topologi.</p>' +
                '<button type="button" class="tp-retry" id="btnCobaLagi"><i class="ti ti-refresh"></i> Coba Lagi</button>';
            document.getElementById('btnCobaLagi').addEventListener('click', () => muatSumber(0));
            document.getElementById('tpSumber').textContent = '';
        }

        function setStatus(teks, jenis, ikon) {
            statusEl.className = 'tp-status' + (jenis ? ' is-' + jenis : '');
            statusEl.innerHTML = '<i class="ti ' + ikon + '"></i> ';
            statusEl.appendChild(document.createTextNode(teks));
        }

        function getCsrf() {
            const el = [...document.getElementsByTagName('meta')].find(m => m.name && m.content && m.name.toLowerCase().includes('csrf'));
            return el ? el : null;
        }

        async function unggah(dataUrl, xml) {
            menyimpan = true;
            setStatus('Menyimpan...', '', 'ti-loader-2');
            try {
                const blob = await (await fetch(dataUrl)).blob();
                const body = new FormData();
                body.append('id', ID);
                body.append('xml', xml);
                body.append('gambar', blob, 'topologi.png');
                const c = getCsrf();
                if (c) body.append(c.name, c.content);

                const res = await fetch(SAVE_URL, {
                    method: 'POST',
                    body: body
                });
                const json = await res.json().catch(() => ({}));
                if (c && json.csrf) c.content = json.csrf;

                if (res.ok && json.status === 'ok') {
                    xmlTerakhir = xml;
                    dirty = false;
                    setStatus('Tersimpan ' + json.waktu, 'ok', 'ti-cloud-check');
                    document.getElementById('btnDownload').hidden = false;
                    kirim({
                        action: 'status',
                        message: 'Tersimpan ' + json.waktu,
                        modified: false
                    });
                    if (keluarSetelahSimpan) window.location.href = LIST_URL;
                } else {
                    throw new Error(json.message || 'Gagal menyimpan topologi.');
                }
            } catch (err) {
                setStatus('Gagal menyimpan', 'dirty', 'ti-alert-triangle');
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan',
                    text: err.message || 'Gagal menyimpan topologi, coba lagi.',
                    confirmButtonColor: '#04a9f5'
                });
            } finally {
                menyimpan = false;
                keluarSetelahSimpan = false;
            }
        }

        function mintaSimpan(xml) {
            if (menyimpan) return;
            const msg = {
                action: 'export',
                format: 'xmlpng',
                spinKey: 'saving',
                scale: 1,
                border: 10,
                background: '#ffffff'
            };
            if (xml) msg.xml = xml; // tanpa xml: draw.io memakai diagram yang sedang terbuka
            kirim(msg);
        }

        async function keluar() {
            if (dirty) {
                const r = await Swal.fire({
                    icon: 'warning',
                    title: 'Perubahan belum disimpan',
                    text: 'Keluar tanpa menyimpan? Perubahan terakhir akan hilang.',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Keluar tanpa simpan',
                    cancelButtonText: 'Batal'
                });
                if (!r.isConfirmed) return;
                dirty = false;
            }
            window.location.href = LIST_URL;
        }

        window.addEventListener('message', function(e) {
            if (e.origin !== SUMBER[sumberKe].origin || e.source !== frame.contentWindow || typeof e.data !== 'string') return;
            let msg;
            try {
                msg = JSON.parse(e.data);
            } catch (_) {
                return;
            }

            switch (msg.event) {
                case 'init':
                    siap = true;
                    clearTimeout(timerMuat);
                    loadingEl.classList.add('hide');
                    document.getElementById('tpSumber').textContent = 'Editor: ' + SUMBER[sumberKe].nama;
                    kirim({
                        action: 'load',
                        autosave: 1,
                        xml: xmlTerakhir || '',
                        title: NAMA
                    });
                    break;
                case 'autosave': // ada perubahan di diagram (belum disimpan ke server)
                    xmlTerakhir = msg.xml;
                    if (!dirty) {
                        dirty = true;
                        setStatus('Belum disimpan', 'dirty', 'ti-pencil');
                    }
                    break;
                case 'save': // tombol Simpan / Simpan & Keluar di draw.io
                    keluarSetelahSimpan = !!msg.exit;
                    xmlTerakhir = msg.xml;
                    mintaSimpan(msg.xml);
                    break;
                case 'export':
                    if (msg.format === 'xmlpng' && msg.data) unggah(msg.data, msg.xml || xmlTerakhir);
                    break;
                case 'exit':
                    keluar();
                    break;
            }
        });

        // tombol Simpan di bar atas & Ctrl+S
        document.getElementById('btnSimpan').addEventListener('click', () => siap && mintaSimpan(xmlTerakhir));
        document.addEventListener('keydown', e => {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 's') {
                e.preventDefault();
                if (siap) mintaSimpan(xmlTerakhir);
            }
        });
        document.getElementById('btnKembali').addEventListener('click', e => {
            e.preventDefault();
            keluar();
        });
        window.addEventListener('beforeunload', e => {
            if (dirty) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        muatSumber(0);
    </script>
    <?php if (!session()->get('logged_in')) : ?>
        <script>
            window.location.href = "<?= base_url('/login') ?>";
        </script>
    <?php endif; ?>
</body>

</html>
