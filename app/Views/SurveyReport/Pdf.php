<?php
// Survey Report versi PDF (dompdf), isi = SurveyReport/Dokumen.php (sama dengan halaman View & Survey Report Form Yesnet.docx).
// $logo / $fotoSrc berupa data URI dari App\Libraries\SurveyReportPdf.
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title><?= esc($sr['survey_id']) ?></title>
    <style>
        /* Margin atas & bawah seperti docx: 1440 twips (25.4 mm); kiri/kanan 0 agar kop & bar sampai tepi kertas
           (margin kiri/kanan isi diatur di SurveyReport/Dokumen.php) */
        @page {
            size: A4;
            margin: 25.4mm 0 25.4mm 0;
        }

        body {
            margin: 0;
        }
    </style>
</head>

<body>
    <?= view('SurveyReport/Dokumen', ['sr' => $sr, 'logo' => $logo ?? null, 'fotoSrc' => $fotoSrc ?? [], 'pdf' => true]) ?>
</body>

</html>
