<?php

namespace App\Services;

use App\Repositories\PaketRepository;

class PaketService
{
    protected PaketRepository $repository;

    public function __construct()
    {
        $this->repository = new PaketRepository();
    }

    /**
     * Ambil semua paket
     */
    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    /**
     * Ambil paket aktif
     */
    public function getActive(): array
    {
        return $this->repository->getActive();
    }

    /**
     * Ambil paket berdasarkan ID
     */
    public function find(int $id): ?array
    {
        return $this->repository->find($id);
    }
}