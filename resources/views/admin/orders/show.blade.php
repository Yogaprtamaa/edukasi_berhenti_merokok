@extends('layouts.app')

@section('title', 'Detail Order Buku')

@section('content')
    <header class="mb-10 md:mb-12">
        <div class="mb-4 flex items-center gap-3 sm:mb-5 sm:gap-4">
            <span class="h-px w-8 bg-[#1A1A1A] sm:w-12"></span>
            <span class="editorial-label">Detail Order</span>
        </div>
        <h1 class="page-title">Order E-Book <span class="italic text-[#D4AF37]">#{{ $order->id }}</span></h1>
        <p class="mt-4 max-w-xl text-sm leading-relaxed text-[#6C6863]">{{ $order->book?->title }}</p>
    </header>

    <section class="grid gap-6 lg:grid-cols-2">
        <div class="card">
            <p class="editorial-label">Order</p>
            <dl class="mt-6 grid gap-4 text-sm">
                <div><dt class="text-[#6C6863]">Pengguna</dt><dd class="mt-1 font-medium text-[#1A1A1A]">{{ $order->user?->name ?? 'Pengguna dihapus' }}</dd></div>
                <div><dt class="text-[#6C6863]">Buku</dt><dd class="mt-1 font-medium text-[#1A1A1A]">{{ $order->book?->title }}</dd></div>
                <div><dt class="text-[#6C6863]">Jumlah</dt><dd class="mt-1 font-medium text-[#1A1A1A]">{{ $order->quantity }}</dd></div>
                <div><dt class="text-[#6C6863]">Total</dt><dd class="mt-1 font-medium text-[#1A1A1A]">Rp{{ number_format($order->total_price, 0, ',', '.') }}</dd></div>
            </dl>
        </div>
        <div class="card">
            <p class="editorial-label">Pembayaran</p>
            <dl class="mt-6 grid gap-4 text-sm">
                <div><dt class="text-[#6C6863]">Status Akses</dt><dd class="mt-1 font-medium text-[#1A1A1A]">{{ $order->status === 'delivered' ? 'Akses Aktif' : ($order->status === 'cancelled' ? 'Batal' : 'Pending') }}</dd></div>
                <div><dt class="text-[#6C6863]">Reference</dt><dd class="mt-1 font-medium text-[#1A1A1A]">{{ $order->payment?->reference_id ?? '-' }}</dd></div>
                <div><dt class="text-[#6C6863]">Status Payment</dt><dd class="mt-1 font-medium text-[#1A1A1A]">{{ $order->payment?->status ? ucfirst($order->payment->status) : '-' }}</dd></div>
                <div><dt class="text-[#6C6863]">Dibayar pada</dt><dd class="mt-1 font-medium text-[#1A1A1A]">{{ $order->payment?->paid_at ? \Carbon\Carbon::parse($order->payment->paid_at)->format('d M Y, H:i') : '-' }}</dd></div>
            </dl>
        </div>
    </section>

    <a href="{{ route('admin.orders') }}" class="mt-6 inline-flex editorial-link">Kembali ke Order</a>
@endsection
