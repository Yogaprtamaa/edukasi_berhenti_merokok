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

        $confirmedAppointments = Appointment::where('professional_id', $professional->id)
            ->where('status', 'confirmed')
            ->count();

        $completedAppointments = Appointment::where('professional_id', $professional->id)
            ->where('status', 'completed')
            ->count();

        $todayAppointmentList = Appointment::with(['user', 'payment'])
            ->where('professional_id', $professional->id)
            ->whereDate('appointment_date', today())
            ->get();

        $monthlyEarnings = Payment::whereHas('appointment', fn($q) => $q->where('professional_id', $professional->id))
            ->where('status', 'success')
            ->whereMonth('paid_at', now()->month)
            ->sum('amount');

        $schedules = $professional->schedules()->orderBy('day_of_week')->get();

        $earningsChart = collect(range(5, 0))->map(function ($monthOffset) use ($professional) {
            $date = now()->subMonths($monthOffset);
            $amount = Payment::whereHas('appointment', fn($query) => $query->where('professional_id', $professional->id))
                ->where('status', 'success')
                ->whereYear('paid_at', $date->year)
                ->whereMonth('paid_at', $date->month)
                ->sum('amount');

            return [
                'label' => $date->format('M'),
                'amount' => (float) $amount,
            ];
        });

        $maxEarnings = max($earningsChart->max('amount'), 1);

        $appointmentStatusChart = collect(['pending', 'confirmed', 'completed', 'cancelled'])
            ->map(fn($status) => [
                'label' => ucfirst($status),
                'count' => Appointment::where('professional_id', $professional->id)
                    ->where('status', $status)
                    ->count(),
            ]);

        $paymentStatusChart = collect(['pending', 'success', 'failed', 'cancelled'])
            ->map(fn($status) => [
                'label' => ucfirst($status),
                'count' => Payment::whereHas('appointment', fn($query) => $query->where('professional_id', $professional->id))
                    ->where('status', $status)
                    ->count(),
            ]);

        return view('professional.dashboard', compact(
            'professional',
            'totalAppointments',
            'pendingAppointments',
            'confirmedAppointments',
            'completedAppointments',
            'todayAppointments',
            'todayAppointmentList',
            'monthlyEarnings',
            'schedules',
            'earningsChart',
            'maxEarnings',
            'appointmentStatusChart',
            'paymentStatusChart'
        ));
    }
}
