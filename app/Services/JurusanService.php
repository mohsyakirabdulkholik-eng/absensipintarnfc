<?php

namespace App\Services;

use App\Repositories\JurusanRepository;

class JurusanService
{
    protected JurusanRepository $repository;

    public function __construct()
    {
        $this->repository = new JurusanRepository();
    }

    public function getAll($idSekolah)
    {
        return $this->repository->getAll($idSekolah);
    }
    public function find($id)
    {
        return $this->repository->findById($id);
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
}