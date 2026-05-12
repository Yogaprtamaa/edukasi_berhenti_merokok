@extends('layouts.app')

@section('title', 'Buku Saya')

@section('content')
    <div class="mb-10 flex flex-col gap-5 border-b border-[#1A1A1A]/10 pb-8 md:flex-row md:items-end md:justify-between">
        <div>
            <span class="editorial-label">Perpustakaan</span>
            <h1 class="mt-2 page-title">Buku Saya</h1>
            <p class="mt-2 text-sm text-[#6C6863]">Buku yang sudah lunas dan siap diakses</p>
        </div>
        <a href="{{ route('books.index') }}" class="btn-secondary self-start md:self-auto">Katalog Buku</a>
    </div>

    <div class="grid gap-px bg-[#1A1A1A]/10 md:grid-cols-2 lg:grid-cols-3">
        @forelse($orders as $order)
            <div class="flex flex-col bg-[#F9F8F6] p-6">
                <div class="mb-5 flex h-40 w-full items-center justify-center bg-[#EBE5DE]">
                    <svg class="h-12 w-12 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>

                <div class="flex flex-1 flex-col">
                    <span class="badge-green self-start">Lunas</span>
                    <h3 class="mt-3 font-serif text-xl leading-snug text-[#1A1A1A]">{{ $order->book->title }}</h3>
                    <p class="editorial-label mt-1">{{ $order->book->author }}</p>
                    <p class="mt-3 text-sm leading-relaxed text-[#6C6863] line-clamp-3">{{ $order->book->description }}</p>

                    <div class="mt-6 grid grid-cols-2 gap-px bg-[#1A1A1A]/10 text-sm">
                        <div class="bg-[#F9F8F6] p-3">
                            <span class="editorial-label">Dibeli</span>
                            <p class="mt-1 text-[#1A1A1A]">{{ \Carbon\Carbon::parse($order->payment->paid_at)->format('d M Y') }}</p>
                        </div>
                        <div class="bg-[#F9F8F6] p-3">
                            <span class="editorial-label">Jumlah</span>
                            <p class="mt-1 text-[#1A1A1A]">{{ $order->quantity }} buku</p>
                        </div>
                    </div>

                    <a href="{{ route('books.read', $order->book) }}" class="btn-primary mt-6 w-full text-center"><span>Baca Buku</span></a>
                </div>
            </div>
        @empty
            <div class="col-span-3 bg-[#F9F8F6] py-20 text-center text-[#6C6863]">
                <p class="font-serif text-xl">Belum ada buku yang sudah lunas.</p>
                <a href="{{ route('books.index') }}" class="btn-primary mt-6 inline-block"><span>Lihat Katalog</span></a>
            </div>
        @endforelse
    </div>

    <div class="mt-8">{{ $orders->links() }}</div>
@endsection
