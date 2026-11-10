<?php
// Installation Report versi PDF (dompdf), isi = InstallationReport/Dokumen.php (sama dengan halaman View & Installation Report Form Yesnet.docx).
// $logo / $fotoSrc berupa data URI dari App\Libraries\SurveyReportPdf.
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title><?= esc($sr['install_id']) ?></title>
    <style>
        /* Margin atas seperti docx: 1420 twips (25.05 mm), bawah 25.4 mm; kiri/kanan 0 agar kop & bar sampai tepi kertas
           (margin kiri/kanan isi diatur di InstallationReport/Dokumen.php) */
        @page {
            size: A4;
            margin: 25.05mm 0 25.4mm 0;
        }

        body {
            margin: 0;
        }
    </style>
</head>

<body>
    <?= view('InstallationReport/Dokumen', ['sr' => $sr, 'logo' => $logo ?? null, 'fotoSrc' => $fotoSrc ?? [], 'pdf' => true]) ?>
</body>

</html>
