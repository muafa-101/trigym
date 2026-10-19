<?php

declare(strict_types=1);

namespace TriGym\Core;

final class Response
{
    public static function redirect(string $url): never
    {
        header('Location: ' . $url);
        exit;
    }

    public static function json(array $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    public static function abort(int $status, string $message): never
    {
        http_response_code($status);
        echo e($message);
        exit;
    }
}
