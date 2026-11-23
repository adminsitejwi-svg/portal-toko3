<?php

namespace App\Models;

// Alert & Notifikasi (menu NMS JWI Group > Alert + lonceng di navbar), mengikuti netmon/workers/check_alerts.php.
// periksa() dipanggil DeviceMonitoringModel::poll() setelah device di-poll: hasil ping, CPU/RAM & interface terbaru
// dibandingkan dengan AMBANG -> alert baru / alert pulih, sekaligus notifikasinya.
// Model biasa (bukan BaseModel): pengecekan tiap menit tidak dicatat di Change Log.
class DeviceAlertModel extends \CodeIgniter\Model
{
    protected $table      = 'd_device_alert';
    protected $primaryKey = 'id';

    public const TABEL_NOTIF   = 'd_device_notifikasi';
    public const TABEL_BACA    = 'd_device_notifikasi_baca';
    public const TABEL_EXCLUDE = 'd_device_alert_exclude';

    /** Ambang batas alert (sama dengan netmon). */
    public const AMBANG = [
        'latency'     => 200.0, // ms
        'packet_loss' => 20.0,  // persen
        'cpu'         => 90.0,  // persen
        'memory'      => 90.0,  // persen
    ];

    /** Hanya interface fisik yang dicek down; VLAN, PPPoE, bridge & tunnel tidak. */
    public const POLA_FISIK = '/^(ether|sfp|combo|wlan|eth|ge-|xe-|et-|gi|te|fa)/i';

    /** Interface hanya dicek kalau datanya masih baru (detik). */
    private const INTERFACE_SEGAR = 180;

    private static ?bool $siap = null;

    /** Semua tabel alert sudah dibuat (database/d_device_alert.sql). */
    public static function siap(): bool
    {
        if (self::$siap === null) {
            $db         = \Config\Database::connect();
            self::$siap = $db->tableExists('d_device_alert') && $db->tableExists(self::TABEL_NOTIF)
                && $db->tableExists(self::TABEL_BACA) && $db->tableExists(self::TABEL_EXCLUDE);
        }

        return self::$siap;
    }

    // =====================================================================
    //  DETEKSI
    // =====================================================================

    /**
     * Cek satu device yang baru saja di-poll.
     *
     * @param array $d    baris d_device_monitoring
     * @param bool  $baru hasil ping/CPU/RAM terbaru tersedia di d_device_nms_metric
     */
    public function periksa(array $d, bool $baru): void
    {
        $id = (int) $d['id'];
        if (! (int) $d['monitoring_enabled']) {
            return;
        }

        // satu pengecekan per device dalam satu waktu, supaya alert tidak tercatat ganda
        $kunci = 'dm_alert_' . md5($this->db->getDatabase()) . '_' . $id;
        $lock  = $this->db->query('SELECT GET_LOCK(?, 0) AS ok', [$kunci])->getRowArray();
        if ((int) ($lock['ok'] ?? 0) !== 1) {
            return;
        }

        try {
            if ($baru) {
                $this->periksaMetrik($d);
            }
            if (DeviceTrafficModel::siap()) {
                $this->periksaInterface($d);
            }
        } finally {
            $this->db->query('SELECT RELEASE_LOCK(?)', [$kunci]);
        }
    }

    private function periksaMetrik(array $d): void
    {
        $id   = (int) $d['id'];
        $host = (string) $d['hostname'];
        $ip   = (string) $d['ip_address'];

        $m = $this->db->table('d_device_nms_metric')->where('device_id', $id)
            ->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
        if (! $m || time() - strtotime($m['checked_at']) > max(180, (int) $d['polling_interval'] * 3)) {
            return; // data lama tidak dipakai mengambil keputusan
        }

        $kOffline = "device:$id:offline";
        $kLatency = "device:$id:high_latency";
        $kLoss    = "device:$id:packet_loss";

        if ($m['ping_status'] !== 'Up') {
            $this->catat($id, null, $kOffline, 'device_offline', 'critical', "$host is Offline",
                "$host ($ip) tidak merespons " . ($m['ping_source'] === 'snmp' ? 'SNMP.' : 'ICMP.'));
            // device mati: alert latency & packet loss tidak perlu ganda
            $this->pulihkan($kLatency);
            $this->pulihkan($kLoss);
        } else {
            $this->pulihkan($kOffline);
            $this->ambang($id, $kLatency, 'high_latency', 'latency', $m['latency_ms'], "$host High Latency", "Latency %s ms pada $host ($ip).");
            $this->ambang($id, $kLoss, 'packet_loss', 'packet_loss', $m['packet_loss'], "$host Packet Loss", "Packet loss %s% pada $host ($ip).");
        }

        // CPU & RAM hanya dari sampel SNMP yang berhasil
        if ((int) $m['snmp_ok'] === 1) {
            $this->ambang($id, "device:$id:high_cpu", 'high_cpu', 'cpu', $m['cpu_usage'], "$host High CPU", "CPU usage %s% pada $host.");
            $this->ambang($id, "device:$id:high_memory", 'high_memory', 'memory', $m['memory_usage'], "$host High RAM", "Memory usage %s% pada $host.");
        }
    }

