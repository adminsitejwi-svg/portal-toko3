<?php
// Isi form tambah/edit Rekap Data Toko. Dipakai FormTambahRKD.php & FormEditRKD.php.
// Variabel: $bagian (fo/modem/vsat), $row (kosong = tambah data)
use App\Models\RekapDataTokoModel as Rekap;

$info     = Rekap::BAGIAN[$bagian];
$isEdit   = ! empty($row['id']);
$nilai    = static fn (string $f, string $awal = '') => (string) old($f, $row[$f] ?? $awal, false);
$inputCls = 'w-full min-h-[46px] px-4 py-3 text-sm border border-[#e3e8ee] rounded-lg text-[#3b4754] bg-white focus:border-primary-500 outline-none';

// Dokumen tersimpan sebagai file upload (uploads/rekap_toko/...) atau link/teks (mis. Google Drive)
$isLocalDoc = static fn ($p) => is_string($p) && str_starts_with($p, 'uploads/rekap_toko/');

// Dropdown dengan pilihan "Isi Manual": [pilihan terpilih, isi manual]
$pilihan = static function (string $f, array $daftar) use ($row): array {
    $lama = old($f, null, false);
    if ($lama !== null) {
        return [(string) $lama, (string) old($f . '_manual', '', false)];
    }
    $db = (string) ($row[$f] ?? '');

    return $db === '' || in_array($db, $daftar, true) ? [$db, ''] : ['manual', $db];
};

// Kolom dropdown + isi manual
$dropdownManual = static function (string $f, string $judul, array $daftar, bool $wajib) use ($pilihan, $inputCls): void {
    [$dipilih, $manual] = $pilihan($f, $daftar);
    ?>
    <div class="form-group md:col-span-2">
        <label><?= esc($judul) ?> <?php if ($wajib) : ?><span style="color:red">*</span><?php endif; ?></label>
        <select name="<?= $f ?>" id="r_<?= $f ?>" <?= $wajib ? 'required' : '' ?> data-manual class="<?= $inputCls ?>">
            <option value="">-- Pilih <?= esc($judul) ?> --</option>
            <?php foreach ($daftar as $d) : ?>
                <option value="<?= esc($d, 'attr') ?>" <?= $dipilih === $d ? 'selected' : '' ?>><?= esc($d) ?></option>
            <?php endforeach; ?>
            <option value="manual" <?= $dipilih === 'manual' ? 'selected' : '' ?>>-- Isi Manual --</option>
        </select>
        <div id="wrap_<?= $f ?>_manual" class="<?= $dipilih === 'manual' ? '' : 'hidden' ?> mt-2">
            <input type="text" name="<?= $f ?>_manual" id="r_<?= $f ?>_manual" maxlength="100"
                placeholder="Ketik <?= esc($judul, 'attr') ?> secara manual" value="<?= esc($manual, 'attr') ?>" class="<?= $inputCls ?>">
        </div>
    </div>
    <?php
};

