<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseAdminController;
use App\Services\PengaturanAbsensiService;

class PengaturanAbsensiController extends BaseAdminController
{
    protected PengaturanAbsensiService $pengaturanAbsensiService;

    public function __construct()
    {
        $this->pengaturanAbsensiService = new PengaturanAbsensiService();
    }

    /**
     * Halaman Pengaturan Absensi
     */
    public function index()
    {
        $data = [
            'title' => 'Pengaturan Absensi',
            'user'  => $this->currentUser()
        ];

        return view('pengaturanabsensi/index', $data);
    }

    /**
     * Data Pengaturan Absensi
     */
    public function getData()
    {
        $idSekolah = session('id_sekolah');

        return $this->response->setJSON(
            $this->pengaturanAbsensiService->getAll($idSekolah)
        );
    }

    /**
     * Detail Pengaturan
     */
    public function show($id)
    {
        $pengaturan = $this->pengaturanAbsensiService->find($id);

        if (!$pengaturan) {
            return $this->response->setJSON(
                responseError('Data tidak ditemukan.')
            );
        }

        return $this->response->setJSON(
            responseSuccess('Data ditemukan.', $pengaturan)
        );
    }

    /**
     * Simpan
     */
    public function store()
    {
        try {

            $this->pengaturanAbsensiService->save(
                $this->getFormData()
            );

            return $this->response->setJSON(
                responseSuccess('Pengaturan absensi berhasil ditambahkan.')
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

            $this->pengaturanAbsensiService->update(
                $id,
                $this->getFormData()
            );

            return $this->response->setJSON(
                responseSuccess('Pengaturan absensi berhasil diubah.')
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

            $this->pengaturanAbsensiService->delete($id);

            return $this->response->setJSON(
                responseSuccess('Pengaturan absensi berhasil dihapus.')
            );

        } catch (\Throwable $e) {

            return $this->response->setJSON(
                responseError($e->getMessage())
            );

        }
    }

    /**
     * Jadikan Aktif
     */
    public function setAktif($id)
    {
        try {

            $this->pengaturanAbsensiService->setAktif($id);

            return $this->response->setJSON(
                responseSuccess('Pengaturan absensi berhasil diaktifkan.')
            );

        } catch (\Throwable $e) {

            return $this->response->setJSON(
                responseError($e->getMessage())
            );

        }
    }

    /**
     * Ambil Pengaturan Aktif
     */
    public function getAktif()
    {
        $idSekolah = session('id_sekolah');

        return $this->response->setJSON(
            responseSuccess(
                'Data ditemukan.',
                $this->pengaturanAbsensiService->getAktif($idSekolah)
            )
        );
    }

    /**
     * Data Form
     */
    private function getFormData(): array
    {
        return [
            'id_sekolah'         => session('id_sekolah'),
            'jam_masuk'          => $this->request->getPost('jam_masuk'),
            'batas_terlambat'    => $this->request->getPost('batas_terlambat'),
            'jam_pulang'         => $this->request->getPost('jam_pulang'),
            'toleransi_pulang'   => $this->request->getPost('toleransi_pulang'),
            'keterangan'         => trim($this->request->getPost('keterangan')),
            'aktif'              => $this->request->getPost('aktif') ?? 0,
        ];
    }
}