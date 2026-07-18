<?php

namespace App\Repositories;

use App\Models\GuruModel;

class GuruRepository
{
    protected GuruModel $model;

    public function __construct()
    {
        $this->model = new GuruModel();
    }

    public function getAll($idSekolah)
    {
        return $this->model
            ->where('id_sekolah',$idSekolah)
            ->findAll();
    }
    public function find($id)
    {
        return $this->model->find($id);
    }
    public function findById($id)
    {
        return $this->model->find($id);
    }

    public function insert(array $data)
    {
        return $this->model->insert($data);
    }

    public function updateData($id, array $data)
    {
        return $this->model->update($id, $data);
    }

    public function deleteData($id)
    {
        return $this->model->delete($id);
    }
    public function getBySekolah($idSekolah)
    {
        return $this->model
            ->where('id_sekolah',$idSekolah)
            ->orderBy('kode')
            ->findAll();
    }
    public function cekNip($nip)
    {
        return $this->model
            ->where('nip', $nip)
            ->first();
    }
    public function getBelumPunyaKartu($idSekolah)
    {
        return $this->model
            ->select('guru.id, guru.nama')
            ->join('kartu_nfc', 'kartu_nfc.id_guru = guru.id', 'left')
            ->where('guru.id_sekolah', $idSekolah)
            ->where('kartu_nfc.id IS NULL')
            ->orderBy('guru.nama', 'ASC')
            ->findAll();
    }
}