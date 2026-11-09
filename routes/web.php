<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollController;
use App\Http\Controllers\GenerateCodeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProgramController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - TriGym Laravel
|--------------------------------------------------------------------------
| Seluruh rute aplikasi didaftarkan di sini dan diarahkan ke method
| Controller terkait dengan method index() sebagai pintu masuk utama.
*/

// 1. Halaman Beranda (Landing Page)
Route::get('/', [HomeController::class, 'index'])->name('home.index');

// 2. Rute Autentikasi (Sign In, Sign Up, Logout)
Route::middleware('guest')->group(function () {
    Route::get('/signin', [AuthController::class, 'signInIndex'])->name('login');
    Route::post('/signin', [AuthController::class, 'signIn']);

    Route::get('/signup', [AuthController::class, 'signUpIndex'])->name('register');
    Route::post('/signup', [AuthController::class, 'signUp']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// 3. Rute Khusus Member (Wajib Login)
Route::middleware('auth')->group(function () {
    // Dashboard Member
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Pendaftaran Kelas (Enroll)
    Route::get('/enroll', [EnrollController::class, 'index'])->name('enroll.index');
    Route::post('/enroll', [EnrollController::class, 'store'])->name('enroll.store');

    // Generate Check-in Code (AJAX)
    Route::post('/generate-code', [GenerateCodeController::class, 'index'])->name('generate-code.index');

    // CRUD Program Management (Fitur PresidentKu)
    Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
    Route::post('/programs', [ProgramController::class, 'store'])->name('programs.store');
    Route::post('/programs/update/{id}', [ProgramController::class, 'update'])->name('programs.update');
    Route::post('/programs/delete/{id}', [ProgramController::class, 'destroy'])->name('programs.destroy');
});

// 4. Kompatibilitas URL Lama (.php)
Route::redirect('/index.php', '/');
Route::redirect('/dashboard.php', '/dashboard');
Route::redirect('/signin.php', '/signin');
Route::redirect('/signup.php', '/signup');
Route::redirect('/programs.php', '/programs');
Route::post('/generate-code.php', [GenerateCodeController::class, 'index'])->middleware('auth');
Route::post('/enroll.php', [EnrollController::class, 'store'])->middleware('auth');
Route::post('/logout.php', [AuthController::class, 'logout'])->middleware('auth');
