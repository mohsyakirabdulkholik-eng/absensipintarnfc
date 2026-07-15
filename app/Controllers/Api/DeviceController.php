<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\Api\DeviceService;

class DeviceController extends BaseController
{
    protected DeviceService $service;

    public function __construct()
    {
        $this->service = new DeviceService();
    }

    /**
     * Pairing Android
     */
    public function pair()
    {
        $data = $this->request->getJSON(true);

        return $this->response->setJSON(
            $this->service->pair($data)
        );
    }

    /**
     * Heartbeat
     */
    public function heartbeat()
    {
        $data = $this->request->getJSON(true);

        return $this->response->setJSON(
            $this->service->heartbeat($data)
        );
    }

    /**
     * Unpair Device
     */
    public function unpair()
    {
        $data = $this->request->getJSON(true);

        return $this->response->setJSON(
            $this->service->unpair($data)
        );
    }

    /**
     * Authenticate Device
     */
    public function authenticate()
    {
        $data = $this->request->getJSON(true);

        return $this->response->setJSON(
            $this->service->authenticate(
                $data['token'] ?? ''
            )
        );
    }
}