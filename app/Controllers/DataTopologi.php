<?php

namespace App\Controllers;

use App\Models\TopologiModel;

// Halaman Data Topologi (NOC Operation > Data Topologi).
// Topologi jaringan digambar dengan draw.io (embed.diagrams.net) di halaman editor;
// saat disimpan, XML diagram masuk database dan gambar PNG disimpan di public/uploads/topologi/.
class DataTopologi extends BaseController
{
    protected $model;
    protected $folder = 'uploads/topologi/'; // di dalam folder public/

    public function __construct()
    {
        $this->model = new TopologiModel();
        helper('activity');
    }

    // Daftar topologi (kartu + preview gambar). XML tidak ikut diambil supaya halaman ringan.
    public function index()
    {
        return view('DataTopologi/index', [
            'topologi' => $this->model
                ->select('id, nama, keterangan, gambar, created_by, updated_by, created_at, updated_at')
                ->orderBy('updated_at', 'DESC')
                ->orderBy('id', 'DESC')
                ->findAll(),
        ]);
    }

    // Buat topologi baru (nama + keterangan), lalu langsung buka editor
    public function save()
    {
        $data = $this->infoPost();
        if ($data === null) {
            return redirect()->to(site_url('DataTopologi'))->withInput()->with('error', 'Nama topologi wajib diisi (maks. 150 karakter).');
        }

        $data['created_by'] = $this->username();
        $data['updated_by'] = $data['created_by'];
        $this->model->insert($data);
        $id = $this->model->getInsertID();
        log_activity('create', 'd_topologi', $id, 'Membuat topologi baru: ' . $data['nama']);

        return redirect()->to(site_url('DataTopologi/editor/' . $id));
    }

    // Ubah nama / keterangan
    public function update()
    {
        $id  = (int) $this->request->getPost('id');
        $row = $this->model->select('id, nama, keterangan')->find($id);
        if (! $row) {
            return redirect()->to(site_url('DataTopologi'))->with('error', 'Topologi tidak ditemukan.');
        }

        $data = $this->infoPost();
        if ($data === null) {
            return redirect()->to(site_url('DataTopologi'))->with('error', 'Nama topologi wajib diisi (maks. 150 karakter).');
        }

        $data['updated_by'] = $this->username();
        $this->model->update($id, $data);
        log_activity('update', 'd_topologi', $id, 'Mengubah info topologi: ' . $data['nama'], $row, $data);

        return redirect()->to(site_url('DataTopologi'))->with('success', 'Info topologi berhasil diperbarui.');
    }

    // Halaman view (hanya lihat): viewer draw.io interaktif dari public/drawio, cadangan gambar PNG
    public function view($id)
    {
        $row = $this->model->find((int) $id);
        if (! $row) {
            return redirect()->to(site_url('DataTopologi'))->with('error', 'Topologi tidak ditemukan.');
        }

        return view('DataTopologi/view', ['row' => $row]);
    }

    // Halaman editor draw.io
    public function editor($id)
    {
        $row = $this->model->find((int) $id);
        if (! $row) {
            return redirect()->to(site_url('DataTopologi'))->with('error', 'Topologi tidak ditemukan.');
        }

        return view('DataTopologi/editor', ['row' => $row]);
    }

    // Simpan diagram dari editor (AJAX): xml + file PNG (format xmlpng dari draw.io)
    public function simpanDiagram()
    {
        $id  = (int) $this->request->getPost('id');
        $row = $this->model->select('id, nama, gambar')->find($id);
        if (! $row) {
            return $this->jsonError('Topologi tidak ditemukan.', 404);
        }

        $xml = (string) $this->request->getPost('xml');
        if ($xml === '' || (! str_contains($xml, '<mxfile') && ! str_contains($xml, '<mxGraphModel'))) {
            return $this->jsonError('Isi diagram tidak valid.');
        }

        $file = $this->request->getFile('gambar');
        if (! $file || ! $file->isValid()) {
            return $this->jsonError('Gambar topologi gagal diupload.');
        }
        if ($file->getSize() > 20 * 1024 * 1024) {
            return $this->jsonError('Gambar topologi terlalu besar (maks. 20 MB).');
        }
        $info = @getimagesize($file->getTempName());
        if (! $info || $info[2] !== IMAGETYPE_PNG) {
            return $this->jsonError('Gambar topologi harus berformat PNG.');
        }

        if (! is_dir(FCPATH . $this->folder)) {
            mkdir(FCPATH . $this->folder, 0775, true);
        }
        $nama = 'topologi_' . $id . '_' . time() . '.png';
        $file->move(FCPATH . $this->folder, $nama);

        $this->hapusGambar($row['gambar']);
        $this->model->update($id, [
            'xml'        => $xml,
            'gambar'     => $this->folder . $nama,
            'updated_by' => $this->username(),
        ]);
        log_activity('update', 'd_topologi', $id, 'Menyimpan gambar topologi: ' . $row['nama']);

        return $this->response->setJSON([
            'status' => 'ok',
            'waktu'  => date('d-m-Y H:i'),
            'csrf'   => csrf_hash(),
        ]);
    }

    // Download gambar PNG (nama file = nama topologi)
    public function download($id)
    {
        $row = $this->model->select('id, nama, gambar')->find((int) $id);
        if (! $row || ! $this->fileLokal($row['gambar']) || ! is_file(FCPATH . $row['gambar'])) {
            return redirect()->to(site_url('DataTopologi'))->with('error', 'Gambar topologi belum ada. Buka editor lalu simpan dulu.');
        }

        $namaFile = trim(preg_replace('/[^A-Za-z0-9 _.-]+/', '', $row['nama'])) ?: 'topologi';

        return $this->response->download(FCPATH . $row['gambar'], null)->setFileName($namaFile . '.png');
    }

    public function delete($id)
    {
        $row = $this->model->select('id, nama, keterangan, gambar')->find((int) $id);
        if (! $row) {
            return redirect()->to(site_url('DataTopologi'))->with('error', 'Topologi tidak ditemukan.');
        }

        $this->hapusGambar($row['gambar']);
        $this->model->delete($row['id']);
        log_activity('delete', 'd_topologi', $row['id'], 'Menghapus topologi: ' . $row['nama'], $row, null);

        return redirect()->to(site_url('DataTopologi'))->with('success', 'Topologi berhasil dihapus.');
    }

    // ---------------------------------------------------------------

    private function infoPost(): ?array
    {
        $nama = trim((string) $this->request->getPost('nama'));
        if ($nama === '' || mb_strlen($nama) > 150) {
            return null;
        }
        $ket = trim((string) $this->request->getPost('keterangan'));

        return ['nama' => $nama, 'keterangan' => $ket === '' ? null : $ket];
    }

    private function username(): ?string
    {
        return session()->get('username') ?: session()->get('nama') ?: null;
    }

    private function fileLokal(?string $path): bool
    {
        return ! empty($path) && str_starts_with($path, $this->folder) && ! str_contains($path, '..');
    }

    private function hapusGambar(?string $path): void
    {
        if ($this->fileLokal($path) && is_file(FCPATH . $path)) {
            unlink(FCPATH . $path);
        }
    }

    private function jsonError(string $pesan, int $status = 422)
    {
        return $this->response->setStatusCode($status)->setJSON(['status' => 'error', 'message' => $pesan, 'csrf' => csrf_hash()]);
    }
}
