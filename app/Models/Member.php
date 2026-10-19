<?php

declare(strict_types=1);

namespace TriGym\Models;

use DateTimeImmutable;

final class Member
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly string $passwordHash,
        public readonly string $status,
        public readonly string $photo,
        public readonly ?int $packageId,
        public readonly string $expiresAt,
        public readonly string $createdAt,
    ) {
    }

    /** @param array<string, mixed> $row */
    public static function fromRow(array $row): self
    {
        return new self(
            (int) $row['id'],
            (string) $row['name'],
            (string) $row['email'],
            (string) $row['password_hash'],
            (string) $row['status'],
            (string) $row['photo'],
            $row['package_id'] !== null ? (int) $row['package_id'] : null,
            (string) $row['expires_at'],
            (string) $row['created_at'],
        );
    }

    public function memberCode(): string
    {
        return 'M-' . str_pad((string) $this->id, 5, '0', STR_PAD_LEFT);
    }

    public function expiryLabel(): string
    {
        return (new DateTimeImmutable($this->expiresAt))->format('m/Y');
    }

    public function daysLeft(): int
    {
        $diff = (new DateTimeImmutable('today'))->diff(new DateTimeImmutable($this->expiresAt));
        return $diff->invert === 1 ? 0 : (int) $diff->days;
    }
}
