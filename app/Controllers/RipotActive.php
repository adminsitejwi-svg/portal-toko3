<?php

namespace App\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
use App\Models\AktivasiRipotModel;
use App\Models\RipotRetailModel;

class RipotActive extends BaseController
{
    public function index(): string
    {
        $model = new AktivasiRipotModel();

        $data['aktivasi'] = $model->orderBy('id', 'DESC')->findAll();

        return view('RipotActive/index', $data);
    }

    public function create()
    {
        return view('RipotActive/FormRipotActive');
    }

    /**
     * Ambil semua field aktivasi_ripot dari request.
     */
    private function getFormData(): array
    {
        return [
            'id_pelanggan' => $this->request->getPost('id_pelanggan'),
            'layanan'      => $this->request->getPost('layanan'),
            'status'       => $this->request->getPost('status'),
        ];
    }

    public function save()
    {
        date_default_timezone_set('Asia/Jakarta');

        $idPelanggan = $this->request->getPost('id_pelanggan');
        if (!preg_match('/^\d+$/', (string) $idPelanggan)) {
            return redirect()->back()->withInput()->with('error', 'ID Pelanggan hanya boleh berisi angka.');
        }

        $model = new AktivasiRipotModel();
        $model->insert($this->getFormData());

        return redirect()->to(site_url('RipotActive'))->with('success', 'Data Aktivasi Retail berhasil ditambahkan.');
    }

    public function delete($id)
    {
        $model = new AktivasiRipotModel();

        if (!$model->find($id)) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $model->delete($id);

        return redirect()->to('/RipotActive')->with('success', 'Data berhasil dihapus.');
    }

    public function update()
    {
        $model = new AktivasiRipotModel();

        $id = $this->request->getPost('id');

        if (!$model->find($id)) {
            return redirect()->to('/RipotActive')->with('error', 'Data tidak ditemukan.');
        }

        $idPelanggan = $this->request->getPost('id_pelanggan');
        if (!preg_match('/^\d+$/', (string) $idPelanggan)) {
            return redirect()->back()->withInput()->with('error', 'ID Pelanggan hanya boleh berisi angka.');
        }

        $model->update($id, $this->getFormData());

        return redirect()->to('/RipotActive')->with('success', 'Data Aktivasi Retail berhasil diupdate.');
    }

