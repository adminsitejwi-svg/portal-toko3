<?php

namespace App\Libraries;

use App\Models\CatatanModel;

/**
 * Menyusun teks "NOC SHIFT REPORT" untuk dicopy ke WhatsApp
 * (tombol Copy di halaman History Report).
 *
 * Tanpa tanda * (teks polos).
 */
class ShiftReportText
{
    private const GARIS = '────────────────────────────────';

    private const BULAN = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
    ];

    /**
     * @param array $report d_report
     * @param array $data   ['gangguan' => [...], 'followup' => [...], 'pengiriman' => [...],
     *                       'maintenance' => [...], 'catatan' => [...]] milik report ini
     */
    public static function build(array $report, array $data): string
    {
        $hm = static fn ($t) => $t ? substr($t, 0, 5) : '-';

        $lines = [
            'NOC SHIFT REPORT',
            '📅 ' . self::tanggal($report['tanggal']) . '  |  SHIFT ' . mb_strtoupper($report['shift']),
            '👤 PIC: ' . mb_strtoupper(trim($report['pic_shift'])),
            '🕐 Periode: ' . $hm($report['jam_mulai']) . ' - ' . $hm($report['jam_selesai']),
            '',
        ];

        self::section($lines, 'RINGKASAN', [self::v($report['ringkasan'])]);

        self::section($lines, 'GANGGUAN / INCIDENT', self::items($data['gangguan'] ?? [], static function ($r, $no) {
            $judul = $r['grup'] . (self::isi($r['sub_grup']) ? ' / ' . trim($r['sub_grup']) : '') . ' — ' . trim($r['customer_site']);

            return [
                $no . '. ' . $judul . (self::isi($r['cid_ticket']) ? ' | ' . trim($r['cid_ticket']) : ''),
                '   • Problem: ' . self::v($r['gangguan']),
                '   • Action: ' . self::v($r['tindakan']),
                '   • Status: ' . $r['status'] . ' | Priority: ' . $r['priority'],
                '   • Next: ' . self::v($r['next_action']),
                '   • PIC: ' . self::v($r['pic']),
            ];
        }));

        self::section($lines, 'FOLLOW UP', self::items($data['followup'] ?? [], static fn ($r, $no) => [
            $no . '. ' . $r['grup'] . ' — ' . trim($r['customer_site']),
            '   • Issue: ' . self::v($r['issue']),
            '   • Next Action: ' . self::v($r['action']),
            '   • Priority: ' . $r['priority'] . ' | Due: ' . self::v($r['due_date']),
            '   • PIC: ' . self::v($r['pic']),
        ]));

        self::section($lines, 'PENGIRIMAN PERANGKAT', self::items($data['pengiriman'] ?? [], static fn ($r, $no) => [
            $no . '. ' . $r['grup'] . ' — ' . trim($r['customer']),
            '   • Device: ' . self::v($r['device']),
            '   • Status: ' . $r['status'] . ' | Resi: ' . self::v($r['tracking_resi']) . ' | ETA: ' . self::v($r['eta']),
            '   • PIC: ' . self::v($r['pic']),
        ]));

        self::section($lines, 'MAINTENANCE', self::items($data['maintenance'] ?? [], static fn ($r, $no) => [
            $no . '. ' . $r['grup'] . ' — ' . trim($r['site']),
            '   • Equipment: ' . self::v($r['equipment']),
            '   • Issue: ' . self::v($r['issue']),
            '   • Action: ' . self::v($r['action']),
            '   • Status: ' . $r['status'] . ' | Schedule: ' . self::v($r['schedule']),
            '   • PIC: ' . self::v($r['pic']),
        ]));

        self::section($lines, 'AKTIVASI', self::items($data['aktivasi'] ?? [], static fn ($r, $no) => [
            $no . '. ' . self::v($r['nama_pelanggan']) . ' (' . self::v($r['id_pelanggan']) . ')',
            '   • Kapasitas: ' . self::v($r['kapasitas_mbps']) . ' Mbps',
            '   • SN: ' . self::v($r['sn']),
            '   • Tanggal Aktivasi: ' . self::v($r['tanggal_aktivasi']),
        ]));

        self::section($lines, 'CATATAN', self::items($data['catatan'] ?? [], static function ($r, $no) {
            $out = [$no . '.1 ' . $r['grup'] . ' — ' . trim($r['judul'])];
            foreach (CatatanModel::poin($r['catatan']) as $p) {
                $out[] = '   • ' . self::v($p);
            }
            $out[] = '   • Status: ' . $r['status'] . ' | Priority: ' . $r['priority'];

            return $out;
        }), true);

        $lines[] = self::GARIS;
        $lines[] = 'End of Shift Report';
        $lines[] = 'NOC Control';

        return implode("\n", $lines);
    }

    /** Judul bagian + garis + isi; antar bagian diberi 2 baris kosong, bagian terakhir 1. */
    private static function section(array &$lines, string $judul, array $isi, bool $terakhir = false): void
    {
        array_push($lines, $judul, self::GARIS, ...$isi);
        $lines[] = '';
        if (! $terakhir && $judul !== 'RINGKASAN') {
            $lines[] = '';
        }
    }

    /** Baris-baris item bernomor, antar item dipisah 1 baris kosong; "-" bila tidak ada data. */
    private static function items(array $rows, callable $fn): array
    {
        if (! $rows) {
            return ['-'];
        }

        $out = [];
        foreach (array_values($rows) as $i => $r) {
            if ($i) {
                $out[] = '';
            }
            array_push($out, ...$fn($r, $i + 1));
        }

        return $out;
    }

    private static function isi($v): bool
    {
        return trim((string) $v) !== '';
    }

    /** Nilai kosong ditulis "-"; baris baru di dalam isian diberi indentasi agar tetap rapi. */
    private static function v($v): string
    {
        $v = trim((string) $v);

        return $v === '' ? '-' : str_replace("\n", "\n     ", str_replace("\r", '', $v));
    }

    private static function tanggal(string $t): string
    {
        $ts = strtotime($t);

        return date('j', $ts) . ' ' . self::BULAN[(int) date('n', $ts)] . ' ' . date('Y', $ts);
    }
}
