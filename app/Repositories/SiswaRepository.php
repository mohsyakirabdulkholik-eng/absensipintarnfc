<?php

namespace App\Repositories;

use App\Models\SiswaModel;

class SiswaRepository
{
    protected SiswaModel $model;

    public function __construct()
    {
        $this->model = new SiswaModel();
    }

    public function getAll($idSekolah)
    {
        return $this->model
        ->select('
            siswa.*,
            kelas.nama_kelas,
            kartu_nfc.id AS id_kartu_nfc,
            kartu_nfc.uid AS uid_kartu,
            kartu_nfc.nomor_kartu,
            kartu_nfc.status AS status_kartu
        ')
        ->join('kelas', 'kelas.id = siswa.id_kelas')
        ->join(
            'kartu_nfc',
            'kartu_nfc.id_siswa = siswa.id',
            'left'
        )
        ->where('siswa.id_sekolah', $idSekolah)
        ->orderBy('siswa.nama_siswa', 'ASC')
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
            ->select('
                siswa.*,
                kelas.nama_kelas,
                sekolah.nama_sekolah
            ')
            ->join('kelas', 'kelas.id = siswa.id_kelas')
            ->join('sekolah', 'sekolah.id_sekolah = siswa.id_sekolah')
            ->where('siswa.id_sekolah', $idSekolah)
            ->findAll();
    }
    public function cekNis($nis)
    {
        return $this->model
            ->where('nis', $nis)
            ->first();
    }
    
    public function cekNisn($nisn)
    {
        return $this->model
            ->where('nisn', $nisn)
            ->first();
    }

    public function getBelumPunyaKartu($idSekolah)
    {
        return $this->model
            ->select('siswa.id, siswa.nama_siswa as nama')
            ->join('kartu_nfc', 'kartu_nfc.id_siswa = siswa.id', 'left')
            ->where('siswa.id_sekolah', $idSekolah)
            ->where('kartu_nfc.id IS NULL')
            ->orderBy('siswa.nama_siswa', 'ASC')
            ->findAll();
    }
    
}