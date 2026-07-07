<?php

namespace App\Controllers;

class Alfamidi extends BaseController
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
    public function index()
    {
        $model = new \App\Models\AlfamidiModel();

        $data['midi']           = $model->orderBy('id', 'DESC')->findAll();
        $data['pemilik_projek'] = (new \App\Models\PemilikProjectModel())->findAll();
        $data['dc']             = (new \App\Models\DCModel())->findAll();
        $data['media']          = (new \App\Models\MediaKoneksiModel())->findAll();
        $data['vendor']         = (new \App\Models\VendorModel())->findAll();
        $data['layanan']        = (new \App\Models\LayananVendorModel())->findAll();
        $data['jenis']          = (new \App\Models\JenisPerangkatModel())->findAll();
        $data['perangkat']      = (new \App\Models\PerangkatModel())->findAll();

        return view('Alfamidi/index', $data);
    }
    public function create()
    {
        $data['pemilik_projek'] = (new \App\Models\PemilikProjectModel())->findAll();
        $data['dc']             = (new \App\Models\DCModel())->findAll();
        $data['media']          = (new \App\Models\MediaKoneksiModel())->findAll();
        $data['vendor']         = (new \App\Models\VendorModel())->findAll();
        $data['layanan']        = (new \App\Models\LayananVendorModel())->findAll();
        $data['jenis']          = (new \App\Models\JenisPerangkatModel())->findAll();
        $data['perangkat']      = (new \App\Models\PerangkatModel())->findAll();
        $data['type_perangkat'] = (new \App\Models\TypePerangkatModel())->findAll();

        return view('Alfamidi/FormMidi', $data);
    }

    public function save()
    {
        date_default_timezone_set('Asia/Jakarta');

        // ── Cegah data ganda berdasarkan kode toko ──
        $modelCek = new \App\Models\AlfamidiModel();
        $kodeToko = trim((string) $this->request->getPost('kode_toko'));
        if ($kodeToko !== '' && $modelCek->where('kode_toko', $kodeToko)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Data Toko Midi dengan kode toko "' . $kodeToko . '" sudah ada. Data tidak boleh ganda.');
        }

        // Ganti bagian upload di save()
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

        // ── Simpan ke database ──────────────────────────────────────────────
        $model = new \App\Models\AlfamidiModel();
        $model->save([

            // Relasi
            'pemilik_projek_id'     => $this->request->getPost('pemilik_projek_id'),
            'nama_dc_id'            => $this->request->getPost('nama_dc_id'),
            'media_koneksi_id'      => $this->request->getPost('media_koneksi_id'),
            'vendor_id'             => $this->request->getPost('vendor_id'),
            'layanan_vendor_id'     => $this->request->getPost('layanan_vendor_id'),
            'jenis_perangkat_id'    => $this->request->getPost('jenis_perangkat_id'),
            'merk_perangkat_id'     => $this->request->getPost('merk_perangkat_id'),
            // Toko
            'nama_alfamidi'         => $this->request->getPost('nama_alfamidi'),
            'kode_toko'             => $this->request->getPost('kode_toko'),
            'alamat_alfamidi'       => $this->request->getPost('alamat_alfamidi'),
            'pic_toko'              => $this->request->getPost('pic_toko'),
            'nomor_hp_pic'          => $this->request->getPost('nomor_hp_pic'),
            'status'                => $this->request->getPost('status'),
            // Map
            'titik_koor_toko'       => $this->request->getPost('titik_koor_toko'),
            'map_toko'              => $this->request->getPost('map_toko'),
            // Perangkat & jaringan
            'tanggal_installasi'    => $this->request->getPost('tanggal_installasi') ?: null,
            'tanggal_aktivasi'      => $this->request->getPost('tanggal_aktivasi')   ?: null,
            'kapasitas_bandwidth'   => $this->request->getPost('kapasitas_bandwidth'),
            'ip_address'            => $this->request->getPost('ip_address'),
            'type_perangkat' => $this->request->getPost('type_perangkat_id'),
            'serial_number_perangkat' => $this->request->getPost('serial_number_perangkat'),
            // Lainnya
            'keterangan'            => $this->request->getPost('keterangan'),
            'upload_lampiran'       => $uploadedFileName,
            'created_at'            => date('Y-m-d H:i:s'),
            'backup_media_koneksi' => $this->request->getPost('backup_media_koneksi') ?: null,

        ]);



        return redirect()->to('/Alfamidi')
            ->with('success', 'Data Toko Midi berhasil disimpan.');
    }

    public function delete($id)
    {
        $model = new \App\Models\AlfamidiModel();
        $data  = $model->find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Hapus file lampiran jika ada
        if (!empty($data['upload_lampiran'])) {
            $filePath = WRITEPATH . 'uploads/lampiran/' . $data['upload_lampiran'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $model->delete($id);
        return redirect()->to('/Alfamidi')->with('success', 'Data berhasil dihapus.');
    }

    public function update()
    {
        date_default_timezone_set('Asia/Jakarta');
        $id    = $this->request->getPost('id');
        $model = new \App\Models\AlfamidiModel();
        $existing = $model->find($id);

        // ── Cegah data ganda berdasarkan kode toko (kecuali baris ini sendiri) ──
        $kodeToko = trim((string) $this->request->getPost('kode_toko'));
        if ($kodeToko !== '' && $model->where('kode_toko', $kodeToko)->where('id !=', $id)->first()) {
            return redirect()->back()->withInput()
                ->with('error', 'Data Toko Midi dengan kode toko "' . $kodeToko . '" sudah ada. Data tidak boleh ganda.');
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
        $allFiles        = array_merge($keptFiles, $newFiles);
        $uploadedFileName = !empty($allFiles) ? json_encode($allFiles) : null;

        // ── Simpan ke DB ──────────────────────────────────
        $model->update($id, [
            'pemilik_projek_id'       => $this->request->getPost('pemilik_projek_id'),
            'nama_dc_id'              => $this->request->getPost('nama_dc_id'),
            'media_koneksi_id'        => $this->request->getPost('media_koneksi_id'),
            'vendor_id'               => $this->request->getPost('vendor_id'),
            'layanan_vendor_id'       => $this->request->getPost('layanan_vendor_id'),
            'jenis_perangkat_id'      => $this->request->getPost('jenis_perangkat_id'),
            'merk_perangkat_id'       => $this->request->getPost('merk_perangkat_id'),
            'nama_alfamidi'           => $this->request->getPost('nama_alfamidi'),
            'kode_toko'               => $this->request->getPost('kode_toko'),
            'alamat_alfamidi'         => $this->request->getPost('alamat_alfamidi'),
            'pic_toko'                => $this->request->getPost('pic_toko'),
            'nomor_hp_pic'            => $this->request->getPost('nomor_hp_pic'),
            'status'                  => $this->request->getPost('status'),
            'titik_koor_toko'         => $this->request->getPost('titik_koor_toko'),
            'map_toko'                => $this->request->getPost('map_toko'),
            'tanggal_installasi'      => $this->request->getPost('tanggal_installasi') ?: null,
            'tanggal_aktivasi'        => $this->request->getPost('tanggal_aktivasi')   ?: null,
            'kapasitas_bandwidth'     => $this->request->getPost('kapasitas_bandwidth'),
            'ip_address'              => $this->request->getPost('ip_address'),
            'type_perangkat'          => $this->request->getPost('type_perangkat'),
            'serial_number_perangkat' => $this->request->getPost('serial_number_perangkat'),
            'keterangan'              => $this->request->getPost('keterangan'),
            'upload_lampiran'         => $uploadedFileName,
            'backup_media_koneksi'    => $this->request->getPost('backup_media_koneksi') ?: null,

        ]);

        return redirect()->to('/Alfamidi')->with('success', 'Data berhasil diperbarui.');
    }

    // Di Alfamidi.php — tambah method edit()
    public function edit($id)
    {
        $model   = new \App\Models\AlfamidiModel();
        $data['midi'] = $model->find($id);

        if (!$data['midi']) {
            return redirect()->to('/Alfamidi')->with('error', 'Data tidak ditemukan.');
        }

        $data['pemilik_projek'] = (new \App\Models\PemilikProjectModel())->findAll();
        $data['dc']             = (new \App\Models\DCModel())->findAll();
        $data['media']          = (new \App\Models\MediaKoneksiModel())->findAll();
        $data['vendor']         = (new \App\Models\VendorModel())->findAll();
        $data['layanan']        = (new \App\Models\LayananVendorModel())->findAll();
        $data['jenis']          = (new \App\Models\JenisPerangkatModel())->findAll();
        $data['perangkat']      = (new \App\Models\PerangkatModel())->findAll();
        $data['type_perangkat'] = (new \App\Models\TypePerangkatModel())->findAll();

        return view('Alfamidi/EditFormMidi', $data);
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

    public function getMapData()
    {
        $db = \Config\Database::connect();

        $data = $db->table('d_midi a')
            ->select('
            a.*,
            dc.nama_dc,
            v.nama_vendor,
            mk.media_koneksi,
            jp.jenis_perangkat,
            mp.merk_perangkat,
            tp.type_perangkat AS type_perangkat_nama
        ')
            ->join('md_dc dc', 'dc.id = a.nama_dc_id', 'left')
            ->join('md_vendor v', 'v.id = a.vendor_id', 'left')
            ->join('md_media_koneksi mk', 'mk.id = a.media_koneksi_id', 'left')
            ->join('md_jenis_perangkat jp', 'jp.id = a.jenis_perangkat_id', 'left')
            ->join('md_merek_perangkat mp', 'mp.id = a.merk_perangkat_id', 'left')
            ->join('md_type_perangkat tp', 'tp.id = a.type_perangkat', 'left')
            ->get()
            ->getResultArray();

        return $this->response->setJSON($data);
    }
}
