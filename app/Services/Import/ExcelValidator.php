<?php

namespace App\Services\Import;

class ExcelValidator
{
    private array $header = [
        'NIS',
        'NISN',
        'Nama',
        'Jenis Kelamin',
        'Kelas',
        'Status'
    ];

    public function validateHeader(array $rows): array
    {
        if (empty($rows)) {
            return [
                'success' => false,
                'message' => 'File Excel kosong.'
            ];
        }

        $header = array_values($rows[1]);

        if ($header !== $this->header) {

            return [
                'success' => false,
                'message' => 'Format template Excel tidak sesuai.'
            ];
        }

        return [
            'success' => true
        ];
    }
}