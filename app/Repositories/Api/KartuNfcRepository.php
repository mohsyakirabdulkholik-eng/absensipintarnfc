<?php

namespace App\Repositories\Api;

use CodeIgniter\Database\BaseConnection;
use Config\Database;

class KartuNfcRepository
{
    protected BaseConnection $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Cari kartu NFC berdasarkan UID dan sekolah
     *
     * Hanya kartu aktif yang boleh digunakan
     * untuk proses absensi.
     */
    public function findByUid(
        string $uid,
        int $idSekolah
    ): ?array {
        return $this->db->table('kartu_nfc k')
            ->select("
                k.id AS id_kartu_nfc,
                k.uid,
                k.nomor_kartu,
                k.status AS status_kartu,

                g.id AS id_guru,
                g.nama AS nama_guru,
                g.status AS status_guru,
                g.id_sekolah AS sekolah_guru,

                s.id AS id_siswa,
                s.nama_siswa,
                s.status AS status_siswa,
                s.id_sekolah AS sekolah_siswa
            ")
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
            ->where('k.uid', $uid)
            ->where('k.id_sekolah', $idSekolah)
            ->where('k.status', 'aktif')
            ->get()
            ->getRowArray();
    }

    /**
     * Cari kartu berdasarkan UID tanpa memperhatikan status
     *
     * Digunakan untuk memastikan UID tidak terdaftar
     * pada kartu lain, termasuk kartu aktif, nonaktif,
     * maupun hilang.
     */
    public function findAnyByUid(
        string $uid,
        int $idSekolah
    ): ?array {
        return $this->db
            ->table('kartu_nfc')
            ->where('uid', $uid)
            ->where('id_sekolah', $idSekolah)
            ->get()
            ->getRowArray();
    }

    /**
     * Cari kartu aktif berdasarkan guru
     */
    public function findByGuru(
        int $idGuru,
        int $idSekolah
    ): ?array {
        return $this->db->table('kartu_nfc')
            ->where('id_guru', $idGuru)
            ->where('id_sekolah', $idSekolah)
            ->where('status', 'aktif')
            ->get()
            ->getRowArray();
    }

    /**
     * Cari kartu aktif berdasarkan siswa
     */
    public function findBySiswa(
        int $idSiswa,
        int $idSekolah
    ): ?array {
        return $this->db->table('kartu_nfc')
            ->where('id_siswa', $idSiswa)
            ->where('id_sekolah', $idSekolah)
            ->where('status', 'aktif')
            ->get()
            ->getRowArray();
    }

    /**
     * Cari kartu berdasarkan ID
     *
     * Digunakan untuk detail kartu dan
     * proses perubahan status.
     */
    public function findById(
        int $id,
        int $idSekolah
    ): ?array {
        return $this->db->table('kartu_nfc')
            ->where('id', $id)
            ->where('id_sekolah', $idSekolah)
            ->get()
            ->getRowArray();
    }

    /**
     * Ubah status kartu NFC
     *
     * Contoh:
     * aktif     → hilang
     * aktif     → nonaktif
     * nonaktif  → aktif
     *
     * Tidak menghapus record kartu.
     */
    public function updateStatus(
        int $id,
        int $idSekolah,
        string $status
    ): bool {
        return $this->db
            ->table('kartu_nfc')
            ->where('id', $id)
            ->where('id_sekolah', $idSekolah)
            ->update([
                'status' => $status
            ]);
    }

    /**
     * Ganti kartu NFC siswa
     *
     * Proses:
     * 1. Nonaktifkan kartu lama sebagai hilang
     * 2. Simpan kartu baru sebagai aktif
     *
     * Menggunakan transaksi agar kedua proses
     * berhasil atau dibatalkan seluruhnya.
     */
    public function replaceSiswaCard(
        int $idKartuLama,
        int $idSekolah,
        array $dataKartuBaru
    ): bool {
        $this->db->transStart();

        // 1. Tandai kartu lama sebagai hilang
        $this->db
            ->table('kartu_nfc')
            ->where('id', $idKartuLama)
            ->where('id_sekolah', $idSekolah)
            ->update([
                'status' => 'hilang'
            ]);

        // 2. Simpan kartu baru
        $this->db
            ->table('kartu_nfc')
            ->insert($dataKartuBaru);

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /**
     * Simpan kartu NFC
     */
    public function insert(array $data): bool
    {
        return $this->db
            ->table('kartu_nfc')
            ->insert($data);
    }
}