<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DailyCheckIn;
use App\Models\ProgressTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckInController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['is_smoke_free' => ['required', 'boolean']]);

        $user    = Auth::user();
        $tracker = $user->progressTracker;

        if (!$tracker) {
            return redirect()->route('user.progress')->with('error', 'Set progress tracker dulu.');
        }

        $alreadyCheckedIn = DailyCheckIn::where('user_id', $user->id)
            ->whereDate('check_in_date', today())
            ->exists();

        if ($alreadyCheckedIn) {
            return redirect()->route('dashboard')->with('error', 'Sudah check-in hari ini.');
        }

        $isSmokeFree = $request->boolean('is_smoke_free');

        DailyCheckIn::create([
            'user_id'             => $user->id,
            'check_in_date'       => today(),
            'is_smoke_free'       => $isSmokeFree,
            'cigarettes_avoided'  => $isSmokeFree ? $tracker->cigarettes_per_day : 0,
            'money_saved'         => 0,
        ]);

        if ($isSmokeFree) {
            $daysSinceQuit       = now()->diffInDays($tracker->quit_date);
            $tracker->streak_days        = $tracker->streak_days + 1;
            $tracker->cigarettes_avoided = $tracker->cigarettes_per_day * $daysSinceQuit;
            $tracker->last_check_in      = today();
        } else {
            $tracker->streak_days = 0;
            $tracker->last_check_in = today();
        }

        $tracker->save();

        $msg = $isSmokeFree
            ? 'Keren! Streak kamu bertambah. Tetap semangat!'
            : 'Tidak apa-apa, besok coba lagi. Kamu pasti bisa!';

        return redirect()->route('dashboard')->with('success', $msg);
    }
}
