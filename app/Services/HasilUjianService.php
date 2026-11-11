<?php

namespace App\Services;

use App\Repositories\HasilUjianRepository;
use App\Repositories\PesertaUjianRepository;
use App\Repositories\UjianRepository;
use Config\Database;
use RuntimeException;

class HasilUjianService
{
    protected HasilUjianRepository $repository;
    protected PesertaUjianRepository $pesertaRepository;
    protected UjianRepository $ujianRepository;
    protected $db;

    public function __construct()
    {
        $this->repository = new HasilUjianRepository();
        $this->pesertaRepository = new PesertaUjianRepository();
        $this->ujianRepository = new UjianRepository();
        $this->db = Database::connect();
    }

    /**
     * Proses penilaian dan penyelesaian ujian.
     */
    public function selesaikanUjian(
        int $idPesertaUjian,
        int $idSekolah
    ): array {
        $this->db->transBegin();

        try {
            // Ambil peserta ujian
            $peserta = $this->db
                ->table('peserta_ujian')
                ->where('id', $idPesertaUjian)
                ->get()
                ->getRowArray();

            if (!$peserta) {
                throw new RuntimeException('Peserta ujian tidak ditemukan.');
            }

            // Ambil ujian
            $ujian = $this->ujianRepository->find(
                (int) $peserta['id_ujian']
            );

            if (!$ujian) {
                throw new RuntimeException('Ujian tidak ditemukan.');
            }

            if ((int) $ujian['id_sekolah'] !== $idSekolah) {
                throw new RuntimeException('Ujian bukan milik sekolah ini.');
            }

            if ($peserta['status'] !== 'sedang_ujian') {
                throw new RuntimeException(
                    'Peserta belum memulai atau ujian sudah selesai.'
                );
            }

            // Periksa hasil yang sudah pernah dibuat
            $hasilLama = $this->repository->findByPeserta(
                $idPesertaUjian
            );

            if ($hasilLama) {
                throw new RuntimeException(
                    'Hasil ujian peserta sudah tersedia.'
                );
            }

            // Ambil seluruh soal ujian
            $soal = $this->db
                ->table('soal')
                ->where('id_ujian', $peserta['id_ujian'])
                ->get()
                ->getResultArray();

            if (empty($soal)) {
                throw new RuntimeException('Ujian belum memiliki soal.');
            }

            $jumlahSoal = count($soal);
            $jumlahBenar = 0;
            $jumlahSalah = 0;
            $totalBobot = 0;
            $bobotBenar = 0;

            foreach ($soal as $item) {
                $bobot = (float) $item['bobot'];
                $totalBobot += $bobot;

                // Ambil jawaban peserta
                $jawaban = $this->db
                    ->table('jawaban_ujian')
                    ->where('id_peserta_ujian', $idPesertaUjian)
                    ->where('id_soal', $item['id'])
                    ->get()
                    ->getRowArray();

                if (!$jawaban) {
                    // Soal yang tidak dijawab dihitung salah
                    $jumlahSalah++;
                    continue;
                }

                if ((int) $jawaban['is_benar'] === 1) {
                    $jumlahBenar++;
                    $bobotBenar += $bobot;
                } else {
                    $jumlahSalah++;
                }
            }

            if ($totalBobot <= 0) {
                throw new RuntimeException(
                    'Total bobot soal harus lebih besar dari nol.'
                );
            }

            // Hitung nilai akhir
            $nilai = ($bobotBenar / $totalBobot) * 100;

            $kkm = (float) $ujian['kkm'];

            $status = $nilai >= $kkm
                ? 'lulus'
                : 'remedial';

            $waktuSelesai = date('Y-m-d H:i:s');

            // Simpan hasil ujian
            $idHasil = $this->repository->insert([
                'id_ujian' => (int) $peserta['id_ujian'],
                'id_siswa' => (int) $peserta['id_siswa'],
                'id_peserta_ujian' => $idPesertaUjian,
                'jumlah_soal' => $jumlahSoal,
                'jumlah_benar' => $jumlahBenar,
                'jumlah_salah' => $jumlahSalah,
                'nilai' => round($nilai, 2),
                'kkm' => $kkm,
                'status' => $status,
                'waktu_selesai' => $waktuSelesai,
            ]);

            if (!$idHasil) {
                throw new RuntimeException(
                    'Gagal menyimpan hasil ujian.'
                );
            }

            // Ubah status peserta menjadi selesai
            $berhasilSelesai = $this->pesertaRepository->finishExam(
                $idPesertaUjian
            );

            if (!$berhasilSelesai) {
                throw new RuntimeException(
                    'Gagal mengubah status peserta menjadi selesai.'
                );
            }

            if ($this->db->transStatus() === false) {
                throw new RuntimeException(
                    'Transaksi penyelesaian ujian gagal.'
                );
            }

            $this->db->transCommit();

            return [
                'id_hasil_ujian' => $idHasil,
                'id_peserta_ujian' => $idPesertaUjian,
                'jumlah_soal' => $jumlahSoal,
                'jumlah_benar' => $jumlahBenar,
                'jumlah_salah' => $jumlahSalah,
                'nilai' => round($nilai, 2),
                'kkm' => $kkm,
                'status' => $status,
                'waktu_selesai' => $waktuSelesai,
            ];

        } catch (\Throwable $e) {
            $this->db->transRollback();
            throw $e;
        }
    }
}