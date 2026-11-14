<?php

use App\Models\SuratTugasModel;

// Form tambah & edit Surat Tugas ($st null = tambah). Nilai: input lama (gagal validasi) > data tersimpan > bawaan.
$edit = $st !== null;
$nilai = static function (string $k, $bawaan = '') use ($st) {
    $lama = old($k, null, false);
    if ($lama !== null) {
        return $lama;
    }

    return $st[$k] ?? $bawaan;
};

$teknisi = old('teknisi', null, false) ?? ($edit ? SuratTugasModel::teknisi($st) : []);
$teknisi = array_values((array) $teknisi) ?: [''];

if (old('perangkat_merk', null, false) !== null) {
    $perangkat = [];
    foreach ((array) old('perangkat_merk', null, false) as $i => $m) {
        $perangkat[] = [
            'merk'  => $m,
            'jenis' => old('perangkat_jenis', null, false)[$i] ?? '',
            'sn'    => old('perangkat_sn', null, false)[$i] ?? '',
        ];
    }
} else {
    $perangkat = $edit ? SuratTugasModel::perangkat($st) : [];
}
$perangkat = $perangkat ?: [['merk' => '', 'jenis' => '', 'sn' => '']];

$perusahaanPilih = $nilai('perusahaan', 'PT JURAGAN WIFI INDONESIA');
$adaTtd = $edit && ! empty($st['ttd']) && is_file(SuratTugasModel::folderTtd() . $st['ttd']);
// Penanda tangan: form tambah dibuka kosong (diisi otomatis saat perusahaan diganti, bila ada surat sebelumnya)
$penandaAwal = ['nama' => '', 'jabatan' => ''];
?>
<!doctype html>
<html lang="en" class="light">

<head>
    <meta charset="utf-8" />
    <link rel="icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="shortcut icon" type="image/png" href="<?= base_url('favicon-N.png') ?>?v=4">
    <link rel="apple-touch-icon" href="<?= base_url('favicon-N.png') ?>?v=4">
    <title><?= $edit ? 'Edit' : 'Form' ?> Surat Tugas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?= view('SuratTugas/_head') ?>
    <style>
        /* Daftar teknisi & tabel perangkat */
        .st-row {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-bottom: 8px;
        }

        .st-row .rf-input {
            flex: 1;
            min-width: 0;
        }

        .st-del {
            flex-shrink: 0;
            width: 38px;
            height: 38px;
            border-radius: 8px;
            color: #ef4444;
            background: #feecec;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .st-del:hover {
            background: #fcd9d9;
        }

        .st-add {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #2f6bff;
            background: #e8efff;
        }

        .st-add:hover {
            background: #d6e3ff;
        }

        .st-no {
            flex-shrink: 0;
            width: 26px;
            text-align: center;
            font-weight: 600;
            color: #64748b;
        }

        .st-head {
            display: flex;
            gap: 8px;
            margin-bottom: 6px;
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
        }

        .st-head span {
            flex: 1;
        }

        .st-preview {
            margin-top: 6px;
            padding: 10px 12px;
            border-radius: 8px;
            background: #f1f5f9;
            color: #3b4754;
            font-size: 13px;
        }

        .dark .st-preview {
            background: rgba(255, 255, 255, .05);
            color: #bfc8d6;
        }

        .st-ttd-img {
            max-height: 110px;
            max-width: 260px;
            object-fit: contain;
            background: #fff;
            border: 1px dashed #cbd5e1;
            border-radius: 8px;
            padding: 6px;
        }

        @media (max-width: 640px) {
            .st-head {
                display: none;
            }

            .st-perangkat .st-row {
                flex-wrap: wrap;
            }

            .st-perangkat .st-row .rf-input {
                flex: 1 1 100%;
            }
        }
    </style>
</head>

