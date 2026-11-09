<?php

namespace App\Controllers;

use App\Models\BastCorporateModel;

// Halaman BAST Corporate (menu Activation > BAST > Corporate); fitur & tampilan dari BastDokumen + view Bast/index.php
class BastCorporate extends BastDokumen
{
    protected string $route    = 'BastCorporate';
    protected string $judul    = 'BAST Corporate';
    protected string $subjudul = 'Berita Acara Serah Terima pelanggan corporate';
    protected string $ikon     = 'ti-building-skyscraper';
    protected string $contohNo = 'BAST/CORP/2026/001';

    public function __construct()
    {
        $this->model = new BastCorporateModel();
    }
}
