<?php

namespace App\Repositories;

use App\Models\HariAktifSekolahModel;

class HariAktifSekolahRepository
{
    protected HariAktifSekolahModel $model;

    public function __construct()
    {
        $this->model = new HariAktifSekolahModel();
    }

    /**
     * Cek apakah hari tertentu aktif.
     */
    public function isHariAktif(
        int $idSekolah,
        int $hari
    ): bool {

        return $this->model
            ->where('id_sekolah', $idSekolah)
            ->where('hari', $hari)
            ->where('aktif', 1)
            ->countAllResults() > 0;
    }

    /**
     * Ambil seluruh konfigurasi hari aktif.
     */
    public function getAll(
        int $idSekolah
    ): array {

        return $this->model
            ->where('id_sekolah', $idSekolah)
            ->orderBy('hari', 'ASC')
            ->findAll();
    }

    /**
     * Update status hari.
     */
    public function updateStatus(
        int $idSekolah,
        int $hari,
        int $aktif
    ): bool {

        return $this->model
            ->where('id_sekolah', $idSekolah)
            ->where('hari', $hari)
            ->set([
                'aktif' => $aktif
            ])
            ->update();

    }
}