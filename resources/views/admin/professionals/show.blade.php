@extends('layouts.app')

@section('title', 'Detail Profesional')

@section('content')
    <header class="mb-10 md:mb-12">
        <a href="{{ route('admin.professionals') }}" class="editorial-link">Kembali</a>
        <div class="mb-4 mt-6 flex items-center gap-3 sm:mb-5 sm:gap-4">
            <span class="h-px w-8 bg-[#1A1A1A] sm:w-12"></span>
            <span class="editorial-label">Detail Verifikasi</span>
        </div>
        <h1 class="page-title break-words">{{ $professional->user->name }}</h1>
        <p class="mt-4 text-sm leading-relaxed text-[#6C6863]">{{ ucfirst($professional->type) }} - {{ $professional->specialization }}</p>
    </header>

    <section class="grid gap-6 lg:grid-cols-12 lg:gap-8">
        <div class="card lg:col-span-7">
            <dl class="grid gap-6 md:grid-cols-2">
                <div>
                    <dt class="editorial-label">Email</dt>
                    <dd class="mt-2 break-words text-[#1A1A1A]">{{ $professional->user->email }}</dd>
                </div>
                <div>
                    <dt class="editorial-label">Status</dt>
                    <dd class="mt-2">
                        @if($professional->is_verified)
                            <span class="badge-green">Terverifikasi</span>
                        @else
                            <span class="badge-yellow">Pending</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="editorial-label">Nomor Lisensi</dt>
                    <dd class="mt-2 text-[#1A1A1A]">{{ $professional->license_number }}</dd>
                </div>
                <div>
                    <dt class="editorial-label">Tarif Konsultasi</dt>
                    <dd class="mt-2 text-[#1A1A1A]">Rp {{ number_format($professional->hourly_rate ?? 0, 0, ',', '.') }}</dd>
                </div>
                <div class="md:col-span-2">
                    <dt class="editorial-label">Dokumen</dt>
                    <dd class="mt-2">
                        @if($professional->document_url)
                            <a href="{{ $professional->document_url }}" target="_blank" class="editorial-link">Buka Dokumen</a>
                        @else
                            <span class="text-sm text-[#6C6863]">Tidak ada dokumen terlampir.</span>
                        @endif
                    </dd>
                </div>
            </dl>
        </div>

        <aside class="card border-t-4 border-t-[#D4AF37] lg:col-span-4 lg:col-start-9">
            <p class="editorial-label">Aksi Admin</p>
            <h2 class="mt-3 text-2xl text-[#1A1A1A] sm:text-3xl">Keputusan Verifikasi</h2>
            <div class="mt-6 flex flex-col gap-3 sm:mt-8">
                <form action="{{ route('admin.professionals.approve', $professional) }}" method="POST">
                    @csrf
                    <button class="btn-primary w-full"><span>Setujui</span></button>
                </form>
                <form action="{{ route('admin.professionals.reject', $professional) }}" method="POST">
                    @csrf
                    <button class="btn-danger w-full">Tolak</button>
                </form>
            </div>
        </aside>
    </section>
@endsection
