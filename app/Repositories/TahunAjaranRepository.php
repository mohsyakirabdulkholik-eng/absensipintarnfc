<?php

namespace App\Repositories;

use App\Models\TahunAjaranModel;

class TahunAjaranRepository
{
    protected TahunAjaranModel $model;

    public function __construct()
    {
        $this->model = new TahunAjaranModel();
    }

    /**
     * Ambil semua tahun ajaran berdasarkan sekolah
     */
    public function getAll($idSekolah)
    {
        return $this->model
            ->where('id_sekolah', $idSekolah)
            ->orderBy('nama', 'DESC')
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
     * Cek nama tahun ajaran
     */
    public function cekNama($idSekolah, $nama)
    {
        return $this->model
            ->where('id_sekolah', $idSekolah)
            ->where('nama', $nama)
            ->first();
    }

    /**
     * Nonaktifkan seluruh tahun ajaran
     */
    public function nonaktifkanSemua($idSekolah)
    {
        return $this->model
            ->where('id_sekolah', $idSekolah)
            ->set(['aktif' => 0])
            ->update();
    }

    /**
     * Aktifkan satu tahun ajaran
     */
    public function aktifkan($id)
    {
        return $this->model
            ->update($id, [
                'aktif' => 1
            ]);
    }
}