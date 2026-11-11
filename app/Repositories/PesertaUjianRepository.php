<?php

namespace App\Repositories;

use App\Models\PesertaUjianModel;
use Config\Database;

class PesertaUjianRepository
{
    protected PesertaUjianModel $model;
    protected $db;

    public function __construct()
    {
        $this->model = new PesertaUjianModel();
        $this->db = Database::connect();
    }

    /**
     * Ambil seluruh peserta berdasarkan ujian
     */
    public function getByUjian(int $idUjian): array
    {
        return $this->model
            ->where('id_ujian', $idUjian)
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    /**
     * Cari peserta berdasarkan ujian dan siswa
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

    public function findById(int $id): ?array
    {
        $data = $this->model->find($id);

        return $data ?: null;
    }

    /**
     * Mulai ujian peserta
     */
    public function startExam(int $id): bool
    {
        return $this->db->table('peserta_ujian')
            ->where('id', $id)
            ->where('status', 'belum_mulai')
            ->update([
                'status' => 'sedang_ujian',
                'waktu_mulai' => date('Y-m-d H:i:s'),
            ]);
    }

    /**
     * Selesaikan ujian peserta
     */
    public function finishExam(int $id): bool
    {
        return $this->db->table('peserta_ujian')
            ->where('id', $id)
            ->where('status', 'sedang_ujian')
            ->update([
                'status' => 'selesai',
                'waktu_selesai' => date('Y-m-d H:i:s'),
            ]);
    }

    /**
     * Tambahkan satu peserta ujian
     */
    public function insert(array $data): int
    {
        return $this->model->insert($data, true);
    }

    /**
     * Tambahkan banyak peserta sekaligus
     */
    public function insertBatch(array $data): bool
    {
        if (empty($data)) {
            return true;
        }

        return $this->model->insertBatch($data);
    }

    /**
     * Update peserta ujian
     */
    public function update(
        int $id,
        array $data
    ): bool {
        return $this->model->update($id, $data);
    }

    /**
     * Update status peserta
     */
    public function updateStatus(
        int $id,
        string $status
    ): bool {
        return $this->model->update($id, [
            'status' => $status
        ]);
    }

    /**
     * Hitung peserta dalam ujian
     */
    public function countByUjian(int $idUjian): int
    {
        return $this->model
            ->where('id_ujian', $idUjian)
            ->countAllResults();
    }

    /**
     * Hapus peserta berdasarkan ujian
     */
    public function deleteByUjian(int $idUjian): bool
    {
        return $this->model
            ->where('id_ujian', $idUjian)
            ->delete();
    }

    /**
     * Ambil seluruh siswa berdasarkan kelas
     */
    public function getSiswaByKelas(int $idKelas): array
    {
        return $this->db
            ->table('siswa')
            ->where('id_kelas', $idKelas)
            ->get()
            ->getResultArray();
    }

}