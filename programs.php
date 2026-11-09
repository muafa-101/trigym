<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

$controller = new TriGym\Controllers\ProgramController();

$action = $_REQUEST['action'] ?? 'index';

match ($action) {
    'store'  => $controller->store(),
    'update' => $controller->update(),
    'delete' => $controller->destroy(),
    default  => $controller->index(),
};
