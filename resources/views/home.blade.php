@extends('layouts.app')

@section('title', 'Beranda - Edukasi Berhenti Merokok')

@section('content')
@php
    $role = auth()->user()->role;

    $primaryAction = match ($role) {
        'admin' => ['label' => 'Buka Dashboard', 'url' => route('admin.dashboard')],
        'professional' => ['label' => 'Lihat Agenda', 'url' => route('professional.appointments')],
        default => ['label' => 'Buka Dashboard', 'url' => route('dashboard')],
    };

    $secondaryAction = match ($role) {
        'admin' => ['label' => 'Kelola Pembayaran', 'url' => route('admin.payments')],
        'professional' => ['label' => 'Kelola Jadwal', 'url' => route('professional.schedule')],
        default => ['label' => 'Lihat Progress', 'url' => route('user.progress')],
    };

    $featureCards = match ($role) {
        'admin' => [
            ['title' => 'Users', 'desc' => 'Kelola akun pengguna, admin, dan profesional.', 'url' => route('admin.users'), 'label' => 'Kelola User'],
            ['title' => 'Profesional', 'desc' => 'Review pengajuan dan verifikasi tenaga ahli.', 'url' => route('admin.professionals'), 'label' => 'Review'],
            ['title' => 'Pembayaran', 'desc' => 'Pantau status pembayaran e-book dan konsultasi.', 'url' => route('admin.payments'), 'label' => 'Pantau'],
            ['title' => 'Appointment', 'desc' => 'Lihat seluruh janji temu di platform.', 'url' => route('admin.appointments'), 'label' => 'Lihat'],
        ],
        'professional' => [
            ['title' => 'Dashboard', 'desc' => 'Ringkasan appointment, payment, dan pendapatan.', 'url' => route('professional.dashboard'), 'label' => 'Buka'],
            ['title' => 'Janji Temu', 'desc' => 'Konfirmasi, selesaikan, atau batalkan appointment.', 'url' => route('professional.appointments'), 'label' => 'Kelola'],
            ['title' => 'Jadwal', 'desc' => 'Atur jam praktik online, offline, atau hybrid.', 'url' => route('professional.schedule'), 'label' => 'Atur'],
        ],
        default => [
            ['title' => 'Progress', 'desc' => 'Catat streak, rokok dihindari, dan uang yang dihemat.', 'url' => route('user.progress'), 'label' => 'Lihat'],
            ['title' => 'Edukasi', 'desc' => 'Baca artikel, video, dan materi berhenti merokok.', 'url' => route('contents.index'), 'label' => 'Jelajahi'],
            ['title' => 'E-Book', 'desc' => 'Beli dan baca buku digital pendukung program.', 'url' => route('books.index'), 'label' => 'Buka'],
            ['title' => 'Konsultasi', 'desc' => 'Buat appointment dengan profesional terverifikasi.', 'url' => route('consultations.index'), 'label' => 'Cari'],
            ['title' => 'Forum', 'desc' => 'Diskusi dan berbagi pengalaman dengan komunitas.', 'url' => route('forums.index'), 'label' => 'Masuk'],
            ['title' => 'Pembayaran', 'desc' => 'Pantau riwayat pembayaran dan status transaksi.', 'url' => route('payments.index'), 'label' => 'Cek'],
        ],
    };
@endphp

