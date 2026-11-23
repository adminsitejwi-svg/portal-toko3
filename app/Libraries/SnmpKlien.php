<?php

namespace App\Libraries;

/**
 * Klien SNMP v2c sederhana (PHP murni lewat UDP, tanpa ekstensi php_snmp).
 *
 * Dipakai Device Monitoring untuk GET OID sistem (sysDescr, sysName, sysUpTime, dst.) dan
 * Traffic Monitoring untuk walk tabel interface IF-MIB (GETBULK, walkTabel()).
 * Hanya SNMP v2c (community); SNMP v3 belum didukung.
 *
 *   $hasil = (new SnmpKlien('10.0.0.1', 'public'))->get(SnmpKlien::OID_SISTEM);
 *   // ['ok' => true, 'ms' => 12, 'nilai' => ['sysName' => 'R1', ...], 'error' => null]
 */
class SnmpKlien
{
    /** OID sistem yang dibaca saat polling (nama => OID). */
    public const OID_SISTEM = [
        'sysDescr'    => '1.3.6.1.2.1.1.1.0',
        'sysUpTime'   => '1.3.6.1.2.1.1.3.0',
        'sysContact'  => '1.3.6.1.2.1.1.4.0',
        'sysName'     => '1.3.6.1.2.1.1.5.0',
        'sysLocation' => '1.3.6.1.2.1.1.6.0',
        'ifNumber'    => '1.3.6.1.2.1.2.1.0',
    ];

    private string $host;
    private string $community;
    private int $port;
    private float $timeout;
    private int $retry;

    public function __construct(string $host, string $community, int $port = 161, float $timeout = 2.0, int $retry = 1)
    {
        $this->host      = $host;
        $this->community = $community;
        $this->port      = $port;
        $this->timeout   = $timeout;
        $this->retry     = max(0, $retry);
    }

    /**
     * GET beberapa OID sekaligus.
     *
     * @param array<string, string> $oid nama => OID
     *
     * @return array{ok: bool, ms: int|null, nilai: array<string, mixed>, error: string|null}
     */
    public function get(array $oid): array
    {
        $fp = $this->buka($error);
        if (! $fp) {
            return $this->gagal($error);
        }

        try {
            $hasil = $this->tanya($fp, fn (int $id) => $this->buatRequest(0xA0, $id, array_values($oid), 0, 0));
            if ($hasil === null) {
                return $this->gagal(self::PESAN_TIMEOUT);
            }
            [$balas, $ms] = $hasil;
            if ($balas['errorStatus'] !== 0) {
                return $this->gagal('Perangkat membalas error SNMP (status ' . $balas['errorStatus'] . ').', $ms);
            }

            $nilai = [];
            $nama  = array_flip($oid);
            foreach ($balas['varbind'] as $vb) {
                $nilai[$nama[$vb['oid']] ?? $vb['oid']] = $vb['nilai'];
            }

            return ['ok' => true, 'ms' => $ms, 'nilai' => $nilai, 'error' => null];
        } finally {
            fclose($fp);
        }
    }

