<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseAdminController;
use App\Services\SemesterService;

class SemesterController extends BaseAdminController
{
    protected $service;

    public function __construct()
    {
        
        $this->service = new SemesterService();
    }

    /**
     * Halaman utama
     */
    public function index()
    {
        $data = [
            'title' => 'Semester',
            'user'  => $this->currentUser()
        ];
        return view('semester/index', $data);
    }

    /**
     * Data Semester
     */
    public function getData()
    {
        $idSekolah = session('id_sekolah');

        return $this->response->setJSON(
            $this->service->getAll($idSekolah)
        );
    }

    /**
     * Detail Semester
     */
    public function show($id)
    {
        $semester = $this->service->find($id);

        if (!$semester) {

            return $this->response->setJSON(
                responseError('Data tidak ditemukan.')
            );

        }

        return $this->response->setJSON(
            responseSuccess('Data ditemukan.', $semester)
        );
    }
    /**
     * Simpan Semester
     */
    public function store()
    {
        try {

            $this->service->save(
                $this->getFormData()
            );

            return $this->response->setJSON(
                responseSuccess('Semester berhasil ditambahkan.')
            );

        } catch (\Throwable $e) {

            return $this->response->setJSON(
                responseError($e->getMessage())
            );

        }
    }

    /**
     * Update Semester
     */
    public function update($id)
    {
        try {

            $this->service->update(
                $id,
                $this->getFormData()
            );

            return $this->response->setJSON(
                responseSuccess('Semester berhasil diubah.')
            );

        } catch (\Throwable $e) {

            return $this->response->setJSON(
                responseError($e->getMessage())
            );

        }
    }

    /**
     * Hapus Semester
     */
    public function delete($id)
    {
        try {

            $this->service->delete($id);

            return $this->response->setJSON(
                responseSuccess('Semester berhasil dihapus.')
            );

        } catch (\Throwable $e) {

            return $this->response->setJSON(
                responseError($e->getMessage())
            );

        }
    }
    /**
     * Jadikan Semester Aktif
     */
    public function setAktif($id)
    {
        try {

            $this->service->setAktif($id);

            return $this->response->setJSON(
                responseSuccess('Semester berhasil diaktifkan.')
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
            'id_tahun' => $this->request->getPost('id_tahun'),
            'nama'     => trim($this->request->getPost('nama')),
            'aktif'    => $this->request->getPost('aktif') ?? 0,
        ];
    }
}