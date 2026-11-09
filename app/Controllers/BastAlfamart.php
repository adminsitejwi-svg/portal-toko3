<?php

namespace App\Controllers;

use App\Models\BastAlfamartModel;

// Halaman BAST Alfamart (menu Activation > BAST > Alfamart); fitur & tampilan dari BastDokumen + view Bast/index.php
class BastAlfamart extends BastDokumen
{
    protected string $route    = 'BastAlfamart';
    protected string $judul    = 'BAST Alfamart';
    protected string $subjudul = 'Berita Acara Serah Terima toko Alfamart';
    protected string $ikon     = 'ti-building-store';
    protected string $contohNo = 'BAST/AMRT/2026/001';

    public function __construct()
    {
        $this->model = new BastAlfamartModel();
    }
}
