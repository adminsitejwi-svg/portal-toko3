<?php
// Dokumen Troubleshoot Report, tata letak SAMA dengan "Troubleshooting Report Form Yesnet (1).docx" (ukuran dari docx, dalam mm):
//   kop tiap halaman : kotak di tepi kiri, logo perusahaan, bar berisi "TROUBLESHOOTING REPORT"
//   halaman 1        : 1. DATA PELANGGAN, 2. KETERANGAN LAYANAN KLIEN, 3. DESKRIPSI PEKERJAAN (Trouble / Action)
//   halaman 2        : 4. RESUME (1., 2., ...) + Dibuat Oleh (Teknis) / Diketahui Oleh (Branch Manager/Spv/Mng)
//   halaman 3 dst    : 5. LAMPIRAN (bar di setiap halaman lampiran seperti header docx), 2 foto per halaman
//   kaki tiap halaman: garis oranye
// Warna kop & bar section mengikuti warna logo perusahaan (di docx kuning #FFFF00), teks putih; sama dengan Survey Report.
// Dipakai halaman View ($pdf kosong: tiap halaman berupa kertas A4) dan PDF ($pdf = true: kop position fixed).
// $sr: data troubleshoot report; $logo: ['src','w','h'] dari SurveyReportPdf::logoInfo(); $fotoSrc: [['src','w','h','ket'], ...].
use App\Models\SurveyReportModel;
use App\Models\TroubleshootReportModel;

$pdf   = ! empty($pdf);
$UTAMA = SurveyReportModel::WARNA_LOGO[$sr['perusahaan']] ?? '#FFFF00';

// Margin atas isi halaman di docx (1520 twips) = 26.8 mm
$ATAS = 26.8;

// Jarak baris isi (spasi docx ~10 mm); dompdf menambah tinggi font Cambria ~14%, jadi di PDF dikurangi
$BARIS = $pdf ? 8.77 : 10;

// Posisi elemen kop/kaki relatif ke kertas: View = absolute di dalam kertas, PDF = fixed (relatif ke area isi)
$pos = static fn (float $x, float $y, float $w, float $h) => ($pdf ? 'position:fixed;' : 'position:absolute;')
    . 'left:' . $x . 'mm;top:' . round($y - ($pdf ? $ATAS : 0), 2) . 'mm;width:' . $w . 'mm;height:' . $h . 'mm;';

$bulan   = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$tanggal = static fn ($d) => $d ? substr($d, 8, 2) . ' ' . $bulan[(int) substr($d, 5, 2)] . ' ' . substr($d, 0, 4) : '';
$isi     = static fn ($v) => ($v === null || trim((string) $v) === '') ? '' : nl2br(esc($v));

// Logo dimuat pas (contain) di kotak logo docx: kiri 10.12 mm, atas 7.29 mm, 39.74 x 16.05 mm
$logoHtml = '';
if (! empty($logo)) {
    [$kw, $kh] = [39.74, 16.05];
    $skala     = min($kw / max(1, $logo['w']), $kh / max(1, $logo['h']));
    $lw        = round($logo['w'] * $skala, 2);
    $lh        = round($logo['h'] * $skala, 2);
    $logoHtml  = '<img src="' . $logo['src'] . '" alt="' . esc($sr['perusahaan'], 'attr') . '" style="' . $pos(10.12 + ($kw - $lw) / 2, 7.29 + ($kh - $lh) / 2, $lw, $lh) . '">';
}

// Kop + garis oranye kaki (diulang di setiap halaman)
$kop = static function () use ($pos, $UTAMA, $logoHtml): string {
    return '<div style="' . $pos(0, 9.53, 10.16, 12.7) . 'background:' . $UTAMA . ';"></div>'
        . $logoHtml
        . '<div style="' . $pos(50.09, 10.7, 159.91, 12.3) . 'background:' . $UTAMA . ';"></div>'
        . '<div class="trd-kop-judul" style="' . $pos(76.1, 13.4, 120, 8) . '">TROUBLESHOOTING REPORT</div>'
        . '<div style="' . $pos(6.0, 285.6, 197.6, 0.7) . 'background:#FF6600;"></div>';
};

