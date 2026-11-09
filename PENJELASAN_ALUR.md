# DOKUMENTASI LENGKAP ALUR APLIKASI TRIGYM
## Panduan Alur Sistem (Route &rarr; Controller &rarr; Model &rarr; Database &rarr; View) & Naskah Presentasi UAS

---

## 1. Pendahuluan & Gambaran Umum Proyek

**TriGym** adalah aplikasi web manajemen kebugaran (gym membership) modern. Proyek ini merupakan hasil migrasi terstruktur dari arsitektur PHP Native (berbasis SQLite) ke dalam kerangka kerja **Laravel** dengan basis data **MySQL**.

Sistem ini menerapkan konsep **MVC (Model - View - Controller)** secara murni dan rapi:
- **Route (`routes/web.php`)**: Menerima request HTTP dari pengguna dan mengarahkannya ke method Controller yang sesuai.
- **Controller (`app/Http/Controllers/`)**: Menangani logika bisnis aplikasi, memproses input request, berinteraksi dengan Model, dan mengirimkan data ke View. Setiap controller utama memiliki method `index()` sebagai gerbang utama.
- **Model (`app/Models/`)**: Menggunakan Eloquent ORM untuk merepresentasikan entitas data, mengelola relasi antar-tabel (*One-to-Many*, *Many-to-Many*), serta menyediakan helper method bisnis (misal perhitungan hari tersisa, kode member, dll).
- **Migration & Database (`database/migrations/`)**: Mengatur skema tabel secara version-controlled dan terstruktur di MySQL (`trigym_db`).
- **View (`resources/views/`)**: Merender tampilan menggunakan Blade templating engine yang bersih, modular, dan terstruktur per fitur.

---

## 2. Konfigurasi Lingkungan & Basis Data (`.env`)

