<?php

namespace App\Controllers\Admin;
use App\Controllers\BaseAdminController;

class DashboardController extends BaseAdminController
{
    public function index()
    {
        // $data = [
        //     'title' => 'Dashboard',
        //     'user' => $this->user,
        // ];
        return view('dashboard/index');
    }
}