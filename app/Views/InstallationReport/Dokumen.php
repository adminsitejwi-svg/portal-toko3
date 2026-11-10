<?php
// Dokumen Installation Report, tata letak SAMA dengan "Installation Report Form Yesnet (1).docx" (ukuran dari docx, dalam mm):
//   kop tiap halaman : kotak di tepi kiri, logo perusahaan, bar berisi "INSTALLATION REPORT" (Corbel tebal, putih)
//   halaman 1        : 1. DATA PELANGGAN, 2. KETERANGAN INSTALASI, 3. INTERFACE PARAMETER
//   halaman 2        : Dibuat Oleh (Teknis) / Diketahui Oleh
//   halaman 3 dst    : 6. LAMPIRAN TESTLINK (foto + keterangan)
//   kaki tiap halaman: garis oranye
// Warna kop & bar section mengikuti warna logo perusahaan (di docx merah #FF0000), sama dengan Survey Report.
// Dipakai halaman View ($pdf kosong: tiap halaman berupa kertas A4) dan PDF ($pdf = true: kop position fixed).
// $sr: data installation report; $logo: ['src','w','h'] dari SurveyReportPdf::logoInfo(); $fotoSrc: [['src','w','h','ket'], ...].
use App\Models\InstallationReportModel;
use App\Models\SurveyReportModel;

$pdf   = ! empty($pdf);
$UTAMA = SurveyReportModel::WARNA_LOGO[$sr['perusahaan']] ?? '#FF0000';

// Margin atas isi halaman di docx (1420 twips) = 25.05 mm
$ATAS = 25.05;

// Jarak baris isi (spasi docx ~10 mm); dompdf menambah tinggi font Cambria ~14%, jadi di PDF dikurangi
$BARIS = $pdf ? 8.7 : 9.9;

// Posisi elemen kop/kaki relatif ke kertas: View = absolute di dalam kertas, PDF = fixed (relatif ke area isi)
$pos = static fn (float $x, float $y, float $w, float $h) => ($pdf ? 'position:fixed;' : 'position:absolute;')
    . 'left:' . $x . 'mm;top:' . round($y - ($pdf ? $ATAS : 0), 2) . 'mm;width:' . $w . 'mm;height:' . $h . 'mm;';

$bulan   = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$tanggal = static fn ($d) => $d ? substr($d, 8, 2) . ' ' . $bulan[(int) substr($d, 5, 2)] . ' ' . substr($d, 0, 4) : '';
$isi     = static fn ($v) => ($v === null || trim((string) $v) === '') ? '' : nl2br(esc($v));

// Logo dimuat pas (contain) di kotak logo docx: kiri 11.82 mm, atas 7.32 mm, 39.74 x 16.05 mm
$logoHtml = '';
if (! empty($logo)) {
    [$kw, $kh] = [39.74, 16.05];
    $skala     = min($kw / max(1, $logo['w']), $kh / max(1, $logo['h']));
    $lw        = round($logo['w'] * $skala, 2);
    $lh        = round($logo['h'] * $skala, 2);
    $logoHtml  = '<img src="' . $logo['src'] . '" alt="' . esc($sr['perusahaan'], 'attr') . '" style="' . $pos(11.82 + ($kw - $lw) / 2, 7.32 + ($kh - $lh) / 2, $lw, $lh) . '">';
}

// Kop + garis oranye kaki (diulang di setiap halaman)
$kop = static function () use ($pos, $UTAMA, $logoHtml): string {
    return '<div style="' . $pos(0, 9.53, 10.16, 12.7) . 'background:' . $UTAMA . ';"></div>'
        . $logoHtml
        . '<div style="' . $pos(52.92, 13.39, 157.25, 7.87) . 'background:' . $UTAMA . ';"></div>'
        . '<div class="ird-kop-judul" style="' . $pos(68.9, 13.3, 135, 7) . '">INSTALLATION REPORT</div>'
        . '<div style="' . $pos(6.35, 285.75, 196.85, 0.7) . 'background:#FF6600;"></div>';
};

// Kotak pilihan seperti di docx (kotak 6.4 mm, sejajar tengah baris). $lebar: lebar grup (mm) per pilihan
$pilihan = static function (string $nilai, array $opsi, array $label, array $lebar, float $kiri): string {
    $lain = $nilai !== '' && ! in_array($nilai, $opsi, true);
    $html = '<table class="ird-cb" style="margin-left:' . $kiri . 'mm;"><tr>';
    foreach (array_merge($opsi, ['Dll']) as $i => $o) {
        $on    = $o === 'Dll' ? $lain || $nilai === 'Dll' : $nilai === $o;
        $teks  = $label[$i] . ($o === 'Dll' && $lain ? ': ' . esc($nilai) : '');
        $html .= '<td style="' . (isset($lebar[$i]) ? 'width:' . $lebar[$i] . 'mm;' : '') . '">'
            . '<table><tr><td class="ird-cb-kotak"><div class="ird-box">' . ($on ? '&#10003;' : '&nbsp;') . '</div></td>'
            . '<td class="ird-cb-teks">' . $teks . '</td></tr></table></td>';
    }

    return $html . '</tr></table>';
};

