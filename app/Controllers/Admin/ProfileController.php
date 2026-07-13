<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseAdminController;
use App\Services\ProfileService;


class ProfileController extends BaseAdminController
{
    protected ProfileService $profileService;

    public function __construct()
    {
        $this->profileService = new ProfileService();
    }

    public function index()
    {
        $data = [

            'title'   => 'Profil Saya',

            'profile' => $this->profileService
                ->getProfile(session('user_id'))

        ];

        return view('profile/index', $data);
    }

    public function update()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        $this->profileService->updateProfile(

            session('user_id'),

            $this->request->getPost()

        );

        return $this->response->setJSON(

            responseSuccess(
                'Profil berhasil diperbarui.'
            )

        );
    }
    public function uploadPhoto()
    {
        
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        try {

            $this->profileService->uploadPhoto(

                session('user_id'),

                $this->request->getFile('foto')

            );

            return $this->response->setJSON(

                responseSuccess(
                    'Foto profil berhasil diperbarui.'
                )

            );

        } catch (\Throwable $e) {

            return $this->response->setJSON(

                responseError($e->getMessage())

            );

        }
    }
    public function uploadLogo()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        try {

            $this->profileService->uploadLogo(
                session('user_id'),
                $this->request->getFile('logo')
            );

            return $this->response->setJSON(
                responseSuccess('Logo sekolah berhasil diperbarui.')
            );

        } catch (\Throwable $e) {

            return $this->response->setJSON(
                responseError($e->getMessage())
            );

        }
    }
}