<?php
// Surat versi PDF (dompdf) dengan kop surat khusus perusahaan, dipakai surat RFO (RFO/PdfRFO.php)
// dan surat Maintenance (MDMaintenance/PdfMaintenance.php). Tampilan sama dengan .rfo-paper--kop
// di halaman View (ViewRFO.php / ViewMaintenance.php).
// $kop: jwi / flazz / norlec / rtiga; $nomor: RFO ID / Maintenance ID; $perihal: RFO / Maintenance;
// $baris: [label => nilai] tabel isi surat; $logoKop / $logoTtd / $hiasan: data URI dari App\Libraries\RfoPdf.
// Ukuran dalam px (dompdf 96 dpi: kertas A4 = 794 x 1123 px, sama dengan kertas di View).
$teks = static fn ($v) => nl2br(esc($v));

// Posisi kop khusus per perusahaan, diukur dari halaman View (px, relatif ke kertas):
// meta = Nomor/Lamp/Perihal [kiri, atas, ukuran font, lebar kolom label]
// logo = logo kanan atas [kiri, atas, lebar, tinggi]
// isi  = isi surat [kiri, atas, lebar]
// ttd  = tinggi bingkai logo di bawah nama petugas
$tataKop = [
    'jwi'    => ['meta' => [119, 98, 16, 96], 'logo' => [460, 58, 320, 62], 'isi' => [119, 195.6, 595], 'ttd' => 29],
    'flazz'  => ['meta' => [90, 100, 15, 93], 'logo' => [487, 46, 205, 41], 'isi' => [90, 194, 624], 'ttd' => 30],
    'norlec' => ['meta' => [94, 101, 15, 93], 'logo' => [435, 47, 240, 40], 'isi' => [94, 195, 620], 'ttd' => 25],
    'rtiga'  => ['meta' => [46, 124, 15, 93], 'logo' => [555, 20, 190, 58], 'isi' => [46, 218, 702], 'ttd' => 46],
][$kop] ?? null;
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title><?= esc($nomor) ?></title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            margin: 0;
            color: #000;
        }

        p {
            margin: 0;
        }

        /* ===== Kop surat khusus (sama dengan .rfo-paper--kop di ViewRFO.php) =====
           Jarak baris dompdf = line-height x tinggi font (Calibri 1.22, Arial 1.15; fontHeightRatio 1),
           jarak baris browser = line-height x font-size; jadi line-height di sini = nilai View / tinggi font */
        .k-body {
            font-family: Calibri, Helvetica, sans-serif;
            font-size: 15px;
            line-height: 1.1066; /* View 1.35 */
        }

        .k-meta {
            position: absolute;
            border-collapse: collapse;
            font-family: Arial, Helvetica, sans-serif;
            line-height: 1.0435; /* View 1.2 */
        }

        .k-meta td {
            padding: 0;
            vertical-align: top;
        }

        .k-meta td.titik2 {
            padding-right: 8px;
        }

        .k-abs {
            position: absolute;
        }

        .k-isi {
            position: absolute;
        }

        .k-greet {
            margin-bottom: 19px;
        }

        .k-tabel {
            border-collapse: collapse;
            margin: 19px 0;
        }

        .k-tabel td {
            padding: 0;
            vertical-align: top;
        }

        .k-tabel td.label {
            width: 170px;
            white-space: nowrap;
        }

        .k-tabel td.titik2 {
            padding-right: 4px;
        }

        .k-sign {
            margin-top: 58px;
        }

        .k-sign-logo {
            display: block;
            width: 150px;
            margin-top: 12px;
        }

        .k-sign-org {
            margin-top: 38px;
        }

        .k-sign-logo + .k-sign-org {
            margin-top: 12px;
        }

        /* Footer memakai posisi atas tetap (diukur dari View): dompdf tidak andal untuk bottom */

        /* Hiasan kanan bawah (api JWI / logo S Sprintnet), bingkai 794x230 di dasar kertas */
        .k-hiasan {
            position: absolute;
            left: 0;
            top: 893px;
            width: 794px;
            height: 230px;
        }

        /* JWI: alamat kantor 2 kolom kiri bawah */
        .jwi-foot {
            position: absolute;
            left: 58px;
            top: 1017.2px;
            border-collapse: collapse;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10.5px;
            font-weight: bold;
            line-height: 1.1304; /* View 1.3 */
            color: #111;
        }

        .jwi-foot td {
            padding: 0;
            vertical-align: top;
            white-space: nowrap;
        }

        .jwi-foot td.jwi-col2 {
            padding: 22px 0 0 32px;
        }

        .jwi-foot .jwi-pt {
            font-size: 12.5px;
            margin-bottom: 2px;
        }

        .jwi-foot .jwi-office {
            font-size: 13px;
        }

        .jwi-foot sup {
            font-size: 7px;
            line-height: 0;
        }

        /* Flazznet: pita hijau + alamat kantor di dasar kertas */
        .flazz-foot {
            position: absolute;
            left: 0;
            top: 998px;
            width: 794px;
            height: 111px;
            padding-top: 14px;
            background: #9BBB59;
            text-align: center;
            font-family: Calibri, Helvetica, sans-serif;
            font-size: 13px;
            line-height: 1.1066; /* View 1.35 */
        }

        .flazz-foot .flazz-pt {
            font-weight: bold;
            margin-bottom: 8px;
        }

        .flazz-foot .flazz-web {
            margin-top: 8px;
        }

        /* Sprintnet & Rnet: alamat kantor kiri bawah */
        .norlec-foot {
            position: absolute;
            left: 94px;
            top: 998.4px;
            font-family: Calibri, Helvetica, sans-serif;
            font-size: 13.5px;
            line-height: 1.1475; /* View 1.4 */
        }

        .rtiga-foot {
            position: absolute;
            left: 46px;
            top: 980px;
            font-family: Calibri, Helvetica, sans-serif;
            font-size: 15px;
            line-height: 1.1885; /* View 1.45 */
        }

        /* Rnet: garis abu tebal di bawah logo (gradasi #6b6b6b -> #4a4a4a, 5 x 1px) */
        .rtiga-garis {
            position: absolute;
            left: 33px;
            width: 724px;
            height: 1px;
        }
    </style>
