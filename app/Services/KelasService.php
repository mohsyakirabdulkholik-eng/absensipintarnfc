<?php

namespace App\Services;

use App\Repositories\KelasRepository;

class KelasService
{
    protected KelasRepository $repository;

    public function __construct()
    {
        $this->repository = new KelasRepository();
    }

    public function getAll($idSekolah)
    {
        return $this->repository->getAll($idSekolah);
    }
    public function find($id)
    {
        return $this->repository->find($id);
    }
    public function getBySekolah($idSekolah)
    {
        return $this->repository->getBySekolah($idSekolah);
    }
    public function save(array $data)
    {
        return $this->repository->insert($data);
    }
    public function isExists($idSekolah, $namaKelas)
    {
        return $this->repository->isExists($idSekolah, $namaKelas);
    }
    public function update($id, array $data)
    {
        return $this->repository->updateData($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->deleteData($id);
    }
}