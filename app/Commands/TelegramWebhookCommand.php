<?php

namespace App\Commands;

use App\Libraries\TelegramClient;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Kelola webhook Telegram untuk mode hosting (tanpa proses polling 24 jam).
 *
 *   php spark telegram:webhook secret   - generate token rahasia acak (isi ke .env)
 *   php spark telegram:webhook set      - daftarkan webhook ke Telegram (pakai app.baseURL)
 *   php spark telegram:webhook delete   - hapus webhook (dipakai lagi kalau mau balik ke mode polling)
 *   php spark telegram:webhook info     - tampilkan status webhook saat ini dari Telegram
 */
class TelegramWebhookCommand extends BaseCommand
{
    protected $group       = 'Backup';
    protected $name        = 'telegram:webhook';
    protected $description = 'Kelola webhook Telegram (mode hosting): secret | set | delete | info.';
    protected $usage       = 'telegram:webhook <secret|set|delete|info>';

    public function run(array $params)
    {
        $action = strtolower((string) ($params[0] ?? ''));

        match ($action) {
            'secret' => $this->generateSecret(),
            'set'    => $this->setWebhook(),
            'delete' => $this->deleteWebhook(),
            'info'   => $this->info(),
            default  => CLI::error('Gunakan: php spark telegram:webhook <secret|set|delete|info>'),
        };
    }

    private function generateSecret(): void
    {
        $secret = bin2hex(random_bytes(24));
        CLI::write('Token rahasia baru (isi ke .env sebagai backup.telegramWebhookSecret):', 'yellow');
        CLI::write($secret, 'green');
    }

    private function setWebhook(): void
    {
        $config = config('Backup');

        if ($config->telegramWebhookSecret === '') {
            CLI::error('backup.telegramWebhookSecret belum diisi di .env. Jalankan "php spark telegram:webhook secret" dulu.');

            return;
        }

        $url = rtrim(base_url(), '/') . '/telegram-webhook/' . $config->telegramWebhookSecret;

        if (! str_starts_with($url, 'https://')) {
            CLI::write("Peringatan: URL webhook tidak diawali https:// ({$url}). Telegram mewajibkan HTTPS — pastikan app.baseURL di .env sudah domain hosting dengan SSL sebelum deploy.", 'yellow');
        }

        $telegram = new TelegramClient();

        try {
            $telegram->setWebhook($url, $config->telegramWebhookSecret);
        } catch (\Throwable $e) {
            CLI::error('Gagal set webhook: ' . $e->getMessage());

            return;
        }

        CLI::write('Webhook berhasil didaftarkan ke: ' . $url, 'green');
        CLI::write('Bot sekarang mode webhook. Jangan jalankan "php spark telegram:bot" (mode polling) bersamaan.', 'yellow');
    }

    private function deleteWebhook(): void
    {
        $telegram = new TelegramClient();

        try {
            $telegram->deleteWebhook();
        } catch (\Throwable $e) {
            CLI::error('Gagal hapus webhook: ' . $e->getMessage());

            return;
        }

        CLI::write('Webhook dihapus. Sekarang boleh pakai mode polling ("php spark telegram:bot") lagi.', 'green');
    }

    private function info(): void
    {
        $telegram = new TelegramClient();

        try {
            $info = $telegram->getWebhookInfo();
        } catch (\Throwable $e) {
            CLI::error('Gagal ambil info webhook: ' . $e->getMessage());

            return;
        }

        foreach ($info as $key => $value) {
            CLI::write($key . ': ' . (is_scalar($value) ? (string) $value : json_encode($value)));
        }
    }
}
