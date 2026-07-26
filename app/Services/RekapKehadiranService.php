<?php

namespace App\Services;

use App\Repositories\RekapKehadiranRepository;

class RekapKehadiranService
{
    protected RekapKehadiranRepository $repository;

    public function __construct()
    {
        $this->repository = new RekapKehadiranRepository();
    }


    /**
     * Rekap siswa per bulan.
     */
    public function getSiswa(
        int $idSekolah,
        int $tahun,
        int $bulan,
        ?int $idKelas = null
    ): array
    {
        [$tanggalMulai, $tanggalSelesai] =
            $this->getPeriodeBulan($tahun, $bulan);

        $orang = $this->repository->getSiswa(
            $idSekolah,
            $idKelas
        );

        $absensi = $this->repository->getAbsensiPeriode(
            $idSekolah,
            'siswa',
            $tanggalMulai,
            $tanggalSelesai
        );

        $hariLibur = $this->repository->getHariLibur(
            $idSekolah,
            $tanggalMulai,
            $tanggalSelesai
        );

        $hariAktif = $this->repository->getHariAktif(
            $idSekolah
        );

         if (empty($hariAktif)) {

            $hariAktif = [
                1, // Senin
                2, // Selasa
                3, // Rabu
                4, // Kamis
                5  // Jumat
            ];
        }


        return $this->buildRekap(
            $orang,
            $absensi,
            $hariLibur,
            $hariAktif,
            $tanggalMulai,
            $tanggalSelesai,
            'siswa'
        );
    }


    /**
     * Rekap guru per bulan.
     */
    public function getGuru(
        int $idSekolah,
        int $tahun,
        int $bulan
    ): array
    {
        [$tanggalMulai, $tanggalSelesai] =
            $this->getPeriodeBulan($tahun, $bulan);

        $orang = $this->repository->getGuru(
            $idSekolah
        );

        $absensi = $this->repository->getAbsensiPeriode(
            $idSekolah,
            'guru',
            $tanggalMulai,
            $tanggalSelesai
        );

        $hariLibur = $this->repository->getHariLibur(
            $idSekolah,
            $tanggalMulai,
            $tanggalSelesai
        );

        $hariAktif = $this->repository->getHariAktif(
            $idSekolah
        );

        /*
        * Fallback untuk sekolah lama yang
        * belum mempunyai konfigurasi.
        */
        if (empty($hariAktif)) {

            $hariAktif = [
                1, // Senin
                2, // Selasa
                3, // Rabu
                4, // Kamis
                5  // Jumat
            ];
        }

        return $this->buildRekap(
            $orang,
            $absensi,
            $hariLibur,
            $hariAktif,
            $tanggalMulai,
            $tanggalSelesai,
            'guru'
        );
    }


    /**
     * Tentukan tanggal awal dan akhir bulan.
     */
    private function getPeriodeBulan(
        int $tahun,
        int $bulan
    ): array
    {
        if ($bulan < 1 || $bulan > 12) {
            throw new \InvalidArgumentException(
                'Bulan tidak valid.'
            );
        }

        if ($tahun < 2000 || $tahun > 2100) {
            throw new \InvalidArgumentException(
                'Tahun tidak valid.'
            );
        }

        $tanggalMulai = sprintf(
            '%04d-%02d-01',
            $tahun,
            $bulan
        );

        $tanggalSelesai = date(
            'Y-m-t',
            strtotime($tanggalMulai)
        );

        return [
            $tanggalMulai,
            $tanggalSelesai
        ];
    }


