@extends('layouts.app')
nah
@section('title', 'Edit Profesional')

@section('content')
    <div class="max-w-xl mx-auto">
        <div class="mb-10 border-b border-[#1A1A1A]/10 pb-8">
            <a href="{{ route('admin.professionals') }}" class="editorial-label text-[#6C6863] hover:text-[#1A1A1A]">← Kembali</a>
            <h1 class="mt-4 page-title">Edit <span class="italic text-[#D4AF37]">Profesional</span></h1>
            <p class="mt-2 text-sm text-[#6C6863]">{{ $professional->user->email }}</p>
        </div>

        <div class="card">
            <form action="{{ route('admin.professionals.update', $professional) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label class="block mb-2 editorial-label">Nama</label>
                    <input type="text" name="name" value="{{ old('name', $professional->user->name) }}" required class="input-field">
                    @error('name')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block mb-2 editorial-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', $professional->user->email) }}" required class="input-field">
                    @error('email')<p class="mt-1 text-xs text-red-700">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block mb-2 editorial-label">Jenis Profesional</label>
                    <select name="type" class="input-field">
                        @foreach(['dokter', 'psikolog', 'konselor', 'nutrisionis'] as $t)
                            <option value="{{ $t }}" {{ old('type', $professional->type) === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block mb-2 editorial-label">Spesialisasi</label>
                    <input type="text" name="specialization" value="{{ old('specialization', $professional->specialization) }}" class="input-field" placeholder="Contoh: Dokter Umum">
                </div>

                <div>
                    <label class="block mb-2 editorial-label">Nomor Lisensi</label>
                    <input type="text" name="license_number" value="{{ old('license_number', $professional->license_number) }}" class="input-field">
                </div>

                <div>
                    <label class="block mb-2 editorial-label">Tarif per Jam (Rp)</label>
                    <input type="number" name="hourly_rate" value="{{ old('hourly_rate', $professional->hourly_rate) }}" class="input-field" min="0">
                </div>

                <div class="flex items-center gap-3">
                    <input type="hidden" name="is_verified" value="0">
                    <input type="checkbox" name="is_verified" id="is_verified" value="1" {{ old('is_verified', $professional->is_verified) ? 'checked' : '' }} class="h-4 w-4 border border-[#1A1A1A]">
                    <label for="is_verified" class="cursor-pointer editorial-label">Tandai sebagai terverifikasi</label>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="btn-primary"><span>Simpan Perubahan</span></button>
                    <a href="{{ route('admin.professionals') }}" class="btn-secondary">Batal</a>
                </div>
            </form>
        </div>
    </div>
@endsection
