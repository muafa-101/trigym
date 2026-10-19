<?php

declare(strict_types=1);

namespace TriGym\Repositories;

use PDO;
use TriGym\Core\Database;

abstract class BaseRepository
{
    protected PDO $db;

    public function __construct(?PDO $db = null)
    {
        $this->db = $db ?? Database::connection();
    }
}
