<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ProgressTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    public function index()
    {
        $tracker = Auth::user()->progressTracker;
        return view('user.progress', compact('tracker'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'quit_date'          => ['required', 'date', 'before_or_equal:today'],
            'cigarettes_per_day' => ['required', 'integer', 'min:1'],
            'price_per_pack'     => ['required', 'numeric', 'min:0'],
            'cigarettes_per_pack'=> ['required', 'integer', 'min:1'],
        ]);

        ProgressTracker::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'quit_date'          => $data['quit_date'],
                'streak_days'        => 0,
                'cigarettes_per_day' => $data['cigarettes_per_day'],
                'cigarettes_avoided' => 0,
                'money_saved'        => 0,
                'last_check_in'      => null,
            ]
        );

        return redirect()->route('dashboard')->with('success', 'Progress tracker berhasil diset!');
    }
}
