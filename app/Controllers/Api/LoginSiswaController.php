<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\Api\LoginSiswaService;
use CodeIgniter\HTTP\ResponseInterface;
use Throwable;

class LoginSiswaController extends BaseController
{
    protected LoginSiswaService $service;

    public function __construct()
    {
        $this->service = new LoginSiswaService();
    }

    /**
     * POST /api/siswa/login
     */
    public function login(): ResponseInterface
    {
        try {
            // Ambil data JSON dari Android
            $input = $this->request->getJSON(true);

            if (!is_array($input)) {
                return $this->response
                    ->setStatusCode(400)
                    ->setJSON([
                        'status' => false,
                        'code' => 'INVALID_REQUEST',
                        'message' => 'Format request tidak valid.'
                    ]);
            }

            // Ambil NIS dan token
            $nis = trim((string) ($input['nis'] ?? ''));

            $token = trim((string) ($input['token'] ?? ''));

            // Validasi input
            if ($nis === '' || $token === '') {
                return $this->response
                    ->setStatusCode(400)
                    ->setJSON([
                        'status' => false,
                        'code' => 'VALIDATION_ERROR',
                        'message' => 'NIS dan token ujian wajib diisi.'
                    ]);
            }

            // Proses login melalui Service
            $result = $this->service->login(
                $nis,
                $token
            );

            // Jika login gagal
            if (!$result['success']) {
                return $this->response
                    ->setStatusCode(401)
                    ->setJSON([
                        'status' => false,
                        'code' => $result['code'],
                        'message' => $result['message']
                    ]);
            }

            // Login berhasil
            return $this->response
                ->setStatusCode(200)
                ->setJSON([
                    'status' => true,
                    'code' => $result['code'],
                    'message' => $result['message'],
                    'data' => $result['data']
                ]);

        } catch (Throwable $e) {

            log_message(
                'error',
                '[LoginSiswaController] ' . $e->getMessage()
            );

            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'status' => false,
                    'code' => 'SERVER_ERROR',
                    'message' => 'Terjadi kesalahan pada server.'
                ]);
        }
    }
}
