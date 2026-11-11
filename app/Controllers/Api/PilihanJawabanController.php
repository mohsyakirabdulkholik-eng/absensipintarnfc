<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\PilihanJawabanService;
use App\Services\Api\DeviceService;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;

class PilihanJawabanController extends BaseController
{
    protected PilihanJawabanService $service;
    protected DeviceService $deviceService;

    public function __construct()
    {
        $this->service = new PilihanJawabanService();
        $this->deviceService = new DeviceService();
    }

    /**
     * Validasi perangkat
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
                $result['message'] ?? 'Perangkat tidak valid.'
            );
        }

        return $result['data'];
    }

    /**
     * GET pilihan berdasarkan soal
     */
    public function index(?int $idSoal = null): ResponseInterface
    {
        try {
            if ($idSoal === null) {
                $idSoal = (int) $this->request
                    ->getUri()
                    ->getSegment(3);
            }

            if ($idSoal <= 0) {
                throw new \RuntimeException(
                    'ID soal tidak valid.'
                );
            }

            $this->getDevice();

            $data = $this->service->getBySoal($idSoal);

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Pilihan jawaban berhasil diambil.',
                'data' => $data
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
     * POST tambah pilihan jawaban
     */
    public function create(?int $idSoal = null): ResponseInterface
    {
        try {
            if ($idSoal === null) {
                $idSoal = (int) $this->request
                    ->getUri()
                    ->getSegment(3);
            }

            if ($idSoal <= 0) {
                throw new \RuntimeException(
                    'ID soal tidak valid.'
                );
            }

            $this->getDevice();

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
                $data['id_soal']
            );

            $idPilihan = $this->service->create(
                $idSoal,
                $data
            );

            return $this->response
                ->setStatusCode(201)
                ->setJSON([
                    'status' => true,
                    'message' => 'Pilihan jawaban berhasil dibuat.',
                    'data' => [
                        'id' => $idPilihan,
                        'id_soal' => $idSoal
                    ]
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
     * PUT update pilihan jawaban
     */
    public function update(
        ?int $idSoal = null,
        ?int $idPilihan = null
    ): ResponseInterface {
        try {
            if ($idSoal === null) {
                $idSoal = (int) $this->request
                    ->getUri()
                    ->getSegment(3);
            }

            if ($idPilihan === null) {
                $idPilihan = (int) $this->request
                    ->getUri()
                    ->getSegment(5);
            }

            $this->getDevice();

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
                $data['id_soal']
            );

            $this->service->update(
                $idSoal,
                $idPilihan,
                $data
            );

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Pilihan jawaban berhasil diperbarui.'
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
     * DELETE pilihan jawaban
     */
    public function delete(
        ?int $idSoal = null,
        ?int $idPilihan = null
    ): ResponseInterface {
        try {
            if ($idSoal === null) {
                $idSoal = (int) $this->request
                    ->getUri()
                    ->getSegment(3);
            }

            if ($idPilihan === null) {
                $idPilihan = (int) $this->request
                    ->getUri()
                    ->getSegment(4);
            }

            $this->getDevice();

            $this->service->delete(
                $idSoal,
                $idPilihan
            );

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Pilihan jawaban berhasil dihapus.'
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
