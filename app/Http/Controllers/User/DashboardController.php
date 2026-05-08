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
            ->whereDate('check_in_date', today())
            ->exists();

        $latestContents = Content::where('is_published', true)
            ->latest('published_at')
            ->take(5)
            ->get();

        $latestForums = Forum::withCount('forumReplies')
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        return view('user.dashboard', compact('tracker', 'checkedInToday', 'latestContents', 'latestForums'));
    }
}
