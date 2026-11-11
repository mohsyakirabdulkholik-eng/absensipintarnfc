<?php

namespace App\Services\Api;

use App\Repositories\Api\LoginSiswaRepository;

class LoginSiswaService
{
    protected LoginSiswaRepository $repository;

    public function __construct()
    {
        $this->repository = new LoginSiswaRepository();
    }

    /**
     * Login siswa menggunakan NIS dan token ujian.
     */
    public function login(string $nis, string $token): array
    {
        $nis = trim($nis);
        $token = strtoupper(trim($token));

        // 1. Validasi input
        if ($nis === '' || $token === '') {
            return [
                'success' => false,
                'code' => 'VALIDATION_ERROR',
                'message' => 'NIS dan token ujian wajib diisi.'
            ];
        }

        // 2. Cari siswa
        $siswa = $this->repository->findSiswaByNis($nis);

        if (!$siswa) {
            return [
                'success' => false,
                'code' => 'SISWA_NOT_FOUND',
                'message' => 'NIS tidak ditemukan atau siswa tidak aktif.'
            ];
        }

        // 3. Cari ujian
        $ujian = $this->repository->findUjianByToken($token);

        if (!$ujian) {
            return [
                'success' => false,
                'code' => 'UJIAN_NOT_FOUND',
                'message' => 'Token ujian tidak valid atau ujian tidak aktif.'
            ];
        }

        // 4. Validasi sekolah
        if (
            (int) $siswa['id_sekolah']
            !== (int) $ujian['id_sekolah']
        ) {
            return [
                'success' => false,
                'code' => 'SCHOOL_MISMATCH',
                'message' => 'Siswa tidak terdaftar di sekolah penyelenggara ujian.'
            ];
        }

        // 5. Validasi kelas siswa dengan kelas ujian
        if (
            (int) $siswa['id_kelas']
            !== (int) $ujian['id_kelas']
        ) {
            return [
                'success' => false,
                'code' => 'CLASS_MISMATCH',
                'message' => 'Siswa tidak terdaftar pada kelas ujian ini.'
            ];
        }

        // 6. Cari peserta ujian
        $peserta = $this->repository->findPesertaUjian(
            (int) $ujian['id'],
            (int) $siswa['id']
        );

        if (!$peserta) {
            return [
                'success' => false,
                'code' => 'PESERTA_NOT_REGISTERED',
                'message' => 'Siswa belum terdaftar sebagai peserta ujian.'
            ];
        }
        // 7. validasi siswa yang sudah ujian
        if ($peserta['status'] === 'selesai') {
            return [
                'success' => false,
                'code' => 'EXAM_ALREADY_COMPLETED',
                'message' => 'Siswa sudah menyelesaikan ujian ini.'
            ];
        }

         // 8. Login berhasil
         return [
            'success' => true,
            'code' => 'LOGIN_SUCCESS',
            'message' => 'Login siswa berhasil.',
            'data' => [
                'siswa' => [
                    'id' => $siswa['id'],
                    'nis' => $siswa['nis'],
                    'nama_siswa' => $siswa['nama_siswa'],
                    'id_kelas' => $siswa['id_kelas']
                ],
                'ujian' => [
                    'id' => $ujian['id'],
                    'nama_ujian' => $ujian['nama_ujian'],
                    'token' => $ujian['token'],
                    'tanggal_mulai' => $ujian['tanggal_mulai'],
                    'waktu_mulai' => $ujian['waktu_mulai'],
                    'durasi_menit' => $ujian['durasi_menit'],
                    'kkm' => $ujian['kkm']
                ],
                'peserta' => $peserta
            ]
        ];
    }
}
