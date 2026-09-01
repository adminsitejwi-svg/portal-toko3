<?php

namespace App\Libraries;

use Google\Client as GoogleClient;
use Google\Service\Drive as GoogleDrive;
use Google\Service\Drive\DriveFile;
use RuntimeException;

/**
 * Wrapper tipis di atas Google API PHP Client untuk kebutuhan backup:
 * otorisasi sekali (offline access -> refresh token), lalu upload file
 * ke satu folder khusus di Google Drive akun yang diotorisasi.
 *
 * Token & folder ID disimpan di writable/google/state.json (di luar
 * public/, diproteksi .htaccess, dan di-gitignore).
 */
class GoogleDriveService
{
    private const FOLDER_NAME = 'Sistem Operasional JWI Group';

    // Port lokal khusus untuk menangkap redirect OAuth. Dipilih port tidak umum
    // supaya tidak bentrok dengan Apache/XAMPP yang biasanya sudah memakai 80/8080.
    public const CALLBACK_PORT = 8765;

    protected \Config\Backup $config;
    protected string $statePath;

    public function __construct()
    {
        $this->config    = config('Backup');
        $this->statePath = WRITEPATH . 'google' . DIRECTORY_SEPARATOR . 'state.json';

        if (! is_dir(dirname($this->statePath))) {
            @mkdir(dirname($this->statePath), 0755, true);
        }
    }

    public function isAuthorized(): bool
    {
        $state = $this->readState();

        return ! empty($state['refresh_token']);
    }

    public function buildClient(): GoogleClient
    {
        if ($this->config->googleClientId === '' || $this->config->googleClientSecret === '') {
            throw new RuntimeException('backup.googleClientId / backup.googleClientSecret belum diisi di .env.');
        }

        $client = new GoogleClient();
        $client->setClientId($this->config->googleClientId);
        $client->setClientSecret($this->config->googleClientSecret);
        $client->setRedirectUri('http://localhost:' . self::CALLBACK_PORT);
        $client->setScopes([GoogleDrive::DRIVE_FILE]);
        $client->setAccessType('offline');
        $client->setPrompt('consent');

        return $client;
    }

    public function getAuthUrl(): string
    {
        return $this->buildClient()->createAuthUrl();
    }

    /**
     * Buka listener HTTP lokal sementara di 127.0.0.1:CALLBACK_PORT, tunggu satu
     * request masuk (hasil redirect dari Google setelah user klik Allow), ambil
     * parameter "code" dari query string-nya, lalu tutup listener.
     *
     * Menghindari perlu copy-paste manual dari address bar browser.
     */
    public function waitForAuthCode(int $timeoutSeconds = 300): string
    {
        $server = @stream_socket_server('tcp://127.0.0.1:' . self::CALLBACK_PORT, $errno, $errstr);
        if ($server === false) {
            throw new RuntimeException("Tidak dapat membuka listener di port " . self::CALLBACK_PORT . ": {$errstr} ({$errno}). Port mungkin sedang dipakai proses lain.");
        }

        stream_set_timeout($server, $timeoutSeconds);
        $deadline = time() + $timeoutSeconds;

        while (time() < $deadline) {
            $conn = @stream_socket_accept($server, 5);
            if ($conn === false) {
                continue;
            }

            stream_set_timeout($conn, 5);
            $requestLine = fgets($conn, 8192) ?: '';

            // GET /?code=XXXX&scope=... HTTP/1.1
            $code = null;
            if (preg_match('#^GET\s+(\S+)\s+HTTP#', $requestLine, $m)) {
                $path  = $m[1];
                $query = parse_url($path, PHP_URL_QUERY) ?: '';
                parse_str($query, $params);
                $code = $params['code'] ?? null;
            }

            $body = $code !== null
                ? '<html><body><h3>Otorisasi berhasil.</h3><p>Anda bisa menutup tab ini dan kembali ke chat.</p></body></html>'
                : '<html><body><h3>Tidak ada kode otorisasi ditemukan.</h3><p>Silakan tutup tab ini.</p></body></html>';

            fwrite($conn, "HTTP/1.1 200 OK\r\nContent-Type: text/html; charset=utf-8\r\nContent-Length: " . strlen($body) . "\r\nConnection: close\r\n\r\n" . $body);
            fclose($conn);
            fclose($server);

            if ($code === null) {
                throw new RuntimeException('Request diterima tapi tidak ada parameter "code" di URL-nya.');
            }

            return $code;
        }

        fclose($server);

        throw new RuntimeException('Timeout menunggu otorisasi (' . $timeoutSeconds . ' detik). Coba jalankan ulang.');
    }

