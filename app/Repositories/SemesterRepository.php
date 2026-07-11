<?php

namespace App\Repositories;

use App\Models\SemesterModel;
use App\Models\TahunAjaranModel;

class SemesterRepository
{
    protected SemesterModel $model;

    public function __construct()
    {
        $this->model = new SemesterModel();
    }

    /**
     * Ambil seluruh semester berdasarkan sekolah
     */
    public function getAll($idSekolah)
    {
        return $this->model
            ->select('semester.*, tahun_ajaran.nama as nama_tahun')
            ->join('tahun_ajaran', 'tahun_ajaran.id = semester.id_tahun')
            ->where('tahun_ajaran.id_sekolah', $idSekolah)
            ->orderBy('tahun_ajaran.nama', 'DESC')
            ->orderBy('semester.nama', 'ASC')
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
     * Cek semester
     */
    public function cekSemester($idTahun, $nama)
    {
        return $this->model
            ->where('id_tahun', $idTahun)
            ->where('nama', $nama)
            ->first();
    }

    /**
     * Nonaktifkan semua semester
     */
    public function nonaktifkanSemua($idTahun)
    {
        return $this->model
            ->where('id_tahun', $idTahun)
            ->set(['aktif' => 0])
            ->update();
    }

    /**
     * Aktifkan semester
     */
    public function aktifkan($id)
    {
        return $this->model->update($id, [
            'aktif' => 1
        ]);
    }

    /**
     * Semester aktif
     */
    public function getSemesterAktif($idSekolah)
    {
        return $this->model
            ->select('semester.*, tahun_ajaran.nama as nama_tahun')
            ->join('tahun_ajaran', 'tahun_ajaran.id=semester.id_tahun')
            ->where('tahun_ajaran.id_sekolah', $idSekolah)
            ->where('semester.aktif',1)
            ->first();
    }
}