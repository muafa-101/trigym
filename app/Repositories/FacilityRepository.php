<?php

declare(strict_types=1);

namespace TriGym\Repositories;

use TriGym\Models\Facility;

final class FacilityRepository extends BaseRepository
{
    /** @return Facility[] */
    public function all(): array
    {
        $rows = $this->db->query('SELECT * FROM facilities ORDER BY id')->fetchAll();
        return array_map([Facility::class, 'fromRow'], $rows);
    }
}
