<?php

namespace App\Services\Api;

use App\Repositories\KelasRepository;

class KelasService
{
    protected DeviceService $deviceService;
    protected KelasRepository $kelasRepository;

    public function __construct()
    {
        $this->deviceService   = new DeviceService();
        $this->kelasRepository = new KelasRepository();
    }

    /**
     * Mengambil daftar kelas berdasarkan sekolah perangkat.
     */
    public function getAll(string $token): array
    {
        /*
         * 1. Validasi perangkat.
         */
        $device = $this->deviceService->validateDevice($token);

        if (!$device['success']) {
            return $device;
        }

        /*
         * 2. Ambil ID sekolah dari perangkat.
         */
        $idSekolah = (int) $device['data']['id_sekolah'];

        /*
         * 3. Ambil kelas sekolah.
         */
        $data = $this->kelasRepository->getBySekolah(
            $idSekolah
        );

        /*
         * 4. Response.
         */
        return [
            'success' => true,
            'code'    => 'SUCCESS',
            'message' => 'Data kelas berhasil diambil.',
            'data'    => $data
        ];
    }
}