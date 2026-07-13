<?php

namespace App\Services;

use App\Repositories\SettingSekolahRepository;

class SettingSekolahService
{
    protected SettingSekolahRepository $repository;

    public function __construct()
    {
        $this->repository = new SettingSekolahRepository();
    }

    /**
     * Ambil data sekolah
     */
    public function getById(int $idSekolah): ?array
    {
        return $this->repository
            ->getById($idSekolah);
    }

    /**
     * Update data sekolah
     */
    public function update(int $idSekolah, array $data): bool
    {
        $updateData = [

            'nama_sekolah'   => trim($data['nama_sekolah']),

            'alamat'         => trim($data['alamat']),

            'telepon'        => trim($data['telepon']),

            'email'          => trim($data['email']),

            'website'        => trim($data['website']),

            'kepala_sekolah' => trim($data['kepala_sekolah']),

            'motto'          => trim($data['motto'])

        ];

        return $this->repository
            ->update($idSekolah, $updateData);
    }
}