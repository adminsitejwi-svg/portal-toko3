<?php

namespace App\Libraries;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Throwable;

/**
 * Export seluruh "halaman data" aplikasi (satu tabel = satu halaman) ke
 * CSV + Excel, masing-masing dalam folder tersendiri sesuai nama
 * halamannya — dipakai oleh Full Backup.
 *
 * Untuk 3 halaman Data Toko (Alfamidi/Lawson/Alfamart), sebagai ganti PDF
 * disertakan folder "foto" berisi foto lampiran asli tiap toko. Halaman
 * lain tidak punya PDF sama sekali (dianggap tidak perlu — cukup CSV/Excel).
 *
 * Tabel `login` dan `activity_logs` sengaja TIDAK diekspor (kredensial &
 * audit log, bukan data bisnis/halaman biasa).
 */
class DataExportService
{
    /** @var array<string, string> tabel => label halaman */
    private const PAGES = [
        'md_dc'              => 'DC',
        'md_media_koneksi'   => 'Media Koneksi',
        'md_pemilik_projek'  => 'Pemilik Projek',
        'md_vendor'          => 'Vendor Non Celullar',
        'md_vendor_cellular' => 'Vendor Celulllar',
        'md_layanan_vendor'  => 'Layanan Vendor',
        'md_merek_perangkat' => 'Merek Perangkat',
        'md_jenis_perangkat' => 'Jenis Perangkat',
        'md_type_perangkat'  => 'Type Perangkat',
        'md_pelanggan'       => 'Kategori Pelanggan',
        'md_nomer_inet'      => 'Nomor INET',
        'md_quota_simcard'   => 'Kuota Simcard',
        'md_vpn'             => 'VPN',
        'md_barang'          => 'Inventory Kantor',
        'd_midi'             => 'Alfamidi',
        'd_lawson'           => 'Lawson',
        'd_alfamart'         => 'Alfamart',
        'd_simcard'          => 'Data SI (Simcard)',
        'd_nomor_inet'       => 'Nomor Inet (Data Penggunaan)',
        'repot_noc'          => 'Report NOC',
        'aktivasi_ripot'     => 'Aktivasi Retail',
        'jadwal_noc'         => 'Jadwal NOC',
    ];

    /** Tabel Data Toko — dapat folder "foto" berisi lampiran asli, bukan PDF. */
    private const TOKO_TABLES = ['d_midi', 'd_lawson', 'd_alfamart'];

    private const HEADER_COLOR = '1F4E78';
    private const BAND_COLOR   = 'F2F6FB';
    private const BORDER_COLOR = 'DDE3EA';

    protected \Config\Backup $config;

    public function __construct()
    {
        $this->config = config('Backup');
    }

    /**
     * Export semua halaman ke dalam sub-folder $destDir/{Label Halaman}/...
     *
     * @param bool $includePhotos Sertakan folder foto/ untuk halaman Data Toko
     *                            (dipakai Full Backup). Untuk export data saja
     *                            (/backup_files), foto sengaja tidak disertakan
     *                            karena sudah ada perintah /backup_photo sendiri.
     *
     * @return array{ok: list<string>, failed: array<string, string>}
     */
    public function exportAllPages(string $destDir, bool $includePhotos = true): array
    {
        date_default_timezone_set($this->config->timezone);

        $db     = \Config\Database::connect();
        $ok     = [];
        $failed = [];

        foreach (self::PAGES as $table => $label) {
            try {
                if (! $db->tableExists($table)) {
                    $failed[$label] = "Tabel `{$table}` tidak ditemukan di database, dilewati.";
                    continue;
                }

                $rows = $db->table($table)->get()->getResultArray();
                $this->exportPage($destDir, $label, $table, $rows, $includePhotos);
                $ok[] = $label;
            } catch (Throwable $e) {
                log_message('error', "DataExportService::exportAllPages [{$table}] - " . $e->getMessage());
                $failed[$label] = 'Gagal export: ' . $e->getMessage();
            }
        }

        return ['ok' => $ok, 'failed' => $failed];
    }

