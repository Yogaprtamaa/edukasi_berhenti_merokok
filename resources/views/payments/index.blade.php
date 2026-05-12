@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
    <div class="mb-8 flex flex-col gap-5 border-b border-[#1A1A1A]/10 pb-8 md:flex-row md:items-end md:justify-between">
        <div>
            <span class="editorial-label">Transaksi</span>
            <h1 class="mt-2 page-title">Pembayaran</h1>
            <p class="mt-2 text-sm text-[#6C6863]">Riwayat invoice buku dan konsultasi Anda</p>
        </div>
        <a href="{{ route('books.purchased') }}" class="btn-secondary self-start md:self-auto">Buku Saya</a>
    </div>

    <div class="mb-8 grid gap-px bg-[#1A1A1A]/10 md:grid-cols-3">
        <div class="bg-[#F9F8F6] p-5">
            <span class="editorial-label">Invoice</span>
            <p class="mt-2 font-serif text-3xl text-[#1A1A1A]">{{ $stats['total'] }}</p>
        </div>
        <div class="bg-[#F9F8F6] p-5">
            <span class="editorial-label">Menunggu</span>
            <p class="mt-2 font-serif text-3xl text-[#1A1A1A]">{{ $stats['pending'] }}</p>
        </div>
        <div class="bg-[#F9F8F6] p-5">
            <span class="editorial-label">Sudah Dibayar</span>
            <p class="mt-2 font-serif text-3xl text-[#D4AF37]">Rp{{ number_format($stats['paid_amount'], 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="space-y-3">
        @forelse($payments as $payment)
            @php
                $itemTitle = $payment->order
                    ? 'Buku: ' . $payment->order->book->title
                    : 'Konsultasi: ' . ($payment->appointment?->professional?->user?->name ?? 'Profesional');
            @endphp
            <div class="border border-[#1A1A1A]/10 bg-[#F9F8F6] p-5">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="editorial-label">{{ $payment->reference_id }}</span>
                        @if($payment->status === 'success')
                            <span class="badge-green">Lunas</span>
                        @else
                            <span class="badge-yellow">Menunggu</span>
                        @endif
                    </div>
                    <h3 class="mt-2 font-serif text-lg text-[#1A1A1A] transition-colors duration-500 group-hover:text-[#D4AF37]">{{ $itemTitle }}</h3>
                    <p class="mt-1 text-sm text-[#6C6863]">{{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y H:i') }}</p>
                </div>
                <div class="text-left lg:text-right">
                    <p class="font-serif text-2xl text-[#1A1A1A]">Rp{{ number_format($payment->amount, 0, ',', '.') }}</p>
                    <span class="editorial-label">{{ str_replace('_', ' ', $payment->payment_method) }}</span>
                </div>
                </div>
                <div class="mt-5 flex flex-wrap gap-3">
                    <a href="{{ route('payments.show', $payment) }}" class="btn-secondary">Detail Invoice</a>
                    @if($payment->status !== 'success')
                        <a href="{{ route('payments.show', $payment) }}" class="btn-primary"><span>Bayar</span></a>
                    @elseif($payment->order)
                        <a href="{{ route('books.read', $payment->order->book) }}" class="btn-primary"><span>Baca Buku</span></a>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-20 text-center text-[#6C6863]">
                <p class="font-serif text-xl">Belum ada pembayaran.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">{{ $payments->links() }}</div>
@endsection
