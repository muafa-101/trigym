<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\GymClass;
use App\Models\Package;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $facilities = Facility::all();
        $classes = GymClass::all();
        $packages = Package::all();
        $user = Auth::user();

        return view('home.index', [
            'pageTitle' => 'TRIGYM - Solusi Kebugaran Anda',
            'header' => 'home',
            'footer' => 'legal',
            'script' => 'home',
            'user' => $user,
            'facilities' => $facilities,
            'classes' => $classes,
            'packages' => $packages,
        ]);
    }
}
