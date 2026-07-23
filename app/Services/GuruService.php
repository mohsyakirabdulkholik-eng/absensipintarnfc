<?php

namespace App\Services;

use App\Repositories\GuruRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;

class GuruService
{
    protected GuruRepository $repository;

    public function __construct()
    {
        $this->repository = new GuruRepository();
    }

    public function getAll($idSekolah)
    {
        return $this->repository->getAll($idSekolah);
    }
    public function find($id)
    {
        return $this->repository->findById($id);
    }

    public function save(array $data)
    {
        return $this->repository->insert($data);
    }
    public function cekNip($nip)
    {
        return $this->repository->cekNip($nip);
    }
    public function update($id, array $data)
    {
        return $this->repository->updateData($id, $data);
    }

    public function import($file): array
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

        $this->validateImportHeader($rows[1]);

        $preview = $this->validateImportRows($rows);

        $valid = count(
            array_filter(
                $preview,
                fn ($row) => $row['valid']
            )
        );

        return responseSuccess(
            'Preview import berhasil dibuat.',
            [
                'total'   => count($preview),
                'valid'   => $valid,
                'invalid' => count($preview) - $valid,
                'rows'    => $preview
            ]
        );
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

        $this->validateImportHeader($rows[1]);

        // Validasi ulang di server
        $preview = $this->validateImportRows($rows);

        $idSekolah = (int) session('id_sekolah');

        $berhasil = 0;
        $gagal = 0;

        foreach ($preview as $row) {

            if (!$row['valid']) {
                $gagal++;
                continue;
            }

            $data = [
                'id_sekolah'     => $idSekolah,

                'nip' => $row['nip'] !== ''
                    ? $row['nip']
                    : null,

                'nama'            => $row['nama'],
                'jenis_kelamin'   => $row['jenis_kelamin'],
                'jabatan'         => $row['jabatan'],
                'no_hp'           => $row['no_hp'],
                'email'           => $row['email'],
                'alamat'          => $row['alamat'],
                'foto'            => 'default.png',
                'status'          => $row['status']
            ];

            if ($this->repository->insert($data)) {
                $berhasil++;
            } else {
                $gagal++;
            }
        }

        return responseSuccess(
            'Import data guru selesai.',
            [
                'total'    => count($preview),
                'berhasil' => $berhasil,
                'gagal'    => $gagal
            ]
        );
    }

    public function delete($id)
    {
        return $this->repository->deleteData($id);
    }
    private function validateImportHeader(array $header): void
    {
        $template = [
            'NIP',
            'Nama',
            'Jenis Kelamin',
            'Jabatan',
            'No HP',
            'Email',
            'Alamat',
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

        unset($rows[1]);

        foreach ($rows as $index => $row) {

            $nip = trim((string) ($row['A'] ?? ''));
            $nama = trim((string) ($row['B'] ?? ''));

            $jenisKelamin = strtoupper(
                trim((string) ($row['C'] ?? ''))
            );

            $jabatan = trim((string) ($row['D'] ?? ''));
            $noHp = trim((string) ($row['E'] ?? ''));
            $email = trim((string) ($row['F'] ?? ''));
            $alamat = trim((string) ($row['G'] ?? ''));

            $status = strtolower(
                trim((string) ($row['H'] ?? ''))
            );

            // Lewati baris benar-benar kosong
            if (
                $nip === '' &&
                $nama === '' &&
                $jenisKelamin === '' &&
                $jabatan === '' &&
                $noHp === '' &&
                $email === '' &&
                $alamat === '' &&
                $status === ''
            ) {
                continue;
            }

            $errors = [];

            /*
            * NIP boleh kosong karena pada CRUD Guru
            * saat ini NIP memang dicek hanya jika diisi.
            */
            if (
                $nip !== '' &&
                $this->repository->cekNip($nip)
            ) {
                $errors[] = 'NIP sudah digunakan.';
            }

            if ($nama === '') {
                $errors[] = 'Nama guru wajib diisi.';
            }

            if (!in_array($jenisKelamin, ['L', 'P'], true)) {
                $errors[] = 'Jenis kelamin harus L atau P.';
            }

            if ($jabatan === '') {
                $errors[] = 'Jabatan wajib diisi.';
            }

            if (
                $email !== '' &&
                !filter_var($email, FILTER_VALIDATE_EMAIL)
            ) {
                $errors[] = 'Format email tidak valid.';
            }

            if (!in_array(
                $status,
                ['aktif', 'nonaktif'],
                true
            )) {
                $errors[] = 'Status harus aktif atau nonaktif.';
            }

            $preview[] = [
                'baris'          => $index,
                'nip'            => $nip,
                'nama'           => $nama,
                'jenis_kelamin'  => $jenisKelamin,
                'jabatan'        => $jabatan,
                'no_hp'          => $noHp,
                'email'          => $email,
                'alamat'         => $alamat,
                'status'         => $status,

                'valid' => empty($errors),

                'errors' => $errors,

                'keterangan' => empty($errors)
                    ? 'Siap diimport'
                    : implode(' ', $errors)
            ];
        }

        return $preview;
    }
}