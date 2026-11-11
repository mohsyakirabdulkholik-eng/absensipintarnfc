<?php

namespace App\Services\Api;

use App\Repositories\GuruRepository;
use App\Repositories\SiswaRepository;
use App\Repositories\Api\KartuNfcRepository;

class NfcService
{
    protected DeviceService $deviceService;
    protected KartuNfcRepository $kartuRepository;
    protected GuruRepository $guruRepository;
    protected SiswaRepository $siswaRepository;

    public function __construct()
    {
        $this->deviceService   = new DeviceService();
        $this->kartuRepository = new KartuNfcRepository();
        $this->guruRepository  = new GuruRepository();
        $this->siswaRepository = new SiswaRepository();
    }

    /**
     * Membaca kartu NFC
     */
    public function read(string $token, string $uid): array
    {
        /*
         * Validasi UID
         */
        if (empty($uid)) {
            return [
                'success' => false,
                'code'    => 'UID_REQUIRED',
                'message' => 'UID wajib diisi.'
            ];
        }

        /*
         * Validasi Device
         */
        $device = $this->deviceService->validateDevice($token);

        if (!$device['success']) {
            return $device;
        }

        /*
         * Cari kartu berdasarkan UID dan Sekolah
         */
        $card = $this->kartuRepository->findByUid(
            $uid,
            $device['data']['id_sekolah']
        );

        if (!$card) {
            return [
                'success' => false,
                'code'    => 'UID_NOT_FOUND',
                'message' => 'UID tidak terdaftar.'
            ];
        }

        /*
         * Kartu Guru
         */
        if (!empty($card['id_guru'])) {

            if ($card['status_guru'] !== 'aktif') {
                return [
                    'success' => false,
                    'code'    => 'GURU_INACTIVE',
                    'message' => 'Guru tidak aktif.'
                ];
            }

            return [

                    'success' => true,

                    'code' => 'CARD_FOUND',

                    'message' => 'Kartu guru berhasil dikenali.',

                    'data' => [

                        'type' => 'guru',

                        'id' => $card['id_guru'],

                        'nama' => $card['nama_guru'],

                        'uid' => $card['uid'],

                        'id_kartu_nfc' => $card['id_kartu_nfc'],

                        'id_sekolah' => $card['sekolah_guru'],

                        'nomor_kartu' => $card['nomor_kartu']

                    ]

                ];
        }

        /*
         * Kartu Siswa
         */
        if (!empty($card['id_siswa'])) {

            if ($card['status_siswa'] !== 'aktif') {
                return [
                    'success' => false,
                    'code'    => 'SISWA_INACTIVE',
                    'message' => 'Siswa tidak aktif.'
                ];
            }

            return [

                        'success' => true,

                        'code' => 'CARD_FOUND',

                        'message' => 'Kartu siswa berhasil dikenali.',

                        'data' => [

                            'type' => 'siswa',

                            'id' => $card['id_siswa'],

                            'nama' => $card['nama_siswa'],

                            'uid' => $card['uid'],

                            'id_kartu_nfc' => $card['id_kartu_nfc'],

                            'id_sekolah' => $card['sekolah_siswa'],

                            'nomor_kartu' => $card['nomor_kartu']

                        ]

                    ];
        }

        return [
            'success' => false,
            'code'    => 'CARD_NO_OWNER',
            'message' => 'Kartu belum memiliki pemilik.'
        ];
    }
    /**
     * Mengambil daftar guru yang belum memiliki kartu NFC
     */
    public function getGuruBelumPunyaKartu(string $token): array
    {
        $device = $this->deviceService->validateDevice($token);

        if (!$device['success']) {
            return $device;
        }

        $data = $this->guruRepository->getBelumPunyaKartu(
            $device['data']['id_sekolah']
        );

        return [
            'success' => true,
            'code'    => 'SUCCESS',
            'message' => 'Data guru berhasil diambil.',
            'data'    => $data
        ];
    }
    /**
     * Mengambil daftar siswa yang belum memiliki kartu NFC
     */
    public function getSiswaBelumPunyaKartu(string $token): array
    {
        $device = $this->deviceService->validateDevice($token);

        if (!$device['success']) {
            return $device;
        }

        $data = $this->siswaRepository->getBelumPunyaKartu(
            $device['data']['id_sekolah']
        );

        return [
            'success' => true,
            'code'    => 'SUCCESS',
            'message' => 'Data siswa berhasil diambil.',
            'data'    => $data
        ];
    }

