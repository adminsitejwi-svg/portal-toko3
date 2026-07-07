<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\LoginModel;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1) Belum login -> ke halaman login
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Ambil ID user dari session
        $userId = session()->get('user_id');

        // Ambil data user terbaru dari database
        $user = (new LoginModel())->find($userId);

        // 2) Akun sudah dihapus -> paksa logout
        if (!$user) {
            session()->destroy();
            return redirect()->to('/login')
                ->with('error', 'Akun Anda telah dihapus oleh Administrator.');
        }
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {}
}
