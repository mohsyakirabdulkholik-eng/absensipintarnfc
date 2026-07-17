<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\Api\NfcService;

class NfcController extends BaseController
{
    protected NfcService $service;

    public function __construct()
    {
        $this->service = new NfcService();
    }

    /**
     * Membaca kartu NFC
     */
    public function read()
    {
        $input = $this->request->getJSON(true);

        if (!$input) {
            $input = $this->request->getPost();
        }

        $token = trim($input['token'] ?? '');
        $uid   = strtoupper(trim($input['uid'] ?? ''));

        $result = $this->service->read($token, $uid);

        return $this->response->setJSON($result);
    }
}