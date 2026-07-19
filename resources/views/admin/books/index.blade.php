@extends('layouts.app')

@section('title', 'Kelola Buku')

@section('content')
    <header class="mb-10 md:mb-12">
        <div class="mb-4 flex items-center gap-3 sm:mb-5 sm:gap-4">
            <span class="h-px w-8 bg-[#1A1A1A] sm:w-12"></span>
            <span class="editorial-label">Katalog E-Book</span>
        </div>
        <h1 class="page-title">Kelola <span class="italic text-[#D4AF37]">Buku</span></h1>
        <p class="mt-4 max-w-xl text-sm leading-relaxed text-[#6C6863]">Tambah, ubah, atau hapus buku digital yang dijual di platform.</p>
        <a href="{{ route('admin.books.create') }}" class="btn-primary mt-6 inline-flex"><span>Tambah Buku</span></a>
    </header>

    <div class="card">
        @forelse($books as $book)
            <div class="grid gap-4 border-b border-[#1A1A1A]/10 py-5 last:border-0 lg:grid-cols-[1fr_auto]">
                <div class="min-w-0">
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        @if($book->is_available)
                            <span class="badge-green">Tersedia</span>
                        @else
                            <span class="badge-red">Nonaktif</span>
                        @endif
                        <span class="editorial-label">Stok {{ $book->stock }}</span>
                    </div>
                    <p class="truncate font-medium text-[#1A1A1A]">{{ $book->title }}</p>
                    <p class="mt-1 text-sm text-[#6C6863]">oleh {{ $book->author }} &middot; Rp{{ number_format($book->price, 0, ',', '.') }}</p>
                    <p class="mt-2 text-sm leading-relaxed text-[#6C6863]">{{ Str::limit($book->description, 150) }}</p>
                </div>
                <div class="flex flex-wrap items-center gap-2 lg:justify-end">
                    <a href="{{ route('admin.books.edit', $book) }}" class="badge-yellow cursor-pointer">Edit</a>
                    <form action="{{ route('admin.books.destroy', $book) }}" method="POST" class="flex" onsubmit="return confirm('Hapus buku ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="badge-red cursor-pointer">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="py-12 text-center text-sm text-[#6C6863]">Belum ada buku.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $books->links() }}</div>
@endsection
