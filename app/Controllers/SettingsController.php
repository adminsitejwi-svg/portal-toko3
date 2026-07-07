<?php

namespace App\Controllers;

use App\Models\LoginModel;

class SettingsController extends BaseController
{
    protected $loginModel;

    public function __construct()
    {
        $this->loginModel = new LoginModel();
    }

    public function index()
    {
        $db = \Config\Database::connect();

        $data['login'] = $db->table('login')
            ->select('id, username, created_at')
            ->get()
            ->getResultArray();

        return view('settings/index', $data);
    }

    public function delete($id)
    {
        $this->loginModel->delete($id);

        return redirect()->back()
            ->with('success', 'User berhasil dihapus');
    }
}
