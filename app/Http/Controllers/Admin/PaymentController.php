<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $payments = Payment::with(['user', 'order.book', 'appointment.professional.user'])
            ->when($status, fn($query) => $query->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total' => Payment::count(),
            'pending' => Payment::where('status', 'pending')->count(),
            'success' => Payment::where('status', 'success')->count(),
            'revenue' => Payment::where('status', 'success')->sum('amount'),
        ];

        return view('admin.payments.index', compact('payments', 'stats', 'status'));
    }

    public function show(Payment $payment)
    {
        $payment->load(['user', 'order.book', 'appointment.professional.user']);

        return view('admin.payments.show', compact('payment'));
    }

    public function updateStatus(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,success,failed,cancelled'],
        ]);

        $update = ['status' => $data['status']];

        if ($data['status'] === 'success') {
            $update['paid_at'] = $payment->paid_at ?? now();
        } else {
            $update['paid_at'] = null;
        }

        $payment->update($update);

        if ($payment->order) {
            $payment->order->update([
                'status' => match ($data['status']) {
                    'success' => 'delivered',
                    'cancelled' => 'cancelled',
                    default => 'pending',
                },
            ]);
        }

        return back()->with('success', 'Status pembayaran berhasil diperbarui.');
    }
}
