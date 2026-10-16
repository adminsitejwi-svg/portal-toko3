<?php

namespace App\Libraries;

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * =====================================================================
 *  PDF SURAT RFO & MAINTENANCE — dibuat otomatis dari data surat (tampilan = halaman View)
 *
 *  - Gelombang kop atas/bawah digambar dengan GD menjadi PNG
 *    (dompdf tidak mendukung gradasi SVG), di-cache di writable/cache.
 *  - Logo perusahaan dipotong otomatis dari ruang kosong agar tampil besar.
 *  - Surat RFO perusahaan dengan kop khusus (JWI, Flazznet, Sprintnet, Rnet) memakai
 *    tata letak kop yang sama dengan halaman View; potongan logo & hiasan footer
 *    (pudar / abu-abu) disiapkan dengan GD karena dompdf tidak mendukung
 *    object-fit, filter, maupun mix-blend-mode.
 *  - Font Calibri & Arial (seperti di View) dipakai bila ada di server,
 *    selain itu kembali ke Helvetica.
 *  - PDF dikunci: hanya bisa dibuka & dicetak, tidak bisa diubah / disalin.
 * =====================================================================
 */
class RfoPdf
{
    // Perusahaan dengan kop surat khusus, sama dengan $kop di app/Views/RFO/ViewRFO.php
    public const KOP = [
        'PT JURAGAN WIFI INDONESIA'          => 'jwi',
        'PT GENEZIS MITRA TECHNOLOGY'        => 'flazz',
        'PT NORLEC TELEKOMUNIKASI INDONESIA' => 'norlec',
        'PT RTIGA GLOBAL MEDIA'              => 'rtiga',
    ];

    /**
     * Bingkai logo per kop, dari CSS ViewRFO.php:
     * [lebar bingkai, tinggi bingkai, lebar gambar, margin kiri, margin atas] (px).
     * 'kop' = logo kanan atas (.kop-logo), 'ttd' = logo di bawah nama petugas (.rfo-sign-logo).
     */
    private const BINGKAI_LOGO = [
        'jwi'    => ['kop' => [320, 62, 342, -8, -57], 'ttd' => [150, 29, 160, -4, -27]],
        'flazz'  => ['kop' => [205, 41, 232, -14, -38], 'ttd' => [150, 30, 170, -10, -28]],
        'norlec' => ['kop' => [240, 40, 271, -11, -17], 'ttd' => [150, 25, 169, -7, -11]],
        'rtiga'  => ['kop' => [190, 58, 202, -6, -8], 'ttd' => [150, 46, 159, -5, -6]],
    ];

    /**
     * Hiasan kanan bawah (.jwi-flame / .norlec-s di ViewRFO.php), bingkai 794x230 di dasar kertas:
     * [file, lebar gambar, kiri, atas, opacity, grayscale].
     */
    private const HIASAN = [
        'jwi'    => ['Logo-footer/LOGO-API.png', 820, 330, -61, 0.65, 0.0],
        'norlec' => ['Logo-footer/LOGO-S.png', 810, 295, 0, 0.5, 0.85],
    ];

    // Font seperti di View: [nama keluarga => [normal, bold]], dicari berurutan (Windows, lalu Linux)
    private const FONT = [
        'calibri' => [
            ['C:/Windows/Fonts/calibri.ttf', 'C:/Windows/Fonts/calibrib.ttf'],
            ['/usr/share/fonts/truetype/crosextra/Carlito-Regular.ttf', '/usr/share/fonts/truetype/crosextra/Carlito-Bold.ttf'],
        ],
        'arial' => [
            ['C:/Windows/Fonts/arial.ttf', 'C:/Windows/Fonts/arialbd.ttf'],
            ['/usr/share/fonts/truetype/liberation/LiberationSans-Regular.ttf', '/usr/share/fonts/truetype/liberation/LiberationSans-Bold.ttf'],
        ],
    ];
    // Path gelombang (viewBox 800x160), sama dengan SVG di app/Views/RFO/ViewRFO.php
    private const GELOMBANG = [
        ['aksen', [['M', 0, 0], ['H', 660], ['C', 480, 34, 250, 56, 0, 66], ['Z']]],
        ['utama', [['M', 0, 80], ['C', 250, 74, 470, 30, 800, 22], ['V', 62], ['C', 560, 66, 330, 128, 0, 132], ['Z']]],
        ['utama', [['M', 360, 148], ['C', 540, 112, 680, 86, 800, 84], ['V', 98], ['C', 660, 104, 520, 134, 360, 148], ['Z']]],
        ['aksen', [['M', 590, 152], ['C', 680, 128, 750, 116, 800, 114], ['V', 136], ['C', 740, 138, 670, 144, 590, 152], ['Z']]],
    ];

