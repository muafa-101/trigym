<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProgramController extends Controller
{
    /**
     * READ: Menampilkan daftar seluruh program
     */
    public function index(Request $request): View|RedirectResponse
    {
        if ($request->filled('edit')) {
            return redirect()->route('programs.edit', $request->query('edit'));
        }

        $member = Auth::user();

        return view('programs.index', [
            'pageTitle' => 'TRIGYM - Program Management',
            'header' => 'dashboard',
            'footer' => 'app',
            'bodyClass' => 'min-h-screen flex flex-col',
            'member' => $member,
            'programs' => Program::latest()->get(),
        ]);
    }

    /**
     * FORM: Menampilkan form tambah program baru
     */
    public function create(): View
    {
        $member = Auth::user();

        return view('programs.create', [
            'pageTitle' => 'TRIGYM - Tambah Program Baru',
            'header' => 'dashboard',
            'footer' => 'app',
            'bodyClass' => 'min-h-screen flex flex-col',
            'member' => $member,
        ]);
    }

    /**
     * CREATE: Menyimpan program baru ke database
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
        ]);

        Program::create($validated);

        return redirect()->route('programs.index')->with('success', 'Program berhasil ditambahkan.');
    }

    /**
     * FORM EDIT: Menampilkan form edit program
     */
    public function edit(int|string $id): View
    {
        $member = Auth::user();
        $program = Program::findOrFail($id);

        return view('programs.edit', [
            'pageTitle' => 'TRIGYM - Edit Program',
            'header' => 'dashboard',
            'footer' => 'app',
            'bodyClass' => 'min-h-screen flex flex-col',
            'member' => $member,
            'program' => $program,
        ]);
    }

    /**
     * UPDATE: Menyimpan pembaruan program ke database
     */
    public function update(Request $request, int|string $id): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
        ]);

        $program = Program::findOrFail($id);
        $program->update($validated);

        return redirect()->route('programs.index')->with('success', 'Program berhasil diperbarui.');
    }

    /**
     * DELETE: Menghapus program dari database
     */
    public function destroy(int|string $id): RedirectResponse
    {
        $program = Program::findOrFail($id);
        $program->delete();

        return redirect()->route('programs.index')->with('success', 'Program berhasil dihapus.');
    }
}
