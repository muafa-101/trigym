<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollController;
use App\Http\Controllers\GenerateCodeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - TriGym Laravel
|--------------------------------------------------------------------------
| Seluruh rute aplikasi didaftarkan di sini dan diarahkan ke method
| Controller terkait dengan arsitektur RESTful CRUD (Pertemuan 10).
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

    // CRUD Program Management (Pertemuan 10 Web Programming)
    Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
    Route::get('/programs/create', [ProgramController::class, 'create'])->name('programs.create');
    Route::post('/programs', [ProgramController::class, 'store'])->name('programs.store');
    Route::get('/programs/{id}/edit', [ProgramController::class, 'edit'])->name('programs.edit');
    Route::post('/programs/update/{id}', [ProgramController::class, 'update'])->name('programs.update');
    Route::put('/programs/{id}', [ProgramController::class, 'update'])->name('programs.update.put');
    Route::post('/programs/delete/{id}', [ProgramController::class, 'destroy'])->name('programs.destroy');
    Route::delete('/programs/{id}', [ProgramController::class, 'destroy'])->name('programs.destroy.delete');

    // CRUD Pengelolaan Informasi User & Member
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::post('/users/update/{id}', [UserController::class, 'update'])->name('users.update');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update.put');
    Route::post('/users/delete/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy.delete');
});

// 4. Kompatibilitas URL Lama (.php)
Route::redirect('/index.php', '/');
Route::redirect('/dashboard.php', '/dashboard');
Route::redirect('/signin.php', '/signin');
Route::redirect('/signup.php', '/signup');
Route::redirect('/programs.php', '/programs');
Route::redirect('/users.php', '/users');
Route::post('/generate-code.php', [GenerateCodeController::class, 'index'])->middleware('auth');
Route::post('/enroll.php', [EnrollController::class, 'store'])->middleware('auth');
Route::post('/logout.php', [AuthController::class, 'logout'])->middleware('auth');
