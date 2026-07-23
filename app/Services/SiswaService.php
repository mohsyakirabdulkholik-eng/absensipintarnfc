<?php

namespace App\Services;

use App\Repositories\SiswaRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SiswaService
{
    protected SiswaRepository $repository;

    public function __construct()
    {
        $this->repository = new SiswaRepository();
    }

    public function getAll($idSekolah)
    {
        return $this->repository->getAll($idSekolah);
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function getBySekolah($idSekolah)
    {
        return $this->repository->getBySekolah($idSekolah);
    }

    /**
     * Simpan data siswa
     */
    public function save(array $data)
    {
        $this->validateUnique($data);

        $data['foto'] = $this->uploadFoto($data['foto']);

        return $this->repository->insert($data);
    }

    /**
     * Update data siswa
     */
    public function update($id, array $data)
    {
        $siswa = $this->repository->find($id);

        if (!$siswa) {
            throw new \Exception('Data siswa tidak ditemukan.');
        }

        $this->validateUnique($data, $siswa);

        if (
            !empty($data['foto']) &&
            $data['foto']->isValid() &&
            $data['foto']->getError() !== UPLOAD_ERR_NO_FILE
        ) {

            $fotoBaru = $this->uploadFoto($data['foto']);

            if ($fotoBaru !== 'default.png') {

                deleteImage('uploads/siswa', $siswa['foto']);

                $data['foto'] = $fotoBaru;
            }

        } else {

            unset($data['foto']);

        }

        return $this->repository->updateData($id, $data);
    }

    /**
     * Hapus data siswa
     */
    public function delete($id)
    {
        $siswa = $this->repository->find($id);

        if (!$siswa) {
            throw new \Exception('Data siswa tidak ditemukan.');
        }

        deleteImage(
            'uploads/siswa',
            $siswa['foto']
        );

        return $this->repository->deleteData($id);
    }

    /**
     * Validasi data unik
     */
    private function validateUnique(array $data, ?array $oldData = null): void
    {
        if (
            !empty($data['nis']) &&
            ($oldData === null || $data['nis'] !== $oldData['nis']) &&
            $this->repository->cekNis($data['nis'])
        ) {
            throw new \Exception('NIS sudah digunakan.');
        }

        if (
            !empty($data['nisn']) &&
            ($oldData === null || $data['nisn'] !== $oldData['nisn']) &&
            $this->repository->cekNisn($data['nisn'])
        ) {
            throw new \Exception('NISN sudah digunakan.');
        }

    }

    /**
     * Upload foto siswa
     */
    private function uploadFoto($foto): string
    {
        if (
            !empty($foto) &&
            $foto->isValid() &&
            $foto->getError() !== UPLOAD_ERR_NO_FILE
        ) {

            $fileName = uploadImage(
                $foto,
                'uploads/siswa',
                'SISWA'
            );

            if ($fileName !== null) {
                return $fileName;
            }
        }

        return 'default.png';
    }

    public function import($file): array
    {
        if (!$file || !$file->isValid()) {
            throw new \Exception(
                'File Excel tidak ditemukan.'
            );
        }

        $spreadsheet = IOFactory::load(
            $file->getTempName()
        );

        $rows = $spreadsheet
            ->getActiveSheet()
            ->toArray(null, true, true, true);

        if (count($rows) <= 1) {
            throw new \Exception(
                'File Excel kosong.'
            );
        }

        $this->validateImportHeader($rows[1]);

        $preview = $this->validateImportRows($rows);

        $valid = count(
            array_filter(
                $preview,
                fn ($row) => $row['valid']
            )
        );

        $invalid = count($preview) - $valid;

        return responseSuccess(
            'Preview import berhasil dibuat.',
            [
                'total' => count($preview),
                'valid' => $valid,
                'invalid' => $invalid,
                'rows' => $preview
            ]
        );
    }

    private function validateImportHeader(array $header): void
    {
        $template = [
            'NIS',
            'NISN',
            'Nama',
            'Jenis Kelamin',
            'Kelas',
            'Status'
        ];

        $header = array_values($header);

        if ($header !== $template) {
            throw new \Exception(
                'Format template Excel tidak sesuai.'
            );
        }
    }

    private function validateImportRows(array $rows): array
    {
        $preview = [];

        $idSekolah = (int) session('id_sekolah');

        unset($rows[1]); // hapus header

        foreach ($rows as $index => $row) {

            $nis = trim((string) ($row['A'] ?? ''));
            $nisn = trim((string) ($row['B'] ?? ''));
            $nama = trim((string) ($row['C'] ?? ''));
            $jenisKelamin = strtoupper(
                trim((string) ($row['D'] ?? ''))
            );
            $kelas = trim((string) ($row['E'] ?? ''));
            $status = strtolower(
                trim((string) ($row['F'] ?? ''))
            );

            /*
            * Lewati baris Excel yang benar-benar kosong
            */
            if (
                $nis === '' &&
                $nisn === '' &&
                $nama === '' &&
                $jenisKelamin === '' &&
                $kelas === '' &&
                $status === ''
            ) {
                continue;
            }

            $errors = [];

            /*
            * NIS
            */
            if ($nis === '') {

                $errors[] = 'NIS wajib diisi.';

            } elseif ($this->repository->cekNis($nis)) {

                $errors[] = 'NIS sudah digunakan.';
            }

            /*
            * NISN
            */
            if (
                $nisn !== '' &&
                $this->repository->cekNisn($nisn)
            ) {
                $errors[] = 'NISN sudah digunakan.';
            }

            /*
            * Nama siswa
            */
            if ($nama === '') {
                $errors[] = 'Nama siswa wajib diisi.';
            }

            /*
            * Jenis kelamin
            */
            if (!in_array($jenisKelamin, ['L', 'P'], true)) {
                $errors[] = 'Jenis kelamin harus L atau P.';
            }

            /*
            * Kelas
            */
            $dataKelas = null;

            if ($kelas === '') {

                $errors[] = 'Kelas wajib diisi.';

            } else {

                $dataKelas = $this->repository
                    ->findKelasByNama($kelas, $idSekolah);

                if (!$dataKelas) {
                    $errors[] = 'Kelas tidak ditemukan.';
                }
            }

            /*
            * Status
            */
            if (!in_array(
                $status,
                ['aktif', 'lulus', 'keluar'],
                true
            )) {
                $errors[] =
                    'Status harus aktif, lulus, atau keluar.';
            }

            $preview[] = [

                'baris' => $index,

                'nis' => $nis,

                'nisn' => $nisn,

                'nama' => $nama,

                'jenis_kelamin' => $jenisKelamin,

                'kelas' => $kelas,

                /*
                * Ini penting untuk proses INSERT nanti.
                */
                'id_kelas' => $dataKelas['id'] ?? null,

                'status' => $status,

                'valid' => empty($errors),

                'errors' => $errors,

                'keterangan' => empty($errors)
                    ? 'Siap diimport'
                    : implode(' ', $errors)
            ];
        }

        return $preview;
    }
    public function storeImport($file): array
    {
        if (!$file || !$file->isValid()) {
            throw new \Exception('File Excel tidak ditemukan.');
        }

        $spreadsheet = IOFactory::load(
            $file->getTempName()
        );

        $rows = $spreadsheet
            ->getActiveSheet()
            ->toArray(null, true, true, true);

        if (count($rows) <= 1) {
            throw new \Exception('File Excel kosong.');
        }

        /*
        * Validasi header lagi.
        */
        $this->validateImportHeader($rows[1]);

        /*
        * Validasi semua data lagi.
        */
        $preview = $this->validateImportRows($rows);

        $idSekolah = (int) session('id_sekolah');

        $berhasil = 0;
        $gagal = 0;

        foreach ($preview as $row) {

            /*
            * Jangan simpan data bermasalah.
            */
            if (!$row['valid']) {

                $gagal++;

                continue;
            }

            $data = [

                'id_sekolah' => $idSekolah,

                'id_kelas' => $row['id_kelas'],

                'nis' => $row['nis'],

                'nisn' => $row['nisn'] !== ''
                    ? $row['nisn']
                    : null,

                'nama_siswa' => $row['nama'],

                'jenis_kelamin' => $row['jenis_kelamin'],

                'status' => $row['status'],

            ];

            $result = $this->repository->insert($data);

            if ($result) {

                $berhasil++;

            } else {

                $gagal++;
            }
        }

        return responseSuccess(
            'Import data siswa selesai.',
            [
                'total' => count($preview),
                'berhasil' => $berhasil,
                'gagal' => $gagal
            ]
        );
    }
}