<div class="space-y-10 md:space-y-12">
    <section class="overflow-hidden border border-[#1A1A1A]/10 bg-white shadow-[0_16px_50px_rgba(26,26,26,0.06)]">
        <div class="grid gap-8 p-5 sm:p-7 lg:grid-cols-[1fr_360px] lg:items-stretch">
            <div class="flex flex-col justify-between">
                <div>
                    <div class="mb-5 flex items-center gap-3">
                        <span class="h-px w-10 bg-[#1A1A1A]"></span>
                        <span class="editorial-label">{{ ucfirst($role) }} Home</span>
                    </div>
                    <h1 class="font-serif text-4xl leading-[0.95] text-[#1A1A1A] sm:text-5xl md:text-6xl">
                        Selamat datang,<br>
                        <span class="italic text-[#D4AF37]">{{ auth()->user()->name }}</span>
                    </h1>
                    <p class="mt-5 max-w-2xl text-sm leading-relaxed text-[#6C6863] sm:text-base">
                        @if($role === 'admin')
                            Pantau aktivitas platform, transaksi, appointment, dan moderasi dari ruang kerja utama.
                        @elseif($role === 'professional')
                            Kelola jadwal konsultasi, pantau pembayaran, dan tindak lanjuti appointment pengguna.
                        @else
                            Satu ruang untuk membangun kebiasaan bebas rokok lewat progress tracker, edukasi, e-book, konsultasi, dan komunitas.
                        @endif
                    </p>
                </div>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ $primaryAction['url'] }}" class="btn-primary"><span>{{ $primaryAction['label'] }}</span></a>
                    <a href="{{ $secondaryAction['url'] }}" class="btn-secondary">{{ $secondaryAction['label'] }}</a>
                </div>
            </div>

            <div class="grid gap-3 bg-[#F9F8F6] p-4 sm:grid-cols-2 lg:grid-cols-1">
                <div class="border border-[#1A1A1A]/10 bg-white p-4">
                    <p class="editorial-label">Konten</p>
                    <p class="mt-3 font-serif text-4xl text-[#1A1A1A]">{{ $stats['contents'] }}</p>
                    <p class="mt-1 text-sm text-[#6C6863]">Materi edukasi aktif</p>
                </div>
                <div class="border border-[#1A1A1A]/10 bg-[#1A1A1A] p-4">
                    <p class="editorial-label text-[#EBE5DE]/60">Profesional</p>
                    <p class="mt-3 font-serif text-4xl text-[#D4AF37]">{{ $stats['professionals'] }}</p>
                    <p class="mt-1 text-sm text-[#EBE5DE]/60">Terverifikasi</p>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="border border-[#1A1A1A]/10 bg-white p-4">
                        <p class="editorial-label">E-Book</p>
                        <p class="mt-3 font-serif text-3xl text-[#1A1A1A]">{{ $stats['books'] }}</p>
                    </div>
                    <div class="border border-[#1A1A1A]/10 bg-white p-4">
                        <p class="editorial-label">Forum</p>
                        <p class="mt-3 font-serif text-3xl text-[#1A1A1A]">{{ $stats['forums'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if($role === 'user')
        <section class="grid gap-5 lg:grid-cols-[1fr_340px]">
            <div class="border border-[#1A1A1A]/10 bg-white p-5 shadow-[0_16px_50px_rgba(26,26,26,0.05)] sm:p-6">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="editorial-label">Progress Kamu</p>
                        <h2 class="mt-2 text-2xl text-[#1A1A1A] sm:text-3xl">Perjalanan Bebas Rokok</h2>
                    </div>
                    <a href="{{ route('user.progress') }}" class="editorial-link">Detail</a>
                </div>

                @if($tracker)
                    <div class="mt-6 grid gap-4 sm:grid-cols-3">
                        <div class="bg-[#1A1A1A] p-5">
                            <p class="editorial-label text-[#EBE5DE]/60">Streak</p>
                            <p class="mt-3 font-serif text-5xl text-[#D4AF37]">{{ $tracker->streak_days ?? 0 }}</p>
                            <p class="mt-1 text-sm text-[#EBE5DE]/60">hari</p>
                        </div>
                        <div class="border border-[#1A1A1A]/10 bg-[#F9F8F6] p-5">
                            <p class="editorial-label">Rokok Dihindari</p>
                            <p class="mt-3 font-serif text-4xl text-[#1A1A1A]">{{ number_format($tracker->cigarettes_avoided ?? 0) }}</p>
                        </div>
                        <div class="border border-[#D4AF37]/50 bg-[#D4AF37]/10 p-5">
                            <p class="editorial-label">Uang Dihemat</p>
                            <p class="mt-3 font-serif text-3xl text-[#1A1A1A]">Rp{{ number_format($tracker->money_saved ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    <p class="mt-4 text-sm text-[#6C6863]">
                        Mulai sejak {{ $tracker->quit_date ? \Carbon\Carbon::parse($tracker->quit_date)->translatedFormat('d F Y') : '-' }}.
                    </p>
                @else
                    <div class="mt-6 border border-[#D4AF37]/40 bg-[#D4AF37]/10 p-5">
                        <p class="font-medium text-[#1A1A1A]">Belum ada tracker aktif.</p>
                        <p class="mt-1 text-sm text-[#6C6863]">Buat target berhenti merokok dan mulai catat progres harian.</p>
                        <a href="{{ route('user.progress') }}" class="btn-primary mt-5"><span>Mulai Tracker</span></a>
                    </div>
                @endif
            </div>

            <div class="border border-[#1A1A1A]/10 bg-white p-5 shadow-[0_16px_50px_rgba(26,26,26,0.05)] sm:p-6">
                <p class="editorial-label">Akses Cepat</p>
                <div class="mt-5 grid gap-2">
                    <a href="{{ route('books.purchased') }}" class="border border-[#1A1A1A]/10 px-4 py-3 text-sm font-medium text-[#1A1A1A] transition-colors hover:border-[#D4AF37]">Buku Saya</a>
                    <a href="{{ route('payments.index') }}" class="border border-[#1A1A1A]/10 px-4 py-3 text-sm font-medium text-[#1A1A1A] transition-colors hover:border-[#D4AF37]">Riwayat Pembayaran</a>
                    <a href="{{ route('notifications.index') }}" class="border border-[#1A1A1A]/10 px-4 py-3 text-sm font-medium text-[#1A1A1A] transition-colors hover:border-[#D4AF37]">Notifikasi</a>
                </div>
            </div>
        </section>
    @endif

    <section>
        <div class="mb-5 flex flex-wrap items-end justify-between gap-3">
            <div>
                <p class="editorial-label">Ruang Kerja</p>
                <h2 class="mt-2 text-2xl text-[#1A1A1A] sm:text-3xl">Fitur Utama</h2>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @foreach($featureCards as $index => $feature)
                <a href="{{ $feature['url'] }}" class="group border border-[#1A1A1A]/10 bg-white p-5 shadow-[0_10px_30px_rgba(26,26,26,0.04)] transition-all duration-300 hover:-translate-y-0.5 hover:border-[#D4AF37] hover:shadow-[0_18px_44px_rgba(26,26,26,0.08)]">
                    <div class="mb-6 flex items-start justify-between gap-4">
                        <span class="flex h-10 w-10 items-center justify-center {{ $index % 3 === 0 ? 'bg-[#1A1A1A] text-[#F9F8F6]' : 'bg-[#EBE5DE] text-[#1A1A1A]' }}">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 5a1 1 0 0 1 1-1h5v6H4V5Zm10-1h5a1 1 0 0 1 1 1v3h-6V4ZM4 14h6v6H5a1 1 0 0 1-1-1v-5Zm10-2h6v7a1 1 0 0 1-1 1h-5v-8Z"/>
                            </svg>
                        </span>
                        <span class="editorial-label text-[#D4AF37]">{{ $feature['label'] }}</span>
                    </div>
                    <p class="font-serif text-xl text-[#1A1A1A]">{{ $feature['title'] }}</p>
                    <p class="mt-2 text-sm leading-relaxed text-[#6C6863]">{{ $feature['desc'] }}</p>
                </a>
            @endforeach
        </div>
    </section>

    @if($role !== 'professional' && $latestContents->count())
        <section>
            <div class="mb-5 flex flex-wrap items-end justify-between gap-3 border-t border-[#1A1A1A]/10 pt-8">
                <div>
                    <p class="editorial-label">Terbaru</p>
                    <h2 class="mt-2 text-2xl text-[#1A1A1A] sm:text-3xl">Konten Edukasi</h2>
                </div>
                @if($role === 'user')
                    <a href="{{ route('contents.index') }}" class="editorial-link">Semua Konten</a>
                @endif
            </div>

            <div class="grid gap-4 lg:grid-cols-3">
                @foreach($latestContents as $content)
                    <div class="border border-[#1A1A1A]/10 bg-white p-5 shadow-[0_10px_30px_rgba(26,26,26,0.04)]">
                        <span class="editorial-label text-[#D4AF37]">{{ ucfirst($content->type ?? 'artikel') }}</span>
                        <p class="mt-3 font-serif text-xl leading-snug text-[#1A1A1A]">{{ $content->title }}</p>
                        <p class="mt-2 text-sm leading-relaxed text-[#6C6863]">{{ $content->description ?? \Illuminate\Support\Str::limit(strip_tags($content->body), 120) }}</p>
                        @if($role === 'user')
                            <a href="{{ route('contents.show', $content) }}" class="editorial-link mt-5 inline-flex">Baca</a>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection
