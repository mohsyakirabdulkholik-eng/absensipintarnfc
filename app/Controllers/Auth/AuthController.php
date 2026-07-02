<?php

namespace App\Controllers\Auth;
use App\Controllers\BaseController;
use App\Services\AuthService;

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
            return redirect()->to(base_url('/dashboard'));
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
    public function logout()
    {
        $this->authService->logout();
        return redirect()->to('/');
    }
}