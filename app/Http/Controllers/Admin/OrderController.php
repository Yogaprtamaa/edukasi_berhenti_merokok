<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');

        $orders = Order::with(['user', 'book', 'payment'])
            ->when($status, fn($query) => $query->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'active' => Order::where('status', 'delivered')->count(),
            'revenue' => Order::whereHas('payment', fn($query) => $query->where('status', 'success'))->sum('total_price'),
        ];

        return view('admin.orders.index', compact('orders', 'stats', 'status'));
    }

    public function show(Order $order)
    {
        $order->load(['user', 'book', 'payment']);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,delivered,cancelled'],
        ]);

        $order->update(['status' => $data['status']]);

        return back()->with('success', 'Status order berhasil diperbarui.');
    }
}
