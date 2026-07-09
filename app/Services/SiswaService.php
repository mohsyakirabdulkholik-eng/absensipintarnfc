<?php

namespace App\Services;

use App\Repositories\SiswaRepository;

class SiswaService
{
    protected SiswaRepository $repository;

    public function __construct()
    {
        $this->repository = new SiswaRepository();
    }

    public function getAll($idSekolah)
    {
        return $this->repository->getAll($idSekolah);
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }
    public function getBySekolah($idSekolah)
    {
        return $this->repository->getBySekolah($idSekolah);
    }

    public function save(array $data)
    {
        if (!empty($data['foto']) && $data['foto']->isValid()) {

            $data['foto'] = uploadImage(
                $data['foto'],
                'uploads/siswa',
                'SISWA'
            );

        } else {

            $data['foto'] = 'default.png';

        }

        return $this->repository->insert($data);
    }

    public function update($id, array $data)
    {
        $siswa = $this->repository->find($id);

        if (!$siswa) {
            throw new \Exception('Data siswa tidak ditemukan.');
        }

        if (!empty($data['foto']) && $data['foto']->isValid()) {

            $fotoBaru = uploadImage(
            $data['foto'],
            'uploads/siswa',
            'SISWA'
        );

        if ($fotoBaru !== null) {

            deleteImage(
                'uploads/siswa',
                $siswa['foto']
            );

            $data['foto'] = $fotoBaru;

        } else {

            unset($data['foto']);

        }

        } else {

            unset($data['foto']);

        }

        return $this->repository->updateData($id, $data);
    }

   public function delete($id)
    {
        $siswa = $this->repository->find($id);

        if (!$siswa) {
            throw new \Exception('Data siswa tidak ditemukan.');
        }

        deleteImage(
            'uploads/siswa',
            $siswa['foto']
        );

        return $this->repository->deleteData($id);
    }

    public function cekNis($nis)
    {
        return $this->repository->cekNis($nis);
    }

    public function cekNisn($nisn)
    {
        return $this->repository->cekNisn($nisn);
    }

    public function cekUid($uid)
    {
        return $this->repository->cekUid($uid);
    }
}