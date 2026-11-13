<?php
/**
 * Daftar centang halaman per grup (dipakai modal Role & modal Akun di HakAkses/index).
 * @var array<string, array<string, string>> $grup  dari Config\HakAkses::$grup
 */
?>
<div class="ha-halaman">
    <?php foreach ($grup as $namaGrup => $isi) : ?>
        <div class="ha-grup">
            <div class="ha-grup-head">
                <label class="ha-cek" style="padding:0">
                    <input type="checkbox" class="ha-semua">
                    <span><?= esc($namaGrup) ?></span>
                </label>
                <span class="ha-meta ha-hitung">0/<?= count($isi) ?></span>
            </div>
            <div class="ha-grup-list">
                <?php foreach ($isi as $kunci => $label) : ?>
                    <label class="ha-cek">
                        <input type="checkbox" name="halaman[]" value="<?= esc($kunci, 'attr') ?>">
                        <span><?= esc($label) ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="ha-meta" style="padding:2px 4px">
        <i class="ti ti-lock"></i> Menu <b>Hak Akses</b>, tambah akun &amp; hapus akun selalu khusus Administrator.
    </div>
</div>
