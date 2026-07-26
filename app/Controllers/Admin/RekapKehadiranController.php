<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseAdminController;
use App\Services\RekapKehadiranService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class RekapKehadiranController extends BaseAdminController
{
    protected RekapKehadiranService $rekapService;

    public function __construct()
    {
        $this->rekapService = new RekapKehadiranService();
    }


    /**
     * Halaman utama Rekap Kehadiran.
     */
    public function index()
    {
        $data = [
            'title' => 'Rekap Kehadiran',
            'user'  => $this->currentUser()
        ];

        return view('rekap_kehadiran/index', $data);
    }


    /**
     * Ambil data rekap berdasarkan:
     *
     * - jenis
     * - bulan
     * - tahun
     * - kelas (khusus siswa)
     */
    public function getData()
    {
        try {

            $idSekolah = (int) session('id_sekolah');

            /*
             * ==========================
             * FILTER JENIS
             * ==========================
             */
            $jenis = strtolower(
                trim(
                    (string) $this->request->getGet('jenis')
                )
            );

            if ($jenis === '') {
                $jenis = 'siswa';
            }

            if (
                !in_array(
                    $jenis,
                    ['siswa', 'guru'],
                    true
                )
            ) {
                return $this->response
                    ->setStatusCode(400)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Jenis rekap tidak valid.'
                    ]);
            }


            /*
             * ==========================
             * FILTER BULAN & TAHUN
             * ==========================
             */
            $bulan = (int) (
                $this->request->getGet('bulan')
                ?: date('n')
            );

            $tahun = (int) (
                $this->request->getGet('tahun')
                ?: date('Y')
            );

            if ($bulan < 1 || $bulan > 12) {
                return $this->response
                    ->setStatusCode(400)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Bulan tidak valid.'
                    ]);
            }

            if ($tahun < 2000 || $tahun > 2100) {
                return $this->response
                    ->setStatusCode(400)
                    ->setJSON([
                        'success' => false,
                        'message' => 'Tahun tidak valid.'
                    ]);
            }


            /*
             * ==========================
             * REKAP GURU
             * ==========================
             */
            if ($jenis === 'guru') {

                $result = $this->rekapService->getGuru(
                    $idSekolah,
                    $tahun,
                    $bulan
                );

                $idKelas = null;


            /*
             * ==========================
             * REKAP SISWA
             * ==========================
             */
            } else {

                $idKelas =
                    $this->request->getGet('id_kelas');

                $idKelas = (
                    $idKelas !== null &&
                    $idKelas !== ''
                )
                    ? (int) $idKelas
                    : null;


                $result = $this->rekapService->getSiswa(
                    $idSekolah,
                    $tahun,
                    $bulan,
                    $idKelas
                );
            }


            /*
             * ==========================
             * RESPONSE
             * ==========================
             */
            return $this->response->setJSON([
                'success' => true,

                'periode' =>
                    $result['periode'],

                'statistik' =>
                    $result['statistik'],

                'data' =>
                    $result['data'],

                'filter' => [
                    'jenis'    => $jenis,
                    'bulan'    => $bulan,
                    'tahun'    => $tahun,
                    'id_kelas' => $idKelas
                ]
            ]);

        } catch (\Throwable $e) {

            log_message(
                'error',
                'Rekap Kehadiran: {message}',
                [
                    'message' => $e->getMessage()
                ]
            );

            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' =>
                        'Gagal mengambil data rekap kehadiran.'
                ]);
        }
    }
    /**
 * Detail rekap satu guru / siswa.
 */
    public function detail()
    {
        try {

            $idSekolah =
                (int) session('id_sekolah');

            $jenis = strtolower(
                trim(
                    (string) $this->request->getGet('jenis')
                )
            );

            $id =
                (int) $this->request->getGet('id');

            $bulan =
                (int) $this->request->getGet('bulan');

            $tahun =
                (int) $this->request->getGet('tahun');


            if (
                !in_array(
                    $jenis,
                    ['siswa', 'guru'],
                    true
                )
            ) {
                return $this->response
                    ->setStatusCode(400)
                    ->setJSON([
                        'success' => false,
                        'message' =>
                            'Jenis rekap tidak valid.'
                    ]);
            }


            if ($id <= 0) {

                return $this->response
                    ->setStatusCode(400)
                    ->setJSON([
                        'success' => false,
                        'message' =>
                            'Data tidak valid.'
                    ]);
            }


            if ($bulan < 1 || $bulan > 12) {

                return $this->response
                    ->setStatusCode(400)
                    ->setJSON([
                        'success' => false,
                        'message' =>
                            'Bulan tidak valid.'
                    ]);
            }


            if ($tahun < 2000 || $tahun > 2100) {

                return $this->response
                    ->setStatusCode(400)
                    ->setJSON([
                        'success' => false,
                        'message' =>
                            'Tahun tidak valid.'
                    ]);
            }


            $result =
                $this->rekapService->getDetail(
                    $idSekolah,
                    $jenis,
                    $id,
                    $tahun,
                    $bulan
                );


            return $this->response->setJSON([
                'success' => true,

                'identitas' =>
                    $result['identitas'],

                'periode' =>
                    $result['periode'],

                'detail' =>
                    $result['detail']
            ]);

        } catch (\Throwable $e) {

            log_message(
                'error',
                'Detail Rekap Kehadiran: {message}',
                [
                    'message' => $e->getMessage()
                ]
            );

            return $this->response
                ->setStatusCode(500)
                ->setJSON([
                    'success' => false,
                    'message' =>
                        'Gagal mengambil detail rekap.'
                ]);
        }
    }

    /**
     * Export Rekap Kehadiran Excel
     */
    public function exportExcel()
    {
        try {

            $idSekolah = (int) session('id_sekolah');

            $jenis = strtolower(
                trim(
                    (string) $this->request->getGet('jenis')
                )
            );

            $bulan = (int) $this->request->getGet('bulan');
            $tahun = (int) $this->request->getGet('tahun');

            $idKelas = $this->request->getGet('id_kelas');

            $idKelas = (
                $idKelas !== null &&
                $idKelas !== ''
            )
                ? (int) $idKelas
                : null;


            /*
            * ==========================
            * VALIDASI
            * ==========================
            */
            if (!in_array($jenis, ['siswa', 'guru'], true)) {
                throw new \InvalidArgumentException(
                    'Jenis rekap tidak valid.'
                );
            }

            if ($bulan < 1 || $bulan > 12) {
                throw new \InvalidArgumentException(
                    'Bulan tidak valid.'
                );
            }

            if ($tahun < 2000 || $tahun > 2100) {
                throw new \InvalidArgumentException(
                    'Tahun tidak valid.'
                );
            }


            /*
            * ==========================
            * AMBIL REKAP
            * ==========================
            */
            if ($jenis === 'siswa') {

                $result = $this->rekapService->getSiswa(
                    $idSekolah,
                    $tahun,
                    $bulan,
                    $idKelas
                );

            } else {

                $result = $this->rekapService->getGuru(
                    $idSekolah,
                    $tahun,
                    $bulan
                );
            }


            $rekap = $result['data'] ?? [];
            $periode = $result['periode'] ?? [];
            $statistik = $result['statistik'] ?? [];


            /*
            * ==========================
            * NAMA BULAN
            * ==========================
            */
            $namaBulan = [
                1  => 'Januari',
                2  => 'Februari',
                3  => 'Maret',
                4  => 'April',
                5  => 'Mei',
                6  => 'Juni',
                7  => 'Juli',
                8  => 'Agustus',
                9  => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember'
            ];


            /*
            * ==========================
            * SPREADSHEET
            * ==========================
            */
            $spreadsheet = new Spreadsheet();

            $sheet = $spreadsheet->getActiveSheet();

            $sheet->setTitle('Rekap Kehadiran');


            /*
            * Jumlah kolom berbeda antara
            * siswa dan guru.
            */
            $lastColumn = $jenis === 'siswa'
                ? 'I'
                : 'H';


            /*
            * ==========================
            * JUDUL
            * ==========================
            */
            $judul =
                'REKAP KEHADIRAN ' .
                strtoupper($jenis);

            $sheet->setCellValue(
                'A1',
                $judul
            );

            $sheet->mergeCells(
                'A1:' . $lastColumn . '1'
            );


            $sheet->setCellValue(
                'A2',
                'Periode: ' .
                $namaBulan[$bulan] .
                ' ' .
                $tahun
            );

            $sheet->mergeCells(
                'A2:' . $lastColumn . '2'
            );


            /*
            * ==========================
            * STATISTIK
            * ==========================
            */
            $sheet->setCellValue(
                'A3',
                'Hari Efektif: ' .
                ($periode['hari_efektif'] ?? 0)
            );

            $sheet->setCellValue(
                'C3',
                'Total: ' .
                ($statistik['total_orang'] ?? 0)
            );

            $sheet->setCellValue(
                'E3',
                'Kehadiran: ' .
                number_format(
                    (float) ($statistik['persentase'] ?? 0),
                    2
                ) .
                '%'
            );


            /*
            * ==========================
            * HEADER
            * ==========================
            */
            $headerRow = 5;

            if ($jenis === 'siswa') {

                $headers = [
                    'No',
                    'NIS',
                    'Nama Siswa',
                    'Kelas',
                    'Hari Efektif',
                    'Hadir',
                    'Terlambat',
                    'Tidak Hadir',
                    'Persentase'
                ];

            } else {

                $headers = [
                    'No',
                    'NIP',
                    'Nama Guru',
                    'Hari Efektif',
                    'Hadir',
                    'Terlambat',
                    'Tidak Hadir',
                    'Persentase'
                ];
            }


            $column = 'A';

            foreach ($headers as $header) {

                $sheet->setCellValue(
                    $column . $headerRow,
                    $header
                );

                $column++;
            }


            /*
            * ==========================
            * ISI DATA
            * ==========================
            */
            $row = 6;
            $no = 1;

            foreach ($rekap as $item) {

                $sheet->setCellValue(
                    'A' . $row,
                    $no
                );


                /*
                * NIS / NIP dibuat string
                * agar angka nol di depan
                * tidak hilang.
                */
                $sheet->setCellValueExplicit(
                    'B' . $row,
                    (string) ($item['identitas'] ?? '-'),
                    DataType::TYPE_STRING
                );


                $sheet->setCellValue(
                    'C' . $row,
                    $item['nama'] ?? '-'
                );


                if ($jenis === 'siswa') {

                    $sheet->setCellValue(
                        'D' . $row,
                        $item['nama_kelas'] ?? '-'
                    );

                    $sheet->setCellValue(
                        'E' . $row,
                        $item['hari_efektif'] ?? 0
                    );

                    $sheet->setCellValue(
                        'F' . $row,
                        $item['hadir'] ?? 0
                    );

                    $sheet->setCellValue(
                        'G' . $row,
                        $item['terlambat'] ?? 0
                    );

                    $sheet->setCellValue(
                        'H' . $row,
                        $item['tidak_hadir'] ?? 0
                    );

                    $sheet->setCellValue(
                        'I' . $row,
                        ((float) ($item['persentase'] ?? 0)) / 100
                    );

                } else {

                    $sheet->setCellValue(
                        'D' . $row,
                        $item['hari_efektif'] ?? 0
                    );

                    $sheet->setCellValue(
                        'E' . $row,
                        $item['hadir'] ?? 0
                    );

                    $sheet->setCellValue(
                        'F' . $row,
                        $item['terlambat'] ?? 0
                    );

                    $sheet->setCellValue(
                        'G' . $row,
                        $item['tidak_hadir'] ?? 0
                    );

                    $sheet->setCellValue(
                        'H' . $row,
                        ((float) ($item['persentase'] ?? 0)) / 100
                    );
                }


                $row++;
                $no++;
            }


            /*
            * ==========================
            * FORMAT PERSENTASE
            * ==========================
            */
            $persenColumn =
                $jenis === 'siswa'
                    ? 'I'
                    : 'H';

            if ($row > 6) {

                $sheet
                    ->getStyle(
                        $persenColumn .
                        '6:' .
                        $persenColumn .
                        ($row - 1)
                    )
                    ->getNumberFormat()
                    ->setFormatCode('0.00%');
            }


            /*
            * ==========================
            * STYLE JUDUL
            * ==========================
            */
            $sheet
                ->getStyle(
                    'A1:' . $lastColumn . '2'
                )
                ->getAlignment()
                ->setHorizontal(
                    Alignment::HORIZONTAL_CENTER
                );

            $sheet
                ->getStyle('A1')
                ->getFont()
                ->setBold(true)
                ->setSize(14);


            /*
            * HEADER TEBAL + CENTER
            */
            $sheet
                ->getStyle(
                    'A5:' . $lastColumn . '5'
                )
                ->getFont()
                ->setBold(true);

            $sheet
                ->getStyle(
                    'A5:' . $lastColumn . '5'
                )
                ->getAlignment()
                ->setHorizontal(
                    Alignment::HORIZONTAL_CENTER
                );


            /*
            * BORDER
            */
            if ($row > 6) {

                $sheet
                    ->getStyle(
                        'A5:' .
                        $lastColumn .
                        ($row - 1)
                    )
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(
                        Border::BORDER_THIN
                    );
            }


            /*
            * AUTO WIDTH
            */
            foreach (
                range('A', $lastColumn)
                as $column
            ) {

                $sheet
                    ->getColumnDimension($column)
                    ->setAutoSize(true);
            }


            /*
            * FREEZE HEADER
            */
            $sheet->freezePane('A6');


            /*
            * ==========================
            * DOWNLOAD
            * ==========================
            */
            $fileName =
                'rekap-kehadiran-' .
                $jenis .
                '-' .
                strtolower($namaBulan[$bulan]) .
                '-' .
                $tahun .
                '.xlsx';


            $writer = new Xlsx(
                $spreadsheet
            );


            /*
            * Stream langsung ke browser.
            * Tidak perlu menyimpan file
            * permanen di server.
            */
            $tempFile = tempnam(
                sys_get_temp_dir(),
                'rekap_'
            );

            $writer->save($tempFile);


            return $this->response
                ->download(
                    $tempFile,
                    null
                )
                ->setFileName($fileName);

        } catch (\Throwable $e) {

            log_message(
                'error',
                'Export Excel Rekap: {message}',
                [
                    'message' => $e->getMessage()
                ]
            );

            return redirect()
                ->to(
                    site_url('rekap-kehadiran')
                )
                ->with(
                    'error',
                    'Gagal membuat file Excel.'
                );
        }
    }
}