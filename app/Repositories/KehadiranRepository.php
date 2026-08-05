<?php

namespace App\Repositories;

use Config\Database;

class KehadiranRepository
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Ambil daftar kehadiran siswa.
     *
     * Dimulai dari tabel siswa supaya siswa yang belum
     * melakukan absensi tetap muncul.
     */
    public function getSiswa(
        int $idSekolah,
        string $tanggal,
        ?int $idKelas = null
    ): array
    {
        $builder = $this->db->table('siswa');

        $builder->select('
            siswa.id,
            siswa.nis,
            siswa.nisn,
            siswa.nama_siswa,
            siswa.status AS status_siswa,

            kelas.id AS id_kelas,
            kelas.nama_kelas,

            kartu_nfc.id AS id_kartu_nfc,

            absensi.id AS id_absensi,
            absensi.tanggal,
            absensi.jam_masuk,
            absensi.jam_pulang,
            absensi.status AS status_absensi,
            absensi.terlambat_menit,
            absensi.keterangan
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

        /*
         * Filter tanggal diletakkan di JOIN.
         * Tujuannya agar siswa yang belum absen
         * tetap muncul.
         */
        $tanggalEscaped = $this->db->escape($tanggal);

        $builder->join(
            'absensi',
            "absensi.id_kartu_nfc = kartu_nfc.id
             AND absensi.tanggal = {$tanggalEscaped}
             AND absensi.jenis = 'siswa'",
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
     * Ambil daftar kehadiran guru.
     *
     * Dimulai dari tabel guru supaya guru yang belum
     * melakukan absensi tetap muncul.
     */
    public function getGuru(
        int $idSekolah,
        string $tanggal
    ): array
    {
        $builder = $this->db->table('guru');

        $builder->select('
            guru.id,
            guru.nip,
            guru.nama,
            guru.jabatan,
            guru.status AS status_guru,

            kartu_nfc.id AS id_kartu_nfc,

            absensi.id AS id_absensi,
            absensi.tanggal,
            absensi.jam_masuk,
            absensi.jam_pulang,
            absensi.status AS status_absensi,
            absensi.terlambat_menit,
            absensi.keterangan
        ');

        $builder->join(
            'kartu_nfc',
            'kartu_nfc.id_guru = guru.id',
            'left'
        );

        $tanggalEscaped = $this->db->escape($tanggal);

        $builder->join(
            'absensi',
            "absensi.id_kartu_nfc = kartu_nfc.id
             AND absensi.tanggal = {$tanggalEscaped}
             AND absensi.jenis = 'guru'",
            'left'
        );

        $builder->where(
            'guru.id_sekolah',
            $idSekolah
        );

        $builder->where(
            'guru.status',
            'aktif'
        );

        /*
         * GuruModel menggunakan soft delete.
         */
        $builder->where(
            'guru.deleted_at',
            null
        );

        $builder->orderBy(
            'guru.nama',
            'ASC'
        );

        return $builder
            ->get()
            ->getResultArray();
    }
    /**
     * Cari absensi guru berdasarkan ID dan sekolah.
     */
    public function findGuruAttendance(
        int $idAbsensi,
        int $idSekolah
    ): ?array
    {
        $data = $this->db
            ->table('absensi')
            ->select('
                absensi.id,
                absensi.id_sekolah,
                absensi.jenis,
                absensi.tanggal,
                absensi.jam_masuk,
                absensi.jam_pulang
            ')
            ->where('absensi.id', $idAbsensi)
            ->where('absensi.id_sekolah', $idSekolah)
            ->where('absensi.jenis', 'guru')
            ->get()
            ->getRowArray();

        return $data ?: null;
    }


    /**
     * Batalkan absensi pulang guru.
     */
    public function cancelGuruCheckout(
        int $idAbsensi
    ): bool
    {
        return $this->db
            ->table('absensi')
            ->where('id', $idAbsensi)
            ->update([
                'jam_pulang' => null
            ]);
    }
}