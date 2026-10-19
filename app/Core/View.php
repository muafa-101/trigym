<?php

declare(strict_types=1);

namespace TriGym\Core;

final class View
{
    public static function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    /**
     * Render views/{$view}.php ke dalam layout views/layouts/main.php.
     *
     * Kunci khusus pada $data: pageTitle, header (home|auth|dashboard),
     * footer (legal|app), bodyClass, scripts.
     */
    public static function render(string $view, array $data = []): void
    {
        $content = self::capture($view, $data);
        echo self::capture('layouts/main', $data + ['content' => $content]);
    }

    public static function partial(string $name, array $data = []): void
    {
        echo self::capture('partials/' . $name, $data);
    }

    private static function capture(string $view, array $data): string
    {
        $file = dirname(__DIR__, 2) . '/views/' . $view . '.php';
        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        return (string) ob_get_clean();
    }
}
