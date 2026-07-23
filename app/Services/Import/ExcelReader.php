<?php

namespace App\Services\Import;

use PhpOffice\PhpSpreadsheet\IOFactory;

class ExcelReader
{
    public function read($file): array
    {
        $spreadsheet = IOFactory::load($file->getTempName());

        $sheet = $spreadsheet->getActiveSheet();

        return $sheet->toArray(null, true, true, true);
    }
}