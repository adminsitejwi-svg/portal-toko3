<?php

namespace App\Controllers;

use App\Models\MonthlyReportModel;
use App\Models\PelangganNocModel;

// Halaman Monthly Report (menu Report NOC > Monthly Report). Tambah / edit lewat pop up.
// Duration (Hour & Minute) dihitung otomatis dari Start Time s/d Finish Time,
// Duration Using Backup dari Start Time s/d Finish Time backup (keduanya tanggal + jam).
class MonthlyReport extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new MonthlyReportModel();
    }

    // Halaman utama (view MonthlyReport/index.php)
    public function index()
    {
        // saran isian Client: nama pelanggan NOC + client yang pernah dicatat
        $klien = [];
        try {
            $klien = array_column((new PelangganNocModel())->select('nama_pelanggan')->findAll(), 'nama_pelanggan');
        } catch (\Throwable $e) {
            // tabel pelanggan NOC belum ada: saran cukup dari data report
        }

        $report = $this->model->orderBy('start_time', 'DESC')->orderBy('id', 'DESC')->findAll();

        $saran = fn (array $isi) => array_values(array_unique(array_filter(array_map('trim', $isi), 'strlen')));

        return view('MonthlyReport/index', [
            'report'       => $report,
            'saranClient'  => $saran(array_merge($klien, array_column($report, 'client'))),
            'saranLayanan' => $saran(array_column($report, 'jenis_layanan')),
            'saranSolver'  => $saran(array_column($report, 'solver')),
        ]);
    }

    // "2026-11-10T08:30" (input datetime-local) -> "2026-11-10 08:30:00"; kosong / salah -> null
    private function waktu(string $v): ?string
    {
        $v = str_replace('T', ' ', trim($v));
        if ($v === '') {
            return null;
        }
        foreach (['Y-m-d H:i:s', 'Y-m-d H:i'] as $format) {
            $d = \DateTime::createFromFormat('!' . $format, $v);
            if ($d && $d->format($format) === $v) {
                return $d->format('Y-m-d H:i:s');
            }
        }

        return null;
    }

    // selisih dalam menit (null bila salah satu kosong)
    private function menit(?string $mulai, ?string $selesai): ?int
    {
        if (! $mulai || ! $selesai) {
            return null;
        }

        return intdiv(strtotime($selesai) - strtotime($mulai), 60);
    }

    // Ambil isian form + hitung durasi; kembalikan [data, pesan error | null]
    private function collectFields(): array
    {
        $post  = fn (string $k) => trim((string) $this->request->getPost($k));
        $satu  = fn (string $k) => preg_replace('/\s+/', ' ', $post($k));
        $teks  = fn (string $k) => preg_replace("/\r\n?/", "\n", $post($k));

        $f = [
            'client'        => $satu('client'),
            'start_time'    => $this->waktu($post('start_time')),
            'finish_time'   => $this->waktu($post('finish_time')),
            'jenis_layanan' => $satu('jenis_layanan'),
            'problem'       => $teks('problem'),
            'action'        => $teks('action'),
            'note'          => $teks('note') ?: null,
            'solver'        => $satu('solver'),
            'backup_start'  => $this->waktu($post('backup_start')),
            'backup_finish' => $this->waktu($post('backup_finish')),
        ];

        $errors = [];
        $wajib  = ['client' => 'Client', 'jenis_layanan' => 'Jenis Layanan', 'problem' => 'Problem', 'action' => 'Action', 'solver' => 'Solver'];
        foreach ($wajib as $k => $label) {
            if ($f[$k] === '') {
                $errors[] = $label . ' wajib diisi.';
            }
        }
        foreach (['client' => 150, 'jenis_layanan' => 100, 'solver' => 150] as $k => $maks) {
            if (mb_strlen($f[$k]) > $maks) {
                $errors[] = $wajib[$k] . ' maksimal ' . $maks . ' karakter.';
            }
        }

        if (! $f['start_time'] || ! $f['finish_time']) {
            $errors[] = 'Start Time dan Finish Time (tanggal & jam) wajib diisi.';
        } elseif ($f['finish_time'] < $f['start_time']) {
            $errors[] = 'Finish Time tidak boleh sebelum Start Time.';
        }

        // Using Backup opsional, tapi bila diisi harus lengkap mulai & selesai
        $adaBackup = trim((string) $this->request->getPost('backup_start')) !== '' || trim((string) $this->request->getPost('backup_finish')) !== '';
        if ($adaBackup) {
            if (! $f['backup_start'] || ! $f['backup_finish']) {
                $errors[] = 'Using Backup: Start Time dan Finish Time harus diisi keduanya (atau kosongkan keduanya).';
            } elseif ($f['backup_finish'] < $f['backup_start']) {
                $errors[] = 'Using Backup: Finish Time tidak boleh sebelum Start Time.';
            }
        }

        $f['durasi_menit']        = max(0, (int) $this->menit($f['start_time'], $f['finish_time']));
        $f['backup_durasi_menit'] = $this->menit($f['backup_start'], $f['backup_finish']);

        return [$f, $errors ? implode(' ', $errors) : null];
    }

    // Gagal: kembali ke halaman dan buka lagi pop up dengan isian sebelumnya
    private function gagal(string $pesan)
    {
        return redirect()->to(site_url('MonthlyReport'))
            ->withInput()
            ->with('popup', 'monthly')
            ->with('error', $pesan);
    }

    // Simpan data baru (POST MonthlyReport/save)
    public function save()
    {
        [$f, $error] = $this->collectFields();

        if ($error) {
            return $this->gagal($error);
        }

        try {
            $ok = $this->model->insert($f) !== false;
        } catch (\Throwable $e) {
            $ok = false;
        }
        if (! $ok) {
            return $this->gagal('Monthly Report gagal disimpan.');
        }

        return redirect()->to(site_url('MonthlyReport'))->with('success', 'Monthly Report berhasil ditambahkan.');
    }

    // Update data (POST MonthlyReport/update)
    public function update()
    {
        $id = (int) $this->request->getPost('id');

        if (! $id || ! $this->model->find($id)) {
            return redirect()->to(site_url('MonthlyReport'))->with('error', 'Data Monthly Report tidak ditemukan.');
        }

        [$f, $error] = $this->collectFields();

        if ($error) {
            return $this->gagal($error);
        }

        try {
            $ok = $this->model->update($id, $f) !== false;
        } catch (\Throwable $e) {
            $ok = false;
        }
        if (! $ok) {
            return $this->gagal('Monthly Report gagal diperbarui.');
        }

        return redirect()->to(site_url('MonthlyReport'))->with('success', 'Monthly Report berhasil diperbarui.');
    }

    // Hapus data
    public function delete($id)
    {
        $this->model->delete($id);

        return redirect()->to(site_url('MonthlyReport'))->with('success', 'Monthly Report berhasil dihapus.');
    }
}
