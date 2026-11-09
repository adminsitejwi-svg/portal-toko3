<?php

namespace App\Controllers;

use App\Models\BastDandanModel;

// Halaman BAST Dandan (menu Activation > BAST > Dandan); fitur & tampilan dari BastDokumen + view Bast/index.php
class BastDandan extends BastDokumen
{
    protected string $route    = 'BastDandan';
    protected string $judul    = 'BAST Dandan';
    protected string $subjudul = 'Berita Acara Serah Terima toko Dandan';
    protected string $ikon     = 'ti-building-store';
    protected string $contohNo = 'BAST/DDN/2026/001';

    public function __construct()
    {
        $this->model = new BastDandanModel();
    }
}
