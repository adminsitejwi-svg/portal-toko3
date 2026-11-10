<?php
// Body email Survey Report: surat pengantar singkat, dokumen lengkap ada di lampiran PDF.
// Nomor & e-mail NOC mengikuti perusahaan; format sama dengan body email RFO (RFO/EmailRFO.php).
use App\Models\RFOModel;

$font = "font-family:Calibri,Carlito,'Segoe UI',Arial,sans-serif;font-size:14px;line-height:1.5;color:#000;";

$bulan   = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$tglSurvey = $sr['tanggal_pelaksanaan']
    ? (int) substr($sr['tanggal_pelaksanaan'], 8, 2) . ' ' . $bulan[(int) substr($sr['tanggal_pelaksanaan'], 5, 2)] . ' ' . substr($sr['tanggal_pelaksanaan'], 0, 4)
    : '-';

// Penanda unik tak terlihat di tiap bagian agar Gmail tidak memangkas isi yang sama dengan email sebelumnya
$unik  = date('YmdHis') . bin2hex(random_bytes(4));
$tanda = static fn (string $bagian) => '<span style="display:none !important;font-size:0;line-height:0;max-height:0;max-width:0;overflow:hidden;opacity:0;color:transparent;mso-hide:all;">' . $unik . '-' . $bagian . '</span>';
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Report <?= esc($sr['survey_id']) ?></title>
</head>

<body style="margin:0;padding:16px;background:#fff;<?= $font ?>">
    <p style="margin:0 0 16px;">Kepada Yth. <?= esc($sr['nama_pelanggan']) ?>,<?= $tanda('a') ?></p>

    <p style="margin:0;">
        <?= $tanda('b') ?>Terima kasih atas kepercayaannya dalam menggunakan jasa layanan kami.<br>
        Bersama ini kami sampaikan Survey Report atas pekerjaan survey yang telah dilaksanakan<br>
        pada tanggal <?= esc($tglSurvey) ?>, detail terlampir.<br>
        Silahkan menghubungi kami di <?= esc($nomorWa) ?> atau email ke <a href="mailto:<?= esc($emailNoc, 'attr') ?>" style="color:#1155cc;"><?= esc($emailNoc) ?></a> apabila bapak/ibu membutuhkan informasi maupun bantuan lebih lanjut.<?= $tanda('c') ?>
    </p>

    <p style="margin:24px 0 0;font-weight:bold;">
        <?= $tanda('d') ?>Best Regards<br>
        <?= esc($sr['pelaksana'] ?: 'NOC') ?>
    </p>

    <p style="margin:32px 0 0;">
        <?= $tanda('e') ?><b><?= esc(strtoupper($namaTtd)) ?></b><br>
        <?= implode('<br>', array_map(static fn ($l) => str_replace('  ', ' &nbsp;', esc($l)), RFOModel::ALAMAT_KANTOR)) ?><?= $tanda('f') ?>
    </p>
</body>

</html>
