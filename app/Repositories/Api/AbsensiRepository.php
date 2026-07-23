<?php

namespace App\Repositories\Api;
use Config\Database;
use App\Models\AbsensiModel;

class AbsensiRepository
{
    protected AbsensiModel $model;
    protected $db;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->model = new AbsensiModel();
    }

    /**
     * Ambil absensi hari ini berdasarkan kartu NFC
     */
    public function getTodayAttendance(
        int $idSekolah,
        int $idKartuNfc,
        string $tanggal
    ): ?array {

        return $this->model
            ->where('id_sekolah', $idSekolah)
            ->where('id_kartu_nfc', $idKartuNfc)
            ->where('tanggal', $tanggal)
            ->first();
    }

    // get data history
    /**
     * Ambil riwayat absensi berdasarkan sekolah
     */
    public function getHistory(
    int $idSekolah,
    int $limit = 50
    ): array {

        return $this->db
            ->table('absensi a')
            ->select('
                a.id,
                a.tanggal,
                a.jam_masuk,
                a.jam_pulang,
                a.status,
                a.jenis,
                a.terlambat_menit,
                a.keterangan,
                a.created_at,

                k.id AS id_kartu_nfc,

                g.nama AS nama_guru,

                s.nama_siswa,
                s.id_kelas,

                kl.nama_kelas
            ')
            ->join(
                'kartu_nfc k',
                'k.id = a.id_kartu_nfc'
            )
            ->join(
                'guru g',
                'g.id = k.id_guru',
                'left'
            )
            ->join(
                'siswa s',
                's.id = k.id_siswa',
                'left'
            )
            ->join(
                'kelas kl',
                'kl.id = s.id_kelas',
                'left'
            )
            ->where(
                'a.id_sekolah',
                $idSekolah
            )
            ->orderBy(
                'a.tanggal',
                'DESC'
            )
            ->orderBy(
                'a.created_at',
                'DESC'
            )
            ->limit($limit)
            ->get()
            ->getResultArray();
    }

    /**
     * Simpan absensi masuk
     */
    public function saveCheckIn(array $data): bool
    {
        return (bool) $this->model->insert($data);
    }

    /**
     * Update absensi pulang
     */
    public function saveCheckOut(
        int $id,
        array $data
    ): bool {

        return $this->model->update($id, $data);
    }

    /**
     * Ambil absensi berdasarkan ID
     */
    public function find(int $id): ?array
    {
        return $this->model->find($id);
    }

    /**
     * Hapus absensi
     */
    public function delete(int $id): bool
    {
        return $this->model->delete($id);
    }
}