$spk     = trim($tanggal($sr['tgl_spk']) . ($sr['tgl_spk'] && $sr['no_spk'] ? ' / ' : '') . ($sr['no_spk'] ?? ''));
$fotoSrc = array_values($fotoSrc ?? []);

// Baris section: [nomor, label, isi]; posisi ":" sama dengan docx
$data1 = [
    ['a.', 'Nama Pelanggan', $isi($sr['nama_pelanggan'])],
    ['b.', 'Tipe Bisnis', $pilihan((string) $sr['tipe_bisnis'], InstallationReportModel::TIPE_BISNIS, ['Corporate', 'Personal', 'Government', 'Dll'], [27.8, 23.8, 31.6], 0)],
    ['c.', 'Alamat', $isi($sr['alamat'])],
    ['d.', 'PIC/HP', $isi($sr['pic_hp'])],
    ['e.', 'Email', $isi($sr['email_pelanggan'])],
    ['f.', 'Tgl SPK /No.SPK', esc($spk)],
    ['g.', 'Tanggal Pelaksanaan', esc($tanggal($sr['tanggal_pelaksanaan']))],
    ['h.', 'Pelaksana', $isi($sr['pelaksana'])],
];
$data2 = [
    ['a.', 'Jenis Layanan', $pilihan((string) $sr['jenis_layanan'], InstallationReportModel::JENIS_LAYANAN, ['FO', 'WIRELESS', 'VSAT', 'DLL'], [14.4, 30.2, 19.5], 1.8)],
    ['b.', 'Layanan', $isi($sr['layanan'])],
    ['c.', 'Catatan', $isi($sr['catatan'])],
];
$data3 = [
    ['a.', 'Nama Perangkat', $isi($sr['nama_perangkat'])],
];

// Tabel baris "a. Label : isi" mulai 14.1 mm; $lebarNo + $lebarLabel = jarak ke ":" (docx)
$baris = static function (array $rows, float $lebarNo, float $lebarLabel): string {
    $html = '<table class="ird-rows">';
    foreach ($rows as [$no, $label, $nilai]) {
        $html .= '<tr><td style="width:' . $lebarNo . 'mm;">' . $no . '</td>'
            . '<td style="width:' . $lebarLabel . 'mm;">' . esc($label) . '</td>'
            . '<td class="ird-titik">:</td><td class="ird-isi">' . $nilai . '</td></tr>';
    }

    return $html . '</table>';
};

// ---------- Halaman 1 ----------
ob_start(); ?>
    <div class="ird-bar" style="margin-top: <?= round(31.4 - $ATAS, 2) ?>mm; padding-left: 6.8mm;">1. DATA PELANGGAN</div>
    <div style="margin-top: 2mm;"><?= $baris($data1, 4.5, 51.6) ?></div>
    <div class="ird-bar" style="margin-top: 8.2mm; padding-left: 6.8mm;">2. KETERANGAN INSTALASI</div>
    <div style="margin-top: 2.2mm;"><?= $baris($data2, 4.5, 39.1) ?></div>
    <div class="ird-bar" style="margin-top: 1.5mm; padding-left: 6.8mm;">3. INTERFACE PARAMETER</div>
    <div style="margin-top: 6mm;"><?= $baris($data3, 6.4, 37.2) ?></div>
<?php
$hal1 = ob_get_clean();

// ---------- Halaman 2: Dibuat Oleh (Teknis) / Diketahui Oleh ----------
ob_start(); ?>
    <table class="ird-ttd" style="margin-top: <?= round(32.5 - $ATAS, 2) ?>mm;">
        <tr>
            <td style="width: 89.4mm; height: 5mm;">Dibuat Oleh :</td>
            <td style="width: 112mm;">Diketahui Oleh :</td>
        </tr>
        <tr>
            <td style="height: 18.5mm;"></td>
            <td></td>
        </tr>
        <tr>
            <td style="height: 6mm;"><?= $sr['dibuat_oleh'] ? '( ' . esc($sr['dibuat_oleh']) . ' )' : '' ?></td>
            <td><?= $sr['diketahui_oleh'] ? '( ' . esc($sr['diketahui_oleh']) . ' )' : '' ?></td>
        </tr>
        <tr>
            <td>Teknis</td>
            <td></td>
        </tr>
    </table>
