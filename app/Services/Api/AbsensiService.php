<?php

namespace App\Services\Api;

use App\Repositories\Api\AbsensiRepository;
use App\Services\HariLiburService;
use App\Services\PengaturanAbsensiService;
use App\Services\SemesterService;
use App\Services\TahunAjaranService;

class AbsensiService
{
    /**
     * Repository
     */
    protected AbsensiRepository $repository;

    /**
     * API Services
     */
    protected DeviceService $deviceService;
    protected NfcService $nfcService;

    /**
     * Master Services
     */
    protected HariLiburService $hariLiburService;
    protected TahunAjaranService $tahunAjaranService;
    protected SemesterService $semesterService;
    protected PengaturanAbsensiService $pengaturanService;

    public function __construct()
    {
        $this->repository = new AbsensiRepository();

        $this->deviceService = new DeviceService();
        $this->nfcService = new NfcService();

        $this->hariLiburService = new HariLiburService();
        $this->tahunAjaranService = new TahunAjaranService();
        $this->semesterService = new SemesterService();
        $this->pengaturanService = new PengaturanAbsensiService();
    }

    /**
     * Proses Absensi
     */
    public function attendance(string $token, string $uid): array
    {
        // 1. Validasi Device & NFC
        $nfc = $this->nfcService->read($token, $uid);
        
        if (!$nfc['success']) {
            return $nfc;
        }

        $card = $nfc['data'];

        // ambil device id atau id perangkat
        $device = $this->deviceService->validateDevice($token);

        if (!$device['success']) {
            return $device;
        }

        $deviceData = $device['data'];

        // 2. Validasi Tahun Ajaran & Semester
        $academic = $this->validateAcademic(
            $card['id_sekolah']
        );

        if (!$academic['success']) {
            return $academic;
        }

        $academicData = $academic['data'];

        // 3. Validasi Hari Libur
        $holiday = $this->validateHoliday(
            $card['id_sekolah']
        );

        if (!$holiday['success']) {
            return $holiday;
        }

        // 4. Ambil Pengaturan Absensi
        $setting = $this->validateSetting(
            $card['id_sekolah']
        );

        if (!$setting['success']) {
            return $setting;
        }
        $settingData = $setting['data'];

        $tanggal = date('Y-m-d');
        $jam = date('H:i:s');
        // 5. Proses Check In / Check Out
       return $this->processAttendance(
                $card,
                $academicData,
                $settingData,
                $deviceData,
                $tanggal,
                $jam
            );
    }

    /**
     * Validasi Tahun Ajaran & Semester
     */
    private function validateAcademic(int $idSekolah): array
    {
        $tahun = $this->tahunAjaranService->getAktif($idSekolah);

        if (!$tahun) {
            return $this->buildResponse(
                false,
                'ACADEMIC_YEAR_NOT_FOUND',
                'Tahun ajaran aktif belum tersedia.'
            );
        }

        $semester = $this->semesterService->getAktif($idSekolah);

        if (!$semester) {
            return $this->buildResponse(
                false,
                'SEMESTER_NOT_FOUND',
                'Semester aktif belum tersedia.'
            );
        }

        return $this->buildResponse(
            true,
            'ACADEMIC_READY',
            'Tahun ajaran dan semester aktif siap digunakan.',
            [
                'id_tahun'      => $tahun['id'],
                'nama_tahun'    => $tahun['nama'],
                'id_semester'   => $semester['id'],
                'nama_semester' => $semester['nama']
            ]
        );
    }