    /** Nilai >= ambang -> alert warning, di bawah ambang -> pulih. Nilai kosong diabaikan. */
    private function ambang(int $id, string $kunci, string $tipe, string $jenis, $nilai, string $judul, string $pesan): void
    {
        if ($nilai === null) {
            return;
        }
        $nilai = (float) $nilai;
        if ($nilai >= self::AMBANG[$jenis]) {
            // hanya %s pertama: hostname di belakangnya boleh berisi "%"
            $teks = preg_replace('/%s/', (string) $nilai, $pesan, 1);
            $this->catat($id, null, $kunci, $tipe, 'warning', $judul, $teks, $nilai, self::AMBANG[$jenis]);
        } else {
            $this->pulihkan($kunci);
        }
    }

    private function periksaInterface(array $d): void
    {
        $id   = (int) $d['id'];
        $host = (string) $d['hostname'];

        $daftar = $this->db->table('d_device_interface i')
            ->select('i.id, i.if_name, i.admin_status, i.oper_status, x.interface_id AS dikecualikan')
            ->join(self::TABEL_EXCLUDE . ' x', 'x.interface_id = i.id', 'left')
            ->where('i.device_id', $id)
            ->where('i.last_seen >=', date('Y-m-d H:i:s', time() - self::INTERFACE_SEGAR))
            ->get()->getResultArray();
        if (! $daftar) {
            return;
        }

        // kunci yang sedang aktif: pulihkan() hanya dijalankan untuk yang memang ada alert-nya
        $aktif = array_column($this->db->table($this->table)->select('alert_key')
            ->where('device_id', $id)->where('alert_type', 'interface_down')
            ->whereIn('status', ['open', 'acknowledged'])->get()->getResultArray(), 'alert_key', 'alert_key');

        foreach ($daftar as $i) {
            $ifId  = (int) $i['id'];
            $kunci = "interface:$ifId:down";
            $nama  = (string) $i['if_name'];

            if ($i['dikecualikan'] !== null) {
                if (isset($aktif[$kunci])) {
                    $this->pulihkan($kunci);
                }
                continue;
            }
            if (! preg_match(self::POLA_FISIK, $nama)) {
                continue;
            }

            if ((int) $i['admin_status'] === 1 && $i['oper_status'] !== null && (int) $i['oper_status'] !== 1) {
                $this->catat($id, $ifId, $kunci, 'interface_down', 'warning', "$host - $nama Down",
                    "Interface $nama pada $host berstatus operational down.", (float) $i['oper_status'], 1.0);
            } elseif (isset($aktif[$kunci])) {
                $this->pulihkan($kunci); // admin down / operational up bukan gangguan
            }
        }
    }

    /** Alert baru (+ notifikasi), atau perbarui last_seen kalau alert yang sama masih aktif. */
    private function catat(int $deviceId, ?int $interfaceId, string $kunci, string $tipe, string $severity, string $judul, string $pesan, ?float $nilai = null, ?float $ambang = null): void
    {
        $sekarang = date('Y-m-d H:i:s');
        $judul    = mb_substr($judul, 0, 255);
        $data     = [
            'severity'        => $severity,
            'title'           => $judul,
            'message'         => $pesan,
            'metric_value'    => $nilai,
            'threshold_value' => $ambang,
            'last_seen'       => $sekarang,
        ];

        $ada = $this->db->table($this->table)->select('id')->where('alert_key', $kunci)
            ->whereIn('status', ['open', 'acknowledged'])->orderBy('id', 'DESC')->limit(1)->get()->getRowArray();
        if ($ada) {
            $this->db->table($this->table)->where('id', $ada['id'])->update($data);

            return;
        }

        $this->db->table($this->table)->insert($data + [
            'device_id'    => $deviceId,
            'interface_id' => $interfaceId,
            'alert_key'    => $kunci,
            'alert_type'   => $tipe,
            'status'       => 'open',
            'first_seen'   => $sekarang,
            'created_at'   => $sekarang,
        ]);
        $this->notifikasi((int) $this->db->insertID(), $deviceId, 'alert', $severity, $judul, $pesan);
    }