    /**
     * Registrasi kartu NFC untuk Guru
     */
    public function registerGuru(
        string $token,
        string $uid,
        int $idGuru
    ): array {

        /*
        * 1. Validasi UID
        */
        if (empty($uid)) {
            return [
                'success' => false,
                'code'    => 'UID_REQUIRED',
                'message' => 'UID kartu NFC wajib diisi.'
            ];
        }

        /*
        * 2. Validasi Device
        */
        $device = $this->deviceService->validateDevice($token);

        if (!$device['success']) {
            return $device;
        }

        $idSekolah = $device['data']['id_sekolah'];

        /*
        * 3. Cari Guru
        */
        $guru = $this->guruRepository->find($idGuru);

        if (!$guru) {
            return [
                'success' => false,
                'code'    => 'GURU_NOT_FOUND',
                'message' => 'Data guru tidak ditemukan.'
            ];
        }

        /*
        * 4. Pastikan Guru berasal dari sekolah yang sama
        */
        if ((int) $guru['id_sekolah'] !== (int) $idSekolah) {
            return [
                'success' => false,
                'code'    => 'GURU_INVALID_SCHOOL',
                'message' => 'Guru tidak terdaftar pada sekolah perangkat ini.'
            ];
        }

        /*
        * 5. Pastikan Guru aktif
        */
        if ($guru['status'] !== 'aktif') {
            return [
                'success' => false,
                'code'    => 'GURU_INACTIVE',
                'message' => 'Guru tidak aktif.'
            ];
        }

        /*
        * 6. Cek UID sudah terdaftar atau belum
        */
        $existingUid = $this->kartuRepository->findByAnyUid(
            $uid,
            $idSekolah
        );

        if ($existingUid) {
            return [
                'success' => false,
                'code'    => 'UID_ALREADY_REGISTERED',
                'message' => 'Kartu NFC sudah terdaftar.'
            ];
        }

        /*
        * 7. Cek Guru sudah memiliki kartu aktif atau belum
        */
        $existingCard = $this->kartuRepository->findAnyByGuru(
            $idGuru,
            $idSekolah
        );

        if ($existingCard) {
            return [
                'success' => false,
                'code'    => 'GURU_ALREADY_HAS_CARD',
                'message' => 'Guru sudah memiliki kartu NFC aktif.'
            ];
        }

        /*
        * 8. Generate nomor kartu
        */
        $nomorKartu = 'GURU-' . $idSekolah . '-' . $idGuru . '-' . date('YmdHis');

        /*
        * 9. Simpan kartu
        */
        $saved = $this->kartuRepository->insert([
            'uid'           => $uid,
            'id_sekolah'    => $idSekolah,
            'id_guru'       => $idGuru,
            'id_siswa'      => null,
            'nomor_kartu'   => $nomorKartu,
            'status'         => 'aktif',
            'tanggal_daftar'=> date('Y-m-d H:i:s')
        ]);

        if (!$saved) {
            return [
                'success' => false,
                'code'    => 'REGISTER_FAILED',
                'message' => 'Registrasi kartu NFC gagal.'
            ];
        }

        /*
        * 10. Registrasi berhasil
        */
        return [
            'success' => true,
            'code'    => 'REGISTER_SUCCESS',
            'message' => 'Kartu NFC guru berhasil didaftarkan.',
            'data'    => [
                'id_guru'      => $idGuru,
                'nama'         => $guru['nama'],
                'uid'          => $uid,
                'nomor_kartu'  => $nomorKartu
            ]
        ];
    }

