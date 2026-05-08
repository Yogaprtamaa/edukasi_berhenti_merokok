<?php

namespace App\Http\Controllers\Professional;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function index()
    {
        $professional = Auth::user()->professional;
        $schedules    = $professional->schedules()->orderBy('day_of_week')->get();

        return view('professional.schedule', compact('schedules', 'professional'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'day_of_week' => ['required', 'integer', 'between:0,6'],
            'start_time'  => ['required', 'date_format:H:i'],
            'end_time'    => ['required', 'date_format:H:i', 'after:start_time'],
            'mode'        => ['required', 'in:online,offline,hybrid'],
        ]);

        Auth::user()->professional->schedules()->create($data);

        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function destroy(Schedule $schedule)
    {
        abort_if($schedule->professional_id !== Auth::user()->professional->id, 403);
        $schedule->delete();

        return back()->with('success', 'Jadwal berhasil dihapus.');
    }
}
