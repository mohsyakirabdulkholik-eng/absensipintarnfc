<?php

namespace App\Repositories;

use App\Models\LanggananModel;

class LanggananRepository
{
    protected LanggananModel $model;

    public function __construct()
    {
        $this->model = new LanggananModel();
    }

    /**
     * Ambil langganan terakhir milik sekolah
     */
    public function getLatestBySekolah(int $idSekolah): ?array
    {
        return $this->model
            ->where('id_sekolah', $idSekolah)
            ->orderBy('id', 'DESC')
            ->first();
    }

    /**
     * Ambil langganan aktif milik sekolah
     */
    public function getActiveBySekolah(int $idSekolah): ?array
    {
        return $this->model
            ->where('id_sekolah', $idSekolah)
            ->where('status', 'aktif')
            ->where('tanggal_berakhir >=', date('Y-m-d'))
            ->orderBy('id', 'DESC')
            ->first();
    }
    /**
     * Ambil seluruh sekolah beserta informasi langganannya
     */
    public function getAllWithDetail(): array
    {
        return $this->model
            ->db
            ->table('sekolah')
            ->select('
                sekolah.id AS id_sekolah,
                sekolah.nama_sekolah,

                langganan.id,
                langganan.id_paket,
                langganan.tanggal_mulai,
                langganan.tanggal_berakhir,
                langganan.status,

                paket.nama_paket,
                paket.harga,
                paket.durasi_hari
            ')
            ->join(
                'langganan',
                'langganan.id_sekolah = sekolah.id',
                'left'
            )
            ->join(
                'paket',
                'paket.id = langganan.id_paket',
                'left'
            )
            ->orderBy('sekolah.id', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Cari berdasarkan ID
     */
    public function find(int $id): ?array
    {
        return $this->model->find($id);
    }

    /**
     * Simpan langganan
     */
    public function insert(array $data): int
    {
        $this->model->insert($data);

        return (int) $this->model->getInsertID();
    }

    /**
     * Update langganan
     */
    public function update(int $id, array $data): bool
    {
        return $this->model
            ->where('id', $id)
            ->set($data)
            ->update();
    }
}