<?php

namespace App\Controllers;

use App\Libraries\HakAkses;
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
        $db    = \Config\Database::connect();
        $akses = HakAkses::instance();

        $builder = $db->table('login')->select('login.id, login.username, login.created_at');

        // kolom Role (bila SQL hak akses sudah dijalankan)
        if ($akses->tersedia()) {
            $builder->select('m_role.nama AS role_nama, login.akses_khusus')
                ->join('m_role', 'm_role.id = login.role_id', 'left');
        }

        $data['login']     = $builder->get()->getResultArray();
        $data['pakaiRole'] = $akses->tersedia();
        $data['isAdmin']   = $akses->isAdmin();

        return view('settings/index', $data);
    }

    // Hanya Administrator (dijaga AuthFilter lewat Config\HakAkses::$khususAdmin)
    public function delete($id)
    {
        $id = (int) $id;

        if ($id === (int) session('user_id')) {
            return redirect()->back()->with('error', 'Akun yang sedang dipakai tidak bisa dihapus dari sini.');
        }

        if (HakAkses::instance()->tersedia() && $this->adminTerakhir($id)) {
            return redirect()->back()->with('error', 'Tidak bisa menghapus Administrator terakhir.');
        }

        $this->loginModel->delete($id);

        // akses khusus akun ini ikut dihapus
        if (HakAkses::instance()->tersedia()) {
            \Config\Database::connect()->table('m_user_akses')->where('user_id', $id)->delete();
        }

        return redirect()->back()
            ->with('success', 'User berhasil dihapus');
    }

    // true bila $id adalah satu-satunya akun Administrator
    private function adminTerakhir(int $id): bool
    {
        $db = \Config\Database::connect();

        $admins = $db->table('login')->select('login.id')
            ->join('m_role', 'm_role.id = login.role_id')
            ->where('m_role.is_admin', 1)->get()->getResultArray();
        $ids = array_map('intval', array_column($admins, 'id'));

        return in_array($id, $ids, true) && count($ids) <= 1;
    }
}