    /**
     * Bangun rekap seluruh orang.
     */
    private function buildRekap(
        array $orang,
        array $absensi,
        array $hariLibur,
        array $hariAktif,
        string $tanggalMulai,
        string $tanggalSelesai,
        string $jenis
    ): array
    {
        /*
         * Buat daftar tanggal efektif.
         */
        $tanggalEfektif = $this->getTanggalEfektif(
            $tanggalMulai,
            $tanggalSelesai,
            $hariLibur,
            $hariAktif
        );

        /*
         * Index absensi berdasarkan kartu dan tanggal.
         *
         * Hasil:
         *
         * [
         *   10 => [
         *      '2026-07-01' => [...],
         *      '2026-07-02' => [...]
         *   ]
         * ]
         */
        $indexAbsensi = $this->indexAbsensi($absensi);

        $hasil = [];

        foreach ($orang as $item) {

            $idKartu = !empty($item['id_kartu_nfc'])
                ? (int) $item['id_kartu_nfc']
                : null;

            $hadir = 0;
            $terlambat = 0;
            $tidakHadir = 0;

            foreach ($tanggalEfektif as $tanggal) {

                $dataAbsensi = null;

                if (
                    $idKartu !== null &&
                    isset($indexAbsensi[$idKartu][$tanggal])
                ) {
                    $dataAbsensi =
                        $indexAbsensi[$idKartu][$tanggal];
                }

                /*
                 * Tidak ada transaksi absensi.
                 */
                if ($dataAbsensi === null) {

                    $tidakHadir++;

                    continue;
                }

                /*
                 * Ada transaksi = dianggap hadir.
                 */
                $hadir++;

                $status = strtolower(
                    (string) ($dataAbsensi['status'] ?? '')
                );

                if ($status === 'terlambat') {
                    $terlambat++;
                }
            }


            $jumlahHariEfektif =
                count($tanggalEfektif);

            $persentase = 0;

            if ($jumlahHariEfektif > 0) {

                $persentase = round(
                    ($hadir / $jumlahHariEfektif) * 100,
                    2
                );
            }


            /*
             * Data umum.
             */
            $row = [
                'id' => (int) $item['id'],

                'jenis' => $jenis,

                'id_kartu_nfc' => $idKartu,

                'hari_efektif' =>
                    $jumlahHariEfektif,

                /*
                 * Hadir termasuk terlambat.
                 */
                'hadir' => $hadir,

                'terlambat' => $terlambat,

                'tidak_hadir' => $tidakHadir,

                'persentase' => $persentase
            ];


            /*
             * Identitas siswa.
             */
            if ($jenis === 'siswa') {

                $row['identitas'] =
                    $item['nis'] ?? '-';

                $row['nama'] =
                    $item['nama_siswa'] ?? '-';

                $row['id_kelas'] =
                    $item['id_kelas'] ?? null;

                $row['nama_kelas'] =
                    $item['nama_kelas'] ?? '-';

            /*
             * Identitas guru.
             */
            } else {

                $row['identitas'] =
                    $item['nip'] ?? '-';

                $row['nama'] =
                    $item['nama'] ?? '-';

                $row['jabatan'] =
                    $item['jabatan'] ?? '-';
            }

            $hasil[] = $row;
        }


        /*
         * Statistik keseluruhan.
         */
        $statistik =
            $this->buildStatistik(
                $hasil,
                count($tanggalEfektif)
            );


        return [
            'periode' => [
                'tanggal_mulai' =>
                    $tanggalMulai,

                'tanggal_selesai' =>
                    $tanggalSelesai,

                'hari_efektif' =>
                    count($tanggalEfektif)
            ],

            'statistik' => $statistik,

            'data' => $hasil
        ];
    }


    /**
     * Index absensi berdasarkan
     * id kartu NFC + tanggal.
     */
    private function indexAbsensi(
        array $absensi
    ): array
    {
        $index = [];

        foreach ($absensi as $row) {

            if (
                empty($row['id_kartu_nfc']) ||
                empty($row['tanggal'])
            ) {
                continue;
            }

            $idKartu =
                (int) $row['id_kartu_nfc'];

            $tanggal =
                $row['tanggal'];

            $index[$idKartu][$tanggal] =
                $row;
        }

        return $index;
    }