    private function exportPage(string $destDir, string $label, string $table, array $rows, bool $includePhotos): void
    {
        $slug   = $this->slug($label);
        $folder = rtrim($destDir, '/\\') . DIRECTORY_SEPARATOR . $label;

        if (! is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        $headers = $rows === [] ? [] : array_keys($rows[0]);

        $this->writeCsv($folder . DIRECTORY_SEPARATOR . $slug . '.csv', $headers, $rows);
        $this->writeExcel($folder . DIRECTORY_SEPARATOR . $slug . '.xlsx', $label, $headers, $rows);

        $isToko     = in_array($table, self::TOKO_TABLES, true);
        $photoCount = 0;
        if ($includePhotos && $isToko) {
            $photoCount = $this->exportPhotos($folder, $rows);
        }

        $this->writeNotes($folder . DIRECTORY_SEPARATOR . 'catatan.txt', $label, $table, $rows, $photoCount, $includePhotos && $isToko);
    }

    private function writeCsv(string $path, array $headers, array $rows): void
    {
        $fh = fopen($path, 'wb');
        fwrite($fh, "\xEF\xBB\xBF"); // BOM supaya Excel baca UTF-8 dengan benar

        if ($headers !== []) {
            fputcsv($fh, $headers);
            foreach ($rows as $row) {
                fputcsv($fh, $row);
            }
        }

        fclose($fh);
    }

    private function writeExcel(string $path, string $label, array $headers, array $rows): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle(mb_substr($label, 0, 31));

        if ($headers === []) {
            $sheet->setCellValue('A1', 'Tidak ada data.');
            $sheet->getStyle('A1')->getFont()->setItalic(true)->getColor()->setRGB('888888');
            (new Xlsx($spreadsheet))->save($path);
            $spreadsheet->disconnectWorksheets();

            return;
        }

        $sheet->fromArray($headers, null, 'A1');
        $sheet->fromArray($rows, null, 'A2');

        $lastCol     = $sheet->getHighestColumn();
        $lastRow     = $sheet->getHighestRow();
        $headerRange = "A1:{$lastCol}1";
        $dataRange   = "A1:{$lastCol}{$lastRow}";

        // Header modern: fill gelap, teks putih tebal, center.
        $sheet->getStyle($headerRange)->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11, 'name' => 'Calibri'],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::HEADER_COLOR]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => self::HEADER_COLOR]]],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(24);

        // Border tipis + alignment rapi untuk seluruh data.
        $sheet->getStyle($dataRange)->applyFromArray([
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => self::BORDER_COLOR]]],
            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER],
            'font'      => ['name' => 'Calibri', 'size' => 10],
        ]);

        // Baris selang-seling (banded rows) supaya lebih mudah dibaca.
        for ($r = 2; $r <= $lastRow; $r++) {
            if ($r % 2 === 0) {
                $sheet->getStyle("A{$r}:{$lastCol}{$r}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setRGB(self::BAND_COLOR);
            }
        }

        $sheet->setAutoFilter($headerRange);
        $sheet->freezePane('A2');

        foreach (range('A', $lastCol) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        (new Xlsx($spreadsheet))->save($path);
        $spreadsheet->disconnectWorksheets();
    }

    /**
     * Salin foto lampiran asli tiap toko ke folder "foto" milik halaman ini,
     * diberi awalan kode_toko supaya jelas foto itu punya toko yang mana.
     */
    private function exportPhotos(string $pageFolder, array $rows): int
    {
        $sourceDir = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . 'lampiran';
        if (! is_dir($sourceDir)) {
            return 0;
        }

        $photoDir = $pageFolder . DIRECTORY_SEPARATOR . 'foto';
        $copied   = 0;

        foreach ($rows as $row) {
            $raw = $row['upload_lampiran'] ?? null;
            if (empty($raw)) {
                continue;
            }

            $decoded = json_decode((string) $raw, true);
            $files   = is_array($decoded) ? $decoded : [$raw];

            $storeCode = $this->slug((string) ($row['kode_toko'] ?? ('id-' . ($row['id'] ?? '0'))));

            foreach ($files as $filename) {
                $filename = basename((string) $filename); // proteksi path traversal
                $source   = $sourceDir . DIRECTORY_SEPARATOR . $filename;

                if ($filename === '' || ! is_file($source)) {
                    continue;
                }

                if (! is_dir($photoDir)) {
                    mkdir($photoDir, 0755, true);
                }

                copy($source, $photoDir . DIRECTORY_SEPARATOR . $storeCode . '_' . $filename);
                $copied++;
            }
        }

        return $copied;
    }

    private function writeNotes(string $path, string $label, string $table, array $rows, int $photoCount, bool $isToko): void
    {
        $count  = count($rows);
        $latest = null;
        foreach ($rows as $row) {
            if (! empty($row['created_at']) && ($latest === null || $row['created_at'] > $latest)) {
                $latest = $row['created_at'];
            }
        }

        $lines   = [];
        $lines[] = "Halaman        : {$label}";
        $lines[] = "Tabel database : {$table}";
        $lines[] = "Jumlah data    : {$count} baris";
        $lines[] = 'Data terbaru dibuat pada : ' . ($latest ?? '-');
        $lines[] = 'Backup dibuat pada       : ' . date('Y-m-d H:i:s') . ' (Asia/Jakarta)';

        if ($isToko) {
            $lines[] = "Foto lampiran  : {$photoCount} file (lihat folder foto/)";
        }

        $lines[] = '';
        $lines[] = 'Catatan: sistem saat ini hanya mencatat tanggal data DIBUAT (created_at),';
        $lines[] = 'belum mencatat tanggal terakhir data DIUBAH (updated_at). Jadi tanggal di';
        $lines[] = 'atas bukan berarti seluruh data di file ini terakhir diubah pada tanggal itu.';

        file_put_contents($path, implode("\r\n", $lines));
    }

    private function slug(string $label): string
    {
        $slug = strtolower($label);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);

        return trim((string) $slug, '-');
    }
}
