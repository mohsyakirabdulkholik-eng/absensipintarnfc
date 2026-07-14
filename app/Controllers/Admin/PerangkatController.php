<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseAdminController;
use App\Services\PerangkatService;

class PerangkatController extends BaseAdminController
{
    protected $perangkatService;

    public function __construct()
    {
        $this->perangkatService = new PerangkatService();
    }

    public function index()
    {
        return view('perangkat/index');
    }

    public function getData()
    {
        $idSekolah = session()->get('id_sekolah');

        return $this->response->setJSON(
            $this->perangkatService->getAll($idSekolah)
        );
    }

    public function save()
    {
        $id = $this->request->getPost('id');

        $data = [
            'id_sekolah'      => session()->get('id_sekolah'),
            'nama_perangkat'  => $this->request->getPost('nama_perangkat'),
            'status'          => $this->request->getPost('status')
        ];

        if (empty($id)) {
            $this->perangkatService->save($data);
            $message = 'Perangkat berhasil ditambahkan.';
        } else {
            $this->perangkatService->update($id, $data);
            $message = 'Perangkat berhasil diperbarui.';
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => $message
        ]);
    }

    public function edit($id)
    {
        return $this->response->setJSON(
            $this->perangkatService->find($id)
        );
    }

    public function delete($id)
    {
        $this->perangkatService->delete($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data perangkat berhasil dihapus.'
        ]);
    }

    public function detail($id)
    {
        $data = $this->perangkatService->find($id);

        if (!$data) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data perangkat tidak ditemukan.'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $data
        ]);
    }

    public function regenerateToken($id)
    {
        $token = $this->perangkatService->regenerateToken($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Token berhasil dibuat ulang.',
            'token'   => $token
        ]);
    }

    public function resetDevice($id)
    {
        $this->perangkatService->resetDevice($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Perangkat berhasil direset.'
        ]);
    }

    public function toggleStatus($id)
    {
        $this->perangkatService->toggleStatus($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Status perangkat berhasil diubah.'
        ]);
    }
    
}