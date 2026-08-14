<?php

namespace App\Controllers\Superadmin;

use App\Controllers\BaseController;
use App\Services\SuperadminSekolahService;
use App\Services\SubscriptionService;
use App\Services\PaketService;

class SekolahController extends BaseController
{
    protected SuperadminSekolahService $sekolahService;
    protected SubscriptionService $subscriptionService;
    protected PaketService $paketService;

    public function __construct()
    {
        $this->sekolahService = new SuperadminSekolahService();

        $this->subscriptionService = new SubscriptionService();

        $this->paketService = new PaketService();
    }

    public function index()
    {
        return view('superadmin/sekolah/index', [
            'title'   => 'Daftar Sekolah',
            'sekolah' => $this->sekolahService->getAll()
        ]);
    }

    public function detail($id)
    {
        $idSekolah = (int) $id;

        $sekolah = $this->sekolahService->find($idSekolah);

        if (!$sekolah) {
            return redirect()
                ->to(base_url('superadmin/sekolah'))
                ->with(
                    'error',
                    'Data sekolah tidak ditemukan.'
                );
        }

        $langganan = $this->subscriptionService
            ->getSubscription($idSekolah);

        $paket = null;

        if ($langganan) {
            $paket = $this->paketService
                ->find((int) $langganan['id_paket']);
        }
        $paketAktif = $this->paketService->getActive();

        $sisaHari = $this->subscriptionService
            ->getRemainingDays($idSekolah);

        $aktif = $this->subscriptionService
            ->isActive($idSekolah);

        return view('superadmin/sekolah/detail', [
            'title'     => 'Detail Sekolah',
            'sekolah'   => $sekolah,
            'langganan' => $langganan,
            'paket'     => $paket,
            'paketAktif' => $paketAktif,
            'sisaHari'  => $sisaHari,
            'aktif'     => $aktif,
        ]);
    }
    public function activateSubscription()
    {
        $idSekolah = (int) $this->request->getPost('id_sekolah');
        $idPaket   = (int) $this->request->getPost('id_paket');

        if (!$idSekolah || !$idPaket) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Data sekolah atau paket tidak valid.'
            ]);
        }

        // Pastikan sekolah tersedia
        $sekolah = $this->sekolahService->find($idSekolah);

        if (!$sekolah) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Data sekolah tidak ditemukan.'
            ]);
        }

        // Pastikan paket tersedia dan aktif
        $paket = $this->paketService->find($idPaket);

        if (!$paket) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Paket tidak ditemukan.'
            ]);
        }

        if ($paket['status'] !== 'aktif') {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Paket sedang tidak aktif.'
            ]);
        }

        // Cek apakah sekolah masih memiliki langganan aktif
        if ($this->subscriptionService->isActive($idSekolah)) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Sekolah masih memiliki langganan aktif.'
            ]);
        }

        try {

            $idLangganan = $this->subscriptionService->activate(
                $idSekolah,
                $idPaket,
                (int) $paket['durasi_hari']
            );

            return $this->response->setJSON([
                'status'       => true,
                'message'      => 'Langganan berhasil diaktifkan.',
                'id_langganan' => $idLangganan
            ]);

        } catch (\Throwable $e) {

            log_message(
                'error',
                'Gagal aktivasi langganan: ' . $e->getMessage()
            );

            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Gagal mengaktifkan langganan.'
            ]);
        }
    }
    public function extendSubscription()
    {
        $idSekolah = (int) $this->request->getPost('id_sekolah');
        $idPaket   = (int) $this->request->getPost('id_paket');

        if (!$idSekolah || !$idPaket) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Data sekolah atau paket tidak valid.'
            ]);
        }

        // Pastikan sekolah ada
        $sekolah = $this->sekolahService->find($idSekolah);

        if (!$sekolah) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Data sekolah tidak ditemukan.'
            ]);
        }

        // Pastikan paket ada
        $paket = $this->paketService->find($idPaket);

        if (!$paket) {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Paket tidak ditemukan.'
            ]);
        }

        // Pastikan paket masih aktif
        if ($paket['status'] !== 'aktif') {
            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Paket sedang tidak aktif.'
            ]);
        }

        try {

            $idLangganan = $this->subscriptionService->extend(
                $idSekolah,
                $idPaket,
                (int) $paket['durasi_hari']
            );

            return $this->response->setJSON([
                'status'       => true,
                'message'      => 'Langganan berhasil diperpanjang.',
                'id_langganan' => $idLangganan
            ]);

        } catch (\Throwable $e) {

            log_message(
                'error',
                'Gagal memperpanjang langganan: ' . $e->getMessage()
            );

            return $this->response->setJSON([
                'status'  => false,
                'message' => 'Gagal memperpanjang langganan.'
            ]);
        }
    }
}