<?php

namespace App\Repositories;

use App\Models\PilihanJawabanModel;

class PilihanJawabanRepository
{
    protected PilihanJawabanModel $model;

    public function __construct()
    {
        $this->model = new PilihanJawabanModel();
    }

    /**
     * Ambil semua pilihan berdasarkan soal
     */
    public function getBySoal(int $idSoal): array
    {
        return $this->model
            ->where('id_soal', $idSoal)
            ->orderBy('urutan', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    /**
     * Ambil satu pilihan jawaban
     */
    public function find(int $id): ?array
    {
        $data = $this->model->find($id);

        return $data ?: null;
    }

    /**
     * Cari pilihan berdasarkan ID soal
     */
    public function findBySoal(
        int $idPilihan,
        int $idSoal
    ): ?array {
        $data = $this->model
            ->where('id', $idPilihan)
            ->where('id_soal', $idSoal)
            ->first();

        return $data ?: null;
    }

    /**
     * Tambah pilihan jawaban
     */
    public function insert(array $data): int
    {
        return $this->model->insert($data, true);
    }

    /**
     * Update pilihan jawaban
     */
    public function update(
        int $id,
        array $data
    ): bool {
        return $this->model->update($id, $data);
    }

    /**
     * Hapus pilihan jawaban
     */
    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }

    /**
     * Hitung jumlah pilihan dalam soal
     */
    public function countBySoal(int $idSoal): int
    {
        return $this->model
            ->where('id_soal', $idSoal)
            ->countAllResults();
    }

    /**
     * Hapus seluruh pilihan berdasarkan soal
     */
    public function deleteBySoal(int $idSoal): bool
    {
        return $this->model
            ->where('id_soal', $idSoal)
            ->delete();
    }
}
