<?php

declare(strict_types=1);

namespace TriGym\Repositories;

use DateTimeImmutable;
use TriGym\Models\GymClass;

final class ClassRepository extends BaseRepository
{
    /** @return GymClass[] */
    public function all(): array
    {
        $rows = $this->db->query('SELECT * FROM classes ORDER BY id')->fetchAll();
        return array_map([GymClass::class, 'fromRow'], $rows);
    }

    public function exists(int $classId): bool
    {
        $stmt = $this->db->prepare('SELECT 1 FROM classes WHERE id = ?');
        $stmt->execute([$classId]);
        return (bool) $stmt->fetchColumn();
    }

    /**
     * ID kelas yang diikuti member, diurutkan dari yang paling awal diikuti.
     * @return int[]
     */
    public function enrolledIds(int $memberId): array
    {
        $stmt = $this->db->prepare(
            'SELECT class_id FROM enrollments WHERE member_id = ? ORDER BY created_at, class_id'
        );
        $stmt->execute([$memberId]);
        return array_map('intval', $stmt->fetchAll(\PDO::FETCH_COLUMN));
    }

    public function enroll(int $memberId, int $classId): void
    {
        $stmt = $this->db->prepare(
            'INSERT OR IGNORE INTO enrollments (member_id, class_id, created_at) VALUES (?, ?, ?)'
        );
        $stmt->execute([$memberId, $classId, (new DateTimeImmutable())->format('Y-m-d H:i:s')]);
    }
}
