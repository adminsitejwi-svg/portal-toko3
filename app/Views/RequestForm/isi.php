<?php

use App\Libraries\RequestForm as RF;

/**
 * Isi request (akun pengisi) / pratinjau (Administrator yang bukan pengisi).
 * Kalau tidak bisa kirim, form tampil hanya-baca dengan jawaban yang tersimpan.
 */
$err  = session()->getFlashdata('errors_field') ?? [];
$baca = !$bisaKirim && !$pratinjau; // pratinjau admin: tampil seperti form kosong, tanpa tombol kirim
$tgl  = static fn ($d) => $d ? date('d M Y H:i', strtotime($d)) : '-';
$sj   = $jawaban['status'] ?? null;
$no   = 0;
?>
<?= view('RequestForm/_atas', ['judulHalaman' => $judulHalaman]) ?>
<?= view('RequestForm/_gaya') ?>
<style>
    .ri-wrap { max-width: 780px; margin: 0 auto; }
    .ri-head { border-top: 8px solid #04a9f5; }
    .ri-head h1 { font-size: 26px; font-weight: 800; line-height: 1.25; }
    .ri-q { border-left: 4px solid transparent; }
    .ri-q.salah { border-left-color: #ef4444; }
    .ri-q h3 { font-size: 15px; font-weight: 600; margin-bottom: 4px; }
    .ri-q .wajib { color: #ef4444; }
    .ri-q .ket { font-size: 13px; color: #64748b; margin-bottom: 10px; white-space: pre-line; }
    .dark .ri-q .ket { color: #94a3b8; }
    .ri-q .pesan { color: #ef4444; font-size: 13px; margin-top: 8px; display: flex; gap: 4px; align-items: center; }
    .ri-bagian { background: #04a9f5; color: #fff; border-radius: 10px 10px 0 0; padding: 12px 20px; margin-bottom: 0; }
    .ri-pilih { display: flex; align-items: center; gap: 10px; padding: 7px 0; font-size: 14px; cursor: pointer; }
    .ri-pilih input { width: 18px; height: 18px; accent-color: #04a9f5; flex: none; }
    .ri-skala { display: flex; align-items: flex-end; gap: 4px; flex-wrap: wrap; }
    .ri-skala label { display: flex; flex-direction: column; align-items: center; gap: 6px; min-width: 38px; font-size: 13px; cursor: pointer; }
    .ri-skala input { width: 18px; height: 18px; accent-color: #04a9f5; }
    .ri-skala .ujung { font-size: 12px; color: #64748b; padding: 0 8px 2px; max-width: 120px; }
    .ri-file { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 10px; font-size: 13px; margin-bottom: 8px; flex-wrap: wrap; }
    .dark .ri-file { border-color: rgba(255, 255, 255, .1); }
    .ri-file a { color: #04a9f5; font-weight: 600; word-break: break-all; }
    .ri-baca { font-size: 14px; padding: 8px 12px; background: #f8fafc; border-radius: 8px; white-space: pre-wrap; word-break: break-word; min-height: 38px; }
    .dark .ri-baca { background: rgba(255, 255, 255, .04); }
    .ri-baca.kosong { color: #94a3b8; font-style: italic; }
</style>

<div class="rfo-page p-6">
    <div class="ri-wrap">
        <div class="flex justify-between items-center mb-4 gap-2 flex-wrap">
            <a href="<?= site_url('Request') ?><?= $pratinjau ? '#kelola' : '' ?>" class="rq-btn"><i class="ti ti-arrow-left"></i> Kembali</a>
            <?php if ($pratinjau) : ?>
                <span class="rq-badge kuning"><i class="ti ti-eye"></i> Mode pratinjau Administrator</span>
            <?php endif; ?>
        </div>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="rq-alert err"><i class="ti ti-alert-circle"></i> <?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <?php if ($sj === 'revisi') : ?>
            <div class="rq-alert warn"><i class="ti ti-arrow-back-up"></i>
                <div><b>Administrator meminta revisi.</b><?php if ($jawaban['catatan_admin']) : ?><br><?= nl2br(esc($jawaban['catatan_admin'])) ?><?php endif; ?></div>
            </div>
        <?php elseif ($sj === 'disetujui') : ?>
            <div class="rq-alert ok"><i class="ti ti-circle-check"></i>
                <div><b>Jawaban Anda sudah disetujui.</b><?php if ($jawaban['catatan_admin']) : ?><br><?= nl2br(esc($jawaban['catatan_admin'])) ?><?php endif; ?></div>
            </div>
        <?php elseif ($baca && !$pratinjau) : ?>
            <div class="rq-alert info"><i class="ti ti-info-circle"></i> <?= esc($alasan) ?></div>
        <?php elseif ($sj === 'terkirim') : ?>
            <div class="rq-alert info"><i class="ti ti-info-circle"></i> Jawaban sudah dikirim <?= $tgl($jawaban['diubah_at'] ?: $jawaban['dikirim_at']) ?>. Anda masih bisa mengubahnya.</div>
        <?php endif; ?>

        <div class="card ri-head">
            <div class="card-body">
                <h1><?= esc($req['judul']) ?></h1>
                <?php if ($req['deskripsi']) : ?>
                    <p class="mt-3 text-sm" style="white-space:pre-line"><?= esc($req['deskripsi']) ?></p>
                <?php endif; ?>
                <div class="flex flex-wrap gap-2 mt-4">
                    <span class="rq-badge"><i class="ti ti-layout-sidebar"></i> <?= esc(RF::labelHalaman($req['halaman'])) ?></span>
                    <span class="rq-badge"><i class="ti ti-clock"></i> <?= $req['batas_waktu'] ? 'Batas ' . $tgl($req['batas_waktu']) : 'Tanpa batas waktu' ?></span>
                    <?php if ($req['halaman'] && \App\Libraries\HakAkses::instance()->bisa($req['halaman'])) : ?>
                        <a class="rq-badge biru" href="<?= site_url($req['halaman']) ?>" target="_blank"><i class="ti ti-external-link"></i> Buka halaman</a>
                    <?php endif; ?>
                </div>
                <?php if (!$baca) : ?>
                    <p class="rq-meta mt-3"><span class="text-red-500">*</span> Menunjukkan pertanyaan yang wajib diisi</p>
                <?php endif; ?>
            </div>
        </div>

        <form method="post" action="<?= site_url('Request/kirim/' . $req['id']) ?>" enctype="multipart/form-data" id="formIsi" novalidate>
            <?= csrf_field() ?>

            <?php foreach ($fields as $f) :
                $fid  = $f['id'];
                $tipe = $f['tipe'];
                $v    = $nilai[$fid] ?? null;
                $nm   = 'f[' . esc($fid, 'attr') . ']';
                $wj   = !empty($f['wajib']);

                if ($tipe === 'bagian') : ?>
                    <div class="card" style="overflow:hidden">
                        <div class="ri-bagian"><h2 class="font-bold text-lg"><?= esc($f['label']) ?></h2></div>
                        <?php if ($f['keterangan'] !== '') : ?><div class="card-body" style="padding:14px 20px;white-space:pre-line;font-size:14px"><?= esc($f['keterangan']) ?></div><?php endif; ?>
                    </div>
                <?php continue;
                endif;
                $no++; ?>
                <div class="card ri-q <?= isset($err[$fid]) ? 'salah' : '' ?>" id="q-<?= esc($fid, 'attr') ?>">
                    <div class="card-body">
                        <h3><?= esc($f['label']) ?> <?php if ($wj) : ?><span class="wajib">*</span><?php endif; ?></h3>
                        <?php if (($f['keterangan'] ?? '') !== '') : ?><div class="ket"><?= esc($f['keterangan']) ?></div><?php endif; ?>

                        <?php if ($baca && $tipe !== 'file') :
                            $teks = RF::teksJawaban($f, $v); ?>
                            <div class="ri-baca <?= $teks === '' ? 'kosong' : '' ?>"><?= $teks === '' ? 'Tidak dijawab' : esc($teks) ?></div>

                        <?php elseif ($tipe === 'teks') : ?>
                            <input type="text" class="rq-input" name="<?= $nm ?>" value="<?= esc((string) $v, 'attr') ?>" maxlength="1000" <?= $wj ? 'required' : '' ?> placeholder="Jawaban Anda">
                        <?php elseif ($tipe === 'paragraf') : ?>
                            <textarea class="rq-input" name="<?= $nm ?>" rows="4" maxlength="10000" <?= $wj ? 'required' : '' ?> placeholder="Jawaban Anda"><?= esc((string) $v) ?></textarea>
                        <?php elseif ($tipe === 'angka') : ?>
                            <input type="number" step="any" class="rq-input" style="max-width:260px" name="<?= $nm ?>" value="<?= esc((string) $v, 'attr') ?>" <?= $wj ? 'required' : '' ?>>
                        <?php elseif ($tipe === 'email') : ?>
                            <input type="email" class="rq-input" style="max-width:360px" name="<?= $nm ?>" value="<?= esc((string) $v, 'attr') ?>" <?= $wj ? 'required' : '' ?> placeholder="nama@email.com">
                        <?php elseif ($tipe === 'tanggal') : ?>
                            <input type="date" class="rq-input" style="max-width:220px" name="<?= $nm ?>" value="<?= esc((string) $v, 'attr') ?>" <?= $wj ? 'required' : '' ?>>
                        <?php elseif ($tipe === 'waktu') : ?>
                            <input type="time" class="rq-input" style="max-width:160px" name="<?= $nm ?>" value="<?= esc((string) $v, 'attr') ?>" <?= $wj ? 'required' : '' ?>>
                        <?php elseif ($tipe === 'pilihan') : ?>
                            <?php foreach ($f['opsi'] ?? [] as $o) : ?>
                                <label class="ri-pilih"><input type="radio" name="<?= $nm ?>" value="<?= esc($o, 'attr') ?>" <?= (string) $v === $o ? 'checked' : '' ?> <?= $wj ? 'required' : '' ?>> <?= esc($o) ?></label>
                            <?php endforeach; ?>
                            <?php if (!$wj) : ?><button type="button" class="rq-btn kecil mt-1" onclick="this.closest('.card-body').querySelectorAll('input[type=radio]').forEach(r=>r.checked=false)">Kosongkan pilihan</button><?php endif; ?>
                        <?php elseif ($tipe === 'centang') :
                            $vv = is_array($v) ? array_map('strval', $v) : []; ?>
                            <div data-wajib-centang="<?= $wj ? 1 : 0 ?>">
                                <?php foreach ($f['opsi'] ?? [] as $o) : ?>
                                    <label class="ri-pilih"><input type="checkbox" name="<?= $nm ?>[]" value="<?= esc($o, 'attr') ?>" <?= in_array($o, $vv, true) ? 'checked' : '' ?>> <?= esc($o) ?></label>
                                <?php endforeach; ?>
                            </div>
                        <?php elseif ($tipe === 'dropdown') : ?>
                            <select class="rq-input" style="max-width:360px" name="<?= $nm ?>" <?= $wj ? 'required' : '' ?>>
                                <option value="">— Pilih —</option>
                                <?php foreach ($f['opsi'] ?? [] as $o) : ?>
                                    <option value="<?= esc($o, 'attr') ?>" <?= (string) $v === $o ? 'selected' : '' ?>><?= esc($o) ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php elseif ($tipe === 'skala') : ?>
                            <div class="ri-skala">
                                <?php if (!empty($f['label_min'])) : ?><span class="ujung"><?= esc($f['label_min']) ?></span><?php endif; ?>
                                <?php for ($n = (int) ($f['min'] ?? 1); $n <= (int) ($f['max'] ?? 5); $n++) : ?>
                                    <label><?= $n ?><input type="radio" name="<?= $nm ?>" value="<?= $n ?>" <?= (string) $v === (string) $n ? 'checked' : '' ?> <?= $wj ? 'required' : '' ?>></label>
                                <?php endfor; ?>
                                <?php if (!empty($f['label_max'])) : ?><span class="ujung"><?= esc($f['label_max']) ?></span><?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($tipe === 'file') :
                            $ada = is_array($v) && !empty($v['file']); ?>
                            <?php if ($ada && $jawaban) : ?>
                                <div class="ri-file">
                                    <i class="ti ti-paperclip"></i>
                                    <a href="<?= site_url('Request/file/' . $jawaban['id'] . '/' . $fid) ?>" target="_blank"><?= esc($v['nama'] ?? 'file') ?></a>
                                    <span class="rq-meta"><?= isset($v['ukuran']) ? number_format($v['ukuran'] / 1024, 0, ',', '.') . ' KB' : '' ?></span>
                                    <?php if (!$baca && !$wj) : ?>
                                        <label class="ri-pilih" style="margin-left:auto;padding:0;font-size:12px"><input type="checkbox" name="hapus_file[]" value="<?= esc($fid, 'attr') ?>"> Hapus file</label>
                                    <?php endif; ?>
                                </div>
                            <?php elseif ($baca) : ?>
                                <div class="ri-baca kosong">Tidak ada file</div>
                            <?php endif; ?>
                            <?php if (!$baca) : ?>
                                <input type="file" class="rq-input" name="file_<?= esc($fid, 'attr') ?>" data-wajib-file="<?= $wj && !$ada ? 1 : 0 ?>"
                                    accept="<?= esc('.' . implode(',.', RF::EKSTENSI), 'attr') ?>">
                                <div class="rq-meta mt-1"><?= $ada ? 'Pilih file baru untuk mengganti. ' : '' ?>Maks. <?= RF::MAKS_KB / 1024 ?> MB · <?= implode(', ', RF::EKSTENSI) ?></div>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if (isset($err[$fid])) : ?>
                            <div class="pesan"><i class="ti ti-alert-circle"></i> <?= esc($err[$fid]) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if ($pratinjau) : ?>
                <div class="rq-alert warn mb-8"><i class="ti ti-eye"></i> Ini pratinjau. Akun Anda bukan pengisi request ini, jadi jawaban tidak bisa dikirim.</div>
            <?php elseif (!$baca) : ?>
                <div class="flex justify-between items-center gap-3 flex-wrap mb-8">
                    <span class="rq-meta">Dikirim sebagai <b><?= esc(session('username')) ?></b></span>
                    <button type="submit" class="rq-btn biru" style="padding:10px 22px;font-size:14px" id="btnKirim"><i class="ti ti-send"></i> <?= $jawaban ? 'Kirim Ulang Jawaban' : 'Kirim Jawaban' ?></button>
                </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<?php if ($bisaKirim) : ?>
    <script>
        // cek wajib di browser (server tetap memeriksa ulang)
        document.getElementById('formIsi').addEventListener('submit', function(e) {
            let pertama = null;
            const tandai = (card, pesan) => {
                card.classList.add('salah');
                let p = card.querySelector('.pesan');
                if (!p) {
                    p = document.createElement('div');
                    p.className = 'pesan';
                    card.querySelector('.card-body').appendChild(p);
                }
                p.innerHTML = '<i class="ti ti-alert-circle"></i> ' + pesan;
                if (!pertama) pertama = card;
            };
            this.querySelectorAll('.ri-q').forEach(c => {
                c.classList.remove('salah');
                const p = c.querySelector('.pesan');
                if (p) p.remove();
            });
            this.querySelectorAll('.ri-q').forEach(card => {
                const el = card.querySelector('[required]');
                if (el) {
                    if (el.type === 'radio') {
                        if (!card.querySelector('input[type=radio]:checked')) tandai(card, 'Pertanyaan ini wajib diisi.');
                    } else if (!el.value.trim()) {
                        tandai(card, 'Pertanyaan ini wajib diisi.');
                    } else if (!el.checkValidity()) {
                        tandai(card, el.validationMessage || 'Isian tidak valid.');
                    }
                } else {
                    const lain = card.querySelector('input[type=email],input[type=number]');
                    if (lain && lain.value && !lain.checkValidity()) tandai(card, lain.validationMessage || 'Isian tidak valid.');
                }
                const ce = card.querySelector('[data-wajib-centang="1"]');
                if (ce && !ce.querySelector('input:checked')) tandai(card, 'Pilih minimal satu.');
                const fl = card.querySelector('input[type=file]');
                if (fl) {
                    if (fl.dataset.wajibFile === '1' && !fl.files.length) tandai(card, 'Wajib upload file.');
                    else if (fl.files.length && fl.files[0].size > <?= RF::MAKS_KB * 1024 ?>) tandai(card, 'Ukuran file maksimal <?= RF::MAKS_KB / 1024 ?> MB.');
                }
            });
            if (pertama) {
                e.preventDefault();
                pertama.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
            const b = document.getElementById('btnKirim');
            b.disabled = true;
            b.innerHTML = '<i class="ti ti-loader-2"></i> Mengirim...';
        });
    </script>
<?php endif; ?>
<?php if ($err) : ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const c = document.querySelector('.ri-q.salah');
            if (c) c.scrollIntoView({ block: 'center' });
        });
    </script>
<?php endif; ?>
<?= view('RequestForm/_bawah') ?>
