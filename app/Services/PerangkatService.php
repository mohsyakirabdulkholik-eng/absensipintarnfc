<?php

namespace App\Services;

use App\Repositories\PerangkatRepository;

class PerangkatService
{
    protected $repository;

    public function __construct()
    {
        $this->repository = new PerangkatRepository();
    }

    public function getAll($idSekolah)
    {
        return $this->repository->getAll($idSekolah);
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function save(array $data)
    {
        $data['token'] = $this->generateToken();
        $data['status'] = 'aktif';

        return $this->repository->insert($data);
    }

    public function update($id, array $data)
    {
        unset($data['token']);

        return $this->repository->updateData($id, $data);
    }

    public function delete($id)
    {
        $perangkat = $this->repository->find($id);

        if (!$perangkat) {
            return [
                'success' => false,
                'message' => 'Perangkat tidak ditemukan.'
            ];
        }

        if (!empty($perangkat['android_id'])) {
            return [
                'success' => false,
                'message' => 'Perangkat masih terhubung dengan Android. Lakukan Reset Device terlebih dahulu.'
            ];
        }

        $this->repository->delete($id);

        return [
            'success' => true,
            'message' => 'Perangkat berhasil dihapus.'
        ];
    }

    public function regenerateToken($id)
    {
        $token = $this->generateToken();

        $this->repository->updateToken($id, $token);

        return $token;
    }

    public function getByAndroidId($androidId)
    {
        return $this->repository->findByAndroidId($androidId);
    }

    public function getByToken($token)
    {
        return $this->repository->findByToken($token);
    }

    public function updateLastSync($id)
    {
        return $this->repository->updateLastSync($id);
    }

    private function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }
    public function resetDevice($id)
    {
        return $this->repository->resetDevice($id);
    }
    public function toggleStatus($id)
    {
        $perangkat = $this->repository->find($id);

        if (!$perangkat) {
            return [
                'success' => false,
                'message' => 'Perangkat tidak ditemukan.'
            ];
        }

        $statusBaru = $perangkat['status'] === 'aktif'
            ? 'nonaktif'
            : 'aktif';

        $this->repository->updateStatus($id, [
            'status' => $statusBaru
        ]);

        return [
            'success' => true,
            'message' => 'Status perangkat berhasil diperbarui.',
            'status' => $statusBaru
        ];
    }
    
}