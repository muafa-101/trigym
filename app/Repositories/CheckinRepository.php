<?php

declare(strict_types=1);

namespace TriGym\Repositories;

use DateTimeImmutable;

final class CheckinRepository extends BaseRepository
{
    /** Buat kode check-in baru, contoh: TG-A1B2C. */
    public function create(int $memberId): string
    {
        $code = 'TG-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 5));

        $stmt = $this->db->prepare('INSERT INTO checkins (member_id, code, created_at) VALUES (?, ?, ?)');
        $stmt->execute([$memberId, $code, (new DateTimeImmutable())->format('Y-m-d H:i:s')]);

        return $code;
    }

    public function countTodayAll(): int
    {
        $start = (new DateTimeImmutable('today'))->format('Y-m-d H:i:s');
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM checkins WHERE created_at >= ?');
        $stmt->execute([$start]);
        return (int) $stmt->fetchColumn();
    }

    public function countThisMonth(int $memberId): int
    {
        $start = (new DateTimeImmutable('first day of this month midnight'))->format('Y-m-d H:i:s');
        $stmt = $this->db->prepare('SELECT COUNT(*) FROM checkins WHERE member_id = ? AND created_at >= ?');
        $stmt->execute([$memberId, $start]);
        return (int) $stmt->fetchColumn();
    }
}
