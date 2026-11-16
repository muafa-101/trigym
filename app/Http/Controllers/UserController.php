<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * READ: Menampilkan daftar seluruh user / member
     */
    public function index(): View
    {
        $member = Auth::user();

        return view('users.index', [
            'pageTitle' => 'TRIGYM - Pengelolaan User & Member',
            'header' => 'dashboard',
            'footer' => 'app',
            'bodyClass' => 'min-h-screen flex flex-col',
            'member' => $member,
            'users' => User::with('package')->latest()->get(),
        ]);
    }

    /**
     * FORM: Menampilkan form tambah user baru
     */
    public function create(): View
    {
        $member = Auth::user();
        $packages = Package::all();

        return view('users.create', [
            'pageTitle' => 'TRIGYM - Tambah User Baru',
            'header' => 'dashboard',
            'footer' => 'app',
            'bodyClass' => 'min-h-screen flex flex-col',
            'member' => $member,
            'packages' => $packages,
        ]);
    }

    /**
     * CREATE: Menyimpan user baru ke database
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'status' => ['required', 'string', 'in:Aktif,Nonaktif'],
            'package_id' => ['nullable', 'exists:packages,id'],
            'expires_at' => ['nullable', 'date'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['photo'] = 'assets/logo.png';

        if (! empty($validated['package_id']) && empty($validated['expires_at'])) {
            $package = Package::find($validated['package_id']);
            if ($package) {
                $validated['expires_at'] = Carbon::today()->addMonths($package->months);
            }
        }

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'User / Member baru berhasil ditambahkan.');
    }

    /**
     * FORM EDIT: Menampilkan form edit informasi user
     */
    public function edit(int|string $id): View
    {
        $member = Auth::user();
        $targetUser = User::findOrFail($id);
        $packages = Package::all();

        return view('users.edit', [
            'pageTitle' => 'TRIGYM - Edit Informasi User',
            'header' => 'dashboard',
            'footer' => 'app',
            'bodyClass' => 'min-h-screen flex flex-col',
            'member' => $member,
            'targetUser' => $targetUser,
            'packages' => $packages,
        ]);
    }

    /**
     * UPDATE: Menyimpan perubahan data user ke database
     */
    public function update(Request $request, int|string $id): RedirectResponse
    {
        $targetUser = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($targetUser->id)],
            'password' => ['nullable', 'string', 'min:8'],
            'status' => ['required', 'string', 'in:Aktif,Nonaktif'],
            'package_id' => ['nullable', 'exists:packages,id'],
            'expires_at' => ['nullable', 'date'],
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if (! empty($validated['package_id']) && empty($validated['expires_at']) && $targetUser->package_id !== (int) $validated['package_id']) {
            $package = Package::find($validated['package_id']);
            if ($package) {
                $validated['expires_at'] = Carbon::today()->addMonths($package->months);
            }
        }

        $targetUser->update($validated);

        return redirect()->route('users.index')->with('success', 'Informasi user berhasil diperbarui.');
    }

    /**
     * DELETE: Menghapus user dari database
     */
    public function destroy(int|string $id): RedirectResponse
    {
        $targetUser = User::findOrFail($id);

        if (Auth::id() === $targetUser->id) {
            return redirect()->route('users.index')->withErrors(['error' => 'Anda tidak dapat menghapus akun yang sedang digunakan untuk login.']);
        }

        $targetUser->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
