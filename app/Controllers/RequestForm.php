<?php

namespace App\Controllers;

use App\Libraries\HakAkses as Akses;
use App\Libraries\RequestForm as RF;
use Config\HakAkses as HakAksesConfig;

/**
 * Halaman Informasi > Request (di bawah Hak Akses).
 *
 * Administrator  : membuat request berbentuk form (seperti Google Form), memilih akun yang
 *                  boleh mengisi + halaman sidebar terkait, melihat & me-review jawaban.
 *                  Semua URL "Request/kelola/..." khusus Administrator (Config\HakAkses::$khususAdmin).
 * Akun lain      : hanya melihat & mengisi request yang ditujukan ke akunnya.
 *
 * Tabel: database/d_request.sql
 */
class RequestForm extends BaseController
{
    private HakAksesConfig $cfg;

    public function __construct()
    {
        $this->cfg = config(HakAksesConfig::class);
        helper(['form', 'filesystem']);
    }

    private function db()
    {
        return \Config\Database::connect();
    }

    private function userId(): int
    {
        return (int) session()->get('user_id');
    }

    private function isAdmin(): bool
    {
        return Akses::instance()->isAdmin();
    }

    private function folderUpload(int $requestId): string
    {
        return WRITEPATH . 'uploads/request/' . $requestId . '/';
    }

    /* ======================= DAFTAR ======================= */

