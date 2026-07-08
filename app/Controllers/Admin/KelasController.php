<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseAdminController;
use App\Services\KelasService;
use App\Traits\AjaxResponseTrait;

class KelasController extends BaseAdminController
{
    protected KelasService $kelasService;

    public function __construct()
    {
        $this->kelasService = new KelasService();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Kelas',
            'user'  => $this->currentUser()
        ];

        return view('kelas/index', $data);
    }

    public function getData()
    {
        $idSekolah = session('id_sekolah');

        $data = $this->kelasService->getAll($idSekolah);

        return $this->response->setJSON($data);
    }
    public function list()
    {
        $idSekolah = session('id_sekolah');

        return $this->response->setJSON(
            $this->kelasService->getBySekolah($idSekolah)
        );
    }
    public function show($id)
    {
        $kelas = $this->kelasService->find($id);

        if (!$kelas) {

            return $this->response->setJSON(
                responseError('Data tidak ditemukan.')
            );

        }

        return $this->response->setJSON(
            responseSuccess('Data ditemukan.', $kelas)
        );
    }
    public function store()
    {
    $validation = \Config\Services::validation();

    $rules = [

        'nama_kelas'=>'required'

    ];

    if(!$this->validate($rules)){

        return $this->response
        ->setJSON(

            responseError(
                'Validasi gagal.',
                $validation->getErrors()
            )

        );

    }

    if ($this->kelasService->isExists(session('id_sekolah'), $this->request->getPost('nama_kelas'))) {
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nama kelas sudah terdaftar.'
                ]);
        }

    $data=[
            'id_sekolah'=>session('id_sekolah'),
            'id_jurusan'=>$this->request->getPost('id_jurusan') ?: null,
            'nama_kelas'=>$this->request->getPost('nama_kelas'),
            'status' => $this->request->getPost('status')
            ];

    $this->kelasService->save($data);

    return $this->response
    ->setJSON(

        responseSuccess(
            'Kelas berhasil ditambahkan.'
        )

    );

}

    public function update($id)
    {
        $data = [
            'id_jurusan' => $this->request->getPost('id_jurusan') ?: null,
            'nama_kelas' => $this->request->getPost('nama_kelas')
        ];

        $this->kelasService->update($id, $data);

        return $this->response->setJSON([
            'success'  => true,
            'message' => 'Data berhasil diubah.'
        ]);
    }

    public function delete($id)
    {
        $this->kelasService->delete($id);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Data berhasil dihapus.'
        ]);
    }
}