<?php
// Troubleshoot Report versi PDF (dompdf), isi = TroubleshootReport/Dokumen.php (sama dengan halaman View & Troubleshooting Report Form Yesnet (1).docx).
// $logo / $fotoSrc berupa data URI dari App\Libraries\SurveyReportPdf.
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title><?= esc($sr['trouble_id']) ?></title>
    <style>
        /* Margin atas seperti docx: 1520 twips (26.8 mm), bawah 25.4 mm; kiri/kanan 0 agar kop & bar sampai tepi kertas
           (margin kiri/kanan isi diatur di TroubleshootReport/Dokumen.php) */
        @page {
            size: A4;
            margin: 26.8mm 0 25.4mm 0;
        }

        body {
            margin: 0;
        }
    </style>
</head>

<body>
    <?= view('TroubleshootReport/Dokumen', ['sr' => $sr, 'logo' => $logo ?? null, 'fotoSrc' => $fotoSrc ?? [], 'pdf' => true]) ?>
</body>

</html>
