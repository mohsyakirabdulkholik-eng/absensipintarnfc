<?php

namespace App\Repositories;

use App\Models\SoalModel;
use Config\Database;

class SoalRepository
{
    protected SoalModel $model;
    protected $db;

    public function __construct()
    {
        $this->model = new SoalModel();
        $this->db = Database::connect();
    }

    /**
     * Ambil semua soal berdasarkan ujian
     * sekaligus mengambil pilihan jawaban.
     */
    public function getByUjian(int $idUjian): array
    {
        $soal = $this->model
            ->where('id_ujian', $idUjian)
            ->orderBy('urutan', 'ASC')
            ->orderBy('id', 'ASC')
            ->findAll();

        foreach ($soal as &$item) {
            $item['pilihan'] = $this->getPilihan(
                (int) $item['id']
            );
        }

        unset($item);

        return $soal;
    }

    /**
     * Ambil satu soal berdasarkan ID
     * sekaligus pilihan jawaban.
     */
    public function find(int $id): ?array
    {
        $data = $this->model->find($id);

        if (!$data) {
            return null;
        }

        $data['pilihan'] = $this->getPilihan(
            (int) $data['id']
        );

        return $data;
    }

    /**
     * Cari soal berdasarkan ID soal dan ID ujian.
     */
    public function findByUjian(
        int $idSoal,
        int $idUjian
    ): ?array {
        $data = $this->model
            ->where('id', $idSoal)
            ->where('id_ujian', $idUjian)
            ->first();

        if (!$data) {
            return null;
        }

        $data['pilihan'] = $this->getPilihan(
            (int) $data['id']
        );

        return $data;
    }

    /**
     * Tambah soal.
     */
    public function insert(array $data): int
    {
        return $this->model->insert(
            $data,
            true
        );
    }

    /**
     * Update soal.
     */
    public function update(
        int $id,
        array $data
    ): bool {
        return $this->model->update(
            $id,
            $data
        );
    }

    /**
     * Hapus soal.
     */
    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }

    /**
     * Hitung jumlah soal dalam ujian.
     */
    public function countByUjian(int $idUjian): int
    {
        return $this->model
            ->where('id_ujian', $idUjian)
            ->countAllResults();
    }

    /**
     * Ambil pilihan jawaban berdasarkan ID soal.
     */
    protected function getPilihan(int $idSoal): array
    {
        return $this->db
            ->table('pilihan_jawaban')
            ->where('id_soal', $idSoal)
            ->orderBy('urutan', 'ASC')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();
    }
}