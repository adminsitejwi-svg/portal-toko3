<?php

namespace App\Libraries;

// Mengubah parameter mentah ONT (d_acs_data) menjadi data siap tampil di halaman Devices (ACS JWI Group):
// info perangkat, redaman optik, WiFi dan client. Parameter standar TR-098 + nama khusus vendor yang umum.
class AcsData
{
    /** Parameter rahasia (nama ruas terakhir): disimpan terenkripsi, tidak pernah ikut tampil di daftar. */
    public const POLA_RAHASIA = '/(password|passphrase|presharedkey|wepkey|psk|secret|key)$/i';

    /** Objek optik per merek ONT. */
    public static function pathOptik(?string $merek): string
    {
        $m    = strtolower((string) $merek);
        $nama = match (true) {
            str_contains($m, 'huawei')    => 'X_GponInterafceConfig', // ejaan asli dari Huawei
            str_contains($m, 'zte')       => 'X_ZTE-COM_WANPONInterfaceConfig',
            str_contains($m, 'fiberhome') => 'X_FH_GponInterfaceConfig',
            default                       => 'X_CT-COM_GponInterfaceConfig',
        };

        return 'InternetGatewayDevice.WANDevice.1.' . $nama . '.';
    }

    /** Filter MAC Huawei (memblokir perangkat LAN & WiFi). */
    public const MAC = 'InternetGatewayDevice.X_HW_Security.';

    /** @return list<string> kosong = merek ini belum didukung */
    public static function pathMac(?string $merek): array
    {
        return str_contains(strtolower((string) $merek), 'huawei') ? [self::MAC . 'MacFilterRight', self::MAC . 'MacFilterPolicy', self::MAC . 'MacFilter.'] : [];
    }

    /**
     * Daftar MAC yang diblokir.
     *
     * @return array{didukung: bool, aktif: bool, putih: bool, daftar: list<array{no: int, mac: string}>}
     */
    public static function blokir(array $p): array
    {
        $ya     = static fn ($k) => in_array(strtolower((string) ($p[self::MAC . $k] ?? '')), ['1', 'true'], true);
        $daftar = [];
        foreach ($p as $k => $v) {
            if (preg_match('/\.MacFilter\.(\d+)\.SourceMACAddress$/', $k, $m)) {
                $daftar[] = ['no' => (int) $m[1], 'mac' => strtoupper(str_replace('-', ':', (string) $v))];
            }
        }

        return ['didukung' => isset($p[self::MAC . 'MacFilterRight']), 'aktif' => $ya('MacFilterRight'), 'putih' => $ya('MacFilterPolicy'), 'daftar' => $daftar];
    }

    /** Hasil ping client (IPPingDiagnostics); null = belum ada. */
    public static function ping(array $p): ?array
    {
        $f = static fn ($n) => $p['InternetGatewayDevice.IPPingDiagnostics.' . $n] ?? null;
        if (! $f('Host')) {
            return null;
        }
        $ok = (int) $f('SuccessCount');
        $no = (int) $f('FailureCount');

        return ['ip' => $f('Host'), 'status' => $f('DiagnosticsState'), 'ok' => $ok, 'total' => $ok + $no,
            'rata' => $f('AverageResponseTime'), 'min' => $f('MinimumResponseTime'), 'maks' => $f('MaximumResponseTime')];
    }

    /** Jenis WAN untuk tampilan: PPPoE, TR-069 (DHCP), DHCP, Static. */
    public static function jenisWan(array $w, array $p): string
    {
        if ($w['jenis'] === 'PPP') {
            return 'PPPoE';
        }
        $alamat = ['DHCP' => 'DHCP', 'Static' => 'Static'][$p[$w['path'] . '.AddressingType'] ?? ''] ?? 'IP';

        return preg_match('/TR-?069/i', $w['layanan'] . ' ' . $w['deskripsi']) ? 'TR-069 (' . $alamat . ')' : $alamat;
    }

    /** Nilai pertama yang nama akhirannya cocok dengan pola. */
    private static function cari(array $p, string $pola): ?string
    {
        foreach ($p as $k => $v) {
            if ($v !== '' && preg_match($pola, $k)) {
                return (string) $v;
            }
        }

        return null;
    }

