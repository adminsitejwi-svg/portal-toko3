<?php
// Dokumen Survey Report, tata letak SAMA dengan "Survey Report Form Yesnet.docx" (ukuran diambil dari docx, dalam mm):
//   kop tiap halaman : kotak di tepi kiri, logo perusahaan, bar berisi judul "SURVEY REPORT" (Open Sans tebal, putih)
//   halaman 1        : 1. DATA CALON PELANGGAN, 2. KETERANGAN SURVEY (Catatan berupa daftar 1), 2), ...)
//   halaman 2        : tabel Sisi Internal/Teknisi vs Sisi Klien, lalu 3. RESUME/REKOMENDASI
//   halaman 3 dst    : foto lampiran beserta keterangannya (bila ada)
//   halaman terakhir : Dilaksanakan Oleh : ... NOC
// Warna kop & tabel mengikuti warna logo perusahaan (di docx oranye #FF6600), bar section versi lebih muda (docx #FE9D59).
// Dipakai halaman View ($pdf kosong: tiap halaman berupa kertas A4) dan PDF ($pdf = true: kop position fixed,
// halaman diatur dompdf). $sr: data survey; $logo: ['src','w','h'] dari SurveyReportPdf::logoInfo();
// $fotoSrc: [['src', 'w', 'h', 'ket'], ...].
use App\Models\SurveyReportModel;

$pdf   = ! empty($pdf);
$UTAMA = SurveyReportModel::WARNA_LOGO[$sr['perusahaan']] ?? '#FF6600';
$MUDA  = SurveyReportModel::warnaMuda($UTAMA);

// Margin atas isi halaman di docx (1440 twips) = 25.4 mm
$ATAS = 25.4;

// Jarak baris isi (spasi ganda docx = 10 mm); dompdf menambah tinggi font Cambria ~14%, jadi di PDF dikurangi
$BARIS = $pdf ? 8.77 : 10;

// Posisi elemen kop relatif ke kertas: View = absolute di dalam kertas, PDF = fixed (relatif ke area isi, jadi dikurangi margin atas)
$pos = static fn (float $x, float $y, float $w, float $h) => ($pdf ? 'position:fixed;' : 'position:absolute;')
    . 'left:' . $x . 'mm;top:' . round($y - ($pdf ? $ATAS : 0), 2) . 'mm;width:' . $w . 'mm;height:' . $h . 'mm;';

$bulan   = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$tanggal = static fn ($d) => $d ? substr($d, 8, 2) . ' ' . $bulan[(int) substr($d, 5, 2)] . ' ' . substr($d, 0, 4) : '';
$isi     = static fn ($v) => ($v === null || trim((string) $v) === '') ? '' : nl2br(esc($v));

// Logo dimuat pas (contain) di kotak logo docx: kiri 15.9 mm, atas 9.1 mm, 31.8 x 14.8 mm, di tengah kotak
$logoHtml = '';
if (! empty($logo)) {
    [$kw, $kh] = [31.8, 14.8];
    $skala     = min($kw / max(1, $logo['w']), $kh / max(1, $logo['h']));
    $lw        = round($logo['w'] * $skala, 2);
    $lh        = round($logo['h'] * $skala, 2);
    $logoHtml  = '<img src="' . $logo['src'] . '" alt="' . esc($sr['perusahaan'], 'attr') . '" style="' . $pos(15.9 + ($kw - $lw) / 2, 9.1 + ($kh - $lh) / 2, $lw, $lh) . '">';
}

// Kop (diulang di setiap halaman) + titik kecil di kiri bawah seperti docx
$kop = static function () use ($pos, $UTAMA, $logoHtml): string {
    return '<div style="' . $pos(0, 9.5, 10.2, 13.2) . 'background:' . $UTAMA . ';"></div>'
        . $logoHtml
        . '<div style="' . $pos(52.9, 9.5, 157.1, 13.2) . 'background:' . $UTAMA . ';"></div>'
        . '<div class="srd-kop-judul" style="' . $pos(52.9, 12.6, 106.2, 8) . '">SURVEY REPORT</div>'
        . '<div style="' . $pos(6.4, 286.6, 0.7, 0.7) . 'background:' . $UTAMA . ';"></div>';
};

