<?php

function responseSuccess(string $message, $data = null): array
{
    return [
        'success' => true,
        'message' => $message,
        'data'    => $data
    ];
}

function responseError(string $message, $errors = []): array
{
    return [
        'success' => false,
        'message' => $message,
        'errors'  => $errors
    ];
}