<?php

namespace App\Libraries;

use RuntimeException;
use Throwable;
use ZipArchive;

/**
 * Membuat file backup (database / file / full) secara lokal-sementara di
 * writable/backups/tmp/. File ini HANYA sementara — dibuat, diupload ke
 * Google Drive oleh GoogleDriveService, lalu dihapus. Tidak ada penyimpanan
 * permanen di server dan tidak ada UI untuk fitur ini (backup dipicu lewat
 * chat / spark command / bot Telegram).
 */
class BackupService
{
    protected \Config\Backup $config;
    protected string $tmpDir;

    private ?string $mysqldumpBin = null;
    private string $lastPassword  = '';

    public function __construct()
    {
        $this->config = config('Backup');
        $this->tmpDir = rtrim(WRITEPATH . 'backups' . DIRECTORY_SEPARATOR . 'tmp', '/\\');

        if (! is_dir($this->tmpDir)) {
            @mkdir($this->tmpDir, 0755, true);
        }
    }

    public function createDatabaseDump(): array
    {
        date_default_timezone_set($this->config->timezone);

        $filename = 'database_' . date('Y-m-d_H-i-s') . '.sql';
        $absPath  = $this->tmpDir . DIRECTORY_SEPARATOR . $filename;

        try {
            $result = $this->performDatabaseDump($absPath);
        } catch (Throwable $e) {
            log_message('error', 'BackupService::createDatabaseDump - ' . $e->getMessage());
            $result = ['success' => false, 'message' => 'Terjadi kesalahan internal saat membuat backup database.'];
        }

        return array_merge($result, ['filename' => $filename, 'path' => $result['success'] ? $absPath : null]);
    }

    /**
     * Backup foto/lampiran mentah saja (isi writable/uploads/ apa adanya).
     */
    public function createPhotoZip(): array
    {
        date_default_timezone_set($this->config->timezone);

        $filename = 'foto_' . date('Y-m-d_H-i-s') . '.zip';
        $absPath  = $this->tmpDir . DIRECTORY_SEPARATOR . $filename;

        try {
            $result = $this->performFilesZip($absPath);
        } catch (Throwable $e) {
            log_message('error', 'BackupService::createPhotoZip - ' . $e->getMessage());
            $result = ['success' => false, 'message' => 'Terjadi kesalahan internal saat membuat backup foto.'];
        }

        return array_merge($result, ['filename' => $filename, 'path' => $result['success'] ? $absPath : null]);
    }

