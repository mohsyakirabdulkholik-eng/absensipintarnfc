<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseAdminController;
use App\Services\HariLiburService;

class HariLiburController extends BaseAdminController
{
    protected HariLiburService $hariLiburService;

    public function __construct()
    {
        $this->hariLiburService = new HariLiburService();
    }

    /**
     * Halaman Hari Libur
     */
    public function index()
    {
        $data = [
            'title' => 'Hari Libur',
            'user'  => $this->currentUser()
        ];

        return view('harilibur/index', $data);
    }

    /**
     * Data Hari Libur
     */
    public function getData()
    {
        $idSekolah = session('id_sekolah');

        return $this->response->setJSON(
            $this->hariLiburService->getAll($idSekolah)
        );
    }

    /**
     * Detail Hari Libur
     */
    public function show($id)
    {
        $hariLibur = $this->hariLiburService->find($id);

        if (!$hariLibur) {
            return $this->response->setJSON(
                responseError('Data tidak ditemukan.')
            );
        }

        return $this->response->setJSON(
            responseSuccess('Data ditemukan.', $hariLibur)
        );
    }

    /**
     * Simpan
     */
    public function store()
    {
        try {

            $this->hariLiburService->save(
                $this->getFormData()
            );

            return $this->response->setJSON(
                responseSuccess('Hari libur berhasil ditambahkan.')
            );

        } catch (\Throwable $e) {

            return $this->response->setJSON(
                responseError($e->getMessage())
            );

        }
    }

    /**
     * Update
     */
    public function update($id)
    {
        try {

            $this->hariLiburService->update(
                $id,
                $this->getFormData()
            );

            return $this->response->setJSON(
                responseSuccess('Hari libur berhasil diubah.')
            );

        } catch (\Throwable $e) {

            return $this->response->setJSON(
                responseError($e->getMessage())
            );

        }
    }

    /**
     * Delete
     */
    public function delete($id)
    {
        try {

            $this->hariLiburService->delete($id);

            return $this->response->setJSON(
                responseSuccess('Hari libur berhasil dihapus.')
            );

        } catch (\Throwable $e) {

            return $this->response->setJSON(
                responseError($e->getMessage())
            );

        }
    }

    /**
     * Ambil data form
     */
    private function getFormData(): array
    {
        return [
            'id_sekolah' => session('id_sekolah'),
            'tanggal'    => $this->request->getPost('tanggal'),
            'nama_libur' => trim($this->request->getPost('nama_libur')),
            'keterangan' => trim($this->request->getPost('keterangan')),
        ];
    }
}