    /**
     * Walk beberapa kolom tabel sekaligus dengan GETBULK (mis. kolom IF-MIB ifTable/ifXTable).
     *
     *   $hasil = $klien->walkTabel(['ifName' => '1.3.6.1.2.1.31.1.1.1.1', 'ifOper' => '1.3.6.1.2.1.2.2.1.8']);
     *   // ['ok' => true, 'ms' => 40, 'data' => ['ifName' => ['1' => 'ether1', ...], 'ifOper' => ['1' => 1, ...]], 'error' => null]
     *
     * Kunci data = sisa OID setelah OID kolom (untuk ifTable = ifIndex).
     * Kolom yang tidak didukung perangkat menghasilkan array kosong.
     *
     * @param array<string, string> $kolom nama => OID kolom
     *
     * @return array{ok: bool, ms: int|null, data: array<string, array<string, mixed>>, error: string|null}
     */
    public function walkTabel(array $kolom, int $maxRep = 10, int $batasBaris = 5000): array
    {
        $data = array_fill_keys(array_keys($kolom), []);
        $fp   = $this->buka($error);
        if (! $fp) {
            return ['ok' => false, 'ms' => null, 'data' => $data, 'error' => $error];
        }

        $posisi = array_map(static fn ($o) => trim($o, '.'), $kolom);
        $prefix = array_map(static fn ($o) => $o . '.', $posisi);
        $mulai  = microtime(true);
        $maxRep = max(1, $maxRep);

        try {
            for ($putaran = 0; $posisi && $putaran < 1000; $putaran++) {
                $nama  = array_keys($posisi);
                $oids  = array_values($posisi);
                $hasil = $this->tanya($fp, fn (int $id) => $this->buatRequest(0xA5, $id, $oids, 0, $maxRep));
                if ($hasil === null) {
                    return ['ok' => false, 'ms' => null, 'data' => $data, 'error' => self::PESAN_TIMEOUT];
                }
                $balas = $hasil[0];

                // tooBig: balasan terlalu besar, kecilkan jumlah baris per request
                if ($balas['errorStatus'] === 1 && $maxRep > 1) {
                    $maxRep = intdiv($maxRep, 2);

                    continue;
                }
                if ($balas['errorStatus'] !== 0) {
                    return ['ok' => false, 'ms' => null, 'data' => $data, 'error' => 'Perangkat membalas error SNMP (status ' . $balas['errorStatus'] . ').'];
                }
                if (! $balas['varbind']) {
                    break;
                }

                // varbind GETBULK berurutan: baris 1 (kolom 1..k), baris 2 (kolom 1..k), dst.
                $k       = count($nama);
                $selesai = [];
                $maju    = false;
                foreach ($balas['varbind'] as $i => $vb) {
                    $n = $nama[$i % $k];
                    if (isset($selesai[$n])) {
                        continue;
                    }
                    if ($vb['tag'] >= 0x80 || ! str_starts_with($vb['oid'], $prefix[$n])) {
                        $selesai[$n] = true; // keluar dari kolom / endOfMibView

                        continue;
                    }
                    $data[$n][substr($vb['oid'], strlen($prefix[$n]))] = $vb['nilai'];
                    if ($vb['oid'] !== $posisi[$n]) {
                        $posisi[$n] = $vb['oid'];
                        $maju       = true;
                    }
                    if (count($data[$n]) >= $batasBaris) {
                        $selesai[$n] = true;
                    }
                }
                foreach (array_keys($selesai) as $n) {
                    unset($posisi[$n]);
                }
                if (! $maju) {
                    break; // OID tidak bertambah: hentikan agar tidak berputar terus
                }
            }

            return ['ok' => true, 'ms' => (int) round((microtime(true) - $mulai) * 1000), 'data' => $data, 'error' => null];
        } finally {
            fclose($fp);
        }
    }

    private const PESAN_TIMEOUT = 'Timeout: tidak ada balasan SNMP (cek IP, port, community & akses SNMP perangkat).';

    /** Buka socket UDP ke perangkat; false + $error kalau gagal. */
    private function buka(?string &$error)
    {
        $alamat = (str_contains($this->host, ':') ? '[' . $this->host . ']' : $this->host) . ':' . $this->port;
        $fp     = @stream_socket_client('udp://' . $alamat, $errno, $errstr, $this->timeout);
        if (! $fp) {
            $error = 'Tidak bisa membuka koneksi UDP: ' . ($errstr ?: 'error ' . $errno);

            return false;
        }

        $detik = (int) floor($this->timeout);
        $mikro = (int) (($this->timeout - $detik) * 1000000);
        stream_set_timeout($fp, $detik, $mikro);

        return $fp;
    }

    /**
     * Kirim satu request (dibuat $buat(reqId)) lalu tunggu balasan dengan request-id yang sama.
     *
     * @return array{0: array, 1: int}|null [balasan, ms] atau null kalau timeout setelah semua retry
     */
    private function tanya($fp, callable $buat): ?array
    {
        for ($coba = 0; $coba <= $this->retry; $coba++) {
            $reqId = random_int(1, 0x7FFFFFFF);
            $mulai = microtime(true);
            if (@fwrite($fp, $buat($reqId)) === false) {
                return null;
            }

            // baca sampai dapat balasan dengan request-id yang sama atau timeout
            while (true) {
                $data = @fread($fp, 65535);
                $info = stream_get_meta_data($fp);
                if ($data === false || $data === '') {
                    if (! empty($info['timed_out'])) {
                        break; // timeout: coba lagi (retry)
                    }

                    // ICMP port unreachable (mis. Windows) bisa membuat fread langsung kosong
                    if (microtime(true) - $mulai >= $this->timeout) {
                        break;
                    }
                    usleep(20000);

                    continue;
                }

                $balas = $this->bacaResponse($data);
                if ($balas === null || $balas['reqId'] !== $reqId) {
                    continue; // paket lain / rusak: abaikan
                }

                return [$balas, (int) round((microtime(true) - $mulai) * 1000)];
            }
        }

        return null;
    }

