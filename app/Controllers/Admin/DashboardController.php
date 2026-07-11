<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseAdminController;
use App\Services\DashboardService;

class DashboardController extends BaseAdminController
{
    protected DashboardService $dashboardService;

    public function __construct()
    {
        $this->dashboardService = new DashboardService();
    }

    public function index()
    {
        $statistik = $this->dashboardService->getStatistik(
            session('id_sekolah')
        );
        $grafik = $this->dashboardService->getGrafikKehadiran(session('id_sekolah'));
        $aktivitas = $this->dashboardService->getAktivitasTerbaru(session('id_sekolah'));
        $statusHariIni = $this->dashboardService->getStatusHariIni(session('id_sekolah'));

        $data = [
            'title' => 'Dashboard',
            'user'  => $this->currentUser(),
            'guru' => $statistik['guru'],
            'siswa' => $statistik['siswa'],
            'absensi' => $statistik['absensi'],
            'nfc' => $statistik['nfc'],
            'grafik' => $grafik,
            'aktivitas' => $aktivitas,
            'statusHariIni' => $statusHariIni,
            'tahunAktif' => $statistik['tahunAktif']['nama'] ?? '-',
            'semesterAktif' => $statistik['semesterAktif']['nama'] ?? '-',
        ];

        return view('dashboard/index', $data);
    }
}