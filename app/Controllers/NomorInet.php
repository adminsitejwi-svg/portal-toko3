<?php

namespace App\Controllers;


class NomorInet extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        $data['MD_nomer_inet'] = $db->table('md_nomer_inet ni')
            ->select('ni.*, lv.kode_layanan_vendor, lv.nama_layanan, v.nama_vendor')
            ->join('md_layanan_vendor lv', 'lv.id = ni.layanan_vendor_id', 'left')
            ->join('md_vendor v', 'v.id = lv.vendor_id', 'left')
            ->orderBy('ni.id', 'DESC')
            ->get()->getResultArray();

        // ⬇️ dekripsi password_inet agar bisa ditampilkan di modal edit
        $encrypter = \Config\Services::encrypter();
        foreach ($data['MD_nomer_inet'] as &$r) {
            try {
                $r['password_inet'] = ($r['password_inet'] !== null && $r['password_inet'] !== '')
                    ? $encrypter->decrypt(base64_decode($r['password_inet']))
                    : '';
            } catch (\Throwable $e) {
                $r['password_inet'] = ''; // data lama (hash) tidak bisa didekripsi → kosongkan
            }
        }
        unset($r);

        // untuk dropdown di modal edit
        $data['MD_layanan_vendor'] = $db->table('md_layanan_vendor lv')
            ->select('lv.id, lv.kode_layanan_vendor, lv.nama_layanan, v.nama_vendor')
            ->join('md_vendor v', 'v.id = lv.vendor_id', 'left')
            ->orderBy('lv.kode_layanan_vendor', 'ASC')
            ->get()->getResultArray();

        return view('NomorInet/index', $data);
    }
    public function show($id)
    {
        $db = \Config\Database::connect();

        $data = $db->table('md_nomer_inet ni')
            ->select('ni.*, lv.kode_layanan_vendor, lv.nama_layanan, v.nama_vendor')
            ->join('md_layanan_vendor lv', 'lv.id = ni.layanan_vendor_id', 'left')
            ->join('md_vendor v', 'v.id = lv.vendor_id', 'left')
            ->where('ni.id', $id)
            ->get()
            ->getRowArray();

        if (!$data) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ]);
        }

        // dekripsi password (fallback kosong utk data legacy)
        $encrypter = \Config\Services::encrypter();
        try {
            $data['password_inet'] = $data['password_inet'] !== null && $data['password_inet'] !== ''
                ? $encrypter->decrypt(base64_decode($data['password_inet']))
                : '';
        } catch (\Throwable $e) {
            $data['password_inet'] = '';
        }

        return $this->response->setJSON([
            'success' => true,
            'data'    => $data
        ]);
    }

    public function create()
    {
        $db = \Config\Database::connect();

        $data['MD_layanan_vendor'] = $db->table('md_layanan_vendor lv')
            ->select('lv.id, lv.kode_layanan_vendor, lv.nama_layanan, v.nama_vendor')
            ->join('md_vendor v', 'v.id = lv.vendor_id', 'left')
            ->orderBy('lv.kode_layanan_vendor', 'ASC')
            ->get()->getResultArray();

        return view('NomorInet/FormNomerInet', $data);
    }

    public function save()
    {
        date_default_timezone_set('Asia/Jakarta');
        $model = new \App\Models\NomerInetModel();

        $layananVendorId = $this->request->getPost('layanan_vendor_id');
        $nomorInet       = trim((string) $this->request->getPost('nomor_inet'));

        $dup = $model->where('layanan_vendor_id', $layananVendorId)
            ->where('nomor_inet', $nomorInet)
            ->first();
        if ($dup) {
            return redirect()->back()->withInput()
                ->with('error', 'Nomor INET "' . $nomorInet . '" pada layanan tersebut sudah ada.');
        }

        $model->save([
            'layanan_vendor_id'   => $layananVendorId,
            'kecepatan_bandwidth' => $this->request->getPost('kecepatan_bandwidth'),
            'harga_layanan'       => $this->request->getPost('harga_layanan'),
            'nomor_inet'          => $nomorInet,
            'status'              => $this->request->getPost('status'),
            'keterangan'          => $this->request->getPost('keterangan'),
            'created_at'          => date('Y-m-d H:i:s'),
            'password_inet'       => base64_encode(
                \Config\Services::encrypter()->encrypt(
                    (string) $this->request->getPost('password_inet')
                )
            ),
        ]);

        return redirect()->to('/NomorInet')
            ->with('success', 'Data Nomor INET berhasil disimpan');
    }

    public function delete($id)
    {
        $model = new \App\Models\NomerInetModel();

        $data = $model->find($id);

        if (!$data) {
            return redirect()->back()
                ->with('error', 'Data tidak ditemukan.');
        }

        $model->delete($id);

        return redirect()->to('/NomorInet')
            ->with('success', 'Data berhasil dihapus.');
    }

    public function update()
    {
        date_default_timezone_set('Asia/Jakarta');
        $id    = $this->request->getPost('id');
        $model = new \App\Models\NomerInetModel();

        $layananVendorId = $this->request->getPost('layanan_vendor_id');
        $nomorInet       = trim((string) $this->request->getPost('nomor_inet'));

        $cekDup = $model->where('layanan_vendor_id', $layananVendorId)
            ->where('nomor_inet', $nomorInet)
            ->where('id !=', $id)
            ->first();
        if ($cekDup) {
            return redirect()->back()->withInput()
                ->with('error', 'Nomor INET "' . $nomorInet . '" pada layanan tersebut sudah ada.');
        }

        $updateData = [
            'layanan_vendor_id'   => $layananVendorId,
            'kecepatan_bandwidth' => $this->request->getPost('kecepatan_bandwidth'),
            'harga_layanan'       => $this->request->getPost('harga_layanan'),
            'nomor_inet'          => $nomorInet,
            'status'              => $this->request->getPost('status'),
            'keterangan'          => $this->request->getPost('keterangan'),
        ];

        $passwordBaru = (string) $this->request->getPost('password_inet');
        if ($passwordBaru !== '') {
            $updateData['password_inet'] = base64_encode(
                \Config\Services::encrypter()->encrypt($passwordBaru)
            );
        }

        $model->update($id, $updateData);

        return redirect()->to('/NomorInet')
            ->with('success', 'Data Nomor INET berhasil diperbarui.');
    }
}