    /** Format timeticks (1/100 detik) jadi "3 hari 04:05:06". */
    public static function formatUptime(?int $ticks): string
    {
        if ($ticks === null) {
            return '-';
        }
        $detik = intdiv($ticks, 100);
        $hari  = intdiv($detik, 86400);
        $sisa  = $detik % 86400;

        return ($hari ? $hari . ' hari ' : '') . sprintf('%02d:%02d:%02d', intdiv($sisa, 3600), intdiv($sisa % 3600, 60), $sisa % 60);
    }

    private function gagal(string $pesan, ?int $ms = null): array
    {
        return ['ok' => false, 'ms' => $ms, 'nilai' => [], 'error' => $pesan];
    }

    // ===================== ENCODE (BER) =====================

    /**
     * PDU 0xA0 = GetRequest (f1 = f2 = 0), 0xA5 = GetBulkRequest (f1 = non-repeaters, f2 = max-repetitions).
     */
    private function buatRequest(int $tag, int $reqId, array $oids, int $f1, int $f2): string
    {
        $varbind = '';
        foreach ($oids as $o) {
            $varbind .= $this->tlv(0x30, $this->tlv(0x06, $this->encOid($o)) . "\x05\x00");
        }
        $pdu = $this->tlv($tag, $this->encInt($reqId) . $this->encInt($f1) . $this->encInt($f2) . $this->tlv(0x30, $varbind));

        // versi 1 = SNMP v2c
        return $this->tlv(0x30, $this->encInt(1) . $this->tlv(0x04, $this->community) . $pdu);
    }

    private function tlv(int $tag, string $isi): string
    {
        $n = strlen($isi);
        if ($n < 0x80) {
            $len = chr($n);
        } else {
            $b   = ltrim(pack('N', $n), "\x00");
            $len = chr(0x80 | strlen($b)) . $b;
        }

        return chr($tag) . $len . $isi;
    }

    private function encInt(int $v): string
    {
        $b = '';
        do {
            $b = chr($v & 0xFF) . $b;
            $v >>= 8;
        } while ($v > 0);
        if (ord($b[0]) & 0x80) {
            $b = "\x00" . $b; // tetap positif
        }

        return $this->tlv(0x02, $b);
    }

    private function encOid(string $oid): string
    {
        $p   = array_map('intval', explode('.', trim($oid, '.')));
        $out = chr(40 * $p[0] + $p[1]);
        for ($i = 2, $n = count($p); $i < $n; $i++) {
            $v   = $p[$i];
            $sub = chr($v & 0x7F);
            $v >>= 7;
            while ($v > 0) {
                $sub = chr(0x80 | ($v & 0x7F)) . $sub;
                $v >>= 7;
            }
            $out .= $sub;
        }

        return $out;
    }

    // ===================== DECODE (BER) =====================

    /** Baca satu TLV mulai $pos; kembalikan [tag, isi, posisi berikutnya] atau null. */
    private function bacaTlv(string $d, int $pos): ?array
    {
        $panjangData = strlen($d);
        if ($pos + 2 > $panjangData) {
            return null;
        }
        $tag = ord($d[$pos]);
        $len = ord($d[$pos + 1]);
        $pos += 2;
        if ($len & 0x80) {
            $nb = $len & 0x7F;
            if ($nb < 1 || $nb > 4 || $pos + $nb > $panjangData) {
                return null;
            }
            $len = 0;
            for ($i = 0; $i < $nb; $i++) {
                $len = ($len << 8) | ord($d[$pos + $i]);
            }
            $pos += $nb;
        }
        if ($pos + $len > $panjangData) {
            return null;
        }

        return [$tag, substr($d, $pos, $len), $pos + $len];
    }

