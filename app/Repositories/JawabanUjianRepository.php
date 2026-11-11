<?php

namespace App\Repositories;

use App\Models\JawabanUjianModel;

class JawabanUjianRepository
{
    protected JawabanUjianModel $model;

    public function __construct()
    {
        $this->model = new JawabanUjianModel();
    }

    /**
     * Ambil seluruh jawaban peserta
     */
    public function getByPeserta(int $idPesertaUjian): array
    {
        return $this->model
            ->where('id_peserta_ujian', $idPesertaUjian)
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    /**
     * Ambil jawaban peserta untuk soal tertentu
     */
    public function findByPesertaAndSoal(
        int $idPesertaUjian,
        int $idSoal
    ): ?array {
        $data = $this->model
            ->where('id_peserta_ujian', $idPesertaUjian)
            ->where('id_soal', $idSoal)
            ->first();

        return $data ?: null;
    }
    
     /**
     * Simpan jawaban baru
     */
    public function insert(array $data): int
    {
        return $this->model->insert($data, true);
    }

    /**
     * Perbarui jawaban
     */
    public function update(int $id, array $data): bool
    {
        return $this->model->update($id, $data);
    }

    /**
     * Hitung jumlah soal yang sudah dijawab
     */
    public function countByPeserta(int $idPesertaUjian): int
    {
        return $this->model
            ->where('id_peserta_ujian', $idPesertaUjian)
            ->countAllResults();
    }

    /**
     * Hapus jawaban peserta
     */
    public function deleteByPeserta(int $idPesertaUjian): bool
    {
        return $this->model
            ->where('id_peserta_ujian', $idPesertaUjian)
            ->delete();
    }
}
