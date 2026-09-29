<?php

namespace App\Controllers;

use App\Models\JadwalModel;
use App\Models\PiketModel;

class Piket extends BaseController
{
    protected $model;

    // Label & warna piket (warna otomatis mengikuti nomor piket)
    public const LABEL = [1 => 'PIKET 1', 2 => 'PIKET 2', 3 => 'PIKET 3'];
    public const WARNA = [1 => '#FFA500', 2 => '#FFFF00', 3 => '#92D050']; // oranye, kuning, hijau

    public function __construct()
    {
        $this->model = new PiketModel();
    }

    // Halaman utama jadwal piket
    public function index()
    {
        // Nama untuk dropdown diambil dari data shift (jadwal_noc)
        $names = (new JadwalModel())->distinct()->select('nama')
            ->where('nama !=', '')
            ->orderBy('nama', 'ASC')
            ->findColumn('nama') ?? [];

        return view('Piket/index', [
            'names' => $names,
            'label' => self::LABEL,
            'warna' => self::WARNA,
        ]);
    }

    // Sumber data JSON untuk FullCalendar
    public function events()
    {
        $start = substr($this->request->getGet('start') ?? date('Y-m-01'), 0, 10);
        $end   = substr($this->request->getGet('end')   ?? date('Y-m-t'), 0, 10);

        $events = [];
        foreach ($this->model->getByRange($start, $end) as $r) {
            $p = (int) $r['piket'];
            $events[] = [
                'id'            => $r['id'],
                'title'         => (self::LABEL[$p] ?? 'PIKET') . ' — ' . $r['nama'],
                'start'         => $r['tanggal'],
                'allDay'        => true,
                'color'         => self::WARNA[$p] ?? '#04a9f5',
                'extendedProps' => [
                    'piket'      => $p,
                    'nama'       => $r['nama'],
                    'keterangan' => $r['keterangan'],
                    'tanggal'    => $r['tanggal'],
                ],
            ];
        }

        return $this->response->setJSON($events);
    }

    // Simpan: tambah (id kosong) atau edit (id ada)
    public function save()
    {
        $id   = $this->request->getPost('id');
        $data = [
            'tanggal'    => $this->request->getPost('tanggal'),
            'nama'       => $this->request->getPost('nama'),
            'piket'      => (int) $this->request->getPost('piket'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        if (! $data['tanggal'] || ! $data['nama'] || ! isset(self::LABEL[$data['piket']])) {
            return $this->response->setStatusCode(422)
                ->setJSON(['status' => 'error', 'message' => 'Tanggal, nama, dan piket wajib diisi.']);
        }

        if ($id) {
            $this->model->update($id, $data);
        } else {
            $id = $this->model->insert($data);
        }

        return $this->response->setJSON(['status' => 'ok', 'id' => $id]);
    }

    // Hapus
    public function delete($id = null)
    {
        if ($id) {
            $this->model->delete($id);
        }
        return $this->response->setJSON(['status' => 'ok']);
    }
}
