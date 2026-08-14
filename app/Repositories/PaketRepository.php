<?php

namespace App\Repositories;

use App\Models\PaketModel;

class PaketRepository
{
    protected PaketModel $model;

    public function __construct()
    {
        $this->model = new PaketModel();
    }

    /**
     * Ambil semua paket
     */
    public function getAll(): array
    {
        return $this->model
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    /**
     * Ambil paket aktif
     */
    public function getActive(): array
    {
        return $this->model
            ->where('status', 'aktif')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    /**
     * Ambil paket berdasarkan ID
     */
    public function find(int $id): ?array
    {
        return $this->model->find($id);
    }
}