<?php

declare(strict_types=1);

namespace TriGym\Models;

final class GymClass
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $description,
        public readonly string $trainer,
        public readonly string $days,
        public readonly string $startTime,
        public readonly string $endTime,
    ) {
    }

    /** @param array<string, mixed> $row */
    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id'],
            (string) $row['name'],
            (string) $row['description'],
            (string) $row['trainer'],
            (string) $row['days'],
            (string) $row['start_time'],
            (string) $row['end_time'],
        );
    }

    public function timeLabel(): string
    {
        return $this->startTime . ' - ' . $this->endTime . ' WIB';
    }
}
