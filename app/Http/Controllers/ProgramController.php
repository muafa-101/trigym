<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProgramController extends Controller
{
    public function index(Request $request): View
    {
        $member = Auth::user();

        $editId = (int) $request->query('edit', 0);
        $editItem = $editId > 0 ? Program::find($editId) : null;

        return view('programs.index', [
            'pageTitle' => 'TRIGYM - Program Management',
            'header' => 'dashboard',
            'footer' => 'app',
            'bodyClass' => 'min-h-screen flex flex-col',
            'member' => $member,
            'programs' => Program::latest()->get(),
            'editItem' => $editItem,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
        ]);

        Program::create($validated);

        return redirect()->route('programs.index')->with('success', 'Program berhasil ditambahkan.');
    }

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

    public function destroy(int|string $id): RedirectResponse
    {
        $program = Program::findOrFail($id);
        $program->delete();

        return redirect()->route('programs.index')->with('success', 'Program berhasil dihapus.');
    }
}
