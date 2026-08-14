<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Repositories\PaketRepository;
use App\Services\PembayaranService;

class PembayaranController extends BaseController
{
    protected PembayaranService $service;
    protected PaketRepository $paketRepository;

    public function __construct()
    {
        $this->service = new PembayaranService();
        $this->paketRepository = new PaketRepository();
    }

    /**
     * Halaman pembayaran sekolah
     */
    public function index()
    {
        $idSekolah = (int) session()->get('id_sekolah');

        return view('pembayaran/index', [
            'title'      => 'Pembayaran',
            'paket'      => $this->paketRepository->getActive(),
            'pembayaran' => $this->service->getBySekolah($idSekolah),
        ]);
    }

    /**
     * Kirim pembayaran
     */
    public function store()
    {
        $idSekolah = (int) session()->get('id_sekolah');

        $idPaket = (int) $this->request->getPost('id_paket');

        $file = $this->request->getFile('bukti_pembayaran');

        try {

            /*
             * Validasi file
             */
            if (!$file || !$file->isValid()) {
                throw new \Exception(
                    'Bukti pembayaran wajib diupload.'
                );
            }

            if ($file->hasMoved()) {
                throw new \Exception(
                    'File bukti pembayaran sudah diproses.'
                );
            }

            /*
             * Validasi ukuran dan tipe file
             */
            $allowedTypes = [
                'image/jpeg',
                'image/jpg',
                'image/png',
                'image/webp',
            ];

            if (!in_array(
                $file->getMimeType(),
                $allowedTypes,
                true
            )) {
                throw new \Exception(
                    'Bukti pembayaran harus berupa JPG, PNG, atau WEBP.'
                );
            }

            if ($file->getSizeByUnit('mb') > 5) {
                throw new \Exception(
                    'Ukuran bukti pembayaran maksimal 5 MB.'
                );
            }

            /*
             * Nama file aman
             */
            $namaFile = $file->getRandomName();

            /*
             * Pastikan folder tersedia
             */
            $uploadPath = FCPATH . 'uploads/pembayaran';

            if (!is_dir($uploadPath)) {
                mkdir(
                    $uploadPath,
                    0775,
                    true
                );
            }

            /*
             * Pindahkan file
             */
            $file->move(
                $uploadPath,
                $namaFile
            );

            /*
             * Simpan pembayaran
             */
            $this->service->create(
                $idSekolah,
                $idPaket,
                'transfer',
                $namaFile
            );

            return redirect()
                ->to(base_url('pembayaran'))
                ->with(
                    'success',
                    'Pembayaran berhasil dikirim dan sedang menunggu verifikasi.'
                );

        } catch (\Throwable $e) {

            /*
             * Hapus file jika pembayaran gagal disimpan
             */
            if (
                isset($namaFile) &&
                file_exists(
                    $uploadPath . '/' . $namaFile
                )
            ) {
                unlink(
                    $uploadPath . '/' . $namaFile
                );
            }

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }
}