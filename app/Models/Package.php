<?php

declare(strict_types=1);

namespace TriGym\Models;

use DateTimeImmutable;

final class Package
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly int $price,
        public readonly string $period,
        public readonly ?string $note,
        public readonly string $description,
        public readonly int $months,
    ) {
    }

    /** @param array<string, mixed> $row */
    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id'],
            (string) $row['name'],
            (int) $row['price'],
            (string) $row['period'],
            $row['note'] !== null ? (string) $row['note'] : null,
            (string) $row['description'],
            (int) $row['months'],
        );
    }

    /** Contoh: "Rp2.400.000/tahun (hemat 2 bulan)." */
    public function priceLabel(): string
    {
        $label = 'Rp' . number_format($this->price, 0, ',', '.') . '/' . $this->period;
        if ($this->note !== null && $this->note !== '') {
            $label .= ' (' . $this->note . ')';
        }
        return $label . '.';
    }

    /** Tanggal berakhir paket bila dimulai hari ini (Y-m-d). */
    public function expiryFromToday(): string
    {
        return (new DateTimeImmutable('today'))->modify("+{$this->months} months")->format('Y-m-d');
    }
}
