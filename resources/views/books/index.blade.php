@extends('layouts.app')

@section('title', 'Katalog Buku')

@section('content')
    <div class="mb-10 border-b border-[#1A1A1A]/10 pb-8">
        <span class="editorial-label">Koleksi</span>
        <h1 class="mt-2 page-title">Katalog Buku</h1>
        <p class="mt-2 text-sm text-[#6C6863]">Buku edukasi seputar berhenti merokok</p>
    </div>

    <div class="grid gap-px bg-[#1A1A1A]/10 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
        @forelse($books as $book)
            <a href="{{ route('books.show', $book) }}" class="group flex flex-col bg-[#F9F8F6] p-6 transition-colors duration-700 hover:bg-[#EBE5DE]">
                <div class="mb-4 flex h-40 w-full items-center justify-center bg-[#EBE5DE] transition-colors duration-700 group-hover:bg-[#1A1A1A]/10">
                    <svg class="h-12 w-12 text-[#6C6863]/40 transition-colors duration-700 group-hover:text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h3 class="font-serif text-sm leading-snug text-[#1A1A1A] line-clamp-2 transition-colors duration-500 group-hover:text-[#D4AF37]">{{ $book->title }}</h3>
                <p class="editorial-label mt-1">{{ $book->author }}</p>
                <p class="mt-3 text-sm font-medium text-[#1A1A1A]">Rp{{ number_format($book->price, 0, ',', '.') }}</p>
            </a>
        @empty
            <div class="col-span-4 py-20 text-center text-[#6C6863]">
                <p class="font-serif text-xl">Belum ada buku tersedia</p>
            </div>
        @endforelse
    </div>

    <div class="mt-8">{{ $books->links() }}</div>
@endsection
