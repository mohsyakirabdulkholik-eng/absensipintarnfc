<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\HasilUjianService;
use App\Services\Api\DeviceService;
use CodeIgniter\HTTP\ResponseInterface;
use RuntimeException;
use Throwable;

class HasilUjianController extends BaseController
{
    protected HasilUjianService $service;
    protected DeviceService $deviceService;

    public function __construct()
    {
        $this->service = new HasilUjianService();
        $this->deviceService = new DeviceService();
    }

    /**
     * Validasi perangkat berdasarkan X-Device-Token.
     */
    
    protected function getDevice(): array
    {
        $token = $this->request->getHeaderLine('X-Device-Token');

        if (empty($token)) {
            throw new RuntimeException(
                'Token perangkat tidak ditemukan.'
            );
        }

        $deviceService = new \App\Services\Api\DeviceService();

        $result = $deviceService->validateDevice($token);

        // Jika DeviceService mengembalikan status false,
        // perangkat tidak valid.
        if (
            isset($result['status'])
            && $result['status'] === false
        ) {
            throw new RuntimeException(
                $result['message'] ?? 'Perangkat tidak valid.'
            );
        }

        // Pastikan data perangkat tersedia.
        if (empty($result['data'])) {
            throw new RuntimeException(
                'Data perangkat tidak ditemukan.'
            );
        }

        return $result['data'];
    }


    /**
     * Menyelesaikan ujian dan menghitung nilai peserta.
     */
    public function selesai(
        ?int $idPesertaUjian = null
    ): ResponseInterface {
        $idPesertaUjian = $idPesertaUjian
            ?? (int) $this->request->getUri()->getSegment(3);

        if ($idPesertaUjian <= 0) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => false,
                    'message' => 'ID peserta ujian tidak valid.'
                ]);
        }

        try {
            // Validasi perangkat
            $device = $this->getDevice();

            $idSekolah = (int) ($device['id_sekolah'] ?? 0);

            if ($idSekolah <= 0) {
                throw new RuntimeException(
                    'ID sekolah perangkat tidak valid.'
                );
            }

            // Proses penyelesaian dan penilaian ujian
            $hasil = $this->service->selesaikanUjian(
                $idPesertaUjian,
                $idSekolah
            );

            return $this->response
                ->setStatusCode(200)
                ->setJSON([
                    'status' => true,
                    'message' => 'Ujian berhasil diselesaikan.',
                    'data' => $hasil
                ]);

        } catch (RuntimeException $e) {
            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'status' => false,
                    'message' => $e->getMessage()
                ]);

        } catch (Throwable $e) {
            log_message(
                'error',
                '[HasilUjianController::selesai] '
                . $e->getMessage()
            );

            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'status' => false,
                    'message' => 'Terjadi kesalahan saat menyelesaikan ujian.'
                ]);
        }
    }
}
