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
    /**
     * Batalkan absensi pulang guru.
     */
    public function cancelGuruCheckout(
        int $idSekolah,
        int $idAbsensi
    ): array
    {
        /*
        * Cari absensi dan pastikan:
        * - milik sekolah yang sedang login
        * - jenisnya guru
        */
        $attendance = $this->repository
            ->findGuruAttendance(
                $idAbsensi,
                $idSekolah
            );

        if (!$attendance) {

            return [
                'success' => false,
                'code'    => 'ATTENDANCE_NOT_FOUND',
                'message' => 'Data absensi guru tidak ditemukan.'
            ];
        }

        /*
        * Jam pulang memang harus sudah terisi.
        */
        if (empty($attendance['jam_pulang'])) {

            return [
                'success' => false,
                'code'    => 'CHECKOUT_EMPTY',
                'message' => 'Guru belum melakukan absensi pulang.'
            ];
        }

        /*
        * Batalkan jam pulang.
        */
        $success = $this->repository
            ->cancelGuruCheckout($idAbsensi);

        if (!$success) {

            return [
                'success' => false,
                'code'    => 'CANCEL_FAILED',
                'message' => 'Absensi pulang guru gagal dibatalkan.'
            ];
        }

        return [
            'success' => true,
            'code'    => 'CHECKOUT_CANCELLED',
            'message' => 'Absensi pulang guru berhasil dibatalkan.',
            'data'    => [
                'id_absensi' => $idAbsensi,
                'jam_pulang' => null
            ]
        ];
    }
}