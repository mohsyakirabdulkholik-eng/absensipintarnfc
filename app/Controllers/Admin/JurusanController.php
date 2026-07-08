<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseAdminController;
use App\Services\JurusanService;
use App\Traits\AjaxResponseTrait;

class JurusanController extends BaseAdminController
{
    protected JurusanService $jurusanService;

    public function __construct()
    {
        $this->jurusanService = new JurusanService();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Jurusan',
            'user'  => $this->currentUser()
        ];

        return view('jurusan/index', $data);
    }

    public function getData()
    {
        $idSekolah = session('id_sekolah');

        $data = $this->jurusanService->getAll($idSekolah);

        return $this->response->setJSON($data);
    }
    public function show($id)
    {
        $jurusan = $this->jurusanService->find($id);

        if (!$jurusan) {

            return $this->response->setJSON(
                responseError('Data tidak ditemukan.')
            );

        }

        return $this->response->setJSON(
            responseSuccess('Data ditemukan.', $jurusan)
        );
    }
    public function store()
    {
    $validation = \Config\Services::validation();

    $rules = [
        'nama_jurusan'=>'required'
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

    if ($this->jurusanService->isExists(session('id_sekolah'), $this->request->getPost('nama_jurusan'))) {
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nama jurusan sudah terdaftar.'
                ]);
        }
                
        $data=[
    
            'id_sekolah'=>session('id_sekolah'),
            'nama_jurusan'=>$this->request->getPost('nama_jurusan'), 
            'status'=>'aktif'
    
        ];

    $this->jurusanService->save($data);

    return $this->response
    ->setJSON(
        responseSuccess(
            'Jurusan berhasil ditambahkan.'
        )

    );

}

    public function update($id)
    {
        $data = [
                  'nama_jurusan' => $this->request->getPost('nama_jurusan')
        ];

        $this->jurusanService->update($id, $data);

        return $this->response->setJSON([
            'success'  => true,
            'message' => 'Data berhasil diubah.'
        ]);
    }

    public function delete($id)
    {
        $this->jurusanService->delete($id);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Data berhasil dihapus.'
        ]);
    }
    public function options()
    {
        return $this->response
            ->setJSON(

                $this->jurusanService
                    ->getAll(session('id_sekolah'))

            );
    }
}