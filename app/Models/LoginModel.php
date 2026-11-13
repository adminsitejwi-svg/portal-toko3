<?php

namespace App\Models;

use CodeIgniter\Model;

class LoginModel extends Model
{
    protected $table = 'login';

    protected $primaryKey = 'id';

    protected $allowedFields = [
        'username',
        'password',
        'role',
        'role_id',       // m_role.id (menu Informasi > Hak Akses)
        'akses_khusus',  // 1 = pakai halaman per akun (m_user_akses), 0 = ikut role
    ];

    protected $returnType = 'array';
}
