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
}