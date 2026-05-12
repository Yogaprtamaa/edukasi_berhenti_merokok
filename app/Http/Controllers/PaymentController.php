<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index()
    {
        $baseQuery = Payment::where('user_id', Auth::id());

        $payments = Payment::with(['order.book', 'appointment.professional.user'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => (clone $baseQuery)->count(),
            'pending' => (clone $baseQuery)->where('status', 'pending')->count(),
            'paid_amount' => (clone $baseQuery)->where('status', 'success')->sum('amount'),
        ];

        return view('payments.index', compact('payments', 'stats'));
    }

    public function show(Payment $payment)
    {
        abort_unless($payment->user_id === Auth::id(), 403);

        $payment->load(['order.book', 'appointment.professional.user']);

        return view('payments.show', compact('payment'));
    }

    public function pay(Payment $payment)
    {
        abort_unless($payment->user_id === Auth::id(), 403);

        if ($payment->status !== 'success') {
            $payment->update([
                'status' => 'success',
                'paid_at' => now(),
            ]);

            if ($payment->order) {
                $payment->order->update(['status' => 'delivered']);
            }
        }

        if ($payment->order) {
            return redirect()->route('books.read', $payment->order->book)
                ->with('success', 'Pembayaran berhasil. Buku sudah masuk ke Buku Saya.');
        }

        return redirect()->route('payments.show', $payment)
            ->with('success', 'Pembayaran berhasil diproses.');
    }
}
