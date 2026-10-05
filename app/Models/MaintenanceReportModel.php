<?php

namespace App\Models;

use App\Models\BaseModel;

class MaintenanceReportModel extends BaseModel
{
    protected $table            = 'd_maintenance_report';
    protected $primaryKey       = 'id';

    protected $allowedFields = [
        'nama',
        'id_pelanggan',
        'nama_pelanggan',
        'alamat',
        'odp_port',
        'maintenance',
        'perpindahan_perangkat',
        'kendala',
        'no_tiket',
        'action',
        'status',
        'created_at'
    ];

    protected $useTimestamps = true;

    protected $createdField  = 'created_at';

    // Nonaktifkan updated_at
    protected $updatedField  = '';

    /** Pilihan status => class badge di halaman. */
    public const STATUS = [
        'On Progress' => 'badge-service',
        'Monitoring'  => 'badge-due',
        'Pending'     => 'badge-pending',
        'Resolved'    => 'badge-paid',
    ];
}
