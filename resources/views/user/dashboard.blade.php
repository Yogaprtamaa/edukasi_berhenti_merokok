@extends('layouts.app')

@section('title', 'Dashboard — BerhentiMerokok')

@section('content')
    {{-- Header --}}
    <div class="mb-10 border-b border-[#1A1A1A]/10 pb-8">
        <span class="editorial-label">Selamat datang</span>
        <h1 class="mt-2 page-title">{{ auth()->user()->name }}</h1>
        <p class="mt-2 text-sm text-[#6C6863]">Setiap hari tanpa rokok adalah kemenangan.</p>
    </div>

    {{-- Progress Tracker Card --}}
    @if($tracker)
        <div class="mb-8 bg-[#1A1A1A] p-6 sm:p-8 md:p-10">
            <span class="editorial-label text-[#EBE5DE]/60">Streak Tidak Merokok</span>
            <div class="mt-4 flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <div class="flex items-baseline gap-3">
                        <span class="font-serif text-7xl leading-none text-[#F9F8F6] md:text-8xl">{{ $tracker->streak_days }}</span>
                        <span class="text-lg text-[#EBE5DE]/70">hari</span>
                    </div>
                    <p class="mt-2 text-xs text-[#EBE5DE]/50">Mulai sejak {{ \Carbon\Carbon::parse($tracker->quit_date)->format('d M Y') }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4 sm:gap-6">
                    <div class="border-t border-[#F9F8F6]/20 pt-3">
                        <p class="font-serif text-2xl text-[#F9F8F6]">{{ $tracker->cigarettes_avoided }}</p>
                        <p class="editorial-label text-[#EBE5DE]/50">Rokok dihindari</p>
                    </div>
                    <div class="border-t border-[#D4AF37] pt-3">
                        <p class="font-serif text-xl text-[#F9F8F6]">Rp{{ number_format($tracker->money_saved, 0, ',', '.') }}</p>
                        <p class="editorial-label text-[#EBE5DE]/50">Uang dihemat</p>
                    </div>
                </div>
            </div>

            {{-- Check-in --}}
            @if(!$checkedInToday)
                <div class="mt-8 border-t border-[#F9F8F6]/10 pt-6">
                    <p class="mb-4 text-sm text-[#EBE5DE]/70">Apakah kamu tidak merokok hari ini?</p>
                    <div class="flex flex-wrap gap-3">
                        <form action="{{ route('user.checkin') }}" method="POST">
                            @csrf
                            <input type="hidden" name="is_smoke_free" value="1">
                            <button type="submit" class="relative inline-flex min-h-10 items-center justify-center overflow-hidden border border-[#D4AF37] bg-[#D4AF37] px-6 py-2 text-[11px] font-medium uppercase tracking-[0.2em] text-[#1A1A1A] transition-all duration-500 hover:bg-transparent hover:text-[#D4AF37]">
                                Ya, tidak merokok!
                            </button>
                        </form>
                        <form action="{{ route('user.checkin') }}" method="POST">
                            @csrf
                            <input type="hidden" name="is_smoke_free" value="0">
                            <button type="submit" class="relative inline-flex min-h-10 items-center justify-center overflow-hidden border border-[#F9F8F6]/30 px-6 py-2 text-[11px] font-medium uppercase tracking-[0.2em] text-[#EBE5DE]/70 transition-all duration-500 hover:border-[#F9F8F6]/60 hover:text-[#F9F8F6]">
                                Tidak hari ini
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="mt-8 border-t border-[#F9F8F6]/10 pt-6">
                    <p class="text-sm text-[#EBE5DE]/60">✓ Kamu sudah check-in hari ini. Tetap semangat!</p>
                </div>
            @endif
        </div>
    @else
        <div class="card mb-8 border-t-4 border-t-[#D4AF37] text-center py-12">
            <span class="editorial-label">Progress Tracker</span>
            <h3 class="mt-4 font-serif text-2xl text-[#1A1A1A]">Mulai Progress Tracker</h3>
            <p class="mt-2 text-sm text-[#6C6863]">Set tanggal berhenti merokokmu dan mulai pantau progressmu</p>
            <div class="mt-6">
                <a href="{{ route('user.progress') }}" class="btn-primary"><span>Mulai Sekarang</span></a>
            </div>
        </div>
    @endif

    {{-- Quick Menu --}}
    <div class="mb-10">
        <span class="editorial-label">Menu Utama</span>
        <div class="mt-4 grid grid-cols-2 gap-px bg-[#1A1A1A]/10 md:grid-cols-4">
            <a href="{{ route('contents.index') }}" class="group flex flex-col items-center gap-3 bg-[#F9F8F6] p-6 text-center transition-colors duration-500 hover:bg-[#EBE5DE]">
                <svg class="h-6 w-6 text-[#6C6863] transition-colors duration-500 group-hover:text-[#D4AF37]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <div>
                    <p class="text-sm font-medium text-[#1A1A1A]">Edukasi</p>
                    <p class="editorial-label mt-0.5">Artikel & Video</p>
                </div>
            </a>
            <a href="{{ route('books.index') }}" class="group flex flex-col items-center gap-3 bg-[#F9F8F6] p-6 text-center transition-colors duration-500 hover:bg-[#EBE5DE]">
                <svg class="h-6 w-6 text-[#6C6863] transition-colors duration-500 group-hover:text-[#D4AF37]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <div>
                    <p class="text-sm font-medium text-[#1A1A1A]">Buku</p>
                    <p class="editorial-label mt-0.5">Beli & Unduh</p>
                </div>
            </a>
            <a href="{{ route('consultations.index') }}" class="group flex flex-col items-center gap-3 bg-[#F9F8F6] p-6 text-center transition-colors duration-500 hover:bg-[#EBE5DE]">
                <svg class="h-6 w-6 text-[#6C6863] transition-colors duration-500 group-hover:text-[#D4AF37]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <div>
                    <p class="text-sm font-medium text-[#1A1A1A]">Konsultasi</p>
                    <p class="editorial-label mt-0.5">Dokter & Psikolog</p>
                </div>
            </a>
            <a href="{{ route('forums.index') }}" class="group flex flex-col items-center gap-3 bg-[#F9F8F6] p-6 text-center transition-colors duration-500 hover:bg-[#EBE5DE]">
                <svg class="h-6 w-6 text-[#6C6863] transition-colors duration-500 group-hover:text-[#D4AF37]" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                </svg>
                <div>
                    <p class="text-sm font-medium text-[#1A1A1A]">Forum</p>
                    <p class="editorial-label mt-0.5">Komunitas</p>
                </div>
            </a>
        </div>
    </div>

    {{-- Konten & Forum Terbaru --}}
    <div class="grid gap-6 md:grid-cols-2">
        <div class="card">
            <div class="flex items-center justify-between">
                <span class="editorial-label">Konten Terbaru</span>
                <a href="{{ route('contents.index') }}" class="text-xs text-[#6C6863] transition-colors duration-500 hover:text-[#D4AF37]">Lihat semua</a>
            </div>
            <div class="mt-4 space-y-0">
                @forelse($latestContents as $content)
                    <a href="{{ route('contents.show', $content) }}" class="group flex items-start gap-3 border-b border-[#1A1A1A]/8 py-3 last:border-0 transition-colors duration-300 hover:bg-[#EBE5DE]/30 -mx-4 px-4">
                        <div class="mt-0.5 h-7 w-7 flex-shrink-0 border border-[#1A1A1A]/20 flex items-center justify-center bg-[#EBE5DE]">
                            @if($content->type === 'video')
                                <svg class="h-3.5 w-3.5 text-[#6C6863]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @else
                                <svg class="h-3.5 w-3.5 text-[#6C6863]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="truncate text-sm text-[#1A1A1A] transition-colors duration-500 group-hover:text-[#D4AF37]">{{ $content->title }}</p>
                            <p class="mt-0.5 text-xs text-[#6C6863]">{{ \Carbon\Carbon::parse($content->published_at)->diffForHumans() }}</p>
                        </div>
                    </a>
                @empty
                    <p class="py-6 text-center text-sm text-[#6C6863]">Belum ada konten tersedia</p>
                @endforelse
            </div>
        </div>

        <div class="card">
            <div class="flex items-center justify-between">
                <span class="editorial-label">Diskusi Forum</span>
                <a href="{{ route('forums.index') }}" class="text-xs text-[#6C6863] transition-colors duration-500 hover:text-[#D4AF37]">Lihat semua</a>
            </div>
            <div class="mt-4 space-y-0">
                @forelse($latestForums as $forum)
                    <a href="{{ route('forums.show', $forum) }}" class="group block border-b border-[#1A1A1A]/8 py-3 last:border-0 transition-colors duration-300 hover:bg-[#EBE5DE]/30 -mx-4 px-4">
                        <p class="truncate text-sm text-[#1A1A1A] transition-colors duration-500 group-hover:text-[#D4AF37]">{{ $forum->title }}</p>
                        <div class="mt-1 flex items-center gap-3">
                            <span class="text-xs text-[#6C6863]">{{ $forum->user->name }}</span>
                            <span class="text-xs text-[#6C6863]/60">{{ $forum->forum_replies_count }} balasan</span>
                            <span class="text-xs text-[#6C6863]/60">{{ \Carbon\Carbon::parse($forum->created_at)->diffForHumans() }}</span>
                        </div>
                    </a>
                @empty
                    <p class="py-6 text-center text-sm text-[#6C6863]">Belum ada diskusi</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection
