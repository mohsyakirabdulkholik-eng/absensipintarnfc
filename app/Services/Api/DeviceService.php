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
     * Pairing Android
     */
    public function pair(array $data): array
    {
        if (empty($data['token'])) {
            return [
                'success' => false,
                'code'    => 'TOKEN_REQUIRED',
                'message' => 'Token perangkat wajib diisi.'
            ];
        }

        if (empty($data['android_id'])) {
            return [
                'success' => false,
                'code'    => 'ANDROID_ID_REQUIRED',
                'message' => 'Android ID wajib diisi.'
            ];
        }

        $device = $this->repository->findByToken($data['token']);

        if (!$device) {
            return [
                'success' => false,
                'code'    => 'DEVICE_NOT_FOUND',
                'message' => 'Token perangkat tidak ditemukan.'
            ];
        }

        if ($device['status'] !== 'aktif') {
            return [
                'success' => false,
                'code'    => 'DEVICE_INACTIVE',
                'message' => 'Perangkat dinonaktifkan oleh administrator.'
            ];
        }

        $android = $this->repository->isAndroidUsed($data['android_id']);

        if ($android && $android['id'] != $device['id']) {
            return [
                'success' => false,
                'code'    => 'ANDROID_ALREADY_USED',
                'message' => 'Android sudah digunakan oleh perangkat lain.'
            ];
        }

        $this->repository->updatePairing($device['id'], [

            'android_id'      => $data['android_id'],

            'merk'            => $data['merk'] ?? null,

            'model'           => $data['model'] ?? null,

            'versi_android'   => $data['versi_android'] ?? null,

            'last_sync'       => date('Y-m-d H:i:s')

        ]);

        return [

            'success' => true,

            'code'    => 'PAIRING_SUCCESS',

            'message' => 'Pairing perangkat berhasil.',

            'data' => [

                'device_id'      => $device['id'],

                'nama_perangkat' => $device['nama_perangkat'],

                'id_sekolah'     => $device['id_sekolah'],

                'token'          => $device['token']

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

        $device = $this->repository->findByToken($data['token']);

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

        $device = $this->repository->findByToken($data['token']);

        if (!$device) {
            return [
                'success' => false,
                'code'    => 'DEVICE_NOT_FOUND',
                'message' => 'Perangkat tidak ditemukan.'
            ];
        }

        $this->repository->clearPairing($device['id']);

        return [
            'success' => true,
            'code'    => 'UNPAIR_SUCCESS',
            'message' => 'Perangkat berhasil dilepas.'
        ];
    }

    /**
     * Autentikasi Device
     */
    public function authenticate(string $token): array
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

        if (empty($device['android_id'])) {
            return [
                'success' => false,
                'code'    => 'DEVICE_NOT_PAIRED',
                'message' => 'Perangkat belum dipairing.'
            ];
        }

        return [
            'success' => true,
            'code'    => 'AUTH_SUCCESS',
            'message' => 'Autentikasi berhasil.',
            'data' => [

                    'device_id'      => $device['id'],

                    'id_sekolah'     => $device['id_sekolah'],

                    'nama_perangkat' => $device['nama_perangkat'],

                    'status'         => $device['status'],

                    'last_sync'      => $device['last_sync']

                ]
        ];
    }
}