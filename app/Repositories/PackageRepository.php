<?php

declare(strict_types=1);

namespace TriGym\Repositories;

use TriGym\Models\Package;

final class PackageRepository extends BaseRepository
{
    /** @return Package[] */
    public function all(): array
    {
        $rows = $this->db->query('SELECT * FROM packages ORDER BY id')->fetchAll();
        return array_map([Package::class, 'fromRow'], $rows);
    }

    public function find(int $id): ?Package
    {
        $stmt = $this->db->prepare('SELECT * FROM packages WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? Package::fromRow($row) : null;
    }

    /** Paket bawaan bila member tidak memilih paket (paket pertama). */
    public function first(): Package
    {
        $row = $this->db->query('SELECT * FROM packages ORDER BY id LIMIT 1')->fetch();
        return Package::fromRow($row);
    }
}
