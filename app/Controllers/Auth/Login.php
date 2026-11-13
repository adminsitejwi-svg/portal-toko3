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

        // arahkan ke halaman pertama yang boleh dibuka role-nya
        \App\Libraries\HakAkses::reset();
        $tujuan = \App\Libraries\HakAkses::instance()->halamanAwal();

        if ($tujuan === null) {
            session()->destroy();

            return redirect()->to('/login')
                ->with('error', 'Akun Anda belum diberi hak akses ke halaman mana pun. Hubungi Administrator.');
        }

          return redirect()->to(site_url($tujuan))
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

        // selain Administrator hanya boleh mengganti password akunnya sendiri
        if (!\App\Libraries\HakAkses::instance()->isAdmin() && $username !== session('username')) {
            return redirect()->back()
                ->with('error', 'Anda hanya bisa mengganti password akun sendiri.');
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



        return redirect()->to('/Profile')
            ->with('success', 'Password berhasil diperbarui.')
            ->with('pw_just_changed', true);   //
    }
}
