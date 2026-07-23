<?php

namespace App\Services\Import;

class StudentImportService
{
    protected ExcelReader $reader;
    protected ExcelValidator $validator;

    public function __construct()
    {
        $this->reader = new ExcelReader();
        $this->validator = new ExcelValidator();
    }

    public function preview($file): array
    {
        $rows = $this->reader->read($file);

        $header = $this->validator->validateHeader($rows);

        if (!$header['success']) {
            return $header;
        }

        return [
            'success' => true,
            'rows' => array_slice($rows, 1)
        ];
    }
}