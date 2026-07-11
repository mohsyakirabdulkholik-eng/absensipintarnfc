<?php

namespace App\Repositories;

use Config\Database;

class DashboardRepository
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Statistik Dashboard
     */
    public function getStatistik($idSekolah): array
    {
        return [

            'guru' => $this->db
                ->table('guru')
                ->where('id_sekolah', $idSekolah)
                ->countAllResults(),

            'siswa' => $this->db
                ->table('siswa')
                ->where('id_sekolah', $idSekolah)
                ->countAllResults(),

            'absensi' => $this->db
                ->table('absensi')
                ->where('id_sekolah', $idSekolah)
                ->where('tanggal', date('Y-m-d'))
                ->countAllResults(),

            'nfc' => $this->countKartuAktif($idSekolah),

            'tahunAktif' => $this->getTahunAktif($idSekolah),

            'semesterAktif' => $this->getSemesterAktif($idSekolah),

        ];
    }

    /**
     * Hitung kartu NFC aktif
     */
    private function countKartuAktif($idSekolah): int
    {
        $guru = $this->db
            ->table('kartu_nfc k')
            ->join('guru g', 'g.id = k.id_guru')
            ->where('g.id_sekolah', $idSekolah)
            ->where('k.status', 'aktif')
            ->countAllResults();

        $siswa = $this->db
            ->table('kartu_nfc k')
            ->join('siswa s', 's.id = k.id_siswa')
            ->where('s.id_sekolah', $idSekolah)
            ->where('k.status', 'aktif')
            ->countAllResults();

        return $guru + $siswa;
    }

    /**
 * Grafik Kehadiran 7 Hari Terakhir
 */
    public function getGrafikKehadiran($idSekolah): array
    {
        $result = $this->db
            ->table('absensi')
            ->select("tanggal, COUNT(*) as total")
            ->where('id_sekolah', $idSekolah)
            ->where('tanggal >=', date('Y-m-d', strtotime('-6 days')))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'ASC')
            ->get()
            ->getResultArray();

        $grafik = [];

        // isi 7 hari terakhir walaupun tidak ada absensi
        for ($i = 6; $i >= 0; $i--) {

            $tanggal = date('Y-m-d', strtotime("-{$i} days"));

            $grafik[$tanggal] = 0;
        }

        foreach ($result as $row) {
            $grafik[$row['tanggal']] = (int) $row['total'];
        }

        return $grafik;
    }
    /**
     * Tahun Ajaran Aktif
     */
    private function getTahunAktif($idSekolah)
    {
        return $this->db
            ->table('tahun_ajaran')
            ->select('nama')
            ->where('id_sekolah', $idSekolah)
            ->where('aktif', 1)
            ->get()
            ->getRowArray();
    }

    /**
     * Semester Aktif
     */
    private function getSemesterAktif($idSekolah)
    {
        return $this->db
            ->table('semester s')
            ->select('s.nama')
            ->join('tahun_ajaran t', 't.id = s.id_tahun')
            ->where('t.id_sekolah', $idSekolah)
            ->where('s.aktif', 1)
            ->get()
            ->getRowArray();
    }
    /**
     * Aktivitas terbaru
     */
    public function getAktivitasTerbaru($idSekolah, $limit = 10)
    {
        return $this->db
            ->table('absensi a')
            ->select("
                a.tanggal,
                a.jam_masuk,
                a.jam_pulang,
                a.status,
                a.jenis,
                a.created_at,
                g.nama,
                s.nama_siswa
            ")
            ->join('kartu_nfc k', 'k.id = a.id_kartu_nfc')
            ->join('guru g', 'g.id = k.id_guru', 'left')
            ->join('siswa s', 's.id = k.id_siswa', 'left')
            ->where('a.id_sekolah', $idSekolah)
            ->orderBy('a.created_at', 'DESC')
            ->limit($limit)
            ->get()
            ->getResultArray();
    }
    /**
 * Ringkasan status absensi hari ini
 */
    public function getStatusHariIni($idSekolah): array
    {
        $rows = $this->db
            ->table('absensi')
            ->select('status, COUNT(*) total')
            ->where('id_sekolah', $idSekolah)
            ->where('tanggal', date('Y-m-d'))
            ->groupBy('status')
            ->get()
            ->getResultArray();

        $hasil = [
            'Hadir'      => 0,
            'Terlambat'  => 0,
            'Izin'       => 0,
            'Sakit'      => 0,
            'Alpha'      => 0,
        ];

        foreach ($rows as $row) {

            if (isset($hasil[$row['status']])) {

                $hasil[$row['status']] = (int)$row['total'];

            }
        }

        return $hasil;
    }
}