@extends('layouts.app')

@section('title', 'Detail Pembayaran')

@section('content')
    <header class="mb-10 md:mb-12">
        <div class="mb-4 flex items-center gap-3 sm:mb-5 sm:gap-4">
            <span class="h-px w-8 bg-[#1A1A1A] sm:w-12"></span>
            <span class="editorial-label">Detail Transaksi</span>
        </div>
        <h1 class="page-title">Pembayaran <span class="italic text-[#D4AF37]">{{ $payment->reference_id }}</span></h1>
        <p class="mt-4 max-w-xl text-sm leading-relaxed text-[#6C6863]">{{ $payment->description }}</p>
    </header>

    <section class="grid gap-6 lg:grid-cols-2">
        <div class="card">
            <p class="editorial-label">Ringkasan</p>
            <dl class="mt-6 grid gap-4 text-sm">
                <div><dt class="text-[#6C6863]">Status</dt><dd class="mt-1 font-medium text-[#1A1A1A]">{{ ucfirst($payment->status) }}</dd></div>
                <div><dt class="text-[#6C6863]">Nominal</dt><dd class="mt-1 font-medium text-[#1A1A1A]">Rp{{ number_format($payment->amount, 0, ',', '.') }}</dd></div>
                <div><dt class="text-[#6C6863]">Metode</dt><dd class="mt-1 font-medium text-[#1A1A1A]">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</dd></div>
                <div><dt class="text-[#6C6863]">Dibayar pada</dt><dd class="mt-1 font-medium text-[#1A1A1A]">{{ $payment->paid_at ? \Carbon\Carbon::parse($payment->paid_at)->format('d M Y, H:i') : '-' }}</dd></div>
            </dl>
        </div>
        <div class="card">
            <p class="editorial-label">Relasi</p>
            <dl class="mt-6 grid gap-4 text-sm">
                <div><dt class="text-[#6C6863]">Pengguna</dt><dd class="mt-1 font-medium text-[#1A1A1A]">{{ $payment->user?->name ?? 'Pengguna dihapus' }}</dd></div>
                @if($payment->order)
                    <div><dt class="text-[#6C6863]">Order E-Book</dt><dd class="mt-1 font-medium text-[#1A1A1A]">{{ $payment->order->book?->title }} x{{ $payment->order->quantity }}</dd></div>
                    <div><dt class="text-[#6C6863]">Status Akses</dt><dd class="mt-1 font-medium text-[#1A1A1A]">{{ $payment->order->status === 'delivered' ? 'Akses Aktif' : ($payment->order->status === 'cancelled' ? 'Batal' : 'Pending') }}</dd></div>
                @endif
                @if($payment->appointment)
                    <div><dt class="text-[#6C6863]">Profesional</dt><dd class="mt-1 font-medium text-[#1A1A1A]">{{ $payment->appointment->professional?->user?->name }}</dd></div>
                    <div><dt class="text-[#6C6863]">Janji Temu</dt><dd class="mt-1 font-medium text-[#1A1A1A]">{{ \Carbon\Carbon::parse($payment->appointment->appointment_date)->format('d M Y, H:i') }}</dd></div>
                @endif
            </dl>
        </div>
    </section>

    <a href="{{ route('admin.payments') }}" class="mt-6 inline-flex editorial-link">Kembali ke Pembayaran</a>
@endsection
