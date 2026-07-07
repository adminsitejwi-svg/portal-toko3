<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\LoginModel;

class Register extends BaseController
{
    public function index()
    {
        // tahu dari mana register dibuka (settings atau publik)
        $data['from'] = $this->request->getGet('from') ?? '';
        return view('auth/register', $data);
    }

    public function save()
    {
        $rules = [
            'username' => [
                'rules'  => 'required|min_length[3]|is_unique[login.username]',
                'errors' => [
                    'required'  => 'Username wajib diisi',
                    'is_unique' => 'Username sudah digunakan',
                ],
            ],
            'password' => [
                'rules'  => 'required|min_length[6]',
                'errors' => ['required' => 'Password wajib diisi'],
            ],
            'confirm_password' => [
                'rules'  => 'required|matches[password]',
                'errors' => ['matches' => 'Konfirmasi password tidak sama'],
            ],
        ];

        $from = $this->request->getPost('from'); // 'settings' atau kosong

        if (!$this->validate($rules)) {
            // kembali ke form register sambil mempertahankan asal
            return redirect()->to('/register' . ($from === 'settings' ? '?from=settings' : ''))
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $model = new LoginModel();
        $model->save([
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
        ]);

        // kalau dibuka dari settings → kembali ke settings dengan pesan
        if ($from === 'settings') {
            return redirect()->to('/settings')
                ->with('success', 'Akun baru berhasil ditambahkan.');
        }

        // alur publik biasa → ke login
        return redirect()->to('/login')
            ->with('success', 'Registrasi berhasil. Silakan login menggunakan akun Anda.');
    }
}
