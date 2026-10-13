<?php

namespace App\Libraries;

use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * =====================================================================
 *  PDF SURAT RFO — dibuat otomatis dari data RFO (tampilan = halaman View)
 *
 *  - Gelombang kop atas/bawah digambar dengan GD menjadi PNG
 *    (dompdf tidak mendukung gradasi SVG), di-cache di writable/cache.
 *  - Logo perusahaan dipotong otomatis dari ruang kosong agar tampil besar.
 *  - PDF dikunci: hanya bisa dibuka & dicetak, tidak bisa diubah / disalin.
 * =====================================================================
 */
class RfoPdf
{
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
        return preg_replace('/[^A-Za-z0-9\-]+/', '-', $rfo['rfo_id']) . '.pdf';
    }

    /**
     * Buat PDF surat RFO (isi biner).
     *
     * @param array $data Data surat dari RFO::suratData() (rfo, warnaKop, pembuka, penutup, ...)
     */
    public function buat(array $data): string
    {
        $rfo = $data['rfo'];

        $html = view('RFO/PdfRFO', $data + [
            'kopAtas'  => $this->dataUri($this->gelombangPng($data['warnaKop'], false)),
            'kopBawah' => $this->dataUri($this->gelombangPng($data['warnaKop'], true)),
            'logo'     => ! empty($rfo['logo']) && is_file(FCPATH . $rfo['logo'])
                ? $this->dataUri($this->logoTrim(FCPATH . $rfo['logo']))
                : null,
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Kunci PDF: tanpa password untuk membuka, hanya izin cetak (tidak bisa diubah / disalin)
        $dompdf->getCanvas()->get_cpdf()->setEncryption('', bin2hex(random_bytes(16)), ['print']);

        return $dompdf->output();
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
