<?php

namespace App\Repositories;

use App\Models\HasilUjianModel;
use Config\Database;

class HasilUjianRepository
{
    protected HasilUjianModel $model;
    protected $db;

    public function __construct()
    {
        $this->model = new HasilUjianModel();
        $this->db = Database::connect();
    }

    /**
     * Simpan hasil ujian siswa.
     */
    public function insert(array $data): int
    {
        return $this->model->insert($data, true);
    }

    /**
     * Cari hasil berdasarkan peserta ujian.
     */
    public function findByPeserta(int $idPesertaUjian): ?array
    {
        $data = $this->model
            ->where('id_peserta_ujian', $idPesertaUjian)
            ->first();

        return $data ?: null;
    }

    /**
     * Cari hasil berdasarkan ujian dan siswa.
     */
    public function findByUjianAndSiswa(
        int $idUjian,
        int $idSiswa
    ): ?array {
        $data = $this->model
            ->where('id_ujian', $idUjian)
            ->where('id_siswa', $idSiswa)
            ->first();

        return $data ?: null;
    }

    /**
     * Ambil seluruh hasil ujian.
     */
    public function getByUjian(int $idUjian): array
    {
        return $this->model
            ->where('id_ujian', $idUjian)
            ->orderBy('nilai', 'DESC')
            ->findAll();
    }

    /**
     * Update hasil ujian.
     */
    public function update(int $id, array $data): bool
    {
        return $this->model->update($id, $data);
    }

    /**
     * Hitung jumlah hasil ujian.
     */
    public function countByUjian(int $idUjian): int
    {
        return $this->model
            ->where('id_ujian', $idUjian)
            ->countAllResults();
    }

    /**
     * Ambil rekap nilai ujian.
     */
    public function getRekap(int $idUjian): array
    {
        return $this->db
            ->table('hasil_ujian')
            ->select('
                COUNT(id) AS jumlah_peserta,
                SUM(jumlah_benar) AS total_benar,
                SUM(jumlah_salah) AS total_salah,
                AVG(nilai) AS rata_rata,
                MAX(nilai) AS nilai_tertinggi,
                MIN(nilai) AS nilai_terendah
            ')
            ->where('id_ujian', $idUjian)
            ->get()
            ->getRowArray();
    }
}