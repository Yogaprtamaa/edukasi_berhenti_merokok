@extends('layouts.app')

@section('title', 'Konsultasi')

@section('content')
    <div class="mb-10 border-b border-[#1A1A1A]/10 pb-8">
        <span class="editorial-label">Layanan</span>
        <h1 class="mt-2 page-title">Konsultasi Profesional</h1>
        <p class="mt-2 text-sm text-[#6C6863]">Temukan dokter atau psikolog yang tepat untuk membantumu</p>
    </div>

    <div class="grid gap-px bg-[#1A1A1A]/10 md:grid-cols-2 lg:grid-cols-3">
        @forelse($professionals as $professional)
            <div class="group flex flex-col bg-[#F9F8F6] p-6 transition-colors duration-700 hover:bg-[#EBE5DE] md:p-8">
                <div class="mb-5 flex items-start gap-4">
                    <div class="flex h-12 w-12 flex-shrink-0 items-center justify-center border border-[#1A1A1A]/20 bg-[#EBE5DE]">
                        <span class="font-serif text-lg text-[#1A1A1A]">{{ strtoupper(substr($professional->user->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <h3 class="font-serif text-lg text-[#1A1A1A]">{{ $professional->user->name }}</h3>
                        <p class="editorial-label mt-0.5">{{ ucfirst($professional->type) }}</p>
                        <p class="mt-0.5 text-xs text-[#6C6863]">{{ $professional->specialization }}</p>
                    </div>
                </div>

                <div class="border-t border-[#1A1A1A]/10 py-4">
                    <span class="editorial-label">Tarif Konsultasi</span>
                    <p class="mt-1 font-serif text-xl text-[#1A1A1A]">Rp{{ number_format($professional->hourly_rate, 0, ',', '.') }}<span class="text-sm text-[#6C6863]">/jam</span></p>
                </div>

                <div class="mt-auto pt-4">
                    <a href="{{ route('consultations.show', $professional) }}" class="btn-primary w-full text-center"><span>Lihat Jadwal & Booking</span></a>
                </div>
            </div>
        @empty
            <div class="col-span-3 py-20 text-center text-[#6C6863]">
                <p class="font-serif text-xl">Belum ada profesional tersedia saat ini</p>
            </div>
        @endforelse
    </div>
@endsection