   /**
     * Hitung tanggal efektif berdasarkan:
     *
     * 1. Hari aktif sekolah
     * 2. Hari libur khusus
     */
    private function getTanggalEfektif(
        string $tanggalMulai,
        string $tanggalSelesai,
        array $hariLibur,
        array $hariAktif
    ): array
    {
        /*
        * Index hari libur.
        */
        $libur = [];

        foreach ($hariLibur as $item) {

            if (!empty($item['tanggal'])) {
                $libur[$item['tanggal']] = true;
            }
        }


        /*
        * Normalisasi hari aktif menjadi integer.
        */
        $hariAktif = array_map(
            'intval',
            $hariAktif
        );


        $hasil = [];

        $current = new \DateTime(
            $tanggalMulai
        );

        $end = new \DateTime(
            $tanggalSelesai
        );


        while ($current <= $end) {

            $tanggal =
                $current->format('Y-m-d');

            /*
            * 1 = Senin
            * ...
            * 7 = Minggu
            */
            $hari =
                (int) $current->format('N');


            /*
            * Bukan hari aktif sekolah.
            */
            if (
                !in_array(
                    $hari,
                    $hariAktif,
                    true
                )
            ) {

                $current->modify('+1 day');

                continue;
            }


            /*
            * Hari tersebut aktif secara mingguan,
            * tetapi ditetapkan sebagai hari libur.
            */
            if (isset($libur[$tanggal])) {

                $current->modify('+1 day');

                continue;
            }


            /*
            * Hari efektif.
            */
            $hasil[] = $tanggal;

            $current->modify('+1 day');
        }


        return $hasil;
    }

