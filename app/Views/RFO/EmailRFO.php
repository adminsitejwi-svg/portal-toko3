<?php
// Body email RFO: surat pengantar singkat, detail RFO ada di lampiran PDF (dibuat otomatis dari View).
// Nomor & e-mail NOC mengikuti perusahaan RFO; nama di bawah "Best Regards" = petugas NOC RFO.
use App\Models\RFOModel;

$font = "font-family:Calibri,Carlito,'Segoe UI',Arial,sans-serif;font-size:14px;line-height:1.5;color:#000;";

// Penanda unik tak terlihat di tiap bagian: Gmail menyembunyikan teks yang sama dengan email
// sebelumnya di thread yang sama (tombol "..." / tampilkan konten yang dipangkas).
// Dengan penanda berbeda per email, isi tidak dianggap berulang sehingga selalu tampil utuh.
$unik  = date('YmdHis') . bin2hex(random_bytes(4));
$tanda = static fn (string $bagian) => '<span style="display:none !important;font-size:0;line-height:0;max-height:0;max-width:0;overflow:hidden;opacity:0;color:transparent;mso-hide:all;">' . $unik . '-' . $bagian . '</span>';
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RFO <?= esc($rfo['rfo_id']) ?></title>
</head>

<body style="margin:0;padding:16px;background:#fff;<?= $font ?>">
    <p style="margin:0 0 16px;">Kepada Pelanggan Yth,<?= $tanda('a') ?></p>

    <p style="margin:0;">
        <?= $tanda('b') ?>Terima kasih atas kepercayaannya dalam menggunakan jasa layanan kami.<br>
        Bersama ini kami infomasikan detail RFO atas gangguan yang telah terjadi sebelumnya<br>
        file terlampir,<br>
        Kami mohon maaf yang sebesar-besarnya atas gangguan yang telah terjadi.<br>
        Silahkan menghubungi kami di <?= esc($nomorWa) ?> atau email ke <a href="mailto:<?= esc($emailNoc, 'attr') ?>" style="color:#1155cc;"><?= esc($emailNoc) ?></a> apabila bapak/ibu membutuhkan informasi maupun bantuan lebih lanjut.<?= $tanda('c') ?>
    </p>

    <!-- Salam penutup & nama petugas: tebal, diberi jarak dari paragraf di atas -->
    <p style="margin:24px 0 0;font-weight:bold;">
        <?= $tanda('d') ?>Best Regards<br>
        <?= esc(!empty($petugas) ? implode(' / ', $petugas) : 'NOC') ?>
    </p>

    <p style="margin:32px 0 0;">
        <?= $tanda('e') ?><b><?= esc(strtoupper($namaTtd)) ?></b><br>
        <?= implode('<br>', array_map(static fn ($l) => str_replace('  ', ' &nbsp;', esc($l)), RFOModel::ALAMAT_KANTOR)) ?><?= $tanda('f') ?>
    </p>
</body>

</html>