    public function show($id)
    {
        $model = new AktivasiRipotModel();

        $data = $model->find($id);

        if (!$data) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'message' => 'Data tidak ditemukan.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data'    => $data
        ]);
    }

    public function edit($id)
    {
        $model = new AktivasiRipotModel();

        $data['aktivasi'] = $model->find($id);

        if (!$data['aktivasi']) {
            return redirect()->to('/RipotActive')->with('error', 'Data tidak ditemukan.');
        }

        return view('RipotActive/EditFormRipotActive', $data);
    }

    /**
     * Sama persis dengan RipotRetail::sendEmail() — supaya kedua tombol
     * "Kirim Email" (di halaman Report NOC maupun Aktivasi Retail)
     * menghasilkan isi email yang identik: Gangguan (repot_noc) + Aktivasi
     * (aktivasi_ripot), digabung dalam satu shift & tanggal yang sama.
     */
    public function sendEmail()
    {
        date_default_timezone_set('Asia/Jakarta');

        $repotModel     = new RipotRetailModel();
        $aktivasiModel  = new AktivasiRipotModel();

        // 1. Ambil record repot_noc terbaru sebagai acuan shift, tanggal & daily.
        // Aktivasi tidak punya kolom shift/daily sendiri, jadi acuannya selalu dari sini.
        $latest = $repotModel
            ->orderBy('start_time', 'DESC')
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->first();

        if (!$latest) {
            return $this->response->setJSON([
                'success'   => false,
                'message'   => 'Belum ada data Report Retail & Corporate di database.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        // Shift diambil apa adanya dari database (angka 1/2/3), bukan label
        // periode waktu — supaya judul email selalu sesuai isi field shift.
        $refShift    = (string) $latest['shift'];
        $refDateTime = $latest['start_time'] ?: $latest['created_at'];
        $refDate     = $refDateTime ? date('Y-m-d', strtotime($refDateTime)) : null;
        $tglLabel    = $refDate ? $this->tglIndo($refDate) : '-';
        $dailyLabel  = $latest['daily'];
        $titleLabel  = $this->emailTitleLabel($dailyLabel);

        // 2a. Baris GANGGUAN (repot_noc): shift + tanggal yang sama dengan acuan
        $gangguanBuilder = $repotModel->where('shift', $refShift);
        if ($refDate) {
            $gangguanBuilder->where("DATE(COALESCE(start_time, created_at)) =", $refDate);
        }
        $gangguanRows = $gangguanBuilder->orderBy('start_time', 'ASC')->orderBy('id', 'ASC')->findAll();

        if (empty($gangguanRows)) {
            return $this->response->setJSON([
                'success'   => false,
                'message'   => 'Tidak ada data Gangguan untuk shift & tanggal terbaru.',
                'csrf_hash' => csrf_hash(),
            ]);
        }

        // 2b. Baris AKTIVASI (aktivasi_ripot): tanggal yang sama dengan acuan (berdasarkan created_at)
        $aktivasiRows = [];
        if ($refDate) {
            $aktivasiRows = $aktivasiModel
                ->where("DATE(created_at) =", $refDate)
                ->orderBy('id', 'ASC')
                ->findAll();
        }

        $narrativeText = $this->buildNarrativeText($gangguanRows, $aktivasiRows, $refShift, $tglLabel, $dailyLabel);

        // 3. Susun HTML tabel Gangguan
        $fmtDT = function ($v) {
            return $v ? date('d-m-Y H:i', strtotime($v)) : '-';
        };
        $fmtDur = function ($h, $m) {
            if ($h === null && $m === null) return '-';
            return (int) $h . 'j ' . (int) $m . 'm';
        };

        $gangguanTableHtml  = '<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;font-family:Arial,sans-serif;font-size:12px;width:100%">';
        $gangguanTableHtml .= '<tr style="background:#04a9f5;color:#fff">
            <th>No</th><th>Shift</th><th>Daily</th><th>Client</th><th>Start Time</th><th>Finish Time</th>
            <th>Durasi</th><th>Jenis Layanan</th><th>Problem</th><th>Action</th><th>Status</th><th>Solver</th><th>Regards</th>
        </tr>';

        $no = 1;
        foreach ($gangguanRows as $r) {
            $gangguanTableHtml .= '<tr>';
            $gangguanTableHtml .= '<td>' . $no++ . '</td>';
            $gangguanTableHtml .= '<td>Shift ' . esc($r['shift']) . '</td>';
            $gangguanTableHtml .= '<td>' . esc($r['daily'] ?: '-') . '</td>';
            $gangguanTableHtml .= '<td>' . esc($r['client']) . '</td>';
            $gangguanTableHtml .= '<td>' . $fmtDT($r['start_time']) . '</td>';
            $gangguanTableHtml .= '<td>' . $fmtDT($r['finish_time']) . '</td>';
            $gangguanTableHtml .= '<td>' . $fmtDur($r['duration_hour'], $r['duration_minute']) . '</td>';
            $gangguanTableHtml .= '<td>' . esc($r['jenis_layanan'] ?: '-') . '</td>';
            $gangguanTableHtml .= '<td>' . esc($r['problem'] ?: '-') . '</td>';
            $gangguanTableHtml .= '<td>' . esc($r['action'] ?: '-') . '</td>';

            $gangguanTableHtml .= '<td>' . esc($r['solver'] ?: '-') . '</td>';
            $gangguanTableHtml .= '<td>' . esc($r['regards'] ?: '-') . '</td>';
            $gangguanTableHtml .= '<td>' . $this->statusBadgeHtml($r['status']) . '</td>';
            $gangguanTableHtml .= '</tr>';
        }
        $gangguanTableHtml .= '</table>';

        // 3b. Susun HTML tabel Aktivasi (kalau ada datanya)
        $aktivasiTableHtml = '';
        if (!empty($aktivasiRows)) {
            $aktivasiTableHtml = '<h4 style="font-family:Arial,sans-serif;margin-top:24px">Data Aktivasi Retail</h4>';
            $aktivasiTableHtml .= '<table border="1" cellpadding="6" cellspacing="0" style="border-collapse:collapse;font-family:Arial,sans-serif;font-size:12px;width:100%">';
            $aktivasiTableHtml .= '<tr style="background:#04a9f5;color:#fff">
                <th>No</th><th>ID Pelanggan</th><th>Layanan</th><th>Status</th>
            </tr>';

            $noAkt = 1;
            foreach ($aktivasiRows as $r) {
                $aktivasiTableHtml .= '<tr>';
                $aktivasiTableHtml .= '<td>' . $noAkt++ . '</td>';
                $aktivasiTableHtml .= '<td>' . esc($r['id_pelanggan']) . '</td>';
                $aktivasiTableHtml .= '<td>' . esc($r['layanan'] ?: '-') . '</td>';
                $aktivasiTableHtml .= '<td>' . esc($r['status'] ?: '-') . '</td>';
                $aktivasiTableHtml .= '</tr>';
            }
            $aktivasiTableHtml .= '</table>';
        }

        // 4. Gabungkan: teks narasi di atas, tabel gangguan, lalu tabel aktivasi (jika ada)
        $html  = '<h3 style="font-family:Arial,sans-serif">' . esc($titleLabel) . ' — Shift ' . esc($refShift) . ' (' . esc($tglLabel) . ')</h3>';
        $html .= '<p style="font-family:Arial,sans-serif;font-size:13px;color:#555">Dikirim otomatis pada ' . date('d-m-Y H:i') . '</p>';
        $html .= '<pre style="font-family:Consolas,\'Courier New\',monospace;font-size:12px;white-space:pre-wrap;line-height:1.5;background:#f8fafc;border:1px solid #e5e7eb;border-radius:8px;padding:16px;margin-bottom:20px">' . $narrativeText . '</pre>';
        $html .= $gangguanTableHtml;
        $html .= $aktivasiTableHtml;

        // 5. Kirim email
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = env('EMAIL_SMTP_USER');
            $mail->Password   = env('EMAIL_SMTP_PASS');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom(env('EMAIL_SMTP_USER'), 'Sistem Operasional JWI Group');
            $mail->addAddress('nocjwi99@gmail.com');

            $mail->isHTML(true);
            $mail->Subject = $titleLabel . ' - Shift ' . $refShift . ' - ' . $tglLabel;
            $mail->Body    = $html;
            $mail->AltBody = strip_tags($narrativeText);

            $mail->send();

            return $this->response->setJSON([
                'success'   => true,
                'message'   => 'Email berhasil dikirim (' . count($gangguanRows) . ' data Gangguan, ' . count($aktivasiRows) . ' data Aktivasi, Shift ' . $refShift . ', ' . $tglLabel . ').',
                'csrf_hash' => csrf_hash(),
            ]);
        } catch (PHPMailerException $e) {
            return $this->response->setJSON([
                'success'   => false,
                'message'   => 'Gagal mengirim email: ' . $mail->ErrorInfo,
                'csrf_hash' => csrf_hash(),
            ]);
        }
    }

    /**
     * Judul utama email: pakai isi kolom "daily" kalau ada, kalau kosong
     * fallback ke "Report NOC". Dipakai untuk Subject email & heading isi
     * email, supaya konsisten dengan judul di bagian narasi teks.
     */
    private function emailTitleLabel(?string $dailyLabel): string
    {
        $dailyLabel = trim((string) $dailyLabel);
        return $dailyLabel !== '' ? $dailyLabel : 'Report NOC';
    }

    /**
     * Badge HTML berwarna untuk status Gangguan (1/0, dari tabel repot_noc)
     * yang dipakai di body email (tabel & narasi) — HIJAU untuk On Progress,
     * MERAH untuk Down. Ini HTML buatan sistem sendiri (bukan dari input
     * pengguna), jadi sengaja TIDAK di-esc() supaya warnanya tampil di email.
     * Catatan: ini BUKAN untuk status Aktivasi (aktivasi_ripot), yang
     * kolomnya bertipe teks bebas dan tidak diubah oleh helper ini.
     */
    private function statusBadgeHtml($status): string
    {
        $isDown = ((string) $status === '1');
        $bg     = $isDown ? '#ffd2dc' : '#e7f8f1';
        $color  = $isDown ? '#ff0000' : '#1aae6f';
        $label  = $isDown ? 'On Progress' : 'Done';

        return '<span style="display:inline-block;background:' . $bg . ';color:' . $color . ';padding:2px 10px;border-radius:6px;font-size:12px;font-weight:600">' . $label . '</span>';
    }

    /**
     * Susun teks narasi gabungan: bagian Gangguan (dari repot_noc, lengkap
     * seluruh field, dengan Status berupa badge warna di baris terakhir
     * setelah Regards) diikuti bagian Aktivasi (dari aktivasi_ripot, ID
     * Pelanggan/Layanan/Status). Masing-masing dipisah Retail (angka murni)
     * / Corporate (ada huruf). Judul memakai kolom "daily" dari repot_noc +
     * nomor shift (angka dari database) & tanggal otomatis.
     *
     * Setiap nilai teks bebas (client, problem, note, dll) di-escape satu-
     * satu saat baris dibentuk — bukan di akhir secara borongan — supaya
     * badge warna Status (HTML terpercaya) tetap bisa disisipkan tanpa
     * ikut ter-escape jadi teks mentah.
     */
    private function buildNarrativeText(array $gangguanRows, array $aktivasiRows, string $refShiftNumber, string $tglLabel, ?string $dailyLabel = null): string
    {
        $fmtDT = function ($v) {
            return $v ? date('d-m-Y H:i', strtotime($v)) : '-';
        };
        $fmtDur = function ($h, $m) {
            if ($h === null && $m === null) return '-';
            return (int) $h . 'j ' . (int) $m . 'm';
        };
        // Baris teks biasa: value di-escape, dan kalau isinya lebih dari
        // satu baris (mis. Note diisi beberapa baris), baris lanjutannya
        // ikut diberi indentasi supaya sejajar dengan baris pertama.
        $field = function (string $label, $value) {
            $prefix = str_pad($label, 20) . ": ";
            $indent = str_repeat(' ', strlen($prefix));
            $lines  = explode("\n", esc((string) $value));
            return $prefix . implode("\n" . $indent, $lines);
        };
        // Baris dengan HTML terpercaya (badge warna Status) — TIDAK di-escape
        $fieldHtml = function (string $label, string $htmlValue) {
            return str_pad($label, 20) . ": " . $htmlValue;
        };

        // ===== Bagian Gangguan (repot_noc) =====
        $groups = [];
        foreach ($gangguanRows as $r) {
            $clientKey = trim((string) $r['client']);
            if ($clientKey === '') {
                $clientKey = '(Tanpa Nama Client)';
            }
            $groups[$clientKey][] = $r;
        }

        $retailBlocks    = [];
        $corporateBlocks = [];

        foreach ($groups as $clientName => $clientRows) {
            $isRetail = (bool) preg_match('/^\d+$/', $clientName);

            $shiftBlocks = [];
            foreach ($clientRows as $r) {
                $lines   = [];
                $lines[] = $field('Client', $r['client'] ?: '-');
                $lines[] = $field('Start Time', $fmtDT($r['start_time']));
                $lines[] = $field('Finish Time', $fmtDT($r['finish_time']));
                $lines[] = $field('Durasi', $fmtDur($r['duration_hour'], $r['duration_minute']));
                $lines[] = $field('Jenis Layanan', $r['jenis_layanan'] ?: '-');
                $lines[] = $field('Problem', $r['problem'] ?: '-');
                $lines[] = $field('Action', $r['action'] ?: '-');
                $lines[] = $field('Note', $r['note'] ?: '-');
                $lines[] = $field('Solver', $r['solver'] ?: '-');
                $lines[] = $field('Backup Start Time', $fmtDT($r['backup_start_time']));
                $lines[] = $field('Backup Finish Time', $fmtDT($r['backup_finish_time']));
                $lines[] = $field('Backup Durasi', $fmtDur($r['backup_duration_hour'], $r['backup_duration_minute']));
                $lines[] = $field('Regards', $r['regards'] ?: '-');
                $lines[] = $fieldHtml('Status', $this->statusBadgeHtml($r['status']));

                $shiftBlocks[] = implode("\n", $lines);
            }

            $block = implode("\n\n", $shiftBlocks);

            if ($isRetail) {
                $retailBlocks[] = $block;
            } else {
                $corporateBlocks[] = $block;
            }
        }

        // ===== Bagian Aktivasi (aktivasi_ripot) =====
        $aktRetailBlocks    = [];
        $aktCorporateBlocks = [];

        foreach ($aktivasiRows as $r) {
            $idPelanggan = trim((string) $r['id_pelanggan']);
            $isRetail    = (bool) preg_match('/^\d+$/', $idPelanggan);

            $lines   = [];
            $lines[] = $field('ID Pelanggan', $idPelanggan ?: '-');
            $lines[] = $field('Layanan', $r['layanan'] ?: '-');
            $lines[] = $field('Status', $r['status'] ?: '-');

            $block = implode("\n", $lines);

            if ($isRetail) {
                $aktRetailBlocks[] = $block;
            } else {
                $aktCorporateBlocks[] = $block;
            }
        }

        // ===== Judul =====
        $titleLabel = $this->emailTitleLabel($dailyLabel);
        $out = esc($titleLabel) . " — Shift {$refShiftNumber} ({$tglLabel})\n\n";

        if (!empty($retailBlocks)) {
            $out .= "=============== Gangguan Pelanggan Retail =====================\n";
            $out .= implode("\n\n", $retailBlocks) . "\n\n";
        }

        if (!empty($corporateBlocks)) {
            $out .= "============= Gangguan Pelanggan Corporate =====================\n";
            $out .= implode("\n\n", $corporateBlocks) . "\n\n";
        }

        if (!empty($aktRetailBlocks)) {
            $out .= "=============== Aktivasi Pelanggan Retail =====================\n";
            $out .= implode("\n\n", $aktRetailBlocks) . "\n\n";
        }

        if (!empty($aktCorporateBlocks)) {
            $out .= "============= Aktivasi Pelanggan Corporate =====================\n";
            $out .= implode("\n\n", $aktCorporateBlocks) . "\n";
        }

        return rtrim($out);
    }

    /**
     * Format tanggal "Y-m-d" jadi "d Bulan yyyy" berbahasa Indonesia.
     */
    private function tglIndo(string $ymd): string
    {
        $bulan = [
            1 => 'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];
        $ts = strtotime($ymd);
        return date('d', $ts) . ' ' . $bulan[(int) date('n', $ts)] . ' ' . date('Y', $ts);
    }
}