Koneksi database dikonfigurasi melalui file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=trigym_db
DB_USERNAME=root
DB_PASSWORD=
```

Database `trigym_db` dibuat di MySQL dan dieksekusi melalui perintah:
```bash
php artisan migrate:fresh --seed
```

---

## 3. Struktur Tabel & Migrasi Database

Aplikasi TriGym menggunakan 7 tabel fungsional:

1. **`packages`**: Menyimpan data paket keanggotaan gym.
   - Kolom: `id`, `name`, `price`, `period`, `note`, `description`, `months`, `created_at`, `updated_at`.
2. **`users`**: Merepresentasikan member gym sekaligus mengelola autentikasi pengguna bawaan Laravel.
   - Kolom: `id`, `name`, `email`, `password`, `status` (default: 'Aktif'), `photo`, `package_id` (FK ke `packages.id`), `expires_at`, `remember_token`, `timestamps`.
3. **`facilities`**: Menyimpan daftar fasilitas gym unggulan.
   - Kolom: `id`, `title`, `description`, `timestamps`.
4. **`classes`**: Menyimpan jadwal dan informasi kelas latihan (Yoga, Weight Training, Zumba, dll).
   - Kolom: `id`, `name`, `description`, `trainer`, `days`, `start_time`, `end_time`, `timestamps`.
5. **`enrollments`**: Tabel pivot relasi *Many-to-Many* antara member (`users`) dan kelas (`classes`).
   - Kolom: `user_id` (FK `users.id`, cascade), `class_id` (FK `classes.id`, cascade), `created_at`.
   - Primary Key gabungan: `(user_id, class_id)`.
6. **`checkins`**: Menyimpan riwayat kehadiran/check-in harian member.
   - Kolom: `id`, `user_id` (FK `users.id`, cascade), `code`, `timestamps`.
7. **`programs`**: Menyimpan data program latihan pada fitur *Program PresidentKu*.
   - Kolom: `id`, `nama`, `deskripsi`, `timestamps`.

---

## 4. Eloquent Model & Relasi Data

1. **`User` (`app/Models/User.php`)**
   - Mewarisi `Illuminate\Foundation\Auth\User`.
   - **Relasi**:
     - `package()`: `belongsTo(Package::class)` (Satu user memiliki satu paket aktif).
     - `classes()`: `belongsToMany(GymClass::class, 'enrollments', 'user_id', 'class_id')` (Satu user dapat mengikuti banyak kelas).
     - `checkins()`: `hasMany(Checkin::class)` (Satu user memiliki banyak riwayat check-in).
   - **Helper Methods**:
     - `memberCode()` &rarr; Format kode member: `M-00001`.
     - `expiryLabel()` &rarr; Format tanggal kedaluwarsa: `01/2027`.
     - `daysLeft()` &rarr; Menghitung sisa hari aktif membership secara dinamis.

2. **`Package` (`app/Models/Package.php`)**
   - **Relasi**: `hasMany(User::class)`
   - **Helper Methods**:
     - `priceLabel()` &rarr; Contoh: `Rp2.400.000/tahun (hemat 2 bulan).`
     - `expiryFromToday()` &rarr; Tanggal berakhir jika paket diaktifkan hari ini.

3. **`GymClass` (`app/Models/GymClass.php`)**
   - Merepresentasikan tabel `classes`.
   - **Relasi**: `belongsToMany(User::class, 'enrollments', 'class_id', 'user_id')`.
   - **Helper Method**:
     - `timeLabel()` &rarr; Contoh: `08:00 - 11:00 WIB`.

4. **`Checkin` (`app/Models/Checkin.php`)**
   - **Relasi**: `belongsTo(User::class)`.

5. **`Facility` (`app/Models/Facility.php`)** & **`Program` (`app/Models/Program.php`)**
   - Mengelola atribut mass-assignment `$fillable`.

6. **`GymStatus` (`app/Support/GymStatus.php`)**
   - Value Object untuk menghitung kepadatan gym berdasarkan jumlah check-in hari ini:
     - `< 10` &rarr; 'Sepi' (Banner Hijau: `#22A94F`)
     - `< 30` &rarr; 'Normal' (Banner Kuning: `#F2A900`)
     - `≥ 30` &rarr; 'Ramai' (Banner Merah: `#FF4444`)

---

## 5. Penjelasan Rinci Alur Kerja Fitur (Route &rarr; Controller &rarr; Model &rarr; DB &rarr; View)

```
┌──────────────┐      ┌────────────────────────┐      ┌──────────────────────┐
│ HTTP Request │ ───> │  Route (web.php)       │ ───> │  Controller@method   │
└──────────────┐      └────────────────────────┘      └──────────┬───────────┘
                                                                 │
                                                       Memanggil Model Eloquent
                                                                 │
                                                                 ▼
┌──────────────┐      ┌────────────────────────┐      ┌──────────────────────┐
│  HTML View   │ <─── │ resources/views/*.blade│ <─── │   Model & Database   │
│  (Browser)   │      │ (Kirim data variabel)  │      │   (Query Builder/ORM)│
└──────────────┘      └────────────────────────┘      └──────────────────────┘
```

---

### Fitur 1: Landing Page / Beranda (Home)
- **1. Route**: `GET /` &rarr; diarahkan ke `HomeController@index`.
- **2. Controller**:
  - `HomeController::index()` memanggil data:
    - `Facility::all()` untuk mengambil semua fasilitas.
    - `GymClass::all()` untuk mengambil daftar kelas.
    - `Package::all()` untuk mengambil pilihan paket membership.
    - `Auth::user()` untuk mengecek apakah ada member yang sedang login.
- **3. Model & DB**: Query dijalankan ke tabel `facilities`, `classes`, dan `packages`.
- **4. View**: Memuat view `resources/views/home/index.blade.php` dengan layout `layouts/main.blade.php`. Jika pengunjung sudah login, tombol header otomatis berubah menjadi "Dashboard" & "Keluar".

---

