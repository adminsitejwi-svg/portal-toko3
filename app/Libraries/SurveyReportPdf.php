<?php

namespace App\Libraries;

use App\Models\SurveyReportModel;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * PDF Survey Report & Installation Report (dompdf), tampilan sama dengan halaman View dan dengan docx Yesnet
 * (dokumen SurveyReport/Dokumen.php & InstallationReport/Dokumen.php).
 * - Logo perusahaan & foto lampiran disisipkan sebagai data URI.
 * - Foto diperkecil dulu (maks 1200 px, JPEG) agar ukuran PDF / lampiran email tetap kecil; di-cache.
 * - Font seperti di docx: isi Cambria/Caladea, judul Open Sans (Survey) / Corbel (Installation); dipakai bila ada di server,
 *   selain itu kembali ke font bawaan dompdf.
 * - PDF dikunci: hanya bisa dibuka & dicetak, tidak bisa diubah / disalin (sama dengan PDF RFO).
 */
class SurveyReportPdf
{
    private const MAKS_SISI = 1200;

    /**
     * Font: [nama keluarga di CSS => daftar kandidat [normal, bold]], dicari berurutan (Windows, lalu Linux).
     * File .ttc (Cambria di Windows) diambil font pertamanya menjadi .ttf karena dompdf hanya membaca .ttf.
     */
    private const FONT = [
        'srcaladea' => [
            ['C:/Windows/Fonts/cambria.ttc', 'C:/Windows/Fonts/cambriab.ttf'],
            ['/usr/share/fonts/truetype/crosextra/Caladea-Regular.ttf', '/usr/share/fonts/truetype/crosextra/Caladea-Bold.ttf'],
        ],
        // Judul & bar section di docx memakai Open Sans tebal; dipakai Open Sans bila ada, selain itu Segoe UI (mirip)
        'sropensans' => [
            ['C:/Windows/Fonts/OpenSans-Regular.ttf', 'C:/Windows/Fonts/OpenSans-Bold.ttf'],
            ['C:/Windows/Fonts/segoeui.ttf', 'C:/Windows/Fonts/segoeuib.ttf'],
            ['/usr/share/fonts/truetype/open-sans/OpenSans-Regular.ttf', '/usr/share/fonts/truetype/open-sans/OpenSans-Bold.ttf'],
        ],
        // Judul & bar section docx Installation Report memakai Corbel tebal
        'srcorbel' => [
            ['C:/Windows/Fonts/corbel.ttf', 'C:/Windows/Fonts/corbelb.ttf'],
        ],
    ];

    /** Nama file PDF Survey Report, mis. SVR-10-11-2026-001.pdf */
    public static function namaFile(array $sr): string
    {
        return self::namaFileId($sr['survey_id']);
    }

    /** Nama file PDF dari nomor dokumen: karakter selain huruf/angka/- jadi "-" */
    public static function namaFileId(string $id): string
    {
        return preg_replace('/[^A-Za-z0-9\-]+/', '-', $id) . '.pdf';
    }

