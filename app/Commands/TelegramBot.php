<?php

namespace App\Commands;

use App\Libraries\TelegramClient;
use App\Libraries\TelegramUpdateHandler;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Bot Telegram (long polling) untuk memicu backup ke Google Drive dari HP.
 * Hanya merespons chat ID yang terdaftar di backup.telegramAllowedChatIds.
 *
 * Cocok untuk development lokal (biarkan terminal terbuka). Untuk hosting,
 * pakai mode webhook: "php spark telegram:webhook set" (lihat
 * App\Controllers\TelegramWebhook), jangan jalankan dua-duanya sekaligus —
 * command ini otomatis menghapus webhook yang aktif sebelum mulai polling.
 *
 *   php spark telegram:bot
 */
class TelegramBot extends BaseCommand
{
    protected $group       = 'Backup';
    protected $name        = 'telegram:bot';
    protected $description = 'Menjalankan bot Telegram (long polling) untuk memicu backup ke Google Drive. Untuk development lokal saja — lihat telegram:webhook untuk hosting.';

    public function run(array $params)
    {
        $config  = config('Backup');
        $allowed = $config->allowedChatIds();

        if ($allowed === []) {
            CLI::error('backup.telegramAllowedChatIds belum diisi di .env. Jalankan "php spark telegram:whoami" dulu untuk dapat chat ID Anda.');

            return;
        }

        $telegram = new TelegramClient();
        $handler  = new TelegramUpdateHandler($telegram);

        try {
            $telegram->deleteWebhook();
        } catch (\Throwable $e) {
            CLI::write('Peringatan: gagal menghapus webhook lama (' . $e->getMessage() . '), lanjut polling.', 'yellow');
        }

        $offsetFile = WRITEPATH . 'backups' . DIRECTORY_SEPARATOR . '.telegram_offset';
        if (! is_dir(dirname($offsetFile))) {
            @mkdir(dirname($offsetFile), 0755, true);
        }
        $offset = is_file($offsetFile) ? (int) file_get_contents($offsetFile) : 0;

        CLI::write('Bot Telegram berjalan (mode polling). Chat ID diizinkan: ' . implode(', ', $allowed), 'green');
        CLI::write('Tekan Ctrl+C untuk berhenti.', 'yellow');

        while (true) {
            try {
                $updates = $telegram->getUpdates($offset, 25);
            } catch (\Throwable $e) {
                log_message('error', 'TelegramBot polling error: ' . $e->getMessage());
                sleep(5);

                continue;
            }

            foreach ($updates as $update) {
                $offset = $update['update_id'] + 1;
                file_put_contents($offsetFile, (string) $offset);

                $handler->handle($update);
            }
        }
    }
}