    /**
     * Registrasi kartu NFC untuk Siswa
     */
    public function registerSiswa(
        string $token,
        string $uid,
        int $idSiswa
    ): array {

        /*
        * 1. Validasi UID
        */
        if (empty($uid)) {
            return [
                'success' => false,
                'code'    => 'UID_REQUIRED',
                'message' => 'UID kartu NFC wajib diisi.'
            ];
        }

        /*
        * 2. Validasi Device
        */
        $device = $this->deviceService->validateDevice($token);

        if (!$device['success']) {
            return $device;
        }

        $idSekolah = $device['data']['id_sekolah'];

        /*
        * 3. Cari Siswa
        */
        $siswa = $this->siswaRepository->find($idSiswa);

        if (!$siswa) {
            return [
                'success' => false,
                'code'    => 'SISWA_NOT_FOUND',
                'message' => 'Data siswa tidak ditemukan.'
            ];
        }

        /*
        * 4. Pastikan Siswa berasal dari sekolah yang sama
        */
        if ((int) $siswa['id_sekolah'] !== (int) $idSekolah) {
            return [
                'success' => false,
                'code'    => 'SISWA_INVALID_SCHOOL',
                'message' => 'Siswa tidak terdaftar pada sekolah perangkat ini.'
            ];
        }

        /*
        * 5. Pastikan Siswa aktif
        */
        if ($siswa['status'] !== 'aktif') {
            return [
                'success' => false,
                'code'    => 'SISWA_INACTIVE',
                'message' => 'Siswa tidak aktif.'
            ];
        }

        /*
        * 6. Cek UID sudah terdaftar atau belum
        */
        $existingUid = $this->kartuRepository->findAnyByUid(
            $uid,
            $idSekolah
        );

        if ($existingUid) {
            return [
                'success' => false,
                'code'    => 'UID_ALREADY_REGISTERED',
                'message' => 'Kartu NFC sudah terdaftar.'
            ];
        }

        /*
        * 7. Cek Siswa sudah memiliki kartu aktif
        */
        $existingCard = $this->kartuRepository->findBySiswa(
            $idSiswa,
            $idSekolah
        );

        if ($existingCard) {
            return [
                'success' => false,
                'code'    => 'SISWA_ALREADY_HAS_CARD',
                'message' => 'Siswa sudah memiliki kartu NFC aktif.'
            ];
        }

        /*
        * 8. Generate nomor kartu
        */
        $nomorKartu = 'SISWA-' . $idSekolah . '-' . $idSiswa . '-' . date('YmdHis');

        /*
        * 9. Simpan kartu
        */
        $saved = $this->kartuRepository->insert([
            'uid'            => $uid,
            'id_sekolah'     => $idSekolah,
            'id_guru'        => null,
            'id_siswa'       => $idSiswa,
            'nomor_kartu'    => $nomorKartu,
            'status'          => 'aktif',
            'tanggal_daftar' => date('Y-m-d H:i:s')
        ]);

        if (!$saved) {
            return [
                'success' => false,
                'code'    => 'REGISTER_FAILED',
                'message' => 'Registrasi kartu NFC gagal.'
            ];
        }

        /*
        * 10. Registrasi berhasil
        */
        return [
            'success' => true,
            'code'    => 'REGISTER_SUCCESS',
            'message' => 'Kartu NFC siswa berhasil didaftarkan.',
            'data'    => [
                'id_siswa'     => $idSiswa,
                'nama'         => $siswa['nama_siswa'],
                'uid'          => $uid,
                'nomor_kartu'  => $nomorKartu
            ]
        ];
    }


