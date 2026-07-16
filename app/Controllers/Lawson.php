<?php

namespace App\Controllers;

class Lawson extends BaseController
{
    private function getCoordinate($address)
    {
        $apiKey = "MASUKKAN_GOOGLE_API_KEY_ANDA";

        $url =
            "https://maps.googleapis.com/maps/api/geocode/json?" .
            http_build_query([
                'address' => $address,
                'key' => $apiKey
            ]);

        $response = file_get_contents($url);

        $result = json_decode($response, true);

        if (
            isset(
                $result['results'][0]['geometry']['location']
            )
        ) {
            return [
                'lat' =>
                $result['results'][0]['geometry']['location']['lat'],

                'lng' =>
                $result['results'][0]['geometry']['location']['lng']
            ];
        }

        return null;
    }

    /**
     * Data master yang dipakai bersama oleh index(), create(), dan edit().
     * Disatukan supaya tidak duplikasi kode di 3 method.
     */
    private function getMasterData(): array
    {
        return [
            'pemilik_projek' => (new \App\Models\PemilikProjectModel())->findAll(),
            'dc'             => (new \App\Models\DCModel())->findAll(),
            'jenis'          => (new \App\Models\JenisPerangkatModel())->findAll(),
            'perangkat'      => (new \App\Models\PerangkatModel())->findAll(),
            'type_perangkat' => (new \App\Models\TypePerangkatModel())->findAll(),

            // Jalur Non-Cellular (media_koneksi = 0) — sudah di-join lengkap sampai nama vendor
            'nomor_inet'     => $this->getNomorInetOptions(),

            // Jalur Cellular (media_koneksi = 1) — sudah di-join lengkap sampai nama vendor
            'simcard'        => $this->getSimcardOptions(),

            // Tujuan koneksi VPN
            'vpn'            => (new \App\Models\VPNModel())->findAll(),
        ];
    }

