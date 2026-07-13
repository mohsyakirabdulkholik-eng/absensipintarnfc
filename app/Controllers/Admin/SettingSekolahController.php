<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseAdminController;
use App\Services\SettingSekolahService;

class SettingSekolahController extends BaseAdminController
{
    protected SettingSekolahService $settingSekolahService;

    public function __construct()
    {
        $this->settingSekolahService = new SettingSekolahService();
    }

    /**
     * Halaman Pengaturan Sekolah
     */
    public function index()
    {
        $data = [

            'title' => 'Pengaturan Sekolah',

            'user'  => $this->currentUser(),

            'sekolah' => $this->settingSekolahService
                ->getById(session('id_sekolah'))

        ];

        return view(
            'setting_sekolah/index',
            $data
        );
    }

    /**
     * Update Pengaturan Sekolah
     */
    public function update()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        try {

            $this->settingSekolahService->update(

                session('id_sekolah'),

                $this->request->getPost()

            );

            return $this->response->setJSON(

                responseSuccess(
                    'Data sekolah berhasil diperbarui.'
                )

            );

        } catch (\Throwable $e) {

            return $this->response->setJSON(

                responseError(
                    $e->getMessage()
                )

            );

        }
    }
}