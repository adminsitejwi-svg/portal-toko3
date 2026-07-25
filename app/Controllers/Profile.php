<?php

namespace App\Controllers;

use App\Models\LoginModel;

class Profile extends BaseController
{
    protected $loginModel;

    public function __construct()
    {
        $this->loginModel = new LoginModel();
    }

    public function index()
    {
        // Ambil hanya kolom yang memang ada di tabel login
        $user = $this->loginModel
            ->select('id, username, created_at')
            ->find(session('user_id'));

        if (!$user) {
            return redirect()->to('/login');
        }

        return view('Profile/index', ['user' => $user]);
    }

    public function deleteAccount()
    {
        $id = session('user_id');

        if ($id) {
            $this->loginModel->delete($id); // hapus baris user dari tabel login
        }

        session()->destroy(); // akhiri sesi login

        return redirect()->to('/login')
            ->with('success', 'Akun berhasil dihapus.');
    }
}