// Kotak pilihan seperti di docx (kotak 7 mm, sejajar tengah baris). $lebar: lebar grup (mm) per pilihan
$pilihan = static function (string $nilai, array $opsi, array $label, array $lebar, float $kiri): string {
    $lain = $nilai !== '' && ! in_array($nilai, $opsi, true);
    $html = '<table class="srd-cb" style="margin-left:' . $kiri . 'mm;"><tr>';
    foreach (array_merge($opsi, ['Dll']) as $i => $o) {
        $on    = $o === 'Dll' ? $lain || $nilai === 'Dll' : $nilai === $o;
        $teks  = $label[$i] . ($o === 'Dll' && $lain ? ': ' . esc($nilai) : '');
        $html .= '<td style="' . (isset($lebar[$i]) ? 'width:' . $lebar[$i] . 'mm;' : '') . '">'
            . '<table><tr><td class="srd-cb-kotak"><div class="srd-box">' . ($on ? '&#10003;' : '&nbsp;') . '</div></td>'
            . '<td class="srd-cb-teks">' . $teks . '</td></tr></table></td>';
    }

    return $html . '</tr></table>';
};

$spk     = trim($tanggal($sr['tgl_spk']) . ($sr['tgl_spk'] && $sr['no_spk'] ? ' / ' : '') . ($sr['no_spk'] ?? ''));
$catatan = SurveyReportModel::poinCatatan($sr['catatan'] ?? '');
$rek     = SurveyReportModel::rekomendasi($sr);
$fotoSrc = array_values($fotoSrc ?? []);

// Baris section: [label, isi]; posisi ":" sama dengan docx
$data1 = [
    ['a. Nama Calon Pelanggan', $isi($sr['nama_pelanggan'])],
    ['b. Tipe Bisnis', $pilihan((string) $sr['tipe_bisnis'], SurveyReportModel::TIPE_BISNIS, ['Corporate', 'Personal', 'Government', 'Dll'], [30.2, 26.5, 33.7], 0)],
    ['c. Alamat', $isi($sr['alamat'])],
    ['d. Telepon/HP', $isi($sr['telepon'])],
    ['e. Email', $isi($sr['email_pelanggan'])],
    ['f. Tgl SPK /No.SPK', esc($spk)],
    ['g. Tanggal Pelaksanaan', esc($tanggal($sr['tanggal_pelaksanaan']))],
];
$data2 = [
    ['a. Jenis Survey', $pilihan((string) $sr['jenis_layanan'], SurveyReportModel::JENIS_LAYANAN, ['FO', 'WIRELESS', 'VSAT', 'DLL'], [17.3, 28.9, 19.5], 2.5)],
    ['b. Layanan', $isi($sr['layanan'])],
    ['c. Catatan', ''],
];

$baris = static function (array $rows, float $lebarLabel): string {
    $html = '<table class="srd-rows">';
    foreach ($rows as [$label, $nilai]) {
        $html .= '<tr><td class="srd-label" style="width:' . $lebarLabel . 'mm;">' . esc($label) . '</td>'
            . '<td class="srd-titik">:</td><td class="srd-isi">' . $nilai . '</td></tr>';
    }

    return $html . '</table>';
};

// ---------- Halaman 1: 1. Data Calon Pelanggan & 2. Keterangan Survey ----------
ob_start(); ?>
    <div class="srd-bar" style="margin-top: <?= round(31.6 - $ATAS, 2) ?>mm; padding-left: 9.1mm;">1. DATA CALON PELANGGAN</div>
    <div style="margin-top: 0.7mm;"><?= $baris($data1, 55.7) ?></div>
    <div class="srd-bar" style="margin-top: 10.3mm; padding-left: 10.9mm;">2. KETERANGAN SURVEY</div>
    <div style="margin-top: 3.5mm;"><?= $baris($data2, 43.2) ?></div>
    <?php if ($catatan) : ?>
        <table class="srd-catatan">
            <?php foreach ($catatan as $i => $poin) : ?>
                <tr>
                    <td class="srd-catatan-no"><?= $i + 1 ?>)</td>
                    <td><?= esc($poin) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
