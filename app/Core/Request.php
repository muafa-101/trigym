<?php

declare(strict_types=1);

namespace TriGym\Core;

final class Request
{
    public function isPost(): bool
    {
        return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
    }

    public function input(string $key, mixed $default = null): mixed
    {
        return $_POST[$key] ?? $default;
    }

    public function query(string $key, mixed $default = null): mixed
    {
        return $_GET[$key] ?? $default;
    }

    /** Ambil string yang sudah di-trim. */
    public function string(string $key): string
    {
        $value = $this->input($key, '');
        return is_string($value) ? trim($value) : '';
    }

    public function csrfIsValid(): bool
    {
        $token = $_POST['_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
        return Session::verifyCsrf(is_string($token) ? $token : null);
    }
}
