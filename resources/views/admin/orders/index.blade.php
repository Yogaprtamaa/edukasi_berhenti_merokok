@extends('layouts.app')

@section('title', 'Manajemen Order Buku')

@section('content')
    <header class="mb-10 md:mb-12">
        <div class="mb-4 flex items-center gap-3 sm:mb-5 sm:gap-4">
            <span class="h-px w-8 bg-[#1A1A1A] sm:w-12"></span>
            <span class="editorial-label">Buku</span>
        </div>
        <h1 class="page-title">Order <span class="italic text-[#D4AF37]">E-Book</span></h1>
        <p class="mt-4 max-w-xl text-sm leading-relaxed text-[#6C6863]">Pantau pembelian e-book dan akses baca pengguna.</p>
    </header>

    <section class="mb-8 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <div class="card"><p class="editorial-label">Total</p><p class="mt-4 font-serif text-4xl text-[#1A1A1A]">{{ $stats['total'] }}</p></div>
        <div class="card"><p class="editorial-label">Pending</p><p class="mt-4 font-serif text-4xl text-[#1A1A1A]">{{ $stats['pending'] }}</p></div>
        <div class="card"><p class="editorial-label">Akses Aktif</p><p class="mt-4 font-serif text-4xl text-[#1A1A1A]">{{ $stats['active'] }}</p></div>
        <div class="card border-t-4 border-t-[#D4AF37]"><p class="editorial-label">Revenue E-Book</p><p class="mt-4 font-serif text-3xl text-[#1A1A1A]">Rp{{ number_format($stats['revenue'], 0, ',', '.') }}</p></div>
    </section>

    <div class="mb-5 flex flex-wrap gap-2">
        @foreach(['' => 'Semua', 'pending' => 'Pending', 'delivered' => 'Akses Aktif', 'cancelled' => 'Batal'] as $value => $label)
            <a href="{{ $value === '' ? route('admin.orders') : route('admin.orders', ['status' => $value]) }}" class="{{ $status === $value || (!$status && $value === '') ? 'badge-yellow' : 'badge-blue' }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="card">
        @forelse($orders as $order)
            <div class="grid gap-4 border-b border-[#1A1A1A]/10 py-5 last:border-0 xl:grid-cols-[1fr_auto] xl:items-center">
                <div class="min-w-0">
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        <span class="{{ $order->status === 'delivered' ? 'badge-green' : ($order->status === 'cancelled' ? 'badge-red' : 'badge-yellow') }}">
                            {{ $order->status === 'delivered' ? 'Akses Aktif' : ($order->status === 'cancelled' ? 'Batal' : 'Pending') }}
                        </span>
                        <span class="editorial-label">{{ $order->payment?->status ? 'Payment ' . ucfirst($order->payment->status) : 'Tanpa Payment' }}</span>
                    </div>
                    <p class="truncate font-medium text-[#1A1A1A]">{{ $order->book?->title }}</p>
                    <p class="mt-1 text-sm text-[#6C6863]">{{ $order->user?->name ?? 'Pengguna dihapus' }} - {{ $order->quantity }} item - Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-2 xl:justify-end">
                    <a href="{{ route('admin.orders.show', $order) }}" class="badge-blue">Detail</a>
                    <form action="{{ route('admin.orders.status', $order) }}" method="POST" class="flex gap-2">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="border border-[#1A1A1A]/20 bg-[#F9F8F6] px-3 py-2 text-xs uppercase tracking-[0.14em] text-[#1A1A1A]">
                            <option value="pending" @selected($order->status === 'pending')>Pending</option>
                            <option value="delivered" @selected($order->status === 'delivered')>Akses Aktif</option>
                            <option value="cancelled" @selected($order->status === 'cancelled')>Batal</option>
                        </select>
                        <button class="badge-yellow cursor-pointer">Update</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="py-12 text-center text-sm text-[#6C6863]">Tidak ada order buku.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $orders->links() }}</div>
@endsection
