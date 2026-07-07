<?php

use App\Models\ActivityLogModel;

/**
 * =====================================================================
 *  HELPER HISTORY / AUDIT LOG
 *  Memuat: log_activity(), activity_summarize(), activity_label_action()
 *  Load dengan: helper('activity');  (otomatis ditemukan dari app/Helpers)
 * =====================================================================
 */

if (! function_exists('log_activity')) {
    /**
     * Menyimpan satu baris history ke tabel activity_logs.
     *
     * @param string      $action      'create' | 'update' | 'delete'
     * @param string      $table       Nama tabel yang terdampak (mis. 'md_vendor')
     * @param mixed       $recordId    Primary key baris terdampak
     * @param string|null $description Keterangan; kalau null akan dibuat otomatis
     * @param array|null  $oldData     Snapshot data lama
     * @param array|null  $newData     Snapshot data baru
     */
    function log_activity(
        string $action,
        string $table,
        $recordId = null,
        ?string $description = null,
        ?array $oldData = null,
        ?array $newData = null
    ): void {
        // Hindari mencatat aktivitas pada tabel log itu sendiri (anti-loop).
        if ($table === 'activity_logs') {
            return;
        }

        // Jangan menyimpan field sensitif ke snapshot.
        $oldData = activity_redact($oldData);
        $newData = activity_redact($newData);

        // Keterangan otomatis bila tidak diberikan.
        if ($description === null) {
            $description = activity_build_description($action, $table, $recordId, $newData ?? $oldData);
        }

        // Identitas user dari session.
        $session  = session();
        $userId   = $session ? $session->get('user_id') : null;
        $username = $session ? $session->get('username') : null;

        // IP address (aman dipanggil dari controller maupun model event).
        $ip = null;
        try {
            $ip = service('request')->getIPAddress();
        } catch (\Throwable $e) {
            $ip = null;
        }

        // Waktu lokal Jakarta agar tanggal & jam konsisten dengan portal.
        $now = new \DateTime('now', new \DateTimeZone('Asia/Jakarta'));

        try {
            (new ActivityLogModel())->insert([
                'user_id'     => $userId,
                'username'    => $username ?: 'system',
                'action'      => $action,
                'table_name'  => $table,
                'record_id'   => is_array($recordId) ? implode(',', $recordId) : (string) $recordId,
                'description' => $description,
                'old_data'    => $oldData ? json_encode($oldData, JSON_UNESCAPED_UNICODE) : null,
                'new_data'    => $newData ? json_encode($newData, JSON_UNESCAPED_UNICODE) : null,
                'ip_address'  => $ip,
                'created_at'  => $now->format('Y-m-d H:i:s'),
            ]);
        } catch (\Throwable $e) {
            // Jangan pernah menggagalkan aksi utama hanya karena log gagal.
            log_message('error', 'Gagal menyimpan activity log: ' . $e->getMessage());
        }
    }
}

