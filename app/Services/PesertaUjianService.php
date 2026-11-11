<?php

namespace App\Services;

use App\Repositories\PesertaUjianRepository;
use App\Repositories\UjianRepository;
use RuntimeException;

class PesertaUjianService
{
    protected PesertaUjianRepository $repository;
    protected UjianRepository $ujianRepository;

    public function __construct()
    {
        $this->repository = new PesertaUjianRepository();
        $this->ujianRepository = new UjianRepository();
    }

    /**
     * Ambil peserta berdasarkan ujian
     */
    public function getByUjian(int $idUjian): array
    {
        $this->validateUjian($idUjian);

        return $this->repository->getByUjian($idUjian);
    }

    /**
     * Daftarkan seluruh siswa kelas ke ujian
     */
    public function registerByKelas(int $idUjian): array
    {
        $ujian = $this->validateUjian($idUjian);

        $idKelas = (int) $ujian['id_kelas'];

        if ($idKelas <= 0) {
            throw new RuntimeException(
                'Ujian belum memiliki kelas.'
            );
        }

        $siswa = $this->repository->getSiswaByKelas($idKelas);

        if (empty($siswa)) {
            throw new RuntimeException(
                'Tidak ada siswa dalam kelas tersebut.'
            );
        }

        $pesertaBaru = [];

        foreach ($siswa as $item) {
            $idSiswa = (int) $item['id'];

            $peserta = $this->repository
                ->findByUjianAndSiswa($idUjian, $idSiswa);

            if ($peserta) {
                continue;
            }

            $pesertaBaru[] = [
                'id_ujian' => $idUjian,
                'id_siswa' => $idSiswa,
                'status' => 'belum_mulai',
                'waktu_mulai' => null,
                'waktu_selesai' => null,
            ];
        }

        if (!empty($pesertaBaru)) {
            $this->repository->insertBatch($pesertaBaru);
        }

        return [
            'id_ujian' => $idUjian,
            'id_kelas' => $idKelas,
            'jumlah_siswa' => count($siswa),
            'jumlah_peserta_baru' => count($pesertaBaru),
            'jumlah_peserta' => $this->repository
                ->countByUjian($idUjian),
        ];
    }

    /**
     * Validasi ujian
     */
    protected function validateUjian(int $idUjian): array
    {
        $ujian = $this->ujianRepository->find($idUjian);

        if (!$ujian) {
            throw new RuntimeException(
                'Ujian tidak ditemukan.'
            );
        }

        return $ujian;
    }
    
    /**
     * Memulai ujian peserta
     */
    public function startExam(int $idPesertaUjian): array
    {
        $peserta = $this->repository->findById($idPesertaUjian);

        if (!$peserta) {
            throw new RuntimeException(
                'Peserta ujian tidak ditemukan.'
            );
        }

        if ($peserta['status'] !== 'belum_mulai') {
            throw new RuntimeException(
                'Peserta sudah memulai ujian atau ujian telah selesai.'
            );
        }

        $ujian = $this->validateUjian(
            (int) $peserta['id_ujian']
        );

        if (($ujian['status'] ?? '') !== 'aktif') {
            throw new RuntimeException(
                'Ujian belum diaktifkan oleh guru.'
            );
        }

        $berhasil = $this->repository->startExam(
            $idPesertaUjian
        );

        if (!$berhasil) {
            throw new RuntimeException(
                'Status peserta gagal diperbarui. Silakan periksa kembali.'
            );
        }

        return [
                    'id_peserta_ujian' => $idPesertaUjian,
                    'status' => 'sedang_ujian',
                    'waktu_mulai' => date('Y-m-d H:i:s'),
                ];
    }

}