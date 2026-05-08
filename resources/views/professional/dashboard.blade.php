@extends('layouts.app')

@section('title', 'Dashboard Profesional — BerhentiMerokok')

@section('content')
    {{-- Header --}}
    <div class="mb-10 border-b border-[#1A1A1A]/10 pb-8">
        <div class="flex items-start justify-between gap-4">
            <div>
                <span class="editorial-label">Profesional</span>
                <h1 class="mt-2 page-title">{{ auth()->user()->name }}</h1>
                <p class="mt-2 text-sm text-[#6C6863]">{{ ucfirst($professional->type) }} — {{ $professional->specialization }}</p>
            </div>
            @if(!$professional->is_verified)
                <span class="badge-yellow shrink-0">Menunggu Verifikasi</span>
            @else
                <span class="badge-green shrink-0">Terverifikasi</span>
            @endif
        </div>
    </div>

    @if(!$professional->is_verified)
        <div class="mb-8 border border-[#D4AF37] bg-[#D4AF37]/10 px-5 py-4">
            <p class="text-sm font-medium text-[#1A1A1A]">Akun Anda sedang dalam proses verifikasi</p>
            <p class="mt-1 text-sm text-[#6C6863]">Anda belum bisa menerima booking hingga admin menyetujui pendaftaran Anda.</p>
        </div>
    @endif

    {{-- Stats --}}
    <div class="mb-8 grid grid-cols-2 gap-px bg-[#1A1A1A]/10 md:grid-cols-4">
        <div class="flex flex-col items-center justify-center bg-[#1A1A1A] p-6 text-center md:p-8">
            <span class="font-serif text-4xl text-[#F9F8F6]">{{ $totalAppointments }}</span>
            <span class="editorial-label mt-2 text-[#EBE5DE]/50">Total Janji Temu</span>
        </div>
        <div class="flex flex-col items-center justify-center bg-[#F9F8F6] p-6 text-center border border-[#1A1A1A]/10 md:p-8">
            <span class="font-serif text-4xl text-[#1A1A1A]">{{ $todayAppointments }}</span>
            <span class="editorial-label mt-2">Hari Ini</span>
        </div>
        <div class="flex flex-col items-center justify-center bg-[#F9F8F6] p-6 text-center border border-[#1A1A1A]/10 md:p-8">
            <span class="font-serif text-4xl text-[#D4AF37]">{{ $pendingAppointments }}</span>
            <span class="editorial-label mt-2">Menunggu Konfirmasi</span>
        </div>
        <div class="flex flex-col items-center justify-center bg-[#F9F8F6] p-6 text-center border border-[#1A1A1A]/10 md:p-8">
            <span class="font-serif text-xl text-[#1A1A1A]">Rp{{ number_format($monthlyEarnings, 0, ',', '.') }}</span>
            <span class="editorial-label mt-2">Pendapatan Bulan Ini</span>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        {{-- Janji Temu Hari Ini --}}
        <div class="card">
            <div class="flex items-center justify-between">
                <span class="editorial-label">Janji Temu Hari Ini</span>
                <a href="{{ route('professional.appointments') }}" class="text-xs text-[#6C6863] transition-colors duration-500 hover:text-[#D4AF37]">Lihat semua</a>
            </div>
            <div class="mt-4 space-y-0">
                @forelse($todayAppointmentList as $appt)
                    <div class="border-b border-[#1A1A1A]/8 py-4 last:border-0">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="text-sm font-medium text-[#1A1A1A]">{{ $appt->user->name }}</p>
                                <p class="mt-0.5 text-xs text-[#6C6863]">
                                    {{ \Carbon\Carbon::parse($appt->appointment_date)->format('H:i') }} —
                                    @if($appt->mode === 'online')
                                        <span class="text-[#1A1A1A]">Online</span>
                                    @else
                                        <span class="text-[#D4AF37]">Offline</span>
                                    @endif
                                </p>
                            </div>
                            @if($appt->status === 'pending')
                                <form action="{{ route('professional.appointments.confirm', $appt) }}" method="POST">
                                    @csrf
                                    <button class="btn-primary text-[10px] px-3 py-1.5 min-h-8"><span>Konfirmasi</span></button>
                                </form>
                            @else
                                <span class="badge-green">{{ ucfirst($appt->status) }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="py-8 text-center text-sm text-[#6C6863]">Tidak ada janji temu hari ini</p>
                @endforelse
            </div>
        </div>

        {{-- Jadwal Tersedia --}}
        <div class="card">
            <div class="flex items-center justify-between">
                <span class="editorial-label">Jadwal Tersedia</span>
                <a href="{{ route('professional.schedule') }}" class="text-xs text-[#6C6863] transition-colors duration-500 hover:text-[#D4AF37]">Kelola jadwal</a>
            </div>
            <div class="mt-4 space-y-0">
                @forelse($schedules as $schedule)
                    <div class="flex items-center justify-between border-b border-[#1A1A1A]/8 py-3 last:border-0">
                        <div>
                            <p class="text-sm text-[#1A1A1A]">{{ ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][$schedule->day_of_week] }}</p>
                            <p class="editorial-label mt-0.5">{{ substr($schedule->start_time, 0, 5) }} – {{ substr($schedule->end_time, 0, 5) }}</p>
                        </div>
                        @if($schedule->mode === 'online')
                            <span class="badge-blue">Online</span>
                        @elseif($schedule->mode === 'offline')
                            <span class="badge-green">Offline</span>
                        @else
                            <span class="badge-yellow">Hybrid</span>
                        @endif
                    </div>
                @empty
                    <div class="py-8 text-center">
                        <p class="text-sm text-[#6C6863] mb-4">Belum ada jadwal</p>
                        <a href="{{ route('professional.schedule') }}" class="btn-primary"><span>Tambah Jadwal</span></a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
