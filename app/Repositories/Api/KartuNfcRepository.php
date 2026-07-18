<?php

namespace App\Repositories\Api;

use CodeIgniter\Database\BaseConnection;
use Config\Database;

class KartuNfcRepository
{
    protected BaseConnection $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Cari kartu NFC berdasarkan UID dan sekolah
     */
    public function findByUid(string $uid, int $idSekolah): ?array
    {
        return $this->db->table('kartu_nfc k')
            ->select("
                        k.id AS id_kartu_nfc,
                        k.uid,
                        k.nomor_kartu,
                        k.status AS status_kartu,

                        g.id AS id_guru,
                        g.nama AS nama_guru,
                        g.status AS status_guru,
                        g.id_sekolah AS sekolah_guru,

                        s.id AS id_siswa,
                        s.nama_siswa,
                        s.status AS status_siswa,
                        s.id_sekolah AS sekolah_siswa
                    ")
            ->join('guru g', 'g.id = k.id_guru', 'left')
            ->join('siswa s', 's.id = k.id_siswa', 'left')
            ->where('k.uid', $uid)
            ->where('k.id_sekolah', $idSekolah)
            ->where('k.status', 'aktif')
            ->get()
            ->getRowArray();
    }

    /**
     * Cari kartu aktif berdasarkan guru
     */
    public function findByGuru(
        int $idGuru,
        int $idSekolah
    ): ?array {
        return $this->db->table('kartu_nfc')
            ->where('id_guru', $idGuru)
            ->where('id_sekolah', $idSekolah)
            ->where('status', 'aktif')
            ->get()
            ->getRowArray();
    }

    /**
     * Cari kartu aktif berdasarkan siswa
     */
    public function findBySiswa(
        int $idSiswa,
        int $idSekolah
    ): ?array {
        return $this->db->table('kartu_nfc')
            ->where('id_siswa', $idSiswa)
            ->where('id_sekolah', $idSekolah)
            ->where('status', 'aktif')
            ->get()
            ->getRowArray();
    }

    /**
     * Simpan kartu NFC
     */
    public function insert(array $data): bool
    {
        return $this->db
            ->table('kartu_nfc')
            ->insert($data);
    }
}