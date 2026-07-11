<?php

namespace App\Repositories;

use App\Models\PengaturanAbsensiModel;

class PengaturanAbsensiRepository
{
    protected PengaturanAbsensiModel $model;

    public function __construct()
    {
        $this->model = new PengaturanAbsensiModel();
    }

    /**
     * Ambil seluruh data berdasarkan sekolah
     */
    public function getAll($idSekolah)
    {
        return $this->model
            ->where('id_sekolah', $idSekolah)
            ->orderBy('created_at', 'DESC')
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
 * Cek konfigurasi yang sama persis
 */
public function cekDuplikat(array $data)
{
    return $this->model
        ->where('id_sekolah', $data['id_sekolah'])
        ->where('jam_masuk', $data['jam_masuk'])
        ->where('batas_terlambat', $data['batas_terlambat'])
        ->where('jam_pulang', $data['jam_pulang'])
        ->where('toleransi_pulang', $data['toleransi_pulang'])
        ->first();
}
    /**
     * Nonaktifkan semua pengaturan
     */
    public function nonaktifkanSemua($idSekolah)
    {
        return $this->model
            ->where('id_sekolah', $idSekolah)
            ->set(['aktif' => 0])
            ->update();
    }

    /**
     * Aktifkan satu pengaturan
     */
    public function aktifkan($id)
    {
        return $this->model->update($id, [
            'aktif' => 1
        ]);
    }

    /**
     * Ambil pengaturan yang sedang aktif
     */
    public function getAktif($idSekolah)
    {
        return $this->model
            ->where('id_sekolah', $idSekolah)
            ->where('aktif', 1)
            ->first();
    }
}