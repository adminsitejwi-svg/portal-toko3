<?php

namespace App\Commands;

use App\Libraries\TelegramClient;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Bantu menemukan chat ID Telegram: kirim pesan apapun ke bot Anda dulu
 * (buka bot, tekan Start / ketik apa saja), lalu jalankan command ini.
 *
 *   php spark telegram:whoami
 */
class TelegramWhoAmI extends BaseCommand
{
    protected $group       = 'Backup';
    protected $name        = 'telegram:whoami';
    protected $description = 'Menampilkan chat ID dari pesan terbaru yang masuk ke bot Telegram (untuk diisi ke backup.telegramAllowedChatIds).';

    public function run(array $params)
    {
        $telegram = new TelegramClient();
        $updates  = $telegram->getUpdates(0, 1);

        if (empty($updates)) {
            CLI::write('Belum ada pesan masuk. Buka bot Anda di Telegram, kirim pesan apa saja (mis. /start), lalu ulangi command ini.', 'yellow');

            return;
        }

        $seen = [];
        foreach ($updates as $update) {
            $chat = $update['message']['chat'] ?? null;
            if ($chat === null) {
                continue;
            }
            $id = $chat['id'];
            if (isset($seen[$id])) {
                continue;
            }
            $seen[$id] = true;

            $name = trim(($chat['first_name'] ?? '') . ' ' . ($chat['last_name'] ?? ''));
            $username = $chat['username'] ?? '-';
            CLI::write("chat_id: {$id}  |  nama: {$name}  |  username: @{$username}", 'green');
        }
    }
}
