<?php

namespace App\Repositories;

use App\Models\UserModel;
use App\Models\SekolahModel;
use App\Models\JenjangModel;
use Config\Database;

class AuthRepository
{
    protected UserModel $userModel;
    protected SekolahModel $sekolahModel;
    protected JenjangModel $jenjangModel;
    protected $db;

    public function __construct()
    {
        $this->userModel     = new UserModel();
        $this->sekolahModel  = new SekolahModel();
        $this->jenjangModel  = new JenjangModel();
        $this->db            = Database::connect();
    }
    public function findByLogin(string $login): ?array
    {
        return $this->userModel
            ->select('
                users.*,
                sekolah.nama_sekolah,
                sekolah.id_jenjang,
                jenjang.kode,
                jenjang.nama as nama_jenjang,
                jenjang.is_jurusan,
                roles.nama_role
            ')
            ->join(
                'sekolah',
                'sekolah.id = users.id_sekolah',
                'left'
            )
            ->join(
                'jenjang',
                'jenjang.id = sekolah.id_jenjang',
                'left'
            )
            ->join(
                'roles',
                'roles.id = users.id_role'
            )
            ->groupStart()
                ->where('users.username', $login)
                ->orWhere('users.email', $login)
            ->groupEnd()
            ->first();
    }
    public function updateLastLogin($id)
    {
        return $this->userModel
            ->update($id, [
                'last_login' => date('Y-m-d H:i:s')
            ]);
    }
    public function getJenjang(): array
    {
        return $this->jenjangModel
            ->orderBy('id')
            ->findAll();
    }

    /**
 * Register Admin Sekolah
 */
    public function register(array $sekolahData, array $userData): bool
    {
        $this->db->transBegin();

        try {

            // Simpan data sekolah
            if (!$this->sekolahModel->insert($sekolahData)) {
                throw new \Exception('Gagal menyimpan data sekolah.');
            }

            $idSekolah = $this->sekolahModel->getInsertID();

            // Lengkapi data user
            $userData['id_sekolah'] = $idSekolah;

            // Simpan user
            if (!$this->userModel->insert($userData)) {
                throw new \Exception('Gagal menyimpan akun administrator.');
            }

            if ($this->db->transStatus() === false) {
                throw new \Exception('Gagal menyimpan data.');
            }

            $this->db->transCommit();

            return true;

        } catch (\Throwable $e) {

            $this->db->transRollback();

            throw $e;

        }
    }
    public function cekUsername(string $username): ?array
    {
        return $this->userModel
            ->where('username', $username)
            ->first();
    }
    public function cekEmail(string $email): ?array
    {
        return $this->userModel
            ->where('email', $email)
            ->first();
    }
    public function cekNpsn(string $npsn): ?array
    {
        return $this->sekolahModel
            ->where('npsn', $npsn)
            ->first();
    }
}