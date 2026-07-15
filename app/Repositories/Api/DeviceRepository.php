<?php

namespace App\Repositories\Api;

use App\Models\PerangkatModel;

class DeviceRepository
{
    protected PerangkatModel $model;

    public function __construct()
    {
        $this->model = new PerangkatModel();
    }

    /**
     * Cari perangkat berdasarkan token.
     */
    public function findByToken(string $token): ?array
    {
        return $this->model
            ->where('token', $token)
            ->first();
    }

    /**
     * Cari perangkat berdasarkan ID.
     */
    public function find(int $id): ?array
    {
        return $this->model->find($id);
    }

    /**
     * Update informasi pairing Android.
     */
    public function updatePairing(int $id, array $data): bool
    {
        return $this->model->update($id, $data);
    }

    /**
     * Update waktu sinkronisasi terakhir.
     */
    public function updateHeartbeat(int $id): bool
    {
        return $this->model->update($id, [
            'last_sync' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Hapus informasi pairing Android.
     */
    public function clearPairing(int $id): bool
    {
        return $this->model->update($id, [

            'android_id' => null,

            'merk' => null,

            'model' => null,

            'versi_android' => null,

            'last_sync' => null

        ]);
    }
    public function isAndroidUsed(string $androidId): ?array
    {
        return $this->model
            ->where('android_id', $androidId)
            ->first();
    }
}