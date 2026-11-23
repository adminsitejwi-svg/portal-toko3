<?php

namespace App\Commands;

use App\Models\DeviceMonitoringModel;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Polling SNMP Device Monitoring dari cron (tanpa perlu membuka halaman).
 *
 *   php spark devicemonitoring:poll
 *
 * Contoh cron tiap menit di hosting:
 *   * * * * * cd /path/portal-toko3 && php spark devicemonitoring:poll >/dev/null 2>&1
 *
 * Hanya device yang sudah waktunya (sesuai polling interval) yang di-polling.
 */
class DeviceMonitoringPoll extends BaseCommand
{
    protected $group       = 'Device Monitoring';
    protected $name        = 'devicemonitoring:poll';
    protected $description = 'Polling SNMP device yang sudah jatuh tempo (polling interval).';

    public function run(array $params)
    {
        date_default_timezone_set('Asia/Jakarta');

        $db = \Config\Database::connect();
        if (! $db->tableExists('d_device_monitoring')) {
            CLI::error('Tabel d_device_monitoring belum ada. Jalankan database/d_device_monitoring.sql.');

            return EXIT_ERROR;
        }

        // batas 50 detik agar tidak bertumpuk dengan cron menit berikutnya
        $n = (new DeviceMonitoringModel())->pollJatuhTempo(50.0);
        CLI::write('Device di-polling: ' . $n, 'green');

        return EXIT_SUCCESS;
    }
}
