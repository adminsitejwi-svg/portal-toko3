<?php

namespace App\Controllers;

use App\Models\JenisPerangkatModel;
use App\Models\TypePerangkatModel;
use App\Models\LayananVendorModel;
use App\Models\PemilikProjectModel;
use App\Models\QuotaSIMCARDModel;
use App\Models\DataSIModel;
use App\Models\NMRInetModel;
use App\Models\PerangkatModel;

class DashboardManager extends BaseController
{
    /**
     * Hitung jumlah Aktif & Non Aktif untuk sebuah model toko.
     * Aman untuk status berupa angka (0/1) maupun teks ('Aktif'/'Non Aktif').
     */
    private function hitungStatus($model): array
    {
        $rows = $model->select('status, COUNT(*) AS jml')
            ->groupBy('status')
            ->findAll();

        $aktif = 0;
        $nonaktif = 0;

        foreach ($rows as $r) {
            // dukung hasil array maupun objek/entity
            $status = is_array($r) ? ($r['status'] ?? null) : ($r->status ?? null);
            $jml    = is_array($r) ? ($r['jml'] ?? 0)       : ($r->jml ?? 0);

            $s = strtolower(trim((string) $status));

            if ($s === '0' || $s === 'aktif') {
                $aktif += (int) $jml;
            } elseif ($s === '1' || $s === 'non aktif' || $s === 'nonaktif' || $s === 'tidak aktif') {
                $nonaktif += (int) $jml;
            }
        }

        return ['aktif' => $aktif, 'nonaktif' => $nonaktif];
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        $dcModel           = new \App\Models\DCModel();
        $vendorModel       = new \App\Models\VendorModel();
        $perangkatModel    = new PerangkatModel();
        $alfamidiModel     = new \App\Models\AlfamidiModel();
        $lawsonModel       = new \App\Models\LawsonModel();
        $mediaKoneksiModel = new \App\Models\MediaKoneksiModel();
        $alfamartModel     = new \App\Models\AlfamartModel();
        $pelangganModel    = new \App\Models\PelangganModel();

        $nomerInetModel    = new \App\Models\NomerInetModel();
        $vendorCellularModel = new \App\Models\VendorCelulllarModel();

        $jenisModel        = new JenisPerangkatModel();
        $typeModel         = new TypePerangkatModel();
        $layananModel      = new LayananVendorModel();
        $pemilikModel      = new PemilikProjectModel();
        $quotaModel        = new QuotaSIMCARDModel();
        $dataSIModel       = new DataSIModel();
        $nmrInetModel      = new NMRInetModel();
        $vpnModel = new \App\Models\VPNModel();


        $data['totalVPN'] = $vpnModel->countAllResults();

        $data['vendorCellular'] = $vendorCellularModel
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // ===== Hitungan master data lain =====
        $db = \Config\Database::connect();
        $data['totalPelanggan']    = $db->table('md_pelanggan')->countAllResults();
        $data['totalNomorInet']    = $db->table('md_nomer_inet')->countAllResults();

        $data['totalDC']           = $dcModel->countAllResults();
        $data['totalMediaKoneksi'] = $mediaKoneksiModel->countAllResults();
        $data['totalPerangkat']    = $perangkatModel->countAllResults();

        // ===== Merek perangkat (untuk tabel) =====
        $data['merekPerangkat'] = $perangkatModel
            ->select('merk_perangkat, COUNT(*) as jumlah')
            ->groupBy('merk_perangkat')
            ->orderBy('jumlah', 'DESC')
            ->findAll();

        // ===== Vendor (untuk tabel) =====
        $data['vendor'] = $vendorModel
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // ===== Alfamidi: total + Aktif/Non Aktif =====
        $data['total_midi']     = $alfamidiModel->countAll();
        $midi                   = $this->hitungStatus($alfamidiModel);
        $data['total_aktif']    = $midi['aktif'];
        $data['total_nonaktif'] = $midi['nonaktif'];

        // ===== Lawson: total + Aktif/Non Aktif =====
        $data['total_lawson']          = $lawsonModel->countAll();
        $lawson                        = $this->hitungStatus($lawsonModel);
        $data['total_lawson_aktif']    = $lawson['aktif'];
        $data['total_lawson_nonaktif'] = $lawson['nonaktif'];

        // ===== Alfamart: total + Aktif/Non Aktif =====
        $data['total_alfamart']          = $alfamartModel->countAll();
        $alfamart                        = $this->hitungStatus($alfamartModel);
        $data['total_alfamart_aktif']    = $alfamart['aktif'];
        $data['total_alfamart_nonaktif'] = $alfamart['nonaktif'];
        $data['totalJenisPerangkat'] = $jenisModel->countAllResults();

        $data['totalTypePerangkat'] = $typeModel->countAllResults();

        $data['totalLayananVendor'] = $layananModel->countAllResults();

        $data['totalPemilikProject'] = $pemilikModel->countAllResults();

        $data['totalKuotaSIMCARD'] = $quotaModel->countAllResults();


        $data['totalSIMCARD'] = $dataSIModel->countAllResults();

        $data['totalPenggunaanInet'] = $nmrInetModel->countAllResults();
        return view('manager/dashboard', $data);
        $data['totalVPN'] = $db->table('md_vpn')->countAllResults();
    }
}
