<?php

namespace App\Services;

use App\Repositories\DashboardRepository;

class DashboardService
{
    protected DashboardRepository $repository;

    public function __construct()
    {
        $this->repository = new DashboardRepository();
    }

    public function getStatistik($idSekolah): array
    {
        return $this->repository->getStatistik($idSekolah);
    }

    /**
     * Grafik Kehadiran
     */
    public function getGrafikKehadiran($idSekolah): array
    {
        return $this->repository->getGrafikKehadiran($idSekolah);
    }
    public function getAktivitasTerbaru($idSekolah)
    {
        return $this->repository->getAktivitasTerbaru($idSekolah);
    }
    public function getStatusHariIni($idSekolah)
    {
        return $this->repository->getStatusHariIni($idSekolah);
    }
}