    /**
     * Validasi Hari Libur
     */
    private function validateHoliday(int $idSekolah): array
    {
        $tanggal = date('Y-m-d');

        $holiday = $this->hariLiburService->isHoliday(
            $idSekolah,
            $tanggal
        );

        if ($holiday) {
            return $this->buildResponse(
                false,
                'HOLIDAY',
                'Hari ini adalah hari libur.',
                [
                    'tanggal' => $holiday['tanggal'],
                    'nama_libur' => $holiday['nama_libur']
                ]
            );
        }

        return $this->buildResponse(
            true,
            'NOT_HOLIDAY',
            'Hari ini bukan hari libur.'
        );
    }
    /**
     * Ambil Pengaturan Aktif
     */
    private function validateSetting(
        int $idSekolah
    ): array
    {
        $setting = $this->pengaturanService
            ->getAktif($idSekolah);

        if (!$setting) {

            return $this->buildResponse(
                false,
                'SETTING_NOT_FOUND',
                'Pengaturan absensi belum tersedia.'
            );

        }

        return $this->buildResponse(
            true,
            'SETTING_READY',
            'Pengaturan absensi siap digunakan.',
            [
                'jam_masuk'         => $setting['jam_masuk'],
                'batas_terlambat'   => $setting['batas_terlambat'],
                'jam_pulang'        => $setting['jam_pulang'],
                'toleransi_pulang'  => $setting['toleransi_pulang']
            ]
        );
    }
    /**
     * Proses Check In / Check Out
     */
    private function processAttendance(
        array $card,
        array $academic,
        array $setting,
        array $device,
        string $tanggal,
        string $jam
    ): array
    {
         $attendance = $this->repository->getTodayAttendance(
            $card['id_sekolah'],
            $card['id_kartu_nfc'],
            $tanggal
            );

            // Belum ada absensi hari ini
            if (!$attendance) {
                return $this->checkIn(
                    $card,
                    $academic,
                    $setting,
                    $device,
                    $tanggal,
                    $jam
                );
            }

            // Sudah check in, tetapi belum check out
            if (empty($attendance['jam_pulang'])) {
                return $this->checkOut(
                    $attendance,
                    $setting,
                    $jam
                );
            }

            // Sudah check in dan check out
            return $this->buildResponse(
                false,
                'ATTENDANCE_COMPLETED',
                'Absensi hari ini sudah selesai.'
            );
    }

    /**
     * Simpan Check In
     */
    private function checkIn(
        array $card,
        array $academic,
        array $setting,
        array $device,
        string $tanggal,
        string $jam
    ): array
    {
        // Belum memasuki jam absensi
        if ($jam < $setting['jam_masuk']) {
            return $this->buildResponse(
                false,
                'TOO_EARLY_CHECKIN',
                'Belum memasuki jam absensi.'
            );
        }

        // Hitung keterlambatan
        $late = $this->calculateLate(
            $jam,
            $setting['batas_terlambat']
        );

        // Data absensi
        $data = [
            'id_sekolah'        => $card['id_sekolah'],
            'jenis'             => $card['type'],
            'id_kartu_nfc'      => $card['id_kartu_nfc'],
            'tanggal'           => $tanggal,
            'jam_masuk'         => $jam,
            'status'            => $late['status'],
            'terlambat_menit'   => $late['terlambat_menit'],
            'keterangan'        => null,
            'device_id'         => $device['id']
        ];

        // Simpan
        if (!$this->repository->saveCheckIn($data)) {
            return $this->buildResponse(
                false,
                'CHECKIN_FAILED',
                'Absensi masuk gagal disimpan.'
            );
        }

        return $this->buildResponse(
            true,
            'CHECKIN_SUCCESS',
            'Absensi masuk berhasil.',
            [
                'nama'               => $card['nama'],
                'jenis'              => $card['type'],
                'tanggal'            => $tanggal,
                'jam_masuk'          => $jam,
                'status'             => $late['status'],
                'terlambat_menit'    => $late['terlambat_menit']
            ]
        );
    }

    /**
     * Simpan Check Out
     */
    private function checkOut(
        array $attendance,
        array $setting,
        string $jam
    ): array
    {
        // Belum waktunya pulang
        if ($jam < $setting['toleransi_pulang']) {
            return $this->buildResponse(
                false,
                'TOO_EARLY_CHECKOUT',
                'Belum waktunya melakukan absensi pulang.'
            );
        }

        $update = [
            'jam_pulang' => $jam
        ];

        if (!$this->repository->saveCheckOut(
            $attendance['id'],
            $update
        )) {
            return $this->buildResponse(
                false,
                'CHECKOUT_FAILED',
                'Absensi pulang gagal disimpan.'
            );
        }

        return $this->buildResponse(
            true,
            'CHECKOUT_SUCCESS',
            'Absensi pulang berhasil.',
            [
                'jam_pulang' => $jam
            ]
        );
    }

    /**
    
    * Hitung keterlambatan
    */
    private function calculateLate(
        string $jamMasuk,
        string $batasTerlambat
    ): array
    {
        $jamMasukTime = strtotime($jamMasuk);
        $batasTime = strtotime($batasTerlambat);

        // Tidak terlambat
        if ($jamMasukTime <= $batasTime) {
            return [
                'status' => 'Hadir',
                'terlambat_menit' => 0
            ];
        }

        // Hitung selisih menit
        $selisihMenit = floor(
            ($jamMasukTime - $batasTime) / 60
        );

        return [
            'status' => 'Terlambat',
            'terlambat_menit' => $selisihMenit
        ];
    }

    /**
     * Standard Response
     */
    private function buildResponse(
        bool $success,
        string $code,
        string $message,
        array $data = []
    ): array
    {
        return [
            'success' => $success,
            'code'    => $code,
            'message' => $message,
            'data'    => $data
        ];
    }
}