<body class="text-[#37474f] dark:text-[#bfc8d6]">
    <?= view('SuratTugas/_sidebar') ?>

        <div class="rfo-page p-6">
            <!-- JUDUL -->
            <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                <div class="flex items-center gap-4">
                    <span class="rfo-title-ic <?= $edit ? 'is-amber' : 'is-blue' ?>"><i class="ti <?= $edit ? 'ti-edit' : 'ti-file-plus' ?>"></i></span>
                    <div>
                        <h1 class="rfo-title"><?= $edit ? 'Edit Surat Tugas' : 'Tambah Surat Tugas' ?></h1>
                        <p class="rfo-subtitle"><?= $edit ? 'Ubah data Surat Tugas ' . esc($st['nomor']) : 'Isi data penugasan teknisi untuk membuat Surat Tugas baru' ?></p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="<?= site_url('SuratTugas') ?>" class="rfo-btn rfo-btn-gray">
                        <i class="ti ti-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            <form action="<?= site_url($edit ? 'SuratTugas/update' : 'SuratTugas/save') ?>" method="POST" id="stForm" class="rfo-form" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <?php if ($edit) : ?>
                    <input type="hidden" name="id" value="<?= (int) $st['id'] ?>">
                <?php endif; ?>

                <div class="rfo-panel">
                    <!-- ===== DATA SURAT ===== -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rf-group">
                            <label class="rf-label" for="nomor"><i class="ti ti-hash" style="color:#6d5dfc"></i> Nomor Surat <span class="req">*</span></label>
                            <input type="text" name="nomor" id="nomor" required readonly tabindex="-1" maxlength="50" value="<?= esc($edit ? $nomor : old('nomor', $nomor, false)) ?>" class="rf-input" style="background:rgba(148,163,184,.15);cursor:not-allowed;font-weight:600">
                        </div>
                        <div class="rf-group">
                            <label class="rf-label" for="tanggal_surat"><i class="ti ti-calendar"></i> Tanggal Surat <span class="req">*</span></label>
                            <input type="date" name="tanggal_surat" id="tanggal_surat" required value="<?= esc($nilai('tanggal_surat', date('Y-m-d'))) ?>" class="rf-input">
                        </div>
                    </div>

                    <div class="rf-group">
                        <label class="rf-label" for="perusahaan"><i class="ti ti-building-skyscraper"></i> Perusahaan / Logo <span class="req">*</span></label>
                        <select name="perusahaan" id="perusahaan" required class="rf-input">
                            <option value="">-- Pilih Perusahaan --</option>
                            <?php foreach ($perusahaan as $nama => $logo) : ?>
                                <option value="<?= esc($nama) ?>" data-logo="<?= base_url($logo) ?>" data-kode="<?= esc($kodeNomor[$nama] ?? '') ?>" <?= $perusahaanPilih === $nama ? 'selected' : '' ?>><?= esc($nama) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div id="logo_box" class="rfo-logo-box hidden">
                            <img id="logo_preview" src="" alt="logo perusahaan" class="h-20 w-auto max-w-full object-contain">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rf-group">
                            <label class="rf-label" for="mitra"><i class="ti ti-building-community" style="color:#2f6bff"></i> Client Colocation</label>
                            <input type="text" name="mitra" id="mitra" placeholder="Nama mitra / client colocation" maxlength="150" value="<?= esc($nilai('mitra')) ?>" class="rf-input">
                        </div>
                        <div class="rf-group">
                            <label class="rf-label" for="kegiatan"><i class="ti ti-briefcase"></i> Kegiatan <span class="req">*</span></label>
                            <input type="text" name="kegiatan" id="kegiatan" placeholder="Kegiatan yang dilaksanakan" required maxlength="255" value="<?= esc($nilai('kegiatan')) ?>" class="rf-input">
                        </div>
                    </div>

                    <!-- ===== RINCIAN TUGAS ===== -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rf-group">
                            <label class="rf-label" for="tanggal_mulai"><i class="ti ti-calendar-event"></i> Tanggal Mulai <span class="req">*</span></label>
                            <input type="date" name="tanggal_mulai" id="tanggal_mulai" required value="<?= esc($nilai('tanggal_mulai')) ?>" class="rf-input">
                        </div>
                        <div class="rf-group">
                            <label class="rf-label" for="tanggal_selesai"><i class="ti ti-calendar-check" style="color:#16a34a"></i> Tanggal Selesai <span class="req">*</span></label>
                            <input type="date" name="tanggal_selesai" id="tanggal_selesai" required value="<?= esc($nilai('tanggal_selesai')) ?>" class="rf-input">
                            <p class="rf-hint">Isi sama dengan tanggal mulai bila tugas hanya satu hari.</p>
                        </div>
                    </div>
                    <div id="hariPreview" class="st-preview hidden" style="margin:-6px 0 16px"></div>

                    <div class="rf-group">
                        <label class="rf-label" for="keperluan"><i class="ti ti-tool"></i> Keperluan <span class="req">*</span></label>
                        <input type="text" name="keperluan" id="keperluan" placeholder="Keperluan penugasan" required maxlength="255" value="<?= esc($nilai('keperluan')) ?>" class="rf-input">
                    </div>

                    <div class="rf-group">
                        <label class="rf-label"><i class="ti ti-users" style="color:#6d5dfc"></i> Teknisi <span class="req">*</span></label>
                        <div id="teknisiList">
                            <?php foreach ($teknisi as $nm) : ?>
                                <div class="st-row">
                                    <input type="text" name="teknisi[]" maxlength="100" placeholder="Nama teknisi" value="<?= esc($nm) ?>" class="rf-input">
                                    <button type="button" class="st-del" onclick="hapusBaris(this, 'teknisiList')" title="Hapus"><i class="ti ti-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="st-add" onclick="tambahTeknisi()"><i class="ti ti-plus"></i> Tambah Teknisi</button>
                    </div>

                    <div class="rf-group">
                        <label class="rf-label" for="lokasi"><i class="ti ti-map-pin" style="color:#ef4444"></i> Lokasi <span class="req">*</span></label>
                        <input type="text" name="lokasi" id="lokasi" placeholder="Lokasi tugas" required maxlength="255" value="<?= esc($nilai('lokasi')) ?>" class="rf-input">
                    </div>

                    <!-- ===== PERANGKAT ===== -->
                    <div class="rf-group st-perangkat">
                        <label class="rf-label"><i class="ti ti-device-desktop" style="color:#6d5dfc"></i> Daftar Perangkat</label>
                        <div class="st-head">
                            <b class="st-no">No</b><span>Merk Perangkat</span><span>Jenis Perangkat</span><span>Serial Number</span><b style="width:38px"></b>
                        </div>
                        <div id="perangkatList">
                            <?php foreach ($perangkat as $i => $p) : ?>
                                <div class="st-row">
                                    <b class="st-no"><?= $i + 1 ?></b>
                                    <input type="text" name="perangkat_merk[]" placeholder="Merk perangkat" maxlength="100" value="<?= esc($p['merk'] ?? '') ?>" class="rf-input">
                                    <input type="text" name="perangkat_jenis[]" placeholder="Jenis perangkat" maxlength="100" value="<?= esc($p['jenis'] ?? '') ?>" class="rf-input">
                                    <input type="text" name="perangkat_sn[]" maxlength="100" placeholder="Serial number perangkat" value="<?= esc($p['sn'] ?? '') ?>" class="rf-input">
                                    <button type="button" class="st-del" onclick="hapusBaris(this, 'perangkatList')" title="Hapus"><i class="ti ti-trash"></i></button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" class="st-add" onclick="tambahPerangkat()"><i class="ti ti-plus"></i> Tambah Perangkat</button>
                    </div>

                    <div class="rf-group">
                        <label class="rf-label" for="penutup"><i class="ti ti-file-description"></i> Keperluan Surat</label>
                        <input type="text" name="penutup" id="penutup" placeholder="Keperluan surat dibuat" maxlength="255" value="<?= esc($nilai('penutup')) ?>" class="rf-input">
                    </div>

                    <!-- ===== PENANDA TANGAN ===== -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="rf-group">
                            <label class="rf-label" for="ttd_nama"><i class="ti ti-user" style="color:#6d5dfc"></i> Nama Penanda Tangan <span class="req">*</span></label>
                            <input type="text" name="ttd_nama" id="ttd_nama" placeholder="Nama penanda tangan" required maxlength="100" value="<?= esc($nilai('ttd_nama', $penandaAwal['nama'])) ?>" class="rf-input">
                        </div>
                        <div class="rf-group">
                            <label class="rf-label" for="ttd_jabatan"><i class="ti ti-id-badge-2"></i> Jabatan <span class="req">*</span></label>
                            <input type="text" name="ttd_jabatan" id="ttd_jabatan" placeholder="Jabatan penanda tangan" required maxlength="100" value="<?= esc($nilai('ttd_jabatan', $penandaAwal['jabatan'])) ?>" class="rf-input">
                        </div>
                    </div>

                    <div class="rf-group">
                        <label class="rf-label" for="ttd_file"><i class="ti ti-signature" style="color:#2f6bff"></i> Tanda Tangan &amp; Stempel</label>
                        <?php if ($adaTtd) : ?>
                            <div class="flex flex-wrap items-end gap-4 mb-2">
                                <img src="<?= site_url('SuratTugas/ttd/' . $st['id']) ?>" alt="Tanda tangan" class="st-ttd-img">
                                <label class="inline-flex items-center gap-2 text-sm text-red-600 cursor-pointer">
                                    <input type="checkbox" name="hapus_ttd" value="1"> Hapus tanda tangan ini
                                </label>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="ttd_file" id="ttd_file" accept="image/png,image/jpeg,image/webp" class="rf-input">
                    </div>

                    <div class="rfo-actions">
                        <button type="submit" class="rfo-btn rfo-btn-blue"><i class="ti ti-device-floppy"></i> <?= $edit ? 'Simpan Perubahan' : 'Simpan' ?></button>
                        <button type="button" class="rfo-btn rfo-btn-gray" onclick="window.location.href='<?= site_url('SuratTugas') ?>'"><i class="ti ti-arrow-left"></i> Kembali</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?= view('SuratTugas/_scripts') ?>

    <!-- Templat baris baru -->
    <template id="tplTeknisi">
        <div class="st-row">
            <input type="text" name="teknisi[]" maxlength="100" placeholder="Nama teknisi" class="rf-input">
            <button type="button" class="st-del" onclick="hapusBaris(this, 'teknisiList')" title="Hapus"><i class="ti ti-trash"></i></button>
        </div>
    </template>
    <template id="tplPerangkat">
        <div class="st-row">
            <b class="st-no"></b>
            <input type="text" name="perangkat_merk[]" placeholder="Merk perangkat" maxlength="100" class="rf-input">
            <input type="text" name="perangkat_jenis[]" placeholder="Jenis perangkat" maxlength="100" class="rf-input">
            <input type="text" name="perangkat_sn[]" maxlength="100" placeholder="Serial number perangkat" class="rf-input">
            <button type="button" class="st-del" onclick="hapusBaris(this, 'perangkatList')" title="Hapus"><i class="ti ti-trash"></i></button>
        </div>
    </template>

    <script>
        const EDIT = <?= $edit ? 'true' : 'false' ?>;

        // ====== TEKNISI & PERANGKAT (tambah / hapus baris) ======
        function nomoriPerangkat() {
            document.querySelectorAll('#perangkatList .st-no').forEach((el, i) => el.textContent = i + 1);
        }

        function tambahBaris(tplId, listId) {
            const list = document.getElementById(listId);
            list.appendChild(document.getElementById(tplId).content.cloneNode(true));
            nomoriPerangkat();
            list.lastElementChild.querySelector('input').focus();
        }

        function tambahTeknisi() {
            tambahBaris('tplTeknisi', 'teknisiList');
        }

        function tambahPerangkat() {
            tambahBaris('tplPerangkat', 'perangkatList');
        }

        // Baris terakhir tidak dihapus, hanya dikosongkan
        function hapusBaris(btn, listId) {
            const list = document.getElementById(listId);
            const row = btn.closest('.st-row');
            if (list.children.length > 1) row.remove();
            else row.querySelectorAll('input').forEach(i => i.value = '');
            nomoriPerangkat();
        }

        // ====== PREVIEW LOGO + KODE NOMOR ======
        function showLogo() {
            const opt = document.getElementById('perusahaan').selectedOptions[0];
            const box = document.getElementById('logo_box');
            if (opt && opt.dataset.logo) {
                document.getElementById('logo_preview').src = opt.dataset.logo;
                box.classList.remove('hidden');
            } else {
                box.classList.add('hidden');
            }
        }

        // ====== NOMOR SURAT: {tanggal}/{kode}.{bulan romawi}/{tahun}, contoh 14/JWI.XI/2026 ======
        const ROMAWI = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII'];
        const PENANDA = <?= json_encode($penanda, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?>;

        function isoLokal(d) {
            return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
        }

        // Nomor tidak bisa diisi manual:
        // - form tambah: tanggal & bulan dari jam komputer (Windows) saat form dibuka
        // - form edit: tanggal/bulan/tahun nomor lama tetap, hanya kode yang ikut perusahaan
        function susunNomor() {
            const opt = document.getElementById('perusahaan').selectedOptions[0];
            const kode = opt ? opt.dataset.kode : '';
            const nomor = document.getElementById('nomor');
            if (!kode) return;
            if (EDIT) {
                const bagian = nomor.value.split('/');
                if (bagian.length === 3) {
                    const titik = bagian[1].indexOf('.');
                    bagian[1] = kode + (titik >= 0 ? bagian[1].slice(titik) : '');
                    nomor.value = bagian.join('/');
                }
                return;
            }
            const d = new Date();
            nomor.value = d.getDate() + '/' + kode + '.' + ROMAWI[d.getMonth()] + '/' + d.getFullYear();
        }

        // Form tambah: tanggal surat & nomor mengikuti tanggal di komputer (Windows)
        if (!EDIT) {
            if (!<?= old('tanggal_surat') ? 'true' : 'false' ?>) {
                document.getElementById('tanggal_surat').value = isoLokal(new Date());
            }
            susunNomor();
        }

        // Ganti perusahaan: logo, kode nomor & penanda tangan (dari surat terakhir perusahaan itu) ikut berubah
        document.getElementById('perusahaan').addEventListener('change', function() {
            showLogo();
            susunNomor();
            const p = PENANDA[this.value];
            if (p && p.nama) {
                document.getElementById('ttd_nama').value = p.nama;
                document.getElementById('ttd_jabatan').value = p.jabatan;
            }
        });
        showLogo();

        // ====== PREVIEW HARI / TANGGAL DI SURAT ======
        const HARI = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const BULAN = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        function tglIndo(d) {
            return d.getDate() + ' ' + BULAN[d.getMonth()] + ' ' + d.getFullYear();
        }

        function previewHari() {
            const a = document.getElementById('tanggal_mulai').value;
            const b = document.getElementById('tanggal_selesai').value;
            const box = document.getElementById('hariPreview');
            if (!a || !b || b < a) {
                box.classList.add('hidden');
                return;
            }
            const da = new Date(a + 'T00:00:00'),
                db = new Date(b + 'T00:00:00');
            let hari = HARI[da.getDay()],
                tgl = tglIndo(da);
            if (a !== b) {
                hari += ' - ' + HARI[db.getDay()];
                if (da.getFullYear() === db.getFullYear() && da.getMonth() === db.getMonth()) {
                    tgl = da.getDate() + '-' + db.getDate() + ' ' + BULAN[db.getMonth()] + ' ' + db.getFullYear();
                } else if (da.getFullYear() === db.getFullYear()) {
                    tgl = da.getDate() + ' ' + BULAN[da.getMonth()] + ' - ' + tglIndo(db);
                } else {
                    tgl = tglIndo(da) + ' - ' + tglIndo(db);
                }
            }
            box.innerHTML = '<i class="ti ti-eye"></i> Di surat: <b>Hari</b> : ' + hari + ' &nbsp;|&nbsp; <b>Tanggal</b> : ' + tgl;
            box.classList.remove('hidden');
        }
        // Tanggal selesai otomatis ikut tanggal mulai bila masih kosong / lebih awal
        document.getElementById('tanggal_mulai').addEventListener('change', function() {
            const s = document.getElementById('tanggal_selesai');
            if (this.value && (!s.value || s.value < this.value)) s.value = this.value;
            previewHari();
        });
        document.getElementById('tanggal_selesai').addEventListener('change', previewHari);
        previewHari();

        // ====== VALIDASI SEBELUM SUBMIT ======
        document.getElementById('stForm').addEventListener('submit', function(e) {
            const wajib = ['nomor', 'tanggal_surat', 'perusahaan', 'kegiatan', 'tanggal_mulai', 'tanggal_selesai', 'keperluan', 'lokasi', 'ttd_nama', 'ttd_jabatan']
                .map(id => document.getElementById(id).value.trim());
            const adaTeknisi = [...document.querySelectorAll('input[name="teknisi[]"]')].some(i => i.value.trim() !== '');
            const mulai = document.getElementById('tanggal_mulai').value;
            const selesai = document.getElementById('tanggal_selesai').value;
            const file = document.getElementById('ttd_file').files[0];

            let msg = '';
            if (wajib.includes('')) msg = 'Semua field bertanda * wajib diisi.';
            else if (!adaTeknisi) msg = 'Isi minimal satu nama teknisi.';
            else if (selesai < mulai) msg = 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.';
            else if (file && file.size > 2 * 1024 * 1024) msg = 'File tanda tangan maksimal 2 MB.';

            if (msg) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Form Belum Lengkap',
                    text: msg,
                    confirmButtonColor: '#2f6bff'
                });
                return false;
            }
        });
    </script>
</body>

</html>
