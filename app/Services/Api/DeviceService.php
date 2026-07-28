<?php

namespace App\Services\Api;

use App\Repositories\Api\DeviceRepository;

class DeviceService
{
    protected DeviceRepository $repository;

    public function __construct()
    {
        $this->repository = new DeviceRepository();
    }

    /**
     * Ambil perangkat aktif berdasarkan token
     */
    private function getValidDevice(string $token): array
    {
        $device = $this->repository->findByToken($token);

        if (!$device) {
            return [
                'success' => false,
                'code'    => 'DEVICE_NOT_FOUND',
                'message' => 'Perangkat tidak ditemukan.'
            ];
        }

        if ($device['status'] !== 'aktif') {
            return [
                'success' => false,
                'code'    => 'DEVICE_INACTIVE',
                'message' => 'Perangkat dinonaktifkan.'
            ];
        }

        return [
            'success' => true,
            'data'    => $device
        ];
    }

    /**
     * Pairing Device
     * Android / NodeMCU
     */
    public function pair(array $data): array
    {
        // ==============================
        // TOKEN
        // ==============================

        if (empty($data['token'])) {
            return [
                'success' => false,
                'code'    => 'TOKEN_REQUIRED',
                'message' => 'Token perangkat wajib diisi.'
            ];
        }

        $result = $this->getValidDevice($data['token']);

        if (!$result['success']) {
            return $result;
        }

        $device = $result['data'];

        $jenis = $device['jenis_perangkat'] ?? 'android';


        // ==============================
        // ANDROID
        // ==============================

        if ($jenis === 'android') {

            if (empty($data['android_id'])) {
                return [
                    'success' => false,
                    'code'    => 'ANDROID_ID_REQUIRED',
                    'message' => 'Android ID wajib diisi.'
                ];
            }

            $android = $this->repository
                ->isAndroidUsed($data['android_id']);

            if (
                $android &&
                $android['id'] != $device['id']
            ) {
                return [
                    'success' => false,
                    'code'    => 'ANDROID_ALREADY_USED',
                    'message' =>
                        'Android sudah digunakan oleh perangkat lain.'
                ];
            }

            // Token yang sudah terikat ke Android lain
            // tidak boleh langsung dipindahkan.
            if (
                !empty($device['android_id']) &&
                $device['android_id'] !== $data['android_id']
            ) {
                return [
                    'success' => false,
                    'code'    => 'DEVICE_ALREADY_PAIRED',
                    'message' =>
                        'Perangkat sudah dipairing. Reset Device terlebih dahulu.'
                ];
            }

            $this->repository->updatePairing(
                $device['id'],
                [
                    'android_id'      => $data['android_id'],
                    'merk'            => $data['merk'] ?? null,
                    'model'           => $data['model'] ?? null,
                    'versi_android'   => $data['versi_android'] ?? null,
                    'last_sync'       => date('Y-m-d H:i:s')
                ]
            );
        }


        // ==============================
        // NODEMCU
        // ==============================

        elseif ($jenis === 'nodemcu') {

            if (empty($data['hardware_id'])) {
                return [
                    'success' => false,
                    'code'    => 'HARDWARE_ID_REQUIRED',
                    'message' =>
                        'Hardware ID NodeMCU wajib diisi.'
                ];
            }

            $hardware = $this->repository
                ->isHardwareIdUsed($data['hardware_id']);

            if (
                $hardware &&
                $hardware['id'] != $device['id']
            ) {
                return [
                    'success' => false,
                    'code'    => 'HARDWARE_ALREADY_USED',
                    'message' =>
                        'NodeMCU sudah digunakan oleh perangkat lain.'
                ];
            }

            // Token ini sudah terkunci ke NodeMCU lain
            if (
                !empty($device['hardware_id']) &&
                $device['hardware_id'] !== $data['hardware_id']
            ) {
                return [
                    'success' => false,
                    'code'    => 'DEVICE_ALREADY_PAIRED',
                    'message' =>
                        'Perangkat sudah dipairing. Reset Device terlebih dahulu.'
                ];
            }

            $this->repository->updatePairing(
                $device['id'],
                [
                    'hardware_id' => $data['hardware_id'],
                    'last_sync'   => date('Y-m-d H:i:s')
                ]
            );
        }


        // ==============================
        // JENIS TIDAK VALID
        // ==============================

        else {

            return [
                'success' => false,
                'code'    => 'DEVICE_TYPE_INVALID',
                'message' =>
                    'Jenis perangkat tidak dikenali.'
            ];
        }


        // ==============================
        // SUCCESS
        // ==============================

        return [
            'success' => true,
            'code'    => 'PAIRING_SUCCESS',
            'message' => 'Pairing perangkat berhasil.',

            'data' => [

                'device_id' =>
                    $device['id'],

                'nama_perangkat' =>
                    $device['nama_perangkat'],

                'jenis_perangkat' =>
                    $jenis,

                'id_sekolah' =>
                    $device['id_sekolah'],

                'token' =>
                    $device['token']
            ]
        ];
    }