    /**
     * =========================================
     * GANTI KARTU NFC SISWA
     * =========================================
     *
     * Kartu lama tidak dihapus.
     * Kartu lama diubah menjadi status "hilang".
     * Kartu baru dibuat dengan status "aktif".
     */
    public function replaceSiswaCard(
        string $token,
        int $idKartuLama,
        string $uidBaru
    ): array {

        /*
        * 1. Validasi UID baru
        */
        if (empty($uidBaru)) {
            return [
                'success' => false,
                'code'    => 'UID_REQUIRED',
                'message' => 'UID kartu NFC baru wajib diisi.'
            ];
        }

        /*
        * 2. Validasi Device
        */
        $device = $this->deviceService->validateDevice($token);

        if (!$device['success']) {
            return $device;
        }

        $idSekolah = (int) $device['data']['id_sekolah'];

        /*
        * 3. Cari kartu lama
        */
        $kartuLama = $this->kartuRepository->findById(
            $idKartuLama,
            $idSekolah
        );

        if (!$kartuLama) {
            return [
                'success' => false,
                'code'    => 'CARD_NOT_FOUND',
                'message' => 'Kartu NFC lama tidak ditemukan.'
            ];
        }

        /*
        * 4. Pastikan kartu lama adalah kartu siswa
        */
        if (empty($kartuLama['id_siswa'])) {
            return [
                'success' => false,
                'code'    => 'CARD_NOT_STUDENT',
                'message' => 'Kartu yang dipilih bukan kartu siswa.'
            ];
        }

        /*
        * 5. Pastikan kartu lama memang berstatus hilang
        */
        if ($kartuLama['status'] !== 'hilang') {
            return [
                'success' => false,
                'code'    => 'CARD_NOT_LOST',
                'message' => 'Kartu lama harus berstatus hilang.'
            ];
        }

        /*
        * 6. Cari data siswa
        */
        $idSiswa = (int) $kartuLama['id_siswa'];

        $siswa = $this->siswaRepository->find($idSiswa);

        if (!$siswa) {
            return [
                'success' => false,
                'code'    => 'SISWA_NOT_FOUND',
                'message' => 'Data siswa tidak ditemukan.'
            ];
        }

        /*
        * 7. Pastikan siswa berasal dari sekolah yang sama
        */
        if ((int) $siswa['id_sekolah'] !== $idSekolah) {
            return [
                'success' => false,
                'code'    => 'SISWA_INVALID_SCHOOL',
                'message' => 'Siswa tidak terdaftar pada sekolah perangkat ini.'
            ];
        }

        /*
        * 8. Pastikan siswa masih aktif
        */
        if ($siswa['status'] !== 'aktif') {
            return [
                'success' => false,
                'code'    => 'SISWA_INACTIVE',
                'message' => 'Siswa tidak aktif.'
            ];
        }

        /*
        * 9. Pastikan UID baru belum digunakan
        */
        $existingUid = $this->kartuRepository->findAnyByUid(
            $uidBaru,
            $idSekolah
        );

        if ($existingUid) {
            return [
                'success' => false,
                'code'    => 'UID_ALREADY_REGISTERED',
                'message' => 'Kartu NFC baru sudah terdaftar.'
            ];
        }

        /*
        * 10. Generate nomor kartu baru
        */
        $nomorKartu = 'SISWA-' .
            $idSekolah . '-' .
            $idSiswa . '-' .
            date('YmdHis');

        /*
        * 11. Data kartu baru
        */
        $dataKartuBaru = [
            'uid'            => $uidBaru,
            'id_sekolah'     => $idSekolah,
            'id_guru'        => null,
            'id_siswa'       => $idSiswa,
            'nomor_kartu'    => $nomorKartu,
            'status'         => 'aktif',
            'tanggal_daftar' => date('Y-m-d H:i:s')
        ];

        /*
        * 12. Ganti kartu menggunakan transaksi
        */
        $replaced = $this->kartuRepository->replaceSiswaCard(
            $idKartuLama,
            $idSekolah,
            $dataKartuBaru
        );

        if (!$replaced) {
            return [
                'success' => false,
                'code'    => 'REPLACE_CARD_FAILED',
                'message' => 'Gagal mengganti kartu NFC siswa.'
            ];
        }

        /*
        * 13. Berhasil
        */
        return [
            'success' => true,
            'code'    => 'REPLACE_CARD_SUCCESS',
            'message' => 'Kartu NFC siswa berhasil diganti.',
            'data'    => [
                'id_siswa'          => $idSiswa,
                'nama'              => $siswa['nama_siswa'],
                'id_kartu_lama'     => $idKartuLama,
                'uid_kartu_lama'    => $kartuLama['uid'],
                'uid_kartu_baru'    => $uidBaru,
                'nomor_kartu_baru'  => $nomorKartu
            ]
        ];
    }

}