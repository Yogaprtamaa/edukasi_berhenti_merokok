@extends('layouts.app')

@section('title', 'Manajemen Appointment')

@section('content')
    <header class="mb-10 md:mb-12">
        <div class="mb-4 flex items-center gap-3 sm:mb-5 sm:gap-4">
            <span class="h-px w-8 bg-[#1A1A1A] sm:w-12"></span>
            <span class="editorial-label">Konsultasi</span>
        </div>
        <h1 class="page-title">Manajemen <span class="italic text-[#D4AF37]">Appointment</span></h1>
        <p class="mt-4 max-w-xl text-sm leading-relaxed text-[#6C6863]">Lihat seluruh janji temu antara pengguna dan profesional.</p>
    </header>

    <section class="mb-8 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
        <div class="card"><p class="editorial-label">Total</p><p class="mt-4 font-serif text-4xl text-[#1A1A1A]">{{ $stats['total'] }}</p></div>
        <div class="card"><p class="editorial-label">Pending</p><p class="mt-4 font-serif text-4xl text-[#1A1A1A]">{{ $stats['pending'] }}</p></div>
        <div class="card"><p class="editorial-label">Dikonfirmasi</p><p class="mt-4 font-serif text-4xl text-[#1A1A1A]">{{ $stats['confirmed'] }}</p></div>
        <div class="card"><p class="editorial-label">Selesai</p><p class="mt-4 font-serif text-4xl text-[#1A1A1A]">{{ $stats['completed'] }}</p></div>
    </section>

    <div class="mb-5 flex flex-wrap gap-2">
        @foreach(['' => 'Semua', 'pending' => 'Pending', 'confirmed' => 'Dikonfirmasi', 'completed' => 'Selesai', 'cancelled' => 'Batal'] as $value => $label)
            <a href="{{ $value === '' ? route('admin.appointments') : route('admin.appointments', ['status' => $value]) }}" class="{{ $status === $value || (!$status && $value === '') ? 'badge-yellow' : 'badge-blue' }}">{{ $label }}</a>
        @endforeach
    </div>

    <div class="card">
        @forelse($appointments as $appointment)
            <div class="grid gap-4 border-b border-[#1A1A1A]/10 py-5 last:border-0 xl:grid-cols-[1fr_auto] xl:items-center">
                <div class="min-w-0">
                    <div class="mb-3 flex flex-wrap items-center gap-2">
                        <span class="{{ $appointment->status === 'confirmed' || $appointment->status === 'completed' ? 'badge-green' : ($appointment->status === 'cancelled' ? 'badge-red' : 'badge-yellow') }}">{{ ucfirst($appointment->status) }}</span>
                        <span class="editorial-label">{{ ucfirst($appointment->mode) }}</span>
                        <span class="editorial-label">{{ $appointment->payment?->status ? 'Payment ' . ucfirst($appointment->payment->status) : 'Tanpa Payment' }}</span>
                    </div>
                    <p class="font-medium text-[#1A1A1A]">{{ $appointment->user?->name ?? 'Pengguna dihapus' }} dengan {{ $appointment->professional?->user?->name ?? 'Profesional dihapus' }}</p>
                    <p class="mt-1 text-sm text-[#6C6863]">{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y, H:i') }}</p>
                </div>
                <form action="{{ route('admin.appointments.status', $appointment) }}" method="POST" class="flex flex-wrap items-center gap-2 xl:justify-end">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="border border-[#1A1A1A]/20 bg-[#F9F8F6] px-3 py-2 text-xs uppercase tracking-[0.14em] text-[#1A1A1A]">
                        @foreach(['pending', 'confirmed', 'completed', 'cancelled'] as $option)
                            <option value="{{ $option }}" @selected($appointment->status === $option)>{{ ucfirst($option) }}</option>
                        @endforeach
                    </select>
                    <button class="badge-yellow cursor-pointer">Update</button>
                </form>
            </div>
        @empty
            <div class="py-12 text-center text-sm text-[#6C6863]">Tidak ada appointment.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $appointments->links() }}</div>
@endsection