    /**
     * Heartbeat
     */
    public function heartbeat(array $data): array
    {
        if (empty($data['token'])) {
            return [
                'success' => false,
                'code'    => 'TOKEN_REQUIRED',
                'message' => 'Token perangkat wajib diisi.'
            ];
        }

        $result = $this->getValidDevice($data['token']);

        if (!$result['success']) {
            return $result;
        }

        $device = $result['data'];

        $this->repository->updateHeartbeat($device['id']);

        return [
            'success' => true,
            'code'    => 'HEARTBEAT_SUCCESS',
            'message' => 'Heartbeat berhasil.',
            'data' => [
                'last_sync' => date('Y-m-d H:i:s')
            ]
        ];
    }

    /**
     * Unpair Android
     */
    public function unpair(array $data): array
    {
        if (empty($data['token'])) {
            return [
                'success' => false,
                'code'    => 'TOKEN_REQUIRED',
                'message' => 'Token perangkat wajib diisi.'
            ];
        }

        $result = $this->getValidDevice($data['token']);

        if (!$result['success']) {
            return $result;
        }

        $device = $result['data'];

        $this->repository->clearPairing($device['id']);

        return [
            'success' => true,
            'code'    => 'UNPAIR_SUCCESS',
            'message' => 'Perangkat berhasil dilepas.'
        ];
    }

    /**
     * Authenticate Device
     */
    public function authenticate(string $token): array
    {
        $result = $this->getValidDevice($token);

        if (!$result['success']) {
            return $result;
        }

        $device = $result['data'];

       $jenis = $device['jenis_perangkat'] ?? 'android';

        if ($jenis === 'android') {

            if (empty($device['android_id'])) {
                return [
                    'success' => false,
                    'code'    => 'DEVICE_NOT_PAIRED',
                    'message' => 'Perangkat belum dipairing.'
                ];
            }

        } elseif ($jenis === 'nodemcu') {

            if (empty($device['hardware_id'])) {
                return [
                    'success' => false,
                    'code'    => 'DEVICE_NOT_PAIRED',
                    'message' => 'Perangkat belum dipairing.'
                ];
            }

        } else {

            return [
                'success' => false,
                'code'    => 'DEVICE_TYPE_INVALID',
                'message' => 'Jenis perangkat tidak dikenali.'
            ];
        }

        return [
            'success' => true,
            'code'    => 'AUTH_SUCCESS',
            'message' => 'Autentikasi berhasil.',
            'data' => [

                        'device_id' =>
                            $device['id'],

                        'id_sekolah' =>
                            $device['id_sekolah'],

                        'nama_perangkat' =>
                            $device['nama_perangkat'],

                        'jenis_perangkat' =>
                            $jenis,

                        'status' =>
                            $device['status'],

                        'last_sync' =>
                            $device['last_sync']
                    ]
        ];
    }

    /**
     * Validasi perangkat untuk seluruh API
     */
    public function validateDevice(string $token): array
    {
        $result = $this->getValidDevice($token);

        if (!$result['success']) {
            return $result;
        }

        $device = $result['data'];

        $jenis = $device['jenis_perangkat'] ?? 'android';

        if ($jenis === 'android') {

            if (empty($device['android_id'])) {
                return [
                    'success' => false,
                    'code'    => 'DEVICE_NOT_PAIRED',
                    'message' => 'Perangkat belum dipairing.'
                ];
            }

        } elseif ($jenis === 'nodemcu') {

            if (empty($device['hardware_id'])) {
                return [
                    'success' => false,
                    'code'    => 'DEVICE_NOT_PAIRED',
                    'message' => 'Perangkat belum dipairing.'
                ];
            }

        } else {

            return [
                'success' => false,
                'code'    => 'DEVICE_TYPE_INVALID',
                'message' => 'Jenis perangkat tidak dikenali.'
            ];
        }

        return [
            'success' => true,
            'code'    => 'DEVICE_VALID',
            'message' => 'Perangkat valid.',
            'data'    => $device
        ];
    }
}