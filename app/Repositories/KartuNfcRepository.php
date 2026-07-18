<?php

namespace App\Repositories;

use Config\Database;

class KartuNfcRepository
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Ambil semua kartu NFC berdasarkan sekolah
     */
    public function getAll(int $idSekolah): array
    {
        return $this->db
            ->table('kartu_nfc k')
            ->select("
                k.id,
                k.uid,
                k.id_sekolah,
                k.id_guru,
                k.id_siswa,
                k.nomor_kartu,
                k.status,
                k.tanggal_daftar,
                k.created_at,
                k.updated_at,

                g.nama AS nama_guru,
                s.nama_siswa,

                COALESCE(g.nama, s.nama_siswa) AS nama_pemilik,

                CASE
                    WHEN k.id_guru IS NOT NULL THEN 'guru'
                    WHEN k.id_siswa IS NOT NULL THEN 'siswa'
                    ELSE 'tidak diketahui'
                END AS jenis_pemilik
            ")
            ->join('guru g', 'g.id = k.id_guru', 'left')
            ->join('siswa s', 's.id = k.id_siswa', 'left')
            ->where('k.id_sekolah', $idSekolah)
            ->orderBy('k.id', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Ambil satu kartu berdasarkan ID
     */
    public function find(int $id): ?array
    {
        return $this->db
            ->table('kartu_nfc k')
            ->select("
                k.*,

                g.nama AS nama_guru,
                s.nama_siswa,

                COALESCE(g.nama, s.nama_siswa) AS nama_pemilik,

                CASE
                    WHEN k.id_guru IS NOT NULL THEN 'guru'
                    WHEN k.id_siswa IS NOT NULL THEN 'siswa'
                    ELSE 'tidak diketahui'
                END AS jenis_pemilik
            ")
            ->join('guru g', 'g.id = k.id_guru', 'left')
            ->join('siswa s', 's.id = k.id_siswa', 'left')
            ->where('k.id', $id)
            ->get()
            ->getRowArray();
    }

    /**
     * Update status kartu
     */
    public function updateStatus(
        int $id,
        string $status
    ): bool {
        return (bool) $this->db
            ->table('kartu_nfc')
            ->where('id', $id)
            ->update([
                'status'     => $status,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
    }

    /**
     * Cek kartu berdasarkan UID
     */
    public function findByUid(
        string $uid,
        int $idSekolah
    ): ?array {
        return $this->db
            ->table('kartu_nfc')
            ->where('uid', $uid)
            ->where('id_sekolah', $idSekolah)
            ->get()
            ->getRowArray();
    }
}