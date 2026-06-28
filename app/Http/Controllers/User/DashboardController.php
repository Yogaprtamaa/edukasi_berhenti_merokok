<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Content;
use App\Models\DailyCheckIn;
use App\Models\Forum;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $tracker = $user->progressTracker;

        $checkedInToday = DailyCheckIn::where('user_id', $user->id)
            ->onDate(today())
            ->exists();

        $latestContents = Content::where('is_published', true)
            ->latest('published_at')
            ->take(5)
            ->get();

        $latestForums = Forum::with('user')
            ->latest()
            ->take(5)
            ->get();

        // MongoDB tidak mendukung withCount lintas-koleksi; hitung via relasi.
        $latestForums->each(function ($forum) {
            $forum->forum_replies_count = $forum->forumReplies()->count();
        });

        return view('user.dashboard', compact('tracker', 'checkedInToday', 'latestContents', 'latestForums'));
    }
}
