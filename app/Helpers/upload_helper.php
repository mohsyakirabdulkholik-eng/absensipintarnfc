<?php

use CodeIgniter\HTTP\Files\UploadedFile;

if (!function_exists('uploadImage')) {

    /**
     * Upload Image
     *
     * @throws Exception
     */
    function uploadImage(
        UploadedFile $file,
        string $folder,
        string $prefix = 'IMG',
        int $maxSize = 2 // MB
    ): string {

        if (!$file->isValid()) {
            throw new Exception('File tidak valid.');
        }

        if ($file->hasMoved()) {
            throw new Exception('File sudah dipindahkan.');
        }

        // Validasi MIME Type
        $allowedMime = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        if (!in_array($file->getMimeType(), $allowedMime)) {
            throw new Exception(
                'Format gambar harus JPG, PNG atau WEBP.'
            );
        }

        // Maksimal ukuran
        if ($file->getSize() > ($maxSize * 1024 * 1024)) {
            throw new Exception(
                "Ukuran gambar maksimal {$maxSize} MB."
            );
        }

        // Nama file
        $fileName = sprintf(
            '%s_%s_%04d.%s',
            strtoupper($prefix),
            date('YmdHis'),
            random_int(1000, 9999),
            strtolower($file->getExtension())
        );

        // Folder tujuan
        $destination = FCPATH . trim($folder, '/');

        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        $file->move($destination, $fileName);

        return $fileName;
    }
}

if (!function_exists('deleteImage')) {

    /**
     * Hapus Image
     */
    function deleteImage(
        string $folder,
        ?string $fileName,
        string $defaultFile = 'default.png'
    ): void {

        if (
            empty($fileName) ||
            $fileName === $defaultFile
        ) {
            return;
        }

        $path = FCPATH .
            trim($folder, '/') .
            '/' .
            $fileName;

        if (is_file($path)) {
            unlink($path);
        }
    }
}