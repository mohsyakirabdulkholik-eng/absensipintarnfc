<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\Api\KelasService;

class KelasController extends BaseController
{
    protected KelasService $service;

    public function __construct()
    {
        $this->service = new KelasService();
    }

    /**
     * Mengambil daftar kelas.
     */
    public function index()
    {
        /*
         * Ambil token perangkat.
         */
        $token = trim(
            $this->request->getHeaderLine('X-Device-Token')
        );

        /*
         * Proses melalui service.
         */
        $result = $this->service->getAll($token);

        return $this->response->setJSON($result);
    }
}