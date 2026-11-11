<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\SoalService;
use App\Services\Api\DeviceService;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;

class SoalController extends BaseController
{
    protected SoalService $service;
    protected DeviceService $deviceService;

    public function __construct()
    {
        $this->service = new SoalService();
        $this->deviceService = new DeviceService();
    }

    /**
     * Validasi device berdasarkan X-Device-Token
     */
    protected function getDevice(): array
    {
        $token = $this->request->getHeaderLine(
            'X-Device-Token'
        );

        if ($token === '') {
            throw new \RuntimeException(
                'Token perangkat wajib diisi.'
            );
        }

        $result = $this->deviceService->validateDevice(
            $token
        );

        if (!$result['success']) {
            throw new \RuntimeException(
                $result['message']
                ?? 'Perangkat tidak valid.'
            );
        }

        return $result['data'];
    }

    /**
     * GET /api/ujian/{id}/soal
     */
    public function index(?int $idUjian = null): ResponseInterface
    {
        try {
            if ($idUjian === null) {
                $idUjian = (int) $this->request->getUri()->getSegment(3);
            }

            if ($idUjian <= 0) {
                return $this->response
                    ->setStatusCode(422)
                    ->setJSON([
                        'status' => false,
                        'message' => 'ID ujian tidak valid.'
                    ]);
            }

            $device = $this->getDevice();

            $idSekolah = (int) $device['id_sekolah'];

            $data = $this->service->getByUjian(
                $idUjian,
                $idSekolah
            );

            return $this->response
                ->setStatusCode(200)
                ->setJSON([
                    'status' => true,
                    'message' => 'Data soal berhasil diambil.',
                    'data' => $data
                ]);

        } catch (\RuntimeException $e) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'status' => false,
                    'message' => $e->getMessage()
                ]);

        } catch (Throwable $e) {

            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'status' => false,
                    'message' => $e->getMessage()
                ]);
        }
    }

    /**
     * GET /api/ujian/{id}/soal/{idSoal}
     */
    public function show(
        int $idUjian,
        int $idSoal
    ): ResponseInterface {
        try {
            $device = $this->getDevice();

            $idSekolah = (int) $device['id_sekolah'];

            $data = $this->service->getById(
                $idSoal,
                $idUjian,
                $idSekolah
            );

            return $this->response
                ->setStatusCode(200)
                ->setJSON([
                    'status' => true,
                    'message' => 'Detail soal berhasil diambil.',
                    'data' => $data
                ]);

        } catch (\RuntimeException $e) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON([
                    'status' => false,
                    'message' => $e->getMessage()
                ]);

        } catch (Throwable $e) {

            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'status' => false,
                    'message' => $e->getMessage()
                ]);
        }
    }

    /**
     * POST /api/ujian/{id}/soal
     */
    public function create(
        ?int $idUjian = null
    ): ResponseInterface {
        try {
    
            if ($idUjian === null) {
                $idUjian = (int) $this->request
                    ->getUri()
                    ->getSegment(3);
            }
    
            if ($idUjian <= 0) {
                return $this->response
                    ->setStatusCode(422)
                    ->setJSON([
                        'status' => false,
                        'message' => 'ID ujian tidak valid.'
                    ]);
            }
    
            $device = $this->getDevice();
    
            $idSekolah = (int) $device['id_sekolah'];
    
            $data = $this->request->getJSON(true);
    
            if (!is_array($data)) {
                return $this->response
                    ->setStatusCode(422)
                    ->setJSON([
                        'status' => false,
                        'message' => 'Data request tidak valid.'
                    ]);
            }
    
            /*
             * ID ini ditentukan oleh URL/server,
             * bukan oleh client.
             */
            unset(
                $data['id'],
                $data['id_ujian'],
                $data['id_sekolah']
            );
    
            $idSoal = $this->service->create(
                $idUjian,
                $data,
                $idSekolah
            );
    
            $soal = $this->service->getById(
                $idSoal,
                $idUjian,
                $idSekolah
            );
    
            return $this->response
                ->setStatusCode(201)
                ->setJSON([
                    'status' => true,
                    'message' => 'Soal berhasil dibuat.',
                    'data' => $soal
                ]);
    
        } catch (\RuntimeException $e) {
    
            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'status' => false,
                    'message' => $e->getMessage()
                ]);
    
        } catch (Throwable $e) {
    
            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'status' => false,
                    'message' => $e->getMessage()
                ]);
        }
    }

    /**
     * PUT /api/ujian/{id}/soal/{idSoal}
     */
    public function update(
        int $idUjian,
        int $idSoal
    ): ResponseInterface {
        try {
            $device = $this->getDevice();

            $idSekolah = (int) $device['id_sekolah'];

            $data = $this->request->getJSON(true);

            if (!is_array($data)) {
                return $this->response
                    ->setStatusCode(422)
                    ->setJSON([
                        'status' => false,
                        'message' => 'Data request tidak valid.'
                    ]);
            }

            unset(
                $data['id'],
                $data['id_ujian'],
                $data['id_sekolah']
            );

            $this->service->update(
                $idSoal,
                $idUjian,
                $data,
                $idSekolah
            );

            $soal = $this->service->getById(
                $idSoal,
                $idUjian,
                $idSekolah
            );

            return $this->response
                ->setStatusCode(200)
                ->setJSON([
                    'status' => true,
                    'message' => 'Soal berhasil diperbarui.',
                    'data' => $soal
                ]);

        } catch (\RuntimeException $e) {

            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'status' => false,
                    'message' => $e->getMessage()
                ]);

        } catch (Throwable $e) {

            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'status' => false,
                    'message' => $e->getMessage()
                ]);
        }
    }

    /**
     * DELETE /api/ujian/{id}/soal/{idSoal}
     */
    public function delete(
        int $idUjian,
        int $idSoal
    ): ResponseInterface {
        try {
            $device = $this->getDevice();

            $idSekolah = (int) $device['id_sekolah'];

            $this->service->delete(
                $idSoal,
                $idUjian,
                $idSekolah
            );

            return $this->response
                ->setStatusCode(200)
                ->setJSON([
                    'status' => true,
                    'message' => 'Soal berhasil dihapus.'
                ]);

        } catch (\RuntimeException $e) {

            return $this->response
                ->setStatusCode(422)
                ->setJSON([
                    'status' => false,
                    'message' => $e->getMessage()
                ]);

        } catch (Throwable $e) {

            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'status' => false,
                    'message' => $e->getMessage()
                ]);
        }
    }
}