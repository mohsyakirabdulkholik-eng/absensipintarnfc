<?php

namespace App\Services;

use App\Repositories\HariLiburRepository;

class HariLiburService
{
    protected HariLiburRepository $repository;

    public function __construct()
    {
        $this->repository = new HariLiburRepository();
    }

    /**
     * Ambil semua data
     */
    public function getAll($idSekolah)
    {
        return $this->repository->getAll($idSekolah);
    }

    /**
     * Ambil satu data
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Simpan data
     */
    public function save(array $data)
    {
        if ($this->repository->cekTanggal($data['id_sekolah'], $data['tanggal'])) {
            throw new \Exception('Tanggal hari libur sudah tersedia.');
        }

        return $this->repository->insert($data);
    }

    /**
     * Update data
     */
    public function update($id, array $data)
    {
        $hariLibur = $this->repository->find($id);

        if (!$hariLibur) {
            throw new \Exception('Data hari libur tidak ditemukan.');
        }

        if (
            $data['tanggal'] !== $hariLibur['tanggal'] &&
            $this->repository->cekTanggal($data['id_sekolah'], $data['tanggal'])
        ) {
            throw new \Exception('Tanggal hari libur sudah tersedia.');
        }

        return $this->repository->updateData($id, $data);
    }

    /**
     * Hapus data
     */
    public function delete($id)
    {
        $hariLibur = $this->repository->find($id);

        if (!$hariLibur) {
            throw new \Exception('Data hari libur tidak ditemukan.');
        }

        return $this->repository->deleteData($id);
    }
}