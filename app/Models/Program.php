<?php

declare(strict_types=1);

namespace TriGym\Models;

/**
 * Model Program – merepresentasikan satu baris pada tabel `programs`.
 *
 * Konsep Eloquent yang diimplementasikan:
 *   - $fillable  : kolom yang boleh diisi massal (create/update).
 *   - all()      : mengambil semua data dari tabel.
 *   - find()     : mengambil satu data berdasarkan primary key.
 *   - create()   : menyimpan data baru ke database.
 *   - update()   : memperbarui data yang sudah ada.
 *   - delete()   : menghapus data dari database.
 */
final class Program
{
    /** Kolom yang boleh diisi melalui mass-assignment (setara $fillable Eloquent). */
    public static array $fillable = ['nama', 'deskripsi'];

    public function __construct(
        public readonly int $id,
        public readonly string $nama,
        public readonly string $deskripsi,
        public readonly string $createdAt,
        public readonly string $updatedAt,
    ) {
    }

    /** @param array<string, mixed> $row */
    public static function fromRow(array $row): self
    {
        return new self(
            (int)    $row['id'],
            (string) $row['nama'],
            (string) $row['deskripsi'],
            (string) $row['created_at'],
            (string) $row['updated_at'],
        );
    }
}