    /**
     * Statistik seluruh hasil rekap.
     */
    private function buildStatistik(
        array $data,
        int $hariEfektif
    ): array
    {
        $totalOrang = count($data);

        $hadir = 0;
        $terlambat = 0;
        $tidakHadir = 0;

        foreach ($data as $row) {

            $hadir +=
                (int) $row['hadir'];

            $terlambat +=
                (int) $row['terlambat'];

            $tidakHadir +=
                (int) $row['tidak_hadir'];
        }


        /*
         * Total kesempatan hadir:
         *
         * jumlah orang × hari efektif.
         */
        $totalKesempatan =
            $totalOrang * $hariEfektif;


        $persentase = 0;

        if ($totalKesempatan > 0) {

            $persentase = round(
                ($hadir / $totalKesempatan) * 100,
                2
            );
        }


        return [
            'total_orang' =>
                $totalOrang,

            'hari_efektif' =>
                $hariEfektif,

            'hadir' =>
                $hadir,

            'terlambat' =>
                $terlambat,

            'tidak_hadir' =>
                $tidakHadir,

            'persentase' =>
                $persentase
        ];
    }
    /**
 * Detail rekap per orang.
 */
    public function getDetail(
        int $idSekolah,
        string $jenis,
        int $id,
        int $tahun,
        int $bulan
    ): array
    {
        if (!in_array($jenis, ['siswa', 'guru'], true)) {
            throw new \InvalidArgumentException(
                'Jenis rekap tidak valid.'
            );
        }

        [$tanggalMulai, $tanggalSelesai] =
            $this->getPeriodeBulan($tahun, $bulan);


        /*
        * ==========================
        * DATA ORANG
        * ==========================
        */
        if ($jenis === 'siswa') {

            $orang = $this->repository->findSiswa(
                $idSekolah,
                $id
            );

        } else {

            $orang = $this->repository->findGuru(
                $idSekolah,
                $id
            );
        }


        if (!$orang) {
            throw new \RuntimeException(
                'Data tidak ditemukan.'
            );
        }


        /*
        * ==========================
        * HARI LIBUR
        * ==========================
        */
        $hariLibur = $this->repository->getHariLibur(
            $idSekolah,
            $tanggalMulai,
            $tanggalSelesai
        );

        $hariAktif = $this->repository->getHariAktif(
            $idSekolah
        );

         if (empty($hariAktif)) {

            $hariAktif = [
                1, // Senin
                2, // Selasa
                3, // Rabu
                4, // Kamis
                5  // Jumat
            ];
        }


        $hariAktif = array_map(
            'intval',
            $hariAktif
        );
        /*
        * ==========================
        * ABSENSI
        * ==========================
        */
        $absensi = [];

        if (!empty($orang['id_kartu_nfc'])) {

            $absensi = $this->repository->getAbsensiKartu(
                $idSekolah,
                (int) $orang['id_kartu_nfc'],
                $jenis,
                $tanggalMulai,
                $tanggalSelesai
            );
        }


        /*
        * Index absensi berdasarkan tanggal.
        */
        $indexAbsensi = [];

        foreach ($absensi as $item) {

            if (!empty($item['tanggal'])) {
                $indexAbsensi[$item['tanggal']] = $item;
            }
        }


        /*
        * Index hari libur.
        */
        $indexLibur = [];

        foreach ($hariLibur as $item) {

            if (!empty($item['tanggal'])) {
                $indexLibur[$item['tanggal']] = $item;
            }
        }


        /*
        * ==========================
        * DETAIL PER TANGGAL
        * ==========================
        */
        $detail = [];

        $current = new \DateTime($tanggalMulai);
        $end     = new \DateTime($tanggalSelesai);

        while ($current <= $end) {

            $tanggal = $current->format('Y-m-d');

            $nomorHari = (int) $current->format('N');

            $row = [
                'tanggal'          => $tanggal,
                'hari'             => $this->namaHari($nomorHari),
                'status'           => 'tidak_hadir',
                'jam_masuk'        => null,
                'jam_pulang'       => null,
                'terlambat_menit'  => 0,
                'keterangan'       => null
            ];


            /*
            * Weekend.
            */
            /*
 * Bukan hari aktif sekolah.
 */
if (
    !in_array(
        $nomorHari,
        $hariAktif,
        true
    )
) {

    $row['status'] = 'libur';

    $row['keterangan'] =
        'Bukan hari aktif sekolah';


/*
 * Hari aktif tetapi ditetapkan
 * sebagai hari libur.
 */
} elseif (isset($indexLibur[$tanggal])) {

    $row['status'] = 'libur';

    $row['keterangan'] =
        $indexLibur[$tanggal]['nama_libur']
        ?? 'Hari Libur';


/*
 * Ada absensi.
 */
} elseif (isset($indexAbsensi[$tanggal])) {

                $dataAbsensi =
                    $indexAbsensi[$tanggal];

                $row['status'] =
                    strtolower(
                        $dataAbsensi['status']
                        ?? 'hadir'
                    );

                $row['jam_masuk'] =
                    $dataAbsensi['jam_masuk']
                    ?? null;

                $row['jam_pulang'] =
                    $dataAbsensi['jam_pulang']
                    ?? null;

                $row['terlambat_menit'] =
                    (int) (
                        $dataAbsensi['terlambat_menit']
                        ?? 0
                    );

                $row['keterangan'] =
                    $dataAbsensi['keterangan']
                    ?? null;
            }

            $detail[] = $row;

            $current->modify('+1 day');
        }


        /*
        * ==========================
        * IDENTITAS
        * ==========================
        */
        $identitas = [
            'id'    => (int) $orang['id'],
            'jenis' => $jenis
        ];

        if ($jenis === 'siswa') {

            $identitas['nomor'] =
                $orang['nis'] ?? '-';

            $identitas['nama'] =
                $orang['nama_siswa'] ?? '-';

            $identitas['kelas'] =
                $orang['nama_kelas'] ?? '-';

        } else {

            $identitas['nomor'] =
                $orang['nip'] ?? '-';

            $identitas['nama'] =
                $orang['nama'] ?? '-';

            $identitas['jabatan'] =
                $orang['jabatan'] ?? '-';
        }


        return [
            'identitas' => $identitas,

            'periode' => [
                'tanggal_mulai'   => $tanggalMulai,
                'tanggal_selesai' => $tanggalSelesai
            ],

            'detail' => $detail
        ];
    }


    /**
     * Nama hari Indonesia.
     */
    private function namaHari(int $hari): string
    {
        $nama = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];

        return $nama[$hari] ?? '-';
    }
}