    /** Info perangkat: [label => nilai], yang kosong dilewati. */
    public static function info(array $p): array
    {
        $f = static fn (string $n) => self::cari($p, '/\.DeviceInfo\.' . $n . '$/i');
        $d = [
            'Merek'              => $f('Manufacturer'),
            'Model'              => $f('ModelName') ?? $f('ProductClass'),
            'Serial Number'      => $f('SerialNumber'),
            'Versi Software'     => $f('SoftwareVersion'),
            'Versi Hardware'     => $f('HardwareVersion'),
            'Deskripsi'          => $f('Description'),
            'Menyala selama'     => ($u = $f('UpTime')) !== null && ctype_digit($u) ? self::durasi((int) $u) : null,
            'Pemakaian CPU'      => self::persen($f('(X_HW_)?Cpu(Used|Usage)') ?? $f('ProcessStatus\.CPUUsage')),
            'Pemakaian Memori'   => self::persen($f('(X_HW_)?Mem(Used|Usage)')),
            'Memori Total'       => ($m = $f('MemoryStatus\.Total')) !== null && ctype_digit($m) ? number_format((int) $m / 1024, 0, ',', '.') . ' MB' : null,
            'Pertama dipakai'    => $f('FirstUseDate'),
        ];

        return array_filter($d, static fn ($v) => $v !== null && $v !== '');
    }

    private static function persen(?string $v): ?string
    {
        if ($v === null) {
            return null;
        }
        $n = rtrim(trim($v), '%');

        return is_numeric($n) && $n >= 0 && $n <= 100 ? $n . '%' : null;
    }

    public static function durasi(int $detik): string
    {
        $hari = intdiv($detik, 86400);

        return ($hari ? $hari . ' hari ' : '') . sprintf('%02d:%02d', intdiv($detik % 86400, 3600), intdiv($detik % 3600, 60));
    }

    /**
     * Daya optik ke dBm. Sebagian ONT mengirim dBm langsung (mis. -19.5), sebagian mengirim satuan 0,1 uW (mis. 110).
     */
    private static function dbm(?string $v, bool $rx): ?float
    {
        if ($v === null || ! preg_match('/^-?\d+(\.\d+)?/', trim($v), $m)) {
            return null;
        }
        $n = (float) $m[0];
        if ($rx ? $n > 0 : $n > 50) {
            $n = 10 * log10($n / 10000);
        }
        // 0 / di luar jangkauan = tidak ada sinyal atau nilai tidak sah
        if (($rx && $n >= 0) || $n < -50 || $n > 15) {
            return null;
        }

        return round($n, 2);
    }

    /** @return array{rx: float|null, tx: float|null, suhu: string|null, tegangan: string|null, arus: string|null, status: array{0: string, 1: string}|null} */
    public static function optik(array $p): array
    {
        $rx = self::dbm(self::cari($p, '/\.(RX|Rx)_?(Optical)?Power$/i'), true);

        return [
            'rx'       => $rx,
            'tx'       => self::dbm(self::cari($p, '/\.(TX|Tx)_?(Optical)?Power$/i'), false),
            'suhu'     => self::cari($p, '/\.(Transceiver)?Temp(erature|rature)$/i'),
            'tegangan' => self::cari($p, '/\.(Supply)?Vott?l?(age|tage)$|\.SupplyVottage$|\.Voltage$/i'),
            'arus'     => self::cari($p, '/\.(Bias)?Current$/i'),
            'status'   => self::nilaiRx($rx),
        ];
    }

    /** Penilaian RX power GPON: [kelas badge, label]. */
    public static function nilaiRx(?float $rx): ?array
    {
        if ($rx === null) {
            return null;
        }

        return match (true) {
            $rx > -8   => ['rb-amber', 'Terlalu kuat'],
            $rx >= -25 => ['rb-green', 'Baik'],
            $rx >= -27 => ['rb-amber', 'Kurang'],
            default    => ['rb-red', 'Buruk'],
        };
    }

