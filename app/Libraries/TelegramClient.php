<?php

namespace App\Libraries;

use RuntimeException;

/**
 * Klien minimal untuk Telegram Bot API (long polling), tanpa dependency
 * tambahan — cukup cURL bawaan PHP.
 */
class TelegramClient
{
    private string $token;

    public function __construct(?string $token = null)
    {
        $this->token = $token ?? config('Backup')->telegramBotToken;

        if ($this->token === '') {
            throw new RuntimeException('backup.telegramBotToken belum diisi di .env.');
        }
    }

    public function getUpdates(int $offset = 0, int $timeout = 25): array
    {
        return $this->call('getUpdates', [
            'offset'  => $offset,
            'timeout' => $timeout,
        ], $timeout + 10);
    }

    public function sendMessage(int|string $chatId, string $text): array
    {
        return $this->call('sendMessage', [
            'chat_id' => $chatId,
            'text'    => $text,
        ]);
    }

    public function setWebhook(string $url, string $secretToken): bool
    {
        return (bool) $this->call('setWebhook', [
            'url'          => $url,
            'secret_token' => $secretToken,
        ]);
    }

    public function deleteWebhook(): bool
    {
        return (bool) $this->call('deleteWebhook', []);
    }

    public function getWebhookInfo(): array
    {
        return (array) $this->call('getWebhookInfo', []);
    }

    private function call(string $method, array $params, int $timeout = 15): array|bool
    {
        $url = "https://api.telegram.org/bot{$this->token}/{$method}";

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $params,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);

        $response = curl_exec($ch);
        $error    = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            throw new RuntimeException('Telegram API request gagal: ' . $error);
        }

        $data = json_decode($response, true);
        if (! is_array($data) || empty($data['ok'])) {
            throw new RuntimeException('Telegram API error: ' . ($data['description'] ?? 'unknown'));
        }

        return $data['result'] ?? [];
    }
}
