<?php

namespace App\Repositories;

use App\Models\PerangkatModel;

class PerangkatRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new PerangkatModel();
    }

    public function getAll($idSekolah)
    {
        return $this->model
            ->where('id_sekolah', $idSekolah)
            ->orderBy('nama_perangkat', 'ASC')
            ->findAll();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function insert(array $data)
    {
        return $this->model->insert($data);
    }

    public function updateStatus($id, $status)
    {
        return $this->model->update($id, [
            'status' => $status
        ]);
    }

    public function delete($id)
    {
        return $this->model->delete($id);
    }

    public function findByAndroidId(string $androidId)
    {
        return $this->model
            ->where('android_id', $androidId)
            ->first();
    }

    public function findByHardwareId(string $hardwareId)
    {
        return $this->model
            ->where('hardware_id', $hardwareId)
            ->first();
    }

    public function findByToken(string $token)
    {
        return $this->model
            ->where('token', $token)
            ->where('status', 'aktif')
            ->first();
    }

    public function updateLastSync($id)
    {
        return $this->model->update($id, [
            'last_sync' => date('Y-m-d H:i:s')
        ]);
    }

    public function updateToken($id, string $token)
    {
        return $this->model->update($id, [
            'token' => $token
        ]);
    }
    public function resetDevice($id)
    {
        return $this->model->update($id, [
            // Android
            'android_id'      => null,
            'merk'            => null,
            'model'           => null,
            'versi_android'   => null,

            // NodeMCU
            'hardware_id'     => null,

            // Umum
            'last_sync'       => null,
        ]);
    }

    public function pairNodeMcu(
    int $id,
    string $hardwareId
    ) {
        return $this->model->update($id, [
            'hardware_id' => $hardwareId,
            'last_sync'   => date('Y-m-d H:i:s')
        ]);
    }
}