<?php

namespace App\Controllers;

// Halaman "Segera Hadir" untuk menu All NOC yang belum dibuat (mis. FTP Client, Data OLT).
// Nama, ikon & keterangan tiap menu ada di app/Config/AllNoc.php.
class ComingSoon extends BaseController
{
    public function show(string $route)
    {
        $menu = config('AllNoc')->cari($route);

        if (! $menu) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('ComingSoon/index', ['halaman' => $menu]);
    }
}
