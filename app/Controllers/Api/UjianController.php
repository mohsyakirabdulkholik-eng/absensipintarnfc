<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\UjianService;
use App\Services\Api\DeviceService;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;

class UjianController extends BaseController
{
    protected UjianService $service;
    protected DeviceService $deviceService;

    public function __construct()
    {
        $this->service = new UjianService();
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
     * GET /api/ujian
     */
    public function index(): ResponseInterface
    {
        try {
            $device = $this->getDevice();

            $idSekolah = (int) $device['id_sekolah'];

            $data = $this->service->getAll(
                $idSekolah
            );

            return $this->response
                ->setStatusCode(200)
                ->setJSON([
                    'status' => true,
                    'message' => 'Data ujian berhasil diambil.',
                    'data' => $data
                ]);

        } catch (\RuntimeException $e) {

            return $this->response
                ->setStatusCode(401)
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
     * GET /api/ujian/{id}
     */
    public function show(int $id): ResponseInterface
    {
        try {
            $device = $this->getDevice();

            $idSekolah = (int) $device['id_sekolah'];

            $data = $this->service->getById(
                $id,
                $idSekolah
            );

            return $this->response
                ->setStatusCode(200)
                ->setJSON([
                    'status' => true,
                    'message' => 'Detail ujian berhasil diambil.',
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
     * POST /api/ujian
     */
    public function create(): ResponseInterface
    {
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

            /*
             * id_sekolah tidak boleh ditentukan
             * oleh client.
             */
            unset($data['id_sekolah']);

            $id = $this->service->create(
                $data,
                $idSekolah
            );

            $ujian = $this->service->getById(
                $id,
                $idSekolah
            );

            return $this->response
                ->setStatusCode(201)
                ->setJSON([
                    'status' => true,
                    'message' => 'Ujian berhasil dibuat.',
                    'data' => $ujian
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
     * PUT /api/ujian/{id}
     */
    public function update(int $id): ResponseInterface
    {
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

            /*
             * Client tidak boleh mengubah sekolah.
             */
            unset($data['id_sekolah']);

            $this->service->update(
                $id,
                $data,
                $idSekolah
            );

            $ujian = $this->service->getById(
                $id,
                $idSekolah
            );

            return $this->response
                ->setStatusCode(200)
                ->setJSON([
                    'status' => true,
                    'message' => 'Ujian berhasil diperbarui.',
                    'data' => $ujian
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
     * DELETE /api/ujian/{id}
     */
    public function delete(int $id): ResponseInterface
    {
        try {
            $device = $this->getDevice();

            $idSekolah = (int) $device['id_sekolah'];

            $this->service->delete(
                $id,
                $idSekolah
            );

            return $this->response
                ->setStatusCode(200)
                ->setJSON([
                    'status' => true,
                    'message' => 'Ujian berhasil dihapus.'
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
     * POST /api/ujian/{id}/activate
     */
    public function activate(int $id = null): ResponseInterface
    {
        // Ambil ID ujian dari URL jika parameter tidak diteruskan
        $id = $id ?? (int) $this->request->getUri()->getSegment(3);

        if ($id <= 0) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'status' => false,
                    'message' => 'ID ujian tidak valid.'
                ]);
        }
        try {
            $device = $this->getDevice();

            $idSekolah = (int) $device['id_sekolah'];

            $this->service->activate(
                $id,
                $idSekolah
            );

            $ujian = $this->service->getById(
                $id,
                $idSekolah
            );

            return $this->response
                ->setStatusCode(200)
                ->setJSON([
                    'status' => true,
                    'message' => 'Ujian berhasil diaktifkan.',
                    'data' => $ujian
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
     * POST /api/ujian/{id}/finish
     */
    public function finish(int $id): ResponseInterface
    {
        try {
            $device = $this->getDevice();

            $idSekolah = (int) $device['id_sekolah'];

            $this->service->finish(
                $id,
                $idSekolah
            );

            $ujian = $this->service->getById(
                $id,
                $idSekolah
            );

            return $this->response
                ->setStatusCode(200)
                ->setJSON([
                    'status' => true,
                    'message' => 'Ujian berhasil diselesaikan.',
                    'data' => $ujian
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