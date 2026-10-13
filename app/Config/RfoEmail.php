<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Akun pengirim email RFO (dropdown "Dari" pada form kirim email).
 *
 * Password TIDAK disimpan di sini, hanya di file .env (tidak ikut git):
 *   RFO_SMTP_<kunci>_PASS = '...'
 * dengan <kunci> = alamat email, "@" dan "." diganti "_" (mis. noc_rnet_id).
 * Akun noc@jwi.id memakai pengaturan email.* bawaan di .env.
 * Semua domain berada di server mail yang sama, jadi host SMTP = email.SMTPHost.
 */
class RfoEmail extends BaseConfig
{
    /** Alamat email => nama pengirim yang tampil di inbox penerima. */
    public array $akun = [
        'noc@jwi.id'        => 'NOC JWI',
        'noc@speednet.id'   => 'NOC Speednet',
        'noc@norlec.id'     => 'NOC Norlec',
        'noc@sprintnet.id'  => 'NOC Sprintnet',
        'noc@genezis.id'    => 'NOC GMT',
        'noc@flazznet.id'   => 'NOC Flazznet',
        'noc@rnet.id'       => 'NOC RNET',
        'noc@anagram.co.id' => 'NOC Anagram',
    ];

    /** Password SMTP akun dari .env; null bila belum diatur. */
    public function password(string $email): ?string
    {
        if ($email === config(Email::class)->SMTPUser) {
            return config(Email::class)->SMTPPass;
        }

        $pass = env('RFO_SMTP_' . str_replace(['@', '.'], '_', $email) . '_PASS');

        return is_string($pass) && $pass !== '' ? $pass : null;
    }
}
