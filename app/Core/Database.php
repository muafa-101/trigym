<?php

declare(strict_types=1);

namespace TriGym\Core;

use PDO;

/** Koneksi PDO tunggal (Singleton) + pembuatan skema dan data awal. */
final class Database
{
    private static ?PDO $pdo = null;

    private function __construct()
    {
    }

    public static function connection(): PDO
    {
        if (self::$pdo === null) {
            $path = (string) Config::get('db.path');
            $pdo = new PDO('sqlite:' . $path, null, null, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            $pdo->exec('PRAGMA foreign_keys = ON');
            self::$pdo = $pdo;
            self::migrate($pdo);
        }
        return self::$pdo;
    }

    private static function migrate(PDO $pdo): void
    {
        $schema = dirname(__DIR__, 2) . '/database/schema.sql';
        $pdo->exec((string) file_get_contents($schema));

        if ((int) $pdo->query('SELECT COUNT(*) FROM classes')->fetchColumn() === 0) {
            self::seed($pdo);
        }
    }

    private static function seed(PDO $pdo): void
    {
        $facility = $pdo->prepare('INSERT INTO facilities (title, description) VALUES (?, ?)');
        foreach ([
            ['Peralatan Modern', 'Lebih dari 60 unit alat kardio dan beban dari merek terpercaya, dirawat rutin setiap minggu. Semua dilengkapi panduan pemakaian singkat di setiap unit.'],
            ['Personal Trainer', 'Pelatih bersertifikat yang menyusun program sesuai kondisi dan target kamu, dari menurunkan berat badan sampai menambah massa otot. Sesi pertama gratis untuk semua anggota baru.'],
            ['Area Luas & Nyaman', 'Ruang latihan 800 m² dengan pendingin udara, loker, dan kamar bilas air panas. Buka setiap hari pukul 06.00–22.00.'],
        ] as $row) {
            $facility->execute($row);
        }

        $class = $pdo->prepare(
            'INSERT INTO classes (name, description, trainer, days, start_time, end_time) VALUES (?, ?, ?, ?, ?, ?)'
        );
        foreach ([
            ['Yoga & Pilates', 'Latihan fokus pernapasan dan fleksibilitas, cocok untuk meredakan stres sekaligus memperkuat otot inti.', 'Rina Maharani', 'Selasa & Jumat', '07:00', '08:00'],
            ['Weight Training', 'Program angkat beban terstruktur untuk membangun massa otot dan kekuatan secara bertahap.', 'Abdoel Fatah', 'Senin - Jumat', '08:00', '11:00'],
            ['Cardio & HIIT', 'Sesi interval intensitas tinggi yang membakar kalori maksimal dalam waktu singkat.', 'Bima Prakoso', 'Senin & Kamis', '16:00', '17:00'],
            ['Zumba', 'Kelas dansa berenergi diiringi musik, cara seru membakar kalori tanpa terasa seperti olahraga.', 'Sari Dewi', 'Rabu & Sabtu', '18:30', '19:30'],
        ] as $row) {
            $class->execute($row);
        }

        $package = $pdo->prepare(
            'INSERT INTO packages (name, price, period, note, description, months) VALUES (?, ?, ?, ?, ?, ?)'
        );
        foreach ([
            ['Paket Bulanan', 250000, 'bulan', null, 'Akses penuh ke semua alat dan kelas grup, fleksibel tanpa kontrak jangka panjang.', 1],
            ['Paket Tahunan', 2400000, 'tahun', 'hemat 2 bulan', 'Semua benefit paket bulanan, plus 1x sesi konsultasi personal trainer gratis tiap bulan.', 12],
        ] as $row) {
            $package->execute($row);
        }
    }
}
