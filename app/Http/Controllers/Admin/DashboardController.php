<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Content;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Professional;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUsers        = User::where('role', 'user')->count();
        $newUsersThisMonth = User::where('role', 'user')
            ->whereMonth('created_at', now()->month)
            ->count();

        $totalProfessionals   = Professional::where('is_verified', true)->count();
        $pendingProfessionals = Professional::where('is_verified', false)->count();

        $pendingContents = Content::where('approval_status', 'pending')->count();

        $totalAppointments = Appointment::count();
        $pendingAppointments = Appointment::where('status', 'pending')->count();
        $pendingPayments = Payment::where('status', 'pending')->count();
        $totalOrders = Order::count();
        $totalRevenue = Payment::where('status', 'success')->sum('amount');

        $pendingProfessionalList = Professional::with('user')
            ->where('is_verified', false)
            ->latest()
            ->take(5)
            ->get();

        $pendingContentList = Content::with('uploader')
            ->where('approval_status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recentPayments = Payment::with(['user', 'order.book', 'appointment.professional.user'])
            ->latest()
            ->take(5)
            ->get();

        $revenueChart = collect(range(5, 0))->map(function ($monthOffset) {
            $date = now()->subMonths($monthOffset);
            $amount = Payment::where('status', 'success')
                ->whereYear('paid_at', $date->year)
                ->whereMonth('paid_at', $date->month)
                ->sum('amount');

            return [
                'label' => $date->format('M'),
                'amount' => (float) $amount,
            ];
        });

        $maxRevenue = max($revenueChart->max('amount'), 1);

        $paymentStatusChart = collect(['pending', 'success', 'failed', 'cancelled'])
            ->map(fn($status) => [
                'label' => ucfirst($status),
                'count' => Payment::where('status', $status)->count(),
            ]);

        $appointmentStatusChart = collect(['pending', 'confirmed', 'completed', 'cancelled'])
            ->map(fn($status) => [
                'label' => ucfirst($status),
                'count' => Appointment::where('status', $status)->count(),
            ]);

        $transactionTypeChart = [
            'ebook' => Payment::whereHas('order')->count(),
            'consultation' => Payment::whereNotNull('appointment_id')->count(),
        ];
        $transactionTypeTotal = max(array_sum($transactionTypeChart), 1);

        return view('admin.dashboard', compact(
            'totalUsers',
            'newUsersThisMonth',
            'totalProfessionals',
            'pendingProfessionals',
            'pendingContents',
            'totalAppointments',
            'pendingAppointments',
            'pendingPayments',
            'totalOrders',
            'totalRevenue',
            'pendingProfessionalList',
            'pendingContentList',
            'recentPayments',
            'revenueChart',
            'maxRevenue',
            'paymentStatusChart',
            'appointmentStatusChart',
            'transactionTypeChart',
            'transactionTypeTotal'
        ));
    }
}
