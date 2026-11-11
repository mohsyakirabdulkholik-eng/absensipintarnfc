<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseAdminController;
use App\Services\KehadiranService;

class KehadiranController extends BaseAdminController
{
    protected KehadiranService $kehadiranService;

    public function __construct()
    {
        $this->kehadiranService = new KehadiranService();
    }


    /**
     * Halaman utama kehadiran.
     */
    public function index()
    {
        $data = [
            'title' => 'Kehadiran',
            'user'  => $this->currentUser()
        ];

        return view('kehadiran/index', $data);
    }


    /**
     * Data kehadiran berdasarkan filter.
     */
    public function getData()
    {
        $idSekolah = (int) session('id_sekolah');

        $tanggal = $this->request->getGet('tanggal')
            ?: date('Y-m-d');

        $jenis = strtolower(
            trim((string) $this->request->getGet('jenis'))
        );

        /*
         * Default halaman adalah siswa.
         */
        if ($jenis === '') {
            $jenis = 'siswa';
        }

        /*
         * Jenis hanya boleh siswa atau guru.
         */
        if (!in_array($jenis, ['siswa', 'guru'], true)) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => 'Jenis kehadiran tidak valid.'
                ]);
        }

        /*
         * Validasi format tanggal.
         */
        $date = \DateTime::createFromFormat(
            'Y-m-d',
            $tanggal
        );

        if (
            !$date ||
            $date->format('Y-m-d') !== $tanggal
        ) {
            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'message' => 'Format tanggal tidak valid.'
                ]);
        }


        /*
         * ============================
         * GURU
         * ============================
         */
        if ($jenis === 'guru') {

            $data = $this->kehadiranService->getGuru(
                $idSekolah,
                $tanggal
            );

        /*
         * ============================
         * SISWA
         * ============================
         */
        } else {

            $idKelas = $this->request->getGet('id_kelas');

            $idKelas = (
                $idKelas !== null &&
                $idKelas !== ''
            )
                ? (int) $idKelas
                : null;

            $data = $this->kehadiranService->getSiswa(
                $idSekolah,
                $tanggal,
                $idKelas
            );
        }


        /*
         * Hitung statistik dari data
         * yang sedang ditampilkan.
         */
        $statistik = $this->kehadiranService
            ->getStatistik($data);


        return $this->response->setJSON([
            'success' => true,

            'data' => $data,

            'statistik' => $statistik,

            'filter' => [
                'tanggal'  => $tanggal,
                'jenis'    => $jenis,
                'id_kelas' => $jenis === 'siswa'
                    ? ($idKelas ?? null)
                    : null
            ]
        ]);
    }
    
    public function updateStudentAttendance()
{
    /*
     * Ambil data dari request.
     */
    $idKartuNfc = (int) $this->request->getPost('id_kartu_nfc');
    $tanggal    = trim((string) $this->request->getPost('tanggal'));
    $status     = strtolower(trim((string) $this->request->getPost('status')));
    $keterangan = $this->request->getPost('keterangan');

    /*
     * Validasi data dasar.
     */
    if ($idKartuNfc <= 0) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Kartu NFC siswa tidak ditemukan.'
        ]);
    }

    if ($tanggal === '') {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Tanggal kehadiran wajib diisi.'
        ]);
    }

    /*
     * Validasi format tanggal.
     */
    $date = \DateTime::createFromFormat('Y-m-d', $tanggal);

    if (!$date || $date->format('Y-m-d') !== $tanggal) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Format tanggal tidak valid.'
        ]);
    }

    /*
     * Status manual yang diperbolehkan.
     */
    $allowedStatus = [
        'sakit',
        'izin',
        'alpa'
    ];

    if (!in_array($status, $allowedStatus, true)) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Status kehadiran tidak valid.'
        ]);
    }

    /*
     * Bersihkan keterangan.
     */
    if ($keterangan !== null) {
        $keterangan = trim((string) $keterangan);
    }

    if ($keterangan === '') {
        $keterangan = null;
    }

    /*
     * Ambil ID sekolah dari session.
     *
     * Gunakan cara yang sama seperti method
     * lain di Controller ini jika nama session
     * berbeda pada project kamu.
     */
    $idSekolah = (int) session()->get('id_sekolah');

    if ($idSekolah <= 0) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Data sekolah tidak ditemukan.'
        ]);
    }

    /*
     * Simpan melalui Service.
     */
    $result = $this->kehadiranService->saveStudentManualAttendance(
        $idSekolah,
        $idKartuNfc,
        $tanggal,
        $status,
        $keterangan
    );

    if (!$result) {
        return $this->response->setJSON([
            'success' => false,
            'message' => 'Kehadiran siswa gagal disimpan.'
        ]);
    }

    return $this->response->setJSON([
        'success' => true,
        'message' => 'Kehadiran siswa berhasil diperbarui.'
    ]);
}

    /**
     * Batalkan absensi pulang guru.
     */
    public function cancelGuruCheckout()
    {
        $idSekolah = (int) session('id_sekolah');

        $idAbsensi = (int) $this->request
            ->getPost('id_absensi');

        /*
        * Validasi ID.
        */
        if ($idAbsensi <= 0) {

            return $this->response
                ->setStatusCode(400)
                ->setJSON([
                    'success' => false,
                    'code'    => 'INVALID_ATTENDANCE_ID',
                    'message' => 'ID absensi tidak valid.'
                ]);
        }

        $result = $this->kehadiranService
            ->cancelGuruCheckout(
                $idSekolah,
                $idAbsensi
            );

        /*
        * Data tidak ditemukan / bukan milik sekolah.
        */
        if (
            !$result['success'] &&
            $result['code'] === 'ATTENDANCE_NOT_FOUND'
        ) {

            return $this->response
                ->setStatusCode(404)
                ->setJSON($result);
        }

        /*
        * Request valid tetapi tidak dapat dibatalkan.
        */
        if (!$result['success']) {

            return $this->response
                ->setStatusCode(400)
                ->setJSON($result);
        }

        return $this->response->setJSON($result);
    }
}