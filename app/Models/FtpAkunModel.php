<?php

namespace App\Models;

/**
 * Akun FTP tersimpan (menu Data Teknis > FTP Client).
 * Password disimpan terenkripsi (Encrypter CI4 + encryption.key di .env), tidak pernah dikirim ke browser.
 */
class FtpAkunModel extends BaseModel
{
    protected $table      = 'd_ftp_akun';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nama',
        'protokol',
        'host',
        'port',
        'username',
        'password',
        'folder_awal',
        'pasif',
        'dibuat_oleh',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public static function enkripsi(string $plain): string
    {
        return base64_encode(service('encrypter')->encrypt($plain));
    }

    public static function dekripsi(?string $simpan): string
    {
        if (! $simpan) {
            return '';
        }
        try {
            return service('encrypter')->decrypt(base64_decode($simpan, true) ?: '');
        } catch (\Throwable $e) {
            return '';
        }
    }

    /** Daftar akun untuk browser (tanpa password). */
    public function daftarAman(): array
    {
        return $this->select('id, nama, protokol, host, port, username, folder_awal, pasif, dibuat_oleh, updated_at')
            ->orderBy('nama', 'ASC')
            ->findAll();
    }
}
