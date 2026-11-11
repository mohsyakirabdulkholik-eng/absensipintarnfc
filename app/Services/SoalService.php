<?php

namespace App\Services;

use App\Repositories\SoalRepository;
use App\Repositories\UjianRepository;
use Config\Database;
use RuntimeException;
use Throwable;

class SoalService
{
    protected SoalRepository $repository;
    protected UjianRepository $ujianRepository;
    protected $db;

    public function __construct()
    {
        $this->repository = new SoalRepository();
        $this->ujianRepository = new UjianRepository();
        $this->db = Database::connect();
    }

    /**
     * Ambil semua soal berdasarkan ujian
     */
    public function getByUjian(
        int $idUjian,
        int $idSekolah
    ): array {
        $this->getUjian(
            $idUjian,
            $idSekolah
        );

        return $this->repository->getByUjian(
            $idUjian
        );
    }

    /**
     * Ambil satu soal
     */
    public function getById(
        int $idSoal,
        int $idUjian,
        int $idSekolah
    ): array {
        $this->getUjian(
            $idUjian,
            $idSekolah
        );

        $soal = $this->repository->findByUjian(
            $idSoal,
            $idUjian
        );

        if (!$soal) {
            throw new RuntimeException(
                'Soal tidak ditemukan.'
            );
        }

        return $soal;
    }