    /**
     * Export data (CSV + Excel + catatan) seluruh halaman, tanpa foto, tanpa
     * database dump — dikemas jadi satu ZIP.
     */
    public function createDataExportZip(): array
    {
        date_default_timezone_set($this->config->timezone);

        $ts        = date('Y-m-d_H-i-s');
        $filename  = 'data-export_' . $ts . '.zip';
        $absPath   = $this->tmpDir . DIRECTORY_SEPARATOR . $filename;
        $exportDir = $this->tmpDir . DIRECTORY_SEPARATOR . '.tmp_export_' . $ts;

        try {
            $export = (new DataExportService())->exportAllPages($exportDir, false);

            $zip = new ZipArchive();
            if ($zip->open($absPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new RuntimeException('Tidak dapat membuat file ZIP export data.');
            }

            $this->addDirectoryToZip($zip, $exportDir, '');
            $zip->close();

            $this->removeDirRecursive($exportDir);

            if (! is_file($absPath) || filesize($absPath) === 0) {
                throw new RuntimeException('File export data gagal dibuat atau kosong.');
            }

            $message = 'Export data berhasil dibuat (' . count($export['ok']) . ' halaman).';
            if ($export['failed'] !== []) {
                $message .= ' Gagal: ' . implode(', ', array_keys($export['failed'])) . '.';
            }

            $result = ['success' => true, 'message' => $message];
        } catch (Throwable $e) {
            log_message('error', 'BackupService::createDataExportZip - ' . $e->getMessage());
            $this->removeDirRecursive($exportDir);
            @unlink($absPath);
            $result = ['success' => false, 'message' => $e->getMessage() ?: 'Terjadi kesalahan saat membuat export data.'];
        }

        return array_merge($result, ['filename' => $filename, 'path' => $result['success'] ? $absPath : null]);
    }

    public function createFullZip(): array
    {
        date_default_timezone_set($this->config->timezone);

        $ts        = date('Y-m-d_H-i-s');
        $filename  = 'backup_' . $ts . '.zip';
        $absPath   = $this->tmpDir . DIRECTORY_SEPARATOR . $filename;
        $tmpSql    = $this->tmpDir . DIRECTORY_SEPARATOR . '.tmp_db_' . $ts . '.sql';
        $exportDir = $this->tmpDir . DIRECTORY_SEPARATOR . '.tmp_export_' . $ts;

        try {
            $dbResult = $this->performDatabaseDump($tmpSql);
            if (! $dbResult['success']) {
                @unlink($tmpSql);

                return array_merge($dbResult, ['filename' => $filename, 'path' => null]);
            }

            $export = (new DataExportService())->exportAllPages($exportDir);

            $zip = new ZipArchive();
            if ($zip->open($absPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
                throw new RuntimeException('Tidak dapat membuat file ZIP full backup.');
            }

            $zip->addFile($tmpSql, 'database/database_' . $ts . '.sql');
            $this->addDirectoryToZip($zip, WRITEPATH . 'uploads', 'files/uploads');
            $this->addDirectoryToZip($zip, $exportDir, 'data-export');
            $zip->close();

            @unlink($tmpSql);
            $this->removeDirRecursive($exportDir);

            if (! is_file($absPath) || filesize($absPath) === 0) {
                throw new RuntimeException('File full backup gagal dibuat atau kosong.');
            }

            $message = 'Full backup berhasil dibuat (' . count($export['ok']) . ' halaman data ter-export).';
            if ($export['failed'] !== []) {
                $message .= ' Gagal: ' . implode(', ', array_keys($export['failed'])) . '.';
            }

            $result = ['success' => true, 'message' => $message];
        } catch (Throwable $e) {
            log_message('error', 'BackupService::createFullZip - ' . $e->getMessage());
            @unlink($tmpSql);
            $this->removeDirRecursive($exportDir);
            @unlink($absPath);
            $result = ['success' => false, 'message' => $e->getMessage() ?: 'Terjadi kesalahan saat membuat full backup.'];
        }

        return array_merge($result, ['filename' => $filename, 'path' => $result['success'] ? $absPath : null]);
    }

    public function cleanup(?string $absPath): void
    {
        if ($absPath !== null && is_file($absPath)) {
            @unlink($absPath);
        }
    }

    public function runSelfTest(): array
    {
        date_default_timezone_set($this->config->timezone);

        $checks = [];

        $writable = is_dir($this->tmpDir) && is_writable($this->tmpDir);
        $checks[] = ['label' => 'Folder sementara writable/backups/tmp', 'ok' => $writable, 'message' => $writable ? 'Dapat ditulis.' : 'Tidak dapat menulis.'];

        $mysqldumpBin = $this->resolveMysqldump();
        $checks[] = ['label' => 'mysqldump', 'ok' => $mysqldumpBin !== null, 'message' => $mysqldumpBin !== null ? 'Ditemukan.' : 'Tidak ditemukan di server.'];

        $zipOk    = class_exists(ZipArchive::class);
        $checks[] = ['label' => 'Ekstensi ZIP', 'ok' => $zipOk, 'message' => $zipOk ? 'Tersedia.' : 'Tidak tersedia.'];

        try {
            \Config\Database::connect()->query('SELECT 1');
            $checks[] = ['label' => 'Koneksi Database', 'ok' => true, 'message' => 'Koneksi berhasil.'];
        } catch (Throwable $e) {
            $checks[] = ['label' => 'Koneksi Database', 'ok' => false, 'message' => 'Tidak dapat terhubung ke database.'];
        }

        return $checks;
    }

    // =====================================================================
    // INTERNAL
    // =====================================================================

    private function performDatabaseDump(string $destAbsPath): array
    {
        $bin = $this->resolveMysqldump();
        if ($bin === null) {
            return ['success' => false, 'message' => 'mysqldump tidak ditemukan di server. Backup database tidak dapat dibuat.'];
        }

        $dbConfig    = config('Database')->default;
        $wantedExtra = ['D']; // database tambahan (data toko) di server yang sama, jika ada

        $skipped  = [];
        $existing = $this->existingDatabases();
        $extraDbs = [];
        foreach ($wantedExtra as $name) {
            if (in_array($name, $existing, true)) {
                $extraDbs[] = $name;
            } else {
                $skipped[] = $name;
            }
        }

        $databases = array_values(array_unique(array_merge([$dbConfig['database']], $extraDbs)));

        $cmd = [
            $bin,
            '--host=' . $dbConfig['hostname'],
            '--port=' . $dbConfig['port'],
            '--user=' . $dbConfig['username'],
            '--single-transaction',
            '--routines',
            '--events',
            '--triggers',
            '--add-drop-table',
            '--default-character-set=utf8mb4',
            '--databases',
        ];
        foreach ($databases as $d) {
            $cmd[] = $d;
        }

        $stderrFile  = $this->tempPath('.err');
        $descriptors = [
            1 => ['pipe', 'w'],
            2 => ['file', $stderrFile, 'w'],
        ];

        $this->lastPassword = (string) $dbConfig['password'];
        $env = $this->buildEnv($this->lastPassword);

        $process = @proc_open($cmd, $descriptors, $pipes, null, $env);
        if (! is_resource($process)) {
            @unlink($stderrFile);

            return ['success' => false, 'message' => 'Gagal menjalankan proses mysqldump.'];
        }

        $fh = @fopen($destAbsPath, 'wb');
        if ($fh === false) {
            fclose($pipes[1]);
            proc_terminate($process);
            proc_close($process);
            @unlink($stderrFile);

            return ['success' => false, 'message' => 'Tidak dapat menulis file backup ke folder tujuan.'];
        }

        stream_set_blocking($pipes[1], true);
        while (! feof($pipes[1])) {
            $chunk = fread($pipes[1], 262144);
            if ($chunk === false) {
                break;
            }
            fwrite($fh, $chunk);
        }
        fclose($pipes[1]);
        fclose($fh);

        $exitCode = proc_close($process);
        $stderr   = is_file($stderrFile) ? (string) file_get_contents($stderrFile) : '';
        @unlink($stderrFile);

        if ($exitCode !== 0) {
            @unlink($destAbsPath);

            return ['success' => false, 'message' => 'mysqldump gagal (kode ' . $exitCode . '): ' . $this->sanitizeErrorMessage($stderr)];
        }

        if (! is_file($destAbsPath) || filesize($destAbsPath) === 0) {
            @unlink($destAbsPath);

            return ['success' => false, 'message' => 'File backup database kosong atau gagal dibuat.'];
        }

        $message = 'Backup database berhasil dibuat (' . implode(', ', $databases) . ').';
        if ($skipped !== []) {
            $message .= ' Database ' . implode(', ', $skipped) . ' tidak ditemukan di server, dilewati.';
        }

        return ['success' => true, 'message' => $message];
    }

    private function performFilesZip(string $destAbsPath): array
    {
        if (! class_exists(ZipArchive::class)) {
            return ['success' => false, 'message' => 'Ekstensi ZIP tidak tersedia di server.'];
        }

        $zip = new ZipArchive();
        if ($zip->open($destAbsPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return ['success' => false, 'message' => 'Tidak dapat membuat file ZIP backup.'];
        }

        $this->addDirectoryToZip($zip, WRITEPATH . 'uploads', 'uploads');
        $zip->close();

        if (! is_file($destAbsPath) || filesize($destAbsPath) === 0) {
            return ['success' => false, 'message' => 'File backup ZIP gagal dibuat.'];
        }

        return ['success' => true, 'message' => 'Backup file berhasil dibuat.'];
    }

    private function removeDirRecursive(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($iterator as $file) {
            $file->isDir() ? @rmdir($file->getPathname()) : @unlink($file->getPathname());
        }

        @rmdir($dir);
    }

    private function addDirectoryToZip(ZipArchive $zip, string $sourceDir, string $zipPrefix): void
    {
        if (! is_dir($sourceDir)) {
            return;
        }

        $sourceDir = rtrim($sourceDir, '/\\');
        $iterator  = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sourceDir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($iterator as $file) {
            if (! $file->isFile() || $file->getFilename() === 'index.html') {
                continue;
            }

            $relative  = ltrim(str_replace('\\', '/', substr($file->getPathname(), strlen($sourceDir))), '/');
            $localName = $zipPrefix === '' ? $relative : $zipPrefix . '/' . $relative;
            $zip->addFile($file->getPathname(), $localName);
        }
    }

    private function existingDatabases(): array
    {
        try {
            $rows = \Config\Database::connect()->query('SHOW DATABASES')->getResultArray();
        } catch (Throwable $e) {
            return [];
        }

        return array_column($rows, 'Database');
    }

    private function buildEnv(string $password): array
    {
        $env = [];
        foreach ([$_SERVER, $_ENV] as $source) {
            foreach ($source as $k => $v) {
                if (is_string($v)) {
                    $env[$k] = $v;
                }
            }
        }
        if ($password !== '') {
            $env['MYSQL_PWD'] = $password;
        }

        return $env;
    }

    private function sanitizeErrorMessage(string $raw): string
    {
        $msg = trim($raw);
        if ($this->lastPassword !== '') {
            $msg = str_replace($this->lastPassword, '***', $msg);
        }
        $msg = (string) preg_replace('/--password=\S+/i', '--password=***', $msg);

        return mb_substr($msg, 0, 500);
    }

    private function tempPath(string $suffix): string
    {
        return $this->tmpDir . DIRECTORY_SEPARATOR . '.tmp_' . uniqid('', true) . $suffix;
    }

    private function resolveMysqldump(): ?string
    {
        if ($this->mysqldumpBin === null) {
            $this->mysqldumpBin = $this->resolveExecutable($this->config->mysqldump, $this->candidateBinaries('mysqldump')) ?? '';
        }

        return $this->mysqldumpBin !== '' ? $this->mysqldumpBin : null;
    }

    private function candidateBinaries(string $name): array
    {
        $exe = $name . (DIRECTORY_SEPARATOR === '\\' ? '.exe' : '');

        $candidates = [
            'C:\\xampp\\mysql\\bin\\' . $exe,
            '/opt/lampp/bin/' . $name,
            '/usr/bin/' . $name,
            '/usr/local/bin/' . $name,
            '/usr/local/mysql/bin/' . $name,
        ];

        foreach (glob('C:/laragon/bin/mysql/mysql-*/bin/' . $exe) ?: [] as $found) {
            $candidates[] = $found;
        }

        return $candidates;
    }

    private function resolveExecutable(string $configured, array $fallbacks): ?string
    {
        $candidates = array_values(array_unique(array_filter(array_merge([$configured], $fallbacks))));

        foreach ($candidates as $bin) {
            if ($this->binaryWorks($bin)) {
                return $bin;
            }
        }

        return null;
    }

    private function binaryWorks(string $bin): bool
    {
        if (preg_match('#[\\\\/]#', $bin)) {
            return is_file($bin);
        }

        $descriptors = [1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
        $process     = @proc_open([$bin, '--version'], $descriptors, $pipes);

        if (! is_resource($process)) {
            return false;
        }

        stream_get_contents($pipes[1]);
        fclose($pipes[1]);
        stream_get_contents($pipes[2]);
        fclose($pipes[2]);

        return proc_close($process) === 0;
    }
}