// Kotak pilihan seperti di docx (kotak 6.4 mm, sejajar tengah baris). $lebar: lebar grup (mm) per pilihan
$pilihan = static function (string $nilai, array $opsi, array $label, array $lebar, float $kiri): string {
    $lain = $nilai !== '' && ! in_array($nilai, $opsi, true);
    $html = '<table class="trd-cb" style="margin-left:' . $kiri . 'mm;"><tr>';
    foreach (array_merge($opsi, ['Dll']) as $i => $o) {
        $on    = $o === 'Dll' ? $lain || $nilai === 'Dll' : $nilai === $o;
        $teks  = $label[$i] . ($o === 'Dll' && $lain ? ': ' . esc($nilai) : '');
        $html .= '<td style="' . (isset($lebar[$i]) ? 'width:' . $lebar[$i] . 'mm;' : '') . '">'
            . '<table><tr><td class="trd-cb-kotak"><div class="trd-box">' . ($on ? '&#10003;' : '&nbsp;') . '</div></td>'
            . '<td class="trd-cb-teks">' . $teks . '</td></tr></table></td>';
    }

    return $html . '</tr></table>';
};

$spk     = trim($tanggal($sr['tgl_spk']) . ($sr['tgl_spk'] && $sr['no_spk'] ? ' / ' : '') . ($sr['no_spk'] ?? ''));
$resume  = SurveyReportModel::poinCatatan($sr['resume'] ?? '');
$fotoSrc = array_values($fotoSrc ?? []);

$data1 = [
    ['a.', 'Nama Pelanggan', $isi($sr['nama_pelanggan'])],
    ['b.', 'Tipe Bisnis', $pilihan((string) $sr['tipe_bisnis'], TroubleshootReportModel::TIPE_BISNIS, ['Corporate', 'Personal', 'Government', 'Dll'], [25.5, 26.3, 26.6], 0)],
    ['c.', 'Alamat', $isi($sr['alamat'])],
    ['d.', 'Telepon/HP', $isi($sr['telepon'])],
    ['e.', 'Email', $isi($sr['email_pelanggan'])],
    ['f.', 'Tgl SPK /No.SPK', esc($spk)],
    ['g.', 'Tanggal Pelaksanaan', esc($tanggal($sr['tanggal_pelaksanaan']))],
    ['h.', 'Pelaksana', $isi($sr['pelaksana'])],
];
$data2 = [
    ['a.', 'Jenis Layanan', $pilihan((string) $sr['jenis_layanan'], TroubleshootReportModel::JENIS_LAYANAN, ['FO', 'WIRELESS', 'VSAT', 'DLL'], [17.1, 28.2, 19.1], 1.8)],
    ['b.', 'Catatan', $isi($sr['catatan'])],
];

// Tabel baris "a. Label : isi" mulai 14.1 mm; $lebarNo + $lebarLabel = jarak ke ":" (docx)
$baris = static function (array $rows, float $lebarNo, float $lebarLabel): string {
    $html = '<table class="trd-rows">';
    foreach ($rows as [$no, $label, $nilai]) {
        $html .= '<tr><td style="width:' . $lebarNo . 'mm;">' . $no . '</td>'
            . '<td style="width:' . $lebarLabel . 'mm;">' . esc($label) . '</td>'
            . '<td class="trd-titik">:</td><td class="trd-isi">' . $nilai . '</td></tr>';
    }

    return $html . '</table>';
};

