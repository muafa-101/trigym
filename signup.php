<?php

declare(strict_types=1);

require __DIR__ . '/bootstrap.php';

(new TriGym\Controllers\AuthController())->signup();