    private function bacaResponse(string $d): ?array
    {
        $msg = $this->bacaTlv($d, 0);
        if (! $msg || $msg[0] !== 0x30) {
            return null;
        }
        $isi = $msg[1];

        $versi = $this->bacaTlv($isi, 0);
        $comm  = $versi ? $this->bacaTlv($isi, $versi[2]) : null;
        $pdu   = $comm ? $this->bacaTlv($isi, $comm[2]) : null;
        if (! $pdu || $pdu[0] !== 0xA2) {
            return null; // bukan GetResponse
        }

        $p     = $pdu[1];
        $req   = $this->bacaTlv($p, 0);
        $err   = $req ? $this->bacaTlv($p, $req[2]) : null;
        $idx   = $err ? $this->bacaTlv($p, $err[2]) : null;
        $list  = $idx ? $this->bacaTlv($p, $idx[2]) : null;
        if (! $list) {
            return null;
        }

        $varbind = [];
        $pos     = 0;
        while ($pos < strlen($list[1])) {
            $vb = $this->bacaTlv($list[1], $pos);
            if (! $vb) {
                break;
            }
            $o = $this->bacaTlv($vb[1], 0);
            $v = $o ? $this->bacaTlv($vb[1], $o[2]) : null;
            if ($o && $v) {
                $varbind[] = ['oid' => $this->decOid($o[1]), 'tag' => $v[0], 'nilai' => $this->decNilai($v[0], $v[1])];
            }
            $pos = $vb[2];
        }

        return [
            'reqId'       => $this->decUint($req[1]),
            'errorStatus' => $this->decUint($err[1]),
            'varbind'     => $varbind,
        ];
    }

    private function decUint(string $b): int
    {
        $v = 0;
        foreach (str_split($b) as $c) {
            $v = ($v << 8) | ord($c);
        }

        return $v;
    }

    /**
     * Counter64 (unsigned): int kalau muat di PHP_INT_MAX, selain itu string angka desimal
     * (decUint biasa akan overflow jadi negatif).
     */
    private function decBesar(string $b)
    {
        $b = ltrim($b, "\x00");
        if (strlen($b) < 8 || (strlen($b) === 8 && ord($b[0]) < 0x80)) {
            return $this->decUint($b);
        }

        $hasil = '0';
        foreach (str_split($b) as $c) {
            // hasil = hasil * 256 + byte (aritmetika string desimal)
            $sisa = ord($c);
            $baru = '';
            for ($i = strlen($hasil) - 1; $i >= 0; $i--) {
                $v    = ((int) $hasil[$i]) * 256 + $sisa;
                $baru = ($v % 10) . $baru;
                $sisa = intdiv($v, 10);
            }
            while ($sisa > 0) {
                $baru = ($sisa % 10) . $baru;
                $sisa = intdiv($sisa, 10);
            }
            $hasil = ltrim($baru, '0') ?: '0';
        }

        return $hasil;
    }

    private function decOid(string $b): string
    {
        if ($b === '') {
            return '';
        }
        $pertama = ord($b[0]);
        $p       = [intdiv($pertama, 40), $pertama % 40];
        $v       = 0;
        for ($i = 1, $n = strlen($b); $i < $n; $i++) {
            $c = ord($b[$i]);
            $v = ($v << 7) | ($c & 0x7F);
            if (! ($c & 0x80)) {
                $p[] = $v;
                $v   = 0;
            }
        }

        return implode('.', $p);
    }

    /** Nilai varbind sesuai tipe; noSuchObject/noSuchInstance/endOfMibView = null. */
    private function decNilai(int $tag, string $b)
    {
        switch ($tag) {
            case 0x02: // INTEGER (bertanda)
                $v = $this->decUint($b);
                $bit = strlen($b) * 8;
                if ($bit > 0 && $bit < 64 && (ord($b[0]) & 0x80)) {
                    $v -= (1 << $bit);
                }

                return $v;
            case 0x04: // OCTET STRING
                return $b;
            case 0x06: // OID
                return $this->decOid($b);
            case 0x40: // IpAddress
                return strlen($b) === 4 ? implode('.', array_map('ord', str_split($b))) : bin2hex($b);
            case 0x41: // Counter32
            case 0x42: // Gauge32
            case 0x43: // TimeTicks
                return $this->decUint($b);
            case 0x46: // Counter64
                return $this->decBesar($b);
            default:   // NULL, noSuchObject (0x80), noSuchInstance (0x81), endOfMibView (0x82)
                return null;
        }
    }
}
