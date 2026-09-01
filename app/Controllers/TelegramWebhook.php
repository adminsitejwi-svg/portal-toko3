<?php

namespace App\Controllers;

use App\Libraries\TelegramClient;
use App\Libraries\TelegramUpdateHandler;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Endpoint webhook Telegram — dipanggil langsung oleh server Telegram lewat
 * HTTPS setiap ada pesan baru, jadi tidak butuh proses yang jalan 24 jam
 * (cocok untuk shared/managed hosting). Daftarkan lewat:
 *
 *   php spark telegram:webhook set
 *
 * Jangan aktifkan bareng mode polling (telegram:bot) — pilih salah satu.
 */
class TelegramWebhook extends BaseController
{
    public function handle(?string $secret = null): ResponseInterface
    {
        $config = config('Backup');

        if ($config->telegramWebhookSecret === '' || ! hash_equals($config->telegramWebhookSecret, (string) $secret)) {
            return $this->response->setStatusCode(404)->setBody('Not Found');
        }

        $headerToken = $this->request->getHeaderLine('X-Telegram-Bot-Api-Secret-Token');
        if (! hash_equals($config->telegramWebhookSecret, $headerToken)) {
            return $this->response->setStatusCode(404)->setBody('Not Found');
        }

        $update = json_decode($this->request->getBody(), true);

        if (is_array($update)) {
            try {
                (new TelegramUpdateHandler())->handle($update);
            } catch (\Throwable $e) {
                log_message('error', 'TelegramWebhook::handle error: ' . $e->getMessage());
            }
        }

        // Selalu balas 200 supaya Telegram tidak mengirim ulang update yang sama.
        return $this->response->setStatusCode(200)->setBody('OK');
    }

    /**
     * Versi "php spark telegram:webhook set" yang bisa dipanggil lewat browser
     * (GET), dipakai kalau hosting tidak punya akses SSH. Dilindungi secret
     * yang sama dengan endpoint handle(). Setelah dipakai sekali dan berhasil,
     * boleh dihapus/di-revert lagi — tidak wajib dibiarkan aktif terus.
     */
    public function setup(?string $secret = null): ResponseInterface
    {
        $config = config('Backup');

        if ($config->telegramWebhookSecret === '' || ! hash_equals($config->telegramWebhookSecret, (string) $secret)) {
            return $this->response->setStatusCode(404)->setBody('Not Found');
        }

        $url = rtrim(base_url(), '/') . '/telegram-webhook/' . $config->telegramWebhookSecret;

        if (! str_starts_with($url, 'https://')) {
            return $this->response->setStatusCode(400)->setBody(
                "Gagal: app.baseURL belum https:// ({$url}). Set app.baseURL di .env hosting ke domain SSL kamu dulu."
            );
        }

        try {
            (new TelegramClient())->setWebhook($url, $config->telegramWebhookSecret);
        } catch (\Throwable $e) {
            return $this->response->setStatusCode(500)->setBody('Gagal set webhook: ' . $e->getMessage());
        }

        return $this->response->setStatusCode(200)->setBody('OK, webhook terdaftar ke: ' . $url);
    }
}
