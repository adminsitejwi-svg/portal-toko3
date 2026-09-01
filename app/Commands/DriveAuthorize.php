<?php

namespace App\Commands;

use App\Libraries\GoogleDriveService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Otorisasi satu-kali ke Google Drive (dijalankan tanpa argumen dulu untuk
 * mendapat link, lalu dijalankan lagi dengan kode hasil login sebagai argumen).
 *
 *   php spark drive:authorize
 *   php spark drive:authorize "4/0Axxxxxx...kode-dari-google"
 */
class DriveAuthorize extends BaseCommand
{
    protected $group       = 'Backup';
    protected $name        = 'drive:authorize';
    protected $description = 'Otorisasi (atau cek status otorisasi) akses Google Drive untuk backup.';
    protected $usage       = 'drive:authorize [code]';

    public function run(array $params)
    {
        $service = new GoogleDriveService();
        $code    = $params[0] ?? null;

        if ($service->isAuthorized() && $code === null) {
            CLI::write('Google Drive sudah terotorisasi.', 'green');

            return;
        }

        // Mode manual: kode sudah di-paste langsung sebagai argumen.
        if ($code !== null) {
            $this->finish($service, $code);

            return;
        }

        // Mode otomatis: buka listener lokal dulu, baru tampilkan link.
        CLI::write('Buka URL berikut di browser, login dengan akun Google Drive tujuan, lalu klik Allow/Izinkan:', 'yellow');
        CLI::write('');
        CLI::write($service->getAuthUrl());
        CLI::write('');
        CLI::write('Menunggu Anda menyelesaikan otorisasi di browser (maks 5 menit)...', 'yellow');

        try {
            $code = $service->waitForAuthCode(300);
        } catch (\Throwable $e) {
            CLI::error('Gagal menangkap kode otorisasi: ' . $e->getMessage());
            CLI::write('Alternatif manual: salin nilai "code=" dari URL setelah redirect, lalu jalankan:');
            CLI::write('  php spark drive:authorize "KODE_YANG_DISALIN"', 'blue');

            return;
        }

        $this->finish($service, $code);
    }

    private function finish(GoogleDriveService $service, string $code): void
    {
        try {
            $result = $service->authorizeWithCode($code);
            CLI::write('Otorisasi berhasil! Refresh token tersimpan.', 'green');
            CLI::write('Folder Drive tujuan siap, ID: ' . $result['folder_id']);
        } catch (\Throwable $e) {
            CLI::error('Otorisasi gagal: ' . $e->getMessage());
        }
    }
}
