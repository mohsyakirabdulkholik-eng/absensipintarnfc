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

    /**
     * Simpan data siswa
     */
    public function save(array $data)
    {
        $this->validateUnique($data);

        $data['foto'] = $this->uploadFoto($data['foto']);

        return $this->repository->insert($data);
    }

    /**
     * Update data siswa
     */
    public function update($id, array $data)
    {
        $siswa = $this->repository->find($id);

        if (!$siswa) {
            throw new \Exception('Data siswa tidak ditemukan.');
        }

        $this->validateUnique($data, $siswa);

        if (
            !empty($data['foto']) &&
            $data['foto']->isValid() &&
            $data['foto']->getError() !== UPLOAD_ERR_NO_FILE
        ) {

            $fotoBaru = $this->uploadFoto($data['foto']);

            if ($fotoBaru !== 'default.png') {

                deleteImage('uploads/siswa', $siswa['foto']);

                $data['foto'] = $fotoBaru;
            }

        } else {

            unset($data['foto']);

        }

        return $this->repository->updateData($id, $data);
    }

    /**
     * Hapus data siswa
     */
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

    /**
     * Validasi data unik
     */
    private function validateUnique(array $data, ?array $oldData = null): void
    {
        if (
            !empty($data['nis']) &&
            ($oldData === null || $data['nis'] !== $oldData['nis']) &&
            $this->repository->cekNis($data['nis'])
        ) {
            throw new \Exception('NIS sudah digunakan.');
        }

        if (
            !empty($data['nisn']) &&
            ($oldData === null || $data['nisn'] !== $oldData['nisn']) &&
            $this->repository->cekNisn($data['nisn'])
        ) {
            throw new \Exception('NISN sudah digunakan.');
        }

        if (
            !empty($data['uid_nfc']) &&
            ($oldData === null || $data['uid_nfc'] !== $oldData['uid_nfc']) &&
            $this->repository->cekUid($data['uid_nfc'])
        ) {
            throw new \Exception('UID NFC sudah terdaftar.');
        }
    }

    /**
     * Upload foto siswa
     */
    private function uploadFoto($foto): string
    {
        if (
            !empty($foto) &&
            $foto->isValid() &&
            $foto->getError() !== UPLOAD_ERR_NO_FILE
        ) {

            $fileName = uploadImage(
                $foto,
                'uploads/siswa',
                'SISWA'
            );

            if ($fileName !== null) {
                return $fileName;
            }
        }

        return 'default.png';
    }
}