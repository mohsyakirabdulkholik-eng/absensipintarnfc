<?php

namespace App\Repositories;

use Config\Database;

class RekapKehadiranRepository
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }


    /**
     * Daftar siswa aktif.
     */
    public function getSiswa(
        int $idSekolah,
        ?int $idKelas = null
    ): array
    {
        $builder = $this->db->table('siswa');

        $builder->select('
            siswa.id,
            siswa.nis,
            siswa.nama_siswa,

            kelas.id AS id_kelas,
            kelas.nama_kelas,

            kartu_nfc.id AS id_kartu_nfc
        ');

        $builder->join(
            'kelas',
            'kelas.id = siswa.id_kelas',
            'left'
        );

        $builder->join(
            'kartu_nfc',
            'kartu_nfc.id_siswa = siswa.id',
            'left'
        );

        $builder->where(
            'siswa.id_sekolah',
            $idSekolah
        );

        $builder->where(
            'siswa.status',
            'aktif'
        );

        if ($idKelas !== null) {
            $builder->where(
                'siswa.id_kelas',
                $idKelas
            );
        }

        $builder->orderBy(
            'kelas.nama_kelas',
            'ASC'
        );

        $builder->orderBy(
            'siswa.nama_siswa',
            'ASC'
        );

        return $builder
            ->get()
            ->getResultArray();
    }


    /**
     * Daftar guru aktif.
     */
    public function getGuru(
        int $idSekolah
    ): array
    {
        return $this->db
            ->table('guru')
            ->select('
                guru.id,
                guru.nip,
                guru.nama,
                guru.jabatan,

                kartu_nfc.id AS id_kartu_nfc
            ')
            ->join(
                'kartu_nfc',
                'kartu_nfc.id_guru = guru.id',
                'left'
            )
            ->where(
                'guru.id_sekolah',
                $idSekolah
            )
            ->where(
                'guru.status',
                'aktif'
            )
            ->where(
                'guru.deleted_at',
                null
            )
            ->orderBy(
                'guru.nama',
                'ASC'
            )
            ->get()
            ->getResultArray();
    }


    /**
     * Semua transaksi absensi pada suatu periode.
     */
    public function getAbsensiPeriode(
        int $idSekolah,
        string $jenis,
        string $tanggalMulai,
        string $tanggalSelesai
    ): array
    {
        return $this->db
            ->table('absensi')
            ->select('
                id,
                id_kartu_nfc,
                tanggal,
                jam_masuk,
                jam_pulang,
                status,
                terlambat_menit,
                keterangan
            ')
            ->where(
                'id_sekolah',
                $idSekolah
            )
            ->where(
                'jenis',
                $jenis
            )
            ->where(
                'tanggal >=',
                $tanggalMulai
            )
            ->where(
                'tanggal <=',
                $tanggalSelesai
            )
            ->orderBy(
                'tanggal',
                'ASC'
            )
            ->get()
            ->getResultArray();
    }


    /**
     * Hari libur sekolah pada periode tertentu.
     */
    public function getHariLibur(
        int $idSekolah,
        string $tanggalMulai,
        string $tanggalSelesai
    ): array
    {
        return $this->db
            ->table('hari_libur')
            ->select('
                tanggal,
                nama_libur,
                keterangan
            ')
            ->where(
                'id_sekolah',
                $idSekolah
            )
            ->where(
                'tanggal >=',
                $tanggalMulai
            )
            ->where(
                'tanggal <=',
                $tanggalSelesai
            )
            ->orderBy(
                'tanggal',
                'ASC'
            )
            ->get()
            ->getResultArray();
    }


    /**
     * Detail satu siswa.
     */
    public function findSiswa(
        int $idSekolah,
        int $idSiswa
    ): ?array
    {
        return $this->db
            ->table('siswa')
            ->select('
                siswa.id,
                siswa.nis,
                siswa.nama_siswa,

                kelas.id AS id_kelas,
                kelas.nama_kelas,

                kartu_nfc.id AS id_kartu_nfc
            ')
            ->join(
                'kelas',
                'kelas.id = siswa.id_kelas',
                'left'
            )
            ->join(
                'kartu_nfc',
                'kartu_nfc.id_siswa = siswa.id',
                'left'
            )
            ->where(
                'siswa.id_sekolah',
                $idSekolah
            )
            ->where(
                'siswa.id',
                $idSiswa
            )
            ->get()
            ->getRowArray();
    }


    /**
     * Detail satu guru.
     */
    public function findGuru(
        int $idSekolah,
        int $idGuru
    ): ?array
    {
        return $this->db
            ->table('guru')
            ->select('
                guru.id,
                guru.nip,
                guru.nama,
                guru.jabatan,

                kartu_nfc.id AS id_kartu_nfc
            ')
            ->join(
                'kartu_nfc',
                'kartu_nfc.id_guru = guru.id',
                'left'
            )
            ->where(
                'guru.id_sekolah',
                $idSekolah
            )
            ->where(
                'guru.id',
                $idGuru
            )
            ->where(
                'guru.deleted_at',
                null
            )
            ->get()
            ->getRowArray();
    }
    /**
 * Ambil absensi berdasarkan satu kartu NFC
 * pada periode tertentu.
 */
    public function getAbsensiKartu(
        int $idSekolah,
        int $idKartuNfc,
        string $jenis,
        string $tanggalMulai,
        string $tanggalSelesai
    ): array
    {
        return $this->db
            ->table('absensi')
            ->select('
                id,
                id_kartu_nfc,
                tanggal,
                jam_masuk,
                jam_pulang,
                status,
                terlambat_menit,
                keterangan
            ')
            ->where('id_sekolah', $idSekolah)
            ->where('id_kartu_nfc', $idKartuNfc)
            ->where('jenis', $jenis)
            ->where('tanggal >=', $tanggalMulai)
            ->where('tanggal <=', $tanggalSelesai)
            ->orderBy('tanggal', 'ASC')
            ->get()
            ->getResultArray();
    }
    /**
     * Ambil daftar hari aktif sekolah.
     *
     * Format hasil:
     * [1, 2, 3, 4, 5]
     *
     * atau sekolah Senin-Sabtu:
     * [1, 2, 3, 4, 5, 6]
     */
    public function getHariAktif(
        int $idSekolah
    ): array
    {
        $rows = $this->db
            ->table('hari_aktif_sekolah')
            ->select('hari')
            ->where('id_sekolah', $idSekolah)
            ->where('aktif', 1)
            ->orderBy('hari', 'ASC')
            ->get()
            ->getResultArray();

        return array_map(
            'intval',
            array_column($rows, 'hari')
        );
    }
}