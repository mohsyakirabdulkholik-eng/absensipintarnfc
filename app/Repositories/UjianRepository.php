<?php

namespace App\Repositories;

use App\Models\UjianModel;

class UjianRepository
{
    protected UjianModel $model;

    public function __construct()
    {
        $this->model = new UjianModel();
    }

    /**
     * Ambil semua ujian berdasarkan sekolah
     */
    public function getAll(int $idSekolah): array
    {
        return $this->model
            ->where('id_sekolah', $idSekolah)
            ->orderBy('id', 'DESC')
            ->findAll();
    }

    /**
     * Ambil satu ujian berdasarkan ID
     */
    public function find(int $id): ?array
    {
        $data = $this->model->find($id);

        return $data ?: null;
    }

    /**
     * Ambil ujian berdasarkan ID sekolah dan ID ujian
     * Digunakan untuk memastikan ujian milik sekolah tersebut.
     */
    public function findBySekolah(
        int $idUjian,
        int $idSekolah
    ): ?array {
        $data = $this->model
            ->where('id', $idUjian)
            ->where('id_sekolah', $idSekolah)
            ->first();

        return $data ?: null;
    }

    /**
     * Ambil ujian berdasarkan guru
     */
    public function getByGuru(
        int $idGuru,
        int $idSekolah
    ): array {
        return $this->model
            ->where('id_guru', $idGuru)
            ->where('id_sekolah', $idSekolah)
            ->orderBy('id', 'DESC')
            ->findAll();
    }

    /**
     * Ambil ujian berdasarkan kelas
     */
    public function getByKelas(
        int $idKelas,
        int $idSekolah
    ): array {
        return $this->model
            ->where('id_kelas', $idKelas)
            ->where('id_sekolah', $idSekolah)
            ->orderBy('id', 'DESC')
            ->findAll();
    }

    /**
     * Ambil ujian berdasarkan status
     */
    public function getByStatus(
        string $status,
        int $idSekolah
    ): array {
        return $this->model
            ->where('status', $status)
            ->where('id_sekolah', $idSekolah)
            ->orderBy('id', 'DESC')
            ->findAll();
    }

    /**
     * Simpan ujian baru
     */
    public function insert(array $data): int
    {
        return $this->model->insert($data, true);
    }

    /**
     * Update ujian
     */
    public function update(
        int $id,
        array $data
    ): bool {
        return $this->model
            ->update($id, $data);
    }

    /**
     * Hapus ujian
     */
    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }

    /**
     * Cek apakah token sudah digunakan
     */
    public function existsToken(
        string $token,
        int $idSekolah,
        ?int $exceptId = null
    ): bool {
        $builder = $this->model
            ->where('token', $token)
            ->where('id_sekolah', $idSekolah);

        if ($exceptId !== null) {
            $builder->where('id !=', $exceptId);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Cari ujian berdasarkan token
     */
    public function findByToken(
        string $token,
        int $idSekolah
    ): ?array {
        $data = $this->model
            ->where('token', $token)
            ->where('id_sekolah', $idSekolah)
            ->first();

        return $data ?: null;
    }
}