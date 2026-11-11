<?php

namespace App\Services;

use App\Repositories\PilihanJawabanRepository;
use App\Repositories\SoalRepository;
use RuntimeException;

class PilihanJawabanService
{
    protected PilihanJawabanRepository $repository;
    protected SoalRepository $soalRepository;

    public function __construct()
    {
        $this->repository = new PilihanJawabanRepository();
        $this->soalRepository = new SoalRepository();
    }

    /**
     * Ambil semua pilihan berdasarkan soal
     */
    public function getBySoal(int $idSoal): array
    {
        $this->validateSoal($idSoal);

        return $this->repository->getBySoal($idSoal);
    }

    /**
     * Tambah pilihan jawaban
     */
    public function create(
        int $idSoal,
        array $data
    ): int {
        $this->validateSoal($idSoal);
        $this->validateData($data);

        $jumlahPilihan = $this->repository
            ->countBySoal($idSoal);

        if ($jumlahPilihan >= 5) {
            throw new RuntimeException(
                'Maksimal 5 pilihan jawaban dalam satu soal.'
            );
        }

        $data['id_soal'] = $idSoal;

        $data['label'] = strtoupper(
            trim($data['label'])
        );

        $data['is_benar'] = (int) (
            $data['is_benar'] ?? 0
        );

        $data['urutan'] = $data['urutan']
            ?? ($jumlahPilihan + 1);

        // Jika jawaban ini benar,
        // jadikan pilihan lainnya salah.
        if ($data['is_benar'] === 1) {
            $this->resetJawabanBenar($idSoal);
        }

        $id = $this->repository->insert($data);

        if (!$id) {
            throw new RuntimeException(
                'Gagal menambahkan pilihan jawaban.'
            );
        }

        return $id;
    }

    /**
     * Update pilihan jawaban
     */
    public function update(
        int $idSoal,
        int $idPilihan,
        array $data
    ): bool {
        $this->validateSoal($idSoal);

        $pilihan = $this->repository->findBySoal(
            $idPilihan,
            $idSoal
        );

        if (!$pilihan) {
            throw new RuntimeException(
                'Pilihan jawaban tidak ditemukan.'
            );
        }

        $this->validateData($data, false);

        if (isset($data['label'])) {
            $data['label'] = strtoupper(
                trim($data['label'])
            );
        }

        if (isset($data['is_benar'])) {
            $data['is_benar'] = (int) $data['is_benar'];

            if ($data['is_benar'] === 1) {
                $this->resetJawabanBenar(
                    $idSoal,
                    $idPilihan
                );
            }
        }

        return $this->repository->update(
            $idPilihan,
            $data
        );
    }

    /**
     * Hapus pilihan jawaban
     */
    public function delete(
        int $idSoal,
        int $idPilihan
    ): bool {
        $this->validateSoal($idSoal);

        $pilihan = $this->repository->findBySoal(
            $idPilihan,
            $idSoal
        );

        if (!$pilihan) {
            throw new RuntimeException(
                'Pilihan jawaban tidak ditemukan.'
            );
        }

        return $this->repository->delete(
            $idPilihan
        );
    }

    /**
     * Reset jawaban benar lainnya
     */
    protected function resetJawabanBenar(
        int $idSoal,
        ?int $exceptId = null
    ): void {
        $pilihan = $this->repository
            ->getBySoal($idSoal);

        foreach ($pilihan as $item) {
            if (
                $exceptId !== null &&
                (int) $item['id'] === $exceptId
            ) {
                continue;
            }

            if ((int) $item['is_benar'] === 1) {
                $this->repository->update(
                    (int) $item['id'],
                    ['is_benar' => 0]
                );
            }
        }
    }

    /**
     * Validasi soal
     */
    protected function validateSoal(int $idSoal): void
    {
        $soal = $this->soalRepository->find($idSoal);

        if (!$soal) {
            throw new RuntimeException(
                'Soal tidak ditemukan.'
            );
        }
    }

    /**
     * Validasi data pilihan jawaban
     */
    protected function validateData(
        array $data,
        bool $required = true
    ): void {
        if ($required) {
            $requiredFields = [
                'label',
                'jawaban'
            ];

            foreach ($requiredFields as $field) {
                if (
                    !isset($data[$field]) ||
                    trim((string) $data[$field]) === ''
                ) {
                    throw new RuntimeException(
                        ucfirst($field) . ' wajib diisi.'
                    );
                }
            }
        }

        if (isset($data['label'])) {
            $label = strtoupper(
                trim($data['label'])
            );

            if (!in_array(
                $label,
                ['A', 'B', 'C', 'D', 'E'],
                true
            )) {
                throw new RuntimeException(
                    'Label harus A, B, C, D, atau E.'
                );
            }
        }

        if (isset($data['is_benar'])) {
            if (!in_array(
                (int) $data['is_benar'],
                [0, 1],
                true
            )) {
                throw new RuntimeException(
                    'is_benar hanya boleh bernilai 0 atau 1.'
                );
            }
        }
    }
}
