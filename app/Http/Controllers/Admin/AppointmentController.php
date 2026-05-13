<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $appointments = Appointment::with(['user', 'professional.user', 'payment'])
            ->when($status, fn($query) => $query->where('status', $status))
            ->latest('appointment_date')
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total' => Appointment::count(),
            'pending' => Appointment::where('status', 'pending')->count(),
            'confirmed' => Appointment::where('status', 'confirmed')->count(),
            'completed' => Appointment::where('status', 'completed')->count(),
        ];

        return view('admin.appointments.index', compact('appointments', 'stats', 'status'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,confirmed,completed,cancelled'],
        ]);

        $appointment->update(['status' => $data['status']]);

        return back()->with('success', 'Status janji temu berhasil diperbarui.');
    }
}
