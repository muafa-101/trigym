<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\GymClass;
use App\Models\Package;
use App\Models\Program;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Packages
        $monthly = Package::firstOrCreate(
            ['name' => 'Paket Bulanan'],
            [
                'price' => 250000,
                'period' => 'bulan',
                'note' => null,
                'description' => 'Akses penuh ke semua alat dan kelas grup, fleksibel tanpa kontrak jangka panjang.',
                'months' => 1,
            ]
        );

        $yearly = Package::firstOrCreate(
            ['name' => 'Paket Tahunan'],
            [
                'price' => 2400000,
                'period' => 'tahun',
                'note' => 'hemat 2 bulan',
                'description' => 'Semua benefit paket bulanan, plus 1x sesi konsultasi personal trainer gratis tiap bulan.',
                'months' => 12,
            ]
        );

        // 2. Facilities
        $facilities = [
            [
                'title' => 'Peralatan Modern',
                'description' => 'Lebih dari 60 unit alat kardio dan beban dari merek terpercaya, dirawat rutin setiap minggu. Semua dilengkapi panduan pemakaian singkat di setiap unit.',
            ],
            [
                'title' => 'Personal Trainer',
                'description' => 'Pelatih bersertifikat yang menyusun program sesuai kondisi dan target kamu, dari menurunkan berat badan sampai menambah massa otot. Sesi pertama gratis untuk semua anggota baru.',
            ],
            [
                'title' => 'Area Luas & Nyaman',
                'description' => 'Ruang latihan 800 m² dengan pendingin udara, loker, dan kamar bilas air panas. Buka setiap hari pukul 06.00–22.00.',
            ],
        ];

        foreach ($facilities as $facility) {
            Facility::firstOrCreate(['title' => $facility['title']], $facility);
        }

        // 3. Classes
        $classes = [
            [
                'name' => 'Yoga & Pilates',
                'description' => 'Latihan fokus pernapasan dan fleksibilitas, cocok untuk meredakan stres sekaligus memperkuat otot inti.',
                'trainer' => 'Rina Maharani',
                'days' => 'Selasa & Jumat',
                'start_time' => '07:00',
                'end_time' => '08:00',
            ],
            [
                'name' => 'Weight Training',
                'description' => 'Program angkat beban terstruktur untuk membangun massa otot dan kekuatan secara bertahap.',
                'trainer' => 'Abdoel Fatah',
                'days' => 'Senin - Jumat',
                'start_time' => '08:00',
                'end_time' => '11:00',
            ],
            [
                'name' => 'Cardio & HIIT',
                'description' => 'Sesi interval intensitas tinggi yang membakar kalori maksimal dalam waktu singkat.',
                'trainer' => 'Bima Prakoso',
                'days' => 'Senin & Kamis',
                'start_time' => '16:00',
                'end_time' => '17:00',
            ],
            [
                'name' => 'Zumba',
                'description' => 'Kelas dansa berenergi diiringi musik, cara seru membakar kalori tanpa terasa seperti olahraga.',
                'trainer' => 'Sari Dewi',
                'days' => 'Rabu & Sabtu',
                'start_time' => '18:30',
                'end_time' => '19:30',
            ],
        ];

        foreach ($classes as $class) {
            GymClass::firstOrCreate(['name' => $class['name']], $class);
        }

        // 4. Programs (Fitur PresidentKu)
        $programs = [
            [
                'nama' => 'Pembentukan Otot',
                'deskripsi' => 'Program latihan beban terstruktur untuk membangun massa otot secara bertahap dengan panduan trainer berpengalaman.',
            ],
            [
                'nama' => 'Penurunan Berat Badan',
                'deskripsi' => 'Kombinasi cardio, HIIT, dan diet coaching untuk membantu anggota mencapai berat badan ideal secara sehat.',
            ],
            [
                'nama' => 'Kebugaran Umum',
                'deskripsi' => 'Program komprehensif untuk meningkatkan stamina, fleksibilitas, dan kesehatan tubuh secara menyeluruh.',
            ],
            [
                'nama' => 'Rehabilitasi & Recovery',
                'deskripsi' => 'Program khusus pemulihan cedera dengan latihan ringan dan terapi gerak yang dipandu fisioterapis.',
            ],
        ];

        foreach ($programs as $prog) {
            Program::firstOrCreate(['nama' => $prog['nama']], $prog);
        }

        // 5. Demo Member
        $demoUser = User::firstOrCreate(
            ['email' => 'abdoel@example.com'],
            [
                'name' => 'Abdoel Fatah',
                'password' => Hash::make('password123'),
                'status' => 'Aktif',
                'photo' => 'assets/logo.png',
                'package_id' => $yearly->id,
                'expires_at' => Carbon::today()->addYear(),
            ]
        );

        // Enroll demo user in first class
        $firstClass = GymClass::first();
        if ($firstClass && !$demoUser->classes()->where('class_id', $firstClass->id)->exists()) {
            $demoUser->classes()->attach($firstClass->id);
        }
    }
}