### Fitur 2: Registrasi Member Baru (Sign Up)
- **1. Route**:
  - `GET /signup` &rarr; `AuthController@signUpIndex` (menampilkan form registrasi).
  - `POST /signup` &rarr; `AuthController@signUp` (memproses registrasi).
- **2. Controller (`AuthController@signUp`)**:
  - Melakukan validasi input: `fullname`, `email` (harus unik di tabel `users`), `password` (minimal 8 karakter), persetujuan `terms`, dan `package_id`.
  - Mengambil model `Package` yang dipilih untuk menghitung tanggal masa aktif (`expires_at = now() + months`).
  - Menyimpan user baru ke model `User::create(...)` dengan password terenkripsi bcrypt (`Hash::make`).
  - Mengotentikasi user secara otomatis menggunakan `Auth::login($user)`.
  - Meregenerasi session ID untuk keamanan dan mengarahkan ke dashboard.
- **3. View**: `resources/views/auth/signup.blade.php` menampilkan input form floating label dan script toggle visibilitas password.

---

### Fitur 3: Autentikasi Masuk & Keluar (Sign In & Logout)
- **1. Route**:
  - `GET /signin` &rarr; `AuthController@signInIndex`.
  - `POST /signin` &rarr; `AuthController@signIn`.
  - `POST /logout` &rarr; `AuthController@logout`.
- **2. Controller**:
  - Pada `signIn()`, dijalankan validasi email & password, lalu dipanggil `Auth::attempt($credentials)`.
  - Jika berhasil, session diregenerasi dan diarahkan ke `dashboard`.
  - Jika gagal, diarahkan kembali ke form dengan pesan error *"Email atau password salah."*
  - Pada `logout()`, `Auth::logout()` dipanggil, session di-invalidate, CSRF token diregenerasi, dan pengguna diarahkan ke beranda.
- **3. View**: `resources/views/auth/signin.blade.php`.

---

### Fitur 4: Member Dashboard & Status Kepadatan Gym
- **1. Route**: `GET /dashboard` (dilindungi middleware `auth`) &rarr; `DashboardController@index`.
- **2. Controller**:
  - Mengambil data member login: `Auth::user()`.
  - Mengambil semua kelas dari model `GymClass::all()`.
  - Mengambil ID kelas yang telah diikuti member melalui relasi `$member->classes()->pluck('classes.id')`.
  - Menentukan *primary class* (kelas pertama yang diikuti) untuk ditampilkan di kartu ringkasan, dan *others* untuk daftar kelas lainnya.
  - Menghitung jumlah check-in hari ini: `Checkin::whereDate('created_at', today())->count()`, lalu menginstansiasi objek `GymStatus`.
  - Menghitung statistik member: jumlah kunjungan bulan ini (`visits`), jumlah kelas (`classes`), dan sisa hari membership (`$member->daysLeft()`).
- **3. View**: `resources/views/dashboard/index.blade.php` menampilkan:
  - Kartu Member TriGym (`M-00001`, status aktif, masa berlaku).
  - Banner dinamis status gym (Sepi/Normal/Ramai) dengan warna indikator otomatis.
  - Kartu aktivitas bulanan.

---

### Fitur 5: Pendaftaran Kelas Gym (Enroll Class)
- **1. Route**:
  - `POST /enroll` (dilindungi middleware `auth`) &rarr; `EnrollController@store`.
  - `GET /enroll` &rarr; `EnrollController@index` (redirect ke dashboard).
- **2. Controller**:
  - `EnrollController@store` menerima parameter `class_id`.
  - Memverifikasi keberadaan kelas di database via `GymClass::where('id', $classId)->exists()`.
  - Menghubungkan member dengan kelas menggunakan relasi Eloquent:
    ```php
    $member->classes()->syncWithoutDetaching([$classId]);
    ```
    Tabel pivot `enrollments` akan terisi record baru `(user_id, class_id)`.
  - Mengarahkan kembali ke `dashboard#kelas`. Tombol pada kelas yang sudah diikuti otomatis berganti menjadi **"Terdaftar"** (disabled).
