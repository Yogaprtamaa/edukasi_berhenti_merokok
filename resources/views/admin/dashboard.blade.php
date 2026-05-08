@extends('layouts.app')

@section('title', 'Admin Dashboard - BerhentiMerokok')

@section('content')
    <header class="mb-10 grid gap-6 md:mb-14 lg:grid-cols-12">
        <div class="lg:col-span-7">
            <div class="mb-4 flex items-center gap-3 sm:mb-5 sm:gap-4">
                <span class="h-px w-8 bg-[#1A1A1A] sm:w-12"></span>
                <span class="editorial-label">Admin Console</span>
            </div>
            <h1 class="page-title md:text-7xl">
                Kurasi <span class="italic text-[#D4AF37]">Platform</span><br>Berhenti Merokok
            </h1>
        </div>
        <p class="max-w-xl self-end text-sm leading-relaxed text-[#6C6863] sm:text-base lg:col-span-4 lg:col-start-9">
            Pantau pengguna, profesional, konten edukasi, dan aktivitas konsultasi dari satu ruang kerja administratif.
        </p>
    </header>

    <section class="mb-10 grid gap-3 sm:grid-cols-2 sm:gap-4 md:mb-14 xl:grid-cols-4">
        <div class="card">
            <p class="editorial-label">Pengguna</p>
            <p class="mt-4 font-serif text-4xl leading-none text-[#1A1A1A] sm:mt-5 sm:text-5xl">{{ $totalUsers }}</p>
            <p class="mt-3 text-sm text-[#6C6863]">+{{ $newUsersThisMonth }} bulan ini</p>
        </div>
        <div class="card border-t-4 border-t-[#D4AF37]">
            <p class="editorial-label">Profesional Aktif</p>
            <p class="mt-4 font-serif text-4xl leading-none text-[#1A1A1A] sm:mt-5 sm:text-5xl">{{ $totalProfessionals }}</p>
            <p class="mt-3 text-sm text-[#6C6863]">{{ $pendingProfessionals }} menunggu review</p>
        </div>
        <div class="card">
            <p class="editorial-label">Konten Pending</p>
            <p class="mt-4 font-serif text-4xl leading-none text-[#1A1A1A] sm:mt-5 sm:text-5xl">{{ $pendingContents }}</p>
            <p class="mt-3 text-sm text-[#6C6863]">Menunggu kurasi</p>
        </div>
        <div class="card">
            <p class="editorial-label">Konsultasi</p>
            <p class="mt-4 font-serif text-4xl leading-none text-[#1A1A1A] sm:mt-5 sm:text-5xl">{{ $totalAppointments }}</p>
            <p class="mt-3 text-sm text-[#6C6863]">Semua waktu</p>
        </div>
    </section>

    <section class="grid gap-6 lg:grid-cols-2 lg:gap-8">
        <div class="card">
            <div class="mb-5 grid gap-3 sm:mb-6 sm:flex sm:items-start sm:justify-between">
                <div>
                    <p class="editorial-label">Verifikasi</p>
                    <h2 class="mt-2 text-2xl text-[#1A1A1A] sm:text-3xl">Profesional Pending</h2>
                </div>
                <a href="{{ route('admin.professionals') }}" class="editorial-link">Lihat Semua</a>
            </div>
            @forelse($pendingProfessionalList as $prof)
                <div class="grid gap-3 border-b border-[#1A1A1A]/10 py-4 last:border-0 sm:grid-cols-[1fr_auto] sm:items-center">
                    <div>
                        <p class="font-medium text-[#1A1A1A]">{{ $prof->user->name }}</p>
                        <p class="mt-1 text-sm text-[#6C6863]">{{ ucfirst($prof->type) }} - {{ $prof->specialization }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2 sm:justify-end">
                        <form action="{{ route('admin.professionals.approve', $prof) }}" method="POST">
                            @csrf
                            <button class="badge-green cursor-pointer transition-colors duration-500 hover:border-[#D4AF37]">Setujui</button>
                        </form>
                        <a href="{{ route('admin.professionals.show', $prof) }}" class="badge-blue">Detail</a>
                    </div>
                </div>
            @empty
                <p class="py-10 text-center text-sm text-[#6C6863]">Tidak ada pengajuan pending.</p>
            @endforelse
        </div>

        <div class="card">
            <div class="mb-5 grid gap-3 sm:mb-6 sm:flex sm:items-start sm:justify-between">
                <div>
                    <p class="editorial-label">Moderasi</p>
                    <h2 class="mt-2 text-2xl text-[#1A1A1A] sm:text-3xl">Konten Menunggu</h2>
                </div>
                <a href="{{ route('admin.contents') }}" class="editorial-link">Lihat Semua</a>
            </div>
            @forelse($pendingContentList as $content)
                <div class="grid gap-3 border-b border-[#1A1A1A]/10 py-4 last:border-0 sm:grid-cols-[1fr_auto] sm:items-center">
                    <div class="min-w-0">
                        <p class="truncate font-medium text-[#1A1A1A]">{{ $content->title }}</p>
                        <p class="mt-1 text-sm text-[#6C6863]">oleh {{ $content->uploader?->name ?? 'Admin' }} - {{ ucfirst($content->type) }}</p>
                    </div>
                    <div class="flex flex-wrap gap-2 sm:justify-end">
                        <form action="{{ route('admin.contents.approve', $content) }}" method="POST">
                            @csrf
                            <button class="badge-green cursor-pointer transition-colors duration-500 hover:border-[#D4AF37]">Setujui</button>
                        </form>
                        <form action="{{ route('admin.contents.reject', $content) }}" method="POST">
                            @csrf
                            <button class="badge-red cursor-pointer transition-colors duration-500 hover:bg-red-700/10">Tolak</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="py-10 text-center text-sm text-[#6C6863]">Tidak ada konten pending.</p>
            @endforelse
        </div>
    </section>
@endsection
