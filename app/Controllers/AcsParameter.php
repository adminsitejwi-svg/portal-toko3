<?php

namespace App\Controllers;

use App\Models\AcsModel;
use App\Models\AcsParameterModel;

// Halaman Parameter Master (menu ACS JWI Group > Parameter Master), mengikuti Parameter Master speednet_acs. Hanya membaca.
class AcsParameter extends BaseController
{
    public function __construct()
    {
        date_default_timezone_set('Asia/Jakarta'); // waktu lokal (WIB), sama dengan halaman lain
    }

    // Halaman utama (view AcsParameter/index.php); ?model=HG8245H5
    public function index()
    {
        if (! AcsModel::siap() || ! AcsParameterModel::siap()) {
            return view('AcsParameter/index', ['tabelSiap' => false]);
        }
        $m      = new AcsParameterModel();
        $daftar = $m->daftarModel();
        $minta  = $this->request->getGet('model');
        $model  = is_string($minta) && in_array($minta, $daftar, true) ? $minta : ($daftar[0] ?? '');

        return view('AcsParameter/index', ['tabelSiap' => true, 'daftar' => $daftar, 'model' => $model, 'katalog' => $m->katalog($model)]);
    }
}
