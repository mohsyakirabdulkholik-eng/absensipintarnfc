<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\Api\AbsensiService;
use CodeIgniter\HTTP\ResponseInterface;

class AbsensiController extends BaseController
{
    protected AbsensiService $service;

    public function __construct()
    {
        $this->service = new AbsensiService();
    }

    /**
     * Proses Absensi NFC
     */
    public function attendance(): ResponseInterface
    {
        $token = trim($this->request->getHeaderLine('X-Device-Token'));
        $uid   = trim($this->request->getVar('uid'));

        if (empty($token)) {
            return $this->response->setJSON([
                'success' => false,
                'code'    => 'TOKEN_REQUIRED',
                'message' => 'Token perangkat wajib dikirim.',
                'data'    => []
            ]);
        }

        if (empty($uid)) {
            return $this->response->setJSON([
                'success' => false,
                'code'    => 'UID_REQUIRED',
                'message' => 'UID kartu wajib dikirim.',
                'data'    => []
            ]);
        }

        $result = $this->service->attendance(
            $token,
            $uid
        );

        return $this->response->setJSON($result);
    }

    /**
     * Ambil Riwayat Absensi
     */
    public function history(): ResponseInterface
    {
        $token = trim(
            $this->request
                ->getHeaderLine('X-Device-Token')
        );

        if (empty($token)) {

            return $this->response->setJSON([
                'success' => false,
                'code'    => 'TOKEN_REQUIRED',
                'message' => 'Token perangkat wajib dikirim.',
                'data'    => []
            ]);
        }

        $limit = (int) (
            $this->request->getGet('limit')
            ?? 50
        );

        // Batasi agar request tidak mengambil data terlalu banyak
        if ($limit < 1) {
            $limit = 50;
        }

        if ($limit > 100) {
            $limit = 100;
        }

        $result = $this->service->history(
            $token,
            $limit
        );

        return $this->response
            ->setJSON($result);
    }
}