</head>

<body class="k-body">
    <?php [$mx, $my, $mfont, $mlabel] = $tataKop['meta']; ?>
    <?php [$lx, $ly, $lw, $lh] = $tataKop['logo']; ?>
    <?php [$ix, $iy, $iw] = $tataKop['isi']; ?>

    <?php if (! empty($hiasan)) : ?>
        <img class="k-hiasan" src="<?= $hiasan ?>" alt="">
    <?php endif; ?>

    <?php if ($kop === 'rtiga') : ?>
        <?php foreach (['#6b6b6b', '#636363', '#5b5b5b', '#525252', '#4a4a4a'] as $i => $warna) : ?>
            <div class="rtiga-garis" style="top: <?= 92 + $i ?>px; background: <?= $warna ?>;"></div>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Nomor / Lamp / Perihal kiri atas -->
    <table class="k-meta" style="left: <?= $mx ?>px; top: <?= $my ?>px; font-size: <?= $mfont ?>px;">
        <tr>
            <td style="width: <?= $mlabel ?>px;">Nomor</td>
            <td class="titik2">:</td>
            <td><?= esc($nomor) ?></td>
        </tr>
        <tr>
            <td>Lamp</td>
            <td class="titik2">:</td>
            <td>-</td>
        </tr>
        <tr>
            <td>Perihal</td>
            <td class="titik2">:</td>
            <td><?= esc($perihal) ?></td>
        </tr>
    </table>

    <!-- Logo kanan atas -->
    <?php if (! empty($logoKop)) : ?>
        <img class="k-abs" src="<?= $logoKop ?>" alt="<?= esc($perusahaan, 'attr') ?>" style="left: <?= $lx ?>px; top: <?= $ly ?>px; width: <?= $lw ?>px; height: <?= $lh ?>px;">
    <?php endif; ?>

    <div class="k-isi" style="left: <?= $ix ?>px; top: <?= $iy ?>px; width: <?= $iw ?>px;">
        <p class="k-greet">Kepada Pelanggan Yth,</p>

        <p><?= $teks($pembuka) ?></p>

        <table class="k-tabel">
            <?php foreach ($baris as $label => $nilai) : ?>
                <tr>
                    <td class="label"><?= esc($label) ?></td>
                    <td class="titik2">:</td>
                    <td><?= $teks($nilai) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <p><?= $teks($penutup) ?></p>

        <div class="k-sign">
            <p>Salam,</p>
            <?php if (! empty($petugas)) : ?>
                <p><?= esc(implode(' / ', $petugas)) ?></p>
            <?php endif; ?>
            <?php if (! empty($logoTtd)) : ?>
                <img class="k-sign-logo" src="<?= $logoTtd ?>" alt="" style="height: <?= $tataKop['ttd'] ?>px;">
            <?php endif; ?>
            <p class="k-sign-org">Network Operation Center</p>
            <p><?= esc($namaTtd) ?></p>
        </div>
    </div>

    <?php if ($kop === 'flazz') : ?>
        <!-- Pita hijau + alamat kantor (Kop Surat Flazznet.pdf) -->
        <div class="flazz-foot">
            <div class="flazz-pt">PT Genezis Mitra Technology</div>
            <div>MTH Square, Jl. Letjen M.T. Haryono No.Kav 10 office Lt. 1 No. 0125, RT.6/RW.12, Bidara Cina,</div>
            <div>Kecamatan Jatinegara, Kota Jakarta Timur, Daerah Khusus Ibukota Jakarta 13330</div>
            <div class="flazz-web">www.flazznet.id</div>
        </div>
    <?php elseif ($kop === 'norlec') : ?>
        <!-- Alamat kantor (norlec.pdf) -->
        <div class="norlec-foot">
            <div>PT Juragan Wifi Indonesia</div>
            <div>MTH Square Lantai 2 No.0219</div>
            <div>Jl. MT Haryono Kav 10 Bidara Cina Jatinegara</div>
            <div>Jakarta Timur DKI Jakarta 13330</div>
        </div>
    <?php elseif ($kop === 'rtiga') : ?>
        <!-- Alamat kantor kiri bawah (kop surat rnet.pdf) -->
        <div class="rtiga-foot">
            <div>PT Juragan Wifi Indonesia (RNET)</div>
            <div>MTH Square Lantai 3A No.12</div>
            <div>Jl. MT Haryono Kav 10 Bidara Cina Jatinegara</div>
            <div>Jakarta Timur DKI Jakarta 13330</div>
        </div>
    <?php elseif ($kop === 'jwi') : ?>
        <!-- Alamat kantor (Kop Surat JWI.pdf) -->
        <table class="jwi-foot">
            <tr>
                <td>
                    <div class="jwi-pt">PT. JURAGAN WIFI INDONESIA</div>
                    <div class="jwi-office">Head Office</div>
                    <div>MTH Square 2<sup>nd</sup> Floor No. 219</div>
                    <div>Jl. MT. Haryono Kav 10, Jakarta Timur 13330</div>
                    <div>Telp +62 21 5011 2225 - www.speednet.id</div>
                </td>
                <td class="jwi-col2">
                    <div class="jwi-office">Branch Cirebon</div>
                    <div>Jl. Pangeran Walangsungsang, Sukadana,</div>
                    <div>Kec. Pabuaran, Kab. Cirebon, Jawa Barat 45188</div>
                    <div>Telp +62 21 5011 2225 - www.speednet.id</div>
                </td>
            </tr>
        </table>
    <?php endif; ?>
</body>

</html>
