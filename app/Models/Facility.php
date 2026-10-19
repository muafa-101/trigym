<?php

declare(strict_types=1);

namespace TriGym\Models;

final class Facility
{
    public function __construct(
        public readonly int $id,
        public readonly string $title,
        public readonly string $description,
    ) {
    }

    /** @param array<string, mixed> $row */
    public static function fromRow(array $row): self
    {
        return new self((int) $row['id'], (string) $row['title'], (string) $row['description']);
    }
}
