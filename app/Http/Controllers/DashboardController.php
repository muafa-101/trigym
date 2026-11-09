<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use App\Models\GymClass;
use App\Support\GymStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /** @var \App\Models\User $member */
        $member = Auth::user();

        $allClasses = GymClass::all();
        $enrolledIds = $member->classes()->pluck('classes.id')->toArray();

        $primary = null;
        if (!empty($enrolledIds)) {
            $primary = $allClasses->firstWhere('id', $enrolledIds[0]);
        }

        $others = $allClasses->filter(function ($c) use ($primary) {
            return $primary === null || $c->id !== $primary->id;
        })->values();

        $checkinsTodayCount = Checkin::whereDate('created_at', Carbon::today())->count();
        $gymStatus = new GymStatus($checkinsTodayCount);

        $visitsThisMonth = Checkin::where('user_id', $member->id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $stats = [
            'visits' => $visitsThisMonth,
            'classes' => count($enrolledIds),
            'daysLeft' => $member->daysLeft(),
        ];

        return view('dashboard.index', [
            'pageTitle' => 'TRIGYM - Member Dashboard',
            'header' => 'dashboard',
            'footer' => 'app',
            'script' => 'dashboard',
            'bodyClass' => 'min-h-screen flex flex-col',
            'member' => $member,
            'gymStatus' => $gymStatus,
            'primary' => $primary,
            'others' => $others,
            'enrolledIds' => $enrolledIds,
            'stats' => $stats,
        ]);
    }
}
