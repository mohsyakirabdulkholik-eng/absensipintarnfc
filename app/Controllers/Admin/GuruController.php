<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseAdminController;
use App\Services\GuruService;
use App\Traits\AjaxResponseTrait;

class GuruController extends BaseAdminController
{
    protected GuruService $guruService;

    public function __construct()
    {
        $this->guruService = new GuruService();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Guru',
            'user'  => $this->currentUser()
        ];

        return view('guru/index', $data);
    }

    public function getData()
    {
        $idSekolah = session('id_sekolah');

        $data = $this->guruService->getAll($idSekolah);

        return $this->response->setJSON($data);
    }
    public function show($id)
    {
        $guru = $this->guruService->find($id);

        if (!$guru) {

            return $this->response->setJSON(
                responseError('Data tidak ditemukan.')
            );

        }

        return $this->response->setJSON([
        'success' => true,
        'data' => $guru
    ]);
    }
    public function store()
    {
        $nip = $this->request->getPost('nip');

        if (!empty($nip) && $this->guruService->cekNip($nip)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'NIP sudah digunakan.'
            ]);
        }
    
            $data=[
            'id_sekolah'=>session('id_sekolah'),
            'nip'=> $nip,
            'nama'=>$this->request->getPost('nama'),
            'jenis_kelamin'=>$this->request->getPost('jenis_kelamin'),
            'alamat'=>$this->request->getPost('alamat'),
            'no_hp'=>$this->request->getPost('no_hp'),
            'email'=>$this->request->getPost('email'),
            'jabatan'=>$this->request->getPost('jabatan'),
            'foto'=>'default.png',
            'status'=>$this->request->getPost('status')
            ];

        $this->guruService->save($data);

         return $this->response
        ->setJSON(

        responseSuccess(
            'Guru berhasil ditambahkan.'
        )

    );

}

   public function update($id)
    {
        try {

            $data = [

                'nama' => trim($this->request->getPost('nama')),

                'nip' => trim($this->request->getPost('nip')),

                'jabatan' => $this->request->getPost('jabatan'),

                'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),

                'no_hp' => trim($this->request->getPost('no_hp')),

                'email' => trim($this->request->getPost('email')),

                'alamat' => trim($this->request->getPost('alamat')),

                'status' => $this->request->getPost('status')

            ];

            $this->guruService->update($id, $data);

            return $this->response->setJSON([

                'success' => true,

                'message' => 'Data guru berhasil diubah.'

            ]);

        } catch (\Throwable $e) {

            return $this->response->setJSON([

                'success' => false,

                'message' => $e->getMessage()

            ]);

        }
    }

    public function delete($id)
    {
        $this->guruService->delete($id);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Data berhasil dihapus.'
        ]);
    }
}