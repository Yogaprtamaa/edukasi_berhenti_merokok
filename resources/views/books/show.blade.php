@extends('layouts.app')

@section('title', $book->title)

@section('content')
    <div class="mx-auto max-w-2xl">
        <a href="{{ route('books.index') }}" class="mb-8 inline-flex items-center gap-2 text-xs text-[#6C6863] transition-colors duration-500 hover:text-[#D4AF37]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            <span class="editorial-label">Kembali ke Katalog</span>
        </a>

        <div class="card">
            <div class="flex h-52 w-full items-center justify-center bg-[#EBE5DE] mb-8">
                <svg class="h-16 w-16 text-[#6C6863]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>

            <h1 class="font-serif text-3xl leading-tight text-[#1A1A1A]">{{ $book->title }}</h1>
            <p class="mt-1 text-sm text-[#6C6863]">Penulis: {{ $book->author }}</p>
            <p class="mt-4 font-serif text-2xl text-[#D4AF37]">Rp{{ number_format($book->price, 0, ',', '.') }}</p>
            <p class="editorial-label mt-1">Stok: {{ $book->stock ?? 'Digital' }}</p>

            <div class="my-6 h-px bg-[#1A1A1A]/10"></div>

            @if($hasPurchased)
                <div class="bg-[#D4AF37]/10 p-5">
                    <span class="editorial-label text-[#D4AF37]">Sudah Dimiliki</span>
                    <p class="mt-2 text-sm leading-relaxed text-[#6C6863]">Buku ini sudah ada di perpustakaan akun Anda.</p>
                </div>
                <a href="{{ route('books.read', $book) }}" class="btn-primary mt-5 block w-full text-center"><span>Baca Buku</span></a>
            @else
                <form action="{{ route('books.order', $book) }}" method="POST" class="space-y-5">
                    @csrf
                    <div>
                        <label class="editorial-label mb-2 block">Jumlah</label>
                        <input type="number" name="quantity" value="1" min="1" class="input-field w-32">
                    </div>
                    <div>
                        <label class="editorial-label mb-2 block">Metode Pembayaran</label>
                        <select name="payment_method" class="input-field">
                            <option value="transfer">Transfer Bank</option>
                            <option value="e-wallet">E-Wallet</option>
                            <option value="credit_card">Kartu Kredit</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary w-full"><span>Beli Sekarang</span></button>
                </form>
            @endif
        </div>
    </div>
@endsection
