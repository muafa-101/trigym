<?php

declare(strict_types=1);

namespace TriGym\Core;

final class Config
{
    private static ?array $items = null;

    /** Ambil nilai konfigurasi dengan notasi titik, mis. Config::get('db.path'). */
    public static function get(string $key, mixed $default = null): mixed
    {
        self::$items ??= require dirname(__DIR__, 2) . '/config/config.php';

        $value = self::$items;
        foreach (explode('.', $key) as $segment) {
            if (!is_array($value) || !array_key_exists($segment, $value)) {
                return $default;
            }
            $value = $value[$segment];
        }
        return $value;
    }
}