    /**
     * Membuat soal
     */
    public function create(
        int $idUjian,
        array $data,
        int $idSekolah
    ): int {
        $ujian = $this->getUjian(
            $idUjian,
            $idSekolah
        );

        /*
         * Ujian yang sudah selesai tidak boleh
         * ditambahkan soal baru.
         */
        if ($ujian['status'] === 'selesai') {
            throw new RuntimeException(
                'Soal tidak dapat ditambahkan pada ujian yang sudah selesai.'
            );
        }

        $this->validateData($data);

        $pilihan = $data['pilihan'];

        unset($data['pilihan']);

        $data['id_ujian'] = $idUjian;

        /*
         * Jika urutan tidak dikirim,
         * otomatis diletakkan setelah soal terakhir.
         */
        if (
            !isset($data['urutan']) ||
            $data['urutan'] === ''
        ) {
            $data['urutan'] =
                $this->repository->countByUjian(
                    $idUjian
                ) + 1;
        }

        $data['bobot'] = $data['bobot'] ?? 1;

        $data['created_at'] = date(
            'Y-m-d H:i:s'
        );

        $data['updated_at'] = date(
            'Y-m-d H:i:s'
        );

        $this->db->transStart();

        try {

            /*
             * Simpan soal
             */
            $idSoal = $this->repository->insert(
                $data
            );

            if (!$idSoal) {
                throw new RuntimeException(
                    'Gagal membuat soal.'
                );
            }

            /*
             * Simpan pilihan jawaban
             */
            $this->insertPilihan(
                $idSoal,
                $pilihan
            );

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new RuntimeException(
                    'Gagal menyimpan soal dan pilihan jawaban.'
                );
            }

            return (int) $idSoal;

        } catch (Throwable $e) {

            $this->db->transRollback();

            throw $e;
        }
    }

    /**
     * Update soal
     */
    public function update(
        int $idSoal,
        int $idUjian,
        array $data,
        int $idSekolah
    ): bool {

        $ujian = $this->getUjian(
            $idUjian,
            $idSekolah
        );

        if ($ujian['status'] === 'selesai') {
            throw new RuntimeException(
                'Soal tidak dapat diubah pada ujian yang sudah selesai.'
            );
        }

        $soal = $this->repository->findByUjian(
            $idSoal,
            $idUjian
        );

        if (!$soal) {
            throw new RuntimeException(
                'Soal tidak ditemukan.'
            );
        }

        /*
         * Untuk sementara update data soal.
         * Pilihan jawaban akan kita kelola
         * melalui endpoint pilihan jawaban.
         */
        unset(
            $data['id'],
            $data['id_ujian'],
            $data['pilihan']
        );

        if (isset($data['bobot'])) {
            if (
                !is_numeric($data['bobot']) ||
                (float) $data['bobot'] <= 0
            ) {
                throw new RuntimeException(
                    'Bobot soal harus lebih dari 0.'
                );
            }
        }

        if (isset($data['urutan'])) {
            if (
                !is_numeric($data['urutan']) ||
                (int) $data['urutan'] <= 0
            ) {
                throw new RuntimeException(
                    'Urutan soal harus lebih dari 0.'
                );
            }
        }

        $data['updated_at'] = date(
            'Y-m-d H:i:s'
        );

        return $this->repository->update(
            $idSoal,
            $data
        );
    }

    /**
     * Hapus soal
     */
    public function delete(
        int $idSoal,
        int $idUjian,
        int $idSekolah
    ): bool {

        $ujian = $this->getUjian(
            $idUjian,
            $idSekolah
        );

        if ($ujian['status'] === 'selesai') {
            throw new RuntimeException(
                'Soal tidak dapat dihapus pada ujian yang sudah selesai.'
            );
        }

        $soal = $this->repository->findByUjian(
            $idSoal,
            $idUjian
        );

        if (!$soal) {
            throw new RuntimeException(
                'Soal tidak ditemukan.'
            );
        }

        return $this->repository->delete(
            $idSoal
        );
    }

    /**
     * Pastikan ujian memang milik sekolah
     */
    protected function getUjian(
        int $idUjian,
        int $idSekolah
    ): array {
        $ujian = $this->ujianRepository->findBySekolah(
            $idUjian,
            $idSekolah
        );

        if (!$ujian) {
            throw new RuntimeException(
                'Ujian tidak ditemukan.'
            );
        }

        return $ujian;
    }

    /**
     * Validasi data soal
     */
    protected function validateData(
        array $data
    ): void {

        if (
            !isset($data['pertanyaan']) ||
            trim((string) $data['pertanyaan']) === ''
        ) {
            throw new RuntimeException(
                'Pertanyaan wajib diisi.'
            );
        }

        $tipe = $data['tipe'] ?? 'pilihan_ganda';

        if (
            !in_array(
                $tipe,
                [
                    'pilihan_ganda',
                    'benar_salah',
                    'esai'
                ],
                true
            )
        ) {
            throw new RuntimeException(
                'Tipe soal tidak valid.'
            );
        }

        if (
            isset($data['bobot']) &&
            (
                !is_numeric($data['bobot']) ||
                (float) $data['bobot'] <= 0
            )
        ) {
            throw new RuntimeException(
                'Bobot soal harus lebih dari 0.'
            );
        }

        if (
            isset($data['urutan']) &&
            (
                !is_numeric($data['urutan']) ||
                (int) $data['urutan'] <= 0
            )
        ) {
            throw new RuntimeException(
                'Urutan soal harus lebih dari 0.'
            );
        }

        if (
            !isset($data['pilihan']) ||
            !is_array($data['pilihan'])
        ) {
            throw new RuntimeException(
                'Pilihan jawaban wajib diisi.'
            );
        }

        /*
         * Untuk pilihan ganda minimal 2 pilihan.
         */
        if (
            $tipe === 'pilihan_ganda' &&
            count($data['pilihan']) < 2
        ) {
            throw new RuntimeException(
                'Soal pilihan ganda minimal memiliki 2 pilihan jawaban.'
            );
        }
    }

    /**
     * Simpan pilihan jawaban
     */
    protected function insertPilihan(
        int $idSoal,
        array $pilihan
    ): void {

        $builder = $this->db->table(
            'pilihan_jawaban'
        );

        $jumlahBenar = 0;
        $urutan = 1;

        foreach ($pilihan as $item) {

            if (!is_array($item)) {
                throw new RuntimeException(
                    'Format pilihan jawaban tidak valid.'
                );
            }

            $label = trim(
                (string) ($item['label'] ?? '')
            );

            $jawaban = trim(
                (string) ($item['jawaban'] ?? '')
            );

            $isBenar = !empty(
                $item['is_benar']
            ) ? 1 : 0;

            if ($label === '') {
                throw new RuntimeException(
                    'Label pilihan jawaban wajib diisi.'
                );
            }

            if ($jawaban === '') {
                throw new RuntimeException(
                    'Isi pilihan jawaban wajib diisi.'
                );
            }

            if ($isBenar === 1) {
                $jumlahBenar++;
            }

            $builder->insert([
                'id_soal' => $idSoal,
                'label' => $label,
                'jawaban' => $jawaban,
                'is_benar' => $isBenar,
                'urutan' => $urutan,
                'created_at' => date(
                    'Y-m-d H:i:s'
                ),
                'updated_at' => date(
                    'Y-m-d H:i:s'
                )
            ]);

            $urutan++;
        }

        /*
         * Untuk pilihan ganda harus ada tepat
         * satu jawaban benar.
         */
        if ($jumlahBenar !== 1) {
            throw new RuntimeException(
                'Soal pilihan ganda harus memiliki tepat satu jawaban benar.'
            );
        }
    }
}