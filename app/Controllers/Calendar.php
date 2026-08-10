<?php

namespace App\Controllers;

use App\Models\JadwalModel;

class Calendar extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new JadwalModel();
    }

    // Halaman utama kalender
    public function index()
    {
        return view('Calendar/index');
    }

    // Sumber data JSON untuk FullCalendar
    public function events()
    {
        // FullCalendar mengirim start & end sesuai bulan yang sedang tampil.
        // Kalau kosong, default ke bulan berjalan (mengikuti tanggal sistem Windows).
        $start = $this->request->getGet('start') ?? date('Y-m-01');
        $end   = $this->request->getGet('end')   ?? date('Y-m-t');
        $start = substr($start, 0, 10);
        $end   = substr($end, 0, 10);

        $rows      = $this->model->getByRange($start, $end);
        $labelShift = [1 => 'Shift 1', 2 => 'Shift 2', 3 => 'Shift 3', 4 => 'Off'];

        $events = [];
        foreach ($rows as $r) {
            $events[] = [
                'id'            => $r['id'],
                'title'         => ($labelShift[$r['shift']] ?? 'Shift') . ' — ' . $r['nama'],
                'start'         => $r['tanggal'],
                'allDay'        => true,
                'color'         => $r['warna'],
                'extendedProps' => [
                    'shift'      => (int) $r['shift'],
                    'nama'       => $r['nama'],
                    'warna'      => $r['warna'],
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
            'shift'      => $this->request->getPost('shift'),
            'nama'       => $this->request->getPost('nama'),
            'warna'      => $this->request->getPost('warna') ?: '#3788d8',
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        if (! $data['tanggal'] || ! $data['shift'] || ! $data['nama']) {
            return $this->response->setStatusCode(422)
                ->setJSON(['status' => 'error', 'message' => 'Tanggal, shift, dan nama wajib diisi.']);
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

    public function notes()
    {
        $file = WRITEPATH . 'calendar_notes.json';

        // POST -> simpan
        if (strtolower($this->request->getMethod()) === 'post') {
            $raw = $this->request->getPost('data') ?? '[]';
            $arr = json_decode($raw, true);
            if (! is_array($arr)) {
                $arr = [];
            }
            file_put_contents($file, json_encode(array_values($arr), JSON_UNESCAPED_UNICODE));
            return $this->response->setJSON(['status' => 'ok']);
        }

        // GET -> ambil
        $json = is_file($file) ? file_get_contents($file) : '[]';
        $data = json_decode($json, true);
        if (! is_array($data)) {
            $data = [];
        }
        return $this->response->setJSON($data);
    }
}
