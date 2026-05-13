@extends('layouts.app')

@section('title', 'Manajemen Pembayaran')

@section('content')
    <header class="mb-10 md:mb-12">
        <div class="mb-4 flex items-center gap-3 sm:mb-5 sm:gap-4">
            <span class="h-px w-8 bg-[#1A1A1A] sm:w-12"></span>
            <span class="editorial-label">Transaksi</span>
        </div>
        <h1 class="page-title">Manajemen <span class="italic text-[#D4AF37]">Pembayaran</span></h1>
        <p class="mt-4 max-w-xl text-sm leading-relaxed text-[#6C6863]">Pantau pembayaran buku dan konsultasi dari seluruh pengguna.</p>
    </header>

    <section class="mb-8 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <div class="card"><p class="editorial-label">Total</p><p class="mt-4 font-serif text-4xl text-[#1A1A1A]">{{ $stats['total'] }}</p></div>
        <div class="card"><p class="editorial-label">Pending</p><p class="mt-4 font-serif text-4xl text-[#1A1A1A]">{{ $stats['pending'] }}</p></div>
        <div class="card"><p class="editorial-label">Sukses</p><p class="mt-4 font-serif text-4xl text-[#1A1A1A]">{{ $stats['success'] }}</p></div>
        <div class="card border-t-4 border-t-[#D4AF37]"><p class="editorial-label">Revenue</p><p class="mt-4 font-serif text-3xl text-[#1A1A1A]">Rp{{ number_format($stats['revenue'], 0, ',', '.') }}</p></div>
    </section>

    <div class="mb-5 flex flex-wrap gap-2">
        @foreach(['' => 'Semua', 'pending' => 'Pending', 'success' => 'Sukses', 'failed' => 'Gagal', 'cancelled' => 'Batal'] as $value => $label)
            <a href="{{ $value === '' ? route('admin.payments') : route('admin.payments', ['status' => $value]) }}" class="{{ $status === $value || (!$status && $value === '') ? 'badge-yellow' : 'badge-blue' }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="card">
        @forelse($payments as $payment)
            <div class="grid gap-4 border-b border-[#1A1A1A]/10 py-5 last:border-0 xl:grid-cols-[1fr_auto] xl:items-center">
                <div class="min-w-0">
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        @if($payment->status === 'success')
                            <span class="badge-green">Sukses</span>
                        @elseif($payment->status === 'pending')
                            <span class="badge-yellow">Pending</span>
                        @else
                            <span class="badge-red">{{ ucfirst($payment->status) }}</span>
                        @endif
                        <span class="editorial-label">{{ $payment->order ? 'E-Book' : 'Konsultasi' }}</span>
                    </div>
                    <p class="truncate font-medium text-[#1A1A1A]">{{ $payment->reference_id }}</p>
                    <p class="mt-1 text-sm text-[#6C6863]">{{ $payment->user?->name ?? 'Pengguna dihapus' }} - Rp{{ number_format($payment->amount, 0, ',', '.') }} - {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</p>
                    <p class="mt-1 text-sm text-[#6C6863]">{{ $payment->description }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-2 xl:justify-end">
                    <a href="{{ route('admin.payments.show', $payment) }}" class="badge-blue">Detail</a>
                    <form action="{{ route('admin.payments.status', $payment) }}" method="POST" class="flex gap-2">
                        @csrf
                        @method('PATCH')
                        <select name="status" class="border border-[#1A1A1A]/20 bg-[#F9F8F6] px-3 py-2 text-xs uppercase tracking-[0.14em] text-[#1A1A1A]">
                            @foreach(['pending', 'success', 'failed', 'cancelled'] as $option)
                                <option value="{{ $option }}" @selected($payment->status === $option)>{{ ucfirst($option) }}</option>
                            @endforeach
                        </select>
                        <button class="badge-yellow cursor-pointer">Update</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="py-12 text-center text-sm text-[#6C6863]">Tidak ada pembayaran.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $payments->links() }}</div>
@endsection
