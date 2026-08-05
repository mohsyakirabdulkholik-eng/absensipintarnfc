<?php

namespace App\Services;

use App\Repositories\HariAktifSekolahRepository;

class HariAktifSekolahService
{
    protected HariAktifSekolahRepository $repository;

    public function __construct()
    {
        $this->repository = new HariAktifSekolahRepository();
    }

    /**
     * Cek apakah hari aktif.
     */
    public function isHariAktif(
        int $idSekolah,
        int $hari
    ): bool {

        return $this->repository
            ->isHariAktif(
                $idSekolah,
                $hari
            );

    }

    /**
     * Ambil semua hari.
     */
    public function getAll(
        int $idSekolah
    ): array {

        return $this->repository
            ->getAll($idSekolah);

    }
}