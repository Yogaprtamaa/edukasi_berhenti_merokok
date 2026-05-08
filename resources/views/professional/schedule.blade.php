@extends('layouts.app')

@section('title', 'Kelola Jadwal')

@section('content')
    <div class="mb-10 border-b border-[#1A1A1A]/10 pb-8">
        <span class="editorial-label">Manajemen</span>
        <h1 class="mt-2 page-title">Jadwal Konsultasi</h1>
        <p class="mt-2 text-sm text-[#6C6863]">Atur hari dan jam ketersediaanmu</p>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        {{-- Jadwal Aktif --}}
        <div class="card">
            <span class="editorial-label">Jadwal Aktif</span>
            <div class="mt-4 space-y-0">
                @forelse($schedules as $schedule)
                    <div class="flex items-center justify-between border-b border-[#1A1A1A]/8 py-3 last:border-0">
                        <div>
                            <p class="text-sm font-medium text-[#1A1A1A]">{{ ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'][$schedule->day_of_week] }}</p>
                            <p class="editorial-label mt-0.5">{{ substr($schedule->start_time, 0, 5) }} – {{ substr($schedule->end_time, 0, 5) }}</p>
                        </div>
                        <div class="flex items-center gap-3">
                            @if($schedule->mode === 'online')
                                <span class="badge-blue">Online</span>
                            @elseif($schedule->mode === 'offline')
                                <span class="badge-green">Offline</span>
                            @else
                                <span class="badge-yellow">Hybrid</span>
                            @endif
                            <form action="{{ route('professional.schedule.destroy', $schedule) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="text-[#6C6863] transition-colors duration-300 hover:text-red-700">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="py-8 text-center text-sm text-[#6C6863]">Belum ada jadwal</p>
                @endforelse
            </div>
        </div>

        {{-- Tambah Jadwal --}}
        <div class="card border-t-4 border-t-[#D4AF37]">
            <span class="editorial-label">Tambah Jadwal</span>
            <form action="{{ route('professional.schedule.store') }}" method="POST" class="mt-5 space-y-5">
                @csrf

                @if($errors->any())
                    <div class="border border-red-700/40 bg-red-700/5 px-4 py-3 text-sm text-red-800">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div>
                    <label class="editorial-label mb-2 block">Hari</label>
                    <select name="day_of_week" required class="input-field">
                        @foreach(['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $i => $day)
                            <option value="{{ $i }}">{{ $day }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="editorial-label mb-2 block">Mulai</label>
                        <input type="time" name="start_time" required class="input-field">
                    </div>
                    <div>
                        <label class="editorial-label mb-2 block">Selesai</label>
                        <input type="time" name="end_time" required class="input-field">
                    </div>
                </div>
                <div>
                    <label class="editorial-label mb-2 block">Mode</label>
                    <select name="mode" required class="input-field">
                        <option value="online">Online</option>
                        <option value="offline">Offline</option>
                        <option value="hybrid">Hybrid</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary w-full"><span>Tambah Jadwal</span></button>
            </form>
        </div>
    </div>
@endsection
