<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\KartuNfcService;

class KartuNfcController extends BaseController
{
    protected KartuNfcService $service;

    public function __construct()
    {
        $this->service = new KartuNfcService();
    }

    /**
     * Halaman utama Kartu NFC
     */
    public function index()
    {
        return view('kartu_nfc/index');
    }

    /**
     * Data Kartu NFC untuk DataTable
     */
    public function data()
    {
        try {

            $idSekolah = (int) session()->get('id_sekolah');

            $data = $this->service->getAll($idSekolah);

            return $this->response->setJSON($data);

        } catch (\Throwable $e) {

            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Detail Kartu NFC
     */
    public function detail($id)
    {
        try {

            $idSekolah = (int) session()->get('id_sekolah');

            $kartu = $this->service->find(
                (int) $id,
                $idSekolah
            );

            return $this->response->setJSON([
                'success' => true,
                'data'    => $kartu
            ]);

        } catch (\Throwable $e) {

            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Ubah Status Kartu NFC
     */
    public function updateStatus($id)
    {
        try {

            $idSekolah = (int) session()->get('id_sekolah');

            $status = $this->request->getPost('status');

            if (empty($status)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Status kartu wajib dipilih.'
                ]);
            }

            $this->service->updateStatus(
                (int) $id,
                $idSekolah,
                $status
            );

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Status kartu NFC berhasil diperbarui.'
            ]);

        } catch (\Throwable $e) {

            return $this->response->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}