- **3. View**: Terintegrasi langsung pada accordion kelas di `resources/views/dashboard/index.blade.php`.

---

### Fitur 6: Check-in Digital (Generate Code via AJAX)
- **1. Route**: `POST /generate-code` (dilindungi middleware `auth`) &rarr; `GenerateCodeController@index`.
- **2. Controller**:
  - Memeriksa otentikasi user.
  - Meng-generate string kode acak unik format TriGym:
    ```php
    $code = 'TG-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 5));
    ```
  - Menyimpan transaksi check-in ke database via model `Checkin::create(['user_id' => $user->id, 'code' => $code])`.
  - Mengembalikan respon JSON: `response()->json(['code' => $code])`.
- **3. View / Client Script**:
  - JavaScript di `resources/views/partials/scripts/dashboard.blade.php` menangkap klik pada tombol `#btn-generate`.
  - Mengirim HTTP POST via `fetch('/generate-code')` dengan header `X-CSRF-TOKEN`.
  - Menampilkan kode yang didapat langsung pada tombol selama 8 detik sebelum kembali ke teks semula.

---

### Fitur 7: CRUD Program PresidentKu
- **1. Route**:
  - `GET /programs` &rarr; `ProgramController@index` (Read + form)
  - `POST /programs` &rarr; `ProgramController@store` (Create)
  - `POST /programs/update/{id}` &rarr; `ProgramController@update` (Update)
  - `POST /programs/delete/{id}` &rarr; `ProgramController@destroy` (Delete)
- **2. Controller**:
  - **Index**: Menampilkan semua data `Program::latest()->get()`. Jika terdapat query param `?edit={id}`, controller mencari data program terkait (`Program::find($editId)`) dan menyajikannya ke form edit.
  - **Store**: Memvalidasi input `nama` dan `deskripsi`, lalu mengeksekusi `Program::create($validated)`. Mengirim pesan flash session `'Program berhasil ditambahkan.'`.
  - **Update**: Memvalidasi input, mencari data via `Program::findOrFail($id)`, dan memperbaruinya dengan `$program->update($validated)`.
  - **Destroy**: Menghapus data via `Program::findOrFail($id)->delete()`.
- **3. View**: `resources/views/programs/index.blade.php` menyatukan form input interaktif, daftar tabel responsif, dan modal konfirmasi hapus JavaScript.

---

## 6. Naskah & Panduan Video Presentasi UAS

Gunakan panduan berikut sebagai panduan berbicara saat merekam video demonstrasi:

### Segmen 1: Pembukaan (0:00 - 1:00)
> *"Halo semuanya, perkenalkan saya [Nama Mahasiswa]. Pada kesempatan kali ini, saya akan mempresentasikan proyek Ujian Akhir Semester (UAS) mata kuliah Pemrograman Web, yaitu migrasi dan implementasi aplikasi **TriGym** ke dalam framework **Laravel** dengan basis data **MySQL**.*
>
> *TriGym adalah platform kebugaran yang menyediakan informasi fasilitas, pendaftaran membership, pemilihan jadwal kelas, check-in harian digital, serta manajemen program latihan.*
>
> *Pada proyek ini, seluruh kode telah diorganisasi menggunakan arsitektur MVC (Model-View-Controller) secara terstruktur, bersih, dan sesuai standar industri."*

### Segmen 2: Konfigurasi Database & Migration (1:00 - 2:30)
*(Tampilkan file `.env` dan folder `database/migrations/` di VS Code / editor)*
> *"Pertama, mari kita lihat konfigurasi database. Pada file `.env`, kita menghubungkan aplikasi ke MySQL dengan database bernama `trigym_db`.*
>
> *Selanjutnya pada folder `database/migrations`, seluruh skema tabel dibangun menggunakan migrasi Laravel: ada tabel `packages`, `users` yang telah disesuaikan dengan atribut member gym, `facilities`, `classes`, tabel pivot `enrollments`, `checkins`, dan `programs`.*
>
> *Kita dapat menjalankan `php artisan migrate:fresh --seed` untuk membangun seluruh tabel dan mengisi data awal secara otomatis melalui DatabaseSeeder."*

