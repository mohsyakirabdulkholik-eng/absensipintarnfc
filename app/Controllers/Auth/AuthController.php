<?php

namespace App\Controllers\Auth;
use App\Controllers\BaseController;
use App\Services\AuthService;
use App\Traits\AjaxResponseTrait;


class AuthController extends BaseController
{
    protected AuthService $authService;
    public function __construct()
    {
        $this->authService = new AuthService();
    }
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectByRole();
        }

        return view('auth/login', [
            'title' => 'Login'
        ]);
    }
    public function login()
    {
        if(!$this->request->isAJAX()){
            return $this->response->setStatusCode(404);
        }
        $login = $this->request->getPost('login');
        $password = $this->request->getPost('password');

        $result = $this->authService->login($login, $password);

        return $this->response->setJSON($result);
    }
    public function register()
    {
        if (session()->get('isLoggedIn')) {
            return $this->redirectByRole();
        }

        return view('auth/register', [
            'title'   => 'Daftar Akun',
            'jenjang' => $this->authService->getJenjang()
        ]);
    }
    public function storeRegister()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404);
        }

        try {

            $this->authService->register(
                $this->getRegisterData()
            );

            return $this->response->setJSON(
                responseSuccess('Registrasi berhasil. Silakan login.')
            );

        } catch (\Throwable $e) {

            return $this->response->setJSON(
                responseError($e->getMessage())
            );

        }
    }
    public function logout()
    {
        $this->authService->logout();
        return redirect()->to('/');
    }

    private function getRegisterData(): array
    {
        return [

            // DATA ADMIN
            'nama'                  => trim($this->request->getPost('nama')),
            'username'              => trim($this->request->getPost('username')),
            'email'                 => trim($this->request->getPost('email')),
            'password'              => $this->request->getPost('password'),
            'konfirmasi_password'   => $this->request->getPost('konfirmasi_password'),

            // DATA SEKOLAH
            'nama_sekolah'          => trim($this->request->getPost('nama_sekolah')),
            'id_jenjang'            => $this->request->getPost('id_jenjang'),
            'npsn'                  => trim($this->request->getPost('npsn')),
            'telepon'               => trim($this->request->getPost('telepon')),
            'email_sekolah'         => trim($this->request->getPost('email_sekolah')),
            'alamat'                => trim($this->request->getPost('alamat'))

        ];
    }
    private function redirectByRole()
    {
        if (session()->get('nama_role') === 'superadmin') {
            return redirect()->to(base_url('superadmin'));
        }

        return redirect()->to(base_url('dashboard'));
    }
}