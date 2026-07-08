<?php
namespace App\Controllers;
use CodeIgniter\Controller;
use App\Traits\AjaxResponseTrait;

class BaseAdminController extends Controller
{
    protected $helpers = ['url', 'form'];
    protected $session;
    protected array $user;
    
    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->session = session();
        $this->user = ['id' => session()->get('user_id'), 
                       'nama' => session()->get('nama'), 
                       'role' => session()->get('id_role'),
                       'id_sekolah' => session()->get('id_sekolah'), 
                       'id_jenjang' => session()->get('id_jenjang'),];
    }
    protected function currentUser(): array
    {
        return $this->user;
    }
}