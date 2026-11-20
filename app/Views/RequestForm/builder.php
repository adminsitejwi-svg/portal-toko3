<?php

use App\Libraries\RequestForm as RF;

/**
 * Buat / Ubah Request (khusus Administrator)
 * Susunan pertanyaan disusun di JS lalu dikirim sebagai JSON (fields_json).
 */
$errors = session()->getFlashdata('errors') ?? [];

$nilai = [
    'judul'       => old('judul', $req['judul'] ?? '', false),
    'deskripsi'   => old('deskripsi', $req['deskripsi'] ?? '', false),
    'halaman'     => old('halaman', $req['halaman'] ?? '', false),
    'batas_waktu' => old('batas_waktu', !empty($req['batas_waktu']) ? date('Y-m-d\TH:i', strtotime($req['batas_waktu'])) : '', false),
    'status'      => old('status', $req['status'] ?? 'aktif', false),
    'boleh_ubah'  => $errors ? (bool) old('boleh_ubah') : (bool) ($req['boleh_ubah'] ?? 0),
];

$fieldsAwal = $fields;
$oldFields  = old('fields_json', null, false);
if ($oldFields) {
    $dec = json_decode((string) $oldFields, true);
    if (is_array($dec)) {
        $fieldsAwal = $dec;
    }
}
if (!$fieldsAwal) {
    $fieldsAwal = [['id' => '', 'tipe' => 'teks', 'label' => '', 'keterangan' => '', 'wajib' => true]];
}

$targetAwal = $target;
$oldTarget  = old('target', null, false);
if (is_array($oldTarget)) {
    $targetAwal = array_map('intval', $oldTarget);
}

