<?php

namespace App\Repositories;

use App\Models\UserModel;
use Config\Database;

class ProfileRepository
{
    protected UserModel $userModel;
    protected $db;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->db = Database::connect();
    }

    /**
     * Ambil profil user beserta sekolah
     */
    public function getProfile(int $idUser)
    {
        return $this->userModel
            ->select("
                users.*,

                roles.nama_role,

                sekolah.nama_sekolah,
                sekolah.npsn,
                sekolah.alamat,
                sekolah.telepon,
                sekolah.email as email_sekolah,
                sekolah.logo,

                jenjang.nama as nama_jenjang
            ")
            ->join("roles", "roles.id = users.id_role")
            ->join("sekolah", "sekolah.id = users.id_sekolah")
            ->join("jenjang", "jenjang.id = sekolah.id_jenjang")
            ->where("users.id", $idUser)
            ->first();
    }

    /**
     * Update profil user
     */
    public function updateProfile($idUser, array $data)
    {
        return $this->userModel
            ->update($idUser, $data);
    }
    /**
 * Update foto profil user
 */
    public function updatePhoto(int $idUser, string $foto)
    {
        return $this->userModel->update($idUser, [
            'foto' => $foto
        ]);
    }

    /**
     * Update logo sekolah
     */
    public function updateLogo(int $idSekolah, string $logo)
    {
        return $this->db
            ->table('sekolah')
            ->where('id', $idSekolah)
            ->update([
                'logo' => $logo
            ]);
    }
    /**
 * Update nama file foto user
 */

}