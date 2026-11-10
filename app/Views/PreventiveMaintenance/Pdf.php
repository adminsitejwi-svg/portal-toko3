<?php
// Preventive Maintenance versi PDF (dompdf), isi = PreventiveMaintenance/Dokumen.php (sama dengan halaman View & Maintenance Report Form Yesnet.docx).
// $logo / $fotoSrc berupa data URI dari App\Libraries\SurveyReportPdf.
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title><?= esc($sr['pm_id']) ?></title>
    <style>
        /* Margin atas 26.8 mm, bawah 25.4 mm (sama dengan Troubleshoot Report); kiri/kanan 0 agar kop & bar sampai tepi kertas
           (margin kiri/kanan isi diatur di PreventiveMaintenance/Dokumen.php) */
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
    <?= view('PreventiveMaintenance/Dokumen', ['sr' => $sr, 'logo' => $logo ?? null, 'fotoSrc' => $fotoSrc ?? [], 'pdf' => true]) ?>
</body>

</html>
