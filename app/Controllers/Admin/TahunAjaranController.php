<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseAdminController;
use App\Services\TahunAjaranService;

class TahunAjaranController extends BaseAdminController
{
    protected TahunAjaranService $tahunAjaranService;

    public function __construct()
    {
        $this->tahunAjaranService = new TahunAjaranService();
    }

    public function index()
    {
        $data = [
            'title' => 'Tahun Ajaran',
            'user'  => $this->currentUser()
        ];

        return view('tahunajaran/index', $data);
    }

    public function getData()
    {
        $idSekolah = session('id_sekolah');

        return $this->response->setJSON(
            $this->tahunAjaranService->getAll($idSekolah)
        );
    }

    public function show($id)
    {
        $tahun = $this->tahunAjaranService->find($id);

        if (!$tahun) {
            return $this->response->setJSON(
                responseError('Data tidak ditemukan.')
            );
        }

        return $this->response->setJSON(
            responseSuccess('Data ditemukan.', $tahun)
        );
    }

    public function store()
    {
        try {

            $this->tahunAjaranService->save(
                $this->getFormData()
            );

            return $this->response->setJSON(
                responseSuccess('Tahun ajaran berhasil ditambahkan.')
            );

        } catch (\Throwable $e) {

            return $this->response->setJSON(
                responseError($e->getMessage())
            );

        }
    }

    public function update($id)
    {
        try {

            $this->tahunAjaranService->update(
                $id,
                $this->getFormData()
            );

            return $this->response->setJSON(
                responseSuccess('Tahun ajaran berhasil diubah.')
            );

        } catch (\Throwable $e) {

            return $this->response->setJSON(
                responseError($e->getMessage())
            );

        }
    }

    public function delete($id)
    {
        try {

            $this->tahunAjaranService->delete($id);

            return $this->response->setJSON(
                responseSuccess('Tahun ajaran berhasil dihapus.')
            );

        } catch (\Throwable $e) {

            return $this->response->setJSON(
                responseError($e->getMessage())
            );

        }
    }

    public function setAktif($id)
    {
        try {

            $this->tahunAjaranService->setAktif($id);

            return $this->response->setJSON(
                responseSuccess('Tahun ajaran berhasil diaktifkan.')
            );

        } catch (\Throwable $e) {

            return $this->response->setJSON(
                responseError($e->getMessage())
            );

        }
    }

    private function getFormData(): array
    {
        return [
            'id_sekolah' => session('id_sekolah'),
            'nama'       => trim($this->request->getPost('nama')),
            'aktif'      => $this->request->getPost('aktif') ?? 0,
        ];
    }
}