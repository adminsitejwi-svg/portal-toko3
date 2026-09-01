<?php

namespace App\Libraries;

use App\Commands\BackupToDrive;
use CodeIgniter\CLI\CLI;

/**
 * Logika pemrosesan 1 update Telegram (perintah backup dsb), dipakai bersama
 * oleh mode long-polling (php spark telegram:bot) dan mode webhook
 * (App\Controllers\TelegramWebhook), supaya keduanya konsisten.
 */
class TelegramUpdateHandler
{
    private const WELCOME = "Halo! Selamat datang di Bot Backup Sistem Operasional JWI Group.\n\n"
        . "Perintah yang tersedia:\n"
        . "1. /backup_full - Full Backup (database + file)\n"
        . "2. /backup_database - Backup Database saja\n"
        . "3. /backup_files - Export Data (Excel, Catatan, CSV)\n"
        . "4. /backup_photo - Backup Foto saja\n"
        . "5. /status - Cek status sistem backup";

    private const REJECTED = "Perintah tidak dikenali/tidak diizinkan.\n\n"
        . "Perintah yang tersedia:\n"
        . "1. /backup_full - Full Backup (database + file)\n"
        . "2. /backup_database - Backup Database saja\n"
        . "3. /backup_files - Export Data (Excel, Catatan, CSV)\n"
        . "4. /backup_photo - Backup Foto saja\n"
        . "5. /status - Cek status sistem backup";

    /**
     * Nama file lock lintas-proses supaya "sedang ada backup berjalan?" tetap
     * akurat di mode webhook (tiap request = proses PHP baru, tidak bisa pakai
     * properti in-memory seperti di mode polling).
     */
    private string $lockFile;

    private array $allowed;
    private TelegramClient $telegram;

    /** @var resource|null */
    private $lockHandle = null;

    public function __construct(?TelegramClient $telegram = null)
    {
        $config         = config('Backup');
        $this->allowed  = $config->allowedChatIds();
        $this->telegram = $telegram ?? new TelegramClient();
        $this->lockFile = WRITEPATH . 'backups' . DIRECTORY_SEPARATOR . '.telegram_busy.lock';
    }

    public function handle(array $update): void
    {
        $message = $update['message'] ?? null;
        if ($message === null) {
            return;
        }

        $chatId = (string) ($message['chat']['id'] ?? '');
        $text   = trim((string) ($message['text'] ?? ''));

        if (! in_array($chatId, $this->allowed, true)) {
            CLI::write("Pesan ditolak dari chat_id tidak dikenal: {$chatId}", 'red');

            try {
                $this->telegram->sendMessage($chatId, 'Anda tidak diizinkan menggunakan bot ini.');
            } catch (\Throwable $e) {
                // abaikan
            }

            return;
        }

        $command = strtolower(explode(' ', $text)[0] ?? '');

        try {
            switch ($command) {
                case '/start':
                    $this->telegram->sendMessage($chatId, self::WELCOME);
                    break;

                case '/status':
                    $this->telegram->sendMessage($chatId, $this->buildStatusText());
                    break;

                case '/backup':
                case '/backup_full':
                    $this->runBackup($chatId, 'full', 'Full Backup (database + file)');
                    break;

                case '/backup_database':
                    $this->runBackup($chatId, 'database', 'Backup Database');
                    break;

                case '/backup_files':
                    $this->runBackup($chatId, 'files', 'Export Data (Excel, Catatan, CSV)');
                    break;

                case '/backup_photo':
                    $this->runBackup($chatId, 'photo', 'Backup Foto');
                    break;

                default:
                    $this->telegram->sendMessage($chatId, self::REJECTED);
            }
        } catch (\Throwable $e) {
            log_message('error', 'TelegramUpdateHandler::handle error: ' . $e->getMessage());
        }
    }

    private function runBackup(string $chatId, string $type, string $label): void
    {
        if (! $this->acquireLock()) {
            $this->telegram->sendMessage(
                $chatId,
                'Masih ada proses backup lain yang sedang berjalan (mungkin jaringan sedang lambat). ' .
                'Mohon tunggu sampai proses sebelumnya selesai sebelum mengirim perintah baru.'
            );

            return;
        }

        try {
            $this->telegram->sendMessage($chatId, "Memproses {$label}... mohon tunggu.");

            $result = BackupToDrive::execute($type);

            if ($result['success']) {
                $this->telegram->sendMessage($chatId, "Backup berhasil.\n" . $result['message'] . "\n" . $result['link']);
            } else {
                $this->telegram->sendMessage($chatId, 'Backup gagal: ' . $result['message']);
            }
        } finally {
            $this->releaseLock();
        }
    }

    private function acquireLock(): bool
    {
        if (! is_dir(dirname($this->lockFile))) {
            @mkdir(dirname($this->lockFile), 0755, true);
        }

        $handle = @fopen($this->lockFile, 'c');
        if ($handle === false) {
            return true;
        }

        if (! flock($handle, LOCK_EX | LOCK_NB)) {
            fclose($handle);

            return false;
        }

        // Sengaja tidak fclose supaya lock tetap dipegang selama request/proses ini berjalan.
        $this->lockHandle = $handle;

        return true;
    }

    private function releaseLock(): void
    {
        if (isset($this->lockHandle) && is_resource($this->lockHandle)) {
            flock($this->lockHandle, LOCK_UN);
            fclose($this->lockHandle);
            unset($this->lockHandle);
        }
    }

    private function buildStatusText(): string
    {
        $checks = (new BackupService())->runSelfTest();
        $drive  = new GoogleDriveService();

        $lines   = [];
        $lines[] = ($drive->isAuthorized() ? '✅' : '❌') . ' Google Drive terotorisasi';
        foreach ($checks as $c) {
            $lines[] = ($c['ok'] ? '✅' : '❌') . ' ' . $c['label'] . ' — ' . $c['message'];
        }
        $lines[] = '';
        $lines[] = is_file($this->lockFile) && $this->isLocked()
            ? 'Status proses: sedang ada backup berjalan.'
            : 'Status proses : semua berjalan dengan baik.';

        return implode("\n", $lines);
    }

    private function isLocked(): bool
    {
        $handle = @fopen($this->lockFile, 'c');
        if ($handle === false) {
            return false;
        }

        $locked = ! flock($handle, LOCK_EX | LOCK_NB);
        if (! $locked) {
            flock($handle, LOCK_UN);
        }
        fclose($handle);

        return $locked;
    }
}
