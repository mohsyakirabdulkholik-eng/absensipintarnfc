<?php

namespace App\Repositories;

use App\Models\JurusanModel;

class JurusanRepository
{
    protected JurusanModel $model;

    public function __construct()
    {
        $this->model = new JurusanModel();
    }

    public function getAll($idSekolah)
    {
        return $this->model
            ->where('id_sekolah', $idSekolah)
            ->orderBy('kode', 'ASC')
            ->findAll();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }
    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function insert(array $data)
    {
        return $this->model->insert($data);
    }

    public function updateData($id, array $data)
    {
        return $this->model->update($id, $data);
    }

    public function deleteData($id)
    {
        return $this->model->delete($id);
    }
}