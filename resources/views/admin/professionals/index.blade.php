@extends('layouts.app')

@section('title', 'Manajemen Profesional')

@section('content')
    <header class="mb-10 md:mb-12">
        <div class="mb-4 flex items-center gap-3 sm:mb-5 sm:gap-4">
            <span class="h-px w-8 bg-[#1A1A1A] sm:w-12"></span>
            <span class="editorial-label">Verifikasi Profesional</span>
        </div>
        <h1 class="page-title">Manajemen <span class="italic text-[#D4AF37]">Profesional</span></h1>
        <p class="mt-4 max-w-xl text-sm leading-relaxed text-[#6C6863]">Review data dokter dan psikolog sebelum mereka melayani konsultasi pengguna.</p>
    </header>

    <div class="card">
        @forelse($professionals as $prof)
            <div class="grid gap-4 border-b border-[#1A1A1A]/10 py-5 last:border-0 lg:grid-cols-[1fr_auto] lg:items-center">
                <div class="flex min-w-0 items-start gap-3 sm:gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center border border-[#1A1A1A]/20 bg-[#EBE5DE]">
                        <span class="font-serif text-xl text-[#1A1A1A]">{{ substr($prof->user->name, 0, 1) }}</span>
                    </div>
                    <div class="min-w-0">
                        <p class="font-medium text-[#1A1A1A]">{{ $prof->user->name }}</p>
                        <p class="mt-1 text-sm text-[#6C6863]">{{ ucfirst($prof->type) }} - {{ $prof->specialization }}</p>
                        <p class="mt-1 break-words text-xs uppercase tracking-[0.16em] text-[#6C6863] sm:tracking-[0.2em]">Lisensi: {{ $prof->license_number }}</p>
                    </div>
                </div>
                <div class="flex flex-wrap items-center gap-2 lg:justify-end">
                    @if($prof->is_verified)
                        <span class="badge-green">Terverifikasi</span>
                    @else
                        <span class="badge-yellow">Pending</span>
                        <a href="{{ route('admin.professionals.show', $prof) }}" class="badge-blue">Detail</a>
                        <form action="{{ route('admin.professionals.approve', $prof) }}" method="POST">
                            @csrf
                            <button class="badge-green cursor-pointer">Setujui</button>
                        </form>
                        <form action="{{ route('admin.professionals.reject', $prof) }}" method="POST">
                            @csrf
                            <button class="badge-red cursor-pointer">Tolak</button>
                        </form>
                    @endif
                    <a href="{{ route('admin.professionals.edit', $prof) }}" class="badge-yellow cursor-pointer">Edit</a>
                    <form action="{{ route('admin.professionals.destroy', $prof) }}" method="POST" onsubmit="return confirm('Hapus profesional {{ $prof->user->name }}?')">
                        @csrf
                        @method('DELETE')
                        <button class="badge-red cursor-pointer">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="py-12 text-center text-sm text-[#6C6863]">Tidak ada data profesional.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $professionals->links() }}</div>
@endsection