    /**
     * Daftar WiFi (SSID) yang punya nama.
     *
     * @param list<string> $rahasia nama parameter rahasia yang ada di ONT
     * @param list<string> $terisi  nama parameter rahasia yang nilainya dikirim ONT
     */
    public static function wifi(array $p, array $rahasia, array $terisi, ?string $merek = null): array
    {
        $w = [];
        foreach ($p as $k => $v) {
            if (preg_match('/^(.*\.LANDevice\.\d+\.WLANConfiguration\.(\d+)\.)(.+)$/', $k, $m)) {
                $w[(int) $m[2]]['_dasar'] = $m[1];
                $w[(int) $m[2]][$m[3]]   = $v;
            }
        }
        ksort($w);
        // urutan parameter password: Huawei memakai PreSharedKey.1.PreSharedKey, merek lain umumnya KeyPassphrase
        $urut = ['KeyPassphrase', 'PreSharedKey.1.KeyPassphrase', 'PreSharedKey.1.PreSharedKey'];
        if (str_contains(strtolower((string) $merek), 'huawei')) {
            $urut = array_reverse($urut);
        }

        $hasil = [];
        foreach ($w as $no => $x) {
            if (($x['SSID'] ?? '') === '') {
                continue;
            }
            $tulis = $baca = null;
            foreach ($urut as $n) {
                $tulis ??= in_array($x['_dasar'] . $n, $rahasia, true) ? $x['_dasar'] . $n : null;
                $baca  ??= in_array($x['_dasar'] . $n, $terisi, true) ? $x['_dasar'] . $n : null;
            }
            $maks = null;
            foreach (['X_HW_AssociateNum', 'X_ZTE-COM_MaxUserNum', 'X_CT-COM_MaxStaNum', 'MaxAssociatedDevices'] as $n) {
                if ($maks === null && isset($x[$n])) {
                    $maks = $n;
                }
            }
            $kanal   = (int) ($x['Channel'] ?? 0);
            $pita    = ($x['X_HW_RFBand'] ?? '') . ($x['OperatingFrequencyBand'] ?? '');
            $lima    = $pita !== '' ? str_starts_with($pita, '5') : ($kanal > 0 ? $kanal > 14 : $no >= 5); // Huawei: SSID 5-8 = 5 GHz
            $hasil[] = [
                'no'          => $no,
                'dasar'       => $x['_dasar'],
                'ssid'        => $x['SSID'],
                'aktif'       => in_array(strtolower((string) ($x['Enable'] ?? '')), ['1', 'true'], true),
                'band'        => $lima ? '5 GHz' : '2.4 GHz',
                'kanal'       => $kanal > 0 ? (string) $kanal : 'Auto',
                'keamanan'    => $x['BeaconType'] ?? '-',
                'bssid'       => $x['BSSID'] ?? '',
                'client'      => $x['TotalAssociations'] ?? '',
                'maks'        => $maks !== null ? (string) $x[$maks] : '',
                'param_maks'  => $maks !== null ? $x['_dasar'] . $maks : null,
                'param_sandi' => $tulis,
                'sandi'       => $baca,
            ];
        }

        return $hasil;
    }

    /** Client: gabungan tabel Hosts dan perangkat yang tersambung ke WiFi. */
    public static function client(array $host, array $wifi): array
    {
        $ssid = [];
        $asos = [];
        foreach ($wifi as $k => $v) {
            if (preg_match('/\.WLANConfiguration\.(\d+)\.SSID$/', $k, $m)) {
                $ssid[$m[1]] = $v;
            } elseif (preg_match('/\.WLANConfiguration\.(\d+)\.AssociatedDevice\.(\d+)\.(.+)$/', $k, $m)) {
                $asos[$m[1] . '.' . $m[2]]['_ssid']  = $m[1];
                $asos[$m[1] . '.' . $m[2]][$m[3]] = $v;
            }
        }

        $hasil = [];
        $baris = [];
        foreach ($host as $k => $v) {
            if (preg_match('/\.Hosts\.Host\.(\d+)\.([^.]+)$/', $k, $m)) {
                $baris[$m[1]][$m[2]] = $v;
            }
        }
        foreach ($baris as $b) {
            $mac = strtoupper((string) ($b['MACAddress'] ?? ''));
            if ($mac === '') {
                continue;
            }
            $hasil[$mac] = [
                'nama'  => $b['HostName'] ?? '',
                'ip'    => $b['IPAddress'] ?? '',
                'mac'   => $mac,
                'lewat' => stripos((string) ($b['InterfaceType'] ?? ''), '802.11') !== false ? 'WiFi' : (($b['InterfaceType'] ?? '') !== '' ? 'LAN' : '-'),
                'aktif' => in_array(strtolower((string) ($b['Active'] ?? '')), ['1', 'true'], true),
                'sinyal' => '',
            ];
        }
        foreach ($asos as $a) {
            $mac = strtoupper((string) ($a['AssociatedDeviceMACAddress'] ?? ''));
            if ($mac === '') {
                continue;
            }
            $lama        = $hasil[$mac] ?? ['nama' => '', 'ip' => '', 'mac' => $mac];
            $hasil[$mac] = [
                'ip'     => $lama['ip'] !== '' ? $lama['ip'] : (string) ($a['AssociatedDeviceIPAddress'] ?? ''),
                'lewat'  => 'WiFi' . (isset($ssid[$a['_ssid']]) ? ' ' . $ssid[$a['_ssid']] : ''),
                'aktif'  => true,
                'sinyal' => (string) ($a['X_HW_RSSI'] ?? ''),
            ] + $lama;
        }
        usort($hasil, static fn ($a, $b) => [$b['aktif'], $a['ip']] <=> [$a['aktif'], $b['ip']]);

        return $hasil;
    }
}