// Contoh isian
$contoh = [
    'msisdn' => '08111930423',
    'iccid'  => '6210081415496256',
];
?>
<div class="rfo-page p-6">
    <!-- JUDUL -->
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <span class="rfo-title-ic is-blue"><i class="ti <?= $isEdit ? 'ti-edit' : 'ti-file-plus' ?>"></i></span>
            <div>
                <h1 class="rfo-title"><?= $isEdit ? 'Edit' : 'Form' ?> <?= esc($info['nama']) ?></h1>
                <p class="rfo-subtitle">Rekap Data Toko</p>
            </div>
        </div>
        <a href="<?= site_url($info['url']) ?>" class="rfo-btn rfo-btn-gray">
            <i class="ti ti-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Tampilkan error validasi server jika ada -->
    <?php if (session()->getFlashdata('errors')) : ?>
        <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-700 text-sm">
            <div class="font-semibold mb-1"><i class="ti ti-alert-circle"></i> Periksa kembali isian berikut:</div>
            <ul class="list-disc pl-5 space-y-0.5">
                <?php foreach (session()->getFlashdata('errors') as $err) : ?>
                    <li><?= esc($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="rfo-panel rfo-form">
        <form action="<?= site_url($isEdit ? 'RekapDataMidi/update' : 'RekapDataMidi/save') ?>" method="POST" enctype="multipart/form-data" id="reportForm">
            <?= csrf_field() ?>
            <?php if ($isEdit) : ?>
                <input type="hidden" name="id" value="<?= (int) $row['id'] ?>">
            <?php else : ?>
                <input type="hidden" name="kategori" value="<?= esc($bagian, 'attr') ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-4 mt-5">
                <div class="form-group">
                    <label>Nama Pengisi <span style="color:red">*</span></label>
                    <input type="text" name="nama_pengisi" id="r_nama_pengisi" maxlength="100" required placeholder="Masukkan Nama Pengisi"
                        value="<?= esc($nilai('nama_pengisi', (string) session('username')), 'attr') ?>" class="<?= $inputCls ?>">
                </div>

                <div class="form-group">
                    <label>Perusahaan <span style="color:red">*</span></label>
                    <?php
                    // perusahaan lama yang sudah tidak ada di pilihan tetap tampil saat datanya diedit
                    $daftarPerusahaan = Rekap::PERUSAHAAN;
                    if (! empty($row['perusahaan']) && ! in_array($row['perusahaan'], $daftarPerusahaan, true)) {
                        $daftarPerusahaan[] = $row['perusahaan'];
                    }
                    ?>
                    <select name="perusahaan" id="r_perusahaan" required class="<?= $inputCls ?>">
                        <option value="">-- Pilih Perusahaan --</option>
                        <?php foreach ($daftarPerusahaan as $p) : ?>
                            <option value="<?= esc($p, 'attr') ?>" <?= $nilai('perusahaan') === $p ? 'selected' : '' ?>><?= esc($p) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal <span style="color:red">*</span></label>
                    <input type="date" name="tanggal" id="r_tanggal" required value="<?= esc($nilai('tanggal', date('Y-m-d')), 'attr') ?>" class="<?= $inputCls ?>">
                </div>

                <div class="form-group">
                    <label>Toko <span style="color:red">*</span></label>
                    <select name="toko" id="r_toko" required class="<?= $inputCls ?>">
                        <option value="">-- Pilih Toko --</option>
                        <?php foreach (Rekap::TOKO as $t) : ?>
                            <option value="<?= esc($t, 'attr') ?>" <?= $nilai('toko') === $t ? 'selected' : '' ?>><?= esc($t) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <?php $dropdownManual('branch', 'Branch', Rekap::BRANCH, true); ?>

                <div class="form-group">
                    <label>Kode Toko <span style="color:red">*</span></label>
                    <input type="text" name="kode_toko" id="r_kode_toko" maxlength="50" required placeholder="Contoh: L001 / M123" value="<?= esc($nilai('kode_toko'), 'attr') ?>" class="<?= $inputCls ?>">
                </div>

                <div class="form-group">
                    <label>Nama Toko <span style="color:red">*</span></label>
                    <input type="text" name="nama_toko" id="r_nama_toko" maxlength="150" required placeholder="Masukkan Nama Toko" value="<?= esc($nilai('nama_toko'), 'attr') ?>" class="<?= $inputCls ?>">
                </div>

                <!-- Isian khusus bagian ini -->
                <?php foreach ($info['grup'] as $judulGrup => $kolomGrup) : ?>
                    <div class="form-group md:col-span-2 border-t border-[#e3e8ee] dark:border-white/10 pt-4 mt-2">
                        <h6 class="text-sm font-semibold text-primary-600 mb-1"><?= esc($judulGrup) ?></h6>
                    </div>

                    <?php foreach ($kolomGrup as $f) : ?>
                        <?php
                        [$judul, $jenis, $maks] = Rekap::ISIAN[$f];
                        $wajib = in_array($f, $info['wajib'], true);
                        ?>

                        <?php if ($jenis === 'modem') : ?>
                            <?php $dropdownManual($f, $judul, Rekap::TIPE_MODEM, $wajib); ?>

                        <?php elseif ($jenis === 'dok') : ?>
                            <?php
                            $lokal = $isLocalDoc($row[$f] ?? null);
                            $mode  = (string) old('mode_' . $f, $lokal ? 'file' : 'link', false);
                            $link  = (string) old('link_' . $f, $lokal ? '' : ($row[$f] ?? ''), false);
                            ?>
                            <div class="form-group <?= $f === 'sn_ont_fo' ? '' : 'md:col-span-2' ?>">
                                <label><?= esc($judul) ?> <span class="text-gray-400 font-normal">(JPG, PNG, Word DOC/DOCX, PDF - maks. 5 MB)</span></label>
                                <div class="flex flex-wrap items-center gap-4 mb-2 text-sm">
                                    <label class="inline-flex items-center gap-2 mb-0 font-normal cursor-pointer">
                                        <input type="radio" name="mode_<?= $f ?>" value="link" data-dok="<?= $f ?>" <?= $mode !== 'file' ? 'checked' : '' ?>>
                                        <i class="ti ti-link"></i> Link / Teks
                                    </label>
                                    <label class="inline-flex items-center gap-2 mb-0 font-normal cursor-pointer">
                                        <input type="radio" name="mode_<?= $f ?>" value="file" data-dok="<?= $f ?>" <?= $mode === 'file' ? 'checked' : '' ?>>
                                        <i class="ti ti-upload"></i> Upload File
                                    </label>
                                </div>
                                <div id="wrap_link_<?= $f ?>" class="<?= $mode === 'file' ? 'hidden' : '' ?>">
                                    <input type="text" name="link_<?= $f ?>" id="r_link_<?= $f ?>" maxlength="<?= $maks ?>" placeholder="Tempel link Google Drive / isi keterangan"
                                        value="<?= esc($link, 'attr') ?>" class="<?= $inputCls ?>">
                                </div>
                                <div id="wrap_file_<?= $f ?>" class="<?= $mode === 'file' ? '' : 'hidden' ?>">
                                    <input type="file" name="<?= $f ?>" id="r_<?= $f ?>" data-judul="<?= esc($judul, 'attr') ?>" accept=".jpg,.jpeg,.png,.doc,.docx,.pdf" class="<?= $inputCls ?>">
                                    <?php if ($lokal) : ?>
                                        <div class="mt-2 text-sm flex flex-wrap items-center gap-3">
                                            <span class="text-gray-500">File saat ini:</span>
                                            <a href="<?= esc(base_url($row[$f]), 'attr') ?>" target="_blank" rel="noopener" class="text-blue-600 hover:underline inline-flex items-center gap-1">
                                                <i class="ti ti-paperclip"></i> <?= esc(basename($row[$f])) ?>
                                            </a>
                                            <label class="inline-flex items-center gap-2 mb-0 text-sm font-normal text-red-600">
                                                <input type="checkbox" name="hapus_<?= $f ?>" value="1"> Hapus file
                                            </label>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-1">Pilih file baru untuk mengganti file saat ini.</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                        <?php elseif ($jenis === 'area') : ?>
                            <div class="form-group md:col-span-2">
                                <label><?= esc($judul) ?> <?php if ($wajib) : ?><span style="color:red">*</span><?php endif; ?></label>
                                <textarea name="<?= $f ?>" id="r_<?= $f ?>" rows="3" <?= $wajib ? 'required' : '' ?>
                                    placeholder="Masukkan Link Sharelock Google Maps, Titik Koordinat, atau Alamat Lengkap Toko" class="<?= $inputCls ?>"><?= esc($nilai($f)) ?></textarea>
                            </div>

                        <?php else : ?>
                            <?php $cth = $contoh[strtok($f, '_')] ?? ''; ?>
                            <div class="form-group">
                                <label><?= esc($judul) ?> <?php if ($wajib) : ?><span style="color:red">*</span><?php endif; ?></label>
                                <?php if ($cth !== '') : ?>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-1.5">Contoh Jawaban : <?= $cth ?></p>
                                <?php endif; ?>
                                <input type="text" name="<?= $f ?>" id="r_<?= $f ?>" maxlength="<?= $maks ?>" <?= $wajib ? 'required' : '' ?>
                                    placeholder="<?= esc($cth ?: 'Masukkan ' . $judul, 'attr') ?>" value="<?= esc($nilai($f), 'attr') ?>" class="<?= $inputCls ?>">
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>

            <div class="d-flex">
                <button type="submit"><i class="ti ti-device-floppy"></i> <?= $isEdit ? 'Update' : 'Simpan' ?></button>
                <button type="button" class="btn-back" onclick="window.location.href='<?= site_url($info['url']) ?>'"><i class="ti ti-arrow-left"></i> Kembali</button>
            </div>
        </form>
    </div>
</div>

<script>
    (function() {
        const MAX_FILE = 5 * 1024 * 1024; // 5 MB
        const EXT_FILE = ['pdf', 'png', 'jpg', 'jpeg', 'doc', 'docx'];
        const form = document.getElementById('reportForm');
        const el = id => document.getElementById(id);

        // Dokumen: tampilkan input link/teks atau input upload sesuai pilihan
        form.querySelectorAll('input[data-dok]').forEach(r => r.addEventListener('change', function() {
            const file = this.value === 'file';
            el('wrap_link_' + this.dataset.dok).classList.toggle('hidden', file);
            el('wrap_file_' + this.dataset.dok).classList.toggle('hidden', !file);
        }));

        // Dropdown: pilihan "Isi Manual" menampilkan kotak isian
        form.querySelectorAll('select[data-manual]').forEach(s => s.addEventListener('change', function() {
            const manual = this.value === 'manual';
            const input = el('r_' + this.name + '_manual');
            el('wrap_' + this.name + '_manual').classList.toggle('hidden', !manual);
            if (manual) input.focus();
            else input.value = '';
        }));

        // ====== VALIDASI SEBELUM SUBMIT ======
        form.addEventListener('submit', function(e) {
            const peringatan = (title, text) => {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title,
                    text,
                    confirmButtonColor: '#185a82'
                });
            };

            for (const f of form.querySelectorAll('[required]')) {
                if (f.value.trim() === '') return peringatan('Form Belum Lengkap', 'Semua field bertanda * wajib diisi.');
            }
            for (const s of form.querySelectorAll('select[data-manual]')) {
                if (s.value === 'manual' && el('r_' + s.name + '_manual').value.trim() === '') {
                    const judul = s.options[0].text.replace(/^-- Pilih | --$/g, '');
                    return peringatan(judul + ' Kosong', 'Silakan ketik ' + judul + ' secara manual.');
                }
            }
            for (const f of form.querySelectorAll('input[type=file]')) {
                const file = f.files[0];
                if (!file || form.querySelector('input[name="mode_' + f.name + '"]:checked').value !== 'file') continue;
                if (!EXT_FILE.includes(file.name.split('.').pop().toLowerCase())) {
                    return peringatan('File Tidak Didukung', f.dataset.judul + ' harus berformat JPG, PNG, Word (DOC/DOCX), atau PDF.');
                }
                if (file.size > MAX_FILE) {
                    return peringatan('File Terlalu Besar', f.dataset.judul + ' maksimal berukuran 5 MB.');
                }
            }
        });
    })();
</script>
