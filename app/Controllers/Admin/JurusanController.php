<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseAdminController;
use App\Services\JurusanService;

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

        'kode'=>'required',

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

    $data=[

        'id_sekolah'=>session('id_sekolah'),

        'kode'=>$this->request->getPost('kode'),

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
            'kode'         => $this->request->getPost('kode'),
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
}