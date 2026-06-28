<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\Professional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ConsultationController extends Controller
{
    public function index()
    {
        $professionals = Professional::with('user')
            ->where('is_verified', true)
            ->get();

        return view('consultations.index', compact('professionals'));
    }

    public function show(Professional $professional)
    {
        abort_if(!$professional->is_verified, 404);
        $schedules = $professional->schedules()->orderBy('day_of_week')->get();
        return view('consultations.show', compact('professional', 'schedules'));
    }

    public function appointments()
    {
        $appointments = Appointment::with(['professional.user', 'payment'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('consultations.appointments', compact('appointments'));
    }

    public function book(Request $request, Professional $professional)
    {
        abort_if(!$professional->is_verified, 404);

        $data = $request->validate([
            'schedule_id'      => ['required', 'exists:schedules,id'],
            'appointment_date' => ['required', 'date', 'after:today'],
            'mode'             => ['required', 'in:online,offline'],
            'duration_hours'   => ['required', 'numeric', 'min:1', 'max:8'],
            'payment_method'   => ['required', 'in:transfer,e-wallet,credit_card'],
        ]);

        $schedule = $professional->schedules()->findOrFail($data['schedule_id']);

        if ($schedule->mode !== 'hybrid' && $schedule->mode !== $data['mode']) {
            return back()
                ->withInput()
                ->with('error', 'Mode konsultasi tidak sesuai dengan jadwal profesional.');
        }

        $appointment = Appointment::create([
            'user_id'          => Auth::id(),
            'professional_id'  => $professional->id,
            'schedule_id'      => $data['schedule_id'],
            'appointment_date' => $data['appointment_date'],
            'mode'             => $data['mode'],
            'status'           => 'pending',
        ]);

        $payment = Payment::create([
            'appointment_id' => $appointment->id,
            'user_id'        => Auth::id(),
            'reference_id'   => 'CONS-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6)),
            'amount'         => $professional->hourly_rate * $data['duration_hours'],
            'duration_hours' => $data['duration_hours'],
            'status'         => 'pending',
            'payment_method' => $data['payment_method'],
            'description'    => 'Booking konsultasi #' . $appointment->id,
        ]);

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Booking berhasil dibuat. Silakan selesaikan pembayaran.');
    }
}
