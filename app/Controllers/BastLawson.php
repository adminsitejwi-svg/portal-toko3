<?php

namespace App\Controllers;

use App\Models\BastLawsonModel;

// Halaman BAST Lawson (menu Activation > BAST > Lawson); fitur & tampilan dari BastDokumen + view Bast/index.php
class BastLawson extends BastDokumen
{
    protected string $route    = 'BastLawson';
    protected string $judul    = 'BAST Lawson';
    protected string $subjudul = 'Berita Acara Serah Terima toko Lawson';
    protected string $ikon     = 'ti-building-store';
    protected string $contohNo = 'BAST/LWS/2026/001';

    public function __construct()
    {
        $this->model = new BastLawsonModel();
    }
}
