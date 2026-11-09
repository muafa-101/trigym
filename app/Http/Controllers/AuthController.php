<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /** Entry point umum /auth. */
    public function index(): RedirectResponse|View
    {
        return $this->signInIndex();
    }

    /** Menampilkan form signin. */
    public function signInIndex(): RedirectResponse|View
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }

        return view('auth.signin', [
            'pageTitle' => 'Sign In - TRIGYM',
            'header' => 'auth',
            'footer' => 'legal',
            'bodyClass' => 'flex min-h-screen flex-col',
        ]);
    }

    /** Memproses login user. */
    public function signIn(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard.index'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    /** Menampilkan form signup. */
    public function signUpIndex(Request $request): RedirectResponse|View
    {
        if (Auth::check()) {
            return redirect()->route('dashboard.index');
        }

        $packageId = (int) $request->query('paket', 0);

        return view('auth.signup', [
            'pageTitle' => 'Sign Up - TRIGYM',
            'header' => 'auth',
            'footer' => 'legal',
            'bodyClass' => 'flex min-h-screen flex-col',
            'packageId' => $packageId,
        ]);
    }

    /** Memproses pendaftaran user baru. */
    public function signUp(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'fullname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'terms' => ['accepted'],
            'package_id' => ['nullable', 'integer'],
        ], [
            'terms.accepted' => 'Anda harus menyetujui Syarat & Ketentuan.',
            'email.unique' => 'Email sudah terdaftar.',
        ]);

        $package = Package::find($request->input('package_id')) ?? Package::first();
        $expiresAt = $package ? Carbon::today()->addMonths($package->months) : Carbon::today()->addMonth();

        $user = User::create([
            'name' => $validated['fullname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status' => 'Aktif',
            'photo' => 'assets/1.jpeg',
            'package_id' => $package?->id,
            'expires_at' => $expiresAt,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard.index');
    }

    /** Logout user. */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home.index');
    }
}
