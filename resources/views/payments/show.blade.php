@extends('layouts.app')

@section('title', 'Invoice ' . $payment->reference_id)

@section('content')
    @php
        $isBook = (bool) $payment->order;
        $itemTitle = $isBook
            ? $payment->order->book->title
            : 'Konsultasi dengan ' . ($payment->appointment?->professional?->user?->name ?? 'Profesional');
        $methodLabel = match ($payment->payment_method) {
            'e-wallet' => 'E-Wallet',
            'credit_card' => 'Kartu Kredit',
            default => 'Transfer Bank',
        };
    @endphp

    <div class="max-w-5xl">
        <a href="{{ route('payments.index') }}" class="mb-8 inline-flex items-center gap-2 text-xs text-[#6C6863] transition-colors duration-500 hover:text-[#D4AF37]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            <span class="editorial-label">Kembali ke Pembayaran</span>
        </a>

        <div class="grid gap-px bg-[#1A1A1A]/10 lg:grid-cols-[minmax(0,1fr)_320px]">
            <div class="bg-[#F9F8F6] p-6 md:p-8">
            <div class="flex flex-col gap-5 border-b border-[#1A1A1A]/10 pb-6 md:flex-row md:items-start md:justify-between">
                <div>
                    <span class="editorial-label">Invoice</span>
                    <h1 class="mt-2 font-serif text-3xl leading-tight text-[#1A1A1A]">{{ $payment->reference_id }}</h1>
                    <p class="mt-2 text-sm text-[#6C6863]">{{ \Carbon\Carbon::parse($payment->created_at)->format('d M Y H:i') }}</p>
                </div>
                @if($payment->status === 'success')
                    <span class="badge-green self-start">Lunas</span>
                @else
                    <span class="badge-yellow self-start">Menunggu Pembayaran</span>
                @endif
            </div>

            <div class="grid gap-5 py-6 md:grid-cols-2">
                <div>
                    <span class="editorial-label">Item</span>
                    <p class="mt-2 text-sm leading-relaxed text-[#1A1A1A]">{{ $itemTitle }}</p>
                    @if($isBook)
                        <p class="mt-1 text-xs text-[#6C6863]">Jumlah: {{ $payment->order->quantity }} buku</p>
                    @else
                        <p class="mt-1 text-xs text-[#6C6863]">Durasi: {{ number_format($payment->duration_hours, 0) }} jam</p>
                    @endif
                </div>
                <div>
                    <span class="editorial-label">Metode</span>
                    <p class="mt-2 text-sm text-[#1A1A1A]">{{ $methodLabel }}</p>
                    @if($payment->paid_at)
                        <p class="mt-1 text-xs text-[#6C6863]">Dibayar: {{ \Carbon\Carbon::parse($payment->paid_at)->format('d M Y H:i') }}</p>
                    @endif
                </div>
            </div>

            @if($payment->status !== 'success')
                <div class="mt-6 space-y-5">
                    <div class="bg-[#EBE5DE] p-5">
                        <span class="editorial-label">Instruksi</span>
                        <p class="mt-2 text-sm leading-relaxed text-[#6C6863]">
                            Gunakan nomor invoice sebagai berita pembayaran. Untuk demo aplikasi ini, tombol di bawah akan langsung menandai pembayaran sebagai lunas.
                        </p>
                    </div>
                    <form action="{{ route('payments.pay', $payment) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-primary"><span>Bayar Sekarang</span></button>
                    </form>
                </div>
            @else
                <div class="mt-6 bg-[#D4AF37]/10 p-5">
                    <span class="editorial-label text-[#D4AF37]">Pembayaran Selesai</span>
                    <p class="mt-2 text-sm leading-relaxed text-[#6C6863]">Transaksi ini sudah tercatat lunas.</p>
                </div>
                @if($isBook)
                    <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('books.read', $payment->order->book) }}" class="btn-primary text-center"><span>Baca Buku</span></a>
                        <a href="{{ route('books.purchased') }}" class="btn-secondary text-center">Buku Saya</a>
                    </div>
                @endif
            @endif
            </div>

            <aside class="bg-[#EBE5DE] p-6 md:p-8">
                <span class="editorial-label">Total Pembayaran</span>
                <p class="mt-3 font-serif text-4xl text-[#D4AF37]">Rp{{ number_format($payment->amount, 0, ',', '.') }}</p>
                <div class="mt-6 space-y-4 border-t border-[#1A1A1A]/10 pt-6">
                    <div>
                        <span class="editorial-label">Status</span>
                        <p class="mt-1 text-sm text-[#1A1A1A]">{{ $payment->status === 'success' ? 'Lunas' : 'Menunggu pembayaran' }}</p>
                    </div>
                    <div>
                        <span class="editorial-label">Metode</span>
                        <p class="mt-1 text-sm text-[#1A1A1A]">{{ $methodLabel }}</p>
                    </div>
                    <div>
                        <span class="editorial-label">Referensi</span>
                        <p class="mt-1 break-all text-sm text-[#1A1A1A]">{{ $payment->reference_id }}</p>
                    </div>
                </div>
            </aside>
        </div>
    </div>
@endsection
