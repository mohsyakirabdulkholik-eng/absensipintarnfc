<?php

namespace App\Services;

use App\Repositories\SuperadminSekolahRepository;

class SuperadminSekolahService
{
    protected SuperadminSekolahRepository $repository;

    public function __construct()
    {
        $this->repository = new SuperadminSekolahRepository();
    }

    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    public function find(int $id): ?array
    {
        return $this->repository->find($id);
    }
}