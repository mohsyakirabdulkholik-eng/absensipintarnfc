<?php

namespace App\Services;

use App\Repositories\SiswaRepository;

class SiswaService
{
    protected SiswaRepository $repository;

    public function __construct()
    {
        $this->repository = new SiswaRepository();
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

    public function update($id, array $data)
    {
        return $this->repository->updateData($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->deleteData($id);
    }

    public function cekNis($nis)
    {
        return $this->repository->cekNis($nis);
    }

    public function cekNisn($nisn)
    {
        return $this->repository->cekNisn($nisn);
    }

    public function cekUid($uid)
    {
        return $this->repository->cekUid($uid);
    }
}