    /** Nama file PDF untuk RFO, mis. RFO-13-10-2026-001.pdf */
    public static function namaFile(array $rfo): string
    {
        return self::namaFileId($rfo['rfo_id']);
    }

    /** Nama file PDF dari nomor surat (RFO ID / Maintenance ID): karakter selain huruf/angka/- jadi "-" */
    public static function namaFileId(string $id): string
    {
        return preg_replace('/[^A-Za-z0-9\-]+/', '-', $id) . '.pdf';
    }

    /**
     * Buat PDF surat (isi biner).
     *
     * @param array  $data Data surat dari RFO::suratData() / MDMaintenance::suratData() (warnaKop, pembuka, penutup, ...)
     * @param string $view View PDF: RFO/PdfRFO atau MDMaintenance/PdfMaintenance
     */
    public function buat(array $data, string $view = 'RFO/PdfRFO'): string
    {
        // Logo perusahaan dari data RFO ($data['rfo']) atau Maintenance ($data['mt'])
        $logo     = ($data['rfo'] ?? $data['mt'] ?? [])['logo'] ?? null;
        $fileLogo = ! empty($logo) && is_file(FCPATH . $logo) ? FCPATH . $logo : null;

        // Kop khusus perusahaan (surat RFO & Maintenance, sama dengan halaman View)
        $kop = self::KOP[($data['rfo'] ?? $data['mt'] ?? [])['perusahaan'] ?? ''] ?? null;

        $html = view($view, $data + [
            'kopAtas'  => $this->dataUri($this->gelombangPng($data['warnaKop'], false)),
            'kopBawah' => $this->dataUri($this->gelombangPng($data['warnaKop'], true)),
            'logo'     => $fileLogo ? $this->dataUri($this->logoTrim($fileLogo)) : null,
            'kop'      => $kop,
        ] + ($kop ? $this->asetKop($kop, $fileLogo) : []));

        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'Helvetica');
        // Folder font di writable agar font tambahan (Calibri/Arial) bisa didaftarkan & di-cache
        $dirFont = WRITEPATH . 'cache/dompdf-fonts';
        if (! is_dir($dirFont)) {
            mkdir($dirFont, 0775, true);
        }
        $options->set('fontDir', $dirFont);
        $options->set('fontCache', $dirFont);

        if ($kop) {
            // Jarak baris = line-height x tinggi font (lihat catatan line-height di RFO/PdfRFO.php)
            $options->set('fontHeightRatio', 1.0);
        }

        $dompdf = new Dompdf($options);
        if ($kop) {
            $this->daftarFont($dompdf);
        }
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Kunci PDF: tanpa password untuk membuka, hanya izin cetak (tidak bisa diubah / disalin)
        $dompdf->getCanvas()->get_cpdf()->setEncryption('', bin2hex(random_bytes(16)), ['print']);