### Segmen 3: Penjelasan Arsitektur & Model (2:30 - 4:00)
*(Buka file `app/Models/User.php` dan `app/Models/GymClass.php`)*
> *"Pada layer Model, kita menggunakan Eloquent ORM. Di model `User`, kita mendefinisikan relasi: satu user memiliki satu paket membership (`belongsTo Package`), dapat mengikuti banyak kelas melalui tabel pivot (`belongsToMany GymClass`), dan memiliki riwayat check-in (`hasMany Checkin`).*
>
> *Model User juga dilengkapi fungsi pembantu seperti `memberCode()` untuk menghasilkan ID member unik, serta `daysLeft()` untuk menghitung sisa masa aktif membership."*

### Segmen 4: Demonstrasi Alur Fitur (4:00 - 8:30)
*(Buka browser, tampilkan aplikasi berjalan di `http://localhost:8000`)*

1. **Halaman Beranda (`/`)**:
   > *"Di beranda, route mengarahkan ke `HomeController@index`. Controller mengambil data fasilitas, kelas, dan paket langsung dari database via Model, lalu merendernya ke view Blade `home.index`."*
2. **Registrasi & Autentikasi (`/signup` & `/signin`)**:
   > *"Pengguna dapat mendaftar dengan memilih paket membership. Data diproses oleh `AuthController@signUp`, di mana password dienkripsi secara aman dengan bcrypt, tanggal kedaluwarsa dihitung otomatis sesuai paket, dan pengguna langsung dialihkan ke dashboard."*
3. **Dashboard Member (`/dashboard`)**:
   > *"Pada dashboard, `DashboardController@index` menampilkan kartu keanggotaan digital, statistik kunjungan bulanan, dan status kepadatan gym yang dihitung dinamis oleh class `GymStatus` berdasarkan banyaknya check-in hari ini."*
4. **Pendaftaran Kelas (`/enroll`)**:
   > *"Member dapat memilih kelas olahraga yang diminati. Ketika tombol 'Ikuti' diklik, request POST dikirim ke `EnrollController@store`, yang menyimpan relasi member dan kelas ke tabel `enrollments`. Tombol kelas tersebut langsung berubah menjadi 'Terdaftar'."*
5. **Check-in Digital (`/generate-code`)**:
   > *"Saat member tiba di gym, mereka dapat mengklik tombol 'Generate Code'. Melalui panggilan AJAX ke `GenerateCodeController@index`, server membuat kode acak unik format `TG-XXXXX`, mencatatnya ke tabel `checkins`, dan menampilkannya langsung di layar."*
6. **CRUD Program PresidentKu (`/programs`)**:
   > *"Terakhir, pada halaman Program Management, kita mengimplementasikan operasi CRUD lengkap melalui `ProgramController`: kita bisa membuat program baru (Create), melihat daftarnya (Read), mengubah nama atau deskripsinya (Update), serta menghapus program (Delete) dengan konfirmasi."*

### Segmen 5: Penutup (8:30 - 9:00)
> *"Seluruh pengujian unit dan fitur telah dilakukan menggunakan automated test PHPUnit dan semuanya berhasil lulus 100%.*
>
> *Dengan demikian, migrasi aplikasi TriGym ke Laravel telah selesai dengan arsitektur yang solid, aman, dan mudah dikembangkan lebih lanjut. Sekian presentasi dari saya, terima kasih."*

---
*Dokumentasi ini dibuat untuk proyek TriGym Web Laravel — Praktikum Pemrograman Web.*
