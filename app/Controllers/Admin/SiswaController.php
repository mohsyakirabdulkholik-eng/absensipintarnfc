<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseAdminController;
use App\Services\SiswaService;
use App\Traits\AjaxResponseTrait;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class SiswaController extends BaseAdminController
{
    protected SiswaService $siswaService;

   
    private function getFormData(): array
    {
        return [
            'id_sekolah'      => session('id_sekolah'),
            'id_kelas'        => $this->request->getPost('id_kelas'),
            'nis'             => trim($this->request->getPost('nis')),
            'nisn'            => trim($this->request->getPost('nisn')),
            'nama_siswa'      => trim($this->request->getPost('nama_siswa')),
            'jenis_kelamin'   => $this->request->getPost('jenis_kelamin'),
            'tempat_lahir'    => trim($this->request->getPost('tempat_lahir')),
            'tanggal_lahir'   => $this->request->getPost('tanggal_lahir'),
            'alamat'          => trim($this->request->getPost('alamat')),
            'nama_ortu'       => trim($this->request->getPost('nama_ortu')),
            'no_hp'           => trim($this->request->getPost('no_hp')),
            'foto'            => $this->request->getFile('foto'),
            'status'          => $this->request->getPost('status'),
        ];
    }

    public function __construct()
    {
        $this->siswaService = new SiswaService();

    }

    public function index()
    {
        $data = [
            'title' => 'Data Siswa',
            'user'  => $this->currentUser()
        ];

        return view('siswa/index', $data);
    }

    public function getData()
    {
        $idSekolah = session('id_sekolah');

        $data = $this->siswaService->getAll($idSekolah);

        return $this->response->setJSON($data);
    }
    public function show($id)
    {
        $siswa = $this->siswaService->find($id);

        if (!$siswa) {

            return $this->response->setJSON(
                responseError('Data tidak ditemukan.')
            );

        }

        return $this->response->setJSON(responseSuccess('Data ditemukan.', $siswa));
    }
    
   public function store()
    {
        try {

            $this->siswaService->save(
                $this->getFormData()
            );

            return $this->response->setJSON(
                responseSuccess('Siswa berhasil ditambahkan.')
            );

        } catch (\Throwable $e) {

            return $this->response->setJSON(
                responseError($e->getMessage())
            );

        }
    }
   public function update($id)
    {
        try {

                               
            $this->siswaService->update($id, $this->getFormData());

            return $this->response->setJSON(responseSuccess('Siswa berhasil diubah.'));

        } catch (\Throwable $e) {

            return $this->response->setJSON(responseError($e->getMessage()));

        }
    }

    public function delete($id)
    {
        $this->siswaService->delete($id);

        return $this->response->setJSON(responseSuccess('Data siswa berhasil dihapus.'));
    }
    public function import()
    {
        try {

            $file = $this->request->getFile('file_excel');

            return $this->response->setJSON(
                $this->siswaService->import($file)
            );

        } catch (\Throwable $e) {

            return $this->response->setJSON(
                responseError($e->getMessage())
            );

        }
    }
    public function storeImport()
    {
        try {

            $file = $this->request->getFile('file_excel');

            return $this->response->setJSON(
                $this->siswaService->storeImport($file)
            );

        } catch (\Throwable $e) {

            return $this->response->setJSON(
                responseError($e->getMessage())
            );
        }
    }
    public function template()
    {
        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle('Template Guru');

        /*
        * Header HARUS sama dengan validator import.
        */
        $headers = [
            'NIS',
            'NISN',
            'Nama',
            'Jenis Kelamin',
            'Kelas',
            'Status'
        ];

        $column = 'A';

        foreach ($headers as $header) {

            $sheet->setCellValue(
                $column . '1',
                $header
            );

            $column++;
        }

        /*
        * Contoh data.
        */
        $sheet->setCellValue('A2', '1987425');
        $sheet->setCellValue('B2', '2452062');
        $sheet->setCellValue('C2', 'Syakir Abdul');
        $sheet->setCellValue('D2', 'L');
        $sheet->setCellValue('E2', 'VII A');
        $sheet->setCellValue('F2', 'aktif');

        /*
        * Lebar kolom otomatis.
        */
        foreach (range('A', 'H') as $col) {

            $sheet
                ->getColumnDimension($col)
                ->setAutoSize(true);
        }

        /*
        * Header bold.
        */
        $sheet
            ->getStyle('A1:F1')
            ->getFont()
            ->setBold(true);

        $filename = 'template_import_siswa.xlsx';

        $writer = new Xlsx($spreadsheet);

        /*
        * Bersihkan output buffer supaya file Excel
        * tidak corrupt.
        */
        if (ob_get_length()) {
            ob_end_clean();
        }

        header(
            'Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );

        header(
            'Content-Disposition: attachment;filename="' . $filename . '"'
        );

        header('Cache-Control: max-age=0');

        $writer->save('php://output');

        exit;
    }
}