<?php
$hal1 = ob_get_clean();

// ---------- Halaman 2: tabel Sisi Internal/Teknisi vs Sisi Klien + 3. Resume/Rekomendasi ----------
ob_start(); ?>
    <?php if ($rek) : ?>
        <!-- Jumlah baris tabel = jumlah baris yang diisi user (tanpa baris kosong tambahan) -->
        <table class="srd-rek" style="margin-top: <?= round(35.2 - $ATAS, 2) ?>mm;">
            <tr>
                <th style="width: 10.5mm;"></th>
                <th style="width: 68.7mm;">Sisi Internal/Teknisi</th>
                <th style="width: 72.6mm;">Sisi Klien</th>
            </tr>
            <?php foreach ($rek as $i => [$internal, $klien]) : ?>
                <tr>
                    <td class="srd-rek-no"><?= $i + 1 ?></td>
                    <td><?= nl2br(esc($internal)) ?></td>
                    <td><?= nl2br(esc($klien)) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
    <!-- tabel kosong (tidak diisi): bar Resume langsung di atas halaman -->
    <div class="srd-bar srd-tetap" style="margin-top: <?= $rek ? 21 : round(31.6 - $ATAS, 2) ?>mm; padding-left: 10.9mm;">3. RESUME/REKOMENDASI</div>
    <div class="srd-resume"><?= $isi($sr['resume']) ?></div>
<?php
$hal2 = ob_get_clean();

// ---------- Halaman foto: foto selebar 139.4 mm (tinggi maks 120 mm) + keterangan di bawahnya ----------
$halFoto = static function (array $foto): string {
    ob_start();
    foreach ($foto as $i => $f) {
        $w = 139.4;
        $h = $w * max(1, $f['h']) / max(1, $f['w']);
        if ($h > 120) {
            [$w, $h] = [$w * 120 / $h, 120];
        } ?>
        <div class="srd-foto" style="margin-top: <?= $i === 0 ? 9.8 : 8 ?>mm;">
            <img src="<?= $f['src'] ?>" alt="<?= esc($f['ket'] ?: 'Foto ' . ($i + 1), 'attr') ?>" style="width: <?= round($w, 2) ?>mm; height: <?= round($h, 2) ?>mm;">
            <?php if (trim($f['ket']) !== '') : ?>
                <div class="srd-foto-ket"><?= esc($f['ket']) ?></div>
            <?php endif; ?>
        </div>
    <?php }

    return ob_get_clean();
};

// ---------- Halaman terakhir: Dilaksanakan Oleh ----------
ob_start(); ?>
    <table class="srd-ttd" style="margin-top: <?= round(35.5 - $ATAS, 2) ?>mm;">
        <tr><td style="height: 5mm;">Dilaksanakan Oleh :</td></tr>
        <tr><td style="height: 19mm;"></td></tr>
        <tr><td style="height: 6mm;"><?= esc($sr['pelaksana'] ?? '') ?></td></tr>
        <tr><td>NOC</td></tr>
    </table>