    /**
     * Data Penggunaan Nomor INET (d_nomor_inet) di-join sampai ke md_vendor,
     * supaya form bisa auto-tampilkan Kode Layanan Vendor, Password INET,
     * Nama Vendor, Nama Layanan Vendor, Harga Layanan, Kecepatan/Bandwidth
     * begitu admin memilih salah satu baris (readonly / disable sesuai dokumentasi).
     */
    private function getNomorInetOptions(): array
    {
        $db = \Config\Database::connect();

        $rows = $db->table('d_nomor_inet dni')
            ->select('
            dni.id                     AS usage_id,
            dni.id_pelanggan,
            dni.kode_toko              AS usage_kode_toko,
            mni.nomor_inet,
            mni.password_inet,
            mni.kecepatan_bandwidth,
            mni.harga_layanan,
            lv.kode_layanan_vendor,
            lv.nama_layanan,
            v.nama_vendor
        ')
            ->join('md_nomer_inet mni', 'mni.id = dni.nomer_inet_id', 'left')
            ->join('md_layanan_vendor lv', 'lv.id = mni.layanan_vendor_id', 'left')
            ->join('md_vendor v', 'v.id = lv.vendor_id', 'left')
            ->where('dni.status', 0)
            ->get()
            ->getResultArray();

        // Decrypt password sebelum dikirim ke view
        $encrypter = \Config\Services::encrypter();

        foreach ($rows as &$row) {
            if (!empty($row['password_inet'])) {
                try {
                    $row['password_inet'] = $encrypter->decrypt(
                        base64_decode($row['password_inet'])
                    );
                } catch (\Throwable $e) {
                    // data lama yang belum terenkripsi / gagal decrypt → biarkan apa adanya
                }
            }
        }
        unset($row);

        return $rows;
    }

    public function encryptOldPasswords()
    {
        $db = \Config\Database::connect();
        $encrypter = \Config\Services::encrypter();

        $rows = $db->table('md_nomer_inet')->get()->getResultArray();

        foreach ($rows as $row) {
            // skip yang sudah terenkripsi (cek dengan coba-decrypt)
            try {
                $encrypter->decrypt(base64_decode($row['password_inet']));
                continue; // sudah terenkripsi
            } catch (\Throwable $e) {
                // belum terenkripsi → encrypt sekarang
            }

            $db->table('md_nomer_inet')->where('id', $row['id'])->update([
                'password_inet' => base64_encode($encrypter->encrypt($row['password_inet'])),
            ]);
        }

        echo 'Selesai.';
    }

    /**
     * Data Penggunaan Simcard (d_simcard) di-join sampai ke md_vendor_cellular,
     * supaya form bisa auto-tampilkan Kode Quota SimCard, Nomor MSISDN,
     * Nomor ISSID/IMEI, Nama Vendor, Nama Paket Data, Harga Paket Quota,
     * Isi Quota Internet SimCard (readonly / disable sesuai dokumentasi).
     */
    private function getSimcardOptions(): array
    {
        $db = \Config\Database::connect();

        return $db->table('d_simcard ds')
            ->select('
                ds.id              AS usage_id,
                ds.nomor_msisdn,
                ds.nomor_imei,
                qs.kode_quota_simcard,
                qs.nama_paket_data,
                qs.quota_internet,
                qs.harga_quota,
                vc.nama_vendor
            ')
            ->join('md_quota_simcard qs', 'qs.id = ds.quota_simcard_id', 'left')
            ->join('md_vendor_cellular vc', 'vc.id = qs.vendor_cellular_id', 'left')
            ->where('ds.status', 0) // hanya yang aktif
            ->get()
            ->getResultArray();
    }

    public function index()
    {
        $db = \Config\Database::connect();

        $data = $this->getMasterData();

        // Ambil data toko + bandwidth dari relasi vendor:
        // - Non Cellular -> kecepatan_bandwidth (md_nomer_inet)
        // - Cellular     -> quota_internet (md_quota_simcard)
        $data['lawson'] = $db->table('d_lawson a')
            ->select('
                a.*,
                COALESCE(mni.kecepatan_bandwidth, qs.quota_internet) AS kapasitas_bandwidth
            ')
            ->join('d_nomor_inet dni', 'dni.id = a.nomor_inet_id', 'left')
            ->join('md_nomer_inet mni', 'mni.id = dni.nomer_inet_id', 'left')
            ->join('d_simcard ds', 'ds.id = a.simcard_id', 'left')
            ->join('md_quota_simcard qs', 'qs.id = ds.quota_simcard_id', 'left')
            ->orderBy('a.id', 'DESC')
            ->get()
            ->getResultArray();

        return view('Lawson/index', $data);
    }

    public function create()
    {
        $data = $this->getMasterData();

        return view('Lawson/FormLawson', $data);
    }

    public function save()
    {
        date_default_timezone_set('Asia/Jakarta');

        // ── Cegah data ganda berdasarkan kode toko ──
        $modelCek = new \App\Models\LawsonModel();
        $kodeToko = trim((string) $this->request->getPost('kode_toko'));
        if ($kodeToko !== '' && $modelCek->where('kode_toko', $kodeToko)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Data Toko Lawson dengan kode toko "' . $kodeToko . '" sudah ada. Data tidak boleh ganda.');
        }

        // ── Upload lampiran ──
        $uploadedFileNames = [];
        $files = $this->request->getFileMultiple('file_input');

        if ($files) {
            $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            $allowedExts  = ['jpg', 'jpeg', 'png', 'pdf'];
            $uploadPath   = WRITEPATH . 'uploads/lampiran/';

            if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);

            foreach ($files as $file) {
                if (!$file || !$file->isValid() || $file->hasMoved()) continue;

                $mimeType = $file->getMimeType();
                $ext      = strtolower($file->getClientExtension());

                if (!in_array($mimeType, $allowedTypes) || !in_array($ext, $allowedExts)) continue;
                if ($file->getSize() > 5 * 1024 * 1024) continue;

                $randomName = bin2hex(random_bytes(10)) . '.' . $ext;
                $file->move($uploadPath, $randomName);
                $uploadedFileNames[] = $randomName;
            }
        }

        $uploadedFileName = !empty($uploadedFileNames) ? json_encode($uploadedFileNames) : null;

        // ── Tentukan jalur media koneksi ──
        // media_koneksi: 0 = Non Cellular (pakai nomor_inet_id), 1 = Cellular (pakai simcard_id)
        $mediaKoneksi = $this->request->getPost('media_koneksi');
        $nomorInetId  = ($mediaKoneksi == 0) ? $this->request->getPost('nomor_inet_id') : null;
        $simcardId    = ($mediaKoneksi == 1) ? $this->request->getPost('simcard_id')    : null;

        // ── Simpan ke database ──────────────────────────────────────────────
        $model = new \App\Models\LawsonModel();
        $model->save([

            // Relasi
            'pemilik_projek_id'       => $this->request->getPost('pemilik_projek_id'),
            'nama_dc_id'              => $this->request->getPost('nama_dc_id'),
            'nomor_inet_id'           => $nomorInetId,
            'simcard_id'              => $simcardId,
            'jenis_perangkat_id'      => $this->request->getPost('jenis_perangkat_id'),
            'merk_perangkat_id'       => $this->request->getPost('merk_perangkat_id'),
            'type_perangkat_id'       => $this->request->getPost('type_perangkat_id'),
            'vpn_id'                  => $this->request->getPost('vpn_id'),

            // Toko
            'nama_lawson'           => $this->request->getPost('nama_lawson'),
            'kode_toko'               => $kodeToko,
            'alamat_lawson'         => $this->request->getPost('alamat_lawson'),
            'pic_toko'                => $this->request->getPost('pic_toko'),
            'nomor_hp_pic'            => $this->request->getPost('nomor_hp_pic'),
            'status'                  => $this->request->getPost('status'),

            // Map
            'titik_koor_toko'         => $this->request->getPost('titik_koor_toko'),
            'map_toko'                => $this->request->getPost('map_toko'),

            // Perangkat & jaringan
            'tanggal_installasi'      => $this->request->getPost('tanggal_installasi') ?: null,
            'tanggal_aktivasi'        => $this->request->getPost('tanggal_aktivasi')   ?: null,
            'media_koneksi'           => $mediaKoneksi,
            'kategori_ip_address'     => $this->request->getPost('kategori_ip_address'),
            'jenis_ip_address'        => $this->request->getPost('jenis_ip_address'),
            'ip_address_toko'         => $this->request->getPost('ip_address_toko'),
            'type_koneksi'            => $this->request->getPost('type_koneksi'),
            'serial_number_perangkat' => $this->request->getPost('serial_number_perangkat'),

            // Lainnya
            'keterangan'              => $this->request->getPost('keterangan'),
            'upload_lampiran'         => $uploadedFileName,
            'created_at'              => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to('/Lawson')
            ->with('success', 'Data Toko Lawson berhasil disimpan.');
    }

    public function delete($id)
    {
        $model = new \App\Models\LawsonModel();
        $data  = $model->find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Hapus file lampiran jika ada
        if (!empty($data['upload_lampiran'])) {
            $decoded = json_decode($data['upload_lampiran'], true);
            $files   = is_array($decoded) ? $decoded : [$data['upload_lampiran']];

            foreach ($files as $f) {
                $filePath = WRITEPATH . 'uploads/lampiran/' . $f;
                if (file_exists($filePath)) unlink($filePath);
            }
        }

        $model->delete($id);
        return redirect()->to('/Lawson')->with('success', 'Data berhasil dihapus.');
    }

    public function update()
    {
        date_default_timezone_set('Asia/Jakarta');
        $id       = $this->request->getPost('id');
        $model    = new \App\Models\LawsonModel();
        $existing = $model->find($id);

        // ── Cegah data ganda berdasarkan kode toko (kecuali baris ini sendiri) ──
        $kodeToko = trim((string) $this->request->getPost('kode_toko'));
        if ($kodeToko !== '' && $model->where('kode_toko', $kodeToko)->where('id !=', $id)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Data Toko Lawson dengan kode toko "' . $kodeToko . '" sudah ada. Data tidak boleh ganda.');
        }

        // ── Deteksi "tidak ada perubahan" ──────────────────────────────────
        // Jika seluruh field sama persis dengan data tersimpan, tidak ada file
        // baru, dan tidak ada file lama yang dihapus, hentikan di sini supaya
        // update tidak dijalankan dan TIDAK tercatat di history (activity_logs).
        $mediaKoneksi = $this->request->getPost('media_koneksi');
        $nomorInetId  = ($mediaKoneksi == 0) ? $this->request->getPost('nomor_inet_id') : null;
        $simcardId    = ($mediaKoneksi == 1) ? $this->request->getPost('simcard_id')    : null;

        $dataBaru = [
            'pemilik_projek_id'       => $this->request->getPost('pemilik_projek_id'),
            'nama_dc_id'              => $this->request->getPost('nama_dc_id'),
            'nomor_inet_id'           => $nomorInetId,
            'simcard_id'              => $simcardId,
            'jenis_perangkat_id'      => $this->request->getPost('jenis_perangkat_id'),
            'merk_perangkat_id'       => $this->request->getPost('merk_perangkat_id'),
            'type_perangkat_id'       => $this->request->getPost('type_perangkat_id'),
            'vpn_id'                  => $this->request->getPost('vpn_id'),
            'nama_lawson'           => $this->request->getPost('nama_lawson'),
            'kode_toko'               => $kodeToko,
            'alamat_lawson'         => $this->request->getPost('alamat_lawson'),
            'pic_toko'                => $this->request->getPost('pic_toko'),
            'nomor_hp_pic'            => $this->request->getPost('nomor_hp_pic'),
            'status'                  => $this->request->getPost('status'),
            'titik_koor_toko'         => $this->request->getPost('titik_koor_toko'),
            'map_toko'                => $this->request->getPost('map_toko'),
            'tanggal_installasi'      => $this->request->getPost('tanggal_installasi') ?: null,
            'tanggal_aktivasi'        => $this->request->getPost('tanggal_aktivasi')   ?: null,
            'media_koneksi'           => $mediaKoneksi,
            'kategori_ip_address'     => $this->request->getPost('kategori_ip_address'),
            'jenis_ip_address'        => $this->request->getPost('jenis_ip_address'),
            'ip_address_toko'         => $this->request->getPost('ip_address_toko'),
            'type_koneksi'            => $this->request->getPost('type_koneksi'),
            'serial_number_perangkat' => $this->request->getPost('serial_number_perangkat'),
            'keterangan'              => $this->request->getPost('keterangan'),
        ];

        $adaPerubahanField = false;
        foreach ($dataBaru as $kolom => $nilaiBaru) {
            if ((string) ($existing[$kolom] ?? '') !== (string) ($nilaiBaru ?? '')) {
                $adaPerubahanField = true;
                break;
            }
        }

        $adaFileBaru = false;
        foreach ((array) $this->request->getFileMultiple('file_input') as $fCek) {
            if ($fCek && $fCek->isValid()) {
                $adaFileBaru = true;
                break;
            }
        }

        $lampiranBerubah = $adaFileBaru
            || (string) $this->request->getPost('existing_files') !== (string) ($existing['upload_lampiran'] ?? '');

        if (!$adaPerubahanField && !$lampiranBerubah) {
            return redirect()->back()
                ->with('error', 'Tidak ada perubahan data. Data tidak disimpan dan tidak tercatat di history.');
        }

        // ── Kelola file lama ──────────────────────────────
        $existingFilesRaw = $this->request->getPost('existing_files');
        $keptFiles = [];

        if (!empty($existingFilesRaw)) {
            $decoded = json_decode($existingFilesRaw, true);
            $keptFiles = is_array($decoded) ? $decoded : [$existingFilesRaw];
        }

        // Hapus file lama yang tidak dipertahankan
        if (!empty($existing['upload_lampiran'])) {
            $oldDecoded = json_decode($existing['upload_lampiran'], true);
            $oldFiles   = is_array($oldDecoded) ? $oldDecoded : [$existing['upload_lampiran']];

            foreach ($oldFiles as $oldFile) {
                if (!in_array($oldFile, $keptFiles)) {
                    $filePath = WRITEPATH . 'uploads/lampiran/' . $oldFile;
                    if (file_exists($filePath)) unlink($filePath);
                }
            }
        }

        // ── Upload file baru ──────────────────────────────
        $newFiles = [];
        $files    = $this->request->getFileMultiple('file_input');

        if ($files) {
            $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
            $allowedExts  = ['jpg', 'jpeg', 'png', 'pdf'];
            $uploadPath   = WRITEPATH . 'uploads/lampiran/';

            if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);

            foreach ($files as $file) {
                if (!$file || !$file->isValid() || $file->hasMoved()) continue;

                $mimeType = $file->getMimeType();
                $ext      = strtolower($file->getClientExtension());

                if (!in_array($mimeType, $allowedTypes) || !in_array($ext, $allowedExts)) continue;
                if ($file->getSize() > 5 * 1024 * 1024) continue;

                $randomName = bin2hex(random_bytes(10)) . '.' . $ext;
                $file->move($uploadPath, $randomName);
                $newFiles[] = $randomName;
            }
        }

        // Gabung file lama yang dipertahankan + file baru
        $allFiles         = array_merge($keptFiles, $newFiles);
        $uploadedFileName = !empty($allFiles) ? json_encode($allFiles) : null;

        // ── Tentukan jalur media koneksi ──
        $mediaKoneksi = $this->request->getPost('media_koneksi');
        $nomorInetId  = ($mediaKoneksi == 0) ? $this->request->getPost('nomor_inet_id') : null;
        $simcardId    = ($mediaKoneksi == 1) ? $this->request->getPost('simcard_id')    : null;

        // ── Simpan ke DB ──────────────────────────────────
        $model->update($id, [
            'pemilik_projek_id'       => $this->request->getPost('pemilik_projek_id'),
            'nama_dc_id'              => $this->request->getPost('nama_dc_id'),
            'nomor_inet_id'           => $nomorInetId,
            'simcard_id'              => $simcardId,
            'jenis_perangkat_id'      => $this->request->getPost('jenis_perangkat_id'),
            'merk_perangkat_id'       => $this->request->getPost('merk_perangkat_id'),
            'type_perangkat_id'       => $this->request->getPost('type_perangkat_id'),
            'vpn_id'                  => $this->request->getPost('vpn_id'),

            'nama_lawson'           => $this->request->getPost('nama_lawson'),
            'kode_toko'               => $kodeToko,
            'alamat_lawson'         => $this->request->getPost('alamat_lawson'),
            'pic_toko'                => $this->request->getPost('pic_toko'),
            'nomor_hp_pic'            => $this->request->getPost('nomor_hp_pic'),
            'status'                  => $this->request->getPost('status'),

            'titik_koor_toko'         => $this->request->getPost('titik_koor_toko'),
            'map_toko'                => $this->request->getPost('map_toko'),

            'tanggal_installasi'      => $this->request->getPost('tanggal_installasi') ?: null,
            'tanggal_aktivasi'        => $this->request->getPost('tanggal_aktivasi')   ?: null,
            'media_koneksi'           => $mediaKoneksi,
            'kategori_ip_address'     => $this->request->getPost('kategori_ip_address'),
            'jenis_ip_address'        => $this->request->getPost('jenis_ip_address'),
            'ip_address_toko'         => $this->request->getPost('ip_address_toko'),
            'type_koneksi'            => $this->request->getPost('type_koneksi'),
            'serial_number_perangkat' => $this->request->getPost('serial_number_perangkat'),

            'keterangan'              => $this->request->getPost('keterangan'),
            'upload_lampiran'         => $uploadedFileName,
        ]);

        return redirect()->to('/Lawson')->with('success', 'Data berhasil diperbarui.');
    }

    public function edit($id)
    {
        $model        = new \App\Models\LawsonModel();
        $data         = $this->getMasterData();
        $data['lawson'] = $model->find($id);

        if (!$data['lawson']) {
            return redirect()->to('/Lawson')->with('error', 'Data tidak ditemukan.');
        }

        return view('Lawson/EditFormLawson', $data);
    }

    public function serveFile($filename)
    {
        // Sanitasi nama file — cegah path traversal
        $filename = basename($filename);
        $filePath = WRITEPATH . 'uploads/lampiran/' . $filename;

        if (!file_exists($filePath)) {
            return $this->response->setStatusCode(404)->setBody('File tidak ditemukan.');
        }

        $ext      = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        $mimeMap  = [
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'pdf'  => 'application/pdf',
        ];

        $mime = $mimeMap[$ext] ?? 'application/octet-stream';

        // PDF → paksa download, gambar → tampil inline
        $disposition = $ext === 'pdf' ? 'attachment' : 'inline';

        return $this->response
            ->setHeader('Content-Type', $mime)
            ->setHeader('Content-Disposition', $disposition . '; filename="' . $filename . '"')
            ->setHeader('Content-Length', filesize($filePath))
            ->setHeader('Cache-Control', 'no-store, no-cache')
            ->setBody(file_get_contents($filePath));
    }
    public function view($id)
    {
        $model        = new \App\Models\LawsonModel();
        $data         = $this->getMasterData();
        $data['lawson'] = $model->find($id);

        if (!$data['lawson']) {
            return redirect()->to('/Lawson')->with('error', 'Data tidak ditemukan.');
        }

        return view('Lawson/ViewLawson', $data);
    }

    public function getMapData()
    {
        $db = \Config\Database::connect();

        $data = $db->table('d_lawson a')
            ->select('
            a.*,
            dc.nama_dc,
            jp.jenis_perangkat,
            mp.merk_perangkat,
            tp.type_perangkat AS type_perangkat_nama
        ')
            ->join('md_dc dc', 'dc.id = a.nama_dc_id', 'left')
            ->join('md_jenis_perangkat jp', 'jp.id = a.jenis_perangkat_id', 'left')
            ->join('md_merek_perangkat mp', 'mp.id = a.merk_perangkat_id', 'left')
            ->join('md_type_perangkat tp', 'tp.id = a.type_perangkat_id', 'left')
            ->get()
            ->getResultArray();

        return $this->response->setJSON($data);
    }
}
