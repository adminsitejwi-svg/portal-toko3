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

    <div class="ha-grup">
        <div class="ha-grup-head">
            <span><i class="ti ti-lock"></i> Informasi</span>
            <span class="ha-meta">selalu terbuka</span>
        </div>
        <div class="ha-grup-list">
            <?php foreach (['Profile', 'Pengguna', 'Change Log'] as $l) : ?>
                <label class="ha-cek kunci"><input type="checkbox" checked disabled><span><?= $l ?></span></label>
            <?php endforeach; ?>
            <label class="ha-cek kunci"><input type="checkbox" disabled><span>Hak Akses (khusus Administrator)</span></label>
        </div>
    </div>
</div>
