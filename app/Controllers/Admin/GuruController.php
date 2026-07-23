<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseAdminController;
use App\Services\GuruService;
use App\Traits\AjaxResponseTrait;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class GuruController extends BaseAdminController
{
    protected GuruService $guruService;

    public function __construct()
    {
        $this->guruService = new GuruService();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Guru',
            'user'  => $this->currentUser()
        ];

        return view('guru/index', $data);
    }

    public function getData()
    {
        $idSekolah = session('id_sekolah');

        $data = $this->guruService->getAll($idSekolah);

        return $this->response->setJSON($data);
    }
    public function show($id)
    {
        $guru = $this->guruService->find($id);

        if (!$guru) {

            return $this->response->setJSON(
                responseError('Data tidak ditemukan.')
            );

        }

        return $this->response->setJSON([
        'success' => true,
        'data' => $guru
    ]);
    }
    public function store()
    {
        $nip = $this->request->getPost('nip');

        if (!empty($nip) && $this->guruService->cekNip($nip)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'NIP sudah digunakan.'
            ]);
        }
    
            $data=[
            'id_sekolah'=>session('id_sekolah'),
            'nip'=> $nip,
            'nama'=>$this->request->getPost('nama'),
            'jenis_kelamin'=>$this->request->getPost('jenis_kelamin'),
            'alamat'=>$this->request->getPost('alamat'),
            'no_hp'=>$this->request->getPost('no_hp'),
            'email'=>$this->request->getPost('email'),
            'jabatan'=>$this->request->getPost('jabatan'),
            'foto'=>'default.png',
            'status'=>$this->request->getPost('status')
            ];

        $this->guruService->save($data);

         return $this->response
        ->setJSON(

        responseSuccess(
            'Guru berhasil ditambahkan.'
        )

    );

}

   public function update($id)
    {
        try {

            $data = [

                'nama' => trim($this->request->getPost('nama')),

                'nip' => trim($this->request->getPost('nip')),

                'jabatan' => $this->request->getPost('jabatan'),

                'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),

                'no_hp' => trim($this->request->getPost('no_hp')),

                'email' => trim($this->request->getPost('email')),

                'alamat' => trim($this->request->getPost('alamat')),

                'status' => $this->request->getPost('status')

            ];

            $this->guruService->update($id, $data);

            return $this->response->setJSON([

                'success' => true,

                'message' => 'Data guru berhasil diubah.'

            ]);

        } catch (\Throwable $e) {

            return $this->response->setJSON([

                'success' => false,

                'message' => $e->getMessage()

            ]);

        }
    }

    public function import()
    {
        try {

            $file = $this->request->getFile('file_excel');

            return $this->response->setJSON(
                $this->guruService->import($file)
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
                $this->guruService->storeImport($file)
            );

        } catch (\Throwable $e) {

            return $this->response->setJSON(
                responseError($e->getMessage())
            );
        }
    }

    public function delete($id)
    {
        $this->guruService->delete($id);

        return $this->response->setJSON([
            'status'  => true,
            'message' => 'Data berhasil dihapus.'
        ]);
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
            'NIP',
            'Nama',
            'Jenis Kelamin',
            'Jabatan',
            'No HP',
            'Email',
            'Alamat',
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
        $sheet->setCellValue('A2', '198701001');
        $sheet->setCellValue('B2', 'Syakir Muhammad');
        $sheet->setCellValue('C2', 'L');
        $sheet->setCellValue('D2', 'Guru Matematika');
        $sheet->setCellValue('E2', '081234567890');
        $sheet->setCellValue('F2', 'ahmad@example.com');
        $sheet->setCellValue('G2', 'Cijenuk, Bandung Barat');
        $sheet->setCellValue('H2', 'aktif');

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
            ->getStyle('A1:H1')
            ->getFont()
            ->setBold(true);

        $filename = 'template_import_guru.xlsx';

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