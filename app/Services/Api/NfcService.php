<?php

namespace App\Services\Api;

use App\Repositories\Api\KartuNfcRepository;

class NfcService
{
    protected DeviceService $deviceService;
    protected KartuNfcRepository $kartuRepository;

    public function __construct()
    {
        $this->deviceService   = new DeviceService();
        $this->kartuRepository = new KartuNfcRepository();
    }

    /**
     * Membaca kartu NFC
     */
    public function read(string $token, string $uid): array
    {
        /*
         * Validasi UID
         */
        if (empty($uid)) {
            return [
                'success' => false,
                'code'    => 'UID_REQUIRED',
                'message' => 'UID wajib diisi.'
            ];
        }

        /*
         * Validasi Device
         */
        $device = $this->deviceService->validateDevice($token);

        if (!$device['success']) {
            return $device;
        }

        /*
         * Cari kartu berdasarkan UID dan Sekolah
         */
        $card = $this->kartuRepository->findByUid(
            $uid,
            $device['data']['id_sekolah']
        );

        if (!$card) {
            return [
                'success' => false,
                'code'    => 'UID_NOT_FOUND',
                'message' => 'UID tidak terdaftar.'
            ];
        }

        /*
         * Kartu Guru
         */
        if (!empty($card['id_guru'])) {

            if ($card['status_guru'] !== 'aktif') {
                return [
                    'success' => false,
                    'code'    => 'GURU_INACTIVE',
                    'message' => 'Guru tidak aktif.'
                ];
            }

            return [

                    'success' => true,

                    'code' => 'CARD_FOUND',

                    'message' => 'Kartu guru berhasil dikenali.',

                    'data' => [

                        'type' => 'guru',

                        'id' => $card['id_guru'],

                        'nama' => $card['nama_guru'],

                        'uid' => $card['uid'],

                        'id_kartu_nfc' => $card['id_kartu_nfc'],

                        'id_sekolah' => $card['sekolah_guru'],

                        'nomor_kartu' => $card['nomor_kartu']

                    ]

                ];
        }

        /*
         * Kartu Siswa
         */
        if (!empty($card['id_siswa'])) {

            if ($card['status_siswa'] !== 'aktif') {
                return [
                    'success' => false,
                    'code'    => 'SISWA_INACTIVE',
                    'message' => 'Siswa tidak aktif.'
                ];
            }

            return [

                        'success' => true,

                        'code' => 'CARD_FOUND',

                        'message' => 'Kartu siswa berhasil dikenali.',

                        'data' => [

                            'type' => 'siswa',

                            'id' => $card['id_siswa'],

                            'nama' => $card['nama_siswa'],

                            'uid' => $card['uid'],

                            'id_kartu_nfc' => $card['id_kartu_nfc'],

                            'id_sekolah' => $card['sekolah_siswa'],

                            'nomor_kartu' => $card['nomor_kartu']

                        ]

                    ];
        }

        return [
            'success' => false,
            'code'    => 'CARD_NO_OWNER',
            'message' => 'Kartu belum memiliki pemilik.'
        ];
    }
}