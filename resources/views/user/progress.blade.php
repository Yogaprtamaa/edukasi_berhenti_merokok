@extends('layouts.app')

@section('title', 'Progress Tracker')

@section('content')
    <div class="mb-10 border-b border-[#1A1A1A]/10 pb-8">
        <span class="editorial-label">Perjalananmu</span>
        <h1 class="mt-2 page-title">Progress Tracker</h1>
        <p class="mt-2 text-sm text-[#6C6863]">Pantau perjalanan berhenti merokokmu</p>
    </div>

    @if($tracker)
        {{-- Stats --}}
        <div class="mb-8 grid grid-cols-2 gap-px bg-[#1A1A1A]/10 md:grid-cols-4">
            <div class="flex flex-col items-center justify-center bg-[#1A1A1A] p-6 text-center md:p-8">
                <span class="font-serif text-5xl leading-none text-[#F9F8F6]">{{ $tracker->streak_days }}</span>
                <span class="editorial-label mt-2 text-[#EBE5DE]/50">Hari Streak</span>
            </div>
            <div class="flex flex-col items-center justify-center bg-[#F9F8F6] p-6 text-center md:p-8 border border-[#1A1A1A]/10">
                <span class="font-serif text-5xl leading-none text-[#1A1A1A]">{{ $tracker->cigarettes_avoided }}</span>
                <span class="editorial-label mt-2">Rokok Dihindari</span>
            </div>
            <div class="flex flex-col items-center justify-center bg-[#F9F8F6] p-6 text-center md:p-8 border border-[#1A1A1A]/10">
                <span class="font-serif text-2xl leading-none text-[#D4AF37]">Rp{{ number_format($tracker->money_saved, 0, ',', '.') }}</span>
                <span class="editorial-label mt-2">Uang Dihemat</span>
            </div>
            <div class="flex flex-col items-center justify-center bg-[#F9F8F6] p-6 text-center md:p-8 border border-[#1A1A1A]/10">
                <span class="font-serif text-xl leading-none text-[#1A1A1A]">{{ \Carbon\Carbon::parse($tracker->quit_date)->format('d M Y') }}</span>
                <span class="editorial-label mt-2">Tanggal Berhenti</span>
            </div>
        </div>

        {{-- Update form --}}
        <div class="card">
            <span class="editorial-label">Update Data Tracker</span>
            <form action="/progress" method="POST" class="mt-6 grid gap-6 md:grid-cols-2">
                @csrf
                <div>
                    <label class="editorial-label mb-2 block">Tanggal Berhenti Merokok</label>
                    <input type="date" name="quit_date" value="{{ $tracker->quit_date }}" class="input-field">
                </div>
                <div>
                    <label class="editorial-label mb-2 block">Rokok per Hari (sebelumnya)</label>
                    <input type="number" name="cigarettes_per_day" value="{{ $tracker->cigarettes_per_day }}" min="1" class="input-field">
                </div>
                <div>
                    <label class="editorial-label mb-2 block">Harga per Bungkus (Rp)</label>
                    <input type="number" name="price_per_pack" value="25000" min="0" class="input-field">
                </div>
                <div>
                    <label class="editorial-label mb-2 block">Rokok per Bungkus</label>
                    <input type="number" name="cigarettes_per_pack" value="16" min="1" class="input-field">
                </div>
                <div class="md:col-span-2">
                    <button type="submit" class="btn-primary"><span>Simpan Perubahan</span></button>
                </div>
            </form>
        </div>
    @else
        <div class="mx-auto max-w-lg">
            <div class="card border-t-4 border-t-[#D4AF37]">
                <span class="editorial-label">Setup Awal</span>
                <h2 class="mt-4 font-serif text-2xl text-[#1A1A1A]">Mulai Progress Tracker</h2>
                <p class="mt-1 text-sm text-[#6C6863]">Isi data berikut untuk mulai memantau progressmu</p>

                <form action="/progress" method="POST" class="mt-6 space-y-5">
                    @csrf
                    <div>
                        <label class="editorial-label mb-2 block">Tanggal Mulai Berhenti Merokok</label>
                        <input type="date" name="quit_date" value="{{ date('Y-m-d') }}" required class="input-field">
                    </div>
                    <div>
                        <label class="editorial-label mb-2 block">Berapa Rokok per Hari?</label>
                        <input type="number" name="cigarettes_per_day" placeholder="contoh: 10" min="1" required class="input-field">
                    </div>
                    <div>
                        <label class="editorial-label mb-2 block">Harga per Bungkus (Rp)</label>
                        <input type="number" name="price_per_pack" value="25000" min="0" required class="input-field">
                    </div>
                    <div>
                        <label class="editorial-label mb-2 block">Jumlah Rokok per Bungkus</label>
                        <input type="number" name="cigarettes_per_pack" value="16" min="1" required class="input-field">
                    </div>
                    <button type="submit" class="btn-primary w-full"><span>Mulai Tracking</span></button>
                </form>
            </div>
        </div>
    @endif
@endsection
