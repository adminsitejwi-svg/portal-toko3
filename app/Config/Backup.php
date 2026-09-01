<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Konfigurasi Backup -> Google Drive + Bot Telegram.
 * Semua nilai diisi lewat .env dengan prefix "backup." (lihat .env).
 */
class Backup extends BaseConfig
{
    public string $timezone  = 'Asia/Jakarta';
    public string $mysqldump = 'mysqldump';

    // Google OAuth (Desktop app client)
    public string $googleClientId       = '';
    public string $googleClientSecret   = '';
    public string $googleDriveAccount   = '';
    public string $googleDriveFolderId  = '';

    // Telegram Bot
    public string $telegramBotToken       = '';
    public string $telegramAllowedChatIds = '';

    /**
     * Token rahasia untuk mode webhook (hosting), dipakai sebagai segmen URL
     * (mis. /telegram-webhook/<token>) dan sebagai secret_token Telegram
     * (dicek lewat header X-Telegram-Bot-Api-Secret-Token). Isi bebas, cukup
     * panjang & acak — generate lewat "php spark telegram:webhook secret".
     */
    public string $telegramWebhookSecret = '';

    /** @return list<string> */
    public function allowedChatIds(): array
    {
        return array_values(array_filter(array_map('trim', explode(',', $this->telegramAllowedChatIds))));
    }
}
