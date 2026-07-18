<?php

namespace App\Services;

use App\Repositories\KartuNfcRepository;

class KartuNfcService
{
    protected KartuNfcRepository $repository;

    public function __construct()
    {
        $this->repository = new KartuNfcRepository();
    }

    /**
     * Ambil semua kartu NFC berdasarkan sekolah
     */
    public function getAll(int $idSekolah): array
    {
        return $this->repository->getAll($idSekolah);
    }

    /**
     * Ambil detail kartu
     */
    public function find(int $id, int $idSekolah): array
    {
        $kartu = $this->repository->find($id);

        if (!$kartu) {
            throw new \Exception(
                'Data kartu NFC tidak ditemukan.'
            );
        }

        // Pastikan kartu milik sekolah yang sedang login
        if ((int) $kartu['id_sekolah'] !== $idSekolah) {
            throw new \Exception(
                'Anda tidak memiliki akses ke kartu NFC ini.'
            );
        }

        return $kartu;
    }

    /**
     * Ubah status kartu
     */
    public function updateStatus(
        int $id,
        int $idSekolah,
        string $status
    ): bool {

        $kartu = $this->find(
            $id,
            $idSekolah
        );

        $allowedStatus = [
            'aktif',
            'nonaktif',
            'hilang'
        ];

        if (!in_array($status, $allowedStatus, true)) {
            throw new \Exception(
                'Status kartu NFC tidak valid.'
            );
        }

        // Jika status sama, tidak perlu update
        if ($kartu['status'] === $status) {
            throw new \Exception(
                'Kartu NFC sudah berstatus ' . $status . '.'
            );
        }

        return $this->repository->updateStatus(
            $id,
            $status
        );
    }
}