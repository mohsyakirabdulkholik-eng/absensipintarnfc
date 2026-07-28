<?php

namespace App\Services\Api;

use App\Services\KehadiranService as BaseKehadiranService;

class KehadiranService
{
    protected DeviceService $deviceService;
    protected BaseKehadiranService $kehadiranService;

    public function __construct()
    {
        $this->deviceService    = new DeviceService();
        $this->kehadiranService = new BaseKehadiranService();
    }

    /**
     * Mengambil laporan kehadiran.
     */
    public function getData(
        string $token,
        string $tanggal,
        string $jenis,
        ?int $idKelas = null
    ): array
    {
        /*
         * 1. Validasi device.
         */
        $device = $this->deviceService->validateDevice($token);

        if (!$device['success']) {
            return $device;
        }

        $idSekolah = (int) $device['data']['id_sekolah'];

        /*
         * 2. Validasi tanggal.
         */
        $date = \DateTime::createFromFormat('Y-m-d', $tanggal);

        if (
            !$date ||
            $date->format('Y-m-d') !== $tanggal
        ) {
            return [
                'success' => false,
                'code'    => 'INVALID_DATE',
                'message' => 'Format tanggal tidak valid.'
            ];
        }

        /*
         * 3. Validasi jenis laporan.
         */
        $jenis = strtolower(trim($jenis));

        if (!in_array($jenis, ['siswa', 'guru'], true)) {
            return [
                'success' => false,
                'code'    => 'INVALID_TYPE',
                'message' => 'Jenis kehadiran tidak valid.'
            ];
        }

        /*
         * 4. Ambil data.
         */
        if ($jenis === 'guru') {

            $data = $this->kehadiranService->getGuru(
                $idSekolah,
                $tanggal
            );

            $idKelas = null;

        } else {

            $data = $this->kehadiranService->getSiswa(
                $idSekolah,
                $tanggal,
                $idKelas
            );
        }

        /*
         * 5. Hitung statistik.
         */
        $statistik = $this->kehadiranService
            ->getStatistik($data);

        /*
         * 6. Response.
         */
        return [
            'success' => true,
            'code'    => 'SUCCESS',
            'message' => 'Data kehadiran berhasil diambil.',

            'data' => $data,

            'statistik' => $statistik,

            'filter' => [
                'tanggal'  => $tanggal,
                'jenis'    => $jenis,
                'id_kelas' => $idKelas
            ]
        ];
    }
}