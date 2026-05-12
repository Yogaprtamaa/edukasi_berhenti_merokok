@extends('layouts.app')

@section('title', 'Moderasi Forum')

@section('content')
    <header class="mb-10 md:mb-12">
        <div class="mb-4 flex items-center gap-3 sm:mb-5 sm:gap-4">
            <span class="h-px w-8 bg-[#1A1A1A] sm:w-12"></span>
            <span class="editorial-label">Komunitas</span>
        </div>
        <h1 class="page-title">Moderasi <span class="italic text-[#D4AF37]">Forum</span></h1>
        <p class="mt-4 max-w-xl text-sm leading-relaxed text-[#6C6863]">Pantau thread diskusi komunitas dan hapus percakapan yang tidak sesuai.</p>
    </header>

    <div class="card">
        @forelse($forums as $forum)
            <div class="grid gap-4 border-b border-[#1A1A1A]/10 py-5 last:border-0 lg:grid-cols-[1fr_auto] lg:items-center">
                <div class="min-w-0">
                    <p class="truncate font-medium text-[#1A1A1A]">{{ $forum->title }}</p>
                    <div class="mt-2 flex flex-wrap items-center gap-2 text-[11px] uppercase tracking-[0.14em] text-[#6C6863] sm:gap-3 sm:text-xs sm:tracking-[0.18em]">
                        <span>{{ $forum->user->name }}</span>
                        <span>{{ $forum->forum_replies_count }} balasan</span>
                        <span>{{ $forum->views }} dilihat</span>
                        <span>{{ \Carbon\Carbon::parse($forum->created_at)->diffForHumans() }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.forums.edit', $forum) }}" class="badge-yellow cursor-pointer">Edit</a>
                    <form action="{{ route('admin.forums.destroy', $forum) }}" method="POST" class="flex" onsubmit="return confirm('Hapus thread ini beserta semua balasannya?')">
                        @csrf
                        @method('DELETE')
                        <button class="badge-red cursor-pointer">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="py-12 text-center text-sm text-[#6C6863]">Tidak ada thread forum.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $forums->links() }}</div>
@endsection
