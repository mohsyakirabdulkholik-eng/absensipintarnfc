<?php

namespace App\Traits;

trait AjaxResponseTrait
{
    protected function successResponse(
        string $message,
        $data = null,
        int $code = 200
    ) {
        return $this->response
            ->setStatusCode($code)
            ->setJSON([
                'success' => true,
                'message' => $message,
                'data'    => $data
            ]);
    }

    protected function errorResponse(
        string $message,
        array $errors = [],
        int $code = 400
    ) {
        return $this->response
            ->setStatusCode($code)
            ->setJSON([
                'success' => false,
                'message' => $message,
                'errors'  => $errors
            ]);
    }
}