// ---------- Halaman 1 ----------
ob_start(); ?>
    <div class="trd-bar" style="margin-top: <?= round(31.8 - $ATAS, 2) ?>mm;">1. DATA PELANGGAN</div>
    <div style="margin-top: 3mm;"><?= $baris($data1, 4.5, 51.6) ?></div>
    <div class="trd-bar" style="margin-top: 8.2mm;">2. KETERANGAN LAYANAN KLIEN</div>
    <div style="margin-top: 1.1mm;"><?= $baris($data2, 4.5, 39.1) ?></div>
    <div class="trd-bar" style="margin-top: 7.5mm;">3. DESKRIPSI PEKERJAAN</div>
    <table class="trd-desk" style="margin-top: 6.4mm;">
        <tr>
            <td class="trd-desk-label">Trouble</td>
            <td class="trd-desk-titik">:</td>
            <td><?= $isi($sr['trouble']) ?></td>
        </tr>
        <tr>
            <td class="trd-desk-label">Action</td>
            <td class="trd-desk-titik">:</td>
            <td><?= $isi($sr['action']) ?></td>
        </tr>
    </table>
<?php
$hal1 = ob_get_clean();

// ---------- Halaman 2: 4. Resume + tanda tangan ----------
ob_start(); ?>
    <div class="trd-bar" style="margin-top: <?= round(33.9 - $ATAS, 2) ?>mm;">4. RESUME</div>
    <div class="trd-resume">
        <?php if ($resume) : ?>
            <table class="trd-resume-daftar">
                <?php foreach ($resume as $i => $poin) : ?>
                    <tr>
                        <td class="trd-resume-no"><?= $i + 1 ?>.</td>
                        <td><?= esc($poin) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
    <table class="trd-ttd">
        <tr>
            <td style="width: 89.4mm; height: 5mm;">Dibuat Oleh :</td>
            <td style="width: 110mm;">Diketahui Oleh :</td>
        </tr>
        <tr>
            <td style="height: 23.1mm;"></td>
            <td></td>
        </tr>
        <tr>
            <td style="height: 6mm;"><?= $sr['dibuat_oleh'] ? '( ' . esc($sr['dibuat_oleh']) . ' )' : '' ?></td>
            <td><?= $sr['diketahui_oleh'] ? '( ' . esc($sr['diketahui_oleh']) . ' )' : '' ?></td>
        </tr>
        <tr>
            <td>Teknis</td>
            <td>Branch Manager/Spv/Mng</td>
        </tr>
    </table>
<?php
$hal2 = ob_get_clean();

// ---------- Halaman lampiran: bar "5. LAMPIRAN" + maks 2 foto (lebar 139.4 mm, tinggi maks 95 mm) ----------
$halFoto = static function (array $foto) use ($ATAS): string {
    ob_start(); ?>
    <div class="trd-bar" style="margin-top: <?= round(31.8 - $ATAS, 2) ?>mm;">5. LAMPIRAN</div>
    <?php foreach ($foto as $i => $f) :
        $w = 139.4;
        $h = $w * max(1, $f['h']) / max(1, $f['w']);
        if ($h > 95) {
            [$w, $h] = [$w * 95 / $h, 95];
        } ?>
        <div class="trd-foto" style="margin-top: <?= $i === 0 ? 6 : 8 ?>mm;">
            <img src="<?= $f['src'] ?>" alt="<?= esc($f['ket'] ?: 'Foto', 'attr') ?>" style="width: <?= round($w, 2) ?>mm; height: <?= round($h, 2) ?>mm;">
            <?php if (trim($f['ket']) !== '') : ?>
                <div class="trd-foto-ket"><?= esc($f['ket']) ?></div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <?php
    return ob_get_clean();
};

