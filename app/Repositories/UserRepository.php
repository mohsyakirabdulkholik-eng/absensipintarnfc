<?php

namespace App\Repositories;

use App\Models\UserModel;

class UserRepository
{
    protected UserModel $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    public function findByLogin(string $login)
    {
        return $this->model
            ->where('username', $login)
            ->orWhere('email', $login)
            ->first();
    }

    public function updateLastLogin(int $id)
    {
        return $this->model->update($id, [
            'last_login' => date('Y-m-d H:i:s')
        ]);
    }
}