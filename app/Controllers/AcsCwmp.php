<?php

namespace App\Controllers;

use App\Models\AcsModel;
use App\Models\AcsProvisionModel;
use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;

// Server TR-069 / CWMP untuk ONT (alamat acs/cwmp, tanpa login portal): ONT masuk dengan ACS Username & Password
// dari halaman ACS Settings (HTTP Basic). Mengikuti speednet_acs/public/cwmp.php:
//   Inform -> ONT dicatat -> GetParameterValues (WAN, optik, WiFi, client, info) -> disimpan di d_acs_wan & d_acs_data.
// Perintah yang mengubah ONT (WiFi, blokir MAC, ping, reboot) dibuat Controllers/AcsKontrol. Factory reset tidak disediakan.
// Bukan BaseController: tidak memakai session portal.
class AcsCwmp extends Controller
{
    private const NS_BAWAAN   = 'urn:dslforum-org:cwmp-1-0';
    private const MAKS_BODY   = 4194304; // 4 MB
    private const UMUR_SESI   = 300;     // detik
    private const COOKIE      = 'acs_sesi';

    private AcsModel $model;
    private string $ns = self::NS_BAWAAN;
    private string $ip = '';

    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');
        $this->ip = $this->request->getIPAddress();

        if (! AcsModel::siap() || (string) config('Encryption')->key === '') {
            return $this->teks(503, 'ACS belum siap');
        }
        $this->model = new AcsModel();
        $s           = $this->model->setting();
        $sandi       = AcsModel::dekripsi($s['acs_password']);
        if ($sandi === '') {
            return $this->teks(503, 'ACS Password belum diisi');
        }

        [$user, $pass] = $this->kredensial();
        if ($user === null || ! hash_equals($s['acs_username'], $user) || ! hash_equals($sandi, $pass)) {
            if ($user !== null) {
                $this->catatGagalLogin($user);
            } else {
                $this->catatTanpaLogin();
            }

            return $this->teks(401, 'ACS authentication required')->setHeader('WWW-Authenticate', 'Basic realm="ACS JWI Group"');
        }
        if (strtoupper($this->request->getMethod()) !== 'POST') {
            return $this->teks(200, 'ACS JWI Group aktif');
        }

        // tombol Uji Koneksi (AcsSetting::uji): cukup sampai login, jangan sampai mengambil antrean ONT
        if ($this->request->getHeaderLine('X-ACS-Uji') !== '') {
            return $this->kosong();
        }

        $raw = (string) $this->request->getBody();
        if (strlen($raw) > self::MAKS_BODY) {
            return $this->teks(413, 'Terlalu besar');
        }
        // body kosong = ONT siap menerima perintah
        if (trim($raw) === '') {
            $id = $this->sesi();

            return $id === null ? $this->kosong() : $this->kirimPerintah($id);
        }

        try {
            $jawab = $this->proses($raw);
        } catch (\Throwable $e) {
            log_message('error', 'ACS CWMP: ' . $e->getMessage());
            $this->catatSekali('error', 'Server gagal memproses data ONT', mb_substr($e->getMessage(), 0, 150));

            return $this->teks(500, 'Server error');
        }
        if ($jawab->getStatusCode() === 400) {
            $this->catatSekali('tidakvalid', 'Data ONT tidak valid', 'Login benar, tetapi isi dari ' . $this->asal() . ' tidak bisa dibaca (' . strlen($raw) . ' byte)');
        }