        return $dompdf->output();
    }

    /** Gambar kop khusus sebagai data URI: logo kanan atas, logo tanda tangan, hiasan kanan bawah. */
    private function asetKop(string $kop, ?string $fileLogo): array
    {
        $aset = ['logoKop' => null, 'logoTtd' => null, 'hiasan' => null];

        if ($fileLogo) {
            $aset['logoKop'] = $this->dataUri($this->bingkai($fileLogo, ...self::BINGKAI_LOGO[$kop]['kop']));
            $aset['logoTtd'] = $this->dataUri($this->bingkai($fileLogo, ...self::BINGKAI_LOGO[$kop]['ttd']));
        }

        if (isset(self::HIASAN[$kop]) && is_file(FCPATH . self::HIASAN[$kop][0])) {
            [$file, $lebar, $kiri, $atas, $opacity, $abu] = self::HIASAN[$kop];
            $aset['hiasan'] = $this->dataUri($this->bingkai(FCPATH . $file, 794, 230, $lebar, $kiri, $atas, $opacity, $abu, 2));
        }

        return $aset;
    }

    /** Daftarkan Calibri & Arial ke dompdf bila file font ada (sekali, lalu tersimpan di cache font). */
    private function daftarFont(Dompdf $dompdf): void
    {
        $metrics = $dompdf->getFontMetrics();

        foreach (self::FONT as $keluarga => $kandidat) {
            if ($metrics->getFamily($keluarga) !== null) {
                continue;
            }
            foreach ($kandidat as [$normal, $bold]) {
                if (is_file($normal) && is_file($bold)) {
                    // dompdf hanya membaca file di dalam chroot
                    $dompdf->getOptions()->setChroot(array_merge($dompdf->getOptions()->getChroot(), [dirname($normal)]));
                    $metrics->registerFont(['family' => $keluarga, 'weight' => 'normal', 'style' => 'normal'], $normal);
                    $metrics->registerFont(['family' => $keluarga, 'weight' => 'bold', 'style' => 'normal'], $bold);
                    break;
                }
            }
        }
    }

    /**
     * Potong gambar seperti bingkai overflow:hidden di View: gambar selebar $lebar px (tinggi proporsional)
     * diletakkan di ($kiri, $atas) dalam bingkai $bw x $bh px. Opsional: opacity & grayscale (0-1).
     * Hasil PNG transparan $skala x ukuran bingkai, di-cache.
     */
    private function bingkai(string $sumber, int $bw, int $bh, int $lebar, int $kiri, int $atas, float $opacity = 1.0, float $abu = 0.0, int $skala = 3): string
    {
        $file = $this->cachePath(implode('|', ['bingkai', $sumber, filemtime($sumber), $bw, $bh, $lebar, $kiri, $atas, $opacity, $abu, $skala]));
        if (is_file($file)) {
            return $file;
        }

        // @: libpng memberi warning "iCCP: known incorrect sRGB profile" untuk sebagian gambar; gambar tetap terbaca
        $src = @imagecreatefromstring((string) file_get_contents($sumber));
        if ($src === false) {
            return $sumber;
        }
        $sw = imagesx($src);
        $sh = imagesy($src);

        $w   = $bw * $skala;
        $h   = $bh * $skala;
        $img = imagecreatetruecolor($w, $h);
        imagealphablending($img, false);
        imagesavealpha($img, true);
        imagefill($img, 0, 0, imagecolorallocatealpha($img, 255, 255, 255, 127));
        imagecopyresampled($img, $src, $kiri * $skala, $atas * $skala, 0, 0, $lebar * $skala, (int) round($lebar * $sh / $sw * $skala), $sw, $sh);
        imagedestroy($src);

        if ($opacity < 1 || $abu > 0) {
            for ($y = 0; $y < $h; $y++) {
                for ($x = 0; $x < $w; $x++) {
                    $c = imagecolorat($img, $x, $y);
                    $a = ($c >> 24) & 0x7F;
                    if ($a === 127) {
                        continue;
                    }
                    $r = ($c >> 16) & 0xFF;
                    $g = ($c >> 8) & 0xFF;
                    $b = $c & 0xFF;
                    if ($abu > 0) {
                        // grayscale() CSS: campuran warna asli dengan luminansi
                        $l = 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
                        $r = $r + ($l - $r) * $abu;
                        $g = $g + ($l - $g) * $abu;
                        $b = $b + ($l - $b) * $abu;
                    }
                    $a = 127 - (int) round((127 - $a) * $opacity);
                    imagesetpixel($img, $x, $y, imagecolorallocatealpha($img, (int) round($r), (int) round($g), (int) round($b), $a));
                }
            }
        }

        imagepng($img, $file, 9);
        imagedestroy($img);

        return $file;
    }

    private function dataUri(string $file): string
    {
        return 'data:image/png;base64,' . base64_encode((string) file_get_contents($file));
    }

    private function cachePath(string $kunci): string
    {
        $dir = WRITEPATH . 'cache/rfo-pdf/';
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        return $dir . md5($kunci) . '.png';
    }

    /**
     * Gelombang kop sebagai PNG transparan 1600x320 (2x viewBox).
     * $balik = true untuk gelombang bawah (diputar 180 derajat seperti di View).
     *
     * @param array{0:string,1:string,2:string} $warna [gradasi gelap, gradasi terang, aksen]
     */
    private function gelombangPng(array $warna, bool $balik): string
    {
        $file = $this->cachePath('kop|' . implode('|', $warna) . '|' . (int) $balik);
        if (is_file($file)) {
            return $file;
        }

        $skala = 2;
        $w     = 800 * $skala;
        $h     = 160 * $skala;
        $img   = imagecreatetruecolor($w, $h);
        imagesavealpha($img, true);
        imagealphablending($img, false);
        imagefill($img, 0, 0, imagecolorallocatealpha($img, 255, 255, 255, 127));
        imagealphablending($img, true);
        imageantialias($img, true);

        $rgb    = static fn (string $hex) => sscanf(ltrim($hex, '#'), '%02x%02x%02x');
        $gelap  = $rgb($warna[0]);
        $terang = $rgb($warna[1]);
        $aksen  = $rgb($warna[2]);

        foreach (self::GELOMBANG as [$jenis, $path]) {
            $titik = $this->ratakanPath($path, $skala);

            if ($jenis === 'aksen') {
                imagefilledpolygon($img, $titik, imagecolorallocate($img, ...$aksen));
                continue;
            }

            // Gradasi horizontal: isi poligon per irisan vertikal 4px dengan warna berbeda
            for ($x = 0; $x < $w; $x += 4) {
                $t = $x / ($w - 1);
                imagesetclip($img, $x, 0, min($x + 3, $w - 1), $h - 1);
                imagefilledpolygon($img, $titik, imagecolorallocate(
                    $img,
                    (int) round($gelap[0] + ($terang[0] - $gelap[0]) * $t),
                    (int) round($gelap[1] + ($terang[1] - $gelap[1]) * $t),
                    (int) round($gelap[2] + ($terang[2] - $gelap[2]) * $t)
                ));
            }
            imagesetclip($img, 0, 0, $w - 1, $h - 1);
        }

        if ($balik) {
            imageflip($img, IMG_FLIP_BOTH);
        }

        imagepng($img, $file, 9);
        imagedestroy($img);

        return $file;
    }

    /** Ubah perintah path (M, H, V, C, Z) menjadi daftar titik poligon untuk GD. */
    private function ratakanPath(array $path, int $skala): array
    {
        $titik = [];
        $x     = 0;
        $y     = 0;

        foreach ($path as $cmd) {
            switch ($cmd[0]) {
                case 'M':
                    [, $x, $y] = $cmd;
                    $titik[]   = [$x, $y];
                    break;

                case 'H':
                    $x       = $cmd[1];
                    $titik[] = [$x, $y];
                    break;

                case 'V':
                    $y       = $cmd[1];
                    $titik[] = [$x, $y];
                    break;

                case 'C':
                    [, $x1, $y1, $x2, $y2, $x3, $y3] = $cmd;
                    for ($i = 1; $i <= 40; $i++) {
                        $t       = $i / 40;
                        $u       = 1 - $t;
                        $titik[] = [
                            $u ** 3 * $x + 3 * $u ** 2 * $t * $x1 + 3 * $u * $t ** 2 * $x2 + $t ** 3 * $x3,
                            $u ** 3 * $y + 3 * $u ** 2 * $t * $y1 + 3 * $u * $t ** 2 * $y2 + $t ** 3 * $y3,
                        ];
                    }
                    [$x, $y] = [$x3, $y3];
                    break;
            }
        }

        $datar = [];
        foreach ($titik as [$px, $py]) {
            $datar[] = (int) round($px * $skala);
            $datar[] = (int) round($py * $skala);
        }

        return $datar;
    }

    /** Potong ruang kosong (putih / transparan) di sekeliling logo, hasil PNG. */
    private function logoTrim(string $sumber): string
    {
        $file = $this->cachePath('logo|' . $sumber . '|' . filemtime($sumber));
        if (is_file($file)) {
            return $file;
        }

        // @: libpng memberi warning "iCCP: known incorrect sRGB profile" untuk sebagian logo; gambar tetap terbaca
        $img = @imagecreatefromstring((string) file_get_contents($sumber));
        if ($img === false) {
            return $sumber;
        }
        imagesavealpha($img, true);
        $w = imagesx($img);
        $h = imagesy($img);

        // Piksel "isi": tidak transparan dan tidak (hampir) putih
        $isi = static function (int $px, int $py) use ($img): bool {
            $c = imagecolorat($img, $px, $py);
            $a = ($c >> 24) & 0x7F;
            $r = ($c >> 16) & 0xFF;
            $g = ($c >> 8) & 0xFF;
            $b = $c & 0xFF;

            return $a < 100 && ($r < 235 || $g < 235 || $b < 235);
        };

        $kiri  = $w;
        $kanan = -1;
        $atas  = $h;
        $bawah = -1;
        for ($py = 0; $py < $h; $py += 2) {
            for ($px = 0; $px < $w; $px += 2) {
                if ($isi($px, $py)) {
                    $kiri  = min($kiri, $px);
                    $kanan = max($kanan, $px);
                    $atas  = min($atas, $py);
                    $bawah = max($bawah, $py);
                }
            }
        }

        if ($kanan >= $kiri && $bawah >= $atas) {
            $pad   = 4;
            $kiri  = max(0, $kiri - $pad);
            $atas  = max(0, $atas - $pad);
            $kanan = min($w - 1, $kanan + $pad);
            $bawah = min($h - 1, $bawah + $pad);
            $potong = imagecrop($img, ['x' => $kiri, 'y' => $atas, 'width' => $kanan - $kiri + 1, 'height' => $bawah - $atas + 1]);
            if ($potong !== false) {
                imagedestroy($img);
                $img = $potong;
                imagesavealpha($img, true);
            }
        }

        imagepng($img, $file, 9);
        imagedestroy($img);

        return $file;
    }
}
