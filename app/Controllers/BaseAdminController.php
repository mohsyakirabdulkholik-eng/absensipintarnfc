<?php
namespace App\Controllers;
use CodeIgniter\Controller;

class BaseAdminController extends Controller
{
    protected $helpers = ['url', 'form'];
    protected $session;
    protected $user;
    
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->session = session();
        $this->user = ['id' => session()->get('user_id'), 
                       'nama' => session()->get('nama'), 
                       'role' => session()->get('id_role'),
                       'id_sekolah' => session()->get('id_sekolah'), ];
    }
    protected function currentUser()
    {
        return ['id' => session('user_id'), 
                       'nama' => session('nama'), 
                       'username' => session('username'), 
                       'role' => session('id_role'),
                       'id_sekolah' => session('id_sekolah'), ];
    }
}