        return $jawab;
    }

    /** Dari mana permintaan ini: ONT (dengan SN), browser, atau lainnya. Untuk keterangan Log Koneksi. */
    private function asal(): string
    {
        $raw = substr((string) $this->request->getBody(), 0, 8192);
        if (preg_match('~<(?:\w+:)?Inform[\s>]~', $raw)) {
            return preg_match('~<SerialNumber[^>]*>\s*([A-Za-z0-9._:-]{4,64})\s*<~', $raw, $m) ? 'ONT ' . $m[1] : 'ONT (SN tidak terbaca)';
        }
        if (strtoupper($this->request->getMethod()) !== 'POST' || stripos($this->request->getHeaderLine('User-Agent'), 'Mozilla') !== false) {
            return 'browser, bukan ONT';
        }

        return 'permintaan lain, bukan Inform ONT';
    }

    /** Catat kegagalan paling sering sekali per menit untuk tiap IP & jenis. */
    private function catatSekali(string $jenis, string $kejadian, string $detail): void
    {
        $kunci = 'acs_gagal_' . md5($this->ip . '|' . $jenis);
        if (! cache($kunci)) {
            cache()->save($kunci, 1, 60);
            $this->model->catat('gagal', $kejadian, $detail, $this->ip);
        }
    }

    private function proses(string $raw): ResponseInterface
    {
        if (stripos($raw, '<!DOCTYPE') !== false) {
            return $this->teks(400, 'SOAP tidak valid');
        }
        libxml_use_internal_errors(true);
        $doc = new \DOMDocument();
        if (! $doc->loadXML($raw, LIBXML_NONET)) {
            return $this->teks(400, 'SOAP tidak valid');
        }
        $xp   = new \DOMXPath($doc);
        $body = $xp->query('/*[local-name()="Envelope"]/*[local-name()="Body"]/*[1]')->item(0);
        if (! $body) {
            return $this->teks(400, 'SOAP Body kosong');
        }
        $cwmpId = $this->isi($xp, '/*[local-name()="Envelope"]/*[local-name()="Header"]/*[local-name()="ID"]');
        // namespace CWMP mengikuti ONT (cwmp-1-0, 1-1, 1-2, ...)
        foreach ($xp->query('namespace::*', $doc->documentElement) as $n) {
            if (str_starts_with($n->nodeValue, 'urn:dslforum-org:cwmp-')) {
                $this->ns = $n->nodeValue;
            }
        }

        if ($body->localName === 'Inform') {
            return $this->inform($xp, $body, $cwmpId);
        }

        $id = $this->sesi();
        if ($id === null) {
            return $this->kosong();
        }

        switch ($body->localName) {
            case 'GetParameterValuesResponse':
                $this->nilai($xp, $body, $id, $cwmpId);

                return $this->kirimPerintah($id);

            case 'Fault':
                $kode  = $this->isi($xp, './/*[local-name()="FaultCode"]', $body);
                $pesan = $this->isi($xp, './/*[local-name()="FaultString"]', $body);
                $teks  = trim('Fault ' . $kode . ' ' . $pesan);
                // SetParameterValues: sebutkan parameter yang ditolak
                $tolak = $this->isi($xp, './/*[local-name()="SetParameterValuesFault"]/*[local-name()="ParameterName"]', $body);
                if ($tolak !== '') {
                    $kode2 = $this->isi($xp, './/*[local-name()="SetParameterValuesFault"]/*[local-name()="FaultCode"]', $body);
                    $teks .= ' - ' . substr((string) strrchr('.' . $tolak, '.'), 1) . ' (' . $kode2 . ')';
                }
                $task = $this->model->selesaikanAntrean($id, $cwmpId, false, $teks);
                if ($task && $task['perintah'] !== 'GetParameterValues') {
                    // {N} pada langkah pembersihan sudah terisi saat langkahnya dibuat
                    $this->model->lanjutkan($task, false);
                }
                $this->model->catat('gagal', 'Fault dari ONT', $teks, $this->ip, $id);

                return $this->kirimPerintah($id);

            case 'SetParameterValuesResponse':
            case 'AddObjectResponse':
            case 'DeleteObjectResponse':
            case 'RebootResponse':
                $status   = $this->isi($xp, './*[local-name()="Status"]', $body);
                $instance = $this->isi($xp, './*[local-name()="InstanceNumber"]', $body);
                $task     = $this->model->selesaikanAntrean($id, $cwmpId, true, 'Berhasil' . ($status === '1' ? ', berlaku setelah ONT reboot' : ''));
                if ($task && $task['perintah'] . 'Response' === $body->localName) {
                    $this->model->lanjutkan($task, true, ctype_digit($instance) ? $instance : '');
                }

                return $this->kirimPerintah($id);

            case 'TransferComplete':
            case 'AutonomousTransferComplete':
                return $this->soap('<cwmp:' . $body->localName . 'Response/>', $cwmpId);

            case 'GetRPCMethods':
                $m = ['Inform', 'GetRPCMethods', 'TransferComplete'];

                return $this->soap('<cwmp:GetRPCMethodsResponse><MethodList soap-enc:arrayType="xsd:string[' . count($m) . ']"><string>'
                    . implode('</string><string>', $m) . '</string></MethodList></cwmp:GetRPCMethodsResponse>', $cwmpId);

            default:
                if (str_ends_with($body->localName, 'Response')) {
                    return $this->kirimPerintah($id);
                }

                return $this->soap('<soap-env:Fault><faultcode>Server</faultcode><faultstring>CWMP fault</faultstring><detail>'
                    . '<cwmp:Fault><FaultCode>8000</FaultCode><FaultString>Method not supported</FaultString></cwmp:Fault></detail></soap-env:Fault>', $cwmpId);
        }
    }

    private function inform(\DOMXPath $xp, \DOMElement $body, string $cwmpId): ResponseInterface
    {
        $serial = $this->isi($xp, './*[local-name()="DeviceId"]/*[local-name()="SerialNumber"]', $body);
        if ($serial === '') {
            return $this->teks(400, 'SerialNumber kosong');
        }
        $this->lupakanTanpaLogin($serial);
        $nilai = $this->daftarNilai($xp, $body);
        $akhir = static function (string $akhiran) use ($nilai): string {
            foreach ($nilai as $k => $v) {
                if (str_ends_with($k, $akhiran)) {
                    return $v;
                }
            }

            return '';
        };
        $event = [];
        foreach ($xp->query('.//*[local-name()="EventStruct"]/*[local-name()="EventCode"]', $body) as $n) {
            $event[] = trim($n->textContent);
        }

        $dev = $this->model->simpanInform([
            'serial'                 => $serial,
            'oui'                    => $this->isi($xp, './*[local-name()="DeviceId"]/*[local-name()="OUI"]', $body),
            'manufacturer'           => $this->isi($xp, './*[local-name()="DeviceId"]/*[local-name()="Manufacturer"]', $body),
            'product_class'          => $this->isi($xp, './*[local-name()="DeviceId"]/*[local-name()="ProductClass"]', $body),
            'software_version'       => $akhir('.DeviceInfo.SoftwareVersion'),
            'hardware_version'       => $akhir('.DeviceInfo.HardwareVersion'),
            'connection_request_url' => $akhir('.ManagementServer.ConnectionRequestURL'),
            'event'                  => $event,
            'ip'                     => $this->ip,
        ]);
        $id = (int) $dev['id'];

        $this->model->catat('ok', implode(', ', $event) ?: 'Inform', 'Inform dari ' . $serial, $this->ip, $id);
        $this->model->pulihkanAntrean($id);

        // baca semua data kalau ONT baru / baru menyala, WAN kalau ada nilai berubah, selain itu yang sudah waktunya
        $nyala = (bool) preg_grep('/^(0 BOOTSTRAP|1 BOOT)/', $event);
        $this->model->antreBaca($dev, $nyala, preg_grep('/^4 VALUE CHANGE/', $event) ? ['wan'] : []);

        // hasil ping client sudah selesai di ONT
        if (preg_grep('/^8 DIAGNOSTICS COMPLETE/', $event)) {
            $this->model->antreKhusus($id, 'ping', ['InternetGatewayDevice.IPPingDiagnostics.']);
        }

        // ONT yang didaftarkan di halaman Provisioning; gagal di sini tidak boleh menggagalkan Inform
        try {
            (new AcsProvisionModel())->terapkan($dev, $this->model, $this->ip);
        } catch (\Throwable $e) {
            log_message('error', 'ACS provisioning: ' . $e->getMessage());
        }

        $this->response->setCookie($this->cookie($id));

        return $this->soap('<cwmp:InformResponse><MaxEnvelopes>1</MaxEnvelopes></cwmp:InformResponse>', $cwmpId);
    }

    private function nilai(\DOMXPath $xp, \DOMElement $body, int $id, string $cwmpId): void
    {
        $nilai = $this->daftarNilai($xp, $body);
        $task  = $this->model->selesaikanAntrean($id, $cwmpId, true, count($nilai) . ' parameter diterima');
        if (! $task || $task['perintah'] !== 'GetParameterValues') {
            return; // bukan jawaban perintah baca yang dikirim ACS
        }
        $this->model->simpanNilai($id, (string) (json_decode((string) $task['payload'], true)['grup'] ?? ''), $nilai);
    }

    /** @return array<string, string> */
    private function daftarNilai(\DOMXPath $xp, \DOMElement $body): array
    {
        $hasil = [];
        foreach ($xp->query('./*[local-name()="ParameterList"]/*[local-name()="ParameterValueStruct"]', $body) as $n) {
            $nama = $this->isi($xp, './*[local-name()="Name"]', $n);
            if ($nama !== '') {
                $hasil[$nama] = $this->isi($xp, './*[local-name()="Value"]', $n);
            }
        }

        return $hasil;
    }

    private function kirimPerintah(int $id): ResponseInterface
    {
        while ($t = $this->model->ambilAntrean($id)) {
            $isi = $this->isiPerintah($t['perintah'], json_decode((string) $t['payload'], true) ?: [], $t['kunci']);
            if ($isi !== null) {
                return $this->soap($isi, $t['kunci']);
            }
            $this->model->selesaikanAntrean($id, $t['kunci'], false, 'Perintah tidak didukung');
        }

        return $this->kosong();
    }

    /** Isi SOAP satu perintah; null = tidak didukung. */
    private function isiPerintah(string $perintah, array $p, string $kunci): ?string
    {
        switch ($perintah) {
            case 'GetParameterValues':
                $names = array_filter($p['names'] ?? [], static fn ($n) => is_string($n) && $n !== '');
                if (! $names) {
                    return null;
                }

                return '<cwmp:GetParameterValues><ParameterNames soap-enc:arrayType="xsd:string[' . count($names) . ']">'
                    . implode('', array_map(fn ($n) => '<string>' . $this->x($n) . '</string>', $names)) . '</ParameterNames></cwmp:GetParameterValues>';

            case 'SetParameterValues':
                if (empty($p['parameter'])) {
                    return null;
                }
                $isi = '';
                foreach ($p['parameter'] as $x) {
                    $v    = ! empty($x['enk']) ? AcsModel::dekripsi($x['v']) : (string) $x['v'];
                    $isi .= '<ParameterValueStruct><Name>' . $this->x($x['n']) . '</Name><Value xsi:type="' . $this->x($x['t']) . '">' . $this->x($v) . '</Value></ParameterValueStruct>';
                }

                return '<cwmp:SetParameterValues><ParameterList soap-enc:arrayType="cwmp:ParameterValueStruct[' . count($p['parameter']) . ']">' . $isi
                    . '</ParameterList><ParameterKey>' . $this->x($kunci) . '</ParameterKey></cwmp:SetParameterValues>';

            case 'AddObject':
            case 'DeleteObject':
                return empty($p['objek']) || str_contains($p['objek'], '{N}') ? null
                    : '<cwmp:' . $perintah . '><ObjectName>' . $this->x($p['objek']) . '</ObjectName><ParameterKey>' . $this->x($kunci) . '</ParameterKey></cwmp:' . $perintah . '>';

            case 'Reboot':
                return '<cwmp:Reboot><CommandKey>' . $this->x($kunci) . '</CommandKey></cwmp:Reboot>';
        }

        return null;
    }

    // ================= LOGIN & SESI =================

    /** @return array{0: string|null, 1: string} */
    private function kredensial(): array
    {
        if (isset($_SERVER['PHP_AUTH_USER'])) {
            return [(string) $_SERVER['PHP_AUTH_USER'], (string) ($_SERVER['PHP_AUTH_PW'] ?? '')];
        }
        // PHP-FPM / CGI: header Authorization diteruskan .htaccess
        $h = (string) ($_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '');
        if (preg_match('/^Basic\s+(\S+)$/i', $h, $m)) {
            $p = explode(':', (string) base64_decode($m[1], true), 2);
            if (count($p) === 2) {
                return [$p[0], $p[1]];
            }
        }

        return [null, ''];
    }

    /** Login gagal dicatat paling sering sekali per menit per IP (agar log tidak dibanjiri). */
    /**
     * Inform pertama ONT selalu tanpa login, lalu diulang dengan login setelah dijawab 401 (normal, tidak dicatat).
     * Dicatat kalau SN yang sama terus datang tanpa login: ONT sampai ke server, tetapi tidak mengirim username / password.
     */
    private function catatTanpaLogin(): void
    {
        $raw = substr((string) $this->request->getBody(), 0, 8192);
        if (! preg_match('~<(?:\w+:)?Inform[\s>]~', $raw) || ! preg_match('~<SerialNumber[^>]*>\s*([A-Za-z0-9._:-]{4,64})\s*<~', $raw, $m)) {
            return;
        }
        $kunci = 'acs_tanya_' . md5($this->ip);
        $sn    = cache($kunci) ?: [];
        if (! isset($sn[$m[1]]) && count($sn) >= 50) {
            return;
        }
        $sn[$m[1]] = ($sn[$m[1]] ?? 0) + 1;
        cache()->save($kunci, $sn, 3600);
        if ($sn[$m[1]] === 2 || $sn[$m[1]] % 12 === 0) {
            $h = (string) ($_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '');
            $this->model->catat('gagal', AcsModel::LOGIN_DITOLAK, 'ONT ' . $m[1] . ' sampai ke server, tetapi '
                . ($h === '' ? 'tidak mengirim username / password ACS' : 'memakai cara login ' . mb_substr(preg_replace('/[^A-Za-z].*/s', '', $h), 0, 20) . ' yang tidak didukung'), $this->ip);
        }
    }

    /** SN ini berhasil login: hitungan tanpa login dihapus. */
    private function lupakanTanpaLogin(string $serial): void
    {
        $kunci = 'acs_tanya_' . md5($this->ip);
        $sn    = cache($kunci) ?: [];
        if (isset($sn[$serial])) {
            unset($sn[$serial]);
            $sn ? cache()->save($kunci, $sn, 3600) : cache()->delete($kunci);
        }
    }

    private function catatGagalLogin(string $user): void
    {
        $asal = $this->asal();
        $this->catatSekali('login' . $asal, AcsModel::LOGIN_DITOLAK, 'Username "' . mb_substr($user, 0, 64) . '" atau password tidak sesuai (dari ' . $asal . ')');
    }

    private function tanda(string $isi): string
    {
        return hash_hmac('sha256', 'acs|' . $isi, (string) config('Encryption')->key);
    }

    /** Cookie sesi bertanda tangan: perintah tidak tertukar antar ONT yang IP-nya sama (NAT). */
    private function cookie(int $id): \CodeIgniter\Cookie\Cookie
    {
        $isi = $id . '.' . (time() + self::UMUR_SESI);

        return new \CodeIgniter\Cookie\Cookie(self::COOKIE, $isi . '.' . $this->tanda($isi), [
            'expires' => time() + self::UMUR_SESI, 'path' => '/', 'domain' => '', 'prefix' => '', 'secure' => false, 'httponly' => true, 'samesite' => 'Lax', 'raw' => true,
        ]);
    }

    private function sesi(): ?int
    {
        $c = $_COOKIE[self::COOKIE] ?? null;
        if (is_string($c) && $c !== '') {
            $p = explode('.', $c);
            if (count($p) === 3 && ctype_digit($p[0]) && ctype_digit($p[1]) && (int) $p[1] >= time()
                && hash_equals($this->tanda($p[0] . '.' . $p[1]), $p[2])) {
                return (int) $p[0];
            }

            return null;
        }

        // ONT tanpa cookie: hanya kalau satu-satunya ONT dari IP ini
        return $this->model->tunggalDariIp($this->ip);
    }

    // ================= JAWABAN =================

    private function isi(\DOMXPath $xp, string $q, ?\DOMNode $dari = null): string
    {
        $n = $xp->query($q, $dari)->item(0);

        return $n ? trim($n->textContent) : '';
    }

    private function x(string $s): string
    {
        return htmlspecialchars($s, ENT_XML1 | ENT_QUOTES, 'UTF-8');
    }

    private function soap(string $body, string $id): ResponseInterface
    {
        $head = $id === '' ? '' : '<cwmp:ID soap-env:mustUnderstand="1">' . $this->x($id) . '</cwmp:ID>';

        return $this->response->setStatusCode(200)->setHeader('Content-Type', 'text/xml; charset=utf-8')->setBody(
            '<?xml version="1.0" encoding="UTF-8"?><soap-env:Envelope xmlns:soap-env="http://schemas.xmlsoap.org/soap/envelope/"'
            . ' xmlns:soap-enc="http://schemas.xmlsoap.org/soap/encoding/" xmlns:xsd="http://www.w3.org/2001/XMLSchema"'
            . ' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:cwmp="' . $this->x($this->ns) . '">'
            . '<soap-env:Header>' . $head . '</soap-env:Header><soap-env:Body>' . $body . '</soap-env:Body></soap-env:Envelope>'
        );
    }

    private function kosong(): ResponseInterface
    {
        return $this->response->setStatusCode(204)->setBody('');
    }

    private function teks(int $kode, string $isi): ResponseInterface
    {
        return $this->response->setStatusCode($kode)->setHeader('Content-Type', 'text/plain; charset=utf-8')->setBody($isi);
    }
}
