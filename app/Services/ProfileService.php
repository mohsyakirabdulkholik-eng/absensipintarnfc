<?php

namespace App\Services;

use App\Repositories\ProfileRepository;
use CodeIgniter\HTTP\Files\UploadedFile;

class ProfileService
{
    protected ProfileRepository $repository;

    public function __construct()
    {
        $this->repository = new ProfileRepository();
    }

    public function getProfile($idUser)
    {
        return $this->repository
            ->getProfile($idUser);
    }

    public function updateProfile($idUser, array $data)
    {
        return $this->repository
            ->updateProfile($idUser, [

                'nama'   => trim($data['nama']),
                'email'  => trim($data['email']),
                'no_hp'  => trim($data['no_hp'])

            ]);
    }
      /**
     * Upload Foto Profil
     */
    public function uploadPhoto(
        int $idUser,
        UploadedFile $file
    ): void
    {
        $profile = $this->repository->getProfile($idUser);

        $foto = uploadImage($file, 'uploads/profile', 'PROFILE');

        $this->repository->updatePhoto($idUser, $foto);

        deleteImage(
            'uploads/profile',
            $profile['foto'],
            'default.png'
        );
    }

   /**
     * Upload Logo Sekolah
     */
    public function uploadLogo(int $idUser, UploadedFile $file): void
    {
        $profile = $this->repository->getProfile($idUser);

        $logo = uploadImage(
            $file,
            'uploads/logo',
            'LOGO'
        );
        
        $this->repository->updateLogo($profile['id_sekolah'], $logo);

        deleteImage(
            'uploads/logo',
            $profile['logo'],
            'logo.png'
        );
    }
    
}