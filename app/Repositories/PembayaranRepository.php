<?php

namespace App\Repositories;

use App\Models\PembayaranModel;

class PembayaranRepository
{
    protected PembayaranModel $model;

    public function __construct()
    {
        $this->model = new PembayaranModel();
    }

    /**
     * Ambil seluruh pembayaran
     * beserta sekolah dan paket.
     */
    public function getAll(): array
    {
        return $this->model
            ->select('
                pembayaran.*,
                sekolah.nama_sekolah,
                paket.nama_paket,
                paket.durasi_hari
            ')
            ->join(
                'sekolah',
                'sekolah.id = pembayaran.id_sekolah'
            )
            ->join(
                'paket',
                'paket.id = pembayaran.id_paket'
            )
            ->orderBy(
                'pembayaran.id',
                'DESC'
            )
            ->findAll();
    }

    /**
     * Cari pembayaran berdasarkan ID.
     */
    public function find(int $id): ?array
    {
        return $this->model
            ->select('
                pembayaran.*,
                sekolah.nama_sekolah,
                paket.nama_paket,
                paket.harga,
                paket.durasi_hari
            ')
            ->join(
                'sekolah',
                'sekolah.id = pembayaran.id_sekolah'
            )
            ->join(
                'paket',
                'paket.id = pembayaran.id_paket'
            )
            ->where(
                'pembayaran.id',
                $id
            )
            ->first();
    }

    /**
     * Ambil pembayaran milik sekolah.
     */
    public function getBySekolah(int $idSekolah): array
    {
        return $this->model
            ->where(
                'id_sekolah',
                $idSekolah
            )
            ->orderBy(
                'id',
                'DESC'
            )
            ->findAll();
    }

    /**
     * Simpan pembayaran baru.
     */
    public function insert(array $data): int
    {
        $this->model->insert($data);

        return (int) $this->model->getInsertID();
    }

    /**
     * Update pembayaran.
     */
    public function update(
        int $id,
        array $data
    ): bool {

        return $this->model
            ->where('id', $id)
            ->set($data)
            ->update();
    }
}