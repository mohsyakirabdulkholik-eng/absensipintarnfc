<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Services\PembayaranService;

class PembayaranController extends BaseController
{
    protected PembayaranService $service;

    public function __construct()
    {
        $this->service = new PembayaranService();
    }

    /**
     * Daftar seluruh pembayaran
     */
    public function index()
    {
        return view('superadmin/pembayaran/index', [
            'title'      => 'Pembayaran',
            'pembayaran' => $this->service->getAll(),
        ]);
    }

    /**
     * Detail pembayaran
     */
    public function detail(int $id)
    {
        $pembayaran = $this->service->find($id);

        if (!$pembayaran) {
            return redirect()
                ->to(base_url('superadmin/pembayaran'))
                ->with(
                    'error',
                    'Data pembayaran tidak ditemukan.'
                );
        }

        return view('superadmin/pembayaran/detail', [
            'title'      => 'Detail Pembayaran',
            'pembayaran' => $pembayaran,
        ]);
    }

    /**
     * Setujui pembayaran
     */
    public function approve(int $id)
    {
        try {

            $this->service->approve($id);

            return redirect()
                ->to(base_url('superadmin/pembayaran'))
                ->with(
                    'success',
                    'Pembayaran berhasil disetujui dan langganan telah diaktifkan.'
                );

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /**
     * Tolak pembayaran
     */
    public function reject(int $id)
    {
        try {

            $catatan = $this->request
                ->getPost('catatan');

            $this->service->reject(
                $id,
                $catatan
            );

            return redirect()
                ->to(base_url('superadmin/pembayaran'))
                ->with(
                    'success',
                    'Pembayaran berhasil ditolak.'
                );

        } catch (\Throwable $e) {

            return redirect()
                ->back()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }
}