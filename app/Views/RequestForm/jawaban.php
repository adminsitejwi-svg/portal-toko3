<?php

use App\Libraries\RequestForm as RF;

/**
 * Jawaban request (khusus Administrator): ringkasan, tabel + export Excel, detail, review.
 */
$tgl     = static fn ($d) => $d ? date('d M Y H:i', strtotime($d)) : '-';
$isian   = array_values(array_filter($fields, static fn ($f) => $f['tipe'] !== 'bagian'));
$jmlStat = ['terkirim' => 0, 'disetujui' => 0, 'revisi' => 0];
foreach ($jawaban as $j) {
    if ($j['target']) {
        $jmlStat[$j['status']]++;
    }
}
$jmlTerisi = array_sum($jmlStat);
$badge = ['terkirim' => ['Terkirim', 'biru'], 'disetujui' => ['Disetujui', 'hijau'], 'revisi' => ['Revisi', 'kuning']];
?>
<?= view('RequestForm/_atas', ['judulHalaman' => $judulHalaman]) ?>
<?= view('RequestForm/_gaya') ?>
<style>
    .rj-stat { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 14px; margin-bottom: 20px; }
    .rj-stat > div { border: 1px solid #e5e7eb; border-radius: 12px; padding: 14px 16px; }
    .dark .rj-stat > div { border-color: rgba(255, 255, 255, .1); }
    .rj-stat b { display: block; font-size: 24px; font-weight: 800; }
    .rj-detail dt { font-size: 13px; font-weight: 700; margin-top: 14px; }
    .rj-detail dd { font-size: 14px; margin-top: 4px; padding: 8px 12px; background: #f8fafc; border-radius: 8px; white-space: pre-wrap; word-break: break-word; }
    .dark .rj-detail dd { background: rgba(255, 255, 255, .04); }
    .rj-detail dd.kosong { color: #94a3b8; font-style: italic; }
    .rj-detail h4 { margin-top: 18px; padding-bottom: 6px; border-bottom: 2px solid #04a9f5; font-weight: 700; }
    #tblJawaban td { font-size: 13px; }
    #tblJawaban td.isi { max-width: 220px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .dark .dataTables_wrapper, .dark .dataTables_wrapper .dataTables_info, .dark .dataTables_wrapper label { color: #bfc8d6 !important; }
</style>

<div class="rfo-page p-6">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <span class="rfo-title-ic is-sky"><i class="ti ti-list-check"></i></span>
            <div>
                <h1 class="rfo-title"><?= esc($req['judul']) ?></h1>
                <p class="rfo-subtitle">Jawaban request · <?= esc(RF::labelHalaman($req['halaman'])) ?> · <?= $req['batas_waktu'] ? 'Batas ' . $tgl($req['batas_waktu']) : 'Tanpa batas waktu' ?>
                    · <?= $req['status'] === 'aktif' ? 'Aktif' : 'Ditutup' ?></p>
            </div>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a href="<?= site_url('Request') ?>#kelola" class="rq-btn"><i class="ti ti-arrow-left"></i> Kembali</a>
            <a href="<?= site_url('Request/isi/' . $req['id']) ?>" class="rq-btn"><i class="ti ti-eye"></i> Lihat Form</a>
            <a href="<?= site_url('Request/kelola/ubah/' . $req['id']) ?>" class="rq-btn biru"><i class="ti ti-edit"></i> Ubah Request</a>
        </div>
    </div>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="rq-alert err"><i class="ti ti-alert-circle"></i> <?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="rj-stat">
        <div><span class="rq-meta">Akun pengisi</span><b><?= $jmlTarget ?></b></div>
        <div><span class="rq-meta">Sudah mengisi</span><b class="text-sky-500"><?= $jmlTerisi ?></b></div>
        <div><span class="rq-meta">Disetujui</span><b class="text-emerald-500"><?= $jmlStat['disetujui'] ?></b></div>
        <div><span class="rq-meta">Perlu revisi</span><b class="text-amber-500"><?= $jmlStat['revisi'] ?></b></div>
        <div><span class="rq-meta">Belum mengisi</span><b class="text-red-500"><?= count($belum) ?></b></div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="rq-tabs">
                <div class="rq-tab active" data-tab="jawaban"><i class="ti ti-inbox"></i> Jawaban (<?= count($jawaban) ?>)</div>
                <div class="rq-tab" data-tab="belum"><i class="ti ti-user-x"></i> Belum mengisi (<?= count($belum) ?>)</div>
            </div>

            <div class="rq-panel active" id="panel-jawaban">
                <?php if (!$jawaban) : ?>
                    <div class="rq-kosong"><i class="ti ti-mailbox"></i>Belum ada yang mengisi request ini.</div>
                <?php else : ?>
                    <div class="rq-tbl-wrap">
                        <table class="rq-tbl display" id="tblJawaban" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Akun</th>
                                    <th>Status</th>
                                    <th>Dikirim</th>
                                    <?php foreach ($isian as $f) : ?><th><?= esc($f['label']) ?></th><?php endforeach; ?>
                                    <th class="noexport" style="text-align:right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($jawaban as $j) : [$bt, $bw] = $badge[$j['status']]; ?>
                                    <tr>
                                        <td><b><?= esc($j['username'] ?? '(akun terhapus)') ?></b>
                                            <?php if (!$j['target']) : ?><br><span class="rq-badge" title="Akun ini sudah tidak dipilih sebagai pengisi">bukan pengisi lagi</span><?php endif; ?></td>
                                        <td><span class="rq-badge <?= $bw ?>"><?= $bt ?></span></td>
                                        <td style="white-space:nowrap" data-order="<?= esc($j['diubah_at'] ?: $j['dikirim_at'], 'attr') ?>"><?= $tgl($j['diubah_at'] ?: $j['dikirim_at']) ?></td>
                                        <?php foreach ($isian as $f) : $t = RF::teksJawaban($f, $j['isi'][$f['id']] ?? null); ?>
                                            <td class="isi" title="<?= esc($t, 'attr') ?>"><?= esc($t) ?></td>
                                        <?php endforeach; ?>
                                        <td style="text-align:right;white-space:nowrap">
                                            <button type="button" class="rq-btn kecil biru" onclick="bukaDetail(<?= (int) $j['id'] ?>)"><i class="ti ti-eye"></i> Detail</button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <div class="rq-panel" id="panel-belum">
                <?php if (!$belum) : ?>
                    <div class="rq-kosong"><i class="ti ti-circle-check"></i>Semua akun pengisi sudah mengisi.</div>
                <?php else : ?>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($belum as $b) : ?>
                            <span class="rq-badge merah" style="font-size:13px;padding:6px 12px"><i class="ti ti-user"></i> <?= esc($b['username'] ?? '(akun terhapus)') ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- ===== DETAIL + REVIEW ===== -->
<?php foreach ($jawaban as $j) : [$bt, $bw] = $badge[$j['status']]; ?>
    <div class="rq-modal" id="detail-<?= (int) $j['id'] ?>">
        <form class="rq-box" method="post" action="<?= site_url('Request/kelola/review') ?>">
            <?= csrf_field() ?>
            <input type="hidden" name="jawaban_id" value="<?= (int) $j['id'] ?>">
            <div class="rq-box-head">
                <h3><i class="ti ti-user"></i> <?= esc($j['username'] ?? '(akun terhapus)') ?> <span class="rq-badge <?= $bw ?>"><?= $bt ?></span></h3>
                <button type="button" class="rb-x rq-btn kecil" onclick="tutupModal(this)"><i class="ti ti-x"></i></button>
            </div>
            <div class="rq-box-body">
                <div class="rq-meta">Dikirim <?= $tgl($j['dikirim_at']) ?><?= $j['diubah_at'] ? ' · diubah ' . $tgl($j['diubah_at']) : '' ?>
                    <?= $j['direview_at'] ? ' · direview ' . esc($j['reviewer'] ?? '-') . ', ' . $tgl($j['direview_at']) : '' ?></div>
                <dl class="rj-detail">
                    <?php foreach ($fields as $f) :
                        if ($f['tipe'] === 'bagian') : ?>
                            <h4><?= esc($f['label']) ?></h4>
                        <?php continue;
                        endif;
                        $v = $j['isi'][$f['id']] ?? null; ?>
                        <dt><?= esc($f['label']) ?></dt>
                        <?php if ($f['tipe'] === 'file') : ?>
                            <?php if (is_array($v) && !empty($v['file'])) : ?>
                                <dd><i class="ti ti-paperclip"></i>
                                    <a href="<?= site_url('Request/file/' . $j['id'] . '/' . $f['id']) ?>" target="_blank" class="text-sky-500 font-semibold"><?= esc($v['nama'] ?? 'file') ?></a>
                                    · <a href="<?= site_url('Request/file/' . $j['id'] . '/' . $f['id']) ?>?unduh=1" class="text-sky-500">Unduh</a></dd>
                            <?php else : ?>
                                <dd class="kosong">Tidak ada file</dd>
                            <?php endif; ?>
                        <?php else : $t = RF::teksJawaban($f, $v); ?>
                            <dd class="<?= $t === '' ? 'kosong' : '' ?>"><?= $t === '' ? 'Tidak dijawab' : esc($t) ?></dd>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </dl>

                <div class="rq-field" style="margin-top:20px">
                    <label class="rq-label">Catatan untuk pengisi <span class="rq-meta">(wajib bila minta revisi)</span></label>
                    <textarea class="rq-input" name="catatan" rows="2" placeholder="Contoh: Foto perangkat kurang jelas, mohon upload ulang"><?= esc($j['catatan_admin'] ?? '') ?></textarea>
                </div>
            </div>
            <div class="rq-box-foot">
                <?php if ($j['status'] !== 'terkirim') : ?>
                    <button type="submit" name="status" value="terkirim" class="rq-btn" style="margin-right:auto">Batalkan review</button>
                <?php endif; ?>
                <button type="submit" name="status" value="revisi" class="rq-btn kuning"><i class="ti ti-arrow-back-up"></i> Minta Revisi</button>
                <button type="submit" name="status" value="disetujui" class="rq-btn hijau"><i class="ti ti-check"></i> Setujui</button>
            </div>
        </form>
    </div>
<?php endforeach; ?>

<script>
    function pilihTab(nama) {
        document.querySelectorAll('.rq-tab').forEach(t => t.classList.toggle('active', t.dataset.tab === nama));
        document.querySelectorAll('.rq-panel').forEach(p => p.classList.toggle('active', p.id === 'panel-' + nama));
    }
    document.querySelectorAll('.rq-tab').forEach(t => t.addEventListener('click', () => pilihTab(t.dataset.tab)));

    function bukaDetail(id) {
        document.getElementById('detail-' + id).classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function tutupModal(el) {
        el.closest('.rq-modal').classList.remove('show');
        document.body.style.overflow = '';
    }
    document.querySelectorAll('.rq-modal').forEach(m => m.addEventListener('click', e => { if (e.target === m) tutupModal(m.firstElementChild); }));
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') document.querySelectorAll('.rq-modal.show').forEach(m => tutupModal(m.firstElementChild));
    });
    // minta revisi wajib ada catatan
    document.querySelectorAll('.rq-modal form').forEach(f => f.addEventListener('submit', e => {
        if (e.submitter && e.submitter.value === 'revisi' && !f.catatan.value.trim()) {
            e.preventDefault();
            f.catatan.focus();
            Swal.fire({ icon: 'info', title: 'Tulis catatan revisi', text: 'Supaya pengisi tahu apa yang harus diperbaiki.', confirmButtonColor: '#04a9f5' });
        }
    }));

    if (window.jQuery && jQuery.fn.DataTable && document.getElementById('tblJawaban')) {
        jQuery('#tblJawaban').DataTable({
            order: [[2, 'desc']],
            pageLength: 25,
            scrollX: true,
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excelHtml5',
                text: '<i class="ti ti-file-spreadsheet"></i> Export Excel',
                title: <?= json_encode('Jawaban Request - ' . $req['judul'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?>,
                exportOptions: { columns: ':not(.noexport)' }
            }],
            columnDefs: [{ targets: 'noexport', orderable: false }],
            language: {
                search: 'Cari:', info: 'Menampilkan _START_–_END_ dari _TOTAL_ jawaban', infoEmpty: 'Tidak ada data',
                zeroRecords: 'Tidak ditemukan', paginate: { previous: '‹', next: '›' }
            }
        });
    }
</script>
<?= view('RequestForm/_bawah') ?>
