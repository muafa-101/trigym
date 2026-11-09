<?php

namespace App\Http\Controllers;

use App\Models\GymClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->route('dashboard.index');
    }

    public function store(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $member */
        $member = Auth::user();

        $classId = (int) $request->input('class_id', 0);
        if ($classId > 0 && GymClass::where('id', $classId)->exists()) {
            $member->classes()->syncWithoutDetaching([$classId]);
        }

        return redirect()->to(route('dashboard.index') . '#kelas');
    }
}
