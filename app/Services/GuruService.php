<?php

namespace App\Services;

use App\Repositories\GuruRepository;

class GuruService
{
    protected GuruRepository $repository;

    public function __construct()
    {
        $this->repository = new GuruRepository();
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
    public function cekNip($nip)
    {
        return $this->repository->cekNip($nip);
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