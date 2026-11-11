<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\JawabanUjianService;
use App\Services\Api\DeviceService;
use App\Repositories\UjianRepository;
use App\Repositories\PesertaUjianRepository;
use RuntimeException;
use Throwable;

class JawabanUjianController extends BaseController
{
    protected JawabanUjianService $service;
    protected DeviceService $deviceService;
    protected UjianRepository $ujianRepository;
    protected PesertaUjianRepository $pesertaRepository;

    public function __construct()
    {
        $this->service = new JawabanUjianService();
        $this->deviceService = new DeviceService();
        $this->ujianRepository = new UjianRepository();
        $this->pesertaRepository = new PesertaUjianRepository();
    }

    /**
     * Validasi perangkat
     */
    protected function getDevice(): array
    {
        $token = $this->request->getHeaderLine('X-Device-Token');

        if (empty($token)) {
            throw new RuntimeException('Token perangkat tidak ditemukan.');
        }

        $result = $this->deviceService->validateDevice($token);

        if (isset($result['data']) && is_array($result['data'])) {
            $result = $result['data'];
        }

        if (empty($result)) {
            throw new RuntimeException('Perangkat tidak valid.');
        }

        if (!isset($result['id_sekolah'])) {
            throw new RuntimeException(
                'Data perangkat tidak memiliki id_sekolah.'
            );
        }

        return $result;
    }

    /**
     * GET jawaban peserta
     */
    public function index(?int $idPesertaUjian = null)
    {
        try {
            $device = $this->getDevice();

            $idPesertaUjian = $idPesertaUjian
                ?? (int) $this->request->getUri()->getSegment(3);

            if ($idPesertaUjian <= 0) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => false,
                    'message' => 'ID peserta ujian tidak valid.'
                ]);
            }

            $peserta = $this->pesertaRepository->findById($idPesertaUjian);

            if (!$peserta) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status' => false,
                    'message' => 'Peserta ujian tidak ditemukan.'
                ]);
            }

            $ujian = $this->ujianRepository->find(
                (int) $peserta['id_ujian']
            );

            if (!$ujian || (int) $ujian['id_sekolah'] !== (int) $device['id_sekolah']) {
                return $this->response->setStatusCode(403)->setJSON([
                    'status' => false,
                    'message' => 'Peserta bukan milik sekolah perangkat.'
                ]);
            }

            $data = $this->service->getByPeserta($idPesertaUjian);

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Jawaban peserta berhasil diambil.',
                'data' => $data
            ]);

        } catch (RuntimeException $e) {
            return $this->response->setStatusCode(401)->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ]);

        } catch (Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * POST simpan jawaban peserta
     */
    public function save(?int $idPesertaUjian = null)
    {
        try {
            $device = $this->getDevice();

            $idPesertaUjian = $idPesertaUjian
                ?? (int) $this->request->getUri()->getSegment(3);

            if ($idPesertaUjian <= 0) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => false,
                    'message' => 'ID peserta ujian tidak valid.'
                ]);
            }

            $peserta = $this->pesertaRepository->findById($idPesertaUjian);

            if (!$peserta) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status' => false,
                    'message' => 'Peserta ujian tidak ditemukan.'
                ]);
            }

            $ujian = $this->ujianRepository->find(
                (int) $peserta['id_ujian']
            );

            if (!$ujian || (int) $ujian['id_sekolah'] !== (int) $device['id_sekolah']) {
                return $this->response->setStatusCode(403)->setJSON([
                    'status' => false,
                    'message' => 'Peserta bukan milik sekolah perangkat.'
                ]);
            }

            $input = $this->request->getJSON(true);

            if (!is_array($input)) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => false,
                    'message' => 'Format JSON tidak valid.'
                ]);
            }

            // ID peserta berasal dari URL, bukan dari body
            $input['id_peserta_ujian'] = $idPesertaUjian;

            $data = $this->service->saveJawaban($input);

            return $this->response->setJSON([
                'status' => true,
                'message' => $data['message'],
                'data' => $data
            ]);

        } catch (RuntimeException $e) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ]);

        } catch (Throwable $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}