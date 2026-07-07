<?php

namespace App\Controllers;

use App\Models\ActivityLogModel;

class Logs extends BaseController
{
    public function index()
    {
        $model = new ActivityLogModel();

        // Filter opsional dari query string (?action=&table=&dari=&sampai=&q=)
        $action = trim((string) $this->request->getGet('action'));
        $table  = trim((string) $this->request->getGet('table'));
        $dari   = trim((string) $this->request->getGet('dari'));
        $sampai = trim((string) $this->request->getGet('sampai'));
        $q      = trim((string) $this->request->getGet('q'));

        $builder = $model->orderBy('created_at', 'DESC');

        if ($action !== '') {
            $builder->where('action', $action);
        }
        if ($table !== '') {
            $builder->where('table_name', $table);
        }
        if ($dari !== '') {
            $builder->where('created_at >=', $dari . ' 00:00:00');
        }
        if ($sampai !== '') {
            $builder->where('created_at <=', $sampai . ' 23:59:59');
        }
        if ($q !== '') {
            $builder->groupStart()
                ->like('username', $q)
                ->orLike('description', $q)
                ->orLike('record_id', $q)
                ->groupEnd();
        }

        // Batasi 5000 baris terbaru agar halaman tetap ringan.
        $data['logs'] = $builder->findAll(5000);

        // Daftar tabel unik untuk dropdown filter.
        $data['tables'] = array_column(
            $model->select('table_name')->distinct()->orderBy('table_name', 'ASC')->findAll(),
            'table_name'
        );

        // Nilai filter agar tetap terisi di form.
        $data['filter'] = compact('action', 'table', 'dari', 'sampai', 'q');

        return view('Logs/index', $data);
    }
}
