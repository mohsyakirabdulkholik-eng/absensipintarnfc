<?php

namespace App\Services;

use App\Repositories\PengaturanAbsensiRepository;

class PengaturanAbsensiService
{
    protected PengaturanAbsensiRepository $repository;

    public function __construct()
    {
        $this->repository = new PengaturanAbsensiRepository();
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
        $this->validasiJam($data);

        if ($this->repository->cekDuplikat($data)) {
            throw new \Exception(
                'Konfigurasi pengaturan absensi sudah tersedia.'
            );
        }

        return $this->repository->insert($data);
    }

    /**
     * Update data
     */
    public function update($id, array $data)
    {
        $pengaturan = $this->repository->find($id);

        if (!$pengaturan) {
            throw new \Exception('Data pengaturan tidak ditemukan.');
        }

        $this->validasiJam($data);

        $duplikat = $this->repository->cekDuplikat($data);

        if ($duplikat && $duplikat['id'] != $id) {
            throw new \Exception(
                'Konfigurasi pengaturan absensi sudah tersedia.'
            );
        }
        
        return $this->repository->updateData($id, $data);
    }

    /**
     * Hapus data
     */
    public function delete($id)
    {
        $pengaturan = $this->repository->find($id);

        if (!$pengaturan) {
            throw new \Exception('Data pengaturan tidak ditemukan.');
        }

        if ($pengaturan['aktif']) {
            throw new \Exception('Pengaturan yang sedang aktif tidak dapat dihapus.');
        }

        return $this->repository->deleteData($id);
    }

    /**
     * Jadikan aktif
     */
    public function setAktif($id)
    {
        $pengaturan = $this->repository->find($id);

        if (!$pengaturan) {
            throw new \Exception('Data pengaturan tidak ditemukan.');
        }

        $this->repository->nonaktifkanSemua(
            $pengaturan['id_sekolah']
        );

        return $this->repository->aktifkan($id);
    }

    /**
     * Ambil pengaturan aktif
     */
    public function getAktif($idSekolah)
    {
        return $this->repository->getAktif($idSekolah);
    }

    /**
     * Validasi Business Rule Jam Absensi
     */
    private function validasiJam(array $data)
    {
        if ($data['jam_masuk'] >= $data['batas_terlambat']) {
            throw new \Exception(
                'Jam masuk harus lebih kecil dari batas terlambat.'
            );
        }

        if ($data['batas_terlambat'] > $data['jam_pulang']) {
            throw new \Exception(
                'Batas terlambat tidak boleh melebihi jam pulang.'
            );
        }

        if ($data['toleransi_pulang'] > $data['jam_pulang']) {
            throw new \Exception(
                'Toleransi pulang tidak boleh melebihi jam pulang.'
            );
        }
    }
}