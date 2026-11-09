<?php

namespace App\Http\Controllers;

use App\Models\Checkin;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GenerateCodeController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Belum login.'], 401);
        }

        $code = 'TG-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 5));

        Checkin::create([
            'user_id' => $user->id,
            'code' => $code,
        ]);

        return response()->json(['code' => $code]);
    }
}
