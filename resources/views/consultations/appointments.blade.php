@extends('layouts.app')

@section('title', 'Janji Temu Saya')

@section('content')
    <header class="mb-8 overflow-hidden border border-[#1A1A1A]/10 bg-white shadow-[0_16px_50px_rgba(26,26,26,0.06)]">
        <div class="p-5 sm:p-7">
            <div class="mb-4 flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center bg-[#1A1A1A] text-[#F9F8F6]">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 4v3m10-3v3M5 8h14v12H5V8Zm3 4h3v3H8v-3Z"/>
                    </svg>
                </span>
                <span class="editorial-label">Konsultasi</span>
            </div>
            <h1 class="font-serif text-4xl leading-none text-[#1A1A1A] sm:text-5xl">Janji Temu Saya</h1>
            <p class="mt-3 max-w-2xl text-sm leading-relaxed text-[#6C6863] sm:text-base">
                Daftar semua janji temu konsultasi yang telah kamu buat.
            </p>
        </div>
    </header>

    <div class="border border-[#1A1A1A]/10 bg-white shadow-[0_16px_50px_rgba(26,26,26,0.05)]">
        @forelse($appointments as $appt)
            <div class="grid gap-4 border-t border-[#1A1A1A]/10 p-5 first:border-t-0 xl:grid-cols-[1fr_auto] xl:items-center">
                <div class="flex min-w-0 items-start gap-4">
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center bg-[#EBE5DE]">
                        <span class="text-sm font-semibold text-[#1A1A1A]">{{ strtoupper(substr($appt->professional->user->name ?? '?', 0, 1)) }}</span>
                    </div>
                    <div class="min-w-0">
                        <div class="mb-2 flex flex-wrap items-center gap-2">
                            @if($appt->status === 'pending')
                                <span class="badge-yellow">Pending</span>
                            @elseif($appt->status === 'confirmed')
                                <span class="badge-blue">Dikonfirmasi</span>
                            @elseif($appt->status === 'completed')
                                <span class="badge-green">Selesai</span>
                            @else
                                <span class="badge-red">Dibatalkan</span>
                            @endif

                            @if($appt->payment?->status === 'success')
                                <span class="badge-green">Lunas</span>
                            @elseif($appt->payment?->status === 'pending')
                                <span class="badge-yellow">Belum Bayar</span>
                            @endif

                            <span class="editorial-label">{{ ucfirst($appt->mode) }}</span>
                        </div>
                        <p class="font-medium text-[#1A1A1A]">{{ $appt->professional->user->name ?? '-' }}</p>
                        <p class="mt-1 text-sm text-[#6C6863]">{{ \Carbon\Carbon::parse($appt->appointment_date)->format('d M Y, H:i') }}</p>
                        <p class="mt-1 text-sm text-[#6C6863]">
                            Durasi {{ $appt->payment?->duration_hours ?? 1 }} jam &mdash;
                            Rp{{ number_format($appt->payment?->amount ?? 0, 0, ',', '.') }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-2 xl:justify-end">
                    @if($appt->payment)
                        <a href="{{ route('payments.show', $appt->payment) }}"
                           class="editorial-label border border-[#1A1A1A]/20 px-3 py-1.5 text-[#1A1A1A] hover:bg-[#1A1A1A] hover:text-[#F9F8F6] transition-colors">
                            Lihat Invoice
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="py-16 text-center text-[#6C6863]">
                <p class="font-serif text-xl">Belum ada janji temu</p>
                <p class="mt-2 text-sm">
                    <a href="{{ route('consultations.index') }}" class="underline hover:text-[#1A1A1A]">Cari konsultan</a>
                </p>
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $appointments->links() }}</div>
@endsection
