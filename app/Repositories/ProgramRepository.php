<?php

declare(strict_types=1);

namespace TriGym\Repositories;

use DateTimeImmutable;
use TriGym\Models\Program;

/**
 * ProgramRepository – lapisan akses data untuk tabel `programs`.
 *
 * Metode ini mencerminkan operasi Eloquent:
 *   all()    → Program::all()
 *   find()   → Program::find($id)
 *   create() → Program::create([...])
 *   update() → $program->update([...])
 *   delete() → $program->delete()
 */
final class ProgramRepository extends BaseRepository
{
    /** Mengambil semua program, diurutkan dari yang terbaru. */
    public function all(): array
    {
        $rows = $this->db->query('SELECT * FROM programs ORDER BY id')->fetchAll();
        return array_map([Program::class, 'fromRow'], $rows);
    }

    /** Mengambil satu program berdasarkan id-nya. */
    public function find(int $id): ?Program
    {
        $stmt = $this->db->prepare('SELECT * FROM programs WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row ? Program::fromRow($row) : null;
    }

    /**
     * Menyimpan program baru ke database.
     *
     * @param array{nama: string, deskripsi: string} $data
     */
    public function create(array $data): Program
    {
        $now  = (new DateTimeImmutable())->format('Y-m-d H:i:s');
        $stmt = $this->db->prepare(
            'INSERT INTO programs (nama, deskripsi, created_at, updated_at) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$data['nama'], $data['deskripsi'], $now, $now]);
        $id = (int) $this->db->lastInsertId();
        return $this->find($id);
    }

    /**
     * Memperbarui data program yang sudah ada.
     *
     * @param array{nama: string, deskripsi: string} $data
     */
    public function update(int $id, array $data): bool
    {
        $now  = (new DateTimeImmutable())->format('Y-m-d H:i:s');
        $stmt = $this->db->prepare(
            'UPDATE programs SET nama = ?, deskripsi = ?, updated_at = ? WHERE id = ?'
        );
        $stmt->execute([$data['nama'], $data['deskripsi'], $now, $id]);
        return $stmt->rowCount() > 0;
    }

    /** Menghapus program dari database berdasarkan id-nya. */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM programs WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
