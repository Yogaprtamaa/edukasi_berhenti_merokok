@extends('layouts.app')

@section('title', 'Setup Akun Profesional')

@section('content')
    <div class="mx-auto max-w-lg">
        <div class="mb-10 border-b border-[#1A1A1A]/10 pb-8">
            <span class="editorial-label">Onboarding</span>
            <h1 class="mt-2 page-title">Lengkapi Data Profesional</h1>
            <p class="mt-2 text-sm text-[#6C6863]">Data ini akan diverifikasi oleh admin sebelum kamu bisa menerima booking.</p>
        </div>

        <div class="card">
            <form action="/professional/setup" method="POST" class="space-y-6">
                @csrf

                @if($errors->any())
                    <div class="border border-red-700/40 bg-red-700/5 px-4 py-3 text-sm text-red-800">
                        <ul class="space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div>
                    <label class="editorial-label mb-2 block">Jenis Profesional</label>
                    <select name="type" required class="input-field">
                        <option value="psikolog" {{ old('type') === 'psikolog' ? 'selected' : '' }}>Psikolog</option>
                        <option value="dokter" {{ old('type') === 'dokter' ? 'selected' : '' }}>Dokter</option>
                    </select>
                </div>
                <div>
                    <label class="editorial-label mb-2 block">Spesialisasi</label>
                    <input type="text" name="specialization" value="{{ old('specialization') }}" required class="input-field" placeholder="contoh: Psikolog Klinis, Dokter Umum">
                </div>
                <div>
                    <label class="editorial-label mb-2 block">Nomor Lisensi / STR</label>
                    <input type="text" name="license_number" value="{{ old('license_number') }}" required class="input-field" placeholder="Nomor lisensi profesional">
                </div>
                <div>
                    <label class="editorial-label mb-2 block">Tarif per Jam (Rp)</label>
                    <input type="number" name="hourly_rate" value="{{ old('hourly_rate') }}" required min="0" class="input-field" placeholder="contoh: 150000">
                </div>
                <button type="submit" class="btn-primary w-full"><span>Kirim untuk Verifikasi</span></button>
            </form>
        </div>
    </div>
@endsection
