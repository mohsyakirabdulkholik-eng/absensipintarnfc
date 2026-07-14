<?php

namespace App\Repositories;

use Config\Database;
use App\Models\AbsensiModel;

class AbsensiRepository
{
    protected $db;
    protected AbsensiModel $absensiModel;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->absensiModel = new AbsensiModel();
    }

    /**
     * Ambil kartu berdasarkan UID
     */
    public function getKartuByUid(string $uid): ?array
    {
        return $this->db
        ->table('kartu_nfc k')
        ->select("
            k.id,
            k.uid,
            k.nomor_kartu,
            k.status,
            k.id_guru,
            k.id_siswa,

            COALESCE(g.nama_guru, s.nama_siswa) AS nama,

            COALESCE(g.id_sekolah, s.id_sekolah) AS id_sekolah,

            kelas.nama_kelas
        ")
        ->join('guru g', 'g.id = k.id_guru', 'left')
        ->join('siswa s', 's.id = k.id_siswa', 'left')
        ->join('kelas', 'kelas.id = s.id_kelas', 'left')
        ->where('k.uid', $uid)
        ->get()
        ->getRowArray();
    }

    /**
     * Ambil absensi hari ini
     */
    public function getAbsensiHariIni(
        int $idKartu,
        string $tanggal
    ): ?array
    {
        return $this->db
            ->table('absensi')
            ->where('id_kartu_nfc', $idKartu)
            ->where('tanggal', $tanggal)
            ->get()
            ->getRowArray();
    }

    /**
     * Tahun Ajaran Aktif
     */
    public function getTahunAktif(
        int $idSekolah
    ): ?array
    {
        return $this->db
            ->table('tahun_ajaran')
            ->where('id_sekolah', $idSekolah)
            ->where('aktif', 1)
            ->get()
            ->getRowArray();
    }

    /**
     * Semester Aktif
     */
    public function getSemesterAktif(
        int $idSekolah
    ): ?array
    {
        return $this->db
            ->table('semester')
            ->where('id_tahun', $idTahun)
            ->where('aktif', 1)
            ->get()
            ->getRowArray();
    }

    /**
     * Pengaturan Absensi
     */
    public function getPengaturan(
        int $idSekolah
    ): ?array
    {
        return $this->db
            ->table('pengaturan_absensi')
            ->where('id_sekolah', $idSekolah)
            ->first();
    }

    /**
     * Simpan Absensi
     */
    public function insertAbsensi(array $data)
    {
        return $this->absensiModel->insert($data);
    }

    /**
     * Update Jam Pulang
     */
    public function updatePulang(
        int $id,
        array $data
    )
    {
        return $this->absensiModel->update($id, $data);
    }
}