<?php
$halTtd = ob_get_clean();
?>
<style>
    .srd-dok {
        font-family: srcaladea, Cambria, Caladea, Georgia, 'Times New Roman', serif;
        font-size: 12pt;
        line-height: 1.2;
        color: #000;
    }

    .srd-dok table {
        border-collapse: collapse;
    }

    .srd-dok td,
    .srd-dok th {
        padding: 0;
        vertical-align: top;
    }

    .srd-kop-judul {
        font-family: sropensans, 'Open Sans', 'Segoe UI', Arial, sans-serif;
        font-weight: bold;
        font-size: 20pt;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
    }

    /* Bar section warna muda, dari tepi kiri sampai tepi kanan kertas */
    .srd-bar {
        height: 5.3mm;
        padding-top: 0.4mm;
        line-height: 1;
        background: <?= $MUDA ?>;
        color: #fff;
        font-family: sropensans, 'Open Sans', 'Segoe UI', Arial, sans-serif;
        font-weight: bold;
        font-size: 12pt;
        white-space: nowrap;
    }

    /* Baris "a. Label : isi": label mulai 14.1 mm, jarak baris 10 mm (spasi ganda seperti docx) */
    .srd-rows {
        margin-left: 14.1mm;
        width: 178.5mm;
    }

    .srd-rows td {
        line-height: <?= $BARIS ?>mm;
    }

    .srd-titik {
        width: 2.5mm;
    }

    .srd-dok .srd-cb td {
        vertical-align: middle;
    }

    .srd-cb-kotak {
        width: 7mm;
    }

    .srd-cb-teks {
        padding-left: 2mm !important;
        white-space: nowrap;
    }

    .srd-box {
        width: 6.4mm;
        height: 5.4mm;
        padding-top: 1mm;
        border: 0.3mm solid #000;
        text-align: center;
        line-height: 1;
        font-family: 'DejaVu Sans', 'Segoe UI Symbol', sans-serif;
        font-weight: bold;
        font-size: 13pt;
    }

    /* Catatan: nomor "1)" di 51.1 mm, teks di 57.3 mm */
    .srd-catatan {
        margin-left: 51.1mm;
        width: 141.5mm;
    }

    .srd-catatan td {
        line-height: <?= $BARIS ?>mm;
    }

    .srd-catatan-no {
        width: 6.2mm;
    }

    /* Tabel Sisi Internal/Teknisi vs Sisi Klien */
    .srd-rek {
        margin-left: 29.8mm;
    }

    .srd-rek th,
    .srd-rek td {
        border: 0.3mm solid #000;
        padding: 1.5mm 2mm !important;
    }

    .srd-rek th {
        height: 9.3mm;
        vertical-align: middle !important;
        background: <?= $UTAMA ?>;
        color: #fff;
        text-align: center;
        font-family: sropensans, 'Open Sans', 'Segoe UI', Arial, sans-serif;
        font-weight: bold;
        font-size: 12pt;
    }

    .srd-rek td {
        height: 10.1mm;
        vertical-align: middle !important;
    }

    .srd-rek td.srd-rek-no {
        text-align: center;
    }

    .srd-tetap {
        page-break-before: avoid;
    }

    .srd-resume {
        margin: 5mm 17.4mm 0 31.75mm;
        line-height: 1.5;
        text-align: justify;
    }

    /* Foto lampiran mulai di margin kiri docx (31.75 mm) */
    .srd-foto {
        margin-left: 31.75mm;
        page-break-inside: avoid;
    }

    .srd-foto img {
        display: block;
    }

    .srd-foto-ket {
        margin-top: 2.7mm;
    }

    .srd-ttd {
        margin-left: 31.6mm;
    }

    .srd-hal-baru {
        page-break-before: always;
    }
</style>

<?php if ($pdf) : ?>
    <!-- PDF: kop position fixed (tercetak di setiap halaman), isi mengalir dengan pemisah halaman seperti docx -->
    <?= $kop() ?>
    <div class="srd-dok">
        <?= $hal1 ?>
        <div class="srd-hal-baru"><?= $hal2 ?></div>
        <?php if ($fotoSrc) : ?>
            <div class="srd-hal-baru"><?= $halFoto($fotoSrc) ?></div>
        <?php endif; ?>
        <div class="srd-hal-baru"><?= $halTtd ?></div>
    </div>
<?php else : ?>
    <!-- View: tiap halaman berupa kertas A4 (2 foto per halaman foto) -->
    <div class="srd-dok">
        <div class="srd-kertas"><?= $kop() . $hal1 ?></div>
        <div class="srd-kertas"><?= $kop() . $hal2 ?></div>
        <?php foreach (array_chunk($fotoSrc, 2) as $potong) : ?>
            <div class="srd-kertas"><?= $kop() . $halFoto($potong) ?></div>
        <?php endforeach; ?>
        <div class="srd-kertas"><?= $kop() . $halTtd ?></div>
    </div>
<?php endif; ?>
