<?php

namespace App\Repositories;

use CodeIgniter\Model;

abstract class BaseRepository
{
    protected Model $model;

    public function findAll()
    {
        return $this->model->findAll();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function insert(array $data)
    {
        return $this->model->insert($data);
    }

    public function update($id,array $data)
    {
        return $this->model->update($id,$data);
    }

    public function delete($id)
    {
        return $this->model->delete($id);
    }
}