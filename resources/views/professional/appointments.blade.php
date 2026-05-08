@extends('layouts.app')

@section('title', 'Janji Temu')

@section('content')
    <div class="mb-10 border-b border-[#1A1A1A]/10 pb-8">
        <span class="editorial-label">Agenda</span>
        <h1 class="mt-2 page-title">Janji Temu</h1>
        <p class="mt-2 text-sm text-[#6C6863]">Daftar semua janji temu konsultasi</p>
    </div>

    <div class="card">
        <div class="space-y-0">
            @forelse($appointments as $appt)
                <div class="flex items-center justify-between gap-4 border-b border-[#1A1A1A]/8 py-5 last:border-0">
                    <div class="flex items-center gap-4 min-w-0">
                        <div class="flex h-10 w-10 flex-shrink-0 items-center justify-center border border-[#1A1A1A]/20 bg-[#EBE5DE]">
                            <span class="text-sm font-medium text-[#1A1A1A]">{{ strtoupper(substr($appt->user->name, 0, 1)) }}</span>
                        </div>
                        <div class="min-w-0">
                            <p class="font-medium text-[#1A1A1A]">{{ $appt->user->name }}</p>
                            <p class="mt-0.5 text-xs text-[#6C6863]">
                                {{ \Carbon\Carbon::parse($appt->appointment_date)->format('d M Y, H:i') }} —
                                <span class="{{ $appt->mode === 'online' ? 'text-[#1A1A1A]' : 'text-[#D4AF37]' }}">
                                    {{ ucfirst($appt->mode) }}
                                </span>
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-shrink-0 items-center gap-3">
                        @if($appt->status === 'pending')
                            <span class="badge-yellow">Pending</span>
                            <form action="{{ route('professional.appointments.confirm', $appt) }}" method="POST">
                                @csrf
                                <button class="btn-primary text-[10px] px-3 min-h-8"><span>Konfirmasi</span></button>
                            </form>
                        @elseif($appt->status === 'confirmed')
                            <span class="badge-green">Dikonfirmasi</span>
                        @else
                            <span class="badge-red">{{ ucfirst($appt->status) }}</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="py-16 text-center text-[#6C6863]">
                    <p class="font-serif text-xl">Belum ada janji temu</p>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-6">{{ $appointments->links() }}</div>
@endsection
