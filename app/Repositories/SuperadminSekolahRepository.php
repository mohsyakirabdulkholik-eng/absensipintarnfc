<?php

namespace App\Repositories;

use Config\Database;

class SuperadminSekolahRepository
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function getAll(): array
    {
        return $this->db
            ->table('sekolah')
            ->select('
                sekolah.id,
                sekolah.nama_sekolah,
                sekolah.npsn,
                sekolah.email,
                sekolah.telepon,
                sekolah.status,
                sekolah.created_at,
                jenjang.nama AS nama_jenjang,
                users.nama AS nama_admin,
                users.username AS username_admin
            ')
            ->join(
                'jenjang',
                'jenjang.id = sekolah.id_jenjang',
                'left'
            )
            ->join(
                'users',
                'users.id_sekolah = sekolah.id
                 AND users.id_role = 2',
                'left'
            )
            ->orderBy('sekolah.id', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function find(int $id): ?array
    {
        return $this->db
            ->table('sekolah')
            ->select('
                sekolah.*,
                jenjang.nama AS nama_jenjang,
                users.nama AS nama_admin,
                users.username AS username_admin,
                users.email AS email_admin
            ')
            ->join(
                'jenjang',
                'jenjang.id = sekolah.id_jenjang',
                'left'
            )
            ->join(
                'users',
                'users.id_sekolah = sekolah.id
                 AND users.id_role = 2',
                'left'
            )
            ->where('sekolah.id', $id)
            ->get()
            ->getRowArray() ?: null;
    }
}