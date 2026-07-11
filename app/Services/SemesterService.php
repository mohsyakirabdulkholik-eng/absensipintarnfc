<?php

namespace App\Services;
use App\Repositories\SemesterRepository;
use App\Repositories\TahunAjaranRepository;

class SemesterService   
{
    protected SemesterRepository $repository;

    public function __construct()
    {
        $this->repository = new SemesterRepository();
        $this->tahunRepository = new TahunAjaranRepository();
    }

    /**
     * Ambil semua data
     */
    public function getAll($idSekolah)
    {
        return $this->repository->getAll($idSekolah);
    }

    /**
     * Ambil satu data
     */
    public function find($id)
    {
        return $this->repository->find($id);
    }

    /**
     * Simpan data
     */
    public function save(array $data)
    {
        if ($this->repository->cekSemester($data['id_tahun'], $data['nama'])) {
            throw new \Exception('Semester sudah tersedia.');
        }

        return $this->repository->insert($data);
    }

    /**
     * Update data
     */
    public function update($id, array $data)
    {
        $semester = $this->repository->find($id);

        if (!$semester) {
            throw new \Exception('Data semester tidak ditemukan.');
        }

        if (
            $data['nama'] != $semester['nama']
            &&
            $this->repository->cekSemester($data['id_tahun'], $data['nama'])
        ) {
            throw new \Exception('Semester sudah tersedia.');
        }

        return $this->repository->updateData($id, $data);
    }

    /**
     * Hapus data
     */
    public function delete($id)
    {
        $semester = $this->repository->find($id);

        if (!$semester) {
            throw new \Exception('Data semester tidak ditemukan.');
        }

        if ($semester['aktif']) {
            throw new \Exception('Semester yang sedang aktif tidak dapat dihapus.');
        }

        return $this->repository->deleteData($id);
    }

    /**
     * Jadikan semester aktif
     */
    public function setAktif($id)
    {
        $semester = $this->repository->find($id);

        if (!$semester) {
            throw new \Exception('Data semester tidak ditemukan.');
        }

        $tahun = $this->tahunRepository->find($semester['id_tahun']);

        if (!$tahun['aktif']) {
            throw new \Exception('Tahun ajaran belum aktif.');
        }

        $this->repository->nonaktifkanSemua(
            $semester['id_tahun']
        );

        return $this->repository->aktifkan($id);
    }
    
    public function getSemesterAktif($idSekolah)
    {
        return $this->repository->getSemesterAktif($idSekolah);
    }
}