    /**
     * @param array $data Data dokumen dari SurveyReport::dokumenData() (sr, namaTtd, warna, ...)
     * @param array  $foto   Foto lampiran yang ada di folder foto: [['file', 'ket'], ...]
     * @param string $view   View PDF (SurveyReport/Pdf atau InstallationReport/Pdf)
     * @param string|null $folder Folder foto (bawaan: folder foto Survey Report)
     */
    public function buat(array $data, array $foto, string $view = 'SurveyReport/Pdf', ?string $folder = null): string
    {
        $folder ??= SurveyReportModel::folderFoto();
        $logo = $data['sr']['logo'] ?? null;

        // Foto: ['src', 'w', 'h', 'ket']
        $fotoSrc = [];
        foreach ($foto as $l) {
            if ($u = $this->fotoUri($folder . $l['file'])) {
                $fotoSrc[] = $u + ['ket' => $l['ket']];
            }
        }

        $html = view($view, $data + [
            'logo'    => self::logoInfo($logo),
            'fotoSrc' => $fotoSrc,
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'Times-Roman');
        // Jarak baris = line-height x tinggi font (seperti browser), agar teks di bar biru tidak turun
        $options->set('fontHeightRatio', 1.0);
        // Folder font di writable agar font tambahan bisa didaftarkan & di-cache
        $dirFont = WRITEPATH . 'cache/dompdf-fonts';
        if (! is_dir($dirFont)) {
            mkdir($dirFont, 0775, true);
        }
        $options->set('fontDir', $dirFont);
        $options->set('fontCache', $dirFont);

        $dompdf = new Dompdf($options);
        $this->daftarFont($dompdf);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Kunci PDF: tanpa password untuk membuka, hanya izin cetak (tidak bisa diubah / disalin)
        $dompdf->getCanvas()->get_cpdf()->setEncryption('', bin2hex(random_bytes(16)), ['print']);

        return $dompdf->output();
    }

    /** Daftarkan font docx ke dompdf bila file font ada (sekali, lalu tersimpan di cache font). */
    private function daftarFont(Dompdf $dompdf): void
    {
        $metrics = $dompdf->getFontMetrics();

        foreach (self::FONT as $keluarga => $kandidat) {
            if ($metrics->getFamily($keluarga) !== null) {
                continue;
            }
            foreach ($kandidat as [$normal, $bold]) {
                if (! is_file($normal) || ! is_file($bold)) {
                    continue;
                }
                if (str_ends_with(strtolower($normal), '.ttc')) {
                    $normal = $this->ttcKeTtf($normal);
                    if ($normal === null) {
                        continue;
                    }
                }
                // dompdf hanya membaca file di dalam chroot
                $dompdf->getOptions()->setChroot(array_merge($dompdf->getOptions()->getChroot(), [dirname($normal), dirname($bold)]));
                $metrics->registerFont(['family' => $keluarga, 'weight' => 'normal', 'style' => 'normal'], $normal);
                $metrics->registerFont(['family' => $keluarga, 'weight' => 'bold', 'style' => 'normal'], $bold);
                break;
            }
        }
    }

    /** Ambil font pertama dari file koleksi .ttc menjadi .ttf (di-cache); null bila gagal. */
    private function ttcKeTtf(string $ttc): ?string
    {
        $out = self::folderCache() . pathinfo($ttc, PATHINFO_FILENAME) . '-' . filemtime($ttc) . '.ttf';
        if (is_file($out)) {
            return $out;
        }

        $d = (string) file_get_contents($ttc);
        if (substr($d, 0, 4) !== 'ttcf') {
            return null;
        }
        $awal      = unpack('N', substr($d, 12, 4))[1];
        $jmlTabel  = unpack('n', substr($d, $awal + 4, 2))[1];
        $posData   = 12 + 16 * $jmlTabel;
        $rekaman   = '';
        $isi       = '';

        for ($i = 0; $i < $jmlTabel; $i++) {
            $r = unpack('a4tag/Ncek/Noff/Nlen', substr($d, $awal + 12 + 16 * $i, 16));
            $rekaman .= $r['tag'] . pack('NNN', $r['cek'], $posData + strlen($isi), $r['len']);
            $tabel = substr($d, $r['off'], $r['len']);
            $isi  .= $tabel . str_repeat("\0", (4 - strlen($tabel) % 4) % 4);
        }

        file_put_contents($out, substr($d, $awal, 12) . $rekaman . $isi);

        return $out;
    }

    /**
     * Logo sebagai data URI beserta ukurannya (px), untuk dimuat pas di kotak logo kop.
     *
     * @return array{src:string,w:int,h:int}|null
     */
    public static function logoInfo(?string $logo): ?array
    {
        $src = self::logoUri($logo);
        if ($src === null) {
            return null;
        }
        $ukuran = @getimagesizefromstring(base64_decode(substr($src, strpos($src, ',') + 1)));

        return ['src' => $src, 'w' => (int) ($ukuran[0] ?? 400), 'h' => (int) ($ukuran[1] ?? 160)];
    }

    private static function folderCache(): string
    {
        $dir = WRITEPATH . 'cache/survey-report-pdf/';
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        return $dir;
    }

    /**
     * Logo perusahaan (path relatif public/) sebagai data URI PNG, ruang kosong di sekelilingnya
     * dipotong agar logo tampil besar (dipakai juga halaman View). Null bila file tidak ada.
     */
    public static function logoUri(?string $logo): ?string
    {
        if (empty($logo) || ! is_file(FCPATH . $logo)) {
            return null;
        }

        $sumber = FCPATH . $logo;
        $file   = self::folderCache() . md5('logo|' . $sumber . '|' . filemtime($sumber)) . '.png';

        if (! is_file($file)) {
            // @: libpng memberi warning "iCCP: known incorrect sRGB profile" untuk sebagian logo; gambar tetap terbaca
            $img = @imagecreatefromstring((string) file_get_contents($sumber));
            if ($img === false) {
                return 'data:image/png;base64,' . base64_encode((string) file_get_contents($sumber));
            }
            imagesavealpha($img, true);
            $w = imagesx($img);
            $h = imagesy($img);

            // Piksel "isi": tidak transparan dan tidak (hampir) putih
            [$kiri, $kanan, $atas, $bawah] = [$w, -1, $h, -1];
            for ($y = 0; $y < $h; $y += 2) {
                for ($x = 0; $x < $w; $x += 2) {
                    $c = imagecolorat($img, $x, $y);
                    if ((($c >> 24) & 0x7F) < 100 && ((($c >> 16) & 0xFF) < 235 || (($c >> 8) & 0xFF) < 235 || ($c & 0xFF) < 235)) {
                        $kiri  = min($kiri, $x);
                        $kanan = max($kanan, $x);
                        $atas  = min($atas, $y);
                        $bawah = max($bawah, $y);
                    }
                }
            }

            if ($kanan >= $kiri && $bawah >= $atas) {
                $kiri  = max(0, $kiri - 4);
                $atas  = max(0, $atas - 4);
                $kanan = min($w - 1, $kanan + 4);
                $bawah = min($h - 1, $bawah + 4);
                $potong = imagecrop($img, ['x' => $kiri, 'y' => $atas, 'width' => $kanan - $kiri + 1, 'height' => $bawah - $atas + 1]);
                if ($potong !== false) {
                    imagedestroy($img);
                    $img = $potong;
                    imagesavealpha($img, true);
                }
            }

            imagepng($img, $file, 9);
            imagedestroy($img);
        }

        return 'data:image/png;base64,' . base64_encode((string) file_get_contents($file));
    }

    /**
     * Foto diperkecil ke JPEG (sisi terpanjang maks 1200 px, perbandingan asli seperti di docx) sebagai data URI.
     * Kembalikan ['src', 'w', 'h'] atau null bila gagal dibaca. Hasil di-cache.
     */
    private function fotoUri(string $file): ?array
    {
        if (! is_file($file)) {
            return null;
        }

        $cache = self::folderCache() . md5('asli|' . $file . '|' . filemtime($file)) . '.jpg';

        if (! is_file($cache)) {
            // @: libpng bisa memberi warning profil warna; gambar tetap terbaca
            $src = @imagecreatefromstring((string) file_get_contents($file));
            if ($src === false) {
                return null;
            }
            $w     = imagesx($src);
            $h     = imagesy($src);
            $skala = min(1, self::MAKS_SISI / max($w, $h));
            $nw    = max(1, (int) round($w * $skala));
            $nh    = max(1, (int) round($h * $skala));

            // latar putih untuk PNG transparan
            $img = imagecreatetruecolor($nw, $nh);
            imagefill($img, 0, 0, imagecolorallocate($img, 255, 255, 255));
            imagecopyresampled($img, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);
            imagejpeg($img, $cache, 80);
            imagedestroy($img);
            imagedestroy($src);
        }

        $ukuran = getimagesize($cache) ?: [4, 3];

        return [
            'src' => 'data:image/jpeg;base64,' . base64_encode((string) file_get_contents($cache)),
            'w'   => (int) $ukuran[0],
            'h'   => (int) $ukuran[1],
        ];
    }
}