if (! function_exists('activity_label_action')) {
    /**
     * Label aksi dalam Bahasa Indonesia untuk ditampilkan di UI.
     */
    function activity_label_action(string $action): string
    {
        switch (strtolower($action)) {
            case 'create':
                return 'Tambah';
            case 'update':
                return 'Ubah';
            case 'delete':
                return 'Hapus';
            default:
                return ucfirst($action);
        }
    }

    if (! function_exists('activity_page_name')) {
        /**
         * Ubah nama tabel mentah menjadi nama halaman/menu yang ramah dibaca.
         */
        function activity_page_name(?string $tableName): string
        {
            $map = [
                'd_midi'               => 'Alfamidi',
                'd_lawson'             => 'Lawson',
                'd_alfamart'           => 'Alfamart',
                'md_vendor'            => 'Vendor',
                'md_layanan_vendor'    => 'Layanan Vendor',
                'md_dc'                => 'DC',
                'md_media_koneksi'     => 'Media Koneksi',
                'md_pemilik_projek'    => 'Pemilik Projek',
                'md_layanan_jwi_group' => 'Layanan JWI Group',
                'md_pelanggan'         => 'Pelanggan',
                'md_data_celullar'     => 'Data Celullar',
                'md_nomer_inet'        => 'Nomor INET',

                'md_quota_simcard'     => 'Kuota Simcard',
                'md_merek_perangkat'   => 'Merek Perangkat',
                'md_jenis_perangkat'   => 'Jenis Perangkat',
                'md_type_perangkat'    => 'Type Perangkat',
                'md_simcard'           => 'Simcard',
                'd_simcard'            => 'Simcard',
                'd_nomor_inet'         => 'Nomor Inet',
                'login'                => 'Pengguna',
                'activity_logs'        => 'Log Aktivitas',
            ];

            $key = strtolower(trim((string) $tableName));
            if (isset($map[$key])) {
                return $map[$key];
            }

            // fallback: buang prefix md_/d_/tbl_ lalu Title Case
            $clean = preg_replace('/^(md_|d_|tbl_)/', '', $key);
            $clean = str_replace('_', ' ', $clean);
            return $clean !== '' ? ucwords($clean) : '-';
        }
    }
}
if (! function_exists('activity_format_description')) {
    /**
     * Ubah keterangan lama "... tabel md_xxx ..." menjadi "... Halaman Nama ..."
     */
    function activity_format_description(?string $desc): string
    {
        if (empty($desc)) return '-';

        // ganti pola "tabel <nama_tabel>" -> "Halaman <Nama Halaman>"
        return preg_replace_callback(
            '/\btabel\s+([a-z0-9_]+)/i',
            function ($m) {
                return 'Halaman ' . activity_page_name($m[1]);
            },
            $desc
        );
    }
}
if (! function_exists('activity_redact')) {
    /**
     * Menyamarkan field sensitif (password, token, dsb) dari snapshot.
     */
    function activity_redact(?array $data): ?array
    {
        if (empty($data)) {
            return $data;
        }

        $sensitive = ['password', 'pass', 'pwd', 'token', 'secret', 'api_key'];

        foreach ($data as $key => $value) {
            foreach ($sensitive as $s) {
                if (stripos((string) $key, $s) !== false) {
                    $data[$key] = '***';
                    break;
                }
            }
        }

        return $data;
    }
}

if (! function_exists('activity_pick_label')) {
    /**
     * Mengambil satu nilai "nama" yang representatif dari sebuah baris data,
     * supaya keterangan log lebih mudah dibaca (mis. "PT Telkom" bukan hanya "ID 12").
     */
    function activity_pick_label(?array $data): ?string
    {
        if (empty($data)) {
            return null;
        }

        // Cari kolom yang biasanya menyimpan "nama".
        foreach ($data as $key => $value) {
            $k = strtolower((string) $key);
            if ($value !== null && $value !== '' && (
                str_contains($k, 'nama') ||
                str_contains($k, 'name') ||
                $k === 'judul' ||
                $k === 'title' ||
                str_contains($k, 'merek') ||
                str_contains($k, 'type') ||
                str_contains($k, 'nomor') ||
                str_contains($k, 'no_') ||
                str_contains($k, 'kode')
            )) {
                return (string) $value;
            }
        }

        return null;
    }
}

if (! function_exists('activity_build_description')) {
    /**
     * Membentuk kalimat keterangan otomatis.
     */
    function activity_build_description(string $action, string $table, $recordId, ?array $data): string
    {
        $verb = [
            'create' => 'Menambah data baru pada',
            'update' => 'Mengubah data pada',
            'delete' => 'Menghapus data pada',
        ][$action] ?? ('Aksi "' . $action . '" pada');

        $text  = $verb . ' halaman ' . activity_page_name($table);
        $text .= $recordId !== null ? ' (ID: ' . (is_array($recordId) ? implode(',', $recordId) : $recordId) . ')' : '';

        $label = activity_pick_label($data);
        if ($label !== null) {
            $text .= ' — ' . $label;
        }

        return $text;
    }
}
