@extends('layouts.app')

@section('title', $forum->title)

@section('content')
    <div class="mx-auto max-w-3xl">
        <a href="{{ route('forums.index') }}" class="mb-8 inline-flex items-center gap-2 text-xs text-[#6C6863] transition-colors duration-500 hover:text-[#D4AF37]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            <span class="editorial-label">Kembali ke Forum</span>
        </a>

        {{-- Thread utama --}}
        <div class="card mb-4">
            <div class="flex items-start gap-4">
                <div class="mt-0.5 flex h-10 w-10 flex-shrink-0 items-center justify-center border border-[#1A1A1A]/20 bg-[#EBE5DE]">
                    <span class="text-sm font-medium text-[#1A1A1A]">{{ strtoupper(substr($forum->user->name, 0, 1)) }}</span>
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-medium text-[#1A1A1A]">{{ $forum->user->name }}</span>
                        <span class="editorial-label">{{ \Carbon\Carbon::parse($forum->created_at)->diffForHumans() }}</span>
                    </div>
                    <h1 class="mt-3 font-serif text-2xl leading-tight text-[#1A1A1A] md:text-3xl">{{ $forum->title }}</h1>
                    <p class="mt-4 leading-relaxed text-[#6C6863]">{!! nl2br(e($forum->body)) !!}</p>
                </div>
            </div>
        </div>

        {{-- Balasan --}}
        <div class="mb-6 space-y-3">
            @foreach($replies as $reply)
                <div class="card">
                    <div class="flex items-start gap-3">
                        <div class="mt-0.5 flex h-8 w-8 flex-shrink-0 items-center justify-center border border-[#1A1A1A]/20 bg-[#EBE5DE]">
                            <span class="text-xs font-medium text-[#1A1A1A]">{{ strtoupper(substr($reply->user->name, 0, 1)) }}</span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-medium text-[#1A1A1A]">{{ $reply->user->name }}</span>
                                <span class="editorial-label">{{ \Carbon\Carbon::parse($reply->created_at)->diffForHumans() }}</span>
                            </div>
                            <p class="mt-2 text-sm leading-relaxed text-[#6C6863]">{!! nl2br(e($reply->body)) !!}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Form Balas --}}
        <div class="card">
            <span class="editorial-label">Tulis Balasan</span>
            <form action="{{ route('forums.reply', $forum) }}" method="POST" class="mt-4 space-y-4">
                @csrf
                <textarea name="body" rows="4" required class="input-field resize-none" placeholder="Tulis balasanmu..."></textarea>
                <button type="submit" class="btn-primary"><span>Kirim Balasan</span></button>
            </form>
        </div>
    </div>
@endsection
