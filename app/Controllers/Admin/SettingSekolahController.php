<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseAdminController;
use App\Services\SettingSekolahService;
use App\Models\HariAktifSekolahModel;

class SettingSekolahController extends BaseAdminController
{
    protected SettingSekolahService $settingSekolahService;
    protected HariAktifSekolahModel $hariAktifModel;

    public function __construct()
    {
        $this->settingSekolahService = new SettingSekolahService();
        $this->hariAktifModel = new HariAktifSekolahModel();
    }

    /**
     * Halaman Pengaturan Sekolah
     */
    public function index()
    {
        $idSekolah = (int) session('id_sekolah');

        /*
        * Ambil konfigurasi hari aktif.
        */
        $hariAktif = $this->hariAktifModel
            ->where('id_sekolah', $idSekolah)
            ->orderBy('hari', 'ASC')
            ->findAll();

        $hariAktifMap = [];

        foreach ($hariAktif as $item) {

            $hariAktifMap[
                (int) $item['hari']
            ] = (bool) $item['aktif'];
        }

        $data = [

            'title' => 'Pengaturan Sekolah',

            'user'  => $this->currentUser(),

            'sekolah' => $this->settingSekolahService
                ->getById(session('id_sekolah')),
            'hariAktif' =>
            $hariAktifMap

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

    /**
 * Update Hari Aktif Sekolah
 */
    public function updateHariAktif()
    {
        if (!$this->request->isAJAX()) {

            return $this->response
                ->setStatusCode(404);
        }


        try {

            $idSekolah =
                (int) session('id_sekolah');


            /*
            * Checkbox yang dicentang dikirim
            * sebagai array:
            *
            * hari[] = 1
            * hari[] = 2
            * ...
            */
            $hariDipilih =
                $this->request->getPost('hari');


            if (!is_array($hariDipilih)) {
                $hariDipilih = [];
            }


            /*
            * Normalisasi menjadi integer.
            */
            $hariDipilih = array_map(
                'intval',
                $hariDipilih
            );


            /*
            * Hanya menerima 1 - 7.
            */
            $hariDipilih = array_values(
                array_filter(
                    array_unique($hariDipilih),
                    fn ($hari) =>
                        $hari >= 1 &&
                        $hari <= 7
                )
            );


            /*
            * Minimal satu hari aktif.
            */
            if (empty($hariDipilih)) {

                throw new \RuntimeException(
                    'Minimal satu hari sekolah harus aktif.'
                );
            }


            /*
            * Simpan semua hari.
            */
            for ($hari = 1; $hari <= 7; $hari++) {

                $aktif = in_array(
                    $hari,
                    $hariDipilih,
                    true
                ) ? 1 : 0;


                /*
                * Cari konfigurasi yang sudah ada.
                */
                $existing =
                    $this->hariAktifModel
                        ->where(
                            'id_sekolah',
                            $idSekolah
                        )
                        ->where(
                            'hari',
                            $hari
                        )
                        ->first();


                if ($existing) {

                    $this->hariAktifModel->update(
                        $existing['id'],
                        [
                            'aktif' => $aktif
                        ]
                    );

                } else {

                    /*
                    * Pengaman jika sekolah lama/belum
                    * memiliki konfigurasi hari.
                    */
                    $this->hariAktifModel->insert([
                        'id_sekolah' =>
                            $idSekolah,

                        'hari' =>
                            $hari,

                        'aktif' =>
                            $aktif
                    ]);
                }
            }


            return $this->response->setJSON(

                responseSuccess(
                    'Hari aktif sekolah berhasil diperbarui.'
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