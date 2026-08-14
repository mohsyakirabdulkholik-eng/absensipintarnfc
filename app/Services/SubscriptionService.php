<?php

namespace App\Services;

use App\Repositories\LanggananRepository;

class SubscriptionService
{
    protected LanggananRepository $repository;

    public function __construct()
    {
        $this->repository = new LanggananRepository();
    }

    /**
     * Ambil langganan sekolah
     */
    public function getSubscription(int $idSekolah): ?array
    {
        return $this->repository->getLatestBySekolah($idSekolah);
    }

    /**
     * Cek apakah sekolah memiliki langganan aktif
     */
    public function isActive(int $idSekolah): bool
    {
        $subscription = $this->repository->getActiveBySekolah($idSekolah);

        return $subscription !== null;
    }

    /**
     * Cek apakah langganan sudah expired
     */
    public function isExpired(int $idSekolah): bool
    {
        $subscription = $this->repository->getLatestBySekolah($idSekolah);

        if (!$subscription) {
            return true;
        }

        return $subscription['tanggal_berakhir'] < date('Y-m-d');
    }

    /**
     * Sisa hari langganan
     */
    public function getRemainingDays(int $idSekolah): int
    {
        $subscription = $this->repository->getLatestBySekolah($idSekolah);

        if (!$subscription) {
            return 0;
        }

        $today = new \DateTime(date('Y-m-d'));
        $end   = new \DateTime($subscription['tanggal_berakhir']);

        if ($end < $today) {
            return 0;
        }

        return (int) $today->diff($end)->days;
    }
    /**
     * Aktivasi langganan baru
     */
    public function activate(
        int $idSekolah,
        int $idPaket,
        int $durasiHari
    ): int {
        $tanggalMulai = date('Y-m-d');

        $tanggalBerakhir = date(
            'Y-m-d',
            strtotime("+{$durasiHari} days")
        );

        return $this->repository->insert([
            'id_sekolah'       => $idSekolah,
            'id_paket'         => $idPaket,
            'tanggal_mulai'    => $tanggalMulai,
            'tanggal_berakhir' => $tanggalBerakhir,
            'status'           => 'aktif',
        ]);
    }
    /**
     * Ambil seluruh data langganan
     */
    public function getAllWithDetail(): array
    {
        return $this->repository->getAllWithDetail();
    }
    /**
     * Perpanjang langganan
     */
    public function extend(
        int $idSekolah,
        int $idPaket,
        int $durasiHari
    ): int {
        $subscription = $this->repository
            ->getLatestBySekolah($idSekolah);

        $today = new \DateTime(date('Y-m-d'));

        /*
        * Jika masih aktif,
        * tambahan dihitung dari tanggal berakhir.
        */
        if (
            $subscription &&
            $subscription['tanggal_berakhir'] >= $today->format('Y-m-d')
        ) {
            $tanggalMulai = $subscription['tanggal_berakhir'];
        } else {
            /*
            * Jika sudah expired,
            * mulai dari hari ini.
            */
            $tanggalMulai = $today->format('Y-m-d');
        }

        $tanggalBerakhir = date(
            'Y-m-d',
            strtotime($tanggalMulai . " +{$durasiHari} days")
        );

        if ($subscription) {
            $this->repository->update(
                (int) $subscription['id'],
                [
                    'id_paket'         => $idPaket,
                    'tanggal_mulai'    => $tanggalMulai,
                    'tanggal_berakhir' => $tanggalBerakhir,
                    'status'           => 'aktif',
                ]
            );

            return (int) $subscription['id'];
        }

        return $this->activate(
            $idSekolah,
            $idPaket,
            $durasiHari
        );
    }
}