<?php

declare(strict_types=1);

namespace TriGym\Repositories;

use DateTimeImmutable;
use TriGym\Core\Config;
use TriGym\Models\Member;
use TriGym\Models\Package;

final class MemberRepository extends BaseRepository
{
    public function findById(int $id): ?Member
    {
        $stmt = $this->db->prepare('SELECT * FROM members WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? Member::fromRow($row) : null;
    }

    public function findByEmail(string $email): ?Member
    {
        $stmt = $this->db->prepare('SELECT * FROM members WHERE email = ? COLLATE NOCASE');
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return $row ? Member::fromRow($row) : null;
    }

    public function emailExists(string $email): bool
    {
        return $this->findByEmail($email) !== null;
    }

    public function create(string $name, string $email, string $plainPassword, Package $package): Member
    {
        $stmt = $this->db->prepare(
            'INSERT INTO members (name, email, password_hash, status, photo, package_id, expires_at, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $name,
            strtolower($email),
            password_hash($plainPassword, PASSWORD_DEFAULT),
            'Aktif',
            (string) Config::get('default_photo'),
            $package->id,
            $package->expiryFromToday(),
            (new DateTimeImmutable())->format('Y-m-d H:i:s'),
        ]);

        return $this->findById((int) $this->db->lastInsertId());
    }
}
