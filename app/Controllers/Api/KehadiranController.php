<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\Api\KehadiranService;

class KehadiranController extends BaseController
{
    protected KehadiranService $service;

    public function __construct()
    {
        $this->service = new KehadiranService();
    }

    /**
     * Mengambil laporan kehadiran siswa / guru.
     */
    public function index()
    {
        /*
         * Token perangkat.
         */
        $token = trim(
            $this->request->getHeaderLine('X-Device-Token')
        );

        /*
         * Filter.
         */
        $tanggal = trim(
            (string) $this->request->getGet('tanggal')
        );

        $jenis = strtolower(
            trim((string) $this->request->getGet('jenis'))
        );

        $idKelas = $this->request->getGet('id_kelas');

        /*
         * Default tanggal hari ini.
         */
        if ($tanggal === '') {
            $tanggal = date('Y-m-d');
        }

        /*
         * Default siswa.
         */
        if ($jenis === '') {
            $jenis = 'siswa';
        }

        /*
         * id_kelas optional.
         */
        $idKelas = (
            $idKelas !== null &&
            $idKelas !== ''
        )
            ? (int) $idKelas
            : null;

        /*
         * Proses melalui service.
         */
        $result = $this->service->getData(
            $token,
            $tanggal,
            $jenis,
            $idKelas
        );

        return $this->response->setJSON($result);
    }
}