// halaman lampiran: 2 foto per halaman; tanpa foto tetap satu halaman berisi bar "5. LAMPIRAN" (seperti docx)
$potongFoto = $fotoSrc ? array_chunk($fotoSrc, 2) : [[]];
?>
<style>
    .trd-dok {
        font-family: srcaladea, Cambria, Caladea, Georgia, 'Times New Roman', serif;
        font-size: 12pt;
        line-height: 1.2;
        color: #000;
    }

    .trd-dok table {
        border-collapse: collapse;
    }

    .trd-dok td {
        padding: 0;
        vertical-align: top;
    }

    .trd-kop-judul {
        font-family: sropensans, 'Open Sans', 'Segoe UI', Arial, sans-serif;
        font-weight: bold;
        font-size: 20pt;
        line-height: 1;
        color: #fff;
        white-space: nowrap;
    }

    /* Bar section warna logo, dari tepi kiri sampai tepi kanan kertas, teks mulai 6.8 mm */
    .trd-bar {
        height: 5mm;
        padding-left: 6.8mm;
        line-height: 1;
        background: <?= $UTAMA ?>;
        color: #fff;
        font-family: sropensans, 'Open Sans', 'Segoe UI', Arial, sans-serif;
        font-weight: bold;
        font-size: 12pt;
        white-space: nowrap;
    }

    /* Baris "a. Label : isi" mulai 14.1 mm, jarak baris ~10 mm seperti docx */
    .trd-rows {
        margin-left: 14.1mm;
        width: 182mm;
    }

    .trd-rows td {
        line-height: <?= $BARIS ?>mm;
    }

    .trd-titik {
        width: 2.5mm;
    }

    .trd-dok .trd-cb td {
        vertical-align: middle;
    }

    .trd-cb-kotak {
        width: 6.4mm;
    }

    .trd-cb-teks {
        padding-left: 1.4mm !important;
        white-space: nowrap;
    }

    .trd-box {
        width: 5.8mm;
        height: 4.8mm;
        padding-top: 1mm;
        border: 0.3mm solid #404040;
        text-align: center;
        line-height: 1;
        font-family: 'DejaVu Sans', 'Segoe UI Symbol', sans-serif;
        font-weight: bold;
        font-size: 12pt;
    }

    /* 3. Deskripsi Pekerjaan: "Trouble :" / "Action :" rapat (spasi tunggal), huruf agak renggang seperti docx */
    .trd-desk {
        margin-left: 15.2mm;
        width: 180mm;
    }

    .trd-desk td {
        line-height: 1.3;
        padding-bottom: 0.6mm !important;
    }

    .trd-desk-label {
        width: 12mm;
        letter-spacing: 1pt;
        white-space: nowrap;
    }

    .trd-desk-titik {
        width: 4mm;
        padding-left: 1mm !important;
    }

    /* 4. Resume: nomor "1." di 10.9 mm; tanda tangan mulai 135 mm dari atas kertas */
    .trd-resume {
        margin-top: 4.1mm;
        min-height: 92mm;
    }

    .trd-resume-daftar {
        margin-left: 10.9mm;
        width: 185mm;
    }

    .trd-resume-daftar td {
        line-height: <?= $BARIS ?>mm;
    }

    .trd-resume-no {
        width: 6.1mm;
    }

    /* Tanda tangan: tengah kolom kiri 51.1 mm, kanan ~150.8 mm (seperti docx) */
    .trd-ttd {
        margin-left: 6.4mm;
    }

    .trd-ttd td {
        text-align: center;
    }

    .trd-foto {
        margin-left: 31.75mm;
        page-break-inside: avoid;
    }

    .trd-foto img {
        display: block;
    }

    .trd-foto-ket {
        margin-top: 2.7mm;
    }

    .trd-hal-baru {
        page-break-before: always;
    }
</style>

<?php if ($pdf) : ?>
    <!-- PDF: kop position fixed (tercetak di setiap halaman), isi dengan pemisah halaman seperti docx -->
    <?= $kop() ?>
    <div class="trd-dok">
        <?= $hal1 ?>
        <div class="trd-hal-baru"><?= $hal2 ?></div>
        <?php foreach ($potongFoto as $potong) : ?>
            <div class="trd-hal-baru"><?= $halFoto($potong) ?></div>
        <?php endforeach; ?>
    </div>
<?php else : ?>
    <!-- View: tiap halaman berupa kertas A4 -->
    <div class="trd-dok">
        <div class="srd-kertas"><?= $kop() . $hal1 ?></div>
        <div class="srd-kertas"><?= $kop() . $hal2 ?></div>
        <?php foreach ($potongFoto as $potong) : ?>
            <div class="srd-kertas"><?= $kop() . $halFoto($potong) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
