<?php

if (!function_exists('uploadImage')) {

    /**
     * Upload Image
     *
     * @param \CodeIgniter\HTTP\Files\UploadedFile|null $file
     * @param string $folder
     * @param string $prefix
     * @return string|null
     */
    function uploadImage($file, string $folder, string $prefix = 'IMG'): ?string
    {
        if (!$file || !$file->isValid() || $file->hasMoved()) {
            return null;
        }

        // Validasi ekstensi
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower($file->getExtension());

        if (!in_array($ext, $allowed)) {
            return null;
        }

        // Nama file unik
        $fileName = sprintf(
            '%s_%s_%04d.%s',
            strtoupper($prefix),
            date('YmdHis'),
            random_int(1000, 9999),
            $ext
        );

        // Folder tujuan
        $destination = rtrim(FCPATH . trim($folder, '/'), '/');

        if (!is_dir($destination)) {
            mkdir($destination, 0775, true);
        }

        // Upload file
        $file->move($destination, $fileName);

        return $fileName;
    }
}

if (!function_exists('deleteImage')) {

    /**
     * Delete Image
     *
     * @param string $folder
     * @param string|null $fileName
     * @return void
     */
    function deleteImage(string $folder, ?string $fileName): void
    {
        if (empty($fileName) || $fileName === 'default.png') {
            return;
        }

        $path = rtrim(FCPATH . trim($folder, '/'), '/') . '/' . $fileName;

        if (is_file($path)) {
            unlink($path);
        }
    }
}