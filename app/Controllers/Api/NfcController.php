<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Services\Api\NfcService;

class NfcController extends BaseController
{
    protected NfcService $service;

    public function __construct()
    {
        $this->service = new NfcService();
    }

    /**
     * Membaca kartu NFC
     */
    public function read()
    {
        $input = $this->request->getJSON(true);

        if (!$input) {
            $input = $this->request->getPost();
        }

        $token = trim($input['token'] ?? '');
        $uid   = strtoupper(trim($input['uid'] ?? ''));

        $result = $this->service->read($token, $uid);

        return $this->response->setJSON($result);
    }

    public function guru()
    {
        $token = $this->request->getHeaderLine('X-Device-Token');

        $result = $this->service->getGuruBelumPunyaKartu($token);

        return $this->response->setJSON($result);
    }
    public function siswa()
    {
        $token = $this->request->getHeaderLine('X-Device-Token');

        $result = $this->service->getSiswaBelumPunyaKartu($token);

        return $this->response->setJSON($result);
    }

    /**
     * Registrasi kartu NFC Guru
     */
    public function registerGuru()
    {
        // Ambil token perangkat dari header
        $token = $this->request->getHeaderLine('X-Device-Token');

        // Ambil JSON body
        $request = $this->request->getJSON(true);

        // Validasi request
        if (empty($request['uid'])) {
            return $this->response->setJSON([
                'success' => false,
                'code'    => 'UID_REQUIRED',
                'message' => 'UID kartu NFC wajib diisi.'
            ]);
        }

        if (empty($request['id_guru'])) {
            return $this->response->setJSON([
                'success' => false,
                'code'    => 'GURU_REQUIRED',
                'message' => 'Guru wajib dipilih.'
            ]);
        }

        // Proses registrasi
        $result = $this->service->registerGuru(
            $token,
            $request['uid'],
            (int) $request['id_guru']
        );

        return $this->response->setJSON($result);
    }

    /**
     * Registrasi kartu NFC Siswa
     */
    public function registerSiswa()
    {
        // Ambil token perangkat
        $token = $this->request->getHeaderLine('X-Device-Token');

        // Ambil JSON body
        $request = $this->request->getJSON(true);

        // Validasi UID
        if (empty($request['uid'])) {
            return $this->response->setJSON([
                'success' => false,
                'code'    => 'UID_REQUIRED',
                'message' => 'UID kartu NFC wajib diisi.'
            ]);
        }

        // Validasi Siswa
        if (empty($request['id_siswa'])) {
            return $this->response->setJSON([
                'success' => false,
                'code'    => 'SISWA_REQUIRED',
                'message' => 'Siswa wajib dipilih.'
            ]);
        }

        // Proses registrasi
        $result = $this->service->registerSiswa(
            $token,
            $request['uid'],
            (int) $request['id_siswa']
        );

        return $this->response->setJSON($result);
    }
}