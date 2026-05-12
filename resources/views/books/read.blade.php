@extends('layouts.app')

@section('title', 'Baca ' . $book->title)

@section('content')
    <div class="max-w-5xl">
        <a href="{{ route('books.purchased') }}" class="mb-8 inline-flex items-center gap-2 text-xs text-[#6C6863] transition-colors duration-500 hover:text-[#D4AF37]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            <span class="editorial-label">Kembali ke Buku Saya</span>
        </a>

        <article class="grid gap-8 lg:grid-cols-[240px_minmax(0,1fr)]">
            <aside class="self-start border border-[#1A1A1A]/10 bg-[#EBE5DE] p-6">
                <div class="flex aspect-[3/4] items-center justify-center border border-[#1A1A1A]/10 bg-[#F9F8F6]">
                    <svg class="h-16 w-16 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.247 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="badge-green mt-5 inline-flex">Akses Aktif</span>
                <p class="editorial-label mt-4">{{ $book->author }}</p>
            </aside>

            <div>
                <span class="editorial-label">Buku Digital</span>
                <h1 class="mt-2 font-serif text-4xl leading-tight text-[#1A1A1A]">{{ $book->title }}</h1>
                <p class="mt-4 text-sm leading-relaxed text-[#6C6863]">{{ $book->description }}</p>

                <div class="my-8 h-px bg-[#1A1A1A]/10"></div>

                <div class="prose max-w-none text-[#1A1A1A]">
                    <h2 class="font-serif text-2xl">Ringkasan Bacaan</h2>
                    <p class="mt-4 leading-relaxed text-[#6C6863]">
                        Materi buku ini tersedia untuk akun Anda karena pembayaran sudah selesai. Gunakan halaman ini sebagai ruang akses buku digital.
                    </p>
                    <p class="mt-4 leading-relaxed text-[#6C6863]">
                        Konten lengkap dapat ditambahkan dari dashboard admin atau diganti dengan file PDF/download saat aset buku digital sudah tersedia.
                    </p>
                </div>
            </div>
        </article>
    </div>
@endsection
