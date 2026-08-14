<?php

namespace App\Services;

use App\Repositories\SuperadminDashboardRepository;

class SuperadminDashboardService
{
    protected SuperadminDashboardRepository $repository;

    public function __construct()
    {
        $this->repository = new SuperadminDashboardRepository();
    }

    public function getDashboardData(): array
    {
        return [
            'totalSekolah' => $this->repository->countSekolah(),

            'sekolahAktif' => $this->repository->countSekolahAktif(),

            'langgananAktif' => $this->repository
                ->countLanggananAktif(),

            'langgananExpired' => $this->repository
                ->countLanggananExpired(),

            'sekolahTerbaru' => $this->repository
                ->getSekolahTerbaru(5),

            'langgananAkanBerakhir' => $this->repository
                ->getLanggananAkanBerakhir(7),
        ];
    }
}