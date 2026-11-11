<?php

namespace App\Repositories\Api;

use CodeIgniter\Database\BaseConnection;
use Config\Database;

class LoginSiswaRepository
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Cari siswa berdasarkan NIS.
     */
    public function findSiswaByNis(string $nis): ?array
    {
        $data = $this->db->table('siswa')
            ->where('nis', $nis)
            ->where('status', 'aktif')
            ->get()
            ->getRowArray();

        return $data ?: null;
    }

    /**
     * Cari ujian berdasarkan token.
     */
    public function findUjianByToken(string $token): ?array
    {
        $data = $this->db->table('ujian')
            ->where('token', strtoupper(trim($token)))
            ->where('status', 'aktif')
            ->get()
            ->getRowArray();

        return $data ?: null;
    }

    /**
     * Pastikan siswa terdaftar sebagai peserta ujian.
     */
    public function findPesertaUjian(
        int $idUjian,
        int $idSiswa
    ): ?array {
        $data = $this->db->table('peserta_ujian')
            ->where('id_ujian', $idUjian)
            ->where('id_siswa', $idSiswa)
            ->get()
            ->getRowArray();

        return $data ?: null;
    }
}
