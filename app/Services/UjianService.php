<?php

namespace App\Services;

use App\Repositories\UjianRepository;
use RuntimeException;

class UjianService
{
    protected UjianRepository $repository;

    public function __construct()
    {
        $this->repository = new UjianRepository();
    }

    /**
     * Ambil semua ujian milik sekolah
     */
    public function getAll(int $idSekolah): array
    {
        return $this->repository->getAll($idSekolah);
    }

    /**
     * Ambil detail ujian
     */
    public function getById(
        int $idUjian,
        int $idSekolah
    ): array {
        $ujian = $this->repository->findBySekolah(
            $idUjian,
            $idSekolah
        );

        if (!$ujian) {
            throw new RuntimeException('Ujian tidak ditemukan.');
        }

        return $ujian;
    }

    /**
     * Ambil ujian milik guru
     */
    public function getByGuru(
        int $idGuru,
        int $idSekolah
    ): array {
        return $this->repository->getByGuru(
            $idGuru,
            $idSekolah
        );
    }

    /**
     * Buat ujian baru
     */
    public function create(
        array $data,
        int $idSekolah
    ): int {
        // Pastikan sekolah selalu berasal dari session/auth
        $data['id_sekolah'] = $idSekolah;

        // Validasi data wajib
        $this->validateData($data);

        // Generate token jika belum diberikan
        if (empty($data['token'])) {
            $data['token'] = $this->generateUniqueToken(
                $idSekolah
            );
        } else {
            $data['token'] = strtoupper(
                trim($data['token'])
            );

            // Cek token tidak boleh sama
            if ($this->repository->existsToken(
                $data['token'],
                $idSekolah
            )) {
                throw new RuntimeException(
                    'Token ujian sudah digunakan.'
                );
            }
        }

        // Default value
        $data['status'] = $data['status'] ?? 'draft';
        $data['kkm'] = $data['kkm'] ?? 75;
        $data['acak_soal'] = $data['acak_soal'] ?? 0;
        $data['acak_jawaban'] = $data['acak_jawaban'] ?? 0;

        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $id = $this->repository->insert($data);

        if (!$id) {
            throw new RuntimeException(
                'Gagal membuat ujian.'
            );
        }

        return $id;
    }

    /**
     * Update ujian
     */
    public function update(
        int $idUjian,
        array $data,
        int $idSekolah
    ): bool {
        // Pastikan ujian memang milik sekolah
        $ujian = $this->repository->findBySekolah(
            $idUjian,
            $idSekolah
        );

        if (!$ujian) {
            throw new RuntimeException(
                'Ujian tidak ditemukan.'
            );
        }

        // Ujian yang sudah selesai tidak boleh diubah
        if ($ujian['status'] === 'selesai') {
            throw new RuntimeException(
                'Ujian yang sudah selesai tidak dapat diubah.'
            );
        }

        // Jangan izinkan id_sekolah diganti
        unset($data['id_sekolah']);

        // Jika token diubah, cek keunikannya
        if (isset($data['token'])) {
            $data['token'] = strtoupper(
                trim($data['token'])
            );

            if ($data['token'] === '') {
                throw new RuntimeException(
                    'Token ujian tidak boleh kosong.'
                );
            }

            if ($this->repository->existsToken(
                $data['token'],
                $idSekolah,
                $idUjian
            )) {
                throw new RuntimeException(
                    'Token ujian sudah digunakan.'
                );
            }
        }

        $data['updated_at'] = date('Y-m-d H:i:s');

        return $this->repository->update(
            $idUjian,
            $data
        );
    }

    /**
     * Hapus ujian
     */
    public function delete(
        int $idUjian,
        int $idSekolah
    ): bool {
        $ujian = $this->repository->findBySekolah(
            $idUjian,
            $idSekolah
        );

        if (!$ujian) {
            throw new RuntimeException(
                'Ujian tidak ditemukan.'
            );
        }

        // Ujian aktif tidak boleh dihapus
        if ($ujian['status'] === 'aktif') {
            throw new RuntimeException(
                'Ujian yang sedang aktif tidak dapat dihapus.'
            );
        }

        // Ujian selesai juga kita lindungi
        if ($ujian['status'] === 'selesai') {
            throw new RuntimeException(
                'Ujian yang sudah selesai tidak dapat dihapus.'
            );
        }

        return $this->repository->delete($idUjian);
    }

    /**
     * Aktifkan ujian
     */
    public function activate(
        int $idUjian,
        int $idSekolah
    ): bool {
        $ujian = $this->repository->findBySekolah(
            $idUjian,
            $idSekolah
        );

        if (!$ujian) {
            throw new RuntimeException(
                'Ujian tidak ditemukan.'
            );
        }

        if ($ujian['status'] === 'selesai') {
            throw new RuntimeException(
                'Ujian yang sudah selesai tidak dapat diaktifkan kembali.'
            );
        }

        return $this->repository->update(
            $idUjian,
            [
                'status' => 'aktif',
                'updated_at' => date('Y-m-d H:i:s')
            ]
        );
    }

    /**
     * Selesaikan ujian
     */
    public function finish(
        int $idUjian,
        int $idSekolah
    ): bool {
        $ujian = $this->repository->findBySekolah(
            $idUjian,
            $idSekolah
        );

        if (!$ujian) {
            throw new RuntimeException(
                'Ujian tidak ditemukan.'
            );
        }

        if ($ujian['status'] !== 'aktif') {
            throw new RuntimeException(
                'Hanya ujian aktif yang dapat diselesaikan.'
            );
        }

        return $this->repository->update(
            $idUjian,
            [
                'status' => 'selesai',
                'updated_at' => date('Y-m-d H:i:s')
            ]
        );
    }

    /**
     * Generate token unik
     */
    protected function generateUniqueToken(
        int $idSekolah
    ): string {
        do {
            $token = strtoupper(
                substr(
                    bin2hex(random_bytes(4)),
                    0,
                    8
                )
            );
        } while (
            $this->repository->existsToken(
                $token,
                $idSekolah
            )
        );

        return $token;
    }

    /**
     * Validasi data ujian
     */
    protected function validateData(array $data): void
    {
        $required = [
            'id_guru' => 'Guru',
            'id_kelas' => 'Kelas',
            'id_tahun_ajaran' => 'Tahun ajaran',
            'id_semester' => 'Semester',
            'nama_ujian' => 'Nama ujian',
            'jenis_ujian' => 'Jenis ujian',
            'mata_pelajaran' => 'Mata pelajaran',
            'tanggal_mulai' => 'Tanggal mulai',
            'waktu_mulai' => 'Waktu mulai',
            'durasi_menit' => 'Durasi ujian'
        ];

        foreach ($required as $field => $label) {
            if (
                !isset($data[$field]) ||
                trim((string) $data[$field]) === ''
            ) {
                throw new RuntimeException(
                    $label . ' wajib diisi.'
                );
            }
        }

        // Validasi durasi
        if (
            !is_numeric($data['durasi_menit']) ||
            (int) $data['durasi_menit'] <= 0
        ) {
            throw new RuntimeException(
                'Durasi ujian harus lebih dari 0 menit.'
            );
        }

        // Validasi KKM
        if (isset($data['kkm'])) {
            $kkm = (float) $data['kkm'];

            if ($kkm < 0 || $kkm > 100) {
                throw new RuntimeException(
                    'KKM harus berada antara 0 sampai 100.'
                );
            }
        }
    }
}