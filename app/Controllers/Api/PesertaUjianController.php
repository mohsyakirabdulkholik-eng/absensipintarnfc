<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\PesertaUjianService;
use App\Services\Api\DeviceService;
use App\Repositories\UjianRepository;
use CodeIgniter\HTTP\ResponseInterface;
use RuntimeException;
use Throwable;

class PesertaUjianController extends BaseController
{
    protected PesertaUjianService $service;
    protected DeviceService $deviceService;
    protected UjianRepository $ujianRepository;

    public function __construct()
    {
        $this->service = new PesertaUjianService();
        $this->deviceService = new DeviceService();
        $this->ujianRepository = new UjianRepository();
    }

    /**
     * Validasi perangkat berdasarkan X-Device-Token
     */
    
    protected function getDevice(): array
    {
        $token = $this->request->getHeaderLine('X-Device-Token');

        if (empty($token)) {
            throw new RuntimeException('Token perangkat tidak ditemukan.');
        }

        $result = $this->deviceService->validateDevice($token);

        // Jika hasil validasi berbentuk response dengan key data
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
     * GET daftar peserta ujian
     */
    public function index(?int $idUjian = null)
    {
        try {
            $device = $this->getDevice();

            // Fallback parameter dari URI
            $idUjian = $idUjian ?? (int) $this->request->getUri()->getSegment(3);

            if ($idUjian <= 0) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => false,
                    'message' => 'ID ujian tidak valid.'
                ]);
            }

            $ujian = $this->ujianRepository->find($idUjian);

            if (!$ujian) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status' => false,
                    'message' => 'Ujian tidak ditemukan.'
                ]);
            }

            // Pastikan ujian milik sekolah perangkat
            if ((int) $ujian['id_sekolah'] !== (int) $device['id_sekolah']) {
                return $this->response->setStatusCode(403)->setJSON([
                    'status' => false,
                    'message' => 'Ujian bukan milik sekolah perangkat.'
                ]);
            }

            $data = $this->service->getByUjian($idUjian);

            return $this->response->setJSON([
                'status' => true,
                'message' => 'Daftar peserta ujian berhasil diambil.',
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
     * POST daftarkan seluruh siswa dari kelas ujian
     */
    public function register(?int $idUjian = null)
    {
        try {
            $device = $this->getDevice();

            // Fallback parameter dari URI
            $idUjian = $idUjian ?? (int) $this->request->getUri()->getSegment(3);

            if ($idUjian <= 0) {
                return $this->response->setStatusCode(400)->setJSON([
                    'status' => false,
                    'message' => 'ID ujian tidak valid.'
                ]);
            }

            $ujian = $this->ujianRepository->find($idUjian);

            if (!$ujian) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status' => false,
                    'message' => 'Ujian tidak ditemukan.'
                ]);
            }

            // Pastikan ujian milik sekolah perangkat
            if ((int) $ujian['id_sekolah'] !== (int) $device['id_sekolah']) {
                return $this->response->setStatusCode(403)->setJSON([
                    'status' => false,
                    'message' => 'Ujian bukan milik sekolah perangkat.'
                ]);
            }

            $data = $this->service->registerByKelas($idUjian);

            return $this->response->setStatusCode(201)->setJSON([
                'status' => true,
                'message' => 'Peserta ujian berhasil didaftarkan.',
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
    
    /**
     * POST mulai ujian peserta
     */
    
    public function start(?int $idPesertaUjian = null)
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

            $peserta = $this->service->startExam(
                $idPesertaUjian
            );

            return $this->response->setStatusCode(200)->setJSON([
                'status' => true,
                'message' => 'Peserta berhasil memulai ujian.',
                'data' => $peserta
            ]);

        } catch (\RuntimeException $e) {
            return $this->response->setStatusCode(422)->setJSON([
                'status' => false,
                'message' => $e->getMessage()
            ]);

        } catch (\Throwable $e) {
            log_message('error', '[PesertaUjianController::start] ' . $e->getMessage());

            return $this->response->setStatusCode(500)->setJSON([
                'status' => false,
                'message' => 'Terjadi kesalahan saat memulai ujian.'
            ]);
        }
    }

}