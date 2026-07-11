<?php

namespace App\Repositories;

use App\Models\HariLiburModel;

class HariLiburRepository
{
    protected HariLiburModel $model;

    public function __construct()
    {
        $this->model = new HariLiburModel();
    }

    /**
     * Ambil seluruh hari libur berdasarkan sekolah
     */
    public function getAll($idSekolah)
    {
        return $this->model
            ->where('id_sekolah', $idSekolah)
            ->orderBy('tanggal', 'DESC')
            ->findAll();
    }

    /**
     * Ambil satu data
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Simpan data
     */
    public function insert(array $data)
    {
        return $this->model->insert($data);
    }

    /**
     * Update data
     */
    public function updateData($id, array $data)
    {
        return $this->model->update($id, $data);
    }

    /**
     * Hapus data
     */
    public function deleteData($id)
    {
        return $this->model->delete($id);
    }

    /**
     * Cek tanggal libur pada sekolah yang sama
     */
    public function cekTanggal($idSekolah, $tanggal)
    {
        return $this->model
            ->where('id_sekolah', $idSekolah)
            ->where('tanggal', $tanggal)
            ->first();
    }
}