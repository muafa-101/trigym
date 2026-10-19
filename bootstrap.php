<?php

declare(strict_types=1);

// Autoloader: namespace TriGym\Xxx\Yyy -> app/Xxx/Yyy.php
spl_autoload_register(static function (string $class): void {
    $prefix = 'TriGym\\';
    if (strncmp($class, $prefix, strlen($prefix)) !== 0) {
        return;
    }
    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = __DIR__ . '/app/' . $relative . '.php';
    if (is_file($file)) {
        require $file;
    }
});

require __DIR__ . '/app/helpers.php';

use TriGym\Core\Config;
use TriGym\Core\Database;
use TriGym\Core\Session;

date_default_timezone_set((string) Config::get('app.timezone', 'UTC'));
Session::start();
Database::connection(); // memastikan tabel + data awal tersedia
