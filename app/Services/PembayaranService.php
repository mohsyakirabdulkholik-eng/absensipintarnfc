<?php

namespace App\Services;

use App\Repositories\PembayaranRepository;
use App\Repositories\LanggananRepository;
use App\Repositories\PaketRepository;

class PembayaranService
{
    protected PembayaranRepository $repository;
    protected LanggananRepository $langgananRepository;
    protected PaketRepository $paketRepository;

    public function __construct()
    {
        $this->repository = new PembayaranRepository();
        $this->langgananRepository = new LanggananRepository();
        $this->paketRepository = new PaketRepository();
    }

    /**
     * Ambil seluruh pembayaran
     */
    public function getAll(): array
    {
        return $this->repository->getAll();
    }

    /**
     * Ambil detail pembayaran
     */
    public function find(int $id): ?array
    {
        return $this->repository->find($id);
    }

    /**
     * Ambil pembayaran milik sekolah
     */
    public function getBySekolah(int $idSekolah): array
    {
        return $this->repository->getBySekolah($idSekolah);
    }

    /**
     * Membuat pembayaran baru
     */
    public function create(
        int $idSekolah,
        int $idPaket,
        string $metodePembayaran = 'transfer',
        ?string $buktiPembayaran = null
    ): int {

        // Pastikan paket tersedia
        $paket = $this->paketRepository->find($idPaket);

        if (!$paket) {
            throw new \Exception(
                'Paket tidak ditemukan.'
            );
        }

        // Pastikan paket aktif
        if ($paket['status'] !== 'aktif') {
            throw new \Exception(
                'Paket tidak tersedia.'
            );
        }

        // Simpan pembayaran
        return $this->repository->insert([
            'id_sekolah'          => $idSekolah,
            'id_paket'            => $idPaket,
            'jumlah'              => $paket['harga'],
            'tanggal_pembayaran'  => date('Y-m-d'),
            'metode_pembayaran'   => $metodePembayaran,
            'bukti_pembayaran'    => $buktiPembayaran,
            'status'              => 'menunggu',
            'catatan'             => null,
            'created_at'          => date('Y-m-d H:i:s'),
            'updated_at'          => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Setujui pembayaran
     *
     * Setelah pembayaran disetujui,
     * subscription otomatis diaktifkan
     * atau diperpanjang.
     */
    public function approve(int $id): bool
    {
        $pembayaran = $this->repository->find($id);

        if (!$pembayaran) {
            throw new \Exception(
                'Data pembayaran tidak ditemukan.'
            );
        }

        // Jangan proses pembayaran yang sudah diproses
        if ($pembayaran['status'] !== 'menunggu') {
            throw new \Exception(
                'Pembayaran sudah diproses.'
            );
        }

        // Ambil paket
        $paket = $this->paketRepository->find(
            (int) $pembayaran['id_paket']
        );

        if (!$paket) {
            throw new \Exception(
                'Paket tidak ditemukan.'
            );
        }

        // Cek subscription sekolah
        $subscription =
            $this->langgananRepository
                ->getLatestBySekolah(
                    (int) $pembayaran['id_sekolah']
                );

        /*
         * Jika belum pernah berlangganan,
         * buat subscription baru.
         */
        if (!$subscription) {

            $this->activateSubscription(
                $pembayaran,
                $paket
            );

        } else {

            /*
             * Jika sudah pernah berlangganan,
             * perpanjang subscription.
             */
            $this->extendSubscription(
                $pembayaran,
                $paket
            );
        }

        // Update status pembayaran
        return $this->repository->update(
            (int) $pembayaran['id'],
            [
                'status'     => 'disetujui',
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
    }

    /**
     * Aktifkan subscription baru
     */
    protected function activateSubscription(
        array $pembayaran,
        array $paket
    ): int {

        $tanggalMulai = date('Y-m-d');

        $tanggalBerakhir = date(
            'Y-m-d',
            strtotime(
                "+{$paket['durasi_hari']} days"
            )
        );

        return $this->langgananRepository->insert([
            'id_sekolah'       => $pembayaran['id_sekolah'],
            'id_paket'         => $pembayaran['id_paket'],
            'tanggal_mulai'    => $tanggalMulai,
            'tanggal_berakhir' => $tanggalBerakhir,
            'status'           => 'aktif',
        ]);
    }

    /**
     * Perpanjang subscription
     */
    protected function extendSubscription(
        array $pembayaran,
        array $paket
    ): int {

        $subscription =
            $this->langgananRepository
                ->getLatestBySekolah(
                    (int) $pembayaran['id_sekolah']
                );

        $today = new \DateTime(
            date('Y-m-d')
        );

        /*
         * Jika masih aktif,
         * tambahan dimulai dari tanggal berakhir.
         */
        if (
            $subscription &&
            $subscription['tanggal_berakhir']
                >= $today->format('Y-m-d')
        ) {

            $tanggalMulai =
                $subscription['tanggal_berakhir'];

        } else {

            /*
             * Jika expired,
             * mulai dari hari ini.
             */
            $tanggalMulai =
                $today->format('Y-m-d');
        }

        $tanggalBerakhir = date(
            'Y-m-d',
            strtotime(
                $tanggalMulai .
                " +{$paket['durasi_hari']} days"
            )
        );

        return $this->langgananRepository->update(
            (int) $subscription['id'],
            [
                'id_paket'         => $pembayaran['id_paket'],
                'tanggal_mulai'    => $tanggalMulai,
                'tanggal_berakhir' => $tanggalBerakhir,
                'status'           => 'aktif',
            ]
        )
            ? (int) $subscription['id']
            : 0;
    }

    /**
     * Tolak pembayaran
     */
    public function reject(
        int $id,
        ?string $catatan = null
    ): bool {

        $pembayaran = $this->repository->find($id);

        if (!$pembayaran) {
            throw new \Exception(
                'Data pembayaran tidak ditemukan.'
            );
        }

        if ($pembayaran['status'] !== 'menunggu') {
            throw new \Exception(
                'Pembayaran sudah diproses.'
            );
        }

        return $this->repository->update(
            $id,
            [
                'status'     => 'ditolak',
                'catatan'    => $catatan,
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        );
    }
}