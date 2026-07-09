<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseAdminController;
use App\Services\SiswaService;
use App\Traits\AjaxResponseTrait;

class SiswaController extends BaseAdminController
{
    protected SiswaService $siswaService;

    public function __construct()
    {
        $this->siswaService = new SiswaService();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Siswa',
            'user'  => $this->currentUser()
        ];

        return view('siswa/index', $data);
    }

    public function getData()
    {
        $idSekolah = session('id_sekolah');

        $data = $this->siswaService->getAll($idSekolah);

        return $this->response->setJSON($data);
    }
    public function show($id)
    {
        $siswa = $this->siswaService->find($id);

        if (!$siswa) {

            return $this->response->setJSON(
                responseError('Data tidak ditemukan.')
            );

        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $siswa
        ]);
    }
    
    public function store()
    {
        $nis = $this->request->getPost('nis');

        if (!empty($nis) && $this->siswaService->cekNis($nis)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'NIS sudah digunakan.'
            ]);
        }
        
        $nisn = trim($this->request->getPost('nisn'));

        if (!empty($nisn) && $this->siswaService->cekNisn($nisn)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'NISN sudah digunakan.'
            ]);
        }

        $uid = trim($this->request->getPost('uid_nfc'));

        if (!empty($uid) && $this->siswaService->cekUid($uid)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'UID NFC sudah terdaftar.'
            ]);
        }
        
           $data = [
                'id_sekolah'      => session('id_sekolah'),
                'id_kelas'        => $this->request->getPost('id_kelas'),
                'uid_nfc'         => $this->request->getPost('uid_nfc'),
                'nis'             => trim($this->request->getPost('nis')),
                'nisn'            => trim($this->request->getPost('nisn')),
                'nama_siswa'      => trim($this->request->getPost('nama_siswa')),
                'jenis_kelamin'   => $this->request->getPost('jenis_kelamin'),
                'tempat_lahir'    => trim($this->request->getPost('tempat_lahir')),
                'tanggal_lahir'   => $this->request->getPost('tanggal_lahir'),
                'alamat'          => trim($this->request->getPost('alamat')),
                'nama_ortu'       => trim($this->request->getPost('nama_ortu')),
                'no_hp'           => trim($this->request->getPost('no_hp')),
                'foto'            => $this->request->getFile('foto'),
                'status'          => $this->request->getPost('status')
            ];

                       
        $this->siswaService->save($data);

         return $this->response
        ->setJSON(

        responseSuccess(
            'Siswa berhasil ditambahkan.'
        )

    );

}

   public function update($id)
    {
        try {

            $data = [
                    'id_sekolah'      => session('id_sekolah'),
                    'id_kelas'        => $this->request->getPost('id_kelas'),
                    'uid_nfc'         => $this->request->getPost('uid_nfc'),
                    'nis'             => trim($this->request->getPost('nis')),
                    'nisn'            => trim($this->request->getPost('nisn')),
                    'nama_siswa'      => trim($this->request->getPost('nama_siswa')),
                    'jenis_kelamin'   => $this->request->getPost('jenis_kelamin'),
                    'tempat_lahir'    => trim($this->request->getPost('tempat_lahir')),
                    'tanggal_lahir'   => $this->request->getPost('tanggal_lahir'),
                    'alamat'          => trim($this->request->getPost('alamat')),
                    'nama_ortu'       => trim($this->request->getPost('nama_ortu')),
                    'no_hp'           => trim($this->request->getPost('no_hp')),
                    'foto'            => $this->request->getFile('foto'),
                    'status'          => $this->request->getPost('status')
                ];  
                
                                       
            $this->siswaService->update($id, $data);

            return $this->response->setJSON([

                'success' => true,

                'message' => 'Data siswa berhasil diubah.'

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
        $this->siswaService->delete($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Data siswa berhasil dihapus.'
        ]);
    }
}