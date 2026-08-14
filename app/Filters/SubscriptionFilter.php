<?php

namespace App\Filters;

use App\Services\SubscriptionService;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class SubscriptionFilter implements FilterInterface
{
    protected SubscriptionService $subscriptionService;

    public function __construct()
    {
        $this->subscriptionService = new SubscriptionService();
    }

    public function before(
        RequestInterface $request,
        $arguments = null
    ) {
        // Pastikan sudah login
        if (!session()->get('isLoggedIn')) {
            return redirect()->to(base_url('login'));
        }

        // Superadmin bebas dari pengecekan subscription
        if (session()->get('nama_role') === 'superadmin') {
            return;
        }

        // Hanya berlaku untuk admin sekolah
        if (session()->get('nama_role') !== 'admin sekolah') {
            return;
        }

        $idSekolah = (int) session()->get('id_sekolah');

        // Sekolah belum memiliki subscription aktif
        if (!$this->subscriptionService->isActive($idSekolah)) {
            return redirect()
                ->to(base_url('pembayaran'))
                ->with(
                    'error',
                    'Langganan sekolah belum aktif atau sudah expired.'
                );
        }
    }

    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
        // Tidak ada proses setelah request
    }
}