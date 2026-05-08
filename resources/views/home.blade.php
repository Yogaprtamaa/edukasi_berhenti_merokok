@extends('layouts.app')

@section('title', 'Beranda — Edukasi Berhenti Merokok')

@section('content')
<div class="space-y-16 md:space-y-20">

    {{-- ── Hero greeting ─────────────────────────────────────────────────────── --}}
    <div class="border-t-2 border-[#1A1A1A] pt-10">
        <span class="editorial-label text-[#D4AF37]">Platform Edukasi</span>
        <h1 class="page-title mt-4">
            Selamat Datang,<br>
            <span class="italic text-[#D4AF37]">{{ auth()->user()->name }}</span>
        </h1>
        <p class="mt-4 max-w-xl text-sm leading-relaxed text-[#6C6863]">
            Satu tempat untuk mendukung perjalanan berhenti merokok Anda — dari edukasi, konsultasi profesional, hingga komunitas yang saling mendukung.
        </p>
    </div>

    {{-- ── Stats strip ────────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 gap-px border border-[#1A1A1A]/10 bg-[#1A1A1A]/10 sm:grid-cols-4">
        <div class="bg-[#F9F8F6] px-6 py-7">
            <p class="font-serif text-4xl text-[#1A1A1A]">{{ $stats['contents'] }}</p>
            <p class="editorial-label mt-2 text-[#6C6863]">Artikel & Video</p>
        </div>
        <div class="bg-[#1A1A1A] px-6 py-7">
            <p class="font-serif text-4xl text-[#D4AF37]">{{ $stats['books'] }}</p>
            <p class="editorial-label mt-2 text-[#EBE5DE]/60">Buku Tersedia</p>
        </div>
        <div class="bg-[#F9F8F6] px-6 py-7">
            <p class="font-serif text-4xl text-[#1A1A1A]">{{ $stats['professionals'] }}</p>
            <p class="editorial-label mt-2 text-[#6C6863]">Profesional</p>
        </div>
        <div class="bg-[#F9F8F6] px-6 py-7">
            <p class="font-serif text-4xl text-[#1A1A1A]">{{ $stats['forums'] }}</p>
            <p class="editorial-label mt-2 text-[#6C6863]">Diskusi Forum</p>
        </div>
    </div>

    {{-- ── Tracker banner (user only) ─────────────────────────────────────────── --}}
    @if(auth()->user()->role === 'user')
        @if($tracker)
            <div class="bg-[#1A1A1A] px-8 py-8 sm:px-12">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <span class="editorial-label text-[#D4AF37]">Progress Kamu</span>
                        <p class="mt-2 font-serif text-5xl text-[#F9F8F6]">
                            {{ $tracker->streak_days ?? 0 }}
                            <span class="text-2xl text-[#EBE5DE]/60">hari</span>
                        </p>
                        <p class="mt-1 text-sm text-[#EBE5DE]/50">
                            Mulai sejak {{ $tracker->quit_date ? \Carbon\Carbon::parse($tracker->quit_date)->translatedFormat('d F Y') : '—' }}
                        </p>
                    </div>
                    <div class="flex flex-col gap-4 sm:flex-row sm:gap-8">
                        <div>
                            <p class="editorial-label text-[#EBE5DE]/40">Rokok Dihindari</p>
                            <p class="mt-1 font-serif text-2xl text-[#D4AF37]">{{ number_format($tracker->cigarettes_avoided ?? 0) }}</p>
                        </div>
                        <div>
                            <p class="editorial-label text-[#EBE5DE]/40">Uang Dihemat</p>
                            <p class="mt-1 font-serif text-2xl text-[#F9F8F6]">Rp {{ number_format($tracker->money_saved ?? 0, 0, ',', '.') }}</p>
                        </div>
                        <div class="self-end">
                            <a href="{{ route('user.progress') }}" class="btn-primary"><span>Lihat Detail</span></a>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="border border-[#D4AF37]/40 bg-[#D4AF37]/5 px-8 py-7">
                <span class="editorial-label text-[#D4AF37]">Mulai Perjalananmu</span>
                <p class="mt-2 text-sm leading-relaxed text-[#6C6863]">Belum ada data progress. Buat tracker sekarang dan mulai catat hari-hari bebas rokok Anda.</p>
                <a href="{{ route('user.progress') }}" class="btn-primary mt-5 inline-block"><span>Mulai Tracker</span></a>
            </div>
        @endif
    @endif

    {{-- ── Feature grid ────────────────────────────────────────────────────────── --}}
    <div>
        <span class="editorial-label text-[#6C6863]">Fitur Platform</span>
        <div class="mt-6 grid gap-px border border-[#1A1A1A]/10 bg-[#1A1A1A]/10
            @if(auth()->user()->role === 'user') grid-cols-1 sm:grid-cols-2 lg:grid-cols-3
            @elseif(auth()->user()->role === 'professional') grid-cols-1 sm:grid-cols-3
            @else grid-cols-1 sm:grid-cols-2 lg:grid-cols-4
            @endif">

            @if(auth()->user()->role === 'user')
                @php
                    $features = [
                        ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'label' => 'Progress Tracker', 'desc' => 'Pantau streak, rokok dihindari, dan uang dihemat setiap hari.', 'url' => route('user.progress'), 'cta' => 'Lihat Progress', 'dark' => false],
                        ['icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'label' => 'Konten Edukasi', 'desc' => 'Artikel, video, dan infografis tentang berhenti merokok.', 'url' => route('contents.index'), 'cta' => 'Jelajahi Konten', 'dark' => true],
                        ['icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'label' => 'Perpustakaan Buku', 'desc' => 'Koleksi buku pilihan untuk mendukung program berhenti merokok.', 'url' => route('books.index'), 'cta' => 'Lihat Buku', 'dark' => false],
                        ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'label' => 'Konsultasi', 'desc' => 'Jadwalkan sesi dengan dokter atau psikolog berpengalaman.', 'url' => route('consultations.index'), 'cta' => 'Cari Profesional', 'dark' => false],
                        ['icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', 'label' => 'Komunitas Forum', 'desc' => 'Diskusi, tanya, dan berbagi pengalaman bersama sesama pengguna.', 'url' => route('forums.index'), 'cta' => 'Buka Forum', 'dark' => true],
                        ['icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'label' => 'Notifikasi', 'desc' => 'Pengingat harian dan info penting terkait program Anda.', 'url' => route('notifications.index'), 'cta' => 'Lihat Notifikasi', 'dark' => false],
                    ];
                @endphp

            @elseif(auth()->user()->role === 'professional')
                @php
                    $features = [
                        ['icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard', 'desc' => 'Ringkasan janji temu, jadwal, dan aktivitas terbaru.', 'url' => route('professional.dashboard'), 'cta' => 'Buka Dashboard', 'dark' => true],
                        ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Janji Temu', 'desc' => 'Kelola jadwal konsultasi dengan klien Anda.', 'url' => route('professional.appointments'), 'cta' => 'Lihat Janji', 'dark' => false],
                        ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'label' => 'Jadwal Konsultasi', 'desc' => 'Atur ketersediaan waktu dan hari praktik Anda.', 'url' => route('professional.schedule'), 'cta' => 'Atur Jadwal', 'dark' => false],
                    ];
                @endphp

            @else {{-- admin --}}
                @php
                    $features = [
                        ['icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Dashboard', 'desc' => 'Statistik dan ringkasan platform.', 'url' => route('admin.dashboard'), 'cta' => 'Buka Dashboard', 'dark' => true],
                        ['icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'label' => 'Profesional', 'desc' => 'Kelola dan verifikasi akun profesional.', 'url' => route('admin.professionals'), 'cta' => 'Kelola Profesional', 'dark' => false],
                        ['icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'label' => 'Moderasi Konten', 'desc' => 'Tinjau dan moderasi konten yang dikirimkan pengguna.', 'url' => route('admin.contents'), 'cta' => 'Moderasi Konten', 'dark' => false],
                        ['icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', 'label' => 'Moderasi Forum', 'desc' => 'Pantau dan moderasi diskusi komunitas.', 'url' => route('admin.forums'), 'cta' => 'Moderasi Forum', 'dark' => false],
                    ];
                @endphp
            @endif

            @foreach($features as $i => $f)
                <div class="{{ $f['dark'] ? 'bg-[#1A1A1A]' : 'bg-[#F9F8F6]' }} flex flex-col gap-4 px-7 py-8">
                    <div class="flex h-10 w-10 items-center justify-center border {{ $f['dark'] ? 'border-[#F9F8F6]/20' : 'border-[#1A1A1A]/20' }}">
                        <svg class="h-5 w-5 {{ $f['dark'] ? 'text-[#D4AF37]' : 'text-[#1A1A1A]' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $f['icon'] }}"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="font-serif text-lg {{ $f['dark'] ? 'text-[#F9F8F6]' : 'text-[#1A1A1A]' }}">{{ $f['label'] }}</p>
                        <p class="mt-1 text-sm leading-relaxed {{ $f['dark'] ? 'text-[#EBE5DE]/60' : 'text-[#6C6863]' }}">{{ $f['desc'] }}</p>
                    </div>
                    <a href="{{ $f['url'] }}" class="editorial-label self-start border-b {{ $f['dark'] ? 'border-[#D4AF37]/50 text-[#D4AF37] hover:border-[#D4AF37]' : 'border-[#1A1A1A]/30 text-[#1A1A1A] hover:border-[#1A1A1A]' }} pb-0.5 transition-colors duration-300">
                        {{ $f['cta'] }} →
                    </a>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ── Konten terbaru (user & admin only) ─────────────────────────────────── --}}
    @if(auth()->user()->role !== 'professional' && $latestContents->count())
        <div>
            <div class="flex items-end justify-between border-t border-[#1A1A1A]/10 pt-8">
                <div>
                    <span class="editorial-label text-[#6C6863]">Terbaru</span>
                    <h2 class="mt-2 font-serif text-3xl text-[#1A1A1A]">Konten Edukasi</h2>
                </div>
                @if(auth()->user()->role === 'user')
                    <a href="{{ route('contents.index') }}" class="editorial-label border-b border-[#1A1A1A]/30 pb-0.5 text-[#1A1A1A] hover:border-[#1A1A1A] transition-colors duration-300">Semua Konten →</a>
                @endif
            </div>

            <div class="mt-6 grid gap-px border border-[#1A1A1A]/10 bg-[#1A1A1A]/10 sm:grid-cols-3">
                @foreach($latestContents as $content)
                    <div class="flex flex-col gap-3 bg-[#F9F8F6] px-6 py-6">
                        <span class="editorial-label text-[#D4AF37]">{{ ucfirst($content->type ?? 'artikel') }}</span>
                        <p class="font-serif text-lg leading-snug text-[#1A1A1A]">{{ $content->title }}</p>
                        <p class="flex-1 text-sm leading-relaxed text-[#6C6863] line-clamp-2">{{ $content->description ?? Str::limit(strip_tags($content->body), 120) }}</p>
                        @if(auth()->user()->role === 'user')
                            <a href="{{ route('contents.show', $content) }}" class="editorial-label border-b border-[#1A1A1A]/30 pb-0.5 text-[#1A1A1A] self-start hover:border-[#1A1A1A] transition-colors duration-300">Baca →</a>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

</div>
@endsection
