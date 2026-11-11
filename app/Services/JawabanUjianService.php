<?php

namespace App\Services;

use App\Repositories\JawabanUjianRepository;
use App\Repositories\PesertaUjianRepository;
use App\Repositories\SoalRepository;
use Config\Database;
use RuntimeException;

class JawabanUjianService
{
    protected JawabanUjianRepository $repository;
    protected PesertaUjianRepository $pesertaRepository;
    protected SoalRepository $soalRepository;
    protected $db;

    public function __construct()
    {
        $this->repository = new JawabanUjianRepository();
        $this->pesertaRepository = new PesertaUjianRepository();
        $this->soalRepository = new SoalRepository();
        $this->db = Database::connect();
    }

    /**
     * Ambil jawaban peserta.
     */
    public function getByPeserta(int $idPesertaUjian): array
    {
        $this->validatePeserta($idPesertaUjian);

        return $this->repository->getByPeserta($idPesertaUjian);
    }

    /**
     * Simpan atau perbarui jawaban siswa.
     */
    public function saveJawaban(array $data): array
    {
        $idPesertaUjian = (int) ($data['id_peserta_ujian'] ?? 0);
        $idSoal = (int) ($data['id_soal'] ?? 0);

        if ($idPesertaUjian <= 0 || $idSoal <= 0) {
            throw new RuntimeException(
                'ID peserta dan ID soal wajib diisi.'
            );
        }

        // Validasi peserta
        $peserta = $this->validatePeserta($idPesertaUjian);

        if (($peserta['status'] ?? '') !== 'sedang_ujian') {
            throw new RuntimeException(
                'Peserta belum memulai atau ujian sudah selesai.'
            );
        }

        // Validasi soal milik ujian peserta
        $soal = $this->soalRepository->find($idSoal);

        if (
            !$soal ||
            (int) $soal['id_ujian'] !== (int) $peserta['id_ujian']
        ) {
            throw new RuntimeException(
                'Soal tidak ditemukan pada ujian peserta.'
            );
        }

        $tipeSoal = $soal['tipe'];
        $bobot = (float) $soal['bobot'];

        $idPilihan = isset($data['id_pilihan'])
            ? (int) $data['id_pilihan']
            : null;

        $jawabanEsai = trim((string) ($data['jawaban_esai'] ?? ''));

        $isBenar = 0;
        $nilai = 0;

        /*
         * SOAL PILIHAN GANDA DAN BENAR/SALAH
         */
        if (in_array($tipeSoal, ['pilihan_ganda', 'benar_salah'], true)) {

            if (!$idPilihan || $idPilihan <= 0) {
                throw new RuntimeException(
                    'Pilihan jawaban wajib dipilih.'
                );
            }

            if ($jawabanEsai !== '') {
                throw new RuntimeException(
                    'Soal ini tidak menerima jawaban esai.'
                );
            }

            // Pastikan pilihan memang milik soal tersebut
            $pilihan = $this->db
                ->table('pilihan_jawaban')
                ->where('id', $idPilihan)
                ->where('id_soal', $idSoal)
                ->get()
                ->getRowArray();

            if (!$pilihan) {
                throw new RuntimeException(
                    'Pilihan jawaban tidak valid untuk soal ini.'
                );
            }

            // Periksa kunci jawaban
            $isBenar = (int) $pilihan['is_benar'];

            // Nilai otomatis sesuai bobot soal
            $nilai = $isBenar === 1 ? $bobot : 0;

            $jawabanEsai = null;
        }

        /*
         * SOAL ESAI
         */
        elseif ($tipeSoal === 'esai') {

            if ($jawabanEsai === '') {
                throw new RuntimeException(
                    'Jawaban esai wajib diisi.'
                );
            }

            if ($idPilihan !== null && $idPilihan > 0) {
                throw new RuntimeException(
                    'Soal esai tidak menerima pilihan jawaban.'
                );
            }

            $idPilihan = null;

            // Esai menunggu koreksi guru
            $isBenar = 0;
            $nilai = 0;
        }

        else {
            throw new RuntimeException(
                'Tipe soal tidak dikenali.'
            );
        }

        // Data jawaban
        $jawaban = [
            'id_peserta_ujian' => $idPesertaUjian,
            'id_soal' => $idSoal,
            'id_pilihan' => $idPilihan,
            'jawaban_esai' => $jawabanEsai !== ''
                ? $jawabanEsai
                : null,
            'is_benar' => $isBenar,
            'nilai' => $nilai,
            'waktu_jawab' => date('Y-m-d H:i:s'),
        ];

        // Cek jawaban sebelumnya
        $existing = $this->repository
            ->findByPesertaAndSoal(
                $idPesertaUjian,
                $idSoal
            );

        if ($existing) {

            $this->repository->update(
                (int) $existing['id'],
                $jawaban
            );

            return [
                'id' => (int) $existing['id'],
                'aksi' => 'update',
                'is_benar' => $isBenar,
                'nilai' => $nilai,
                'message' => 'Jawaban berhasil diperbarui.',
            ];
        }

        // Simpan jawaban baru
        $id = $this->repository->insert($jawaban);

        if (!$id) {
            throw new RuntimeException(
                'Gagal menyimpan jawaban.'
            );
        }

        return [
            'id' => $id,
            'aksi' => 'insert',
            'is_benar' => $isBenar,
            'nilai' => $nilai,
            'message' => 'Jawaban berhasil disimpan.',
        ];
    }

    /**
     * Validasi peserta ujian.
     */
    protected function validatePeserta(
        int $idPesertaUjian
    ): array {
        $peserta = $this->pesertaRepository
            ->findById($idPesertaUjian);

        if (!$peserta) {
            throw new RuntimeException(
                'Peserta ujian tidak ditemukan.'
            );
        }

        return $peserta;
    }
}