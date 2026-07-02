<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseAdminController;

class DashboardController extends BaseAdminController
{
    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'guru' => 0,
            'siswa' => 0,
            'absensi' => 0,
            'nfc' => 0,
        ];
        return view('dashboard/index', $data);
    }
}