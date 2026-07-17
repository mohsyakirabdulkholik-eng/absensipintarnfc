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
}