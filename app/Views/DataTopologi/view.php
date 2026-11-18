<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title><?= esc($row['nama']) ?> - Data Topologi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        * { box-sizing: border-box; }
        html, body { margin: 0; height: 100%; overflow: hidden; font-family: Inter, system-ui, sans-serif; background: #f1f5f9; }
        .tp-bar { height: 52px; display: flex; align-items: center; gap: 10px; padding: 0 14px; background: #1c2434; color: #e2e8f0; }
        .tp-back, .tp-btn { display: inline-flex; align-items: center; gap: 6px; color: #e2e8f0; text-decoration: none; font-size: 13px; font-weight: 600; padding: 7px 12px; border-radius: 8px; background: rgba(255, 255, 255, .08); border: 0; cursor: pointer; white-space: nowrap; font-family: inherit; }
        .tp-back:hover, .tp-btn:hover { background: rgba(255, 255, 255, .16); }
        .tp-btn-edit { background: #04a9f5; color: #fff; }
        .tp-btn-edit:hover { background: #04a9f5; filter: brightness(1.08); }
        .tp-name { min-width: 0; flex: 1; display: flex; flex-direction: column; line-height: 1.25; }
        .tp-name b { font-size: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .tp-name small { font-size: 11.5px; color: #94a3b8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .tp-mode { display: inline-flex; background: rgba(255, 255, 255, .08); border-radius: 8px; padding: 3px; }
        .tp-mode button { border: 0; background: transparent; color: #cbd5e1; font: 600 12.5px Inter, sans-serif; padding: 5px 10px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; gap: 5px; }
        .tp-mode button.on { background: #fff; color: #1c2434; }
        .tp-stage { position: relative; height: calc(100% - 52px); background: #fff; }
        #tpViewer { position: absolute; inset: 0; }
        #tpViewer .geDiagramContainer { width: 100% !important; height: 100% !important; }
        #tpGambar { position: absolute; inset: 0; overflow: auto; display: none; background: #fff; cursor: grab; }
        #tpGambar img { display: block; margin: 20px auto; max-width: none; transform-origin: top left; }
        .tp-zoom { position: absolute; right: 16px; bottom: 16px; display: none; gap: 4px; background: #1c2434; border-radius: 10px; padding: 4px; box-shadow: 0 6px 18px rgba(0, 0, 0, .18); }
        .tp-zoom button { border: 0; background: transparent; color: #e2e8f0; width: 34px; height: 32px; border-radius: 7px; cursor: pointer; font-size: 17px; }
        .tp-zoom button:hover { background: rgba(255, 255, 255, .14); }
        .tp-zoom span { color: #cbd5e1; font-size: 12px; min-width: 46px; display: inline-flex; align-items: center; justify-content: center; }
        .tp-empty { position: absolute; inset: 0; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px; color: #64748b; text-align: center; padding: 20px; }
        .tp-empty i { font-size: 46px; color: #94a3b8; }
        .tp-empty h3 { margin: 0; color: #1e293b; font-size: 17px; }
        .tp-hint { position: absolute; left: 16px; bottom: 16px; font-size: 12px; color: #64748b; background: rgba(255, 255, 255, .9); padding: 6px 10px; border-radius: 8px; border: 1px solid #e2e8f0; }
        @media (max-width: 720px) {
            .tp-back span, .tp-btn span, .tp-mode span, .tp-name small, .tp-hint { display: none; }
        }
    </style>
</head>

<body>
    <?php
    $adaXml    = trim((string) ($row['xml'] ?? '')) !== '';
    $adaGambar = ! empty($row['gambar']) && is_file(FCPATH . $row['gambar']);
    $waktu     = $row['updated_at'] ?: $row['created_at'];
    $info      = 'Diperbarui ' . ($waktu ? date('d-m-Y H:i', strtotime($waktu)) : '-')
        . (! empty($row['updated_by']) ? ' oleh ' . $row['updated_by'] : '')
        . (! empty($row['keterangan']) ? ' · ' . $row['keterangan'] : '');
    ?>
    <div class="tp-bar">
        <a href="<?= site_url('DataTopologi') ?>" class="tp-back"><i class="ti ti-arrow-left"></i> <span>Data Topologi</span></a>
        <div class="tp-name" title="<?= esc($row['nama'] . ' — ' . $info, 'attr') ?>">
            <b><i class="ti ti-eye"></i> <?= esc($row['nama']) ?></b>
            <small><?= esc($info) ?></small>
        </div>
        <?php if ($adaXml && $adaGambar) : ?>
            <div class="tp-mode" role="group" aria-label="Mode tampilan">
                <button type="button" id="modeInteraktif" class="on" title="Zoom, geser, pilih halaman & layer"><i class="ti ti-hand-finger"></i> <span>Interaktif</span></button>
                <button type="button" id="modeGambar" title="Tampilkan gambar PNG"><i class="ti ti-photo"></i> <span>Gambar</span></button>
            </div>
        <?php endif; ?>
        <button type="button" class="tp-btn" id="btnLayar" title="Layar penuh"><i class="ti ti-maximize"></i> <span>Layar Penuh</span></button>
        <?php if ($adaGambar) : ?>
            <a href="<?= site_url('DataTopologi/download/' . $row['id']) ?>" class="tp-btn"><i class="ti ti-download"></i> <span>PNG</span></a>
        <?php endif; ?>
        <a href="<?= site_url('DataTopologi/editor/' . $row['id']) ?>" class="tp-btn tp-btn-edit"><i class="ti ti-pencil"></i> <span>Edit</span></a>
    </div>

    <div class="tp-stage" id="tpStage">
        <?php if (! $adaXml && ! $adaGambar) : ?>
            <div class="tp-empty">
                <i class="ti ti-topology-star-3"></i>
                <h3>Topologi ini belum digambar</h3>
                <div>Klik tombol <b>Edit</b> untuk mulai menggambar di draw.io.</div>
            </div>
        <?php else : ?>
            <div id="tpViewer"></div>
            <div id="tpGambar">
                <?php if ($adaGambar) : ?>
                    <img id="tpImg" src="<?= base_url($row['gambar']) ?>?v=<?= esc((string) strtotime((string) $waktu), 'attr') ?>" alt="<?= esc($row['nama'], 'attr') ?>" draggable="false">
                <?php endif; ?>
            </div>
            <div class="tp-zoom" id="tpZoom">
                <button type="button" data-z="-1" title="Perkecil"><i class="ti ti-minus"></i></button>
                <span id="tpZoomVal">100%</span>
                <button type="button" data-z="1" title="Perbesar"><i class="ti ti-plus"></i></button>
                <button type="button" data-z="0" title="Pas layar"><i class="ti ti-arrows-minimize"></i></button>
            </div>
            <div class="tp-hint" id="tpHint">Scroll / tombol di pojok kiri atas untuk zoom · tarik untuk menggeser</div>
        <?php endif; ?>
    </div>

    <?php if ($adaXml || $adaGambar) : ?>
        <!-- Viewer draw.io dari server portal (public/drawio), tidak butuh internet -->
        <script>
            window.DRAWIO_BASE_URL = "<?= rtrim(base_url('drawio'), '/') ?>";
        </script>
        <?php if ($adaXml) : ?>
            <script src="<?= base_url('drawio/js/viewer-static.min.js') ?>"></script>
        <?php endif; ?>
        <script>
            const XML = <?= json_encode((string) ($row['xml'] ?? ''), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
            const viewerEl = document.getElementById('tpViewer');
            const gambarEl = document.getElementById('tpGambar');
            const zoomEl = document.getElementById('tpZoom');
            const hintEl = document.getElementById('tpHint');
            const img = document.getElementById('tpImg');
            let viewerOk = false;
            let viewerGraph = null;

            // paskan diagram ke layar (maks. 150%) dan taruh di tengah
            function pasDiagram() {
                if (!viewerGraph) return;
                const g = viewerGraph;
                g.maxFitScale = 1.5;
                g.fit(30);
                g.center(true, true);
            }

            function viewerGagal() {
                document.getElementById('tpStage').innerHTML =
                    '<div class="tp-empty"><i class="ti ti-alert-triangle"></i><h3>Topologi tidak bisa ditampilkan</h3>' +
                    '<div>Buka lewat tombol <b>Edit</b>, lalu simpan ulang supaya gambarnya dibuat lagi.</div></div>';
            }

            // ---- mode interaktif: viewer draw.io (zoom, geser, halaman, layer) ----
            function tampilViewer() {
                if (!XML || typeof GraphViewer === 'undefined') return false;
                try {
                    if (!viewerOk) {
                        viewerEl.className = 'mxgraph';
                        viewerEl.setAttribute('data-mxgraph', JSON.stringify({
                            xml: XML,
                            toolbar: 'zoom pages layers',
                            'toolbar-nohide': true,
                            nav: true,
                            resize: false,
                            'auto-fit': true,
                            center: true,
                            highlight: '#04a9f5'
                        }));
                        GraphViewer.createViewerForElement(viewerEl, function(v) {
                            viewerGraph = v && v.graph;
                            if (!viewerGraph) return;
                            // area diagram memenuhi layar, lalu diagram dipaskan & ditaruh di tengah
                            // (kontainer diagram = #tpViewer; viewer memberi ukuran tetap, jadi ditimpa)
                            Object.assign(viewerEl.style, {
                                position: 'absolute',
                                left: '0',
                                right: '0',
                                top: '0',
                                bottom: '0',
                                width: 'auto',
                                height: 'auto',
                                maxWidth: 'none',
                                overflow: 'hidden'
                            });
                            const zo = viewerEl.querySelector('[title="Zoom Out"]');
                            if (zo && zo.parentNode) Object.assign(zo.parentNode.style, {
                                width: 'auto',
                                left: '0',
                                right: '0',
                            });
                            pasDiagram();
                            window.addEventListener('resize', pasDiagram);
                        });
                        viewerOk = true;
                        // kalau setelah 3 detik diagram tidak tergambar, pakai gambar PNG
                        setTimeout(() => {
                            if (!viewerEl.querySelector('svg')) {
                                viewerOk = false;
                                if (!tampilGambar()) viewerGagal();
                            }
                        }, 3000);
                        // zoom pakai scroll mouse
                        viewerEl.addEventListener('wheel', function(e) {
                            if (!viewerGraph) return;
                            e.preventDefault();
                            if (e.deltaY < 0) viewerGraph.zoomIn();
                            else viewerGraph.zoomOut();
                        }, {
                            passive: false
                        });
                    }
                    viewerEl.style.display = '';
                    gambarEl.style.display = 'none';
                    zoomEl.style.display = 'none';
                    if (hintEl) hintEl.textContent = 'Tombol di pojok kiri atas: zoom, halaman & layer · tarik untuk menggeser';
                    setMode('interaktif');
                    return true;
                } catch (e) {
                    console.error(e);
                    return false;
                }
            }

            // ---- mode gambar: PNG dengan zoom sendiri ----
            let skala = 1;

            function setSkala(s) {
                if (!img) return;
                skala = Math.min(4, Math.max(0.1, s));
                img.style.width = Math.round(img.naturalWidth * skala) + 'px';
                document.getElementById('tpZoomVal').textContent = Math.round(skala * 100) + '%';
            }

            function skalaPas() {
                if (!img || !img.naturalWidth) return 1;
                return Math.min(1, (gambarEl.clientWidth - 40) / img.naturalWidth, (gambarEl.clientHeight - 40) / img.naturalHeight);
            }

            function tampilGambar() {
                if (!img) return false;
                viewerEl.style.display = 'none';
                gambarEl.style.display = 'block';
                zoomEl.style.display = 'flex';
                if (hintEl) hintEl.textContent = 'Scroll + Ctrl / tombol di pojok kanan bawah untuk zoom · tarik untuk menggeser';
                const mulai = () => setSkala(skalaPas());
                if (img.complete) mulai();
                else img.addEventListener('load', mulai, {
                    once: true
                });
                setMode('gambar');
                return true;
            }

            if (img) {
                zoomEl.addEventListener('click', e => {
                    const b = e.target.closest('button');
                    if (!b) return;
                    const z = Number(b.dataset.z);
                    setSkala(z === 0 ? skalaPas() : skala * (z > 0 ? 1.25 : 0.8));
                });
                gambarEl.addEventListener('wheel', e => {
                    if (!e.ctrlKey) return;
                    e.preventDefault();
                    setSkala(skala * (e.deltaY < 0 ? 1.1 : 0.9));
                }, {
                    passive: false
                });
                // tarik untuk menggeser
                let drag = null;
                gambarEl.addEventListener('mousedown', e => {
                    drag = {
                        x: e.clientX,
                        y: e.clientY,
                        l: gambarEl.scrollLeft,
                        t: gambarEl.scrollTop
                    };
                    gambarEl.style.cursor = 'grabbing';
                });
                window.addEventListener('mouseup', () => {
                    drag = null;
                    gambarEl.style.cursor = 'grab';
                });
                window.addEventListener('mousemove', e => {
                    if (!drag) return;
                    gambarEl.scrollLeft = drag.l - (e.clientX - drag.x);
                    gambarEl.scrollTop = drag.t - (e.clientY - drag.y);
                });
            }

            function setMode(m) {
                const a = document.getElementById('modeInteraktif');
                const b = document.getElementById('modeGambar');
                if (a) a.classList.toggle('on', m === 'interaktif');
                if (b) b.classList.toggle('on', m === 'gambar');
            }

            const tA = document.getElementById('modeInteraktif');
            const tB = document.getElementById('modeGambar');
            if (tA) tA.addEventListener('click', () => {
                if (!tampilViewer()) tampilGambar();
            });
            if (tB) tB.addEventListener('click', tampilGambar);

            // layar penuh
            document.getElementById('btnLayar').addEventListener('click', () => {
                const el = document.documentElement;
                if (document.fullscreenElement) document.exitFullscreen();
                else if (el.requestFullscreen) el.requestFullscreen();
            });

            // awal: viewer interaktif; kalau gagal, pakai gambar PNG
            if (!tampilViewer() && !tampilGambar()) viewerGagal();
        </script>
    <?php endif; ?>
    <?php if (!session()->get('logged_in')) : ?>
        <script>
            window.location.href = "<?= base_url('/login') ?>";
        </script>
    <?php endif; ?>
</body>

</html>
