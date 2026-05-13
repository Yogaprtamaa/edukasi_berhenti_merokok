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
        $appointments = Appointment::with(['user', 'payment'])
            ->where('professional_id', $professional->id)
            ->latest('appointment_date')
            ->paginate(20);

        return view('professional.appointments', compact('appointments'));
    }

    public function confirm(Appointment $appointment)
    {
        $professional = Auth::user()->professional;
        abort_if($appointment->professional_id !== $professional->id, 403);

        if ($appointment->payment?->status !== 'success') {
            return back()->with('error', 'Pembayaran belum sukses, janji temu belum bisa dikonfirmasi.');
        }

        if ($appointment->status !== 'pending') {
            return back()->with('error', 'Hanya janji temu pending yang bisa dikonfirmasi.');
        }

        $appointment->update(['status' => 'confirmed']);

        return back()->with('success', 'Janji temu berhasil dikonfirmasi.');
    }

    public function complete(Appointment $appointment)
    {
        $professional = Auth::user()->professional;
        abort_if($appointment->professional_id !== $professional->id, 403);

        if ($appointment->status !== 'confirmed') {
            return back()->with('error', 'Janji temu harus dikonfirmasi terlebih dahulu.');
        }

        $appointment->update(['status' => 'completed']);

        return back()->with('success', 'Janji temu ditandai selesai.');
    }

    public function cancel(Appointment $appointment)
    {
        $professional = Auth::user()->professional;
        abort_if($appointment->professional_id !== $professional->id, 403);

        if ($appointment->status === 'completed') {
            return back()->with('error', 'Janji temu yang sudah selesai tidak bisa dibatalkan.');
        }

        $appointment->update(['status' => 'cancelled']);

        return back()->with('success', 'Janji temu dibatalkan.');
    }
}
