<?php

use App\Libraries\RequestForm as RF;

/**
 * Informasi > Request
 *  - Tab "Request Saya"  : request yang ditujukan ke akun yang login
 *  - Tab "Kelola Request": khusus Administrator (buat, ubah, tutup, hapus, lihat jawaban)
 */
$tgl = static fn ($d) => $d ? date('d M Y H:i', strtotime($d)) : '-';
$lewat = static fn ($d) => $d && strtotime($d) < time();
?>
<?= view('RequestForm/_atas', ['judulHalaman' => 'Request']) ?>
<?= view('RequestForm/_gaya') ?>
<style>
    .rq-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(290px, 1fr)); gap: 16px; }
    .rq-card { border: 1px solid #e5e7eb; border-radius: 12px; padding: 18px; display: flex; flex-direction: column; gap: 10px; background: inherit; }
    .dark .rq-card { border-color: rgba(255, 255, 255, .1); }
    .rq-card.perlu { border-color: #7dd3fc; box-shadow: 0 0 0 3px rgba(4, 169, 245, .08); }
    .rq-card h3 { font-size: 16px; font-weight: 700; line-height: 1.35; }
    .rq-card .desk { font-size: 13px; color: #64748b; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    .dark .rq-card .desk { color: #94a3b8; }
    .rq-card .bawah { margin-top: auto; display: flex; gap: 8px; align-items: center; justify-content: space-between; padding-top: 6px; flex-wrap: wrap; }
    .rq-cat { font-size: 12px; padding: 8px 10px; border-radius: 8px; background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
    .dark .rq-cat { background: rgba(245, 158, 11, .1); color: #fcd34d; border-color: rgba(245, 158, 11, .3); }
</style>

<div class="rfo-page p-6">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <span class="rfo-title-ic is-sky"><i class="ti ti-forms"></i></span>
            <div>
                <h1 class="rfo-title">Request</h1>
                <p class="rfo-subtitle"><?= $isAdmin ? 'Buat form permintaan, pilih akun pengisi & halamannya, lalu pantau jawabannya' : 'Form permintaan dari Administrator yang perlu Anda isi' ?></p>
            </div>
        </div>
        <?php if ($tersedia && $isAdmin) : ?>
            <a href="<?= site_url('Request/kelola/buat') ?>" class="rfo-btn rfo-btn-blue"><i class="ti ti-plus"></i> Buat Request</a>
        <?php endif; ?>
    </div>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="rq-alert err"><i class="ti ti-alert-circle"></i> <?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <?php if (!$tersedia) : ?>
                <div class="rq-alert info">
                    <i class="ti ti-database-exclamation text-xl"></i>
                    <div><b>Tabel request belum ada di database.</b><br>
                        Jalankan file <code>database/d_request.sql</code> di database (lokal &amp; hosting), lalu buka halaman ini lagi.</div>
                </div>
            <?php else : ?>
                <?php $perlu = count(array_filter($saya, static fn ($r) => $r['status'] === 'aktif' && !$lewat($r['batas_waktu']) && (!$r['jawaban_id'] || $r['status_jawaban'] === 'revisi'))); ?>
                <div class="rq-tabs">
                    <div class="rq-tab active" data-tab="saya"><i class="ti ti-inbox"></i> Request Saya (<?= count($saya) ?>)
                        <?php if ($perlu) : ?><span class="rq-badge merah"><?= $perlu ?> belum diisi</span><?php endif; ?>
                    </div>
                    <?php if ($isAdmin) : ?>
                        <div class="rq-tab" data-tab="kelola"><i class="ti ti-settings-cog"></i> Kelola Request (<?= count($kelola) ?>)</div>
                    <?php endif; ?>
                </div>

                <!-- ===== REQUEST SAYA ===== -->
                <div class="rq-panel active" id="panel-saya">
                    <?php if (!$saya) : ?>
                        <div class="rq-kosong"><i class="ti ti-mailbox"></i>Belum ada request untuk akun Anda.</div>
                    <?php else : ?>
                        <div class="rq-grid">
                            <?php foreach ($saya as $r) :
                                $tutup  = $r['status'] !== 'aktif';
                                $telat  = $lewat($r['batas_waktu']);
                                $sj     = $r['status_jawaban'];
                                $harus  = !$tutup && !$telat && (!$r['jawaban_id'] || $sj === 'revisi');
                                if (!$r['jawaban_id']) {
                                    [$bt, $bw] = $tutup ? ['Ditutup', ''] : ($telat ? ['Lewat batas', 'merah'] : ['Belum diisi', 'biru']);
                                } else {
                                    [$bt, $bw] = ['terkirim' => ['Terkirim', 'biru'], 'disetujui' => ['Disetujui', 'hijau'], 'revisi' => ['Perlu revisi', 'kuning']][$sj] ?? ['Terkirim', 'biru'];
                                }
                                $bisaUbah = !$tutup && !$telat && $r['jawaban_id'] && ($sj === 'revisi' || ($sj === 'terkirim' && (int) $r['boleh_ubah'] === 1));
                            ?>
                                <div class="rq-card <?= $harus ? 'perlu' : '' ?>">
                                    <div class="flex items-start justify-between gap-2">
                                        <h3><?= esc($r['judul']) ?></h3>
                                        <span class="rq-badge <?= $bw ?>"><?= $bt ?></span>
                                    </div>
                                    <?php if ($r['deskripsi']) : ?><div class="desk"><?= esc($r['deskripsi']) ?></div><?php endif; ?>
                                    <div class="flex flex-wrap gap-2">
                                        <span class="rq-badge"><i class="ti ti-layout-sidebar"></i> <?= esc(RF::labelHalaman($r['halaman'])) ?></span>
                                        <span class="rq-badge <?= $telat ? 'merah' : '' ?>"><i class="ti ti-clock"></i> <?= $r['batas_waktu'] ? 'Batas ' . $tgl($r['batas_waktu']) : 'Tanpa batas' ?></span>
                                    </div>
                                    <?php if ($sj === 'revisi' && $r['catatan_admin']) : ?>
                                        <div class="rq-cat"><b>Catatan revisi:</b> <?= nl2br(esc($r['catatan_admin'])) ?></div>
                                    <?php endif; ?>
                                    <div class="bawah">
                                        <span class="rq-meta"><?= $r['dikirim_at'] ? 'Dikirim ' . $tgl($r['dikirim_at']) : '' ?></span>
                                        <?php if ($harus) : ?>
                                            <a href="<?= site_url('Request/isi/' . $r['id']) ?>" class="rq-btn biru"><i class="ti ti-pencil"></i> <?= $sj === 'revisi' ? 'Perbaiki' : 'Isi Sekarang' ?></a>
                                        <?php elseif ($bisaUbah) : ?>
                                            <a href="<?= site_url('Request/isi/' . $r['id']) ?>" class="rq-btn"><i class="ti ti-edit"></i> Ubah Jawaban</a>
                                        <?php else : ?>
                                            <a href="<?= site_url('Request/isi/' . $r['id']) ?>" class="rq-btn"><i class="ti ti-eye"></i> Lihat</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if ($isAdmin) : ?>
                    <!-- ===== KELOLA REQUEST (Administrator) ===== -->
                    <div class="rq-panel" id="panel-kelola">
                        <?php if (!$kelola) : ?>
                            <div class="rq-kosong"><i class="ti ti-forms"></i>Belum ada request.<br><br>
                                <a href="<?= site_url('Request/kelola/buat') ?>" class="rq-btn biru"><i class="ti ti-plus"></i> Buat Request Pertama</a>
                            </div>
                        <?php else : ?>
                            <div class="rq-tbl-wrap">
                                <table class="rq-tbl" id="tblKelola">
                                    <thead>
                                        <tr>
                                            <th>Judul</th>
                                            <th>Halaman</th>
                                            <th>Pengisian</th>
                                            <th>Batas Waktu</th>
                                            <th>Status</th>
                                            <th style="text-align:right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($kelola as $r) :
                                            $persen = $r['jml_target'] ? round($r['jml_terisi'] / $r['jml_target'] * 100) : 0;
                                            $telat = $lewat($r['batas_waktu']);
                                        ?>
                                            <tr>
                                                <td>
                                                    <b><?= esc($r['judul']) ?></b>
                                                    <div class="rq-meta"><?= $r['jml_isian'] ?> pertanyaan · dibuat <?= esc($r['pembuat'] ?? '-') ?>, <?= $tgl($r['created_at']) ?></div>
                                                </td>
                                                <td><span class="rq-badge"><?= esc(RF::labelHalaman($r['halaman'])) ?></span></td>
                                                <td style="min-width:150px">
                                                    <div class="flex items-center gap-2">
                                                        <div class="rq-progress flex-1"><span style="width:<?= $persen ?>%"></span></div>
                                                        <b class="text-sm"><?= $r['jml_terisi'] ?>/<?= $r['jml_target'] ?></b>
                                                    </div>
                                                    <div class="rq-meta mt-1">
                                                        <?php if (!empty($r['jml_status']['disetujui'])) : ?><?= $r['jml_status']['disetujui'] ?> disetujui · <?php endif; ?>
                                                        <?php if (!empty($r['jml_status']['revisi'])) : ?><?= $r['jml_status']['revisi'] ?> revisi · <?php endif; ?>
                                                        <?= max(0, $r['jml_target'] - $r['jml_terisi']) ?> belum mengisi
                                                    </div>
                                                </td>
                                                <td class="<?= $telat ? 'text-red-500' : '' ?>" style="white-space:nowrap"><?= $r['batas_waktu'] ? $tgl($r['batas_waktu']) : '<span class="rq-meta">Tanpa batas</span>' ?></td>
                                                <td>
                                                    <?php if ($r['status'] !== 'aktif') : ?>
                                                        <span class="rq-badge">Ditutup</span>
                                                    <?php elseif ($telat) : ?>
                                                        <span class="rq-badge merah">Lewat batas</span>
                                                    <?php else : ?>
                                                        <span class="rq-badge hijau">Aktif</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="flex gap-1 justify-end flex-wrap">
                                                        <a class="rq-btn kecil biru" href="<?= site_url('Request/kelola/jawaban/' . $r['id']) ?>"><i class="ti ti-list-check"></i> Jawaban</a>
                                                        <a class="rq-btn kecil" href="<?= site_url('Request/isi/' . $r['id']) ?>" title="Pratinjau form"><i class="ti ti-eye"></i></a>
                                                        <a class="rq-btn kecil" href="<?= site_url('Request/kelola/ubah/' . $r['id']) ?>" title="Ubah"><i class="ti ti-edit"></i></a>
                                                        <a class="rq-btn kecil kuning" href="<?= site_url('Request/kelola/status/' . $r['id']) ?>" title="<?= $r['status'] === 'aktif' ? 'Tutup request' : 'Buka kembali' ?>">
                                                            <i class="ti <?= $r['status'] === 'aktif' ? 'ti-lock' : 'ti-lock-open' ?>"></i>
                                                        </a>
                                                        <button type="button" class="rq-btn kecil merah" title="Hapus"
                                                            onclick="hapusRequest(<?= (int) $r['id'] ?>, <?= esc(json_encode($r['judul']), 'attr') ?>, <?= (int) $r['jml_terisi'] ?>)"><i class="ti ti-trash"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function pilihTab(nama) {
        document.querySelectorAll('.rq-tab').forEach(t => t.classList.toggle('active', t.dataset.tab === nama));
        document.querySelectorAll('.rq-panel').forEach(p => p.classList.toggle('active', p.id === 'panel-' + nama));
    }
    document.querySelectorAll('.rq-tab').forEach(t => t.addEventListener('click', () => {
        pilihTab(t.dataset.tab);
        history.replaceState(null, '', '#' + t.dataset.tab);
    }));
    if (location.hash === '#kelola' && document.getElementById('panel-kelola')) pilihTab('kelola');

    function hapusRequest(id, judul, terisi) {
        Swal.fire({
            title: 'Hapus request "' + judul + '"?',
            text: terisi ? terisi + ' jawaban (termasuk file upload) ikut terhapus.' : 'Request ini belum punya jawaban.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal'
        }).then(r => {
            if (r.isConfirmed) location.href = '<?= site_url('Request/kelola/hapus/') ?>' + id;
        });
    }
</script>
<?= view('RequestForm/_bawah') ?>
