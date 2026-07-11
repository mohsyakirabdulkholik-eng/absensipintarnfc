<?php

namespace App\Services;

use App\Repositories\TahunAjaranRepository;

class TahunAjaranService
{
    protected TahunAjaranRepository $repository;

    public function __construct()
    {
        $this->repository = new TahunAjaranRepository();
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
        if ($this->repository->cekNama($data['id_sekolah'], $data['nama'])) {
            throw new \Exception('Tahun ajaran sudah tersedia.');
        }

        return $this->repository->insert($data);
    }

    /**
     * Update data
     */
    public function update($id, array $data)
    {
        $tahun = $this->repository->find($id);

        if (!$tahun) {
            throw new \Exception('Data tahun ajaran tidak ditemukan.');
        }

        if (
            $data['nama'] !== $tahun['nama'] &&
            $this->repository->cekNama($data['id_sekolah'], $data['nama'])
        ) {
            throw new \Exception('Tahun ajaran sudah tersedia.');
        }

        return $this->repository->updateData($id, $data);
    }

    /**
     * Hapus data
     */
    public function delete($id)
    {
        $tahun = $this->repository->find($id);

        if (!$tahun) {
            throw new \Exception('Data tahun ajaran tidak ditemukan.');
        }

        if ($tahun['aktif']) {
            throw new \Exception('Tahun ajaran yang sedang aktif tidak dapat dihapus.');
        }

        return $this->repository->deleteData($id);
    }

    /**
     * Jadikan tahun ajaran aktif
     */
    public function setAktif($id)
    {
        $tahun = $this->repository->find($id);

        if (!$tahun) {
            throw new \Exception('Data tahun ajaran tidak ditemukan.');
        }

        $this->repository->nonaktifkanSemua($tahun['id_sekolah']);

        return $this->repository->aktifkan($id);
    }
}