$tipe = array_map(static fn ($t) => ['label' => $t[0], 'ikon' => $t[1]], RF::TIPE);
?>
<?= view('RequestForm/_atas', ['judulHalaman' => $judulHalaman]) ?>
<?= view('RequestForm/_gaya') ?>
<style>
    /* ===== tata letak formulir lebar (1 kolom penuh, 3 bagian bernomor) ===== */
    .rb-page { padding: 20px 24px !important; }
    @media (max-width: 640px) { .rb-page { padding: 16px !important; } }
    .rb-form { width: 100%; }
    .rb-sec { margin-bottom: 20px; overflow: hidden; }
    .rb-sec-head { display: flex; align-items: center; gap: 14px; padding: 16px 24px; border-bottom: 1px solid #e5e7eb; background: linear-gradient(90deg, rgba(4, 169, 245, .08), transparent 60%); flex-wrap: wrap; }
    .dark .rb-sec-head { border-color: rgba(255, 255, 255, .08); background: linear-gradient(90deg, rgba(4, 169, 245, .12), transparent 60%); }
    .rb-sec-no { width: 34px; height: 34px; border-radius: 50%; background: #04a9f5; color: #fff; font-weight: 800; display: flex; align-items: center; justify-content: center; flex: none; }
    .rb-sec-head h2 { font-size: 16px; font-weight: 700; line-height: 1.2; }
    .rb-sec-head p { font-size: 12px; color: #64748b; margin-top: 2px; }
    .dark .rb-sec-head p { color: #94a3b8; }
    .rb-sec-body { padding: 22px 24px; }
    @media (max-width: 640px) { .rb-sec-head, .rb-sec-body { padding-left: 16px; padding-right: 16px; } }

    /* baris form: label kiri, input kanan */
    .rb-row { display: grid; grid-template-columns: 240px minmax(0, 1fr); gap: 8px 24px; padding: 14px 0; border-bottom: 1px dashed #e5e7eb; align-items: start; }
    .rb-row:first-child { padding-top: 0; }
    .rb-row:last-child { border-bottom: 0; padding-bottom: 0; }
    .dark .rb-row { border-color: rgba(255, 255, 255, .08); }
    .rb-row > label, .rb-row > .rb-row-label { font-size: 14px; font-weight: 600; padding-top: 9px; }
    .rb-row .rq-meta { margin-top: 4px; font-weight: 400; }
    .rb-row-dua { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; }
    @media (max-width: 900px) { .rb-row { grid-template-columns: 1fr; } .rb-row > label, .rb-row > .rb-row-label { padding-top: 0; } }

    /* pertanyaan */
    .rb-q { border: 1px solid #e5e7eb; border-left: 5px solid #cbd5e1; border-radius: 12px; padding: 18px 20px; margin-bottom: 14px; background: inherit; transition: border-color .15s, box-shadow .15s; }
    .rb-q:focus-within { border-left-color: #04a9f5; box-shadow: 0 6px 22px rgba(4, 169, 245, .10); }
    .rb-q.bagian { background: #f8fafc; border-left-color: #8b5cf6; }
    .dark .rb-q { border-color: rgba(255, 255, 255, .1); border-left-color: rgba(255, 255, 255, .2); }
    .dark .rb-q:focus-within { border-left-color: #04a9f5; }
    .dark .rb-q.bagian { background: rgba(255, 255, 255, .03); border-left-color: #8b5cf6; }
    .rb-q-top { display: grid; grid-template-columns: minmax(0, 1fr) 260px; gap: 12px; }
    @media (max-width: 640px) { .rb-q-top { grid-template-columns: 1fr; } }
    .rb-q-no { font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; }
    .rb-label { font-weight: 600; font-size: 15px !important; }
    .rb-ket { margin-top: 10px; font-size: 13px; }
    .rb-body { margin-top: 14px; }
    .rb-preview { font-size: 13px; color: #94a3b8; border-bottom: 1px dotted #cbd5e1; padding: 6px 0; max-width: 480px; }
    .rb-opsi { display: flex; align-items: center; gap: 8px; margin-bottom: 6px; max-width: 720px; }
    .rb-opsi .ic { color: #94a3b8; width: 18px; text-align: center; }
    .rb-opsi input { flex: 1; padding: 6px 10px; }
    .rb-link { background: none; border: 0; color: #04a9f5; font-size: 13px; font-weight: 600; cursor: pointer; padding: 6px 0; display: inline-flex; align-items: center; gap: 4px; }
    .rb-ikon { background: none; border: 0; width: 32px; height: 32px; border-radius: 8px; color: #64748b; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; font-size: 17px; }
    .rb-ikon:hover { background: #f1f5f9; color: #0f172a; }
    .dark .rb-ikon:hover { background: rgba(255, 255, 255, .08); color: #fff; }
    .rb-ikon.merah:hover { background: #fef2f2; color: #dc2626; }
    .rb-ikon[disabled] { opacity: .3; pointer-events: none; }
    .rb-q-foot { display: flex; align-items: center; justify-content: flex-end; gap: 2px; margin-top: 14px; padding-top: 10px; border-top: 1px solid #f1f5f9; flex-wrap: wrap; }
    .dark .rb-q-foot { border-color: rgba(255, 255, 255, .06); }
    .rb-sw { display: inline-flex; align-items: center; gap: 8px; font-size: 13px; cursor: pointer; margin-right: auto; user-select: none; }
    .rb-sw input { width: 16px; height: 16px; accent-color: #04a9f5; }
    .rb-tambah { display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 8px; padding: 16px; border: 2px dashed #cbd5e1; border-radius: 12px; }
    .rb-tambah-judul { grid-column: 1 / -1; font-size: 13px; font-weight: 600; color: #64748b; }
    .rb-tambah .rq-btn { justify-content: flex-start; padding: 9px 12px; }
    .dark .rb-tambah { border-color: rgba(255, 255, 255, .15); }
    .rb-skala { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; font-size: 13px; }
    .rb-skala select, .rb-skala input { width: auto; padding: 6px 10px; }

    /* akun pengisi: grid beberapa kolom */
    .rb-akun-bar { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; margin-bottom: 14px; }
    .rb-akun-bar .rq-input { max-width: 340px; }
    .rb-akun-list { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 10px; max-height: 460px; overflow-y: auto; padding: 2px; }
    .rb-akun { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border: 1px solid #e5e7eb; border-radius: 10px; cursor: pointer; font-size: 13px; transition: border-color .15s, background .15s; }
    .rb-akun:hover { background: #f8fafc; }
    .rb-akun:has(input:checked) { border-color: #04a9f5; background: rgba(4, 169, 245, .06); }
    .dark .rb-akun { border-color: rgba(255, 255, 255, .1); }
    .dark .rb-akun:hover { background: rgba(255, 255, 255, .04); }
    .dark .rb-akun:has(input:checked) { border-color: #04a9f5; background: rgba(4, 169, 245, .12); }
    .rb-akun input { width: 16px; height: 16px; accent-color: #04a9f5; flex: none; }
    .rb-akun .nm { flex: 1; min-width: 0; }
    .rb-akun .nm b { display: block; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .rb-role-btns { display: flex; flex-wrap: wrap; gap: 6px; }

    .rb-foot { position: sticky; bottom: 0; z-index: 20; display: flex; justify-content: space-between; align-items: center; gap: 8px; padding: 14px 24px; margin: 0 -24px -20px; background: rgba(255, 255, 255, .94); border-top: 1px solid #e5e7eb; backdrop-filter: blur(6px); flex-wrap: wrap; }
    .dark .rb-foot { background: rgba(38, 50, 64, .94); border-color: rgba(255, 255, 255, .1); }
    @media (max-width: 640px) { .rb-foot { margin: 0 -16px -16px; padding: 12px 16px; } }
</style>

<div class="rfo-page rb-page p-6">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <span class="rfo-title-ic is-sky"><i class="ti ti-forms"></i></span>
            <div>
                <h1 class="rfo-title"><?= esc($judulHalaman) ?></h1>
                <p class="rfo-subtitle">Isi informasi request, susun pertanyaan form, lalu pilih akun yang bisa mengisi</p>
            </div>
        </div>
        <a href="<?= site_url('Request') ?>#kelola" class="rq-btn"><i class="ti ti-arrow-left"></i> Kembali</a>
    </div>

    <?php if ($errors) : ?>
        <div class="rq-alert err"><i class="ti ti-alert-circle"></i>
            <div><b>Request belum tersimpan:</b>
                <ul><?php foreach ($errors as $e) : ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
            </div>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')) : ?>
        <div class="rq-alert err"><i class="ti ti-alert-circle"></i> <?= esc(session()->getFlashdata('error')) ?></div>
    <?php endif; ?>
    <?php if ($jmlJawaban) : ?>
        <div class="rq-alert warn"><i class="ti ti-alert-triangle"></i>
            <div>Request ini sudah punya <b><?= $jmlJawaban ?> jawaban</b>. Mengubah teks pertanyaan aman; menghapus pertanyaan membuat jawabannya tidak tampil lagi.</div>
        </div>
    <?php endif; ?>

    <form method="post" action="<?= site_url('Request/kelola/simpan') ?>" id="formRequest" class="rb-form" autocomplete="off">
        <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) ($req['id'] ?? 0) ?>">
        <input type="hidden" name="fields_json" id="fieldsJson">

        <!-- ===== 1. INFORMASI ===== -->
        <div class="card rb-sec">
            <div class="rb-sec-head">
                <span class="rb-sec-no">1</span>
                <div><h2>Informasi Request</h2><p>Judul, instruksi, halaman terkait, dan aturan pengisian</p></div>
            </div>
            <div class="rb-sec-body">
                <div class="rb-row">
                    <label for="judul">Judul Request <span class="text-red-500">*</span></label>
                    <input type="text" class="rq-input" style="font-size:18px;font-weight:700" id="judul" name="judul" maxlength="200" required
                        value="<?= esc($nilai['judul'], 'attr') ?>" placeholder="Contoh: Update data perangkat toko November">
                </div>
                <div class="rb-row">
                    <label for="deskripsi">Deskripsi / Instruksi
                        <div class="rq-meta">Opsional, tampil di atas form</div></label>
                    <textarea class="rq-input" id="deskripsi" name="deskripsi" rows="4" placeholder="Jelaskan apa yang harus diisi"><?= esc($nilai['deskripsi']) ?></textarea>
                </div>
                <div class="rb-row">
                    <label for="halaman">Halaman yang di-request
                        <div class="rq-meta">Pengingat muncul di halaman ini</div></label>
                    <select class="rq-input" id="halaman" name="halaman">
                        <option value="">Umum (tanpa halaman)</option>
                        <?php foreach ($grup as $namaGrup => $isi) : ?>
                            <optgroup label="<?= esc($namaGrup, 'attr') ?>">
                                <?php foreach ($isi as $k => $l) : ?>
                                    <option value="<?= esc($k, 'attr') ?>" <?= strcasecmp((string) $nilai['halaman'], $k) === 0 ? 'selected' : '' ?>><?= esc($l) ?></option>
                                <?php endforeach; ?>
                            </optgroup>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="rb-row">
                    <div class="rb-row-label">Waktu &amp; Status</div>
                    <div class="rb-row-dua">
                        <div>
                            <label class="rq-label" for="batas_waktu">Batas Waktu</label>
                            <input type="datetime-local" class="rq-input" id="batas_waktu" name="batas_waktu" value="<?= esc($nilai['batas_waktu'], 'attr') ?>">
                            <div class="rq-meta">Kosongkan bila tanpa batas waktu</div>
                        </div>
                        <div>
                            <label class="rq-label" for="status">Status</label>
                            <select class="rq-input" id="status" name="status">
                                <option value="aktif" <?= $nilai['status'] === 'aktif' ? 'selected' : '' ?>>Aktif (bisa diisi)</option>
                                <option value="ditutup" <?= $nilai['status'] === 'ditutup' ? 'selected' : '' ?>>Ditutup</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="rb-row">
                    <div class="rb-row-label">Perubahan Jawaban</div>
                    <label class="rb-sw" style="padding-top:8px">
                        <input type="checkbox" name="boleh_ubah" value="1" <?= $nilai['boleh_ubah'] ? 'checked' : '' ?>>
                        Pengisi boleh mengubah jawaban setelah dikirim (sebelum disetujui)
                    </label>
                </div>
            </div>
        </div>

        <!-- ===== 2. PERTANYAAN ===== -->
        <div class="card rb-sec">
            <div class="rb-sec-head">
                <span class="rb-sec-no">2</span>
                <div><h2>Pertanyaan Form</h2><p>Susun pertanyaan seperti Google Form: pilih jenis isian, wajib/tidak, dan urutannya</p></div>
            </div>
            <div class="rb-sec-body">
                <div id="daftarPertanyaan"></div>
                <div class="rb-tambah">
                    <div class="rb-tambah-judul"><i class="ti ti-plus"></i> Tambah pertanyaan</div>
                    <?php foreach ($tipe as $k => $t) : ?>
                        <button type="button" class="rq-btn" onclick="tambahPertanyaan('<?= $k ?>')"><i class="ti <?= $t['ikon'] ?>"></i> <?= esc($t['label']) ?></button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- ===== 3. AKUN PENGISI ===== -->
        <div class="card rb-sec">
            <div class="rb-sec-head">
                <span class="rb-sec-no">3</span>
                <div style="flex:1"><h2>Akun yang Bisa Mengisi <span class="text-red-500">*</span></h2><p>Hanya akun yang dicentang yang melihat &amp; bisa mengisi request ini di menu Request</p></div>
                <span class="rq-badge biru" id="jmlDipilih" style="font-size:12px;padding:5px 12px">0 dipilih</span>
            </div>
            <div class="rb-sec-body">
                <div class="rb-akun-bar">
                    <input type="search" class="rq-input" id="cariAkun" placeholder="Cari username / role...">
                    <div class="rb-role-btns">
                        <button type="button" class="rq-btn kecil" onclick="pilihSemua(true)"><i class="ti ti-checks"></i> Pilih semua</button>
                        <button type="button" class="rq-btn kecil" onclick="pilihSemua(false)"><i class="ti ti-square-off"></i> Kosongkan</button>
                        <?php foreach ($roles as $r) : ?>
                            <button type="button" class="rq-btn kecil" onclick="pilihRole(<?= (int) $r['id'] ?>)" title="Centang semua akun role ini">+ <?= esc($r['nama']) ?></button>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="rq-alert warn" id="infoAkses" style="display:none"></div>
                <div class="rb-akun-list" id="daftarAkun">
                    <?php foreach ($akun as $a) : ?>
                        <label class="rb-akun" data-id="<?= $a['id'] ?>" data-role="<?= (int) $a['role_id'] ?>"
                            data-cari="<?= esc(strtolower($a['username'] . ' ' . $a['role']), 'attr') ?>">
                            <input type="checkbox" name="target[]" value="<?= $a['id'] ?>" <?= in_array($a['id'], $targetAwal, true) ? 'checked' : '' ?>>
                            <span class="nm"><b><?= esc($a['username']) ?></b><span class="rq-meta"><?= esc($a['role']) ?></span></span>
                            <span class="rq-badge merah tanda-akses" style="display:none" title="Akun ini tidak punya hak akses ke halaman yang dipilih, pengingat tidak akan terlihat di halaman tsb (tetap bisa diisi dari menu Request)"><i class="ti ti-lock"></i> tanpa akses</span>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="rb-foot">
            <span class="rq-meta"><span class="text-red-500">*</span> wajib diisi</span>
            <div class="flex gap-2">
                <a href="<?= site_url('Request') ?>#kelola" class="rq-btn">Batal</a>
                <button type="submit" class="rq-btn biru" style="padding:9px 20px"><i class="ti ti-device-floppy"></i> Simpan Request</button>
            </div>
        </div>
    </form>
</div>

<script>
    const RB_TIPE = <?= json_encode($tipe, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?>;
    const RB_PAKAI_OPSI = <?= json_encode(RF::PAKAI_OPSI) ?>;
    const RB_AKUN = <?= json_encode(array_column($akun, null, 'id'), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP) ?>;
    let FIELDS = <?= json_encode(array_values($fieldsAwal), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) ?>;

    const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
    const idBaru = () => 'f' + Math.random().toString(36).slice(2, 10) + Date.now().toString(36).slice(-3);

    FIELDS = FIELDS.map(f => normal(f));

    function normal(f) {
        f = Object.assign({ id: '', tipe: 'teks', label: '', keterangan: '', wajib: false }, f || {});
        if (!/^[a-z0-9]{4,24}$/.test(f.id)) f.id = idBaru();
        if (RB_PAKAI_OPSI.includes(f.tipe) && !(Array.isArray(f.opsi) && f.opsi.length)) f.opsi = ['Opsi 1'];
        if (f.tipe === 'skala') {
            f.min = parseInt(f.min, 10) === 0 ? 0 : 1;
            f.max = Math.min(10, Math.max(2, parseInt(f.max, 10) || 5));
            f.label_min = f.label_min || '';
            f.label_max = f.label_max || '';
        }
        return f;
    }

    function bodyTipe(f, i) {
        if (RB_PAKAI_OPSI.includes(f.tipe)) {
            const ic = f.tipe === 'pilihan' ? 'ti-circle' : (f.tipe === 'centang' ? 'ti-square' : null);
            let h = '';
            f.opsi.forEach((o, j) => {
                h += '<div class="rb-opsi"><span class="ic">' + (ic ? '<i class="ti ' + ic + '"></i>' : (j + 1) + '.') + '</span>' +
                    '<input type="text" class="rq-input" value="' + esc(o) + '" data-i="' + i + '" data-opsi="' + j + '" placeholder="Opsi ' + (j + 1) + '">' +
                    '<button type="button" class="rb-ikon merah" title="Hapus opsi" onclick="hapusOpsi(' + i + ',' + j + ')"' + (f.opsi.length < 2 ? ' disabled' : '') + '><i class="ti ti-x"></i></button></div>';
            });
            return h + '<button type="button" class="rb-link" onclick="tambahOpsi(' + i + ')"><i class="ti ti-plus"></i> Tambah opsi</button>';
        }
        if (f.tipe === 'skala') {
            let opMax = '';
            for (let n = 2; n <= 10; n++) opMax += '<option value="' + n + '"' + (f.max === n ? ' selected' : '') + '>' + n + '</option>';
            return '<div class="rb-skala">' +
                '<select class="rq-input" data-i="' + i + '" data-k="min"><option value="0"' + (f.min === 0 ? ' selected' : '') + '>0</option><option value="1"' + (f.min === 1 ? ' selected' : '') + '>1</option></select>' +
                ' sampai <select class="rq-input" data-i="' + i + '" data-k="max">' + opMax + '</select>' +
                '<input type="text" class="rq-input" data-i="' + i + '" data-k="label_min" value="' + esc(f.label_min) + '" placeholder="Label nilai terendah (opsional)">' +
                '<input type="text" class="rq-input" data-i="' + i + '" data-k="label_max" value="' + esc(f.label_max) + '" placeholder="Label nilai tertinggi (opsional)">' +
                '</div>';
        }
        const contoh = {
            teks: 'Jawaban singkat', paragraf: 'Jawaban panjang', angka: 'Angka', email: 'nama@email.com',
            tanggal: 'Hari / Bulan / Tahun', waktu: 'Jam : Menit',
            file: 'Upload 1 file (maks. <?= RF::MAKS_KB / 1024 ?> MB): <?= implode(', ', RF::EKSTENSI) ?>'
        };
        if (f.tipe === 'bagian') return '';
        return '<div class="rb-preview">' + esc(contoh[f.tipe] || '') + '</div>';
    }

    function render() {
        const wadah = document.getElementById('daftarPertanyaan');
        let no = 0;
        wadah.innerHTML = FIELDS.map((f, i) => {
            const bagian = f.tipe === 'bagian';
            if (!bagian) no++;
            let opTipe = '';
            Object.keys(RB_TIPE).forEach(k => opTipe += '<option value="' + k + '"' + (k === f.tipe ? ' selected' : '') + '>' + esc(RB_TIPE[k].label) + '</option>');
            return '<div class="rb-q' + (bagian ? ' bagian' : '') + '">' +
                '<div class="rb-q-no"><i class="ti ' + RB_TIPE[f.tipe].ikon + '"></i>' + (bagian ? 'Judul bagian' : 'Pertanyaan ' + no) + '</div>' +
                '<div class="rb-q-top">' +
                '<input type="text" class="rq-input rb-label" data-i="' + i + '" data-k="label" value="' + esc(f.label) + '" placeholder="' + (bagian ? 'Judul bagian' : 'Tulis pertanyaan') + '" maxlength="500">' +
                '<select class="rq-input" data-i="' + i + '" data-k="tipe">' + opTipe + '</select>' +
                '</div>' +
                '<input type="text" class="rq-input rb-ket" data-i="' + i + '" data-k="keterangan" value="' + esc(f.keterangan) + '" placeholder="Keterangan / petunjuk (opsional)">' +
                '<div class="rb-body">' + bodyTipe(f, i) + '</div>' +
                '<div class="rb-q-foot">' +
                (bagian ? '<span style="margin-right:auto"></span>' : '<label class="rb-sw"><input type="checkbox" data-i="' + i + '" data-k="wajib"' + (f.wajib ? ' checked' : '') + '> Wajib diisi</label>') +
                '<button type="button" class="rb-ikon" title="Naik" onclick="geser(' + i + ',-1)"' + (i === 0 ? ' disabled' : '') + '><i class="ti ti-arrow-up"></i></button>' +
                '<button type="button" class="rb-ikon" title="Turun" onclick="geser(' + i + ',1)"' + (i === FIELDS.length - 1 ? ' disabled' : '') + '><i class="ti ti-arrow-down"></i></button>' +
                '<button type="button" class="rb-ikon" title="Duplikat" onclick="duplikat(' + i + ')"><i class="ti ti-copy"></i></button>' +
                '<button type="button" class="rb-ikon merah" title="Hapus" onclick="hapusPertanyaan(' + i + ')"' + (FIELDS.length < 2 ? ' disabled' : '') + '><i class="ti ti-trash"></i></button>' +
                '</div></div>';
        }).join('');
    }

    // ubah nilai tanpa render ulang (supaya fokus ketikan tidak hilang)
    const wadahQ = document.getElementById('daftarPertanyaan');
    wadahQ.addEventListener('input', e => {
        const el = e.target, i = parseInt(el.dataset.i, 10);
        if (isNaN(i) || !FIELDS[i]) return;
        if (el.dataset.opsi !== undefined) FIELDS[i].opsi[parseInt(el.dataset.opsi, 10)] = el.value;
        else if (['label', 'keterangan', 'label_min', 'label_max'].includes(el.dataset.k)) FIELDS[i][el.dataset.k] = el.value;
    });
    wadahQ.addEventListener('change', e => {
        const el = e.target, i = parseInt(el.dataset.i, 10), k = el.dataset.k;
        if (isNaN(i) || !FIELDS[i]) return;
        if (k === 'wajib') FIELDS[i].wajib = el.checked;
        else if (k === 'min' || k === 'max') FIELDS[i][k] = parseInt(el.value, 10);
        else if (k === 'tipe') {
            FIELDS[i].tipe = el.value;
            if (el.value === 'bagian') FIELDS[i].wajib = false;
            FIELDS[i] = normal(FIELDS[i]);
            render();
        }
    });
    // Enter di kotak opsi = tambah opsi baru
    wadahQ.addEventListener('keydown', e => {
        if (e.key === 'Enter' && e.target.dataset.opsi !== undefined) {
            e.preventDefault();
            tambahOpsi(parseInt(e.target.dataset.i, 10));
        } else if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
            e.preventDefault();
        }
    });

    function tambahPertanyaan(tipe) {
        FIELDS.push(normal({ tipe, wajib: tipe !== 'bagian' }));
        render();
        const semua = wadahQ.querySelectorAll('.rb-label');
        const akhir = semua[semua.length - 1];
        if (akhir) { akhir.scrollIntoView({ behavior: 'smooth', block: 'center' }); akhir.focus({ preventScroll: true }); }
    }
    function hapusPertanyaan(i) { if (FIELDS.length > 1) { FIELDS.splice(i, 1); render(); } }
    function duplikat(i) {
        const salin = JSON.parse(JSON.stringify(FIELDS[i]));
        salin.id = idBaru();
        FIELDS.splice(i + 1, 0, salin);
        render();
    }
    function geser(i, arah) {
        const j = i + arah;
        if (j < 0 || j >= FIELDS.length) return;
        [FIELDS[i], FIELDS[j]] = [FIELDS[j], FIELDS[i]];
        render();
    }
    function tambahOpsi(i) {
        FIELDS[i].opsi.push('Opsi ' + (FIELDS[i].opsi.length + 1));
        render();
        const inp = wadahQ.querySelectorAll('[data-i="' + i + '"][data-opsi]');
        if (inp.length) { inp[inp.length - 1].focus(); inp[inp.length - 1].select(); }
    }
    function hapusOpsi(i, j) { if (FIELDS[i].opsi.length > 1) { FIELDS[i].opsi.splice(j, 1); render(); } }

    // ===== akun pengisi =====
    const daftarAkun = document.getElementById('daftarAkun');
    const halamanSel = document.getElementById('halaman');

    function segarkanAkun() {
        const hal = halamanSel.value.toLowerCase();
        let dipilih = 0, tanpaAkses = 0;
        daftarAkun.querySelectorAll('.rb-akun').forEach(el => {
            const a = RB_AKUN[el.dataset.id];
            const cek = el.querySelector('input').checked;
            const bisa = !hal || !a || a.semua || a.halaman.includes(hal);
            el.querySelector('.tanda-akses').style.display = bisa ? 'none' : '';
            if (cek) { dipilih++; if (!bisa) tanpaAkses++; }
        });
        document.getElementById('jmlDipilih').textContent = dipilih + ' dipilih';
        const info = document.getElementById('infoAkses');
        info.style.display = tanpaAkses ? 'flex' : 'none';
        info.innerHTML = '<i class="ti ti-alert-triangle"></i><div>' + tanpaAkses + ' akun terpilih tidak punya akses ke halaman <b>' +
            esc(halamanSel.options[halamanSel.selectedIndex].text) + '</b>. Mereka tetap bisa mengisi dari menu Request. Atur aksesnya di <a href="<?= site_url('HakAkses') ?>#akun" target="_blank" style="text-decoration:underline">Hak Akses</a>.</div>';
    }
    daftarAkun.addEventListener('change', segarkanAkun);
    halamanSel.addEventListener('change', segarkanAkun);

    document.getElementById('cariAkun').addEventListener('input', e => {
        const q = e.target.value.trim().toLowerCase();
        daftarAkun.querySelectorAll('.rb-akun').forEach(el => el.style.display = !q || el.dataset.cari.includes(q) ? '' : 'none');
    });
    function pilihSemua(v) {
        daftarAkun.querySelectorAll('.rb-akun').forEach(el => { if (el.style.display !== 'none') el.querySelector('input').checked = v; });
        segarkanAkun();
    }
    function pilihRole(roleId) {
        daftarAkun.querySelectorAll('.rb-akun[data-role="' + roleId + '"] input').forEach(c => c.checked = true);
        segarkanAkun();
    }

    // ===== simpan =====
    document.getElementById('formRequest').addEventListener('submit', e => {
        const pesan = [];
        if (!document.getElementById('judul').value.trim()) pesan.push('Judul request wajib diisi.');
        FIELDS.forEach((f, i) => { if (!String(f.label).trim()) pesan.push('Isian #' + (i + 1) + ' belum ada pertanyaan / judulnya.'); });
        if (!FIELDS.some(f => f.tipe !== 'bagian')) pesan.push('Tambahkan minimal satu pertanyaan.');
        if (!daftarAkun.querySelector('input:checked')) pesan.push('Pilih minimal satu akun yang bisa mengisi.');
        if (pesan.length) {
            e.preventDefault();
            Swal.fire({ icon: 'warning', title: 'Lengkapi dulu', html: pesan.map(esc).join('<br>'), confirmButtonColor: '#04a9f5' });
            return;
        }
        document.getElementById('fieldsJson').value = JSON.stringify(FIELDS);
    });

    render();
    segarkanAkun();
</script>
<?= view('RequestForm/_bawah') ?>