    public function index()
    {
        $data = [
            'judulHalaman' => 'Request',
            'tersedia'     => RF::tersedia(),
            'isAdmin'      => $this->isAdmin(),
            'saya'         => [],
            'kelola'       => [],
        ];

        if (!$data['tersedia']) {
            return view('RequestForm/index', $data);
        }

        $db  = $this->db();
        $uid = $this->userId();

        // request yang ditujukan ke akun ini
        $data['saya'] = $db->table('d_request r')
            ->select('r.id, r.judul, r.deskripsi, r.halaman, r.batas_waktu, r.status, r.boleh_ubah,
                      j.id AS jawaban_id, j.status AS status_jawaban, j.dikirim_at, j.catatan_admin')
            ->join('d_request_target t', 't.request_id = r.id AND t.user_id = ' . $uid, 'inner')
            ->join('d_request_jawaban j', 'j.request_id = r.id AND j.user_id = ' . $uid, 'left')
            ->orderBy('r.created_at', 'DESC')
            ->get()->getResultArray();

        if ($data['isAdmin']) {
            $target = [];
            foreach ($db->table('d_request_target')->select('request_id, COUNT(*) n')->groupBy('request_id')->get()->getResultArray() as $r) {
                $target[(int) $r['request_id']] = (int) $r['n'];
            }
            // hanya jawaban dari akun yang masih menjadi target
            $terisi = [];
            $rows = $db->table('d_request_jawaban j')
                ->select('j.request_id, j.status, COUNT(*) n')
                ->join('d_request_target t', 't.request_id = j.request_id AND t.user_id = j.user_id', 'inner')
                ->groupBy('j.request_id, j.status')->get()->getResultArray();
            foreach ($rows as $r) {
                $terisi[(int) $r['request_id']][$r['status']] = (int) $r['n'];
            }

            $kelola = $db->table('d_request r')
                ->select('r.*, l.username AS pembuat')
                ->join('login l', 'l.id = r.created_by', 'left')
                ->orderBy('r.created_at', 'DESC')->get()->getResultArray();

            foreach ($kelola as &$r) {
                $id               = (int) $r['id'];
                $r['jml_target']  = $target[$id] ?? 0;
                $r['jml_status']  = $terisi[$id] ?? [];
                $r['jml_terisi']  = array_sum($r['jml_status']);
                $r['jml_isian']   = count(array_filter(RF::fields($r['fields']), static fn ($f) => ($f['tipe'] ?? '') !== 'bagian'));
            }
            unset($r);

            $data['kelola'] = $kelola;
        }

        return view('RequestForm/index', $data);
    }

    /* ======================= ADMIN: BUAT / UBAH ======================= */

    public function buat()
    {
        return $this->formBuilder(null);
    }

    public function ubah($id)
    {
        $req = $this->ambil((int) $id);
        if (!$req) {
            return redirect()->to(site_url('Request'))->with('error', 'Request tidak ditemukan.');
        }

        return $this->formBuilder($req);
    }

    private function formBuilder(?array $req)
    {
        if (!RF::tersedia()) {
            return $this->belumSiap();
        }

        $db = $this->db();

        $target = [];
        $jmlJawaban = 0;
        if ($req) {
            $target = array_map('intval', array_column(
                $db->table('d_request_target')->select('user_id')->where('request_id', $req['id'])->get()->getResultArray(),
                'user_id'
            ));
            $jmlJawaban = $db->table('d_request_jawaban')->where('request_id', $req['id'])->countAllResults();
        }

        return view('RequestForm/builder', [
            'judulHalaman' => $req ? 'Ubah Request' : 'Buat Request',
            'req'          => $req,
            'fields'       => $req ? RF::fields($req['fields']) : [],
            'target'       => $target,
            'jmlJawaban'   => $jmlJawaban,
            'grup'         => $this->cfg->grup,
            'akun'         => $this->daftarAkun(),
            'roles'        => Akses::instance()->tersedia()
                ? $db->table('m_role')->orderBy('is_admin', 'DESC')->orderBy('nama', 'ASC')->get()->getResultArray()
                : [],
        ]);
    }

    /**
     * Semua akun + role + halaman yang boleh dibuka (untuk tanda "tidak punya akses" di builder).
     *
     * @return list<array{id:int, username:string, role:string, semua:bool, halaman:list<string>}>
     */
    private function daftarAkun(): array
    {
        $db = $this->db();

        if (!Akses::instance()->tersedia()) {
            return array_map(static fn ($a) => [
                'id' => (int) $a['id'], 'username' => $a['username'], 'role_id' => 0, 'role' => '-', 'semua' => true, 'halaman' => [],
            ], $db->table('login')->select('id, username')->orderBy('username', 'ASC')->get()->getResultArray());
        }

        $halamanRole = [];
        foreach ($db->table('m_role_akses')->get()->getResultArray() as $r) {
            $halamanRole[(int) $r['role_id']][] = strtolower($r['halaman']);
        }
        $halamanUser = [];
        foreach ($db->table('m_user_akses')->get()->getResultArray() as $r) {
            $halamanUser[(int) $r['user_id']][] = strtolower($r['halaman']);
        }

        $akun = $db->table('login')
            ->select('login.id, login.username, login.role_id, login.akses_khusus, m_role.nama AS role_nama, m_role.is_admin')
            ->join('m_role', 'm_role.id = login.role_id', 'left')
            ->orderBy('login.username', 'ASC')->get()->getResultArray();

        return array_map(static function ($a) use ($halamanRole, $halamanUser) {
            $admin = (int) ($a['is_admin'] ?? 0) === 1;

            return [
                'id'       => (int) $a['id'],
                'username' => $a['username'],
                'role_id'  => (int) $a['role_id'],
                'role'     => $a['role_nama'] ?: 'Tanpa Role',
                'semua'    => $admin,
                'halaman'  => $admin ? [] : ((int) $a['akses_khusus'] === 1
                    ? ($halamanUser[(int) $a['id']] ?? [])
                    : ($halamanRole[(int) $a['role_id']] ?? [])),
            ];
        }, $akun);
    }

    public function simpan()
    {
        if (!RF::tersedia()) {
            return $this->belumSiap();
        }

        $db   = $this->db();
        $id   = (int) $this->request->getPost('id');
        $lama = $id ? $this->ambil($id) : null;

        if ($id && !$lama) {
            return redirect()->to(site_url('Request'))->with('error', 'Request tidak ditemukan.');
        }

        $judul     = trim((string) $this->request->getPost('judul'));
        $deskripsi = trim((string) $this->request->getPost('deskripsi'));
        $halaman   = trim((string) $this->request->getPost('halaman'));
        $batas     = trim((string) $this->request->getPost('batas_waktu'));
        $status    = $this->request->getPost('status') === 'ditutup' ? 'ditutup' : 'aktif';
        $bolehUbah = $this->request->getPost('boleh_ubah') ? 1 : 0;

        $errors = [];

        if ($judul === '') {
            $errors[] = 'Judul request wajib diisi.';
        } elseif (mb_strlen($judul) > 200) {
            $errors[] = 'Judul maksimal 200 karakter.';
        }

        // halaman harus terdaftar di sidebar (Config\HakAkses), kosong = umum
        if ($halaman !== '') {
            $peta = [];
            foreach (array_keys($this->cfg->semuaHalaman()) as $k) {
                $peta[strtolower($k)] = $k;
            }
            $halaman = $peta[strtolower($halaman)] ?? null;
            if ($halaman === null) {
                $errors[] = 'Halaman yang dipilih tidak terdaftar di sidebar.';
            }
        }

        $batasDb = null;
        if ($batas !== '') {
            $t = strtotime($batas);
            if ($t === false) {
                $errors[] = 'Format batas waktu tidak valid.';
            } else {
                $batasDb = date('Y-m-d H:i:s', $t);
            }
        }

        [$fields, $errField] = $this->rapikanFields((string) $this->request->getPost('fields_json'));
        $errors = array_merge($errors, $errField);

        // akun pengisi
        $semuaId = array_map('intval', array_column($db->table('login')->select('id')->get()->getResultArray(), 'id'));
        $target  = array_values(array_unique(array_intersect(
            array_map('intval', (array) ($this->request->getPost('target') ?? [])),
            $semuaId
        )));
        if (!$target) {
            $errors[] = 'Pilih minimal satu akun yang bisa mengisi request.';
        }

        if ($errors) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        $baris = [
            'judul'       => $judul,
            'deskripsi'   => $deskripsi !== '' ? $deskripsi : null,
            'halaman'     => $halaman ?: null,
            'batas_waktu' => $batasDb,
            'status'      => $status,
            'boleh_ubah'  => $bolehUbah,
            'fields'      => json_encode($fields, JSON_UNESCAPED_UNICODE),
        ];

        $db->transStart();

        if ($lama) {
            $baris['updated_at'] = date('Y-m-d H:i:s');
            $db->table('d_request')->where('id', $id)->update($baris);
        } else {
            $baris['created_by'] = $this->userId() ?: null;
            $baris['created_at'] = date('Y-m-d H:i:s');
            $db->table('d_request')->insert($baris);
            $id = (int) $db->insertID();
        }

        $db->table('d_request_target')->where('request_id', $id)->delete();
        $db->table('d_request_target')->insertBatch(array_map(
            static fn ($u) => ['request_id' => $id, 'user_id' => $u],
            $target
        ));

        $db->transComplete();

        if (!$db->transStatus()) {
            return redirect()->back()->withInput()->with('errors', ['Gagal menyimpan request, coba lagi.']);
        }

        return redirect()->to(site_url('Request') . '#kelola')
            ->with('success', 'Request "' . $judul . '" berhasil disimpan.');
    }

    /**
     * Bersihkan JSON isian dari builder.
     *
     * @return array{0: list<array>, 1: list<string>}
     */
    private function rapikanFields(string $json): array
    {
        $data   = json_decode($json, true);
        $out    = [];
        $errors = [];
        $idDipakai = [];
        $adaIsian  = false;

        if (!is_array($data)) {
            return [[], ['Susunan isian form tidak terbaca, muat ulang halaman lalu coba lagi.']];
        }

        foreach (array_values($data) as $i => $f) {
            if (!is_array($f)) {
                continue;
            }
            $no   = $i + 1;
            $tipe = (string) ($f['tipe'] ?? '');
            if (!isset(RF::TIPE[$tipe])) {
                $errors[] = 'Isian #' . $no . ': jenis isian tidak dikenal.';
                continue;
            }

            $fid = (string) ($f['id'] ?? '');
            if (!preg_match('/^[a-z0-9]{4,24}$/', $fid) || isset($idDipakai[$fid])) {
                $fid = 'f' . bin2hex(random_bytes(5));
            }
            $idDipakai[$fid] = true;

            $label = trim((string) ($f['label'] ?? ''));
            if ($label === '') {
                $errors[] = 'Isian #' . $no . ': pertanyaan / judul wajib diisi.';
            }

            $baris = [
                'id'         => $fid,
                'tipe'       => $tipe,
                'label'      => mb_substr($label, 0, 500),
                'keterangan' => mb_substr(trim((string) ($f['keterangan'] ?? '')), 0, 2000),
                'wajib'      => $tipe !== 'bagian' && !empty($f['wajib']),
            ];

            if (in_array($tipe, RF::PAKAI_OPSI, true)) {
                $opsi = [];
                foreach ((array) ($f['opsi'] ?? []) as $o) {
                    $o = mb_substr(trim((string) $o), 0, 300);
                    if ($o !== '' && !in_array($o, $opsi, true)) {
                        $opsi[] = $o;
                    }
                }
                if (!$opsi) {
                    $errors[] = 'Isian "' . ($label ?: '#' . $no) . '": tambahkan minimal satu opsi.';
                }
                $baris['opsi'] = $opsi;
            }

            if ($tipe === 'skala') {
                $min = (int) ($f['min'] ?? 1) === 0 ? 0 : 1;
                $max = max(2, min(10, (int) ($f['max'] ?? 5)));
                $baris += [
                    'min'       => $min,
                    'max'       => $max,
                    'label_min' => mb_substr(trim((string) ($f['label_min'] ?? '')), 0, 100),
                    'label_max' => mb_substr(trim((string) ($f['label_max'] ?? '')), 0, 100),
                ];
            }

            if ($tipe !== 'bagian') {
                $adaIsian = true;
            }
            $out[] = $baris;
        }

        if (!$adaIsian) {
            $errors[] = 'Tambahkan minimal satu pertanyaan di form.';
        }

        return [$out, $errors];
    }

    public function hapus($id)
    {
        if (!RF::tersedia()) {
            return $this->belumSiap();
        }

        $req = $this->ambil((int) $id);
        if (!$req) {
            return redirect()->to(site_url('Request') . '#kelola')->with('error', 'Request tidak ditemukan.');
        }

        $db = $this->db();
        $db->transStart();
        $db->table('d_request_jawaban')->where('request_id', $req['id'])->delete();
        $db->table('d_request_target')->where('request_id', $req['id'])->delete();
        $db->table('d_request')->where('id', $req['id'])->delete();
        $db->transComplete();

        $folder = $this->folderUpload((int) $req['id']);
        if (is_dir($folder)) {
            delete_files($folder, true);
            @rmdir($folder);
        }

        return redirect()->to(site_url('Request') . '#kelola')
            ->with('success', 'Request "' . $req['judul'] . '" berhasil dihapus.');
    }

    public function status($id)
    {
        if (!RF::tersedia()) {
            return $this->belumSiap();
        }

        $req = $this->ambil((int) $id);
        if (!$req) {
            return redirect()->to(site_url('Request') . '#kelola')->with('error', 'Request tidak ditemukan.');
        }

        $baru = $req['status'] === 'aktif' ? 'ditutup' : 'aktif';
        $this->db()->table('d_request')->where('id', $req['id'])->update([
            'status' => $baru, 'updated_at' => date('Y-m-d H:i:s'),
        ]);

        return redirect()->to(site_url('Request') . '#kelola')
            ->with('success', 'Request "' . $req['judul'] . '" ' . ($baru === 'aktif' ? 'dibuka kembali.' : 'ditutup.'));
    }

    /* ======================= ADMIN: JAWABAN ======================= */

    public function jawaban($id)
    {
        if (!RF::tersedia()) {
            return $this->belumSiap();
        }

        $req = $this->ambil((int) $id);
        if (!$req) {
            return redirect()->to(site_url('Request') . '#kelola')->with('error', 'Request tidak ditemukan.');
        }

        $db = $this->db();

        $target = $db->table('d_request_target t')
            ->select('t.user_id, l.username')
            ->join('login l', 'l.id = t.user_id', 'left')
            ->where('t.request_id', $req['id'])
            ->orderBy('l.username', 'ASC')->get()->getResultArray();

        $jawaban = $db->table('d_request_jawaban j')
            ->select('j.*, l.username, r.username AS reviewer')
            ->join('login l', 'l.id = j.user_id', 'left')
            ->join('login r', 'r.id = j.direview_oleh', 'left')
            ->where('j.request_id', $req['id'])
            ->orderBy('j.dikirim_at', 'DESC')->get()->getResultArray();

        $sudah = array_map('intval', array_column($jawaban, 'user_id'));
        $idTarget = array_map('intval', array_column($target, 'user_id'));

        foreach ($jawaban as &$j) {
            $j['isi']      = json_decode((string) $j['jawaban'], true) ?: [];
            $j['target']   = in_array((int) $j['user_id'], $idTarget, true);
        }
        unset($j);

        return view('RequestForm/jawaban', [
            'judulHalaman' => 'Jawaban Request',
            'req'          => $req,
            'fields'       => RF::fields($req['fields']),
            'jawaban'      => $jawaban,
            'belum'        => array_values(array_filter($target, static fn ($t) => !in_array((int) $t['user_id'], $sudah, true))),
            'jmlTarget'    => count($target),
        ]);
    }

    public function review()
    {
        if (!RF::tersedia()) {
            return $this->belumSiap();
        }

        $db      = $this->db();
        $jid     = (int) $this->request->getPost('jawaban_id');
        $status  = (string) $this->request->getPost('status');
        $catatan = trim((string) $this->request->getPost('catatan'));

        $j = $db->table('d_request_jawaban')->where('id', $jid)->get()->getRowArray();
        if (!$j || !in_array($status, ['terkirim', 'disetujui', 'revisi'], true)) {
            return redirect()->back()->with('error', 'Jawaban tidak ditemukan.');
        }
        if ($status === 'revisi' && $catatan === '') {
            return redirect()->back()->with('error', 'Tulis catatan revisi supaya pengisi tahu apa yang harus diperbaiki.');
        }

        $db->table('d_request_jawaban')->where('id', $jid)->update([
            'status'        => $status,
            'catatan_admin' => $catatan !== '' ? $catatan : null,
            'direview_at'   => date('Y-m-d H:i:s'),
            'direview_oleh' => $this->userId() ?: null,
        ]);

        $pesan = ['disetujui' => 'disetujui', 'revisi' => 'dikembalikan untuk revisi', 'terkirim' => 'dikembalikan ke status terkirim'][$status];

        return redirect()->to(site_url('Request/kelola/jawaban/' . $j['request_id']))
            ->with('success', 'Jawaban ' . $pesan . '.');
    }

    /* ======================= PENGISI ======================= */

    public function isi($id)
    {
        if (!RF::tersedia()) {
            return $this->belumSiap();
        }

        $req = $this->ambil((int) $id);
        $uid = $this->userId();

        if (!$req) {
            return redirect()->to(site_url('Request'))->with('error', 'Request tidak ditemukan.');
        }

        $target = $this->isTarget((int) $req['id'], $uid);
        if (!$target && !$this->isAdmin()) {
            return redirect()->to(site_url('Request'))->with('error', 'Request ini tidak ditujukan untuk akun Anda.');
        }

        $jawaban = $this->db()->table('d_request_jawaban')
            ->where('request_id', $req['id'])->where('user_id', $uid)->get()->getRowArray();

        [$bisaKirim, $alasan] = $this->bisaKirim($req, $jawaban, $target);

        $nilai = $jawaban ? (json_decode((string) $jawaban['jawaban'], true) ?: []) : [];
        $old   = old('f', null, false);
        if (is_array($old)) {
            // isi ulang input teks/pilihan setelah gagal validasi (file tetap memakai yang tersimpan)
            foreach ($old as $k => $v) {
                $nilai[$k] = $v;
            }
        }

        return view('RequestForm/isi', [
            'judulHalaman' => $req['judul'],
            'req'          => $req,
            'fields'       => RF::fields($req['fields']),
            'jawaban'      => $jawaban,
            'nilai'        => $nilai,
            'bisaKirim'    => $bisaKirim,
            'alasan'       => $alasan,
            'pratinjau'    => !$target,
        ]);
    }

    public function kirim($id)
    {
        if (!RF::tersedia()) {
            return $this->belumSiap();
        }

        $db  = $this->db();
        $req = $this->ambil((int) $id);
        $uid = $this->userId();

        if (!$req || !$this->isTarget((int) $req['id'], $uid)) {
            return redirect()->to(site_url('Request'))->with('error', 'Request ini tidak ditujukan untuk akun Anda.');
        }

        $jawaban = $db->table('d_request_jawaban')
            ->where('request_id', $req['id'])->where('user_id', $uid)->get()->getRowArray();

        [$bisaKirim, $alasan] = $this->bisaKirim($req, $jawaban, true);
        if (!$bisaKirim) {
            return redirect()->to(site_url('Request/isi/' . $req['id']))->with('error', $alasan);
        }

        $fields = RF::fields($req['fields']);
        $lama   = $jawaban ? (json_decode((string) $jawaban['jawaban'], true) ?: []) : [];
        $post   = (array) ($this->request->getPost('f') ?? []);
        $hapusFile = array_map('strval', (array) ($this->request->getPost('hapus_file') ?? []));

        $hasil    = [];
        $errors   = [];
        $fileBaru = [];   // [fid => UploadedFile]

        foreach ($fields as $f) {
            $fid  = $f['id'];
            $tipe = $f['tipe'];
            $nama = $f['label'];

            if ($tipe === 'bagian') {
                continue;
            }

            if ($tipe === 'file') {
                $file = $this->request->getFile('file_' . $fid);
                if ($file && $file->getError() !== UPLOAD_ERR_NO_FILE) {
                    if (!$file->isValid()) {
                        $errors[$fid] = 'Upload gagal: ' . $file->getErrorString();
                    } elseif (!in_array(strtolower($file->getClientExtension()), RF::EKSTENSI, true)) {
                        $errors[$fid] = 'Jenis file tidak diizinkan. Boleh: ' . implode(', ', RF::EKSTENSI) . '.';
                    } elseif ($file->getSizeByUnit('kb') > RF::MAKS_KB) {
                        $errors[$fid] = 'Ukuran file maksimal ' . (RF::MAKS_KB / 1024) . ' MB.';
                    } else {
                        $fileBaru[$fid] = $file;
                    }
                    $hasil[$fid] = $lama[$fid] ?? null; // sementara, diganti setelah file dipindah
                } elseif (in_array($fid, $hapusFile, true)) {
                    $hasil[$fid] = null;
                } else {
                    $hasil[$fid] = $lama[$fid] ?? null;
                }

                if (!empty($f['wajib']) && empty($hasil[$fid]) && !isset($fileBaru[$fid]) && !isset($errors[$fid])) {
                    $errors[$fid] = 'Wajib upload file.';
                }
                continue;
            }

            $v = $post[$fid] ?? null;

            if ($tipe === 'centang') {
                $v = array_values(array_intersect($f['opsi'] ?? [], array_map('strval', (array) ($v ?? []))));
                if (!empty($f['wajib']) && !$v) {
                    $errors[$fid] = 'Pilih minimal satu.';
                }
                $hasil[$fid] = $v;
                continue;
            }

            $v = is_array($v) ? '' : trim((string) $v);

            if ($v === '') {
                if (!empty($f['wajib'])) {
                    $errors[$fid] = 'Pertanyaan ini wajib diisi.';
                }
                $hasil[$fid] = '';
                continue;
            }

            switch ($tipe) {
                case 'teks':
                    if (mb_strlen($v) > 1000) {
                        $errors[$fid] = 'Maksimal 1000 karakter.';
                    }
                    break;
                case 'paragraf':
                    if (mb_strlen($v) > 10000) {
                        $errors[$fid] = 'Maksimal 10000 karakter.';
                    }
                    break;
                case 'angka':
                    $v = str_replace(',', '.', $v);
                    if (!is_numeric($v)) {
                        $errors[$fid] = 'Isi dengan angka.';
                    }
                    break;
                case 'email':
                    if (!filter_var($v, FILTER_VALIDATE_EMAIL)) {
                        $errors[$fid] = 'Format email tidak valid.';
                    }
                    break;
                case 'tanggal':
                    $d = \DateTime::createFromFormat('Y-m-d', $v);
                    if (!$d || $d->format('Y-m-d') !== $v) {
                        $errors[$fid] = 'Format tanggal tidak valid.';
                    }
                    break;
                case 'waktu':
                    if (!preg_match('/^([01]\d|2[0-3]):[0-5]\d$/', substr($v, 0, 5))) {
                        $errors[$fid] = 'Format jam tidak valid.';
                    }
                    $v = substr($v, 0, 5);
                    break;
                case 'pilihan':
                case 'dropdown':
                    if (!in_array($v, $f['opsi'] ?? [], true)) {
                        $errors[$fid] = 'Pilihan tidak valid.';
                    }
                    break;
                case 'skala':
                    if (!ctype_digit($v) || (int) $v < (int) ($f['min'] ?? 1) || (int) $v > (int) ($f['max'] ?? 5)) {
                        $errors[$fid] = 'Pilih nilai pada skala.';
                    }
                    $v = (int) $v;
                    break;
            }

            $hasil[$fid] = $v;
        }

        if ($errors) {
            return redirect()->to(site_url('Request/isi/' . $req['id']))->withInput()
                ->with('errors_field', $errors)
                ->with('error', 'Masih ada ' . count($errors) . ' isian yang perlu diperbaiki.');
        }

        // simpan file baru, hapus file lama yang diganti / dihapus
        $folder = $this->folderUpload((int) $req['id']);
        foreach ($fields as $f) {
            if ($f['tipe'] !== 'file') {
                continue;
            }
            $fid     = $f['id'];
            $fileLama = $lama[$fid] ?? null;

            if (isset($fileBaru[$fid])) {
                if (!is_dir($folder)) {
                    mkdir($folder, 0775, true);
                }
                $file    = $fileBaru[$fid];
                $namaAsli = $file->getClientName();
                $namaSimpan = $uid . '_' . $fid . '_' . bin2hex(random_bytes(6)) . '.' . strtolower($file->getClientExtension());
                $ukuran = $file->getSize();
                $file->move($folder, $namaSimpan);
                $hasil[$fid] = ['nama' => $namaAsli, 'file' => $namaSimpan, 'ukuran' => $ukuran];
            }

            if (is_array($fileLama) && !empty($fileLama['file']) && ($hasil[$fid]['file'] ?? null) !== $fileLama['file']) {
                @unlink($folder . basename($fileLama['file']));
            }
        }

        $sekarang = date('Y-m-d H:i:s');
        $baris = [
            'jawaban' => json_encode($hasil, JSON_UNESCAPED_UNICODE),
            'status'  => 'terkirim',
        ];

        if ($jawaban) {
            $baris['diubah_at'] = $sekarang;
            $db->table('d_request_jawaban')->where('id', $jawaban['id'])->update($baris);
        } else {
            $baris += ['request_id' => $req['id'], 'user_id' => $uid, 'dikirim_at' => $sekarang];
            $db->table('d_request_jawaban')->insert($baris);
        }

        return redirect()->to(site_url('Request'))
            ->with('success', 'Jawaban untuk "' . $req['judul'] . '" berhasil dikirim.');
    }

    /** Unduh file jawaban (pemilik jawaban atau Administrator) */
    public function file($jawabanId, $fid)
    {
        if (!RF::tersedia()) {
            return $this->belumSiap();
        }

        $j = $this->db()->table('d_request_jawaban')->where('id', (int) $jawabanId)->get()->getRowArray();
        if (!$j || ((int) $j['user_id'] !== $this->userId() && !$this->isAdmin())) {
            return redirect()->to(site_url('Request'))->with('error', 'File tidak ditemukan.');
        }

        $isi  = json_decode((string) $j['jawaban'], true) ?: [];
        $info = $isi[(string) $fid] ?? null;
        $path = is_array($info) && !empty($info['file'])
            ? $this->folderUpload((int) $j['request_id']) . basename($info['file'])
            : null;

        if (!$path || !is_file($path)) {
            return redirect()->back()->with('error', 'File tidak ditemukan di server.');
        }

        $unduh = $this->request->getGet('unduh') !== null;
        $mime  = mime_content_type($path) ?: 'application/octet-stream';
        $aman  = in_array($mime, ['application/pdf', 'image/jpeg', 'image/png', 'image/gif', 'image/webp'], true);

        if (!$unduh && $aman) {
            return $this->response
                ->setHeader('Content-Type', $mime)
                ->setHeader('Content-Disposition', 'inline; filename="' . str_replace('"', '', $info['nama'] ?? 'file') . '"')
                ->setHeader('X-Content-Type-Options', 'nosniff')
                ->setBody(file_get_contents($path));
        }

        return $this->response->download($path, null)->setFileName($info['nama'] ?? basename($path));
    }

    /* ======================= helper ======================= */

    private function ambil(int $id): ?array
    {
        if (!$id) {
            return null;
        }

        return $this->db()->table('d_request')->where('id', $id)->get()->getRowArray() ?: null;
    }

    private function isTarget(int $requestId, int $userId): bool
    {
        return $userId > 0 && $this->db()->table('d_request_target')
            ->where('request_id', $requestId)->where('user_id', $userId)->countAllResults() > 0;
    }

    /** @return array{0: bool, 1: string} */
    private function bisaKirim(array $req, ?array $jawaban, bool $target): array
    {
        if (!$target) {
            return [false, 'Pratinjau: akun Anda tidak termasuk pengisi request ini.'];
        }
        if ($req['status'] !== 'aktif') {
            return [false, 'Request ini sudah ditutup oleh Administrator.'];
        }
        if ($req['batas_waktu'] && strtotime($req['batas_waktu']) < time()) {
            return [false, 'Batas waktu pengisian sudah lewat.'];
        }
        if (!$jawaban || $jawaban['status'] === 'revisi') {
            return [true, ''];
        }
        if ($jawaban['status'] === 'disetujui') {
            return [false, 'Jawaban Anda sudah disetujui Administrator.'];
        }
        if ((int) $req['boleh_ubah'] === 1) {
            return [true, ''];
        }

        return [false, 'Jawaban sudah dikirim. Request ini tidak mengizinkan perubahan jawaban.'];
    }

    private function belumSiap()
    {
        return redirect()->to(site_url('Request'))
            ->with('error', 'Tabel request belum ada. Jalankan database/d_request.sql dulu.');
    }
}
