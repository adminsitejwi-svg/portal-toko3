<?php

namespace App\Controllers;

use App\Models\BastAlfamidiModel;

// Halaman BAST Alfamidi (menu Activation > BAST > Alfamidi); fitur & tampilan dari BastDokumen + view Bast/index.php
class BastAlfamidi extends BastDokumen
{
    protected string $route    = 'BastAlfamidi';
    protected string $judul    = 'BAST Alfamidi';
    protected string $subjudul = 'Berita Acara Serah Terima toko Alfamidi';
    protected string $ikon     = 'ti-building-store';
    protected string $contohNo = 'BAST/MIDI/2026/001';

    public function __construct()
    {
        $this->model = new BastAlfamidiModel();
    }
}
