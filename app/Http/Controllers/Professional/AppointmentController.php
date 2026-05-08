<?php

namespace App\Http\Controllers\Professional;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index()
    {
        $professional = Auth::user()->professional;
        $appointments = Appointment::with('user')
            ->where('professional_id', $professional->id)
            ->latest('appointment_date')
            ->paginate(20);

        return view('professional.appointments', compact('appointments'));
    }

    public function confirm(Appointment $appointment)
    {
        $professional = Auth::user()->professional;
        abort_if($appointment->professional_id !== $professional->id, 403);

        $appointment->update(['status' => 'confirmed']);

        return back()->with('success', 'Janji temu berhasil dikonfirmasi.');
    }
}
