<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use Illuminate\Support\Facades\Auth;

class BadgeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $earned = $user->userBadges()
            ->with('badge')
            ->get();

        $badges = Badge::all();

        return view('santri.badges.index', compact(
            'user',
            'earned',
            'badges'
        ));
    }
}