@extends('layouts.app')

@section('title', 'Dashboard Profesional - BerhentiMerokok')

@section('content')
    <header class="mb-8 overflow-hidden border border-[#1A1A1A]/10 bg-white shadow-[0_16px_50px_rgba(26,26,26,0.06)]">
        <div class="grid gap-6 p-5 sm:p-7 lg:grid-cols-[1fr_auto] lg:items-end">
            <div>
                <div class="mb-4 flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center bg-[#1A1A1A] text-[#F9F8F6]">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14.5a5 5 0 1 0 0-10 5 5 0 0 0 0 10Zm-7 6a7 7 0 0 1 14 0M12 7v5m-2.5-2.5h5"/>
                        </svg>
                    </span>
                    <span class="editorial-label">Profesional</span>
                </div>
                <h1 class="font-serif text-4xl leading-none text-[#1A1A1A] sm:text-5xl">{{ auth()->user()->name }}</h1>
                <p class="mt-3 max-w-2xl text-sm leading-relaxed text-[#6C6863] sm:text-base">
                    {{ ucfirst($professional->type) }} - {{ $professional->specialization }}. Pantau jadwal, pembayaran, dan progres konsultasi.
                </p>
            </div>
            <div class="flex flex-wrap gap-2 lg:justify-end">
                @if(!$professional->is_verified)
                    <span class="badge-yellow">Menunggu Verifikasi</span>
                @else
                    <span class="badge-green">Terverifikasi</span>
                @endif
                <a href="{{ route('professional.schedule') }}" class="badge-blue">Kelola Jadwal</a>
            </div>
        </div>
    </header>

    @if(!$professional->is_verified)
        <div class="mb-8 border border-[#D4AF37] bg-[#D4AF37]/10 px-5 py-4">
            <p class="text-sm font-medium text-[#1A1A1A]">Akun Anda sedang dalam proses verifikasi</p>
            <p class="mt-1 text-sm text-[#6C6863]">Anda belum bisa menerima booking hingga admin menyetujui pendaftaran Anda.</p>
        </div>
    @endif

    <section class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="border border-[#1A1A1A]/10 bg-white p-5 shadow-[0_10px_30px_rgba(26,26,26,0.04)]">
            <p class="editorial-label">Total Appointment</p>
            <p class="mt-5 font-serif text-4xl leading-none text-[#1A1A1A]">{{ $totalAppointments }}</p>
            <p class="mt-3 text-sm text-[#6C6863]">{{ $pendingAppointments }} perlu ditinjau</p>
        </div>
        <div class="border border-[#1A1A1A]/10 bg-white p-5 shadow-[0_10px_30px_rgba(26,26,26,0.04)]">
            <p class="editorial-label">Hari Ini</p>
            <p class="mt-5 font-serif text-4xl leading-none text-[#1A1A1A]">{{ $todayAppointments }}</p>
            <p class="mt-3 text-sm text-[#6C6863]">Agenda konsultasi hari ini</p>
        </div>
        <div class="border border-[#1A1A1A]/10 bg-white p-5 shadow-[0_10px_30px_rgba(26,26,26,0.04)]">
            <p class="editorial-label">Dikonfirmasi</p>
            <p class="mt-5 font-serif text-4xl leading-none text-[#1A1A1A]">{{ $confirmedAppointments }}</p>
            <p class="mt-3 text-sm text-[#6C6863]">{{ $completedAppointments }} selesai</p>
        </div>
        <div class="border border-[#D4AF37]/60 bg-[#D4AF37]/10 p-5 shadow-[0_10px_30px_rgba(212,175,55,0.10)]">
            <p class="editorial-label">Pendapatan Bulan Ini</p>
            <p class="mt-5 font-serif text-3xl leading-none text-[#1A1A1A]">Rp{{ number_format($monthlyEarnings, 0, ',', '.') }}</p>
            <p class="mt-3 text-sm text-[#6C6863]">Dari pembayaran sukses</p>
        </div>
    </section>

    <section class="mb-10 grid gap-5 xl:grid-cols-12">
        <div class="border border-[#1A1A1A]/10 bg-white p-5 shadow-[0_16px_50px_rgba(26,26,26,0.05)] sm:p-6 xl:col-span-7">
            <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
                <div>
                    <p class="editorial-label">Pendapatan</p>
                    <h2 class="mt-2 text-2xl text-[#1A1A1A] sm:text-3xl">Tren 6 Bulan</h2>
                </div>
                <p class="font-serif text-2xl text-[#1A1A1A]">Rp{{ number_format($monthlyEarnings, 0, ',', '.') }}</p>
            </div>
            <div class="relative grid h-64 grid-cols-6 items-end gap-3 border-b border-[#1A1A1A]/10 pb-4 sm:gap-5">
                <div class="pointer-events-none absolute inset-x-0 top-1/3 h-px bg-[#1A1A1A]/5"></div>
                <div class="pointer-events-none absolute inset-x-0 top-2/3 h-px bg-[#1A1A1A]/5"></div>
                @foreach($earningsChart as $point)
                    @php
                        $height = max(($point['amount'] / $maxEarnings) * 100, $point['amount'] > 0 ? 8 : 2);
                    @endphp
                    <div class="flex h-full min-w-0 flex-col justify-end gap-3">
                        <div class="flex flex-1 items-end">
                            <div class="relative w-full overflow-hidden border border-[#1A1A1A]/10 bg-[#F9F8F6]">
                                <div class="absolute bottom-0 w-full bg-[#D4AF37]" style="height: {{ $height }}%"></div>
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="truncate text-xs font-medium text-[#1A1A1A]">{{ $point['label'] }}</p>
                            <p class="mt-1 truncate text-[11px] text-[#6C6863]">Rp{{ number_format($point['amount'], 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid gap-5 xl:col-span-5">
            <div class="border border-[#1A1A1A]/10 bg-white p-5 shadow-[0_16px_50px_rgba(26,26,26,0.05)] sm:p-6">
                <p class="editorial-label">Status Payment</p>
                <div class="mt-5 space-y-4">
                    @foreach($paymentStatusChart as $status)
                        @php
                            $maxPaymentStatus = max($paymentStatusChart->max('count'), 1);
                            $width = ($status['count'] / $maxPaymentStatus) * 100;
                        @endphp
                        <div>
                            <div class="mb-2 flex justify-between text-sm">
                                <span class="font-medium text-[#1A1A1A]">{{ $status['label'] }}</span>
                                <span class="text-[#6C6863]">{{ $status['count'] }}</span>
                            </div>
                            <div class="h-3 bg-[#EBE5DE]">
                                <div class="h-3 {{ $status['label'] === 'Success' ? 'bg-[#D4AF37]' : ($status['label'] === 'Pending' ? 'bg-[#1A1A1A]' : 'bg-red-700/60') }}" style="width: {{ $width }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="border border-[#1A1A1A]/10 bg-white p-5 shadow-[0_16px_50px_rgba(26,26,26,0.05)] sm:p-6">
                <p class="editorial-label">Status Appointment</p>
                <div class="mt-5 grid grid-cols-2 gap-3">
                    @foreach($appointmentStatusChart as $status)
                        <div class="border border-[#1A1A1A]/10 bg-[#F9F8F6] p-3">
                            <p class="truncate text-sm text-[#6C6863]">{{ $status['label'] }}</p>
                            <p class="mt-2 font-serif text-3xl text-[#1A1A1A]">{{ $status['count'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="grid gap-5 lg:grid-cols-2">
        <div class="border border-[#1A1A1A]/10 bg-white p-5 shadow-[0_16px_50px_rgba(26,26,26,0.05)] sm:p-6">
            <div class="mb-5 flex items-start justify-between gap-4">
                <div>
                    <p class="editorial-label">Agenda</p>
                    <h2 class="mt-2 text-2xl text-[#1A1A1A]">Janji Temu Hari Ini</h2>
                </div>
                <a href="{{ route('professional.appointments') }}" class="editorial-link">Lihat Semua</a>
            </div>
            @forelse($todayAppointmentList as $appt)
                <div class="grid gap-3 border-t border-[#1A1A1A]/10 py-4 first:border-t-0 sm:grid-cols-[1fr_auto] sm:items-center">
                    <div class="min-w-0">
                        <p class="truncate font-medium text-[#1A1A1A]">{{ $appt->user->name }}</p>
                        <p class="mt-1 text-sm text-[#6C6863]">{{ \Carbon\Carbon::parse($appt->appointment_date)->format('H:i') }} - {{ ucfirst($appt->mode) }}</p>
                        <p class="mt-1 text-xs uppercase tracking-[0.16em] text-[#6C6863]">Payment: {{ ucfirst($appt->payment?->status ?? 'none') }}</p>
                    </div>
                    @if($appt->status === 'pending' && $appt->payment?->status === 'success')
                        <form action="{{ route('professional.appointments.confirm', $appt) }}" method="POST">
                            @csrf
                            <button class="badge-yellow cursor-pointer">Konfirmasi</button>
                        </form>
                    @elseif($appt->status === 'confirmed')
                        <form action="{{ route('professional.appointments.complete', $appt) }}" method="POST">
                            @csrf
                            <button class="badge-green cursor-pointer">Selesai</button>
                        </form>
                    @else
                        <span class="{{ $appt->status === 'pending' ? 'badge-yellow' : ($appt->status === 'completed' ? 'badge-green' : 'badge-red') }}">{{ ucfirst($appt->status) }}</span>
                    @endif
                </div>
            @empty
                <p class="py-10 text-center text-sm text-[#6C6863]">Tidak ada janji temu hari ini.</p>
            @endforelse
        </div>

        <div class="border border-[#1A1A1A]/10 bg-white p-5 shadow-[0_16px_50px_rgba(26,26,26,0.05)] sm:p-6">
            <div class="mb-5 flex items-start justify-between gap-4">
                <div>
                    <p class="editorial-label">Ketersediaan</p>
                    <h2 class="mt-2 text-2xl text-[#1A1A1A]">Jadwal Aktif</h2>
                </div>
                <a href="{{ route('professional.schedule') }}" class="editorial-link">Kelola</a>
            </div>
            @forelse($schedules as $schedule)
                <div class="flex items-center justify-between gap-4 border-t border-[#1A1A1A]/10 py-4 first:border-t-0">
                    <div>
                        <p class="font-medium text-[#1A1A1A]">{{ ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][$schedule->day_of_week] }}</p>
                        <p class="mt-1 text-sm text-[#6C6863]">{{ substr($schedule->start_time, 0, 5) }} - {{ substr($schedule->end_time, 0, 5) }}</p>
                    </div>
                    <span class="{{ $schedule->mode === 'hybrid' ? 'badge-yellow' : ($schedule->mode === 'online' ? 'badge-blue' : 'badge-green') }}">{{ ucfirst($schedule->mode) }}</span>
                </div>
            @empty
                <div class="py-10 text-center">
                    <p class="mb-4 text-sm text-[#6C6863]">Belum ada jadwal.</p>
                    <a href="{{ route('professional.schedule') }}" class="btn-primary"><span>Tambah Jadwal</span></a>
                </div>
            @endforelse
        </div>
    </section>
@endsection