    /**
     * Tukar authorization code (hasil user login manual di browser) menjadi token,
     * simpan refresh token, lalu siapkan folder tujuan di Drive.
     */
    public function authorizeWithCode(string $code): array
    {
        $client = $this->buildClient();
        $token  = $client->fetchAccessTokenWithAuthCode(trim($code));

        if (isset($token['error'])) {
            throw new RuntimeException('Gagal menukar authorization code: ' . ($token['error_description'] ?? $token['error']));
        }
        if (empty($token['refresh_token'])) {
            throw new RuntimeException('Google tidak mengirim refresh_token. Coba ulangi drive:authorize dari awal (link otorisasi baru), pastikan akses sebelumnya sudah di-revoke di myaccount.google.com/permissions bila pernah otorisasi sebelumnya.');
        }

        $this->writeState(['refresh_token' => $token['refresh_token']]);
        $client->setAccessToken($token);

        $folderId = $this->ensureFolder($client);
        $this->writeState(['folder_id' => $folderId]);

        return ['folder_id' => $folderId];
    }

    public function getAuthorizedClient(): GoogleClient
    {
        $state = $this->readState();
        if (empty($state['refresh_token'])) {
            throw new RuntimeException('Google Drive belum diotorisasi. Jalankan "php spark drive:authorize" terlebih dahulu.');
        }

        $client = $this->buildClient();
        $client->refreshToken($state['refresh_token']);

        return $client;
    }

    /**
     * Ganti nama folder Drive yang sudah pernah dibuat agar sesuai FOLDER_NAME
     * saat ini (dipakai saat nama folder standar berubah, supaya tidak membuat
     * folder duplikat di Drive).
     */
    public function renameRootFolderIfNeeded(): ?string
    {
        $folderId = $this->readState()['folder_id'] ?? null;
        if ($folderId === null) {
            return null;
        }

        $drive   = new GoogleDrive($this->getAuthorizedClient());
        $current = $drive->files->get($folderId, ['fields' => 'id, name']);

        if ($current->getName() === self::FOLDER_NAME) {
            return $folderId;
        }

        $drive->files->update($folderId, new DriveFile(['name' => self::FOLDER_NAME]));

        return $folderId;
    }

    public function uploadFile(string $localPath, string $remoteName, string $mimeType): array
    {
        $client = $this->getAuthorizedClient();
        $drive  = new GoogleDrive($client);

        $folderId = $this->readState()['folder_id'] ?? null;
        if ($folderId === null) {
            $folderId = $this->ensureFolder($client);
            $this->writeState(['folder_id' => $folderId]);
        }

        $fileMetadata = new DriveFile([
            'name'    => $remoteName,
            'parents' => [$folderId],
        ]);

        $content = file_get_contents($localPath);
        if ($content === false) {
            throw new RuntimeException('Tidak dapat membaca file lokal untuk diupload: ' . $localPath);
        }

        $uploaded = $drive->files->create($fileMetadata, [
            'data'       => $content,
            'mimeType'   => $mimeType,
            'uploadType' => 'multipart',
            'fields'     => 'id, name, size, webViewLink',
        ]);

        return [
            'id'          => $uploaded->getId(),
            'name'        => $uploaded->getName(),
            'size'        => $uploaded->getSize(),
            'webViewLink' => $uploaded->getWebViewLink(),
        ];
    }

    private function ensureFolder(GoogleClient $client): string
    {
        $drive = new GoogleDrive($client);

        $escapedName = str_replace("'", "\\'", self::FOLDER_NAME);
        $result = $drive->files->listFiles([
            'q'      => "name = '{$escapedName}' and mimeType = 'application/vnd.google-apps.folder' and trashed = false",
            'fields' => 'files(id, name)',
            'spaces' => 'drive',
        ]);

        $files = $result->getFiles();
        if (! empty($files)) {
            return $files[0]->getId();
        }

        $folder = new DriveFile([
            'name'     => self::FOLDER_NAME,
            'mimeType' => 'application/vnd.google-apps.folder',
        ]);

        $created = $drive->files->create($folder, ['fields' => 'id']);

        return $created->getId();
    }

    private function readState(): array
    {
        if (! is_file($this->statePath)) {
            return [];
        }

        $content = file_get_contents($this->statePath);
        $data    = json_decode((string) $content, true);

        return is_array($data) ? $data : [];
    }

    private function writeState(array $partial): void
    {
        $state = array_merge($this->readState(), $partial);
        file_put_contents($this->statePath, json_encode($state, JSON_PRETTY_PRINT));
        @chmod($this->statePath, 0600);
    }
}
