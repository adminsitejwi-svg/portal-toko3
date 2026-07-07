<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Models\LoginModel;

class Login extends BaseController
{
    public function index()
    {
        return view('auth/Login');
    }

    public function auth()
    {
        $username = trim($this->request->getPost('username'));
        $password = $this->request->getPost('password');

        $model = new LoginModel();

        // Cari berdasarkan username
        $user = $model->where('username', $username)->first();

        // Pastikan username benar-benar sama
        if (!$user || $user['username'] !== $username) {

            return redirect()->back()
                ->withInput()
                ->with('error', 'Username tidak sesuai. Perhatikan huruf besar dan kecil.');
        }

        if (!password_verify($password, $user['password'])) {

            return redirect()->back()
                ->withInput()
                ->with('error', 'Password salah.');
        }

        session()->set([
            'user_id'  => $user['id'],
            'username' => $user['username'],

            'logged_in' => true,

        ]);

          return redirect()->to('/dashboard-manager')
            ->with('success', 'Selamat datang, ' . $user['username'] . '');
    }

    public function logout()
    {
        session()->destroy();

        return redirect()->to('/login');
    }
    public function forgotPassword()
    {
        return view('auth/forgot_password');
    }

    public function resetPassword()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');

        if ($password !== $confirmPassword) {

            return redirect()->back()
                ->with('error', 'Konfirmasi password tidak cocok');
        }

        $model = new \App\Models\LoginModel();

        $user = $model->where('username', $username)->first();

        if (!$user) {

            return redirect()->back()
                ->with('error', 'Username tidak ditemukan');
        }

        $model->update($user['id'], [
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);

        return redirect()->to('/login')
            ->with('success', 'Password berhasil diperbarui. Silakan login dengan password baru.');
    }
}
