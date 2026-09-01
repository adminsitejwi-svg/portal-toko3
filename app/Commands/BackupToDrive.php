<?php

namespace App\Commands;

use App\Libraries\BackupService;
use App\Libraries\GoogleDriveService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Backup manual -> Google Drive. Tidak ada penyimpanan lokal permanen dan
 * tidak ada UI — dijalankan langsung lewat spark, chat, atau bot Telegram.
 *
 *   php spark backup:drive database   - database saja (.sql)
 *   php spark backup:drive files      - export data: CSV + Excel + catatan (semua halaman)
 *   php spark backup:drive photo      - foto/lampiran mentah saja
 *   php spark backup:drive full       - database + file + export data (+ foto khusus toko)
 */
class BackupToDrive extends BaseCommand
{
    protected $group       = 'Backup';
    protected $name        = 'backup:drive';
    protected $description = 'Membuat backup (database/files/photo/full) dan mengupload ke Google Drive, tanpa menyimpan salinan lokal.';
    protected $usage       = 'backup:drive <database|files|photo|full>';

    public function run(array $params)
    {
        $type = strtolower((string) ($params[0] ?? 'full'));

        if (! in_array($type, ['database', 'files', 'photo', 'full'], true)) {
            CLI::error('Jenis backup tidak valid. Gunakan: database, files, photo, atau full.');

            return;
        }

        $result = self::execute($type);

        if ($result['success']) {
            CLI::write('Berhasil: ' . $result['message'], 'green');
            CLI::write('Drive: ' . $result['link']);
        } else {
            CLI::error('Gagal: ' . $result['message']);
        }
    }

    /**
     * Dipakai bersama oleh command ini dan bot Telegram.
     *
     * @return array{success: bool, message: string, link?: string}
     */
    public static function execute(string $type): array
    {
        $backup = new BackupService();
        $drive  = new GoogleDriveService();

        if (! $drive->isAuthorized()) {
            return ['success' => false, 'message' => 'Google Drive belum diotorisasi. Jalankan "php spark drive:authorize" dulu.'];
        }

        $created = match ($type) {
            'database' => $backup->createDatabaseDump(),
            'files'    => $backup->createDataExportZip(),
            'photo'    => $backup->createPhotoZip(),
            default    => $backup->createFullZip(),
        };

        if (! $created['success']) {
            return ['success' => false, 'message' => $created['message']];
        }

        $mime = str_ends_with($created['filename'], '.zip') ? 'application/zip' : 'application/sql';

        try {
            $uploaded = $drive->uploadFile($created['path'], $created['filename'], $mime);
        } catch (\Throwable $e) {
            log_message('error', 'BackupToDrive::execute upload - ' . $e->getMessage());
            $backup->cleanup($created['path']);

            return ['success' => false, 'message' => 'Backup dibuat tapi gagal diupload ke Google Drive: ' . $e->getMessage()];
        }

        $backup->cleanup($created['path']);

        return [
            'success' => true,
            'message' => $created['filename'] . ' (' . self::formatSize((int) $uploaded['size']) . ') berhasil diupload.',
            'link'    => $uploaded['webViewLink'],
        ];
    }

    private static function formatSize(int $bytes): string
    {
        if ($bytes <= 0) {
            return '0 B';
        }
        $units = ['B', 'KB', 'MB', 'GB'];
        $i     = min((int) floor(log($bytes, 1024)), count($units) - 1);

        return round($bytes / (1024 ** $i), 2) . ' ' . $units[$i];
    }
}
