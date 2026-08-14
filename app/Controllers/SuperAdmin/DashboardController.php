<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Services\SuperadminDashboardService;

class DashboardController extends BaseController
{
    protected SuperadminDashboardService $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new SuperadminDashboardService();
    }

    public function index()
    {
        $data = $this->dashboardService
            ->getDashboardData();

        return view('superadmin/dashboard', [
            'title' => 'Dashboard Superadmin',
            'data'  => $data
        ]);
    }
}