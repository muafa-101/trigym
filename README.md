# TriGym - versi PHP OOP

## Cara menjalankan
1. Salin folder `assets/` dan `src/` (berisi `output.css` hasil Tailwind) dari proyek lama ke folder ini.
2. Di `tailwind.config.js`, tambahkan file PHP ke `content`, lalu build ulang CSS:
   ```js
   content: ["./*.php", "./views/**/*.php", "./app/**/*.php"]
   ```
   (class warna status gym ada di `app/Models/GymStatus.php`, jadi `app/` wajib ikut dipindai.)
3. Jalankan: `php -S localhost:8000` lalu buka http://localhost:8000
4. Butuh PHP 8.1+ dengan ekstensi `pdo_sqlite`. Database `database/trigym.sqlite` dibuat otomatis.

## Struktur
- `index.php`, `signup.php`, `signin.php`, `dashboard.php`, `logout.php`, `enroll.php`, `generate-code.php` : entry point tipis, hanya memanggil controller.
- `app/Core`: Config, Database (Singleton PDO), Session, Request, Response, View, Validator, Auth
- `app/Models`: Member, GymClass, Package, Facility, GymStatus
- `app/Repositories`: akses database per entitas (extends BaseRepository)
- `app/Controllers`: HomeController, AuthController, DashboardController
- `views/`: layout, partial (header/footer/script), dan view tiap halaman