    /** Alert aktif dengan kunci ini selesai (+ notifikasi pulih). */
    private function pulihkan(string $kunci): void
    {
        $aktif = $this->db->table($this->table)->select('id, device_id, severity, title')
            ->where('alert_key', $kunci)->whereIn('status', ['open', 'acknowledged'])->get()->getResultArray();
        foreach ($aktif as $a) {
            $this->db->table($this->table)->where('id', $a['id'])->update(['status' => 'resolved', 'resolved_at' => date('Y-m-d H:i:s')]);
            $this->notifikasi((int) $a['id'], (int) $a['device_id'], 'recovery', $a['severity'],
                'Recovered: ' . $a['title'], 'Gangguan telah pulih. Alert: ' . $a['title']);
        }
    }

    private function notifikasi(int $alertId, int $deviceId, string $event, string $severity, string $judul, string $pesan): void
    {
        $this->db->table(self::TABEL_NOTIF)->ignore(true)->insert([
            'alert_id'   => $alertId,
            'device_id'  => $deviceId,
            'event_type' => $event,
            'severity'   => $severity,
            'title'      => mb_substr($judul, 0, 255),
            'message'    => $pesan,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /** Hapus alert & notifikasi satu device (dipakai saat device dihapus; panggil sebelum interface-nya dihapus). */
    public function hapusDevice(int $deviceId): void
    {
        if (DeviceTrafficModel::siap()) {
            $this->db->query('DELETE FROM ' . self::TABEL_EXCLUDE . ' WHERE interface_id IN (SELECT id FROM d_device_interface WHERE device_id = ?)', [$deviceId]);
        }
        $this->db->table(self::TABEL_NOTIF)->where('device_id', $deviceId)->delete();
        $this->db->table($this->table)->where('device_id', $deviceId)->delete();
    }

    // =====================================================================
    //  AKSI HALAMAN ALERT
    // =====================================================================

    public function acknowledge(int $id, string $oleh): bool
    {
        $this->db->table($this->table)->where('id', $id)->where('status', 'open')->update([
            'status'          => 'acknowledged',
            'acknowledged_at' => date('Y-m-d H:i:s'),
            'acknowledged_by' => $oleh,
        ]);

        return $this->db->affectedRows() > 0;
    }

    public function selesaikan(int $id): bool
    {
        $this->db->table($this->table)->where('id', $id)->whereIn('status', ['open', 'acknowledged'])
            ->update(['status' => 'resolved', 'resolved_at' => date('Y-m-d H:i:s')]);

        return $this->db->affectedRows() > 0;
    }

    /** Aktifkan / kecualikan (Exclude) interface dari alert; false kalau interface tidak ada. */
    public function aturInterface(int $interfaceId, bool $aktif, string $oleh): bool
    {
        if (! $this->db->table('d_device_interface')->where('id', $interfaceId)->countAllResults()) {
            return false;
        }
        if ($aktif) {
            $this->db->table(self::TABEL_EXCLUDE)->where('interface_id', $interfaceId)->delete();

            return true;
        }

        $this->db->table(self::TABEL_EXCLUDE)->ignore(true)->insert([
            'interface_id' => $interfaceId,
            'created_by'   => $oleh,
            'created_at'   => date('Y-m-d H:i:s'),
        ]);
        $this->db->table($this->table)->where('interface_id', $interfaceId)->where('alert_type', 'interface_down')
            ->whereIn('status', ['open', 'acknowledged'])
            ->update(['status' => 'resolved', 'resolved_at' => date('Y-m-d H:i:s')]);

        return true;
    }

    // =====================================================================
    //  DATA HALAMAN ALERT
    // =====================================================================

    /** Kartu ringkasan: jumlah per status + critical/warning yang masih aktif. */
    public function ringkasan(): array
    {
        $r = ['open' => 0, 'acknowledged' => 0, 'resolved' => 0, 'critical' => 0, 'warning' => 0];
        $q = $this->db->query('SELECT status, severity, COUNT(*) AS n FROM ' . $this->table . ' GROUP BY status, severity');
        foreach ($q->getResultArray() as $b) {
            $r[$b['status']] += (int) $b['n'];
            if ($b['status'] !== 'resolved' && isset($r[$b['severity']])) {
                $r[$b['severity']] += (int) $b['n'];
            }
        }

        return $r;
    }

    /** Query daftar alert sesuai filter (dipakai tabel, jumlah data & export CSV). */
    private function kueri(array $f)
    {
        $q = $this->db->table($this->table . ' a')
            ->join('d_device_monitoring d', 'd.id = a.device_id', 'left')
            ->join('d_device_interface i', 'i.id = a.interface_id', 'left');

        if ($f['status'] === 'active') {
            $q->whereIn('a.status', ['open', 'acknowledged']);
        } elseif ($f['status'] !== 'all') {
            $q->where('a.status', $f['status']);
        }
        if ($f['severity'] !== 'all') {
            $q->where('a.severity', $f['severity']);
        }
        if ($f['q'] !== '') {
            $q->groupStart()->like('d.hostname', $f['q'])->orLike('d.ip_address', $f['q'])->groupEnd();
        }
        if ($f['dari'] !== '') {
            $q->where('a.last_seen >=', $f['dari'] . ' 00:00:00');
        }
        if ($f['sampai'] !== '') {
            $q->where('a.last_seen <=', $f['sampai'] . ' 23:59:59');
        }

        return $q;
    }

    public function jumlah(array $f): int
    {
        return $this->kueri($f)->countAllResults();
    }

    public function daftar(array $f, int $per, int $mulai): array
    {
        return $this->kueri($f)
            ->select('a.*, d.hostname, d.ip_address, i.if_name')
            ->orderBy("FIELD(a.status, 'open', 'acknowledged', 'resolved')", '', false)
            ->orderBy("FIELD(a.severity, 'critical', 'warning', 'info')", '', false)
            ->orderBy('a.last_seen', 'DESC')->orderBy('a.id', 'DESC')
            ->limit($per, $mulai)->get()->getResultArray();
    }

    /** Alert terbaru (Dashboard Netmon). */
    public function terbaru(int $batas = 5): array
    {
        return $this->db->table($this->table . ' a')->select('a.*, d.hostname')
            ->join('d_device_monitoring d', 'd.id = a.device_id', 'left')
            ->orderBy('a.first_seen', 'DESC')->orderBy('a.id', 'DESC')
            ->limit($batas)->get()->getResultArray();
    }

    /** Data export CSV (filter sama dengan tabel, tanpa halaman). */
    public function dataExport(array $f): array
    {
        return $this->kueri($f)
            ->select('a.id, a.alert_type, a.severity, a.title, a.message, d.hostname, d.ip_address, i.if_name,
                a.metric_value, a.threshold_value, a.status, a.first_seen, a.last_seen, a.acknowledged_at, a.acknowledged_by, a.resolved_at')
            ->orderBy('a.last_seen', 'DESC')->orderBy('a.id', 'DESC')
            ->get()->getResultArray();
    }

    public function jumlahInterface(): int
    {
        return $this->db->table('d_device_interface i')->join('d_device_monitoring d', 'd.id = i.device_id')->countAllResults();
    }

    public function daftarInterface(int $per, int $mulai): array
    {
        return $this->db->table('d_device_interface i')
            ->select('i.id, i.if_name, i.if_alias, i.oper_status, d.hostname, d.ip_address, x.interface_id AS dikecualikan')
            ->join('d_device_monitoring d', 'd.id = i.device_id')
            ->join(self::TABEL_EXCLUDE . ' x', 'x.interface_id = i.id', 'left')
            ->orderBy('d.hostname', 'ASC')->orderBy('i.if_index', 'ASC')
            ->limit($per, $mulai)->get()->getResultArray();
    }

    // =====================================================================
    //  LONCENG NOTIFIKASI
    // =====================================================================

    private function terakhirDibaca(int $userId): int
    {
        $b = $this->db->table(self::TABEL_BACA)->where('user_id', $userId)->get()->getRowArray();

        return (int) ($b['notifikasi_id'] ?? 0);
    }

    /** @return array{unread_count: int, notifications: list<array>} */
    public function lonceng(int $userId, int $batas = 8): array
    {
        $baca = $this->terakhirDibaca($userId);
        $list = $this->db->table(self::TABEL_NOTIF . ' n')
            ->select('n.id, n.alert_id, n.event_type, n.severity, n.title, n.message, n.created_at, d.hostname, d.ip_address')
            ->join('d_device_monitoring d', 'd.id = n.device_id', 'left')
            ->orderBy('n.id', 'DESC')->limit($batas)->get()->getResultArray();
        foreach ($list as &$n) {
            $n['is_read'] = (int) $n['id'] <= $baca ? 1 : 0;
        }
        unset($n);

        return [
            'unread_count'  => $this->db->table(self::TABEL_NOTIF)->where('id >', $baca)->countAllResults(),
            'notifications' => $list,
        ];
    }

    /** Tandai semua notifikasi sampai $sampaiId dibaca oleh akun ini. */
    public function tandaiDibaca(int $userId, int $sampaiId): void
    {
        $maks = (int) ($this->db->table(self::TABEL_NOTIF)->selectMax('id', 'm')->get()->getRowArray()['m'] ?? 0);
        $this->db->table(self::TABEL_BACA)->replace([
            'user_id'       => $userId,
            'notifikasi_id' => max($this->terakhirDibaca($userId), min($maks, $sampaiId)),
            'read_at'       => date('Y-m-d H:i:s'),
        ]);
    }
}
