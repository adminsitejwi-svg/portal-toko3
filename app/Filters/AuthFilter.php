<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\LoginModel;
use App\Libraries\HakAkses;

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

        // 3) Hak akses per role / per akun (diatur di Informasi > Hak Akses)
        $akses = HakAkses::instance();
        $path  = method_exists($request, 'getPath') ? $request->getPath() : uri_string();

        if (!$akses->bolehPath($path)) {
            if ($request->isAJAX() || str_contains($request->getHeaderLine('Accept'), 'application/json')) {
                return service('response')->setStatusCode(403)->setJSON([
                    'status'  => false,
                    'message' => 'Anda tidak punya hak akses ke halaman ini.',
                ]);
            }

            return redirect()->to(site_url($akses->halamanAwal()))
                ->with('akses_ditolak', 'Anda tidak punya hak akses ke halaman tersebut. Hubungi Administrator.');
        }
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {}
}
