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
 * Simpan atau ubah kehadiran manual siswa.
 *
 * Jika absensi pada tanggal tersebut sudah ada,
 * maka status dan keterangan akan diperbarui.
 *
 * Jika belum ada, maka dibuat record absensi baru.
 */
public function saveStudentManualAttendance(
    int $idSekolah,
    int $idKartuNfc,
    string $tanggal,
    string $status,
    ?string $keterangan
): bool {
    /*
     * Cari absensi siswa pada tanggal yang dipilih.
     */
    $attendance = $this->db
        ->table('absensi')
        ->where('id_sekolah', $idSekolah)
        ->where('id_kartu_nfc', $idKartuNfc)
        ->where('tanggal', $tanggal)
        ->where('jenis', 'siswa')
        ->get()
        ->getRowArray();

    /*
     * Jika sudah ada absensi,
     * cukup update status dan keterangan.
     */
    if ($attendance) {
        return $this->db
            ->table('absensi')
            ->where('id', $attendance['id'])
            ->update([
                'status'          => $status,
                'keterangan'      => $keterangan,
                'terlambat_menit' => 0
            ]);
    }

    /*
     * Jika belum ada absensi,
     * buat record baru.
     *
     * Jam masuk dan jam pulang
     * sengaja dikosongkan karena
     * ini adalah input manual.
     */
    return $this->db
        ->table('absensi')
        ->insert([
            'id_sekolah'       => $idSekolah,
            'jenis'            => 'siswa',
            'id_kartu_nfc'     => $idKartuNfc,
            'tanggal'          => $tanggal,
            'jam_masuk'        => null,
            'jam_pulang'       => null,
            'status'           => $status,
            'terlambat_menit'  => 0,
            'keterangan'       => $keterangan,
            'device_id'        => null
        ]);
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