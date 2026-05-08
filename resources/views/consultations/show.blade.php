@extends('layouts.app')

@section('title', 'Booking — ' . $professional->user->name)

@section('content')
    <div class="mx-auto max-w-2xl">
        <a href="{{ route('consultations.index') }}" class="mb-8 inline-flex items-center gap-2 text-xs text-[#6C6863] transition-colors duration-500 hover:text-[#D4AF37]">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            <span class="editorial-label">Kembali</span>
        </a>

        {{-- Info Profesional --}}
        <div class="card mb-5">
            <div class="flex items-center gap-5">
                <div class="flex h-16 w-16 flex-shrink-0 items-center justify-center border border-[#1A1A1A]/20 bg-[#EBE5DE]">
                    <span class="font-serif text-2xl text-[#1A1A1A]">{{ strtoupper(substr($professional->user->name, 0, 1)) }}</span>
                </div>
                <div>
                    <h1 class="font-serif text-2xl text-[#1A1A1A]">{{ $professional->user->name }}</h1>
                    <p class="mt-0.5 text-sm text-[#6C6863]">{{ ucfirst($professional->type) }} — {{ $professional->specialization }}</p>
                    <p class="mt-2 font-serif text-lg text-[#D4AF37]">Rp{{ number_format($professional->hourly_rate, 0, ',', '.') }}/jam</p>
                </div>
            </div>
        </div>

        {{-- Jadwal Tersedia --}}
        <div class="card mb-5">
            <span class="editorial-label">Jadwal Tersedia</span>
            <div class="mt-4 space-y-0">
                @forelse($schedules as $schedule)
                    <div class="flex items-center justify-between border-b border-[#1A1A1A]/8 py-3 last:border-0">
                        <span class="text-sm text-[#1A1A1A]">
                            {{ ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][$schedule->day_of_week] }}
                        </span>
                        <span class="editorial-label">{{ substr($schedule->start_time, 0, 5) }} – {{ substr($schedule->end_time, 0, 5) }}</span>
                        @if($schedule->mode === 'online')
                            <span class="badge-blue">Online</span>
                        @elseif($schedule->mode === 'offline')
                            <span class="badge-green">Offline</span>
                        @else
                            <span class="badge-yellow">Hybrid</span>
                        @endif
                    </div>
                @empty
                    <p class="py-4 text-sm text-[#6C6863]">Belum ada jadwal tersedia</p>
                @endforelse
            </div>
        </div>

        {{-- Form Booking --}}
        <div class="card">
            <span class="editorial-label">Buat Janji Temu</span>
            <form action="{{ route('consultations.book', $professional) }}" method="POST" class="mt-6 space-y-5">
                @csrf
                <div>
                    <label class="editorial-label mb-2 block">Pilih Jadwal</label>
                    <select name="schedule_id" required class="input-field">
                        <option value="">— Pilih jadwal —</option>
                        @foreach($schedules as $schedule)
                            <option value="{{ $schedule->id }}">
                                {{ ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][$schedule->day_of_week] }}
                                {{ substr($schedule->start_time, 0, 5) }} – {{ substr($schedule->end_time, 0, 5) }}
                                ({{ ucfirst($schedule->mode) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="editorial-label mb-2 block">Tanggal Konsultasi</label>
                    <input type="date" name="appointment_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="input-field">
                </div>
                <div>
                    <label class="editorial-label mb-2 block">Mode</label>
                    <select name="mode" required class="input-field">
                        <option value="online">Online</option>
                        <option value="offline">Offline</option>
                    </select>
                </div>
                <div>
                    <label class="editorial-label mb-2 block">Durasi (jam)</label>
                    <input type="number" name="duration_hours" value="1" min="1" max="8" required class="input-field">
                    <p class="mt-1 text-xs text-[#6C6863]">Total: Rp{{ number_format($professional->hourly_rate, 0, ',', '.') }} × durasi</p>
                </div>
                <div>
                    <label class="editorial-label mb-2 block">Metode Pembayaran</label>
                    <select name="payment_method" required class="input-field">
                        <option value="transfer">Transfer Bank</option>
                        <option value="e-wallet">E-Wallet</option>
                        <option value="credit_card">Kartu Kredit</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary w-full"><span>Konfirmasi Booking</span></button>
            </form>
        </div>
    </div>
@endsection