<?php
$hal2 = ob_get_clean();

// ---------- Halaman lampiran testlink: foto selebar 139.4 mm (tinggi maks 110 mm) + keterangan ----------
$halFoto = static function (array $foto, bool $denganBar) use ($ATAS): string {
    ob_start(); ?>
    <?php if ($denganBar) : ?>
        <div class="ird-bar" style="margin-top: <?= round(41.6 - $ATAS, 2) ?>mm; padding-left: 10.9mm;">6. LAMPIRAN TESTLINK</div>
    <?php endif; ?>
    <?php foreach ($foto as $i => $f) :
        $w = 139.4;
        $h = $w * max(1, $f['h']) / max(1, $f['w']);
        if ($h > 110) {
            [$w, $h] = [$w * 110 / $h, 110];
        } ?>
        <div class="ird-foto" style="margin-top: <?= $i === 0 ? ($denganBar ? 6 : 9.8) : 8 ?>mm;">
            <img src="<?= $f['src'] ?>" alt="<?= esc($f['ket'] ?: 'Foto ' . ($i + 1), 'attr') ?>" style="width: <?= round($w, 2) ?>mm; height: <?= round($h, 2) ?>mm;">
            <?php if (trim($f['ket']) !== '') : ?>
                <div class="ird-foto-ket"><?= esc($f['ket']) ?></div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <?php
    return ob_get_clean();
};
?>
<style>
    .ird-dok {
        font-family: srcaladea, Cambria, Caladea, Georgia, 'Times New Roman', serif;
        font-size: 12pt;
        line-height: 1.2;
        color: #000;
    }

    .ird-dok table {
        border-collapse: collapse;
    }

    .ird-dok td {
        padding: 0;
        vertical-align: top;
    }

    .ird-kop-judul {
        font-family: srcorbel, Corbel, 'Segoe UI', Arial, sans-serif;
        font-weight: bold;
        font-size: 20pt;
        line-height: 1;
        color: #fff;
        white-space: nowrap;
    }

    /* Bar section warna logo, dari tepi kiri sampai tepi kanan kertas */
    .ird-bar {
        height: 5mm;
        padding-top: 0;
        line-height: 1;
        background: <?= $UTAMA ?>;
        color: #fff;
        font-family: srcorbel, Corbel, 'Segoe UI', Arial, sans-serif;
        font-weight: bold;
        font-size: 12pt;
        white-space: nowrap;
    }

    /* Baris "a. Label : isi" mulai 14.1 mm, jarak baris ~10 mm seperti docx */
    .ird-rows {
        margin-left: 14.1mm;
        width: 182mm;
    }

    .ird-rows td {
        line-height: <?= $BARIS ?>mm;
    }

    .ird-titik {
        width: 2.5mm;
    }

    .ird-dok .ird-cb td {
        vertical-align: middle;
    }

    .ird-cb-kotak {
        width: 6.4mm;
    }

    .ird-cb-teks {
        padding-left: 2mm !important;
        white-space: nowrap;
    }

    .ird-box {
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

    /* Tanda tangan: tengah kolom kiri 51.1 mm, kanan 151.8 mm (seperti docx) */
    .ird-ttd {
        margin-left: 6.4mm;
    }

    .ird-ttd td {
        text-align: center;
    }

    .ird-foto {
        margin-left: 31.75mm;
        page-break-inside: avoid;
    }

    .ird-foto img {
        display: block;
    }

    .ird-foto-ket {
        margin-top: 2.7mm;
    }

    .ird-hal-baru {
        page-break-before: always;
    }
</style>

<?php if ($pdf) : ?>
    <!-- PDF: kop position fixed (tercetak di setiap halaman), isi mengalir dengan pemisah halaman seperti docx -->
    <?= $kop() ?>
    <div class="ird-dok">
        <?= $hal1 ?>
        <div class="ird-hal-baru"><?= $hal2 ?></div>
        <div class="ird-hal-baru"><?= $halFoto($fotoSrc, true) ?></div>
    </div>
<?php else : ?>
    <!-- View: tiap halaman berupa kertas A4 (halaman lampiran: 2 foto per halaman) -->
    <div class="ird-dok">
        <div class="srd-kertas"><?= $kop() . $hal1 ?></div>
        <div class="srd-kertas"><?= $kop() . $hal2 ?></div>
        <?php foreach ($fotoSrc ? array_chunk($fotoSrc, 2) : [[]] as $i => $potong) : ?>
            <div class="srd-kertas"><?= $kop() . $halFoto($potong, $i === 0) ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
