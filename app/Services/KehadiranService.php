<?php

namespace App\Services;

use App\Repositories\KehadiranRepository;

class KehadiranService
{
    protected KehadiranRepository $repository;

    public function __construct()
    {
        $this->repository = new KehadiranRepository();
    }


    /**
     * Kehadiran siswa.
     */
    public function getSiswa(
        int $idSekolah,
        string $tanggal,
        ?int $idKelas = null
    ): array
    {
        $data = $this->repository->getSiswa(
            $idSekolah,
            $tanggal,
            $idKelas
        );

        return $this->formatData($data);
    }


    /**
     * Kehadiran guru.
     */
    public function getGuru(
        int $idSekolah,
        string $tanggal
    ): array
    {
        $data = $this->repository->getGuru(
            $idSekolah,
            $tanggal
        );

        return $this->formatData($data);
    }


    /**
     * Format data kehadiran.
     */
    private function formatData(array $data): array
    {
        foreach ($data as &$row) {

            /*
             * Tidak mempunyai data absensi pada tanggal
             * yang dipilih.
             */
            if (empty($row['id_absensi'])) {

                $row['status_kehadiran'] = 'belum_hadir';

                $row['jam_masuk'] = null;
                $row['jam_pulang'] = null;
                $row['terlambat_menit'] = 0;

                continue;
            }

            /*
             * Gunakan status yang sudah ditentukan
             * oleh NFC Engine.
             *
             * Service Kehadiran tidak menghitung ulang
             * aturan keterlambatan.
             */
            $row['status_kehadiran'] =
                $row['status_absensi'] ?? 'hadir';
        }

        return $data;
    }


    /**
     * Statistik daftar kehadiran.
     */
    public function getStatistik(array $data): array
    {
        $statistik = [
            'total'       => count($data),
            'hadir'       => 0,
            'terlambat'   => 0,
            'belum_hadir' => 0,
        ];

        foreach ($data as $row) {

            $status = strtolower(
                $row['status_kehadiran'] ?? 'belum_hadir'
            );

            if ($status === 'terlambat') {

                $statistik['terlambat']++;

            } elseif ($status === 'belum_hadir') {

                $statistik['belum_hadir']++;

            } else {

                $statistik['hadir']++;
            }
        }

        return $statistik;
    }
}