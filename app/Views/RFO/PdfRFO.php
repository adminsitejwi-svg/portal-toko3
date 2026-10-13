<?php
// Surat RFO versi PDF (dompdf), tampilan & kalimat sama dengan halaman View (ViewRFO.php).
// $kopAtas / $kopBawah / $logo berupa data URI PNG dari App\Libraries\RfoPdf.
$hari  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
$bulan = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

$tsGangguan  = strtotime($rfo['waktu_gangguan']);
$hariTanggal = $hari[date('w', $tsGangguan)] . ', ' . date('j', $tsGangguan) . ' ' . $bulan[(int) date('n', $tsGangguan)] . ' ' . date('Y', $tsGangguan);
$fmtWaktu    = static fn ($dt) => $dt ? date('d/m/Y H:i', strtotime($dt)) . ' WIB' : '-';
$teks        = static fn ($v) => nl2br(esc($v));

$baris = [
    'Hari/Tanggal'         => $hariTanggal,
    'Jenis Gangguan'       => $rfo['penyebab'],
    'Impact'               => $rfo['impact'],
    'Start Tanggal/Waktu'  => $fmtWaktu($rfo['waktu_gangguan']),
    'Closed Tanggal/Waktu' => $fmtWaktu($rfo['waktu_selesai']),
    'Tindakan'             => $rfo['action'],
    'Status'               => $rfo['status'],
];
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title><?= esc($rfo['rfo_id']) ?></title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            margin: 0;
            font-family: Helvetica, Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #000;
        }

        /* Gelombang kop atas & bawah, menempel ke tepi kertas */
        .kop-atas {
            position: absolute;
            top: 0;
            left: 0;
            width: 210mm;
            height: 37mm;
        }

        .kop-bawah {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 210mm;
            height: 37mm;
        }

        /* Logo perusahaan kiri atas, RFO ID kanan atas */
        .head {
            position: absolute;
            top: 41mm;
            left: 7mm;
            width: 196mm;
        }

        .head td {
            vertical-align: middle;
        }

        .logo {
            height: 15mm;
        }

        .isi {
            position: absolute;
            top: 72mm;
            left: 26mm;
            right: 22mm;
        }

        p {
            margin: 0;
        }

        .greet {
            margin-bottom: 5mm;
        }

        .tabel {
            border-collapse: collapse;
            margin: 5mm 0;
        }

        .tabel td {
            padding: 0;
            vertical-align: top;
        }

        .tabel td.label {
            width: 37mm;
        }

        .tabel td.titik2 {
            padding-right: 1mm;
        }

        .salam {
            margin-top: 15mm;
        }

        .org {
            margin-top: 10mm;
        }
    </style>
</head>

<body>
    <img class="kop-atas" src="<?= $kopAtas ?>" alt="">

    <table class="head" cellpadding="0" cellspacing="0">
        <tr>
            <td>
                <?php if ($logo) : ?>
                    <img class="logo" src="<?= $logo ?>" alt="<?= esc($rfo['perusahaan'], 'attr') ?>">
                <?php else : ?>
                    <b><?= esc($namaTtd) ?></b>
                <?php endif; ?>
            </td>
            <td style="text-align:right;white-space:nowrap;"><?= esc($rfo['rfo_id']) ?></td>
        </tr>
    </table>

    <div class="isi">
        <p class="greet">Kepada Pelanggan Yth,</p>

        <p><?= $teks($pembuka) ?></p>

        <table class="tabel">
            <?php foreach ($baris as $label => $nilai) : ?>
                <tr>
                    <td class="label"><?= esc($label) ?></td>
                    <td class="titik2">:</td>
                    <td><?= $teks($nilai) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <p><?= $teks($penutup) ?></p>

        <p class="salam">
            Salam,
            <?php if (!empty($petugas)) : ?>
                <br><?= esc(implode(' / ', $petugas)) ?>
            <?php endif; ?>
        </p>
        <p class="org">
            Network Operation Center<br>
            <?= esc($namaTtd) ?>
        </p>
    </div>

    <img class="kop-bawah" src="<?= $kopBawah ?>" alt="">
</body>

</html>
