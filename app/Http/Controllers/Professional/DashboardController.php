<?php

namespace App\Http\Controllers\Professional;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user         = Auth::user();
        $professional = $user->professional;

        if (!$professional) {
            return redirect()->route('professional.setup');
        }

        $totalAppointments   = Appointment::where('professional_id', $professional->id)->count();
        $pendingAppointments = Appointment::where('professional_id', $professional->id)
            ->where('status', 'pending')->count();

        $todayAppointments = Appointment::where('professional_id', $professional->id)
            ->whereDate('appointment_date', today())
            ->count();

        $todayAppointmentList = Appointment::with('user')
            ->where('professional_id', $professional->id)
            ->whereDate('appointment_date', today())
            ->get();

        $monthlyEarnings = Payment::whereHas('appointment', fn($q) => $q->where('professional_id', $professional->id))
            ->where('status', 'success')
            ->whereMonth('paid_at', now()->month)
            ->sum('amount');

        $schedules = $professional->schedules()->orderBy('day_of_week')->get();

        return view('professional.dashboard', compact(
            'professional',
            'totalAppointments',
            'pendingAppointments',
            'todayAppointments',
            'todayAppointmentList',
            'monthlyEarnings',
            'schedules'
        ));
    }
}
