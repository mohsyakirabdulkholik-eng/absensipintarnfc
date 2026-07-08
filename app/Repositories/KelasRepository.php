<?php

namespace App\Repositories;

use App\Models\KelasModel;

class KelasRepository
{
    protected KelasModel $model;

    public function __construct()
    {
        $this->model = new KelasModel();
    }

    public function getAll($idSekolah)
    {
        return $this->model
            ->select('kelas.*, jurusan.nama_jurusan')
            ->join('jurusan', 'jurusan.id = kelas.id_jurusan', 'left')
            ->where('kelas.id_sekolah', $idSekolah)
            ->findAll();
    }
    public function find($id)
    {
        return $this->model->find($id);
    }
    public function insert(array $data)
    {
        return $this->model->insert($data);
    }
    public function getBySekolah($idSekolah)
    {
        return $this->model
            ->select('id, nama_kelas')
            ->where('id_sekolah', $idSekolah)
            ->orderBy('nama_kelas', 'ASC')
            ->findAll();
    }
    public function updateData($id, array $data)
    {
        return $this->model->update($id, $data);
    }

    public function deleteData($id)
    {
        return $this->model->delete($id);
    }
    public function isExists($idSekolah, $namaKelas)
    {
        return $this->model
            ->where('id_sekolah', $idSekolah)
            ->where('nama_kelas', $namaKelas)
            ->countAllResults() > 0;
    }
}