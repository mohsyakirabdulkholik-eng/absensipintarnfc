<?php

namespace App\Repositories;

use CodeIgniter\Database\BaseConnection;

class SuperadminDashboardRepository
{
    protected BaseConnection $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    /**
     * Total seluruh sekolah
     */
    public function countSekolah(): int
    {
        return (int) $this->db
            ->table('sekolah')
            ->countAllResults();
    }

    /**
     * Total sekolah aktif
     */
    public function countSekolahAktif(): int
    {
        return (int) $this->db
            ->table('sekolah')
            ->where('status', 'aktif')
            ->countAllResults();
    }

    /**
     * Total langganan aktif
     */
    public function countLanggananAktif(): int
    {
        return (int) $this->db
            ->table('langganan')
            ->where('status', 'aktif')
            ->where('tanggal_berakhir >=', date('Y-m-d'))
            ->countAllResults();
    }

    /**
     * Total langganan expired
     */
    public function countLanggananExpired(): int
    {
        return (int) $this->db
            ->table('langganan')
            ->groupStart()
                ->where('status', 'expired')
                ->orWhere('tanggal_berakhir <', date('Y-m-d'))
            ->groupEnd()
            ->countAllResults();
    }

    /**
     * Sekolah terbaru
     */
    public function getSekolahTerbaru(int $limit = 5): array
    {
        return $this->db
            ->table('sekolah')
            ->select('
                sekolah.id,
                sekolah.nama_sekolah,
                sekolah.npsn,
                sekolah.status,
                jenjang.nama AS nama_jenjang
            ')
            ->join(
                'jenjang',
                'jenjang.id = sekolah.id_jenjang',
                'left'
            )
            ->orderBy('sekolah.id', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Langganan yang akan berakhir dalam 7 hari
     */
    public function getLanggananAkanBerakhir(int $hari = 7): array
    {
        $hariIni = date('Y-m-d');
        $batas   = date(
            'Y-m-d',
            strtotime("+{$hari} days")
        );

        return $this->db
            ->table('langganan')
            ->select('
                langganan.id,
                langganan.id_sekolah,
                langganan.tanggal_berakhir,
                sekolah.nama_sekolah,
                paket.nama_paket
            ')
            ->join(
                'sekolah',
                'sekolah.id = langganan.id_sekolah',
                'left'
            )
            ->join(
                'paket',
                'paket.id = langganan.id_paket',
                'left'
            )
            ->where('langganan.status', 'aktif')
            ->where('langganan.tanggal_berakhir >=', $hariIni)
            ->where('langganan.tanggal_berakhir <=', $batas)
            ->orderBy('langganan.tanggal_berakhir', 'ASC')
            ->get()
            ->getResultArray();
    }
}