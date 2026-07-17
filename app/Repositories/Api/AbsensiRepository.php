<?php

namespace App\Repositories\Api;

use App\Models\AbsensiModel;

class AbsensiRepository
{
    protected AbsensiModel $model;

    public function __construct()
    {
        $this->model = new AbsensiModel();
    }

    /**
     * Ambil absensi hari ini berdasarkan kartu NFC
     */
    public function getTodayAttendance(
        int $idSekolah,
        int $idKartuNfc,
        string $tanggal
    ): ?array {

        return $this->model
            ->where('id_sekolah', $idSekolah)
            ->where('id_kartu_nfc', $idKartuNfc)
            ->where('tanggal', $tanggal)
            ->first();
    }

    /**
     * Simpan absensi masuk
     */
    public function saveCheckIn(array $data): bool
    {
        return (bool) $this->model->insert($data);
    }

    /**
     * Update absensi pulang
     */
    public function saveCheckOut(
        int $id,
        array $data
    ): bool {

        return $this->model->update($id, $data);
    }

    /**
     * Ambil absensi berdasarkan ID
     */
    public function find(int $id): ?array
    {
        return $this->model->find($id);
    }

    /**
     * Hapus absensi
     */
    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }
}